<section class="services-content-wrap">
    <div class="container">
		<?php echo get_field( "customer_pack_section_title" ) ? "<h2 class=\"line-title\">" . get_field( "customer_pack_section_title" ) . "</h2>" : ""; //section title ?>

		<?php
		if ( get_field( "customer_pack_text_before" ) ) { // text to be displayed before the package listings
			?>
            <div class="two-col-content">
				<?php echo get_field( "customer_pack_text_before" ); ?>
            </div><!-- .two-col-content -->
			<?php
		}
		?>

		<?php
		// Check rows exists.
		// list all the packages
		if ( have_rows( 'customer_packs' ) ):
			?>
            <div class="row counter-wrap">
				<?php
				// Loop through rows.
				while ( have_rows( 'customer_packs' ) ) :
					the_row();
					$title        = get_sub_field( 'title' ); //package title
					$hours        = get_sub_field( 'hours' ); //hours
					$features     = get_sub_field( 'package_features' ); //package features
					$product_link = get_sub_field( 'product_link' ); //package link
					$product_url  = isset( $product_link["url"] ) ? esc_url( $product_link["url"] ) : "";
					?>
                    <div class="col-sm-4">
                        <div class="counter-box">
							<?php
							echo $title ? "<h4>" . $title . "</h4>" : "";
							echo $hours ? "<div class=\"customer-number\"><span class=\"number\" data-n=\"{$hours}\">" . $hours . "</span>h</div>" : "";
							if ( have_rows( 'package_features' ) ):
								while ( have_rows( 'package_features' ) ) :
									the_row();
									$title = get_sub_field( 'feature_title' ); //feature title
									?>
                                    <p class="access-info">(+ <?php echo $title; ?> )</p>
                                    <a href="<?php echo $product_url; ?>" class="stretched-link"></a>
								<?php
								endwhile;
							endif;
							?>
                        </div><!-- .counter-box -->
                    </div>
				<?php
					// End loop.
				endwhile;
				?>
            </div>

		<?php
		endif;
		?>
		<?php
		if ( get_field( "customer_pack_text_after" ) ) { // text to be displayed after the package listings
			?>
            <div class="two-col-content">
				<?php echo get_field( "customer_pack_text_after" ); ?>
            </div><!-- .two-col-content -->
			<?php
		}
		?>
    </div>
</section><!-- .services-content-wrap -->