<?php
$description  = get_field( "join_us_descritpion" ); // description to be displayed
$banner_image = get_field( "join_us_section_background_image" ); //banner type
$imageUrl     = isset( $banner_image["id"] ) ? wp_get_attachment_image_url( $banner_image["id"], 'full' ) : "";
if ( $description ) {
	?>
    <section class="cta-block" style="background: url('<?php echo $imageUrl; ?>') no-repeat center; background-size: cover;">
        <div class="cta-back-overlay"></div>
        <div class="container">
			<?php
			echo $description;
			$link = get_field( "joins_us_button" ); //join us button link details
			if ( ! empty( $link ) ) {
				$url       = isset( $link["url"] ) ? esc_url( $link["url"] ) : "";
				$btn_title = isset( $link["title"] ) ? $link["title"] : "";
				?>
                <div class="btn-container">
                    <div class="btn-wrap">
                        <a class="btn transparent" href="<?php echo $url; ?>"><span class="text"><?php echo $btn_title; ?></span></a>
                    </div><!-- .btn-wrap -->
                </div><!-- .btn-container -->
				<?php
			}
			?>
        </div>
    </section><!-- .cta -->
	<?php
}
?>