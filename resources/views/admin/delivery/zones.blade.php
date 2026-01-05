@extends('layouts.admin')

@section('content')
<div class="luxury-zones-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-map-marked-alt"
        title="Manajemen Zona Pengiriman"
        subtitle="Kelola zona pengiriman dan tarif"
        description="Atur zona pengiriman, tarif dasar, dan tarif per kilometer untuk setiap zona"
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

    <!-- Quick Stats Bar -->
    <x-admin-responsive-grid class="quick-stats-bar auto-fit" :delay="300">
        <x-admin-stat-card 
            icon="fas fa-map"
            :value="$totalZones ?? 0"
            label="Total Zona"
            change="Semua Zona"
            changeType="info"
            iconType="primary"
            :delay="400"
        />
        
        <x-admin-stat-card 
            icon="fas fa-check-circle"
            :value="$activeZones ?? 0"
            label="Zona Aktif"
            change="Active"
            changeType="positive"
            iconType="success"
            :delay="500"
        />
        
        <x-admin-stat-card 
            icon="fas fa-times-circle"
            :value="$inactiveZones ?? 0"
            label="Zona Nonaktif"
            change="Inactive"
            changeType="warning"
            iconType="warning"
            :delay="600"
        />
    </x-admin-responsive-grid>

    <!-- Create Zone Form -->
    <x-admin-content-card 
        title="Tambah Zona Baru"
        icon="fas fa-plus-circle"
        :delay="400"
    >
        <form method="POST" action="{{ route('admin.delivery.zones.create') }}" id="createZoneForm" class="zone-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Zona *</label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           class="form-input @error('name') error @enderror" 
                           placeholder="Contoh: Jakarta Pusat" 
                           required />
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" 
                              class="form-input @error('description') error @enderror" 
                              rows="2" 
                              placeholder="Deskripsi zona pengiriman">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tarif Dasar (Rp) *</label>
                    <input type="number" 
                           name="base_rate" 
                           value="{{ old('base_rate', 10000) }}" 
                           class="form-input @error('base_rate') error @enderror" 
                           min="0" 
                           step="0.01" 
                           required />
                    @error('base_rate')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tarif per KM (Rp) *</label>
                    <input type="number" 
                           name="per_km_rate" 
                           value="{{ old('per_km_rate', 2000) }}" 
                           class="form-input @error('per_km_rate') error @enderror" 
                           min="0" 
                           step="0.01" 
                           required />
                    @error('per_km_rate')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Multiplier *</label>
                    <input type="number" 
                           name="multiplier" 
                           value="{{ old('multiplier', 1) }}" 
                           class="form-input @error('multiplier') error @enderror" 
                           min="0.1" 
                           max="5" 
                           step="0.1" 
                           required />
                    <small class="form-hint">Faktor pengali untuk tarif (default: 1.0)</small>
                    @error('multiplier')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Maksimal Jarak (KM) *</label>
                    <input type="number" 
                           name="max_distance_km" 
                           value="{{ old('max_distance_km', 50) }}" 
                           class="form-input @error('max_distance_km') error @enderror" 
                           min="1" 
                           max="200" 
                           required />
                    @error('max_distance_km')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Warna Zona</label>
                    <input type="color" 
                           name="color" 
                           value="{{ old('color', '#EC4899') }}" 
                           class="form-input color-input @error('color') error @enderror" />
                    @error('color')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group full-width">
                    <label class="form-label">Koordinat Polygon *</label>
                    <div class="map-container">
                        <div id="zoneMap" style="width: 100%; height: 400px; border-radius: 8px; border: 2px solid var(--gray-200);"></div>
                    </div>
                    <input type="hidden" 
                           name="polygon_coordinates" 
                           id="polygon_coordinates" 
                           value="{{ old('polygon_coordinates', '[]') }}" 
                           required />
                    <small class="form-hint">Klik pada peta untuk membuat polygon zona. Minimal 3 titik diperlukan.</small>
                    <div class="map-actions">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearPolygon()">
                            <i class="fas fa-eraser mr-2"></i>Hapus Polygon
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="loadSamplePolygon()">
                            <i class="fas fa-map-marker-alt mr-2"></i>Load Sample
                        </button>
                    </div>
                    @error('polygon_coordinates')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Simpan Zona
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-undo mr-2"></i>Reset
                </button>
            </div>
        </form>
    </x-admin-content-card>

    <!-- Zones List -->
    <x-admin-content-card 
        title="Daftar Zona Pengiriman"
        icon="fas fa-list"
        :delay="500"
    >
        @if($zones->count() > 0)
            <div class="zones-table-container">
                <table class="zones-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Tarif Dasar</th>
                            <th>Tarif/KM</th>
                            <th>Multiplier</th>
                            <th>Max Jarak</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($zones as $zone)
                            <tr data-zone-id="{{ $zone->id }}">
                                <td>
                                    <div class="zone-name">
                                        <span class="zone-color" style="background-color: {{ $zone->color ?? '#EC4899' }};"></span>
                                        <strong>{{ $zone->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $zone->description ?? '-' }}</td>
                                <td>Rp {{ number_format($zone->base_rate, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($zone->per_km_rate, 0, ',', '.') }}</td>
                                <td>{{ $zone->multiplier }}x</td>
                                <td>{{ $zone->max_distance_km }} KM</td>
                                <td>
                                    <span class="status-badge {{ $zone->is_active ? 'active' : 'inactive' }}">
                                        {{ $zone->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" 
                                                class="btn btn-sm btn-info" 
                                                onclick="editZone({{ $zone->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="deleteZone({{ $zone->id }}, '{{ $zone->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container">
                {{ $zones->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-map-marked-alt empty-icon"></i>
                <h3 class="empty-title">Belum Ada Zona</h3>
                <p class="empty-description">Tambahkan zona pengiriman pertama Anda menggunakan form di atas.</p>
            </div>
        @endif
    </x-admin-content-card>
</div>

<!-- Edit Zone Modal -->
<div id="editZoneModal" class="modal-overlay" style="display: none;">
    <div class="modal-container modal-large">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-edit"></i>
                Edit Zona
            </h3>
            <button class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="editZoneForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Zona *</label>
                        <input type="text" 
                               name="name" 
                               id="edit_name" 
                               class="form-input" 
                               required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" 
                                  id="edit_description" 
                                  class="form-input" 
                                  rows="2"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tarif Dasar (Rp) *</label>
                        <input type="number" 
                               name="base_rate" 
                               id="edit_base_rate" 
                               class="form-input" 
                               min="0" 
                               step="0.01" 
                               required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tarif per KM (Rp) *</label>
                        <input type="number" 
                               name="per_km_rate" 
                               id="edit_per_km_rate" 
                               class="form-input" 
                               min="0" 
                               step="0.01" 
                               required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Multiplier *</label>
                        <input type="number" 
                               name="multiplier" 
                               id="edit_multiplier" 
                               class="form-input" 
                               min="0.1" 
                               max="5" 
                               step="0.1" 
                               required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Maksimal Jarak (KM) *</label>
                        <input type="number" 
                               name="max_distance_km" 
                               id="edit_max_distance_km" 
                               class="form-input" 
                               min="1" 
                               max="200" 
                               required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Warna Zona</label>
                        <input type="color" 
                               name="color" 
                               id="edit_color" 
                               class="form-input color-input" />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="toggle-container">
                            <label class="toggle-label">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="edit_is_active" 
                                       value="1" 
                                       class="toggle-input" />
                                <span class="toggle-slider"></span>
                                <span class="toggle-text">Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Luxury Zones Page Styles */
.luxury-zones-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
}

.zone-form {
    margin-top: 1rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    display: block;
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

.color-input {
    height: 50px;
    padding: 0.25rem;
    cursor: pointer;
}

.form-hint {
    display: block;
    font-size: 0.75rem;
    color: var(--gray-500);
    margin-top: 0.25rem;
}

.form-error {
    color: #dc2626;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    font-weight: 500;
}

.map-container {
    margin: 1rem 0;
}

.map-actions {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--gray-200);
}

.zones-table-container {
    overflow-x: auto;
    margin-top: 1rem;
}

.zones-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--pure-white);
    border-radius: 8px;
    overflow: hidden;
}

.zones-table thead {
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    color: white;
}

.zones-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.zones-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--gray-200);
    font-size: 0.875rem;
}

.zones-table tbody tr:hover {
    background: rgba(236, 72, 153, 0.05);
}

.zone-name {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.zone-color {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid var(--gray-300);
}

.status-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
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
}

.btn-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: 1px solid #3b82f6;
}

.btn-info:hover {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    transform: translateY(-2px);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: 1px solid #ef4444;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    transform: translateY(-2px);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.empty-description {
    color: var(--gray-500);
    font-size: 0.875rem;
}

.pagination-container {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.modal-container {
    background: white;
    border-radius: 16px;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-large {
    max-width: 900px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 2px solid var(--gray-200);
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-800);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--gray-500);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: var(--gray-100);
    color: var(--gray-800);
}

.modal-body {
    padding: 1.5rem;
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

.mr-2 {
    margin-right: 0.5rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .zones-table {
        font-size: 0.75rem;
    }
    
    .zones-table th,
    .zones-table td {
        padding: 0.5rem;
    }
}
</style>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
let map;
let polygonLayer;
let polygonPoints = [];

document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    map = L.map('zoneMap').setView([-6.2088, 106.8456], 11);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Handle map clicks
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        polygonPoints.push([lng, lat]);
        updatePolygon();
        updatePolygonInput();
    });
    
    // Load existing polygon if editing
    const existingPolygon = document.getElementById('polygon_coordinates').value;
    if (existingPolygon && existingPolygon !== '[]') {
        try {
            polygonPoints = JSON.parse(existingPolygon);
            updatePolygon();
        } catch (e) {
            console.error('Error parsing polygon:', e);
        }
    }
});

function updatePolygon() {
    if (polygonLayer) {
        map.removeLayer(polygonLayer);
    }
    
    if (polygonPoints.length >= 3) {
        polygonLayer = L.polygon(
            polygonPoints.map(p => [p[1], p[0]]),
            { color: '#EC4899', fillColor: '#EC4899', fillOpacity: 0.3 }
        ).addTo(map);
        
        // Add markers
        polygonPoints.forEach((point, index) => {
            L.marker([point[1], point[0]])
                .addTo(map)
                .bindPopup(`Point ${index + 1}`);
        });
    }
}

function updatePolygonInput() {
    document.getElementById('polygon_coordinates').value = JSON.stringify(polygonPoints);
}

function clearPolygon() {
    if (polygonLayer) {
        map.removeLayer(polygonLayer);
    }
    polygonPoints = [];
    updatePolygonInput();
    map.eachLayer(function(layer) {
        if (layer instanceof L.Marker) {
            map.removeLayer(layer);
        }
    });
}

function loadSamplePolygon() {
    // Sample polygon for Jakarta area
    polygonPoints = [
        [106.8, -6.2],
        [106.9, -6.2],
        [106.9, -6.3],
        [106.8, -6.3],
        [106.8, -6.2]
    ];
    updatePolygon();
    updatePolygonInput();
    map.fitBounds(polygonLayer.getBounds());
}

function editZone(zoneId) {
    // Fetch zone data and populate edit form
    fetch(`/admin/delivery/zones/${zoneId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_description').value = data.description || '';
            document.getElementById('edit_base_rate').value = data.base_rate;
            document.getElementById('edit_per_km_rate').value = data.per_km_rate;
            document.getElementById('edit_multiplier').value = data.multiplier;
            document.getElementById('edit_max_distance_km').value = data.max_distance_km;
            document.getElementById('edit_color').value = data.color || '#EC4899';
            document.getElementById('edit_is_active').checked = data.is_active;
            
            document.getElementById('editZoneForm').action = `/admin/delivery/zones/${zoneId}`;
            document.getElementById('editZoneModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data zona');
        });
}

function closeEditModal() {
    document.getElementById('editZoneModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function deleteZone(zoneId, zoneName) {
    if (!confirm(`Apakah Anda yakin ingin menghapus zona "${zoneName}"?`)) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/delivery/zones/${zoneId}`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    document.body.appendChild(form);
    form.submit();
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

