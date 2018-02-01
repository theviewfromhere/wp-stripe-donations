/**
 * WP Stripe Donations: Stripe Processing
 * Payment processing for FE
 *
 * @version 0.02
 */
var donationsComponent = function() {

	var s = {};

	s.ui = {
		stripeButton: 	document.getElementById('stripeButton'),
		input_amount: 	document.querySelector('input[name="donation_amount"]:checked'),
		amount_other: 	document.getElementById('otherAmount'),
		monthly_label: 	document.querySelector('.donate_monthly label')
	};

	s.getDonationAmount = function(){
		var amount = false;

		if ( s.ui.input_amount !== null) {

			if ( s.ui.input_amount.value !== 'other') {
				amount = s.ui.input_amount.value * 100;
			}

			if ( s.ui.input_amount.value === 'other') {
				amount = s.ui.amount_other.value * 100;
			}

		}

		return amount;
	};

	s.monthlyDonation = function() {
		var input = document.getElementById('donate_monthly');
		if ( input.checked === true ) {
			document.querySelector('input[name="donate_monthly"]').value = 'false';
		} else {
			document.querySelector('input[name="donate_monthly"]').value = 'true';
		}
	};

	s.init = (function() {

		var handler = StripeCheckout.configure({
			key: stripe_vars.publishable_key,
			locale: 'auto',
			token: function(token) {
				// You can access the token ID with `token.id`.
				// Get the token ID to your server-side code for use.
				var stripeForm = document.getElementById('donateStripe');
				var elToken = document.getElementById('stripeToken');
				var elAmount = document.getElementById('stripeAmount');
				var amount = s.getDonationAmount();
				elToken.setAttribute('value', token.id);
				elAmount.setAttribute('value', amount);
				stripeForm.submit();
			}
		});

		s.ui.monthly_label.addEventListener('click', function() {
			s.monthlyDonation();
		});

		if ( s.ui.stripeButton) {
			s.ui.stripeButton.removeAttribute('disabled', false);
			s.ui.stripeButton.addEventListener('click', function(e) {
				e.preventDefault();


				// change donation button label for monthly donations
				var buttonLabel = 'Donate';
				if ( document.getElementById('donate_monthly').checked === true ) {

					buttonLabel = 'Donate Monthly';
				}

				var amount = s.getDonationAmount();
				if (amount>50) {
					// Open Checkout with further options:
					handler.open({
						name: 'Charitable Donation',
						description: 'The Murle Education Foundation',
						amount: amount,
						currency: 'AUD',
						billingAddress: true,
						panelLabel: buttonLabel
					});
				}


				var amount = s.getDonationAmount();
				var amountDisplay = (amount/100);
				var description = 'Charitable Donation $' + amountDisplay + ' ' + stripe_vars.currency.toUpperCase();
				if (amount>50) {
					// Open Checkout with further options:
					handler.open({
						name: 'Rapid Relief Team',
						description: description,
						amount: amount,
						currency: stripe_vars.currency,
						billingAddress: true,
						panelLabel: 'Donate'
					});
				}
			});
		}

		// Close Checkout on page navigation:
		window.addEventListener('popstate', function() {
			handler.close();
		});
	});

	return s;
};

var

window.addEventListener('load', donationsComponent.init(), false);