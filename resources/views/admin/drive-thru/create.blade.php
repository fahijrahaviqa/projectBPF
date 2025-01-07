@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Buat Pesanan Drive Thru</h5>
                    <a href="{{ route('admin.drive-thru.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
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

                    <form id="driveThruForm" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Item Pesanan -->
                        <div class="mb-4">
                            <h6>Item Pesanan</h6>
                            <div id="orderItems">
                                <div class="order-item mb-3">
                                    <div class="row">
                                        <div class="col-md-5">
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
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger remove-item" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addItem" class="btn btn-secondary mt-2">
                                <i class="fas fa-plus"></i> Tambah Item
                            </button>
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

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Buat Pesanan
                            </button>
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
        const form = document.getElementById('driveThruForm');
        const orderItems = document.getElementById('orderItems');
        const addItemBtn = document.getElementById('addItem');
        let itemCount = 1;

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
            
            fetch('{{ route('admin.drive-thru.store') }}', {
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
                    window.location.href = '{{ route('admin.drive-thru.index') }}';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat membuat pesanan');
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Buat Pesanan';
            });
        });

        // Initialize
        initializeEventListeners();
    });
</script>
@endpush
@endsection 