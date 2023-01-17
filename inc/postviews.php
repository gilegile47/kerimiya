<?php
/*
 * Keremiya Postviews
 * WP Postviews eklentisi aktif değilse çalışır.
 */

// İzlenme sayısını görüntüler.
function keremiya_izlenme($izlenme='', $post_id=''){
	global $post;
	    
	if( ! $post_id )
	    $post_id = $post->ID;

	$count = get_post_meta($post_id, 'views', true);

	if( empty($count) )
	    return '0'.$izlenme;

	$count = number_format($count);
	return $count . $izlenme;
}

if( !function_exists('the_views') ) {

    // İzlenme sayısını kaydeder.
    add_action( 'wp_head', 'keremiya_process_postviews' );
    function keremiya_process_postviews($post_id = '') {
    	// Get post data
		global $post;
		if( is_int( $post ) ) {
			$post = get_post( $post );
		}

		if( ! wp_is_post_revision( $post ) && ! is_preview() ) {
			if( is_single() ) {

				if( ! $post_id )
					$post_id = intval( $post->ID );

				$count = get_post_meta($post_id, 'views', true);

				if( empty($count) )
					$count = 0;

				$count++;

				if( !keremiya_get_option('views_cache') ) {
					update_post_meta( $post_id, 'views', $count );
				}
        	}
        }
    }

    add_action('wp_enqueue_scripts', 'keremiya_cache_count_enqueue');
    function keremiya_cache_count_enqueue() {
        global $post;

		if( !keremiya_get_option('views_cache') )
			return;

		if ( !wp_is_post_revision( $post ) && ( is_single() ) ) {
			wp_enqueue_script( 'keremiya-postviews-cache', get_template_directory_uri().'/js/postviews-cache.js', array( 'jquery' ), '1.68', true );
			wp_localize_script( 'keremiya-postviews-cache', 'viewsL10n', array( 'post_id' => intval( $post->ID ) ) );
		}
    }

    add_action( 'wp_ajax_keremiya_increment_views', 'keremiya_increment_views' );
    add_action( 'wp_ajax_nopriv_keremiya_increment_views', 'keremiya_increment_views' );
    function keremiya_increment_views() {
        if( empty( $_GET['postviews_id'] ) )
            return;

        if( !keremiya_get_option('views_cache') )
            return;
        
		$post_id = intval( $_GET['postviews_id'] );
		if( $post_id > 0 ) {
			$count = get_post_meta($post_id, 'views', true);
			$count++;
			update_post_meta( $post_id, 'views', $count );
			echo $count;
			exit();
		}
    }

	/**
	 * Otomatik post meta oluşturur.
	 */
	add_action('publish_post', 'add_views_fields');
	add_action('publish_page', 'add_views_fields');
	function add_views_fields($post_ID) {
	    global $wpdb;
	    if(!wp_is_post_revision($post_ID)) {
	        add_post_meta($post_ID, 'views', 0, true);
	    }
	}

	// WP-admin Yazılar bölümüne bilgi ekle.
	add_filter('manage_posts_columns', 'posts_column_views');
	add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
	function posts_column_views($defaults){
	    $defaults['post_views'] = _k_('İzlenme');
	    return $defaults;
	}
	function posts_custom_column_views($column_name, $id){
	    if($column_name === 'post_views'){
	        echo keremiya_izlenme();
	    }
	}

	//Function: Register the 'Views' column as sortable in the WP dashboard.
	function register_post_column_views_sortable( $newcolumn ) {
	    $newcolumn['post_views'] = 'post_views';
	    return $newcolumn;
	}
	add_filter( 'manage_edit-post_sortable_columns', 'register_post_column_views_sortable' );
	 
	//Function: Sort Post Views in WP dashboard based on the Number of Views (ASC or DESC).
	function sort_views_column( $vars )
	{
	    if ( isset( $vars['orderby'] ) && 'post_views' == $vars['orderby'] ) {
	        $vars = array_merge( $vars, array(
	            'meta_key' => 'views', //Custom field key
	            'orderby' => 'meta_value_num') //Custom field value (number)
	        );
	    }
	    return $vars;
	}
	add_filter( 'request', 'sort_views_column' );
} // the_views

?>