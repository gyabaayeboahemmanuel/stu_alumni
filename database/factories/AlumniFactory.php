<?php

namespace Database\Factories;

use App\Models\Alumni;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlumniFactory extends Factory
{
    protected $model = Alumni::class;

    public function definition()
    {
        $completionYear = $this->faker->numberBetween(2014, date('Y'));
        
        return [
            'user_id' => User::factory(),
            'student_id' => 'STU' . $completionYear . str_pad($this->faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'other_names' => $this->faker->optional()->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '+233 24 ' . $this->faker->numberBetween(100, 999) . ' ' . $this->faker->numberBetween(1000, 9999),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->dateTimeBetween('-45 years', '-25 years')->format('Y-m-d'),
            'year_of_completion' => $completionYear,
            'programme' => $this->faker->randomElement([
                'BSc. Computer Science',
                'BSc. Information Technology',
                'BSc. Electrical Engineering',
                'BSc. Mechanical Engineering',
                'BSc. Civil Engineering',
                'BSc. Business Administration',
                'BSc. Accounting',
            ]),
            'qualification' => 'Bachelor',
            'current_employer' => $this->faker->optional()->company(),
            'job_title' => $this->faker->optional()->jobTitle(),
            'industry' => $this->faker->optional()->word(),
            'country' => 'Ghana',
            'city' => $this->faker->randomElement(['Accra', 'Kumasi', 'Sunyani', 'Takoradi', 'Tamale', 'Cape Coast']),
            'postal_address' => $this->faker->optional()->address(),
            'website' => $this->faker->optional()->url(),
            'linkedin' => $this->faker->optional()->url(),
            'twitter' => $this->faker->optional()->url(),
            'facebook' => $this->faker->optional()->url(),
            'profile_photo_path' => null,
            'verification_status' => 'verified',
            'verification_source' => 'sis',
            'verified_at' => now(),
            'is_visible_in_directory' => $this->faker->boolean(80),
            'registration_method' => 'sis',
            'proof_document_path' => null,
        ];
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'verification_status' => 'unverified',
                'verification_source' => null,
                'verified_at' => null,
            ];
        });
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'verification_status' => 'pending',
                'verification_source' => 'manual',
                'verified_at' => null,
                'registration_method' => 'manual',
                'proof_document_path' => 'proofs/sample-document.pdf',
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'verification_status' => 'rejected',
                'verification_source' => 'manual',
                'verified_at' => null,
                'registration_method' => 'manual',
            ];
        });
    }

    public function manualRegistration()
    {
        return $this->state(function (array $attributes) {
            return [
                'registration_method' => 'manual',
                'student_id' => null,
                'year_of_completion' => $this->faker->numberBetween(1990, 2013),
            ];
        });
    }
}
