<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenderRequest extends FormRequest
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
            'title' => 'required',
            'delivery_condition' => 'required',
            'payment_condition' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Bạn phải nhập tiêu đề.',
            'delivery_condition.required' => 'Bạn phải nhập giao hàng.',
            'payment_condition.required' => 'Bạn phải nhập thanh toán.',
            'start_time.required' => 'Bạn phải nhập thời gian bắt đầu.',
            'end_time.required' => 'Bạn phải nhập thời gian kết thúc',
        ];
    }
}
