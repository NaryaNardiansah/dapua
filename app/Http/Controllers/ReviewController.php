<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
	public function store(Request $request, Product $product)
	{
		$validated = $request->validate([
			'rating' => ['required','integer','min:1','max:5'],
			'comment' => ['nullable','string','max:2000'],
		]);

		Review::updateOrCreate(
			['user_id' => $request->user()->id, 'product_id' => $product->id],
			['rating' => $validated['rating'], 'comment' => $validated['comment'] ?? null]
		);

		return back()->with('status', 'Terima kasih atas ulasannya!');
	}
}
