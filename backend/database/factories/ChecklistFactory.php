<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Checklist\Models\Checklist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChecklistFactory extends Factory
{
    protected $model = Checklist::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => 1,
            'wedding_id' => 1,
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'progress' => 0,
        ];
    }
}
