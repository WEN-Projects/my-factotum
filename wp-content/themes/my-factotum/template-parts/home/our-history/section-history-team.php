<?php
$args       = array(
	"post_type"      => "my-factotum-team",
	"post_status"    => "publish",
	"order"          => "ASC",
	"posts_per_page" => - 1
); //query to get all team members
$team_query = new WP_Query( $args );
if ( $team_query->have_posts() ) { //if team is not empty
	$section_title = get_field( "team_section_title" ); //section title
	?>
    <section class="history-team">
        <div class="container">
			<?php
			echo $section_title ? "<h2>" . $section_title . "<span class='underline'></span></h2>" : ""; ?>
            <div class="row">
				<?php
				while ( $team_query->have_posts() ) {
					$team_query->the_post();
					$profile_image = get_field( "profile_image" ); //profile image of team member
					$full_name     = get_field( "full_name" ); //fullname of member
					$role          = get_field( "role" ); //role of the member
					$description   = get_field( "description" ); //description of team member
					?>
                    <div class="col-sm-4">
						<?php
						if ( ! empty( $profile_image ) ) { //if profile image is set, display the image
							echo isset( $profile_image["ID"] ) ? wp_get_attachment_image( $profile_image["ID"], "full" ) : "";
						}
						?>
                        <p><strong><?php echo $full_name; ?></strong><br><?php echo $role; ?></p>
						<?php echo $description; ?>
                    </div>
					<?php
				}
				?>
            </div>
        </div>
        <div class="colored-bg"></div>
    </section><!-- .history-team -->
	<?php
}
wp_reset_query(); //reset the post query
?>
