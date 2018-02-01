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

if(!defined('STRIPE_BASE_URL')) {
	define('STRIPE_BASE_URL', plugin_dir_url(__FILE__));
}
if(!defined('STRIPE_BASE_DIR')) {
	define('STRIPE_BASE_DIR', dirname(__FILE__));
}

$stripe_options = get_option('rrt_stripe_settings');

if(is_admin()) {
	// load admin includes
	include(STRIPE_BASE_DIR . '/includes/settings.php');
} else {
	// load front-end includes
	include(STRIPE_BASE_DIR . '/includes/scripts.php');
	include(STRIPE_BASE_DIR . '/includes/process-payment.php');
}
