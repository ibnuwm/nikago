<?php

declare(strict_types=1);

namespace App\Core\Base;

abstract class Action
{
    abstract public function execute(mixed ...$params): mixed;
}
