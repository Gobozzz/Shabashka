<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalDataRequest extends FormRequest
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
        $user = auth()->user();
        return [
            'name' => 'required|string|max:255',
            'email' => "required|string|max:255|unique:users,email," . $user->getKey(),
            'phone' => "nullable|phone:AUTO|unique:users,phone," . $user->getKey(),
            'city_id' => "nullable|numeric|exists:cities,id",
            'image' => "nullable|image|max:3072",
        ];
    }
}
