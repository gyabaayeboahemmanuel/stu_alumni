<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Alumni;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // =============== SUPER ADMIN ==================
        $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->first();

        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@stu.edu.gh'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'role_id' => $superAdminRole?->id,
            ]
        );

        // =============== ALUMNI ADMIN ==================
        $alumniAdminRole = Role::where('name', Role::ALUMNI_ADMIN)->first();

        $alumniAdmin = User::firstOrCreate(
            ['email' => 'alumni@stu.edu.gh'],
            [
                'name' => 'Alumni Office',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'role_id' => $alumniAdminRole?->id,
            ]
        );

        // =============== CONTENT EDITOR ==================
        $contentEditorRole = Role::where('name', Role::CONTENT_EDITOR)->first();

        $contentEditor = User::firstOrCreate(
            ['email' => 'content@stu.edu.gh'],
            [
                'name' => 'Content Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'role_id' => $contentEditorRole?->id,
            ]
        );

        // =============== SAMPLE ALUMNI USERS ==================
        $alumniRole = Role::where('name', 'Alumni')->first();

        $sampleAlumni = [
            [
                'first_name' => 'Kwame',
                'last_name' => 'Mensah',
                'email' => 'kwame.mensah@example.com',
                'student_id' => 'STU2014001',
                'year_of_completion' => 2018,
                'programme' => 'BSc. Computer Science',
            ],
            [
                'first_name' => 'Abena',
                'last_name' => 'Owusu',
                'email' => 'abena.owusu@example.com',
                'student_id' => 'STU2015002',
                'year_of_completion' => 2019,
                'programme' => 'BSc. Information Technology',
            ],
            [
                'first_name' => 'Kofi',
                'last_name' => 'Ampofo',
                'email' => 'kofi.ampofo@example.com',
                'student_id' => 'STU2016003',
                'year_of_completion' => 2020,
                'programme' => 'BSc. Electrical Engineering',
            ],
        ];

        foreach ($sampleAlumni as $alumniData) {
            // Create user if not already existing
            $user = User::firstOrCreate(
                ['email' => $alumniData['email']],
                [
                    'name' => $alumniData['first_name'] . ' ' . $alumniData['last_name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role_id' => $alumniRole?->id,
                    'is_active' => true,
                ]
            );

            // Create alumni profile only if not already existing
            Alumni::firstOrCreate(
                ['email' => $alumniData['email']],
                [
                    'user_id' => $user->id,
                    'student_id' => $alumniData['student_id'],
                    'first_name' => $alumniData['first_name'],
                    'last_name' => $alumniData['last_name'],
                    'phone' => '+233 24 ' . rand(100, 999) . ' ' . rand(1000, 9999),
                    'gender' => ['male', 'female'][rand(0, 1)],
                    'date_of_birth' => now()->subYears(rand(25, 35))->format('Y-m-d'),
                    'year_of_completion' => $alumniData['year_of_completion'],
                    'programme' => $alumniData['programme'],
                    'qualification' => 'Bachelor',
                    'current_employer' => ['MTN Ghana', 'Vodafone Ghana', 'Ecobank', 'Ghana Health Service'][rand(0, 3)],
                    'job_title' => ['Software Engineer', 'IT Manager', 'Network Administrator', 'Data Analyst'][rand(0, 3)],
                    'country' => 'Ghana',
                    'city' => ['Accra', 'Kumasi', 'Sunyani', 'Takoradi'][rand(0, 3)],
                    'verification_status' => 'verified',
                    'verification_source' => 'sis',
                    'verified_at' => now(),
                    'registration_method' => 'sis',
                ]
            );
        }

        $this->command->info('✅ Users and alumni seeded successfully (duplicates skipped)!');
    }
}
