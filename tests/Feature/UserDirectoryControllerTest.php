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

        $response = $this->getJson('/api/doctorsPatients');
        $response->assertStatus(200);
    }
}
