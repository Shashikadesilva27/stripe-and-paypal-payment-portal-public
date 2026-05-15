<?php
if (!defined('ABSPATH')) exit;

class PPS_Order_Log {
    public static function upsert(array $data): bool {
        global $wpdb;
        $table = $wpdb->prefix . 'pps_external_orders';

        $row = [
            'external_order_id'  => $data['external_order_id'],
            'source_domain'      => $data['source_domain'],
            'woo_order_id'       => $data['woo_order_id'] ?? null,
            'payment_provider'   => $data['payment_provider'] ?? null,
            'provider_reference' => $data['provider_reference'] ?? null,
            'amount'             => $data['amount'] ?? 0,
            'currency'           => $data['currency'] ?? 'USD',
            'status'             => $data['status'] ?? 'pending',
            'request_hash'       => $data['request_hash'] ?? null,
            'created_at'         => current_time('mysql'),
            'updated_at'         => current_time('mysql'),
        ];

        $existing_id = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$table} WHERE external_order_id = %s LIMIT 1",
            $row['external_order_id']
        ));

        if ($existing_id) {
            unset($row['created_at']);
            return false !== $wpdb->update($table, $row, ['id' => (int) $existing_id]);
        }

        return false !== $wpdb->insert($table, $row);
    }

    public static function mark_paid(string $external_order_id, string $provider_reference): bool {
        global $wpdb;
        $table = $wpdb->prefix . 'pps_external_orders';

        return false !== $wpdb->update(
            $table,
            [
                'status' => 'paid',
                'provider_reference' => sanitize_text_field($provider_reference),
                'updated_at' => current_time('mysql'),
            ],
            ['external_order_id' => sanitize_text_field($external_order_id)]
        );
    }
}
