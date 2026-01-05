@extends('layouts.admin')

@section('content')
<div class="luxury-product-form-page">
    <!-- Hero Section -->
    <div class="hero-section fade-in-up">
        <div class="hero-content">
            <div class="hero-title-container">
                <h1 class="hero-title fade-in-up delay-200">
                    <i class="fas fa-edit hero-icon"></i>
                    Edit Produk
                </h1>
                <p class="hero-subtitle fade-in-up delay-300">
                    Perbarui informasi produk Dapur Sakura
                </p>
            </div>
            <div class="hero-decorative-elements">
                <div class="decorative-line fade-in-up delay-400"></div>
                <div class="decorative-dots fade-in-up delay-500"></div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons-section flex flex-wrap gap-4 mt-8 fade-in-up delay-200" data-aos="fade-up">
        <a href="{{ route('admin.products.index') }}" class="action-btn secondary">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Produk
        </a>
    </div>

    <!-- Form Container -->
    <div class="form-container mt-8 fade-in-up delay-300" data-aos="fade-up">
        <form action="{{ route('admin.products.update', $product) }}" 
              method="post" enctype="multipart/form-data" class="luxury-form">
            @csrf
            @method('PUT')
            
            <!-- Informasi Dasar -->
            <div class="form-section fade-in-up delay-400" data-aos="fade-up">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="section-title">
                        <h3>Informasi Dasar</h3>
                        <p>Data utama produk yang akan ditampilkan</p>
                    </div>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Kategori <span class="required">*</span>
                            </label>
                            <div class="select-wrapper">
                                <select name="category_id" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $id => $name)
                                        <option value="{{ $id }}" 
                                                @selected(old('category_id', $product->category_id) == $id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                            @error('category_id')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Nama Produk <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input name="name" 
                                       value="{{ old('name', $product->name) }}" 
                                       placeholder="Masukkan nama produk" 
                                       class="form-input" 
                                       required />
                                <i class="fas fa-box input-icon"></i>
                            </div>
                            @error('name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Harga -->
            <div class="form-section fade-in-up delay-500" data-aos="fade-up">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="section-title">
                        <h3>Harga & Penetapan</h3>
                        <p>Atur harga normal dan harga diskon produk</p>
                    </div>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Harga Normal <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <span class="currency-symbol">Rp</span>
                                <input type="number" 
                                       name="price" 
                                       value="{{ old('price', $product->price) }}" 
                                       min="0" 
                                       placeholder="0" 
                                       class="form-input currency-input" 
                                       required />
                            </div>
                            @error('price')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga Diskon</label>
                            <div class="input-wrapper">
                                <span class="currency-symbol">Rp</span>
                                <input type="number" 
                                       name="sale_price" 
                                       value="{{ old('sale_price', $product->sale_price) }}" 
                                       min="0" 
                                       placeholder="0" 
                                       class="form-input currency-input" />
                            </div>
                            @error('sale_price')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deskripsi & Konten -->
            <div class="form-section fade-in-up delay-600" data-aos="fade-up">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-align-left"></i>
                    </div>
                    <div class="section-title">
                        <h3>Deskripsi & Konten</h3>
                        <p>Informasi detail dan konten produk</p>
                    </div>
                </div>
                <div class="section-content">
                    <div class="form-group">
                        <label class="form-label">Deskripsi Singkat</label>
                        <div class="textarea-wrapper">
                            <textarea name="short_description" 
                                      rows="3" 
                                      placeholder="Deskripsi singkat produk (maksimal 500 karakter)" 
                                      class="form-textarea">{{ old('short_description', $product->short_description) }}</textarea>
                            <div class="char-counter">
                                <span class="current-count">0</span>/500
                            </div>
                        </div>
                        @error('short_description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi Lengkap</label>
                        <div class="editor-wrapper">
                            <trix-editor input="description" 
                                        class="form-editor"></trix-editor>
                            <input type="hidden" 
                                   id="description" 
                                   name="description" 
                                   value="{{ old('description', $product->description) }}" />
                        </div>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tags</label>
                        <div class="input-wrapper">
                            <input name="tags_input" 
                                   value="{{ old('tags_input', is_array($product->tags) ? implode(', ', $product->tags) : '') }}" 
                                   placeholder="Masukkan tags dipisahkan koma (contoh: makanan, jepang, sehat)" 
                                   class="form-input" />
                            <i class="fas fa-tags input-icon"></i>
                        </div>
                        <p class="form-help">Pisahkan dengan koma untuk multiple tags</p>
                        <input type="hidden" name="tags" id="tags" value="{{ old('tags') }}" />
                        @error('tags')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Media -->
            <div class="form-section fade-in-up delay-700" data-aos="fade-up">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="section-title">
                        <h3>Media & Gambar</h3>
                        <p>Upload gambar utama produk</p>
                    </div>
                </div>
                <div class="section-content">
                    <div class="form-group">
                        <label class="form-label">Gambar Utama</label>
                        @if($product->image_path)
                            <div class="current-image">
                                <img src="{{ asset('storage/'.$product->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="image-preview" />
                                <div class="image-overlay">
                                    <span class="image-label">Gambar saat ini</span>
                                </div>
                            </div>
                        @endif
                        <div class="file-upload-wrapper">
                            <input type="file" 
                                   name="image" 
                                   accept="image/*" 
                                   class="file-input" 
                                   id="image-upload" />
                            <label for="image-upload" class="file-upload-label">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">
                                    <span class="upload-title">Pilih Gambar Baru</span>
                                    <span class="upload-subtitle">JPG, PNG, maksimal 2MB</span>
                                </div>
                            </label>
                        </div>
                        @error('image')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status & Pengaturan -->
            <div class="form-section fade-in-up delay-800" data-aos="fade-up">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="section-title">
                        <h3>Status & Pengaturan</h3>
                        <p>Konfigurasi status dan pengaturan produk</p>
                    </div>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Urutan Tampilan</label>
                            <div class="input-wrapper">
                                <input type="number" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', $product->sort_order) }}" 
                                       min="0" 
                                       class="form-input" />
                                <i class="fas fa-sort input-icon"></i>
                            </div>
                            @error('sort_order')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="status-options">
                        <h4 class="status-title">Status Produk</h4>
                        <div class="status-grid">
                            <label class="status-option">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }} 
                                       class="status-checkbox" />
                                <div class="status-content">
                                    <div class="status-icon active">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="status-text">
                                        <span class="status-name">Aktif</span>
                                        <span class="status-desc">Produk dapat dilihat pelanggan</span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="status-option">
                                <input type="checkbox" 
                                       name="is_best_seller" 
                                       value="1" 
                                       {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }} 
                                       class="status-checkbox" />
                                <div class="status-content">
                                    <div class="status-icon best-seller">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="status-text">
                                        <span class="status-name">Best Seller</span>
                                        <span class="status-desc">Produk terlaris</span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="status-option">
                                <input type="checkbox" 
                                       name="is_featured" 
                                       value="1" 
                                       {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} 
                                       class="status-checkbox" />
                                <div class="status-content">
                                    <div class="status-icon featured">
                                        <i class="fas fa-gem"></i>
                                    </div>
                                    <div class="status-text">
                                        <span class="status-name">Featured</span>
                                        <span class="status-desc">Produk unggulan</span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="status-option">
                                <input type="checkbox" 
                                       name="is_new" 
                                       value="1" 
                                       {{ old('is_new', $product->is_new) ? 'checked' : '' }} 
                                       class="status-checkbox" />
                                <div class="status-content">
                                    <div class="status-icon new">
                                        <i class="fas fa-sparkles"></i>
                                    </div>
                                    <div class="status-text">
                                        <span class="status-name">Produk Baru</span>
                                        <span class="status-desc">Produk terbaru</span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="status-option">
                                <input type="checkbox" 
                                       name="is_on_sale" 
                                       value="1" 
                                       {{ old('is_on_sale', $product->is_on_sale) ? 'checked' : '' }} 
                                       class="status-checkbox" />
                                <div class="status-content">
                                    <div class="status-icon sale">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="status-text">
                                        <span class="status-name">Sedang Diskon</span>
                                        <span class="status-desc">Produk dengan harga diskon</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions fade-in-up delay-900" data-aos="fade-up">
                <div class="actions-container">
                    <a href="{{ route('admin.products.index') }}" class="action-btn secondary">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="action-btn primary">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Custom Styles -->
<style>
    .luxury-product-form-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #fce7f3 0%, #ffffff 100%);
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #ec4899 0%, #f472b6 50%, #f9a8d4 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .hero-icon {
        margin-right: 1rem;
        animation: float 3s ease-in-out infinite;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        font-weight: 300;
    }

    .hero-decorative-elements {
        margin-top: 2rem;
    }

    .decorative-line {
        width: 100px;
        height: 3px;
        background: rgba(255,255,255,0.5);
        margin: 0 auto 1rem;
        border-radius: 2px;
    }

    .decorative-dots {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .decorative-dots::before,
    .decorative-dots::after {
        content: '';
        width: 8px;
        height: 8px;
        background: rgba(255,255,255,0.6);
        border-radius: 50%;
    }

    /* Action Buttons */
    .action-buttons-section {
        margin-bottom: 2rem;
    }

    .action-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .action-btn.primary {
        background: linear-gradient(135deg, #ec4899, #f472b6);
        color: white;
    }

    .action-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
    }

    .action-btn.secondary {
        background: white;
        color: #6b7280;
        border-color: #e5e7eb;
    }

    .action-btn.secondary:hover {
        border-color: #ec4899;
        color: #ec4899;
    }

    .action-btn.info {
        background: #3b82f6;
        color: white;
    }

    /* Form Container */
    .form-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .luxury-form {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        border: 1px solid rgba(236, 72, 153, 0.1);
    }

    /* Form Sections */
    .form-section {
        padding: 2rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .section-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ec4899, #f472b6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
    }

    .section-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.5rem 0;
    }

    .section-title p {
        color: #6b7280;
        margin: 0;
        font-size: 0.875rem;
    }

    .section-content {
        padding-left: 4rem;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .section-content {
            padding-left: 0;
        }
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .required {
        color: #ef4444;
    }

    /* Input Styles */
    .input-wrapper {
        position: relative;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: #ec4899;
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 0.875rem;
    }

    /* Currency Input */
    .currency-input {
        padding-left: 3rem;
    }

    .currency-symbol {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-weight: 600;
        font-size: 0.875rem;
    }

    /* Select Styles */
    .select-wrapper {
        position: relative;
    }

    .form-select {
        width: 100%;
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.875rem;
        background: white;
        transition: all 0.3s ease;
        appearance: none;
    }

    .form-select:focus {
        outline: none;
        border-color: #ec4899;
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
    }

    .select-arrow {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    /* Textarea Styles */
    .textarea-wrapper {
        position: relative;
    }

    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: white;
        resize: vertical;
        min-height: 100px;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #ec4899;
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
    }

    .char-counter {
        position: absolute;
        bottom: 0.5rem;
        right: 1rem;
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .current-count {
        color: #ec4899;
        font-weight: 600;
    }

    /* Editor Styles */
    .editor-wrapper {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .editor-wrapper:focus-within {
        border-color: #ec4899;
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
    }

    .form-editor {
        min-height: 200px;
        border: none;
        padding: 1rem;
    }

    /* File Upload */
    .file-upload-wrapper {
        margin-top: 1rem;
    }

    .file-input {
        display: none;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .file-upload-label:hover {
        border-color: #ec4899;
        background: #fef7ff;
    }

    .upload-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background: linear-gradient(135deg, #ec4899, #f472b6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .upload-text {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .upload-title {
        font-weight: 600;
        color: #374151;
    }

    .upload-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
    }

    /* Current Image */
    .current-image {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .image-preview {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
    }

    .image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 0.5rem;
        border-radius: 0 0 12px 12px;
        text-align: center;
    }

    .image-label {
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Status Options */
    .status-options {
        margin-top: 2rem;
    }

    .status-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .status-option {
        display: block;
        cursor: pointer;
    }

    .status-checkbox {
        display: none;
    }

    .status-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: white;
    }

    .status-option:hover .status-content {
        border-color: #ec4899;
        background: #fef7ff;
    }

    .status-checkbox:checked + .status-content {
        border-color: #ec4899;
        background: linear-gradient(135deg, #fef7ff, #fce7f3);
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.2);
    }

    .status-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
    }

    .status-icon.active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-icon.best-seller {
        background: #fef3c7;
        color: #92400e;
    }

    .status-icon.featured {
        background: #e0e7ff;
        color: #3730a3;
    }

    .status-icon.new {
        background: #d1fae5;
        color: #065f46;
    }

    .status-icon.sale {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-text {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .status-name {
        font-weight: 600;
        color: #374151;
    }

    .status-desc {
        font-size: 0.75rem;
        color: #6b7280;
    }

    /* Form Help */
    .form-help {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }

    /* Form Error */
    .form-error {
        font-size: 0.75rem;
        color: #ef4444;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-error::before {
        content: '⚠';
        font-size: 0.875rem;
    }

    /* Form Actions */
    .form-actions {
        padding: 2rem;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
    }

    .actions-container {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    /* Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .form-section {
            padding: 1.5rem;
        }
        
        .section-header {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }
        
        .section-content {
            padding-left: 0;
        }
        
        .actions-container {
            flex-direction: column;
        }
        
        .status-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        mirror: false,
    });

    // Handle tags input
    const tagsInput = document.querySelector('input[name="tags_input"]');
    const tagsHidden = document.getElementById('tags');
    
    if (tagsInput && tagsHidden) {
        tagsInput.addEventListener('input', function() {
            const tags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag);
            tagsHidden.value = JSON.stringify(tags);
        });
        
        // Initialize tags if there's existing data
        if (tagsInput.value) {
            const tags = tagsInput.value.split(',').map(tag => tag.trim()).filter(tag => tag);
            tagsHidden.value = JSON.stringify(tags);
        }
    }

    // Handle character counter
    const textarea = document.querySelector('.form-textarea');
    const charCounter = document.querySelector('.char-counter .current-count');
    
    if (textarea && charCounter) {
        textarea.addEventListener('input', function() {
            charCounter.textContent = this.value.length;
            
            if (this.value.length > 500) {
                charCounter.style.color = '#ef4444';
            } else if (this.value.length > 400) {
                charCounter.style.color = '#f59e0b';
            } else {
                charCounter.style.color = '#ec4899';
            }
        });
        
        // Initialize counter
        charCounter.textContent = textarea.value.length;
    }

    // Handle file upload preview
    const fileInput = document.getElementById('image-upload');
    const fileLabel = document.querySelector('.file-upload-label');
    
    if (fileInput && fileLabel) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Update label text
                    const uploadTitle = fileLabel.querySelector('.upload-title');
                    uploadTitle.textContent = file.name;
                    
                    // Add preview if needed
                    const existingPreview = document.querySelector('.file-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    const preview = document.createElement('div');
                    preview.className = 'file-preview';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-top: 1rem;">
                    `;
                    fileLabel.appendChild(preview);
                };
                
                reader.readAsDataURL(file);
            }
        });
    }

    // Handle form validation
    const form = document.querySelector('.luxury-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission started...');
            
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let firstErrorField = null;
            
            // Clear previous errors
            form.querySelectorAll('.form-error').forEach(error => error.remove());
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ef4444';
                    
                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                    
                    // Add error message
                    const error = document.createElement('div');
                    error.className = 'form-error';
                    error.textContent = 'Field ini wajib diisi';
                    field.parentNode.appendChild(error);
                } else {
                    field.style.borderColor = '#e5e7eb';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                console.log('Form validation failed');
                
                // Scroll to first error
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                console.log('Form validation passed, submitting...');
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                }
            }
        });
    }

    // Handle status checkbox animations
    const statusCheckboxes = document.querySelectorAll('.status-checkbox');
    statusCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const content = this.nextElementSibling;
            if (this.checked) {
                content.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    content.style.transform = 'scale(1)';
                }, 200);
            }
        });
    });

    // Debug form data before submission
    const form = document.querySelector('.luxury-form');
    if (form) {
        // Add debug button for testing
        const debugBtn = document.createElement('button');
        debugBtn.type = 'button';
        debugBtn.className = 'px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors shadow-md mr-2';
        debugBtn.innerHTML = '<i class="fas fa-bug mr-2"></i>Debug Form';
        debugBtn.addEventListener('click', function() {
            const formData = new FormData(form);
            console.log('Form Data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
        });
        
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.parentNode.insertBefore(debugBtn, submitBtn);
        }
    }
});
</script>
@endsection