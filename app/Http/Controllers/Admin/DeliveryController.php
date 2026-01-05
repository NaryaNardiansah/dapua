<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\DeliveryZone;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    /**
     * Show delivery management dashboard
     */
    public function index()
    {
        $orders = Order::with(['driver', 'user'])
            ->whereIn('status', ['diproses', 'dikirim'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $availableDrivers = User::where('is_driver', true)
            ->where('is_available', true)
            ->get();

        $deliveryZones = DeliveryZone::where('is_active', true)->get();

        return view('admin.delivery.index', compact('orders', 'availableDrivers', 'deliveryZones'));
    }

    /**
     * Assign order to driver
     */
    public function assignDriver(Request $request, Order $order)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id'
        ]);

        $driver = User::findOrFail($request->driver_id);

        if (!$driver->is_driver || !$driver->is_available) {
            return back()->with('error', 'Driver tidak tersedia');
        }

        $order->assignToDriver($driver);

        return back()->with('status', "Pesanan {$order->order_code} telah ditugaskan ke {$driver->name}");
    }

    /**
     * Mark order as picked up
     */
    public function markPickedUp(Order $order)
    {
        $order->markAsPickedUp();

        return back()->with('status', "Pesanan {$order->order_code} telah diambil oleh kurir");
    }

    /**
     * Mark order as delivered with photo proof
     */
    public function markDelivered(Request $request, Order $order)
    {
        $request->validate([
            'delivery_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_notes' => 'nullable|string|max:500'
        ]);

        $photoPath = $request->file('delivery_photo')->store('delivery-proofs', 'public');

        $order->markAsDelivered($photoPath, $request->delivery_notes);

        return back()->with('status', "Pesanan {$order->order_code} telah diselesaikan");
    }

    /**
     * Update driver location
     */
    public function updateDriverLocation(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $driver = User::findOrFail($request->driver_id);
        $driver->updateLocation($request->latitude, $request->longitude);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi driver diperbarui'
        ]);
    }

    /**
     * Shipping cost management (Manajemen Ongkir)
     */
    public function zones()
    {
        // Get shipping settings
        $settings = [
            'store_lat' => \App\Models\ShippingSetting::getValue('store_lat', config('app.store_lat', '-6.2088')),
            'store_lng' => \App\Models\ShippingSetting::getValue('store_lng', config('app.store_lng', '106.8456')),
            'shipping_base' => \App\Models\ShippingSetting::getValue('shipping_base', '10000'),
            'shipping_per_km' => \App\Models\ShippingSetting::getValue('shipping_per_km', '2000'),
            'shipping_radius' => \App\Models\ShippingSetting::getValue('shipping_radius', '50'),
            'free_shipping_min' => \App\Models\ShippingSetting::getValue('free_shipping_min', '0'),
            'max_shipping_distance' => \App\Models\ShippingSetting::getValue('max_shipping_distance', '100'),
        ];

        return view('admin.delivery.shipping', compact('settings'));
    }

    /**
     * Update shipping cost settings
     */
    public function createZone(Request $request)
    {
        $request->validate([
            'store_lat' => 'required|numeric|between:-90,90',
            'store_lng' => 'required|numeric|between:-180,180',
            'shipping_base' => 'required|numeric|min:0',
            'shipping_per_km' => 'required|numeric|min:0',
            'shipping_radius' => 'required|numeric|min:0',
            'free_shipping_min' => 'nullable|numeric|min:0',
            'max_shipping_distance' => 'required|numeric|min:1',
        ]);

        // Update shipping settings
        \App\Models\ShippingSetting::setValue('store_lat', $request->store_lat, 'Latitude toko');
        \App\Models\ShippingSetting::setValue('store_lng', $request->store_lng, 'Longitude toko');
        \App\Models\ShippingSetting::setValue('shipping_base', $request->shipping_base, 'Tarif dasar ongkir (Rp)');
        \App\Models\ShippingSetting::setValue('shipping_per_km', $request->shipping_per_km, 'Tarif per kilometer (Rp)');
        \App\Models\ShippingSetting::setValue('shipping_radius', $request->shipping_radius, 'Radius layanan (km)');
        \App\Models\ShippingSetting::setValue('free_shipping_min', $request->free_shipping_min ?? 0, 'Minimum belanja gratis ongkir (Rp)');
        \App\Models\ShippingSetting::setValue('max_shipping_distance', $request->max_shipping_distance, 'Maksimum jarak pengiriman (km)');

        // Clear all cache to ensure changes are immediately visible
        \App\Models\ShippingSetting::clearCache();

        return back()->with('status', 'Pengaturan ongkir berhasil diperbarui');
    }

    /**
     * Update delivery zone (kept for backward compatibility, redirects to createZone)
     */
    public function updateZone(Request $request, DeliveryZone $zone)
    {
        return $this->createZone($request);
    }

    /**
     * Delete delivery zone (kept for backward compatibility)
     */
    public function deleteZone(DeliveryZone $zone)
    {
        // This method is kept for backward compatibility but not used in shipping management
        return back()->with('status', 'Fitur zona telah diubah menjadi manajemen ongkir');
    }

    /**
     * Weather integration for delivery planning
     */
    public function getWeather(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $weatherService = app(WeatherService::class);
        $weather = $weatherService->getCurrentWeather($request->latitude, $request->longitude);
        $recommendations = $weatherService->getDeliveryRecommendations($request->latitude, $request->longitude);
        $isSuitable = $weatherService->isDeliverySuitable($request->latitude, $request->longitude);

        return response()->json([
            'weather' => $weather,
            'recommendations' => $recommendations,
            'is_delivery_suitable' => $isSuitable,
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}

