<?php

add_action('wp_ajax_nopriv_keremiya_addto', 'keremiya_ajax_addto');
add_action('wp_ajax_keremiya_addto', 'keremiya_ajax_addto');

function keremiya_ajax_addto() {

    // Güvenlik kontrolü
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) { 
        $error = _keremiya_addto_error(_k_('Hay Aksi'), _k_('Güvenlik hatası var gibi görünüyor, lütfen sonra tekrar dene.'), '..');
        die( $error );
    }

    // İşlem türü
    $w = $_POST['this'];

    // post id
    $post_id = $_POST['post_id'];

    switch ($w) {
        case 'later':
            if( is_user_logged_in() ) {
                _keremiya_addto_later($post_id);
            } else {
                $error = _keremiya_addto_error(_k_('Hay Aksi'), _k_('Sadece üyeler "İzleme" listesi oluşturabilir.'), keremiya_popup_login_url());
                die( $error );
            }
            break;
        case 'fav':
            if( is_user_logged_in() ) {
                _keremiya_addto_fav($post_id);
            } else {
                $error = _keremiya_addto_error(_k_('Hay Aksi'), _k_('Sadece üyeler "Favori" listesi oluşturabilir.'), keremiya_popup_login_url());
                die( $error );
            }
            break;

        default:
            echo '';
            break;
    }

    exit;
}

function _keremiya_addto_error($title, $content, $footer) {
    $json = array(
        'error' => true,
        'title' => $title,
        'content' => $content,
        'footer' => $footer
    );
    return json_encode($json);
}

function _keremiya_addto_later($post_id) {

    $later = _keremiya_addto_data($post_id, 'addto_later', 'later_total');

    if(!$later) {
        _keremiya_addto_remove_data($post_id, 'addto_later', 'later_total');
    }

    $json = array(
        'error' => false,
        'html' => keremiya_addto_button($post_id, 'addto_later', _k_('İzleme Listesi'), _k_('Listeden Çıkar'), 'icon-clock')
    );

    echo json_encode($json);
}

function _keremiya_addto_fav($post_id) {
    
    $fav = _keremiya_addto_data($post_id, 'addto_fav', 'fav_total'); 

    if(!$fav) {
        _keremiya_addto_remove_data($post_id, 'addto_fav', 'fav_total');
    }

    $json = array(
        'error' => false,
        'html' => keremiya_addto_button($post_id, 'addto_fav', _k_('Favoriler'), _k_('Favorilerden Çıkar'), 'icon-star'),  
    );

    echo json_encode($json);
}

function _keremiya_addto_data($post_id, $meta, $post_meta){

    // Kullanıcı ID
    $user_ID = get_current_user_id();

    // Array Post ID
    $sid = array($post_id);

    // Veriler
    $exists = unserialize( get_user_meta($user_ID, $meta, true) );

 
    if( is_array( $exists ) ){
        $narray = array_merge($exists, $sid);
    } else {
        $narray = $sid;
        $exists = array();
    }

    if( !in_array($post_id, $exists) ) {
    
        // serialize data
        $seri = serialize($narray);
        update_user_meta($user_ID, $meta, $seri);

        $toplam = (int) get_post_meta($post_id, $post_meta, true);
        update_post_meta($post_id, $post_meta, ++$toplam);

        addPointToUser($user_ID, $post_meta);

        return true;
    }
    return false;
}


function _keremiya_addto_remove_data($post_id, $meta, $post_meta){
    
    // Kullanıcı ID
    $user_ID = get_current_user_id();

    // Array Post ID
    $sid = array($post_id);

    // Veriler
    $exists = unserialize( get_user_meta($user_ID, $meta, true) );

    // Silinecek veriyi bul
    $key = array_search( $post_id, $exists );

    if($key !== false) {
        // Veriyi sil
        unset( $exists[$key] );
        $exists = array_values($exists);
        // serialize data
        $seri = serialize( $exists );
        update_user_meta($user_ID, $meta, $seri);
          
        $toplam = (int) get_post_meta($post_id, $post_meta, true);
        update_post_meta($post_id, $post_meta, --$toplam);

        addPointToUser($user_ID, $post_meta);

        return true;
    }
    return false;
}

function keremiya_addto_button($post_id, $meta, $title, $remove, $icon){
    // Kullanıcı ID
    $user_ID = get_current_user_id();

    if(is_user_logged_in()){
        $meta = unserialize(get_user_meta($user_ID, $meta, true));
        $link = '';
        if( is_array($meta) ){
            if( !in_array($post_id, $meta) ){
                $link .= '<span class="'.$icon.'"></span>'._k_($title);
            } else {
                $link .= '<span class="icon-cancel"></span>'._k_($remove);
            }
        } else {
            $link .= '<span class="'.$icon.'"></span>'._k_($title);
        }
    } else {
    $link .= '<span class="'.$icon.' no-logged-in" data-type="no-logged"></span>'._k_($title);
    }
    return $link;
}

function keremiya_addto_watchlist($echo = 1) {

    if( ! keremiya_get_option('addto') )
        return;

    global $post;

    $user_ID = get_current_user_id();
    $meta = 'addto_later';

    if(is_user_logged_in()){
        $meta = unserialize(get_user_meta($user_ID, $meta, true));
        if( is_array($meta) ){
            if( !in_array($post->ID, $meta) ){
                $class = 'addto watchlist ribbon tooltip-s';
                $icon = 'icon-plus';
                $title = _k_('İzleme Listesine Ekle');
            } else {
                $class = 'watchlist ribbon active tooltip-s';
                $icon = 'icon-ok';
                $title = _k_('İzleme Listenizde');
            }
        } else {
            $class = 'addto watchlist ribbon tooltip-s';
            $icon = 'icon-plus';
            $title = _k_('İzleme Listesine Ekle');
        }
    } else {
        $class = 'addto watchlist no-logged-in ribbon tooltip-s';
        $icon = 'icon-plus';
        $title = _k_('İzleme Listesine Ekle');
    }

    $output = '';
    $output .= '<span class="'.$class.'" data-is="1" data-id="'.$post->ID.'" data-this="later" title="'.$title.'">';
    $output .= '<span class="'.$icon.'"></span>';
    $output .= '</span>';
    
    if (!$echo) 
        return $output;
    
    echo $output;
}

function keremiya_get_user_addto_count($user_ID, $meta) {
    $data = unserialize(get_user_meta($user_ID, $meta, true));
    
    if($data)
        return count($data);
}

?>