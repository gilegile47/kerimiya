<?php

add_action('wp_ajax_keremiya_update_user', 'keremiya_update_user');
add_action('wp_ajax_nopriv_keremiya_update_user', 'keremiya_update_user');
// Herşey bunda biter
function keremiya_update_user(){
	$data = keremiya_ajax_unserialize( $_POST['form'] );

		switch($data['action']){

			case 'update-user':
				_keremiya_update_user($data);
			break;

			case 'update-password':
				_keremiya_update_user_password($data);
			break;

			default:
			break;
		}
	exit();
}

function _keremiya_update_user($data){

		$first_name = trim(strip_tags($data['keremiya_user_first_name']));
		$last_name = trim(strip_tags($data['keremiya_user_last_name']));
		$description = trim(strip_tags($data['keremiya_user_description']));
		$nickname = trim(strip_tags($data['keremiya_user_nickname']));

		$facebook = trim(strip_tags($data['keremiya_user_fb']));
		$twitter = trim(strip_tags($data['keremiya_user_tw']));
		
		$user_ID = get_current_user_id();

	// Validate the Form Data
	if(isEmptyString($user_ID)) $error = _k_("Üzgünüz, bir hata oluştu.");
	elseif(isEmptyString($nickname)) $error = _k_("Takma ad alanı boş bırakılamaz.");
	elseif (strlen(trim($nickname)) > 21) $error = _k_("Takma ad en fazla 21 karakter uzunluğunda olabilir.");
	
	if ( $error != null )
		die($error);
	
	// Change User
	wp_update_user( array( 
		'ID' => $user_ID, 
		'first_name' => $first_name,
		'last_name' => $last_name,
		'display_name' => $nickname,
		'description' => $description
		) 
	);

	update_usermeta( $user_ID, 'facebook', $facebook);
	update_usermeta( $user_ID, 'twitter', $twitter);

	echo _k_("Tüm değişiklikler kaydedildi.");
	die();
}

function _keremiya_update_user_password($data) {

	$user = wp_get_current_user();
	$oldPass = $user->user_pass;

	//Strip any tags then may have been put into the array
	$currentPass_stripped = strip_tags($data['keremiya_currentPass']);
	$newPass1_stripped = strip_tags($data['keremiya_newPass1']);
	$newPass2_stripped = strip_tags($data['keremiya_newPass2']);

	// Validate the Form Data
	if(isEmptyString($user->ID)) $error = _k_("Üzgünüz, bir hata oluştu.");
	elseif (isEmptyString($currentPass_stripped)) $error = _k_("Geçerli parolanızı girin.");
	elseif (!wp_check_password($currentPass_stripped, $oldPass)) $error = _k_("Geçerli parola hatalı.");
	elseif (isEmptyString($newPass1_stripped)) $error = _k_("Yeni bir parola girin.");
	elseif (strlen(trim($newPass1_stripped)) < 7) $error = _k_("Parola en az yedi karakter uzunluğunda olmalıdır.");
	elseif (isEmptyString($newPass2_stripped)) $error = _k_("Yeni parolanızı tekrar girin.");
	elseif ($newPass1_stripped != $newPass2_stripped) $error = _k_("Yeni parola ile tekrar uyuşmuyor.");

	if ( $error != null )
		die($error);
	
	// Change User's Password 
	wp_update_user( array( 
		'ID' => $user->ID, 
		'user_pass' => $newPass1_stripped 
		) 
	);
	
    // Log the User In
    $creds = array();
    $creds['user_login'] = $user->user_login;
    $creds['user_password'] = $newPass1_stripped;
    $creds['remember'] = true;
    $login = wp_signon( $creds, false );
    // Login error
    if ( is_wp_error($login) )
        die($login->get_error_message());
	
	// Add a flag for Messaging the user that they have successfully changed their password
	update_usermeta($user->ID, 'changed_password_my_account', 'yes');
	
	echo _k_("Tüm değişiklikler kaydedildi.");
	die();
}

function keremiya_update_user_form() { 

$user_ID = get_current_user_id();

if(!$user_ID) 
	die();

	// KULLANICI BILGILERI
	$profil = get_userdata(intval($user_ID));
	$meta = get_user_meta( $user_ID );
	?>

<h2 class="title"><span><?php _k('Bilgilerimi Güncelle'); ?></span></h2>
	<form method="post" action="<?php echo keremiya_pages_get_url('settings'); ?>" id="update-user" name="update-user">
		<div class="left col-1">
			<fieldset>
			<label for="keremiya_user_login" class="bold"><?php _k('Kullanıcı Adı'); ?> <small><?php _k('Kullanıcı adı değiştirilemez'); ?></small></label><br />
			<input type="text" name="keremiya_user_login" class="disabled" id="keremiya_user_login" disabled="disabled" value="<?php echo $profil->user_login; ?>" tabindex="1" />
			</fieldset>
						
			<fieldset>
			<label for="keremiya_user_first_name" class="bold"><?php _k('Ad'); ?></small></label><br />
			<input type="text" name="keremiya_user_first_name" id="keremiya_user_first_name" value="<?php echo $meta['first_name'][0]; ?>" tabindex="2" />
			</fieldset>

			<fieldset>
			<label for="keremiya_user_last_name" class="bold"><?php _k('Soyad'); ?></small></label><br />
			<input type="text" name="keremiya_user_last_name" id="keremiya_user_last_name" value="<?php echo $meta['last_name'][0]; ?>" tabindex="3" />
			</fieldset>


            <fieldset>
			<label for="keremiya_user_description" class="bold"><?php _k('Hakkında'); ?></label><br />
			<textarea name="keremiya_user_description" id="keremiya_user_description" class="flexcroll" tabindex="4"><?php echo $meta['description'][0]; ?></textarea>
			</fieldset>
			
			
            <fieldset>
			<label for="keremiya_user_nickname" class="bold"><?php _k('Takma Ad'); ?> *</label><br />
			<input type="text" name="keremiya_user_nickname" id="keremiya_user_nickname" value="<?php if( $profil->display_name ) { echo $profil->display_name; } else { echo $meta['nickname'][0]; } ?>" tabindex="5" />
			</fieldset>

		</div>
			
		<div class="left col-2">
			
			<fieldset>
			<label for="keremiya_user_email" class="bold"><?php _k('E-Posta'); ?> <small><?php _k('E-Posta değiştirilemez'); ?></small></label><br />
			<input type="text" name="keremiya_user_email"  id="keremiya_user_email" disabled="disabled" value="<?php echo $profil->user_email; ?>" tabindex="6" />
			</fieldset>
			
			<fieldset>
			<label for="keremiya_user_fb" class="bold"><?php _k('Facebook Kullanıcı adı'); ?></label><br />
			<input type="text" name="keremiya_user_fb"  id="keremiya_user_fb" value="<?php echo $meta['facebook'][0] ?>" tabindex="8" />
			</fieldset>

			<fieldset>
			<label for="keremiya_user_tw" class="bold"><?php _k('Twitter Kullanıcı Adı'); ?></label><br />
			<input type="text" name="keremiya_user_tw"  id="keremiya_user_tw" value="<?php echo $meta['twitter'][0]; ?>" tabindex="9" />
			</fieldset>

		</div>

		<div class="clear"></div>

		<div class="right">
			<input type="hidden" name="keremiya_user_post" value="1" />
			<input type="hidden" name="action" value="update-user" />
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('update-user'); ?>" />

			<div class="upd"></div>
			<button type="submit" name="submit" id="update-user-form-button" class="button tooltip"><?php _k('Değişiklikleri kaydet'); ?></button>
		</div>

	</form>
<?php
}

function keremiya_update_password_form() { 

$user_ID = get_current_user_id();

if(!$user_ID) 
	die();
	?>

<h2 class="title"><span><?php _k('Şifre Değişikliği'); ?></span></h2>
	<form method="post" action="<?php echo keremiya_pages_get_url('settings'); ?>" id="update-user" name="update-user">
	<div class="message info">
		<?php _k('İpucu: Parola en az yedi karakter uzunluğunda olmalıdır. Daha güçlü olması için büyük harf, küçük harf, rakamlar ve ! " ? $ % ^ & ) vb semboller kullanabilirsiniz.'); ?>
	</div>
		<form method="post" action="<?php echo keremiya_pages_get_url('settings'); ?>" id="update-user" name="update-user">
			<fieldset>
			<label for="keremiya_currentPass" class="bold"><?php _k('Şuan ki Parola'); ?> *</label><br />
			<input type="password" name="keremiya_currentPass" id="keremiya_currentPass" value="" tabindex="1" />
			</fieldset>
						
			<fieldset>
			<label for="keremiya_newPass1" class="bold"><?php _k('Yeni Parola'); ?> *</label><br />
			<input type="password" name="keremiya_newPass1" id="keremiya_newPass1" value="" tabindex="2" />
			</fieldset>

			<fieldset>
			<label for="keremiya_newPass2" class="bold"><?php _k('Yeni parolayı tekrar yazın'); ?> *</label><br />
			<input type="password" name="keremiya_newPass2" id="keremiya_newPass2" value="" tabindex="3" />
			</fieldset>

			<div class="clear"></div>

			<div class="right mt10">
			<input type="hidden" name="keremiya_user_post" value="1" />
			<input type="hidden" name="action" value="update-password" />
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('update-user'); ?>" />

			<div class="upd"></div>
			<button type="submit" name="submit" id="update-user-form-button" class="button tooltip"><?php _k('Değişiklikleri kaydet'); ?></button>
			</div>
		</form>
<?php 
}