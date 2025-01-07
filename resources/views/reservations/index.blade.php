@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.customer')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Reservasi</h5>
            @if(auth()->user()->role === 'customer')
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Buat Reservasi
            </a>
            @endif
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form action="{{ route('reservations.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari kode reservasi..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Reservations Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Reservasi</th>
                            @if(auth()->user()->role === 'admin')
                            <th>Pelanggan</th>
                            @endif
                            <th>Meja</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->reservation_code }}</td>
                            @if(auth()->user()->role === 'admin')
                            <td>{{ $reservation->user->name }}</td>
                            @endif
                            <td>
                                @if($reservation->table)
                                    Meja #{{ $reservation->table->table_number }}
                                @else
                                    <span class="text-muted">Meja tidak tersedia</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</td>
                            <td>
                                @if($reservation->status === 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                @elseif($reservation->status === 'approved') 
                                    <span class="badge bg-success text-light">Disetujui</span>
                                @elseif($reservation->status === 'completed')
                                    <span class="badge bg-info text-dark">Selesai</span>
                                @elseif($reservation->status === 'rejected')
                                    <span class="badge bg-danger text-light">Ditolak</span>
                                @elseif($reservation->status === 'cancelled')
                                    <span class="badge bg-secondary text-light">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('reservations.show', $reservation) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        @if($reservation->status === 'pending')
                                        <button type="button" 
                                                class="btn btn-sm btn-success"
                                                onclick="document.getElementById('approve-form-{{ $reservation->id }}').submit()">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $reservation->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @elseif($reservation->status === 'approved')
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                onclick="document.getElementById('complete-form-{{ $reservation->id }}').submit()">
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                        @endif
                                    @endif
                                    @if(auth()->user()->role === 'customer' && $reservation->status === 'pending')
                                        <a href="{{ route('reservations.edit', $reservation) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')) document.getElementById('delete-form-{{ $reservation->id }}').submit()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Hidden Forms -->
                                @if(auth()->user()->role === 'admin')
                                    <form id="approve-form-{{ $reservation->id }}"
                                          action="{{ route('reservations.approve', $reservation) }}"
                                          method="POST"
                                          class="d-none">
                                        @csrf
                                    </form>
                                    <form id="complete-form-{{ $reservation->id }}"
                                          action="{{ route('reservations.complete', $reservation) }}"
                                          method="POST"
                                          class="d-none">
                                        @csrf
                                    </form>
                                @endif
                                @if(auth()->user()->role === 'customer')
                                    <form id="delete-form-{{ $reservation->id }}"
                                          action="{{ route('reservations.destroy', $reservation) }}"
                                          method="POST"
                                          class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif

                                <!-- Reject Modal -->
                                @if(auth()->user()->role === 'admin')
                                <div class="modal fade" id="rejectModal{{ $reservation->id }}" tabindex="-1">
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? '7' : '6' }}" class="text-center py-4">
                                <div class="text-muted">Tidak ada data reservasi</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 