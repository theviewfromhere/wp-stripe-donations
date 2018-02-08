<?php

function tvfh_stripe_process_payment() {

	global $stripe_options;

	if ( isset($_POST['action']) && $_POST['action'] === 'stripe_charge' ) {

		if (defined('WP_DEV') || $stripe_options['test_mode'] == 1) :
			$key = $stripe_options['test_secret_key'];
		else :
			$key = $stripe_options['live_secret_key'];
		endif;

		$currency = $stripe_options['currency'];
		$description =  get_bloginfo( 'name' ) . ' Donation';

		// load the stripe libraries
		require_once(TVFH_STRIPE_BASE_DIR . '/lib/stripe/init.php');

		// Create a charge: this will charge the user's card
		try {

			// Set the secret key
			\Stripe\Stripe::setApiKey($key);

			$token = $_POST['stripeToken'];
			$amount = $_POST['stripeAmount'];
			$monthly = $_POST['donate_monthly'];

			$stripeinfo = \Stripe\Token::retrieve($token);
			$email = $stripeinfo->email;

			if ($monthly == 'false') {
				try {
					$charge = \Stripe\Charge::create(array(
						"amount" => $amount, // Amount in cents
						"currency" => $currency,
						"source" => $token,
						"description" => $description,
						"receipt_email" => $email
					));

					/**
					 * Success!
					 * - Redirect to donation success page OR home with success header
					 */
					$status = 'success';
					if ( null === get_page_by_path( '/donate/donation-success/' ) ) {
						wp_safe_redirect(get_home_url() . '?status=' . $status);
					} else {
						wp_safe_redirect( '/donate/donation-success/' );
					}
					exit;

				/**
				 * Oops Invalid Request!
				 * - Catch invalid request
				 */
				} catch (\Stripe\Error\InvalidRequest $e) {
					// Error for invalid requests
					$status = 'invalid_request';
					if ( null === get_page_by_path( '/donate/donation-error/' ) ) {
						$status = 'error';
						wp_safe_redirect( get_home_url() . '?status=' . $status );
					} else {
						wp_safe_redirect( '/donate/donation-error/' );
					}
				}

				/**
				 * Oops Card Declined!
				 * - Catch Error Due to Card
				 */
				catch(\Stripe\Error\Card $e) {
					if ( null === get_page_by_path( '/donate/donation-error/' ) ) {
						$status = 'card';
						wp_safe_redirect( get_home_url() . '?status=' . $status );
					} else {
						wp_safe_redirect( '/donate/donation-error/' );
					}
					exit;
				}

				/**
				 * Oops General Error!
				 * - Catch All Other Errors
				 */
				catch (Error $e) {
					$status = 'error';
					setCookie( 'err', $e->getMessage(), time() + 86400, "/" );
					if ( null === get_page_by_path( '/donate/donation-error/' ) ) {
						wp_safe_redirect( get_home_url() . '?status=' . $status );
					} else {
						wp_safe_redirect( '/donate/donation-error/' );
					}
				}
			} else {

				/**
				 * Create Plan!
				 * - Attempt to create a plan
				 *      --> assign to plan
				 */
				try {
					$plan = \Stripe\Plan::create(array(
						"name" => $description ." | $" . $amount / 100 ."Plan",
						"id" => $amount / 100 . "subscription",
						"interval" => "month",
						"currency" => $currency,
						"amount" => $amount,
					));

					$customer = \Stripe\Customer::create(array(
						"email" => $email,
						"source" => $token,
						"description" => $description,
						"plan" => $amount / 100 . "subscription"
					));
				}

				/**
				 * Catch IF Plan Exists
				 * -> Assign to this plan
				 */
				catch (\Stripe\Error\InvalidRequest $e) {
					$customer = \Stripe\Customer::create(array(
						"email" => $email,
						"source" => $token,
						"description" => $description,
						"plan" => $amount / 100 . "subscription"
					));
				}
			}

			/**
			 * Success!
			 * - Redirect to donation success page OR home with success header
			 */
			$status = 'success';
			if ( null === get_page_by_path( '/donate/donation-success/' ) ) {
				wp_safe_redirect(get_home_url() . '?status=' . $status);
			} else {
				wp_safe_redirect( '/donate/donation-success/' );
			}
			exit;

			/**
			 * Oops Invalid Request!
			 * - Catch invalid request
			 */
		} catch (\Stripe\Error\InvalidRequest $e) {
			// Error for invalid requests
			$status = 'invalid_request';
			if ( null === get_page_by_path( '/donate/donation-error/' ) ) {
				$status = 'error';
				wp_safe_redirect( get_home_url() . '?status=' . $status );
			} else {
				wp_safe_redirect( '/donate/donation-error/' );
			}
		}

			/**
			 * Oops Card Declined!
			 * - Catch Error Due to Card
			 */
		catch(\Stripe\Error\Card $e) {
			if ( null === get_page_by_path( '/donate/donation-error/' ) ) {
				$status = 'card';
				wp_safe_redirect( get_home_url() . '?status=' . $status );
			} else {
				wp_safe_redirect( '/donate/donation-error/' );
			}
			exit;
		}

			/**
			 * Oops General Error!
			 * - Catch All Other Errors
			 */
		catch (Error $e) {
			$status = 'error';
			setCookie( 'err', $e->getMessage(), time() + 86400, "/" );
			if ( null === get_page_by_path( '/donate/donation-error/' ) ) {
				wp_safe_redirect( get_home_url() . '?status=' . $status );
			} else {
				wp_safe_redirect( '/donate/donation-error/' );
			}
		}

		die();

	}

}
add_action('init', 'tvfh_stripe_process_payment');
