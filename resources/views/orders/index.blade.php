@extends($layout ?? 'layouts.customer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ auth()->user()->isAdmin() ? 'Daftar Pesanan' : 'Pesanan Saya' }}</h5>
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('orders.create') }}" class="btn btn-primary">Buat Pesanan</a>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ auth()->user()->isAdmin() ? route('admin.orders.show', $order) : route('orders.show', $order) }}" 
                                                   class="btn btn-sm btn-info text-white">
                                                    Detail
                                                </a>
                                                
                                                @if(auth()->user()->isAdmin())
                                                    <button type="button" 
                                                            class="btn btn-sm btn-primary dropdown-toggle"
                                                            data-bs-toggle="dropdown">
                                                        Update Status
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button class="dropdown-item" 
                                                                    onclick="updateStatus('{{ $order->id }}', 'processing')">
                                                                Proses
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item" 
                                                                    onclick="updateStatus('{{ $order->id }}', 'completed')">
                                                                Selesai
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item" 
                                                                    onclick="updateStatus('{{ $order->id }}', 'cancelled')">
                                                                Batal
                                                            </button>
                                                        </li>
                                                    </ul>
                                                @endif

                                                @if($order->status === 'pending' && auth()->user()->isCustomer())
                                                    <a href="{{ route('orders.edit', $order) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        Edit
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete('{{ $order->id }}')">
                                                        Hapus
                                                    </button>
                                                @endif

                                                @if(auth()->user()->isCustomer() && $order->canBeReviewed())
                                                    <a href="{{ route('testimonial.create', ['order_id' => $order->id]) }}" 
                                                       class="btn btn-sm btn-success">
                                                        Beri Ulasan
                                                    </a>
                                                @elseif(auth()->user()->isCustomer() && $order->hasTestimonial())
                                                    <a href="{{ route('testimonial.show', $order->testimonial) }}" 
                                                       class="btn btn-sm btn-secondary">
                                                        Lihat Ulasan
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada pesanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateStatus(orderId, status) {
        if (confirm('Apakah Anda yakin ingin mengubah status pesanan ini?')) {
            fetch(`{{ auth()->user()->isAdmin() ? '/admin' : '' }}/orders/${orderId}/status/${status}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah status pesanan');
            });
        }
    }

    function confirmDelete(orderId) {
        if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
            fetch(`/orders/${orderId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus pesanan');
            });
        }
    }
</script>
@endpush
@endsection 