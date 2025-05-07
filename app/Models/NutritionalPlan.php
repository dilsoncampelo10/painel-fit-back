<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NutritionalPlan extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['user_id'];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
