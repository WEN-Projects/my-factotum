<div id="rs-alerte" class="rs-alert-wrap">
    <h2><?php _e( "Alertes", "factotum" ); ?></h2>
	<?php
	//template to display all user's wishlist of realstates
	global $MY_Factotum_Alerte;
	$alerte = $MY_Factotum_Alerte->getAllAlerte();
	if ( ! empty( $alerte ) ) {
		?>
        <ul class="alert-list">
			<?php
			foreach ( $alerte as $key => $item ) {
				if ( isset( $item["alerte_criteria"] ) && isset( $item["get_email_status"] ) ) {
					$alerte_criteria = $item["alerte_criteria"];
					?>
                    <li>
                        <div class="inner">
							<?php
							if ( isset( $item["get_email_status"] ) ) {
								if ( $item["get_email_status"] == "true" ) {
									?>
                                    <p class="add-remove-frm-allow-mail"
                                       alerte_data='<?php echo json_encode( array(
										   "get_email_status" => "false",
										   "alerte_criteria"  => $alerte_criteria
									   ) ); ?>'><?php _e( "Ne pas m'alerter par mail", "factotum" ); ?></p>
									<?php
								} else {
									?>
                                    <p class="add-remove-frm-allow-mail"
                                       alerte_data='<?php echo json_encode( array(
										   "get_email_status" => "true",
										   "alerte_criteria"  => $alerte_criteria
									   ) ); ?>'><?php _e( "M’alerter par mail", "factotum" ); ?></p>
									<?php
								}
							}
							?>

                            <div class="title-btn">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <h4>
											<?php
											if ( isset( $alerte_criteria["location"] ) ) {
												if ( ! empty( $alerte_criteria["location"] ) ) {
													foreach ( $alerte_criteria["location"] as $key => $location_slug ) {
														$term = get_term_by( "slug", $location_slug, "postal-ville" );
														if ( $term ) {
															if ( isset( $term->name ) ) {
																if ( $key > 0 ) {
																	echo ", " . $term->name;
																} else {
																	echo $term->name;
																}
															}
														}
														else{
															if ( $key > 0 ) {
																echo ", ". $location_slug;
															} else {
																echo $location_slug;
															}

														}
													}
												}
											}
											?>
                                            <br>
											<?php
											if ( isset( $alerte_criteria["pieces"] ) ) {
												echo $alerte_criteria["pieces"] . __( " pièces min.", "factotum" );
												if ( isset( $alerte_criteria["min_surface"] ) || isset( $alerte_criteria["max_price"] ) ) {
													echo " / ";
												}
											}
											?>
											<?php
											if ( isset( $alerte_criteria["min_surface"] ) ) {
												echo $alerte_criteria["min_surface"] . __( " m2 min.", "factotum" );
												if ( isset( $alerte_criteria["max_price"] ) ) {
													echo " / ";
												}
											}
											?>
											<?php
											if ( isset( $alerte_criteria["max_price"] ) ) {
												echo $alerte_criteria["max_price"] . __( " € max.", "factotum" );
											}
											?></h4>
                                    </div>
                                    <div class="col-sm-5">
										<?php
										$search_url = get_field( "real_states_listing_page", "option" );
										if ( $search_url ) {
											$search_url_new = add_query_arg( $alerte_criteria, $search_url );
											?>
                                            <a class="btn"
                                               href="<?php echo esc_url( $search_url_new ); ?>"><?php _e( "Lancer la recherche", "factotum" ); ?></a>
											<?php
										}
										?>

                                    </div>
                                </div>
                            </div>
                        </div><!-- .inner -->
                        <a href="#" class="add-to-trash rs-item"
                           itemid='<?php echo json_encode( $alerte_criteria ); ?>'><?php _e( "Supprimer", "factotum" ); ?></a>
                    </li>
					<?php

				}
			}
			?>
        </ul>
		<?php
	} else {
		_e( "<h4>Votre liste est vide</h4>", "factotum" );
	}
	?>
</div>
