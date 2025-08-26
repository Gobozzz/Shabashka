<?php

namespace App\Http\Requests\Messenger;

use App\Models\LinkedMessenger;
use Illuminate\Foundation\Http\FormRequest;

class RemoveLinkedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $linkedMessenger = $this->route('linkedMessenger');
        return $this->user()->can('delete', $linkedMessenger);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
