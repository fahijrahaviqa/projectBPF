@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Drive Thru #{{ $order->order_number }}</h5>
                    <div>
                        <a href="{{ route('admin.drive-thru.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Status dan Informasi Dasar -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Status Pesanan</th>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $order->status === 'completed' ? 'success' : 
                                            ($order->status === 'cancelled' ? 'danger' : 
                                            ($order->status === 'processing' ? 'info' : 'warning')) 
                                        }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Status Pembayaran</th>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $order->payment->status === 'paid' ? 'success' : 
                                            ($order->payment->status === 'failed' ? 'danger' : 'warning') 
                                        }}">
                                            {{ ucfirst($order->payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($order->payment->paid_at)
                                    <tr>
                                        <th>Waktu Pembayaran</th>
                                        <td>{{ $order->payment->paid_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($order->payment->status === 'pending')
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Aksi</h6>
                                        <button type="button" class="btn btn-success btn-sm"
                                                onclick="updatePaymentStatus('{{ $order->id }}')">
                                            <i class="fas fa-check me-2"></i>
                                            Tandai Pembayaran Lunas
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Item Pesanan -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">Item Pesanan</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Subtotal</th>
                                        <th>Instruksi Khusus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>{{ $item->menuItem->name }}</td>
                                            <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-center">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            <td>{{ $item->special_instructions ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="text-center fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Catatan -->
                    @if($order->notes)
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2">Catatan</h6>
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                    @endif
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