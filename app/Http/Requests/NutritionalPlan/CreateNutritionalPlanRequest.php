<?php

namespace App\Http\Requests\NutritionalPlan;

use Illuminate\Foundation\Http\FormRequest;

class CreateNutritionalPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'meals' => ['required', 'array'],
            'meals.*.title' => ['required', 'string'],
            'meals.*.items' => ['required', 'array'],
            'meals.*.items.*.title' => ['required', 'string'],
            'meals.*.items.*.description' => ['nullable', 'string'],
        ];
    }
}
