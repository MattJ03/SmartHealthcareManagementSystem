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

        $payload = [
            'patient_id' => $patient->id,
            'doctor_id' => User::factory()->create()->id,
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
        Sanctum::actingAs($admin);

        $appointment = Appointment::factory()->make()->toArray();
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

}
