@extends($layout ?? 'layouts.customer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Buat Pesanan Baru</h5>
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
                        
                        <!-- Item Pesanan -->
                        <div class="mb-4">
                            <h6>Item Pesanan</h6>
                            <div id="orderItems">
                                <div class="order-item mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select name="items[0][menu_item_id]" class="form-select menu-item" required>
                                                <option value="">Pilih Menu</option>
                                                @foreach($menuItems as $item)
                                                    <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                                        {{ $item->name }} - Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Silakan pilih menu
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="items[0][quantity]" class="form-control quantity" 
                                                   placeholder="Jumlah" min="1" max="100" required>
                                            <div class="invalid-feedback">
                                                Jumlah harus antara 1-100
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="items[0][special_instructions]" 
                                                   class="form-control" placeholder="Instruksi Khusus"
                                                   maxlength="255">
                                            <div class="invalid-feedback">
                                                Instruksi khusus terlalu panjang
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-item" style="display: none;">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
                                               maxlength="255" value="{{ auth()->user()->name }}">
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
                                               value="{{ auth()->user()->phone }}">
                                        <div class="invalid-feedback">
                                            Nomor telepon tidak valid
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea name="address" class="form-control" rows="3" required
                                                  maxlength="500">{{ auth()->user()->address }}</textarea>
                                        <div class="invalid-feedback">
                                            Alamat harus diisi
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Pos</label>
                                        <input type="text" name="postal_code" class="form-control"
                                               pattern="^[0-9]*$" maxlength="10">
                                        <div class="invalid-feedback">
                                            Kode pos harus berupa angka
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
                                            <option value="cash">Tunai</option>
                                            <option value="transfer_bank">Transfer Bank</option>
                                            <option value="e_wallet">E-Wallet</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Metode pembayaran harus dipilih
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bukti Pembayaran</label>
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
                            <textarea name="notes" class="form-control" rows="2" maxlength="500"></textarea>
                            <div class="invalid-feedback">
                                Catatan terlalu panjang
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="mb-4">
                            <h5>Total: <span id="totalAmount">Rp 0</span></h5>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Buat Pesanan</button>
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
        let itemCount = 1;
        const orderItems = document.getElementById('orderItems');
        const addItemBtn = document.getElementById('addItem');
        const form = document.getElementById('orderForm');
        const paymentMethod = document.getElementById('paymentMethod');
        const proofOfPayment = document.getElementById('proofOfPayment');

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
            
            fetch('{{ route('orders.store') }}', {
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
                alert('Terjadi kesalahan saat membuat pesanan');
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Buat Pesanan';
            });
        });

        // Initialize
        initializeEventListeners();
    });
</script>
@endpush
@endsection 