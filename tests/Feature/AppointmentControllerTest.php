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
use Nette\Utils\Json;
use Nette\Schema\ValidationException;

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

//CHECK THAT TIME END DATE CANNOT BE BEFORE START DATE USING CARBON
    public function test_start_time_cannot_be_after_end_time(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $appointment = Appointment::factory()->make()->toArray();
        $appointment['starts_at'] = Carbon::Tomorrow()->addMinutes(60)->format('Y-m-d H:i:s');
        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(422);
    }

    public function test_end_time_cannot_be_before_start_time(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $appointment = Appointment::factory()->make()->toArray();
        $appointment['starts_at'] = Carbon::tomorrow()->addMinutes(60)->format('Y-m-d H:i:s');
        $appointment['ends_at'] = Carbon::tomorrow()->subMinutes(60)->format('Y-m-d H:i:s');

        $response = $this->postJson('/api/storeAppointment', $appointment);
        $response->assertStatus(422);
    }

    public function test_appointment_can_be_updated(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $response = $this->putJson('/api/updateAppointment/' . $appointment->id, [
           'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow()->addMinutes(60)->format('Y-m-d H:i:s'),
            'ends_at' => Carbon::tomorrow()->addMinutes(85)->format('Y-m-d H:i:s'),
            'status' => 'pending',
            'notes' => fake()->paragraph(),
        ]);
        $response->assertStatus(200);
    }

    public function test_appointment_update_status(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');


        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);
        $response = $this->putJson('/api/updateAppointment/' . $appointment->id, [
            'doctor_id' => $doctor->id,
             'starts_at' => Carbon::tomorrow()->addMinutes(120)->format('Y-m-d H:i:s'),
             'ends_at' => Carbon::tomorrow()->addMinutes(135)->format('Y-m-d H:i:s'),
              'status' => 'cancelled',
              'notes' => fake()->paragraph(),
            ]);
        $response->assertStatus(200);
    }

    public function test_appointment_starts_can_be_updated(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');


        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $response = $this->putJson('/api/updateAppointment/' . $appointment->id, [
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow()->addMinutes(200)->format('Y-m-d H:i:s'),
            'ends_at' => Carbon::tomorrow()->addMinutes(215)->format('Y-m-d H:i:s'),
            'status' => 'confirmed',
            'notes' => fake()->paragraph(),
            ]);
        $response->assertStatus(200);
    }

    public function test_update_blocked_when_end_date_changed_to_pre_start_date(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow()->addMinutes(60)->format('Y-m-d H:i:s'),
            'ends_at' => Carbon::tomorrow()->addMinutes(85)->format('Y-m-d H:i:s'),
            'status' => 'pending',
            'notes' => fake()->paragraph(),
            ]);

        $response = $this->putJson('/api/updateAppointment/' . $appointment->id, [
           'doctor_id' => $doctor->id,
           'starts_at' => Carbon::tomorrow()->addMinutes(60)->format('Y-m-d H:i:s'),
           'ends_at' => Carbon::tomorrow()->addMinutes(45)->format('Y-m-d H:i:s'),
           'status' => 'pending',
           'notes' => fake()->paragraph(),
        ]);
        $response->assertStatus(422);
    }

    public function test_update_blocked_when_start_date_changed_to_post_end_date(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $appointment = Appointment::factory()->create([
           'patient_id' => $patient->id,
           'doctor_id' => $doctor->id,
        ]);

        $response = $this->putJson('/api/updateAppointment/' . $appointment->id, [
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow()->addMinutes(100)->format('Y-m-d H:i:s'),
            'ends_at' => Carbon::tomorrow()->addMinutes(85)->format('Y-m-d H:i:s'),
        ]);
        $response->assertStatus(422);
    }

    public function test_notes_is_updated(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');

        $appointment = Appointment::factory()->create([
           'patient_id' => $patient->id,
           'doctor_id' => $doctor->id,
        ]);
        $response = $this->putJson('/api/updateAppointment/' . $appointment->id, [
            'doctor_id' => $doctor->id,
            'starts_at' => Carbon::tomorrow()->addMinutes(100)->format('Y-m-d H:i:s'),
            'ends_at' => Carbon::tomorrow()->addMinutes(115)->format('Y-m-d H:i:s'),
            'status' => 'cancelled',
            'notes' => 'Chud site Twin',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('appointments', [
           'notes' => 'Chud site Twin',
        ]);
    }

    public function test_get_all_appointments_works(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

         Appointment::factory()->count(5)->create();

        $this->assertCount(5, Appointment::all());
        $response = $this->getJson('/api/getAllMyAppointments');
        $response->assertStatus(200);
        $this->assertDatabaseCount('appointments', 5);
         }

         public function test_index_retrieves_large_num_of_appointments(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

          Appointment::factory()->count(1000)->create([
              'patient_id' => $patient->id,
              'status' => 'confirmed',
              'starts_at' => now()->addHour(),
          ]);
          $this->assertCount(1000, Appointment::all());
          $response = $this->getJson('/api/getAllMyAppointments');
          $response->assertStatus(200);

          $response->assertJsonCount(1000, 'appointments');
         }

         public function test_index_doesnt_show_cancelled_appointments(): void {
             $patient = User::factory()->create();
             $patient->assignRole('patient');
             Sanctum::actingAs($patient);

             Appointment::factory()->count(100)->create([
                 'status' => 'confirmed',
             ]);
             $this->assertDatabaseCount('appointments', 100);
             $response = $this->getJson('/api/getAllMyAppointments');
             $response->assertStatus(200);
             $response->assertJsonMissing([
                 'status' => 'cancelled',
             ]);
         }

         public function test_index_all_after_time_now(): void {
          $patient = User::factory()->create();
          $patient->assignRole('patient');
          Sanctum::actingAs($patient);

          Appointment::factory()->count(10)->create([
              'starts_at' => Carbon::yesterday()->addMinutes(60)->format('Y-m-d H:i:s'),
          ]);
          $this->assertCount(10, Appointment::all());
          $response = $this->getJson('/api/getAllMyAppointments');
          $response->assertStatus(200);
          $response->assertJsonStructure([
              'message',
          ]);
         }

         public function test_index_return_confirmed_dont_pull_non_confirmed_appointments(): void {
          $patient = User::factory()->create();
          $patient->assignRole('patient');
          Sanctum::actingAs($patient);

          Appointment::factory()->count(5)->create([
              'patient_id' => $patient->id,
              'starts_at' => now()->addHour(),
              'status' => 'confirmed',
          ]);
          $this->assertCount(5, Appointment::all());
          Appointment::factory()->count(5)->create([
              'patient_id' => $patient->id,
               'starts_at' => now()->addMinutes(60)->format('Y-m-d H:i:s'),
               'status' => 'cancelled',
          ]);
          $this->assertCount(10, Appointment::all());
          $response = $this->getJson('/api/getAllMyAppointments');
          $response->assertStatus(200);
          $response->assertJsonCount(5, 'appointments');

         }

         public function test_no_appointments_correct_json(): void {
             $patient = User::factory()->create();
             $patient->assignRole('patient');
             Sanctum::actingAs($patient);

             $response = $this->getJson('/api/getAllMyAppointments');
             $response->assertStatus(200);
             $response->assertJsonStructure([
                 'message',
             ]);
         }

         public function test_correct_json_response(): void {
           $patient = User::factory()->create();
           $patient->assignRole('patient');
           Sanctum::actingAs($patient);

           Appointment::factory()->count(10)->create([
               'patient_id' => $patient->id,
               'starts_at' => now()->addHour(),
               'status' => 'confirmed',
           ]);
           $response = $this->getJson('/api/getAllMyAppointments');
           $response->assertStatus(200);
           $response->assertJsonStructure([
               'message', 'appointments',
           ]);
    }

    public function test_delete_appointment(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $appointment = Appointment::factory()->create([
           'patient_id' => $patient->id,
           'starts_at' => now()->addHour(),
           'status' => 'confirmed',
        ]);
        $this->assertDatabaseCount('appointments', 1);

        $response = $this->deleteJson('/api/deleteAppointment/' . $appointment->id);
        $this->assertDatabaseCount('appointments', 0);
    }


}
