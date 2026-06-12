<?php

declare(strict_types=1);

namespace Restock\Contract;

defined('ABSPATH') || exit;

/**
 * A service that is booted on startup.
 */
interface Bootable
{
    public function boot(): void;
}
