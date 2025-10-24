<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->latest()
            ->paginate(20);

        // Marquer les notifications affichées comme lues
        Auth::user()->notifications()
            ->nonLues()
            ->update(['lu' => true, 'lu_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read
     */
    public function marquerLu(Notification $notification)
    {
        $this->authorize('markAsRead', $notification);

        $notification->marquerCommeLue();

        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Mark all notifications as read
     */
    public function marquerToutesLues()
    {
        Auth::user()->notifications()
            ->nonLues()
            ->update(['lu' => true, 'lu_at' => now()]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Get unread count (for AJAX)
     */
    public function unreadCount()
    {
        $count = Auth::user()->notifications()->nonLues()->count();

        return response()->json(['count' => $count]);
    }
}
