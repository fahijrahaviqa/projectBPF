@extends('layouts.admin')

@section('content')
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Edit Kategori</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}" 
                                   required>
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon (Font Awesome Class)</label>
                            <div class="input-group">
                                <span class="input-group-text">fa</span>
                                <input type="text" 
                                       class="form-control @error('icon') is-invalid @enderror" 
                                       id="icon" 
                                       name="icon" 
                                       value="{{ old('icon', str_replace('fa ', '', $category->icon)) }}"
                                       required>
                            </div>
                            <div class="form-text">
                                Contoh: fa-utensils, fa-coffee, fa-hamburger
                                <a href="https://fontawesome.com/icons" target="_blank">
                                    Lihat daftar icon
                                </a>
                            </div>
                            @error('icon')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preview Icon</label>
                            <div class="p-3 bg-light text-center rounded">
                                <i id="icon-preview" class="{{ $category->icon }} fa-2x"></i>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update
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
document.getElementById('icon').addEventListener('input', function(e) {
    const iconClass = 'fa ' + e.target.value;
    document.getElementById('icon-preview').className = iconClass + ' fa-2x';
});
</script>
@endpush
@endsection 