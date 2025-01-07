@php
    $isAdmin = auth()->user()->isAdmin();
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.customer';
@endphp

@extends($layout)

@section('title', 'Detail Testimoni')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Detail Testimoni</h4>
                        <div>
                            @if($isAdmin)
                                @if(!$testimonial->is_published)
                                    <button type="button" 
                                            class="btn btn-success publish-btn" 
                                            data-id="{{ $testimonial->id }}">
                                        <i class="fas fa-check me-2"></i>Publikasikan
                                    </button>
                                @endif
                            @else
                                @if(!$testimonial->is_published)
                                    <a href="{{ route('testimonial.edit', $testimonial) }}" 
                                       class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a>
                                @endif
                            @endif
                            <a href="{{ $isAdmin ? route('admin.testimonials.index') : route('testimonial.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-4">
                                @if($testimonial->image)
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" 
                                         alt="Profile" 
                                         class="rounded-circle me-3"
                                         style="width: 64px; height: 64px; object-fit: cover;">
                                @else
                                    @php
                                        $name = $testimonial->order->user->name;
                                        $initials = strtoupper(substr($name, 0, 2));
                                    @endphp
                                    <div class="profile-initial rounded-circle d-flex align-items-center justify-content-center bg-primary text-white me-3" 
                                         style="width: 64px; height: 64px;">
                                        <span style="font-size: 1.5rem;">{{ $initials }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $testimonial->order->user->name }}</h5>
                                    <div class="text-muted">
                                        <small>Ditulis pada {{ $testimonial->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-4">
                                <h5>{{ $testimonial->title }}</h5>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $testimonial->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="mb-2">
                                    @if($testimonial->is_published)
                                        <span class="badge bg-success">Dipublikasi</span>
                                    @else
                                        <span class="badge bg-warning">Menunggu Review</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4">
                                <p class="text-muted mb-2">Konten Testimoni:</p>
                                <p class="border rounded p-3 bg-light">{{ $testimonial->content }}</p>
                            </div>

                            @if($isAdmin)
                            <div class="mb-4">
                                <p class="text-muted mb-2">Informasi Order:</p>
                                <div class="border rounded p-3">
                                    <p class="mb-1"><strong>Order ID:</strong> {{ $testimonial->order->order_number }}</p>
                                    <p class="mb-1"><strong>Tanggal Order:</strong> {{ $testimonial->order->created_at->format('d M Y') }}</p>
                                    <p class="mb-0"><strong>Status Order:</strong> {{ ucfirst($testimonial->order->status) }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.profile-initial {
    font-size: 1.2rem;
    font-weight: bold;
    font-family: 'Nunito', sans-serif;
}
</style>
@endpush

@push('scripts')
@if($isAdmin)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const publishButton = document.querySelector('.publish-btn');
    
    if (publishButton) {
        publishButton.addEventListener('click', function() {
            const testimonialId = this.dataset.id;
            
            if(confirm('Apakah Anda yakin ingin mempublikasikan testimoni ini?')) {
                fetch(`/admin/testimonials/${testimonialId}/publish`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.message) {
                        alert(data.message);
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mempublikasikan testimoni');
                });
            }
        });
    }
});
</script>
@endif
@endpush 