<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $title = $this->faker->sentence(4);
        $eventDate = $this->faker->dateTimeBetween('+1 week', '+6 months');
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraphs(2, true),
            'event_date' => $eventDate,
            'event_end_date' => $this->faker->optional()->dateTimeBetween($eventDate, '+1 day'),
            'venue' => $this->faker->optional()->company(),
            'online_link' => $this->faker->optional()->url(),
            'event_type' => $this->faker->randomElement(['physical', 'online', 'hybrid']),
            'max_attendees' => $this->faker->optional()->numberBetween(10, 500),
            'featured_image' => null,
            'is_published' => true,
            'is_featured' => $this->faker->boolean(30),
            'registration_deadline' => $this->faker->dateTimeBetween('now', $eventDate),
            'requires_approval' => $this->faker->boolean(20),
            'price' => $this->faker->randomElement([0, 0, 0, 50, 100, 200]),
            'currency' => 'GHS',
        ];
    }

    public function upcoming()
    {
        return $this->state(function (array $attributes) {
            return [
                'event_date' => $this->faker->dateTimeBetween('+1 day', '+3 months'),
            ];
        });
    }

    public function past()
    {
        return $this->state(function (array $attributes) {
            return [
                'event_date' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
            ];
        });
    }

    public function online()
    {
        return $this->state(function (array $attributes) {
            return [
                'event_type' => 'online',
                'venue' => null,
                'online_link' => $this->faker->url(),
            ];
        });
    }

    public function physical()
    {
        return $this->state(function (array $attributes) {
            return [
                'event_type' => 'physical',
                'online_link' => null,
                'venue' => $this->faker->company(),
            ];
        });
    }
}
