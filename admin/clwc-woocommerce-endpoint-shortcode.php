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

    global $woocommerce;

    // Set empty vars.
    $clwc_order_ids = '';
    $coupon_codes   = '';

	// Check if user is logged in.
	if ( is_user_logged_in() ) {
		// Get the user ID.
		$user_id = get_current_user_id();

		// Get the user object.
        $user_meta = get_userdata( $user_id );

        // Get loyalty points.
        $loyalty_points = get_user_meta( $user_id, 'clwc_loyalty_points', TRUE );

        // Set to zero if customer has no points.
        if ( ! $loyalty_points ) {
            $loyalty_points = 0;
        }

        // Redeemable points minimum.
        $redeem_points_min = clwc_loyalty_points_redeem_points_minimum();

        // Set redeem points variable if availabe.
        if ( $redeem_points_min && $loyalty_points >= $redeem_points_min ) {
            // Create new coupon when user redeem's loyalty points.
            if ( isset( $_POST['clwc_redeem_points'] ) ) {
                $coupon_code   = clwc_get_random_string(); // Code.
                $amount        = clwc_loyalty_points_redeem_points_value(); // Amount.

                $coupon = array(
                    'post_title'   => $coupon_code,
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_author'  => $user_id,
                    'post_type'    => 'shop_coupon'
                );

                // Get newly create coupon's ID #
                $new_coupon_id = wp_insert_post( $coupon );

                // Add custom meta data to the newly created coupon.
                update_post_meta( $new_coupon_id, 'discount_type', 'fixed_cart' );
                update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
                update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
                update_post_meta( $new_coupon_id, 'product_ids', '' );
                update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
                update_post_meta( $new_coupon_id, 'usage_limit', '1' );
                update_post_meta( $new_coupon_id, 'expiry_date', '' );
                update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
                update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

                // Reduce required points from user's loyalty points.
                $new_loyalty_points = $loyalty_points - $redeem_points_min;

                // Update user meta with the updated loyalty points amount.
                update_user_meta( $user_id, 'clwc_loyalty_points', $new_loyalty_points, $loyalty_points );

                // Apply new coupon to the cart automatically.
                if ( ! $woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ) ) ) {
                    wc_print_notices();
                }

                // Redirect to cart when discount applied.
                wp_redirect( apply_filters( 'clwc_redeem_points_redirect_url', $woocommerce->cart->wc_get_cart_url() ) );
                //exit;
            }

            // Redeem loyalty points.
            $redeem_button = '<form class="clwc-redeem-points" name="clwc_redeem_loyalty_points" method="post">
			<input type="submit" class="button clwc-button" name="clwc_redeem_points" value="' . __( 'Redeem', 'clwc' ) . '" />'
			. wp_nonce_field( 'clwc-redeem-points' ) . 
			'</form>';

            // Redeem loyalty points.
            $redeem_points = '<tr><td><strong>' . __( 'Redeem Points', 'ddwc' ) . '</strong></td><td>' . $redeem_button . '</td></tr>';
        } else {
            $redeem_points = '';
        }

        // Coupons args.
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'asc',
            'post_type'        => 'shop_coupon',
            'post_status'      => 'publish',
        );

        // Filter the coupons args.
        $args = apply_filters( 'clwc_customer_coupons_args', $args );

        // Get all coupons.
        $customer_coupons = get_posts( $args );

        // Loop through coupons.
        foreach ( $customer_coupons as $customer_coupon ) {
            if ( $user_id == $customer_coupon->post_author ) {

                // Get coupon object.
                $coupon = new WC_Coupon( $customer_coupon->post_name );

                // Get coupon data.
                $coupon_data = array(
                    'id'          => $customer_coupon->ID,
                    'usage_limit' => ( ! empty( $customer_coupon->usage_limit ) ) ? $customer_coupon->usage_limit : null,
                    'usage_count' => (int) $customer_coupon->usage_count,
                    'amount'      => wc_format_decimal( $coupon->get_amount(), 2 ),
                );

                // How many uses are left for this coupon?
                $usage_left = $coupon_data['usage_limit'] - $coupon_data['usage_count'];

                // Set is_coupon_active var.
                if ( $usage_left > 0 ) {
                    $is_coupon_active = '<span class="clwc-available-coupon">' . __( 'Available', 'clwc' ) . '</span>';
                    $coupon_class     = '';
                } 
                else {
                    $is_coupon_active = '';
                    $coupon_class     = ' class="clwc-inactive-coupon" ';
                }

                $coupon_codes .= '<tr><td ' . $coupon_class . '><strong>' . $customer_coupon->post_title . '</strong> - ' . wc_price( $coupon_data['amount'] ) . '</td><td>' . $is_coupon_active . '</td></tr>';
            }
        }

        // Display lotalty points if activated in the admin settings.
        if ( 'on' == clwc_loyalty_points_activate() ) {
            // Table loyalty points.
            echo '<h4 class="clwc-loyalty-points">' . __( 'My Loyalty Points', 'clwc' ) . '</h4>';

            do_action( 'clwc_customer_dashboard_loyalty_points_table_before' );

            echo '<table class="clwc-dashboard">';
            echo '<tbody>';

            do_action( 'clwc_customer_dashboard_loyalty_points_table_tbody_top' );

            echo '<tr><td><strong>' . __( 'Loyalty Points', 'ddwc' ) . '</strong></td><td>' . $loyalty_points . '</td></tr>';
            echo $redeem_points;

            do_action( 'clwc_customer_dashboard_loyalty_points_table_tbody_bottom' );

            echo '</tbody>';
            echo '</table>';

            do_action( 'clwc_customer_dashboard_loyalty_points_table_after' );

        }

        // Get all customer orders.
        $customer_orders = get_posts( array(
            'numberposts' => -1,
            'meta_key'    => '_customer_user',
            'meta_value'  => $user_id,
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys( wc_get_order_statuses() ),
        ) );

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
                    $is_coupon_active = '<span class="clwc-available-coupon">' . __( 'Available', 'clwc' ) . '</span>';
                    $coupon_class     = '';
                } else {
                    $is_coupon_active = '';
                    $coupon_class     = ' class="clwc-inactive-coupon" ';
                }

                $coupon_codes .= '<tr><td ' . $coupon_class . '><strong>' . $coupon_added . '</strong> - ' . wc_price( $coupon_data['amount'] ) . '</td><td>' . $is_coupon_active . '</td></tr>';
            }
        }

        // Set message when no coupons are available.
        if ( '' == $coupon_codes ) {
            $coupon_codes .= '<tr><td class="clwc-no-coupons">' . apply_filters( 'clwc_no_coupons_message', __( 'You do not have any coupons available', 'clwc' ) ) . '</td></tr>';
        }

        // Get rewards card punches.
        $rewards_card_punches = get_user_meta( $user_id, 'clwc_rewards_card_punches', TRUE );

        // Set to zero if customer has no punches.
        if ( ! $rewards_card_punches ) {
            $rewards_card_punches = 0;
        }

        // Get rewards earned.
        $rewards_earned = get_user_meta( $user_id, 'clwc_rewards_earned', TRUE );

        // Set to zero if customer has no earned rewards.
        if ( ! $rewards_earned ) {
            $rewards_earned = 0;
        }

        // Display rewards card if it's activated in admin settings.
        if ( 'on' == clwc_rewards_card_activate() ) {
            // Table rewards card.
            echo '<h4 class="clwc-rewards-card">' . __( 'My Rewards Card', 'clwc' ) . '</h4>';

            do_action( 'clwc_customer_dashboard_rewards_card_table_before' );

            echo '<table class="clwc-dashboard rewards-card">';
            echo '<tbody>';

            do_action( 'clwc_customer_dashboard_rewards_card_table_tbody_top' );

            echo '<tr><td><strong>' . __( 'Rewards Card Punches', 'ddwc' ) . '</strong></td><td>' . $rewards_card_punches . '</td></tr>';
            echo '<tr><td><strong>' . __( 'Rewards Earned', 'ddwc' ) . '</strong></td><td>' . $rewards_earned . '</td></tr>';

            do_action( 'clwc_customer_dashboard_rewards_card_table_tbody_bottom' );

            echo '</tbody>';
            echo '</table>';

            do_action( 'clwc_customer_dashboard_rewards_card_table_after' );

        }

        // Display coupons if rewards card or loyalty points are active.
        if ( 'on' == clwc_rewards_card_activate() || 'on' == clwc_loyalty_points_activate() ) {
            // My coupons.
            echo '<h4 class="clwc-rewards-coupons">' . __( 'My Coupons', 'clwc' ) . '</h4>';

            do_action( 'clwc_customer_dashboard_coupons_table_before' );

            echo '<table class="clwc-dashboard rewards-coupons">';
            echo '<tbody>';

            do_action( 'clwc_customer_dashboard_coupons_table_tbody_top' );

            echo $coupon_codes;

            do_action( 'clwc_customer_dashboard_coupons_table_tbody_bottom' );

            echo '</tbody>';
            echo '</table>';

            do_action( 'clwc_customer_dashboard_coupons_table_after' );

        }

    } else {
        // Display login form.
        apply_filters( 'clwc_customer_dashboard_login_form', wp_login_form() );
    }
}
add_shortcode( 'clwc_dashboard', 'clwc_dashboard_shortcode' );
