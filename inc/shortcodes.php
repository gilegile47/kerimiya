<?php

// Custom Player Replace
function keremiya_custom_player($url, $image, $autoplay) {
	
	$pattern = array("/%URL%/", "/%IMAGE%/", "/%AUTOPLAY%/");
	$replacement = array($url, $image, $autoplay);

	$player = preg_replace($pattern, $replacement, keremiya_get_option('custom_player'));

	return htmlspecialchars_decode($player);
}

// Keremiya Player
function keremiya_player($atts) {
extract(shortcode_atts(array(
	'id' => '',
    'file' => '',
    'image' => ''
  ), $atts));

	$player = '<div class="embed-container mg10">';
	$player .= keremiya_custom_player($file, $image, keremiya_get_option('player_autoplay'));
	$player .= '</div>';

return $player;
}
add_shortcode('player', 'keremiya_player');

// Youtube Player
function keremiya_youtube($atts) {
extract(shortcode_atts(array(
    'id' => ''
  ), $atts));
	
	$player = "<iframe width=\"711\" height=\"400\" src=\"http://www.youtube.com/embed/$id\" frameborder=\"0\" allowfullscreen></iframe>";

return $player;
}
add_shortcode('youtube', 'keremiya_youtube');

// Note Shortcode
function note_shortcode( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'color' => 'grey',
	), $atts );

	$t = esc_attr($a['color']) . '-' . rand(0, 99);
	return '
		<script type="text/javascript">
			jQuery( document ).ready(function( $ ) {
				var n = $(".'. $t .'"),
					c = $(".video-content");

				c.prepend(n);
			});
		</script>
		<span id="note" class="' . esc_attr($a['color']) .' '. $t .'">' . $content . ' <i class="icon-cancel remove"></i></span>
	';
}
add_shortcode( 'note', 'note_shortcode' );


?>