<?php

/*-----------------------------------------------------------------------------------*/
# Custom Admin Bar Menus
/*-----------------------------------------------------------------------------------*/
function keremiya_admin_bar() {
	global $wp_admin_bar;
	
	if ( !is_super_admin() || !is_admin_bar_showing() ) // Kullanıcı bir yönetici değil ise yönetici çubuğunda bildirim görüntülemeyi devre dışı bırak
	return;

	$wp_admin_bar->add_menu( array(
        'parent' => 0,
        'id' => 'keremiya_page',
        'title' => 'KeremiyaPanel',
        'href' => admin_url( 'admin.php?page=keremiya_panel')
    ) );
	
}
add_action( 'wp_before_admin_bar_render', 'keremiya_admin_bar' );

/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles
/*-----------------------------------------------------------------------------------*/
function keremiya_admin_register() {
    wp_register_script( 'keremiya-admin-checkbox', get_template_directory_uri() . '/panel/js/checkbox.min.js', array( 'jquery' ) , false , false );  
    wp_register_script( 'keremiya-admin-main', get_template_directory_uri() . '/panel/js/keremiya.js', array( 'jquery' ) , false , false );  
    wp_register_script( 'keremiya-admin-tipsy', get_template_directory_uri() . '/panel/js/jquery.tipsy.js', array( 'jquery' ) );  
	wp_register_script( 'keremiya-admin-colorpicker', get_template_directory_uri() . '/panel/js/colorpicker.js', array( 'jquery' ) , false , false );
	wp_register_script( 'keremiya-imdb-importer', get_template_directory_uri() . '/panel/js/imdb-importer.js', array( 'jquery' ) , false , false );   
	
	wp_register_style( 'keremiya-style', get_template_directory_uri().'/panel/style.css', array(), '2016', 'all' ); 

	if ( isset( $_GET['page'] ) && $_GET['page'] == 'keremiya_panel' ) {

		wp_enqueue_script( 'keremiya-admin-colorpicker');  
		wp_enqueue_script( array( 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse', 'jquery-ui-sortable' ) );  
		wp_enqueue_script( 'keremiya-admin-checkbox' ); 
	}
	wp_enqueue_script( 'keremiya-admin-main' );
	wp_enqueue_script( 'keremiya-admin-tipsy' ); 
	wp_enqueue_style( 'keremiya-style' );

}
add_action( 'admin_enqueue_scripts', 'keremiya_admin_register' ); 


/*-----------------------------------------------------------------------------------*/
# To change Insert into Post Text
/*-----------------------------------------------------------------------------------*/
function keremiya_options_setup() {
    global $pagenow;  
	
    if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow )
        add_filter( 'gettext', 'keremiya_replace_thickbox_text'  , 1, 3 ); 
} 
add_action( 'admin_init', 'keremiya_options_setup' ); 
  
function keremiya_replace_thickbox_text($translated_text, $text, $domain) { 
    if ('Insert into Post' == $text) { 
	
        $referer = strpos( wp_get_referer(), 'keremiya-settings' );
        if ( $referer != '' )
            return _kp_('Bu resmi kullan'); 
    }  
    return $translated_text;  
}  
	

/*-----------------------------------------------------------------------------------*/
# get Google Fonts
/*-----------------------------------------------------------------------------------*/
function keremiya_get_googlefonts() {
require ('google-fonts.php');
$google_font_array = json_decode ($google_api_output,true) ;
	
$items = $google_font_array['items'];
	
$options_fonts=array();
array_push($options_fonts, _kp_('Varsayılan Yazı Tipi (Nato Sans)'));
$fontID = 0;
foreach ($items as $item) {
	$fontID++;
	$variants='';
	$variantCount=0;
	foreach ($item['variants'] as $variant) {
		$variantCount++;
		if ($variantCount>1) { $variants .= '|'; }
		$variants .= $variant;
	}
	//$variantText = ' (' . $variantCount . ' Styles' . ')';
	if ($variantCount <= 1) $variantText = '';
	$options_fonts[ $item['family'] . ':' . $variants ] = $item['family']. $variantText;
}
return $options_fonts;
}

/*-----------------------------------------------------------------------------------*/
# Clean options before store it in DB
/*-----------------------------------------------------------------------------------*/
function keremiya_clean_options(&$value) {
  $value = htmlspecialchars(stripslashes($value));
}

	
/*-----------------------------------------------------------------------------------*/
# Options Array
/*-----------------------------------------------------------------------------------*/
$array_options = 
	array(
		"keremiya_home_cats",
		"keremiya_options"
	);
	
	
/*-----------------------------------------------------------------------------------*/
# Save Theme Settings
/*-----------------------------------------------------------------------------------*/	
function keremiya_save_settings ( $data , $refresh = 0 ) {
	global $array_options;
		
	foreach( $array_options as $option ){
		if( isset( $data[$option] )){
			array_walk_recursive( $data[$option] , 'keremiya_clean_options');
			update_option( $option ,  $data[$option]   );
		}
		else{
			delete_option($option);
		}		
	}
	if( $refresh == 2 )  die('2');
	elseif( $refresh == 1 )	die('1');
}
	
	
/*-----------------------------------------------------------------------------------*/
# Save Options
/*-----------------------------------------------------------------------------------*/
add_action('wp_ajax_test_theme_data_save', 'keremiya_save_ajax');
function keremiya_save_ajax() {
	
	check_ajax_referer('keremiya-data', 'security');
	$data = $_POST;
	$refresh = 1;

	if( $data['keremiya_import'] ){
		$refresh = 2;
		$data = unserialize(base64_decode( $data['keremiya_import'] ));
	}
	
	keremiya_save_settings ($data , $refresh );
	
}


/*-----------------------------------------------------------------------------------*/
# Add Panel Page
/*-----------------------------------------------------------------------------------*/
function keremiya_add_admin() {

	add_menu_page(theme_name.' '._kp_('Ayarları'), _kp_('KeremiyaPanel') ,'install_themes', 'keremiya_panel' , 'panel_options', 'dashicons-admin-generic', 26 );
	$theme_page = add_submenu_page('keremiya_panel', theme_name.' '._kp_('Ayarları'), _kp_('Ayarlar'), 'install_themes', 'keremiya_panel' , 'panel_options');
	//add_submenu_page('keremiya_panel',theme_name.' '._kp_('Güncelleme'), _kp_('Güncelleyici'),'install_themes', 'update' , 'iframe_update');
	add_submenu_page('keremiya_panel',theme_name.' '._kp_('Dökümantasyon'), _kp_('Dökümantasyon'),'install_themes', 'docs' , 'iframe_docs');

	function iframe_docs(){
		$docs_url = "http://www.keremiya.com/support/docs/v5/"._kp_('yardim').".html";
		echo "<iframe width='980' height='600' src='$docs_url' frameborder='0' allowfullscreen></iframe>";
	}

	function iframe_licence() {
		$data = get_option('keremiya_licence');
		?>
		<div class="license-status">
		<div class="option-item"><span class="label"><?php _kp('Durum'); ?>:</span> <?php if($data['license_status']){ echo '<strong class="active">'._kp_('Aktif').'</strong>'; } else { echo '<strong class="inactive">'._kp_('Pasif').'</strong>'; } ?></div>
		<?php if($data['license_owner']): ?><div class="option-item"><span class="label"><?php _kp('Sahip'); ?>:</span> <?php echo $data['license_owner']; ?></div><?php endif; ?>
		<?php if($data['license_time']): ?><div class="option-item"><span class="label"><?php _kp('Süre'); ?>:</span> <?php echo _kp_($data['license_time']); ?></div><?php endif; ?>
		<?php if($data['license_package']): ?><div class="option-item"><span class="label"><?php _kp('Paket'); ?>:</span> <?php echo $data['license_package']; ?></div><?php endif; ?>
		<?php if($data['license_key']): ?><div class="option-item"><span class="label"><?php _kp('Lisans Anahtarı'); ?>:</span> <?php echo $data['license_key']; ?></div><?php endif; ?>
	</div>
	<?php
	}

	add_action( 'admin_head-'. $theme_page, 'keremiya_admin_head' );
	function keremiya_admin_head(){
	
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {

		  jQuery('.on-of').checkbox({empty:'<?php echo get_template_directory_uri(); ?>/panel/images/empty.png'});

		  jQuery('form#keremiya_form').submit(function() {
			  var data = jQuery(this).serialize();

			  jQuery.post(ajaxurl, data, function(response) {
				  if(response == 1) {
					  jQuery('#save-alert').addClass('save-done');
					  t = setTimeout('fade_message()', 1000);
				  }
				else if( response == 2 ){
					location.reload();
				}
				else {
					 jQuery('#save-alert').addClass('save-error');
					  t = setTimeout('fade_message()', 1000);
				  }
			  });
			  return false;
		  });
		  
		});
		
		function fade_message() {
			jQuery('#save-alert').fadeOut(function() {
				jQuery('#save-alert').removeClass('save-done');
			});
			clearTimeout(t);
		}
				

		jQuery(function() {
		jQuery( "#cat_sortable" ).sortable({
			placeholder: "ui-state-highlight",
			connectWith: "#cat_sortable",
			start: function(e, ui){
				ui.placeholder.height(ui.item.height());
			},
		});
		});
	</script>
	<?php
		wp_print_scripts('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		do_action('admin_print_styles');
	}
	
	if( isset( $_REQUEST['action'] ) ){
		if( 'reset' == $_REQUEST['action'] ) {
			global $default_data;
			keremiya_save_settings( $default_data );
			header("Location: admin.php?page=keremiya_panel&reset=true");
			die;
		}
	}
}

if(!is_wls()) add_action('admin_menu', 'keremiya_add_admin');

?>