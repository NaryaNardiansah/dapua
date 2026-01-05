@extends('layouts.admin')

@section('content')
<div class="luxury-driver-create-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-user-plus"
        title="Tambah Driver Baru"
        subtitle="Daftarkan driver baru ke sistem"
        description="Tambahkan driver baru dengan informasi lengkap untuk melayani pelanggan"
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

    <!-- Quick Actions -->
    <div class="quick-actions-bar fade-in-up delay-200" data-aos="fade-up">
        <div class="actions-container">
            <a href="{{ route('admin.driver.index') }}" class="action-btn secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Driver
            </a>
            
            <div class="action-buttons">
                <a href="{{ route('admin.users.index') }}" class="action-btn info">
                    <i class="fas fa-users mr-2"></i>Daftar Pengguna
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="form-container fade-in-up delay-300" data-aos="fade-up">
        <div class="form-header">
            <h2 class="form-title">
                <i class="fas fa-truck"></i>
                Form Tambah Driver
            </h2>
            <p class="form-description">Isi informasi driver baru dengan lengkap dan akurat</p>
        </div>
        
        <form method="POST" action="{{ route('admin.driver.store') }}" class="responsive-form" enctype="multipart/form-data">
            @csrf
        <!-- Basic Information -->
        <div class="form-group full-width">
            <label class="form-label">Informasi Dasar</label>
            <div class="form-section">
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               class="form-input @error('name') error @enderror" 
                               placeholder="Masukkan nama lengkap driver" 
                               required />
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-col">
                        <label class="form-label">Email *</label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               class="form-input @error('email') error @enderror" 
                               placeholder="Masukkan email driver" 
                               required />
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Password *</label>
                        <input type="password" 
                               name="password" 
                               class="form-input @error('password') error @enderror" 
                               placeholder="Masukkan password minimal 8 karakter" 
                               required />
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-col">
                        <label class="form-label">Konfirmasi Password *</label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="form-input @error('password_confirmation') error @enderror" 
                               placeholder="Ulangi password yang sama" 
                               required />
                        @error('password_confirmation')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="tel" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               class="form-input @error('phone') error @enderror" 
                               placeholder="Masukkan nomor telepon" />
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-col">
                        <label class="form-label">Foto Driver</label>
                        <div class="image-upload-container">
                            <input type="file" 
                                   name="photo" 
                                   id="photo" 
                                   class="image-upload-input @error('photo') error @enderror" 
                                   accept="image/*" />
                            <label for="photo" class="image-upload-label">
                                <div class="upload-content">
                                    <i class="fas fa-camera upload-icon"></i>
                                    <span class="upload-text">Pilih Foto</span>
                                    <span class="upload-hint">JPG, PNG, maksimal 2MB</span>
                                </div>
                            </label>
                        </div>
                        @error('photo')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="form-group full-width">
            <label class="form-label">Informasi Kendaraan</label>
            <div class="form-section">
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Tipe Kendaraan</label>
                        <select name="vehicle_type" class="form-select @error('vehicle_type') error @enderror">
                            <option value="">Pilih Tipe Kendaraan</option>
                            <option value="Mobil" @selected(old('vehicle_type') === 'Mobil')>Mobil</option>
                            <option value="Motor" @selected(old('vehicle_type') === 'Motor')>Motor</option>
                            <option value="Truk" @selected(old('vehicle_type') === 'Truk')>Truk</option>
                            <option value="Van" @selected(old('vehicle_type') === 'Van')>Van</option>
                        </select>
                        @error('vehicle_type')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-col">
                        <label class="form-label">Nomor Kendaraan</label>
                        <input type="text" 
                               name="vehicle_number" 
                               value="{{ old('vehicle_number') }}" 
                               class="form-input @error('vehicle_number') error @enderror" 
                               placeholder="Contoh: B-1234-ABC" />
                        @error('vehicle_number')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Nomor SIM</label>
                        <input type="text" 
                               name="license_number" 
                               value="{{ old('license_number') }}" 
                               class="form-input @error('license_number') error @enderror" 
                               placeholder="Contoh: SIM-A-123456789" />
                        @error('license_number')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-col">
                        <label class="form-label">Nomor Lisensi Driver</label>
                        <input type="text" 
                               name="driver_license" 
                               value="{{ old('driver_license') }}" 
                               class="form-input @error('driver_license') error @enderror" 
                               placeholder="Masukkan nomor lisensi driver" />
                        @error('driver_license')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Settings -->
        <div class="form-group full-width">
            <label class="form-label">Pengaturan Status</label>
            <div class="form-section">
                <div class="form-row">
                    <div class="form-col">
                        <div class="toggle-container">
                            <label class="toggle-label">
                                <input type="checkbox" 
                                       name="is_blocked" 
                                       value="1" 
                                       class="toggle-input" 
                                       @checked(old('is_blocked')) />
                                <span class="toggle-slider"></span>
                                <span class="toggle-text">Blokir Driver</span>
                            </label>
                            <p class="toggle-description">Driver yang diblokir tidak dapat mengakses sistem</p>
                        </div>
                    </div>
                    
                    <div class="form-col">
                        <div class="toggle-container">
                            <label class="toggle-label">
                                <input type="checkbox" 
                                       name="is_available" 
                                       value="1" 
                                       class="toggle-input" 
                                       @checked(old('is_available', true)) />
                                <span class="toggle-slider"></span>
                                <span class="toggle-text">Tersedia</span>
                            </label>
                            <p class="toggle-description">Driver yang tersedia dapat menerima pesanan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Information -->
        <div class="form-group full-width">
            <label class="form-label">Informasi Lokasi</label>
            <div class="form-section">
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Latitude</label>
                        <input type="number" 
                               step="any" 
                               name="current_latitude" 
                               value="{{ old('current_latitude') }}" 
                               class="form-input @error('current_latitude') error @enderror" 
                               placeholder="Contoh: -6.200000" />
                        @error('current_latitude')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-col">
                        <label class="form-label">Longitude</label>
                        <input type="number" 
                               step="any" 
                               name="current_longitude" 
                               value="{{ old('current_longitude') }}" 
                               class="form-input @error('current_longitude') error @enderror" 
                               placeholder="Contoh: 106.816666" />
                        @error('current_longitude')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="location-help">
                            <div class="help-content">
                                <i class="fas fa-info-circle help-icon"></i>
                                <div class="help-text">
                                    <h4 class="help-title">Informasi Lokasi</h4>
                                    <p class="help-description">
                                        Koordinat GPS akan digunakan untuk tracking lokasi driver. 
                                        Driver dapat mengupdate lokasi mereka melalui aplikasi mobile.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>Simpan Driver
            </button>
            <a href="{{ route('admin.driver.index') }}" class="btn btn-secondary">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>
</div>

<style>
/* Luxury Driver Create Page Styles */
.luxury-driver-create-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
}

/* Form Container */
.form-container {
    background: var(--pure-white);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 8px 32px rgba(236, 72, 153, 0.1);
    border: 1px solid rgba(236, 72, 153, 0.1);
    margin-bottom: 2rem;
}

.form-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--gray-200);
}

.form-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0 0 0.5rem 0;
}

.form-title i {
    color: var(--primary-pink);
    font-size: 1.5rem;
}

.form-description {
    color: var(--gray-600);
    margin: 0;
    font-size: 0.875rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--gray-200);
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    color: white;
    border: 1px solid var(--primary-pink);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--secondary-pink) 0%, var(--primary-pink) 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
}

.btn-secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.2);
}

.btn-secondary:hover {
    background: rgba(107, 114, 128, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 114, 128, 0.2);
}

/* Quick Actions Bar */
.quick-actions-bar {
    margin: 2rem 0;
}

.actions-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-radius: 16px;
    padding: 1.5rem 2rem;
    box-shadow: 0 8px 32px rgba(236, 72, 153, 0.1);
    border: 1px solid rgba(236, 72, 153, 0.1);
}

.action-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.action-btn.secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.2);
}

.action-btn.secondary:hover {
    background: rgba(107, 114, 128, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 114, 128, 0.2);
}

.action-btn.info {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.action-btn.info:hover {
    background: rgba(59, 130, 246, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
}

.action-buttons {
    display: flex;
    gap: 1rem;
    align-items: center;
}

/* Form Styles */
.form-group.full-width {
    grid-column: 1 / -1;
}

.form-section {
    background: rgba(255, 255, 255, 0.8);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid rgba(236, 72, 153, 0.1);
    margin-top: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-row:last-child {
    margin-bottom: 0;
}

.form-col {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    display: block;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    background: var(--pure-white);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-pink);
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

.form-input.error {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    background: var(--pure-white);
    cursor: pointer;
}

.form-select:focus {
    outline: none;
    border-color: var(--primary-pink);
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

.form-select.error {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-error {
    color: #dc2626;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    font-weight: 500;
}

/* Toggle Switch */
.toggle-container {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.toggle-label {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    user-select: none;
}

.toggle-input {
    display: none;
}

.toggle-slider {
    position: relative;
    width: 50px;
    height: 26px;
    background: var(--gray-300);
    border-radius: 13px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.toggle-slider::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 22px;
    height: 22px;
    background: var(--pure-white);
    border-radius: 50%;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.toggle-input:checked + .toggle-slider {
    background: var(--primary-pink);
}

.toggle-input:checked + .toggle-slider::before {
    transform: translateX(24px);
}

.toggle-text {
    font-weight: 600;
    color: var(--gray-700);
}

.toggle-description {
    font-size: 0.75rem;
    color: var(--gray-500);
    margin: 0;
    margin-left: 66px;
}

/* Image Upload */
.image-upload-container {
    position: relative;
}

.image-upload-input {
    display: none;
}

.image-upload-label {
    display: block;
    cursor: pointer;
    border: 2px dashed var(--gray-300);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    background: rgba(236, 72, 153, 0.05);
}

.image-upload-label:hover {
    border-color: var(--primary-pink);
    background: rgba(236, 72, 153, 0.1);
}

.upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.upload-icon {
    font-size: 2rem;
    color: var(--primary-pink);
}

.upload-text {
    font-weight: 600;
    color: var(--gray-700);
}

.upload-hint {
    font-size: 0.75rem;
    color: var(--gray-500);
}

/* Location Help */
.location-help {
    grid-column: 1 / -1;
}

.help-content {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(59, 130, 246, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(59, 130, 246, 0.1);
}

.help-icon {
    font-size: 1.5rem;
    color: #2563eb;
    margin-top: 0.25rem;
}

.help-text {
    flex: 1;
}

.help-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0 0 0.5rem 0;
}

.help-description {
    font-size: 0.875rem;
    color: var(--gray-600);
    margin: 0;
    line-height: 1.5;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .actions-container {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .action-buttons {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-section {
        padding: 1rem;
    }
    
    .actions-container {
        padding: 1rem;
    }
    
    .action-btn {
        padding: 0.625rem 1.25rem;
        font-size: 0.8rem;
    }
    
    .help-content {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .form-section {
        padding: 0.75rem;
    }
    
    .form-input,
    .form-select {
        padding: 0.625rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .toggle-slider {
        width: 45px;
        height: 24px;
    }
    
    .toggle-slider::before {
        width: 20px;
        height: 20px;
    }
    
    .toggle-input:checked + .toggle-slider::before {
        transform: translateX(21px);
    }
    
    .image-upload-label {
        padding: 1.5rem;
    }
    
    .upload-icon {
        font-size: 1.5rem;
    }
    
    .help-content {
        padding: 1rem;
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

    // Image preview functionality
    const imageInput = document.getElementById('photo');
    const imageLabel = document.querySelector('.image-upload-label');
    
    if (imageInput && imageLabel) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview
                    const preview = document.createElement('div');
                    preview.className = 'image-preview';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="preview-image">
                        <span class="preview-text">Preview</span>
                    `;
                    
                    // Remove existing preview
                    const existingPreview = imageLabel.querySelector('.image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    // Add new preview
                    imageLabel.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('error');
                    isValid = false;
                } else {
                    field.classList.remove('error');
                }
            });
            
            // Password confirmation validation
            const password = form.querySelector('input[name="password"]');
            const passwordConfirmation = form.querySelector('input[name="password_confirmation"]');
            
            if (password && passwordConfirmation) {
                if (password.value !== passwordConfirmation.value) {
                    passwordConfirmation.classList.add('error');
                    isValid = false;
                } else {
                    passwordConfirmation.classList.remove('error');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi dan pastikan password konfirmasi sama.');
            }
        });
    }

    // Real-time password confirmation
    const password = document.querySelector('input[name="password"]');
    const passwordConfirmation = document.querySelector('input[name="password_confirmation"]');
    
    if (password && passwordConfirmation) {
        passwordConfirmation.addEventListener('input', function() {
            if (this.value && password.value !== this.value) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });
    }
});
</script>
@endsection








