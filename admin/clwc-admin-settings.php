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
			'title' => __( 'Rewards Card', 'clwc' ),
		)
    );

    // Section: Loyalty Points.
	$clwc_obj->add_section(
		array(
			'id'    => 'clwc_loyalty_points',
			'title' => __( 'Loyalty Points', 'clwc' ),
		)
    );

    // Field: Title.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'   => 'clwc_reward_settings_title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Rewards Card', 'clwc' ) . '</h1>',
		)
	);

	// Field: Checkbox.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'   => 'clwc_rewards_card_activate',
			'type' => 'checkbox',
			'name' => __( 'Activate Rewards Card', 'clwc' ),
			'desc' => __( 'Check to active the included customer rewards card features.', 'clwc' ),
		)
	);

    // Field: Text.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'      => 'clwc_rewards_card_title',
			'type'    => 'text',
			'name'    => __( 'Rewards Card Title', 'clwc' ),
			'desc'    => __( 'The title displayed in a customer\'s order when a new reward is earned.', 'clwc' ),
			'default' => 'You earned a reward',
		)
	);

	// Field: Textarea.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'      => 'clwc_rewards_card_text',
			'type'    => 'textarea',
			'name'    => __( 'Rewards Card Text', 'clwc' ),
            'desc'    => __( 'The text displayed in a customer\'s order when a new reward is earned.', 'clwc' ),
            'default' => '',
		)
	);

    // Field: Image.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'      => 'clwc_rewards_card_image',
			'type'    => 'image',
			'name'    => __( 'Rewards Card Image', 'clwc' ),
			'desc'    => __( 'Display an image in the customer\'s order when a new reward is earned.', 'clwc' ),
			'options' => array(
				'button_label' => __( 'Choose Image', 'clwc' ),
			),
		)
	);

    // Field: Title.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'   => 'clwc_rewards_card_coupon_settings_title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Rewards coupon', 'clwc' ) . '</h1>',
		)
    );

    // Field: Rewards Card - Required punches.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'                => 'clwc_rewards_card_required_punches',
			'type'              => 'number',
			'name'              => __( 'Required punches', 'clwc' ),
			'desc'              => __( 'How many punches are required before a coupon is created for the customer?', 'clwc' ),
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
			'name'              => __( 'Coupon amount', 'clwc' ),
			'desc'              => __( 'Enter the amount you would like used when creating the coupon.', 'clwc' ),
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
			'name'    => __( 'Coupon type', 'clwc' ),
			'desc'    => __( 'Select the type of coupon that you would like created for the customer', 'clwc' ),
			'options' => array(
				'fixed_cart' => __( 'Fixed cart', 'clwc' ),
				'percent'    => __( 'Percentage', 'clwc' ),
			),
		)
	);

    // Field: Rewards Card - Coupon prefix.
	$clwc_obj->add_field(
		'clwc_rewards_card',
		array(
			'id'      => 'clwc_rewards_card_coupon_prefix',
			'type'    => 'text',
			'name'    => __( 'Coupon prefix', 'clwc' ),
			'desc'    => __( 'Add the text you would like included before the randomize coupon code', 'clwc' ),
			'default' => 'CLWC',
		)
	);

    // Field: Title - Loyalty Points.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'   => 'clwc_loyalty_points_settings_title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Loyalty Points', 'clwc' ) . '</h1>',
		)
	);

	// Field: Checkbox.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'   => 'clwc_loyalty_points_activate',
			'type' => 'checkbox',
			'name' => __( 'Activate Loyalty Points', 'clwc' ),
			'desc' => __( 'Check to active the included customer loyalty points features.', 'clwc' ),
		)
	);

    // Field: Loyalty Points - Calculation type.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'      => 'clwc_loyalty_points_redeem_points_calculation_type',
			'type'    => 'select',
			'name'    => __( 'Calculation type', 'clwc' ),
			'desc'    => __( 'Should the points be calculated from the order total or subtotal?', 'clwc' ),
			'options' => array(
				'total'   => __( 'Order total', 'clwc' ),
				'subotal' => __( 'Order subtotal', 'clwc' ),
			),
		)
	);

    // Field: Title.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'   => 'clwc_loyalty_points_redeem_points_settings_title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Redeeming Points', 'clwc' ) . '</h1>',
		)
	);

    // Field: Loyalty Points - Redeem points.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'                => 'clwc_loyalty_points_redeem_points_minimum',
			'type'              => 'number',
			'name'              => __( 'Minimum points', 'clwc' ),
			'desc'              => __( 'How many points are required before a customer can redeem points?', 'clwc' ),
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
			'name'              => __( 'Redeemable value', 'clwc' ),
			'desc'              => __( 'How much should the redeemed points be worth in actual currency?', 'clwc' ),
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
			'name' => '<h1>' . __( 'Earning Points', 'clwc' ) . '</h1>',
		)
	);

    // Field: Earning Points - Customer registration.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'                => 'clwc_earning_points_customer_registration',
			'type'              => 'number',
			'name'              => __( 'Customer registration', 'clwc' ),
			'desc'              => __( 'The amount of points a customer earns when they register an account.', 'clwc' ),
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
			'name'              => __( 'Order complete', 'clwc' ),
			'desc'              => __( 'The amount of points a customer earns when completing an order.', 'clwc' ),
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
			'name'              => __( 'Money spent', 'clwc' ),
			'desc'              => __( 'The amount of points a customer earns per dollar spent.', 'clwc' ),
			'default'           => 1,
			'sanitize_callback' => 'intval',
		)
	);
}
