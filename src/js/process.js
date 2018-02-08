/**
 * WP Stripe Donations: Stripe Processing
 * Payment processing for FE
 *
 * @version 0.02
 */

/**
 * Set Cookie
 * @param string name
 * @param string value
 * @param number days (expirey)
 */
var setCookie = function( name, value, days ) {
	var expires = "";

	if ( days ) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		expires = "; expires=" + date.toGMTString();
	}

	document.cookie = name + "=" + value + expires + "; path=/";
};

var donationsComponent = (function() {

	var s = {};

	s.ui = {
		stripeButton: 	document.getElementById('stripeButton'),
		input_amount: 	document.querySelectorAll('input[name="donation_amount"]'),
		amount_other: 	document.getElementById('otherAmount'),
		monthly_label: 	document.querySelector('.donate_monthly label'),
		elToken:		document.getElementById('stripeToken'),
		elAmount:		document.getElementById('stripeAmount'),
		stripeForm:		document.getElementById('donateStripe')
	};

	s.getDonationAmount = function(){
		var amount = false;

		Array.prototype.forEach.call( s.ui.input_amount, function(el) {
			if ( el.checked ) {

				if ( el.value !== 'other') {
					amount = el.value * 100;
				}

				if ( el.value === 'other') {
					amount = s.ui.amount_other.value * 100;
				}

				return amount;
			}
		});

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
				console.log(token);
				// You can access the token ID with `token.id`.
				// Get the token ID to your server-side code for use.
				var amount = s.getDonationAmount();
				s.ui.elToken.value = token.id;
				s.ui.elAmount.value = amount;
				s.ui.stripeForm.submit();
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
						panelLabel: buttonLabel
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
}());

window.addEventListener('load', donationsComponent.init(), false);