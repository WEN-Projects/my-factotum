<div class="col-lg-3 offset-lg-1 sidebar-col">
	<?php dynamic_sidebar( 'blog-sidebar-id' ); ?>
	<div class="widget subscription-widget">
        <h2 class="widget-title"><?php echo get_field("subscription_form_title","option"); ?></h2>
		<?php
		echo do_shortcode('[sibwp_form id=1]'); //sidebar in actualites
		?>
	</div>
</div>