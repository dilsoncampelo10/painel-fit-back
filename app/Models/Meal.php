<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meal extends Model
{
    use SoftDeletes;

    protected $fillable = ['nutritional_plan_id', 'title'];

    public function items(): HasMany
    {
        return $this->hasMany(MealItem::class);
    }

    public function nutrional_plan(): BelongsTo
    {
        return $this->belongsTo(NutritionalPlan::class);
    }
}
