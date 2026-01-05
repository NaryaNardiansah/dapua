<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTimeline;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrackingApiController extends Controller
{
    /**
     * Get order tracking information
     */
    public function getTracking(Request $request, string $trackingCode): JsonResponse
    {
        try {
            $order = Order::where('tracking_code', $trackingCode)
                ->with(['orderItems', 'driver', 'timeline' => function($query) {
                    $query->where('is_visible_to_customer', true)
                          ->orderBy('timestamp', 'desc');
                }])
                ->firstOrFail();

            $eta = $this->calculateETA($order);
            $timeline = $order->timeline ?? collect();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'order_code' => $order->order_code,
                        'status' => $order->status,
                        'status_text' => ucfirst($order->status),
                        'progress_percentage' => $this->getProgressPercentage($order->status),
                        'created_at' => $order->created_at,
                        'estimated_delivery_at' => $order->estimated_delivery_at,
                        'grand_total' => $order->grand_total,
                        'shipping_fee' => $order->shipping_fee,
                    ],
                    'customer' => [
                        'name' => $order->recipient_name,
                        'phone' => $order->recipient_phone,
                        'address' => $order->address_line,
                        'coordinates' => $order->latitude && $order->longitude ? [
                            'lat' => $order->latitude,
                            'lng' => $order->longitude
                        ] : null,
                        'instructions' => $order->delivery_instructions,
                        'special_requests' => $order->special_requests,
                    ],
                    'driver' => $order->driver ? [
                        'id' => $order->driver->id,
                        'name' => $order->driver->name,
                        'phone' => $order->driver->phone ?? null,
                        'photo' => $order->driver->photo ?? null,
                        'vehicle_type' => $order->driver->vehicle_type,
                        'vehicle_number' => $order->driver->vehicle_number,
                        'license' => $order->driver->driver_license,
                        'location' => $order->driver->current_latitude && $order->driver->current_longitude ? [
                            'lat' => $order->driver->current_latitude,
                            'lng' => $order->driver->current_longitude,
                            'updated_at' => $order->driver->last_location_update
                        ] : null,
                        'is_available' => $order->driver->is_available
                    ] : null,
                    'timeline' => $timeline->map(function($item) {
                        return [
                            'id' => $item->id,
                            'status' => $item->status,
                            'title' => $item->title,
                            'description' => $item->description,
                            'icon' => $item->icon,
                            'color' => $item->color,
                            'timestamp' => $item->timestamp,
                            'metadata' => $item->metadata,
                            'triggered_by' => $item->triggered_by,
                        ];
                    }),
                    'eta' => $eta,
                    'communication' => [
                        'whatsapp_link' => $this->generateWhatsAppLink($order),
                        'can_cancel' => $order->is_cancellable && in_array($order->status, ['pending', 'diproses']),
                        'can_modify' => $order->status === 'pending',
                    ],
                    'performance' => $this->getPerformanceMetrics($order),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update driver location
     */
    public function updateDriverLocation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'order_id' => 'nullable|exists:orders,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $driver = Auth::user();
            
            if (!$driver->is_driver) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a driver'
                ], 403);
            }

            $driver->update([
                'current_latitude' => $request->latitude,
                'current_longitude' => $request->longitude,
                'last_location_update' => now(),
            ]);

            // If order_id is provided, update order timeline
            if ($request->order_id) {
                $order = Order::findOrFail($request->order_id);
                
                OrderTimeline::createStatusEntry(
                    $order,
                    'location_updated',
                    [
                        'driver_id' => $driver->id,
                        'driver_name' => $driver->name,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude
                    ],
                    'driver',
                    $driver
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully',
                'data' => [
                    'driver_id' => $driver->id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'updated_at' => now()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status (for drivers)
     */
    public function updateOrderStatus(Request $request, Order $order): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,diproses,dikirim,selesai,dibatalkan',
            'notes' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $driver = Auth::user();
            
            if (!$driver->is_driver) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a driver'
                ], 403);
            }

            $oldStatus = $order->status;
            
            $updateData = [
                'status' => $request->status,
                'last_status_update' => now(),
            ];

            // Handle photo upload for delivery confirmation
            if ($request->hasFile('photo') && $request->status === 'selesai') {
                $photoPath = $request->file('photo')->store('delivery-photos', 'public');
                $updateData['delivery_photo'] = $photoPath;
            }

            // Set specific timestamps based on status
            switch ($request->status) {
                case 'diproses':
                    $updateData['preparation_started_at'] = now();
                    break;
                case 'dikirim':
                    $updateData['out_for_delivery_at'] = now();
                    $updateData['picked_up_at'] = now();
                    break;
                case 'selesai':
                    $updateData['delivered_at'] = now();
                    break;
                case 'dibatalkan':
                    $updateData['cancelled_at'] = now();
                    $updateData['is_cancellable'] = false;
                    break;
            }

            $order->update($updateData);

            // Create timeline entry
            OrderTimeline::createStatusEntry(
                $order,
                $request->status,
                array_merge($request->metadata ?? [], [
                    'notes' => $request->notes,
                    'driver_updated' => true,
                    'photo_path' => $updateData['delivery_photo'] ?? null
                ]),
                'driver',
                $driver
            );

            // Send notifications
            $notificationService = app(NotificationService::class);
            $notificationService->sendStatusNotifications($order, $oldStatus, $request->status);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'data' => [
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'updated_at' => now()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get driver's assigned orders
     */
    public function getDriverOrders(Request $request): JsonResponse
    {
        try {
            $driver = Auth::user();
            
            if (!$driver->is_driver) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a driver'
                ], 403);
            }

            $status = $request->get('status', 'all');
            $query = $driver->driverOrders()->with(['user', 'orderItems']);

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $orders->map(function($order) {
                    return [
                        'id' => $order->id,
                        'order_code' => $order->order_code,
                        'status' => $order->status,
                        'status_text' => ucfirst($order->status),
                        'customer' => [
                            'name' => $order->recipient_name,
                            'phone' => $order->recipient_phone,
                            'address' => $order->address_line,
                            'coordinates' => $order->latitude && $order->longitude ? [
                                'lat' => $order->latitude,
                                'lng' => $order->longitude
                            ] : null,
                        ],
                        'total' => $order->grand_total,
                        'created_at' => $order->created_at,
                        'estimated_delivery_at' => $order->estimated_delivery_at,
                        'items_count' => $order->orderItems->count(),
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit customer feedback
     */
    public function submitFeedback(Request $request, Order $order): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
            'delivery_photo' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [
                'customer_rating' => $request->rating,
                'customer_feedback' => $request->feedback,
            ];

            if ($request->hasFile('delivery_photo')) {
                $photoPath = $request->file('delivery_photo')->store('customer-photos', 'public');
                $updateData['delivery_photo'] = $photoPath;
            }

            $order->update($updateData);

            // Create timeline entry
            OrderTimeline::createStatusEntry(
                $order,
                'feedback_received',
                [
                    'rating' => $request->rating,
                    'feedback' => $request->feedback,
                    'photo_path' => $updateData['delivery_photo'] ?? null
                ],
                'customer',
                Auth::user()
            );

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register device token for push notifications
     */
    public function registerDeviceToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required|string|max:255',
            'platform' => 'required|string|in:ios,android,web'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            $user->update([
                'device_token' => $request->device_token,
                'platform' => $request->platform,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Device token registered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register device token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate ETA for order
     */
    private function calculateETA(Order $order): ?array
    {
        if (!$order->estimated_delivery_time) {
            return null;
        }

        $now = now();
        $eta = \Carbon\Carbon::parse($order->estimated_delivery_time);
        
        return [
            'estimated_time' => $eta->format('H:i'),
            'estimated_date' => $eta->format('d M Y'),
            'minutes_remaining' => max(0, $now->diffInMinutes($eta, false)),
            'is_delayed' => $eta->isPast(),
            'delay_minutes' => $eta->isPast() ? $now->diffInMinutes($eta) : 0,
        ];
    }

    /**
     * Get progress percentage based on status
     */
    private function getProgressPercentage(string $status): int
    {
        $progress = [
            'pending' => 10,
            'diproses' => 30,
            'dikirim' => 70,
            'selesai' => 100,
            'dibatalkan' => 0,
        ];

        return $progress[$status] ?? 0;
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics(Order $order): array
    {
        return [
            'preparation_time' => $order->total_preparation_time_minutes,
            'delivery_time' => $order->total_delivery_time_minutes,
            'total_time' => ($order->total_preparation_time_minutes ?? 0) + ($order->total_delivery_time_minutes ?? 0),
            'delay_minutes' => $order->delay_minutes ?? 0,
            'performance_score' => $this->calculatePerformanceScore($order),
        ];
    }

    /**
     * Calculate performance score
     */
    private function calculatePerformanceScore(Order $order): int
    {
        $score = 100;
        
        // Deduct points for delays
        if ($order->delay_minutes > 0) {
            $score -= min(30, $order->delay_minutes);
        }
        
        // Deduct points for long preparation time
        if ($order->total_preparation_time_minutes > 30) {
            $score -= min(20, ($order->total_preparation_time_minutes - 30) * 2);
        }
        
        return max(0, $score);
    }

    /**
     * Generate WhatsApp link
     */
    private function generateWhatsAppLink(Order $order): string
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $order->recipient_phone);
        if (!str_starts_with($phoneNumber, '62')) {
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            } else {
                $phoneNumber = '62' . $phoneNumber;
            }
        }
        
        $message = "Halo {$order->recipient_name}, ini dari Dapur Sakura. Status pesanan #{$order->id}: " . ucfirst($order->status) . ". Apakah ada yang bisa kami bantu?";
        
        return "https://wa.me/{$phoneNumber}?text=" . urlencode($message);
    }
}
