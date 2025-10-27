<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Setting;
use App\Notifications\AdminNotificationBookingCreated;


class AdminNotifyBookingCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingCreated $event): void
    {
        $setting = Setting::firstOrFail();
        \Notification::route('mail',$setting['email'])->notify(new AdminNotificationBookingCreated($event->appointment));
    }
}
