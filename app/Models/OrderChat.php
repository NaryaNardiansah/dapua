<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sender_id',
        'sender_type',
        'message',
        'message_type',
        'metadata',
        'is_read',
        'read_at',
        'is_system_message',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_system_message' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Create a system message
     */
    public static function createSystemMessage(Order $order, string $message, string $messageType = 'text', array $metadata = []): self
    {
        return self::create([
            'order_id' => $order->id,
            'sender_id' => 1, // System user ID
            'sender_type' => 'system',
            'message' => $message,
            'message_type' => $messageType,
            'metadata' => $metadata,
            'is_system_message' => true,
        ]);
    }

    /**
     * Create a location message
     */
    public static function createLocationMessage(Order $order, User $sender, float $latitude, float $longitude, string $address = null): self
    {
        return self::create([
            'order_id' => $order->id,
            'sender_id' => $sender->id,
            'sender_type' => $sender->is_driver ? 'driver' : 'customer',
            'message' => $address ?? "Lokasi: {$latitude}, {$longitude}",
            'message_type' => 'location',
            'metadata' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'address' => $address,
            ],
        ]);
    }

    /**
     * Get unread messages count for order
     */
    public static function getUnreadCount(Order $order, string $userType): int
    {
        return self::where('order_id', $order->id)
            ->where('sender_type', '!=', $userType)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get recent messages for order
     */
    public static function getRecentMessages(Order $order, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('order_id', $order->id)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();
    }
}

