<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryController extends Controller
{
	public function index(Request $request)
	{
		$search = $request->get('search');
		$status = $request->get('status', 'all');
		$sort = $request->get('sort', 'sort_order');
		$order = $request->get('order', 'asc');
		$view = $request->get('view', 'table'); // table, grid, tree

		$query = Category::withCount('products')
			->with('parent', 'children');

		// Search functionality
		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('name', 'like', "%{$search}%")
					->orWhere('description', 'like', "%{$search}%")
					->orWhere('meta_title', 'like', "%{$search}%");
			});
		}

		// Status filtering
		switch ($status) {
			case 'active':
				$query->where('is_active', true);
				break;
			case 'inactive':
				$query->where('is_active', false);
				break;
			case 'featured':
				$query->where('is_featured', true);
				break;
			case 'trending':
				$query->where('is_trending', true);
				break;
			case 'empty':
				$query->where('product_count', 0);
				break;
			case 'with_products':
				$query->where('product_count', '>', 0);
				break;
		}

		// Sorting
		switch ($sort) {
			case 'sales':
				$query->orderBy('total_sales', $order);
				break;
			case 'quantity':
				$query->orderBy('total_quantity_sold', $order);
				break;
			default:
				$query->orderBy($sort, $order);
				break;
		}

		$categories = $query->paginate(15)->withQueryString();

		// Get statistics
		$stats = [
			'total' => Category::count(),
			'active' => Category::where('is_active', true)->count(),
			'inactive' => Category::where('is_active', false)->count(),
			'featured' => Category::where('is_featured', true)->count(),
			'empty' => Category::where('product_count', 0)->count(),
			'total_products' => Category::sum('product_count'),
			'total_sales' => \App\Models\OrderItem::whereHas('order', function ($query) {
				$query->whereIn('status', ['selesai', 'dikirim']);
			})->sum('line_total'),
		];

		return view('admin.categories.index', compact('categories', 'search', 'status', 'sort', 'order', 'view', 'stats'));
	}

	public function create()
	{
		$parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
		return view('admin.categories.create', compact('parentCategories'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'color' => ['nullable', 'string', 'max:20'],
			'parent_id' => ['nullable', 'exists:categories,id'],
			'is_active' => ['nullable', 'boolean'],
			'is_featured' => ['nullable', 'boolean'],
			'is_trending' => ['nullable', 'boolean'],
			'sort_order' => ['nullable', 'integer', 'min:0'],
			'meta_title' => ['nullable', 'string', 'max:255'],
			'meta_description' => ['nullable', 'string', 'max:500'],
			'keywords' => ['nullable', 'string'],
			'promotional_text' => ['nullable', 'string', 'max:255'],
			'featured_until' => ['nullable', 'date'],
			'image' => ['nullable', 'image', 'max:2048'],
			'banner' => ['nullable', 'image', 'max:5120'],
			'icon' => ['nullable', 'image', 'max:1024'],
		]);

		DB::beginTransaction();
		try {
			$slug = Str::slug($validated['name']);

			// Handle file uploads
			$imagePath = null;
			$bannerPath = null;
			$iconPath = null;

			if ($request->hasFile('image')) {
				$imagePath = $request->file('image')->store('categories/images', 'public');
			}
			if ($request->hasFile('banner')) {
				$bannerPath = $request->file('banner')->store('categories/banners', 'public');
			}
			if ($request->hasFile('icon')) {
				$iconPath = $request->file('icon')->store('categories/icons', 'public');
			}

			// Calculate level
			$level = 0;
			if (!empty($validated['parent_id'] ?? null)) {
				$parent = Category::find($validated['parent_id']);
				$level = $parent->level + 1;
			}

			$category = Category::create([
				'name' => $validated['name'],
				'slug' => $slug,
				'description' => $validated['description'] ?? null,
				'color' => $validated['color'] ?? null,
				'parent_id' => $validated['parent_id'] ?? null,
				'level' => $level,
				'is_active' => (bool) ($validated['is_active'] ?? true),
				'is_featured' => (bool) ($validated['is_featured'] ?? false),
				'is_trending' => (bool) ($validated['is_trending'] ?? false),
				'sort_order' => $validated['sort_order'] ?? 0,
				'meta_title' => $validated['meta_title'] ?? null,
				'meta_description' => $validated['meta_description'] ?? null,
				'keywords' => $validated['keywords'] ?? null,
				'promotional_text' => $validated['promotional_text'] ?? null,
				'featured_until' => $validated['featured_until'] ?? null,
				'image' => $imagePath,
				'banner' => $bannerPath,
				'icon' => $iconPath,
			]);

			// Generate path
			$category->generatePath();

			DB::commit();
			return redirect()->route('admin.categories.index')->with('status', 'Kategori berhasil dibuat');
		} catch (\Exception $e) {
			DB::rollback();
			return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}
	}

	public function show(Category $category)
	{
		$category->load([
			'products' => function ($query) {
				$query->with('orderItems')->latest()->limit(10);
			},
			'children',
			'parent'
		]);

		$stats = [
			'total_products' => $category->products()->count(),
			'active_products' => $category->products()->where('is_active', true)->count(),
			'total_sales' => $category->total_sales,
			'view_count' => $category->view_count,
			'children_count' => $category->children()->count(),
		];

		return view('admin.categories.show', compact('category', 'stats'));
	}

	public function edit(Category $category)
	{
		$parentCategories = Category::whereNull('parent_id')
			->where('id', '!=', $category->id)
			->orderBy('name')
			->get();

		return view('admin.categories.edit', compact('category', 'parentCategories'));
	}

	public function update(Request $request, Category $category)
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'color' => ['nullable', 'string', 'max:20'],
			'parent_id' => ['nullable', 'exists:categories,id'],
			'is_active' => ['nullable', 'boolean'],
			'is_featured' => ['nullable', 'boolean'],
			'is_trending' => ['nullable', 'boolean'],
			'sort_order' => ['nullable', 'integer', 'min:0'],
			'meta_title' => ['nullable', 'string', 'max:255'],
			'meta_description' => ['nullable', 'string', 'max:500'],
			'keywords' => ['nullable', 'string'],
			'promotional_text' => ['nullable', 'string', 'max:255'],
			'featured_until' => ['nullable', 'date'],
			'image' => ['nullable', 'image', 'max:2048'],
			'banner' => ['nullable', 'image', 'max:5120'],
			'icon' => ['nullable', 'image', 'max:1024'],
		]);

		DB::beginTransaction();
		try {
			$slug = Str::slug($validated['name']);

			// Handle file uploads
			$imagePath = $category->image;
			$bannerPath = $category->banner;
			$iconPath = $category->icon;

			if ($request->hasFile('image')) {
				if ($category->image) {
					Storage::disk('public')->delete($category->image);
				}
				$imagePath = $request->file('image')->store('categories/images', 'public');
			}
			if ($request->hasFile('banner')) {
				if ($category->banner) {
					Storage::disk('public')->delete($category->banner);
				}
				$bannerPath = $request->file('banner')->store('categories/banners', 'public');
			}
			if ($request->hasFile('icon')) {
				if ($category->icon) {
					Storage::disk('public')->delete($category->icon);
				}
				$iconPath = $request->file('icon')->store('categories/icons', 'public');
			}

			// Calculate level
			$level = 0;
			if ($validated['parent_id']) {
				$parent = Category::find($validated['parent_id']);
				$level = $parent->level + 1;
			}

			$category->update([
				'name' => $validated['name'],
				'slug' => $slug,
				'description' => $validated['description'] ?? null,
				'color' => $validated['color'] ?? null,
				'parent_id' => $validated['parent_id'] ?? null,
				'level' => $level,
				'is_active' => isset($validated['is_active']) ? (bool) $validated['is_active'] : false,
				'is_featured' => isset($validated['is_featured']) ? (bool) $validated['is_featured'] : false,
				'is_trending' => isset($validated['is_trending']) ? (bool) $validated['is_trending'] : false,
				'sort_order' => $validated['sort_order'] ?? $category->sort_order,
				'meta_title' => $validated['meta_title'] ?? null,
				'meta_description' => $validated['meta_description'] ?? null,
				'keywords' => $validated['keywords'] ?? null,
				'promotional_text' => $validated['promotional_text'] ?? null,
				'featured_until' => $validated['featured_until'] ?? null,
				'image' => $imagePath,
				'banner' => $bannerPath,
				'icon' => $iconPath,
			]);

			// Generate path
			$category->generatePath();

			DB::commit();
			return redirect()->route('admin.categories.index')->with('status', 'Kategori diperbarui');
		} catch (\Exception $e) {
			DB::rollback();
			return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}
	}

	public function destroy(Category $category)
	{
		if (!$category->canBeDeleted()) {
			return redirect()->route('admin.categories.index')
				->with('error', "Tidak dapat menghapus kategori '{$category->name}' karena masih memiliki produk atau subkategori.");
		}

		DB::beginTransaction();
		try {
			// Delete files
			if ($category->image) {
				Storage::disk('public')->delete($category->image);
			}
			if ($category->banner) {
				Storage::disk('public')->delete($category->banner);
			}
			if ($category->icon) {
				Storage::disk('public')->delete($category->icon);
			}

			$category->delete();

			DB::commit();
			return redirect()->route('admin.categories.index')->with('status', 'Kategori dihapus');
		} catch (\Exception $e) {
			DB::rollback();
			return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}
	}

	// Bulk Operations
	public function bulkAction(Request $request)
	{
		$action = $request->input('action');
		$categoryIds = $request->input('category_ids', []);

		if (empty($categoryIds)) {
			return redirect()->back()->with('error', 'Pilih kategori terlebih dahulu');
		}

		DB::beginTransaction();
		try {
			switch ($action) {
				case 'activate':
					Category::whereIn('id', $categoryIds)->update(['is_active' => true]);
					$message = 'Kategori berhasil diaktifkan';
					break;
				case 'deactivate':
					Category::whereIn('id', $categoryIds)->update(['is_active' => false]);
					$message = 'Kategori berhasil dinonaktifkan';
					break;
				case 'feature':
					Category::whereIn('id', $categoryIds)->update(['is_featured' => true]);
					$message = 'Kategori berhasil ditandai sebagai featured';
					break;
				case 'unfeature':
					Category::whereIn('id', $categoryIds)->update(['is_featured' => false]);
					$message = 'Kategori berhasil dihapus dari featured';
					break;
				case 'delete':
					$categories = Category::whereIn('id', $categoryIds)->get();
					$deletable = $categories->filter->canBeDeleted();
					$nonDeletable = $categories->reject->canBeDeleted();

					if ($nonDeletable->count() > 0) {
						$names = $nonDeletable->pluck('name')->implode(', ');
						return redirect()->back()->with('error', "Tidak dapat menghapus kategori: {$names}");
					}

					Category::whereIn('id', $categoryIds)->delete();
					$message = 'Kategori berhasil dihapus';
					break;
				default:
					return redirect()->back()->with('error', 'Aksi tidak valid');
			}

			DB::commit();
			return redirect()->back()->with('status', $message);
		} catch (\Exception $e) {
			DB::rollback();
			return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}
	}

	// Analytics & Statistics
	public function analytics()
	{
		$stats = [
			'total_categories' => Category::count(),
			'active_categories' => Category::where('is_active', true)->count(),
			'featured_categories' => Category::where('is_featured', true)->count(),
			'empty_categories' => Category::where('product_count', 0)->count(),
			'total_products' => Category::sum('product_count'),
			'total_sales' => Category::sum('total_sales'),
			'total_views' => Category::sum('view_count'),
		];

		$topCategories = Category::withCount('products')
			->orderBy('total_sales', 'desc')
			->limit(10)
			->get();

		$recentCategories = Category::latest()->limit(5)->get();

		return view('admin.categories.analytics', compact('stats', 'topCategories', 'recentCategories'));
	}

	// Export functionality
	public function export(Request $request)
	{
		$format = $request->get('format', 'pdf');
		$categories = Category::with('parent')->get();

		$filename = 'categories_' . now()->format('Y-m-d_H-i-s') . '.' . $format;

		if ($format === 'pdf') {
			$pdf = Pdf::loadView('admin.categories.export_pdf', compact('categories'));
			return $pdf->download($filename);
		}

		if ($format === 'csv') {
			$headers = [
				'Content-Type' => 'text/csv',
				'Content-Disposition' => 'attachment; filename="' . $filename . '"',
			];

			$callback = function () use ($categories) {
				$file = fopen('php://output', 'w');

				// CSV Header
				fputcsv($file, [
					'ID',
					'Nama',
					'Slug',
					'Deskripsi',
					'Parent',
					'Level',
					'Aktif',
					'Featured',
					'Trending',
					'Jumlah Produk',
					'Total Sales',
					'View Count',
					'Sort Order',
					'Tanggal Dibuat'
				]);

				// CSV Data
				foreach ($categories as $category) {
					fputcsv($file, [
						$category->id,
						$category->name,
						$category->slug,
						$category->description,
						$category->parent ? $category->parent->name : '',
						$category->level,
						$category->is_active ? 'Ya' : 'Tidak',
						$category->is_featured ? 'Ya' : 'Tidak',
						$category->is_trending ? 'Ya' : 'Tidak',
						$category->product_count,
						$category->total_sales,
						$category->view_count,
						$category->sort_order,
						$category->created_at->format('Y-m-d H:i:s')
					]);
				}

				fclose($file);
			};

			return response()->stream($callback, 200, $headers);
		}

		return redirect()->back()->with('error', 'Format export tidak didukung');
	}

	/**
	 * Refresh sales data for all categories
	 */
	public function refreshSales(Request $request)
	{
		try {
			// Update all categories sales data
			$categories = Category::all();
			$updatedCount = 0;

			foreach ($categories as $category) {
				$category->updateSalesTotal();
				$updatedCount++;
			}

			return response()->json([
				'success' => true,
				'message' => "Sales data updated for {$updatedCount} categories",
				'updated_count' => $updatedCount
			]);

		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Failed to update sales data: ' . $e->getMessage()
			], 500);
		}
	}
}


