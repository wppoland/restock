<?php

declare(strict_types=1);

namespace Restock;

defined('ABSPATH') || exit;

use Restock\Repository\WaitlistRepository;
use Restock\Service\WaitlistService;
use Restock\Util\TemplateLoader;

/**
 * Service registration. Returns a callable that binds every service into the
 * container. Bindings are lazy.
 */
return static function (Container $c): void {
    // Infrastructure
    $c->singleton(Migrator::class, static fn (): Migrator => new Migrator());
    $c->singleton(WaitlistRepository::class, static function (): WaitlistRepository {
        global $wpdb;
        return new WaitlistRepository($wpdb);
    });

    // Utilities
    $c->singleton(TemplateLoader::class, static fn (): TemplateLoader => new TemplateLoader());

    // Services
    $c->singleton(WaitlistService::class, static fn (): WaitlistService => new WaitlistService(
        $c->get(WaitlistRepository::class),
        $c->get(TemplateLoader::class),
    ));
};
