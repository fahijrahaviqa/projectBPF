<?php

namespace App\Http\Requests\Reservation;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $reservation = $this->route('reservation');
        return Auth::check() && (
            Auth::user()->role === 'admin' || 
            (Auth::user()->role === 'customer' && Auth::id() === $reservation->user_id)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Jika user adalah admin
        if (Auth::user()->role === 'admin') {
            return [
                'status' => [
                    'required',
                    Rule::in([
                        Reservation::STATUS_APPROVED,
                        Reservation::STATUS_REJECTED,
                        Reservation::STATUS_CANCELLED,
                        Reservation::STATUS_COMPLETED
                    ])
                ],
                'rejection_reason' => [
                    'required_if:status,rejected',
                    'nullable',
                    'string',
                    'max:500'
                ]
            ];
        }

        // Jika user adalah customer
        return [
            'notes' => [
                'nullable',
                'string',
                'max:500'
            ],
            'status' => [
                'sometimes',
                'required',
                Rule::in([Reservation::STATUS_CANCELLED])
            ]
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
            'status' => 'Status',
            'rejection_reason' => 'Alasan Penolakan',
            'notes' => 'Catatan'
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
            'status.in' => 'Status tidak valid.',
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi jika status ditolak.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 500 karakter.',
            'notes.max' => 'Catatan maksimal 500 karakter.'
        ];
    }
} 