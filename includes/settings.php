<?php
/**
 * Plugin Settings Page
 *
 * @version 0.02
 */

/**
 * Add Menu Page
 */
function tvfh_stripe_add_page() {
	add_options_page('Stripe Donations', 'Stripe Donations', 'manage_options', 'tvfh_stripe', 'tvfh_stripe_do_page');
}
add_action('admin_menu', 'tvfh_stripe_add_page');

/**
 * Draw Menu Page
 */
function tvfh_stripe_do_page() {

	/* Stripe Options */
	global $stripe_options;
	$woo_managed = false;

	/* @var $currs {currencies list} */
	$currs = array(
		array('aed', 'United Arab Emirates dirham, AED'),
		array('afn', 'Afghan afghani, AFN'),
		array('all', 'Albanian lek, ALL'),
		array('amd', 'Armenian dram, AMD'),
		array('ang', 'Netherlands Antillean guilder, ANG'),
		array('aoa', 'Angolan kwanza, AOA'),
		array('ars', 'Argentine peso, ARS'),
		array('aud', 'Australian dollar, AUD'),
		array('awg', 'Aruban florin, AWG'),
		array('azn', 'Azerbaijani manat, AZN'),
		array('bam', 'Bosnia and Herzegovina convertible mark, BAM'),
		array('bbd', 'Barbadian dollar, BBD'),
		array('bdt', 'Bangladeshi taka, BDT'),
		array('bgn', 'Bulgarian lev, BGN'),
		array('bhd', 'Bahraini dinar, BHD'),
		array('bif', 'Burundian franc, BIF'),
		array('bmd', 'Bermudian dollar, BMD'),
		array('bnd', 'Brunei dollar, BND'),
		array('bob', 'Bolivian boliviano, BOB'),
		array('brl', 'Brazilian real, BRL'),
		array('bsd', 'Bahamian dollar, BSD'),
		array('btc', 'Bitcoin, BTC'),
		array('btn', 'Bhutanese ngultrum, BTN'),
		array('bwp', 'Botswana pula, BWP'),
		array('byr', 'Belarusian ruble, BYR'),
		array('bzd', 'Belize dollar, BZD'),
		array('cad', 'Canadian dollar, CAD'),
		array('cdf', 'Congolese franc, CDF'),
		array('chf', 'Swiss franc, CHF'),
		array('clp', 'Chilean peso, CLP'),
		array('cny', 'Chinese yuan, CNY'),
		array('cop', 'Colombian peso, COP'),
		array('crc', 'Costa Rican colón, CRC'),
		array('cuc', 'Cuban convertible peso, CUC'),
		array('cup', 'Cuban peso, CUP'),
		array('cve', 'Cape Verdean escudo, CVE'),
		array('czk', 'Czech koruna, CZK'),
		array('djf', 'Djiboutian franc, DJF'),
		array('dkk', 'Danish krone, DKK'),
		array('dop', 'Dominican peso, DOP'),
		array('dzd', 'Algerian dinar, DZD'),
		array('egp', 'Egyptian pound, EGP'),
		array('ern', 'Eritrean nakfa, ERN'),
		array('etb', 'Ethiopian birr, ETB'),
		array('eur', 'Euro, EUR'),
		array('fjd', 'Fijian dollar, FJD'),
		array('fkp', 'Falkland Islands pound, FKP'),
		array('gbp', 'Pound sterling, GBP'),
		array('gel', 'Georgian lari, GEL'),
		array('ggp', 'Guernsey pound, GGP'),
		array('ghs', 'Ghana cedi, GHS'),
		array('gip', 'Gibraltar pound, GIP'),
		array('gmd', 'Gambian dalasi, GMD'),
		array('gip', 'Gibraltar pound, GIP'),
		array('gnf', 'Guinean franc, GNF'),
		array('gip', 'Gibraltar pound, GIP'),
		array('gtq', 'Guatemalan quetzal, gtq'),
		array('gyd', 'Guyanese dollar, GYD'),
		array('hkd', 'Hong Kong dollar, HKD'),
		array('hnl', 'Honduran lempira, HNL'),
		array('hrk', 'Croatian kuna, HRK'),
		array('htg', 'Haitian gourde, HTG'),
		array('huf', 'Hungarian forint, HUF'),
		array('idr', 'Indonesian rupiah, IDR'),
		array('ils', 'Israeli new shekel, ILS'),
		array('imp', 'Manx pound, IMP'),
		array('inr', 'Indian rupee, INR'),
		array('iqd', 'Iraqi dinar, IQD'),
		array('irr', 'Iranian rial, IRR'),
		array('irt', 'Iranian toman, IRT'),
		array('isk', 'Icelandic króna, ISK'),
		array('jep', 'Jersey pound, JEP'),
		array('jmd', 'Jamaican dollar, JMD'),
		array('jod', 'Jordanian dinar, JOD'),
		array('jpy', 'Japanese yen, JPY'),
		array('kes', 'Kenyan shilling, KES'),
		array('kgs', 'Kyrgyzstani som, KGS'),
		array('khr', 'Cambodian riel, KHR'),
		array('kmf', 'Comorian franc, KMF'),
		array('kpw', 'North Korean won, KPW'),
		array('krw', 'South Korean won, KRW'),
		array('kwd', 'Kuwaiti dinar, KWD'),
		array('kyd', 'Cayman Islands dollar, KYD'),
		array('kzt', 'Kazakhstani tenge, KZT'),
		array('lak', 'Lao kip, LAK'),
		array('lbp', 'Lebanese pound, LBP'),
		array('lkr', 'Sri Lankan rupee, LKR'),
		array('lrd', 'Liberian dollar, LRD'),
		array('lsl', 'Lesotho loti, LSL'),
		array('lyd', 'Libyan dinar, LYD'),
		array('mad', 'Moroccan dirham, MAD'),
		array('mdl', 'Moldovan leu, MDL'),
		array('mga', 'Malagasy ariary, MGA'),
		array('mkd', 'Macedonian denar, MKD'),
		array('mmk', 'Burmese kyat, MMK'),
		array('mnt', 'Mongolian tögrög, MNT'),
		array('mop', 'Macanese pataca, MOP'),
		array('mro', 'Mauritanian ouguiya, MRO'),
		array('mur', 'Mauritian rupee, MUR'),
		array('mvr', 'Maldivian rufiyaa, MVR'),
		array('mwk', 'Malawian kwacha, MWK'),
		array('mxn', 'Mexican peso, MXN'),
		array('myr', 'Malaysian ringgit, MYR'),
		array('mzn', 'Mozambican metical, MZN'),
		array('nad', 'Namibian dollar, NAD'),
		array('ngn', 'Nigerian naira, NGN'),
		array('nio', 'Nicaraguan córdoba, NIO'),
		array('nok', 'Norwegian krone, NOK'),
		array('npr', 'Nepalese rupee, NPR'),
		array('nzd', 'New Zealand dollar, NZD'),
		array('omr', 'Omani rial, OMR'),
		array('pab', 'Panamanian balboa, PAB'),
		array('pen', 'Peruvian nuevo sol, PEN'),
		array('pgk', 'Papua New Guinean kina, PGK'),
		array('php', 'Philippine peso, PHP'),
		array('pkr', 'Pakistani rupee, PKR'),
		array('pln', 'Polish złoty, PLN'),
		array('prb', 'Transnistrian ruble, PRB'),
		array('pyg', 'Paraguayan guaraní, PYG'),
		array('qar', 'Qatari riyal, qar'),
		array('ron', 'Romanian leu, RON'),
		array('rsd', 'Serbian dinar, RSD'),
		array('rup', 'Russian ruble, RUP'),
		array('rwf', 'Rwandan franc, RWF'),
		array('sar', 'Saudi riyal, SAR'),
		array('sbd', 'Solomon Islands dollar, SBD'),
		array('scr', 'Seychellois rupee, SCR'),
		array('sdg', 'Sudanese pound, SDG'),
		array('shp', 'Saint Helena pound, SHP'),
		array('sll', 'Sierra Leonean leone, SLL'),
		array('sos', 'Somali shilling, SOS'),
		array('srd', 'Surinamese dollar, SRD'),
		array('ssp', 'South Sudanese pound, SSP'),
		array('std', 'São Tomé and Príncipe dobra, STD'),
		array('syp', 'Syrian pound, SYP'),
		array('szl', 'Swazi lilangeni, SZL'),
		array('thb', 'Thai baht, THB'),
		array('tjs', 'Tajikistani somoni, TJS'),
		array('tmt', 'Turkmenistan manat, TMT'),
		array('tnd', 'Tunisian dinar, TND'),
		array('top', 'Tongan paʻanga, TOP'),
		array('try', 'Turkish lira, TRY'),
		array('ttd', 'Trinidad and Tobago dollar, TTD'),
		array('twd', 'New Taiwan dollar, TWD'),
		array('tzs', 'Tanzanian shilling, TZS'),
		array('uah', 'Ukrainian hryvnia, UAH'),
		array('ugx', 'Ugandan shilling, UGX'),
		array('usd', 'United States dollar, USD'),
		array('uyu', 'Uruguayan peso, UYU'),
		array('uzs', 'Uzbekistani som, UZS'),
		array('vef', 'Venezuelan bolívar, VEF'),
		array('vnd', 'Vietnamese đồng, VND'),
		array('vuv', 'Vanuatu vatu, VUV'),
		array('wst', 'Samoan tālā, WST'),
		array('xaf', 'Central African CFA franc, XAF'),
		array('xcd', 'East Caribbean dollar, XCD'),
		array('xof', 'West African CFA franc, XOF'),
		array('xpf', 'CFP franc, XPF'),
		array('yer', 'Yemeni rial, YER'),
		array('zar', 'South African rand, ZAR'),
		array('zmw', 'Zambian kwacha, ZMW'),
	);

	/**
	 * Check For WooComm
	 */
	if ( class_exists( 'WooCommerce' ) && class_exists( 'WC_Stripe' ) ) :

		// Get the WooCommerce Stripe settings
		$woo_stripe_options = get_option( 'woocommerce_stripe_settings', array() );
		$woo_currency = get_option( 'woocommerce_currency', 'AUD' );

		if ( isset($woo_stripe_options) && is_array($woo_stripe_options) && isset($woo_currency) ) :

			$woo_managed = true;

			// Get WooCommerce currency
			$stripe_options['currency'] = strtolower($woo_currency);

			// Check for test mode
			if ( array_key_exists( 'testmode', $woo_stripe_options ) && 'yes' === $woo_stripe_options['testmode'] ) {
				$stripe_options['test_mode'] = 1;
			} else {
				$stripe_options['test_mode'] = 0;
			}

			// Check for test keys
			if ( array_key_exists( 'test_publishable_key', $woo_stripe_options ) && false === empty( $woo_stripe_options['test_publishable_key'] ) && array_key_exists( 'test_secret_key', $woo_stripe_options ) && false === empty( $woo_stripe_options['test_secret_key'] ) ) {
				$stripe_options['test_publishable_key'] = $woo_stripe_options['test_publishable_key'];
				$stripe_options['test_secret_key'] = $woo_stripe_options['test_secret_key'];
			}

			// Check for live keys
			if ( isset( $woo_stripe_options['secret_key'] ) && false === empty( $woo_stripe_options['secret_key'] ) && isset( $woo_stripe_options['publishable_key'] ) && false === empty( $woo_stripe_options['publishable_key'] ) ) {
				$stripe_options['live_publishable_key'] = $woo_stripe_options['publishable_key'];
				$stripe_options['live_secret_key'] = $woo_stripe_options['secret_key'];
			}

			update_option('tvfh_stripe_settings', $stripe_options, false);

		endif;

	endif;

	/**
	 * WooCommerce Managed Keys
	 * - Import/display WooCom Keys
	 */
	if ($woo_managed) {

		echo '<div class="wrap">';
			echo '<h2>' . __('Stripe Donations', 'tvfh_stripe') . '</h2>';
			echo '<p class="description">WooCommerce is installed, the information below is shown for reference only.</p>';
			echo '<p class="description">Manage your Stripe keys and test mode settings <a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=stripe') . '">here</a> and manage your currency <a href="' . admin_url('admin.php?page=wc-settings&tab=general') . '">here</a>.</p>';
			echo '<table class="form-table">';
				echo '<tr>';
					echo '<th>' . __('Test Mode') . '</th>';
					echo '<td><label><input disabled type="checkbox" value="1" name="tvfh_stripe_settings[test_mode]" id="test_mode"';
					if (isset($stripe_options['test_mode'])) checked($stripe_options['test_mode'], 1);
					echo '> ' . __('Check this to use the plugin in test mode.', 'tvfh_stripe') . '</label></td>';
				echo '</tr>';
			echo '</table>';
			echo '<hr>';

			echo '<table class="form-table">';
				echo '<tr>';
					echo '<th><label for="currency">' . __('Currency', 'tvfh_stripe') . '</label></th>';
					echo '<td>';
						echo '<select disabled name="currency" id="currency">';
						foreach ($currs as $curr) :
							if ($stripe_options['currency'] === $curr[0]) {
								echo '<option selected value="' . $curr[0] . '">' . $curr[1] . '</option>';
							} else {
								echo '<option value="' . $curr[0] . '">' . $curr[1] . '</option>';
							}
						endforeach;
						echo '</select>';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
			echo '<hr>';

			echo '<h3>' . __('API Keys', 'tvfh_stripe') . '</h3>';
			echo '<table class="form-table">';
				echo '<tr>';
					echo '<th><label for="test_publish">' . __('Test Publishable', 'tvfh__stripe') . '</label></th>';
					echo '<td>';
						echo '<input readonly type="text" class="regular-text code" name="tvfh_stripe_settings[test_publishable_key]" id="test_publish" value="' . $stripe_options['test_publishable_key'] . '">';
						echo '<p class="description">' . __('Paste your test publishable key.', 'tvfh_stripe') . '</p>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th><label for="test_secret">' . __('Test Secret', 'tvfh_stripe') . '</label></th>';
					echo '<td>';
						echo '<input readonly type="text" class="regular-text code" name="tvfh_stripe_settings[test_secret_key]" id="test_secret" value="' . $stripe_options['test_secret_key'] . '">';
						echo '<p class="description">' . __('Paste your test secret key.', 'tvfh_stripe') . '</p>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th><label for="live_publish">' . __('Live Publishable', 'tvfh_stripe') . '</label></th>';
					echo '<td>';
						echo '<input readonly type="text" class="regular-text code" name="tvfh_stripe_settings[live_publishable_key]" id="live_publish" value="' . $stripe_options['live_publishable_key'] . '">';
						echo '<p class="description">' . __('Paste your live publishable key.', 'tvfh_stripe') . '</p>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th><label for="live_secret">' . __('Live Secret', 'tvfh_stripe') . '</label></th>';
					echo '<td>';
						echo '<input readonly type="text" class="regular-text code" name="tvfh_stripe_settings[live_secret_key]" id="live_secret" value="' . $stripe_options['live_secret_key'] . '">';
						echo '<p class="description">' . __('Paste your live secret key.', 'tvfh_stripe') . '</p>';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
		echo '</div>';
	}

	/**
	 * Plugin Managed Keys
	 * - Set/Display keys from plugin
	 */
	else {
		echo '<div class="wrap">';
			echo '<h2>' . __('Stripe Donations', 'tvfh_stripe') . '</h2>';

			settings_fields('tvfh_stripe_options');

			echo '<table class="form-table">';
				echo '<tr>';
					echo '<th>' . __('Test Mode') . '</th>';
					echo '<td><label><input type="checkbox" value="1" name="tvfh_stripe_settings[test_mode]" id="test_mode"';
					if (isset($stripe_options['test_mode'])) checked($stripe_options['test_mode'], 1);
					echo '> ' . __('Check this to use the plugin in test mode.', 'tvfh_stripe') . '</label></td>';
				echo '</tr>';
			echo '</table>';
			echo '<hr>';

			echo '<table class="form-table">';
				echo '<tr>';
					echo '<th><label for="currency">' . __('Currency', 'tvfh_stripe') . '</label></th>';
					echo '<td>';
						echo '<select name=tvfh__stripe_settings[currency]" id="currency">';

						foreach ($currs as $curr) :
							if ($stripe_options['currency'] === $curr[0]) {
								echo '<option selected value="' . $curr[0] . '">' . $curr[1] . '</option>';
							} else {
								echo '<option value="' . $curr[0] . '">' . $curr[1] . '</option>';
							}

						endforeach;

						echo '</select>';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
			echo '<hr>';

			echo '<h3>' . __('API Keys', 'tvfh__stripe') . '</h3>';
			echo '<table class="form-table">';
				echo '<tr>';
					echo '<th><label for="test_publish">' . __('Test Publishable', 'tvfh__stripe') . '</label></th>';
					echo '<td>';
						echo '<input type="text" class="regular-text code" name=tvfh__stripe_settings[test_publishable_key]" id="test_publish" value="' . $stripe_options['test_publishable_key'] . '">';
						echo '<p class="description">' . __('Paste your test publishable key.', 'tvfh__stripe') . '</p>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th><label for="test_secret">' . __('Test Secret', 'tvfh__stripe') . '</label></th>';
					echo '<td>';
						echo '<input type="text" class="regular-text code" name=tvfh__stripe_settings[test_secret_key]" id="test_secret" value="' . $stripe_options['test_secret_key'] . '">';
						echo '<p class="description">' . __('Paste your test secret key.', 'tvfh__stripe') . '</p>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th><label for="live_publish">' . __('Live Publishable', 'tvfh__stripe') . '</label></th>';
					echo '<td>';
						echo '<input type="text" class="regular-text code" name="tvfh_stripe_settings[live_publishable_key]" id="live_publish" value="' . $stripe_options['live_publishable_key'] . '">';
						echo '<p class="description">' . __('Paste your live publishable key.', 'tvfh_stripe') . '</p>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th><label for="live_secret">' . __('Live Secret', 'tvfh_stripe') . '</label></th>';
					echo '<td>';
						echo '<input type="text" class="regular-text code" name="tvfh_stripe_settings[live_secret_key]" id="live_secret" value="' . $stripe_options['live_secret_key'] . '">';
						echo '<p class="description">' . __('Paste your live secret key.', 'tvfh_stripe') . '</p>';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
			echo '<hr>';

			echo '<p class="submit"><input type="submit" class="button-primary" value="' . __('Save Changes') . '"></p>';

			echo '</form>';

		echo '</div>';

	};


	/**
	 * Donation Amounts Table
	 * - Fields for donations amounts to display in table
	 */

	echo '<div class="wrap">';
		echo '<h2>' . __('Donation Amounts', 'tvfh_stripe') . '</h2>';
		echo '<p class="description">Donation Amounts.</p>';
		echo '<table class="form-table">';
			echo '<tr>';
				echo '<th><label for="test_publish">' . __('Donation amount One', 'tvfh_stripe') . '</label></th>';
				echo '<td>';
					echo '<input type="number" class="regular-text code" name="tvfh_stripe_settings[donation_amount_one_value]" id="test_publish" value="' . $stripe_options['donation_amount_one_value'] . '">';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<th><label for="test_publish">' . __('Donation amount Two', 'tvfh_stripe') . '</label></th>';
				echo '<td>';
					echo '<input type="number" class="regular-text code" name="tvfh_stripe_settings[donation_amount_two_value]" id="test_publish" value="' . $stripe_options['donation_amount_two_value'] . '">';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<th><label for="test_publish">' . __('Donation amount Three', 'tvfh_stripe') . '</label></th>';
				echo '<td>';
					echo '<input type="number" class="regular-text code" name="tvfh_stripe_settings[donation_amount_three_value]" id="test_publish" value="' . $stripe_options['donation_amount_three_value'] . '">';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<th><label for="test_publish">' . __('Donation amount Four', 'tvfh_stripe') . '</label></th>';
				echo '<td>';
					echo '<input type="number" class="regular-text code" name=tvfh__stripe_settings[donation_amount_four_value]" id="test_publish" value="' . $stripe_options['donation_amount_four_value'] . '">';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<th><label for="test_publish">' . __('Donation amount Five', 'tvfh__stripe') . '</label></th>';
				echo '<td>';
					echo '<input type="number" class="regular-text code" name=tvfh__stripe_settings[donation_amount_five_value]" id="test_publish" value="' . $stripe_options['donation_amount_five_value'] . '">';
				echo '</td>';
			echo '</tr>';
		echo '</table>';

	echo '</div>';

}

// Init plugin options
function tvfh_stripe_init() {
	register_setting( 'tvfh_stripe_options', 'tvfh_stripe_settings' );
}
add_action('admin_init', 'tvfh_stripe_init' );