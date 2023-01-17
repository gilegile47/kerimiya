<?php
/*
 * Framework Dosyaları
 */
$dir = get_template_directory() . '/inc';

// Önemli Dosyalar
include_once ($dir . '/translator.php');
include_once ($dir . '/functions.php');
include_once ($dir . '/core-functions.php');
include_once ($dir . '/common-scripts.php');
include_once ($dir . '/taxonomies.php');
include_once ($dir . '/theme-setup.php');

// Diğer Fonksiyonlar
include_once ($dir . '/seo.php');
include_once ($dir . '/hooks.php');
include_once ($dir . '/popups.php');
include_once ($dir . '/ratings.php');
include_once ($dir . '/comment-like.php');
include_once ($dir . '/buttons.php');
include_once ($dir . '/addto.php');
include_once ($dir . '/report.php');
include_once ($dir . '/navi.php');
include_once ($dir . '/home-builder.php');
include_once ($dir . '/user.php');
include_once ($dir . '/user-update.php');
include_once ($dir . '/shortcodes.php');
include_once ($dir . '/category_meta_data.php');
include_once ($dir . '/add-post.php');
include_once ($dir . '/live-search.php');
include_once ($dir . '/tabs.php');
include_once ($dir . '/postviews.php');
include_once ($dir . '/wp-login.php');

// Widgets
include_once ($dir . '/widgets/facebook-like-widget.php');
include_once ($dir . '/widgets/keremiya-kategoriler.php');
include_once ($dir . '/widgets/keremiya-comments.php');
include_once ($dir . '/widgets/keremiya-film-kutusu.php');
include_once ($dir . '/widgets/keremiya-today-movie.php');
include_once ($dir . '/widgets/keremiya-news-widget.php');

// Deprecated
include_once ($dir . '/deprecated.php');
?>