<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => Role::SUPER_ADMIN,
                'description' => 'Full system access with all permissions',
                'permissions' => json_encode(['*']),
            ],
            [
                'name' => Role::ALUMNI_ADMIN,
                'description' => 'Alumni management and verification access',
                'permissions' => json_encode([
                    'manage_alumni',
                    'verify_alumni',
                    'view_reports',
                    'manage_businesses',
                ]),
            ],
            [
                'name' => Role::CONTENT_EDITOR,
                'description' => 'Content management access',
                'permissions' => json_encode([
                    'manage_announcements',
                    'manage_events',
                    'manage_executives',
                ]),
            ],
            [
                'name' => Role::VERIFICATION_OFFICER,
                'description' => 'Alumni verification access only',
                'permissions' => json_encode([
                    'verify_alumni',
                    'view_alumni',
                ]),
            ],
            [
                'name' => 'Alumni',
                'description' => 'Standard alumni user',
                'permissions' => json_encode([
                    'view_profile',
                    'update_profile',
                    'manage_businesses',
                    'register_events',
                    'view_announcements',
                ]),
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                [
                    'description' => $roleData['description'],
                    'permissions' => $roleData['permissions'],
                ]
            );
        }

        $this->command->info('✅ Roles seeded successfully (duplicates skipped)!');
    }
}
