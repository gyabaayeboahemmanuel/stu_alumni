<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BusinessDirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected $alumni;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->alumni = User::factory()->create();
        $this->alumni->alumni()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $this->alumni->email,
            'year_of_completion' => 2018,
            'programme' => 'BSc. Computer Science',
            'verification_status' => 'verified',
        ]);
    }

    public function test_business_directory_page_loads()
    {
        $response = $this->get('/businesses');

        $response->assertStatus(200);
        $response->assertSee('Alumni Business Directory');
    }

    public function test_verified_alumni_can_create_business_listing()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->alumni)->post('/businesses', [
            'name' => 'Test Business',
            'description' => 'This is a test business description.',
            'industry' => 'Technology',
            'website' => 'https://testbusiness.com',
            'email' => 'contact@testbusiness.com',
            'phone' => '+233241234567',
            'address' => '123 Test Street',
            'city' => 'Accra',
            'country' => 'Ghana',
            'logo' => UploadedFile::fake()->image('logo.jpg'),
        ]);

        $response->assertRedirect(route('businesses.my-businesses'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('businesses', [
            'name' => 'Test Business',
            'status' => 'pending',
        ]);
    }

    public function test_business_creation_requires_name_and_description()
    {
        $response = $this->actingAs($this->alumni)->post('/businesses', [
            // Missing required fields
            'industry' => 'Technology',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'description']);
    }

    public function test_alumni_can_view_their_businesses()
    {
        Business::factory()->create(['alumni_id' => $this->alumni->alumni->id]);

        $response = $this->actingAs($this->alumni)->get('/alumni/my-businesses');

        $response->assertStatus(200);
        $response->assertSee('My Businesses');
    }

    public function test_business_details_page_loads()
    {
        $business = Business::factory()->create([
            'alumni_id' => $this->alumni->alumni->id,
            'status' => 'active',
            'is_verified' => true,
        ]);

        $response = $this->get('/businesses/' . $business->slug);

        $response->assertStatus(200);
        $response->assertSee($business->name);
    }

    public function test_unverified_business_not_visible_in_public_directory()
    {
        $business = Business::factory()->create([
            'alumni_id' => $this->alumni->alumni->id,
            'status' => 'pending',
        ]);

        $response = $this->get('/businesses');

        $response->assertStatus(200);
        $response->assertDontSee($business->name);
    }
}
