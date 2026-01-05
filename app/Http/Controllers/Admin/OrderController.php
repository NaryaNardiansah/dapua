<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTimeline;
use App\Models\ShippingSetting;
use Illuminate\Http\Request;

class OrderController extends Controller
{	public function index(Request $request)
	{
		$query = Order::query();
		
		// Search functionality
		$search = $request->get('search');
		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('order_code', 'like', "%{$search}%")
				  ->orWhere('recipient_name', 'like', "%{$search}%")
				  ->orWhere('recipient_phone', 'like', "%{$search}%")
				  ->orWhere('address_line', 'like', "%{$search}%");
			});
		}
		
		// Status filter
		$status = $request->get('status');
		if ($status) {
			$query->where('status', $status);
		}
		
		// Date filter
		$dateFilter = $request->get('date');
		if ($dateFilter) {
			switch ($dateFilter) {
				case 'today':
					$query->whereDate('created_at', today());
					break;
				case 'week':
					$query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
					break;
				case 'month':
					$query->whereMonth('created_at', now()->month)
						  ->whereYear('created_at', now()->year);
					break;
			}
		}
		
		// Sort functionality
		$sort = $request->get('sort', 'latest');
		switch ($sort) {
			case 'oldest':
				$query->oldest();
				break;
			case 'highest':
				$query->orderBy('grand_total', 'desc');
				break;
			case 'lowest':
				$query->orderBy('grand_total', 'asc');
				break;
			case 'latest':
			default:
				$query->latest();
				break;
		}
		
		$orders = $query->paginate(20)->withQueryString();
		
		// Generate WhatsApp links for each order
		$orders->getCollection()->transform(function ($order) {
			$order->whatsapp_link = $this->generateWhatsAppLink($order);
			return $order;
		});
		
		// Calculate statistics for quick stats
		$totalOrders = Order::count();
		$pendingOrders = Order::where('status', 'pending')->count();
		$processedOrders = Order::where('status', 'diproses')->count();
		$shippedOrders = Order::where('status', 'dikirim')->count();
		$completedOrders = Order::where('status', 'selesai')->count();
		$cancelledOrders = Order::where('status', 'dibatalkan')->count();
		$todayOrders = Order::whereDate('created_at', today())->count();
		$todayRevenue = Order::where('status', 'selesai')
			->whereDate('created_at', today())
			->sum('grand_total');
		
		// Get filter values for form
		$filters = [
			'search' => $search,
			'status' => $status,
			'date' => $dateFilter,
			'sort' => $sort
		];
		
		// Handle CSV export
		if ($request->get('export') === 'csv') {
			return $this->exportToCsv($query->get());
		}
		
		return view('admin.orders.index', compact(
			'orders', 'filters',
			'totalOrders', 'pendingOrders', 'processedOrders', 'shippedOrders', 
			'completedOrders', 'cancelledOrders', 'todayOrders', 'todayRevenue'
		));
	}

	public function show(Order $order)
	{
		$order->load('orderItems');
		
		// Get store location from settings
		$storeLat = ShippingSetting::getValue('store_lat', config('app.store_lat', -0.947100));
		$storeLng = ShippingSetting::getValue('store_lng', config('app.store_lng', 100.417200));
		
		// Generate WhatsApp link
		$whatsappLink = $this->generateWhatsAppLink($order);
		
		return view('admin.orders.show', compact('order', 'storeLat', 'storeLng', 'whatsappLink'));
	}

	public function update(Request $request, Order $order)
	{
		$validated = $request->validate([
			'status' => ['required','in:pending,diproses,dikirim,selesai,dibatalkan'],
		]);
		
		$oldStatus = $order->status;
		$newStatus = $validated['status'];
		
		// Update order status
		$order->update(['status' => $newStatus]);
		
		// Add timeline entry if status changed
		if ($oldStatus !== $newStatus) {
			$this->addTimelineEntry($order, $newStatus);
		}
		
		return back()->with('status', 'Status pesanan diperbarui');
	}
	
	/**
	 * Add timeline entry for status change
	 */
	private function addTimelineEntry(Order $order, string $status)
	{
		$timelineData = $this->getTimelineData($status);
		
		OrderTimeline::create([
			'order_id' => $order->id,
			'status' => $status,
			'title' => $timelineData['title'],
			'description' => $timelineData['description'],
			'icon' => $timelineData['icon'],
			'color' => $timelineData['color'],
			'timestamp' => now(),
			'is_visible_to_customer' => true,
			'is_automatic' => false,
			'triggered_by' => 'admin',
			'user_id' => null, // Set to null to avoid foreign key constraint
		]);
	}
	
	/**
	 * Get timeline data for status
	 */
	private function getTimelineData(string $status): array
	{
		$timelineMap = [
			'pending' => [
				'title' => 'Pesanan Diterima',
				'description' => 'Pesanan Anda telah diterima dan menunggu konfirmasi',
				'icon' => 'check-circle',
				'color' => '#f59e0b',
			],
			'diproses' => [
				'title' => 'Pesanan Diproses',
				'description' => 'Pesanan Anda sedang diproses oleh dapur',
				'icon' => 'cog',
				'color' => '#3b82f6',
			],
			'dikirim' => [
				'title' => 'Pesanan Dikirim',
				'description' => 'Pesanan Anda sedang dalam perjalanan',
				'icon' => 'truck',
				'color' => '#8b5cf6',
			],
			'selesai' => [
				'title' => 'Pesanan Selesai',
				'description' => 'Pesanan Anda telah sampai dan selesai',
				'icon' => 'check-double',
				'color' => '#10b981',
			],
			'dibatalkan' => [
				'title' => 'Pesanan Dibatalkan',
				'description' => 'Pesanan Anda telah dibatalkan',
				'icon' => 'times-circle',
				'color' => '#ef4444',
			],
		];
		
		return $timelineMap[$status] ?? [
			'title' => ucfirst($status),
			'description' => 'Status pesanan telah diubah',
			'icon' => 'info-circle',
			'color' => '#6b7280',
		];
	}

	public function destroy(Order $order)
	{
		$order->delete();
		return redirect()->route('admin.orders.index')->with('status', 'Pesanan dihapus');
	}

	/**
	 * Generate WhatsApp link for customer communication
	 */
	private function generateWhatsAppLink(Order $order): string
	{
		// Clean phone number (remove non-numeric characters)
		$phoneNumber = preg_replace('/[^0-9]/', '', $order->recipient_phone);
		
		// Add country code if not present (assuming Indonesia +62)
		if (!str_starts_with($phoneNumber, '62')) {
			if (str_starts_with($phoneNumber, '0')) {
				$phoneNumber = '62' . substr($phoneNumber, 1);
			} else {
				$phoneNumber = '62' . $phoneNumber;
			}
		}

		// Generate message based on order status
		$message = $this->generateWhatsAppMessage($order);
		
		// Create WhatsApp link using wa.me format
		// This is the official WhatsApp API format
		return "https://wa.me/{$phoneNumber}?text=" . urlencode($message);
	}

	/**
	 * Generate appropriate WhatsApp message based on order status
	 */
	private function generateWhatsAppMessage(Order $order): string
	{
		$customerName = $order->recipient_name;
		$orderId = $order->id;
		$orderCode = $order->order_code ?? "#{$orderId}";
		$status = ucfirst($order->status);
		
		switch ($order->status) {
			case 'pending':
				return "Halo {$customerName}, ini dari Dapur Sakura. Kami telah menerima pesanan {$orderCode} Anda. Pesanan sedang dalam proses konfirmasi. Apakah ada yang bisa kami bantu?";
			
			case 'diproses':
				return "Halo {$customerName}, pesanan {$orderCode} Anda sedang diproses oleh tim kami. Estimasi waktu penyiapan adalah 15-30 menit. Terima kasih atas kesabaran Anda!";
			
			case 'dikirim':
				$driverInfo = $order->driver ? "Driver: {$order->driver->name} ({$order->driver->vehicle_type} {$order->driver->vehicle_number})" : "Driver sedang dalam perjalanan";
				return "Halo {$customerName}, pesanan {$orderCode} Anda sedang dikirim. {$driverInfo}. Estimasi waktu pengiriman akan segera kami informasikan.";
			
			case 'selesai':
				return "Halo {$customerName}, pesanan {$orderCode} Anda telah selesai dan diterima. Terima kasih telah memilih Dapur Sakura! Silakan berikan rating dan review untuk pengalaman Anda.";
			
			case 'dibatalkan':
				return "Halo {$customerName}, kami ingin mengkonfirmasi pembatalan pesanan {$orderCode} Anda. Apakah ada yang bisa kami bantu atau ada pertanyaan lain?";
			
			default:
				return "Halo {$customerName}, ini dari Dapur Sakura. Kami ingin mengkonfirmasi pesanan {$orderCode} Anda. Status: {$status}. Apakah ada yang bisa kami bantu?";
		}
	}

	/**
	 * Export orders to CSV
	 */
	private function exportToCsv($orders)
	{
		$filename = 'orders-export-' . date('Y-m-d-H-i-s') . '.csv';
		
		$headers = [
			'Content-Type' => 'text/csv',
			'Content-Disposition' => "attachment; filename=\"{$filename}\"",
		];
		
		$callback = function() use ($orders) {
			$file = fopen('php://output', 'w');
			
			// Add BOM for UTF-8
			fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
			
			// CSV headers
			fputcsv($file, [
				'ID Pesanan',
				'Tanggal',
				'Nama Pelanggan',
				'Telepon',
				'Alamat',
				'Status',
				'Subtotal',
				'Ongkir',
				'Total',
				'Metode Pembayaran'
			]);
			
			// CSV data
			foreach ($orders as $order) {
				fputcsv($file, [
					$order->order_code ?? "#{$order->id}",
					$order->created_at->format('d/m/Y H:i'),
					$order->recipient_name,
					$order->recipient_phone ?? '',
					$order->address_line ?? '',
					ucfirst($order->status),
					'Rp ' . number_format($order->subtotal ?? 0, 0, ',', '.'),
					'Rp ' . number_format($order->shipping_fee ?? 0, 0, ',', '.'),
					'Rp ' . number_format($order->grand_total, 0, ',', '.'),
					ucfirst($order->payment_method ?? 'COD')
				]);
			}
			
			fclose($file);
		};
		
		return response()->stream($callback, 200, $headers);
	}

/**
 * Check payment status from Midtrans API
 */
public function checkPaymentStatus(Order $order)
{
try {
// Check if order has Midtrans Order ID
if (!$order->midtrans_order_id) {
return response()->json([
'success' => false,
'message' => 'Order ini tidak memiliki Midtrans Order ID'
], 400);
}

// Configure Midtrans
\Midtrans\Config::$serverKey = config('services.midtrans.server_key');
\Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production');

// Get transaction status from Midtrans
$status = \Midtrans\Transaction::status($order->midtrans_order_id);

// Update order based on Midtrans response
$oldPaymentStatus = $order->payment_status;
$oldOrderStatus = $order->status;

switch ($status->transaction_status) {
case 'capture':
if ($status->fraud_status === 'challenge') {
$order->update(['payment_status' => 'challenge']);
} else {
$order->update([
'payment_status' => 'paid',
'status' => $order->status === 'pending' ? 'diproses' : $order->status
]);
}
break;

case 'settlement':
$order->update([
'payment_status' => 'paid',
'status' => $order->status === 'pending' ? 'diproses' : $order->status
]);
break;

case 'pending':
$order->update(['payment_status' => 'pending']);
break;

case 'deny':
case 'cancel':
case 'expire':
$order->update([
'payment_status' => $status->transaction_status,
'status' => 'dibatalkan'
]);
break;

default:
$order->update(['payment_status' => $status->transaction_status]);
break;
}

// Add timeline entry if status changed
if ($oldOrderStatus !== $order->status) {
$this->addTimelineEntry($order, $order->status);
}

// Prepare success message
$message = "Status pembayaran diupdate: " . ucfirst($order->payment_status);
if ($oldPaymentStatus !== $order->payment_status) {
$message .= " (sebelumnya: " . ucfirst($oldPaymentStatus) . ")";
}

return response()->json([
'success' => true,
'message' => $message,
'data' => [
'payment_status' => $order->payment_status,
'order_status' => $order->status,
'old_payment_status' => $oldPaymentStatus,
'old_order_status' => $oldOrderStatus
]
]);

} catch (\Exception $e) {
\Log::error('Failed to check payment status: ' . $e->getMessage());
return response()->json([
'success' => false,
'message' => 'Gagal mengecek status pembayaran: ' . $e->getMessage()
], 500);
}
}
}
