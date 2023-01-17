<?php
/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles
/*-----------------------------------------------------------------------------------*/

function keremiya_scripts() {
	// CSS
	wp_enqueue_style( 'style', keremiya_get_css('style'), array(), null);
	wp_enqueue_style( 'responsive', keremiya_get_css('responsive'), array(), null);
	wp_enqueue_style( 'icon', keremiya_icons_css_url(), array(), '5.5.0' );

	wp_enqueue_script( 'keremiya', keremiya_get_js('main'), array( 'jquery' ), '5.5.0', true ); // True
	wp_localize_script( 
		'keremiya', 
		'kL10n', 
		array( 
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax-nonce'),
			'more' => _k_('Daha fazla göster'),
			'less' => _k_('Daha az göster'),
			) 
		);

	if( keremiya_get_option('live_search') ):
		wp_enqueue_script( 'search', keremiya_get_js('live.search'), array(), '5.5.0', true );
		wp_localize_script( 
			'search', 
			'sL10n', 
			array( 
				'api' => keremiya_wpc_search_url(),
				'nonce' => keremiya_create_nonce('keremiya_search-nonce'),
				'area' => "#live-search",
				'button' => "#search-button",
				'more' => _k_('%s için daha fazla sonuç bul'),
				) 
			);
	endif;

	if( keremiya_get_option('on_slider') ):
		wp_enqueue_style( 'slider-css', keremiya_get_css('css/owl.carousel', false), array(), null);
		wp_enqueue_script( 'slider-js', keremiya_get_js('owl.carousel.min', false), array(), null, true );
	endif;

	wp_enqueue_script( 'tipsy', keremiya_get_js('tipsy'), array(), '5.5.0', true );

}
add_action( 'wp_enqueue_scripts', 'keremiya_scripts' );

function keremiya_default_font() {
	wp_enqueue_style( 'Noto-Sans', 'https://fonts.googleapis.com/css?family=Noto+Sans:400,700', array(), null );
}

function keremiya_icons_css_url() {
	$css = get_template_directory_uri().'/font/icon/css/keremiya-icons.css';
	
	return apply_filters('keremiya_icons_css_url', $css);
}

function keremiya_get_css($id, $minify = true) {
	$path = get_template_directory_uri().'/';
	$end = '.css';

	if( keremiya_get_option('css_minify') && $minify ) {
		$path = get_template_directory_uri().'/css/';
		$end = '.min.css';
	}

	$file = $path . $id . $end;
	
	return apply_filters("keremiya_get_css_$id", $file, $id);
}

function keremiya_get_js($id, $minify = true) {
	$path = get_template_directory_uri().'/js/';
	
	$end = '.js';
	if( keremiya_get_option('js_minify') && $minify )
		$end = '.min.js';

	$file = $path . $id . $end;
	
	return apply_filters("keremiya_get_js_$id", $file, $id);
}

/*-----------------------------------------------------------------------------------*/
# Enqueue Fonts From Google
/*-----------------------------------------------------------------------------------*/
function keremiya_enqueue_font($got_font = ''){
	if ($got_font) {
		$font_pieces = explode(':', $got_font);
			
		$font_name = $font_pieces[0];
		$font_name = str_replace (' ','+', $font_pieces[0] );
				
		$font_variants = $font_pieces[1];
		$font_variants = str_replace ('|',',', $font_pieces[1] );
				
		wp_enqueue_style( $font_name , 'http://fonts.googleapis.com/css?family='.$font_name . ':' . $font_variants );
	} else {
		keremiya_default_font();
	}
}


/*-----------------------------------------------------------------------------------*/
# Get Font Name
/*-----------------------------------------------------------------------------------*/
function keremiya_get_font ( $got_font ) {
	if ($got_font) {
		$font_pieces = explode(':', $got_font);
			
		$font_name = $font_pieces[0];

		return $font_name;
	}
}

/*-----------------------------------------------------------------------------------*/
# Get Custom Typography
/*-----------------------------------------------------------------------------------*/
add_action('wp_enqueue_scripts', 'keremiya_typography');
function keremiya_typography() {
	// GET FONT NAME
	$font_data = keremiya_get_option('font');
	// JS
	keremiya_enqueue_font( $font_data['font'] );
}

// WPLION HTML BUTON KONTROLU
if ( !function_exists( 'wplion_check' ) ) :
	/*
	 * Dönüşüm
	 */
	keremiya_return_value();
endif;

// HTML BUTONLAR
if( !function_exists('wplion_buttons') ){
    function wplion_buttons()
    { ?>
        <script type="text/javascript">
		edButtons[edButtons.length]=new edButton("ed_np","NextPage","<!--nextpage-->");
		edButtons[edButtons.length]=new edButton("ed_npt", "<?php _k("NextPage Başlık"); ?>", "<?php _k("<!--baslik:"); ?>","-->","npt");
		edButtons[edButtons.length]=new edButton('ed_jw','Player','[player file="','"]','jw');
		edButtons[edButtons.length]=new edButton('ed_yt','Youtube','[youtube id="','"]','yt');
		edButtons[edButtons.length]=new edButton('ed_note',"<?php _k("Not"); ?>",'[note color="grey"]','[/note]','note');
		edButtons[edButtons.length]=new edButton('ed_tab',"<?php _k("Tab"); ?>",'[tab:',']','tab');

        </script>
    <?php }
    add_action('admin_print_footer_scripts',  'wplion_buttons');
}

/*-----------------------------------------------------------------------------------*/
# CSS
/*-----------------------------------------------------------------------------------*/

add_action('wp_head', 'keremiya_wp_head');
function keremiya_wp_head() {

	// Script HTML Tag
	echo "\n", '<script type="text/javascript">', "\n";

	$header_fixed = keremiya_get_option('header_fixed');
	$sticky_sidebar = keremiya_get_option('sticky_sidebar');
	$admin_bar = keremiya_get_option('admin_bar');

	// CDATA Start
	echo "/* <![CDATA[ */\n";

	// Header Fixed ON
	if( ! $header_fixed )
		echo "var offtop = 0;\n";
	elseif ( $admin_bar )
		echo "var offtop = 80;\n";
	else
		echo "var offtop = 50;\n";

	// Sticky Sidebar
	if( $sticky_sidebar )
		echo "var sticky_sidebar = true;\n";
	else
		echo "var sticky_sidebar = false;\n";

	// CDATA End
	echo "/* ]]> */\n";

	// Script End
	echo '</script>';

	// Style HTML Tag
	echo "\n", '<style type="text/css" media="screen">', "\n";

	if( ! $header_fixed ) {
		echo ".sticky { position: relative !important; top: 0 !important; }\n";
		echo "#sticky-sidebar { top: 0 !important; }\n";
	}

	// Theme Width
	$theme_width = keremiya_get_option('theme_width');

	if($theme_width):
	// Get Skin
	$skin = keremiya_get_option('theme_skin');
	// Get Padding
	$padding = keremiya_get_option('theme_padding');

	if($skin == 'boxed' || $skin == 'oval')
		echo ".boxed #wrap, .oval #wrap, #header.sticky .wrapper, #navbar.sticky .wrapper, #splash .wrapper { width: {$theme_width}px }\n";

	if($skin != 'full')
		echo ".wrapper { width: {$theme_width}px; }\n";
	
	if($skin != 'modern')
		echo ".wrapper { padding: 0px {$padding}px;}\n";
	
	if ( ! function_exists( 'is_wls' ) ) {
		exit;
	}

	endif; // Theme Width

	if ( $admin_bar && is_user_logged_in() && current_user_can('administrator') ) {
		
		if($skin == 'oval')
			$top = '32px';
		else
			$top = 'auto';

		echo ".animate { top: $top !important; } #navbar.sticky { top: 84px !important; }\n";
		echo "@media only screen and (max-width: 782px) { #navbar.sticky { top: 110px !important; } }\n";
	}

	// Custom Background for Body
	$background_type = keremiya_get_option('background_type');

	if( $background_type == 'pattern' ) {
		// Pattern Data
		$background_pattern = keremiya_get_option('background_pattern');
		$background_pattern_color = keremiya_get_option('background_pattern_color');

		if( $background_pattern )
			echo "body {background: $background_pattern_color url(".get_template_directory_uri()."/images/patterns/$background_pattern.png) center;\n";

	} elseif( $background_type == 'custom' ) {
		// BG Data
		$bg = keremiya_get_option( 'background' );

		if( $bg )
			echo "body{ background: {$bg['color']} url('{$bg['img']}') {$bg['repeat']} {$bg['attachment']} {$bg['hor']} {$bg['ver']}; }\n";

 	}; // background_type

 	/**
 	* FONT COLORS
 	*/
 	$global_color = keremiya_get_option( 'global_color' );

 	if( $global_color )
 		echo "body, .movie-release{ color: $global_color; }\n";

 	// Link Color
 	$links_color = keremiya_get_option( 'links_color' );
 	$links_color_hover = keremiya_get_option( 'links_color' );

 	if( $links_color )
 		echo "a, .movie-title a{ color: $links_color; }\n";

 	if( $links_color_hover )
 		echo "a:hover{ color: $links_color_hover; }\n";

 	// Header Links Color
 	$header_links_color = keremiya_get_option( 'header_links_color' );
 	$header_links_color_hover = keremiya_get_option( 'header_links_color_hover' );

 	if ( $header_links_color ) {
 		echo "#nav li a, #nav li a:link, #nav li a:visited{ color: $header_links_color; }\n";
 		echo "#nav li li a, #nav li li a:link, #nav li li a:visited{ color: $header_links_color; }\n";
 		echo "#nav li.menu-item-has-children:after{ color: $header_links_color; opacity: .7; }\n";
 	}

 	if ( $header_links_color_hover ) {
 		echo "#nav li a:hover{ color: $header_links_color_hover; }\n";
 		echo "#nav li li a:hover{ color: $header_links_color_hover; }\n";
 		echo "#nav li.menu-item-has-children:hover:after{ color: $header_links_color_hover; opacity: .7; }\n";
 	}

 	$header_icon_color = keremiya_get_option( 'header_icon_color' );
 	
 	if( $header_icon_color )
 		echo ".header-social-icons a, .header-social-icons a:hover{ color: $header_icon_color; }\n";
 	
 	// Footer Links Color
 	$footer_color = keremiya_get_option( 'footer_color' );
 	$footer_links_color = keremiya_get_option( 'footer_links_color' );
 	$footer_links_color_hover = keremiya_get_option( 'footer_links_color_hover' );

 	if( $footer_color )
 		echo "#footer, .footer-info{ color: $footer_color; }\n";

 	if( $footer_links_color )
 		echo "#footer a, #footer a:visited, #footer .footer-menu a{ color: $footer_links_color; }\n";

 	if( $footer_links_color_hover )
 		echo "#footer a:hover, #footer .footer-menu a:hover{ color: $footer_links_color_hover; }\n";

 	/**
 	* LOGO COLOR
 	*/
 	$logo_color = keremiya_get_option( 'logo_color' );
 	$logo_span_color = keremiya_get_option( 'logo_span_color' );

 	if( $logo_color ) {
 		echo "a.logo-text{ color: $logo_color; }\n";
 		echo ".menu-toogle, .search-toogle{ color: $logo_color; }\n";
 	}

 	if( $logo_span_color )
 		echo "a.logo-text span{ color: $logo_span_color; }\n";

 	/**
 	* ÇİZGİLER
 	*/
 	$global_line_color = keremiya_get_option( 'global_line_color' );
 	$sidebar_line_color = keremiya_get_option( 'sidebar_line_color' );

 	if( $global_line_color ) {
 		echo "h1.title span, h2.title span, h4.title span { border-bottom: 2px solid $global_line_color; }\n";
 		echo "#nav li.current-menu-item a:before, #nav li.current-menu-parent a:before{ background: $global_line_color; }\n";
 		echo "#nav li li:hover > a, #nav li li.current-menu-item > a{ border-left: 1px solid $global_line_color; }\n";
 		echo ".c-sidebar.list-categories .tags a.active, .c-sidebar.list-categories .tags li.active a, .c-sidebar.list-categories .tags li.current-cat a { color: $global_line_color; }\n";
 	}

 	if( $sidebar_line_color )
 		echo ".top span{ border-bottom: 2px solid $sidebar_line_color; }\n";

 	/**
 	* GENİŞ SİDEBAR
 	*/

 	$large_sidebar = keremiya_get_option('large_sidebar');

 	if( $large_sidebar ) {
 		echo ".single-content.detail, .content-right .single-content.sidebar { width:100%; }.content-left { width: 70%; }.content-right { width: 30%; }\n";
 	}

 	/**
 	* BACKGROUNDS
 	*/

 	// HEADER
 	$header_bg_type = keremiya_get_option( 'header_background_type' );

 	if( $header_bg_type == 'custom' ) {
 		// bg data
 		$header_bg = keremiya_get_option( 'header_background' );

 		if( !empty( $header_bg['img']) || !empty( $header_bg['color'] ) ):
 		echo '#header {';
 			if( empty($header_bg['img']) && $header_bg['gradient_color'] ) {
 			echo "
 				background: -moz-linear-gradient(top, {$header_bg['color']}) 0%, {$header_bg['gradient_color']} 100%); /* FF3.6+ */
    			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, {$header_bg['color']}), color-stop(100%,{$header_bg['gradient_color']})); /* Chrome,Safari4+ */
    			background: -webkit-linear-gradient(top, {$header_bg['color']} 0%,{$header_bg['gradient_color']} 100%); /* Chrome10+,Safari5.1+ */
    			background: -o-linear-gradient(top, {$header_bg['color']} 0%,{$header_bg['gradient_color']} 100%); /* Opera 11.10+ */
    			background: -ms-linear-gradient(top, {$header_bg['color']} 0%,{$header_bg['gradient_color']} 100%); /* IE10+ */
    			background: linear-gradient(to bottom, {$header_bg['color']} 0%,{$header_bg['gradient_color']} 100%); /* W3C */
    			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$header_bg['color']}', endColorstr='{$header_bg['gradient_color']}',GradientType=0 ); /* IE6-9 */
 			";
 			} else {
 			echo "background: {$header_bg['color']} url({$header_bg['img']}) {$header_bg['repeat']} {$header_bg['attachment']} {$header_bg['hor']} {$header_bg['ver']};";
	 		}
	 	echo "}\n";
	 	echo "#search-form{opacity: .75}\n";
 		endif;

	 	// MENU
	 	$menu_bg = keremiya_get_option( 'menu_background' );

	 	if( $menu_bg ) {
	 		echo "#navbar{ background: $menu_bg !important; border: 0; }\n";
	 		echo "#nav li ul{ background: $menu_bg !important; box-shadow: -3px 4px 4px -2px rgba(17, 17, 17, 0.81), inset 1px 0px 0px rgba(34, 34, 34, 0.56); }\n";
	 		echo ".navbar-in-border{ border-top: 1px solid rgba(54, 54, 54, 0.4); border-bottom: 1px solid rgba(54, 54, 54, 0.4); }\n";
	 		echo "#nav li.current-menu-item a:before, #nav li.current-menu-parent a:before{ border-top: 1px solid rgba(0, 0, 0, 0.5); }\n";
	 		echo "#nav li a:after{ border-left: 1px solid rgba(20, 20, 20, 0.4);border-right: 1px solid rgba(34, 34, 34, 0.05); }\n";
	 		echo "#nav li:first-child > a{ box-shadow: inset 1px 0px 0px rgba(20, 20, 20, 0.36), inset 2px 0px 0px rgba(34, 34, 34, 0.05); }\n";
	 	}
 	}

 	// FOOTER
 	$footer_bg_type = keremiya_get_option('footer_background_type');

 	if( $footer_bg_type == 'custom' ) {
 		// bg data
 		$footer_bg = keremiya_get_option( 'footer_background' );

 		if( !empty( $footer_bg['img']) || !empty( $footer_bg['color'] ) ):
 			echo "#footer{ background:{$footer_bg['color']} url('{$footer_bg['img']}') {$footer_bg['repeat']} {$footer_bg['attachment']} {$footer_bg['hor']} {$footer_bg['ver']};}\n";
 		endif;

 		// MENU
	 	$footer_menu_bg = keremiya_get_option( 'footer_menu_background' );

	 	if( $footer_menu_bg ) {
	 		echo ".footer-menu{ background: $footer_menu_bg !important; }\n";
	 		echo ".footer-menu li:after{ opacity: .7; }\n";
	 	}
 	}

 	/**
 	* CUSTOM FONT
 	*/
	$font_data = keremiya_get_option('font');

 	if( $font_data['font'] )
		echo ".top, .profile-tabs, #nav li a, .movie-preview, .series-preview, .popup, #footer, body, textarea, input, select, h1, h2, h3, h4, h5 { font-family: '".keremiya_get_font($font_data['font'])."', arial, sans-serif !important; }\n";

 	/**
 	* CUSTOM CSS
 	*/
 	$custom_css = keremiya_get_option('css');

 	if( $custom_css )
		echo $custom_css , "\n";

	// Style End
	echo '</style>', "\n";

	// CUSTOM CSS
	$header_code = keremiya_get_option('header_code');

	if( $header_code )
		echo htmlspecialchars_decode( keremiya_get_option('header_code') ) , "\n";
}
?>