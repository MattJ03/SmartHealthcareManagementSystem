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

    public function test_store_appointment_creates_log_in_db_doctor_booked(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $appointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->make()->toArray();

        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(201);
        $this->assertDatabaseCount('activity_logs', 1);
    }

    public function test_store_appointment_creates_log_in_db_admin_booked(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin);

        $appointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->make()->toArray();

        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(201);
        $this->assertDatabaseCount('activity_logs', 1);
    }

    public function test_update_appointment_after_store_shows_2_entries(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($patient);

        $appointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->make()->toArray();

        $response1 = $this->postJson('/api/storeAppointment', $appointment);
        $appointmentId = $response1->json('appointment.id');
        $response1->assertStatus(201);

        $this->assertDatabaseCount('activity_logs', 1);

        $updateAppointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->make()->toArray();

        $response2 = $this->putJson('/api/updateAppointment/' . $appointmentId, $updateAppointment);
        $response2->assertStatus(200);
        $this->assertDatabaseCount('activity_logs', 2);
    }

    public function test_description_is_correct_for_update_activity_log_for_store_appointment(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $appointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->make()->toArray();

        $response = $this->postJson('/api/storeAppointment', $appointment);

        $appointmentId = $response->json('appointment.id');
        $response->assertStatus(201);
        $this->assertDatabaseCount('activity_logs', 1);

        $updateAppointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->make()->toArray();

        $response2 = $this->putJson('/api/updateAppointment/' . $appointmentId, $updateAppointment);
        $response2->assertStatus(200);
        $this->assertDatabaseHas('activity_logs', [
            'description' => 'Dr. ' . $doctor->name . ' updated an appointment for ' . $patient->name,
        ]);
    }

    public function test_store_appointment_shows_the_correct_activity_log_action(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($patient);

        $appointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->make()->toArray();

        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(201);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'appointment booked',
        ]);
    }

    public function test_delete_appointment_stores_correct_activity_log_action(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $appointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $response = $this->deleteJson('/api/deleteAppointment/' . $appointment->id);
        $response->assertStatus(200);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'appointment deleted',
        ]);
    }

    public function test_delete_appointment_shows_correct_description(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $appointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $response = $this->deleteJson('/api/deleteAppointment/' . $appointment->id);
        $response->assertStatus(200);
        $this->assertDatabaseHas('activity_logs', [
           'description' => 'Dr. ' . $appointment->doctor->name . ' deleted an appointment for ' . $appointment->patient->name,
        ]);
    }

    public function test_activity_log_has_correct_entity(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($patient);

        $appointment = Appointment::factory([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $response = $this->deleteJson('/api/deleteAppointment/' . $appointment->id);
        $response->assertStatus(200);
        $this->assertDatabaseHas('activity_logs', [
           'entity_type' => 'appointment',
        ]);
    }



}
