<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * @var class-string<\App\Models\Role>
     */
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => Role::ALUMNI,
            'description' => 'Alumni',
            'permissions' => [],
        ];
    }
}

