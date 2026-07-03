<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository
{
    public function paginatedForUser(\Illuminate\Http\Request $request): LengthAwarePaginator
    {
        return Notification::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    public function markRead(Notification $notification): Notification
    {
        $notification->update(['is_read' => true]);

        return $notification->refresh();
    }
}
