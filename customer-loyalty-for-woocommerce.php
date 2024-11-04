<?php

/**
 * The plugin bootstrap file
 *
 * @package CLWC
 * @author  Devio Digital <contact@deviodigital.com>
 * @license GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link    https://www.deviodigital.com
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:      Customer Loyalty for WooCommerce®
 * Plugin URI:       https://www.deviodigital.com/customer-loyalty-for-woocommerce
 * Description:      Increase customer loyalty by rewarding your customers for their repeat purchase behavior.
 * Version:          1.3.1
 * Author:           Devio Digital
 * Author URI:       https://www.deviodigital.com
 * License:          GPL-2.0+
 * License URI:      http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:      customer-loyalty-for-woocommerce
 * Domain Path:      /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    wp_die();
}

/**
 * Current plugin version.
 */
define( 'CUSTOMER_LOYALTY_VERSION', '1.3.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-clwc-activator.php
 * 
 * @return void
 */
function activate_clwc() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-clwc-activator.php';
    Customer_Loyalty_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-clwc-deactivator.php
 * 
 * @return void
 */
function deactivate_clwc() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-clwc-deactivator.php';
    Customer_Loyalty_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_clwc' );
register_deactivation_hook( __FILE__, 'deactivate_clwc' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-clwc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since  1.0
 * @return void
 */
function run_clwc() {

    $plugin = new CLWC();
    $plugin->run();

}
run_clwc();

/**
 * Add settings link on plugin page
 *
 * @param array $links an array of links related to the plugin.
 * 
 * @since  1.0.0
 * @return array
 */
function clwc_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=clwc_admin_settings">' . esc_attr__( 'Settings', 'customer-loyalty-for-woocommerce' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}

$pluginname = plugin_basename( __FILE__ );

add_filter( "plugin_action_links_$pluginname", 'clwc_settings_link' );

/**
 * Add a check for our plugin before redirecting
 * 
 * @since  1.0.0
 * @return void
 */
function clwc_activate() {
    add_option( 'clwc_do_activation_redirect', true );
}
register_activation_hook( __FILE__, 'clwc_activate' );

/**
 * Redirect to the Customer Loyalty for WooCommerce Settings page 
 * on single plugin activation
 * 
 * @since  1.0
 * @return void
 */
function clwc_redirect() {
    if ( get_option( 'clwc_do_activation_redirect', false ) ) {
        delete_option( 'clwc_do_activation_redirect' );
        if ( null === filter_input( INPUT_GET, 'activate-multi' ) ) {
            wp_safe_redirect( 'admin.php?page=clwc_admin_settings' );
        }
    }
}
add_action( 'admin_init', 'clwc_redirect' );

/**
 * Display a custom admin notice to inform users about plugin update issues.
 *
 * This function displays a dismissible admin notice warning users about 
 * restrictions imposed by WordPress® leadership that may impact automatic 
 * plugin updates. It provides a link to a resource where users can learn how 
 * to continue receiving updates.
 *
 * @since  1.3.1
 * @return void
 */
function custom_update_notice() {
    // Translating the notice text using WordPress® translation functions.
    $notice_text = sprintf(
        esc_html__( 'Important Notice: Due to recent changes initiated by WordPress® leadership, access to the plugin repository is being restricted for certain hosting providers and developers. This may impact automatic updates for your plugins. To ensure you continue receiving updates and to learn about the next steps, please visit %s.', 'dispensary-age-verification' ),
        '<a href="https://robertdevore.com/wordpress-plugin-updates/" target="_blank">this page</a>'
    );

    // Display the admin notice.
    echo '<div class="notice notice-warning is-dismissible">
        <p>' . $notice_text . '</p>
    </div>';
}
//add_action( 'admin_notices', 'custom_update_notice' );
