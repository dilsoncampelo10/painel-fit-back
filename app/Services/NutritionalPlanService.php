<?php

namespace App\Services;

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
        return NutritionalPlan::with('meals.items')
            ->where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();
    }

    public function destroy(int $id)
    {
        $plan = $this->findById($id);
        $plan->delete();
    }
}
