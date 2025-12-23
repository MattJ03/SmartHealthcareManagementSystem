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



}
