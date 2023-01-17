<?php

if( get_option('keremiya_active') ) {
	if( !get_option('keremiya_upgrade') ) {
		$update_options = array(
			'keremiya_logo' => 'logo',
			'keremiya_favicon' => 'favicon',
			'keremiya_email' => 'email',
			'keremiya_twitter_id' => 'twitter',
			'keremiya_facebook_id' => 'facebook',
			'keremiya_footer_left' => 'footer_left',
			'keremiya_footer_right' => 'footer_right',
			'keremiya_seo_home_titletext' => 'seo_home_title',
			'keremiya_seo_home_description' => 'seo_home_description',
			'keremiya_seo_home_keywords' => 'seo_home_keywords',
		);

		//global $default_data;
		$current_options = get_option('keremiya_options');
	
		foreach( $update_options as $old_option => $current_option ){
			if( get_option( $old_option ) && ! keremiya_get_option($current_option)){

				$current_options[$current_option] = get_option( $old_option );
				update_option('keremiya_options', $current_options);
			}
		}
		update_option('keremiya_upgrade', 1);
	}
}

if( get_option('keremiya_active') == '1' ) {

	$current_options = get_option('keremiya_options');
	$update_options = array(
		'excerpt_hide' 			=> 		'hide',
		'showmore' 				=> 		true,
		'category_list_lang'	=>		true,
		'category_list_year'	=>		true,
		'add_post'				=>		true,
		'post_status'			=>		'pending',
		'imdb_importer'			=>		true,
		'auto_thumbnail'		=>		true,
		'admin_bar'				=>		true,
	);

		foreach( $update_options as $option => $value ){
			if( ! keremiya_get_option($option) ){

				$current_options[$option] = $value;
				update_option('keremiya_options', $current_options);
			}
		}

	update_option('keremiya_active', '2');
}

if( get_option('keremiya_active') == '2' ) {

	$current_options = get_option('keremiya_options');
	$update_options = array(
		'today_movie_on' 		=> 		true,
		'news_on' 				=> 		true,
	);

		foreach( $update_options as $option => $value ){
			if( ! keremiya_get_option($option) ){

				$current_options[$option] = $value;
				update_option('keremiya_options', $current_options);
			}
		}

	update_option('keremiya_active', '3');
}

if( get_option('keremiya_active') == '3' ) {

	$current_options = get_option('keremiya_options');
	$update_options = array(
		'slider_number' 		=> 		'14',
		'slider_style' 			=> 		'1',
		'tax_columns'			=> 		'5',
		'tax_movies_number' 	=> 		'10',
		'css_minify'			=>		true,
		'js_minify'				=>		true,
		'tabs_linktype'			=>		'hideshow',
		'banner_splash_time' 	=> 		'10',
	);

		foreach( $update_options as $option => $value ){
			if( ! keremiya_get_option($option) ){

				$current_options[$option] = $value;
				update_option('keremiya_options', $current_options);
			}
		}

	update_option('keremiya_active', '4');
}


?>