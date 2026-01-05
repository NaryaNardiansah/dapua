<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'order_id',
        'user_id',
        'type',
        'channel',
        'title',
        'message',
        'data',
        'status',
        'scheduled_at',
        'sent_at',
        'delivered_at',
        'error_message',
        'retry_count',
        'metadata',
    ];

    protected $casts = [
        'data' => 'array',
        'metadata' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
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
     * Create a notification for order update
     */
    public static function createOrderNotification(Order $order, string $type, string $channel, string $title, string $message, array $data = [], ?\DateTime $scheduledAt = null): self
    {
        return self::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'type' => $type,
            'channel' => $channel,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'status' => 'pending',
            'scheduled_at' => $scheduledAt ?? now(),
        ]);
    }

    /**
     * Mark notification as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark notification as delivered
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark notification as failed
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'retry_count' => $this->retry_count + 1,
        ]);
    }

    /**
     * Get pending notifications
     */
    public static function getPendingNotifications(): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('status', 'pending')
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at')
            ->get();
    }

    /**
     * Get notification templates
     */
    public static function getTemplates(): array
    {
        return [
            'order_confirmed' => [
                'email' => [
                    'title' => 'Pesanan Dikonfirmasi - Dapur Sakura',
                    'message' => 'Pesanan #{order_code} Anda telah dikonfirmasi dan sedang diproses.',
                ],
                'sms' => [
                    'title' => 'Pesanan Dikonfirmasi',
                    'message' => 'Pesanan #{order_code} Anda telah dikonfirmasi. Estimasi pengiriman: {estimated_time}',
                ],
                'whatsapp' => [
                    'title' => 'Pesanan Dikonfirmasi',
                    'message' => 'Halo {customer_name}, pesanan #{order_code} Anda telah dikonfirmasi dan sedang diproses. Estimasi pengiriman: {estimated_time}',
                ],
            ],
            'order_prepared' => [
                'email' => [
                    'title' => 'Pesanan Siap - Dapur Sakura',
                    'message' => 'Pesanan #{order_code} Anda telah siap dan akan segera dikirim.',
                ],
                'sms' => [
                    'title' => 'Pesanan Siap',
                    'message' => 'Pesanan #{order_code} Anda telah siap. Driver akan segera mengambil pesanan.',
                ],
                'whatsapp' => [
                    'title' => 'Pesanan Siap',
                    'message' => 'Halo {customer_name}, pesanan #{order_code} Anda telah siap dan driver akan segera mengambil pesanan.',
                ],
            ],
            'order_out_for_delivery' => [
                'email' => [
                    'title' => 'Pesanan Sedang Dikirim - Dapur Sakura',
                    'message' => 'Pesanan #{order_code} Anda sedang dalam perjalanan dengan driver {driver_name}.',
                ],
                'sms' => [
                    'title' => 'Pesanan Sedang Dikirim',
                    'message' => 'Pesanan #{order_code} Anda sedang dikirim oleh {driver_name}. Estimasi tiba: {estimated_time}',
                ],
                'whatsapp' => [
                    'title' => 'Pesanan Sedang Dikirim',
                    'message' => 'Halo {customer_name}, pesanan #{order_code} Anda sedang dikirim oleh {driver_name}. Estimasi tiba: {estimated_time}',
                ],
            ],
            'order_delivered' => [
                'email' => [
                    'title' => 'Pesanan Diterima - Dapur Sakura',
                    'message' => 'Pesanan #{order_code} Anda telah berhasil diterima. Terima kasih telah memilih Dapur Sakura!',
                ],
                'sms' => [
                    'title' => 'Pesanan Diterima',
                    'message' => 'Pesanan #{order_code} Anda telah diterima. Silakan berikan rating dan review.',
                ],
                'whatsapp' => [
                    'title' => 'Pesanan Diterima',
                    'message' => 'Halo {customer_name}, pesanan #{order_code} Anda telah berhasil diterima. Terima kasih telah memilih Dapur Sakura! Silakan berikan rating dan review.',
                ],
            ],
        ];
    }
}
