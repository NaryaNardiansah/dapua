<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderChat;
use App\Models\DriverLocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Get chat messages for an order
     */
    public function getMessages(Request $request, Order $order): JsonResponse
    {
        $messages = OrderChat::getRecentMessages($order, 50);
        
        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender ? $message->sender->name : 'System',
                    'message' => $message->message,
                    'message_type' => $message->message_type,
                    'metadata' => $message->metadata,
                    'is_read' => $message->is_read,
                    'is_system_message' => $message->is_system_message,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    'time_ago' => $message->created_at->diffForHumans(),
                ];
            }),
        ]);
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request, Order $order): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
            'message_type' => 'sometimes|string|in:text,image,voice,location,file',
            'metadata' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        $senderType = $user->is_driver ? 'driver' : 'customer';

        $message = OrderChat::create([
            'order_id' => $order->id,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'message' => $request->message,
            'message_type' => $request->message_type ?? 'text',
            'metadata' => $request->metadata ?? [],
        ]);

        // Send real-time notification
        $this->broadcastMessage($message);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_type' => $message->sender_type,
                'sender_name' => $message->sender->name,
                'message' => $message->message,
                'message_type' => $message->message_type,
                'metadata' => $message->metadata,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'time_ago' => $message->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Send location message
     */
    public function sendLocation(Request $request, Order $order): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'sometimes|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        
        $message = OrderChat::createLocationMessage(
            $order,
            $user,
            $request->latitude,
            $request->longitude,
            $request->address
        );

        // Send real-time notification
        $this->broadcastMessage($message);

        return response()->json([
            'success' => true,
            'message' => 'Location shared successfully',
            'data' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_type' => $message->sender_type,
                'sender_name' => $message->sender->name,
                'message' => $message->message,
                'message_type' => $message->message_type,
                'metadata' => $message->metadata,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'time_ago' => $message->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request, Order $order): JsonResponse
    {
        $user = Auth::user();
        $userType = $user->is_driver ? 'driver' : 'customer';

        OrderChat::where('order_id', $order->id)
            ->where('sender_type', '!=', $userType)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read',
        ]);
    }

    /**
     * Get unread count
     */
    public function getUnreadCount(Order $order): JsonResponse
    {
        $user = Auth::user();
        $userType = $user->is_driver ? 'driver' : 'customer';
        
        $count = OrderChat::getUnreadCount($order, $userType);

        return response()->json([
            'success' => true,
            'unread_count' => $count,
        ]);
    }

    /**
     * Broadcast message to real-time channels
     */
    private function broadcastMessage(OrderChat $message): void
    {
        // This would integrate with WebSockets, Pusher, or similar real-time service
        // For now, we'll just log it
        \Log::info('Broadcasting message', [
            'order_id' => $message->order_id,
            'sender_type' => $message->sender_type,
            'message_type' => $message->message_type,
        ]);
    }
}

