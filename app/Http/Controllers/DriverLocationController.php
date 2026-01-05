<?php

namespace App\Http\Controllers;

use App\Models\DriverLocation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DriverLocationController extends Controller
{
    /**
     * Update driver location
     */
    public function updateLocation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'sometimes|numeric|min:0',
            'speed' => 'sometimes|numeric|min:0',
            'heading' => 'sometimes|numeric|between:0,360',
            'order_id' => 'sometimes|exists:orders,id',
            'status' => 'sometimes|string|in:online,offline,busy,delivering',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $driver = Auth::user();
        
        if (!$driver->is_driver) {
            return response()->json([
                'success' => false,
                'message' => 'Only drivers can update location',
            ], 403);
        }

        $location = DriverLocation::updateLocation($driver, 
            $request->latitude, 
            $request->longitude, 
            $request->only(['accuracy', 'speed', 'heading', 'order_id', 'status', 'metadata'])
        );

        // Broadcast location update
        $this->broadcastLocationUpdate($location);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => [
                'id' => $location->id,
                'driver_id' => $location->driver_id,
                'driver_name' => $location->driver->name,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'accuracy' => $location->accuracy,
                'speed' => $location->speed,
                'heading' => $location->heading,
                'status' => $location->status,
                'last_seen_at' => $location->last_seen_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Get driver's current location
     */
    public function getCurrentLocation(): JsonResponse
    {
        $driver = Auth::user();
        
        if (!$driver->is_driver) {
            return response()->json([
                'success' => false,
                'message' => 'Only drivers can access this endpoint',
            ], 403);
        }

        $location = DriverLocation::getLatestLocation($driver);

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'No location data found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $location->id,
                'driver_id' => $location->driver_id,
                'driver_name' => $location->driver->name,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'accuracy' => $location->accuracy,
                'speed' => $location->speed,
                'heading' => $location->heading,
                'status' => $location->status,
                'last_seen_at' => $location->last_seen_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Get driver location for specific order
     */
    public function getOrderDriverLocation(Order $order): JsonResponse
    {
        if (!$order->driver_id) {
            return response()->json([
                'success' => false,
                'message' => 'No driver assigned to this order',
            ], 404);
        }

        $location = DriverLocation::where('driver_id', $order->driver_id)
            ->where('order_id', $order->id)
            ->orderBy('last_seen_at', 'desc')
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'No location data found for this order',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $location->id,
                'driver_id' => $location->driver_id,
                'driver_name' => $location->driver->name,
                'driver_phone' => $location->driver->phone ?? null,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'accuracy' => $location->accuracy,
                'speed' => $location->speed,
                'heading' => $location->heading,
                'status' => $location->status,
                'last_seen_at' => $location->last_seen_at->format('Y-m-d H:i:s'),
                'distance_to_destination' => $this->calculateDistanceToDestination($location, $order),
            ],
        ]);
    }

    /**
     * Get nearby drivers
     */
    public function getNearbyDrivers(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'sometimes|numeric|min:0.1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $drivers = DriverLocation::getNearbyDrivers(
            $request->latitude,
            $request->longitude,
            $request->radius ?? 10
        );

        return response()->json([
            'success' => true,
            'data' => $drivers->map(function ($location) {
                return [
                    'id' => $location->id,
                    'driver_id' => $location->driver_id,
                    'driver_name' => $location->driver->name,
                    'driver_phone' => $location->driver->phone ?? null,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'accuracy' => $location->accuracy,
                    'speed' => $location->speed,
                    'heading' => $location->heading,
                    'status' => $location->status,
                    'distance' => round($location->distance, 2),
                    'last_seen_at' => $location->last_seen_at->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }

    /**
     * Calculate distance to destination
     */
    private function calculateDistanceToDestination(DriverLocation $location, Order $order): ?float
    {
        if (!$order->latitude || !$order->longitude) {
            return null;
        }

        return DriverLocation::calculateDistance(
            $location->latitude,
            $location->longitude,
            $order->latitude,
            $order->longitude
        );
    }

    /**
     * Broadcast location update
     */
    private function broadcastLocationUpdate(DriverLocation $location): void
    {
        // This would integrate with WebSockets, Pusher, or similar real-time service
        \Log::info('Broadcasting location update', [
            'driver_id' => $location->driver_id,
            'order_id' => $location->order_id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);
    }
}

