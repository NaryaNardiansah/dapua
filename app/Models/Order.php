<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
	protected $fillable = [
		'user_id',
		'status',
		'subtotal',
		'shipping_fee',
		'discount_total',
		'grand_total',
		'payment_method',
		'payment_status',
		'midtrans_order_id',
		'midtrans_transaction_id',
		'tracking_code',
		'tracking_url',
		'assigned_at',
		'driver_id',
		'picked_up_at',
		'delivered_at',
		'delivery_photo',
		'delivery_notes',
		'delivery_rating',
		'delivery_feedback',
		'delivery_zone',
		'estimated_delivery_at',
		'order_notes',
		'delivery_instructions',
		'is_cancellable',
		'cancelled_at',
		'cancellation_reason',
		'customer_notified',
		'last_status_update',
		'status_history',
		'recipient_name',
		'recipient_phone',
		'address_line',
		'latitude',
		'longitude',
		'distance_meters',
		'order_code',
	];

	protected $casts = [
		'assigned_at' => 'datetime',
		'picked_up_at' => 'datetime',
		'delivered_at' => 'datetime',
		'estimated_delivery_at' => 'datetime',
		'cancelled_at' => 'datetime',
		'last_status_update' => 'datetime',
		'status_history' => 'array',
		'is_cancellable' => 'boolean',
		'customer_notified' => 'boolean',
	];

	protected static function booted(): void
	{
		static::creating(function (Order $order) {
			if (empty($order->order_code)) {
				$prefix = 'DS-' . now()->format('Ymd') . '-';
				$seq = str_pad((string) (static::whereDate('created_at', now()->toDateString())->count() + 1), 3, '0', STR_PAD_LEFT);
				$order->order_code = $prefix . $seq;
			}
		});
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function orderItems(): HasMany
	{
		return $this->hasMany(OrderItem::class);
	}

	public function driver(): BelongsTo
	{
		return $this->belongsTo(User::class, 'driver_id');
	}

	public function deliveryZone(): BelongsTo
	{
		return $this->belongsTo(DeliveryZone::class, 'delivery_zone', 'slug');
	}

	public function communications(): HasMany
	{
		return $this->hasMany(OrderChat::class);
	}

	public function timeline(): HasMany
	{
		return $this->hasMany(OrderTimeline::class);
	}

	public function notifications(): HasMany
	{
		return $this->hasMany(Notification::class);
	}

	public function chats(): HasMany
	{
		return $this->hasMany(OrderChat::class);
	}

	public function driverLocations(): HasMany
	{
		return $this->hasMany(DriverLocation::class);
	}

	/**
	 * Generate tracking code and URL
	 */
	public function generateTracking()
	{
		if (!$this->tracking_code) {
			$this->tracking_code = 'TRK-' . strtoupper(substr(md5($this->id . time()), 0, 8));
			$this->tracking_url = route('tracking.show', $this->tracking_code);
			$this->save();
		}
		return $this;
	}

	/**
	 * Send tracking notification via SMS/WhatsApp
	 */
	public function sendTrackingNotification()
	{
		$this->generateTracking();

		// Use NotificationService for actual SMS/WhatsApp integration
		$notificationService = app(\App\Services\NotificationService::class);
		$success = $notificationService->sendTrackingNotification($this);

		\Log::info("Tracking notification sent for order {$this->order_code}", [
			'phone' => $this->recipient_phone,
			'tracking_url' => $this->tracking_url,
			'tracking_code' => $this->tracking_code,
			'success' => $success
		]);

		return $this;
	}

	/**
	 * Assign order to driver
	 */
	public function assignToDriver(User $driver)
	{
		$this->update([
			'driver_id' => $driver->id,
			'assigned_at' => now(),
			'status' => 'dikirim'
		]);

		// Send tracking notification to customer
		$this->sendTrackingNotification();

		// Add timeline entry
		\App\Models\OrderTimeline::createStatusEntry($this, 'dikirim', [
			'driver_name' => $driver->name,
			'notes' => 'Driver ditugaskan oleh admin'
		], 'admin', auth()->user());

		// Send notification to driver
		\Log::info("Order {$this->order_code} assigned to driver {$driver->name}");

		return $this;
	}

	/**
	 * Mark as picked up
	 */
	public function markAsPickedUp()
	{
		$this->update([
			'picked_up_at' => now(),
			'status' => 'dikirim'
		]);

		// Send tracking notification to customer
		$this->sendTrackingNotification();

		return $this;
	}

	/**
	 * Mark as delivered with photo proof
	 */
	public function markAsDelivered($photoPath = null, $notes = null)
	{
		$this->update([
			'delivered_at' => now(),
			'delivery_photo' => $photoPath,
			'delivery_notes' => $notes,
			'status' => 'selesai'
		]);

		return $this;
	}

	/**
	 * Add customer feedback
	 */
	public function addFeedback($rating, $feedback = null)
	{
		$this->update([
			'delivery_rating' => $rating,
			'delivery_feedback' => $feedback
		]);

		return $this;
	}

	/**
	 * Update order status with history tracking
	 */
	public function updateStatus($newStatus, $notes = null)
	{
		$oldStatus = $this->status;

		// Update status
		$this->update([
			'status' => $newStatus,
			'last_status_update' => now(),
			'customer_notified' => false
		]);

		// Add to status history
		$history = $this->status_history ?? [];
		$history[] = [
			'from_status' => $oldStatus,
			'to_status' => $newStatus,
			'timestamp' => now()->toDateTimeString(),
			'notes' => $notes
		];

		$this->update(['status_history' => $history]);

		// Add system communication
		$this->communications()->create([
			'sender_type' => 'system',
			'message_type' => 'system',
			'message' => "Order status changed from {$oldStatus} to {$newStatus}" . ($notes ? ": {$notes}" : ''),
			'metadata' => [
				'old_status' => $oldStatus,
				'new_status' => $newStatus
			]
		]);

		return $this;
	}

	/**
	 * Cancel order
	 */
	public function cancel($reason = null)
	{
		if (!$this->is_cancellable) {
			throw new \Exception('Order cannot be cancelled');
		}

		$this->updateStatus('dibatalkan', $reason);
		$this->update([
			'cancelled_at' => now(),
			'cancellation_reason' => $reason,
			'is_cancellable' => false
		]);

		return $this;
	}

	/**
	 * Estimate delivery time
	 */
	public function estimateDeliveryTime()
	{
		if ($this->status === 'selesai') {
			return $this->delivered_at;
		}

		$baseTime = now();

		switch ($this->status) {
			case 'pending':
				$baseTime = $this->created_at->addMinutes(30); // 30 min processing
				break;
			case 'diproses':
				$baseTime = $this->assigned_at ? $this->assigned_at->addMinutes(15) : now()->addMinutes(15);
				break;
			case 'dikirim':
				$baseTime = $this->picked_up_at ? $this->picked_up_at->addMinutes(30) : now()->addMinutes(30);
				break;
		}

		// Add distance-based time
		if ($this->distance_meters) {
			$distanceKm = $this->distance_meters / 1000;
			$travelTimeMinutes = $distanceKm * 3; // 3 minutes per km
			$baseTime = $baseTime->addMinutes($travelTimeMinutes);
		}

		$this->update(['estimated_delivery_at' => $baseTime]);
		return $baseTime;
	}

	/**
	 * Get order timeline
	 */
	public function getTimeline()
	{
		$timeline = [];

		// Created
		$timeline[] = [
			'status' => 'created',
			'title' => 'Order Created',
			'description' => 'Your order has been placed successfully',
			'timestamp' => $this->created_at,
			'completed' => true
		];

		// Assigned
		if ($this->assigned_at) {
			$timeline[] = [
				'status' => 'assigned',
				'title' => 'Driver Assigned',
				'description' => $this->driver ? "Assigned to {$this->driver->name}" : 'Driver assigned',
				'timestamp' => $this->assigned_at,
				'completed' => true
			];
		}

		// Picked Up
		if ($this->picked_up_at) {
			$timeline[] = [
				'status' => 'picked_up',
				'title' => 'Order Picked Up',
				'description' => 'Driver has picked up your order',
				'timestamp' => $this->picked_up_at,
				'completed' => true
			];
		}

		// In Transit
		if ($this->status === 'dikirim') {
			$timeline[] = [
				'status' => 'in_transit',
				'title' => 'In Transit',
				'description' => 'Your order is on the way',
				'timestamp' => $this->picked_up_at,
				'completed' => false,
				'current' => true
			];
		}

		// Delivered
		if ($this->delivered_at) {
			$timeline[] = [
				'status' => 'delivered',
				'title' => 'Delivered',
				'description' => 'Order has been delivered successfully',
				'timestamp' => $this->delivered_at,
				'completed' => true
			];
		}

		// Cancelled
		if ($this->status === 'dibatalkan') {
			$timeline[] = [
				'status' => 'cancelled',
				'title' => 'Order Cancelled',
				'description' => $this->cancellation_reason ?: 'Order has been cancelled',
				'timestamp' => $this->cancelled_at,
				'completed' => true
			];
		}

		return $timeline;
	}

	/**
	 * Check if order can be cancelled
	 */
	public function canBeCancelled()
	{
		return $this->is_cancellable &&
			in_array($this->status, ['pending', 'diproses']) &&
			!$this->cancelled_at;
	}

	/**
	 * Check if order can be modified
	 */
	public function canBeModified()
	{
		return $this->status === 'pending' &&
			$this->created_at->diffInMinutes(now()) < 15; // 15 minutes window
	}

	/**
	 * Get unread communications count
	 */
	public function getUnreadCommunicationsCount()
	{
		return $this->communications()
			->where('sender_type', '!=', 'customer')
			->where('is_read', false)
			->count();
	}
}
