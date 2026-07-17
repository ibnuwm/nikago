<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\CMS\Models\BlogTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogTagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Dress', 'slug' => 'dress'],
            ['name' => 'Catering', 'slug' => 'catering'],
            ['name' => 'Photography', 'slug' => 'photography'],
            ['name' => 'Decoration', 'slug' => 'decoration'],
            ['name' => 'Music', 'slug' => 'music'],
            ['name' => 'Invitations', 'slug' => 'invitations'],
            ['name' => 'Honeymoon', 'slug' => 'honeymoon'],
            ['name' => 'Venue', 'slug' => 'venue'],
        ];

        foreach ($tags as $tag) {
            BlogTag::firstOrCreate(
                ['slug' => $tag['slug']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $tag['name'],
                ]
            );
        }
    }
}
