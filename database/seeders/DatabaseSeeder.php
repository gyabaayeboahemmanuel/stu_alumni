<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AnnouncementCategorySeeder::class,
            AnnouncementSeeder::class,
            EventSeeder::class,
            ExecutiveSeeder::class,
        ]);
        
        $this->command->info('STU Alumni System seeded successfully!');
    }
}
