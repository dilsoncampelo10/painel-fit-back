<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealItem extends Model
{
    protected $fillable = ['meal_id', 'name', 'description'];

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }
}
