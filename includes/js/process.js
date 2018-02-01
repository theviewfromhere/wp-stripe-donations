var getDonationAmount = function(){
	var amount;
	var input_amount = document.querySelector('input[name="donation_amount"]:checked');
	if (input_amount !== null) {

		if (input_amount.value !== 'other') {
			amount = input_amount.value * 100;
		}

		if (input_amount.value === 'other') {
			var amount_other = document.getElementById('otherAmount');
			amount = amount_other.value * 100;
		}

		return amount;

	} else {
		return false;
	}
};

var init = function(){

	var handler = StripeCheckout.configure({
		key: stripe_vars.publishable_key,
		locale: 'auto',
		token: function(token) {
			// You can access the token ID with `token.id`.
			// Get the token ID to your server-side code for use.
			var stripeForm = document.getElementById('donateStripe');
			var elToken = document.getElementById('stripeToken');
			var elAmount = document.getElementById('stripeAmount');
			var amount = getDonationAmount();
			elToken.setAttribute('value', token.id);
			elAmount.setAttribute('value', amount);
			stripeForm.submit();
		}
	});

	var stripeButton = document.getElementById('stripeButton');

	if (stripeButton) {
		stripeButton.removeAttribute('disabled', false);
		stripeButton.addEventListener('click', function(e) {
			e.preventDefault();
			var amount = getDonationAmount();
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
};

window.addEventListener('load', init, false);