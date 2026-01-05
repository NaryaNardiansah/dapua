@extends('layouts.driver')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div>
    <!-- Header -->
    <div class="kitchen-card" style="margin-bottom: 2rem; background: linear-gradient(135deg, rgba(139, 90, 43, 0.1), rgba(160, 120, 70, 0.1));">
        <div class="order-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div style="flex: 1; min-width: 0;">
                <a href="{{ route('driver.orders.index') }}" class="back-link" style="color: #8b5a2b; text-decoration: none; margin-bottom: 0.75rem; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600; transition: all 0.3s; font-size: 0.875rem;" onmouseover="this.style.color='#a07050'" onmouseout="this.style.color='#8b5a2b'">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
                <h1 class="order-title" style="font-size: 2.5rem; font-weight: 800; color: #1a202c; margin: 0; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); word-break: break-word;">
                    <i class="fas fa-receipt" style="color: #8b5a2b; margin-right: 1rem;"></i>
                    <span>Detail Pesanan - {{ $order->order_code }}</span>
                </h1>
            </div>
            <div class="status-badge-wrapper" style="flex-shrink: 0;">
                <span class="status-badge" style="padding: 0.75rem 1.5rem; background: 
                    @if($order->status == 'selesai') linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(52, 211, 153, 0.15)); color: #065f46; border: 2px solid rgba(16, 185, 129, 0.3);
                    @elseif($order->status == 'dikirim') linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(251, 191, 36, 0.15)); color: #92400e; border: 2px solid rgba(245, 158, 11, 0.3);
                    @elseif($order->status == 'diproses') linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(96, 165, 250, 0.15)); color: #1e40af; border: 2px solid rgba(59, 130, 246, 0.3);
                    @else linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(248, 113, 113, 0.15)); color: #991b1b; border: 2px solid rgba(239, 68, 68, 0.3);
                    @endif
                    border-radius: 9999px; font-weight: 700; font-size: 1rem; display: inline-block;">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Order Information Grid -->
    <div class="order-info-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Customer Info -->
        <div class="kitchen-card">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, rgba(139, 90, 43, 0.95), rgba(160, 120, 70, 0.95)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; box-shadow: 0 4px 15px rgba(139, 90, 43, 0.3);">
                    <i class="fas fa-user"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin: 0;">Informasi Penerima</h3>
            </div>
            <div style="display: grid; gap: 1rem;">
                <div>
                    <strong style="color: #6b7280; font-size: 0.875rem; display: block; margin-bottom: 0.25rem;">Nama:</strong>
                    <div style="color: #1a202c; font-size: 1.125rem; font-weight: 600;">{{ $order->recipient_name }}</div>
                </div>
                <div>
                    <strong style="color: #6b7280; font-size: 0.875rem; display: block; margin-bottom: 0.25rem;">Telepon:</strong>
                    <div style="color: #1a202c; font-size: 1.125rem; display: flex; gap: 1rem; align-items: center;">
                        <a href="tel:{{ $order->recipient_phone }}" style="color: #8b5a2b; text-decoration: none; font-weight: 600; transition: all 0.3s; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.color='#a07050'" onmouseout="this.style.color='#8b5a2b'">
                            <i class="fas fa-phone"></i>
                            <span>{{ $order->recipient_phone }}</span>
                        </a>
                        @php
                            $waRecipientPhone = preg_replace('/[^0-9]/', '', $order->recipient_phone);
                            if (str_starts_with($waRecipientPhone, '0')) {
                                $waRecipientPhone = '62' . substr($waRecipientPhone, 1);
                            }
                        @endphp
                        <a href="https://wa.me/{{ $waRecipientPhone }}?text=Halo%20{{ urlencode($order->recipient_name) }},%20saya%20driver%20Dapur%20Sakura%20ingin%20konfirmasi%20pengiriman%20pesanan%20{{ $order->order_code }}" target="_blank" style="color: #25D366; text-decoration: none; font-weight: 600; transition: all 0.3s; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                            <i class="fab fa-whatsapp" style="font-size: 1.25rem;"></i>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>
                <div>
                    <strong style="color: #6b7280; font-size: 0.875rem; display: block; margin-bottom: 0.25rem;">Alamat:</strong>
                    <div style="color: #1a202c; font-size: 1rem; line-height: 1.6;">{{ $order->address_line }}</div>
                </div>
                @if($order->delivery_instructions)
                <div>
                    <strong style="color: #6b7280; font-size: 0.875rem; display: block; margin-bottom: 0.25rem;">Instruksi Pengiriman:</strong>
                    <div style="color: #1a202c; background: linear-gradient(135deg, rgba(139, 90, 43, 0.05), rgba(160, 120, 70, 0.05)); padding: 1rem; border-radius: 12px; border: 1px solid rgba(139, 90, 43, 0.2); line-height: 1.6;">
                        {{ $order->delivery_instructions }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Details -->
        <div class="kitchen-card">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.95), rgba(96, 165, 250, 0.95)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin: 0;">Detail Pesanan</h3>
            </div>
            <div style="display: grid; gap: 1rem;">
                <div>
                    <strong style="color: #6b7280; font-size: 0.875rem; display: block; margin-bottom: 0.25rem;">Tanggal Pesanan:</strong>
                    <div style="color: #1a202c; font-size: 1rem; font-weight: 600;">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>
                @if($order->tracking_code)
                <div>
                    <strong style="color: #6b7280; font-size: 0.875rem; display: block; margin-bottom: 0.25rem;">Tracking Code:</strong>
                    <div style="color: #1a202c; font-size: 1rem; font-weight: 600; font-family: monospace;">{{ $order->tracking_code }}</div>
                </div>
                @endif
                @if($order->picked_up_at)
                <div>
                    <strong style="color: #6b7280; font-size: 0.875rem; display: block; margin-bottom: 0.25rem;">Diambil pada:</strong>
                    <div style="color: #1a202c; font-size: 1rem; font-weight: 600;">{{ \Carbon\Carbon::parse($order->picked_up_at)->format('d M Y, H:i') }}</div>
                </div>
                @endif
                @if($order->delivered_at)
                <div>
                    <strong style="color: #6b7280; font-size: 0.875rem; display: block; margin-bottom: 0.25rem;">Disampaikan pada:</strong>
                    <div style="color: #1a202c; font-size: 1rem; font-weight: 600;">{{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y, H:i') }}</div>
                </div>
                @endif
                @if($order->latitude && $order->longitude)
                <div>
                    <strong style="color: #6b7280; font-size: 0.875rem; display: block; margin-bottom: 0.25rem;">Koordinat:</strong>
                    <div style="color: #1a202c; font-size: 0.875rem; font-family: monospace; background: rgba(139, 90, 43, 0.05); padding: 0.5rem; border-radius: 8px;">{{ $order->latitude }}, {{ $order->longitude }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Map Section -->
    @if($order->latitude && $order->longitude)
    <div class="kitchen-card" style="margin-bottom: 2rem;">
        <div class="map-header" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.95), rgba(96, 165, 250, 0.95)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); flex-shrink: 0;">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <h3 class="map-title" style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin: 0;">Peta Lokasi Pengiriman</h3>
                <p class="map-subtitle" style="color: #6b7280; margin: 0.25rem 0 0 0; font-size: 0.875rem;">Lihat rute dan navigasi ke lokasi tujuan</p>
            </div>
            <div class="map-actions" style="display: flex; gap: 0.5rem; flex-wrap: wrap; width: 100%;">
                <a href="https://www.google.com/maps/dir/{{ $storeLat }},{{ $storeLng }}/{{ $order->latitude }},{{ $order->longitude }}" 
                   target="_blank" 
                   class="kitchen-btn kitchen-btn-primary" 
                   style="text-decoration: none; white-space: nowrap; flex: 1; min-width: 140px; justify-content: center;">
                    <i class="fas fa-route"></i>
                    <span>Buka Navigasi</span>
                </a>
                <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" 
                   target="_blank" 
                   class="kitchen-btn kitchen-btn-primary" 
                   style="text-decoration: none; white-space: nowrap; flex: 1; min-width: 140px; justify-content: center; background: linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(52, 211, 153, 0.95)); border-color: rgba(16, 185, 129, 0.4);">
                    <i class="fas fa-map"></i>
                    <span>Google Maps</span>
                </a>
            </div>
        </div>
        
        <div id="driver-order-map" class="driver-map-container" style="width: 100%; height: 500px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(139, 90, 43, 0.15); border: 2px solid rgba(139, 90, 43, 0.2);"></div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(139, 90, 43, 0.2);">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 12px; height: 12px; border-radius: 50%; background: linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(52, 211, 153, 0.95)); box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);"></div>
                <span style="color: #4a5568; font-size: 0.875rem; font-weight: 600;">Lokasi Toko</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 12px; height: 12px; border-radius: 50%; background: linear-gradient(135deg, rgba(139, 90, 43, 0.95), rgba(160, 120, 70, 0.95)); box-shadow: 0 2px 8px rgba(139, 90, 43, 0.3);"></div>
                <span style="color: #4a5568; font-size: 0.875rem; font-weight: 600;">Lokasi Tujuan</span>
            </div>
            @if($order->distance_meters)
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-route" style="color: #8b5a2b;"></i>
                <span style="color: #4a5568; font-size: 0.875rem; font-weight: 600;">
                    Jarak: {{ number_format($order->distance_meters / 1000, 2) }} km
                </span>
            </div>
            @endif
            @php
                $estimatedMinutes = $order->distance_meters ? round(($order->distance_meters / 1000 / 30) * 60) : 0;
                $estimatedTime = $estimatedMinutes < 60 ? $estimatedMinutes . ' menit' : floor($estimatedMinutes/60) . ' jam ' . ($estimatedMinutes%60) . ' menit';
            @endphp
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-clock" style="color: #8b5a2b;"></i>
                <span style="color: #4a5568; font-size: 0.875rem; font-weight: 600;">
                    Estimasi: {{ $estimatedTime }}
                </span>
            </div>
        </div>
    </div>
    @endif

    <!-- Order Items -->
    <div class="kitchen-card" style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(52, 211, 153, 0.95)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin: 0;">Item Pesanan</h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, rgba(139, 90, 43, 0.1), rgba(160, 120, 70, 0.1));">
                        <th style="padding: 1rem; text-align: left; font-weight: 700; color: #1a202c; border-bottom: 2px solid rgba(139, 90, 43, 0.2);">Produk</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 700; color: #1a202c; border-bottom: 2px solid rgba(139, 90, 43, 0.2);">Qty</th>
                        <th style="padding: 1rem; text-align: right; font-weight: 700; color: #1a202c; border-bottom: 2px solid rgba(139, 90, 43, 0.2);">Harga</th>
                        <th style="padding: 1rem; text-align: right; font-weight: 700; color: #1a202c; border-bottom: 2px solid rgba(139, 90, 43, 0.2);">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr style="border-bottom: 1px solid rgba(139, 90, 43, 0.1);">
                        <td data-label="Produk" style="padding: 1rem; color: #1a202c; font-weight: 600;">{{ $item->product_name }}</td>
                        <td data-label="Qty" style="padding: 1rem; text-align: center; color: #4a5568;">{{ $item->quantity }}</td>
                        <td data-label="Harga" style="padding: 1rem; text-align: right; color: #4a5568;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td data-label="Subtotal" style="padding: 1rem; text-align: right; font-weight: 700; color: #1a202c;">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" data-label="Subtotal" style="padding: 1rem; text-align: right; font-weight: 700; color: #6b7280;">Subtotal:</td>
                        <td data-label="Nilai" style="padding: 1rem; text-align: right; font-weight: 700; color: #1a202c;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" data-label="Ongkir" style="padding: 1rem; text-align: right; font-weight: 700; color: #6b7280;">Ongkir:</td>
                        <td data-label="Nilai" style="padding: 1rem; text-align: right; font-weight: 700; color: #1a202c;">Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</td>
                    </tr>
                    @if($order->discount_total > 0)
                    <tr>
                        <td colspan="3" data-label="Diskon" style="padding: 1rem; text-align: right; font-weight: 700; color: #6b7280;">Diskon:</td>
                        <td data-label="Nilai" style="padding: 1rem; text-align: right; font-weight: 700; color: #10b981;">-Rp {{ number_format($order->discount_total, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr style="background: linear-gradient(135deg, rgba(139, 90, 43, 0.1), rgba(160, 120, 70, 0.1));">
                        <td colspan="3" data-label="Total" style="padding: 1.25rem 1rem; text-align: right; font-weight: 800; font-size: 1.25rem; color: #1a202c;">Total:</td>
                        <td data-label="Nilai" style="padding: 1.25rem 1rem; text-align: right; font-weight: 800; font-size: 1.25rem; color: #8b5a2b;">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Update Status Form -->
    @if(in_array($order->status, ['diproses', 'dikirim']))
    <div class="kitchen-card" style="margin-bottom: 2rem; background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(52, 211, 153, 0.05)); border-color: rgba(16, 185, 129, 0.3);">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #10b981, #34d399); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                <i class="fas fa-sync-alt"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin: 0;">Update Status Pesanan</h3>
        </div>
        
        <form id="updateStatusForm" method="POST" action="{{ route('driver.orders.update-status', $order) }}" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; gap: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.75rem; color: #374151; font-weight: 600; font-size: 0.875rem;">Status Baru</label>
                    <select name="status" id="statusSelect" required style="width: 100%; padding: 0.875rem 1rem; border: 2px solid rgba(139, 90, 43, 0.2); border-radius: 12px; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='rgba(139, 90, 43, 0.4)'; this.style.boxShadow='0 0 0 3px rgba(139, 90, 43, 0.1)'" onblur="this.style.borderColor='rgba(139, 90, 43, 0.2)'; this.style.boxShadow='none'">
                        @if($order->status == 'diproses')
                            <option value="dikirim">Dikirim</option>
                        @elseif($order->status == 'dikirim')
                            <option value="selesai">Selesai</option>
                        @endif
                    </select>
                </div>
                
                <div id="deliveryPhotoSection" style="display: none;">
                    <label style="display: block; margin-bottom: 0.75rem; color: #374151; font-weight: 600; font-size: 0.875rem;">Foto Pengiriman</label>
                    <input type="file" name="delivery_photo" accept="image/*" style="width: 100%; padding: 0.875rem 1rem; border: 2px solid rgba(139, 90, 43, 0.2); border-radius: 12px; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); transition: all 0.3s;" onfocus="this.style.borderColor='rgba(139, 90, 43, 0.4)'; this.style.boxShadow='0 0 0 3px rgba(139, 90, 43, 0.1)'" onblur="this.style.borderColor='rgba(139, 90, 43, 0.2)'; this.style.boxShadow='none'">
                    <small style="color: #6b7280; margin-top: 0.5rem; display: block;">Maksimal 2MB, format: JPG, PNG</small>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 0.75rem; color: #374151; font-weight: 600; font-size: 0.875rem;">Catatan (Opsional)</label>
                    <textarea name="notes" rows="4" placeholder="Tambahkan catatan jika diperlukan..." style="width: 100%; padding: 0.875rem 1rem; border: 2px solid rgba(139, 90, 43, 0.2); border-radius: 12px; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); resize: vertical; font-family: inherit; transition: all 0.3s;" onfocus="this.style.borderColor='rgba(139, 90, 43, 0.4)'; this.style.boxShadow='0 0 0 3px rgba(139, 90, 43, 0.1)'" onblur="this.style.borderColor='rgba(139, 90, 43, 0.2)'; this.style.boxShadow='none'"></textarea>
                </div>
                
                <div>
                    <button type="submit" class="kitchen-btn kitchen-btn-success" style="width: 100%; justify-content: center; font-size: 1.125rem; padding: 1rem;">
                        <i class="fas fa-check"></i>
                        <span>Update Status</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif

    <!-- Delivery Photo (if exists) -->
    @if($order->delivery_photo)
    <div class="kitchen-card">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, rgba(139, 92, 246, 0.95), rgba(167, 139, 250, 0.95)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);">
                <i class="fas fa-camera"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin: 0;">Foto Pengiriman</h3>
        </div>
        <div>
            <img src="{{ Storage::url($order->delivery_photo) }}" alt="Delivery Photo" style="max-width: 100%; border-radius: 16px; box-shadow: 0 10px 30px rgba(139, 90, 43, 0.2); border: 2px solid rgba(139, 90, 43, 0.2);">
        </div>
    </div>
    @endif
</div>

@if($order->latitude && $order->longitude)
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        /* Map Styles - Kitchen Theme */
        #driver-order-map {
            position: relative;
        }
        
        .driver-map-marker {
            position: relative;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border: 3px solid white;
        }
        
        .driver-map-marker.store {
            background: linear-gradient(135deg, #10b981, #34d399);
        }
        
        .driver-map-marker.customer {
            background: linear-gradient(135deg, rgba(139, 90, 43, 0.95), rgba(160, 120, 70, 0.95));
        }
        
        .driver-map-marker.driver {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }
        
        .driver-map-marker::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: inherit;
            opacity: 0.3;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.3;
            }
            50% {
                transform: scale(1.5);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 0;
            }
        }
        
        .leaflet-popup-content-wrapper {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
            backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 2px solid rgba(139, 90, 43, 0.2);
            box-shadow: 0 10px 30px rgba(139, 90, 43, 0.2);
        }
        
        .leaflet-popup-content {
            margin: 1rem;
            font-family: 'Inter', sans-serif;
        }
        
        .map-popup-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            font-weight: 700;
            color: #1a202c;
            font-size: 1.125rem;
        }
        
        .map-popup-body {
            color: #4a5568;
            font-size: 0.875rem;
        }
        
        .map-popup-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }
        
        .map-popup-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }
        
        .map-popup-btn-primary {
            background: linear-gradient(135deg, rgba(139, 90, 43, 0.95), rgba(160, 120, 70, 0.95));
            color: white;
        }
        
        .map-popup-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 90, 43, 0.3);
        }
        
        /* Responsive Styles for Order Detail */
        
        /* Tablet */
        @media (max-width: 1024px) {
            .order-title {
                font-size: 2rem !important;
            }
            
            .order-info-grid {
                grid-template-columns: 1fr !important;
            }
        }
        
        /* Mobile & Tablet */
        @media (max-width: 768px) {
            .order-header {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
            
            .order-title {
                font-size: 1.75rem !important;
            }
            
            .order-title i {
                margin-right: 0.5rem !important;
            }
            
            .status-badge-wrapper {
                width: 100% !important;
            }
            
            .status-badge {
                width: 100% !important;
                text-align: center !important;
                padding: 0.625rem 1rem !important;
                font-size: 0.9375rem !important;
            }
            
            .back-link span {
                display: none;
            }
            
            .order-info-grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            /* Responsive Map Styles */
            .driver-map-container {
                height: 400px !important;
            }
            
            .map-header {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
            
            .map-title {
                font-size: 1.25rem !important;
            }
            
            .map-subtitle {
                font-size: 0.8125rem !important;
            }
            
            .map-actions {
                flex-direction: column !important;
            }
            
            .map-actions .kitchen-btn {
                width: 100% !important;
            }
            
            .kitchen-card[style*="grid-template-columns: repeat(auto-fit"] {
                grid-template-columns: 1fr !important;
            }
            
            .kitchen-card[style*="font-size: 2.5rem"] {
                font-size: 2rem !important;
            }
            
            .kitchen-card[style*="font-size: 1.5rem"] {
                font-size: 1.25rem !important;
            }
            
            .kitchen-card[style*="width: 50px"] {
                width: 45px !important;
                height: 45px !important;
                font-size: 1.125rem !important;
            }
            
            table {
                font-size: 0.875rem !important;
            }
            
            table th,
            table td {
                padding: 0.75rem 0.5rem !important;
            }
        }
        
        @media (max-width: 480px) {
            .driver-map-container {
                height: 350px !important;
                border-radius: 12px !important;
            }
            
            .map-title {
                font-size: 1.125rem !important;
            }
            
            .map-subtitle {
                font-size: 0.75rem !important;
            }
            
            .kitchen-card[style*="font-size: 2.5rem"] {
                font-size: 1.75rem !important;
            }
            
            .kitchen-card[style*="font-size: 1.5rem"] {
                font-size: 1.125rem !important;
            }
            
            .kitchen-card[style*="width: 50px"],
            .kitchen-card[style*="width: 45px"] {
                width: 40px !important;
                height: 40px !important;
                font-size: 1rem !important;
            }
            
            table {
                font-size: 0.8125rem !important;
            }
            
            table th,
            table td {
                padding: 0.625rem 0.375rem !important;
            }
            
            .kitchen-card[style*="display: grid; gap: 1rem"] {
                gap: 0.75rem !important;
            }
            
            .kitchen-card[style*="display: grid; gap: 1.5rem"] {
                gap: 1rem !important;
            }
        }
        
        /* Mobile */
        @media (max-width: 480px) {
            .order-title {
                font-size: 1.5rem !important;
                flex-direction: column !important;
                align-items: flex-start !important;
            }
            
            .order-title i {
                margin-right: 0 !important;
                margin-bottom: 0.5rem;
            }
            
            .status-badge {
                padding: 0.5rem 0.875rem !important;
                font-size: 0.875rem !important;
            }
            
            .kitchen-card[style*="font-size: 1.5rem"] {
                font-size: 1.25rem !important;
            }
            
            .kitchen-card[style*="font-size: 1.25rem"] {
                font-size: 1.125rem !important;
            }
            
            .kitchen-card[style*="width: 50px"] {
                width: 40px !important;
                height: 40px !important;
                font-size: 1rem !important;
            }
            
            table {
                display: block !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            table thead {
                display: none !important;
            }
            
            table tbody,
            table tr,
            table td {
                display: block !important;
                width: 100% !important;
            }
            
            table tr {
                border-bottom: 2px solid rgba(139, 90, 43, 0.2) !important;
                margin-bottom: 1rem !important;
                padding-bottom: 1rem !important;
            }
            
            table td {
                text-align: left !important;
                padding: 0.5rem 0 !important;
                border: none !important;
            }
            
            table td::before {
                content: attr(data-label) ": ";
                font-weight: 700;
                color: #6b7280;
                display: inline-block;
                min-width: 100px;
            }
        }
        
        /* Touch-friendly map controls */
        @media (hover: none) and (pointer: coarse) {
            .leaflet-control-zoom {
                font-size: 18px !important;
            }
            
            .leaflet-control-zoom a {
                width: 36px !important;
                height: 36px !important;
                line-height: 36px !important;
            }
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store location
            const storeLat = {{ $storeLat }};
            const storeLng = {{ $storeLng }};
            
            // Customer location
            const customerLat = {{ $order->latitude }};
            const customerLng = {{ $order->longitude }};
            
            // Driver location (if available)
            @php
                $driver = auth()->user();
                $driverLat = $driver->current_latitude ?? null;
                $driverLng = $driver->current_longitude ?? null;
            @endphp
            const driverLat = {{ $driverLat !== null ? $driverLat : 'null' }};
            const driverLng = {{ $driverLng !== null ? $driverLng : 'null' }};
            
            // Calculate distance and time
            const distanceKm = {{ $order->distance_meters ? ($order->distance_meters / 1000) : 0 }};
            const estimatedMinutes = distanceKm > 0 ? Math.round((distanceKm / 30) * 60) : 0;
            const estimatedTime = estimatedMinutes < 60 ? `${estimatedMinutes} menit` : `${Math.floor(estimatedMinutes/60)} jam ${estimatedMinutes%60} menit`;
            
            // Initialize map
            const map = L.map('driver-order-map', {
                zoomControl: true,
                gestureHandling: true,
                scrollWheelZoom: true
            });
            
            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
            
            // Create custom icons
            const storeIcon = L.divIcon({
                className: 'driver-map-marker store',
                html: '<i class="fas fa-store"></i>',
                iconSize: [50, 50],
                iconAnchor: [25, 25],
                popupAnchor: [0, -25]
            });
            
            const customerIcon = L.divIcon({
                className: 'driver-map-marker customer',
                html: '<i class="fas fa-home"></i>',
                iconSize: [50, 50],
                iconAnchor: [25, 25],
                popupAnchor: [0, -25]
            });
            
            const driverIcon = L.divIcon({
                className: 'driver-map-marker driver',
                html: '<i class="fas fa-truck"></i>',
                iconSize: [50, 50],
                iconAnchor: [25, 25],
                popupAnchor: [0, -25]
            });
            
            // Add store marker
            const storeMarker = L.marker([storeLat, storeLng], { icon: storeIcon }).addTo(map);
            storeMarker.bindPopup(`
                <div class="map-popup-header">
                    <i class="fas fa-store" style="color: #10b981;"></i>
                    <span>Dapur Sakura</span>
                </div>
                <div class="map-popup-body">
                    <div>Lokasi Toko</div>
                    <div style="font-family: monospace; font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
                        ${storeLat.toFixed(6)}, ${storeLng.toFixed(6)}
                    </div>
                </div>
            `);
            
            // Add customer marker
            const customerMarker = L.marker([customerLat, customerLng], { icon: customerIcon }).addTo(map);
            customerMarker.bindPopup(`
                <div class="map-popup-header">
                    <i class="fas fa-home" style="color: #8b5a2b;"></i>
                    <span>{{ $order->recipient_name }}</span>
                </div>
                <div class="map-popup-body">
                    <div style="font-weight: 600; margin-bottom: 0.5rem;">Lokasi Pengiriman</div>
                    <div style="margin-bottom: 0.5rem;">{{ $order->address_line }}</div>
                    <div style="font-family: monospace; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.75rem;">
                        ${customerLat.toFixed(6)}, ${customerLng.toFixed(6)}
                    </div>
                    @if($order->distance_meters)
                    <div style="margin-bottom: 0.25rem;">
                        <i class="fas fa-route" style="color: #8b5a2b; margin-right: 0.5rem;"></i>
                        Jarak: {{ number_format($order->distance_meters / 1000, 2) }} km
                    </div>
                    @endif
                    <div style="margin-bottom: 0.75rem;">
                        <i class="fas fa-clock" style="color: #8b5a2b; margin-right: 0.5rem;"></i>
                        Estimasi: ${estimatedTime}
                    </div>
                    <div class="map-popup-actions">
                        <a href="https://www.google.com/maps/dir/${storeLat},${storeLng}/${customerLat},${customerLng}" 
                           target="_blank" 
                           class="map-popup-btn map-popup-btn-primary">
                            <i class="fas fa-route"></i>
                            <span>Navigasi</span>
                        </a>
                        @php
                            $waRecipientPhoneMap = preg_replace('/[^0-9]/', '', $order->recipient_phone);
                            if (str_starts_with($waRecipientPhoneMap, '0')) {
                                $waRecipientPhoneMap = '62' . substr($waRecipientPhoneMap, 1);
                            }
                        @endphp
                        <a href="https://wa.me/{{ $waRecipientPhoneMap }}?text=Halo%20{{ urlencode($order->recipient_name) }},%20saya%20driver%20Dapur%20Sakura%20ingin%20konfirmasi%20pengiriman%20pesanan%20{{ $order->order_code }}" 
                           target="_blank"
                           class="map-popup-btn map-popup-btn-primary"
                           style="background: #25D366;">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="tel:{{ $order->recipient_phone }}" 
                           class="map-popup-btn map-popup-btn-primary">
                            <i class="fas fa-phone"></i>
                            <span>Telepon</span>
                        </a>
                    </div>
                </div>
            `);
            
            // Add driver marker if location available
            let driverMarker = null;
            if (driverLat && driverLng) {
                driverMarker = L.marker([driverLat, driverLng], { icon: driverIcon }).addTo(map);
                driverMarker.bindPopup(`
                    <div class="map-popup-header">
                        <i class="fas fa-truck" style="color: #3b82f6;"></i>
                        <span>Lokasi Anda</span>
                    </div>
                    <div class="map-popup-body">
                        <div>Posisi driver saat ini</div>
                        <div style="font-family: monospace; font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
                            ${driverLat.toFixed(6)}, ${driverLng.toFixed(6)}
                        </div>
                    </div>
                `);
            }
            
            // Add route line
            const routeLine = L.polyline([
                [storeLat, storeLng],
                [customerLat, customerLng]
            ], {
                color: '#8b5a2b',
                weight: 4,
                opacity: 0.7,
                dashArray: '10, 10'
            }).addTo(map);
            
            // Fit map to show all markers
            const markers = [storeMarker, customerMarker];
            if (driverMarker) {
                markers.push(driverMarker);
            }
            
            const group = new L.featureGroup([...markers, routeLine]);
            map.fitBounds(group.getBounds().pad(0.2));
            
            // Auto-open customer popup
            setTimeout(() => {
                customerMarker.openPopup();
            }, 500);
        });
    </script>
@endif

<script>
    // Show/hide delivery photo section based on status
    document.getElementById('statusSelect')?.addEventListener('change', function() {
        const deliveryPhotoSection = document.getElementById('deliveryPhotoSection');
        if (this.value === 'selesai') {
            deliveryPhotoSection.style.display = 'block';
            deliveryPhotoSection.style.animation = 'fadeIn 0.3s ease';
        } else {
            deliveryPhotoSection.style.display = 'none';
        }
    });

    // Form submission with loading
    document.getElementById('updateStatusForm')?.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Memproses...</span>';
    });

    // Add fadeIn animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection
