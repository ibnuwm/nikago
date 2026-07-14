<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Authentication\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Create default admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@nikago.com',
        ]);
    }
}
