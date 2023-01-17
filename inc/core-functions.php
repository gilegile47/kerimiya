<?php
/**
 * Poster çağırma
 */
function keremiya_get_image($args = array()) {

    if( !$args['post_ID'] ) {
        global $post;
        $post_ID = $post->ID;
    } else {
        $post_ID = $args['post_ID'];
    }

    $defaults = array(
        'size' => 'full',
        'customfield' => apply_filters('keremiya_meta_resim', 'resim'),
        'title' => get_the_title( $post_ID ),
        'height' => '',
        'width' => '',
        'type' => 'html'
    );

    $args = wp_parse_args( $args, $defaults );
    extract( $args, EXTR_SKIP );

    if(has_post_thumbnail()) {
        $is_full = keremiya_get_option('full_size');
        $size = $is_full ? $defaults['size'] : $size;

        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), $size );
        $image = $image['0'];
    } elseif( get_post_meta($post_ID, $customfield, true) ){
        $image = get_post_meta($post_ID, $customfield, true);
    } else {
        $image = keremiya_resim_bulucu();
    }

    if($type == 'html') {

        $width = $width ? 'width="'.$width.'" ' : '';
        $height = $height ? 'height="'.$height.'" ' : '';

        echo '<img src="'. $image .'" alt="'. $title .'" '.$width.$height.'/>';
    } else {
        return $image;
    }
}

/**
 * Özel yorum şablonu
 */
if ( ! function_exists( 'keremiya_list_comments' ) ) :
function keremiya_list_comments( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case '' :
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <div id="comment-<?php comment_ID(); ?>" class="clearfix">
        
        <div class="bi-avatar">
            <div class="comment-avatar">
                <?php echo get_avatar($comment, '64', keremiya_custom_gravatar());  ?>
            </div>
        </div>
        
        <div class="comment-meta commentmetadata">
        <div class="comment-author vcard">
            <div id="comment-user"><?php keremiya_comment_user_link(); ?></div> <div id="comment-age"><?php echo keremiya_zaman('comment'); ?></div>
        </div>
        
        <div class="comment-body"><?php comment_text(); ?></div>
        
        <div class="comment-buttons clearfix">

            <?php if($args['max_depth'] > 1): ?>
            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'login_text' => _k_('Cevapla') ) ) ); ?>
            </div>
            <?php endif; ?>

            <div class="voting">
                <?php keremiya_comment_vote_button($comment) ?>
            </div>
        </div>
        
        <?php if ( $comment->comment_approved == '0' ) : ?>
                <em class="comment-awaiting-moderation"><?php _k("Yorumunuz moderatör onayından sonra yayınlanacaktır."); ?></em>
        <?php endif; ?>
        </div>
    </div>

    <?php
        break;
    endswitch;
}
endif;

/**
 * Comment Reply JS
 */
function comments_queue_js(){
if ( (!is_admin()) && is_singular() && comments_open() && get_option('thread_comments') )
  wp_enqueue_script( 'comment-reply' );
}
add_action('wp_print_scripts', 'comments_queue_js');

/**
 * Özel Giriş Sayfası Logosu
 */
function keremiya_login_logo(){
    if( keremiya_get_option('dashboard_logo') )
    echo '<style type="text/css"> h1 a {  background-image:url('.keremiya_get_option('dashboard_logo').') !important; height: 100px !important; text-indent: -9999px !important; background: transparent; } </style>';
}  
add_action('login_head',  'keremiya_login_logo'); 

/**
 * Özel Giriş Sayfası URL
 */
function the_url( $url ) {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'the_url' );

/**
 * Özel Giriş Sayfası Başlık
 */
function change_title_on_logo() {
    return get_bloginfo( 'name' );
}
add_filter('login_headertitle', 'change_title_on_logo');

/**
 * Keremiya Favicon
 */
function keremiya_favicon() {
    $default_favicon = get_template_directory_uri()."/favicon.ico";
    $custom_favicon = keremiya_get_option('favicon');
    $favicon = (empty($custom_favicon)) ? $default_favicon : $custom_favicon;
    echo '<link rel="shortcut icon" href="'.$favicon.'" />';
}
add_action('wp_head', 'keremiya_favicon');

/**
 * Keremiya WP Footer
 */
add_action('wp_footer', 'keremiya_wp_footer');
function keremiya_wp_footer() { 
    if ( keremiya_get_option('footer_code')) echo htmlspecialchars_decode( stripslashes(keremiya_get_option('footer_code') )); 
}

/**
 * Özet uzunluğu
 */
function custom_excerpt_length( $length ) {
    return 10;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/**
 * Özet Devamı
 */
function new_excerpt_more( $more ) {
    return '..';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * Özel Gravatar Resmi
 */
function keremiya_custom_gravatar ($avatar = '') {
    $avatar = !$avatar ? keremiya_get_option( 'gravatar' ) : '';
    return $avatar;
}

/**
 * Haberler Devamını Oku Butonu
 */
add_filter( 'the_content_more_link', 'modify_read_more_link' );
function modify_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '">'._k_('Devamını oku &raquo;').'</a>';
}

/**
 * Keremiya Include Layouts
 */
function keremiya_get_layout($name) {

    $dir = get_template_directory() . '/inc/layout/';

    if($name) {
        include ( apply_filters('keremiya_layout_dir', $dir) . $name .'.php' );
    }
}

/**
 * Part Dil Alanı TR Dublaj iconu
 */
add_filter('keremiya_part_replace_lang', 'part_replace_tr_dublaj', 10, 2);
function part_replace_tr_dublaj($text) {
    $values = array('tr', 'türkçe', 'türkçe dublaj', 'tr dublaj');

    if(in_array(trim(strtolower($text)), $values)) {
        return apply_filters('part_replace_tr_dublaj', '<span class="flag-icon tr" title="'._k_('Türkçe Dublaj').'"></span>');
    }
    return $text;
}

/**
 * Part Dil Alanı TR Altyazı iconu
 */
add_filter('keremiya_part_replace_lang', 'part_replace_tr_altyazi', 10, 2);
function part_replace_tr_altyazi($text) {
    $values = array('en', 'türkçe altyazı', 'tr altyazı');

    if(in_array(trim(strtolower($text)), $values)) {
        return apply_filters('part_replace_tr_altyazi', '<span class="flag-icon cc" title="'._k_('Türkçe Altyazı').'"></span>');
    }
    return $text;
}

/**
 * Part Kalite alanı hd iconu
 */
add_filter('keremiya_part_replace_quality', 'part_replace_720p', 10, 2);
function part_replace_720p($text) {
    $values = array('hd', '1080p');

    if(in_array(trim(strtolower($text)), $values)) {
        return '<span class="hd-icon" title="'._k_('hd kalitesinde izle').'">'.$text.'</span>';
    }

    return $text;
}

/**
 * Facebook Beğen Butonu
 */
function keremiya_facebook_like_button($post_id) {
    echo '
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/tr_TR/sdk.js#xfbml=1&version=v2.5";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, "script", "facebook-jssdk"));</script>
    ';

    echo '<div class="fb-like" data-href="'.get_the_permalink( $post_id ).'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>';
}

/**
 * Google Paylaş Butonu
 */
function keremiya_google_share_button($post_id) {
    echo '
    <script src="https://apis.google.com/js/platform.js" async defer>
      {lang: \'tr\'}
    </script>
    ';

    echo '<div class="g-plusone" data-size="medium"></div>';
}

/**
 * Twiiter Paylaş Butonu
 */
function keremiya_twitter_share_button() {
    echo '
    <a href="https://twitter.com/share" class="twitter-share-button" data-lang="tr">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>
    ';
}

/**
 * Film Listeleme Stün Sayıları
 */
function keremiya_get_col_movies() {
    if(is_category() || is_page())
        $col = keremiya_get_option('category_columns');
    elseif( is_author() )
        $col = '6';
    elseif( is_archive() )
        $col = keremiya_get_option('tax_columns');
    else
        $col = keremiya_get_option('columns');

    return $col;
}

/**
 * Dizi Listeleme Stün Sayıları
 */
function keremiya_get_col_series() {
    if(is_category() || is_page())
        
        $col = keremiya_get_option('category_columns');
        if( is_page( keremiya_pages_get_id('settings') ) )
            $col = '4';

    elseif( is_author() )
        $col = '5';
    else
        $col = keremiya_get_option('columns');

    return $col;
}

/**
 * Seo Eklentisi Algılayıcı
 */
function keremiya_is_active_seo_plugins() {

    if ( function_exists( 'aioseop_init_class' ) ) {
        return 'AIOSP';
    }
    elseif ( function_exists( 'wpseo_init' ) ) {
        return 'YOAST';
    } 
    else {
        return false;
    }
}


/*
 * ADD POST
 */
function keremiya_categories_checklist($taxonomy) {

    //Set arguments - don't 'hide' empty terms.
    $args = array(
    'orderby' => 'name',
    'hide_empty' => 0
    );
    
    $categories = get_terms( $taxonomy, $args);
    
    if ( empty( $categories ) ) {
        echo _k_('Kategori yok');
        return;
    }
    echo '<input type="hidden" name="post_category[]" value="0">';
    foreach ( $categories as $category ) {
        $cat_id = $category->term_id;
        $checked_categories = $_POST['post_category'];

        $name = esc_html( apply_filters( 'the_category', $category->name ) );
        if ($checked_categories) {
            $checked = in_array( $cat_id, $checked_categories ) ? ' checked="checked"' : '';
        }
        echo '<li id="post-category-', $cat_id, '"><label for="in-post-category-', $cat_id, '" class="selectit"><input value="', $cat_id, '" type="checkbox" name="post_category[]" id="in-post-category-', $cat_id, '"', $checked, '/> ', $name, "</label></li>";
    }
}

/*
 * KEREMİYA EXCERPT HOOKS
 */

add_filter('keremiya_the_excerpt', 'keremiya_excerpt_nl2br', 10, 1);
function keremiya_excerpt_nl2br($excerpt) {
    return nl2br($excerpt);
}

/*
 * TRAILER
 */
add_filter('keremiya_afis_sistemi', 'keremiya_bilgi_icon');
function keremiya_bilgi_icon( $html ) {
    if( is_single() )
        return;

    global $post;

    $data = keremiya_get_meta('bilgi');

    if( $data )
        $icon = '<span class="bilgi-icon">'.esc_html( $data ).'</span>';

    $html .= $icon;
    return $html;
}

function keremiya_create_nonce( $id ) {
    if( ! get_option( $id ) ) {
        $nonce = wp_create_nonce( $id );
        update_option( $id, $nonce );
    }

    return get_option( $id );
}

function keremiya_verify_nonce( $id, $value ) {
    $nonce = get_option( $id );

    if( $nonce == $value )
        return true;

    return false;
}

function keremiya_get_slider() {
    $style = keremiya_get_option('slider_style');

    if($style == '1')
        keremiya_include('inc/slider/slide-big');
    elseif($style == '2')
        keremiya_include('inc/slider/slide');
}


/*
 * Keremiya Film Arsivi Mobile iconlar
 */
add_action('film_archive_before', 'keremiya_film_archive_mobile_icons', 1, 1);
function keremiya_film_archive_mobile_icons() {
    echo '
    <div class="archive-icons">
        <span class="icon-menu"></span>
        <span class="icon-cancel"></span>
    </div>
    ';
}

/*
 * Taxonomy, Tags Movies Number
 */
function tax_pre_get_posts( $query ) {
    if ( is_tax() || is_tag() )
        $query->set( 'posts_per_page', keremiya_get_option('tax_movies_number') );
}
add_action( 'pre_get_posts', 'tax_pre_get_posts' );

/*
 * Keremiya Linkini Siler
 */
function keremiya_remove_support_link() {
    if( keremiya_get_option('disable_keremiya_support') ) {
        remove_action('keremiya_footer_after', 'keremiya_footer_link');
    }
}
add_action('init', 'keremiya_remove_support_link');

/*
 * Release Slug
 */
function keremiya_arg_release_id() {
    $lang = get_theme_notifier_lang();

    if( $lang == 'tr' )
        return 'release';

    return 'release-year';
}

function keremiya_arg_release_change() {
    return keremiya_arg_release_id();
}
add_action('keremiya_get_release', 'keremiya_arg_release_change');

?>