<?php

add_action("admin_init", "posts_init");
function posts_init(){
	add_meta_box("post_options", _kp_("KeremiyaPanel"), "post_options", "post", "normal", "high");
	add_meta_box("news_options", _kp_("KeremiyaPanel"), "news_options", "news", "normal", "high");
	add_meta_box("page_options", _kp_("KeremiyaPanel"), "page_options", "page", "normal", "high");
}

function post_options(){
	global $post ;
	$get_meta = get_post_custom($post->ID);

	$categories_obj = get_categories('hide_empty=0');
	$categories = array(
		'' => _kp_('Otomatik Seçim'),
	);
	foreach ($categories_obj as $pn_cat) {
		$categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
	}
?>
	<div id="keremiya-panel-post-tabs" class="clearfix">
	<div class="keremiya-panel-post-tab">

		<li class="tab film-info active"><a href="#tab1"><span></span><?php _kp('Film Bilgileri'); ?></a></li>
		<li class="tab film-details"><a href="#tab2"><span></span><?php _kp('Detaylar'); ?></a></li>
		<?php if( keremiya_get_option('seo_active') ): ?><li class="tab film-seo last-item-li"><a href="#tab3"><span class="dashicons-before"></span><?php _kp('SEO Seçenekleri'); ?></a></li><?php endif; ?>
		<li class="tab film-gallery"><a href="#tab4"><span></span><?php _kp('Galeri'); ?></a></li>
		<div class="clear"></div>
	</div> <!-- .keremiya-panel-tabs -->
	
		<div id="tab1" class="tabs-post-wrap">
		<div class="keremiyapanel-item">
		<div class="option-item" id="keremiya_excerpt">
		<span class="label" for="excerpt"><?php _kp('Özet'); ?></span>
			<textarea style="direction:ltr; text-align:left; width:65%; margin: 0; height: auto;" name="excerpt" id="excerpt" type="textarea" cols="100%" rows="4"><?php echo get_the_excerpt(); ?></textarea>		
		</div>

			<?php
			if( keremiya_get_option('imdb_importer') ) {
				wp_enqueue_script( 'keremiya-imdb-importer');
				keremiya_post_options(				
					array(	"name" => _kp_("IMDB ID:"),
							"id" => "imdb-id",
							"type" => "text",
							"help" => _kp_('IMDB Botu. Değişiklikler yazı yayımlandığında veya güncellendiğinde geçerli olur.')));
			}// IMDB IMPORTER
			keremiya_post_options(				
				array(	"name" => _kp_("Yapım:"),
						"id" => "yapim",
						"tax" => 'true',
						"type" => "text",
						"help" => _kp_('Sadece film yılını yazın. Örneğin: 2016') ));
			keremiya_post_options(				
				array(	"name" => _kp_("IMDB Puanı:"),
						"id" => "imdb",
						"type" => "text",
						"help" => _kp_('Örneğin: 8.6') ));
			keremiya_post_options(				
				array(	"name" => _kp_("Yönetmen:"),
						"id" => "yonetmen",
						"tax" => 'true',
						"type" => "text"));
			keremiya_post_options(				
				array(	"name" => _kp_("Oyuncular:"),
						"id" => "oyuncular",
						"tax" => 'true',
						"type" => "textarea"));
			keremiya_post_options(				
				array(	"name" => _kp_("Diğer Adları:"),
						"id" => "diger-adlari",
						"type" => "text"));

			keremiya_post_options(				
				array(	"name" => _kp_("Dil:"),
						"id" => "afisbilgi",
						"type" => "select",
						"options" => array(
							'' => '',
							'Turkce Dublaj' => _kp_('Türkçe Dublaj'),
							'Turkce Altyazi' => _kp_('Türkçe Altyazı'),
							'Turkce Dublaj ve Altyazi' => _kp_('Türkçe Dublaj ve Altyazı'),)));
			keremiya_post_options(				
				array(	"name" => _kp_("Kalite"),
						"id" => "partbilgi",
						"type" => "select",
						"options" => array(
							'' => '',
							'4k' => _kp_('4K'),
							'1080p' => _kp_('1080p'),
							'720p' => _kp_('HD Kalite'),
							'480p' => _kp_('Normal Kalite'),
							'360p' => _kp_('Düşük Kalite'))));
			keremiya_post_options(				
				array(	"name" => _k_("Bilgi"),
						"id" => "bilgi",
						"type" => "text",
						"help" => "Örneğin; Fragman, Yerli Film, Vizyonda vb. tür bilgiler ekleyebilirsiniz."));
			keremiya_post_options(				
				array(	"name" => _kp_("Resim:"),
						"id" => "resim",
						"type" => "text",
						"help" => _kp_("Başka sunucudan resim adresi belirleyebilirsiniz.")));
			?>
			<a href="javascript:;" onclick="jQuery('.video-other-settings').slideToggle(200);" class="other-settings-link"><?php _kp("Ekstra"); ?></a>
			<div class="clear"></div>
			<div class="video-other-settings" style="display:none">
			<?php
			keremiya_post_options(				
				array(	"name" => _kp_("İzlenme Sayısı"),
						"id" => "views",
						"type" => "small-text"));
			?>
				<p style="margin-left: 16px;padding:0"><?php _kp('&lt;!--baslik: Tab Adı, Dil Adı, Kalite Adı--&gt; şeklinde part sistemini kullanabilirsiniz.'); ?></p>
			</div>
		</div>
		</div>
	
		<div id="tab2" class="tabs-post-wrap">
		<div class="keremiyapanel-item">
			<?php
			keremiya_post_options(				
				array(	"name" => _kp_("Detaylı Konu:"),
						"id" => "review",
						"type" => "textarea",
						"help" => _kp_('Film hakkında detaylı bilgi girmeniz için oluşturuldu.')));
			keremiya_post_options(				
				array(	"name" => _kp_("İlgili Filmler:"),
						"id" => "seri",
						"type" => "text",
						"help" => _kp_("Örneğin: 'Batman Serisi' adını anahtar kelime olarak belirleyin. Bu anahtar kelimeyi kullanan tüm filmler listelenir.")));
			keremiya_post_options(				
				array(	"name" => _kp_("İlgili Haberler:"),
						"id" => "news",
						"type" => "text",
						"help" => _kp_("Örneğin: 'Batman Haberleri' adını anahtar kelime olarak belirleyin. Bu anahtar kelimeyi kullanan tüm haberler listelenir.")));
			keremiya_post_options(				
				array(	"name" => _kp_("Yayın Tarihi:"),
						"id" => "yayin-tarihi",
						"type" => "text"));
			keremiya_post_options(				
				array(	"name" => _kp_("Senaryo:"),
						"id" => "senaryo",
						"type" => "text"));
			keremiya_post_options(				
				array(	"name" => _kp_("Ödüller:"),
						"id" => "oduller",
						"type" => "text"));
			keremiya_post_options(				
				array(	"name" => _kp_("Yapım Şirketi:"),
						"id" => "yapim-sirketi",
						"type" => "text"));
			keremiya_post_options(				
				array(	"name" => _kp_("Bütçe:"),
						"id" => "butce",
						"type" => "text"));
			keremiya_post_options(
				array(	"name" => _kp_("Box Office:"),
						"id" => "box-office",
						"type" => "text"));
			keremiya_post_options(				
				array(	"name" => _kp_("Benzer Filmler:"),
						"id" => "similar",
						"type" => "select",
						"options" => $categories,
						"help" => _kp_("Benzer filmler için istediğiniz bir kategoriyi seçebilirsiniz.")));
			?>
		
		<div class="option-item" id="keremiya_allow_comments">
		<span class="label" for="comment_status"><?php _kp('Yorumlara izin ver'); ?></span>
				<input class="on-of" name="comment_status" type="checkbox" id="comment_status" value="open" <?php checked($post->comment_status, 'open'); ?> />			
		</div>
		
		</div>
		</div>

		<?php if( keremiya_get_option('seo_active') && !keremiya_is_active_seo_plugins() ): ?>
		<div id="tab3" class="tabs-post-wrap">
<script>
jQuery(document).ready(function() {

var title = jQuery("#keremiya_seotitle").val(),
	title2 = jQuery("#title").val();

if( !title )
	title = title2;

jQuery(".keremiya_seotitle").text(title);
var str = jQuery("#keremiya_seodescription").val();
jQuery(".keremiya_seodescription").text(str);

jQuery("#keremiya_seotitle").bind("input", function() {
jQuery(".keremiya_seotitle").text(jQuery(this).val());
});
jQuery("#keremiya_seodescription").bind("input", function() {
jQuery(".keremiya_seodescription").text(jQuery(this).val());
});
});
</script>

<style>
#keremiya_snippet { font-family: arial,sans-serif; font-size: 13px; font-style: normal; height:auto; max-height: 70px; overflow:hidden; position:relative;}
#keremiya_snippet span.label { display: block; margin-bottom: 3px;}
.keremiya_seotitle { color: #1A0DAB; font-size: 18px; cursor: pointer; margin-bottom: 1px; padding:0px; max-height: 19px; overflow: hidden;}
.keremiya_seodescription { color:#333; width:99%; max-height:38px; overflow: hidden; padding:0px; margin:0px;}
.keremiya_snippet_link { color: #006621; font-size: 14px; padding:0px; margin:0px; margin-top:-1px;}
.snippet_preview { float:left; width: 70%; height:auto; margin: 0px; padding:0px;}
</style>

<div class="option-item" id="keremiya_snippet">
<span class="label"><?php _kp('Önizleme'); ?></span>
<div class="snippet_preview">
<div class="keremiya_seotitle"></div>
<div class="keremiya_snippet_link"><?php echo get_permalink(); ?></div>
<div class="keremiya_seodescription"></div>
</div>
<a original-title="<?php _kp('Bu bölüm arama motoru sonuçlarında neye benzeyeceğini gösteren bir önizlemedir.'); ?>" class="keremiya-help tooltip"></a>
</div>
		<div class="keremiyapanel-item">
			<?php
			keremiya_post_options(				
				array(	"name" => _kp_("Başlık"),
						"id" => "keremiya_seotitle",
						"type" => "text"));

			keremiya_post_options(				
				array(	"name" => _kp_("Açıklama"),
						"id" => "keremiya_seodescription",
						"type" => "textarea"));

			keremiya_post_options(				
				array(	"name" => _kp_("Anahtar Kelimeler"),
						"id" => "keremiya_seokeywords",
						"type" => "text"));
			?>
		</div>

		</div>
		<?php endif; ?>

		<div id="tab4" class="tabs-post-wrap">
			<div class="keremiyapanel-item">
				<p><?php _kp('Galeri oluşturmak için öne çıkan görsel belirlerken birden fazla resim ekleyin.'); ?></p>
			<?php
				$args = array(
					'post_parent' => $post->ID,
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'posts_per_page' => -1,
					'orderby' => 'menu_order',
					'order' => 'ASC',
				);
				 
				$attachments = get_children( $args );
				$images = count( $attachments );
			?>

			<?php if($images > 1): ?>
				<div id="images" class="gallery" data-max="<?php echo $images; ?>">
				<?php $i = 0; foreach ( $attachments as $attachment_id => $attachment ) { $i++; ?>
					<div class="thumb" data-num="<?php echo $i; ?>">
						<?php the_attachment_link( $attachment_id , false ); ?>
					</div>
				<?php }; ?>
				</div>
			<?php endif; ?>
			</div>
		</div>
	<?php wp_nonce_field( basename( __FILE__ ), 'keremiya_meta_box_nonce' ); ?>
	</div> <!-- .keremiya-panel-post-tabs -->
  <?php
}

/*********************************************************************************************/

function page_options(){
	global $post ;
	$get_meta = get_post_custom($post->ID);

?>
	<div id="keremiya-panel-post-tabs" class="clearfix">
	<div class="keremiya-panel-post-tab">

		<li class="tab film-info active"><a href="#tab1"><span></span><?php _kp('Sayfa Bilgileri'); ?></a></li>
		<?php if( keremiya_get_option('seo_active') ): ?><li class="tab film-seo last-item-li"><a href="#tab3"><span class="dashicons-before"></span><?php _kp('SEO Seçenekleri'); ?></a></li><?php endif; ?>
		<div class="clear"></div>
	</div> <!-- .keremiya-panel-tabs -->
	
		<div id="tab1" class="tabs-post-wrap">
		<div class="keremiyapanel-item">
		<?php	

			keremiya_post_options(				
				array(	"name" => _kp_("Resim:"),
						"id" => "resim",
						"type" => "text"));
		?>

		</div>
		</div>
	
		<?php if( keremiya_get_option('seo_active') ): ?>
		<div id="tab3" class="tabs-post-wrap">
		<div class="keremiyapanel-item">
			<?php
			keremiya_post_options(				
				array(	"name" => _kp_("Başlık"),
						"id" => "keremiya_seotitle",
						"type" => "text"));
			?>

			<?php
			keremiya_post_options(				
				array(	"name" => _kp_("Açıklama"),
						"id" => "keremiya_seodescription",
						"type" => "textarea"));
			?>
		</div>

		</div>
		<?php endif; ?>
	<?php wp_nonce_field( basename( __FILE__ ), 'keremiya_meta_box_nonce' ); ?>
	</div> <!-- .keremiya-panel-post-tabs -->


  <?php
}

function news_options(){
	global $post ;
	$get_meta = get_post_custom($post->ID);

?>
	<div id="keremiya-panel-post-tabs" class="clearfix">
	<div class="keremiya-panel-post-tab">

		<li class="tab film-info active"><a href="#tab1"><span></span><?php _kp('Haber Bilgileri'); ?></a></li>
		<?php if( keremiya_get_option('seo_active') ): ?><li class="tab film-seo last-item-li"><a href="#tab3"><span class="dashicons-before"></span><?php _kp('SEO Seçenekleri'); ?></a></li><?php endif; ?>
		<div class="clear"></div>
	</div> <!-- .keremiya-panel-tabs -->
	
		<div id="tab1" class="tabs-post-wrap">
		<div class="keremiyapanel-item">
		<?php	

			keremiya_post_options(				
				array(	"name" => _kp_("İlgili Filmler:"),
						"id" => "news",
						"type" => "text",
						"help" => _kp_("Örneğin: 'Batman Haberleri' adını anahtar kelime olarak belirleyin. Bu anahtar kelimeyi kullanan tüm filmlerde listelenir.")));
		?>

		</div>
		</div>
	
		<?php if( keremiya_get_option('seo_active') ): ?>
		<div id="tab3" class="tabs-post-wrap">
		<div class="keremiyapanel-item">
			<?php
			keremiya_post_options(				
				array(	"name" => _kp_("Başlık"),
						"id" => "keremiya_seotitle",
						"type" => "text"));
			?>

			<?php
			keremiya_post_options(				
				array(	"name" => _kp_("Açıklama"),
						"id" => "keremiya_seodescription",
						"type" => "textarea"));
			?>
		</div>

		</div>
		<?php endif; ?>
	<?php wp_nonce_field( basename( __FILE__ ), 'keremiya_meta_box_nonce' ); ?>
	</div> <!-- .keremiya-panel-post-tabs -->

  <?php
}

add_action('save_post', 'save_post');
function save_post( $post_id ){
	global $post;
	
	// check security
	if ( !isset( $_POST['keremiya_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['keremiya_meta_box_nonce'], basename( __FILE__ ) ) ){
		return $post_id;
	}

	// return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return $post_id;
	}

	// check
	if ( 'page' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id ) )
		return $post_id;
	elseif ( 'post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id ) )
		return $post_id;

	// ALL FIELDS
 	$custom_meta_fields = array(
		'yapim',
		'imdb',
		'imdb-id',
		'yonetmen',
		'oyuncular',
		'diger-adlari',
		'yayin-tarihi',
		'oduller',
		'senaryo',
		'yapim-sirketi',
		'butce',
		'box-office',
		'keremiya_seotitle',
		'keremiya_seodescription',
		'keremiya_seokeywords',
		'partbilgi',
		'afisbilgi',
		'views',
		'seri',
		'news',
		'review',
		'resim',
		'similar',
		'bilgi'
		);

 	// UPDATE DATA
	foreach( $custom_meta_fields as $meta_field ):

		$data = stripslashes( $_POST[$meta_field] );

		if ( get_post_meta( $post_id, $meta_field ) == '' )
			add_post_meta( $post_id, $meta_field, $data, true );
		elseif ( $data != get_post_meta( $post_id, $meta_field, true ) )
			update_post_meta( $post_id, $meta_field, $data );
		elseif ( $data == '' )
			delete_post_meta( $post_id, $meta_field, get_post_meta( $post_id, $meta_field, true ) );

	endforeach;

	// AUTO THUMBNAIL
	if( keremiya_get_option('auto_thumbnail') && keremiya_get_option('imdb_importer') && !has_post_thumbnail() ) {

		$new_thumbnail = $_POST['resim'];

		if ( $new_thumbnail ) {
			keremiya_video_upload_image( $new_thumbnail, $post->ID );
		}
	}

}

function keremiya_video_upload_image($new_thumbnail, $video_id) {
	if ( $new_thumbnail != null && !has_post_thumbnail() ) {

	$error = '';
		$ch = curl_init();
		$timeout = 0;
		curl_setopt ( $ch, CURLOPT_URL, $new_thumbnail );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_FAILONERROR, true ); // Return an error for curl_error() processing if HTTP response code >= 400
			$image_contents = curl_exec( $ch );
			if ( curl_error( $ch ) != null || $image_contents == null ) {
				$curl_error = '';
					if ( curl_error( $ch ) != null ) {
						$curl_error = '<br /><a href="http://curl.haxx.se/libcurl/c/libcurl-errors.html">Libcurl error</a> ' . curl_errno( $ch ) . ': <code>' . curl_error( $ch ) . '</code>';
					}
				$error = new WP_Error( 'thumbnail_retrieval', __( 'Error retrieving a thumbnail from the URL <a href="' . $new_thumbnail . '">' . $new_thumbnail . '</a>' . $curl_error . '. If opening that URL in your web browser shows an image, the problem may be related to your web server and might be something your server administrator can solve.' ) );
			}
			curl_close( $ch );

		if ( $error != null ) {
				return $error;
		}

			$upload = wp_upload_bits( basename( $new_thumbnail ), null, $image_contents );

			$new_thumbnail = $upload['url'];

			$filename = $upload['file'];

			$wp_filetype = wp_check_filetype( basename( $filename ), null );
			$attachment = array(
				'post_mime_type'	=> $wp_filetype['type'],
				'post_title'		=> get_the_title($video_id),
				'post_content'		=> '',
				'post_status'		=> 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $filename, $video_id );
			// you must first include the image.php file
			// for the function wp_generate_attachment_metadata() to work
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
			wp_update_attachment_metadata( $attach_id,  $attach_data );

			// Add hidden custom field with thumbnail URL
			if ( !update_post_meta( $video_id, '_video_thumbnail', $new_thumbnail ) ) add_post_meta( $video_id, '_video_thumbnail', $new_thumbnail, true );

			// Set attachment as featured image if enabled
			if ( !update_post_meta( $video_id, '_thumbnail_id', $attach_id ) ) add_post_meta( $video_id, '_thumbnail_id', $attach_id, true );
			
	}
}


/*********************************************************/

function keremiya_post_options($value){
	global $post;
?>

	<div class="option-item" id="<?php echo $value['id'] ?>-item">
		<span class="label"><?php  echo $value['name']; ?></span>
	<?php
		$id = $value['id'];

		$current_value = stripslashes( get_post_meta( $post->ID, $id, true ) );

	switch ( $value['type'] ) {
	
		case 'text': ?>
			<input name="<?php echo $id; ?>" id="<?php echo $id; ?>" type="text" value="<?php echo $current_value; ?>" />
			<input type="hidden" name="<?php echo $id; ?>_noncename" id="<?php echo $id; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		<?php 
		break;

		case 'small-text': ?>
			<input name="<?php echo $id; ?>" id="<?php echo $id; ?>" type="text" value="<?php echo $current_value; ?>" style="width:80px;" />
			<input type="hidden" name="<?php echo $id; ?>_noncename" id="<?php echo $id; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		<?php 
		break;

		case 'checkbox':
			if( !empty( $current_value ) ){ $checked = "checked=\"checked\""; } else{ $checked = ''; } ?>
				<input type="checkbox" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="true" <?php echo $checked; ?> />
				<input type="hidden" name="<?php echo $id; ?>_noncename" id="<?php echo $id; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />			
		<?php	
		break;
		
		case 'radio':
		?>
			<div style="float:left;">
				<?php foreach ($value['options'] as $key => $option) { ?>
				<div style="display:block;">
				<input name="<?php echo $id; ?>" id="<?php echo $id; ?>" type="radio" value="<?php echo $key ?>" <?php if ( $current_value == $key) { echo ' checked="checked"' ; } ?>> <?php echo $option; ?>
				<input type="hidden" name="<?php echo $id; ?>_noncename" id="<?php echo $id; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
				</div>				
				<?php } ?>
			</div>
		<?php
		break;
		
		case 'select':
		?>
			<select name="<?php echo $id; ?>" id="<?php echo $id; ?>">
				<?php foreach ($value['options'] as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( $current_value == $key) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
			<input type="hidden" name="<?php echo $id; ?>_noncename" id="<?php echo $id; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		<?php
		break;
		
		case 'textarea':
		?>
			<textarea style="direction:ltr; text-align:left; width:65%;" name="<?php echo $id; ?>" id="<?php echo $id; ?>" type="textarea" cols="100%" rows="4"><?php echo $current_value; ?></textarea>
			<input type="hidden" name="<?php echo $id; ?>_noncename" id="<?php echo $id; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		<?php
		break;
	} ?>
	<?php if( isset( $value['tax'] ) ): ?>
		<script type="text/javascript">
		
		var <?php echo $value['id']; ?> = '#tax-input-<?php echo _k_($value['id']); ?>';
		jQuery('#<?php echo $value['id']; ?>-item input, #<?php echo $value['id']; ?>-item textarea').keyup(function() {
			val = jQuery('#<?php echo $value['id']; ?>-item input, #<?php echo $value['id']; ?>-item textarea').val();
			jQuery('#tax-input-<?php echo _k_($value['id']); ?>').html(val);
		});
		
		jQuery('#tagsdiv-<?php echo _k_($value['id']); ?>').hide();
		</script>
	<?php endif; ?>
	<?php if( isset( $value['help'] ) ) : ?>
		<a class="keremiya-help tooltip"  title="<?php echo $value['help'] ?>"></a>
		<?php endif; ?>
	</div>
<?php
}
?>