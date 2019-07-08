<?php
/**
 * The Customer Loyalty Dashboard Shortcode.
 *
 * @link       https://www.deviodigital.com
 * @since      1.0.
 *
 * @package    CLWC
 * @subpackage CLWC/admin
 */
function clwc_dashboard_shortcode() {

	// Check if user is logged in.
	if ( is_user_logged_in() ) {
		// Get the user ID.
		$user_id = get_current_user_id();

		// Get the user object.
        $user_meta = get_userdata( $user_id );

        // Get loyalty points.
        $loyalty_points = get_user_meta( $user_id, 'clwc_loyalty_points', TRUE );

        // Redeemable points minimum.
        $redeem_points_min = clwc_loyalty_points_redeem_points_minimum();

        // Set redeem points variable if availabe.
        if ( $loyalty_points >= $redeem_points_min ) {
            // Redeem loyalty points.
            $redeem_button = '<a href="#" class="button clwc-button">' . __( 'Redeem', 'clwc' ) . '</a>';
            // Redeem loyalty points.
            $redeem_points = '<tr><td><strong>' . __( 'Redeem Points', 'ddwc' ) . '</strong></td><td>' . $redeem_button . '</td></tr>';
        } else {
            $redeem_points = '';
        }

        // Table loyalty points.
        echo '<h4 class="clwc-loyalty-points">' . __( 'My Loyalty Points', 'clwc' ) . '</h4>';
        echo '<table class="clwc-dashboard">';
        echo '<tbody>';
        echo '<tr><td><strong>' . __( 'Loyalty Points', 'ddwc' ) . '</strong></td><td>' . $loyalty_points . '</td></tr>';
        echo $redeem_points;
        echo '</tbody>';
        echo '</table>';

        // Get all customer orders.
        $customer_orders = get_posts( array(
            'numberposts' => -1,
            'meta_key'    => '_customer_user',
            'meta_value'  => $user_id,
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys( wc_get_order_statuses() ),
        ) );

        /*
        echo '<pre>';
        var_dump( $customer_orders );
        echo '</pre>';
        */

        // Set empty vars.
        $clwc_order_ids = '';
        $coupon_codes   = '';

        /**
         * Add coupon codes to CLWC Dashboard
         * 
         * Checks to see if there's coupons created and added to a customer's order, then it
         * also checks to see if the coupon is active or not, and displays these details to the
         * customer in the Rewards Card table.
         */
        foreach ( $customer_orders as $clwc_order ) {
            // Get CLWC customer coupon code - if any.
            $coupon_added = get_post_meta( $clwc_order->ID, 'clwc_customer_coupon_code', TRUE );

            // Add coupon code to output if it's active.
            if ( $coupon_added ) {

                // Get coupon data.
                $coupon = new WC_Coupon( $coupon_added );
                $coupon_post = get_post( $coupon->get_id() );
                $coupon_data = array(
                    'id'          => $coupon->get_id(),
                    'usage_limit' => ( ! empty( $coupon->get_usage_limit() ) ) ? $coupon->get_usage_limit() : null,
                    'usage_count' => (int) $coupon->get_usage_count(),
                    'amount'      => wc_format_decimal( $coupon->get_amount(), 2 ),
                );
                
                // How many uses are left for this coupon?
                $usage_left = $coupon_data['usage_limit'] - $coupon_data['usage_count'];

                // Set is_coupon_active var.
                if ( $usage_left > 0 ) {
                    $is_coupon_active = '<i>Active</i>';
                } 
                else {
                    $is_coupon_active = '<i>Inactive</i>';
                }

                $coupon_codes .= '<tr><td><strong>' . $coupon_added . '</strong> - ' . wc_price( $coupon_data['amount'] ) . '</td><td>' . $is_coupon_active . '</td></tr>';
            }
        }

        // Get rewards card punches.
        $rewards_card_punches = get_user_meta( $user_id, 'clwc_rewards_card_punches', TRUE );

        // Get rewards earned.
        $rewards_earned = get_user_meta( $user_id, 'clwc_rewards_earned', TRUE );

        // Table rewards card.
        echo '<h4 class="clwc-loyalty-points">' . __( 'My Rewards Card', 'clwc' ) . '</h4>';
        echo '<table class="clwc-dashboard">';
        echo '<tbody>';
        echo '<tr><td><strong>' . __( 'Rewards Card Punches', 'ddwc' ) . '</strong></td><td>' . $rewards_card_punches . '</td></tr>';
        echo '<tr><td><strong>' . __( 'Rewards Earned', 'ddwc' ) . '</strong></td><td>' . $rewards_earned . '</td></tr>';
        echo '</tbody>';
        echo '</table>';

        // Rewards earned coupons.
        echo '<h4 class="clwc-loyalty-points">' . __( 'Rewards Coupons', 'clwc' ) . '</h4>';
        echo '<table class="clwc-dashboard">';
        echo '<tbody>';
        echo $coupon_codes;
        echo '</tbody>';
        echo '</table>';

    } else {
        // Display login form.
        wp_login_form();
    }
}
add_shortcode( 'clwc_dashboard', 'clwc_dashboard_shortcode' );
