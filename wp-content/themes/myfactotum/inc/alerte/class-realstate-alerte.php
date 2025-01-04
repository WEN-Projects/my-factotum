<?php
if ( ! class_exists( "MY_Factotum_Alerte" ) ) {
	class MY_Factotum_Alerte {
		public function __construct() {
			add_action( "wp_ajax_real_state_add_to_alerte", array(
				$this,
				"addToAlerte"
			) ); // ajax response functionality to add the search criteria to alerte
			add_action( "wp_ajax_nopriv_real_state_add_to_alerte", array(
				$this,
				"responseForNonLoggedinUsers"
			) ); // ajax response for not loggedin users

			add_action( "wp_ajax_real_state_remove_from_alerte", array(
				$this,
				"removeFromAlerte"
			) ); // ajax response functionality to remove from alerte
			add_action( "wp_ajax_nopriv_real_state_remove_from_alerte", array(
				$this,
				"responseForNonLoggedinUsers"
			) ); // ajax response for not loggedin users

			add_action( "wp_ajax_real_state_change_alerte_send_email_status", array(
				$this,
				"changeAlerteEmailSendingStatus"
			) ); // ajax response functionality to change alerte email sending status
			add_action( "wp_ajax_nopriv_real_state_change_alerte_send_email_status", array(
				$this,
				"responseForNonLoggedinUsers"
			) ); // ajax response for not loggedin users

			add_action( "wp_ajax_real_state_move_remove_from_alerte_trash", array(
				$this,
				"moveRemoveFromAlerteTrash"
			) ); // ajax response functionality to add or remove to alerte trash list
			add_action( "wp_ajax_nopriv_real_state_move_remove_from_alerte_trash", array(
				$this,
				"responseForNonLoggedinUsers"
			) ); // ajax response for not loggedin users

			add_action( "wp", array(
				$this,
				"deleteAlerteOnPageReload"
			) ); //delete the alerte from alerte list(that are saved in alerte trash) on reload of page and after delete clear the alerte trash list

		}

		public function deleteAlerteOnPageReload() {
			if ( get_current_user_id() ) {
				$user_id           = get_current_user_id();
				$alerte_trash_list = get_user_meta( $user_id, "_alerte_real_state_trash", true );
				if ( ! empty( $alerte_trash_list ) ) {
					foreach ( $alerte_trash_list as $item ) {
						$all_alerte      = get_user_meta( get_current_user_id(), "_alerte_real_state", false );
						if ( ! empty( $all_alerte ) ) {
							$alerte_criterias = array();
							foreach ( $all_alerte as $criteria ) {
								if ( isset( $criteria["alerte_criteria"] ) ) {
									$alerte_criterias[] = $criteria["alerte_criteria"];
								}
							}
							if ( $this->inArrayr( $item, $alerte_criterias ) ) {
								if ( delete_user_meta( get_current_user_id(), "_alerte_real_state", array(
									"get_email_status" => "false",
									"alerte_criteria"  => $item
								) ) ) { //delete in case of get email status is false
									$status = 2;
								}
								if ( delete_user_meta( get_current_user_id(), "_alerte_real_state", array(
									"get_email_status" => "true",
									"alerte_criteria"  => $item
								) ) ) { //delete in case of get email status is true
									$status = 2;
								}

							}
						}
					}
				}
				delete_user_meta( $user_id, "_alerte_real_state_trash" );
			}
		}

		public function moveRemoveFromAlerteTrash() {
			if ( ! wp_verify_nonce( $_REQUEST['nonce'], "real_state_add_to_alerte_nonce" ) ) { // if ajax request with invalid nonce
				exit( "No naughty business please" );
			}
			if ( empty( $_REQUEST["alerte"] ) || ! isset( $_REQUEST["alerte"] ) ) { // if file is not selected
				exit( "Empty real state id" );
			}
			if ( empty( $_REQUEST["trash_action"] ) || ! isset( $_REQUEST["trash_action"] ) ) { // if file is not selected
				exit( "Action Not Defined" );
			}
			if ( $_REQUEST["trash_action"] == 1 ) { // action is 1 then add to trash list
				$alerte_trash_list = get_user_meta( get_current_user_id(), "_alerte_real_state_trash", true );
				if ( ! empty( $alerte_trash_list ) ) {
					if ( ! in_array( $_REQUEST["alerte"], $alerte_trash_list ) ) {
						$alerte_trash_list[] = $_REQUEST["alerte"];
					}
					update_user_meta( get_current_user_id(), "_alerte_real_state_trash", $alerte_trash_list );
				} else {
					add_user_meta( get_current_user_id(), "_alerte_real_state_trash", array( $_REQUEST["alerte"] ) );
				}
			} else { // else remove from the trash list
				$trash_list = get_user_meta( get_current_user_id(), "_alerte_real_state_trash", true );
				if ( in_array( $_REQUEST["alerte"], $trash_list ) ) {
					if ( count( $trash_list ) == 1 ) { // if the wishlist array has only one last item then, delete the whole meta field
						delete_user_meta( get_current_user_id(), "_alerte_real_state_trash" );
					} else {
						$key = array_search( $_REQUEST["alerte"], $trash_list );
						if ( false !== $key ) {
							unset( $trash_list[ $key ] );
						}
						update_user_meta( get_current_user_id(), "_alerte_real_state_trash", $trash_list );
					}
				}
			}
			die( json_encode( array(
				"status" => 1
			) ) ); // if the xml file is empty, send failed response

		}

		public function addToAlerte() {
			$status = 0;
			if ( ! wp_verify_nonce( $_REQUEST['nonce'], "real_state_add_to_alerte_nonce" ) ) { // if ajax request with invalid nonce
				exit( "No naughty business please" );
			}
			if ( empty( $_REQUEST["alerte_criteria"] ) || ! isset( $_REQUEST["alerte_criteria"] ) ) { // if file is not selected
				exit( "Empty Criteria" );
			}
			$alerte = get_user_meta( get_current_user_id(), "_alerte_real_state", false );
			if ( ! empty( $alerte ) ) {
				$alerte_criterias = array();
				foreach ( $alerte as $criteria ) {
					if ( isset( $criteria["alerte_criteria"] ) ) {
						$alerte_criterias[] = $criteria["alerte_criteria"];
					}
				}
				if ( $this->inArrayr( $_REQUEST["alerte_criteria"], $alerte_criterias ) ) {
					$status = 2;
				} else {
					add_user_meta( get_current_user_id(), "_alerte_real_state", array(
						"get_email_status" => "true",
						"alerte_criteria"  => $_REQUEST["alerte_criteria"]
					) );
					$status = 1;
				}
			} else {
				add_user_meta( get_current_user_id(), "_alerte_real_state", array(
					"get_email_status" => "true",
					"alerte_criteria"  => $_REQUEST["alerte_criteria"]
				) );
				$status = 1;
			}
			die( json_encode( array(
				"status" => $status
			) ) ); // if the xml file is empty, send failed response
		}

		public function inArrayr( $needle, $haystack, $strict = false ) {
			foreach ( $haystack as $item ) {
				if ( ( $strict ? $item === $needle : $item == $needle ) || ( is_array( $item ) && $this->inArrayr( $needle, $item, $strict ) ) ) {
					return true;
				}
			}

			return false;
		}

		public function removeFromAlerte() {
			if ( ! wp_verify_nonce( $_REQUEST['nonce'], "real_state_add_to_alerte_nonce" ) ) { // if ajax request with invalid nonce
				exit( "No naughty business please" );
			}
			if ( empty( $_REQUEST["alerte"] ) || ! isset( $_REQUEST["alerte"] ) ) { // if file is not selected
				exit( "Empty alerte" );
			}
			$status          = 0;
			$alerte_criteria = $_REQUEST["alerte"];
			$all_alerte      = get_user_meta( get_current_user_id(), "_alerte_real_state", false );
			if ( ! empty( $all_alerte ) ) {
				$alerte_criterias = array();
				foreach ( $all_alerte as $criteria ) {
					if ( isset( $criteria["alerte_criteria"] ) ) {
						$alerte_criterias[] = $criteria["alerte_criteria"];
					}
				}
				if ( $this->inArrayr( $alerte_criteria, $alerte_criterias ) ) {
					if ( delete_user_meta( get_current_user_id(), "_alerte_real_state", array(
						"get_email_status" => "false",
						"alerte_criteria"  => $alerte_criteria
					) ) ) { //delete in case of get email status is false
						$status = 2;
					}
					if ( delete_user_meta( get_current_user_id(), "_alerte_real_state", array(
						"get_email_status" => "true",
						"alerte_criteria"  => $alerte_criteria
					) ) ) { //delete in case of get email status is true
						$status = 2;
					}

				}
			}
			die( json_encode( array( "status" => $status ) ) );
		}

		public function responseForNonLoggedinUsers() {
			die( json_encode( array(
				"status"  => 0,
				"message" => "You are not logged in"
			) ) );
		}

		public function getAllAlerte() {
			$alerte = false;
			if ( is_user_logged_in() && get_current_user_id() ) {
				$user_id = get_current_user_id();
				$alerte  = get_user_meta( $user_id, "_alerte_real_state", false );
			}

			return $alerte;
		}

		public function changeAlerteEmailSendingStatus() {
			if ( ! wp_verify_nonce( $_REQUEST['nonce'], "real_state_add_to_alerte_nonce" ) ) { // if ajax request with invalid nonce
				exit( "No naughty business please" );
			}
			if ( empty( $_REQUEST["alerte"] ) || ! isset( $_REQUEST["alerte"] ) ) { // if file is not selected
				exit( "Empty alerte" );
			}
			$status     = 0;
			$alerte     = $_REQUEST["alerte"];
			$all_alerte = get_user_meta( get_current_user_id(), "_alerte_real_state", false );
			if ( ! empty( $all_alerte ) ) {
				foreach ( $all_alerte as $singlealerte ) {
					if ( $singlealerte["alerte_criteria"] == $alerte["alerte_criteria"] ) {
						if ( delete_user_meta( get_current_user_id(), "_alerte_real_state", $singlealerte ) ) {
							add_user_meta( get_current_user_id(), "_alerte_real_state", $alerte );
							$status = 2;
						}
					}
				}
			}
			die( json_encode( array( "status" => $status ) ) );
		}


	}

	global $MY_Factotum_Alerte;
	$MY_Factotum_Alerte = new MY_Factotum_Alerte();
}
