<?php

function keremiya_seo() {

if(!keremiya_get_option('seo_active') || keremiya_is_active_seo_plugins() )
	return;

	keremiya_description();
	keremiya_keywords();
	keremiya_canonical();
	keremiya_og();
	keremiya_web_verifacation();

	remove_action('wp_head', 'rel_canonical');
}

// Site Meta Başlıkları
function keremiya_titles() {

	if(!keremiya_get_option('title_format') || !keremiya_get_option('seo_active')) {
		echo wp_title( '|', true, 'right' ).get_bloginfo("name");
		return;
	}

	global $wp_locale;
	$m = get_query_var('m');
	$year = get_query_var('year');
	$monthnum = get_query_var('monthnum');
	$day = get_query_var('day');
	$search = get_query_var('s');

	$sep = " | ";
	$t_sep = " ";

	#Anasayfa Başlığı
	if (is_home() || is_front_page()) {
		if (keremiya_get_option('seo_home_title')) { 
			$title = keremiya_get_option('seo_home_title');  
		} else {
			$title = get_bloginfo("name");
		}
	}

	#İçerik ve Sayfa Başlığı
	if (is_single() || is_page()) { 
		global $post; 
		$postid = $post->ID; 
		$title = get_post_meta($postid, 'keremiya_seotitle', true);
		// post meta yoksa
		if ( empty($title) ) $title = get_the_title($post->ID);

		$subject = keremiya_get_option("page_title_format");
		$pattern = array("/{blog_title}/", "/{page_title}/");
		$replacement = array(get_bloginfo("name"), $title);
		$blog_title = 1;
	}

	if (is_category() || is_tag()) {
		$cat = get_query_var('cat');
		if($cat) {
	    	$description = category_description($cat);
	   		$pattern = array("/{blog_title}/", "/{blog_description}/", "/{category_title}/", "/{category_description}/");
			$subject = keremiya_get_option("category_title_format");
			
			$cat_data = get_option("category_$cat");
			if (isset($cat_data['seo_title'])){
				$title = $cat_data['seo_title'];
			} else {
				$title = single_term_title("", false);
			}
		}

		$tag = get_query_var('tag');
		if($tag) {
			$description = tag_description($tag);
			$pattern = array("/{blog_title}/", "/{blog_description}/", "/{tag_title}/", "/{tag_description}/");
			$subject = keremiya_get_option("tag_title_format");
			
			$cat_data = get_option("category_$cat");
			if (isset($cat_data['seo_title'])){
				$title = $cat_data['seo_title'];
			} else {
				$title = single_term_title("", false);
			}
		}

		$replacement = array(get_bloginfo("name"), get_bloginfo("description"), $title, $description);
		$blog_title = 1;
	}

	// If there's an author
	if ( is_author() && ! is_post_type_archive() ) {
		$author = get_queried_object();
		if ( $author )
			$title = $author->display_name;
		
		$blog_title = 1;

		$pattern = array("/{blog_title}/", "/{blog_description}/", "/{author_title}/");
		$subject = keremiya_get_option("author_title_format");
		$replacement = array(get_bloginfo("name"), get_bloginfo("description"), $title);
		$blog_title = 1;
	}

	// Post type archives with has_archive should override terms.
	if ( is_post_type_archive() && $post_type_object->has_archive ) {
		$title = post_type_archive_title( '', false );
		$blog_title = 1;
	}

	// If there's a month
	if ( is_archive() && !empty($m) ) {
		$my_year = substr($m, 0, 4);
		$my_month = $wp_locale->get_month(substr($m, 4, 2));
		$my_day = intval(substr($m, 6, 2));
		$title = $my_year . ( $my_month ? $t_sep . $my_month : '' ) . ( $my_day ? $t_sep . $my_day : '' );
		$blog_title = 1;
	}

	// If there's a year
	if ( is_archive() && !empty($year) ) {
		$title = $year;
		if ( !empty($monthnum) )
			$title .= $t_sep . $wp_locale->get_month($monthnum);
		if ( !empty($day) )
			$title .= $t_sep . zeroise($day, 2);
		$blog_title = 1;
	}

	// If it's a search
	if ( is_search() ) {
		/* translators: 1: separator, 2: search phrase */
		$title = strip_tags($search);
		$blog_title = 1;

		$pattern = array("/{blog_title}/", "/{blog_description}/", "/{search_title}/");
		$subject = keremiya_get_option("search_title_format");
		$replacement = array(get_bloginfo("name"), get_bloginfo("description"), $title);
	}

	// If it's a 404 page
	if ( is_404() ) {
		$title = _k_('Sayfa bulunamadı!');
		$blog_title = 1;
	}

	if(!empty($subject)) {
		echo preg_replace($pattern, $replacement, $subject);
	} else {
		if(!$title) {
		echo wp_title( '|', true, 'right' ).get_bloginfo("name");
		} else {
			if(!$blog_title) {
			echo $title;
			} else {
			echo $title.$sep.get_bloginfo("name");
			}
		}
	} 
} //keremiya_titles()

if ( ! function_exists( 'wplion_check' ) ) :
	function wplion_check() {
	    if( !get_wplion() )
	        update_option( 'wplion_session', array('0' => 1 ) );
	}
	wplion_check();
endif;

function keremiya_keywords(){
	
	#Anasayfa Anahtar Kelimeler
	if (is_home() || is_front_page()) {
		$keywords = trim(stripslashes(keremiya_get_option('seo_home_keywords')));
		if($keywords) {
		echo '<meta name="keywords" itemprop="keywords" content="'.$keywords.'" />'; 
		echo "\n"; 
		}
	}
	
	#Sayfa Anahtar Kelimeler
	if (is_single() || is_page()) {
		global $post; 
		$postid = $post->ID;
		$keywords = array();

		// post meta
		$meta = get_post_meta($postid, 'keremiya_seokeywords', true);
		if($meta)
		$keywords[] = trim(stripslashes($meta));
		
		$tags_on = keremiya_get_option("seo_auto_tags");
		// post tags
		if($tags_on) {
			$posttags = get_the_tags($postid);
			if ( $posttags && is_array( $posttags) ) {
				foreach($posttags as $tag) { 
					$keywords[] = $tag->name; 
				} 
			}
		}

		if($keywords) {
		$numItems = count($keywords);
		$i = 0;
		echo '<meta name="keywords" itemprop="keywords" content="';
		foreach($keywords as $keyword) {
			if(++$i === $numItems) {
			$sep = "";
			} else {
			$sep = ", ";
			}
			echo $keyword.$sep;
		}
		echo '" />';
		echo "\n";
		}
	}

	if (is_category()) {
		$cat = get_query_var('cat');
		$cat_data = get_option("category_$cat");
		if (isset($cat_data['seo_keywords'])){
			echo '<meta name="keywords" itemprop="keywords" content="'.$cat_data['seo_keywords'].'" />'; 
			echo "\n";
		}
	}
} // keremiya_keywords()

function keremiya_description(){
	
	#Anasayfa Anahtar Kelimeler
	if (is_home() || is_front_page()) {
		if(keremiya_get_option('seo_home_description')) {
			$description  = trim(stripslashes(htmlspecialchars(keremiya_get_option('seo_home_description'))));
		}
	}
	
	#Sayfa Anahtar Kelimeler
	if (is_single() || is_page()) {
		global $post; 
		$postid = $post->ID;

		// post meta
		$meta = get_post_meta($postid, 'keremiya_seodescription', true);
		if($meta) {
			$description = trim(stripslashes(htmlspecialchars($meta)));

		}else{
			if(keremiya_get_option("seo_auto_description")) {
				$description = keremiya_get_description($post, '157', '...');
			} #keremiya_get_option
		} #meta
	}

	if (is_category()) {
		$cat = get_query_var('cat');
		$cat_data = get_option("category_$cat");
		if (isset($cat_data['seo_description'])){
			$description = trim(stripslashes(htmlspecialchars($cat_data['seo_description'])));
		}
	}

	if($description) {
		echo '<meta name="description" itemprop="description" content="'.$description.'" />'; 
		echo "\n"; 
	}

} // keremiya_description()

function keremiya_get_description($post, $str, $sep) {
	$text = $post->post_content;

	if ( false !== strpos( $text, '<!--nextpage-->' ) ) {
		$text = $post->post_excerpt;
	}
			    
	if($text) {
		$text = wp_strip_all_tags($text);
		$text = str_replace(array("\n", "\r", "\t"), "", $text);
		$text = mb_substr($text, 0, $str);
		$description = trim(stripslashes(htmlspecialchars( $text.$sep )));
		return $description;
	}
}

function keremiya_web_verifacation() {

	if ( !is_front_page() ) return;

	$verify = array(
		'google' => keremiya_get_option('google_meta_code'), 
		'bing' => keremiya_get_option('bing_meta_code'),
		'pinterest' => keremiya_get_option('pinterest_meta_code')
		);

	foreach( Array( 'google' => 'google-site-verification', 'bing' => 'msvalidate.01', 'pinterest' => 'p:domain_verify' ) as $k => $v )
		if ( !empty( $verify[$k] ) )
			echo '<meta name="' . $v . '" content="' . trim( strip_tags( $verify[$k] ) ) . '" />' . "\n";

}

function keremiya_canonical() {
if (!keremiya_get_option('canonical_url_active')) 
	return;

	global $wp_query; 
	$url = keremiya_aiosp_get_url($wp_query);

	if($url)
		echo "<link rel=\"canonical\" href=\"$url\" />\n";	
}

function keremiya_aiosp_get_url($query) {
	if ($query->is_404 || $query->is_search) {
		return false;
		}
			$haspost = count($query->posts) > 0;
			$has_ut = function_exists('user_trailingslashit');

			if (get_query_var('m')) {
				$m = preg_replace('/[^0-9]/', '', get_query_var('m'));
				switch (strlen($m)) {
					case 4: 
					$link = get_year_link($m);
					break;
            		case 6: 
                	$link = get_month_link(substr($m, 0, 4), substr($m, 4, 2));
                	break;
            		case 8: 
                	$link = get_day_link(substr($m, 0, 4), substr($m, 4, 2), substr($m, 6, 2));
	                break;
           			default:
           			return false;
				}
		} elseif ( ( $query->is_single || $query->is_page ) && $haspost ) {
			$post = $query->posts[0];
			$link = get_permalink( $post->ID );
		} elseif ($query->is_author && $haspost) {
			$author = get_userdata( get_query_var( 'author' ) );
     		if ($author === false) return false;
			$link = get_author_posts_url( $author->ID, $author->user_nicename );
  		} elseif ($query->is_category && $haspost) {
    		$link = get_category_link(get_query_var('cat'));
			$link = keremiya_yoast_get_paged($link);
		} else if ($query->is_tag  && $haspost) {
			$tag = get_term_by('slug',get_query_var('tag'),'post_tag');
       		if (!empty($tag->term_id)) {
				$link = get_tag_link($tag->term_id);
			} 
			$link = keremiya_yoast_get_paged($link);			
  		} elseif ($query->is_day && $haspost) {
  			$link = get_day_link(get_query_var('year'),
	                             get_query_var('monthnum'),
	                             get_query_var('day'));
	    } elseif ($query->is_month && $haspost) {
	        $link = get_month_link(get_query_var('year'),
	                               get_query_var('monthnum'));
	    } elseif ($query->is_year && $haspost) {
	        $link = get_year_link(get_query_var('year'));
		} elseif ($query->is_home) {
				$link = get_option( 'home' );
				$link = keremiya_yoast_get_paged($link);
				$link = trailingslashit($link);
		} elseif ($query->is_tax && $haspost ) {
				$taxonomy = get_query_var( 'taxonomy' );
				$term = get_query_var( 'term' );
				$link = get_term_link( $term, $taxonomy );
				$link = keremiya_yoast_get_paged( $link );
		} elseif ( $query->is_archive && function_exists( 'get_post_type_archive_link' ) && ( $post_type = get_query_var( 'post_type' ) ) ) {
			if ( is_array( $post_type ) )
				$post_type = reset( $post_type );
			$link = get_post_type_archive_link( $post_type );
	    } else {
	        return false;
	    }

		return $link;

	}  

function keremiya_yoast_get_paged($link) {
	$page = get_query_var('paged');
		if ($page && $page > 1) {
			$link = trailingslashit($link) ."page/". "$page";
			if ($has_ut) {
				$link = user_trailingslashit($link, 'paged');
			} else {
				$link .= '/';
			}
		}
	return $link;
}

function keremiya_og() {
	
	if(!keremiya_get_option('og_active') || !is_single()) 
		return false;

	global $post;

	if( is_singular( 'post' ) ) {
		$type = 'video.movie';
	}
	elseif( is_singular( 'news' ) ) {
		$type = 'article';
	}

	$og = array(
		'og:url' => get_the_permalink($post->ID),
		'og:type'	=> $type,
		'og:site_name' => get_bloginfo('name'), 
		'og:title' => get_the_title($post->ID),
		'og:description' => keremiya_get_description($post, '157', '...'),
		'og:image' => keremiya_get_image(array('type' => 'url')),
		);

	foreach( $og as $k => $v )
		if ( !empty( $og[$k] ) )
			echo '<meta property="' . $k . '" content="' . trim( strip_tags( $v ) ) . '" />' . "\n";
}

?>