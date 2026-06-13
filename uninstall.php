<?php

/**
 * Restock uninstall routine.
 *
 * Drops the plugin table and removes plugin options when the user deletes
 * the plugin from the WordPress admin.
 *
 * @package Restock
 */

defined('WP_UNINSTALL_PLUGIN') || exit;

global $wpdb;

// Drop the waitlist table.
$table = $wpdb->prefix . 'restock_waitlist';
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

// Remove options.
delete_option( 'restock_settings' );
delete_option( 'restock_schema_version' );
