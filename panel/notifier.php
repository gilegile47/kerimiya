<?php
/**************************************************************
 *   Yapan: Joao Araujo                                       *
 *   Profili: http://themeforest.net/user/unisphere           *
 *   Takip Edin: http://twitter.com/unispheredesign 		  *
 *	 Geliştiren: Keremiya									  *
 *	 Site: www.keremiya.com                                   *
 **************************************************************/
 
// Tema adı, klasör ve uzak bir XML url Sabitleri
define( 'NOTIFIER_THEME_NAME', theme_name ); // Temanın adı
define( 'NOTIFIER_THEME_FOLDER_NAME', 'keremiya' ); // Tema dosyasının adı
define( 'NOTIFIER_XML_FILE', 'https://www.keremiya.com/support/notifier/v5/'.get_theme_notifier_lang().'/notifier.xml' ); // En son tema sürümü ve değişiklikleri içeren uzaktan bildirme XML dosyası
define( 'NOTIFIER_CACHE_INTERVAL', 21600 ); // Veritabanında XML dosyasının değişiklik zamanı (21600 saniye = 6 saat)

// WordPress Dashboard menüsüne bir güncelleştirme bildirimi ekler
function keremiya_update_notifier_menu() {
	if( keremiya_get_option('disable_notifier') )
		return;

	if (function_exists('simplexml_load_string')) { // Simplexml_load_string işlev kullanılabilir değilse Durdur
	    $xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Bizim sunucumuzdan sizin sunucuza XML dosyası alma
		$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Temanın Style.css dosyasını okur
		// && !get_option( 'notifier-read' )
		if( (float)$xml->latest > (float)theme_ver && !get_option( 'notifier-read' ) ) { // Tema sürümünü XML dosyası ile karşılaştırma
			add_dashboard_page( sprintf(_kp_('%s bildirimleri'), NOTIFIER_THEME_NAME), NOTIFIER_THEME_NAME . ' <span class="update-plugins count-1"><span class="update-count">'._k_("yeni").'</span></span>', 'administrator', 'theme-update-notifier', 'keremiya_update_notifier');
		}
	}	
}
add_action('admin_menu', 'keremiya_update_notifier_menu');  



// WordPress 3.1 için bir güncelleştirme bildirimi + Admin Bar ekler
function keremiya_update_notifier_bar_menu() {
	if (function_exists('simplexml_load_string')) { // Simplexml_load_string işlev kullanılabilir değilse Durdur
		global $wp_admin_bar, $wpdb;
	
		if ( !is_super_admin() || !is_admin_bar_showing() ) // Kullanıcı bir yönetici değil ise yönetici çubuğunda bildirim görüntülemeyi devre dışı bırak
		return;
		
		$xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Bizim sunucumuzdan sizin sunucuza XML dosyası alma
		$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Temanın Style.css dosyasını okur
	
		/*if( (float)$xml->latest > (float)$theme_data['Version']) { // Tema sürümünü XML dosyası ile karşılaştırma
			$wp_admin_bar->add_menu( array( 'id' => 'update_notifier', 'title' => '<span>' . NOTIFIER_THEME_NAME . ' <span id="ab-updates">yeni</span></span>', 'href' => get_admin_url() . 'index.php?page=theme-update-notifier' ) );
		}*/
	}
}
add_action( 'admin_bar_menu', 'keremiya_update_notifier_bar_menu', 1000 );



// The notifier page
function keremiya_update_notifier() { 
	$xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Bizim sunucumuzdan sizin sunucuza XML dosyası alma
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Temanın Style.css dosyasını okur 
	update_option( 'notifier-read', true ); ?>
	
	<style>
		.update-nag { display: none; }
		#instructions { }
		h3.title {margin: 30px 0 0 0; padding: 30px 0 0 0; border-top: 1px solid #ddd;}
	</style>

	<div class="wrap">
	
		<div id="icon-tools" class="icon32"></div>
		<h2><?php printf(_kp_("%s bildirimleri"), NOTIFIER_THEME_NAME); ?></h2>
	    <div id="message" class="updated below-h2"><p><strong><?php printf(_kp_("%s'in %1s sürümü çıktı."), NOTIFIER_THEME_NAME, $xml->latest); ?></strong></p></div>

		<div id="instructions">
		    
			<p><?php echo $xml->changelog; ?></p>		    
		    
		</div>

	</div>
    
<?php } 

// Varsa ve belirlenen süre içinde önbelleğe alınmış sürümünü kullanır
function get_latest_notifier_version($interval) {
	$notifier_version = keremiya_parse_url( get_bloginfo('url') );
	$notifier_file_url = add_query_arg( 'b', $notifier_version,  keremiya_hl_url() . '/' );	
	$db_cache_field = 'version-cache';
	$db_cache_field_last_updated = 'version-cache-last-updated';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	// önbellek kontrol
	if ( !$last || (( $now - $last ) > $interval) ) {
		// önbellek yoksa veya eski sürüm varsa yenilenirr
		if( function_exists('curl_init') ) { // cURL varsa, onu kullanın ...
			$ch = curl_init($notifier_file_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$cache = curl_exec($ch);
			curl_close($ch);
		} else {
			$cache = file_get_contents($notifier_file_url); // ... değilse, ortak file_get_contents () kullanın
		}
		
		if ($cache) {			
			// biz iyi sonuçlar ver
			update_option( $db_cache_field, $cache );
			update_option( $db_cache_field_last_updated, time() );
		} 
		// önbellek dosyası okuma
		$notifier_data = get_option( $db_cache_field );
	}
	else {
		// önbellek dosyası yeterince taze, bu yüzden okuma
		$notifier_data = get_option( $db_cache_field );
	}

	return $notifier_data;
}

// Belirlenen süre içinde önbelleğe alınmış sürümünü günceller
function update_latest_notifier_version() {
	$version = get_latest_notifier_version( NOTIFIER_CACHE_INTERVAL );
	$version = json_decode($version);

	if( $version->response ) {
		if ( $version->status == 200 )
			exit();

		if ( $version->status == 100 )
			return $version->data;
	}
}
add_action('init', 'update_latest_notifier_version');



// Varsa ve belirlenen süre içinde önbelleğe alınmış sürümünü kullanır
function get_latest_theme_version($interval) {
	$notifier_file_url = NOTIFIER_XML_FILE;	
	$db_cache_field = 'notifier-cache';
	$db_cache_field_last_updated = 'notifier-cache-last-updated';
	$db_cache_read = 'notifier-read';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	// önbellek kontrol
	if ( !$last || (( $now - $last ) > $interval) ) {
		// önbellek yoksa veya eski sürüm varsa yenilenirr
		if( function_exists('curl_init') ) { // cURL varsa, onu kullanın ...
			$ch = curl_init($notifier_file_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$cache = curl_exec($ch);
			curl_close($ch);
		} else {
			$cache = file_get_contents($notifier_file_url); // ... değilse, ortak file_get_contents () kullanın
		}
		
		if ($cache) {			
			// biz iyi sonuçlar ver
			update_option( $db_cache_field, $cache );
			update_option( $db_cache_field_last_updated, time() );
			update_option( $db_cache_read, false );
		} 
		// önbellek dosyası okuma
		$notifier_data = get_option( $db_cache_field );
	}
	else {
		// önbellek dosyası yeterince taze, bu yüzden okuma
		$notifier_data = get_option( $db_cache_field );
	}
	
	// Beklendiği gibi xml veri döndü Bakalım.
	// versiyon 1.0
	if( strpos((string)$notifier_data, '<notifier>') === false ) {
		$notifier_data = '<?xml version="1.0" encoding="UTF-8"?><notifier><latest>1.0</latest><changelog></changelog></notifier>';
	}
	
	// Sunucudak xml
	if ( function_exists('simplexml_load_string') ) {	
		return simplexml_load_string($notifier_data); 	
	}
}

function get_theme_notifier_lang() {
	$lang = get_option("WPLANG");

	if($lang == 'tr_TR') {
		return 'tr';
	} else {
		return 'en';
	}
}

?>