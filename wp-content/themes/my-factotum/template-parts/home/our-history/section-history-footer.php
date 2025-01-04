<?php
$trustpilot_embed = get_field( "trustpilot_embed" ); // trustpilot review to be embeded

if ( $trustpilot_embed ) {
	?>
    <section class="trust-pilot-sec">
        <div class="gray-overlay"></div>
        <div class="container">
			<?php echo $trustpilot_embed; ?>
        </div>
    </section><!-- .trust-pilot-sec -->
	<?php
}

