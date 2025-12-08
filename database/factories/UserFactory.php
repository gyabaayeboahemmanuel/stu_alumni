<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => Role::where('name', 'Alumni')->first()->id ?? Role::factory(),
            'is_active' => true,
            'last_login_at' => $this->faker->optional()->dateTimeThisYear(),
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_id' => Role::where('name', Role::SUPER_ADMIN)->first()->id,
            ];
        });
    }

    public function alumniAdmin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_id' => Role::where('name', Role::ALUMNI_ADMIN)->first()->id,
            ];
        });
    }

    public function contentEditor()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_id' => Role::where('name', Role::CONTENT_EDITOR)->first()->id,
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}
