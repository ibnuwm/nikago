<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\CMS\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Wedding Tips', 'slug' => 'wedding-tips', 'description' => 'Tips and advice for planning your perfect wedding.'],
            ['name' => 'Real Weddings', 'slug' => 'real-weddings', 'description' => 'Real wedding stories and inspiration.'],
            ['name' => 'Vendor Spotlight', 'slug' => 'vendor-spotlight', 'description' => 'Featured vendors and their services.'],
            ['name' => 'Trends & Ideas', 'slug' => 'trends-ideas', 'description' => 'Latest wedding trends and creative ideas.'],
            ['name' => 'Budget Guide', 'slug' => 'budget-guide', 'description' => 'Guides to help you plan your wedding budget.'],
        ];

        foreach ($categories as $category) {
            BlogCategory::firstOrCreate(
                ['slug' => $category['slug']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }
    }
}
