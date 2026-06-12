<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

use Restock\Service\WaitlistService;

/**
 * Ordered list of HasHooks services to register during plugin booting.
 */
return [
    WaitlistService::class,
];
