<?php
// adding woocommerce theme support
function factotum_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

add_action( 'after_setup_theme', 'factotum_add_woocommerce_support' );

add_action( 'template_redirect', 'my_custom_redirect' ); // if non logged in user tries to access login/register page in my account, redirect to the home page.
function my_custom_redirect() {
	if ( ! is_user_logged_in() ) {
		global $wp;
		if ( home_url( $wp->request ) ) {
			$last_segment = basename( home_url( $wp->request ) );
			if ( $last_segment == "my-account" ) {
				wp_redirect( home_url() );
				die();
			}
		}

	}
}

/**
 * @snippet       Custom Redirect @ WooCommerce My Account Login
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 4.0
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

add_filter( 'woocommerce_login_redirect', 'factotum_customer_login_redirect', 9999, 2 );

function factotum_customer_login_redirect( $redirect, $user ) {
	if ( basename( $redirect ) != "checkout" ) //	if ( wc_user_has_role( $user, 'customer' ) ) {
	{
		$redirect = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
	} // my account
//	}
	return $redirect;
}

/**
 * Change the subscription thank you message after purchase
 *
 * @param int $order_id
 *
 * @return string
 */
function my_factotum_change_custom_memberships_thank_you( $order_id ) {

//	if( WC_Subscriptions_Order::order_contains_subscription( $order_id ) ) {
	$thank_you_message = sprintf( __( '%sMerci pour l\'achat de votre pack. Vous pouvez désormais accéder à l\'ensemble des contenus de notre site. Pour plus de détails, consultez  %svotre compte%s%s', 'woocommerce-subscriptions' ), '<p>', '<a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '">', '</a>', '</p>' );

	return $thank_you_message;
//	}

}

add_filter( 'woocommerce_memberships_thank_you_message', 'my_factotum_change_custom_memberships_thank_you' );

//add_action( 'user_register', function ( $user_id ) { // as soon as user is created/registered if user role is admin,mandataire,expert, prestataire, subscribe him to membership automatically
//
//	if ( isset( $user_id ) ) {
//		$user       = get_userdata( $user_id );
//		$user_roles = empty( $user ) ? array() : $user->roles;
//		if ( in_array( "mandataire", $user_roles ) || in_array( "prestataire", $user_roles ) || in_array( "expert", $user_roles ) || in_array( "administrator", $user_roles ) ) {
//			if ( ! empty( wc_memberships_get_membership_plans() ) ) {
//				foreach ( wc_memberships_get_membership_plans() as $key => $membership_plan ) {
//					$args = array(
//						// Enter the ID (post ID) of the plan to grant at registration
//						'plan_id' => $key,
//						'user_id' => $user_id,
//					);
//					if ( function_exists( "wc_memberships_create_user_membership" ) ) {
//						wc_memberships_create_user_membership( $args );
//					}
//				}
//			}
//		}
//	}
//} );

/**
 * ADDING PASSWORD CHECKING SCRIPT IN ALL PAGES
 * DEQUEUEING WILL DEQUEUE SCRIPT FROM ONLY SPECIFIC PAGES EG : MY ACCOUNT PAGE,CHECKOUT PAGE
 * ENQUEUEING THE SCRIPT AGAIN WILL ENQUEUE IN ALL PAGES, THROUGHOUT THE SITE PAGES
 */
function iconic_remove_password_strength() { // making the password stenght meter script global
	wp_dequeue_script( 'wc-password-strength-meter' );
	wp_enqueue_script( 'wc-password-strength-meter' );
}

add_action( 'wp_print_scripts', 'iconic_remove_password_strength', 10 );

//customize woocommerce checkout form
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'my_factotum_checkout_sign_in', 'woocommerce_checkout_login_form', 10 );
add_action( 'my_factotum_checkout_coupon', 'woocommerce_checkout_coupon_form', 10 );
add_filter( "woocommerce_checkout_fields", "custom_override_checkout_fields", 1 );
function custom_override_checkout_fields( $fields ) { //ordering of the checkout form fields
	$fields['billing']['billing_first_name']['priority'] = 1;
	$fields['billing']['billing_last_name']['priority']  = 2;
	$fields['billing']['billing_email']['priority']      = 3;
	$fields['billing']['billing_phone']['priority']      = 4;
	$fields['billing']['billing_address_1']['priority']  = 5;
	$fields['billing']['billing_country']['priority']    = 2000;

	return $fields;
}

//customize the checkout fields of woocommerce - removing unnecessary fields from the checkout form
add_filter( 'woocommerce_checkout_fields', 'factotum_remove_checkout_fields' );
function factotum_remove_checkout_fields( $fields ) {
	unset( $fields['billing']['billing_postcode'] );
	unset( $fields['billing']['billing_state'] );
	unset( $fields['billing']['billing_city'] );
	unset( $fields['billing']['billing_company'] );
//	unset($fields['billing']['billing_address_1']);
	unset( $fields['billing']['billing_address_2'] );

	$fields['billing']['billing_email']['class'] = array( 'form-row-first' );  //  50%
	$fields['billing']['billing_phone']['class'] = array( 'form-row-last' ); //  50%

	return $fields;

}

add_action( "woocommerce_customer_reset_password", function () { // after successfully changed the password redirect to home page and add query variable(do be displayed as success message in the slide)
	wp_safe_redirect( add_query_arg( 'password-reset-success', 'true', home_url() ) );
	exit();
} );



// while save account detail -  if is_sendinblue_subscribed is checked subscribe him to sendinblue
add_action( 'woocommerce_save_account_details', 'save_users_send_newsletter_option', 12, 1 );
function save_users_send_newsletter_option( $user_id ) {
	if ( ! isset( $_POST['account_email'] ) ) {
		return;
	}
	// For Favorite color
	if ( isset( $_POST['is_sendinblue_subscribed'] ) ) {
		if ( add_to_subscription_sendinblue( $_POST['account_email'] ) ) {
			update_user_meta( $user_id, 'is_sendinblue_subscribed', sanitize_text_field( $_POST['is_sendinblue_subscribed'] ) );
		}
	} else {
		if ( remove_from_subscription_sendinblue( $_POST['account_email'] ) ) {
			update_user_meta( $user_id, 'is_sendinblue_subscribed', 0 );
		}
	}
	// For Billing email (added related to your comment)
//	if( isset( $_POST['account_email'] ) )
//		update_user_meta( $user_id, 'billing_email', sanitize_text_field( $_POST['account_email'] ) );
}

//remove "required" property for display name in edit account page
add_filter( 'woocommerce_save_account_details_required_fields', 'wc_save_account_details_required_fields' );
function wc_save_account_details_required_fields( $required_fields ) {
	unset( $required_fields['account_display_name'] );

	return $required_fields;
}

//change the column title names in my orders
function filter_woocommerce_account_orders_columns( $columns ) {

	$columns['order-number']  = __( 'N°', 'woocommerce' );
	$columns['order-date']    = __( 'Date', 'woocommerce' );
	$columns['order-status']  = __( 'État', 'woocommerce' );
	$columns['order-total']   = __( 'Montant', 'woocommerce' );
	$columns['order-actions'] = __( '', 'woocommerce' );

	return $columns;
}

add_filter( 'woocommerce_account_orders_columns', 'filter_woocommerce_account_orders_columns', 10, 1 );

//remove item count in my order table total price column
add_filter( 'ngettext', 'remove_item_count_from_my_account_orders', 105, 3 );
function remove_item_count_from_my_account_orders( $translated, $text, $domain ) {
	switch ( $text ) {
		case '%1$s for %2$s item' :
			$translated = '%1$s';
			break;

		case '%1$s for %2$s items' :
			$translated = '%1$s';
			break;
	}

	return $translated;
}


//product detail page modifier
//disable to click on image in product detail page
function factotum_remove_product_image_link( $html, $post_id ) {
	return preg_replace( "!<(a|/a).*?>!", '', $html );
}

add_filter( 'woocommerce_single_product_image_thumbnail_html', 'factotum_remove_product_image_link', 10, 2 );
/**
 * Override theme default specification for product # per row
 */


//remove product description from woocommerce_after_single_product_summary hook and moving to woocommerce_single_product_summary in product detail page
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_product_description', 10 );
//removing the tabs in product detail page and replacing with simple product description
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_description', 100 );
function woocommerce_template_product_description() {
	wc_get_template( 'single-product/tabs/description.php' );
}

// Change WooCommerce "Related products" text
add_filter( 'gettext', 'change_rp_text', 10, 3 );
add_filter( 'ngettext', 'change_rp_text', 10, 3 );

function change_rp_text( $translated, $text, $domain ) {
	if ( $text === 'Related products' && $domain === 'woocommerce' ) {
		$translated = esc_html__( 'Nos autres packs', $domain );
	}
	if ( $text === 'Create an account' && $domain === 'woocommerce' ) {
		$translated = esc_html__( 'Créer un compte?', $domain );
	}

	return $translated;
}

//remove zoom effect in woocommerce single product page
add_action( 'after_setup_theme', 'add_wc_gallery_lightbox', 100 );
function add_wc_gallery_lightbox() {
	remove_theme_support( 'wc-product-gallery-zoom' );
}

/* Remove Categories from Single Products */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

function loop_columns() {
	return 3; // 3 products per row
}

add_filter( 'loop_shop_columns', 'loop_columns', 999 );

//woocommerce hook to display all notices
add_action( "factotum_wc_notices", "woocommerce_output_all_notices" );

//remove unnecessary links/items from woocommerce dashboard menu
add_filter( 'woocommerce_account_menu_items', 'my_factoutm_wc_remove_my_account_links' );
function my_factoutm_wc_remove_my_account_links( $menu_links ) {

	unset( $menu_links['edit-address'] ); // Addresses
	unset( $menu_links['dashboard'] ); // Remove Dashboard
	unset( $menu_links['payment-methods'] ); // Remove Payment Methods
	//unset( $menu_links['orders'] ); // Remove Orders
	unset( $menu_links['downloads'] ); // Disable Downloads
	//unset( $menu_links['edit-account'] ); // Remove Account details tab
	//unset( $menu_links['customer-logout'] ); // Remove Logout link

	return $menu_links;

}

/**
 * Rename WooCommerce MyAccount menu items
 */
add_filter( 'woocommerce_account_menu_items', 'rename_menu_items' );
function rename_menu_items( $items ) {

	$items['members-area']    = 'Espace adhérent';

	return $items;
}

//adding new checkbox(terms and conditions) in checkout page accept Generales de Vente
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');

function my_custom_checkout_field_process() {
	// Check if set, if its not set add an error.
	if ( ! $_POST['general_terms_of_sale'] )
		wc_add_notice( __( 'Veuillez lire et accepter les Conditions Générales de Vente.".' ), 'error' );
}


