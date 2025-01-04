<?php
function my_factotum_create_account() {
	$nonce_value = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? wp_unslash( $_POST['woocommerce-register-nonce'] ) : $nonce_value; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( isset( $_POST['factotum_register'], $_POST['email'] ) && wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
		$username = 'no' === get_option( 'woocommerce_registration_generate_username' ) && isset( $_POST['username'] ) ? wp_unslash( $_POST['username'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$password = 'no' === get_option( 'woocommerce_registration_generate_password' ) && isset( $_POST['password'] ) ? $_POST['password'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$email    = wp_unslash( $_POST['email'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		try {
			$validation_error  = new WP_Error();
			$validation_error  = apply_filters( 'woocommerce_process_registration_errors', $validation_error, $username, $password, $email );
			$validation_errors = $validation_error->get_error_messages();

			if ( 1 === count( $validation_errors ) ) {
				throw new Exception( $validation_error->get_error_message() );
			} elseif ( $validation_errors ) {
				foreach ( $validation_errors as $message ) {
					wc_add_notice( '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong> ' . $message, 'error' );
					$_SESSION['register_message'] = $message;
				}
				throw new Exception();
			}

			$new_customer = wc_create_new_customer( sanitize_email( $email ), wc_clean( $username ), $password );

			if ( is_wp_error( $new_customer ) ) {
				throw new Exception( $new_customer->get_error_message() );
			}

			if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) ) {
				wc_add_notice( __( 'Your account was created successfully and a password has been sent to your email address.', 'woocommerce' ) );
				$_SESSION['register_message'] = __( 'Your account was created successfully and a password has been sent to your email address.', 'woocommerce' );
			} else {
				wc_add_notice( __( 'Your account was created successfully. Your login details have been sent to your email address.', 'woocommerce' ) );
				$_SESSION['register_message'] = __( 'Your account was created successfully. Your login details have been sent to your email address.', 'woocommerce' );
			}

			// Only redirect after a forced login - otherwise output a success notice.
			if ( apply_filters( 'woocommerce_registration_auth_new_customer', true, $new_customer ) ) {
				wc_set_customer_auth_cookie( $new_customer );

				if ( ! empty( $_POST['redirect'] ) ) {
					$redirect = wp_sanitize_redirect( wp_unslash( $_POST['redirect'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				} elseif ( wc_get_raw_referer() ) {
					$redirect = wc_get_raw_referer();
				} else {
					$redirect = wc_get_page_permalink( 'myaccount' );
				}

				wp_redirect( wc_get_page_permalink( 'myaccount' ) ); //phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
				exit;
			}
		} catch ( Exception $e ) {
			if ( $e->getMessage() ) {
				wc_add_notice( '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong> ' . $e->getMessage(), 'error' );
				$_SESSION['register_message'] = $e->getMessage();
			}
		}
	}
}

add_action( 'init', 'my_factotum_create_account' ); //custom form processing and validation for new user registration

add_action( 'wp_login_failed', function ( $username, $error ) { // when login fails display error message in the slider login form using session
	if ( isset( $error->errors ) ) {
		$_SESSION['login_message'] = $error->errors;
	}
}, 10, 2 );

//redirect to home page if user is not an admin user
add_filter( 'logout_redirect', 'my_factotum_redirect_after_logout', 10, 3 );
function my_factotum_redirect_after_logout( $redirect_to, $requested_redirect_to, $user ) {
	if ( ! in_array( 'administrator', $user->roles ) ) {
		$redirect_to = home_url();
	}

	return $redirect_to;
}

//login and my account form customization
// Add the code below to your theme's functions.php file to add a confirm password field on the register form under My Accounts.
add_filter( 'woocommerce_registration_errors', 'registration_errors_validation', 10, 3 );
function registration_errors_validation( $reg_errors, $sanitized_user_login, $user_email ) {
	global $woocommerce;
	extract( $_POST );
	if ( strcmp( $password, $password2 ) !== 0 ) { // check if password entered matches
		return new WP_Error( 'registration-error', __( 'Les mots de passe ne correspondent pas.', 'woocommerce' ) );
	}
	if ( empty( $accept_terms_policy ) ) { // check if terms and condtions are accepted
		return new WP_Error( 'registration-error', __( 'Veuillez accepter les politique de confidentialité de MyFactotum..', 'woocommerce' ) );
	}

	return $reg_errors;
}

//customizing the my account nav menu
//First hook that adds the menu item to my-account WooCommerce menu
function adding_alerte_menu( $menu_links ) {
	$new        = array( 'alerte-factotum' => 'Alertes' );
	$menu_links = array_slice( $menu_links, 0, 1, true )
	              + $new
	              + array_slice( $menu_links, 1, null, true );

	return $menu_links;
}

add_filter( 'woocommerce_account_menu_items', 'adding_alerte_menu' );

function adding_wishlist_menu( $menu_links ) {
	$new        = array( 'wishlist-factotum' => 'Favoris' );
	$menu_links = array_slice( $menu_links, 0, 1, true )
	              + $new
	              + array_slice( $menu_links, 1, null, true );

	return $menu_links;
}

add_filter( 'woocommerce_account_menu_items', 'adding_wishlist_menu' );


/*
 * Part 2. Register Permalink Endpoint
 */
add_action( 'init', 'factotum_wc_add_endpoint' );
function factotum_wc_add_endpoint() {
	add_rewrite_endpoint( 'alerte-factotum', EP_PAGES );
	add_rewrite_endpoint( 'wishlist-factotum', EP_PAGES );
}


/*
 * Part 3. Content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
 */
add_action( 'woocommerce_account_alerte-factotum_endpoint', 'factotum_wc_my_account_endpoint_content_alerte' );
function factotum_wc_my_account_endpoint_content_alerte() {
	wc_get_template_part( "myaccount/factotum-alerte" );
}

/*
 * Part 3. Content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
 */
add_action( 'woocommerce_account_wishlist-factotum_endpoint', 'factotum_wc_my_account_endpoint_content_wishlist' );
function factotum_wc_my_account_endpoint_content_wishlist() {
	wc_get_template_part( "myaccount/factotum-wishlist" );
}