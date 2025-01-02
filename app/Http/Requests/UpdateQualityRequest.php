<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQualityRequest extends FormRequest
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
            'material' => 'required',
            'detail' => 'required',
            'status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'material.required' => 'Bạn chọn tên nguyên liệu.',
            'detail.required' => 'Bạn phải nhập chi tiết.',
            'status.required' => 'Bạn phải chọn trạng thái.',
        ];
    }
}
