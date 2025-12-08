<?php
namespace Database\Seeders;

use App\Models\AnnouncementCategory;
use Illuminate\Database\Seeder;

class AnnouncementCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'News',
                'slug' => 'news',
                'description' => 'General news and updates',
                'color' => '#3b82f6',
                'is_active' => true,
            ],
            [
                'name' => 'Events',
                'slug' => 'events',
                'description' => 'Upcoming events and activities',
                'color' => '#ef4444',
                'is_active' => true,
            ],
            [
                'name' => 'Jobs',
                'slug' => 'jobs',
                'description' => 'Job opportunities and career postings',
                'color' => '#10b981',
                'is_active' => true,
            ],
            [
                'name' => 'Scholarships',
                'slug' => 'scholarships',
                'description' => 'Scholarship and funding opportunities',
                'color' => '#f59e0b',
                'is_active' => true,
            ],
            [
                'name' => 'Alumni Stories',
                'slug' => 'alumni-stories',
                'description' => 'Success stories from our alumni',
                'color' => '#8b5cf6',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            AnnouncementCategory::create($category);
        }

        $this->command->info('Announcement categories seeded successfully!');
    }
}
