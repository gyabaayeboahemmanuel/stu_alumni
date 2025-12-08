<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\AnnouncementCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition()
    {
        $title = $this->faker->sentence(6);
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraphs(3, true),
            'excerpt' => $this->faker->paragraph(),
            'category_id' => AnnouncementCategory::factory(),
            'author_id' => User::factory(),
            'featured_image' => null,
            'is_published' => true,
            'is_pinned' => $this->faker->boolean(20),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'visibility' => $this->faker->randomElement(['public', 'alumni']),
            'meta_title' => $title,
            'meta_description' => $this->faker->sentence(),
        ];
    }

    public function unpublished()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => false,
                'published_at' => null,
            ];
        });
    }

    public function pinned()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_pinned' => true,
            ];
        });
    }
}
