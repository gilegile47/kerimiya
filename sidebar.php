<?php 
do_action('keremiya_sidebar_before');
	if(is_front_page() && is_active_sidebar('home')) {
		dynamic_sidebar('home');
	}elseif(is_single()) {
		if(is_active_sidebar('single')) 
			dynamic_sidebar('single');
	}elseif(is_page() && is_active_sidebar('page')) {
		dynamic_sidebar('page');
	}elseif(is_category() && is_active_sidebar('category')) {
		dynamic_sidebar('category');
	}elseif(is_author()) {
		dynamic_sidebar('author');
	}else {
		dynamic_sidebar('main');
	}
?>