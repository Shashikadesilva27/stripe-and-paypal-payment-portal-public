<?php
if (!defined('ABSPATH')) exit;

class PPS_REST_Controller {
    public static function init(): void {
        add_action('rest_api_init', [__CLASS__, 'routes']);
    }

    public static function routes(): void {
        register_rest_route('payment-portal/v1', '/request', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'create_payment_request'],
            'permission_callback' => '__return_true',
        ]);
    }

    public static function create_payment_request(WP_REST_Request $request): WP_REST_Response {
        $body = (array) $request->get_json_params();
        $signature = (string) $request->get_header('x-pps-signature');

        if (!PPS_Security::verify_payload($body, $signature)) {
            return new WP_REST_Response(['error' => 'Invalid signature'], 401);
        }

        $data = PPS_Security::sanitize_payment_request($body);
        if (!$data['external_order_id'] || $data['amount'] <= 0) {
            return new WP_REST_Response(['error' => 'Invalid payment request'], 422);
        }

        $woo_order_id = PPS_Payment_Service::create_virtual_order($data);

        PPS_Order_Log::upsert(array_merge($data, [
            'woo_order_id' => $woo_order_id,
            'status' => 'pending',
            'request_hash' => hash('sha256', wp_json_encode($body)),
        ]));

        return new WP_REST_Response([
            'order_id' => $woo_order_id,
            'checkout_url' => $GLOBALS['woocommerce']->cart ? wc_get_checkout_url() : home_url('/checkout/'),
        ], 200);
    }
}
