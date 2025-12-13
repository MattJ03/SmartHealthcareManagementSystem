<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Factories\UserFactory;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
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
            'contact_number' => '09712345',
            'emergency_contact' => '09343535',

        ];

        $response = $this->postJson('/api/registerPatient', $payload);
        $response->assertStatus(201);
        $response->assertJson(['message' => 'Patient Registered Successfully'], 201);
    }
}
