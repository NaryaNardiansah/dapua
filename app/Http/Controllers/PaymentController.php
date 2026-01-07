<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingSetting;
use App\Models\DeliveryZone;
use App\Models\Analytics;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification as MidtransNotification;

class PaymentController extends Controller
{
	private function calculateShippingFee(?float $lat, ?float $lng): int
	{
		$storeLat = (float) ShippingSetting::getValue('store_lat', config('app.store_lat', env('STORE_LAT')));
		$storeLng = (float) ShippingSetting::getValue('store_lng', config('app.store_lng', env('STORE_LNG')));
		$base = (int) ShippingSetting::getValue('shipping_base', env('SHIPPING_BASE', 5000));
		$perKm = (int) ShippingSetting::getValue('shipping_per_km', env('SHIPPING_PER_KM', 2000));
		$maxDistance = (int) ShippingSetting::getValue('max_shipping_distance', 100);

		if ($lat === null || $lng === null) {
			return $base;
		}

		$distanceKm = $this->haversineDistanceKm($storeLat, $storeLng, $lat, $lng);

		// Check delivery zones first
		$deliveryZone = DeliveryZone::where('is_active', true)->get()->first(function ($zone) use ($lat, $lng) {
			return $zone->containsPoint($lat, $lng);
		});

		if ($deliveryZone) {
			$cost = $deliveryZone->calculateShippingCost($distanceKm);
			return $cost ? (int) $cost : 0;
		}

		// Check if distance exceeds maximum
		if ($distanceKm > $maxDistance) {
			return 0; // No shipping available
		}

		return (int) round($base + ($distanceKm * $perKm));
	}

	private function haversineDistanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
	{
		$earthRadius = 6371; // km
		$dLat = deg2rad($lat2 - $lat1);
		$dLon = deg2rad($lon2 - $lon1);
		$a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		return $earthRadius * $c;
	}

	// Public API: calculate shipping fee by lat/lng (for realtime UI)
	public function calcShipping(Request $request)
	{
		$validated = $request->validate([
			'lat' => ['required', 'numeric', 'between:-90,90'],
			'lng' => ['required', 'numeric', 'between:-180,180'],
		]);

		$lat = (float) $validated['lat'];
		$lng = (float) $validated['lng'];

		$storeLat = (float) ShippingSetting::getValue('store_lat', config('app.store_lat', env('STORE_LAT')));
		$storeLng = (float) ShippingSetting::getValue('store_lng', config('app.store_lng', env('STORE_LNG')));

		$distanceKm = $this->haversineDistanceKm($storeLat, $storeLng, $lat, $lng);
		$fee = $this->calculateShippingFee($lat, $lng);

		return response()->json([
			'distance_km' => round($distanceKm, 2),
			'shipping_fee' => (int) $fee,
			'formatted_distance' => number_format($distanceKm, 2) . ' km',
			'formatted_fee' => 'Rp ' . number_format($fee, 0, ',', '.'),
		]);
	}

	public function checkout(Request $request)
	{
		$cart = $request->session()->get('cart', []);
		if (empty($cart)) {
			return redirect()->route('cart.index')->with('status', 'Keranjang masih kosong');
		}

		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'phone' => ['required', 'string', 'max:30'],
			'address' => ['nullable', 'string', 'max:500'],
			'latitude' => ['nullable', 'numeric'],
			'longitude' => ['nullable', 'numeric'],
		]);

		$subtotal = 0;
		$items = [];
		foreach ($cart as $productId => $qty) {
			$product = Product::findOrFail($productId);
			$lineTotal = $product->current_price * (int) $qty;
			$subtotal += $lineTotal;
			$items[] = compact('product', 'qty', 'lineTotal');
		}

		$lat = $validated['latitude'] ?? null;
		$lng = $validated['longitude'] ?? null;
		$shipping = $this->calculateShippingFee($lat !== null ? (float) $lat : null, $lng !== null ? (float) $lng : null);
		$discount = 0;
		$grandTotal = $subtotal + $shipping - $discount;

		// Determine delivery zone
		$deliveryZone = null;
		$zoneMultiplier = 1.00;
		if ($lat !== null && $lng !== null) {
			$deliveryZone = DeliveryZone::where('is_active', true)->get()->first(function ($zone) use ($lat, $lng) {
				return $zone->containsPoint($lat, $lng);
			});
			if ($deliveryZone) {
				$zoneMultiplier = $deliveryZone->multiplier;
			}
		}

		$order = Order::create([
			'user_id' => auth()->id(),
			'status' => 'pending',
			'subtotal' => $subtotal,
			'shipping_fee' => $shipping,
			'discount_total' => $discount,
			'grand_total' => $grandTotal,
			'payment_method' => 'midtrans',
			'payment_status' => 'unpaid',
			'recipient_name' => $validated['name'],
			'recipient_phone' => $validated['phone'],
			'address_line' => $validated['address'] ?? 'Lokasi Peta (' . ($lat . ', ' . $lng) . ')',
			'latitude' => $lat,
			'longitude' => $lng,
			'distance_meters' => $lat && $lng ? (int) round($this->haversineDistanceKm((float) env('STORE_LAT'), (float) env('STORE_LNG'), (float) $lat, (float) $lng) * 1000) : null,
			'delivery_zone' => $deliveryZone ? $deliveryZone->slug : null,
			'zone_multiplier' => $zoneMultiplier,
		]);

		// Add timeline entry for order placement
		\App\Models\OrderTimeline::createStatusEntry($order, 'pending', [
			'method' => 'checkout',
			'notes' => 'Pesanan baru dibuat dari website'
		], 'customer', auth()->user());

		foreach ($items as $it) {
			OrderItem::create([
				'order_id' => $order->id,
				'product_id' => $it['product']->id,
				'product_name' => $it['product']->name,
				'unit_price' => $it['product']->current_price,
				'quantity' => (int) $it['qty'],
				'line_total' => $it['lineTotal'],
			]);
		}

		// Konfigurasi Midtrans
		MidtransConfig::$serverKey = config('services.midtrans.server_key');
		MidtransConfig::$isProduction = (bool) config('services.midtrans.is_production');
		MidtransConfig::$is3ds = true;

		$params = [
			'transaction_details' => [
				'order_id' => 'ORDER-' . $order->id . '-' . time(),
				'gross_amount' => $grandTotal,
			],
			'customer_details' => [
				'first_name' => $order->recipient_name,
				'phone' => $order->recipient_phone,
				'shipping_address' => [
					'first_name' => $order->recipient_name,
					'phone' => $order->recipient_phone,
					'address' => $order->address_line,
				],
			],
			'item_details' => collect($order->orderItems)->map(function ($oi) {
				return [
					'id' => (string) $oi->product_id,
					'price' => (int) $oi->unit_price,
					'quantity' => (int) $oi->quantity,
					'name' => $oi->product_name,
				];
			})->push([
						'id' => 'SHIPPING',
						'price' => (int) $order->shipping_fee,
						'quantity' => 1,
						'name' => 'Ongkos Kirim',
					])->when($order->discount_total > 0, function ($collection) use ($order) {
						return $collection->push([
							'id' => 'DISCOUNT',
							'price' => -(int) $order->discount_total,
							'quantity' => 1,
							'name' => 'Diskon',
						]);
					})->values()->all(),
		];

		$snapToken = Snap::getSnapToken($params);
		$order->update([
			'midtrans_order_id' => $params['transaction_details']['order_id'],
		]);

		// Generate tracking code
		$order->generateTracking();

		// kosongkan keranjang sesi
		$request->session()->forget('cart');

		// Load order items with product relationship
		$order->load('orderItems.product');

		return view('checkout', [
			'order' => $order,
			'snapToken' => $snapToken,
			'clientKey' => config('services.midtrans.client_key'),
		]);
	}

	public function paymentSuccess(Request $request)
	{
		$resultInput = $request->input('payment_result');
		if (!$resultInput) {
			return redirect()->route('home');
		}

		$result = json_decode($resultInput, true);
		$midtransOrderId = $result['order_id'] ?? null;

		if (!$midtransOrderId) {
			return redirect()->route('home');
		}

		// Configure Midtrans
		MidtransConfig::$serverKey = config('services.midtrans.server_key');
		MidtransConfig::$isProduction = (bool) config('services.midtrans.is_production');

		try {
			// Check status from Midtrans to be safe
			$status = (object) Transaction::status($midtransOrderId);
			$transactionStatus = $status->transaction_status;
			$fraudStatus = $status->fraud_status ?? null;

			// Extract Order ID from "ORDER-123-TIMESTAMP"
			$parts = explode('-', $midtransOrderId);
			if (count($parts) >= 2) {
				$dbOrderId = $parts[1];
				$order = Order::find($dbOrderId);

				if ($order) {
					// Update status based on transaction status
					if ($transactionStatus == 'capture') {
						if ($fraudStatus == 'challenge') {
							$order->update(['payment_status' => 'pending', 'status' => 'pending']);
						} else {
							$order->update(['payment_status' => 'paid', 'status' => 'diproses']);

							// Add timeline entry for payment
							\App\Models\OrderTimeline::createStatusEntry($order, 'paid', [
								'midtrans_order_id' => $midtransOrderId,
								'status' => $transactionStatus
							], 'system');

							// Also add diproses timeline
							\App\Models\OrderTimeline::createStatusEntry($order, 'diproses', [
								'notes' => 'Otomatis diproses setelah pembayaran berhasil'
							], 'system');
						}
					} else if ($transactionStatus == 'settlement') {
						$order->update(['payment_status' => 'paid', 'status' => 'diproses']);

						// Add timeline entry for payment
						\App\Models\OrderTimeline::createStatusEntry($order, 'paid', [
							'midtrans_order_id' => $midtransOrderId,
							'status' => $transactionStatus
						], 'system');

						// Also add diproses timeline
						\App\Models\OrderTimeline::createStatusEntry($order, 'diproses', [
							'notes' => 'Otomatis diproses setelah pembayaran berhasil'
						], 'system');
					} else if ($transactionStatus == 'pending') {
						$order->update(['payment_status' => 'pending', 'status' => 'pending']);
					} else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
						$order->update(['payment_status' => 'failed', 'status' => 'dibatalkan']);
					}

					// Save Midtrans ID if not present
					if (!$order->midtrans_order_id) {
						$order->midtrans_order_id = $midtransOrderId;
						$order->save();
					}

					// Ensure tracking code exists
					if (!$order->tracking_code) {
						$order->generateTracking();
					}

					return redirect()->route('tracking.show', $order->tracking_code)->with('success', 'Pembayaran berhasil!');
				}
			}
		} catch (\Exception $e) {
			\Log::error('Payment Success Error: ' . $e->getMessage());
			// If verification fails, try to redirect to tracking if we can guess the order
			// Or just redirect to orders index
		}

		return redirect()->route('orders.index');
	}

	public function webhook(Request $request)
	{
		MidtransConfig::$serverKey = config('services.midtrans.server_key');
		MidtransConfig::$isProduction = (bool) config('services.midtrans.is_production');
		$notification = new MidtransNotification();

		$orderId = $notification->order_id; // e.g., ORDER-123-...
		$transactionStatus = $notification->transaction_status;
		$fraudStatus = $notification->fraud_status ?? null;

		$order = Order::where('midtrans_order_id', $orderId)->first();
		if (!$order) {
			return response()->json(['message' => 'order not found'], 404);
		}

		switch ($transactionStatus) {
			case 'capture':
				if ($fraudStatus === 'challenge') {
					$order->update(['payment_status' => 'challenge']);
				} else {
					$order->update(['payment_status' => 'paid', 'status' => 'diproses']);

					// Add timeline entry for payment
					\App\Models\OrderTimeline::createStatusEntry($order, 'paid', [
						'midtrans_order_id' => $orderId,
						'status' => $transactionStatus
					], 'system');

					// Also add diproses timeline
					\App\Models\OrderTimeline::createStatusEntry($order, 'diproses', [
						'notes' => 'Otomatis diproses setelah pembayaran berhasil'
					], 'system');

					// Send tracking notification when payment is confirmed
					$order->sendTrackingNotification();
				}
				break;
			case 'settlement':
				$order->update(['payment_status' => 'paid', 'status' => 'diproses']);

				// Add timeline entry for payment
				\App\Models\OrderTimeline::createStatusEntry($order, 'paid', [
					'midtrans_order_id' => $orderId,
					'status' => $transactionStatus
				], 'system');

				// Also add diproses timeline
				\App\Models\OrderTimeline::createStatusEntry($order, 'diproses', [
					'notes' => 'Otomatis diproses setelah pembayaran berhasil'
				], 'system');

				// Send tracking notification when payment is settled
				$order->sendTrackingNotification();
				break;
			case 'pending':
				$order->update(['payment_status' => 'pending']);
				break;
			case 'deny':
			case 'cancel':
			case 'expire':
				$order->update(['payment_status' => $transactionStatus, 'status' => 'dibatalkan']);
				break;
		}

		return response()->json(['message' => 'ok']);
	}
}
