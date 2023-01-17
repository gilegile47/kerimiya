<?php


function k_array_is_numeric($array) {
	foreach($array as $element) {
		if(!is_numeric($element))
			return false;
	}
	return true;
}

function keremiya_is_image($path) {
    $a = @getimagesize($path);

    $image_type = $a[2];

    if(in_array($image_type, array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP)))
    {
        return true;
    }
    return false;
}

function keremiya_user_status_role() {
	if ( keremiya_get_option('post_status_roles') ) {
		foreach (keremiya_get_option('post_status_roles') as $key => $value) {
			if( current_user_can( $value ) ) {
				return $status = "publish";
			}
		}
	} else {
		return $status = "publish";
	}
}

function showBBcodes($text) {
	// BBcode array
	$find = array(
		'~\[b\](.*?)\[/b\]~s',
		'~\[i\](.*?)\[/i\]~s',
		'~\[u\](.*?)\[/u\]~s',
		'~\[quote\](.*?)\[/quote\]~s',
		'~\[size=(.*?)\](.*?)\[/size\]~s',
		'~\[color=(.*?)\](.*?)\[/color\]~s',
		'~\[url\]((?:ftp|https?)://.*?)\[/url\]~s',
		'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
		'~\[video\](.*?)\[/video\]~s'
	);
	// HTML tags to replace BBcode
	$replace = array(
		'<strong>$1</strong>',
		'<i>$1</i>',
		'<span style="text-decoration:underline;">$1</span>',
		'<pre>$1</'.'pre>',
		'<span style="font-size:$1px;">$2</span>',
		'<span style="color:$1;">$2</span>',
		'<a href="$1">$1</a>',
		'<img src="$1" alt="" />',
		'<span class="video">$1</span>'
	);
	// Replacing the BBcodes with corresponding HTML tags
	return preg_replace($find,$replace,$text);
}


function keremiya_add_post($args) {

	if( ! keremiya_get_option('add_post') )
		return;

	if( ! is_user_logged_in() )
		return;

	// Değişken haline getir
	extract( $args, EXTR_SKIP );

	//Strip any tags then may have been put into the array
	$title = keremiya_clean($title);
	$content = keremiya_clean($textarea);
	$tags = keremiya_clean($tags);
	$image = keremiya_clean($image);
	$type = keremiya_clean($type);
	
	// Validate the Form Data
	if (isEmptyString($title)) return new WP_Error('no_title_entered', _k_('Lütfen bir başlık girin.'));
	if (isEmptyString($content)) return new WP_Error('no_content', _k_('Lütfen bir açıklama girin.'));

	if (isEmptyString($tags) && $type == 'news') return new WP_Error('no_tags_entered', _k_('En az bir tane etiket girmelisiniz.'));

	if (!isset($category[1])) return new WP_Error('no_select_category', _k_('Lütfen bir kategori seçin.'));
	if (!k_array_is_numeric($category)) return new WP_Error('no_valid_category', _k_('Üzgünüz bir hata oluştu.'));
	if (!keremiya_is_image($image)) return new WP_Error('no_image', _k_('Lütfen geçerli bir resim url girin.'));
	
	// Tag Process
	$new_tags = preg_replace( '/\s*,\s*/', ',', rtrim( trim($tags), ' ,' ) );
	$new_tags = explode(',', $new_tags);
	
	// Get the Current User Info
	$user = wp_get_current_user();

	if (!keremiya_is_last_post($user->ID, '300')) return new WP_Error('fast_added', _k_('Çok hızlısın :) Lütfen, daha sonra tekrar dene.'));
	
	global $wpdb;
	$keremiya_video_gather = "SELECT * FROM wp_posts WHERE post_author = '" . $user->ID . "'";
	$keremiya_video_user = $wpdb->get_results($keremiya_video_gather);
			
	// Validate - Check to see if user already posted this exact question
	foreach ($keremiya_video_user as $keremiya_video_user) {
		if ($keremiya_video_user->post_author == $user->ID ) {
			if ($keremiya_video_user->post_title == $title) {
				return new WP_Error('duplicate_user_video', _k_('Daha önce bu başlıkta bir içerik eklediniz, lütfen farklı bir başlık deneyin.'));
			}			
		}
	}

	$new_thumbnail = $image;
	if ( $new_thumbnail != null ) {
		$image_contents = wp_remote_get($new_thumbnail);
			
			if ( is_wp_error( $image_contents ) ) {
				$error = $image_contents->get_error_message();
			} else {
				$image_contents = $image_contents[body];
			}
	}

	if ( $error != null ) {
		return $error;
	}


	if( current_user_can( 'administrator' ) ){
		$status = "pending";
	} else {
		$status = keremiya_get_option("post_status");

		if ($status == 'publish') {
			if( keremiya_user_status_role() )
				$status = keremiya_user_status_role();
			else
				$status = 'pending';
		} else {
			$status = 'pending';
		}

	}
	
	if($type == 'news') {
		$post = array(
		  'ID' => '',
		  'post_type' => keremiya_news_post_type(),
		  'post_author' => $user->ID, 
		  'post_content' => showBBcodes($content), 
		  'post_title' => $title,
		  'post_status' => $status,
		  'tax_input' => array( 
		  	keremiya_news_tag_name() => $new_tags,
		  	keremiya_news_category_name() => $category,
		  	)
		);  
	}
	else {
		$post = array(
		  'ID' => '',
		  'post_author' => $user->ID, 
		  'post_category' => $category,
		  'post_excerpt' => showBBcodes($content), 
		  'post_title' => $title,
		  'post_status' => $status,
		  'tags_input' => $new_tags
		);  
	}

	// Insert
	$post_id = wp_insert_post($post);

	if($type != 'news') {
		// Update Meta Keys
		foreach ($meta as $key => $value) {
			update_post_meta($post_id, $key, keremiya_clean($value), true);
		}
	}

	// Upload Image
	if ( $image_contents != null ) {

		$upload = wp_upload_bits( basename( $new_thumbnail ), null, $image_contents );

		$new_thumbnail = $upload['url'];

		$filename = $upload['file'];

		$wp_filetype = wp_check_filetype( basename( $filename ), null );
		$attachment = array(
				'post_mime_type'	=> $wp_filetype['type'],
				'post_title'		=> get_the_title($post_id),
				'post_content'		=> '',
				'post_status'		=> 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
		// you must first include the image.php file
		// for the function wp_generate_attachment_metadata() to work
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id,  $attach_data );	

		// Set attachment as featured image if enabled
		if ( !update_post_meta( $post_id, '_thumbnail_id', $attach_id ) ) add_post_meta( $post_id, '_thumbnail_id', $attach_id, true );
	}

	// Add a Point to the User's Point Score
	addPointToUser($user->ID);
	
	// Get the newly created post info for redirection
	$post = get_post($post_id);

	/*if( $post ) {
		$message['complete'] = true;
		$message['content'] = '<div class="message complete">İçerik başarıyla gönderildi. Kontrol edildikten sonra yayına alınacaktır.</div>';
		$message['content'] .= '<style>.center{opacity: .3;} .center a { cursor:default; }</style>';
		return $message;
	}*/

	update_user_meta($user->ID, 'last_added_post_time', time());

	$url = add_query_arg( 'complete', 'true', keremiya_pages_get_url('add') );

	header("Location: $url");
	exit();
	
}

function keremiya_is_last_post($user_id, $interval) {
	$last = get_user_meta( $user_id, 'last_added_post_time', true );
	$now = time();

	if ( !$last || (( $now - $last ) > $interval) ) {
		return true;
	}
	return false;
}

add_filter( 'add_menu_classes', 'post_show_pending_number');
function post_show_pending_number( $menu ) {
    $type = "post";
    $status = "pending";
    $num_posts = wp_count_posts( $type, 'readable' );
    $pending_count = 0;
    if ( !empty($num_posts->$status) )
        $pending_count = $num_posts->$status;

    // build string to match in $menu array
    if ($type == 'post') {
        $menu_str = 'edit.php';
    } else {
        $menu_str = 'edit.php?post_type=' . $type;
    }

    // loop through $menu items, find match, add indicator
    foreach( $menu as $menu_key => $menu_data ) {
        if( $menu_str != $menu_data[2] )
            continue;
        $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
    }
    return $menu;
}

function post_update_notifier_bar_menu() {
	global $wp_admin_bar, $wpdb;
	
	if ( !is_super_admin() || !is_admin_bar_showing() ) // Kullanıcı bir yönetici değil ise yönetici çubuğunda bildirim görüntülemeyi devre dışı bırak
	return;
		
	$type = "post";
	$status = "pending";
	$num_posts = wp_count_posts( $type, 'readable' );
	$pending_count = 0;
	if ( !empty($num_posts->$status) )
		$pending_count = $num_posts->$status;

    // build string to match in $menu array
    if ($type == 'post') {
        $menu_str = admin_url('edit.php?post_status='.$status);
    } else {
        $menu_str = admin_url('edit.php?post_status='.$status.'&post_type=' . $type);
    }

		if($pending_count) { // Tema sürümünü XML dosyası ile karşılaştırma
			$wp_admin_bar->add_menu( array( 'id' => 'update_notifier', 'title' => '<span title="'.sprintf(_k_("%s film moderasyon bekliyor"), $pending_count).'"><span id="ab-updates">'.sprintf(_k_("%s Film"), $pending_count).'</span></span>', 'href' => $menu_str ) );
		}
	}
add_action( 'admin_bar_menu', 'post_update_notifier_bar_menu', 990 );

add_filter( 'add_menu_classes', 'news_show_pending_number');
function news_show_pending_number( $menu ) {
    $type = "news";
    $status = "pending";
    $num_posts = wp_count_posts( $type, 'readable' );
    $pending_count = 0;
    if ( !empty($num_posts->$status) )
        $pending_count = $num_posts->$status;

    // build string to match in $menu array
    if ($type == 'post') {
        $menu_str = 'edit.php';
    } else {
        $menu_str = 'edit.php?post_type=' . $type;
    }

    // loop through $menu items, find match, add indicator
    foreach( $menu as $menu_key => $menu_data ) {
        if( $menu_str != $menu_data[2] )
            continue;
        $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
    }
    return $menu;
}

function news_update_notifier_bar_menu() {
	global $wp_admin_bar, $wpdb;
	
	if ( !is_super_admin() || !is_admin_bar_showing() ) // Kullanıcı bir yönetici değil ise yönetici çubuğunda bildirim görüntülemeyi devre dışı bırak
	return;
		
	$type = "news";
	$status = "pending";
	$num_posts = wp_count_posts( $type, 'readable' );
	$pending_count = 0;
	if ( !empty($num_posts->$status) )
		$pending_count = $num_posts->$status;
	
    $menu_str = admin_url('edit.php?post_status='.$status.'&post_type=' . $type);

		if($pending_count) { // Tema sürümünü XML dosyası ile karşılaştırma
			$wp_admin_bar->add_menu( array( 'id' => 'update_news-notifier', 'title' => '<span title="'.sprintf(_k_("%s haber moderasyon bekliyor"), $pending_count).'"><span id="ab-updates">'.sprintf(_k_("%s Haber"), $pending_count).'</span></span>', 'href' => $menu_str ) );
		}
	}
add_action( 'admin_bar_menu', 'news_update_notifier_bar_menu', 1000 );

?>