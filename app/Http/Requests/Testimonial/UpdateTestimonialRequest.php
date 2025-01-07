<?php

namespace App\Http\Requests\Testimonial;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->testimonial);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'Judul',
            'content' => 'Konten',
            'rating' => 'Rating',
            'image' => 'Gambar',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul testimonial wajib diisi',
            'title.max' => 'Judul tidak boleh lebih dari :max karakter',
            'content.required' => 'Konten testimonial wajib diisi',
            'content.min' => 'Konten minimal :min karakter',
            'content.max' => 'Konten tidak boleh lebih dari :max karakter',
            'rating.required' => 'Rating wajib diisi',
            'rating.integer' => 'Rating harus berupa angka',
            'rating.min' => 'Rating minimal :min',
            'rating.max' => 'Rating maksimal :max',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
        ];
    }
} 