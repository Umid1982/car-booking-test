<?php

namespace App\Http\Requests\Car;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AvailableCarsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'car_model_id' => ['nullable', 'integer', 'exists:car_models,id'],
            'comfort_category_id' => ['nullable', 'integer', 'exists:comfort_categories,id'],
        ];
    }


    /**
     * Преобразование запроса в нормальный массив фильтров.
     */
    public function filters(): array
    {
        return [
            'user_id' => $this->user()->id,
            'start_at' => Carbon::parse($this->validated('start_at')),
            'end_at' => Carbon::parse($this->validated('end_at')),
            'car_model_id' => $this->validated('car_model_id'),
            'comfort_category_id' => $this->validated('comfort_category_id'),
        ];
    }
}
