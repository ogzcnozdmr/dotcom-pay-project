<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function approve() {
        $notification = new Notifications();
        $notification->__update(null, ['notifications_read' => '1'], 10);
    }
}
