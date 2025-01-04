<?php
//$args = array( 'role' => array( 'mandataire' ) );
$args = array( 'meta_key' => "show_as_mandataire", 'meta_value' => true ); //query to select only all those users which has field "show as mandataire" checked in backend
if ( get_field( "mandataires_order" ) ) {
	$args['order'] = get_field( "mandataires_order" );
}
if ( get_field( "mandataires_order_by" ) ) {
	$args['orderby'] = get_field( "mandataires_order_by" );
}
$agents = get_users( $args );

if ( ! empty( $agents ) ) { //if team is not empty
	$section_title = get_field( "mandataires_section_title" ); //section title
	$section_disc  = get_field( "mandataires_section_description" ); //section Disc
	?>
    <section class="user-slide-sec">
        <div class="container">
            <h2><?php echo $section_title ? $section_title : ""; ?></h2>
            <div class="two-col-content">
				<?php echo $section_disc ? $section_disc : ""; ?>
            </div><!-- .two-col-content -->
            <div class="user-slider">
				<?php
				foreach ( $agents as $row ) {
					$user_descrption = get_field( "user_description", 'user_' . $row->ID ); // get description of the user
					$user_phone_no   = get_field( "contact_number", 'user_' . $row->ID );; // get phoneno of the user
					?>
                    <div class="user-item">
						<?php
						if ( function_exists( "my_factotum_get_user_profile_image" ) ) {
							echo my_factotum_get_user_profile_image( $row->ID );
						}
						?>
                        <p><strong><?php echo $row->display_name; ?></strong><br>
							<?php echo $user_descrption; ?></p>
                        <p>
							<?php
							if ( $user_phone_no ) {
								?>
                                <a href="tel:<?php echo $user_phone_no; ?>"><?php echo $user_phone_no; ?></a>
								<?php
							}
							?>
                            <a href="mailto:<?php echo $row->user_email; ?>"><?php echo $row->user_email; ?></a></p>
                    </div><!-- .user-item -->
					<?php
				}
				?>
            </div><!-- .user-slider -->
        </div>
    </section><!-- .user-slide-sec -->
	<?php
}
wp_reset_query(); //reset the post query
?>
