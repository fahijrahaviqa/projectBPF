@php
    $isAdmin = auth()->user()->isAdmin();
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.customer';
@endphp

@extends($layout)

@section('title', 'Manajemen Testimoni')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ $isAdmin ? 'Manajemen Testimoni' : 'Testimoni Saya' }}</h2>
        </div>
        @if(!$isAdmin)
        <div class="col-md-6 text-end">
            <a href="{{ route('testimonial.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Testimoni
            </a>
        </div>
        @endif
    </div>

    @if($testimonials->isEmpty())
        <div class="alert alert-info">
            {{ $isAdmin ? 'Belum ada testimoni yang diberikan.' : 'Anda belum memberikan testimoni.' }}
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        @if($isAdmin)
                            <th>Pelanggan</th>
                        @endif
                        <th>Judul</th>
                        <th>Rating</th>
                        <th>Konten</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testimonials as $testimonial)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        @if($isAdmin)
                            <td>{{ $testimonial->order->user->name }}</td>
                        @endif
                        <td>{{ $testimonial->title }}</td>
                        <td>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </td>
                        <td>{{ Str::limit($testimonial->content, 50) }}</td>
                        <td>
                            @if($testimonial->is_published)
                                <span class="badge bg-success">Dipublikasi</span>
                            @else
                                <span class="badge bg-warning">Menunggu Review</span>
                            @endif
                        </td>
                        <td>{{ $testimonial->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('testimonial.show', $testimonial) }}" 
                                   class="btn btn-sm btn-info text-white" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($isAdmin)
                                    @if(!$testimonial->is_published)
                                        <button type="button" 
                                                class="btn btn-sm btn-success publish-btn" 
                                                data-id="{{ $testimonial->id }}"
                                                title="Publikasikan">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                @else
                                    @if(!$testimonial->is_published)
                                        <a href="{{ route('testimonial.edit', $testimonial) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('testimonial.destroy', $testimonial) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus testimoni ini?')"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $testimonials->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .btn-group .btn {
        margin-right: 5px;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endpush

@push('scripts')
@if($isAdmin)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const publishButtons = document.querySelectorAll('.publish-btn');
    
    publishButtons.forEach(button => {
        button.addEventListener('click', function() {
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
    });
});
</script>
@endif
@endpush 