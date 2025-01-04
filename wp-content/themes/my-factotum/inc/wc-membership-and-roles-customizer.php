<?php
/*
 * Allow editor to view all restricted content
 */
add_filter( 'user_has_cap', 'my_factotum_add_role_cap', 20, 3 ); //allow the restricted content if user role is admin/mandataire/expert, prestataire

/*
 * @param array $allcaps All user capabilities
 * @param array $caps Current capability we're filtering now - $caps[0]
 * @param array $args Capability parameters, e.g. $args[0] - capability name, $args[1] - user ID, $args[2] - post ID
 */
function my_factotum_add_role_cap( $allcaps, $caps, $args ) {
	if ( isset( $caps[0] ) )  :

		switch ( $caps[0] ) :
			case 'wc_memberships_access_all_restricted_content':
			case 'wc_memberships_view_restricted_post_content' :
			case 'wc_memberships_view_restricted_product' :
			case 'wc_memberships_view_restricted_product_taxonomy_term':
			case 'wc_memberships_view_delayed_product_taxonomy_term':
			case 'wc_memberships_view_restricted_taxonomy_term' :
			case 'wc_memberships_view_restricted_taxonomy' :
			case 'wc_memberships_view_restricted_post_type' :
			case 'wc_memberships_view_delayed_post_type':
			case 'wc_memberships_view_delayed_taxonomy':
			case 'wc_memberships_view_delayed_taxonomy_term':
			case 'wc_memberships_view_delayed_post_content' :
			case 'wc_memberships_view_delayed_product' :
				//case 'wc_memberships_purchase_delayed_product' :
				//case 'wc_memberships_purchase_restricted_product' :

				// if you want to apply changes only for specific user, use:
				// if( get_current_user_id() == 2451 ) // 2451 - User ID
				// or
				// if( $args[1] = 2451 )
				// check if Editor
				if ( isset( $allcaps['mandataire'] ) ) {
					if ( $allcaps['mandataire'] === true ) {
						$allcaps[ $caps[0] ] = true;
						break;
					}
				}
				if ( isset( $allcaps['prestataire'] ) ) {
					if ( $allcaps['prestataire'] === true ) {
						$allcaps[ $caps[0] ] = true;
						break;
					}
				}
				if ( isset( $allcaps['expert'] ) ) {
					if ( $allcaps['expert'] === true ) {
						$allcaps[ $caps[0] ] = true;
						break;
					}
				}
				break;
		endswitch;

	endif;

	return $allcaps;

}

add_action( 'admin_head', 'hide_menu' );
function hide_menu() { // for agents : mandataire, expert and prestataire, hide/block backend menu access.

	$user  = wp_get_current_user();
	$roles = ( array ) $user->roles;


	if ( in_array( 'mandataire', $roles ) || in_array( 'prestataire', $roles ) || in_array( 'expert', $roles ) ) {
		remove_menu_page( 'themes.php' );

		remove_menu_page( 'index.php' );
		remove_submenu_page( 'index.php', 'update-core.php' );

		// comments
		remove_menu_page( 'edit-comments.php' );

		// tools
		remove_menu_page( 'tools.php' );

		// users menu items
		remove_menu_page( 'users.php' );

		// Real estate post type menu
		remove_menu_page( 'edit.php?post_type=real-state-product' );
		remove_submenu_page( 'edit.php?post_type=real-state-product', 'post-new.php?post_type=real-state-product' );

		// Ream post type menu
		remove_menu_page( 'edit.php?post_type=my-factotum-team' );
		remove_submenu_page( 'edit.php?post_type=my-factotum-team', 'post-new.php?post_type=my-factotum-team' );

		// My factotum import logs
		remove_menu_page( 'edit.php?post_type=xml-import-log' );

		// contact form 7
		remove_menu_page( 'wpcf7' );

		// Theme option created using ACF
		remove_menu_page( 'my-factotum-settings' );

		// Members plugin
		remove_menu_page( 'members' );
	}

}

//membership area menu titles customizer
function factotum_rename_member_area_sections( $sections ) {
	$sections['back-to-memberships'] = __( 'Retour', 'factotum' );
	$sections['my-membership-content'] = __( 'Contenu', 'factotum' );
	$sections['my-membership-products'] = __( 'Produits', 'factotum' );
	$sections['my-membership-discounts'] = __( 'Réductions', 'factotum' );
	$sections['my-membership-notes'] = __( 'Notes', 'factotum' );
	$sections['my-membership-details'] = __( 'Détails', 'factotum' );

	return $sections;
}
add_filter( 'wc_membership_plan_members_area_sections', 'factotum_rename_member_area_sections' );

//replace texts which cannot be replaced using the hooks and templating
add_filter( 'gettext', 'change_membership_text', 10, 3 );
add_filter( 'ngettext', 'change_membership_text', 10, 3 );
function change_membership_text( $translated, $text, $domain ) {
	if ( $text === 'View' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Voir', $domain );
	}
	if ( $text === 'Back to %s' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Retour', $domain );
	}
	if ( $text === 'Membership Details' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Détails d\'adhésion', $domain );
	}
	if ( $text === 'Start Date' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Date de début', $domain );
	}
	if ( $text === 'Expires' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Date de fin', $domain );
	}
	if ( $text === 'Actions' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Voulez-vous résilier votre abonnement ?', $domain );
	}
	if ( $text === 'Cancel' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Résilier', $domain );
	}
	if ( $text === 'There are no discounts available for this membership.' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Aucune réduction attribuée.', $domain );
	}
	if ( $text === 'There are no notes for this membership.' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Aucune note attribué.', $domain );
	}
	if ( $text === 'There are no products assigned to this membership.' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Aucun produit attribué.', $domain );
	}
	if ( $text === 'Looks like you don\'t have a membership yet!' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Il semble que vous n\'ayez pas encore d\'adhésion.', $domain );
	}
	if ( $text === 'Plan' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Produit', $domain );
	}
	if ( $text === 'Start' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Début', $domain );
	}
	if ( $text === 'Status' && $domain === 'woocommerce-memberships' ) {
		$translated = esc_html__( 'Statut', $domain );
	}
	return $translated;
}

add_filter( 'gettext_with_context', 'factotum_gettext_with_context', 10, 4 );
add_filter( 'ngettext_with_context', 'factotum_gettext_with_context', 10, 4 );
function factotum_gettext_with_context( $translation, $text, $context, $domain ) {
	if ( 'woocommerce-memberships' === $domain ) {
		if ( 'Cancelled' === $text && 'Membership Status' === $context ) {
			$translation = 'Résilier';
		}
	}

	return $translation;
}