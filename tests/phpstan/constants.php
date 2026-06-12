<?php
/**
 * Constants needed by PHPStan to analyse the plugin without bootstrapping WordPress.
 *
 * @package Restock
 */

declare(strict_types=1);

namespace {
    if (! defined('ABSPATH')) {
        define('ABSPATH', '/tmp/wordpress/');
    }
    // WC_VERSION is provided by the WooCommerce stubs bootstrap file.
}

namespace Restock {
    if (! defined('Restock\\VERSION')) {
        define('Restock\\VERSION', '0.1.0');
    }
    if (! defined('Restock\\PLUGIN_FILE')) {
        define('Restock\\PLUGIN_FILE', '/tmp/restock/restock.php');
    }
    if (! defined('Restock\\PLUGIN_DIR')) {
        define('Restock\\PLUGIN_DIR', '/tmp/restock');
    }
    if (! defined('Restock\\MIN_PHP_VERSION')) {
        define('Restock\\MIN_PHP_VERSION', '8.1.0');
    }
    if (! defined('Restock\\MIN_WC_VERSION')) {
        define('Restock\\MIN_WC_VERSION', '8.0.0');
    }
}
