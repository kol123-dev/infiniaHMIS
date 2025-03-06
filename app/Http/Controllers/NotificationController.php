<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends AppBaseController
{
    public function readNotification(Notification $notification)
    {
        $notification->read_at = Carbon::now();
        $notification->save();

        return $this->sendSuccess(__('messages.notification.notification_read_successfully'));
    }

    public function readAllNotification()
    {
        Notification::whereReadAt(null)->where('user_id', getLoggedInUserId())->update(['read_at' => Carbon::now()]);

        return $this->sendSuccess(__('messages.notification.all_notification_read_successfully'));
    }
}
