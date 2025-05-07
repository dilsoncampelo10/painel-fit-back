<?php

namespace App\Http\Controllers;

use App\Http\Requests\NutritionalPlan\CreateNutritionalPlanRequest;
use App\Http\Requests\NutritionalPlan\UpdateNutritionalPlanRequest;
use App\Services\NutritionalPlanService;
use Illuminate\Http\Request;

class NutritionalPlanController extends Controller
{
    public function __construct(private NutritionalPlanService $service) {}

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function store(CreateNutritionalPlanRequest $request)
    {
        $plan = $this->service->create($request->validated());
        return response()->json($plan, 201);
    }

    public function show($id)
    {
        $plan = $this->service->findById($id);
        return response()->json($plan);
    }

    public function update(UpdateNutritionalPlanRequest $request, int $id)
    {
        $plan = $this->service->update($request->validated(), $id);
        return response()->json($plan);
    }


    public function destroy($id)
    {
        $this->service->destroy($id);
        return response()->noContent();
    }
}
