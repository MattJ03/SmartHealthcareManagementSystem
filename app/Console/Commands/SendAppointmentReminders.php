<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
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
    protected $twilioClient;
    protected $from;

    /**
     * Execute the console command.
     */


    public function __construct() {
        parent::__construct();

        $twilioConfig = \Config::get('services.twilio');
       $accountSid = $twilioConfig['sid'];
       $authToken = $twilioConfig['auth_token'];
       $this->from = $twilioConfig['from'];

       $this->twilioClient = new Client($accountSid, $authToken);

    }
    public function handle()
    {
        $now = Carbon::now();
        $oneWeekFromNow = $now->copy()->addWeek();


        $appointments = Appointment::where('reminder_sent', false)
        ->whereBetween('starts_at', [now(), now()->addWeek()])->get();

       $this->info('Appointments found ' . $appointments->count());
        foreach($appointments as $appointment) {
            $patient = $appointment->patient;
            Mail::to($appointment->patient->email)->send(new ReminderMail($appointment));
            $message = "Hello $patient->name you have an appointment at $appointment->starts_at with Dr. {$appointment->doctor->name} ";
             $this->sendSms($patient->contact_number, $message);
            $appointment->update(['reminder_sent' => true]);

            $this->info('Reminder email sent to patient ' . $appointment->patient->email);
            Log::info('Reminder email sent to patient ' . $appointment->patient->email);
        }
    }

    private function sendSms($number, $message) {
       try {
         if(str_starts_with($number, '07')) {
             $number = '+44' . substr($number, 1);
         }

         $this->twilioClient->messages->create($number,
         [
             'from' => $this->from,
              'body' => $message
         ]);
         $this->info('SMS sent to ' . $number);
         Log::info('SMS sent to ' . $number);
       } catch(\Exception $e) {
           $this->error('SMS failed to ' . $e->getMessage());
           Log::error('SMS failed to ' . $e->getMessage());
       }
    }

}
