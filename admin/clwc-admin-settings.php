<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Define global constants.
 *
 * @since 1.0.0
 */

 // Plugin version.
if ( ! defined( 'CUSTOMER_LOYALTY_NAME' ) ) {
	define( 'CUSTOMER_LOYALTY_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'CUSTOMER_LOYALTY_DIR' ) ) {
	define( 'CUSTOMER_LOYALTY_DIR', WP_PLUGIN_DIR . '/' . CUSTOMER_LOYALTY_NAME );
}

if ( ! defined( 'CUSTOMER_LOYALTY_URL' ) ) {
	define( 'CUSTOMER_LOYALTY_URL', WP_PLUGIN_URL . '/' . CUSTOMER_LOYALTY_NAME );
}

/**
 * Actions/Filters
 *
 * Related to all settings API.
 *
 * @since  1.0.0
 */
if ( class_exists( 'Customer_Loyalty_OSA' ) ) {
	/**
	 * Object Instantiation.
	 *
	 * Object for the class `Customer_Loyalty_OSA`.
	 */
	$clwc_obj = new Customer_Loyalty_OSA();

    // Section: Rewards Card.
	$clwc_obj->add_section(
		array(
			'id'    => 'clwc_rewards_card',
			'title' => __( 'Rewards Card', 'customer-loyalty-for-woocommerce' ),
		)
    );

    // Section: Loyalty Points.
	$clwc_obj->add_section(
		array(
			'id'    => 'clwc_loyalty_points',
			'title' => __( 'Loyalty Points', 'customer-loyalty-for-woocommerce' ),
		)
    );

    // Field: Title.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'   => 'clwc_reward_settings_title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Rewards Card', 'customer-loyalty-for-woocommerce' ) . '</h1>',
		)
	);

	// Field: Checkbox.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'   => 'clwc_rewards_card_activate',
			'type' => 'checkbox',
			'name' => __( 'Activate Rewards Card', 'customer-loyalty-for-woocommerce' ),
			'desc' => __( 'Check to activate the included customer rewards card features.', 'customer-loyalty-for-woocommerce' ),
		)
	);

    // Field: Text.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'      => 'clwc_rewards_card_title',
			'type'    => 'text',
			'name'    => __( 'Rewards Card Title', 'customer-loyalty-for-woocommerce' ),
			'desc'    => __( 'The title displayed in a customer\'s order when a new reward is earned.', 'customer-loyalty-for-woocommerce' ),
			'default' => 'You earned a reward',
		)
	);

	// Field: Textarea.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'      => 'clwc_rewards_card_text',
			'type'    => 'textarea',
			'name'    => __( 'Rewards Card Text', 'customer-loyalty-for-woocommerce' ),
            'desc'    => __( 'The text displayed in a customer\'s order when a new reward is earned.', 'customer-loyalty-for-woocommerce' ),
            'default' => '',
		)
	);

    // Field: Image.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'      => 'clwc_rewards_card_image',
			'type'    => 'image',
			'name'    => __( 'Rewards Card Image', 'customer-loyalty-for-woocommerce' ),
			'desc'    => __( 'Display an image in the customer\'s order when a new reward is earned.', 'customer-loyalty-for-woocommerce' ),
			'options' => array(
				'button_label' => __( 'Choose Image', 'customer-loyalty-for-woocommerce' ),
			),
		)
	);

    // Field: Title.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'   => 'clwc_rewards_card_coupon_settings_title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Rewards coupon', 'customer-loyalty-for-woocommerce' ) . '</h1>',
		)
    );

    // Field: Rewards Card - Required punches.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'                => 'clwc_rewards_card_required_punches',
			'type'              => 'number',
			'name'              => __( 'Required punches', 'customer-loyalty-for-woocommerce' ),
			'desc'              => __( 'How many punches are required before a coupon is created for the customer?', 'customer-loyalty-for-woocommerce' ),
			'default'           => 10,
			'sanitize_callback' => 'intval',
		)
	);

    // Field: Rewards Card - Coupon amount.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'                => 'clwc_rewards_card_coupon_amount',
			'type'              => 'number',
			'name'              => __( 'Coupon amount', 'customer-loyalty-for-woocommerce' ),
			'desc'              => __( 'Enter the amount you would like used when creating the coupon.', 'customer-loyalty-for-woocommerce' ),
			'default'           => 0,
			'sanitize_callback' => 'intval',
		)
	);

    // Field: Rewards Card - Coupon type.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'      => 'clwc_rewards_card_coupon_type',
			'type'    => 'select',
			'name'    => __( 'Coupon type', 'customer-loyalty-for-woocommerce' ),
			'desc'    => __( 'Select the type of coupon that you would like created for the customer', 'customer-loyalty-for-woocommerce' ),
			'options' => array(
				'fixed_cart' => __( 'Fixed cart', 'customer-loyalty-for-woocommerce' ),
				'percent'    => __( 'Percentage', 'customer-loyalty-for-woocommerce' ),
			),
		)
	);

    // Field: Rewards Card - Coupon prefix.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'      => 'clwc_rewards_card_coupon_prefix',
			'type'    => 'text',
			'name'    => __( 'Coupon prefix', 'customer-loyalty-for-woocommerce' ),
			'desc'    => __( 'Add the text you would like included before the randomize coupon code', 'customer-loyalty-for-woocommerce' ),
			'default' => 'CLWC',
		)
	);

    // Field: Title - Loyalty Points.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'   => 'clwc_loyalty_points_settings_title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Loyalty Points', 'customer-loyalty-for-woocommerce' ) . '</h1>',
		)
	);

	// Field: Checkbox.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'   => 'clwc_loyalty_points_activate',
			'type' => 'checkbox',
			'name' => __( 'Activate Loyalty Points', 'customer-loyalty-for-woocommerce' ),
			'desc' => __( 'Check to activate the included customer loyalty points features.', 'customer-loyalty-for-woocommerce' ),
		)
	);

    // Field: Loyalty Points - Calculation type.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'      => 'clwc_loyalty_points_redeem_points_calculation_type',
			'type'    => 'select',
			'name'    => __( 'Calculation type', 'customer-loyalty-for-woocommerce' ),
			'desc'    => __( 'Should the points be calculated from the order total or subtotal?', 'customer-loyalty-for-woocommerce' ),
			'options' => array(
				'total'   => __( 'Order total', 'customer-loyalty-for-woocommerce' ),
				'subotal' => __( 'Order subtotal', 'customer-loyalty-for-woocommerce' ),
			),
		)
	);

    // Field: Title.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'   => 'clwc_loyalty_points_redeem_points_settings_title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Redeeming Points', 'customer-loyalty-for-woocommerce' ) . '</h1>',
		)
	);

    // Field: Loyalty Points - Redeem points.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'                => 'clwc_loyalty_points_redeem_points_minimum',
			'type'              => 'number',
			'name'              => __( 'Minimum points', 'customer-loyalty-for-woocommerce' ),
			'desc'              => __( 'How many points are required before a customer can redeem points?', 'customer-loyalty-for-woocommerce' ),
			'default'           => 10,
			'sanitize_callback' => 'intval',
		)
	);

    // Field: Loyalty Points - Redeem points value.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'                => 'clwc_loyalty_points_redeem_points_value',
			'type'              => 'number',
			'name'              => __( 'Redeemable value', 'customer-loyalty-for-woocommerce' ),
			'desc'              => __( 'How much should the redeemed points be worth in actual currency?', 'customer-loyalty-for-woocommerce' ),
			'default'           => 10,
			'sanitize_callback' => 'intval',
		)
	);

    // Field: Title.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'   => 'clwc_earning_points_settings_title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Earning Points', 'customer-loyalty-for-woocommerce' ) . '</h1>',
		)
	);

    // Field: Earning Points - Customer registration.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'                => 'clwc_earning_points_customer_registration',
			'type'              => 'number',
			'name'              => __( 'Customer registration', 'customer-loyalty-for-woocommerce' ),
			'desc'              => __( 'The amount of points a customer earns when they register an account.', 'customer-loyalty-for-woocommerce' ),
			'default'           => 0,
			'sanitize_callback' => 'intval',
		)
	);

    // Field: Earning Points - Order Complete.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'                => 'clwc_earning_points_order_complete',
			'type'              => 'number',
			'name'              => __( 'Order complete', 'customer-loyalty-for-woocommerce' ),
			'desc'              => __( 'The amount of points a customer earns when completing an order.', 'customer-loyalty-for-woocommerce' ),
			'default'           => 0,
			'sanitize_callback' => 'intval',
		)
	);

    // Field: Earning Points - Money spent.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'                => 'clwc_earning_points_money_spent',
			'type'              => 'number',
			'name'              => __( 'Money spent', 'customer-loyalty-for-woocommerce' ),
			'desc'              => __( 'The amount of points a customer earns per dollar spent.', 'customer-loyalty-for-woocommerce' ),
			'default'           => 1,
			'sanitize_callback' => 'intval',
		)
	);
}
