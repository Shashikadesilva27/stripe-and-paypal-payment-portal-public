<?php
if (!defined('ABSPATH')) exit;

class PPS_Payment_Service {
    public static function create_virtual_order(array $request): int {
        $order = wc_create_order();
        $product_id = self::get_virtual_product_id();
        $product = wc_get_product($product_id);

        if ($product) {
            $order->add_product($product, 1, [
                'subtotal' => $request['amount'],
                'total'    => $request['amount'],
            ]);
        }

        $order->set_currency($request['currency']);
        $order->update_meta_data('_pps_external_order_id', $request['external_order_id']);
        $order->update_meta_data('_pps_source_domain', $request['source_domain']);
        $order->calculate_totals();
        $order->save();

        return $order->get_id();
    }

    public static function get_virtual_product_id(): int {
        $product_id = (int) get_option('pps_virtual_product_id', 0);
        if ($product_id && get_post($product_id)) {
            return $product_id;
        }

        $product = new WC_Product_Simple();
        $product->set_name('External Payment');
        $product->set_virtual(true);
        $product->set_regular_price(0);
        $product->set_catalog_visibility('hidden');
        $product_id = $product->save();

        update_option('pps_virtual_product_id', $product_id);
        return $product_id;
    }

    public static function create_provider_session(int $order_id, string $provider): array {
        // Public-safe placeholder. Production implementations call Stripe/PayPal APIs server-side.
        return [
            'provider' => sanitize_text_field($provider),
            'redirect_url' => add_query_arg([
                'pps_demo_payment' => 1,
                'order_id' => $order_id,
            ], wc_get_checkout_url()),
        ];
    }
}
