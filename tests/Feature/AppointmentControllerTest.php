<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Appointment;
use App\Http\Controllers\AppointmentController;
use App\Services\AppointmentService;
use Database\Factories\AppointmentFactory;
use Carbon\Carbon;
use App\Models\User;
use Laravel\Sanctum\Sanctum;


class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    protected function setUp(): void {
        Parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_create_appointment(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $payload = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
             'starts_at' => Carbon::tomorrow(),
            'ends_at' => Carbon::tomorrow()->addMinutes(30),
            'status' => 'confirmed',
            'notes' => fake()->paragraph(),
        ];

        $response = $this->postJson('/api/storeAppointment', $payload);
        $response->assertStatus(201);
        $response->assertJsonStructure(['appointment', 'patient_id']);
    }

    public function test_doctor_cannot_create_appointment(): void {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);

        $appointment = Appointment::factory()->make()->toArray();
        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(403);
    }

    public function test_admin_can_create_appointment(): void {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $patient = User::factory()->create();
        $patient->assignRole('patient');

        Sanctum::actingAs($admin);

        $appointment = Appointment::factory()->make()->toArray();
        $appointment['patient_id'] = $patient['id'];
        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(201);
    }

    public function test_appointment_requires_starts_at(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $payload = [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
            'ends_at' => Carbon::tomorrow()->addMinutes(30)->format('Y-m-d H:i:s'),
            'status' => 'confirmed',
            'notes' => fake()->paragraph(),
        ];

        $response = $this->postJson('/api/storeAppointment', $payload);
        $response->assertStatus(422);
    }

    public function test_appointment_requires_real_doctor_id(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);
        $fakeDoctor = User::factory()->create();

        $payload = [
          'patient_id' => $patient->id,
          'doctor_id' => $fakeDoctor->id,
          'starts_at' => Carbon::tomorrow()->addMinutes(30)->format('Y-m-d H:i:s'),
          'ends_at' => Carbon::tomorrow()->addMinutes(30)->format('Y-m-d H:i:s'),
          'status' => 'confirmed',
          'notes' => fake()->paragraph(),
        ];

        $response = $this->postJson('/api/storeAppointment', $payload);
        $response->assertStatus(422);
    }

    public function test_notes_can_be_null(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $payload = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
            'ends_at' => Carbon::tomorrow()->addMinutes(30)->format('Y-m-d H:i:s'),
            'status' => 'confirmed',
        ];

        $response = $this->postJson('/api/storeAppointment', $payload);
        $response->assertStatus(201);
    }

    public function test_status_requires_enum_in_controller(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $payload = [
          'patient_id' => $patient->id,
          'doctor_id' => $doctor->id,
          'starts_at' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
          'ends_at' => Carbon::tomorrow()->addMinutes(30)->format('Y-m-d H:i:s'),
          'status' => 'trashSite',
          'notes' => fake()->paragraph(),
        ];

        $response = $this->postJson('/api/storeAppointment', $payload);
        $response->assertStatus(422);
    }

    public function test_successful_appointment_returns_correct_json(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $appointment = Appointment::factory()->make()->toArray();
        $response = $this->postJson('/api/storeAppointment', $appointment);

        $response->assertStatus(201);
        $response->assertJsonStructure(['appointment', 'patient_id']);
    }

    public function test_database_has_appointment_stored(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $appointment = Appointment::factory()->make()->toArray();
        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(201);
        $this->assertDatabaseHas('appointments', [
            'patient_id' => $patient->id,
            'doctor_id' => $appointment['doctor_id'],
            'starts_at' => Carbon::parse($appointment['starts_at'])->format('Y-m-d H:i:s'),
        ]);

    }

    public function test_database_has_one_appointment(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $appointment = Appointment::factory()->make()->toArray();

        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(201);
        $this->assertDatabaseCount('appointments', 1);
    }

    public function test_database_has_multiple_appointments(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        for($i = 0; $i < 3; $i++) {
            Appointment::factory()->create();
        }
        $this->assertDatabaseCount('appointments', 3);
    }

    public function test_doctor_id_cannot_be_set_as_the_patients_id(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $appointment = [
            'patient_id' => $patient->id,
            'doctor_id' => $patient->id,
            'starts_at' => Carbon::tomorrow()->addMinutes(30)->format('Y-m-d H:i:s'),
            'ends_at' => Carbon::tomorrow()->addMinutes(60)->format('Y-m-d H:i:s'),
             'status' => 'confirmed',
            'notes' => fake()->paragraph(),
            ];
        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(403);
    }

    public function test_admin_id_cannot_be_set_as_the_patients_id(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $appointment = Appointment::factory()->make()->toArray();
        $appointment['doctor_id'] = $admin['id'];
        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('appointments', $appointment);
    }

    public function test_patient_cannot_create_appointment_for_other_patient(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $patientB = User::factory()->create();
       $patientB->assignRole('patient');

        $appointment = Appointment::factory()->make()->toArray();
        $appointment['patient_id'] = $patientB->id;
        $appointment['doctor_id'] = $doctor->id;

    $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(201);

        $this->assertDatabaseHas('appointments', [
            'patient_id' => $patient->id,
        ]);
    }

}
