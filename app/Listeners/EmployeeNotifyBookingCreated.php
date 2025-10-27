<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\EmployeeNotificationBookingCreated;

class EmployeeNotifyBookingCreated
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
        $email = $event->appointment->employee->user->email;
        \Notification::route('mail',$email)->notify(new EmployeeNotificationBookingCreated($event->appointment));
    }
}
