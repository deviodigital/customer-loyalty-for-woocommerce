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
if ( ! defined( 'CLWC_NAME' ) ) {
	define( 'CLWC_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'CLWC_DIR' ) ) {
	define( 'CLWC_DIR', WP_PLUGIN_DIR . '/' . CLWC_NAME );
}

if ( ! defined( 'CLWC_URL' ) ) {
	define( 'CLWC_URL', WP_PLUGIN_URL . '/' . CLWC_NAME );
}

/**
 * Actions/Filters
 *
 * Related to all settings API.
 *
 * @since  1.0.0
 */
if ( class_exists( 'CLWC_OSA' ) ) {
	/**
	 * Object Instantiation.
	 *
	 * Object for the class `CLWC_OSA`.
	 */
	$clwc_obj = new CLWC_OSA();

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
				'button_label' => 'Choose Image',
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
				'fixed_cart' => 'Fixed cart',
				'percent'    => 'Percentage',
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

    // Field: Title.
	$clwc_obj->add_field(
		'clwc_loyalty_points',
		array(
			'id'   => 'clwc_loyalty_points_redeem_points_settings_title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Redeeming Points', 'clwc' ) . '</h1>',
		)
	);

    // Field: Rewards Card - Redeem points.
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

    // Field: Rewards Card - Redeem points value.
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














    // Field: Text.
	$clwc_obj->add_field(
		'clwc_basic',
		array(
			'id'      => 'text',
			'type'    => 'text',
			'name'    => __( 'Text Input', 'clwc' ),
			'desc'    => __( 'Text input description', 'clwc' ),
			'default' => 'Default Text',
		)
	);

    // Field: Number.
	$clwc_obj->add_field(
		'clwc_basic',
		array(
			'id'                => 'text_no',
			'type'              => 'number',
			'name'              => __( 'Number Input', 'clwc' ),
			'desc'              => __( 'Number field with validation callback `intval`', 'clwc' ),
			'default'           => 1,
			'sanitize_callback' => 'intval',
		)
	);

	// Field: Textarea.
	$clwc_obj->add_field(
		'clwc_basic',
		array(
			'id'   => 'textarea',
			'type' => 'textarea',
			'name' => __( 'Textarea Input', 'clwc' ),
			'desc' => __( 'Textarea description', 'clwc' ),
		)
	);

    // Field: Separator.
	$clwc_obj->add_field(
		'clwc_basic',
		array(
			'id'   => 'separator',
			'type' => 'separator',
		)
	);

    // Field: Title.
	$clwc_obj->add_field(
		'clwc_basic',
		array(
			'id'   => 'title',
			'type' => 'title',
			'name' => '<h1>' . __( 'Title', 'clwc' ) . '</h1>',
		)
	);
	// Field: Checkbox.
	$clwc_obj->add_field(
		'clwc_basic',
		array(
			'id'   => 'checkbox',
			'type' => 'checkbox',
			'name' => __( 'Checkbox', 'clwc' ),
			'desc' => __( 'Checkbox Label', 'clwc' ),
		)
	);

    // Field: Radio.
	$clwc_obj->add_field(
		'clwc_basic',
		array(
			'id'      => 'radio',
			'type'    => 'radio',
			'name'    => __( 'Radio', 'clwc' ),
			'desc'    => __( 'Radio Button', 'clwc' ),
			'options' => array(
				'yes' => 'Yes',
				'no'  => 'No',
			),
		)
	);

    // Field: Multicheck.
	$clwc_obj->add_field(
		'clwc_basic',
		array(
			'id'      => 'multicheck',
			'type'    => 'multicheck',
			'name'    => __( 'Multile checkbox', 'clwc' ),
			'desc'    => __( 'Multile checkbox description', 'clwc' ),
			'options' => array(
				'yes' => 'Yes',
				'no'  => 'No',
			),
		)
	);

    // Field: Select.
	$clwc_obj->add_field(
		'clwc_basic',
		array(
			'id'      => 'select',
			'type'    => 'select',
			'name'    => __( 'A Dropdown', 'clwc' ),
			'desc'    => __( 'A Dropdown description', 'clwc' ),
			'options' => array(
				'yes' => 'Yes',
				'no'  => 'No',
			),
		)
	);

    // Field: Image.
	$clwc_obj->add_field(
		'clwc_reward_points',
		array(
			'id'      => 'image',
			'type'    => 'image',
			'name'    => __( 'Image', 'clwc' ),
			'desc'    => __( 'Image description', 'clwc' ),
			'options' => array(
				'button_label' => 'Choose Image',
			),
		)
	);

    // Field: File.
	$clwc_obj->add_field(
		'clwc_reward_points',
		array(
			'id'      => 'file',
			'type'    => 'file',
			'name'    => __( 'File', 'clwc' ),
			'desc'    => __( 'File description', 'clwc' ),
			'options' => array(
				'button_label' => 'Choose file',
			),
		)
	);

    // Field: Color.
	$clwc_obj->add_field(
		'clwc_reward_points',
		array(
			'id'          => 'color',
			'type'        => 'color',
			'name'        => __( 'Color', 'clwc' ),
			'desc'        => __( 'Color description', 'clwc' ),
			'placeholder' => __( '#5F4B8B', 'clwc' ),
		)
	);
	// Field: WYSIWYG.
	$clwc_obj->add_field(
		'clwc_reward_points',
		array(
			'id'   => 'wysiwyg',
			'type' => 'wysiwyg',
			'name' => __( 'WP_Editor', 'clwc' ),
			'desc' => __( 'WP_Editor description', 'clwc' ),
		)
	);
}