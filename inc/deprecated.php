<?php
/*
 * DEPRECATED
 * Keremiya deprecated functions
 */

// common-scripts.php # keremiya_get_css();
function keremiya_css_url() {
	$css = get_template_directory_uri().'/style.css';
	
	if( keremiya_get_option('css_minify') )
		$css = get_template_directory_uri().'/css/style-min.css';

	return apply_filters('keremiya_css_url', $css);
}

// common-scripts.php # keremiya_get_css();
function keremiya_responsive_css_url() {
	$css = get_template_directory_uri().'/responsive.css';
	
	if( keremiya_get_option('css_minify') )
		$css = get_template_directory_uri().'/css/responsive-min.css';

	return apply_filters('keremiya_responsive_css_url', $css);
}

?>