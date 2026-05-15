<?php
if (!defined('ABSPATH')) exit;

class PPS_Webhook_Service {
    public static function init(): void {
        add_action('woocommerce_payment_complete', [__CLASS__, 'handle_payment_complete']);
    }

    public static function handle_payment_complete(int $order_id): void {
        $order = wc_get_order($order_id);
        if (!$order) return;

        $external_order_id = (string) $order->get_meta('_pps_external_order_id');
        if (!$external_order_id) return;

        PPS_Order_Log::mark_paid($external_order_id, 'gateway-reference-placeholder');

        $payload = [
            'external_order_id' => $external_order_id,
            'woo_order_id' => $order_id,
            'status' => 'paid',
            'timestamp' => time(),
        ];

        self::send_signed_callback($order, $payload);
    }

    public static function send_signed_callback(WC_Order $order, array $payload): void {
        $callback_url = (string) get_option('pps_callback_url', '');
        if (!$callback_url) return;

        wp_remote_post($callback_url, [
            'timeout' => 20,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-PPS-Signature' => PPS_Security::sign_payload($payload),
            ],
            'body' => wp_json_encode($payload),
        ]);
    }
}
