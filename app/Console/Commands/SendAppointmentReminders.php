<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;
class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-appointment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $oneWeekFromNow = $now->copy()->addWeeks();

        $appointments = Appointment::whereBetween('starts_at', [$oneWeekFromNow->startOfDay(), $oneWeekFromNow->endOfDay()])->get();


        foreach($appointments as $appointment) {
            Mail::to($appointment->patient->user->email)->send(new ReminderMail($appointment));
            $appointment->update(['reminder_sent' => true]);

            $this->info('Reminder email sent to patient ' . $appointment->patient->user->email);
        }
    }
}
