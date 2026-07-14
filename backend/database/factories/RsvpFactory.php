<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\RSVP\Models\Rsvp;
use Illuminate\Database\Eloquent\Factories\Factory;

class RsvpFactory extends Factory
{
    protected $model = Rsvp::class;

    public function definition(): array
    {
        return [
            'tenant_id' => 1,
            'guest_id' => GuestFactory::new(),
            'attendance' => fake()->randomElement(Rsvp::attendances()),
            'total_guest' => fake()->numberBetween(1, 5),
            'message' => fake()->optional()->sentence(),
        ];
    }

    public function yes(): static
    {
        return $this->state(fn (array $attributes): array => [
            'attendance' => Rsvp::ATTENDANCE_YES,
        ]);
    }

    public function no(): static
    {
        return $this->state(fn (array $attributes): array => [
            'attendance' => Rsvp::ATTENDANCE_NO,
        ]);
    }

    public function maybe(): static
    {
        return $this->state(fn (array $attributes): array => [
            'attendance' => Rsvp::ATTENDANCE_MAYBE,
        ]);
    }
}
