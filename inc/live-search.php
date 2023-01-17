<?php

function keremiya_live_search( $request_data ) {
    // DATA
   	$parameters = $request_data->get_params();

    $keyword = keremiya_clean( $parameters['keyword'] );
    $nonce = keremiya_clean( $parameters['nonce'] );

	if( !keremiya_verify_nonce( 'keremiya_search-nonce', $nonce ) ) return array( 'error' => 'no_verify_nonce', 'title' => _kp_('Güvenlik hatası var gibi görünüyor.') );
	if( !isset( $keyword ) || empty($keyword) ) return array( 'error' => 'no_parameter_given' );
	if( strlen( $keyword ) <= 2 ) return array( 'error' => 'keyword_not_long_enough', 'title' => _kp_('Kelime en az üç karakter uzunluğunda olmalıdır.') );

	$args = array(
		's' => $keyword,
		'post_type' => 'post',
		'posts_per_page' => 5,
		//'orderby' => 'meta_value_num',
		//'meta_key' => 'views'
    );
    // The Query
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {

    	$data = array();
        while ( $query->have_posts() ) {
            $query->the_post();

            global $post;
            $data[$post->ID]['title'] = $post->post_title;
            $data[$post->ID]['url'] = get_the_permalink();
            $data[$post->ID]['img'] = keremiya_get_image(array('type' => 'url', 'size' => 'izlenen-resim'));
            $data[$post->ID]['extra']['imdb'] = keremiya_get_meta('imdb');
            $data[$post->ID]['extra']['date'] = keremiya_get_meta('yapim');
            $data[$post->ID]['extra']['names'] = keremiya_get_meta('diger-adlari');
        }

        return $data;
    } else {
    	return array( 'error' => 'no_posts', 'title' => _k_('Film bulunamadı.') );
    }
    /* Restore original Post Data */
    wp_reset_postdata();

}

add_action( 'rest_api_init', 'wpc_register_wp_api_endpoints' );
function wpc_register_wp_api_endpoints() {

	if( ! keremiya_get_option('live_search') )
		return;

	register_rest_route( 'keremiya', '/search/', array(
        'methods' => 'GET',
        'callback' => 'keremiya_live_search',
    ));
}

function keremiya_wpc_search_url() {
	return rest_url('/keremiya/search/');
}

?>