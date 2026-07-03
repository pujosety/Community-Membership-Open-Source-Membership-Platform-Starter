<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;

class NotificationController extends ApiController
{
    public function __construct(private NotificationRepository $notifications) {}

    public function index(Request $request)
    {
        return $this->ok($this->notifications->paginatedForUser($request));
    }

    public function markRead(Request $request, Notification $notification)
    {
        $updated = $this->notifications->markRead($notification);

        return $this->ok($updated, 'Updated');
    }
}
