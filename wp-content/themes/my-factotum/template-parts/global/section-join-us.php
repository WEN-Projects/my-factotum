<?php
$description = get_field( "join_us_description" ); // description to be displayed

if ( $description ) {
	?>
	<section class="cta-block">
		<div class="cta-back-overlay"></div>
		<div class="container">
			<?php
			echo $description;

			$link = get_field( "join_us_button_link" ); //join us button link details
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
	</section><!-- .cta-block -->
	<?php
}


