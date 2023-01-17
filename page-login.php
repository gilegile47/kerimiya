<?php
/*
Template Name: Üye Girişi Sayfası
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
				window.location="<?php bloginfo('url'); ?>";
			</script>
		</div>
	<?php else : ?>


		<h1 class="title"><span><?php the_title(); ?></span></h1>

		<div class="loginForm">

			<form action="<?php echo site_url('wp-login.php', 'login_post') ?>" method="post">
				<p><label for="log" id="user"><?php _k('Kullanıcı Adı'); ?></label>
				<input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="22" /></p>
				
				<p><label for="pwd" id="password"><?php _k('Parola'); ?></label>
				<input type="password" name="pwd" id="pwd" size="22" /></p>
				
				<input type="submit" name="submit" value="<?php _k('Giriş Yap'); ?>" class="button" />
				<label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> <?php _k('Beni Hatırla'); ?></label>
				<input type="hidden" name="redirect_to" value="<?php bloginfo('url') ?>"/>
			</form>

			<p><a href="<?php echo site_url('wp-login.php?action=lostpassword', 'lostpassword_post') ?>"><?php _k('Parolanızı mı unuttunuz?'); ?></a><p>
		</div>


	<?php endif; ?>
	</div>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>