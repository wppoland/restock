<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

use Restock\Admin\Settings;
use Restock\Admin\Subscribers;
use Restock\Service\WaitlistService;

/**
 * Ordered list of HasHooks services to register during plugin booting.
 *
 * Admin-only classes are included only when running in wp-admin context.
 */
$hooks = [
    WaitlistService::class,
];

if (is_admin()) {
    $hooks[] = Settings::class;
    $hooks[] = Subscribers::class;
}

return $hooks;
