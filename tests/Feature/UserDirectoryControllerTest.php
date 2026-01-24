<?php

namespace Tests\Feature;

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\MedicalRecord;
use Database\Factories\MedicalRecordFactory;
use App\Http\Controllers\AuthController;
use Database\Factories\UserFactory;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\PatientProfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Database\Factories\PatientProfileFactory;

class UserDirectoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function setUp(): void {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_doctor_can_get_all_patients(): void {
        $patient1 = User::factory()->create()->assignRole('patient');
        $patient2 = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $profile1 = PatientProfile::factory()->create([
            'user_id' => $patient1->id,
            'doctor_id' => $doctor->id,
        ]);

        $profile2 = PatientProfile::factory()->create([
            'user_id' => $patient2->id,
            'doctor_id' => $doctor->id,
        ]);


        $response = $this->getJson('/api/doctorPatients');
        $response->assertStatus(200);
        $response->assertJsonStructure(['patients']);
    }

    public function test_correct_patients_returned(): void {
        $patient1 = User::factory()->create()->assignRole('patient');
        $patient2 = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $profile1 = PatientProfile::factory()->create([
            'user_id' => $patient1->id,
            'doctor_id' => $doctor->id,
        ]);

        $profile2 = PatientProfile::factory()->create([
            'user_id' => $patient2->id,
            'doctor_id' => $doctor->id,
        ]);

        $response = $this->getJson('/api/doctorPatients');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $profile1->id,
        ]);
    }

    public function test_doctor_only_returns_their_patients(): void {
        $patient1 = User::factory()->create()->assignRole('patient');
        $patient2 = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $doctor2 = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $profile1 = PatientProfile::factory()->create([
            'user_id' => $patient1->id,
            'doctor_id' => $doctor->id,
        ]);

        $profile2 = PatientProfile::factory()->create([
            'user_id' => $patient2->id,
            'doctor_id' => $doctor2->id,
        ]);

        $response = $this->getJson('/api/doctorPatients');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'patients');
    }
}
