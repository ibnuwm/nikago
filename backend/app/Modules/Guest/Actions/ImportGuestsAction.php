<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Core\Base\Action;
use App\Modules\Guest\Models\Guest;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Support\Str;

class ImportGuestsAction extends Action
{
    private const ALLOWED_COLUMNS = ['name', 'phone', 'email', 'address', 'pax'];

    public function execute(mixed ...$params): array
    {
        $request = $params[0];

        $user = $request->user();

        $wedding = Wedding::query()
            ->forUser($user->id)
            ->find($request->input('wedding_id'));

        if (! $wedding) {
            return ['success' => false, 'imported' => 0, 'failed' => 0];
        }

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        $headers = fgetcsv($handle);

        $imported = 0;
        $failed = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);

            $safeData = array_intersect_key($data ?? [], array_flip(self::ALLOWED_COLUMNS));

            try {
                Guest::create([
                    'tenant_id' => $user->tenant_id ?? 1,
                    'wedding_id' => $wedding->id,
                    'name' => $safeData['name'] ?? '',
                    'phone' => $safeData['phone'] ?? null,
                    'email' => $safeData['email'] ?? null,
                    'address' => $safeData['address'] ?? null,
                    'pax' => max(1, (int) ($safeData['pax'] ?? 1)),
                    'qr_code' => (string) Str::uuid(),
                    'status' => Guest::STATUS_ACTIVE,
                ]);

                $imported++;
            } catch (\Exception) {
                $failed++;
            }
        }

        fclose($handle);

        return ['success' => true, 'imported' => $imported, 'failed' => $failed];
    }
}
