<?php
if (!defined('ABSPATH')) exit;

class PPS_Install {
    public static function activate(): void {
        global $wpdb;

        $table = $wpdb->prefix . 'pps_external_orders';
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            external_order_id VARCHAR(100) NOT NULL,
            source_domain VARCHAR(190) NOT NULL,
            woo_order_id BIGINT UNSIGNED NULL,
            payment_provider VARCHAR(50) NULL,
            provider_reference VARCHAR(190) NULL,
            amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            currency VARCHAR(10) NOT NULL DEFAULT 'USD',
            status VARCHAR(50) NOT NULL DEFAULT 'pending',
            request_hash VARCHAR(190) NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NULL,
            PRIMARY KEY (id),
            UNIQUE KEY external_order_id (external_order_id),
            KEY woo_order_id (woo_order_id),
            KEY status (status)
        ) {$charset};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
