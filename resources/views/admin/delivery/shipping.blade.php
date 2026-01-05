@extends('layouts.admin')

@section('content')
<div class="luxury-shipping-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-shipping-fast"
        title="Manajemen Ongkir"
        subtitle="Kelola tarif ongkos kirim"
        description="Atur tarif dasar, tarif per kilometer, dan pengaturan pengiriman lainnya"
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
        <div class="status-alert fade-in-up delay-100" data-aos="fade-down" style="background: #fee2e2; border-color: #fca5a5;">
            <div class="alert-content">
                <i class="fas fa-exclamation-circle alert-icon" style="color: #991b1b;"></i>
                <span class="alert-text" style="color: #991b1b;">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Modern Shipping Settings Form -->
    <div class="modern-shipping-container" data-aos="fade-up" data-aos-delay="200">
        <form method="POST" action="{{ route('admin.delivery.zones.create') }}" class="modern-shipping-form">
            @csrf
            
            <!-- Store Location Card -->
            <div class="modern-form-card" data-aos="fade-up" data-aos-delay="300">
                <div class="card-header-modern">
                    <div class="card-icon-modern">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="card-title-modern">
                        <h3>Lokasi Toko</h3>
                        <p>Klik pada peta untuk menentukan lokasi toko</p>
                    </div>
                </div>
                <div class="card-body-modern">
                    <div class="map-preview-modern">
                        <div class="map-container">
                            <div id="storeMap" class="modern-map"></div>
                            <div class="map-overlay-info">
                                <i class="fas fa-mouse-pointer"></i>
                                <span>Klik pada peta untuk menentukan lokasi toko</span>
                            </div>
                        </div>
                    </div>
                    <div class="coordinates-grid">
                        <div class="input-group-modern">
                            <label class="input-label-modern">
                                <i class="fas fa-globe"></i>
                                Latitude Toko *
                            </label>
                            <div class="input-wrapper-modern">
                                <input type="number" 
                                       name="store_lat" 
                                       step="any"
                                       value="{{ old('store_lat', $settings['store_lat'] ?? '-6.2088') }}" 
                                       class="modern-input @error('store_lat') error @enderror" 
                                       placeholder="-6.2088"
                                       required />
                            </div>
                            <small class="input-hint">Koordinat latitude lokasi toko</small>
                            @error('store_lat')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="input-group-modern">
                            <label class="input-label-modern">
                                <i class="fas fa-globe"></i>
                                Longitude Toko *
                            </label>
                            <div class="input-wrapper-modern">
                                <input type="number" 
                                       name="store_lng" 
                                       step="any"
                                       value="{{ old('store_lng', $settings['store_lng'] ?? '106.8456') }}" 
                                       class="modern-input @error('store_lng') error @enderror" 
                                       placeholder="106.8456"
                                       required />
                            </div>
                            <small class="input-hint">Koordinat longitude lokasi toko</small>
                            @error('store_lng')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Rates Card -->
            <div class="modern-form-card" data-aos="fade-up" data-aos-delay="400">
                <div class="card-header-modern">
                    <div class="card-icon-modern">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="card-title-modern">
                        <h3>Tarif Ongkir</h3>
                        <p>Atur biaya dasar dan tarif per kilometer</p>
                    </div>
                </div>
                <div class="card-body-modern">
                    <div class="modern-input-grid">
                        <div class="input-group-modern">
                            <label class="input-label-modern">
                                <i class="fas fa-coins"></i>
                                Tarif Dasar (Rp) *
                            </label>
                            <div class="input-wrapper-modern">
                                <span class="currency-prefix">Rp</span>
                                <input type="number" 
                                       name="shipping_base" 
                                       value="{{ old('shipping_base', $settings['shipping_base'] ?? 10000) }}" 
                                       class="modern-input @error('shipping_base') error @enderror" 
                                       min="0" 
                                       step="100"
                                       required />
                            </div>
                            <small class="input-hint">Biaya dasar ongkir yang dikenakan untuk setiap pengiriman</small>
                            @error('shipping_base')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="input-group-modern">
                            <label class="input-label-modern">
                                <i class="fas fa-route"></i>
                                Tarif per Kilometer (Rp) *
                            </label>
                            <div class="input-wrapper-modern">
                                <span class="currency-prefix">Rp</span>
                                <input type="number" 
                                       name="shipping_per_km" 
                                       value="{{ old('shipping_per_km', $settings['shipping_per_km'] ?? 2000) }}" 
                                       class="modern-input @error('shipping_per_km') error @enderror" 
                                       min="0" 
                                       step="100"
                                       required />
                            </div>
                            <small class="input-hint">Biaya tambahan untuk setiap kilometer jarak pengiriman</small>
                            @error('shipping_per_km')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Modern Calculator -->
                    <div class="modern-calculator">
                        <div class="calculator-header">
                            <i class="fas fa-calculator"></i>
                            <span>Kalkulator Ongkir</span>
                        </div>
                        <div class="calculator-body">
                            <div class="calculator-input-wrapper">
                                <i class="fas fa-ruler-combined calculator-icon"></i>
                                <input type="number" 
                                       id="calc_distance" 
                                       class="calculator-input" 
                                       placeholder="Masukkan jarak (KM)"
                                       step="0.1"
                                       min="0" />
                                <button type="button" class="calculator-btn" onclick="calculateShipping()">
                                    <i class="fas fa-equals"></i>
                                </button>
                            </div>
                            <div id="calculator-result" class="calculator-result-modern"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Limits & Free Shipping Card -->
            <div class="modern-form-card" data-aos="fade-up" data-aos-delay="500">
                <div class="card-header-modern">
                    <div class="card-icon-modern">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div class="card-title-modern">
                        <h3>Pengaturan Lainnya</h3>
                        <p>Batasan dan pengaturan gratis ongkir</p>
                    </div>
                </div>
                <div class="card-body-modern">
                    <div class="modern-input-grid">
                        <div class="input-group-modern">
                            <label class="input-label-modern">
                                <i class="fas fa-circle-notch"></i>
                                Radius Layanan (KM) *
                            </label>
                            <div class="input-wrapper-modern">
                                <input type="number" 
                                       name="shipping_radius" 
                                       value="{{ old('shipping_radius', $settings['shipping_radius'] ?? 50) }}" 
                                       class="modern-input @error('shipping_radius') error @enderror" 
                                       min="0" 
                                       step="1"
                                       required />
                                <span class="input-suffix">KM</span>
                            </div>
                            <small class="input-hint">Radius maksimal area layanan pengiriman</small>
                            @error('shipping_radius')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="input-group-modern">
                            <label class="input-label-modern">
                                <i class="fas fa-ruler"></i>
                                Maksimal Jarak (KM) *
                            </label>
                            <div class="input-wrapper-modern">
                                <input type="number" 
                                       name="max_shipping_distance" 
                                       value="{{ old('max_shipping_distance', $settings['max_shipping_distance'] ?? 100) }}" 
                                       class="modern-input @error('max_shipping_distance') error @enderror" 
                                       min="1" 
                                       step="1"
                                       required />
                                <span class="input-suffix">KM</span>
                            </div>
                            <small class="input-hint">Jarak maksimal yang masih bisa dilayani</small>
                            @error('max_shipping_distance')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="input-group-modern">
                            <label class="input-label-modern">
                                <i class="fas fa-gift"></i>
                                Min. Gratis Ongkir (Rp)
                            </label>
                            <div class="input-wrapper-modern">
                                <span class="currency-prefix">Rp</span>
                                <input type="number" 
                                       name="free_shipping_min" 
                                       value="{{ old('free_shipping_min', $settings['free_shipping_min'] ?? 0) }}" 
                                       class="modern-input @error('free_shipping_min') error @enderror" 
                                       min="0" 
                                       step="1000"
                                       placeholder="0 = Tidak ada gratis ongkir" />
                            </div>
                            <small class="input-hint">Isi 0 jika tidak ada gratis ongkir. Contoh: 100000 untuk gratis ongkir jika belanja minimal Rp 100.000</small>
                            @error('free_shipping_min')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Summary Card -->
            <div class="modern-form-card summary-card" data-aos="fade-up" data-aos-delay="600">
                <div class="card-header-modern">
                    <div class="card-icon-modern">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="card-title-modern">
                        <h3>Ringkasan Pengaturan</h3>
                        <p>Pengaturan ongkir saat ini</p>
                    </div>
                </div>
                <div class="card-body-modern">
                    <div class="modern-summary-grid">
                        <div class="summary-card-item">
                            <div class="summary-icon">
                                <i class="fas fa-coins"></i>
                            </div>
                            <div class="summary-content">
                                <span class="summary-label">Tarif Dasar</span>
                                <span class="summary-value">Rp {{ number_format($settings['shipping_base'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="summary-card-item">
                            <div class="summary-icon">
                                <i class="fas fa-route"></i>
                            </div>
                            <div class="summary-content">
                                <span class="summary-label">Tarif per KM</span>
                                <span class="summary-value">Rp {{ number_format($settings['shipping_per_km'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="summary-card-item">
                            <div class="summary-icon">
                                <i class="fas fa-circle-notch"></i>
                            </div>
                            <div class="summary-content">
                                <span class="summary-label">Radius Layanan</span>
                                <span class="summary-value">{{ $settings['shipping_radius'] ?? 0 }} KM</span>
                            </div>
                        </div>
                        <div class="summary-card-item">
                            <div class="summary-icon">
                                <i class="fas fa-ruler"></i>
                            </div>
                            <div class="summary-content">
                                <span class="summary-label">Maksimal Jarak</span>
                                <span class="summary-value">{{ $settings['max_shipping_distance'] ?? 0 }} KM</span>
                            </div>
                        </div>
                        <div class="summary-card-item">
                            <div class="summary-icon">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div class="summary-content">
                                <span class="summary-label">Min. Gratis Ongkir</span>
                                <span class="summary-value">
                                    @if(($settings['free_shipping_min'] ?? 0) > 0)
                                        Rp {{ number_format($settings['free_shipping_min'], 0, ',', '.') }}
                                    @else
                                        <span class="no-value">Tidak ada</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions-modern" data-aos="fade-up" data-aos-delay="700">
                <button type="submit" class="modern-action-btn primary">
                    <i class="fas fa-save"></i>
                    <span>Simpan Pengaturan</span>
                </button>
                <button type="reset" class="modern-action-btn secondary">
                    <i class="fas fa-redo"></i>
                    <span>Reset</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Luxury Shipping Page Styles */
.luxury-shipping-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
}

/* Modern Shipping Container */
.modern-shipping-container {
    margin: 2rem 0;
}

.modern-shipping-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Modern Form Card */
.modern-form-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(236, 72, 153, 0.08);
    border: 1px solid rgba(236, 72, 153, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.modern-form-card:hover {
    box-shadow: 0 8px 30px rgba(236, 72, 153, 0.12);
    transform: translateY(-2px);
}

.card-header-modern {
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.1) 0%, rgba(236, 72, 153, 0.05) 100%);
    padding: 1.5rem;
    border-bottom: 1px solid rgba(236, 72, 153, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-icon-modern {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
    flex-shrink: 0;
}

.card-title-modern h3 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0 0 0.25rem 0;
}

.card-title-modern p {
    font-size: 0.875rem;
    color: var(--gray-600);
    margin: 0;
}

.card-body-modern {
    padding: 1.5rem;
}

/* Modern Input Grid */
.modern-input-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.input-group-modern {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.input-label-modern {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.input-label-modern i {
    color: var(--primary-pink);
    font-size: 0.875rem;
}

.input-wrapper-modern {
    position: relative;
    display: flex;
    align-items: center;
}

.currency-prefix {
    position: absolute;
    left: 1rem;
    color: var(--gray-500);
    font-size: 0.875rem;
    font-weight: 500;
    z-index: 2;
    pointer-events: none;
}

.input-suffix {
    position: absolute;
    right: 1rem;
    color: var(--gray-500);
    font-size: 0.875rem;
    font-weight: 500;
    z-index: 2;
    pointer-events: none;
}

.modern-input {
    width: 100%;
    padding: 0.875rem 1rem;
    padding-left: 3rem;
    border: 2px solid rgba(236, 72, 153, 0.15);
    border-radius: 12px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.8);
    color: var(--gray-800);
}

.modern-input:focus {
    outline: none;
    border-color: var(--primary-pink);
    background: var(--pure-white);
    box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
}

.modern-input.error {
    border-color: #dc2626;
    box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
}

.modern-input::placeholder {
    color: var(--gray-400);
}

.input-hint {
    display: block;
    font-size: 0.75rem;
    color: var(--gray-500);
    margin-top: 0.25rem;
}

.input-error {
    color: #dc2626;
    font-size: 0.75rem;
    font-weight: 500;
    margin-top: 0.25rem;
}

/* Coordinates Grid */
.coordinates-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 1rem;
}

.map-preview-modern {
    margin-bottom: 1.5rem;
}

.map-container {
    position: relative;
    width: 100%;
    height: 400px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(236, 72, 153, 0.15);
    border: 2px solid rgba(236, 72, 153, 0.2);
    background: var(--gray-100);
}

.modern-map {
    width: 100%;
    height: 100%;
    border-radius: 14px;
}

.map-overlay-info {
    position: absolute;
    top: 1rem;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-700);
    z-index: 1000;
    border: 1px solid rgba(236, 72, 153, 0.2);
    pointer-events: none;
    animation: fadeInDown 0.5s ease-out;
}

.map-overlay-info i {
    color: var(--primary-pink);
    font-size: 1rem;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

/* Modern Calculator */
.modern-calculator {
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.05) 0%, rgba(236, 72, 153, 0.02) 100%);
    border-radius: 16px;
    border: 1px solid rgba(236, 72, 153, 0.1);
}

.calculator-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
}

.calculator-header i {
    color: var(--primary-pink);
    font-size: 1.125rem;
}

.calculator-body {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.calculator-input-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: white;
    border-radius: 12px;
    padding: 0.5rem;
    border: 2px solid rgba(236, 72, 153, 0.15);
    transition: all 0.3s ease;
}

.calculator-input-wrapper:focus-within {
    border-color: var(--primary-pink);
    box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
}

.calculator-icon {
    color: var(--primary-pink);
    font-size: 1rem;
    margin-left: 0.5rem;
}

.calculator-input {
    flex: 1;
    border: none;
    outline: none;
    padding: 0.75rem;
    font-size: 0.875rem;
    background: transparent;
    color: var(--gray-800);
}

.calculator-input::placeholder {
    color: var(--gray-400);
}

.calculator-btn {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    color: white;
    border: none;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
    font-size: 1rem;
}

.calculator-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
}

.calculator-result-modern {
    padding: 1.25rem;
    background: white;
    border-radius: 12px;
    border: 2px solid rgba(236, 72, 153, 0.15);
    display: none;
    transition: all 0.3s ease;
}

.calculator-result-modern.show {
    display: block;
    animation: slideDown 0.3s ease-out;
}

.calculator-result-modern.success {
    border-color: #10b981;
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modern Summary */
.modern-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}

.summary-card-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(236, 72, 153, 0.1);
    transition: all 0.3s ease;
}

.summary-card-item:hover {
    background: white;
    border-color: rgba(236, 72, 153, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.1);
}

.summary-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.1) 0%, rgba(236, 72, 153, 0.05) 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-pink);
    font-size: 1.125rem;
    flex-shrink: 0;
}

.summary-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
    min-width: 0;
}

.summary-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-800);
}

.summary-value .no-value {
    color: var(--gray-500);
    font-style: italic;
}

/* Form Actions Modern */
.form-actions-modern {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1rem;
}

.modern-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    white-space: nowrap;
}

.modern-action-btn i {
    font-size: 0.875rem;
}

.modern-action-btn.primary {
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    color: var(--pure-white);
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
}

.modern-action-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
}

.modern-action-btn.secondary {
    background: rgba(236, 72, 153, 0.1);
    color: var(--primary-pink);
}

.modern-action-btn.secondary:hover {
    background: rgba(236, 72, 153, 0.2);
    transform: translateY(-2px);
}

/* Modern Marker Styles */
.modern-store-marker {
    background: transparent !important;
    border: none !important;
}

.marker-pin {
    position: relative;
    width: 40px;
    height: 40px;
    animation: markerBounce 0.6s ease-out;
}

.marker-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    background: rgba(236, 72, 153, 0.3);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.marker-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.125rem;
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
    border: 3px solid white;
    z-index: 10;
    transition: all 0.3s ease;
}

.marker-icon:hover {
    transform: translate(-50%, -50%) scale(1.1);
    box-shadow: 0 6px 20px rgba(236, 72, 153, 0.5);
}

@keyframes markerBounce {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes pulse {
    0% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(2);
        opacity: 0;
    }
}

/* Modern Popup Styles */
.modern-popup-container {
    border-radius: 12px !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15) !important;
    border: none !important;
    padding: 0 !important;
    overflow: hidden;
}

.modern-popup {
    padding: 0;
    margin: 0;
}

.popup-header {
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    color: white;
    padding: 0.875rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
}

.popup-header i {
    font-size: 1rem;
}

.popup-body {
    padding: 1rem;
    background: white;
}

.popup-coords {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.coord-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 500;
}

.coord-value {
    font-size: 0.875rem;
    color: var(--gray-800);
    font-family: 'Courier New', monospace;
    font-weight: 600;
}

/* Leaflet Popup Close Button */
.leaflet-popup-close-button {
    color: white !important;
    font-size: 1.25rem !important;
    padding: 0.5rem !important;
    transition: all 0.3s ease !important;
}

.leaflet-popup-close-button:hover {
    background: rgba(255, 255, 255, 0.2) !important;
    border-radius: 50% !important;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .modern-input-grid {
        grid-template-columns: 1fr;
    }
    
    .coordinates-grid {
        grid-template-columns: 1fr;
    }
    
    .modern-summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .modern-shipping-container {
        margin: 1rem 0;
    }
    
    .card-header-modern {
        padding: 1rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .card-icon-modern {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .card-body-modern {
        padding: 1rem;
    }
    
    .card-title-modern h3 {
        font-size: 1rem;
    }
    
    .card-title-modern p {
        font-size: 0.8rem;
    }
    
    .modern-input-grid {
        grid-template-columns: 1fr;
    }
    
    .coordinates-grid {
        grid-template-columns: 1fr;
    }
    
    .modern-summary-grid {
        grid-template-columns: 1fr;
    }
    
    .map-container {
        height: 300px;
    }
    
    .map-overlay-info {
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        left: 1rem;
        right: 1rem;
        transform: none;
        width: calc(100% - 2rem);
    }
    
    .form-actions-modern {
        flex-direction: column;
    }
    
    .modern-action-btn {
        width: 100%;
        justify-content: center;
    }
    
    .calculator-input-wrapper {
        flex-wrap: wrap;
    }
    
    .calculator-btn {
        width: 100%;
        height: 44px;
    }
}
</style>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
let map;
let storeMarker;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const storeLat = parseFloat(document.querySelector('input[name="store_lat"]').value) || -6.2088;
    const storeLng = parseFloat(document.querySelector('input[name="store_lng"]').value) || 106.8456;
    
    map = L.map('storeMap', {
        zoomControl: true,
        scrollWheelZoom: true,
        doubleClickZoom: true,
        boxZoom: true,
        keyboard: true,
        dragging: true,
        touchZoom: true
    }).setView([storeLat, storeLng], 13);
    
    // Modern tile layer with better styling
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
        minZoom: 3
    }).addTo(map);
    
    // Create custom modern marker icon
    const modernIcon = L.divIcon({
        className: 'modern-store-marker',
        html: `
            <div class="marker-pin">
                <div class="marker-pulse"></div>
                <div class="marker-icon">
                    <i class="fas fa-store"></i>
                </div>
            </div>
        `,
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });
    
    // Add store marker (non-draggable)
    storeMarker = L.marker([storeLat, storeLng], {
        draggable: false,
        icon: modernIcon,
        zIndexOffset: 1000
    }).addTo(map);
    
    // Modern popup styling
    storeMarker.bindPopup(`
        <div class="modern-popup">
            <div class="popup-header">
                <i class="fas fa-store"></i>
                <strong>Lokasi Toko</strong>
            </div>
            <div class="popup-body">
                <div class="popup-coords">
                    <span class="coord-label">Koordinat:</span>
                    <span class="coord-value">${storeLat.toFixed(6)}, ${storeLng.toFixed(6)}</span>
                </div>
            </div>
        </div>
    `, {
        className: 'modern-popup-container',
        maxWidth: 250,
        closeButton: true
    });
    
    // Update marker position when map is clicked
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Update marker position with smooth animation
        storeMarker.setLatLng([lat, lng]);
        
        // Update input fields
        document.querySelector('input[name="store_lat"]').value = lat.toFixed(6);
        document.querySelector('input[name="store_lng"]').value = lng.toFixed(6);
        
        // Update popup
        storeMarker.setPopupContent(`
            <div class="modern-popup">
                <div class="popup-header">
                    <i class="fas fa-store"></i>
                    <strong>Lokasi Toko</strong>
                </div>
                <div class="popup-body">
                    <div class="popup-coords">
                        <span class="coord-label">Koordinat:</span>
                        <span class="coord-value">${lat.toFixed(6)}, ${lng.toFixed(6)}</span>
                    </div>
                </div>
            </div>
        `);
        
        // Open popup with animation
        storeMarker.openPopup();
        
        // Add visual feedback
        map.setView([lat, lng], map.getZoom(), {
            animate: true,
            duration: 0.3
        });
    });
    
    // Update marker when coordinates are changed manually
    document.querySelector('input[name="store_lat"]').addEventListener('change', updateMarker);
    document.querySelector('input[name="store_lng"]').addEventListener('change', updateMarker);
    
    // Hide overlay info after first click
    let overlayShown = true;
    map.once('click', function() {
        if (overlayShown) {
            const overlay = document.querySelector('.map-overlay-info');
            if (overlay) {
                overlay.style.opacity = '0';
                setTimeout(() => overlay.style.display = 'none', 300);
            }
            overlayShown = false;
        }
    });
});

function updateMarker() {
    const lat = parseFloat(document.querySelector('input[name="store_lat"]').value);
    const lng = parseFloat(document.querySelector('input[name="store_lng"]').value);
    
    if (!isNaN(lat) && !isNaN(lng)) {
        storeMarker.setLatLng([lat, lng]);
        map.setView([lat, lng], map.getZoom(), {
            animate: true,
            duration: 0.5
        });
        
        // Update popup
        storeMarker.setPopupContent(`
            <div class="modern-popup">
                <div class="popup-header">
                    <i class="fas fa-store"></i>
                    <strong>Lokasi Toko</strong>
                </div>
                <div class="popup-body">
                    <div class="popup-coords">
                        <span class="coord-label">Koordinat:</span>
                        <span class="coord-value">${lat.toFixed(6)}, ${lng.toFixed(6)}</span>
                    </div>
                </div>
            </div>
        `);
    }
}

function calculateShipping() {
    const distance = parseFloat(document.getElementById('calc_distance').value);
    const baseRate = parseFloat(document.querySelector('input[name="shipping_base"]').value) || 10000;
    const perKmRate = parseFloat(document.querySelector('input[name="shipping_per_km"]').value) || 2000;
    const resultDiv = document.getElementById('calculator-result');
    
    if (!distance || distance <= 0) {
        resultDiv.className = 'calculator-result-modern';
        resultDiv.innerHTML = '<p style="color: #dc2626; margin: 0; font-weight: 500;">Mohon masukkan jarak yang valid</p>';
        resultDiv.classList.add('show');
        return;
    }
    
    const shippingFee = baseRate + (distance * perKmRate);
    
    resultDiv.className = 'calculator-result-modern success show';
    resultDiv.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div style="flex: 1; min-width: 200px;">
                <p style="margin: 0; font-weight: 600; font-size: 0.875rem; color: #065f46;">Jarak: ${distance.toFixed(2)} KM</p>
                <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; color: #047857; opacity: 0.9;">
                    Tarif Dasar: Rp ${baseRate.toLocaleString('id-ID')} + 
                    (${distance.toFixed(2)} KM × Rp ${perKmRate.toLocaleString('id-ID')})
                </p>
            </div>
            <div style="text-align: right; flex-shrink: 0;">
                <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #065f46;">
                    Rp ${shippingFee.toLocaleString('id-ID')}
                </p>
            </div>
        </div>
    `;
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

