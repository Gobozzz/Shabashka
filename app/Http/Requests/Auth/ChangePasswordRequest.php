<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => "required|string",
            'new_password' => "required|string|min:5|confirmed",
        ];
    }

    protected function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if ($validator->errors()->isEmpty()) {
                $user = auth()->user();
                if (!Hash::check($this->old_password, $user->password)) {
                    $validator->errors()->add('old_password', __('errors.password_incorrect'));
                }
            }
        });
    }

}
