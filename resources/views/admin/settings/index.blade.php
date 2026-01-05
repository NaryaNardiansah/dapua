@extends('layouts.admin')

@section('content')
<div class="luxury-settings-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-cog"
        title="Pengaturan Sistem"
        subtitle="Kelola konfigurasi aplikasi"
        description="Ubah pengaturan umum dan email"
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

    <!-- Settings Form -->
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="settings-form">
        @csrf
        
        <!-- General Settings -->
        <x-admin-content-card 
            title="Pengaturan Umum"
            icon="fas fa-info-circle"
            :delay="200"
        >
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Situs *</label>
                    <input type="text" 
                           name="site_name" 
                           value="{{ old('site_name', $settings['site_name'] ?? '') }}" 
                           class="form-input @error('site_name') error @enderror" 
                           required />
                    @error('site_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deskripsi Situs *</label>
                    <textarea name="site_description" 
                              class="form-input @error('site_description') error @enderror" 
                              rows="3" 
                              required>{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                    @error('site_description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Situs *</label>
                    <input type="email" 
                           name="site_email" 
                           value="{{ old('site_email', $settings['site_email'] ?? '') }}" 
                           class="form-input @error('site_email') error @enderror" 
                           required />
                    @error('site_email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Telepon *</label>
                    <input type="text" 
                           name="site_phone" 
                           value="{{ old('site_phone', $settings['site_phone'] ?? '') }}" 
                           class="form-input @error('site_phone') error @enderror" 
                           required />
                    @error('site_phone')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Alamat *</label>
                    <textarea name="site_address" 
                              class="form-input @error('site_address') error @enderror" 
                              rows="2" 
                              required>{{ old('site_address', $settings['site_address'] ?? '') }}</textarea>
                    @error('site_address')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Logo Situs</label>
                    <input type="file" 
                           name="site_logo" 
                           accept="image/*" 
                           class="form-input @error('site_logo') error @enderror" />
                    @if(isset($settings['site_logo']) && $settings['site_logo'])
                        <div class="current-logo">
                            <img src="{{ $settings['site_logo'] }}" alt="Current Logo" style="max-width: 200px; margin-top: 10px;">
                        </div>
                    @endif
                    @error('site_logo')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </x-admin-content-card>

        <!-- System Settings -->
        <x-admin-content-card 
            title="Pengaturan Sistem"
            icon="fas fa-server"
            :delay="300"
        >
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Mode Maintenance</label>
                    <div class="toggle-container">
                        <label class="toggle-label">
                            <input type="checkbox" 
                                   name="maintenance_mode" 
                                   value="1" 
                                   class="toggle-input" 
                                   @checked(old('maintenance_mode', $settings['maintenance_mode'] ?? false)) />
                            <span class="toggle-slider"></span>
                            <span class="toggle-text">Aktifkan Mode Maintenance</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Registrasi Pengguna</label>
                    <div class="toggle-container">
                        <label class="toggle-label">
                            <input type="checkbox" 
                                   name="registration_enabled" 
                                   value="1" 
                                   class="toggle-input" 
                                   @checked(old('registration_enabled', $settings['registration_enabled'] ?? true)) />
                            <span class="toggle-slider"></span>
                            <span class="toggle-text">Izinkan Registrasi Baru</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Verifikasi Email Wajib</label>
                    <div class="toggle-container">
                        <label class="toggle-label">
                            <input type="checkbox" 
                                   name="email_verification_required" 
                                   value="1" 
                                   class="toggle-input" 
                                   @checked(old('email_verification_required', $settings['email_verification_required'] ?? false)) />
                            <span class="toggle-slider"></span>
                            <span class="toggle-text">Wajibkan Verifikasi Email</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Timezone *</label>
                    <select name="timezone" class="form-select @error('timezone') error @enderror" required>
                        <option value="Asia/Jakarta" @selected(old('timezone', $settings['timezone'] ?? 'Asia/Jakarta') === 'Asia/Jakarta')>Asia/Jakarta (WIB)</option>
                        <option value="Asia/Makassar" @selected(old('timezone', $settings['timezone'] ?? '') === 'Asia/Makassar')>Asia/Makassar (WITA)</option>
                        <option value="Asia/Jayapura" @selected(old('timezone', $settings['timezone'] ?? '') === 'Asia/Jayapura')>Asia/Jayapura (WIT)</option>
                    </select>
                    @error('timezone')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Maksimal Ukuran File (KB) *</label>
                    <input type="number" 
                           name="max_file_size" 
                           value="{{ old('max_file_size', $settings['max_file_size'] ?? 2048) }}" 
                           class="form-input @error('max_file_size') error @enderror" 
                           min="100" 
                           max="10240" 
                           required />
                    @error('max_file_size')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tipe File yang Diizinkan *</label>
                    <input type="text" 
                           name="allowed_file_types" 
                           value="{{ old('allowed_file_types', $settings['allowed_file_types'] ?? 'jpg,jpeg,png,gif') }}" 
                           class="form-input @error('allowed_file_types') error @enderror" 
                           placeholder="jpg,jpeg,png,gif" 
                           required />
                    @error('allowed_file_types')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </x-admin-content-card>

        <!-- Email Settings -->
        <x-admin-content-card 
            title="Pengaturan Email"
            icon="fas fa-envelope"
            :delay="400"
        >
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Email Notifikasi *</label>
                    <input type="email" 
                           name="notification_email" 
                           value="{{ old('notification_email', $settings['notification_email'] ?? '') }}" 
                           class="form-input @error('notification_email') error @enderror" 
                           required />
                    @error('notification_email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Support *</label>
                    <input type="email" 
                           name="support_email" 
                           value="{{ old('support_email', $settings['support_email'] ?? '') }}" 
                           class="form-input @error('support_email') error @enderror" 
                           required />
                    @error('support_email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Test Email</label>
                    <div class="test-email-container">
                        <input type="email" 
                               id="test_email" 
                               placeholder="Masukkan email untuk test" 
                               class="form-input" />
                        <button type="button" 
                                class="btn btn-secondary" 
                                onclick="testEmail()">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Test Email
                        </button>
                    </div>
                    <div id="test-email-result" class="test-email-result"></div>
                </div>
            </div>
        </x-admin-content-card>

        <!-- Social Media Settings -->
        <x-admin-content-card 
            title="Media Sosial"
            icon="fas fa-share-alt"
            :delay="500"
        >
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Facebook URL</label>
                    <input type="url" 
                           name="social_facebook" 
                           value="{{ old('social_facebook', $settings['social_media']['facebook'] ?? '') }}" 
                           class="form-input @error('social_facebook') error @enderror" />
                    @error('social_facebook')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" 
                           name="social_instagram" 
                           value="{{ old('social_instagram', $settings['social_media']['instagram'] ?? '') }}" 
                           class="form-input @error('social_instagram') error @enderror" />
                    @error('social_instagram')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Twitter URL</label>
                    <input type="url" 
                           name="social_twitter" 
                           value="{{ old('social_twitter', $settings['social_media']['twitter'] ?? '') }}" 
                           class="form-input @error('social_twitter') error @enderror" />
                    @error('social_twitter')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" 
                           name="social_whatsapp" 
                           value="{{ old('social_whatsapp', $settings['social_media']['whatsapp'] ?? '') }}" 
                           class="form-input @error('social_whatsapp') error @enderror" 
                           placeholder="Contoh: +6281234567890" />
                    @error('social_whatsapp')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </x-admin-content-card>

        <!-- System Actions -->
        <x-admin-content-card 
            title="Aksi Sistem"
            icon="fas fa-tools"
            :delay="600"
        >
            <div class="system-actions">
                <button type="button" 
                        class="btn btn-warning" 
                        onclick="clearCache()">
                    <i class="fas fa-broom mr-2"></i>Bersihkan Cache
                </button>
                
                <button type="button" 
                        class="btn btn-info" 
                        id="backup-database-btn"
                        onclick="backupDatabase()">
                    <i class="fas fa-database mr-2"></i>Backup Database
                </button>
            </div>
        </x-admin-content-card>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
            </button>
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-undo mr-2"></i>Reset
            </button>
        </div>
    </form>
</div>

<style>
/* Luxury Settings Page Styles */
.luxury-settings-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
    padding: 2rem 0;
}

.settings-form {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.form-input,
.form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    background: var(--pure-white);
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--primary-pink);
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

.form-input.error,
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

.form-section-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 1.5rem 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--gray-200);
}

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

.current-logo {
    margin-top: 0.5rem;
}

.test-email-container {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.test-email-result {
    margin-top: 0.5rem;
    padding: 0.75rem;
    border-radius: 8px;
    font-size: 0.875rem;
    display: none;
}

.test-email-result.success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
    display: block;
}

.test-email-result.error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
    display: block;
}

.system-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--gray-200);
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

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border: 1px solid #f59e0b;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
}

.btn-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: 1px solid #3b82f6;
}

.btn-info:hover {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

.mr-2 {
    margin-right: 0.5rem;
}

/* Backup Notification Styles */
.backup-notification {
    position: fixed;
    top: 2rem;
    right: 2rem;
    min-width: 400px;
    max-width: 500px;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    border: 2px solid rgba(236, 72, 153, 0.2);
    z-index: 10000;
    animation: slideInRight 0.3s ease-out;
    transition: all 0.3s ease;
}

.backup-notification.success {
    border-color: rgba(34, 197, 94, 0.3);
}

.backup-notification.error {
    border-color: rgba(220, 38, 38, 0.3);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.notification-content {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.backup-notification.success .notification-icon {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.1) 100%);
    color: #16a34a;
}

.backup-notification.error .notification-icon {
    background: linear-gradient(135deg, rgba(220, 38, 38, 0.15) 0%, rgba(220, 38, 38, 0.1) 100%);
    color: #dc2626;
}

.notification-body {
    flex: 1;
    min-width: 0;
}

.notification-message {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
}

.notification-details {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    margin-top: 0.5rem;
}

.detail-item {
    font-size: 0.75rem;
    color: var(--gray-600);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-item i {
    color: var(--primary-pink);
    width: 14px;
    text-align: center;
}

.notification-close {
    background: none;
    border: none;
    color: var(--gray-400);
    font-size: 1rem;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 6px;
    transition: all 0.3s ease;
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-close:hover {
    background: rgba(107, 114, 128, 0.1);
    color: var(--gray-600);
}

.notification-actions {
    padding: 0 1.25rem 1.25rem 1.25rem;
    border-top: 1px solid rgba(236, 72, 153, 0.1);
    margin-top: 0.5rem;
    padding-top: 1rem;
}

.btn-download-backup {
    display: inline-flex;
    align-items: center;
    padding: 0.625rem 1.25rem;
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    color: white;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
    width: 100%;
    justify-content: center;
}

.btn-download-backup:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
    color: white;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .test-email-container {
        flex-direction: column;
    }
    
    .system-actions {
        flex-direction: column;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
function testEmail() {
    const email = document.getElementById('test_email').value;
    const resultDiv = document.getElementById('test-email-result');
    
    if (!email) {
        resultDiv.className = 'test-email-result error';
        resultDiv.textContent = 'Mohon masukkan email terlebih dahulu.';
        return;
    }
    
    resultDiv.className = 'test-email-result';
    resultDiv.textContent = 'Mengirim email...';
    resultDiv.style.display = 'block';
    
    fetch('{{ route("admin.settings.test-email") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ test_email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.className = 'test-email-result success';
            resultDiv.textContent = data.message;
        } else {
            resultDiv.className = 'test-email-result error';
            resultDiv.textContent = data.message;
        }
    })
    .catch(error => {
        resultDiv.className = 'test-email-result error';
        resultDiv.textContent = 'Error: ' + error.message;
    });
}

function clearCache() {
    if (!confirm('Apakah Anda yakin ingin membersihkan cache?')) {
        return;
    }
    
    fetch('{{ route("admin.settings.clear-cache") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function backupDatabase() {
    if (!confirm('Apakah Anda yakin ingin membuat backup database?\n\nProses ini mungkin memakan waktu beberapa saat.')) {
        return;
    }
    
    const btn = document.getElementById('backup-database-btn');
    const originalHTML = btn.innerHTML;
    
    // Show loading state
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Membuat Backup...';
    
    fetch('{{ route("admin.settings.backup-database") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
        
        if (data.success) {
            const downloadUrl = data.download_url || `/admin/settings/backup-database/${data.filename}/download`;
            showBackupNotification('success', data.message, data.filename, data.size, downloadUrl);
        } else {
            showBackupNotification('error', data.message || 'Gagal membuat backup database');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
        showBackupNotification('error', 'Error: ' + error.message);
    });
}

function showBackupNotification(type, message, filename = null, size = null, downloadUrl = null) {
    // Remove existing notification if any
    const existingNotification = document.getElementById('backup-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.id = 'backup-notification';
    notification.className = `backup-notification ${type}`;
    
    let content = `
        <div class="notification-content">
            <div class="notification-icon">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            </div>
            <div class="notification-body">
                <div class="notification-message">${message}</div>
    `;
    
    if (type === 'success' && filename) {
        content += `
                <div class="notification-details">
                    <span class="detail-item"><i class="fas fa-file"></i> ${filename}</span>
                    ${size ? `<span class="detail-item"><i class="fas fa-weight"></i> ${size}</span>` : ''}
                </div>
        `;
    }
    
    content += `
            </div>
            <button class="notification-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    if (type === 'success' && downloadUrl) {
        content += `
        <div class="notification-actions">
            <a href="${downloadUrl}" class="btn-download-backup" download>
                <i class="fas fa-download mr-2"></i>Download Backup
            </a>
        </div>
        `;
    }
    
    notification.innerHTML = content;
    
    // Insert at the top of the page
    const pageContent = document.querySelector('.luxury-settings-page');
    if (pageContent) {
        pageContent.insertBefore(notification, pageContent.firstChild);
    } else {
        document.body.insertBefore(notification, document.body.firstChild);
    }
    
    // Auto remove after 10 seconds for success, 5 seconds for error
    setTimeout(() => {
        if (notification && notification.parentElement) {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => notification.remove(), 300);
        }
    }, type === 'success' ? 10000 : 5000);
}
</script>

<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        once: true,
        mirror: false
    });
});
</script>
@endsection
