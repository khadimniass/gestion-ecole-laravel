<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the notification.
     */
    public function view(User $user, Notification $notification)
    {
        return $notification->user_id === $user->id;
    }

    /**
     * Determine if the user can mark the notification as read.
     */
    public function markAsRead(User $user, Notification $notification)
    {
        return $notification->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the notification.
     */
    public function delete(User $user, Notification $notification)
    {
        return $notification->user_id === $user->id;
    }
}
