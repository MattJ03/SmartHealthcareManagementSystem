<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;
use Illuminate\Support\Facades\Log;
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
        $oneWeekFromNow = $now->copy()->addWeek();

        $appointments = Appointment::where('reminder_sent', false)
        ->whereBetween('starts_at', [now(), now()->addWeek()])->get();

       $this->info('Appointments found ' . $appointments->count());
        foreach($appointments as $appointment) {
            Mail::to($appointment->patient->email)->send(new ReminderMail($appointment));
            $appointment->update(['reminder_sent' => true]);

            $this->info('Reminder email sent to patient ' . $appointment->patient->email);
            Log::info('Reminder email sent to patient ' . $appointment->patient->email);
        }
    }
}
