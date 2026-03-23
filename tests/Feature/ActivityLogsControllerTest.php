<?php

namespace Tests\Feature;

use App\Http\Controllers\ActivityLogsController;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Appointment;
class ActivityLogsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        Parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_index_returns_logs(): void {
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin);
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');

        $payload = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow(),
            'ends_at' => Carbon::tomorrow()->addMinutes(30),
            'status' => 'confirmed',
        ];

        $response = $this->postJson('/api/storeAppointment', $payload);
        $response->assertStatus(201);

        $response2 = $this->getJson('/api/getCompleteLogList');
        $response2->assertStatus(200);
        $response2->assertJsonStructure([
            'logs', 'message',
        ]);
    }

    public function test_complete_list_returns_correct_message_upon_returning_logs(): void {
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin);
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');

        $payload = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow(),
            'ends_at' => Carbon::tomorrow()->addMinutes(30),
            'status' => 'completed',
        ];

        $response = $this->postJson('/api/storeAppointment', $payload);
        $response->assertStatus(201);

        $response2 = $this->get('/api/getCompleteLogList');
        $response2->assertStatus(200);
        $response2->assertJsonFragment([
            'message' => 'Logs retrieved',
        ]);
    }

    public function test_log_returned_from_complete_log_list_endpoint(): void {
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin);
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');

        $payload = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow(),
            'ends_at' => Carbon::tomorrow()->addMinutes(30),
            'status' => 'completed',
        ];

        $response1 = $this->postJson('/api/storeAppointment', $payload);
        $response1->assertStatus(201);

        $response2 = $this->getJson('/api/getCompleteLogList');
        $response2->assertStatus(200);
        $response2->assertJsonFragment([
            'logs' => $response2->json('logs'),
        ]);
    }

    public function test_cannot_access_complete_log_list_as_patient(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $this->actingAs($patient);
        $doctor = User::factory()->create()->assignRole('doctor');

        $payload = [
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow(),
            'ends_at' => Carbon::tomorrow()->addMinutes(30),
            'status' => 'completed',
        ];

        $response = $this->postJson('/api/storeAppointment', $payload);
        $response->assertStatus(201);

        $response2 = $this->getJson('/api/getCompleteLogList');
        $response2->assertStatus(403);
    }

    public function test_unauthorised_complete_log_list_as_doctor(): void {
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);
        $patient = User::factory()->create()->assignRole('patient');

        $payload = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow(),
            'ends_at' => Carbon::tomorrow()->addMinutes(30),
            'status' => 'completed',
        ];

        $response1 = $this->postJson('/api/storeAppointment', $payload);
        $response1->assertStatus(201);

        $response2 = $this->getJson('/api/getCompleteLogList');
        $response2->assertStatus(403);
    }

    public function test_correct_return_upon_no_logs(): void {
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->getJson('/api/getCompleteLogList');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'No logs found',
        ]);
    }

    public function test_correct_action_returned(): void {
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin);
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');

        $payload = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow(),
            'ends_at' => Carbon::tomorrow()->addMinutes(30),
            'status' => 'completed',
            ];

        $response = $this->postJson('/api/storeAppointment', $payload);
        $response->assertStatus(201);

        $response2 = $this->getJson('/api/getCompleteLogList');
        $response2->assertStatus(200);
        $response2->assertJsonFragment([
            'action' => 'appointment_booked',
        ]);
    }

    public function test_pagination_works(): void {
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin);
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');

        for($i = 0; $i < 35; $i++) {
            ActivityLog::create([
                'user_id' => $admin->id,
                'action' => 'appointment_booked',
                'entity_type' => 'appointment',
                'entity_id' => $i,
                'description' => 'TESTING',
            ]);
        }
        $response = $this->getJson('/api/getCompleteLogList');
        $response->assertStatus(200);
        $response->assertJsonCount(30, 'logs.data');
    }



}
