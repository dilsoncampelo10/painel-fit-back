<?php

namespace App\Http\Controllers;

use App\Http\Requests\Training\CreateTrainingRequest;
use App\Http\Requests\Training\UpdateTrainingRequest;
use App\Services\TrainingService;

class TrainingController extends Controller
{
    public function __construct(protected TrainingService $service) {}

    public function index()
    {
        return $this->service->getAll();
    }

    public function store(CreateTrainingRequest $request)
    {
        $data = $request->validated();

        $training = $this->service->save($data);

        return response()->json($training, 201);
    }

    public function show(int $id)
    {
        $training = $this->service->findById($id);

        return response()->json($training, 200);
    }
    public function update(UpdateTrainingRequest $request, int $id)
    {
        return $this->service->update($request->all(), $id);
    }
    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->noContent();
    }
}
