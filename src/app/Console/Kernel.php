<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\jobs\SendReservationConfirmation;
use App\Models\Reservation;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $reservations = Reservation::all();
        foreach($reservations as $reservation) {
            $schedule->job(new SendReservationConfirmation($reservation))
                    ->delay(Carbon::parse($reservation->date . ' 08:00:00'))
                    ->delay(Carbon::parse($reservation->date . ' 11:00:00'))
                    ->delay(Carbon::parse($reservation->date . ' 19:00:00'));
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
