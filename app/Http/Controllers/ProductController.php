<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
	public function index(Request $request)
	{
		$search = $request->string('q');
		$categorySlug = $request->string('category');

		$productsQuery = Product::query()->where('is_active', true);
		if ($search->isNotEmpty()) {
			$productsQuery->where('name', 'like', '%' . $search . '%');
		}
		if ($categorySlug->isNotEmpty()) {
			$category = Category::where('slug', $categorySlug)->first();
			if ($category) {
				$productsQuery->where('category_id', $category->id);
			}
		}

		$products = $productsQuery->orderBy('sort_order')->paginate(12)->withQueryString();

		// Get categories with active products count
		$categories = Category::where('is_active', true)
			->withCount([
				'products' => function ($query) {
					$query->where('is_active', true);
				}
			])
			->orderBy('sort_order')
			->get();

		return view('products.index', compact('products', 'categories', 'search', 'categorySlug'));
	}

	public function show(string $slug)
	{
		$product = Product::where('slug', $slug)
			->where('is_active', true)
			->with(['reviews.user', 'category'])
			->withCount('reviews')
			->withAvg('reviews', 'rating')
			->firstOrFail();

		$related = Product::where('category_id', $product->category_id)
			->where('id', '<>', $product->id)
			->where('is_active', true)
			->take(8)
			->get();

		return view('products.show', compact('product', 'related'));
	}
}
