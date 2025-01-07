@extends($layout ?? 'layouts.customer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Pesanan #{{ $order->order_number }}</h5>
                    <div>
                        @if($order->status === 'pending' && auth()->user()->isCustomer())
                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endif
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">
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
                                    <th width="150">Status</th>
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
                                    <th>Tanggal Pesanan</th>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Pelanggan</th>
                                    <td>{{ $order->user->name }}</td>
                                </tr>
                            </table>
                        </div>
                        @if(auth()->user()->isAdmin())
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Ubah Status</h6>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-warning btn-sm {{ $order->status === 'pending' ? 'active' : '' }}"
                                                    onclick="updateStatus('pending')"
                                                    {{ $order->status === 'cancelled' || $order->status === 'completed' ? 'disabled' : '' }}>
                                                Pending
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm {{ $order->status === 'processing' ? 'active' : '' }}"
                                                    onclick="updateStatus('processing')"
                                                    {{ $order->status === 'cancelled' || $order->status === 'completed' ? 'disabled' : '' }}>
                                                Processing
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm {{ $order->status === 'completed' ? 'active' : '' }}"
                                                    onclick="updateStatus('completed')"
                                                    {{ $order->status === 'cancelled' || $order->status === 'completed' ? 'disabled' : '' }}>
                                                Completed
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm {{ $order->status === 'cancelled' ? 'active' : '' }}"
                                                    onclick="updateStatus('cancelled')"
                                                    {{ $order->status === 'completed' || $order->status === 'cancelled' ? 'disabled' : '' }}>
                                                Cancelled
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
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

                    <div class="row">
                        <!-- Informasi Pengiriman -->
                        <div class="col-md-6 mb-4">
                            <h6 class="border-bottom pb-2">Informasi Pengiriman</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Nama Penerima</th>
                                    <td>{{ $order->deliveryAddress?->recipient_name }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon</th>
                                    <td>{{ $order->deliveryAddress?->recipient_phone }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $order->deliveryAddress?->address }}</td>
                                </tr>
                                <tr>
                                    <th>Kode Pos</th>
                                    <td>{{ $order->deliveryAddress?->postal_code ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Instruksi Pengiriman</th>
                                    <td>{{ $order->deliveryAddress?->delivery_instructions ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Informasi Pembayaran -->
                        <div class="col-md-6 mb-4">
                            <h6 class="border-bottom pb-2">Informasi Pembayaran</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Nomor Pembayaran</th>
                                    <td>{{ $order->payment->payment_number }}</td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}
                                        </span>
                                    </td>
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
                                    <th>Jumlah</th>
                                    <td>Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</td>
                                </tr>
                                @if($order->payment->paid_at)
                                    <tr>
                                        <th>Tanggal Pembayaran</th>
                                        <td>{{ $order->payment->paid_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endif
                                @if($order->payment->proof_of_payment)
                                    <tr>
                                        <th>Bukti Pembayaran</th>
                                        <td>
                                            <a href="{{ Storage::url($order->payment->proof_of_payment) }}" 
                                               target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-image"></i> Lihat Bukti
                                            </a>
                                        </td>
                                    </tr>
                                @endif
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
    function updateStatus(status) {
        if (!confirm('Apakah Anda yakin ingin mengubah status pesanan menjadi ' + status + '?')) {
            return;
        }

        fetch(`/orders/{{ $order->id }}/status/${status}`, {
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
            alert('Terjadi kesalahan saat mengubah status pesanan');
        });
    }
</script>
@endpush
@endsection 