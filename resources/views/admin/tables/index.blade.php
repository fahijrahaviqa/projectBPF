@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Meja</h1>
        <a href="{{ route('admin.tables.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Meja
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.tables.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nomor meja atau deskripsi...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Lokasi</label>
                    <select class="form-select" name="location">
                        <option value="">Semua Lokasi</option>
                        @foreach($locationOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('location') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kapasitas Min.</label>
                    <input type="number" class="form-control" name="capacity" value="{{ request('capacity') }}" 
                           min="2" max="20" placeholder="Min. kapasitas">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.tables.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tables List -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">No. Meja</th>
                            <th scope="col">Kapasitas</th>
                            <th scope="col">Status</th>
                            <th scope="col">Lokasi</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tables as $table)
                            <tr>
                                <td class="fw-bold">{{ $table->table_number }}</td>
                                <td>{{ $table->capacity }} orang</td>
                                <td>
                                    @if($table->status === 'available')
                                        <span class="badge bg-success">{{ $table->status_label }}</span>
                                    @elseif($table->status === 'occupied') 
                                        <span class="badge bg-danger">{{ $table->status_label }}</span>
                                    @elseif($table->status === 'reserved')
                                        <span class="badge bg-warning text-dark">{{ $table->status_label }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $table->status_label }}</span>
                                    @endif
                                </td>
                                <td>{{ $table->location_label }}</td>
                                <td>{{ Str::limit($table->description, 50) }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.tables.view', $table) }}" 
                                           class="btn btn-sm btn-primary"
                                           title="Lihat Kalender Reservasi">
                                            <i class="fas fa-calendar-alt"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info text-white" 
                                                data-bs-toggle="modal" data-bs-target="#statusModal{{ $table->id }}"
                                                title="Update Status">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <a href="{{ route('admin.tables.edit', $table) }}" 
                                           class="btn btn-sm btn-warning text-white"
                                           title="Edit Meja">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $table->id }})"
                                                title="Hapus Meja">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Status Update Modal -->
                                    <div class="modal fade" id="statusModal{{ $table->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Status Meja {{ $table->table_number }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="statusForm{{ $table->id }}" 
                                                          action="{{ route('admin.tables.update-status', $table) }}" 
                                                          method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select class="form-select" name="status" required>
                                                                @foreach($statusOptions as $value => $label)
                                                                    <option value="{{ $value }}" 
                                                                            {{ $table->status == $value ? 'selected' : '' }}>
                                                                        {{ $label }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Batal
                                                    </button>
                                                    <button type="submit" form="statusForm{{ $table->id }}" 
                                                            class="btn btn-primary">
                                                        Update Status
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Form -->
                                    <form id="deleteForm{{ $table->id }}" 
                                          action="{{ route('admin.tables.destroy', $table) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <img src="{{ asset('assets/img/empty.svg') }}" alt="Empty" 
                                         style="max-width: 200px; opacity: 0.5;">
                                    <p class="text-muted mt-3">Belum ada data meja</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $tables->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Meja yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}
</script>
@endpush 