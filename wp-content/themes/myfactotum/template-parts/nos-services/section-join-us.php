<?php
$description = get_field( "join_us_description" ); // description to be displayed

if ( $description ) {
	?>
    <section class="cta-block">
        <div class="container">
			<?php
			echo $description;
			$link = get_field( "join_us_button_link" ); //join us button link details
			if ( ! empty( $link ) ) {
				$url       = isset( $link["url"] ) ? esc_url( $link["url"] ) : "";
				$btn_title = isset( $link["title"] ) ? $link["title"] : "";
				?>
                <a class="btn transparent" href="<?php echo $url; ?>"><?php echo $btn_title; ?></a>
				<?php
			}
			?>
        </div>
    </section><!-- .cta-block -->
	<?php
}

