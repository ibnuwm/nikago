<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Integration\Models\Webhook;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WebhookFactory extends Factory
{
    protected $model = Webhook::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'user_id' => 1,
            'name' => fake()->word() . ' Webhook',
            'url' => fake()->url(),
            'secret' => Str::random(32),
            'events' => [fake()->word() . '.' . fake()->word()],
            'is_active' => true,
        ];
    }
}
