<?php

namespace Tests\Unit;

use App\Models\Alumni;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlumniModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_alumni_full_name_attribute()
    {
        $alumni = Alumni::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'other_names' => 'Smith',
        ]);

        $this->assertEquals('John Doe Smith', $alumni->full_name);
    }

    public function test_alumni_full_name_without_other_names()
    {
        $alumni = Alumni::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'other_names' => null,
        ]);

        $this->assertEquals('Jane Doe', $alumni->full_name);
    }

    public function test_alumni_verification_scopes()
    {
        $verified = Alumni::factory()->create(['verification_status' => 'verified']);
        $pending = Alumni::factory()->create(['verification_status' => 'pending']);
        $unverified = Alumni::factory()->create(['verification_status' => 'unverified']);

        $this->assertEquals(1, Alumni::verified()->count());
        $this->assertEquals(1, Alumni::pending()->count());
        
        $verifiedAlumni = Alumni::verified()->first();
        $this->assertEquals('verified', $verifiedAlumni->verification_status);
    }

    public function test_alumni_can_be_marked_as_verified()
    {
        $alumni = Alumni::factory()->create(['verification_status' => 'pending']);

        $alumni->markAsVerified('manual');

        $this->assertEquals('verified', $alumni->verification_status);
        $this->assertEquals('manual', $alumni->verification_source);
        $this->assertNotNull($alumni->verified_at);
    }

    public function test_alumni_user_relationship()
    {
        $user = User::factory()->create();
        $alumni = Alumni::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $alumni->user);
        $this->assertEquals($user->id, $alumni->user->id);
    }

    public function test_alumni_businesses_relationship()
    {
        $alumni = Alumni::factory()->create();
        $business = \App\Models\Business::factory()->create(['alumni_id' => $alumni->id]);

        $this->assertCount(1, $alumni->businesses);
        $this->assertEquals($business->id, $alumni->businesses->first()->id);
    }
}
