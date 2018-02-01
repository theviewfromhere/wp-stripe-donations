<?php

function rrt_stripe_process_payment() {

	global $stripe_options;


	if (isset($_POST['action']) && $_POST['action'] == 'stripe_charge') {

		if ( defined( 'WP_DEV' ) || $stripe_options['test_mode'] === 1 ) :
			$key = $stripe_options['test_secret_key'];
		else :
			$key = $stripe_options['live_secret_key'];
		endif;

		$currency = $stripe_options['currency'];

		// load the stripe libraries
		require_once(STRIPE_BASE_DIR . '/lib/stripe/init.php');

		// Create a charge: this will charge the user's card
		try {

			// Set the secret key
			\Stripe\Stripe::setApiKey($key);

			$token = $_POST['stripeToken'];
			$amount = $_POST['stripeAmount'];

			$stripeinfo = \Stripe\Token::retrieve($token);
			$email = $stripeinfo->email;
			$charge = \Stripe\Charge::create(array(
				"amount" => $amount, // Amount in cents
				"currency" => $currency,
				"source" => $token,
				"description" => "Rapid Relief Team Donation",
				"receipt_email" => $email
			));

			// Great success, redirect to the success page
			if ( null === get_page_by_path( '/donate/donation-success/' ) ) {
				wp_redirect( get_home_url() );
			} else {
				wp_safe_redirect( '/donate/donation-success/' );
			}
			exit;

		} catch(\Stripe\Error\Card $e) {

			// The card has been declined, redirect to the error page
			if ( null === get_page_by_path( '/donate/donation-error/' ) ) {
				wp_redirect( get_home_url() );
			} else {
				wp_safe_redirect( '/donate/donation-error/' );
			}
			exit;

		} catch(Error $e) {

			// Save the error to cookie for debugging
			setCookie( 'err', $e->getMessage(), time() + 86400, "/" );

			if ( null === get_page_by_path( '/donate/donation-error/' ) ) {
				wp_redirect( get_home_url() );
			} else {
				wp_safe_redirect( '/donate/donation-error/' );
			}
			exit;

		}

		wp_safe_redirect( get_home_url() );
		exit;

	}

}
add_action('init', 'rrt_stripe_process_payment');
