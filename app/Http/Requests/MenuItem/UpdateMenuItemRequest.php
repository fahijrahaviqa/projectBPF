<?php

namespace App\Http\Requests\MenuItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id']
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama menu wajib diisi',
            'name.max' => 'Nama menu maksimal 255 karakter',
            'price.required' => 'Harga menu wajib diisi',
            'price.numeric' => 'Harga menu harus berupa angka',
            'price.min' => 'Harga menu minimal 0',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'image.max' => 'Ukuran gambar maksimal 2MB',
            'category_ids.required' => 'Kategori menu wajib dipilih',
            'category_ids.*.exists' => 'Kategori yang dipilih tidak valid',
        ];
    }
} 