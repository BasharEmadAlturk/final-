<?php

namespace App\Listeners;

use App\Events\StatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\EmployeeNotificationBookingUpdated;

class NotifyEmployeeAppointmentStatusUpdated
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
    public function handle(StatusUpdated $event): void
    {
        $email = $event->appointment->employee->user->email;
        \Notification::route('mail',$email)->notify(new EmployeeNotificationBookingUpdated($event->appointment));
    }
}
