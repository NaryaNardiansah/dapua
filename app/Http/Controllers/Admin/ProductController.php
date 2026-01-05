<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
	public function index(Request $request)
	{
        $search = $request->get('q');
        $category = $request->get('category');
        $sort = $request->get('sort', 'latest');
        $status = $request->get('status');
        $quickFilter = $request->get('quick_filter');
        $priceMin = $request->get('price_min');
        $priceMax = $request->get('price_max');
        $stockFilter = $request->get('stock_filter');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $viewMode = $request->get('view_mode', 'table'); // table, grid

        $productsQuery = Product::with(['category', 'orderItems'])
            ->withCount(['orderItems as total_quantity_sold' => function($query) {
                $query->whereHas('order', function($orderQuery) {
                    $orderQuery->whereIn('status', ['selesai', 'dikirim']);
                });
            }])
            ->withSum(['orderItems as total_sales_amount' => function($query) {
                $query->whereHas('order', function($orderQuery) {
                    $orderQuery->whereIn('status', ['selesai', 'dikirim']);
                });
            }], 'line_total')
            ->withSum(['orderItems as total_quantity_sum' => function($query) {
                $query->whereHas('order', function($orderQuery) {
                    $orderQuery->whereIn('status', ['selesai', 'dikirim']);
                });
            }], 'quantity');
        
        // Status filtering
        if ($status === 'trashed') {
            $productsQuery->onlyTrashed();
        } else {
            if ($status === 'active') { $productsQuery->where('is_active', true); }
            if ($status === 'draft') { $productsQuery->where('is_active', false); }
            if ($status === 'featured') { $productsQuery->where('is_featured', true); }
            if ($status === 'new') { $productsQuery->where('is_new', true); }
            if ($status === 'on_sale') { $productsQuery->where('is_on_sale', true); }
        }

        // Quick filters
        if ($quickFilter === 'no_image') {
            $productsQuery->whereNull('image_path');
        }
        if ($quickFilter === 'best_seller') {
            $productsQuery->where('is_best_seller', true);
        }
        if ($quickFilter === 'no_sales') {
            $productsQuery->having('total_sales_amount', '=', 0);
        }

        // Enhanced search - comprehensive search across multiple fields
        if ($search) {
            $searchTerm = trim($search);
            $productsQuery->where(function($query) use ($searchTerm) {
                // Split search term into words for better matching
                $words = explode(' ', $searchTerm);
                
                foreach ($words as $word) {
                    if (strlen($word) > 2) { // Only search words longer than 2 characters
                        $query->where(function($subQuery) use ($word) {
                            // Exact matches (highest priority)
                            $subQuery->where('name', 'like', "%$word%")
                                    ->orWhere('slug', 'like', "%$word%");
                            
                            // Partial matches in descriptions
                            $subQuery->orWhere('description', 'like', "%$word%")
                                    ->orWhere('short_description', 'like', "%$word%")
                                    ->orWhere('specifications', 'like', "%$word%");
                            
                            // Meta fields
                            $subQuery->orWhere('meta_title', 'like', "%$word%")
                                    ->orWhere('meta_description', 'like', "%$word%")
                                    ->orWhere('meta_keywords', 'like', "%$word%");
                            
                            // Tags
                            $subQuery->orWhere('tags', 'like', "%$word%");
                            
                            // SKU and barcode (exact match for these)
                            if (Schema::hasColumn('products', 'sku')) {
                                $subQuery->orWhere('sku', 'like', "%$word%");
                            }
                            if (Schema::hasColumn('products', 'barcode')) {
                                $subQuery->orWhere('barcode', 'like', "%$word%");
                            }
                            
                            // Search by price (if word is numeric)
                            if (is_numeric($word)) {
                                $subQuery->orWhere('price', '=', $word)
                                        ->orWhere('sale_price', '=', $word);
                            }
                            
                            // Search by category
                            $subQuery->orWhereHas('category', function($catQuery) use ($word) {
                                $catQuery->where('name', 'like', "%$word%")
                                         ->orWhere('slug', 'like', "%$word%")
                                         ->orWhere('description', 'like', "%$word%");
                            });
                        });
                    }
                }
            });
        }

        // Category filter
        if ($category) {
            $cat = Category::where('slug', $category)->first();
            if ($cat) { $productsQuery->where('category_id', $cat->id); }
        }

        // Price range filter
        if ($priceMin) {
            $productsQuery->where('price', '>=', $priceMin);
        }
        if ($priceMax) {
            $productsQuery->where('price', '<=', $priceMax);
        }


        // Date range filter
        if ($dateFrom) {
            $productsQuery->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $productsQuery->whereDate('created_at', '<=', $dateTo);
        }

        // Sorting
        switch ($sort) {
            case 'price_asc': $productsQuery->orderBy('price', 'asc'); break;
            case 'price_desc': $productsQuery->orderBy('price', 'desc'); break;
            case 'best': $productsQuery->orderBy('is_best_seller', 'desc'); break;
            case 'sales': $productsQuery->orderBy('total_sales_amount', 'desc'); break;
            case 'views': $productsQuery->orderBy('view_count', 'desc'); break;
            case 'name': $productsQuery->orderBy('name', 'asc'); break;
            case 'relevance': 
                // If there's a search term, sort by relevance
                if ($search) {
                    $searchTerm = trim($search);
                    $productsQuery->orderByRaw("
                        CASE 
                            WHEN name LIKE ? THEN 1
                            WHEN slug LIKE ? THEN 2
                            WHEN description LIKE ? THEN 3
                            WHEN short_description LIKE ? THEN 4
                            WHEN tags LIKE ? THEN 5
                            ELSE 6
                        END
                    ", ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"]);
                } else {
                    $productsQuery->latest();
                }
                break;
            default: $productsQuery->latest(); break;
        }

        $products = $productsQuery->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        // Statistics
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'draft' => Product::where('is_active', false)->count(),
            'featured' => Product::where('is_featured', true)->count(),
            'best_seller' => Product::where('is_best_seller', true)->count(),
            'on_sale' => Product::where('is_on_sale', true)->count(),
            'total_value' => Product::sum('price'),
            'total_sales' => \App\Models\OrderItem::whereHas('order', function($query) {
                $query->whereIn('status', ['selesai', 'dikirim']);
            })->sum('line_total'),
        ];

        return view('admin.products.index', compact(
            'products', 'categories', 'search', 'category', 'sort', 'status', 
            'quickFilter', 'priceMin', 'priceMax', 'dateFrom', 
            'dateTo', 'viewMode', 'stats'
        ));
	}

    public function create()
    {
		$categories = Category::orderBy('name')->pluck('name','id');
		return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
		$validated = $request->validate([
			'category_id' => ['required','exists:categories,id'],
			'name' => ['required','string','max:255'],
			'price' => ['required','integer','min:0'],
			'sale_price' => ['nullable','integer','min:0'],
			'short_description' => ['nullable','string','max:500'],
			'description' => ['nullable','string'],
			'tags_input' => ['nullable','string'],
			'is_best_seller' => ['nullable','boolean'],
			'is_featured' => ['nullable','boolean'],
			'is_new' => ['nullable','boolean'],
			'is_on_sale' => ['nullable','boolean'],
			'is_active' => ['nullable','boolean'],
			'sort_order' => ['nullable','integer','min:0'],
			'image' => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
		]);

		$imagePath = null;
		if ($request->hasFile('image')) {
			$imagePath = $request->file('image')->store('products','public');
		}

		// Process tags
		$tags = [];
		if ($request->tags_input) {
			$tags = array_map('trim', explode(',', $request->tags_input));
			$tags = array_filter($tags);
		}

		Product::create([
			'category_id' => $validated['category_id'],
			'name' => $validated['name'],
			'slug' => Str::slug($validated['name']),
			'price' => $validated['price'],
			'sale_price' => $validated['sale_price'] ?? null,
			'short_description' => $validated['short_description'] ?? null,
			'description' => $validated['description'] ?? null,
			'tags' => $tags,
			'is_best_seller' => (bool) ($validated['is_best_seller'] ?? false),
			'is_featured' => (bool) ($validated['is_featured'] ?? false),
			'is_new' => (bool) ($validated['is_new'] ?? false),
			'is_on_sale' => (bool) ($validated['is_on_sale'] ?? false),
			'is_active' => (bool) ($validated['is_active'] ?? false),
			'sort_order' => $validated['sort_order'] ?? 0,
			'image_path' => $imagePath,
		]);

		return redirect()->route('admin.products.index')->with('status', 'Produk berhasil ditambahkan');
	}

    public function show(Product $product)
    {
        $product->load(['category', 'orderItems.order']);
        
        // Get sales statistics
        $salesStats = [
            'total_quantity_sold' => $product->orderItems()
                ->whereHas('order', function($query) {
                    $query->whereIn('status', ['selesai', 'dikirim']);
                })
                ->sum('quantity'),
            'total_sales_amount' => $product->orderItems()
                ->whereHas('order', function($query) {
                    $query->whereIn('status', ['selesai', 'dikirim']);
                })
                ->sum('line_total'),
            'total_orders' => $product->orderItems()
                ->whereHas('order', function($query) {
                    $query->whereIn('status', ['selesai', 'dikirim']);
                })
                ->distinct('order_id')
                ->count('order_id')
        ];
        
        // Get recent orders containing this product
        $recentOrders = $product->orderItems()
            ->with(['order.user'])
            ->whereHas('order', function($query) {
                $query->whereIn('status', ['selesai', 'dikirim']);
            })
            ->latest()
            ->limit(10)
            ->get();
        
        return view('admin.products.show', compact('product', 'salesStats', 'recentOrders'));
    }

    public function edit(Product $product)
    {
		$categories = Category::orderBy('name')->pluck('name','id');
		return view('admin.products.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
		$validated = $request->validate([
			'category_id' => ['required','exists:categories,id'],
			'name' => ['required','string','max:255'],
			'price' => ['required','integer','min:0'],
			'sale_price' => ['nullable','integer','min:0'],
			'short_description' => ['nullable','string','max:500'],
			'description' => ['nullable','string'],
			'tags_input' => ['nullable','string'],
			'is_best_seller' => ['nullable','boolean'],
			'is_featured' => ['nullable','boolean'],
			'is_new' => ['nullable','boolean'],
			'is_on_sale' => ['nullable','boolean'],
			'is_active' => ['nullable','boolean'],
			'sort_order' => ['nullable','integer','min:0'],
			'image' => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
		]);

		$imagePath = $product->image_path;
		if ($request->hasFile('image')) {
			if ($imagePath) { Storage::disk('public')->delete($imagePath); }
			$imagePath = $request->file('image')->store('products','public');
		}

		// Process tags
		$tags = $product->tags ?? [];
		if ($request->tags_input) {
			$tags = array_map('trim', explode(',', $request->tags_input));
			$tags = array_filter($tags);
		}

		$product->update([
			'category_id' => $validated['category_id'],
			'name' => $validated['name'],
			'slug' => Str::slug($validated['name']),
			'price' => $validated['price'],
			'sale_price' => $validated['sale_price'] ?? $product->sale_price,
			'short_description' => $validated['short_description'] ?? $product->short_description,
			'description' => $validated['description'] ?? $product->description,
			'tags' => $tags,
			'is_best_seller' => isset($validated['is_best_seller']) ? (bool) $validated['is_best_seller'] : false,
			'is_featured' => isset($validated['is_featured']) ? (bool) $validated['is_featured'] : false,
			'is_new' => isset($validated['is_new']) ? (bool) $validated['is_new'] : false,
			'is_on_sale' => isset($validated['is_on_sale']) ? (bool) $validated['is_on_sale'] : false,
			'is_active' => isset($validated['is_active']) ? (bool) $validated['is_active'] : false,
			'sort_order' => $validated['sort_order'] ?? $product->sort_order,
			'image_path' => $imagePath,
		]);

		return redirect()->route('admin.products.index')->with('status', 'Produk berhasil diperbarui');
	}

    public function destroy(Product $product)
    {
		$product->delete();
		return redirect()->route('admin.products.index')->with('status', 'Produk dihapus');
	}

	public function restore(Product $product)
	{
		$product->restore();
		return redirect()->route('admin.products.index')->with('status', 'Produk dipulihkan');
	}

	public function forceDelete(Product $product)
	{
		if ($product->image_path) { 
			Storage::disk('public')->delete($product->image_path); 
		}
		$product->forceDelete();
		return redirect()->route('admin.products.index')->with('status', 'Produk dihapus permanen');
	}

	// Bulk Operations
	public function bulkAction(Request $request)
	{
		$action = $request->get('action');
		$productIds = $request->get('product_ids', []);

		if (empty($productIds)) {
			return redirect()->back()->with('error', 'Pilih produk terlebih dahulu');
		}

		$products = Product::whereIn('id', $productIds);

		switch ($action) {
			case 'activate':
				$products->update(['is_active' => true]);
				$message = 'Produk berhasil diaktifkan';
				break;
			case 'deactivate':
				$products->update(['is_active' => false]);
				$message = 'Produk berhasil dinonaktifkan';
				break;
			case 'feature':
				$products->update(['is_featured' => true]);
				$message = 'Produk berhasil ditandai sebagai featured';
				break;
			case 'unfeature':
				$products->update(['is_featured' => false]);
				$message = 'Produk berhasil dihapus dari featured';
				break;
			case 'best_seller':
				$products->update(['is_best_seller' => true]);
				$message = 'Produk berhasil ditandai sebagai best seller';
				break;
			case 'remove_best_seller':
				$products->update(['is_best_seller' => false]);
				$message = 'Produk berhasil dihapus dari best seller';
				break;
			case 'delete':
				$products->delete();
				$message = 'Produk berhasil dihapus';
				break;
			case 'restore':
				Product::onlyTrashed()->whereIn('id', $productIds)->restore();
				$message = 'Produk berhasil dipulihkan';
				break;
			default:
				return redirect()->back()->with('error', 'Aksi tidak valid');
		}

		return redirect()->back()->with('status', $message);
	}

	// Analytics
	public function analytics()
	{
		$stats = [
			'total_products' => Product::count(),
			'active_products' => Product::where('is_active', true)->count(),
			'featured_products' => Product::where('is_featured', true)->count(),
			'best_sellers' => Product::where('is_best_seller', true)->count(),
			'on_sale' => Product::where('is_on_sale', true)->count(),
			'total_inventory_value' => Product::sum('price'),
			'total_sales' => Product::sum('total_sales'),
			'total_views' => Product::sum('view_count'),
		];

		$topSelling = Product::orderBy('total_sales', 'desc')->limit(10)->get();
		$mostViewed = Product::orderBy('view_count', 'desc')->limit(10)->get();
		$recentProducts = Product::latest()->limit(10)->get();

		return view('admin.products.analytics', compact('stats', 'topSelling', 'mostViewed', 'recentProducts'));
	}

	// Export
	public function export(Request $request)
	{
		$format = $request->get('format', 'csv');
		$products = Product::with('category')->get();

		$filename = 'products_' . now()->format('Y-m-d_H-i-s') . '.' . $format;

		if ($format === 'csv') {
			$headers = [
				'Content-Type' => 'text/csv',
				'Content-Disposition' => 'attachment; filename="' . $filename . '"',
			];

			$callback = function() use ($products) {
				$file = fopen('php://output', 'w');
				
				// CSV Headers
				fputcsv($file, [
					'ID', 'Nama', 'Kategori', 'Harga', 'Status', 
					'Best Seller', 'Featured', 'Total Sales', 'View Count', 'Tanggal Dibuat'
				]);

				// CSV Data
				foreach ($products as $product) {
					fputcsv($file, [
						$product->id,
						$product->name,
						$product->category->name ?? '',
						$product->price,
						$product->is_active ? 'Aktif' : 'Draft',
						$product->is_best_seller ? 'Ya' : 'Tidak',
						$product->is_featured ? 'Ya' : 'Tidak',
						$product->total_sales,
						$product->view_count,
						$product->created_at->format('Y-m-d H:i:s')
					]);
				}

				fclose($file);
			};

			return response()->stream($callback, 200, $headers);
		}

		return redirect()->back()->with('error', 'Format tidak didukung');
	}

	// Duplicate Product
	public function duplicate(Product $product)
	{
		$newProduct = $product->replicate();
		$newProduct->name = $product->name . ' (Copy)';
		$newProduct->sku = null; // SKU harus unik
		$newProduct->is_active = false; // Set as draft
		$newProduct->save();

		return redirect()->route('admin.products.edit', $newProduct)->with('status', 'Produk berhasil diduplikasi');
	}
}
 
 
