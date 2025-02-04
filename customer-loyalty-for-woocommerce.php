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
 * Plugin Name:      Loyalty & Rewards for WooCommerce®
 * Plugin URI:       https://www.deviodigital.com/customer-loyalty-for-woocommerce
 * Description:      Increase customer loyalty by rewarding your customers for their repeat purchase behavior.
 * Version:          2.0.0
 * Author:           Devio Digital
 * Author URI:       https://www.deviodigital.com
 * License:          GPL-2.0+
 * License URI:      http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:      customer-loyalty-for-woocommerce
 * Domain Path:      /languages
 * Update URI:       https://github.com/deviodigital/customer-loyalty-for-woocommerce/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    wp_die();
}

// Current plugin version.
define( 'CUSTOMER_LOYALTY_VERSION', '2.0.0' );

require 'vendor/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/robertdevore/customer-loyalty-for-woocommerce/',
    __FILE__,
    'customer-loyalty-for-woocommerce'
);

// Set the branch that contains the stable release.
$myUpdateChecker->setBranch( 'main' );

// Check if Composer's autoloader is already registered globally.
if ( ! class_exists( 'RobertDevore\WPComCheck\WPComPluginHandler' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use RobertDevore\WPComCheck\WPComPluginHandler;

new WPComPluginHandler( plugin_basename( __FILE__ ), 'https://robertdevore.com/why-this-plugin-doesnt-support-wordpress-com-hosting/' );

/**
 * Create the CLWC Loyalty Log database table.
 *
 * This function creates a table for storing loyalty log entries in the database.
 * It includes fields for storing the customer ID, name, email, points, action details, and date.
 *
 * @package CLWC
 * @since   2.0.0
 */
function clwc_create_loyalty_log_table() {
    global $wpdb;

    // Define table name with the WordPress® table prefix.
    $table_name = $wpdb->prefix . 'clwc_loyalty_log';

    // Set the database character set and collation for security.
    $charset_collate = $wpdb->get_charset_collate();

    // SQL statement to create the table if it does not exist.
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        points INT(11) NOT NULL,
        details TEXT NOT NULL,
        date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id),
        KEY user_id (user_id),
        KEY date (date)
    ) $charset_collate;";

    // Load the dbDelta function, which manages database upgrades and creation.
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'clwc_create_loyalty_log_table' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-clwc-activator.php
 * 
 * @since  1.0.0
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
 * @since  1.0.0
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
 * @since  1.0.0
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
    $settings_link = '<a href="admin.php?page=clwc-customer-loyalty">' . esc_attr__( 'Settings', 'customer-loyalty-for-woocommerce' ) . '</a>';
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
 * @since  1.0.0
 * @return void
 */
function clwc_redirect() {
    if ( get_option( 'clwc_do_activation_redirect', false ) ) {
        delete_option( 'clwc_do_activation_redirect' );
        if ( null === filter_input( INPUT_GET, 'activate-multi' ) ) {
            wp_safe_redirect( 'admin.php?page=clwc-customer-loyalty' );
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

/**
 * AJAX handler to redeem points and return a new coupon.
 * 
 * @since  2.0.0
 * @return void
 */
function clwc_redeem_points_callback() {
    // Verify the nonce for security.
    if ( ! check_ajax_referer( 'clwc_redeem_nonce', 'nonce', false ) ) {
        wp_send_json_error( ['message' => __( 'Nonce verification failed', 'customer-loyalty-for-woocommerce' )] );
        return;
    }

    $user_id = get_current_user_id();

    // Check if the user is authenticated.
    if ( ! $user_id ) {
        wp_send_json_error( ['message' => __( 'User not authenticated', 'customer-loyalty-for-woocommerce' )] );
        return;
    }

    // Fetch loyalty points and minimum redeemable points.
    $loyalty_points    = get_user_meta( $user_id, 'clwc_loyalty_points', true ) ?: 0;
    $redeem_points_min = clwc_loyalty_points_redeem_points_minimum();

    // Check if the user has enough points.
    if ( $loyalty_points < $redeem_points_min ) {
        wp_send_json_error( ['message' => __( 'Insufficient points to redeem.', 'customer-loyalty-for-woocommerce' )] );
        return;
    }

    // Generate a new coupon code.
    $coupon_code   = clwc_get_random_string();
    $coupon_amount = clwc_loyalty_points_redeem_points_value();

    // Create the coupon post.
    $coupon_id = wp_insert_post( [
        'post_title'  => $coupon_code,
        'post_status' => 'publish',
        'post_type'   => 'shop_coupon',
        'post_author' => $user_id,
    ] );

    if ( is_wp_error( $coupon_id ) ) {
        wp_send_json_error( ['message' => __( 'Failed to create coupon.', 'customer-loyalty-for-woocommerce' )] );
        return;
    }

    // Add metadata to the coupon.
    update_post_meta( $coupon_id, 'discount_type', 'fixed_cart' );
    update_post_meta( $coupon_id, 'coupon_amount', $coupon_amount );
    update_post_meta( $coupon_id, 'usage_limit', '1' );

    // Deduct points and update the user's points.
    $updated_points = $loyalty_points - $redeem_points_min;
    update_user_meta( $user_id, 'clwc_loyalty_points', $updated_points );

    // Log the redemption in the loyalty log table.
    $user_info  = get_userdata( $user_id );
    $user_name  = $user_info ? $user_info->display_name : '';
    $user_email = $user_info ? $user_info->user_email : '';

    // Generate the WooCommerce Orders link for the specific user.
    $orders_link = admin_url( 'edit.php?post_type=shop_order&s=' . urlencode( $user_email ) );

    // Create a linked customer name for the details message.
    $details = sprintf(
        wp_kses(
            /* Translators: %1$s is the linked customer name, %2$d is points, %3$s is the coupon amount. */
            __( '<a href="%1$s" target="_blank">%2$s</a> redeemed %3$d points for a coupon worth %4$s.', 'customer-loyalty-for-woocommerce' ),
            [ 'a' => [ 'href' => [], 'target' => [] ] ]
        ),
        esc_url( $orders_link ),
        esc_html( $user_name ),
        $redeem_points_min,
        wc_price( $coupon_amount )
    );
    clwc_insert_loyalty_log_entry( $user_id, $user_name, $user_email, $redeem_points_min, $details );

    // Generate HTML for the new coupon row.
    $new_coupon_html = sprintf(
        '<tr style="font-weight: bold; color: green;"><td><strong>%s</strong> - %s</td><td><span class="clwc-available-coupon">Available</span></td></tr>',
        esc_html( $coupon_code ),
        wc_price( $coupon_amount )
    );

    // Send success response.
    wp_send_json_success( ['html' => $new_coupon_html, 'updated_points' => $updated_points] );
}
add_action( 'wp_ajax_clwc_redeem_points', 'clwc_redeem_points_callback' );

/**
 * AJAX handler to update loyalty points for a specific user.
 *
 * @since  2.0.0
 * @return void
 */
function clwc_update_loyalty_points_callback() {
    // Verify the nonce for security.
    if ( ! check_ajax_referer( 'clwc_nonce', 'security', false ) ) {
        wp_send_json_error( [ 'message' => __( 'Nonce verification failed', 'customer-loyalty-for-woocommerce' ) ] );
        return;
    }

    // Retrieve and sanitize data from the AJAX request.
    $user_id = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : 0;
    $new_points = isset( $_POST['points'] ) ? intval( $_POST['points'] ) : 0;

    if ( ! $user_id ) {
        wp_send_json_error( [ 'message' => __( 'Invalid user ID.', 'customer-loyalty-for-woocommerce' ) ] );
        return;
    }

    // Get current points and calculate the change.
    $current_points = (int) get_user_meta( $user_id, 'clwc_loyalty_points', true );
    $points_change = $new_points - $current_points;

    // Update the user's points.
    update_user_meta( $user_id, 'clwc_loyalty_points', $new_points );

    // Get the admin username who made the change.
    $admin_user = wp_get_current_user();
    $admin_name = $admin_user->display_name;

    // Log the change in the loyalty log table.
    $user_info  = get_userdata( $user_id );
    $user_name  = $user_info->display_name;
    $user_email = $user_info->user_email;
    $details    = sprintf(
        esc_html__( '%s adjusted points for %s by %s%d.', 'customer-loyalty-for-woocommerce' ),
        $admin_name,
        $user_name,
        $points_change > 0 ? '+' : '',
        $points_change
    );
    clwc_insert_loyalty_log_entry( $user_id, $user_name, $user_email, $points_change, $details );

    wp_send_json_success( [ 'message' => __( 'Points updated successfully.', 'customer-loyalty-for-woocommerce' ) ] );
}
add_action( 'wp_ajax_clwc_update_loyalty_points', 'clwc_update_loyalty_points_callback' );
