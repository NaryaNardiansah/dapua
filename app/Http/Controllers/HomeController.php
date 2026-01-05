<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
	public function index()
	{
		$categories = Category::where('is_active', true)
			->withCount('products')
			->orderBy('sort_order')
			->get();

		$bestSellers = Product::where('is_active', true)
			->where('is_best_seller', true)
			->orderBy('sort_order')
			->take(8)
			->get();

		$latest = Product::where('is_active', true)
			->latest()
			->take(12)
			->get();

		// Get all active products for carousel (ordered by sort_order, then by created_at)
		$allProducts = Product::where('is_active', true)
			->with(['category', 'reviews'])
			->withCount('reviews as total_reviews')
			->withAvg('reviews as average_rating', 'rating')
			->orderBy('sort_order')
			->orderBy('created_at', 'desc')
			->get();

		return view('home', compact('categories', 'bestSellers', 'latest', 'allProducts'));
	}
}
