<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmation;

class SendReservationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reservation-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reservation reminder emails for today\'s reservations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now()->startOfDay();
        $reservations = Reservation::whereDate('date', '=', $now->toDateString())
            ->get();

        foreach ($reservations as $reservation) {
            Mail::to($reservation->user->email)->send(new ReservationConfirmation($reservation));
        }

        $this->info('Reservation reminder emails sent successfully.');
    }

}
