<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\CMS\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        Page::firstOrCreate(
            ['slug' => 'terms-of-service'],
            [
                'uuid' => (string) Str::uuid(),
                'type' => 'terms',
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h2>Terms of Service</h2><p>Your terms of service content goes here.</p>',
                'status' => 'published',
                'is_published' => true,
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'uuid' => (string) Str::uuid(),
                'type' => 'privacy_policy',
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h2>Privacy Policy</h2><p>Your privacy policy content goes here.</p>',
                'status' => 'published',
                'is_published' => true,
            ]
        );
    }
}
