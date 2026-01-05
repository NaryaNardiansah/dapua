<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $reviews = $query->latest()->paginate(20);
        $products = Product::orderBy('name')->get();

        return view('admin.reviews.index', compact('reviews', 'products'));
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('status', 'Ulasan berhasil dihapus');
    }
}
