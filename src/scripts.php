<?php
/**
 * Enqueue Scripts & Stylesheets
 * 
 * @version 0.02
 */

function tvfh_load_stripe_scripts() {

	global $stripe_options;

	/* check to see if we are in test mode */
	if( isset($stripe_options['test_mode']) && $stripe_options['test_mode'] != 1) {
		$publishable = $stripe_options['live_publishable_key'];
	} else {
		$publishable = $stripe_options['test_publishable_key'];
	}

	/* Check for stripe currency ( if null default to USD */
	if ( isset($stripe_options['currency']) && false === empty($stripe_options['currency']) ) {
		$currency = $stripe_options['currency'];
	} else {
		$currency = 'USD';
	}

	/* Enqueue Scripts */
	wp_enqueue_script( 'stripe-checkout', '//checkout.stripe.com/checkout.js', array(), '1.0', true);
	wp_enqueue_script('stripe-processing', TVFH_STRIPE_BASE_URL . 'dist/js/process.min.js', array('stripe-checkout'), '1.0', true);
	wp_localize_script('stripe-processing', 'stripe_vars', array(
			'publishable_key' => $publishable,
			'currency' => $currency,
		)
	);
}
add_action('wp_enqueue_scripts', 'tvfh_load_stripe_scripts');


function tvfh_donation_enqueue_styles() {
	wp_enqueue_style( 'tvfh-donations-styles', TVFH_STRIPE_BASE_URL . 'dist/scss/styles.min.css', array(), '0.02', 'all' );
}
add_action( 'wp_enqueue_scripts', 'tvfh_donation_enqueue_styles' );