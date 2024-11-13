<?php
/**
 * The Customer Loyalty Dashboard Shortcode.
 *
 * @package    CLWC
 * @subpackage CLWC/admin
 * @since      1.0.0
 */
function clwc_dashboard_shortcode() {
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();

        // Get user loyalty points.
        $loyalty_points = get_user_meta( $user_id, 'clwc_loyalty_points', true ) ?: 0;
        $redeem_points_min = clwc_loyalty_points_redeem_points_minimum();

        echo '<h4 class="clwc-loyalty-points">' . esc_attr__( 'My Loyalty Points', 'customer-loyalty-for-woocommerce' ) . '</h4>';
        echo '<table class="clwc-dashboard"><tbody>';
        echo '<tr><td><strong>' . esc_attr__('Loyalty Points', 'customer-loyalty-for-woocommerce') . '</strong></td><td class="clwc-loyalty-points-total">' . esc_attr( $loyalty_points ) . '</td></tr>';
        if ( $redeem_points_min && $loyalty_points >= $redeem_points_min ) {
            echo '<tr><td><strong>' . esc_attr__( 'Redeem Points', 'customer-loyalty-for-woocommerce' ) . '</strong></td><td>';
            echo '<button id="clwc-redeem-points" class="button clwc-button">' . esc_attr__( 'Redeem', 'customer-loyalty-for-woocommerce' ) . '</button>';
            echo '</td></tr>';
        }
        echo '</tbody></table>';

        // Display existing coupons
        echo '<h4 class="clwc-rewards-coupons">' . esc_attr__( 'My Coupons', 'customer-loyalty-for-woocommerce' ) . '</h4>';
        echo '<table class="clwc-dashboard rewards-coupons" id="clwc-coupons-table"><tbody>';
        echo clwc_get_user_coupons_html( $user_id );
        echo '</tbody></table>';
    } else {
        echo wp_login_form();
    }
}
add_shortcode( 'clwc_dashboard', 'clwc_dashboard_shortcode' );

/**
 * Helper function to retrieve and render user coupons as HTML rows.
 *
 * @param int $user_id User ID.
 * @return string HTML of coupon rows.
 */
function clwc_get_user_coupons_html( $user_id ) {
    $args = [
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_type'      => 'shop_coupon',
        'post_status'    => 'publish',
        'author'         => $user_id,
    ];

    $customer_coupons = get_posts( $args );
    $output = '';

    foreach ( $customer_coupons as $customer_coupon ) {
        $coupon = new WC_Coupon( $customer_coupon->post_name );
        $amount = wc_price( $coupon->get_amount() );
        $usage_left = $coupon->get_usage_limit() - $coupon->get_usage_count();
        $is_coupon_active = ( $usage_left > 0 ) ? '<span class="clwc-available-coupon">Available</span>' : '';
        $output .= '<tr><td><strong>' . $customer_coupon->post_title . '</strong> - ' . $amount . '</td><td>' . $is_coupon_active . '</td></tr>';
    }

    if ( empty( $output ) ) {
        $output = '<tr><td class="clwc-no-coupons">' . esc_attr__( 'You do not have any coupons available', 'customer-loyalty-for-woocommerce' ) . '</td></tr>';
    }

    return $output;
}
add_shortcode( 'clwc_dashboard', 'clwc_dashboard_shortcode' );
