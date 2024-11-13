<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    CLWC
 * @subpackage CLWC/public
 * @author     Devio Digital <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CLWC
 * @subpackage CLWC/public
 * @author     Devio Digital <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 */
class Customer_Loyalty_Public {

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
     * @param string $plugin_name - The name of the plugin.
     * @param string $version     - The version of this plugin.
     * 
     * @since 1.0.0
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/clwc-public.min.css', [], $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_scripts($hook_suffix) {
        // Ensure this is only enqueued on the relevant page or template
        if (is_account_page()) { // Modify condition as per your requirements
            $user_id = get_current_user_id();
    
            wp_enqueue_script(
                'clwc-redeem-script',
                plugin_dir_url(__FILE__) . 'js/clwc-redeem.js',
                ['jquery'],
                time(),
                true
            );
    
            wp_localize_script('clwc-redeem-script', 'clwc_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'user_id'  => $user_id,
                'nonce'    => wp_create_nonce('clwc_redeem_nonce')
            ]);
        }
    }
    
}
