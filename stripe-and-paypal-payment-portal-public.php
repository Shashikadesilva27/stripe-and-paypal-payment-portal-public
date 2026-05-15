<?php
/**
 * Plugin Name: Stripe and Paypal payment portal showcase
 * Plugin URI: https://labs.flashify.ae/
 * Description: Public-safe WooCommerce external Stripe and Paypal payment portal architecture demo. This is a demostration only.
 * Version: 1.0.3
 * Author: Shashika De Silva
 * Author URI: https://www.linkedin.com/in/shashika-de-silva27/
 */

if (!defined('ABSPATH')) {
    exit;
}

define('PPS_VERSION', '1.0.0');
define('PPS_PATH', plugin_dir_path(__FILE__));
define('PPS_URL', plugin_dir_url(__FILE__));

require_once PPS_PATH . 'includes/class-pps-install.php';
require_once PPS_PATH . 'includes/class-pps-security.php';
require_once PPS_PATH . 'includes/class-pps-order-log.php';
require_once PPS_PATH . 'includes/class-pps-payment-service.php';
require_once PPS_PATH . 'includes/class-pps-webhook-service.php';
require_once PPS_PATH . 'includes/class-pps-rest-controller.php';
require_once PPS_PATH . 'includes/class-pps-admin.php';

register_activation_hook(__FILE__, ['PPS_Install', 'activate']);

add_action('plugins_loaded', function () {
    if (!class_exists('WooCommerce')) {
        return;
    }

    PPS_Admin::init();
    PPS_REST_Controller::init();
    PPS_Webhook_Service::init();
});
