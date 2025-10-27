<?php

namespace App\Listeners;

use App\Events\StatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Setting;
use App\Notifications\AdminNotificationBookingUpdated;

class NotifyAdminAppointmentStatusUpdated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }



    public function handle(StatusUpdated $event): void
    {
        $setting = Setting::firstOrFail();
        \Notification::route('mail', $setting['email'])->notify(new AdminNotificationBookingUpdated($event->appointment));
    }
}
