<?php

namespace App\Http\Requests\Table;

use App\Models\Table;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'table_number' => [
                'required',
                'string',
                'max:10',
                'unique:tables,table_number',
                'regex:/^[A-Z0-9]+$/' // Hanya huruf kapital dan angka
            ],
            'capacity' => [
                'required',
                'integer',
                'min:2',
                'max:20'
            ],
            'status' => [
                'required',
                Rule::in([
                    Table::STATUS_AVAILABLE,
                    Table::STATUS_OCCUPIED,
                    Table::STATUS_RESERVED,
                    Table::STATUS_MAINTENANCE
                ])
            ],
            'location' => [
                'required',
                Rule::in([
                    Table::LOCATION_INDOOR,
                    Table::LOCATION_OUTDOOR,
                    Table::LOCATION_ROOFTOP,
                    Table::LOCATION_PRIVATE_ROOM
                ])
            ],
            'description' => [
                'required',
                'string',
                'max:255'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500'
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
            'table_number' => 'Nomor Meja',
            'capacity' => 'Kapasitas',
            'status' => 'Status',
            'location' => 'Lokasi',
            'description' => 'Deskripsi',
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
            'table_number.required' => 'Nomor meja wajib diisi',
            'table_number.unique' => 'Nomor meja sudah digunakan',
            'table_number.regex' => 'Nomor meja hanya boleh menggunakan huruf kapital dan angka',
            'capacity.required' => 'Kapasitas meja wajib diisi',
            'capacity.integer' => 'Kapasitas harus berupa angka',
            'capacity.min' => 'Kapasitas minimal 2 orang',
            'capacity.max' => 'Kapasitas maksimal 20 orang',
            'status.required' => 'Status meja wajib diisi',
            'status.in' => 'Status meja tidak valid',
            'location.required' => 'Lokasi meja wajib diisi',
            'location.in' => 'Lokasi meja tidak valid',
            'description.required' => 'Deskripsi meja wajib diisi',
            'description.max' => 'Deskripsi tidak boleh lebih dari :max karakter',
            'notes.max' => 'Catatan tidak boleh lebih dari :max karakter'
        ];
    }
} 