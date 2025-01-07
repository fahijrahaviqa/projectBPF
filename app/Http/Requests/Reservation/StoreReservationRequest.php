<?php

namespace App\Http\Requests\Reservation;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'customer';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $minDate = Carbon::today();
        $maxDate = Carbon::today()->addMonths(3); // Maksimal 3 bulan ke depan
        
        return [
            'table_id' => [
                'required',
                'exists:tables,id',
                function ($attribute, $value, $fail) {
                    $table = Table::find($value);
                    if ($table && $table->status !== 'available') {
                        $fail('Meja ini sedang tidak tersedia.');
                    }
                }
            ],
            'reservation_date' => [
                'required',
                'date',
                'after_or_equal:' . $minDate->format('Y-m-d'),
                'before_or_equal:' . $maxDate->format('Y-m-d'),
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
                'after_or_equal:10:00',
                'before:22:00',
                function ($attribute, $value, $fail) {
                    // Cek apakah ada reservasi lain di waktu yang sama
                    $existingReservation = Reservation::where('table_id', $this->table_id)
                        ->where('reservation_date', $this->reservation_date)
                        ->where(function ($query) use ($value) {
                            $query->where(function ($q) use ($value) {
                                $q->where('start_time', '<=', $value)
                                  ->where('end_time', '>', $value);
                            });
                        })
                        ->where('status', '!=', 'rejected')
                        ->where('status', '!=', 'cancelled')
                        ->exists();

                    if ($existingReservation) {
                        $fail('Meja sudah direservasi pada waktu tersebut.');
                    }
                }
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
                'before_or_equal:22:00',
                function ($attribute, $value, $fail) {
                    $start = Carbon::createFromFormat('H:i', $this->start_time);
                    $end = Carbon::createFromFormat('H:i', $value);
                    
                    $duration = $end->diffInHours($start);
                    
                    if ($duration > 3) {
                        $fail('Durasi reservasi maksimal 3 jam.');
                    }
                }
            ],
            'guest_count' => [
                'required',
                'integer',
                'min:1',
                'max:20',
                function ($attribute, $value, $fail) {
                    $table = Table::find($this->table_id);
                    if ($table && $value > $table->capacity) {
                        $fail("Jumlah tamu melebihi kapasitas meja ({$table->capacity} orang).");
                    }
                }
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
            'table_id' => 'Meja',
            'reservation_date' => 'Tanggal Reservasi',
            'start_time' => 'Jam Mulai',
            'end_time' => 'Jam Selesai',
            'guest_count' => 'Jumlah Tamu',
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
            'reservation_date.after_or_equal' => 'Tanggal reservasi minimal hari ini.',
            'reservation_date.before_or_equal' => 'Tanggal reservasi maksimal 3 bulan ke depan.',
            'start_time.after_or_equal' => 'Jam mulai minimal pukul 10:00.',
            'start_time.before' => 'Jam mulai maksimal pukul 22:00.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
            'end_time.before_or_equal' => 'Jam selesai maksimal pukul 22:00.',
            'guest_count.min' => 'Jumlah tamu minimal 1 orang.',
            'guest_count.max' => 'Jumlah tamu maksimal 20 orang.',
        ];
    }
} 