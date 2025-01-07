@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.customer')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Buat Reservasi Baru</h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf
                        
                        <!-- Pilih Meja -->
                        <div class="mb-3">
                            <label for="table_id" class="form-label">Pilih Meja <span class="text-danger">*</span></label>
                            <select name="table_id" id="table_id" class="form-select @error('table_id') is-invalid @enderror" required>
                                <option value="">Pilih Meja</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }}>
                                        Meja #{{ $table->table_number }} (Kapasitas: {{ $table->capacity }} orang)
                                    </option>
                                @endforeach
                            </select>
                            @error('table_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Reservasi -->
                        <div class="mb-3">
                            <label for="reservation_date" class="form-label">Tanggal Reservasi <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('reservation_date') is-invalid @enderror" 
                                   id="reservation_date" 
                                   name="reservation_date"
                                   value="{{ old('reservation_date') }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   max="{{ now()->addMonths(3)->format('Y-m-d') }}"
                                   required>
                            @error('reservation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Waktu Reservasi -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" 
                                           name="start_time"
                                           value="{{ old('start_time') }}"
                                           min="10:00"
                                           max="21:00"
                                           required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimal jam 10:00, maksimal jam 21:00</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" 
                                           name="end_time"
                                           value="{{ old('end_time') }}"
                                           min="11:00"
                                           max="22:00"
                                           required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Maksimal jam 22:00 dan durasi maksimal 3 jam</small>
                                </div>
                            </div>
                        </div>

                        <!-- Jumlah Tamu -->
                        <div class="mb-3">
                            <label for="guest_count" class="form-label">Jumlah Tamu <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('guest_count') is-invalid @enderror" 
                                   id="guest_count" 
                                   name="guest_count"
                                   value="{{ old('guest_count') }}"
                                   min="1"
                                   max="20"
                                   required>
                            @error('guest_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 1 orang, maksimal 20 orang</small>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes"
                                      rows="3"
                                      maxlength="500">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 500 karakter</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Buat Reservasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk memvalidasi durasi reservasi
    function validateDuration() {
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        
        if (startTime && endTime) {
            const start = new Date(`2000-01-01T${startTime}`);
            const end = new Date(`2000-01-01T${endTime}`);
            const duration = (end - start) / (1000 * 60 * 60); // dalam jam
            
            if (duration > 3) {
                alert('Durasi reservasi maksimal 3 jam');
                document.getElementById('end_time').value = '';
            }
        }
    }

    // Event listener untuk perubahan waktu
    document.getElementById('start_time').addEventListener('change', validateDuration);
    document.getElementById('end_time').addEventListener('change', validateDuration);
});
</script>
@endpush
@endsection 