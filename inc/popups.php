<?php

/*
 * Popup Fonksiyonları
 */
add_action('wp_footer', 'keremiya_popups');
add_action('wp_footer', 'keremiya_gallery_popups');
add_action('wp_footer', 'keremiya_splash_popup');
/*
 * HTML
 */
function keremiya_popups() {
	?>
<div id="popup" class="modal" style="display:none">
	<div class="modal-inner">
		<div class="modal-message" style="display:none">
			<div class="message-header"></div>
			<div class="message-content"></div>
			<div class="message-footer"></div>
			<span class="message-close"><?php _k('Kapat'); ?></span>
		</div>

		<div class="modal-header">
		</div>

		<div id="register-form" class="modal-form" style="display:none">
			<form class="register-form">
			<div class="display-message"></div>
				<input id="register_username" class="register-form-author" name="register_username" value="" aria-required="true" placeholder="<?php _k('Kullanıcı Adı'); ?> *" type="text">
				<input id="register_email" class="register-form-email" name="register_email" value="" aria-required="true" placeholder="<?php _k('E-Posta'); ?> *" type="text">
				<input id="register_re_email" class="register-form-re-email" name="register_remail" value="" aria-required="true" placeholder="<?php _k('E-Posta Tekrar'); ?> *" type="text">
				<input class="register-form-password" name="register_password" value="" aria-required="true" placeholder="<?php _k('Parola'); ?> *" type="password">
				<input class="register-form-confirm" name="register_confirm" value="" aria-required="true" placeholder="<?php _k('Parola Tekrar'); ?> *" type="password">
				<input type="hidden" name="keremiya_action" value="register">
				<input type="hidden" name="url" value="<?php echo keremiya_pages_get_url('settings'); ?>" id="redirect-url">
				<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('user_ajax-nonce'); ?>">
				<button name="submit" id="submit" class="button submit-button" type="submit"><span class="icon-right-open"><?php _k('Kaydol'); ?></span></button>
			</form>

			<div class="modal-footer">
				<?php echo keremiya_popup_login_url(); ?>
			</div>
		</div>

		<div id="login-form" class="modal-form" style="display:none">
			<form id="user-login" class="login-form">
			<div class="display-message"></div>
				<input id="login_username" class="login-form-author" name="login_username" value="" aria-required="true" placeholder="<?php _k('Kullanıcı Adı'); ?> *" type="text">
				<input id="login_password" class="login-form-password" name="login_password" value="" aria-required="true" placeholder="<?php _k('Parola'); ?> *" type="password">
				<input type="hidden" name="keremiya_action" value="login">
				<input type="hidden" name="url" value="<?php echo keremiya_get_page_url(); ?>" id="redirect-url">
				<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('user_ajax-nonce'); ?>">
				<button name="submit" id="submit" class="button submit-button" value="<?php _k('Gönder'); ?>" type="submit"><span class="icon-right-open"><?php _k('Giriş Yap'); ?></span></button>
			
				<div class="lost-password">
					<a href="<?php echo site_url('wp-login.php?action=lostpassword', 'lostpassword_post') ?>"><?php _k('Parolanızı mı unuttunuz?'); ?></a>
				</div>
			</form>

			<div class="modal-footer">
				<?php echo keremiya_popup_register_url(); ?>
			</div>
		</div>
	</div>
	<div class="modal-bg"></div>
</div><!--#popup-->

<?php
}

function keremiya_popup_login_url() {
	$html = _k_('Zaten üye misin?').' <a href="javascript:void(0);" class="show-modal" data-is="#popup" data-id="#login-form">'._k_('Giriş Yap').'</a>';
	return apply_filters('keremiya_popup_login_url', $html);
}

function keremiya_popup_register_url() {
	$html = _k_('Üye değil misin?').' <a href="javascript:void(0);" class="show-modal" data-is="#popup" data-id="#register-form">'._k_('Hemen Kaydol').'</a>';
	return apply_filters('keremiya_popup_register_url', $html);
}

function keremiya_gallery_popups() {
	if(!is_single())
		return;
	?>

<div id="gallery" style="display:none">
	<div class="gallery-inner">
		<div class="gallery-header"></div>
		<div class="gallery-content"></div>
		<div class="gallery-footer"></div>
	</div>
	<div class="gallery-bg"></div>
</div><!--#gallery-->

<?php
}


function keremiya_splash_popup() {
	if( ! keremiya_get_option('banner_splash') )
		return;
	?>
	<script type="text/javascript">
        var cookie = readCookie('splash');
        var myVar = setInterval(function(){ splashTimer() }, 1000);

        function splashTimer() {
        	if( !cookie )
 				splashShow();

            var time = document.getElementById("splash-time"),
                timeCounter = time.innerHTML,
                updateTime = parseInt(timeCounter) - 1;

                time.innerHTML = updateTime;

            if(updateTime == 0)
                splashHideFunction();
        }

        function splashStopFunction() {
            clearInterval(myVar);
        }

        function splashHideFunction() {
            var x=document.getElementById('splash-message').style;
            var y=document.getElementById('splash-close').style; 
            x.display='none';
            y.display='block';

            splashStopFunction();
        }

        function splashHide() {
            var x=document.getElementById('splash').style;
            x.display='none';

            splashHideFunction();
            createCookie('splash', 'true', '1');
        }

        function splashShow() {
            var x=document.getElementById('splash').style;
            x.visibility='visible';
            x.opacity='1';
        }

		function createCookie(name,value,days) {
			if (days) {
				var date = new Date();
				date.setTime(date.getTime()+(days*24*60*60*1000));
				var expires = "; expires="+date.toGMTString();
			}
			else var expires = "";
			document.cookie = name+"="+value+expires+"; path=/";
		}

		function readCookie(name) {
			var nameEQ = name + "=";
			var ca = document.cookie.split(';');
			for(var i=0;i < ca.length;i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') c = c.substring(1,c.length);
				if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
			}
			return null;
		}
	</script>
	<div id="splash">
		<div class="splash-header wrapper">
			<span id="splash-message"><?php printf( _k_('Reklamı geçmek için %s saniye bekleyiniz.'), '<span id="splash-time">'. keremiya_get_option('banner_splash_time'). '</span>' ); ?></span>

			<a onclick="splashHide();return false;" id="splash-close" class="button" href="javascript:void(0);"><?php _k('Reklamı Geç'); ?></a>
		</div>
		<div class="splash-content align-center">
			<?php echo htmlspecialchars_decode( keremiya_get_option('banner_splash_code') ); ?>
		</div>
	</div>
<?php
}