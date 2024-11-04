<?php
/**
 * Customer Loyalty for WooCommerce® - Admin Settings
 * 
 * @package    CLWC
 * @subpackage CLWC/admin
 * @author     Devio Digital <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://www.deviodigital.com
 * @since      1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    wp_die();
}

/**
 * Add a Customer Loyalty Points page as a sub-menu under WooCommerce®.
 */
function clwc_add_loyalty_points_submenu() {
    add_submenu_page(
        'woocommerce', // Parent slug to add the menu under WooCommerce®.
        esc_html__( 'Customer Loyalty Settings', 'customer-loyalty-for-woocommerce' ),
        esc_html__( 'Loyalty Points', 'customer-loyalty-for-woocommerce' ),
        'manage_options',
        'clwc-customer-loyalty',
        'clwc_render_loyalty_points_page'
    );
}
add_action( 'admin_menu', 'clwc_add_loyalty_points_submenu' );

function clwc_render_loyalty_points_page() {
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'rewards_card';
    ?>
    <div class="wrap">
        <h1 style="display: block; align-items: center;">
            <?php esc_html_e( 'Customer Loyalty Settings', 'customer-loyalty-for-woocommerce' ); ?>
            <span style="font-size: small; margin-left: 10px; display: inline-block; vertical-align: middle;">
                <?php if ( defined( 'CUSTOMER_LOYALTY_VERSION' ) ) : ?>
                    v<?php echo esc_html( CUSTOMER_LOYALTY_VERSION ); ?> &middot; 
                <?php endif; ?>
            </span>
            <span style="font-size: small; margin-left: 10px; display: inline-block; vertical-align: middle; float:right;">
                <a href="https://robertdevore.com/customer-loyalty-for-woocommerce-documentation/" target="_blank" class="button button-primary">
                    <?php esc_html_e( 'Documentation', 'customer-loyalty-for-woocommerce' ); ?>
                </a>
                <a href="https://robertdevore.com/wordpress-and-woocommerce-plugins/" target="_blank" class="button">
                    <?php esc_html_e( 'More Plugins', 'customer-loyalty-for-woocommerce' ); ?>
                </a>
            </span>
        </h1>

        <h2 class="nav-tab-wrapper" style="margin-top:18px;">
            <a href="?page=clwc-customer-loyalty&tab=rewards_card" class="nav-tab <?php echo $active_tab === 'rewards_card' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e( 'Rewards Card', 'customer-loyalty-for-woocommerce' ); ?>
            </a>
            <a href="?page=clwc-customer-loyalty&tab=loyalty_points" class="nav-tab <?php echo $active_tab === 'loyalty_points' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e( 'Loyalty Points', 'customer-loyalty-for-woocommerce' ); ?>
            </a>
            <a href="?page=clwc-customer-loyalty&tab=manage_points" class="nav-tab <?php echo $active_tab === 'manage_points' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e( 'Manage Points', 'customer-loyalty-for-woocommerce' ); ?>
            </a>
            <a href="?page=clwc-customer-loyalty&tab=log" class="nav-tab <?php echo $active_tab === 'log' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e( 'Log', 'customer-loyalty-for-woocommerce' ); ?>
            </a>
        </h2>

        <form method="post" action="options.php">
            <?php
            switch ( $active_tab ) {
                case 'rewards_card':
                    // Register the settings fields for Rewards Card.
                    settings_fields( 'clwc_rewards_card' );
                    // Display Rewards Card settings sections and fields.
                    do_settings_sections( 'clwc_rewards_card' );
                    break;

                case 'loyalty_points':
                    // Register the settings fields for Loyalty Points.
                    settings_fields( 'clwc_loyalty_points' );
                    // Display Loyalty Points settings sections and fields.
                    do_settings_sections( 'clwc_loyalty_points' );
                    break;

                case 'manage_points':
                    // Display the Manage Points table.
                    clwc_display_manage_points_table();
                    break;

                case 'log':
                    // Display the Log table.
                    clwc_display_log_table();
                    break;
            }
            ?>
            <?php if ( $active_tab === 'rewards_card' || $active_tab === 'loyalty_points' ) : ?>
                <?php submit_button(); ?>
            <?php endif; ?>
        </form>
    </div>
    <?php
}

/**
 * Register and display Rewards Card settings fields.
 * 
 * @since  2.0.0
 * @return void
 */
function clwc_rewards_card_settings() {
    // Register settings
    register_setting( 'clwc_rewards_card', 'clwc_rewards_card_settings', 'clwc_sanitize_rewards_card_settings' );

    // Add settings section
    add_settings_section( 'clwc_rewards_card_section', '', '__return_false', 'clwc_rewards_card' );

    // Rewards Card Fields
    add_settings_field(
        'activate_rewards_card',
        __( 'Activate Rewards Card', 'customer-loyalty-for-woocommerce' ),
        'clwc_checkbox_field',
        'clwc_rewards_card',
        'clwc_rewards_card_section',
        [
            'label_for' => 'activate_rewards_card',
            'name'      => 'clwc_rewards_card_settings[activate_rewards_card]',
            'checked'   => get_option( 'clwc_rewards_card_settings' )['activate_rewards_card'] ?? ''
        ]
    );

    add_settings_field(
        'rewards_card_title',
        __( 'Rewards Card Title', 'customer-loyalty-for-woocommerce' ),
        'clwc_text_field',
        'clwc_rewards_card',
        'clwc_rewards_card_section',
        [
            'label_for' => 'rewards_card_title',
            'name'      => 'clwc_rewards_card_settings[rewards_card_title]',
            'value'     => get_option( 'clwc_rewards_card_settings' )['rewards_card_title'] ?? ''
        ]
    );

    add_settings_field(
        'rewards_card_text',
        __( 'Rewards Card Text', 'customer-loyalty-for-woocommerce' ),
        'clwc_wysiwyg_field',
        'clwc_rewards_card',
        'clwc_rewards_card_section',
        [
            'name'  => 'clwc_rewards_card_settings[rewards_card_text]',
            'value' => get_option( 'clwc_rewards_card_settings' )['rewards_card_text'] ?? ''
        ]
    );

    add_settings_field(
        'rewards_card_image',
        __( 'Rewards Card Image', 'customer-loyalty-for-woocommerce' ),
        'clwc_image_upload_field',
        'clwc_rewards_card',
        'clwc_rewards_card_section',
        [
            'name'  => 'clwc_rewards_card_settings[rewards_card_image]',
            'value' => get_option( 'clwc_rewards_card_settings' )['rewards_card_image'] ?? ''
        ]
    );

    // Rewards Coupon Fields
    add_settings_field(
        'required_punches',
        __( 'Required Punches', 'customer-loyalty-for-woocommerce' ),
        'clwc_number_field',
        'clwc_rewards_card',
        'clwc_rewards_card_section',
        [
            'label_for' => 'required_punches',
            'name'      => 'clwc_rewards_card_settings[required_punches]',
            'value'     => get_option( 'clwc_rewards_card_settings' )['required_punches'] ?? ''
        ]
    );

    add_settings_field(
        'coupon_amount',
        __( 'Coupon Amount', 'customer-loyalty-for-woocommerce' ),
        'clwc_number_field',
        'clwc_rewards_card',
        'clwc_rewards_card_section',
        [
            'label_for' => 'coupon_amount',
            'name'      => 'clwc_rewards_card_settings[coupon_amount]',
            'value'     => get_option( 'clwc_rewards_card_settings' )['coupon_amount'] ?? ''
        ]
    );

    add_settings_field(
        'coupon_type',
        __( 'Coupon Type', 'customer-loyalty-for-woocommerce' ),
        'clwc_select_field',
        'clwc_rewards_card',
        'clwc_rewards_card_section',
        [
            'label_for' => 'coupon_type',
            'name'      => 'clwc_rewards_card_settings[coupon_type]',
            'options'   => [
                'fixed_cart' => __( 'Fixed Cart', 'customer-loyalty-for-woocommerce' ),
                'percentage' => __( 'Percentage', 'customer-loyalty-for-woocommerce' ),
            ],
            'selected'  => get_option( 'clwc_rewards_card_settings' )['coupon_type'] ?? ''
        ]
    );

    add_settings_field(
        'coupon_prefix',
        __( 'Coupon Prefix', 'customer-loyalty-for-woocommerce' ),
        'clwc_text_field',
        'clwc_rewards_card',
        'clwc_rewards_card_section',
        [
            'label_for' => 'coupon_prefix',
            'name'      => 'clwc_rewards_card_settings[coupon_prefix]',
            'value'     => get_option( 'clwc_rewards_card_settings' )['coupon_prefix'] ?? ''
        ]
    );
}
add_action( 'admin_init', 'clwc_rewards_card_settings' );

// Callbacks for fields

/**
 * Render checkbox field.
 *
 * @param array $args Field arguments.
 * 
 * @since  2.0.0
 * @return void
 */
function clwc_checkbox_field( $args ) {
    printf(
        '<input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s />',
        esc_attr( $args['label_for'] ),
        esc_attr( $args['name'] ),
        checked( 1, $args['checked'], false )
    );
}

/**
 * Render text field.
 *
 * @param array $args Field arguments.
 * 
 * @since  2.0.0
 * @return void
 */
function clwc_text_field( $args ) {
    printf(
        '<input type="text" id="%1$s" name="%2$s" value="%3$s" class="regular-text" />',
        esc_attr( $args['label_for'] ),
        esc_attr( $args['name'] ),
        esc_attr( $args['value'] )
    );
}

/**
 * Render WYSIWYG field.
 *
 * @param array $args Field arguments.
 * 
 * @since  2.0.0
 * @return void
 */
function clwc_wysiwyg_field( $args ) {
    wp_editor( wp_kses_post( $args['value'] ), $args['name'], [ 'textarea_name' => esc_attr( $args['name'] ) ] );
}

/**
 * Render image upload field.
 *
 * @param array $args Field arguments.
 * 
 * @since  2.0.0
 * @return void
 */
function clwc_image_upload_field( $args ) {
    $image = wp_get_attachment_url( intval( $args['value'] ) );
    printf(
        '<input type="hidden" name="%1$s" value="%2$s" />',
        esc_attr( $args['name'] ),
        esc_attr( $args['value'] )
    );
    printf(
        '<img src="%s" style="max-width:150px;display:block;margin-top:10px;" /><br />',
        esc_url( $image )
    );
    printf(
        '<button type="button" class="button clwc-upload-image-button">%s</button>',
        esc_html__( 'Upload Image', 'customer-loyalty-for-woocommerce' )
    );
}

/**
 * Render number field.
 *
 * @param array $args Field arguments.
 * 
 * @since  2.0.0
 * @return void
 */
function clwc_number_field( $args ) {
    printf(
        '<input type="number" id="%1$s" name="%2$s" value="%3$s" class="small-text" />',
        esc_attr( $args['label_for'] ),
        esc_attr( $args['name'] ),
        esc_attr( $args['value'] )
    );
}

/**
 * Render select field.
 *
 * @param array $args Field arguments.
 * 
 * @since  2.0.0
 * @return void
 */
function clwc_select_field( $args ) {
    printf( '<select id="%1$s" name="%2$s">', esc_attr( $args['label_for'] ), esc_attr( $args['name'] ) );

    foreach ( $args['options'] as $value => $label ) {
        printf(
            '<option value="%1$s" %2$s>%3$s</option>',
            esc_attr( $value ),
            selected( $args['selected'], $value, false ),
            esc_html( $label )
        );
    }

    echo '</select>';
}

/**
 * Sanitize rewards card settings.
 *
 * @param array $input Settings input.
 * 
 * @since  2.0.0
 * @return array Sanitized settings.
 */
function clwc_sanitize_rewards_card_settings( $input ) {
    $sanitized = [];

    $sanitized['activate_rewards_card'] = isset( $input['activate_rewards_card'] ) ? 1 : 0;
    $sanitized['rewards_card_title']    = sanitize_text_field( $input['rewards_card_title'] ?? '' );
    $sanitized['rewards_card_text']     = wp_kses_post( $input['rewards_card_text'] ?? '' );
    $sanitized['rewards_card_image']    = intval( $input['rewards_card_image'] ?? 0 );
    $sanitized['required_punches']      = intval( $input['required_punches'] ?? 0 );
    $sanitized['coupon_amount']         = floatval( $input['coupon_amount'] ?? 0 );
    $sanitized['coupon_type']           = sanitize_text_field( $input['coupon_type'] ?? '' );
    $sanitized['coupon_prefix']         = sanitize_text_field( $input['coupon_prefix'] ?? '' );

    return $sanitized;
}

/**
 * Register and display Loyalty Points settings fields.
 * 
 * @since  2.0.0
 * @return void
 */
function clwc_loyalty_points_settings() {
    // Register settings
    register_setting( 'clwc_loyalty_points', 'clwc_loyalty_points_settings', 'clwc_sanitize_loyalty_points_settings' );

    // Add settings section
    add_settings_section( 'clwc_loyalty_points_section', '', '__return_false', 'clwc_loyalty_points' );

    // Loyalty Points Activation
    add_settings_field(
        'activate_loyalty_points',
        __( 'Activate Loyalty Points', 'customer-loyalty-for-woocommerce' ),
        'clwc_checkbox_field',
        'clwc_loyalty_points',
        'clwc_loyalty_points_section',
        [
            'label_for' => 'activate_loyalty_points',
            'name'      => 'clwc_loyalty_points_settings[activate_loyalty_points]',
            'checked'   => get_option( 'clwc_loyalty_points_settings' )['activate_loyalty_points'] ?? ''
        ]
    );

    // Calculation Type (Order Subtotal or Order Total)
    add_settings_field(
        'calculation_type',
        __( 'Calculation Type', 'customer-loyalty-for-woocommerce' ),
        'clwc_select_field',
        'clwc_loyalty_points',
        'clwc_loyalty_points_section',
        [
            'label_for' => 'calculation_type',
            'name'      => 'clwc_loyalty_points_settings[calculation_type]',
            'options'   => [
                'order_subtotal' => __( 'Order Subtotal', 'customer-loyalty-for-woocommerce' ),
                'order_total'    => __( 'Order Total', 'customer-loyalty-for-woocommerce' ),
            ],
            'selected'  => get_option( 'clwc_loyalty_points_settings' )['calculation_type'] ?? 'order_subtotal'
        ]
    );

    // Redeeming Points
    add_settings_field(
        'minimum_points',
        __( 'Minimum Points', 'customer-loyalty-for-woocommerce' ),
        'clwc_number_field',
        'clwc_loyalty_points',
        'clwc_loyalty_points_section',
        [
            'label_for' => 'minimum_points',
            'name'      => 'clwc_loyalty_points_settings[minimum_points]',
            'value'     => get_option( 'clwc_loyalty_points_settings' )['minimum_points'] ?? 0
        ]
    );

    add_settings_field(
        'redeemable_value',
        __( 'Redeemable Value', 'customer-loyalty-for-woocommerce' ),
        'clwc_number_field',
        'clwc_loyalty_points',
        'clwc_loyalty_points_section',
        [
            'label_for' => 'redeemable_value',
            'name'      => 'clwc_loyalty_points_settings[redeemable_value]',
            'value'     => get_option( 'clwc_loyalty_points_settings' )['redeemable_value'] ?? 0
        ]
    );

    // Earning Points
    add_settings_field(
        'customer_registration',
        __( 'Customer Registration', 'customer-loyalty-for-woocommerce' ),
        'clwc_number_field',
        'clwc_loyalty_points',
        'clwc_loyalty_points_section',
        [
            'label_for' => 'customer_registration',
            'name'      => 'clwc_loyalty_points_settings[customer_registration]',
            'value'     => get_option( 'clwc_loyalty_points_settings' )['customer_registration'] ?? 0
        ]
    );

    add_settings_field(
        'order_complete',
        __( 'Order Complete', 'customer-loyalty-for-woocommerce' ),
        'clwc_number_field',
        'clwc_loyalty_points',
        'clwc_loyalty_points_section',
        [
            'label_for' => 'order_complete',
            'name'      => 'clwc_loyalty_points_settings[order_complete]',
            'value'     => get_option( 'clwc_loyalty_points_settings' )['order_complete'] ?? 0
        ]
    );

    add_settings_field(
        'money_spent',
        __( 'Money Spent', 'customer-loyalty-for-woocommerce' ),
        'clwc_number_field',
        'clwc_loyalty_points',
        'clwc_loyalty_points_section',
        [
            'label_for' => 'money_spent',
            'name'      => 'clwc_loyalty_points_settings[money_spent]',
            'value'     => get_option( 'clwc_loyalty_points_settings' )['money_spent'] ?? 0
        ]
    );
}
add_action( 'admin_init', 'clwc_loyalty_points_settings' );

// Sanitize Loyalty Points settings

/**
 * Sanitize loyalty points settings input.
 *
 * @param array $input Raw input data.
 * 
 * @since  2.0.0
 * @return array Sanitized settings data.
 */
function clwc_sanitize_loyalty_points_settings( $input ) {
    $sanitized = [];

    $sanitized['activate_loyalty_points'] = isset( $input['activate_loyalty_points'] ) ? 1 : 0;
    $sanitized['calculation_type']        = sanitize_text_field( $input['calculation_type'] ?? 'order_subtotal' );
    $sanitized['minimum_points']          = intval( $input['minimum_points'] ?? 0 );
    $sanitized['redeemable_value']        = floatval( $input['redeemable_value'] ?? 0 );
    $sanitized['customer_registration']   = intval( $input['customer_registration'] ?? 0 );
    $sanitized['order_complete']          = intval( $input['order_complete'] ?? 0 );
    $sanitized['money_spent']             = intval( $input['money_spent'] ?? 0 );

    return $sanitized;
}

/**
 * Register and display the Manage Points settings
 *
 * @since  2.0.0
 * @return void
 */
function clwc_display_manage_points_table() {
    $manage_points_table = new CLWC_Customer_Loyalty_Table();
    $manage_points_table->prepare_items();
    $manage_points_table->display();
}

/**
 * Display the Log table.
 *
 * @since 2.0.0
 */
function clwc_display_log_table() {
    $log_table = new CLWC_Customer_Loyalty_Log_Table();
    $log_table->prepare_items();
    $log_table->display();
}