<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->take(20)->get();

        return response()->json([
            'unread_count' => Auth::user()->unreadNotifications()->count(),
            'notifications' => $notifications->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->data['type'] ?? 'general',
                'message' => $n->data['message'] ?? '',
                'read' => ! is_null($n->read_at),
                'created_at' => $n->created_at->diffForHumans(),
            ]),
        ]);
    }

    public function unreadCount()
    {
        return response()->json([
            'unread_count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}