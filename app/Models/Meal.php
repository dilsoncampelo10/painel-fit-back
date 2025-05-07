<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meal extends Model
{
    protected $fillable = ['nutritional_plan_id', 'name'];

    public function items(): HasMany
    {
        return $this->hasMany(MealItem::class);
    }

    public function nutrional_plan(): BelongsTo
    {
        return $this->belongsTo(NutritionalPlan::class);
    }
}
