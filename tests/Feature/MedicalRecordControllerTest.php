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

    public function test_store_medical_record_found_in_private_disk(): void {
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
        $response->assertStatus(201);
        $medicalRecord = MedicalRecord::all()->first();
        $response = Storage::disk('private')->assertExists($medicalRecord->file_path);
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

    public function test_other_doctor_cannot_delete_medical_record(): void {
        Storage::fake('private');
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        $doctorOther = User::factory()->create();
        $doctorOther->assignRole('doctor');
        Sanctum::actingAs($doctorOther);

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

    public function test_delete_removes_record_from_private_disk(): void
    {
        Storage::fake('private');

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        Sanctum::actingAs($doctor);

        $patientProfile = PatientProfile::factory()->create([
            'user_id'   => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $filePath = 'medical-records/test.pdf';
        Storage::disk('private')->put($filePath, 'fake pdf content');

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id,
            'doctor_id'  => $doctor->id,
            'file_path'  => $filePath,
        ]);

        Storage::disk('private')->assertExists($filePath);

        $response = $this->deleteJson('/api/deleteMedicalRecord/' . $record->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('medical_records', [
            'id' => $record->id,
        ]);

        Storage::disk('private')->assertMissing($filePath);
    }

    public function test_show_medical_record(): void {
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

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id,
        ]);

        $response = $this->getJson('/showMedicalRecord/' . $record->id);
        $response->assertStatus(200);
    }

    public function test_show_medical_record_returns_file(): void {
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

        $fakePdfPath = 'medical-records/results.pdf';
        Storage::disk('private')->put($fakePdfPath, '%PDF-1.4 fake pdf content');

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id,
            'file_path' => $fakePdfPath,
            'file_type' => 'pdf',
            'title' => 'results.pdf',
        ]);

        $response = $this->get('/showMedicalRecord/' . $record->id);

        $response->assertStatus(200);
        $content = Storage::disk('private')->get($record->file_path);
        $this->assertStringContainsString('%PDF-1.4', $content);
    }

    public function test_show_wrong_id_doesnt_return_file(): void {
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

      //  $file = UploadedFile::fake()->create('results.pdf', 500, 'application/pdf');
        $fakePath = 'medical-records/results.pdf';
        Storage::disk('private')->put($fakePath, '%PDF-1.4');

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id,
            'file_path' => $fakePath,
            'file_type' => 'pdf',
            'title' => 'results.pdf',
        ]);
        $fakeId = $record->id + 1;

        $response = $this->get('/api/showMedicalRecord/' . $fakeId);
        $response->assertStatus(404);
    }

    public function test_download_file_works(): void {
        Storage::fake('private');
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        Sanctum::actingAs($patient);

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $filePath = 'medical-records/results.pdf';
        Storage::disk('private')->put($filePath, '%PDF-1.4 fake pdf content');

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id,
            'file_path' => $filePath,
            'file_type' => 'pdf',
            'title' => 'results.pdf',
        ]);

        $response = $this->get('api/downloadFile/' . $record->id . '/download');
        $response->assertDownload();
    }

    public function test_doctor_can_download_file_from_their_patient(): void {
        Storage::fake('private');
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        Sanctum::actingAs($doctor);

        $patientProfile = PatientProfile::factory()->create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $filePath = 'medical-records/results.pdf';
        Storage::disk('private')->put($filePath, '%PDF-1.4 fake pdf content');

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id,
            'file_path' => $filePath,
            'file_type' => 'pdf',
            'title' => 'results.pdf',
        ]);

        $response = $this->get('api/downloadFile/' . $record->id . '/download');
        $response->assertDownload();
    }

}
