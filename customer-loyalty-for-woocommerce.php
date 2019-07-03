<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://www.deviodigital.com
 * @since             1.0
 * @package           CLWC
 *
 * @wordpress-plugin
 * Plugin Name:       Customer Loyalty for WooCommerce
 * Plugin URI:        https://www.deviodigital.com/customer-loyalty-for-woocommerce
 * Description:       Increase customer loyalty by rewarding your customers for their repeat purchase behavior.
 * Version:           1.0
 * Author:            Devio Digital
 * Author URI:        https://www.deviodigital.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       clwc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'CLWC_VERSION', '1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-clwc-activator.php
 */
function activate_clwc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clwc-activator.php';
	CLWC_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-clwc-deactivator.php
 */
function deactivate_clwc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clwc-deactivator.php';
	CLWC_Deactivator::deactivate();
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
 * @since    1.0
 */
function run_clwc() {

	$plugin = new CLWC();
	$plugin->run();

}
run_clwc();

/**
 * Add settings link on plugin page
 *
 * @since 1.0
 * @param array $links an array of links related to the plugin.
 * @return array updatead array of links related to the plugin.
 */
function clwc_settings_link( $links ) {
	$settings_link = '<a href="admin.php?page=clwc_admin_settings">' . __( 'Settings', 'clwc' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}

$pluginname = plugin_basename( __FILE__ );

add_filter( "plugin_action_links_$pluginname", 'clwc_settings_link' );

