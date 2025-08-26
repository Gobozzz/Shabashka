<?php

namespace App\Http\Requests\Auth;

use App\DTO\UserCreateDTO;
use App\Enums\RolesUser;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => "required|string|max:255|min:2",
            'email' => "required|email|max:255|unique:users,email",
            'phone' => "required|phone:AUTO|unique:users,phone",
            'password' => "required|string|min:5|confirmed",
            'role' => "required|string",
        ];
    }

    protected function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if ($validator->errors()->isEmpty()) {
                if (!in_array((int) $this->role, RolesUser::publicRoles())) {
                    $validator->errors()->add('role', __('errors.role_blocked'));
                }
            }
        });
    }

}
