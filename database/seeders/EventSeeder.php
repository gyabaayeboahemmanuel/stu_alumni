<?php
namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run()
    {
        $users = User::whereIn('email', ['admin@stu.edu.gh', 'content@stu.edu.gh'])->get();

        $events = [
            [
                'title' => 'STU Alumni Networking Mixer',
                'description' => 'Join fellow STU alumni for an evening of networking, drinks, and meaningful connections. Perfect opportunity to expand your professional network.',
                'event_date' => now()->addDays(15)->setHour(18)->setMinute(0),
                'event_end_date' => now()->addDays(15)->setHour(21)->setMinute(0),
                'venue' => 'STU Main Auditorium',
                'event_type' => 'physical',
                'max_attendees' => 100,
                'is_published' => true,
                'is_featured' => true,
                'registration_deadline' => now()->addDays(12),
                'requires_approval' => false,
                'price' => 0,
            ],
            [
                'title' => 'Tech Career Webinar',
                'description' => 'Learn about the latest trends in technology and career opportunities in the digital space. Featuring successful STU alumni in tech.',
                'event_date' => now()->addDays(8)->setHour(14)->setMinute(0),
                'event_end_date' => now()->addDays(8)->setHour(16)->setMinute(0),
                'online_link' => 'https://meet.google.com/xyz-alumni-tech',
                'event_type' => 'online',
                'max_attendees' => 200,
                'is_published' => true,
                'is_featured' => false,
                'registration_deadline' => now()->addDays(6),
                'requires_approval' => false,
                'price' => 0,
            ],
            [
                'title' => 'Annual General Meeting',
                'description' => 'The STU Alumni Association Annual General Meeting. Important decisions about alumni activities and leadership elections.',
                'event_date' => now()->addDays(30)->setHour(10)->setMinute(0),
                'event_end_date' => now()->addDays(30)->setHour(14)->setMinute(0),
                'venue' => 'STU Conference Hall',
                'event_type' => 'physical',
                'max_attendees' => 150,
                'is_published' => true,
                'is_featured' => true,
                'registration_deadline' => now()->addDays(25),
                'requires_approval' => true,
                'price' => 0,
            ],
        ];

        foreach ($events as $eventData) {
            Event::create(array_merge($eventData, [
                'slug' => Str::slug($eventData['title']),
                'featured_image' => null,
                'currency' => 'GHS',
            ]));
        }

        $this->command->info('Events seeded successfully!');
    }
}
