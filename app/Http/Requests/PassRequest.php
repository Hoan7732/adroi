<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required|string|min:6',
            'new_password'     => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*\d).+$/', // ít nhất 1 chữ thường và 1 số
                'confirmed'
            ],
            'new_password_confirmation' => 'required_with:new_password|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Mật khẩu hiện tại là bắt buộc.',
            'new_password.required'     => 'Mật khẩu mới là bắt buộc.',
            'new_password.confirmed'    => 'Mật khẩu mới không khớp với xác nhận.',
            'new_password.min'          => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.regex'        => 'Mật khẩu mới phải có ít nhất 1 chữ thường và 1 số.',
            'new_password_confirmation.required_with' => 'Xác nhận mật khẩu mới là bắt buộc khi mật khẩu mới được cung cấp.',
        ];
    }
}
