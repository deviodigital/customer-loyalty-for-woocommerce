<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    CLWC
 * @subpackage CLWC/admin
 * @author     Devio Digital <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CLWC
 * @subpackage CLWC/admin
 * @author     Devio Digital <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 */
class Customer_Loyalty_Admin {

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string  $plugin_name - The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string  $version - The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name - The name of this plugin.
     * @param string $version     - The version of this plugin.
     * 
     * @since 1.0.0
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_styles( $hook_suffix ) {
        // Only enqueue on your specific admin page.
        if ( 'woocommerce_page_clwc-customer-loyalty' !== $hook_suffix ) {
            return;
        }

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/clwc-admin.css', [], $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_scripts( $hook_suffix ) {
        // Only enqueue on your specific admin page.
        if ( 'woocommerce_page_clwc-customer-loyalty' !== $hook_suffix ) {
            return;
        }

        wp_enqueue_media();

        wp_enqueue_script(
            'clwc-loyalty-ajax',
            plugin_dir_url( __FILE__ ) . 'js/clwc-admin.js',
            [ 'jquery' ],
            '1.0.0',
            true
        );

        wp_localize_script( 'clwc-loyalty-ajax', 'clwc_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'clwc_update_points_nonce' ),
        ] );
    }

}

/**
 * Handle AJAX request to update loyalty points.
 * 
 * @since  2.0.0
 * @return void
 */
function clwc_update_loyalty_points() {
    // Verify nonce for security
    check_ajax_referer( 'clwc_update_points_nonce', 'security' );

    // Get POST variables
    $user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : 0;
    $points  = isset( $_POST['points'] ) ? intval( $_POST['points'] ) : 0;

    // Verify user ID
    if ( ! $user_id || ! get_userdata( $user_id ) ) {
        wp_send_json_error( 'Invalid user ID.' );
    }

    // Capability check - ensure the current user can edit users
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        wp_send_json_error( 'You do not have permission to edit this user.' );
    }

    // Update user meta
    update_user_meta( $user_id, 'clwc_loyalty_points', $points );

    // Return success message
    wp_send_json_success( 'Loyalty points updated successfully.' );
}
add_action( 'wp_ajax_clwc_update_loyalty_points', 'clwc_update_loyalty_points' );
