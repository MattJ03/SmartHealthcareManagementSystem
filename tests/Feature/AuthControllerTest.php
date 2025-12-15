<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;
use Database\Factories\UserFactory;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Database\Seeders\RolePermissionSeeder;
use App\Http\Controllers\AuthController;
class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run your roles and permissions seeder
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }


    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function doctor_register_patient(): void {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);

        $payload = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'contact_number' => '09712345',
            'emergency_contact' => '09343535',

        ];

        $response = $this->postJson('/api/registerPatient', $payload);
        $response->assertStatus(201);
        $response->assertJson(['message' => 'Patient Registered Successfully'], 201);
    }

    public function test_admin_register_patient(): void {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin);

        $payload = [
            'name' => 'jane Doe',
            'email' => 'janedoe@gmail.com',
            'password' => 'password2',
            'password_confirmation' => 'password2',
            'contact_number' => '053682759',
            'emergency_contact' => '54325252',
        ];

        $response = $this->postJson('/api/registerPatient', $payload);
             $response->assertStatus(201);
            $response->assertJson(['message' => 'Patient Registered Successfully'], 201);
    }

    public function test_register_must_have_name(): void {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);

        $payload = [
            'name' => '',
            'email' => 'noname@gmail.com',
            'password' => 'password3',
            'password_confirmation' => 'password3',
            'contact_number' => '0525353252',
            'emergency_contact' => '0783535353',
        ];

        $response = $this->postJson('/api/registerPatient', $payload);
        $response->assertStatus(403);
    }

    public function test_patient_cannot_register(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $payload = [
          'name' => 'Ryan Doe',
          'email' => 'ryandoe@gmail.com',
          'password' => 'password4',
          'password_confirmation' => 'password4',
          'contact_number' => '6364363',
          'emergency_contact' => '54325252',
        ];

        $response = $this->postJson('/api/registerPatient', $payload);
        $response->assertStatus(403);
    }

    public function test_admin_register_doctor(): void {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin);

        $payload = [
            'name' => 'James Doe',
            'email' => 'jamesdoe@gmail.com',
            'password' => 'password5',
            'password_confirmation' => 'password5',
            'contact_number' => '65326422',
            'speciality' => 'cardiology',
            'license_number' => '4346272726',
            'clinic_hours' =>  [
                              'Monday' => ['09:00-17:00'],
                              'Tuesday' => ['09:00-17:00'],
                                  ]
        ];

        $response = $this->postJson('/api/registerDoctor', $payload);
        $response->assertStatus(201);
        $response->assertJson(['message' => 'Doctor Registered Successfully'], 201);
    }

    public function test_doctor_cannot_register_doctor(): void {
        $doctor = User::factory()->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);

        $payload = [
            'name' => 'James Doe',
            'email' => 'jamesdoe@gmail.com',
            'password' => 'password5',
            'password_confirmation' => 'password5',
            'contact_number' => '65326422',
            'speciality' => 'cardiology',
            'license_number' => '4346272726',
            'clinic_hours' =>  [
                'Monday' => ['09:00-17:00'],
                'Tuesday' => ['09:00-17:00'],
            ],
            ];

        $response = $this->postJson('/api/registerDoctor', $payload);
        $response->assertStatus(403);
    }

    public function test_patient_cannot_register_doctor(): void {
        $patient = User::factory()->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);

        $payload = [
            'name' => 'James Doe',
            'email' => 'jamesdoe@gmail.com',
            'password' => 'password5',
            'password_confirmation' => 'password5',
            'contact_number' => '65326422',
            'speciality' => 'cardiology',
            'license_number' => '4346272726',
            'clinic_hours' => [
                'Monday' => ['09:00-17:00'],
                'Tuesday' => ['09:00-17:00'],
            ],
        ];

        $response = $this->postJson('/api/registerDoctor', $payload);
        $response->assertStatus(403);
    }

    public function test_name_is_required(): void {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin);

        $payload = [
          'email' => 'noemailused@gmail.com',
          'password'=> 'password',
          'password_confirmation' => 'password',
          'contact_number' => '532532532',
            'speciality' => 'rheumatology',
            'license_number' => '5253253253',
            'clinic_hours' => [
                'Monday' => ['09:00-17:00'],
                'Tuesday' => ['09:00-17:00'],
            ],
        ];

        $response = $this->postJson('/api/registerDoctor', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_email_needs_at(): void {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin);

        $payload = [
          'name' => 'James Mason',
          'email' => 'jamesmasongmail.com',
          'password' => 'password',
          'password_confirmation' => 'password',
          'contact_number' => '5435443543',
          'speciality' => 'urology',
          'license_number' => '53534543543',
          'clinic_hours' => [
              'Monday' => ['09:00-18:00'],
              'Tuesday' => ['09:00-18:00'],
          ],
        ];
        $response = $this->postJson('/api/registerDoctor', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_password_confirmation_matches(): void {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin);

        $payload = [
            'name' => 'James Mason',
            'email' => 'jamesmasongmail.com',
            'password' => 'password',
            'password_confirmation' => 'password3',
            'contact_number' => '5435443543',
            'speciality' => 'urology',
            'license_number' => '53534543543',
            'clinic_hours' => [
                'Monday' => ['09:00-18:00'],
                'Tuesday' => ['09:00-18:00'],
            ],
        ];

        $response = $this->postJson('/api/registerDoctor', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }
}
