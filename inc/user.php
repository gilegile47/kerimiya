<?php

add_action('wp_ajax_nopriv_keremiya_user_action', 'keremiya_ajax_user_action');
add_action('wp_ajax_keremiya_user_action', 'keremiya_ajax_user_action');

function keremiya_ajax_user_action() {
    // Tüm Veriler
    $form = keremiya_ajax_unserialize( $_POST['form'] );
    
    // Güvenlik kontrolü
    $nonce = $form['nonce'];
  
    if ( ! wp_verify_nonce( $nonce, 'user_ajax-nonce' ) ) { 
        $error = _keremiya_addto_error('Hay Aksi', 'Güvenlik hatası var gibi görünüyor, lütfen sonra tekrar dene.', '..');
        die( $error );
    }

    switch ($form['keremiya_action']) {
        case 'login':
            if( !is_user_logged_in() ) {

                $login = _keremiya_user_login($form['login_username'], $form['login_password'], '');
                
                $message = display_message($login);
  
                if(!$message)
                    echo '1';

                die();

            } else {
                $error = _keremiya_addto_error(_k_('İnanılmaz!'), _k_('Zaten üye girişi yapmışsın.'), '...');
                die( $error );
            }
            break;
        case 'register':
            if( !is_user_logged_in() ) {

                $args = array(
                    'username' => $form['register_username'],
                    'password' => $form['register_password'],
                    'confirm' => $form['register_confirm'],
                    'email' => $form['register_email'],
                    're_email' => $form['register_remail'],
                );

                $register = _keremiya_user_register($args);

                $message = display_message($register);
  
                if(!$message)
                    echo '1';

                die();

            } else {
                $error = _keremiya_addto_error(_k_('İnanılmaz!'), _k_('Zaten kaydolmuşsun.'), '...');
                die( $error );
            }
            break;

        default:
            echo '';
            break;
    }

    exit;
}

function _keremiya_user_login($username, $password, $url) {

    $errors = new WP_Error();

    // Get the user based on the username from the POST
    $user = parse_user($username);

    // Remove html tags from the title and content fields
    $username_stripped = strip_tags($username);
    $password_stripped = strip_tags($password);

    // Validate the Form Data
    if(isEmptyString($username_stripped)) $errors->add('forgot_username', _k_('Kullanıcı adınızı girmeyi unuttunuz.'));
    elseif(isEmptyString($password_stripped)) $errors->add('incorrect_password', _k_("Parolanızı girmeyi unuttunuz."));
    elseif(!username_exists($username_stripped)) $errors->add('username_exists', _k_('Girdiğiniz kullanıcı adı geçersiz.'));
    elseif(!wp_check_password( $password_stripped, $user->user_pass ) ) $errors->add('incorrect_password', _k_("Bu parola yanlış gibi görünüyor, tekrar deneyin."));

    if ( $errors->get_error_code() )
        return $errors;
    
    // Log the User In
    $creds = array();
    $creds['user_login'] = $user->user_login;
    $creds['user_password'] = $password_stripped;
    $creds['remember'] = true;
    $login = wp_signon( $creds, false );
    // Login error
    if ( is_wp_error($login) )
        return $login->get_error_message();
    
    // Redirect
    return true;
}

function _keremiya_user_register($args) {
    require ( ABSPATH . WPINC . '/registration.php' );
    
    $defaults = array(
        'username' => '',
        'password' => '',
        'confirm' => '',
        'email' => '',
        'urlto' => '',
    );

    $args = wp_parse_args($args, $defaults);
    extract( $args, EXTR_SKIP );

    $errors = new WP_Error();

    // Remove html tags from the title and content fields
    $password_stripped = strip_tags($password);
    $confirm_stripped = strip_tags($confirm);
    $email_stripped = strip_tags($email);
    $user_email = $email_stripped;
    $sanitized_user_login = sanitize_user( $username );
    
    // Check to see if User Registration is turned OFF
    if (get_option('users_can_register') == '0') $errors->add('reg_off', _k_('Üzgünüz, şu anda kayıtlar kapalıdır.'));

    // Validate the Form Data
    if(isEmptyString($sanitized_user_login)) $errors->add('forgot_username', _k_('Tüm alanları doldurmalısın.'));
    elseif(! validate_username( $sanitized_user_login )) $errors->add('validate_username', _k_('Bu kullanıcı adı geçersiz. Çünkü illegal karakterler içeriyor. Geçerli bir kullanıcı adı girin.'));
    elseif(username_exists($sanitized_user_login)) $errors->add('username_exists', _k_('Bu kullanıcı adı zaten alınmış, başka birşey deneyin.'));
    elseif(!is_email($email_stripped)) $errors->add('verify_email', _k_('Bu e-posta adresi geçersiz, tekrar deneyin.'));
    elseif(email_exists($email_stripped)) $errors->add('email_exists', _k_('Üzgünüz, görünüşe göre bu e-posta kullanılmış.'));
    elseif(isEmptyString($password_stripped)) $errors->add('forgot_password', _k_('Bir parola girmelisin.'));
    elseif(strlen(trim($password_stripped)) < 7) $errors->add('password_len', _k_("Parola en az yedi karakter uzunluğunda olmalıdır."));
    elseif($password_stripped != $confirm_stripped) $errors->add('passwords_no_match', _k_('Bu parolalar eşleşmiyor. Tekrar deneyin.'));

    do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

    $errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

    if ( $errors->get_error_code() )
        return $errors;

    // Create the user
    $user_data = array(
        'user_login' => $sanitized_user_login,
        'user_pass' => $password_stripped,
        'user_email' => $email_stripped,
    );
    $user_id = wp_insert_user($user_data);
    $user = get_userdata($user_id);
    
    // Set the users Point Rating to 1 for new member
    update_usermeta( $user_id, 'points', '1' );
    
    // Set a flag for a newuser for first time messaging on My Account
    update_usermeta( $user_id, 'newuser', 'yes' );
    update_usermeta( $user_id, 'user_default_icon', '1');
    
    $code = sha1( $user_id . time() );
    update_usermeta( $user_id, 'activated_code', $code);
    update_usermeta( $user_id, 'activated', 'no');

    $activation_link = add_query_arg( array( 'key' => $code, 'user' => $user_id ), keremiya_pages_get_url( 'activation' ) );

    // Send user activation mail
    keremiya_user_activation_mail( $user, $activation_link );

    // Log the User In
    $creds = array();
    $creds['user_login'] = $user->user_login;
    $creds['user_password'] = $password_stripped;
    $creds['remember'] = true;
    $login = wp_signon( $creds, false );
    // Login error
    if ( is_wp_error($login) )
        return $login->get_error_message();

    // Redirect the User to Settings
    return true;
}

function display_message( $message = false ) {
    if( is_wp_error( $message ) ) {
        echo '<div class="message error">' . $message->get_error_message() . '</div>';
        return true;
    }
    return false;
}

function keremiya_ajax_unserialize( $data ) {
    // Unserialize
    parse_str( $data, $parse_data);
    
    // Return Data
    return $parse_data;
}

function isEmptyString($data) {
        return (trim($data) === "" or $data === null);
}

// Add point to User
function addPointToUser($user_id, $meta ='points', $point = '1') {
    $currentPointNumber = get_usermeta($user_id, $meta);
    
    //Add 1 to the current Point Score
    $newPointNumber = $currentPointNumber + $point;  
    update_usermeta( $user_id, $meta, $newPointNumber);
}
// Add point to User
function removePointToUser($user_id, $meta ='points', $point = '1') {
    $currentPointNumber = get_usermeta($user_id, $meta);
    
    //Add 1 to the current Point Score
    $newPointNumber = $currentPointNumber - $point;  
    update_usermeta( $user_id, $meta, $newPointNumber);
}

function parse_user($info = null, $return = 'object') {
    if ( is_null( $info ) ) {
        global $current_user;
        if ( empty( $current_user->ID ) ) return null;
        $info = get_userdata( $current_user->ID );
    }
    elseif ( empty( $info ) ) {
        return null;
    }
    if( $return == 'ID' ) {
        if ( is_object( $info ) ) return $info->ID;
        if ( is_numeric( $info ) ) return $info;
    }
    elseif( $return == 'object' ) {
        if ( is_object( $info ) && $info->ID) return $info;
        if ( is_object( $info )) return get_userdata( $info->ID );
        if ( is_numeric( $info ) ) return get_userdata( $info );
        if ( is_string( $info ) ) return get_userdatabylogin( $info );
    }   
    else {
        return null;
    }
}

function redirect_to_url($url = '') {
    if(!$url) 
        $url = get_bloginfo("url");

    wp_redirect($url);
}

function redirect_to_settings_url() {
    $url = keremiya_pages_get_url('settings');
    wp_redirect($url);
}

function keremiya_user_activation_mail($user, $activation_link) {

    $sitename = get_bloginfo("name");
    if(keremiya_get_option('logo_title'))
        $sitename = preg_replace(array("/{color}/", "/{end}/"), '', keremiya_get_option('logo_title'));

    $siteurl = keremiya_parse_url( get_bloginfo('url') );

    $headers = 'From: '.$sitename.' <wordpress@'.$siteurl.'>' . "\r\n";
    $to = $user->user_email;

    $subject = sprintf(_k_('%s kaydınızı onaylayın'), $sitename);

    $message = sprintf(_k_('Merhaba %s,'), $user->user_login) . "\r\n\r\n";
    $message .= _k_('Kaydınızı tamamlamak için aşağıdaki aktivasyon bağlantısına tıklayın.') . "\r\n";
    $message .= $activation_link . "\r\n\r\n";
    $message .= _k_('Teşekkürler,') . "\r\n";
    $message .= $siteurl . "\r\n";

    wp_mail($to, $subject, $message, $headers);
}

function keremiya_resend_activation_mail($user_ID) {
    $user = get_userdata($user_ID);

    $code = sha1( $user_ID . time() );
    update_usermeta( $user_ID, 'activated_code', $code);
 
    $activation_link = add_query_arg(array( 
        'key' => $code, 
        'user' => $user_ID 
        ), 
    keremiya_pages_get_url( 'activation' )
    );

    keremiya_user_activation_mail($user, $activation_link);

    return sprintf(_k_("Aktivasyon e-postası tekrar gönderildi: %s. Hesabınızı etkinleştirmek için e-postadaki linke tıklayın."), $user->user_email);
}

function keremiya_is_activated($user_id) {

    if(get_the_author_meta( 'activated', $user_id ) == 'yes') {
        return true;
    } elseif(get_the_author_meta( 'activated', $user_id ) == 'no') {
        return false;
    } else {
        return true;
    }

}

function keremiya_user_activated() {
    if ( $_GET['key'] && $_GET['user'] ) {
        $key = keremiya_clear( $_GET['key'] );
        $user_id = keremiya_clear( $_GET['user'] );

        $activated = get_the_author_meta( 'activated', $user_id );
        $activated_code = get_the_author_meta( 'activated_code', $user_id );

        if($activated == 'no' && $key == $activated_code) {
            update_usermeta( $user_id, 'activated', 'yes');
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function keremiya_parse_url($input) {
    // If scheme not included, prepend it
    if (!preg_match('#^http(s)?://#', $input)) {
        $input = 'http://' . $input;
    }

    $urlParts = parse_url($input);

    // remove www
    $domain = preg_replace('/^www\./', '', $urlParts['host']);

    return $domain;
}

function keremiya_clear($d) {
    return htmlspecialchars( trim( $d ) );
}

function filter_handler( $approved , $commentdata )
{
    if($commentdata['user_ID'])
        addPointToUser($commentdata['user_ID'], 'total_comments');

    //update_comment_meta($comment_id, 'votes_up', 0);
    //update_comment_meta($comment_id, 'votes_down', 0);

    return $approved;
}

add_filter( 'pre_comment_approved' , 'filter_handler' , '99', 2 );

?>