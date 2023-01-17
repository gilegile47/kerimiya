<?php

add_action('wp_ajax_nopriv_keremiya_ratings', 'keremiya_ratings');
add_action('wp_ajax_keremiya_ratings', 'keremiya_ratings');

function keremiya_ratings() {
    // Güvenlik kontrolü
    $nonce = $_POST['nonce'];
    // Kullanılan Oy Puanı
    $rate = $_POST['rate'];
    $post_id = $_POST['post_id'];

    if ( ! wp_verify_nonce( $nonce, 'ratings_ajax-nonce' ) ) { 
        $error = _keremiya_addto_error(_k_('Hay Aksi'), _k_('Güvenlik hatası var gibi görünüyor, lütfen daha sonra tekrar dene.'), '..');
        die( $error );
    }

    if( keremiya_is_rated($post_id) || $_COOKIE["voted_{$post_id}"] ) {
        $error = _keremiya_addto_error(_k_('Hay Aksi'), _k_('Bu film için verdiğiniz puan değiştirilemez.'), '..');
        die( $error );
    }

    // Sahte puan kontrolü
    if(isset($rate) && $rate <= 10) {
    	$total = keremiya_get_total($post_id);
    	$score = keremiya_get_score($post_id);

    	$status = keremiya_set_ratings($rate, $total, $score, $post_id);

    	if($status) {
		    $json = array(
		        'error' => false,
		        'message' => _k_('Oyunuz için teşekkürler!'),
		        'html' => keremiya_get_average($post_id),
		    );

		    echo json_encode($json);
    	} else {
    	    $error = _keremiya_addto_error(_k_('Hay Aksi'), _k_('Sadece üyeler puan verebilir.'), keremiya_popup_login_url());
       		die( $error );	
    	}

    } else {
    	die ('403');
    }

    exit;
}

function keremiya_find_average($score, $total) {
	return round($score / $total, 1);
}
function keremiya_find_score($score, $rate) {
	return $score + intval( $rate );
}
function keremiya_find_total($total) {
	return ++$total;
}

function keremiya_get_total($post_id, $key = '_rating_total') {
	$total = keremiya_get_meta($key, $post_id);

	if(empty($total))
		$total = 0;

	return $total;
}

function keremiya_get_score($post_id) {
	$score = keremiya_get_meta('_rating_score', $post_id);

	if(empty($score))
		$score = 0;

	return $score;
}

function keremiya_get_average($post_id) {
	$average = keremiya_get_meta('_rating_average', $post_id);

	$average = (strlen($average) == 1 ? $average.'.0' : $average);
	$average = (!$average ? '' : $average);

	return $average;
}

function keremiya_set_ratings($rate, $total, $score, $post_id) {
	$public = keremiya_get_option('vote');

	if(!is_user_logged_in() && $public == 'user')
		return false;

	// Toplam Oy Sayısı
	$total = keremiya_find_total($total);

	// Oyların Toplam Puanı
	$score = keremiya_find_score($score, $rate);

	// Oyların 10 üzerinden Ortalaması
	$average = keremiya_find_average($score, $total);

	// Kaydet
	update_post_meta($post_id, '_rating_total', $total);
	update_post_meta($post_id, '_rating_score', $score);
	update_post_meta($post_id, '_rating_average', $average);

	update_post_meta($post_id, '_rating_total_'.$rate, keremiya_find_total( keremiya_get_total($post_id, '_rating_total_'.$rate) ) );

	if($public == 'public') {
		$date_of_expiry = time()+31556926;
		setcookie( "voted_{$post_id}", $rate, $date_of_expiry, "/" );
	}

	keremiya_ratings_set_user_data($post_id, $rate, 'my_ratings');

	return true;
}

function keremiya_ratings_set_user_data($post_id, $rate, $meta) {
	if( !is_user_logged_in() )
		return false;

    // Kullanıcı ID
    $user_ID = get_current_user_id();

    // Array Post ID
    $sid = array($post_id => $rate);

    // Veriler
    $exists = array();
    $exists = unserialize( get_user_meta($user_ID, $meta, true) );

    if( is_array( $exists ) ){
        $narray = $exists + $sid;
    } else {
        $narray = $sid;
    }

    if( !in_array($post_id, $exists) ) {
    
        // serialize data
        $seri = serialize($narray);
        update_user_meta($user_ID, $meta, $seri);

        addPointToUser($user_ID, $meta.'_total');

        return true;
    }
    return false;
}

function get_ipaddress() {
	if ( isset( $_SERVER['X-Real-IP'] ) ) {
		return $_SERVER['X-Real-IP'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		return trim( current( explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
	} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		return $_SERVER['REMOTE_ADDR'];
	}
	return '';
}

function keremiya_is_voted($post_id) {
    // Retrieve post votes IPs
    $meta_IP = get_post_meta($post_id, "rating_IP");
    $rating_IP = $meta_IP[0];
     
    if(!is_array($voted_IP))
        $rating_IP = array();
         
    // Retrieve current user IP
    $ip = get_ipaddress();
     
    // If user has already voted
    if(in_array($ip, array_keys($rating_IP)))
    	return true;
     
    return false;
}


function keremiya_ratings_html() {
	$post_id = get_the_ID();
	// toplam oy sayısı
	$total = keremiya_get_total($post_id);
	// 10 üzerinden puan
	$average = keremiya_get_average($post_id);
	// User Rate
	$user_rate = keremiya_is_rated($post_id);
	$user_rate = $user_rate ? $user_rate : $_COOKIE["voted_{$post_id}"];

	$html = '<div class="siteRating" itemtype="http://schema.org/AggregateRating" itemscope itemprop="aggregateRating">';
	$html .= '<div class="site-vote">';
	$html .= '<span class="icon-star"><span class="average">'.$average.'</span></span>';
	$html .= '</div>'; //.site-vote

	$html .= '<div class="rating-star">';
	$html .= '<span class="your-vote">'._k_('Sizin Oyunuz').': <span>'.$user_rate.'</span></span>';
	$html .= '<div class="stars" data-id="'.$post_id.'" data-nonce="'.wp_create_nonce("ratings_ajax-nonce").'">';
	
	for ($i = 1; $i <= 10; $i++) {
		
		$class = 'icon-star tooltip';
		if($i <= $user_rate)
			$class = 'icon-star tooltip fullStar';
    	
    	$html .= '<a href="#" class="'.$class.'" title="'._k_('Oy için tıkla').': '.$i.'"><span>'.$i.'</span></a>';
	}

	$html .= '</div>';
	$html .= '</div>';
	if($total):
		$html .= '<div class="details">'._k_('Oylama').': <strong class="average"><span itemprop="ratingValue">'.$average.'</span></strong> <span class="seperator">/</span><span itemprop="bestRating">10</span> <span class="icon-user tooltip" title="'._k_('Toplam oylayan kişi sayısı').'">(<span class="total" itemprop="ratingCount">'.$total.'</span>)</span></div>';
	else:
		$html .= '<div class="details">'._k_('Haydi, ilk sen oyla!').'</div>';
	endif;
	$html .= '</div>';

	return $html;
}

function keremiya_get_user_ratings() {
	return unserialize( get_user_meta(get_current_user_id(), 'my_ratings', true) );
}
function keremiya_is_rated($post_id) {
	$data = keremiya_get_user_ratings();
	
	if(isset($data[$post_id])) {
		return $data[$post_id];
	}
	return false;
}

?>