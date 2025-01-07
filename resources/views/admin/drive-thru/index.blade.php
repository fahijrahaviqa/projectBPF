@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pesanan Drive Thru</h5>
                    <a href="{{ route('admin.drive-thru.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Buat Pesanan
                    </a>
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
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $order->status === 'completed' ? 'success' : 
                                                ($order->status === 'cancelled' ? 'danger' : 
                                                ($order->status === 'processing' ? 'info' : 'warning')) 
                                            }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $order->payment->status === 'paid' ? 'success' : 
                                                ($order->payment->status === 'failed' ? 'danger' : 'warning') 
                                            }}">
                                                {{ ucfirst($order->payment->status) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.drive-thru.show', $order) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($order->payment->status === 'pending')
                                                    <button type="button" 
                                                            class="btn btn-success btn-sm"
                                                            onclick="updatePaymentStatus('{{ $order->id }}')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada pesanan drive thru</td>
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
    function updatePaymentStatus(orderId) {
        if (!confirm('Apakah Anda yakin ingin menandai pembayaran ini sebagai lunas?')) {
            return;
        }

        fetch(`/admin/drive-thru/${orderId}/payment`, {
            method: 'POST',
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
            alert('Terjadi kesalahan saat memperbarui status pembayaran');
        });
    }
</script>
@endpush
@endsection 