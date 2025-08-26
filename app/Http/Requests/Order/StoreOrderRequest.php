<?php

namespace App\Http\Requests\Order;

use App\Enums\PaymentType;
use App\Models\PaymentPer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreOrderRequest extends FormRequest
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
            'title' => "required|string|max:255",
            'content' => "required|string|max:6000",
            'city_id' => "required|numeric|exists:cities,id",
            'address' => "required|string|max:255",
            'payment_type' => "required|string|max:255",
            'price' => "nullable|numeric|min:1|max:10000000",
            'payment_per' => "nullable|numeric|exists:payment_pers,id",
            'need_count_workers' => "nullable|numeric|min:1|max:200",
            'images' => 'nullable|array|min:1|max:10',
            'images.*' => "string",
            'categories_selected' => 'required|array|min:1|max:' . config('data_app.max_count_selected_categories_for_new_order'),
            'categories_selected.*' => "numeric|exists:work_categories,id",
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->payment_type === PaymentType::DOGOVOR->value) {
                    $this->merge([
                        'price' => null,
                        'payment_per' => null,
                    ]);
                } else if ($this->payment_type === PaymentType::FIXED->value && !$this->price) {
                    $validator->errors()->add(
                        'price',
                        'Укажите объем оплаты'
                    );
                } else if ($this->payment_type === PaymentType::FIXED->value && !$this->payment_per) {
                    $validator->errors()->add(
                        'payment_per',
                        'Выберите формат оплаты'
                    );
                }
                if (!$this->images) {
                    $this->merge([
                        'images' => null,
                    ]);
                }
                if ($this->payment_per) {
                    $payment_per = PaymentPer::find($this->payment_per);
                    $this->merge([
                        'payment_per' => $payment_per->name,
                    ]);
                }
            }
        ];
    }

}
