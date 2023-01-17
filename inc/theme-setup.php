<?php
/**
 * Keremiya Başlangıç
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'after_setup_theme', 'keremiya_setup' );
function keremiya_setup() {
	
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );

	add_image_size('large-resim', 236, 350, true);
	add_image_size('anasayfa-resim', 138, 204, true);
	add_image_size('izlenen-resim', 70, 80, true);
	add_image_size('slide-resim', 110, 138, true);

	register_nav_menus( array(
		'header-nav' => _kp_( 'Keremiya Header Menüsü'),
		'footer-nav' => _kp_( 'Keremiya Footer Menüsü')
	) );
	
	/* Register Sidebars */
	register_sidebar(array(
		'name' => _kp_('Genel Sidebar'),
		'id' => 'main',
		'description' => _kp_('Bu sidebar varsayılandır. Diğer sidebar bölümleri boş ise bu sidebar kullanılır.'),
		'before_widget' => '<div id="%1$s" class="sidebar-content clearfix %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="top"><span class="fix">',
		'after_title' => '</span></div><div class="space"></div>',
	));
	
	register_sidebar(array(
		'name' => _kp_('Anasayfa Sidebarı'),
		'id' => 'home',
		'description' => _kp_('Bu sidebar sadece anasayfada görünür.'),
		'before_widget' => '<div id="%1$s" class="sidebar-content clearfix %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="top"><span class="fix">',
		'after_title' => '</span></div><div class="space"></div>',
	));
	
	register_sidebar(array(
		'name' => _kp_('Kategori Sidebarı'),
		'id' => 'category',
		'description' => _kp_('Bu sidebar sadece kategorilerde görünür.'),
		'before_widget' => '<div id="%1$s" class="tags clearfix %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="title"><span class="fix">',
		'after_title' => '</span></h4><div class="space"></div>',
	));
	
	register_sidebar(array(
		'name' => _kp_('Video Sidebarı'),
		'id' => 'single',
		'description' => _kp_('Bu sidebar sadece video izlerken görünür.'),
		'before_widget' => '<div id="%1$s" class="sidebar-content clearfix %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="top"><span class="fix">',
		'after_title' => '</span></div><div class="space"></div>',
	));
	
	register_sidebar(array(
		'name' => _kp_('Sayfa Sidebarı'),
		'id' => 'page',
		'description' => _kp_('Bu sidebar sadece sayfalarda görünür.'),
		'before_widget' => '<div id="%1$s" class="sidebar-content clearfix %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="top"><span class="fix">',
		'after_title' => '</span></div><div class="space"></div>',
	));
	
	register_sidebar(array(
		'name' => _kp_('Üye Sidebarı'),
		'id' => 'author',
		'description' => _kp_('Bu sidebar sadece üye sayfalarında görünür.'),
		'before_widget' => '<div id="%1$s" class="sidebar-content clearfix %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="top"><span class="fix">',
		'after_title' => '</span></div><div class="space"></div>',
	));

	register_sidebar(array(
		'name' => _kp_('Haber Sidebarı'),
		'id' => 'news',
		'description' => _kp_('Bu sidebar sadece haber sayfalarında görünür.'),
		'before_widget' => '<div id="%1$s" class="sidebar-content clearfix %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="top"><span class="fix">',
		'after_title' => '</span></div><div class="space"></div>',
	));

	register_sidebar(array(
		'name' => _kp_('Haber Kategori Sidebarı'),
		'id' => 'news_category',
		'description' => _kp_('Bu sidebar sadece haber kategorilerinde görünür.'),
		'before_widget' => '<div id="%1$s" class="sidebar-content clearfix %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="top"><span class="fix">',
		'after_title' => '</span></div><div class="space"></div>',
	));

	register_sidebar(array(
		'name' => _kp_('Liste Sidebarı'),
		'id' => 'list',
		'description' => _kp_('Bu sidebar sadece liste sayfalarında görünür.'),
		'before_widget' => '<div id="%1$s" class="sidebar-content clearfix %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="top"><span class="fix">',
		'after_title' => '</span></div><div class="space"></div>',
	));
}

global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action('init', 'keremiya_extra_setup', 1);

/*-----------------------------------------------------------------------------------*/
/* Kurulum */
/*-----------------------------------------------------------------------------------*/

function keremiya_extra_setup() {
	
	global $default_data, $wp_rewrite;
	
    /*
     * Keremiya activated pages
     * @since 1.0.0
    */
	keremiya_page_setup();
    

	/* Kayıt Ayarı */
	update_option('users_can_register', 1);
	
    /*
     * Reset wp_lion session values
     * @since 1.0.0
    */
    wplion_session_reset();
    
	$wp_rewrite->flush_rules();
}

/*-----------------------------------------------------------------------------------*/
/* Sayfa Kurulumları */
/*-----------------------------------------------------------------------------------*/

function keremiya_page_setup() {

	global $wpdb;
    
    $pages = array(
        _k_('uye-girisi')   => array( 'meta' => 'keremiya_login_page_id',   	'file' => 'page-login.php',    		'title' => _k_('Üye Girişi')),
        _k_('kaydol')       => array( 'meta' => 'keremiya_register_page_id',    'file' => 'page-register.php',    	'title' => _k_('Kaydol')),
        _k_('ayarlar')      => array( 'meta' => 'keremiya_settings_page_id',    'file' => 'page-settings.php',  	'title' => _k_('Ayarlar')),
        _k_('film-arsivi')  => array( 'meta' => 'keremiya_archive_page_id',     'file' => 'page-film-archive.php',  'title' => _k_('Film Arşivi')),
        _k_('bize-yazin')  	=> array( 'meta' => 'keremiya_contact_page_id',     'file' => 'page-contact.php', 		'title' => _k_('İletişim')),
        _k_('aktivasyon')  	=> array( 'meta' => 'keremiya_activation_page_id',  'file' => 'page-activation.php',   	'title' => _k_('Aktivasyon')),
        _k_('icerik-ekle')  => array( 'meta' => 'keremiya_add_page_id',  		'file' => 'page-add.php',   		'title' => _k_('İçerik Ekle')),
        );

    foreach( $pages as $k => $v ) {

        $page_id = $wpdb->get_var("SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '$k';");

        if (!$page_id) {
        
            $my_page = array(
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'post_name' => $k,
                'post_title' => $v['title']
            );
            $page_id = wp_insert_post($my_page);

            update_post_meta($page_id, '_wp_page_template', $v['file']);
            update_option($v['meta'], $page_id);

        } else {

            $update_page = array(
                'ID' => $page_id,
                'post_status' => 'publish'
            );
            wp_update_post( $update_page );

            update_post_meta($page_id, '_wp_page_template', $v['file']);
            update_option($v['meta'], $page_id);

        }

    }

}

?>