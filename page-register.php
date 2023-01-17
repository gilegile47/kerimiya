<?php
/*
Template Name: Üye Ol Sayfası
*/
get_header(); ?>
<div id="content">
<div class="content wrapper clearfix">

	<div class="single-content page-login">

	<?php if ( is_user_logged_in() ) : ?>
		<div class="olmayansayfa">
			<span style="font-size:40px;"><?php _k('Zaten giriş yapmışsın!'); ?></span>
				<p><?php _k('Yönlendiriliyorsunuz...'); ?></p>
			<script type="text/javascript">
				window.location="<?php keremiya_get_user_url( get_current_user_id() ); ?>";
			</script>
		</div>
	<?php else : ?>

		<?php $register = $_GET['register']; if ($register == true) { ?>
			<h1 class="title"><span><?php _k('Tebrikler!'); ?></span></h1>
			<p class="mt10"><?php _k('Parolanızı öğrenmek için e-postanızı kontrol edin.'); ?></p>

			<div class="loginForm">

				<form action="<?php echo site_url('wp-login.php', 'login_post') ?>" method="post">
					<p><label for="log" id="user"><?php _k('Kullanıcı Adı'); ?></label>
					<input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="22" /></p>
					
					<p><label for="pwd" id="pwd"><?php _k('Parola'); ?></label>
					<input type="password" name="pwd" id="pwd" size="22" /></p>
					
					<input type="submit" name="submit" value="<?php _k('Giriş Yap'); ?>" class="button" />
					<label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> <?php _k('Beni Hatırla'); ?></label>
					<input type="hidden" name="redirect_to" value="<?php bloginfo('url') ?>"/>
				</form>

				<p><a href="<?php echo site_url('wp-login.php?action=lostpassword', 'lostpassword_post') ?>"><?php _k('Parolanızı mı unuttunuz?'); ?></a><p>
			</div>
		<?php } else { ?>
			<h1 class="title"><span><?php the_title(); ?></span></h1>
			<div class="registerForm">
				<form method="post" action="<?php echo site_url('wp-login.php?action=register', 'register_post') ?>" class="wp-user-form">
					<p><label for="user_login" id="user"><?php _k('Kullanıcı Adı'); ?></label></br>
					<input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="22" /></p>
					
					<p><label for="user_email" id="mail"><?php _k('E-Posta'); ?></label></br>
					<input type="text" name="user_email" id="user_email" class="input" value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="22" /></p>
					
					<p><?php _k('E-posta adresinize parolanız gönderilecektir.'); ?></p>

					<?php do_action('register_form'); ?>
					<input type="submit" name="user-submit" value="<?php _k('Kaydol'); ?>" class="button" style="margin-bottom:10px;" />
					<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?register=true" />
					<input type="hidden" name="user-cookie" value="1" />
				</form>
			</div>
		<?php } ?>
	<?php endif; ?>
	</div>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>