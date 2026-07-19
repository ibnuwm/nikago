<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Integration\Models\Integration;
use Illuminate\Database\Seeder;

class IntegrationProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            ['code' => 'GOOGLE_OAUTH', 'name' => 'Google OAuth', 'category' => 'Authentication', 'description' => 'Sign in with Google', 'icon' => 'google'],
            ['code' => 'GOOGLE_CALENDAR', 'name' => 'Google Calendar', 'category' => 'Calendar', 'description' => 'Sync events with Google Calendar', 'icon' => 'calendar'],
            ['code' => 'GOOGLE_MAPS', 'name' => 'Google Maps', 'category' => 'Maps', 'description' => 'Embed maps and location services', 'icon' => 'map'],
            ['code' => 'WHATSAPP', 'name' => 'WhatsApp API', 'category' => 'Communication', 'description' => 'Send WhatsApp notifications', 'icon' => 'message-circle'],
            ['code' => 'RESEND', 'name' => 'Resend', 'category' => 'Communication', 'description' => 'Transactional email delivery', 'icon' => 'mail'],
            ['code' => 'MIDTRANS', 'name' => 'Midtrans', 'category' => 'Payment', 'description' => 'Payment gateway for Indonesia', 'icon' => 'credit-card'],
            ['code' => 'XENDIT', 'name' => 'Xendit', 'category' => 'Payment', 'description' => 'Payment gateway for Southeast Asia', 'icon' => 'credit-card'],
            ['code' => 'CLOUDFLARE_R2', 'name' => 'Cloudflare R2', 'category' => 'Storage', 'description' => 'Object storage compatible with S3', 'icon' => 'cloud'],
            ['code' => 'ZOOM', 'name' => 'Zoom', 'category' => 'Meeting', 'description' => 'Video conferencing integration', 'icon' => 'video'],
            ['code' => 'YOUTUBE_LIVE', 'name' => 'YouTube Live', 'category' => 'Streaming', 'description' => 'Live streaming integration', 'icon' => 'youtube'],
        ];

        foreach ($providers as $provider) {
            Integration::updateOrCreate(
                ['code' => $provider['code']],
                $provider,
            );
        }
    }
}
