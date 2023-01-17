<?php
/**
 * Anasayfa Standart Demo Görünümü
*/	
	
do_action('keremiya_home_before');

	// En Yeni Filmleri Listeler
    echo '<div class="film-content">';
    	echo '<h2 class="title"><span>'._k_('En Son Eklenen Filmler').'</span></h2>';
		keremiya_home_list_movies();
	echo '</div>';

	if( ! keremiya_get_option('on_sidebar') ) {
		
		// Kategorileri Listeler
		keremiya_home_list_categories();
	
		if( keremiya_get_option('news_on') ) {
			// Haberleri Listeler
			keremiya_home_list_news();
		}

		if( keremiya_get_option('today_movie_on') ) {
			// Günün Filmi
			keremiya_home_today_movie();
		}
	}

do_action('keremiya_home_after');

?>