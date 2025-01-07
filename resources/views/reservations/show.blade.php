@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Detail Reservasi</h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
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
                                    <td class="fw-bold">Jam</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jumlah Tamu</td>
                                    <td>{{ $reservation->guest_count }} orang</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informasi Pelanggan</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-bold" width="40%">Nama</td>
                                    <td>{{ $reservation->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email</td>
                                    <td>{{ $reservation->user->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">No. Telepon</td>
                                    <td>{{ $reservation->user->phone }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informasi Meja</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-bold" width="40%">Nomor Meja</td>
                                    <td>Meja #{{ $reservation->table->table_number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Kapasitas</td>
                                    <td>{{ $reservation->table->capacity }} orang</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Lokasi</td>
                                    <td>{{ $reservation->table->location }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Catatan Tambahan</h6>
                            <div class="border rounded p-3 bg-light">
                                {{ $reservation->notes ?? 'Tidak ada catatan' }}
                            </div>
                        </div>
                    </div>

                    @if($reservation->status === 'rejected')
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">Alasan Penolakan</h6>
                            <div class="alert alert-danger">
                                {{ $reservation->rejection_reason }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    @if(auth()->user()->role === 'admin')
                        @if($reservation->status === 'pending')
                            <form action="{{ route('reservations.approve', $reservation) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check me-2"></i>Setujui Reservasi
                                </button>
                            </form>
                            <button type="button" 
                                    class="btn btn-danger w-100 mb-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">
                                <i class="fas fa-times me-2"></i>Tolak Reservasi
                            </button>
                        @elseif($reservation->status === 'approved')
                            <form action="{{ route('reservations.complete', $reservation) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-double me-2"></i>Selesaikan Reservasi
                                </button>
                            </form>
                        @else
                            No Action Available
                        @endif
                    @endif

                    @if(auth()->user()->role === 'customer' && $reservation->status === 'pending')
                        <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit me-2"></i>Edit Reservasi
                        </a>
                        <form action="{{ route('reservations.destroy', $reservation) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger w-100"
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')">
                                <i class="fas fa-trash me-2"></i>Batalkan Reservasi
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if(auth()->user()->role === 'admin' && $reservation->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('reservations.reject', $reservation) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Reservasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan</label>
                        <textarea name="rejection_reason"
                                  class="form-control"
                                  rows="3"
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Reservasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection 