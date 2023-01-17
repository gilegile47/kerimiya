<?php
/*
 * Bazı wordpress fonksiyonlarını geçersiz kılar
 */

// Sidebar Kayıt Silme
function unregister_default_wp_widgets(){
    unregister_widget( 'WP_Widget_Calendar' );
    unregister_widget( 'WP_Widget_Links' );
    unregister_widget( 'WP_Widget_Meta' );
    unregister_widget( 'WP_Widget_Search' );
    unregister_widget( 'WP_Widget_Recent_Comments' );
    unregister_widget( 'WP_Widget_RSS' );
}
add_action( 'widgets_init', 'unregister_default_wp_widgets', 1 );

// WP Head bazı meta linklerini siler
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wlwmanifest_link' );
remove_action('wp_head', 'wp_generator' );
remove_action('wp_head', 'rsd_link' );
remove_action('wp_head', 'start_post_rel_link' );
remove_action('wp_head', 'index_rel_link' );
remove_action('wp_head', 'adjacent_posts_rel_link' );

if (is_admin()) :
function my_remove_meta_boxes() {
  remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
  remove_meta_box( 'postexcerpt', 'post', 'normal' );
}
add_action( 'admin_menu', 'my_remove_meta_boxes' );
endif;

// ADMIN BAR IPTAL

if( !current_user_can('administrator') ) {
  add_filter( 'show_admin_bar', '__return_false' );

  // ADMIN BAR KULLANICI ENGELLE
  remove_action( 'personal_options', '_admin_bar_preferences' );
} else {
  if(!keremiya_get_option('admin_bar')) {
    add_filter( 'show_admin_bar', '__return_false' );
  }
}


// WP EMOJI DEVRE DIŞI BIRAK
function disable_wp_emojicons() {

  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  
}
add_action( 'init', 'disable_wp_emojicons' );


function keremiya_foreach_meta_keys() {
  if(!keremiya_get_option('change_meta_keys'))
    return;

  foreach (keremiya_custom_meta_keys() as $key => $title) {
    add_filter('keremiya_meta_'.$key, 'keremiya_change_meta_key');
  }
}
add_action( 'init', 'keremiya_foreach_meta_keys');

function keremiya_change_meta_key($id) {
    $key = keremiya_get_option('meta_'.$id);
    
    if($key)
      return $key;

    return $id;
}

function keremiya_custom_meta_keys() {
    $metas = array(
        _k_('yapim') => _k_('Yapım'),
        _k_('imdb') => _k_('IMDB'),
        _k_('yonetmen') => _k_('Yönetmen'),
        _k_('oyuncular') => _k_('Oyuncular'),
        _k_('konu') => _k_('Konu'),
        _k_('resim') => _k_('Resim'),
        _k_('diger-adlari') => _k_('Diğer Adları'),
        _k_('yayin-tarihi') => _k_('Yayın Tarihi'),
        _k_('senaryo') => _k_('Senaryo'),
        _k_('oduller') => _k_('Ödüller'),
        _k_('yapim-sirketi') => _k_('Yapım Şirketi'),
        _k_('butce') => _k_('Bütçe'),
        _k_('box-office') => _k_('Box Office'),
        );
    return apply_filters('keremiya_custom_meta_keys', $metas);
}

function change_submenu_class($menu) {  
  $menu = preg_replace('/ class="sub-menu"/',' class="sub-menu flexcroll"',$menu);  
  return $menu;  
}  
add_filter('wp_nav_menu','change_submenu_class');

?>