<?php
$banner_type        = get_field( "banner_type" ); //banner type
$banner_text        = get_field( "banner_text" ); //text overlay on banner
$banner_button_text = get_field( "banner_button_text" ); //button text at bottom

if ( $banner_type == "video" ) { // if the banner type is video
	$banner_video = get_field( "banner_video" ); //banner type
	if ( ! empty( $banner_video ) ) {
		?>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
					<?php echo $banner_text; ?>
                </div><!-- .hero-content -->
            </div>
            <div class="video-bg">
                <video autoplay loop muted playsinline class="wrapper__video" data-paroller-factor="-0.6"
                       data-paroller-type="foreground" data-paroller-direction="vertical">
                    <source src="<?php echo $banner_video['url']; ?>">
                </video>
            </div>
            <div class="scroll-to-wrap">
                <a href="#<?php _e( 'notre-histoire', 'factotum' ); //url slug to be translation ready ?>"
                   class="scroll-to"><?php echo $banner_button_text; ?><br>
                    <span class="arrow"></span>
                </a>
            </div><!-- .scroll-to-wrap -->
        </section>
		<?php
	}
} else {// if the banner type is image
	$banner_image = get_field( "banner_image" ); //banner type
	if ( ! empty( $banner_image ) ) {
		$imageUrl = wp_get_attachment_image_url( $banner_image["id"], 'full' ); ?>
        <section class="hero"
                 style="background: url('<?php echo $imageUrl; ?>') no-repeat center; background-size: cover;">
            <div class="container">
                <div class="hero-content">
					<?php echo $banner_text; ?>
                </div><!-- .hero-content -->
            </div>
            <div class="scroll-to-wrap">
                <a href="#<?php _e( 'notre-histoire', 'factotum' ); //url slug to be translation ready ?>"
                   class="scroll-to"><?php echo $banner_button_text; ?><br>
                    <span class="arrow"></span>
                </a>
            </div><!-- .scroll-to-wrap -->
        </section>
		<?php
	}
}
?>