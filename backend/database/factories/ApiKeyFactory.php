<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\System\Models\ApiKey;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ApiKeyFactory extends Factory
{
    protected $model = ApiKey::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'user_id' => 1,
            'name' => fake()->word() . ' API Key',
            'key' => hash('sha256', Str::random(40)),
        ];
    }
}
