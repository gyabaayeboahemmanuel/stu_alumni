<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlumniRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_selection_page_loads()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Join STU Alumni Network');
    }

    public function test_sis_registration_page_loads()
    {
        $response = $this->get('/register/sis');

        $response->assertStatus(200);
        $response->assertSee('SIS Verification Registration');
    }

    public function test_manual_registration_page_loads()
    {
        $response = $this->get('/register/manual');

        $response->assertStatus(200);
        $response->assertSee('Manual Registration');
    }

    public function test_successful_sis_registration()
    {
        // Mock SIS verification
        \App::bind(\App\Services\SISIntegrationService::class, function () {
            $mock = $this->createMock(\App\Services\SISIntegrationService::class);
            $mock->method('verifyAlumni')->willReturn([
                'success' => true,
                'data' => [
                    'student_id' => 'STU2018001',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'other_names' => 'Smith',
                    'gender' => 'male',
                    'programme' => 'BSc. Computer Science',
                    'qualification' => 'Bachelor',
                    'year_of_completion' => 2018,
                ],
                'message' => 'Verification successful'
            ]);
            return $mock;
        });

        $response = $this->post('/register/sis/complete', [
            'student_id' => 'STU2018001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'other_names' => 'Smith',
            'gender' => 'male',
            'date_of_birth' => '1995-05-15',
            'year_of_completion' => 2018,
            'programme' => 'BSc. Computer Science',
            'qualification' => 'Bachelor',
            'email' => 'john.doe@example.com',
            'phone' => '+233241234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'agree_terms' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
        ]);
        
        $this->assertDatabaseHas('alumni', [
            'student_id' => 'STU2018001',
            'verification_status' => 'verified',
        ]);
    }

    public function test_successful_manual_registration()
    {
        Storage::fake('public');

        $response = $this->post('/register/manual', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'other_names' => 'Doe',
            'gender' => 'female',
            'date_of_birth' => '1990-03-20',
            'email' => 'jane.smith@example.com',
            'phone' => '+233241234568',
            'year_of_completion' => 2012,
            'programme' => 'BSc. Business Administration',
            'qualification' => 'Bachelor',
            'proof_document' => UploadedFile::fake()->create('document.pdf', 1000),
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'agree_terms' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('users', [
            'email' => 'jane.smith@example.com',
        ]);
        
        $this->assertDatabaseHas('alumni', [
            'first_name' => 'Jane',
            'verification_status' => 'pending',
        ]);

        // Assert file was stored
        Storage::disk('public')->assertExists('proofs/document.pdf');
    }

    public function test_registration_requires_terms_agreement()
    {
        $response = $this->post('/register/manual', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '+233241234569',
            'year_of_completion' => 2012,
            'programme' => 'Test Programme',
            'qualification' => 'Bachelor',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            // Missing agree_terms
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['agree_terms']);
    }

    public function test_alumni_cannot_register_with_existing_email()
    {
        \App\Models\User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/register/manual', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'existing@example.com',
            'phone' => '+233241234569',
            'year_of_completion' => 2012,
            'programme' => 'Test Programme',
            'qualification' => 'Bachelor',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'agree_terms' => true,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
