@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.customer')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">

        @if ($errors->any())
            <div class="col-md-8 mb-4">
                <div class="alert alert-danger">
                    <h6 class="alert-heading fw-bold mb-1">Terjadi Kesalahan:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                @if(auth()->user()->role === 'admin')
                                    Edit Status Reservasi
                                @else
                                    Edit Reservasi #{{ $reservation->reservation_code }}
                                @endif
                            </h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Informasi Reservasi -->
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Informasi Reservasi</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td class="fw-bold" width="40%">Kode Reservasi</td>
                                        <td>{{ $reservation->reservation_code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status</td>
                                        <td>
                                            @if($reservation->status === 'pending')
                                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                            @elseif($reservation->status === 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($reservation->status === 'completed')
                                                <span class="badge bg-info">Selesai</span>
                                            @elseif($reservation->status === 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @elseif($reservation->status === 'cancelled')
                                                <span class="badge bg-secondary">Dibatalkan</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal</td>
                                        <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Waktu</td>
                                        <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Detail Reservasi</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td class="fw-bold" width="40%">Nama Pelanggan</td>
                                        <td>{{ $reservation->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nomor Meja</td>
                                        <td>Meja #{{ $reservation->table->table_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Jumlah Tamu</td>
                                        <td>{{ $reservation->guest_count }} orang</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Form Edit -->
                    <form action="{{ route('reservations.update', $reservation) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if(auth()->user()->role === 'admin')
                            <!-- Form untuk Admin -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status Reservasi <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="approved" {{ old('status', $reservation->status) === 'approved' ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="rejected" {{ old('status', $reservation->status) === 'rejected' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                    <option value="cancelled" {{ old('status', $reservation->status) === 'cancelled' ? 'selected' : '' }}>
                                        Dibatalkan
                                    </option>
                                    <option value="completed" {{ old('status', $reservation->status) === 'completed' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4" id="rejection_reason_container" style="display: none;">
                                <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="rejection_reason" 
                                          id="rejection_reason" 
                                          class="form-control @error('rejection_reason') is-invalid @enderror"
                                          rows="3"
                                          maxlength="500">{{ old('rejection_reason', $reservation->rejection_reason) }}</textarea>
                                @error('rejection_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 500 karakter</small>
                            </div>

                        @else
                            <!-- Form untuk Customer -->
                            <div class="mb-4">
                                <label for="notes" class="form-label">Catatan Tambahan</label>
                                <textarea name="notes" 
                                          id="notes" 
                                          class="form-control @error('notes') is-invalid @enderror"
                                          rows="3"
                                          maxlength="500"
                                          placeholder="Tambahkan catatan khusus untuk reservasi Anda...">{{ old('notes', $reservation->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 500 karakter</small>
                            </div>

                            <!-- Hidden input untuk status -->
                            <input type="hidden" name="status" id="reservation_status" value="{{ old('status', $reservation->status) }}">
                        @endif

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            @if(auth()->user()->role === 'customer')
                                <button type="button" 
                                        class="btn btn-danger"
                                        onclick="if(confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')) { document.getElementById('reservation_status').value = 'cancelled'; this.form.submit(); }">
                                    <i class="fas fa-times me-2"></i>Batalkan Reservasi
                                </button>
                            @endif
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
    const statusSelect = document.getElementById('status');
    const rejectionReasonContainer = document.getElementById('rejection_reason_container');
    
    if (statusSelect) {
        function toggleRejectionReason() {
            if (statusSelect.value === 'rejected') {
                rejectionReasonContainer.style.display = 'block';
                document.getElementById('rejection_reason').required = true;
            } else {
                rejectionReasonContainer.style.display = 'none';
                document.getElementById('rejection_reason').required = false;
            }
        }
        
        // Initial check
        toggleRejectionReason();
        
        // Event listener
        statusSelect.addEventListener('change', toggleRejectionReason);
    }
});
</script>
@endpush
@endsection 