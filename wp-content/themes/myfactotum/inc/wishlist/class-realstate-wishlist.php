<?php
if ( ! class_exists( "MY_Factotum_Wishlist" ) ) {
	class MY_Factotum_Wishlist {
		public function __construct() {
			add_action( "wp_ajax_real_state_add_to_wishlist", array(
				$this,
				"addToWishlist"
			) ); // ajax response functionality to import single item from the xml file (using identifiant)
			add_action( "wp_ajax_nopriv_real_state_add_to_wishlist", array(
				$this,
				"responseForNonLoggedinUsers"
			) ); // ajax response for not loggedin users
			add_action( "wp_ajax_real_state_remove_from_wishlist", array(
				$this,
				"removeFromWishlist"
			) ); // ajax response functionality to import single item from the xml file (using identifiant)
			add_action( "wp_ajax_nopriv_real_state_remove_from_wishlist", array(
				$this,
				"responseForNonLoggedinUsers"
			) ); // ajax response for not loggedin users

			add_action( "wp_ajax_real_state_move_remove_from_wishlist_trash", array(
				$this,
				"moveRemoveFromWishlistTrash"
			) ); // ajax response functionality to import single item from the xml file (using identifiant)
			add_action( "wp_ajax_nopriv_real_state_move_to_trash_wishlist", array(
				$this,
				"responseForNonLoggedinUsers"
			) ); // ajax response for not loggedin users

			add_action( "wp", array(
				$this,
				"deleteWishlistOnPageReload"
			) ); //delete the realstates from wishlist(that are saved in wishlist trash) on reload of page after after delete clear the wishlist trash list

		}

		public function deleteWishlistOnPageReload() {
			if ( get_current_user_id() ) {
				$user_id  = get_current_user_id();
				$wishlist_trash_list = get_user_meta( $user_id, "_wishlist_real_state_trash", true );
				if ( ! empty( $wishlist_trash_list ) ) {
					foreach ( $wishlist_trash_list as $item ) {
						$this->removeSingleItemFromWishlist( $item );
					}
				}
				delete_user_meta( $user_id, "_wishlist_real_state_trash" );
			}
		}

		public function removeSingleItemFromWishlist( $item ) {
			$delete_status = false;
			$user_wishlist = get_user_meta( get_current_user_id(), "_wishlist_real_state", true );
			if ( in_array( $item, $user_wishlist ) ) {
				if ( count( $user_wishlist ) == 1 ) { // if the wishlist array has only one last item then, delete the whole meta field
					delete_user_meta( get_current_user_id(), "_wishlist_real_state" );
				} else {
					$key = array_search( $item, $user_wishlist );
					if ( false !== $key ) {
						unset( $user_wishlist[ $key ] );
					}
					update_user_meta( get_current_user_id(), "_wishlist_real_state", $user_wishlist );
				}
				$delete_status = true;
			}

			return $delete_status;
		}

		public function addToWishlist() {
			if ( ! wp_verify_nonce( $_REQUEST['nonce'], "real_state_add_to_wishlist_nonce" ) ) { // if ajax request with invalid nonce
				exit( "No naughty business please" );
			}
			if ( empty( $_REQUEST["real_state_id"] ) || ! isset( $_REQUEST["real_state_id"] ) ) { // if file is not selected
				exit( "Empty real state id" );
			}
			$wishlist = get_user_meta( get_current_user_id(), "_wishlist_real_state", true );
			if ( ! empty( $wishlist ) ) {
				if ( ! in_array( $_REQUEST["real_state_id"], $wishlist ) ) {
					$wishlist[] = $_REQUEST["real_state_id"];
				}
				update_user_meta( get_current_user_id(), "_wishlist_real_state", $wishlist );
			} else {
				add_user_meta( get_current_user_id(), "_wishlist_real_state", array( $_REQUEST["real_state_id"] ) );
			}

			ob_start();
			get_template_part( "template-parts/single-real-state/favoris/remove-from-wishlist" );
			$data = ob_get_clean();

			die( json_encode( array(
				"status"  => 1,
				"message" => "added",
				"content" => $data
			) ) ); // if the xml file is empty, send failed response
		}

		public function moveRemoveFromWishlistTrash() {
			if ( ! wp_verify_nonce( $_REQUEST['nonce'], "real_state_add_to_wishlist_nonce" ) ) { // if ajax request with invalid nonce
				exit( "No naughty business please" );
			}
			if ( empty( $_REQUEST["real_state_id"] ) || ! isset( $_REQUEST["real_state_id"] ) ) { // if file is not selected
				exit( "Empty real state id" );
			}
			if ( empty( $_REQUEST["trash_action"] ) || ! isset( $_REQUEST["trash_action"] ) ) { // if file is not selected
				exit( "Action Not Defined" );
			}
			if ( $_REQUEST["trash_action"] == 1 ) { // action is 1 then add to trash list
				$wishlist = get_user_meta( get_current_user_id(), "_wishlist_real_state_trash", true );
				if ( ! empty( $wishlist ) ) {
					if ( ! in_array( $_REQUEST["real_state_id"], $wishlist ) ) {
						$wishlist[] = $_REQUEST["real_state_id"];
					}
					update_user_meta( get_current_user_id(), "_wishlist_real_state_trash", $wishlist );
				} else {
					add_user_meta( get_current_user_id(), "_wishlist_real_state_trash", array( $_REQUEST["real_state_id"] ) );
				}
			} else { // else remove from the trash list
				$trash_list = get_user_meta( get_current_user_id(), "_wishlist_real_state_trash", true );
				if ( in_array( $_REQUEST["real_state_id"], $trash_list ) ) {
					if ( count( $trash_list ) == 1 ) { // if the wishlist array has only one last item then, delete the whole meta field
						delete_user_meta( get_current_user_id(), "_wishlist_real_state_trash" );
					} else {
						$key = array_search( $_REQUEST["real_state_id"], $trash_list );
						if ( false !== $key ) {
							unset( $trash_list[ $key ] );
						}
						update_user_meta( get_current_user_id(), "_wishlist_real_state_trash", $trash_list );
					}
				}
			}
			die( json_encode( array(
				"status" => 1
			) ) ); // if the xml file is empty, send failed response

		}

		public function removeFromWishlist() {
			if ( ! wp_verify_nonce( $_REQUEST['nonce'], "real_state_add_to_wishlist_nonce" ) ) { // if ajax request with invalid nonce
				exit( "No naughty business please" );
			}
			if ( empty( $_REQUEST["real_state_id"] ) || ! isset( $_REQUEST["real_state_id"] ) ) { // if file is not selected
				exit( "Empty real state id" );
			}
			$response    = array();
			$realstat_id = $_REQUEST["real_state_id"];
			if ( ! $this->isRealStateInWishlist( $realstat_id ) ) {
				$response = array(
					"status"  => 0,
					"message" => "Item not found in wishlist"
				);
			}
			if ( $this->removeSingleItemFromWishlist( $realstat_id ) == true ) {
				ob_start();
				get_template_part( "template-parts/single-real-state/favoris/add-to-wishlist" );
				$data     = ob_get_clean();
				$response = array(
					"status"  => 1,
					"message" => "Delete Success",
					"content" => $data
				);
			} else {
				$response = array(
					"status"  => 0,
					"message" => "Failed to delete"
				);
			}
			die( json_encode( $response ) );
		}

		public function responseForNonLoggedinUsers() {
			die( json_encode( array(
				"status"  => 0,
				"message" => "You are not logged in"
			) ) );
		}

		public function isRealStateInWishlist( $real_state_id ) {
			$result = false;
			if ( is_user_logged_in() && get_current_user_id() ) {
				$user_id  = get_current_user_id();
				$wishlist = get_user_meta( $user_id, "_wishlist_real_state", true );
				if ( is_array( $wishlist ) ) {
					if ( in_array( $real_state_id, $wishlist ) ) {
						$result = true;
					}
				}
			}

			return $result;
		}

		public function getAllWishlists() { // get all wishlist of the current user if logged in
			$wishlist = false;
			if ( is_user_logged_in() && get_current_user_id() ) {
				$user_id  = get_current_user_id();
				$wishlist = get_user_meta( $user_id, "_wishlist_real_state", true );
			}

			return $wishlist;
		}


	}

	global $MY_Factotum_Wishlist;
	$MY_Factotum_Wishlist = new MY_Factotum_Wishlist();
}
