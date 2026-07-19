<?php

declare(strict_types=1);

namespace App\Modules\Integration\Services;

use App\Modules\Integration\Models\Integration;
use App\Modules\Integration\Models\IntegrationCredential;
use App\Modules\Integration\Models\ApiLog;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class IntegrationService
{
    public function getProviders(): array
    {
        return Integration::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function getUserIntegrations(int $userId): array
    {
        $providers = Integration::where('is_active', true)->get();
        $result = [];

        foreach ($providers as $provider) {
            $credentials = IntegrationCredential::where('user_id', $userId)
                ->where('integration_id', $provider->id)
                ->get();

            $result[] = [
                'id' => $provider->id,
                'code' => $provider->code,
                'name' => $provider->name,
                'category' => $provider->category,
                'description' => $provider->description,
                'icon' => $provider->icon,
                'is_connected' => $credentials->isNotEmpty(),
                'credentials' => $credentials->pluck('key'),
            ];
        }

        return $result;
    }

    public function connect(int $userId, string $providerCode, array $credentials): void
    {
        $integration = Integration::where('code', $providerCode)->firstOrFail();

        foreach ($credentials as $key => $value) {
            IntegrationCredential::updateOrCreate(
                [
                    'user_id' => $userId,
                    'integration_id' => $integration->id,
                    'key' => $key,
                ],
                ['value' => $value],
            );
        }
    }

    public function disconnect(int $userId, string $providerCode): void
    {
        $integration = Integration::where('code', $providerCode)->firstOrFail();

        IntegrationCredential::where('user_id', $userId)
            ->where('integration_id', $integration->id)
            ->delete();
    }

    public function isConnected(int $userId, string $providerCode): bool
    {
        $integration = Integration::where('code', $providerCode)->first();

        if (! $integration) {
            return false;
        }

        return IntegrationCredential::where('user_id', $userId)
            ->where('integration_id', $integration->id)
            ->exists();
    }

    public function logApiCall(array $data): ApiLog
    {
        return ApiLog::create($data);
    }
}
