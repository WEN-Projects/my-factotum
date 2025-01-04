<?php
$count = 0;
if ( have_rows( 'prestataires_list' ) ) {
	while ( have_rows( 'prestataires_list' ) ) {
		the_row();
		$title            = get_sub_field( 'title' );
		$content          = get_sub_field( 'content' );
		$image            = get_sub_field( "featured_image" );
		$image_url        = isset( $image['url'] ) ? $image['url'] : "";
		$show_contact_btn = get_sub_field( "show_button" );
		$contact_btn      = get_sub_field( "button" );
		?>
        <section id="prestataires-popup-content-<?php echo $count; ?>" class="pop-immovable-sec prestataires-popup">
            <a href="javascript:void(0);" class="icon-close">
                <span></span>
                <span></span>
            </a>
            <div class="container-fluid">
                <div class="row no-gutters">
                    <div class="col-sm-6 img-col match-height"
                         style="background: url('<?php echo $image['url']; ?>') no-repeat center; background-size: cover;">
                    </div>
                    <div class="col-sm-6 content-col match-height">
                        <div class="inner">
							<?php
							echo $title ? "<h2>" . $title . "</h2>" : "";
							echo $content;
							if ( ! empty( $show_contact_btn ) && ! empty( $contact_btn ) ) {
								$url       = isset( $contact_btn["url"] ) ? esc_url( $contact_btn["url"] ) : "";
								$btn_title = isset( $contact_btn["title"] ) ? $contact_btn["title"] : "";
								?>
                                <a class="btn transparent" href="<?php echo $url; ?>"><?php echo $btn_title; ?></a>
								<?php
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		<?php
		$count ++;
	}
}
?>


<?php
if ( have_rows( 'prestataires_list' ) ) { //if have providers, list them
	$section_title       = get_field( "prestataires_title" ); //section title description
	$section_description = get_field( "prestataires_description" ); //section title description
	?>
    <section id="section-select-prestataires" class="providers-sec dark-bg">
        <div class="container">
            <h2><?php echo $section_title ? $section_title : ""; ?></h2>
            <div class="two-col-content">
				<?php echo $section_description ? $section_description : ""; ?>
            </div><!-- .two-col-content -->
            <div class="row prestataires-list-container">
				<?php
				$count = 0;
				while ( have_rows( 'prestataires_list' ) ) {
					the_row();
					$title     = get_sub_field( 'title' );
					$image     = get_sub_field( "featured_image" );
					$image_url = isset( $image['url'] ) ? $image['url'] : "";
					?>
                    <div class="col-lg-6">
                        <div class="provider-box" style="background: url('<?php echo $image_url; ?>') no-repeat center ; background-size: cover;">
                            <div class="inner">
	                            <?php echo $title ? "<h2>" . $title . "</h2>" : ""; ?>
                                <span class="link">+</span>
                            </div><!-- .inner -->
                            <a id="prestatair-<?php echo $count; ?>" href="javascript:void(0)" class="stretched-link"></a>
                        </div><!-- .provider-box -->
                    </div>
					<?php
					$count++;
				}
				?>
            </div>
        </div>
    </section><!-- .history-team -->
	<?php
}
?>
