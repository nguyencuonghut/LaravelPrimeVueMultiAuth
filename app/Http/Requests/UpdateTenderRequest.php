<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenderRequest extends FormRequest
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
            'material' => 'required',
            'delivery_condition' => 'required',
            'payment_condition' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'quantities' => 'required',
            'quantities.*.qty' => 'required|gt:1',
            'quantities.*.unit' => 'required',
            'quantities.*.delivery_time' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Bạn phải nhập tiêu đề.',
            'material.required' => 'Bạn phải chọn hàng hóa.',
            'delivery_condition.required' => 'Bạn phải nhập giao hàng.',
            'payment_condition.required' => 'Bạn phải nhập thanh toán.',
            'start_time.required' => 'Bạn phải nhập thời gian bắt đầu.',
            'end_time.required' => 'Bạn phải nhập thời gian kết thúc',
            'quantities.required' => 'Bạn phải chọn lượng thầu.',
            'quantities.*.qty.required' => 'Bạn phải nhập số lượng.',
            'quantities.*.qty.gt' => 'Số lượng phải lớn hơn 0.',
            'quantities.*.unit.required' => 'Bạn phải chọn đơn vị.',
            'quantities.*.delivery_time.required' => 'Bạn phải nhập thời gian giao hàng.',
        ];
    }
}
