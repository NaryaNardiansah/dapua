<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Notification;
use App\Models\OrderTimeline;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send tracking notification
     */
    public function sendStatusNotifications(Order $order, string $oldStatus, string $newStatus): void
    {
        $templates = Notification::getTemplates();

        // Map order status to template keys if necessary
        $statusMap = [
            'pending' => 'order_confirmed',
            'diproses' => 'order_prepared',
            'dikirim' => 'order_out_for_delivery',
            'selesai' => 'order_delivered',
        ];

        $templateKey = $statusMap[$newStatus] ?? $newStatus;

        if (isset($templates[$templateKey])) {
            foreach ($templates[$templateKey] as $type => $template) {
                $message = str_replace(
                    ['{order_code}', '{customer_name}', '{estimated_time}', '{driver_name}'],
                    [$order->order_code, $order->recipient_name, $order->estimated_delivery_time, $order->driver?->name ?? 'Driver'],
                    $template['message']
                );

                Notification::createOrderNotification(
                    $order,
                    $type,
                    'order_update',
                    $template['title'],
                    $message
                );
            }
        }
    }

    /**
     * Send tracking notification
     */
    public function sendTrackingNotification(Order $order): bool
    {
        try {
            $this->sendWhatsAppNotification($order);
            $this->sendSMSNotification($order);
            $this->sendEmailNotification($order);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send tracking notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send WhatsApp notification
     */
    public function sendWhatsAppNotification(Order $order): bool
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($order->recipient_phone);
            $message = $this->generateWhatsAppMessage($order);

            // Using WhatsApp Business API or webhook
            $response = Http::post('https://api.whatsapp.com/send', [
                'phone' => $phoneNumber,
                'text' => $message
            ]);

            if ($response->successful()) {
                Notification::createOrderNotification(
                    $order,
                    'whatsapp',
                    'order_update',
                    'Tracking Notification',
                    $message
                )->markAsSent();

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send SMS notification
     */
    public function sendSMSNotification(Order $order): bool
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($order->recipient_phone);
            $message = $this->generateSMSMessage($order);

            // Using SMS gateway (e.g., Twilio, Nexmo, etc.)
            $response = Http::post('https://api.sms-gateway.com/send', [
                'to' => $phoneNumber,
                'message' => $message,
                'api_key' => config('services.sms.api_key')
            ]);

            if ($response->successful()) {
                Notification::createOrderNotification(
                    $order,
                    'sms',
                    'order_update',
                    'SMS Notification',
                    $message
                )->markAsSent();

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('SMS notification failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send email notification
     */
    public function sendEmailNotification(Order $order): bool
    {
        try {
            $subject = "Update Pesanan #{$order->order_code} - Dapur Sakura";
            $message = $this->generateEmailMessage($order);

            Mail::raw($message, function ($mail) use ($order, $subject) {
                $mail->to($order->user->email)
                    ->subject($subject);
            });

            Notification::createOrderNotification(
                $order,
                'email',
                'order_update',
                $subject,
                $message
            )->markAsSent();

            return true;
        } catch (\Exception $e) {
            Log::error('Email notification failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send push notification
     */
    public function sendPushNotification(Order $order, string $title, string $message): bool
    {
        try {
            if (!$order->device_token) {
                return false;
            }

            // Using Firebase Cloud Messaging or similar
            $response = Http::withHeaders([
                'Authorization' => 'key=' . config('services.fcm.server_key'),
                'Content-Type' => 'application/json'
            ])->post('https://fcm.googleapis.com/fcm/send', [
                        'to' => $order->device_token,
                        'notification' => [
                            'title' => $title,
                            'body' => $message,
                            'icon' => 'icon',
                            'sound' => 'default'
                        ],
                        'data' => [
                            'order_id' => $order->id,
                            'tracking_code' => $order->tracking_code,
                            'status' => $order->status
                        ]
                    ]);

            if ($response->successful()) {
                Notification::createOrderNotification(
                    $order,
                    'push',
                    'order_update',
                    $title,
                    $message
                )->markAsSent();

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Push notification failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send bulk notifications
     */
    public function sendBulkNotifications(array $orders, string $type, string $channel, string $title, string $message): array
    {
        $results = [];

        foreach ($orders as $order) {
            $results[$order->id] = $this->sendNotification($order, $type, $channel, $title, $message);
        }

        return $results;
    }

    /**
     * Send single notification
     */
    public function sendNotification(Order $order, string $type, string $channel, string $title, string $message): bool
    {
        switch ($type) {
            case 'whatsapp':
                return $this->sendWhatsAppNotification($order);
            case 'sms':
                return $this->sendSMSNotification($order);
            case 'email':
                return $this->sendEmailNotification($order);
            case 'push':
                return $this->sendPushNotification($order, $title, $message);
            default:
                return false;
        }
    }

    /**
     * Process pending notifications
     */
    public function processPendingNotifications(): int
    {
        $notifications = Notification::getPendingNotifications();
        $processed = 0;

        foreach ($notifications as $notification) {
            try {
                $success = $this->sendNotification(
                    $notification->order,
                    $notification->type,
                    $notification->channel,
                    $notification->title,
                    $notification->message
                );

                if ($success) {
                    $notification->markAsDelivered();
                    $processed++;
                } else {
                    $notification->markAsFailed('Failed to send notification');
                }
            } catch (\Exception $e) {
                $notification->markAsFailed($e->getMessage());
            }
        }

        return $processed;
    }

    /**
     * Generate WhatsApp message
     */
    private function generateWhatsAppMessage(Order $order): string
    {
        $statusMessages = [
            'pending' => "Halo {$order->recipient_name}, pesanan #{$order->order_code} Anda telah diterima dan sedang dalam proses konfirmasi.",
            'diproses' => "Halo {$order->recipient_name}, pesanan #{$order->order_code} Anda sedang diproses. Estimasi waktu penyiapan: 15-30 menit.",
            'dikirim' => "Halo {$order->recipient_name}, pesanan #{$order->order_code} Anda sedang dikirim. Driver: {$order->driver?->name}.",
            'selesai' => "Halo {$order->recipient_name}, pesanan #{$order->order_code} Anda telah selesai dan diterima. Terima kasih!",
            'dibatalkan' => "Halo {$order->recipient_name}, pesanan #{$order->order_code} Anda telah dibatalkan. Alasan: {$order->cancellation_reason}",
        ];

        $message = $statusMessages[$order->status] ?? "Update pesanan #{$order->order_code}: " . ucfirst($order->status);

        if ($order->tracking_url) {
            $message .= "\n\nTrack pesanan Anda: {$order->tracking_url}";
        }

        return $message;
    }

    /**
     * Generate SMS message
     */
    private function generateSMSMessage(Order $order): string
    {
        $statusMessages = [
            'pending' => "Pesanan #{$order->order_code} diterima. Status: Konfirmasi",
            'diproses' => "Pesanan #{$order->order_code} sedang diproses. Estimasi: 15-30 menit",
            'dikirim' => "Pesanan #{$order->order_code} sedang dikirim. Driver: {$order->driver?->name}",
            'selesai' => "Pesanan #{$order->order_code} telah diterima. Terima kasih!",
            'dibatalkan' => "Pesanan #{$order->order_code} dibatalkan. Alasan: {$order->cancellation_reason}",
        ];

        $message = $statusMessages[$order->status] ?? "Update pesanan #{$order->order_code}: " . ucfirst($order->status);

        if ($order->tracking_code) {
            $message .= " Track: {$order->tracking_code}";
        }

        return $message;
    }

    /**
     * Generate email message
     */
    private function generateEmailMessage(Order $order): string
    {
        $message = "Halo {$order->recipient_name},\n\n";

        switch ($order->status) {
            case 'pending':
                $message .= "Pesanan #{$order->order_code} Anda telah diterima dan sedang dalam proses konfirmasi.\n\n";
                break;
            case 'diproses':
                $message .= "Pesanan #{$order->order_code} Anda sedang diproses oleh tim kami. Estimasi waktu penyiapan adalah 15-30 menit.\n\n";
                break;
            case 'dikirim':
                $message .= "Pesanan #{$order->order_code} Anda sedang dikirim. Driver: {$order->driver?->name} ({$order->driver?->vehicle_type} {$order->driver?->vehicle_number}).\n\n";
                break;
            case 'selesai':
                $message .= "Pesanan #{$order->order_code} Anda telah berhasil diterima. Terima kasih telah memilih Dapur Sakura!\n\n";
                break;
            case 'dibatalkan':
                $message .= "Pesanan #{$order->order_code} Anda telah dibatalkan. Alasan: {$order->cancellation_reason}\n\n";
                break;
            default:
                $message .= "Update pesanan #{$order->order_code}: " . ucfirst($order->status) . "\n\n";
        }

        if ($order->tracking_url) {
            $message .= "Anda dapat melacak pesanan Anda di: {$order->tracking_url}\n\n";
        }

        $message .= "Terima kasih telah memilih Dapur Sakura!\n\n";
        $message .= "Salam,\nTim Dapur Sakura";

        return $message;
    }

    /**
     * Format phone number for international use
     */
    private function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (!str_starts_with($phone, '62')) {
            if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            } else {
                $phone = '62' . $phone;
            }
        }

        return $phone;
    }

    /**
     * Get notification statistics
     */
    public function getNotificationStats(): array
    {
        return [
            'total' => Notification::count(),
            'sent' => Notification::where('status', 'sent')->count(),
            'delivered' => Notification::where('status', 'delivered')->count(),
            'failed' => Notification::where('status', 'failed')->count(),
            'pending' => Notification::where('status', 'pending')->count(),
            'by_type' => Notification::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'by_channel' => Notification::selectRaw('channel, COUNT(*) as count')
                ->groupBy('channel')
                ->pluck('count', 'channel')
                ->toArray(),
        ];
    }

    /**
     * Retry failed notifications
     */
    public function retryFailedNotifications(int $maxRetries = 3): int
    {
        $failedNotifications = Notification::where('status', 'failed')
            ->where('retry_count', '<', $maxRetries)
            ->get();

        $retried = 0;

        foreach ($failedNotifications as $notification) {
            try {
                $success = $this->sendNotification(
                    $notification->order,
                    $notification->type,
                    $notification->channel,
                    $notification->title,
                    $notification->message
                );

                if ($success) {
                    $notification->markAsDelivered();
                    $retried++;
                } else {
                    $notification->markAsFailed('Retry failed');
                }
            } catch (\Exception $e) {
                $notification->markAsFailed($e->getMessage());
            }
        }

        return $retried;
    }
}







