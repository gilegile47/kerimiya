<?php

function update_page_add_admin() {
    add_submenu_page('keremiya_panel',theme_name.' '._kp_('Güncelleyici'), _kp_('Güncelleyici'), 'install_themes', 'keremiya_theme_update' , 'keremiya_update_page');
}
add_action('admin_menu', 'update_page_add_admin');

function keremiya_update_page() {

    $method = get_filesystem_method();
    
    // Theme File Directory
    $to = get_template_directory();

    if(isset($_POST['password'])){
        $cred = $_POST;
        $filesystem = WP_Filesystem($cred);
    }
    elseif(isset($_POST['keremiya_ftp_cred'])){
        $cred = unserialize(base64_decode($_POST['keremiya_ftp_cred']));
        $filesystem = WP_Filesystem($cred);
    }
    else {
        $filesystem = WP_Filesystem();
    }

    $url = admin_url( 'admin.php?page=keremiya_theme_update' );
    ?>
        <div class="wrap update-page">
        <?php
            if($filesystem == false){

            request_filesystem_credentials ( $url );

            }  else {

            // Test if new version
            $upd = true;

        ?>
        <h1><?php _kp('Güncelleyici'); ?></h1>
        <p style="margin:0"><?php printf(_kp_('Şu anda Keremiya %s sürümünü kullanıyorsunuz.'), '<strong>'. theme_ver .'</strong>'); ?></p>
        <p style="margin-top:0"><?php _kp('Güncelleme yapmadan önce temanızın yedeğini almanızı öneriyoruz.'); ?></p>

        <div class="upload-theme" style="display:block">
            <p class="install-help"><?php _kp('Eğer elinizde .zip biçiminde bir dosya varsa, buradan yükleyerek güncelleyebilirsiniz.'); ?></p>
            
            <span style="display:none"><?php echo $method; ?></span>
            <form method="post" enctype="multipart/form-data" id="keremiya_update" class="wp-upload-form" action="<?php /* echo $url; */ ?>">
                
                <input type="file" name="keremiya_update_url" />
                <input type="submit" id="install-theme-submit" class="button" value="<?php _kp('Güncelle'); ?>" disabled="" />

                <?php wp_nonce_field('keremiya_update_form', '_wpnonce'); ?>
                <input type="hidden" name="keremiya_update_save" value="save" />
                <input type="hidden" name="keremiya_ftp_cred" value="<?php echo esc_attr( base64_encode(serialize($_POST))); ?>" />
            </form>
            <p style="text-align:center"><a href="<?php echo esc_url( 'https://www.keremiya.com/destek/dokumantasyon/Keremiya-v5/#upgrading' ); ?>" target="_blank" title="<?php echo _kp_( 'Güncelleme yapmadan önce Keremiya Güncelleme Notlarını okumanızı öneriyoruz.'); ?>"><strong><?php _kp( 'Güncelleme Notlarını Okuyun' ); ?></strong></a></p>
        </div>
        <?php } ?>
        </div>
    <?php
};

function keremiya_update_head(){

  if(isset($_REQUEST['page'])){

    // Sanitize page being requested.
    $_page = esc_attr( $_REQUEST['page'] );

    if( $_page == 'keremiya_theme_update'){

        //Setup Filesystem
        $method = get_filesystem_method();

        if( isset( $_POST['keremiya_ftp_cred'] ) ) {
            $cred = unserialize( base64_decode( $_POST['keremiya_ftp_cred'] ) );
            $filesystem = WP_Filesystem($cred);
        } else {
           $filesystem = WP_Filesystem();
        }

        if($filesystem == false && $_POST['upgrade'] != 'Proceed'){

            function keremiya_update_filesystem_warning() {
                    $method = get_filesystem_method();
                    echo "<div id='filesystem-warning' class='error fade'><p>Failed: Filesystem preventing downloads. ( ". $method .")</p></div>";
                }
                add_action( 'admin_notices', 'keremiya_update_filesystem_warning' );
                return;
        }
        if(isset($_REQUEST['keremiya_update_save'])){

            // Sanitize action being requested.
            $_action = esc_attr( $_REQUEST['keremiya_update_save'] );

        if( $_action == 'save' && wp_verify_nonce( $_POST['_wpnonce'], 'keremiya_update_form' )){

            if( $_FILES["keremiya_update_url"]["error"] > 0 )
                return;

            $filename = explode('-', $_FILES["keremiya_update_url"]['name']);

            if( $filename['0'] != 'keremiya' ) {
                function keremiya_update_invalid_warning() {
                    echo "<div id='keremiya-invalid-warning' class='error fade'><p>"._kp_('Hata: Uyumsuz Güncelleme Paketi')."</p></div>";
                }
                add_action( 'admin_notices', 'keremiya_update_invalid_warning' );
                return;
            }

        $temp_file_addr = $_FILES["keremiya_update_url"]['tmp_name'];

        //Unzip it
        global $wp_filesystem;
        $to = $wp_filesystem->wp_content_dir() . "/themes/" . get_option( 'template');

        $dounzip = unzip_file($temp_file_addr, $to);

        unlink($temp_file_addr); // Delete Temp File

        if ( is_wp_error($dounzip) ) {

            //DEBUG
            $error = esc_html( $dounzip->get_error_code() );
            $data = $dounzip->get_error_data($error);
            //echo $error. ' - ';
            //print_r($data);

            if($error == 'incompatible_archive') {
                //The source file was not found or is invalid
                function keremiya_update_no_archive_warning() {
                    echo "<div id='keremiya-no-archive-warning' class='error fade'><p>"._kp_('Hata: Uyumsuz Arşiv')."</p></div>";
                }
                add_action( 'admin_notices', 'keremiya_update_no_archive_warning' );
            }
            if($error == 'empty_archive') {
                function keremiya_update_empty_archive_warning() {
                    echo "<div id='keremiya-empty-archive-warning' class='error fade'><p>"._kp_('Hata: Boş Arşiv')."</p></div>";
                }
                add_action( 'admin_notices', 'keremiya_update_empty_archive_warning' );
            }
            if($error == 'mkdir_failed') {
                function keremiya_update_mkdir_warning() {
                    echo "<div id='keremiya-mkdir-warning' class='error fade'><p>"._kp_('Hata: mkdir Hatası')."</p></div>";
                }
                add_action( 'admin_notices', 'keremiya_update_mkdir_warning' );
            }
            if($error == 'copy_failed') {
                function keremiya_update_copy_fail_warning() {
                    echo "<div id='keremiya-copy-fail-warning' class='error fade'><p>"._kp_('Hata: Kopyalama Başarısız')."</p></div>";
                }
                add_action( 'admin_notices', 'keremiya_update_copy_fail_warning' );
            }

            return;

        }

        function keremiya_updated_success() {
            echo "<div id='framework-upgraded' class='updated fade'><p>"._kp_('Yeni tema dosyaları başarıyla yüklendi, çıkarıldı ve güncellendi.')."</p></div>";
        }
        
        add_action( 'admin_notices', 'keremiya_updated_success' );

        }
    }
    } //End user input save part of the update
 }
}

add_action( 'admin_head', 'keremiya_update_head' );

?>