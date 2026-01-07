<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTimeline extends Model
{
    use HasFactory;

    protected $table = 'order_timeline';

    protected $fillable = [
        'order_id',
        'status',
        'title',
        'description',
        'icon',
        'color',
        'timestamp',
        'metadata',
        'triggered_by',
        'user_id',
        'is_automatic',
        'is_visible_to_customer',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'metadata' => 'array',
        'is_automatic' => 'boolean',
        'is_visible_to_customer' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a timeline entry for order status change
     */
    public static function createStatusEntry(Order $order, string $status, array $metadata = [], string $triggeredBy = 'system', ?User $user = null): self
    {
        $statusConfig = self::getStatusConfig($status);

        return self::create([
            'order_id' => $order->id,
            'status' => $status,
            'title' => $statusConfig['title'],
            'description' => $statusConfig['description'],
            'icon' => $statusConfig['icon'],
            'color' => $statusConfig['color'],
            'timestamp' => now(),
            'metadata' => $metadata,
            'triggered_by' => $triggeredBy,
            'user_id' => $user?->id,
            'is_automatic' => $triggeredBy === 'system',
            'is_visible_to_customer' => $statusConfig['visible_to_customer'],
        ]);
    }

    /**
     * Get status configuration
     */
    public static function getStatusConfig(string $status): array
    {
        $configs = [
            'pending' => [
                'title' => 'Pesanan Diterima',
                'description' => 'Pesanan Anda telah diterima dan sedang dalam proses konfirmasi.',
                'icon' => 'fas fa-clock',
                'color' => '#F59E0B',
                'visible_to_customer' => true,
            ],
            'confirmed' => [
                'title' => 'Pesanan Dikonfirmasi',
                'description' => 'Pesanan Anda telah dikonfirmasi dan akan segera diproses.',
                'icon' => 'fas fa-check-circle',
                'color' => '#10B981',
                'visible_to_customer' => true,
            ],
            'preparation_started' => [
                'title' => 'Memulai Persiapan',
                'description' => 'Tim kami telah mulai mempersiapkan pesanan Anda.',
                'icon' => 'fas fa-utensils',
                'color' => '#3B82F6',
                'visible_to_customer' => true,
            ],
            'preparation_completed' => [
                'title' => 'Persiapan Selesai',
                'description' => 'Pesanan Anda telah siap dan menunggu pengambilan driver.',
                'icon' => 'fas fa-check-double',
                'color' => '#8B5CF6',
                'visible_to_customer' => true,
            ],
            'ready_for_pickup' => [
                'title' => 'Siap Diambil',
                'description' => 'Pesanan Anda siap untuk diambil oleh driver.',
                'icon' => 'fas fa-box',
                'color' => '#F59E0B',
                'visible_to_customer' => true,
            ],
            'out_for_delivery' => [
                'title' => 'Sedang Dikirim',
                'description' => 'Driver sedang dalam perjalanan mengantarkan pesanan Anda.',
                'icon' => 'fas fa-truck',
                'color' => '#EF4444',
                'visible_to_customer' => true,
            ],
            'driver_arrived' => [
                'title' => 'Driver Telah Tiba',
                'description' => 'Driver telah tiba di lokasi pengiriman.',
                'icon' => 'fas fa-map-marker-alt',
                'color' => '#10B981',
                'visible_to_customer' => true,
            ],
            'delivered' => [
                'title' => 'Pesanan Diterima',
                'description' => 'Pesanan Anda telah berhasil diterima. Terima kasih!',
                'icon' => 'fas fa-gift',
                'color' => '#059669',
                'visible_to_customer' => true,
            ],
            'cancelled' => [
                'title' => 'Pesanan Dibatalkan',
                'description' => 'Pesanan Anda telah dibatalkan.',
                'icon' => 'fas fa-times-circle',
                'color' => '#DC2626',
                'visible_to_customer' => true,
            ],
            'diproses' => [
                'title' => 'Pesanan Diproses',
                'description' => 'Pesanan Anda sedang diproses oleh tim kami.',
                'icon' => 'fas fa-utensils',
                'color' => '#3B82F6',
                'visible_to_customer' => true,
            ],
            'dikirim' => [
                'title' => 'Pesanan Dikirim',
                'description' => 'Driver sedang dalam perjalanan mengantarkan pesanan Anda.',
                'icon' => 'fas fa-truck',
                'color' => '#F59E0B',
                'visible_to_customer' => true,
            ],
            'selesai' => [
                'title' => 'Pesanan Selesai',
                'description' => 'Pesanan Anda telah berhasil diterima. Terima kasih!',
                'icon' => 'fas fa-check-circle',
                'color' => '#10B981',
                'visible_to_customer' => true,
            ],
            'dibatalkan' => [
                'title' => 'Pesanan Dibatalkan',
                'description' => 'Pesanan Anda telah dibatalkan.',
                'icon' => 'fas fa-times-circle',
                'color' => '#DC2626',
                'visible_to_customer' => true,
            ],
            'paid' => [
                'title' => 'Pembayaran Berhasil',
                'description' => 'Pembayaran Anda telah berhasil dikonfirmasi. Pesanan akan segera kami siapkan.',
                'icon' => 'fas fa-credit-card',
                'color' => '#10B981',
                'visible_to_customer' => true,
            ],
        ];

        return $configs[$status] ?? [
            'title' => 'Update Status',
            'description' => 'Status pesanan telah diperbarui.',
            'icon' => 'fas fa-info-circle',
            'color' => '#6B7280',
            'visible_to_customer' => true,
        ];
    }
}
