@extends('layouts.driver')

@section('content')
    <div>
        <!-- Page Header -->
        <div class="kitchen-card"
            style="margin-bottom: 2rem; text-align: center; background: linear-gradient(135deg, rgba(139, 90, 43, 0.1), rgba(160, 120, 70, 0.1));">
            <h1
                style="font-size: 2.5rem; font-weight: 800; color: #1a202c; margin-bottom: 0.5rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
                <i class="fas fa-list" style="color: #8b5a2b; margin-right: 1rem;"></i>
                Daftar Pesanan
            </h1>
            <p style="color: #6b7280; font-size: 1.125rem;">Kelola semua pesanan yang ditugaskan kepada Anda</p>
        </div>

        <!-- Filters -->
        <div class="kitchen-card" style="margin-bottom: 2rem;">
            <form method="GET" action="{{ route('driver.orders.index') }}"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div>
                    <label
                        style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 600; font-size: 0.875rem;">Cari</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                        placeholder="Kode order, nama, alamat..."
                        style="width: 100%; padding: 0.75rem 1rem; border: 2px solid rgba(139, 90, 43, 0.2); border-radius: 12px; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); transition: all 0.3s;">
                    <style>
                        input[type="text"]:focus {
                            outline: none;
                            border-color: rgba(139, 90, 43, 0.4);
                            box-shadow: 0 0 0 3px rgba(139, 90, 43, 0.1);
                        }
                    </style>
                </div>

                <div>
                    <label
                        style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 600; font-size: 0.875rem;">Status</label>
                    <select name="status"
                        style="width: 100%; padding: 0.75rem 1rem; border: 2px solid rgba(139, 90, 43, 0.2); border-radius: 12px; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px);">
                        <option value="">Semua Status</option>
                        <option value="diproses" {{ ($filters['status'] ?? '') == 'diproses' ? 'selected' : '' }}>Diproses
                        </option>
                        <option value="dikirim" {{ ($filters['status'] ?? '') == 'dikirim' ? 'selected' : '' }}>Dikirim
                        </option>
                        <option value="selesai" {{ ($filters['status'] ?? '') == 'selesai' ? 'selected' : '' }}>Selesai
                        </option>
                    </select>
                </div>

                <div>
                    <label
                        style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 600; font-size: 0.875rem;">Periode</label>
                    <select name="date"
                        style="width: 100%; padding: 0.75rem 1rem; border: 2px solid rgba(139, 90, 43, 0.2); border-radius: 12px; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px);">
                        <option value="">Semua Waktu</option>
                        <option value="today" {{ ($filters['date'] ?? '') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ ($filters['date'] ?? '') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ ($filters['date'] ?? '') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>

                <div>
                    <label
                        style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 600; font-size: 0.875rem;">Urutkan</label>
                    <select name="sort"
                        style="width: 100%; padding: 0.75rem 1rem; border: 2px solid rgba(139, 90, 43, 0.2); border-radius: 12px; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px);">
                        <option value="latest" {{ ($filters['sort'] ?? 'latest') == 'latest' ? 'selected' : '' }}>Terbaru
                        </option>
                        <option value="oldest" {{ ($filters['sort'] ?? '') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="highest" {{ ($filters['sort'] ?? '') == 'highest' ? 'selected' : '' }}>Total Tertinggi
                        </option>
                        <option value="lowest" {{ ($filters['sort'] ?? '') == 'lowest' ? 'selected' : '' }}>Total Terendah
                        </option>
                    </select>
                </div>

                <div style="display: flex; align-items: end; gap: 0.5rem;">
                    <button type="submit" class="kitchen-btn kitchen-btn-primary" style="flex: 1; justify-content: center;">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('driver.orders.index') }}" class="kitchen-btn kitchen-btn-primary"
                        style="padding: 0.75rem 1rem; text-decoration: none;">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
            <div style="display: grid; gap: 1.5rem;">
                @foreach($orders as $order)
                    <div class="kitchen-card" style="border-left: 5px solid 
                        @if($order->status == 'selesai') #10b981;
                        @elseif($order->status == 'dikirim') #f59e0b;
                        @elseif($order->status == 'diproses') #3b82f6;
                        @else #ef4444;
                        @endif">
                        <div style="display: grid; grid-template-columns: 1fr auto; gap: 1.5rem; align-items: start;">
                            <div>
                                <div
                                    style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap;">
                                    <span
                                        style="font-size: 1.375rem; font-weight: 800; color: #1a202c;">{{ $order->order_code }}</span>
                                    <span style="padding: 0.5rem 1rem; background: 
                                        @if($order->status == 'selesai') linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(52, 211, 153, 0.15)); color: #065f46; border: 1px solid rgba(16, 185, 129, 0.3);
                                        @elseif($order->status == 'dikirim') linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(251, 191, 36, 0.15)); color: #92400e; border: 1px solid rgba(245, 158, 11, 0.3);
                                        @elseif($order->status == 'diproses') linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(96, 165, 250, 0.15)); color: #1e40af; border: 1px solid rgba(59, 130, 246, 0.3);
                                        @else linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(248, 113, 113, 0.15)); color: #991b1b; border: 1px solid rgba(239, 68, 68, 0.3);
                                        @endif
                                        border-radius: 9999px; font-size: 0.875rem; font-weight: 700;">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    @if($order->tracking_code)
                                        <span
                                            style="padding: 0.5rem 1rem; background: rgba(139, 90, 43, 0.1); color: #8b5a2b; border-radius: 9999px; font-size: 0.875rem; border: 1px solid rgba(139, 90, 43, 0.2);">
                                            <i class="fas fa-barcode" style="margin-right: 0.5rem;"></i>{{ $order->tracking_code }}
                                        </span>
                                    @endif
                                </div>

                                <div
                                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; color: #4a5568;">
                                    <div>
                                        <i class="fas fa-user" style="color: #8b5a2b; margin-right: 0.5rem;"></i>
                                        <strong>Penerima:</strong> {{ $order->recipient_name }}
                                    </div>
                                    <div>
                                        <i class="fas fa-phone" style="color: #8b5a2b; margin-right: 0.5rem;"></i>
                                        <strong>Telepon:</strong>
                                        <span style="margin-right: 0.5rem;">{{ $order->recipient_phone }}</span>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->recipient_phone) }}?text=Halo%20{{ urlencode($order->recipient_name) }},%20saya%20driver%20Dapur%20Sakura%20ingin%20konfirmasi%20pengiriman%20pesanan%20{{ $order->order_code }}"
                                            target="_blank"
                                            style="color: #25D366; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 700;">
                                            <i class="fab fa-whatsapp"></i> Chat
                                        </a>
                                    </div>
                                    <div style="grid-column: 1 / -1;">
                                        <i class="fas fa-map-marker-alt" style="color: #8b5a2b; margin-right: 0.5rem;"></i>
                                        <strong>Alamat:</strong> {{ $order->address_line }}
                                    </div>
                                    <div>
                                        <i class="fas fa-calendar" style="color: #8b5a2b; margin-right: 0.5rem;"></i>
                                        <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <div>
                                        <i class="fas fa-money-bill-wave" style="color: #8b5a2b; margin-right: 0.5rem;"></i>
                                        <strong>Total:</strong> Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                    </div>
                                    @if($order->picked_up_at)
                                        <div>
                                            <i class="fas fa-box" style="color: #8b5a2b; margin-right: 0.5rem;"></i>
                                            <strong>Diambil:</strong>
                                            {{ \Carbon\Carbon::parse($order->picked_up_at)->format('d M Y, H:i') }}
                                        </div>
                                    @endif
                                    @if($order->delivered_at)
                                        <div>
                                            <i class="fas fa-check-circle" style="color: #8b5a2b; margin-right: 0.5rem;"></i>
                                            <strong>Disampaikan:</strong>
                                            {{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y, H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <a href="{{ route('driver.orders.show', $order) }}" class="kitchen-btn kitchen-btn-primary"
                                    style="text-decoration: none; text-align: center; white-space: nowrap;">
                                    <i class="fas fa-eye"></i>
                                    <span>Detail</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                <div class="kitchen-card" style="padding: 1rem; display: inline-block;">
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <div class="kitchen-card" style="text-align: center; padding: 4rem 2rem;">
                <div
                    style="width: 100px; height: 100px; margin: 0 auto 1.5rem; border-radius: 50%; background: linear-gradient(135deg, rgba(139, 90, 43, 0.1), rgba(160, 120, 70, 0.1)); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #8b5a2b;">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin-bottom: 0.5rem;">Tidak ada pesanan
                    ditemukan</h3>
                <p style="color: #6b7280; font-size: 1.125rem;">Coba ubah filter pencarian Anda</p>
            </div>
        @endif
    </div>

    <style>
        /* Responsive Styles for Orders Index */

        /* Tablet */
        @media (max-width: 1024px) {
            .kitchen-card[style*="font-size: 2.5rem"] {
                font-size: 2rem !important;
            }

            .kitchen-card form[style*="grid-template-columns"] {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        /* Mobile & Tablet */
        @media (max-width: 768px) {
            .kitchen-card[style*="font-size: 2.5rem"] {
                font-size: 1.75rem !important;
            }

            .kitchen-card form[style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
            }

            .kitchen-card[style*="grid-template-columns: 1fr auto"] {
                grid-template-columns: 1fr !important;
            }

            .kitchen-card[style*="grid-template-columns: repeat(auto-fit"] {
                grid-template-columns: 1fr !important;
            }

            .kitchen-btn {
                width: 100% !important;
                justify-content: center !important;
            }
        }

        /* Mobile */
        @media (max-width: 480px) {
            .kitchen-card[style*="font-size: 2.5rem"] {
                font-size: 1.5rem !important;
            }

            .kitchen-card[style*="font-size: 1.375rem"] {
                font-size: 1.125rem !important;
            }

            .kitchen-card[style*="font-size: 1.125rem"] {
                font-size: 1rem !important;
            }

            .kitchen-card[style*="padding: 0.5rem 1rem"] {
                padding: 0.375rem 0.75rem !important;
                font-size: 0.75rem !important;
            }

            .kitchen-card[style*="display: flex; align-items: center; gap: 0.75rem"] {
                flex-wrap: wrap !important;
            }
        }
    </style>
@endsection