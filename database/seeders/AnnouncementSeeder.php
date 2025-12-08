<?php
namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\AnnouncementCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $categories = AnnouncementCategory::all();
        $users = User::whereIn('email', ['admin@stu.edu.gh', 'content@stu.edu.gh'])->get();

        $announcements = [
            [
                'title' => 'Annual Alumni Homecoming 2024',
                'content' => 'Join us for the annual STU Alumni Homecoming event! This year we have exciting activities planned including networking sessions, campus tours, and a grand dinner.',
                'category_id' => $categories->where('slug', 'events')->first()->id,
                'is_published' => true,
                'is_pinned' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Career Opportunities at MTN Ghana',
                'content' => 'MTN Ghana is looking for talented STU alumni to join their growing team. Multiple positions available in IT, Engineering, and Business Development.',
                'category_id' => $categories->where('slug', 'jobs')->first()->id,
                'is_published' => true,
                'is_pinned' => false,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Master\'s Degree Scholarship Program',
                'content' => 'Applications are now open for the STU Alumni Masters Scholarship Program. Full and partial scholarships available for various programs.',
                'category_id' => $categories->where('slug', 'scholarships')->first()->id,
                'is_published' => true,
                'is_pinned' => false,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Alumni Success: Dr. Ama Serwaa',
                'content' => 'Dr. Ama Serwaa (Class of 2015) has been appointed as the new Director of Technology at Ghana Health Service. Read her inspiring journey.',
                'category_id' => $categories->where('slug', 'alumni-stories')->first()->id,
                'is_published' => true,
                'is_pinned' => false,
                'published_at' => now()->subDays(7),
            ],
        ];

        foreach ($announcements as $announcementData) {
            $author = $users->random();
            
            Announcement::create(array_merge($announcementData, [
                'slug' => Str::slug($announcementData['title']),
                'excerpt' => Str::limit($announcementData['content'], 150),
                'author_id' => $author->id,
                'visibility' => 'alumni',
                'featured_image' => null,
                'meta_title' => $announcementData['title'],
                'meta_description' => Str::limit($announcementData['content'], 160),
            ]));
        }

        $this->command->info('Announcements seeded successfully!');
    }
}
