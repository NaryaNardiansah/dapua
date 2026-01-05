<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class WishlistController extends Controller
{
	public function index(Request $request)
	{
		$items = $request->user()->wishlist()
			->with(['category'])
			->latest('wishlists.created_at')
			->paginate(12);

		return view('wishlist.index', compact('items'));
	}

	public function toggle(Request $request, Product $product)
	{
		$user = $request->user();
		$exists = $user->wishlist()->where('product_id', $product->id)->exists();

		if ($exists) {
			$user->wishlist()->detach($product->id);
			$message = 'Produk dihapus dari wishlist';
		} else {
			$user->wishlist()->attach($product->id);
			$message = 'Produk ditambahkan ke wishlist';
		}

		if ($request->ajax()) {
			return response()->json([
				'status' => 'success',
				'message' => $message,
				'in_wishlist' => !$exists
			]);
		}

		return back()->with('status', $message);
	}

	public function add(Request $request, Product $product)
	{
		$request->user()->wishlist()->syncWithoutDetaching([$product->id]);
		return back()->with('status', 'Ditambahkan ke wishlist');
	}

	public function remove(Request $request, Product $product)
	{
		$request->user()->wishlist()->detach($product->id);
		return back()->with('status', 'Dihapus dari wishlist');
	}
}
