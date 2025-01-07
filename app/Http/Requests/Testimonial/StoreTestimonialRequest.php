<?php

namespace App\Http\Requests\Testimonial;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTestimonialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan order milik user yang sedang login dan statusnya completed
        if (!$this->order_id) {
            return false;
        }

        $order = $this->user()->orders()->find($this->order_id);
        return $order && $order->status === 'completed';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'integer',
                Rule::exists('orders', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id())
                          ->where('status', 'completed');
                }),
                Rule::unique('testimonials', 'order_id'),
            ],
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
            'order_id' => 'Order',
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
            'order_id.required' => 'Order wajib dipilih',
            'order_id.exists' => 'Order tidak valid atau belum selesai',
            'order_id.unique' => 'Anda sudah memberikan testimonial untuk order ini',
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