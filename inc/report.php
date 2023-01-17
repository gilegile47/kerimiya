<?php

add_action('wp_ajax_nopriv_keremiya_report', 'keremiya_ajax_report');
add_action('wp_ajax_keremiya_report', 'keremiya_ajax_report');

function keremiya_ajax_report() {
    // Tüm Veriler
    $form = keremiya_ajax_unserialize($_POST['form']);

    // Güvenlik kontrolü
    $nonce = $form['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'report_ajax-nonce' ) ) { 
        $error = _keremiya_addto_error(_k_('Hay Aksi'), _k_('Güvenlik hatası var gibi görünüyor.. Lütfen daha sonra tekrar dene.'), '..');
        die( $error );
    }  

    $email = strip_tags($form['report_email']);
    $message = strip_tags($form['report_excerpt']);

    if(!is_email($email)) $error = _k_('Bu e-posta adresi geçersiz, tekrar deneyin.');
    elseif(isEmptyString($message)) $error = _k_('Tüm alanları doldurmalısın.');

    if ( $error != null ) {
        $error = _keremiya_addto_error('Hay Aksi', $error, '..');
        die( $error );
    }

    $title = get_the_title($form['post_id']);
    $url = get_the_permalink($form['post_id']);
    $link = $url .' - ('.$title.')';

    $emailTo = keremiya_get_option('email');
    if (!isset($emailTo) || ($emailTo == '') ){
        $emailTo = get_option('admin_email');
    }

    $subject = sprintf( _k_('[%s] Raporu'), $title);
    $body = _k_('Film').": $link \n\n"._k_('E-Posta').": $email \n\n"._k_('Mesaj').": $message";
    $headers = 'From: '._k_('Rapor Sistemi').' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

    wp_mail($emailTo, $subject, $body, $headers);

    $json = array(
        'error' => false,
        'html' => _k_('Bizi uyardığınız için teşekkürler, en kısa sürede bu sorunu çözeceğiz.'),
    );

    echo json_encode($json);

    exit;
}



?>