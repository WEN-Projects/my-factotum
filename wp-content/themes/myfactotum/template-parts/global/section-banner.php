<?php
$banner_type         = get_field( "banner_type" ); //banner type
$banner_title        = get_field( "banner_title" ); //Title overlay on banner
$banner_text         = get_field( "banner_text" ); //text overlay on banner
$banner_text_columns = get_field( "banner_text_columns" ); //text columns
$banner_size         = get_field( "section_banner_size" ); // banner section (size 0 or 1)

$section_class = $banner_size == 1 ? "hero shorter-hero" : "hero";
if ( $banner_type == "video" ) { // if the banner type is video
	$banner_video_source = get_field( "banner_video_source" ); //banner type
	if ( $banner_video_source == "vimeo" ) {
		$vimeo_video_id = get_field( "banner_vimeo_video_id" );
		$video_url      = esc_url( "https://player.vimeo.com/video/" . $vimeo_video_id . "?autoplay=1&muted=1&showinfo=0&controls=0&loop=1" ); // if viemo link is selected then we need a vimeo player to embed the vimeo link
	} else {
		$youtube_video_id = get_field( "banner_youtube_video_id" );
//		$video_url        = esc_url( "https://www.youtube.com/embed/" . $youtube_video_id . "??rel=0&autoplay=1&mute=1&showinfo=0&controls=0&loop=1&playlist=".$youtube_video_id ); // if youtube link is selected
	}
	?>
    <section class="<?php echo $section_class; ?>">
        <div class="container">
			<?php
			if ( $banner_size == 1 ){
			?>
            <div class="row">
                <div class="col-lg-12">
					<?php
					}
					?>

                    <div class="hero-content">
						<?php
						echo $banner_title ? '<h1 class="banner-title">' . $banner_title . '</h1>' : '';
						?>

						<?php if ( $banner_text ) { ?>
							<?php
							if ( $banner_text_columns == 2 ) {
								?>
                                <div class="two-col-content">
									<?php echo $banner_text; ?>
                                </div>
								<?php
							} else {
								?>
								<?php echo $banner_text; ?>
								<?php
							}
							?>

						<?php } ?>
                    </div><!-- .hero-content -->
					<?php
					if ( $banner_size == 1 ){
					?>
                </div>
            </div>
		<?php
		}
		?>

        </div>
        <div class="video-bg">
			<?php
			if ( $banner_video_source == "vimeo" ) {
				?>
                <div class="video-external">
                    <iframe id="banner-video-iframe" class="ytplayer" width="100%" height="100%"
                            src="<?php echo $video_url; ?>" frameborder="0"
                            allow="autoplay; fullscreen; encrypted-media"
                            allowfullscreen></iframe>
                </div>
			<?php
			} else {
			?>
                <div id="header-banner-youtube" class="video-external" data-ytID="<?php echo $youtube_video_id; ?>">
                    <div  id="banner-video-player-youtube"></div>
                </div>
                <script>
                    // 2. This code loads the IFrame Player API code asynchronously.

                    var banner_youtube_video_id = "<?php echo $youtube_video_id; ?>";
                </script>
				<?php
			}
			?>
        </div>
		<?php
		if ( get_field( "banner_show_scroll_button" ) ) { //only show scroll button if home banner
			$banner_button_text = get_field( "banner_scroll_button_text" ); //button text at bottom
			?>
            <div class="scroll-to-wrap smooth-scroll">
                <a href="#<?php echo get_field( "banner_scroll_btn_target" );
				?>"
                   class="scroll-to"><?php echo $banner_button_text; ?><br>
                    <span class="arrow"></span>
                </a>
            </div><!-- .scroll-to-wrap -->

			<?php
		}
		?>
    </section>
	<?php
} else {// if the banner type is image
	$banner_image = get_field( "banner_image" ); //banner type
	if ( ! empty( $banner_image ) ) {
		$imageUrl = wp_get_attachment_image_url( $banner_image["id"], 'full' ); ?>
        <section class="<?php echo $section_class; ?>"
                 style="background: url('<?php echo $imageUrl; ?>') no-repeat center; background-size: cover;">
            <div class="container">
				<?php
				if ( $banner_size == 1 ){
				?>
                <div class="row">
                    <div class="col-lg-12">
						<?php
						}
						?>
                        <div class="hero-content">
							<?php
							echo $banner_title ? '<h1 class="banner-title">' . $banner_title . '</h1>' : '';
							?>
							<?php if ( $banner_text ) { ?>
								<?php
								if ( $banner_text_columns == 2 ) {
									?>
                                    <div class="two-col-content">
										<?php echo $banner_text; ?>
                                    </div>
									<?php
								} else {
									?>
									<?php echo $banner_text; ?>
									<?php
								}
								?>

							<?php } ?>
                        </div><!-- .hero-content -->
						<?php
						if ( $banner_size == 1 ){
						?>
                    </div>
                </div>
			<?php
			}
			?>
            </div>
			<?php
			if ( get_field( "banner_show_scroll_button" ) ) { //only show scroll button if home banner
				$banner_button_text = get_field( "banner_scroll_button_text" ); //button text at bottom
				?>
                <div class="scroll-to-wrap smooth-scroll">
                    <a href="#<?php echo get_field( "banner_scroll_btn_target" );
					?>"
                       class="scroll-to"><?php echo $banner_button_text; ?><br>
                        <span class="arrow"></span>
                    </a>
                </div><!-- .scroll-to-wrap -->
				<?php
			}
			?>
        </section>
		<?php
	}
}
?>