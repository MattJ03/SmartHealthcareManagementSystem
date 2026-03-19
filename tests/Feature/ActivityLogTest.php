<?php
namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use App\Http\Controllers\AppointmentController;
use App\Models\Appointment;
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

}
