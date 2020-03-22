=== Customer Loyalty for WooCommerce ===
Contributors: deviodigital
Tags: loyalty, rewards, customer, woocommerce, gift
Requires at least: 3.0.1
Tested up to: 5.3.2
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Increase your customer loyalty by rewarding them for their repeat purchase behavior.

== Description ==

Use the Customer Loyalty for WooCommerce plugin to retain customers by rewarding them for their repeat purchase behavior.

## Rewards Card

CLWC has a Rewards Card setting that *"punches"* your customers virtual **Rewards Card** and creates a coupon code after a specific amount of *"punches"* have occured.

### Rewards Card Features

* Title, Text & Image customization
* Coupon amount
* Coupon type (flat rate, percentage)
* Coupon prefix

## Loyalty Points

CLWC also has settings for a Loyalty Points system, giving your customers points for completing a set of predefined tasks:

### Loyalty Points Features

*   Minimum points required to redeem
*   Set the coupon amount when points are redeemed
*   Set points for registration
*   Set points for order completion
*   Set points per dollar spent

## Customer retention

Using a plugin like Customer Loyalty for WooCommerce increases the likelihood of repeat business, which means you make more money while making happier customers!

== Installation ==

1. In your dashboard, go to `Plugins -> Add New`
2. Search for `Customer Loyalty for WooCommerce` and install this plugin
3. Go to `Settings -> Permalinks` and re-save the page
4. Pat yourself on the back for a job well done :)

== Screenshots ==

1. CLWC admin settings - Rewards Card
2. CLWC admin settings - Loyalty Points
3. CLWC Rewards Card - customer reward notice order details
4. CLWC customer dashboard - what the customer sees when viewing the Customer Loyalty page
5. CLWC user settings - Edit a user's rewards card and loyalty points

== Changelog ==

= 1.2.1 =
*   Added `clwc_redeem_points_coupon_args` filter in `admin/clwc-woocommerce-endpoint-shortcode.php`
*   Bugfix `wc_get_cart_url` function for redeem points redirect url in `admin/clwc-woocommerce-endpoint-shortcode.php`

= 1.2 =
*   Added `clwc_customer_coupons_args` filter in `admin/clwc-woocommerce-endpoint-shortcode.php`
*   Updated cart redirect URL to use `wc_get_cart_url` in `admin/clwc-woocommerce-endpoint-shortcode.php`
*   Updated cart redirect to not use `exit` which was causing `headers already sent` messages in `admin/clwc-woocommerce-endpoint-shortcode.php`
*   General code cleanup throughout multiple files in the plugin

= 1.1 =
*   Added calculation type setting for loyalty points in `admin/clwc-admin-settings.php`
*   Added calculation type helper function in `includes/clwc-helper-functions.php`
*   Updated money spent calculation to include the admin calculation type setting in `admin/clwc-earning-loyalty-points.php`
*   Updated `$woocommerce->show_messages` to `wc_print_notices` in `admin/clwc-woocommerce-endpoint-shortcode.php`
*   Updated text strings for localization in `languages/clwc.pot`
*   General code cleanup throughout multiple files in the plugin

= 1.0 =
*   Initial release
