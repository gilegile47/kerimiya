<?php
/*
 * IMDB BOTU
 */

add_action('wp_ajax_nopriv_keremiya_imdb_importer', 'keremiya_imdb_importer');
add_action('wp_ajax_keremiya_imdb_importer', 'keremiya_imdb_importer');

function keremiya_imdb_importer() {
	// IMDB ID
	$id = $_POST['id'];
	// GET IMDB DATA
	$data = keremiya_get_imdb($id);

	if($data->Response != 'True')
		return;

	$awards = imdb_replace_awards( $data->Awards );
	$writer = imdb_replace_writer( $data->Writer );
	$categories = imdb_get_category_ids( $data->Genre );
	$released = imdb_replace_date( $data->Released );

	$newData = array(
		array('id' => 'diger-adlari', 	'text' => imdb_empty( $data->Title ) ),
		array('id' => 'yapim', 			'text' => imdb_empty( $data->Year ) ),
		array('id' => 'yayin-tarihi', 	'text' => imdb_empty( $released ) ),
		array('id' => 'yonetmen', 		'text' => imdb_empty( $data->Director ) ),
		array('id' => 'oyuncular', 		'text' => imdb_empty( $data->Actors ) ),
		array('id' => 'senaryo', 		'text' => imdb_empty( $writer ) ),
		array('id' => 'oduller', 		'text' => imdb_empty( $awards ) ),
		array('id' => 'imdb', 			'text' => imdb_empty( $data->imdbRating ) ),
		array('id' => 'resim', 			'text' => imdb_empty( $data->Poster ) ),
		array('id' => 'category', 		'text' => imdb_empty( $categories ) ),
		'count' => 10,
		'response' => true
	);

	$json = json_encode($newData);
	echo $json;

	exit();
}

function keremiya_get_imdb($id) {
	$url = "http://www.omdbapi.com/?i={$id}&plot=short&r=json";
	$response = wp_remote_get( $url );

	if( is_array($response) ) {
		$header = $response['headers']; // array of http header lines
		$data = $response['body']; // use the content
		$data = json_decode($data);

		if( ! $data->Response )
			return;

		return apply_filters('keremiya_imdb_data', $data);
	}

	return false;
}

function imdb_empty($data) {
	if($data == 'N/A')
		return '';

	return $data;
}

function imdb_get_category_ids($content) {
	if(!keremiya_get_option('auto_category'))
		return;

	$categories = explode(',', $content);

	foreach ($categories as $key => $value) {
		$category[] = imdb_get_genre_id( $value );
	}

	return $category;
}

function imdb_get_genre_id($name) {
	$category_id = keremiya_get_option('imdb_' . trim($name));

	return $category_id;
}

function imdb_genres() {
	$genres = array(
		'Action', 
		'Adventure',
		'Comedies',
		'Crime',
		'Fantasy',
		'Mystery',
		'Drama',
		'Animation',
		'Sci-Fi',
		'Thrillers',
		'Family',
		'Romance',
		'Horror',
		'Sport',
		'War',
		'Westerns',
		'Documentaries',
		'History',
		'Music',
		'Biographies',
		'Musicals',
	);
	
	return apply_filters('imdb_genres', $genres);
}

function imdb_replace_date($text) {
	if( get_option('WPLANG') != 'tr_TR' ) {
		return $text;
	}

	$search = array('Apr', 'May', 'Jan', 'Mar', 'Jun', 'Jul', 'Oct', 'Dec', 'Sept', 'Nov', 'Aug', 'Feb');
	$replace = array('Nisan', 'Mayıs', 'Ocak', 'Mart', 'Haziran', 'Temmuz', 'Ekim', 'Aralık', 'Eylül', 'Kasım', 'Ağustos', 'Şubat');

	$dateText = str_replace($search, $replace, $text);

	return $dateText;
}

function imdb_replace_writer($text) {
	if( get_option('WPLANG') != 'tr_TR' ) {
		return $text;
	}

	$newText = preg_replace("#\((.*?)\)#", '', $text);
	$newText = str_replace(' ,', ',', $newText);

	return $newText;
}

function imdb_replace_awards($text) {
	if( get_option('WPLANG') != 'tr_TR' ) {
		return $text;
	}

	$d = explode('.', $text);

	if($d[0]) {
		$won = 'Won';

		$pos = strpos($d[0], $won);

		if ($pos !== false) {
			$search = array('Won', 'Oscars.', 'Oscar.', 'Emmys.', 'Emmy.', 'Golden Globes.', 'Golden Globe.');
			$replace = array('', 'Oscar Kazandı.', 'Oscar Kazandı.', 'Emmy Kazandı.', 'Emmy Kazandı.', 'Golden Globe Kazandı.', 'Golden Globe Kazandı.');

			$oscarText = str_replace($search, $replace, $d[0].'.');
			//echo $oscarText;
		}

		$nominated = 'Nominated';

		$pos = strpos($d[0], $nominated);

		if ($pos !== false) {
			$search = array('Nominated for', 'Oscars.', 'Oscar.', 'Emmys.', 'Emmy.', 'Golden Globes.', 'Golden Globe.');
			$replace = array('', 'Oscar Adaylığı.', 'Oscar Adaylığı.', 'Emmy Adaylığı.', 'Emmy Adaylığı.', 'Golden Globe Adaylığı.', 'Golden Globe Adaylığı.');

			$nominatedText = str_replace($search, $replace, $d[0].'.');
			//echo $nominatedText;
		}

		$win = 'win';

		$pos = strpos($d[0], $win);

		if ($pos !== false) {
			$search = array('Another', 'wins', 'win', 'nominations');
			$replace = array('Diğer', 'ödül', 'ödül', 'Adaylık.');

			$winText = str_replace($search, $replace, $d[0]);
			//echo $winText;
		}
	}

	if($d[1]) {

		$search = array('Another', 'wins', 'win', 'nominations');
		$replace = array('Diğer', 'ödül', 'ödül',  'adaylık.');

		$anotherText = str_replace($search, $replace, $d[1]);

		//echo $anotherText;
	}

	return trim( $oscarText . $nominatedText . $winText . $anotherText );
}



?>