<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsRequest extends FormRequest
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
            'txtname' => 'required|string|max:255',
            'txtcategory' => 'required|string|max:255',
            'txtdate' => 'required|date',
            'txtgia' => 'required|numeric|min:0',
            'txtsoluong' => 'required|integer|min:1',
            'txtmota' => 'nullable|string|max:1000',
            'txtcauhinhtt' => 'nullable|string|max:1000',
            'txtcauhinhdx' => 'nullable|string|max:1000',
            'txtanh' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480', // Max 2MB
        ];
    }
    public function messages(): array
    {
        return [
            'txtname.required'      => 'Vui lòng nhập tên sản phẩm.',
            'txtcategory.required'  => 'Vui lòng chọn danh mục.',
            'txtdate.required'      => 'Vui lòng chọn ngày.',
            'txtgia.required'       => 'Vui lòng nhập giá.',
            'txtgia.numeric'        => 'Giá phải là số.',
            'txtsoluong.required'   => 'Vui lòng nhập số lượng.',
            'txtsoluong.integer'    => 'Số lượng phải là số nguyên.',
            'txtmota.required'      => 'Vui lòng nhập mô tả.',
            'txtanh.image'          => 'File tải lên phải là ảnh.',
            'txtanh.mimes'          => 'Ảnh phải có định dạng jpeg, png, jpg, gif hoặc webp.',
            'txtanh.max'            => 'Ảnh không được vượt quá 2MB.',
        ];
    }
}
