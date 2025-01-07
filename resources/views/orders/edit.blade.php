@extends($layout ?? 'layouts.customer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Pesanan #{{ $order->order_number }}</h5>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="orderForm" class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Item Pesanan -->
                        <div class="mb-4">
                            <h6>Item Pesanan</h6>
                            <div id="orderItems">
                                @foreach($order->orderItems as $index => $orderItem)
                                    <div class="order-item mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select name="items[{{ $index }}][menu_item_id]" class="form-select menu-item" required>
                                                    <option value="">Pilih Menu</option>
                                                    @foreach($menuItems as $item)
                                                        <option value="{{ $item->id }}" 
                                                                data-price="{{ $item->price }}"
                                                                {{ $orderItem->menu_item_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }} - Rp {{ number_format($item->price, 0, ',', '.') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Silakan pilih menu
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" name="items[{{ $index }}][quantity]" 
                                                       class="form-control quantity" 
                                                       placeholder="Jumlah" min="1" max="100" required
                                                       value="{{ $orderItem->quantity }}">
                                                <div class="invalid-feedback">
                                                    Jumlah harus antara 1-100
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="items[{{ $index }}][special_instructions]" 
                                                       class="form-control" placeholder="Instruksi Khusus"
                                                       maxlength="255" value="{{ $orderItem->special_instructions }}">
                                                <div class="invalid-feedback">
                                                    Instruksi khusus terlalu panjang
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger remove-item" 
                                                        {{ $index == 0 ? 'style=display:none' : '' }}>
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="addItem" class="btn btn-secondary mt-2">
                                Tambah Item
                            </button>
                        </div>

                        <!-- Informasi Pengiriman -->
                        <div class="mb-4">
                            <h6>Informasi Pengiriman</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Penerima</label>
                                        <input type="text" name="recipient_name" class="form-control" required
                                               maxlength="255" value="{{ $order->deliveryAddress->recipient_name }}">
                                        <div class="invalid-feedback">
                                            Nama penerima harus diisi
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Telepon</label>
                                        <input type="text" name="recipient_phone" class="form-control" required
                                               maxlength="20" pattern="^[0-9\+\-\(\)\/\s]*$"
                                               value="{{ $order->deliveryAddress->recipient_phone }}">
                                        <div class="invalid-feedback">
                                            Nomor telepon tidak valid
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea name="address" class="form-control" rows="3" required
                                                  maxlength="500">{{ $order->deliveryAddress->address }}</textarea>
                                        <div class="invalid-feedback">
                                            Alamat harus diisi
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Pos</label>
                                        <input type="text" name="postal_code" class="form-control"
                                               pattern="^[0-9]*$" maxlength="10"
                                               value="{{ $order->deliveryAddress->postal_code }}">
                                        <div class="invalid-feedback">
                                            Kode pos harus berupa angka
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Instruksi Pengiriman</label>
                                        <textarea name="delivery_instructions" class="form-control" rows="2"
                                                  maxlength="500">{{ $order->deliveryAddress->delivery_instructions }}</textarea>
                                        <div class="invalid-feedback">
                                            Instruksi pengiriman terlalu panjang
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pembayaran -->
                        <div class="mb-4">
                            <h6>Informasi Pembayaran</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Metode Pembayaran</label>
                                        <select name="payment_method" class="form-select" required id="paymentMethod">
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option value="cash" {{ $order->payment->payment_method === 'cash' ? 'selected' : '' }}>Tunai</option>
                                            <option value="transfer_bank" {{ $order->payment->payment_method === 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                                            <option value="e_wallet" {{ $order->payment->payment_method === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Metode pembayaran harus dipilih
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bukti Pembayaran</label>
                                        @if($order->payment->proof_of_payment)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($order->payment->proof_of_payment) }}" 
                                                     alt="Bukti Pembayaran" class="img-thumbnail" 
                                                     style="max-height: 100px;">
                                            </div>
                                        @endif
                                        <input type="file" name="proof_of_payment" class="form-control" 
                                               accept="image/jpeg,image/png,image/jpg" id="proofOfPayment">
                                        <div class="invalid-feedback">
                                            Bukti pembayaran harus berupa gambar (JPG, PNG) maksimal 2MB
                                        </div>
                                        <small class="text-muted">*Wajib untuk pembayaran non-tunai</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <h6>Catatan Tambahan</h6>
                            <textarea name="notes" class="form-control" rows="2" 
                                      maxlength="500">{{ $order->notes }}</textarea>
                            <div class="invalid-feedback">
                                Catatan terlalu panjang
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="mb-4">
                            <h5>Total: <span id="totalAmount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></h5>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
        const form = document.getElementById('orderForm');
        const orderItems = document.getElementById('orderItems');
        const addItemBtn = document.getElementById('addItem');
        const paymentMethod = document.getElementById('paymentMethod');
        const proofOfPayment = document.getElementById('proofOfPayment');
        let itemCount = {{ count($order->orderItems) }};

        // Add new item
        addItemBtn.addEventListener('click', function() {
            const template = document.querySelector('.order-item').cloneNode(true);
            template.querySelector('.menu-item').name = `items[${itemCount}][menu_item_id]`;
            template.querySelector('.quantity').name = `items[${itemCount}][quantity]`;
            template.querySelector('input[placeholder="Instruksi Khusus"]').name = `items[${itemCount}][special_instructions]`;
            
            // Reset values
            template.querySelector('.menu-item').value = '';
            template.querySelector('.quantity').value = '';
            template.querySelector('input[placeholder="Instruksi Khusus"]').value = '';
            
            // Show remove button
            template.querySelector('.remove-item').style.display = 'block';
            
            orderItems.appendChild(template);
            itemCount++;
            
            // Reinitialize event listeners
            initializeEventListeners();
        });

        // Remove item
        function initializeEventListeners() {
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    if (document.querySelectorAll('.order-item').length > 1) {
                        this.closest('.order-item').remove();
                        calculateTotal();
                    } else {
                        alert('Minimal harus ada 1 item pesanan');
                    }
                });
            });

            document.querySelectorAll('.menu-item, .quantity').forEach(element => {
                element.addEventListener('change', calculateTotal);
            });
        }

        // Calculate total
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.order-item').forEach(item => {
                const select = item.querySelector('.menu-item');
                const quantity = item.querySelector('.quantity');
                
                if (select.value && quantity.value) {
                    const price = select.options[select.selectedIndex].dataset.price;
                    total += price * quantity.value;
                }
            });
            
            document.getElementById('totalAmount').textContent = 
                `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
        }

        // Payment method change handler
        paymentMethod.addEventListener('change', function() {
            const isNonCash = this.value === 'transfer_bank' || this.value === 'e_wallet';
            proofOfPayment.required = isNonCash;
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            
            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            
            fetch('{{ route('orders.update', $order) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.href = '{{ route('orders.index') }}';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui pesanan');
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Simpan Perubahan';
            });
        });

        // Initialize
        initializeEventListeners();
        
        // Set initial payment method requirement
        const isNonCash = paymentMethod.value === 'transfer_bank' || paymentMethod.value === 'e_wallet';
        proofOfPayment.required = isNonCash;
    });
</script>
@endpush
@endsection 