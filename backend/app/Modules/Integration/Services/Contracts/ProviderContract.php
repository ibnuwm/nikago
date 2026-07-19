<?php

declare(strict_types=1);

namespace App\Modules\Integration\Services\Contracts;

interface ProviderContract
{
    public function connect(array $credentials): array;
    public function disconnect(): bool;
    public function test(): array;
    public function isConnected(): bool;
}
