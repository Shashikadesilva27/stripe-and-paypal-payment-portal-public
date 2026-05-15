<?php
if (!defined('ABSPATH')) exit;

class PPS_Admin {
    public static function init(): void {
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_init', [__CLASS__, 'settings']);
    }

    public static function menu(): void {
        add_menu_page(
            'Payment Portal',
            'Payment Portal',
            'manage_options',
            'payment-portal-showcase',
            [__CLASS__, 'page'],
            'dashicons-shield-alt'
        );
    }

    public static function settings(): void {
        register_setting('pps_settings', 'pps_shared_secret');
        register_setting('pps_settings', 'pps_callback_url');
    }

    public static function page(): void {
        ?>
        <div class="wrap">
            <h1>Payment Portal Showcase</h1>
            <p>Public-safe settings page demonstrating secure configuration structure.</p>
            <form method="post" action="options.php">
                <?php settings_fields('pps_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Shared Secret</th>
                        <td><input type="password" class="regular-text" name="pps_shared_secret" value="<?php echo esc_attr(get_option('pps_shared_secret')); ?>"></td>
                    </tr>
                    <tr>
                        <th scope="row">Callback URL</th>
                        <td><input type="url" class="regular-text" name="pps_callback_url" value="<?php echo esc_attr(get_option('pps_callback_url')); ?>"></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
