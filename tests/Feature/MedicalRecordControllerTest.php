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

class MedicalRecordControllerTest extends TestCase
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

    public function test_store_medical_record_returns_correct_structure(): void {
        Storage::fake('private');

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
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
            'notes' => 'Patient showing low blood cell count',
            'file' => UploadedFile::fake()->create('cancerreport.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $record);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'record', 'message', 'uploaded_by',
            ]);
    }

    public function test_store_medical_record_return_correct_record(): void {
        Storage::fake('private');
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);
        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $record = [
            'patient_id' => $patientProfile->id,
            'title' => 'Death Certificate',
            'notes' => 'Patient showing low death cell count lol',
            'file' => UploadedFile::fake()->create('death-certificate.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $record);
        $response->assertStatus(201)
            ->assertJsonFragment([
                'record' => $record,
            ]);
    }

    public function test_store_medical_record_return_correct_message(): void {
        Storage::fake('private');
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $patientProfile = PatientProfile::factory()->create([
           'user_id' => $patient->id,
           'doctor_id' => $doctor->id,
        ]);

        $record = [
            'patient_id' => $patientProfile->id,
            'title' => 'Blood Tests 1',
            'notes' => 'Patient showing low blood cell count',
            'file' => UploadedFIle::fake()->create('results.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $record);
        $response->assertStatus(201)
            ->assertJsonFragment([
                'message' => 'record created',
            ]);

    }

    public function test_store_medical_record_returns_correct_doctor(): void {
        Storage::fake('private');
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($doctor);

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $record = [
          'patient_id' => $patientProfile->id,
          'title' => 'Blood Tests 2',
          'notes' => 'Patient showing low blood cell count',
          'file' => UploadedFile::fake()->create('results.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $record);
        $response->assertStatus(201)
            ->assertJsonFragment([
                'uploaded_by' => $patientProfile->doctor_id,
            ]);
    }

    public function test_store_medical_record_requires_doctor_id(): void {
        Storage::fake('private');
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);

        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $doctor2 = User::factory()->create();
        $doctor2->assignRole('doctor');

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor2->id
        ]);

        $record = [
          'patient_id' => $patientProfile->id,
          'title' => 'Blood Tests 2',
          'notes' => 'Patient showing low blood cell count',
          'file' => UploadedFile::fake()->create('results.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $record);
        $response->assertStatus(403);
}

    public function test_store_medical_record_requires_patient_id(): void {
        Storage::fake('private');
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);
        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $record = [
            'title' => 'Blood Tests 2',
            'notes' => 'Patient showing low blood cell count',
            'file' => UploadedFile::fake()->create('results.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $record);
        $response->assertStatus(422);
    }

    public function test_store_medical_record_notes_can_be_null(): void {
        Storage::fake('private');
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
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
            'file' => UploadedFile::fake()->create('results.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $record);
        $response->assertStatus(201);
    }

    public function test_store_medical_records_title_cannot_be_null(): void {
        Storage::fake('private');

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $record = [
            'patient_id' => $patientProfile->id,
            'notes' => 'Patient showing low blood cell count',
            'file' => UploadedFile::fake()->create('results.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $record);
        $response->assertStatus(422);
    }

    public function test_store_medical_record_requires_token(): void {
        Storage::fake('private');

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $record = [
            'patient_id' => $patientProfile->id,
            'title' => 'Blood Tests 2',
            'notes' => 'Patient showing low blood cell count',
            'file' => UploadedFile::fake()->create('results.pdf', 500, 'application/pdf'),
            ];
         $response = $this->postJson('/api/storeMedicalRecord', $record);
         $response->assertStatus(401);
    }

    public function test_store_medical_record_admin_cannot_store(): void {
        Storage::fake('private');

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');
        Sanctum::actingAs($adminUser);

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $record = [
            'patient_id' => $patientProfile->id,
            'title' => 'Blood Tests 2',
            'notes' => 'Patient showing low blood cell count',
            'file' => UploadedFile::fake()->create('results.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $record);
        $response->assertStatus(403);
    }

    public function test_delete_medical_record(): void {
        Storage::fake('private');
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);
        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id,
        ]);

        $response = $this->deleteJson('/api/deleteMedicalRecord/' . $record->id);
        $response->assertStatus(200);
    }

    public function test_delete_record_removes_from_db_correctly(): void {
        Storage::fake('private');
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);
        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $record = MedicalRecord::factory()->count(5)->create([
            'patient_id' => $patientProfile->id,
        ]);

        $response = $this->deleteJson('/api/deleteMedicalRecord/' . $record[0]->id);
        $response->assertStatus(200);
        $this->assertDatabaseCount('medical_records', 4);
    }

    public function test_admin_cannot_delete_medical_record(): void {
        Storage::fake('private');
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin);

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id,
        ]);

        $response = $this->deleteJson('/api/deleteMedicalRecord/' . $record->id);
        $response->assertStatus(403);

    }


}
