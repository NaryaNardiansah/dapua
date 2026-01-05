<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentCheckController extends Controller
{
    /**
     * Check payment status from Midtrans API
     */
    public function checkPaymentStatus($orderId)
    {
        try {
            $order = \App\Models\Order::findOrFail($orderId);

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
