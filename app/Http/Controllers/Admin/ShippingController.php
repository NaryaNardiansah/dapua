<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingSetting;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $settings = [
            'store_lat' => ShippingSetting::getValue('store_lat', config('app.store_lat', '-0.947100')),
            'store_lng' => ShippingSetting::getValue('store_lng', config('app.store_lng', '100.417200')),
            'shipping_base' => ShippingSetting::getValue('shipping_base', config('app.shipping_base', '10000')),
            'shipping_per_km' => ShippingSetting::getValue('shipping_per_km', config('app.shipping_per_km', '2000')),
            'shipping_radius' => ShippingSetting::getValue('shipping_radius', config('app.shipping_radius', '50')),
            'free_shipping_min' => ShippingSetting::getValue('free_shipping_min', '0'),
            'max_shipping_distance' => ShippingSetting::getValue('max_shipping_distance', '100'),
        ];

        return view('admin.shipping.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_lat' => ['required', 'numeric', 'between:-90,90'],
            'store_lng' => ['required', 'numeric', 'between:-180,180'],
            'shipping_base' => ['required', 'numeric', 'min:0'],
            'shipping_per_km' => ['required', 'numeric', 'min:0'],
            'shipping_radius' => ['required', 'numeric', 'min:0'],
            'free_shipping_min' => ['nullable', 'numeric', 'min:0'],
            'max_shipping_distance' => ['required', 'numeric', 'min:1'],
        ]);

        // Update settings
        ShippingSetting::setValue('store_lat', $validated['store_lat'], 'Latitude toko');
        ShippingSetting::setValue('store_lng', $validated['store_lng'], 'Longitude toko');
        ShippingSetting::setValue('shipping_base', $validated['shipping_base'], 'Tarif dasar ongkir (Rp)');
        ShippingSetting::setValue('shipping_per_km', $validated['shipping_per_km'], 'Tarif per kilometer (Rp)');
        ShippingSetting::setValue('shipping_radius', $validated['shipping_radius'], 'Radius layanan (km)');
        ShippingSetting::setValue('free_shipping_min', $validated['free_shipping_min'] ?? 0, 'Minimum belanja gratis ongkir (Rp)');
        ShippingSetting::setValue('max_shipping_distance', $validated['max_shipping_distance'], 'Maksimum jarak pengiriman (km)');

        return redirect()->route('admin.shipping.index')->with('status', 'Pengaturan ongkir berhasil diperbarui');
    }

    public function testDistance(Request $request)
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $storeLat = ShippingSetting::getValue('store_lat', config('app.store_lat', '-0.947100'));
        $storeLng = ShippingSetting::getValue('store_lng', config('app.store_lng', '100.417200'));
        $shippingBase = ShippingSetting::getValue('shipping_base', config('app.shipping_base', '10000'));
        $shippingPerKm = ShippingSetting::getValue('shipping_per_km', config('app.shipping_per_km', '2000'));

        $distance = $this->calculateDistance($storeLat, $storeLng, $validated['lat'], $validated['lng']);
        $shippingFee = $this->calculateShippingFee($distance, $shippingBase, $shippingPerKm);

        return response()->json([
            'distance' => round($distance, 2),
            'shipping_fee' => $shippingFee,
            'formatted_distance' => number_format($distance, 2) . ' km',
            'formatted_fee' => 'Rp ' . number_format($shippingFee, 0, ',', '.'),
        ]);
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    private function calculateShippingFee($distance, $baseRate, $perKmRate)
    {
        return $baseRate + ($distance * $perKmRate);
    }
}
