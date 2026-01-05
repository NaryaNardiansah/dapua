@extends('layouts.admin')

@section('content')
<div class="luxury-category-form-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-edit"
        title="Edit Kategori"
        subtitle="Perbarui informasi kategori Dapur Sakura"
        description="Ubah data kategori dengan mudah dan efisien"
        :showCircle="true"
    />

    <!-- Status Alert -->
    @if(session('status'))
        <div class="status-alert fade-in-up delay-100" data-aos="fade-down">
            <div class="alert-content">
                <i class="fas fa-check-circle alert-icon"></i>
                <span class="alert-text">{{ session('status') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="status-alert error fade-in-up delay-100" data-aos="fade-down">
            <div class="alert-content">
                <i class="fas fa-exclamation-circle alert-icon"></i>
                <span class="alert-text">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="action-buttons-section flex flex-wrap gap-4 mt-8 fade-in-up delay-200" data-aos="fade-up">
        <a href="{{ route('admin.categories.index') }}" class="action-btn secondary">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Kategori
        </a>
    </div>

    <!-- Form Container -->
    <x-admin-responsive-form 
        method="PUT"
        action="{{ route('admin.categories.update', $category) }}"
        enctype="multipart/form-data"
        title="Edit Kategori"
        icon="fas fa-tags"
        description="Perbarui informasi kategori dengan mudah"
        :delay="300"
    >
        <!-- Informasi Dasar -->
        <div class="form-group">
            <label class="form-label">Nama Kategori *</label>
            <input type="text" 
                   name="name" 
                   value="{{ old('name', $category->name) }}" 
                   class="form-input" 
                   placeholder="Masukkan nama kategori" 
                   required>
            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Induk Kategori</label>
            <select name="parent_id" class="form-input">
                <option value="">Tanpa Induk (Kategori Utama)</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            @error('parent_id')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Warna</label>
            <div class="color-picker-container">
                <input type="color" 
                       id="color-picker" 
                       value="{{ old('color', $category->color) }}" 
                       class="color-picker">
                <input type="text" 
                       name="color" 
                       id="color-input" 
                       value="{{ old('color', $category->color) }}" 
                       class="form-input color-input" 
                       placeholder="#f472b6">
            </div>
            @error('color')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Urutan</label>
            <input type="number" 
                   name="sort_order" 
                   value="{{ old('sort_order', $category->sort_order) }}" 
                   class="form-input" 
                   placeholder="0">
            @error('sort_order')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" 
                      class="form-textarea" 
                      rows="4" 
                      placeholder="Masukkan deskripsi kategori">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Gambar Kategori</label>
            <div class="image-upload-container">
                <input type="file" 
                       name="image" 
                       id="image-upload" 
                       class="form-file" 
                       accept="image/*">
                <label for="image-upload" class="image-upload-label">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Pilih Gambar</span>
                </label>
                @if($category->image)
                    <div class="current-image">
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="preview-image">
                        <span class="current-image-text">Gambar saat ini</span>
                    </div>
                @endif
            </div>
            @error('image')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Status</label>
            <div class="toggle-container">
                <input type="checkbox" 
                       name="is_active" 
                       id="is_active" 
                       value="1" 
                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                       class="toggle-input">
                <label for="is_active" class="toggle-label">
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">Aktif</span>
                </label>
            </div>
            @error('is_active')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>
					
        @slot('actions')
            <x-admin-button-group>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>Simpan Perubahan
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>Batal
                </a>
            </x-admin-button-group>
        @endslot
    </x-admin-responsive-form>
</div>

<style>
/* Luxury Category Form Page Styles */
.luxury-category-form-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
}

/* Color Picker Container */
.color-picker-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.color-picker {
    width: 50px;
    height: 40px;
    border: 2px solid var(--gray-300);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.color-picker:hover {
    border-color: var(--primary-pink);
    transform: scale(1.05);
}

.color-input {
    flex: 1;
}

/* Image Upload Container */
.image-upload-container {
    position: relative;
}

.form-file {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.image-upload-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem;
    border: 2px dashed var(--gray-300);
    border-radius: 12px;
    background: var(--pure-white);
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.image-upload-label:hover {
    border-color: var(--primary-pink);
    background: rgba(236, 72, 153, 0.05);
    color: var(--primary-pink);
}

.image-upload-label i {
    font-size: 1.25rem;
}

.current-image {
    margin-top: 1rem;
    text-align: center;
}

.preview-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid var(--gray-200);
}

.current-image-text {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: var(--gray-500);
}

/* Toggle Container */
.toggle-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.toggle-input {
    display: none;
}

.toggle-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-weight: 500;
    color: var(--gray-700);
}

.toggle-slider {
    position: relative;
    width: 50px;
    height: 24px;
    background: var(--gray-300);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.toggle-slider::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background: var(--pure-white);
    border-radius: 50%;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.toggle-input:checked + .toggle-label .toggle-slider {
    background: var(--primary-pink);
}

.toggle-input:checked + .toggle-label .toggle-slider::before {
    transform: translateX(26px);
}

.toggle-text {
    font-size: 0.875rem;
}

/* Form Error */
.form-error {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: var(--error-red);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.form-error::before {
    content: '⚠';
    font-size: 0.75rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .color-picker-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .color-picker {
        width: 100%;
        height: 50px;
    }
    
    .toggle-container {
        justify-content: space-between;
    }
    
    .image-upload-label {
        padding: 0.75rem;
        font-size: 0.875rem;
    }
    
    .preview-image {
        width: 80px;
        height: 80px;
    }
}

@media (max-width: 480px) {
    .color-picker {
        height: 45px;
    }
    
    .image-upload-label {
        padding: 0.5rem;
        font-size: 0.8rem;
    }
    
    .preview-image {
        width: 60px;
        height: 60px;
    }
}
</style>

<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        mirror: false
    });

    // Color picker synchronization
    const colorPicker = document.getElementById('color-picker');
    const colorInput = document.getElementById('color-input');
    
    if (colorPicker && colorInput) {
        colorPicker.addEventListener('input', function() {
            colorInput.value = this.value;
        });
        
        colorInput.addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                colorPicker.value = this.value;
            }
        });
    }

    // Image preview
    const imageUpload = document.getElementById('image-upload');
    if (imageUpload) {
        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview if doesn't exist
                    let preview = document.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.className = 'image-preview';
                        preview.innerHTML = '<img class="preview-image" alt="Preview"><span class="preview-text">Preview</span>';
                        imageUpload.parentNode.appendChild(preview);
                    }
                    
                    const img = preview.querySelector('img');
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection