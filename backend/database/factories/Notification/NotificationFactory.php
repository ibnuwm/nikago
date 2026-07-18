<?php

declare(strict_types=1);

namespace Database\Factories\Notification;

use App\Modules\Notification\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['reminder', 'payment', 'invitation', 'system']),
            'title' => fake()->sentence(),
            'message' => fake()->paragraph(),
            'channel' => 'in_app',
            'is_read' => false,
            'data' => null,
        ];
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
