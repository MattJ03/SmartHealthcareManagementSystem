<?php
namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use App\Http\Controllers\AppointmentController;
use App\Models\Appointment;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Factories\UserFactory;
use Database\Factories\AppointmentFactory;


class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_store_appointment_creates_log_in_db_patient_booked(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($patient);

        $appointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->make()->toArray();

        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(201);

        $this->assertDatabaseCount('activity_logs', 1);

    }

    public function test_update_appointment_creates_log_in_db_patient_booked(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($patient);

        $appointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();
        $this->assertDatabaseCount('appointments', 1);

        $updatedAppointment = Appointment::factory([
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'starts_at' => Carbon::tomorrow()->addMinutes(100)->format('Y-m-d H:i:s'),
            'ends_at' => Carbon::tomorrow()->addMinutes(115)->format('Y-m-d H:i:s'),
            'status' => 'cancelled',
            'notes' => null,
        ])->make()->toArray();

        $response = $this->putJson('/api/updateAppointment/' . $appointment->id, $updatedAppointment);
        $response->assertStatus(200);
        $this->assertDatabaseCount('activity_logs', 1);
    }

}
