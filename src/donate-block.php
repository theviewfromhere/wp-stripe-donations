<?php
/**
 * Donation Block Shortcode
 * - Displays the form for donations
 *
 * @version 0.02
 */

/**
 * @param $atts
 * @return string
 */

global $donations_options;

function tvfh_donation_block( $atts ){

	$output = false;

	$amounts = array(
		'donation_amount_one_value',
		'donation_amount_two_value',
		'donation_amount_three_value',
		'donation_amount_four_value',
		'donation_amount_five_value'
	);

	$output  = '<div id="donate">';
	$output .= '<div class="donation-amounts">';
	foreach ( $amounts as $amount ) :
		$val = get_option('tvfh_donation_settings')[$amount];
		if ( isset( $val ) ) :
			$output .=  '<div>';
			$output .=  '<input name="donation_amount" type="radio" id="donate_' . $val . '" value="' . $val . '">';
			$output .=  '<label for="donate_' . $val . '">$' . $val . '</label>';
			$output .=  '</div>';
		endif;
	endforeach;
	$output .= '<div>';
	$output .= '<input name="donation_amount" type="radio" id="donate_other" value="other">';
	$output .= '<label for="donate_other" class="amount-other"><span>' . __('Other', 'rrt') . '</span><input type="number" id="otherAmount" placeholder="' . __('Enter amount', 'rrt') . '" value=""></label>';
	$output .= '</div>';
	$output .= '<div class="donate_monthly">';
	$output .= '<input name="donate_monthly_option" type="checkbox" id="donate_monthly"><label for="donate_monthly"><span>I want to donate this amount monthly *</span></label>';
	$output .= '</div>';
	$output .= '<span class="terms">';
	$output .= '<p>* By checking this box you agree to be charged the amount selected on a monthly basis, this can be canceld at any time</p>';
	$output .= '</span>';
	$output .= '<form id="donateStripe" method="post" action="' . esc_url( admin_url('admin-post.php') ) . '">';
	$output .= '<input type="hidden" name="action" value="stripe_charge">';
	$output .= '<input type="hidden" name="donate_monthly" value="false">';
	$output .= '<input type="hidden" name="stripeAmount" id="stripeAmount">';
	$output .= '<input type="hidden" name="stripeToken" id="stripeToken">';
	$output .= '<input type="hidden" name="stripeEmail" id="stripeEmail">';
	$output .= '<button id="stripeButton" class="button" disabled>Donate Now</button>';
	$output .= '</form>';
	$output .= '</div>';
	$output .= '</div>';

	return $output;
}
add_shortcode( 'donation_block', 'tvfh_donation_block' );