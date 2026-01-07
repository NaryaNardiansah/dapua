<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DeliveryController as AdminDeliveryController;
use App\Http\Controllers\Admin\DriverController as AdminDriverController;
use App\Http\Controllers\Admin\TrackingDashboardController as AdminTrackingDashboardController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DriverLocationController;
use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Artisan;

Route::get('/db-migrate-sakura', function () {
	try {
		Artisan::call('migrate', ['--force' => true]);
		return "Migrasi Berhasil! Semua tabel sudah terbuat di Supabase.";
	} catch (\Exception $e) {
		return "Gagal Migrasi: " . $e->getMessage();
	}
});

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public product browse and search
Route::get('/menu', [ProductController::class, 'index'])->name('products.index');
Route::get('/menu/{slug}', [ProductController::class, 'show'])->name('products.show');

// Simple session cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout & Payment
Route::middleware('auth')->group(function () {
	Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');

	// Wishlist
	Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
	Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
	Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
	Route::post('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
	// Reviews
	Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});
// Public shipping fee calc (for realtime in checkout)
Route::post('/shipping/calc', [PaymentController::class, 'calcShipping'])->name('shipping.calc');
Route::post('/midtrans/webhook', [PaymentController::class, 'webhook'])->name('midtrans.webhook');

// Social Auth
Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])->whereIn('provider', ['google', 'facebook'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->whereIn('provider', ['google', 'facebook'])->name('social.callback');

// Tracking (Public)
Route::get('/tracking/{trackingCode}', [TrackingController::class, 'show'])->name('tracking.show');
Route::get('/api/tracking/{trackingCode}', [TrackingController::class, 'api'])->name('tracking.api');
Route::post('/tracking/{trackingCode}/status', [TrackingController::class, 'updateStatus'])->name('tracking.update-status');
Route::post('/tracking/{trackingCode}/feedback', [TrackingController::class, 'feedback'])->name('tracking.feedback');
Route::post('/tracking/{trackingCode}/cancel', [TrackingController::class, 'cancel'])->name('tracking.cancel');
Route::post('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/tracking/{trackingCode}/share', [TrackingController::class, 'share'])->name('tracking.share');

// Chat System (Public for customers, Auth for drivers)
Route::get('/tracking/{trackingCode}/chat', [ChatController::class, 'getMessages'])->name('tracking.chat.messages');
Route::post('/tracking/{trackingCode}/chat', [ChatController::class, 'sendMessage'])->name('tracking.chat.send');
Route::post('/tracking/{trackingCode}/chat/location', [ChatController::class, 'sendLocation'])->name('tracking.chat.location');
Route::post('/tracking/{trackingCode}/chat/read', [ChatController::class, 'markAsRead'])->name('tracking.chat.read');
Route::get('/tracking/{trackingCode}/chat/unread', [ChatController::class, 'getUnreadCount'])->name('tracking.chat.unread');

// Driver Location (Public for testing, Auth required for updates)
Route::get('/tracking/{trackingCode}/driver-location', [DriverLocationController::class, 'getOrderDriverLocation'])->name('tracking.driver.location');

Route::middleware('auth')->group(function () {
	Route::post('/driver/location', [DriverLocationController::class, 'updateLocation'])->name('driver.location.update');
	Route::get('/driver/location', [DriverLocationController::class, 'getCurrentLocation'])->name('driver.location.current');
	Route::get('/driver/nearby', [DriverLocationController::class, 'getNearbyDrivers'])->name('driver.nearby');
});

// Order Management (Authenticated)
Route::middleware('auth')->group(function () {
	Route::get('/my-orders', [OrderManagementController::class, 'index'])->name('orders.index');
	Route::get('/orders/{order}/details', [OrderManagementController::class, 'getOrderDetails'])->name('orders.details');
	Route::post('/orders/{order}/cancel', [OrderManagementController::class, 'cancelOrder'])->name('orders.cancel');
	Route::post('/orders/{order}/message', [OrderManagementController::class, 'sendMessage'])->name('orders.message');
	Route::get('/orders/{order}/communications', [OrderManagementController::class, 'getCommunications'])->name('orders.communications');
	Route::post('/orders/{order}/mark-read', [OrderManagementController::class, 'markAsRead'])->name('orders.mark-read');
	Route::post('/orders/{order}/instructions', [OrderManagementController::class, 'updateDeliveryInstructions'])->name('orders.instructions');
	Route::post('/orders/{order}/reorder', [OrderManagementController::class, 'reorder'])->name('orders.reorder');
	Route::get('/orders/{order}/realtime', [OrderManagementController::class, 'getRealtimeStatus'])->name('orders.realtime');
	Route::get('/orders/{order}/timeline', [OrderManagementController::class, 'getTimeline'])->name('orders.timeline');
	Route::post('/orders/{order}/share', [OrderManagementController::class, 'shareOrder'])->name('orders.share');
	Route::get('/orders/{order}/invoice', [OrderManagementController::class, 'downloadInvoice'])->name('orders.invoice');
});

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Driver routes
Route::middleware(['auth', 'driver'])->prefix('driver')->name('driver.')->group(function () {
	Route::get('/', [DriverController::class, 'dashboard'])->name('dashboard');
	Route::get('/orders', [DriverController::class, 'orders'])->name('orders.index');
	Route::get('/orders/{order}', [DriverController::class, 'showOrder'])->name('orders.show');
	Route::post('/orders/{order}/update-status', [DriverController::class, 'updateOrderStatus'])->name('orders.update-status');
	Route::get('/orders/active/list', [DriverController::class, 'getActiveOrders'])->name('orders.active');
	Route::post('/location/update', [DriverController::class, 'updateLocation'])->name('location.update');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
	Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
	Route::get('/dashboard/export-pdf', [AdminDashboardController::class, 'exportPdf'])->name('dashboard.export-pdf');

	// Category routes
	Route::get('categories/export', [AdminCategoryController::class, 'export'])->name('categories.export');
	Route::post('categories/bulk-action', [AdminCategoryController::class, 'bulkAction'])->name('categories.bulk-action');
	Route::post('categories/refresh-sales', [AdminCategoryController::class, 'refreshSales'])->name('categories.refresh-sales');
	Route::resource('categories', AdminCategoryController::class);

	// Product routes
	Route::get('products/export', [AdminProductController::class, 'export'])->name('products.export');
	Route::post('products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');
	Route::post('products/{product}/restore', [AdminProductController::class, 'restore'])->name('products.restore');
	Route::post('products/{product}/duplicate', [AdminProductController::class, 'duplicate'])->name('products.duplicate');
	Route::delete('products/{product}/force-delete', [AdminProductController::class, 'forceDelete'])->name('products.force-delete');
	Route::resource('products', AdminProductController::class);
	Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);
	Route::post('orders/{order}/check-payment', [AdminOrderController::class, 'checkPaymentStatus'])->name('orders.check-payment');
	Route::resource('users', AdminUserController::class)->only(['index', 'show', 'destroy']);
	Route::post('users/{user}/block', [AdminUserController::class, 'block'])->name('users.block');
	Route::post('users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('users.unblock');
	Route::post('users/{user}/assign-role', [AdminUserController::class, 'assignRole'])->name('users.assign-role');
	Route::post('users/{user}/remove-role', [AdminUserController::class, 'removeRole'])->name('users.remove-role');
	Route::post('users/{user}/sync-roles', [AdminUserController::class, 'syncRoles'])->name('users.sync-roles');
	Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'destroy']);

	// Driver Management Routes
	Route::get('driver', [AdminDriverController::class, 'index'])->name('driver.index');
	Route::get('driver/create', [AdminDriverController::class, 'create'])->name('driver.create');
	Route::post('driver', [AdminDriverController::class, 'store'])->name('driver.store');
	Route::get('driver/{driver}', [AdminDriverController::class, 'show'])->name('driver.show');
	Route::get('driver/{driver}/edit', [AdminDriverController::class, 'edit'])->name('driver.edit');
	Route::put('driver/{driver}', [AdminDriverController::class, 'update'])->name('driver.update');
	Route::delete('driver/{driver}', [AdminDriverController::class, 'destroy'])->name('driver.destroy');


	// Delivery Management
	Route::get('delivery', [AdminDeliveryController::class, 'index'])->name('delivery.index');
	Route::post('orders/{order}/assign-driver', [AdminDeliveryController::class, 'assignDriver'])->name('delivery.assign-driver');
	Route::post('orders/{order}/mark-picked-up', [AdminDeliveryController::class, 'markPickedUp'])->name('delivery.mark-picked-up');
	Route::post('orders/{order}/mark-delivered', [AdminDeliveryController::class, 'markDelivered'])->name('delivery.mark-delivered');
	Route::post('delivery/update-driver-location', [AdminDeliveryController::class, 'updateDriverLocation'])->name('delivery.update-driver-location');
	Route::get('delivery/weather', [AdminDeliveryController::class, 'getWeather'])->name('delivery.weather');

	// Delivery Zones
	Route::get('zones', [AdminDeliveryController::class, 'zones'])->name('zones.index');
	Route::get('delivery/zones', [AdminDeliveryController::class, 'zones'])->name('delivery.zones');
	Route::post('delivery/zones', [AdminDeliveryController::class, 'createZone'])->name('delivery.zones.create');
	Route::put('delivery/zones/{zone}', [AdminDeliveryController::class, 'updateZone'])->name('delivery.zones.update');
	Route::delete('delivery/zones/{zone}', [AdminDeliveryController::class, 'deleteZone'])->name('delivery.zones.delete');


	// Driver Management
	Route::resource('drivers', AdminDriverController::class);
	Route::post('drivers/{driver}/toggle-availability', [AdminDriverController::class, 'toggleAvailability'])->name('drivers.toggle-availability');
	Route::post('drivers/{driver}/update-location', [AdminDriverController::class, 'updateLocation'])->name('drivers.update-location');

	// Tracking Dashboard
	Route::get('tracking', [AdminTrackingDashboardController::class, 'index'])->name('tracking.dashboard');
	Route::post('tracking/orders/{order}/status', [AdminTrackingDashboardController::class, 'updateOrderStatus'])->name('tracking.update-status');
	Route::get('tracking/orders/{order}/timeline', [AdminTrackingDashboardController::class, 'getOrderTimeline'])->name('tracking.order-timeline');
	Route::post('tracking/notifications/bulk', [AdminTrackingDashboardController::class, 'sendBulkNotifications'])->name('tracking.bulk-notifications');

	// Settings
	Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
	Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');
	Route::post('settings/test-email', [AdminSettingController::class, 'testEmail'])->name('settings.test-email');
	Route::post('settings/clear-cache', [AdminSettingController::class, 'clearCache'])->name('settings.clear-cache');
	Route::get('settings/backup-database/{filename}/download', [AdminSettingController::class, 'downloadBackup'])->name('settings.download-backup');
	Route::post('settings/backup-database', [AdminSettingController::class, 'backupDatabase'])->name('settings.backup-database');
	Route::get('tracking/notifications/stats', [AdminTrackingDashboardController::class, 'getNotificationStats'])->name('tracking.notification-stats');
	Route::post('tracking/notifications/process', [AdminTrackingDashboardController::class, 'processNotifications'])->name('tracking.process-notifications');
	Route::get('tracking/realtime-updates', [AdminTrackingDashboardController::class, 'getRealTimeUpdates'])->name('tracking.realtime-updates');

});

