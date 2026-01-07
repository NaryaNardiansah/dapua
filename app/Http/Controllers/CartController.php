<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Setting;

class CartController extends Controller
{
	public function index(Request $request)
	{
		$cart = collect($request->session()->get('cart', []));
		$items = [];
		$subtotal = 0;
		foreach ($cart as $productId => $quantity) {
			$product = Product::find($productId);
			if ($product) {
				$lineTotal = $product->current_price * $quantity;
				$subtotal += $lineTotal;
				$items[] = [
					'product' => $product,
					'quantity' => $quantity,
					'line_total' => $lineTotal,
				];
			}
		}

		// Get store location from shipping settings
		$storeLatitude = \App\Models\ShippingSetting::getValue('store_lat', '-0.947100');
		$storeLongitude = \App\Models\ShippingSetting::getValue('store_lng', '100.417200');

		// Get shipping settings
		$shippingBaseCost = \App\Models\ShippingSetting::getValue('shipping_base', 10000);
		$shippingCostPerKm = \App\Models\ShippingSetting::getValue('shipping_per_km', 2000);

		return view('cart.index', [
			'items' => $items,
			'subtotal' => $subtotal,
			'storeLatitude' => $storeLatitude,
			'storeLongitude' => $storeLongitude,
			'shippingBaseCost' => $shippingBaseCost,
			'shippingCostPerKm' => $shippingCostPerKm,
		]);
	}

	public function add(Request $request, Product $product)
	{
		$qty = max(1, (int) $request->input('quantity', 1));

		$cart = $request->session()->get('cart', []);
		$cart[$product->id] = ($cart[$product->id] ?? 0) + $qty;
		$request->session()->put('cart', $cart);

		// Jika ada parameter redirect_to, redirect ke halaman cart
		if ($request->input('redirect_to') === 'cart') {
			return redirect()->route('cart.index')->with('status', 'Produk ditambahkan ke keranjang');
		}

		return back()->with('status', 'Produk ditambahkan ke keranjang');
	}

	public function remove(Request $request, Product $product)
	{
		$cart = $request->session()->get('cart', []);
		unset($cart[$product->id]);
		$request->session()->put('cart', $cart);
		return back()->with('status', 'Produk dihapus dari keranjang');
	}

	public function update(Request $request, Product $product)
	{
		$qty = (int) $request->input('quantity', 1);

		if ($qty <= 0) {
			return $this->remove($request, $product);
		}

		$cart = $request->session()->get('cart', []);
		$cart[$product->id] = $qty;
		$request->session()->put('cart', $cart);

		return back()->with('status', 'Jumlah produk berhasil diperbarui');
	}
}
