@extends('layouts.admin')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="luxury-driver-form-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-user-edit"
        title="Edit Driver"
        subtitle="Manajemen Driver"
        description="Perbarui informasi driver pengiriman"
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

    <!-- Action Buttons -->
    <div class="action-buttons-section fade-in-up delay-200" data-aos="fade-up">
        <a href="{{ route('admin.driver.index') }}" class="action-btn secondary">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Driver
        </a>
        <a href="{{ route('admin.driver.show', $driver) }}" class="action-btn info">
            <i class="fas fa-eye mr-2"></i>Lihat Detail
        </a>
    </div>

    <!-- Form Container -->
    <div class="form-container mt-8 fade-in-up delay-300" data-aos="fade-up">
        <form action="{{ route('admin.driver.update', $driver) }}" 
              method="post" 
              enctype="multipart/form-data" 
              class="luxury-form">
            @csrf
            @method('PUT')
            
            <!-- Informasi Dasar -->
            <x-admin-content-card 
                title="Informasi Dasar" 
                icon="fas fa-info-circle" 
                :delay="400"
            >
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Nama Driver <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input name="name" 
                                   value="{{ old('name', $driver->name) }}" 
                                   placeholder="Masukkan nama driver" 
                                   class="form-input" 
                                   required />
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', $driver->email) }}" 
                                   placeholder="Masukkan email driver" 
                                   class="form-input" 
                                   required />
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Telepon <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="tel" 
                                   name="phone" 
                                   value="{{ old('phone', $driver->phone) }}" 
                                   placeholder="Masukkan nomor telepon" 
                                   class="form-input" 
                                   required />
                            <i class="fas fa-phone input-icon"></i>
                        </div>
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <div class="input-wrapper">
                            <input type="password" 
                                   name="password" 
                                   placeholder="Kosongkan jika tidak ingin mengubah" 
                                   class="form-input" />
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                        <p class="form-help">Kosongkan jika tidak ingin mengubah password</p>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <input type="password" 
                               name="password_confirmation" 
                               placeholder="Konfirmasi password baru" 
                               class="form-input" />
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
            </x-admin-content-card>

            <!-- Informasi Kendaraan -->
            <x-admin-content-card 
                title="Informasi Kendaraan" 
                icon="fas fa-car" 
                :delay="500"
            >
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Jenis Kendaraan <span class="required">*</span>
                        </label>
                        <div class="select-wrapper">
                            <select name="vehicle_type" class="form-select" required>
                                <option value="">Pilih Jenis Kendaraan</option>
                                <option value="Motor" {{ old('vehicle_type', $driver->vehicle_type) == 'Motor' ? 'selected' : '' }}>Motor</option>
                                <option value="Mobil" {{ old('vehicle_type', $driver->vehicle_type) == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                <option value="Truk" {{ old('vehicle_type', $driver->vehicle_type) == 'Truk' ? 'selected' : '' }}>Truk</option>
                                <option value="Sepeda" {{ old('vehicle_type', $driver->vehicle_type) == 'Sepeda' ? 'selected' : '' }}>Sepeda</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                        @error('vehicle_type')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Nomor Kendaraan <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input name="vehicle_number" 
                                   value="{{ old('vehicle_number', $driver->vehicle_number) }}" 
                                   placeholder="Contoh: B 1234 XYZ" 
                                   class="form-input" 
                                   required />
                            <i class="fas fa-car-side input-icon"></i>
                        </div>
                        @error('vehicle_number')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        Nomor SIM <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <input name="license_number" 
                               value="{{ old('license_number', $driver->license_number ?? $driver->driver_license) }}" 
                               placeholder="Masukkan nomor SIM" 
                               class="form-input" 
                               required />
                        <i class="fas fa-id-card input-icon"></i>
                    </div>
                    @error('license_number')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </x-admin-content-card>

            <!-- Pengaturan Status -->
            <x-admin-content-card 
                title="Pengaturan Status" 
                icon="fas fa-cog" 
                :delay="600"
            >
                <div class="status-options">
                    <h4 class="status-title">Status Driver</h4>
                    <div class="status-grid">
                        <label class="status-option">
                            <input type="checkbox" 
                                   name="is_available" 
                                   value="1" 
                                   {{ old('is_available', $driver->is_available) ? 'checked' : '' }} 
                                   class="status-checkbox" />
                            <div class="status-content">
                                <div class="status-icon available">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="status-text">
                                    <span class="status-name">Tersedia</span>
                                    <span class="status-desc">Driver siap menerima pesanan</span>
                                </div>
                            </div>
                        </label>
                        
                        <label class="status-option">
                            <input type="checkbox" 
                                   name="is_blocked" 
                                   value="1" 
                                   {{ old('is_blocked', $driver->is_blocked) ? 'checked' : '' }} 
                                   class="status-checkbox" />
                            <div class="status-content">
                                <div class="status-icon blocked">
                                    <i class="fas fa-ban"></i>
                                </div>
                                <div class="status-text">
                                    <span class="status-name">Diblokir</span>
                                    <span class="status-desc">Blokir akses driver</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </x-admin-content-card>

            <!-- Form Actions -->
            <div class="form-actions fade-in-up delay-700" data-aos="fade-up">
                <div class="actions-container">
                    <a href="{{ route('admin.driver.index') }}" class="action-btn secondary">
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
.luxury-driver-form-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
}

/* Action Buttons */
.action-buttons-section {
    margin-bottom: 2rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
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

.action-btn.info {
    background: #3b82f6;
    color: white;
}

.action-btn.info:hover {
    background: #2563eb;
    transform: translateY(-2px);
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
    cursor: pointer;
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

.status-icon.available {
    background: #d1fae5;
    color: #065f46;
}

.status-icon.blocked {
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
            const password = form.querySelector('input[name="password"]').value;
            const passwordConfirmation = form.querySelector('input[name="password_confirmation"]').value;
            
            if (password && password !== passwordConfirmation) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
            
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

