<?php
$history_video_source    = get_field( "history_video_source" ); // video source whether vimero or youtube
$history_video_thumbnail = get_field( "history_video_thumbnail" ); // video thumbnail
$video_text_overlay      = get_field( "history_video_text_overlay" ); // text to be displayed on the video
if ( isset( $history_video_thumbnail["ID"] ) ) {
	if ( $history_video_source ) {
		?>
        <section class="video-about">
			<?php
			if ( $history_video_source == "vimeo" ) { //if video source is vimeo
				$vimeo_video_id = get_field( "history_vimeo_video_id" ); // vimeo video id
				$video_url      = esc_url( "https://player.vimeo.com/video/" . $vimeo_video_id . "?&muted=0&showinfo=0&controls=0&loop=1" ); // if viemo link is selected then we need a vimeo player to embed the vimeo link
				?>
                <div class="container">
                    <div class="video-bg video-frame-wrap vimeo-video">
                        <div class="hero-content">
                            <a href="javascript:void(0);" id="vimeoPlayButton" class="trigger-video vimeoPlayButton">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/play-icon.svg" alt="Play Icon">
                            </a>
                            <div class="video-caption"><?php echo $video_text_overlay ? $video_text_overlay : ""; ?></div>
                        </div>
                        <div class="video-poster vimeo-thumbnail">
							<?php
							echo wp_get_attachment_image( $history_video_thumbnail["ID"], "full" ); //video thumbnail
							?>
                        </div><!-- .video-poster -->
                        <div class="video-external" style="display: none">
                            <iframe id="banner-video-iframe" class="ytplayer" width="100%"
                                    height="100%"
                                    src="<?php echo $video_url; ?>" frameborder="0"
                                    allow="autoplay; fullscreen; encrypted-media"
                                    allowfullscreen></iframe>
                        </div>
                    </div><!-- .video-bg -->
                </div>
                <script>
                    //click to play vimeo player on nos rejoindre page
                    jQuery(function ($) {
                        $(document).ready(function () {
                            var iframe = document.querySelector('.vimeo-video .video-external iframe');
                            var player = new Vimeo.Player(iframe);

                            $('#vimeoPlayButton').on("click", function () { // on click on play button trigger play on vimeo video
                                player.play();
                            });

                            player.on('play', function () {
                                $('#vimeoPlayButton').hide(); // on video played hide the player button
                                if (!$('video-about .vimeo-thumbnail').is(":hidden")) { // on video played, if video thumbnail not hidden
                                    $(".video-about .vimeo-thumbnail").hide(); // on video played, if not hidden, hide the player video thumbnail
                                    $(".video-about .vimeo-video .video-external").show();
                                    $(".video-caption").hide(); // on video played, hide video caption
                                }
                            });

                            $('.video-about').on("click", function () { // on click on video frame if video is being played then, pause the video
                                //If URL contains "mystring"
                                if ($('#vimeoPlayButton').is(":hidden")) {
                                    player.pause();
                                    $('#vimeoPlayButton').show();
                                }
                            });

                            $(".video-about #vimeoPlayButton").on("click", function () {
                                player.play();
                            });
                        });
                    });
                </script>
			<?php
			}
			else {
			$youtube_video_id = get_field( "history_youtube_video_id" ); // if video source is youtube
			?>
                <div class="container play-button-container">
                    <div class="video-bg video-frame-wrap youtube-video">
                        <div class="hero-content">
                            <a href="javascript:void(0);" id="youtubePlayButton" class="trigger-video youtubePlayButton">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/play-icon.svg" alt="Play Icon"></a>
                            <div class="video-caption"><?php echo $video_text_overlay ? $video_text_overlay : ""; ?></div>
                        </div><!-- .hero-content -->
                        <div class="video-poster youtube-thumbnail">
		                    <?php
		                    echo wp_get_attachment_image( $history_video_thumbnail["ID"], "full" );
		                    ?>
                        </div><!-- .video-poster -->
                        <div class="video-external youtube-video" data-ytID="<?php echo $youtube_video_id; ?>">
                            <div id="history-youtube-video-player"></div>
                        </div>
                    </div><!-- .video-bg -->
                </div>
                <script>
                    // 2. This code loads the IFrame Player API code asynchronously.
                    // youtube api handled dynamically from youtube-api-customization.js
                    var history_youtube_video_id = "<?php echo $youtube_video_id; ?>";
                </script>
				<?php
			}
			?>

        </section><!-- .video-about -->
		<?php
	}
}
?>