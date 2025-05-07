<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionalPlan extends Model
{
    protected $fillable = ['user_id', 'date'];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
