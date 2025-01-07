<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'customer';
    }

    public function rules(): array
    {
        return [
            // Order details
            'notes' => ['nullable', 'string', 'max:500'],
            
            // Order items
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => ['required', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'items.*.special_instructions' => ['nullable', 'string', 'max:255'],
            
            // Payment details
            'payment_method' => ['required', 'in:cash,transfer_bank,e_wallet'],
            'proof_of_payment' => [
                'nullable',
                'required_if:payment_method,transfer_bank,e_wallet',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
            
            // Delivery details
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\(\)\/\s]*$/'],
            'address' => ['required', 'string', 'max:500'],
            'postal_code' => ['nullable', 'string', 'max:10', 'regex:/^[0-9]*$/'],
            'delivery_instructions' => ['nullable', 'string', 'max:500']
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Pesanan tidak boleh kosong',
            'items.min' => 'Minimal harus memesan 1 item',
            'items.*.menu_item_id.required' => 'Menu harus dipilih',
            'items.*.menu_item_id.exists' => 'Menu yang dipilih tidak valid',
            'items.*.quantity.required' => 'Jumlah pesanan harus diisi',
            'items.*.quantity.min' => 'Jumlah pesanan minimal 1',
            'items.*.quantity.max' => 'Jumlah pesanan maksimal 100',
            'payment_method.required' => 'Metode pembayaran harus dipilih',
            'payment_method.in' => 'Metode pembayaran tidak valid',
            'proof_of_payment.required_if' => 'Bukti pembayaran wajib diupload untuk pembayaran non-tunai',
            'proof_of_payment.image' => 'Bukti pembayaran harus berupa gambar',
            'proof_of_payment.mimes' => 'Format bukti pembayaran harus jpeg, png, atau jpg',
            'proof_of_payment.max' => 'Ukuran bukti pembayaran maksimal 2MB',
            'recipient_name.required' => 'Nama penerima harus diisi',
            'recipient_name.max' => 'Nama penerima maksimal 255 karakter',
            'recipient_phone.required' => 'Nomor telepon penerima harus diisi',
            'recipient_phone.max' => 'Nomor telepon maksimal 20 karakter',
            'recipient_phone.regex' => 'Format nomor telepon tidak valid',
            'address.required' => 'Alamat pengiriman harus diisi',
            'address.max' => 'Alamat maksimal 500 karakter',
            'postal_code.regex' => 'Kode pos harus berupa angka',
            'postal_code.max' => 'Kode pos maksimal 10 karakter',
            'delivery_instructions.max' => 'Instruksi pengiriman maksimal 500 karakter',
            'notes.max' => 'Catatan maksimal 500 karakter'
        ];
    }

    protected function prepareForValidation(): void
    {
        $user = Auth::user();
        
        if ($user) {
            $this->merge([
                'recipient_name' => $this->recipient_name ?? $user->name,
                'recipient_phone' => $this->recipient_phone ?? $user->phone,
                'address' => $this->address ?? $user->address,
            ]);
        }
    }
} 