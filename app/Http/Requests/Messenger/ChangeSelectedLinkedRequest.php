<?php

namespace App\Http\Requests\Messenger;
;
use Illuminate\Foundation\Http\FormRequest;

class ChangeSelectedLinkedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $linkedMessenger = $this->route('linkedMessenger');
        return $this->user()->can('update', $linkedMessenger);
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
