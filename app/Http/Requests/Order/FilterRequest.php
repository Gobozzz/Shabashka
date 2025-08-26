<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class FilterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'city_id' => "nullable|numeric|exists:cities,id",
            'categories_selected' => 'nullable|array',
            'categories_selected.*' => 'numeric|exists:work_categories,id',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (!$this->city_id) {
                    $this->merge([
                        'city_id' => auth()->user()?->city?->id,
                    ]);
                }
            }
        ];
    }

}
