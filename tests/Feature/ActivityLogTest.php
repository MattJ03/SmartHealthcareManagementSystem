<?php
namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\MedicalRecord;
use App\Models\PatientProfile;
use App\Models\User;
use App\Http\Controllers\AppointmentController;
use App\Models\Appointment;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Faker\Provider\Medical;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Database\Factories\UserFactory;
use Database\Factories\AppointmentFactory;
use App\Http\Controllers\AuthController;

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
            'action' => 'appointment_booked',
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
            'action' => 'appointment_deleted',
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

    public function test_store_record_creates_activity_log(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $payload = [
            'patient_id' => $patientProfile->id,
            'title' => 'Test title',
            'notes' => 'Test notes',
            'file' => UploadedFile::fake()->create('file.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $payload);
        $response->assertStatus(201);
    }

    public function test_no_log_generated_for_failed_medical_record_upload(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $payload = [
            'patient_id' => $patientProfile->id,
            'title' => '',
            'notes' => 'Test notes',
            'file' => UploadedFile::fake()->create('file.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $payload);
        $recordId = $response->json('record.id');
        $response->assertStatus(422);

        $this->assertDatabaseMissing('activity_logs', [
            'entity_id' => $recordId,
        ]);
    }

    public function test_description_formatted_correctly_for_store_record_log(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $payload = [
            'patient_id' => $patientProfile->id,
            'title' => 'Test title',
            'notes' => 'Test notes',
            'file' => UploadedFile::fake()->create('file.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $payload);
        $response->assertStatus(201);
        $this->assertDatabaseHas('activity_logs', [
            'description' => 'Dr. ' . $doctor->name . ' created a medical record for ' . $patient->name,
        ]);
    }

    public function test_delete_record_creates_activity_log(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $payload = [
            'patient_id' => $patientProfile->id,
            'title' => 'Test title',
            'notes' => 'Test notes',
            'file' => UploadedFile::fake()->create('file.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $payload);
        $response->assertStatus(201);
        $response2 = $this->deleteJson('/api/deleteMedicalRecord/' . $response->json('record.id'));
        $this->assertDatabaseCount('activity_logs', 2);
    }

    public function test_description_format_is_correct_in_log_for_delete(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $payload = [
            'patient_id' => $patientProfile->id,
            'title' => 'Test title',
            'notes' => 'Test notes',
            'file' => UploadedFile::fake()->create('file.pdf', 500, 'application/pdf'),
        ];

        $response = $this->postJson('/api/storeMedicalRecord', $payload);
        $response->assertStatus(201);
        $response2 = $this->deleteJson('/api/deleteMedicalRecord/' . $response->json('record.id'));
        $this->assertDatabaseHas('activity_logs', [
            'description' => 'Dr. ' . $doctor->name . ' deleted the record ' . $payload['title'],
        ]);
    }

    public function test_activity_log_shows_correct_id(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id
        ])->create();

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id
        ]);

        $response = $this->deleteJson('/api/deleteMedicalRecord/' . $record->id);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $doctor->id,
        ]);
    }

    public function test_no_log_created_when_non_doctor_attempts_to_delete_record(): void {
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($patient);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $record = MedicalRecord::factory([
            'patient_id' => $patientProfile->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $response = $this->deleteJson('/api/deleteMedicalRecord/' . $record->id);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('activity_logs', [
            'entity_id' => $record->id,
        ]);
    }

    public function test_log_created_when_doctor_views_record(): void {
        Storage::fake('private');
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');

        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $fakePath = 'medical-records/fake.pdf';
        Storage::disk('private')->put($fakePath, '%PDF-1.4 fake pdf content');

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id,
            'file_path' => $fakePath,
            'file_type' => 'pdf',
            'doctor_id' => $doctor->id
        ]);

        $response = $this->get('/api/showMedicalRecord/' . $record->id);
        $response->assertStatus(200);
        $this->assertDatabaseCount('activity_logs', 1);
    }

    public function test_two_records_when_user_views_record_twice(): void {
        Storage::fake('private');
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $fakePath = 'medical-records/fake.pdf';
        Storage::disk('private')->put($fakePath, '%PDF-1.4 fake pdf content');

        $record = MedicalRecord::factory([
            'patient_id' => $patientProfile->id,
            'file_path' => $fakePath,
            'doctor_id' => $doctor->id,
        ])->create();

        $response1 = $this->get('/api/showMedicalRecord/' . $record->id);
        $response1->assertStatus(200);
        $response2 = $this->get('/api/showMedicalRecord/' . $record->id);
        $response2->assertStatus(200);
        $this->assertDatabaseCount('activity_logs', 2);
    }

    public function test_description_formatted_correct_for_show_record(): void {
        Storage::fake('private');
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $fakePath = 'medical_records/fake.pdf';
        Storage::fake('private')->put($fakePath, '%PDF-1.4 fake pdf content');

        $record = MedicalRecord::factory()->create([
            'patient_id' => $patientProfile->id,
            'file_path' => $fakePath,
            'doctor_id' => $doctor->id,
        ]);

        $response = $this->get('/api/showMedicalRecord/' . $record->id);
        $response->assertStatus(200);
        $this->assertDatabaseHas('activity_logs', [
            'description' => 'Dr. ' . $record->doctor->name . ' viewed the record ' . $record->title,
        ]);
    }

    public function test_download_record_saves_log_to_db(): void {
        Storage::fake('private');
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $fakePath = 'medical-records/fake.pdf';
        Storage::fake('private')->put($fakePath, '%PDF-1.4 fake pdf content');

        $record = MedicalRecord::factory([
            'patient_id' => $patientProfile->id,
            'file_path' => $fakePath,
            'doctor_id' => $doctor->id,
        ])->create();

        $response = $this->get('/api/downloadFile/' . $record->id . '/download');
        $response->assertStatus(200);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'download_medical_record',
        ]);
    }

    public function test_patient_id_shows_id_in_log_for_download_file(): void {
        Storage::fake('private');
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($patient);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();
        $fakePath = 'medical-records/fake.pdf';
        Storage::fake('private')->put($fakePath, '%PDF-1.4 fake pdf content');

        $record = MedicalRecord::factory([
            'patient_id' => $patientProfile->id,
            'file_path' => $fakePath,
            'doctor_id' => $doctor->id,
        ])->create();

        $response = $this->get('/api/downloadFile/' . $record->id . '/download');
        $response->assertStatus(200);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $patient->id,
        ]);
    }

    public function test_correctly_formatted_description_for_download_file(): void {
        Storage::fake('private');
        $patient = User::factory()->create()->assignRole('patient');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($doctor);

        $patientProfile = PatientProfile::factory([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ])->create();

        $fakePath = 'medical-records/fake.pdf';
        Storage::fake('private')->put($fakePath, '%PDF-1.4 fake pdf content');

        $record = MedicalRecord::factory([
            'patient_id' => $patientProfile->id,
            'file_path' => $fakePath,
            'doctor_id' => $doctor->id,
        ])->create();

        $response = $this->get('/api/downloadFile/' . $record->id . '/download');
        $response->assertStatus(200);
        $this->assertDatabaseHas('activity_logs', [
            'description' => 'Dr. ' . $doctor->name . ' downloaded the medical record ' . $record->title,
        ]);
    }

    public function test_activity_log_when_patient_registered(): void {
        $admin = User::factory()->create()->assignRole('admin');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($admin);

        $patientUser = [
            'name' => 'james',
            'email' => 'james@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'contact_number' => '123456789',
            'emergency_contact' => '123456789',
            'doctor_id' => $doctor->id,
        ];

        $response = $this->postJson('/api/registerPatient', $patientUser);
        $response->assertStatus(201);
        $this->assertDatabaseCount('activity_logs', 1);
    }

    public function test_formatting_correct_when_patient_registered(): void {
        $admin = User::factory()->create()->assignRole('admin');
        $doctor = User::factory()->create()->assignRole('doctor');
        $this->actingAs($admin);

        $patientUser = [
            'name' => 'james',
            'email' => 'james@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'contact_number' => '123456789',
            'emergency_contact' => '123456789',
            'doctor_id' => $doctor->id,
        ];

        $response = $this->postJson('/api/registerPatient', $patientUser);
        $response->assertStatus(201);
        $this->assertDatabaseHas('activity_logs', [
           'description' => $patientUser['name'] . ' was registered and assigned to Dr. ' . $doctor->name,
        ]);
    }
}
