<?php

/*-----------------------------------------------------------------------------------*/
# NEWS TAXONOMY
/*-----------------------------------------------------------------------------------*/

// Keremiya Haber Post Type Taxonomy
function keremiya_news_taxonomy() {

    $labels = array(
        'name'                  => _kp_('Haberler'),
        'singular_name'         => _kp_('Haber'),
        'menu_name'             => _kp_('Haberler'),
        'all_items'             => _kp_('Tüm Haberler'),
        'add_new_item'          => _kp_('Yeni Haber Ekle'),
        'add_new'               => _kp_('Yeni Ekle'),
        );
    $args = array(
        'label'                 => _kp_('Haber'),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        'taxonomies'            => array( keremiya_news_category_name() ),
        'rewrite'               => array( 'slug' => keremiya_news_post_type_slug() ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,        
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( keremiya_news_post_type(), $args );

}
add_action( 'init', 'keremiya_news_taxonomy', 0 );

// Keremiya Haber Kategori Taxonomy
function keremiya_news_taxonomy_category() {
    register_taxonomy(
        keremiya_news_category_name(),
        keremiya_news_post_type(),
        array(
            'labels' => array(
                'name' => _kp_('Haber Kategorileri'),
            ),
            'rewrite'       => array( 'slug' => keremiya_news_category_slug() ),
            'show_ui'       => true,
            'show_tagcloud' => false,
            'hierarchical'  => true
        )
    );
}
add_action( 'init', 'keremiya_news_taxonomy_category', 0 );

// Keremiya Haber Etiket Taxonomy
function keremiya_news_taxonomy_tag() {
    register_taxonomy(
        keremiya_news_tag_name(),
        keremiya_news_post_type(),
        array(
            'labels' => array(
                'name' => _kp_('Haber Etiketleri'),
            ),
            'rewrite'       => array( 'slug' => keremiya_news_tag_slug() ),
            'query_var' => true,
            'hierarchical'  => false,
        )
    );
}
add_action( 'init', 'keremiya_news_taxonomy_tag', 0 );

function keremiya_news_post_type() {
    return apply_filters('keremiya_news_post_type', 'news');
}
function keremiya_news_category_name() {
    return apply_filters('keremiya_news_category_name', 'news_category');
}
function keremiya_news_tag_name() {
    return apply_filters('keremiya_news_tag_name', 'news_tag');
}
function keremiya_news_post_type_slug() {
    return apply_filters('keremiya_news_post_type_slug', _k_('haber'));
}
function keremiya_news_category_slug() {
    return apply_filters('keremiya_news_category_slug', _k_('haber-kategori'));
}
function keremiya_news_tag_slug() {
    return apply_filters('keremiya_news_tag_slug', _k_('haber-etiket'));
}

/*-----------------------------------------------------------------------------------*/
#  LIST TAXONOMY
/*-----------------------------------------------------------------------------------*/

function keremiya_list_taxonomy() {

    $labels = array(
        'name'                  => _kp_('Listeler'),
        'singular_name'         => _kp_('Liste'),
        'menu_name'             => _kp_('Listeler'),
        'all_items'             => _kp_('Tüm Listeler'),
        'add_new_item'          => _kp_('Yeni Liste Ekle'),
        'add_new'               => _kp_('Yeni Ekle'),
        );
    $args = array(
        'label'                 => _kp_('Liste'),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
        'rewrite'               => array( 'slug' => keremiya_list_post_type_slug() ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,        
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( keremiya_list_post_type(), $args );

}
//add_action( 'init', 'keremiya_list_taxonomy', 0 );

function keremiya_list_post_type() {
    return apply_filters('keremiya_list_post_type', 'list');
}

function keremiya_list_post_type_slug() {
    return apply_filters('keremiya_list_post_type_slug', 'liste');
}

/*-----------------------------------------------------------------------------------*/
#  OTHER TAXONOMIES
/*-----------------------------------------------------------------------------------*/
function keremiya_taxonomy_meta_keys() {
    return array(
        _k_('yapim') => _k_('Yapım'),
        _k_('yonetmen') => _k_('Yönetmen'),
        _k_('oyuncular') => _k_('Oyuncular'),
        );
}

add_action('init', 'keremiya_post_taxonomies');
function keremiya_post_taxonomies() {
    foreach (keremiya_taxonomy_meta_keys() as $key => $title) {
        register_taxonomy( $key, 'post', array( 'hierarchical' => false, 'label' => $title, 'query_var' => true, 'rewrite' => true ) );
    }
}
?>