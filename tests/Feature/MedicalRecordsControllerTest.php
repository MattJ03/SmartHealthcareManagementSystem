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

class MedicalRecordsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    protected function setUp(): void {
        Parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_create_medical_record(): void {
        Storage::fake('private');

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $doctor->refresh();
        Sanctum::actingAs($doctor);

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $record = [
            'patient_id' => $patientProfile->id,
            'title' => 'Blood Tests 2',
            'notes' => 'Patient showing low white blood cell count',
            'file' => UploadedFile::fake()->create('results.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $record);
        $response->dump();
        $response->assertStatus(201);
    }

}
