<?php

declare(strict_types=1);

namespace App\Core\Base;

use Illuminate\Http\Request;

abstract class Action
{
    abstract public function execute(mixed ...$params): mixed;

    protected function getSortField(Request $request, array $allowed, string $default): string
    {
        $sort = $request->query('sort', $default);

        return in_array($sort, $allowed, true) ? $sort : $default;
    }

    protected function getSortDirection(Request $request, string $default = 'asc'): string
    {
        $direction = $request->query('direction', $default);

        return in_array($direction, ['asc', 'desc'], true) ? $direction : $default;
    }
}
