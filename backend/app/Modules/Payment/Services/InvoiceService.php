<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services;

class InvoiceService
{
    public function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

        return "{$prefix}-{$date}-{$random}";
    }
}
