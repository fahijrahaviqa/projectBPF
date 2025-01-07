<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // Order details
            'status' => [
                'required',
                Rule::in(['pending', 'processing', 'completed', 'cancelled'])
            ],
            'notes' => ['nullable', 'string'],
            
            // Payment details (jika belum dibayar)
            'payment_method' => [
                'required_if:status,pending',
                Rule::in(['cash', 'transfer_bank', 'e_wallet'])
            ],
            'proof_of_payment' => [
                'nullable',
                'required_if:payment_method,transfer_bank,e_wallet',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
            
            // Delivery details
            'recipient_name' => ['sometimes', 'required', 'string', 'max:255'],
            'recipient_phone' => ['sometimes', 'required', 'string', 'max:20'],
            'address' => ['sometimes', 'required', 'string'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'delivery_instructions' => ['nullable', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status pesanan harus dipilih',
            'status.in' => 'Status pesanan tidak valid',
            'payment_method.required_if' => 'Metode pembayaran harus dipilih untuk pesanan pending',
            'payment_method.in' => 'Metode pembayaran tidak valid',
            'proof_of_payment.required_if' => 'Bukti pembayaran harus diupload untuk pembayaran transfer',
            'proof_of_payment.image' => 'Bukti pembayaran harus berupa gambar',
            'proof_of_payment.mimes' => 'Format bukti pembayaran harus jpeg, png, atau jpg',
            'proof_of_payment.max' => 'Ukuran bukti pembayaran maksimal 2MB',
            'recipient_name.required' => 'Nama penerima harus diisi',
            'recipient_phone.required' => 'Nomor telepon penerima harus diisi',
            'address.required' => 'Alamat pengiriman harus diisi'
        ];
    }

    public function authorize(): bool
    {
        $order = $this->route('order');
        
        // Admin bisa update semua pesanan
        if (Auth::user()->isAdmin()) {
            return true;
        }
        
        // Customer hanya bisa update pesanan miliknya yang masih pending
        return Auth::id() === $order->user_id && $order->status === 'pending';
    }
} 