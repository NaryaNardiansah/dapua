<x-app-layout>
	<div class="p-6 space-y-6">
		<!-- Header -->
		<div class="flex items-center justify-between">
			<div class="flex items-center gap-4">
				<a href="{{ route('admin.products.index') }}" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
					<i class="fas fa-arrow-left mr-2"></i>Kembali ke Produk
				</a>
				<h1 class="text-2xl font-bold text-gray-800">
					<i class="fas fa-chart-bar mr-2 text-brand-600"></i>Analytics Produk
				</h1>
			</div>
			<div class="flex items-center gap-3">
				<a href="{{ route('admin.products.export') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
					<i class="fas fa-download mr-2"></i>Export Data
				</a>
			</div>
		</div>

		<!-- Statistics Overview -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
			<div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm font-medium text-gray-600">Total Produk</p>
						<p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_products']) }}</p>
					</div>
					<i class="fas fa-box text-blue-500 text-3xl"></i>
				</div>
			</div>
			<div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm font-medium text-gray-600">Produk Aktif</p>
						<p class="text-3xl font-bold text-gray-900">{{ number_format($stats['active_products']) }}</p>
					</div>
					<i class="fas fa-check-circle text-green-500 text-3xl"></i>
				</div>
			</div>
			<div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm font-medium text-gray-600">Best Sellers</p>
						<p class="text-3xl font-bold text-gray-900">{{ number_format($stats['best_sellers']) }}</p>
					</div>
					<i class="fas fa-star text-yellow-500 text-3xl"></i>
				</div>
			</div>
			<div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm font-medium text-gray-600">Featured</p>
						<p class="text-3xl font-bold text-gray-900">{{ number_format($stats['featured_products']) }}</p>
					</div>
					<i class="fas fa-gem text-purple-500 text-3xl"></i>
				</div>
			</div>
			<div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm font-medium text-gray-600">Stok Rendah</p>
						<p class="text-3xl font-bold text-gray-900">{{ number_format($stats['low_stock']) }}</p>
					</div>
					<i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
				</div>
			</div>
		</div>

		<!-- Performance Metrics -->
		<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
			<div class="bg-white rounded-lg shadow p-6">
				<div class="flex items-center justify-between mb-4">
					<h3 class="text-lg font-semibold text-gray-800">Total Penjualan</h3>
					<i class="fas fa-dollar-sign text-green-500 text-2xl"></i>
				</div>
				<div class="text-3xl font-bold text-green-600">
					Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}
				</div>
				<p class="text-sm text-gray-500 mt-2">Total revenue dari semua produk</p>
			</div>
			<div class="bg-white rounded-lg shadow p-6">
				<div class="flex items-center justify-between mb-4">
					<h3 class="text-lg font-semibold text-gray-800">Total Views</h3>
					<i class="fas fa-eye text-blue-500 text-2xl"></i>
				</div>
				<div class="text-3xl font-bold text-blue-600">
					{{ number_format($stats['total_views']) }}
				</div>
				<p class="text-sm text-gray-500 mt-2">Total views dari semua produk</p>
			</div>
			<div class="bg-white rounded-lg shadow p-6">
				<div class="flex items-center justify-between mb-4">
					<h3 class="text-lg font-semibold text-gray-800">Nilai Inventory</h3>
					<i class="fas fa-warehouse text-purple-500 text-2xl"></i>
				</div>
				<div class="text-3xl font-bold text-purple-600">
					Rp {{ number_format($stats['total_inventory_value'], 0, ',', '.') }}
				</div>
				<p class="text-sm text-gray-500 mt-2">Total nilai inventory</p>
			</div>
		</div>

		<!-- Top Selling Products -->
		<div class="bg-white rounded-lg shadow">
			<div class="px-6 py-4 border-b border-gray-200">
				<h3 class="text-lg font-semibold text-gray-800">
					<i class="fas fa-trophy mr-2 text-yellow-500"></i>Produk Terlaris
				</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terjual</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						@forelse($topSelling as $index => $product)
							<tr class="hover:bg-gray-50">
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="flex items-center">
										@if($index < 3)
											<i class="fas fa-medal text-{{ $index === 0 ? 'yellow' : ($index === 1 ? 'gray' : 'orange') }}-500 mr-2"></i>
										@endif
										<span class="text-sm font-medium text-gray-900">#{{ $index + 1 }}</span>
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="flex items-center">
										@if($product->image_path)
											<img src="{{ asset('storage/'.$product->image_path) }}" 
												 class="h-10 w-10 rounded-lg object-cover mr-3" 
												 alt="{{ $product->name }}">
										@else
											<div class="h-10 w-10 rounded-lg bg-gray-100 mr-3 flex items-center justify-center">
												<i class="fas fa-image text-gray-400"></i>
											</div>
										@endif
										<div>
											<div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
										</div>
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
									{{ $product->category->name ?? '-' }}
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
									Rp {{ number_format($product->total_sales, 0, ',', '.') }}
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
									{{ number_format($product->purchase_count) }}
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
									{{ number_format($product->view_count) }}
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="px-6 py-12 text-center text-gray-500">
									<i class="fas fa-chart-line text-4xl mb-4"></i>
									<div class="text-lg font-medium">Belum ada data penjualan</div>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Most Viewed Products -->
		<div class="bg-white rounded-lg shadow">
			<div class="px-6 py-4 border-b border-gray-200">
				<h3 class="text-lg font-semibold text-gray-800">
					<i class="fas fa-eye mr-2 text-blue-500"></i>Produk Paling Dilihat
				</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cart Count</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conversion</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						@forelse($mostViewed as $index => $product)
							<tr class="hover:bg-gray-50">
								<td class="px-6 py-4 whitespace-nowrap">
									<span class="text-sm font-medium text-gray-900">#{{ $index + 1 }}</span>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="flex items-center">
										@if($product->image_path)
											<img src="{{ asset('storage/'.$product->image_path) }}" 
												 class="h-10 w-10 rounded-lg object-cover mr-3" 
												 alt="{{ $product->name }}">
										@else
											<div class="h-10 w-10 rounded-lg bg-gray-100 mr-3 flex items-center justify-center">
												<i class="fas fa-image text-gray-400"></i>
											</div>
										@endif
										<div>
											<div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
										</div>
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
									{{ number_format($product->view_count) }}
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
									{{ number_format($product->cart_count) }}
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
									@if($product->view_count > 0)
										{{ number_format(($product->cart_count / $product->view_count) * 100, 1) }}%
									@else
										0%
									@endif
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="5" class="px-6 py-12 text-center text-gray-500">
									<i class="fas fa-eye text-4xl mb-4"></i>
									<div class="text-lg font-medium">Belum ada data views</div>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Recent Products -->
		<div class="bg-white rounded-lg shadow">
			<div class="px-6 py-4 border-b border-gray-200">
				<h3 class="text-lg font-semibold text-gray-800">
					<i class="fas fa-clock mr-2 text-gray-500"></i>Produk Terbaru
				</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						@forelse($recentProducts as $product)
							<tr class="hover:bg-gray-50">
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="flex items-center">
										@if($product->image_path)
											<img src="{{ asset('storage/'.$product->image_path) }}" 
												 class="h-10 w-10 rounded-lg object-cover mr-3" 
												 alt="{{ $product->name }}">
										@else
											<div class="h-10 w-10 rounded-lg bg-gray-100 mr-3 flex items-center justify-center">
												<i class="fas fa-image text-gray-400"></i>
											</div>
										@endif
										<div>
											<div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
										</div>
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
									{{ $product->category->name ?? '-' }}
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
									Rp {{ number_format($product->price, 0, ',', '.') }}
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									@if($product->is_active)
										<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
											<i class="fas fa-check mr-1"></i>Aktif
										</span>
									@else
										<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
											<i class="fas fa-edit mr-1"></i>Draft
										</span>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
									{{ $product->created_at->format('d M Y') }}
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="5" class="px-6 py-12 text-center text-gray-500">
									<i class="fas fa-box text-4xl mb-4"></i>
									<div class="text-lg font-medium">Belum ada produk</div>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</x-app-layout>








