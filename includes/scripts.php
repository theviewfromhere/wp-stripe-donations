<?php

function tvfh_load_stripe_scripts() {

	global $stripe_options;

	// check to see if we are in test mode
	if(isset($stripe_options['test_mode']) && $stripe_options['test_mode']) {
		$publishable = $stripe_options['test_publishable_key'];
	} else {
		$publishable = $stripe_options['live_publishable_key'];
	}

	if (isset($stripe_options['currency']) && false === empty($stripe_options['currency']) ) {
		$currency = $stripe_options['currency'];
	} else {
		$currency = 'USD';
	}

	wp_enqueue_script( 'stripe-checkout', '//checkout.stripe.com/checkout.js', array(), '1.0', true);
	wp_enqueue_script('stripe-processing', STRIPE_BASE_URL . 'includes/js/process.js', array('stripe-checkout'), '1.0', true);
	wp_localize_script('stripe-processing', 'stripe_vars', array(
			'publishable_key' => $publishable,
			'currency' => $currency,
		)
	);
}
add_action('wp_enqueue_scripts', 'tvfh_load_stripe_scripts');
