<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\NutritionalPlan;
use Illuminate\Support\Facades\Auth;

class NutritionalPlanService
{
    public function create(array $data)
    {
        $plan = NutritionalPlan::create([
            'user_id' => Auth::user()->id,
        ]);

        foreach ($data['meals'] as $mealData) {

            $meal = $plan->meals()->create([
                'title' => $mealData['title'],
            ]);

            foreach ($mealData['items'] as $itemData) {
                $meal->items()->create($itemData);
            }
        }

        return $plan->load('meals.items');
    }

    public function getAll()
    {
        return NutritionalPlan::with('meals.items')
            ->where('user_id', Auth::user()->id)
            ->get();
    }

    public function findById(int $id)
    {
        $plan = NutritionalPlan::with('meals.items')
            ->where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$plan) {
            throw new NotFoundException('Nutritional plan not found');
        }
        return $plan;
    }

    public function update(array $data, int $id)
    {
        $plan = NutritionalPlan::findOrFail($id);

        $existingMeals = $plan->meals()->withTrashed()->get()->keyBy('id');
        $mealsToKeep = [];

        foreach ($data['meals'] as $mealData) {
            if (!empty($mealData['id']) && $existingMeals->has($mealData['id'])) {
                $meal = $existingMeals[$mealData['id']];
                $meal->update(['title' => $mealData['title']]);

                if ($meal->trashed()) $meal->restore();
            } else {
                $meal = $plan->meals()->create([
                    'title' => $mealData['title']
                ]);
            }

            $mealsToKeep[] = $meal->id;

            $existingItems = $meal->items()->withTrashed()->get()->keyBy('id');
            $itemsToKeep = [];

            foreach ($mealData['items'] as $itemData) {
                if (!empty($itemData['id']) && $existingItems->has($itemData['id'])) {
                    $item = $existingItems[$itemData['id']];
                    $item->update([
                        'title' => $itemData['title'],
                        'description' => $itemData['description']
                    ]);

                    if ($item->trashed()) $item->restore();
                } else {
                    $item = $meal->items()->create([
                        'title' => $itemData['title'],
                        'description' => $itemData['description']
                    ]);
                }

                $itemsToKeep[] = $item->id;
            }

            $meal->items()->whereNotIn('id', $itemsToKeep)->delete();
        }
      
        $plan->meals()->whereNotIn('id', $mealsToKeep)->delete();

        return $plan->load('meals.items');
    }



    public function destroy(int $id)
    {
        $plan = $this->findById($id);
        $plan->delete();
    }
}
