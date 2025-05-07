<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;

class TrainingService
{
    public function getAll()
    {
        $user = Auth::user();

        return $user->trainings;
    }

    public function save(array $data)
    {
        $data['user_id'] = Auth::user()->id;
        return Training::create($data);
    }

    public function findById(int $id)
    {
        $training = Training::find($id);

        if (!$training) {
            throw new NotFoundException('Training not found');
        }

        return $training;
    }

    public function update(array $data, int $id)
    {
        $training = $this->findById($id);

        return $training->update($data);
    }

    public function delete(int $id)
    {
        $training = $this->findById($id);
        $training->delete();
    }
}
