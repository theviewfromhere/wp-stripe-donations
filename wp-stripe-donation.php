<?php
/**
 * Plugin Name: WP Stripe Donations
 * Plugin URI: https://theviewfromhere.com.au/
 * Description: A plugin to manage the Stripe library, form processing and API Keys
 * Version: 0.2
 * Author: THE VIEW FROM HERE
 * Author URI: https://theviewfromhere.com.au/
 * License: GPL2
 */

/*
	Copyright 2017 The View From Here (email : digital@theviewfromhere.com.au)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !defined( 'ABSPATH' ) ) exit;

if( !defined('TVFH_STRIPE_BASE_URL') ) {
	define('TVFH_STRIPE_BASE_URL', plugin_dir_url(__FILE__));
}
if( !defined('TVFH_STRIPE_BASE_DIR') ) {
	define('TVFH_STRIPE_BASE_DIR', dirname(__FILE__));
}

/**
 * Define Stripe Options
 * @var $stripe_options
 */
if ( is_multisite() ) :
	$id = get_current_blog_id();
	$stripe_options = get_blog_option( $id, 'rrt_stripe_settings');
else :
	$stripe_options = get_option('rrt_stripe_settings');
endif;

if( is_admin() ) {
	// load admin includes
	include(TVFH_STRIPE_BASE_DIR . '/dist/settings.php');
	include(TVFH_STRIPE_BASE_DIR . '/dist/process-payment.php');
} else {
	// load front-end includes
	include(TVFH_STRIPE_BASE_DIR . '/dist/scripts.php');
	include(TVFH_STRIPE_BASE_DIR .'/dist/donate-block.php');
}