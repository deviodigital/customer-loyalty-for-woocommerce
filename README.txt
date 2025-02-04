=== Loyalty & Rewards for WooCommerce ===
Contributors: deviodigital
Tags: loyalty, rewards, customer, woocommerce, gift
Requires at least: 3.0.1
Tested up to: 6.7.0
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Increase your customer loyalty by rewarding them for their repeat purchase behavior.

== Description ==

Use the [Customer Loyalty for WooCommerce](https://www.deviodigital.com/customer-loyalty-for-woocommerce-release-notes/) plugin to retain customers by rewarding them for their repeat purchase behavior.

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

= 2.0.2 =

* [🐛 BUG: Fixed is_wc_endpoint_url causing a fatal error when not within a WooCommerce context](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/44966ec6ceff814929a0d72b324a0450911b59ae)

= 2.0.1 =

* [📦 NEW: Added plugin usage restrictions for websites hosted on wordpress.com](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/3bbb277ce7d1eed782f50f7cfefefe193092ada8)
* [👌 IMPROVE: General code cleanup](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/4c8fe2b53eb3466f6a5fec6043f6d97ec8af92f8)
* [👌 IMPROVE: General code cleanup](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/42bc1b8d1a112051a84602a421aa79d1307e01ea)
* [👌 IMPROVE: Updated the WPCom Check composer script to restrict plugin usage on wordpress.com](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/363199a2746fa9c9f9096225ca5f14c00855b546)
* [👌 IMPROVE: Updated language translations files](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/f78fec7c3d7163cfcba019513869d1069caf44ea)

= 2.0.0 =
* [👌 IMPROVE: Updated plugin serve updates from GitHub instead of wp.org](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/5c222fabc637e0aa27658ee77ae821e516bf72cf)
* [📖 DOC: Updated README.md](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/7ea10b24eb041de93f8345b8406c33ea2e43bb15)
* [👌 IMPROVE: Updated logging functionality](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/102f8b64aa798d20f29740ee38ea3a04d85a547e)
* [👌 IMPROVE: Updated My Account tab icon](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/4ba2d65c49a80bc37e32a6f136b2f3f33aca5fd7)
* [👌 IMPROVE: Updated my account 'Loyalty & Rewards' tab title text](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/9e553166d8ba74dae86d055c5b348a0db95def8e)
* [👌 IMPROVE: Updated clwc_dashboard display HTML](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/998e60b3253fac93b4c5e1dbc8a0ef4dbe2a0b86)
* [👌 IMPROVE: Updated redeem points ajax callback](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/387149e3ec8dc7d6df61c613ba69ff4714103109)
* [👌 IMPROVE: Updated redeem functionality on frontend in my account page](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/5393ed5a26ec9f0501b8a55d671450130c88eebe)
* [👌 IMPROVE: Updated admin settings](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/528cefaa6d8daaed6e2fdd3a796c082e4ba0d15b)
* [👌 IMPROVE: Updated CSS for admin settings styles](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/0a4c3167d08ad45aabacd94cec6aae749b12241e)
* [👌 IMPROVE: Updated settings image upload modal](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/221c72b33826ceddb35b87fe9481c20c0a35b2c4)
* [👌 IMPROVE: Updated points triggers to utilize the new setings](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/08040f0317f4d5d1ec1f8ddff61a0709e0052636)
* [👌 IMPROVE: Updated customer registration points to log into the db](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/930bcc5c8b43b4c40477428132dde22525c36e1b)
* [👌 IMPROVE: Updated text strings in settings page](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/bb8ccecda21141188feb6eb53f8927cacc2af7ab)
* [👌 IMPROVE: Updated the Log and Loyalty tables](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/5baf6909383c0e847c371b17d0dd46167bba605c)
* [📦 NEW: Added clwc_insert_loyalty_log_entry helper function](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/a2a9f87baba1e3a06294993f989841764dc53089)
* [👌 IMPROVE: Updated Settings page links in a couple places](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/517df78afb766197f9f451e26c9c40c50a79e422)
* [📦 NEW: Added clwc_loyalty_log db table](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/dd91d423f10517cb8653f391c44cb1a7c332968e)
* [👌 IMPROVE: Updated variables to fix deprecation notice](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/2ff4916bcc7d7c724d6fe63713a40f9f1ed827ce)
* [👌 IMPROVE: Updated settings page to prepare for v2.0 release](https://github.com/deviodigital/customer-loyalty-for-woocommerce/commit/96dac60d9b6ae8aee1518ead83399e2b66b3a07b)

= 1.3.1 =
*   Added notice about potential disruption to plugin updates by Matthew Mullenweg and what our next steps will be in `customer-loyalty-for-woocommerce.php`

= 1.3 =
*   Added new French translation in `languages/customer-loyalty-for-woocommerce-fr_FR.po`
*   Updated Italian translation in `languages/customer-loyalty-for-woocommerce-it_IT.po`
*   WordPress® Coding Standards code updates based on Codacy report throughout multiple files in the plugin
*   General code cleanup throughout multiple files in the plugin

= 1.2.2 =
*   Added new Italian translation in `languages/customer-loyalty-for-woocommerce-it_IT.po`

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
