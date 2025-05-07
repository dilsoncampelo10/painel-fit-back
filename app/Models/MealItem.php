<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealItem extends Model
{

    use SoftDeletes;
    
    protected $fillable = ['meal_id', 'name', 'description'];

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }
}
