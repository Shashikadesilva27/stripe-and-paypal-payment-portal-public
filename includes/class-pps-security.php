<?php
if (!defined('ABSPATH')) exit;

class PPS_Security {
    public static function get_shared_secret(): string {
        return (string) get_option('pps_shared_secret', 'change-me-in-production');
    }

    public static function sign_payload(array $payload): string {
        ksort($payload);
        return hash_hmac('sha256', wp_json_encode($payload), self::get_shared_secret());
    }

    public static function verify_payload(array $payload, string $signature): bool {
        $expected = self::sign_payload($payload);
        return hash_equals($expected, $signature);
    }

    public static function sanitize_payment_request(array $raw): array {
        return [
            'external_order_id' => sanitize_text_field($raw['external_order_id'] ?? ''),
            'source_domain'     => sanitize_text_field($raw['source_domain'] ?? ''),
            'amount'            => (float) ($raw['amount'] ?? 0),
            'currency'          => sanitize_text_field($raw['currency'] ?? 'USD'),
            'return_url'        => esc_url_raw($raw['return_url'] ?? ''),
        ];
    }
}
