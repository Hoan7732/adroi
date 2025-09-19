<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Xác định xem user có quyền thực hiện request này không
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Các rule validate áp dụng cho request
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*\d).+$/', // ít nhất 1 chữ thường và 1 số
                'confirmed'
            ],
            'avatar'   => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ];
    }

    /**
     * Thông báo lỗi tùy chỉnh
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'Tên là bắt buộc.',
            'email.required'    => 'Email là bắt buộc.',
            'email.email'       => 'Email không hợp lệ.',
            'email.unique'      => 'Email đã tồn tại.',
            
            'password.required'  => 'Mật khẩu là bắt buộc.',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex'     => 'Mật khẩu phải chứa ít nhất 1 chữ thường và 1 số.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',

            'avatar.required' => 'Ảnh đại diện là bắt buộc.',
            'avatar.image'    => 'Ảnh đại diện phải là file hình ảnh.',
            'avatar.mimes'    => 'Ảnh đại diện chỉ được định dạng jpeg, png, jpg, gif, svg.',
            'avatar.max'      => 'Ảnh đại diện không được vượt quá 5MB.',
        ];
    }
}
