@extends('layouts.admin')

@section('content')
<div class="luxury-category-form-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-folder-plus"
        title="Tambah Kategori Baru"
        subtitle="Manajemen Kategori"
        description="Buat kategori baru untuk mengorganisir produk Dapur Sakura"
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
    <div class="action-buttons-section fade-in-up delay-200" data-aos="fade-up">
        <a href="{{ route('admin.categories.index') }}" class="action-btn secondary">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Kategori
        </a>
		</div>

    <!-- Form Container -->
    <div class="form-container mt-8 fade-in-up delay-300" data-aos="fade-up">
        <form action="{{ route('admin.categories.store') }}" 
              method="post" 
              enctype="multipart/form-data" 
              class="luxury-form">
			@csrf
			
            <!-- Informasi Dasar -->
            <x-admin-content-card 
                title="Informasi Dasar" 
                icon="fas fa-info-circle" 
                :delay="400"
            >
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Nama Kategori <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Masukkan nama kategori" 
                                   class="form-input" 
                                   required />
                            <i class="fas fa-tag input-icon"></i>
                        </div>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Induk Kategori</label>
                        <div class="input-wrapper">
                            <select name="parent_id" class="form-input">
                                <option value="">Tanpa Induk (Kategori Utama)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-folder input-icon"></i>
                        </div>
                        @error('parent_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Urutan Tampilan</label>
                        <div class="input-wrapper">
                            <input type="number" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', 0) }}" 
                                   min="0" 
                                   class="form-input" />
                            <i class="fas fa-sort-numeric-down input-icon"></i>
                        </div>
                        @error('sort_order')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
						</div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <div class="textarea-wrapper">
                        <textarea name="description" 
                                  rows="3" 
                                  placeholder="Deskripsi kategori (opsional)" 
                                  class="form-textarea">{{ old('description') }}</textarea>
							</div>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
						</div>

                <div class="form-group">
                    <label class="form-label">Warna Kategori</label>
                    <div class="color-picker-wrapper">
                        <div class="color-input-group">
                            <input type="color" 
                                   name="color" 
                                   value="{{ old('color', '#ec4899') }}" 
                                   class="color-picker" 
                                   id="colorPicker" />
                            <div class="input-wrapper">
                                <input type="text" 
                                       name="color_text" 
                                       value="{{ old('color', '#ec4899') }}" 
                                       placeholder="#ec4899" 
                                       class="form-input color-text-input" 
                                       id="colorText" 
                                       pattern="^#[0-9A-F]{6}$" />
                                <i class="fas fa-palette input-icon"></i>
						</div>
					</div>
                        <p class="form-help">Pilih warna untuk kategori ini</p>
                    </div>
                    @error('color')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
				</div>
            </x-admin-content-card>

				<!-- Media & Visual -->
            <x-admin-content-card 
                title="Media & Visual" 
                icon="fas fa-images" 
                :delay="500"
            >
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Gambar Kategori</label>
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
                                    <span class="upload-title">Pilih Gambar</span>
                                    <span class="upload-subtitle">JPG, PNG, GIF. Maksimal 2MB</span>
                                </div>
                            </label>
                        </div>
                        @error('image')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
						</div>

                    <div class="form-group">
                        <label class="form-label">Banner Kategori</label>
                        <div class="file-upload-wrapper">
                            <input type="file" 
                                   name="banner" 
                                   accept="image/*" 
                                   class="file-input" 
                                   id="banner-upload" />
                            <label for="banner-upload" class="file-upload-label">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
						</div>
                                <div class="upload-text">
                                    <span class="upload-title">Pilih Banner</span>
                                    <span class="upload-subtitle">JPG, PNG, GIF. Maksimal 5MB</span>
						</div>
                            </label>
					</div>
                        @error('banner')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
				</div>
			</div>

                <div class="form-group">
                    <label class="form-label">Icon Kategori</label>
                    <div class="file-upload-wrapper">
                        <input type="file" 
                               name="icon" 
                               accept="image/*" 
                               class="file-input" 
                               id="icon-upload" />
                        <label for="icon-upload" class="file-upload-label">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="upload-text">
                                <span class="upload-title">Pilih Icon</span>
                                <span class="upload-subtitle">JPG, PNG, GIF. Maksimal 1MB</span>
                            </div>
                        </label>
                    </div>
                    @error('icon')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </x-admin-content-card>

            <!-- Pengaturan -->
            <x-admin-content-card 
                title="Pengaturan" 
                icon="fas fa-cog" 
                :delay="600"
            >
                <div class="status-options">
                    <h4 class="status-title">Status Kategori</h4>
                    <div class="status-grid">
                        <label class="status-option">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', 1) ? 'checked' : '' }} 
                                   class="status-checkbox" />
                            <div class="status-content">
                                <div class="status-icon active">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="status-text">
                                    <span class="status-name">Aktif</span>
                                    <span class="status-desc">Kategori dapat dilihat pengguna</span>
                                </div>
					</div>
                        </label>
                        
                        <label class="status-option">
                            <input type="checkbox" 
                                   name="is_featured" 
                                   value="1" 
                                   {{ old('is_featured') ? 'checked' : '' }} 
                                   class="status-checkbox" />
                            <div class="status-content">
                                <div class="status-icon featured">
                                    <i class="fas fa-gem"></i>
                                </div>
                                <div class="status-text">
                                    <span class="status-name">Featured</span>
                                    <span class="status-desc">Kategori unggulan</span>
                                </div>
					</div>
                        </label>
                        
                        <label class="status-option">
                            <input type="checkbox" 
                                   name="is_trending" 
                                   value="1" 
                                   {{ old('is_trending') ? 'checked' : '' }} 
                                   class="status-checkbox" />
                            <div class="status-content">
                                <div class="status-icon trending">
                                    <i class="fas fa-fire"></i>
                                </div>
                                <div class="status-text">
                                    <span class="status-name">Trending</span>
                                    <span class="status-desc">Kategori populer</span>
					</div>
                            </div>
                        </label>
				</div>
			</div>
            </x-admin-content-card>

            <!-- Form Actions -->
            <div class="form-actions fade-in-up delay-700" data-aos="fade-up">
                <div class="actions-container">
                    <a href="{{ route('admin.categories.index') }}" class="action-btn secondary">
					<i class="fas fa-times mr-2"></i>Batal
				</a>
                    <button type="submit" class="action-btn primary">
					<i class="fas fa-save mr-2"></i>Simpan Kategori
				</button>
                </div>
			</div>
		</form>
	</div>
</div>

<!-- Custom Styles -->
<style>
.luxury-category-form-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
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
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
}

.action-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
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

/* Color Picker */
.color-picker-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.color-input-group {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.color-picker {
    width: 80px;
    height: 50px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.color-picker:hover {
    border-color: #ec4899;
    transform: scale(1.05);
}

.color-text-input {
    flex: 1;
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
    font-family: inherit;
}

.form-textarea:focus {
    outline: none;
    border-color: #ec4899;
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

/* File Upload */
.file-upload-wrapper {
    margin-top: 0.5rem;
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

/* Status Options */
.status-options {
    margin-top: 1rem;
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

.status-icon.featured {
    background: #e0e7ff;
    color: #3730a3;
}

.status-icon.trending {
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

@media (max-width: 768px) {
    .actions-container {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
        justify-content: center;
    }
    
    .status-grid {
        grid-template-columns: 1fr;
    }
    
    .color-input-group {
        flex-direction: column;
    }
    
    .color-picker {
        width: 100%;
    }
}
</style>

<!-- JavaScript -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        mirror: false,
    });

		// Color picker synchronization
    const colorPicker = document.getElementById('colorPicker');
    const colorText = document.getElementById('colorText');
			
			if (colorPicker && colorText) {
				colorPicker.addEventListener('change', function() {
					colorText.value = this.value;
				});
				
				colorText.addEventListener('change', function() {
					if (this.value.match(/^#[0-9A-F]{6}$/i)) {
						colorPicker.value = this.value;
					}
				});
        
        colorText.addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                colorPicker.value = this.value;
            }
        });
    }

    // Handle file upload preview
    const fileInputs = ['image-upload', 'banner-upload', 'icon-upload'];
    
    fileInputs.forEach(inputId => {
        const fileInput = document.getElementById(inputId);
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const uploadLabel = this.nextElementSibling;
                    const uploadTitle = uploadLabel.querySelector('.upload-title');
                    
                    if (uploadTitle) {
                        uploadTitle.textContent = file.name;
                    }
                }
            });
        }
    });

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

    // Handle form validation
    const form = document.querySelector('.luxury-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let firstErrorField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ef4444';
                    
                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                } else {
                    field.style.borderColor = '#e5e7eb';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                }
            }
        });
			}
		});
	</script>
@endsection
