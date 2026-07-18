<?php

declare(strict_types=1);

namespace Database\Factories\CRM;

use App\Modules\CRM\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'source' => fake()->randomElement(['website', 'referral', 'instagram', 'whatsapp', 'marketplace']),
            'stage' => fake()->randomElement(['new', 'contacted', 'negotiation', 'won', 'lost']),
            'deal_value' => fake()->optional(0.6)->randomFloat(2, 1000000, 100000000),
            'notes' => fake()->optional(0.7)->paragraph(),
        ];
    }
}
