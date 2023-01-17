<?php
ob_start();
/*
Template Name: İletişim Sayfası
*/
?>
<?php get_header();
if(isset($_POST['submitted']) && wp_verify_nonce( $_POST['contactform'], 'keremiya_contact' ) ) {
		if(trim($_POST['contactName']) === '') {
			$nameError = _k_('Lütfen adınızı giriniz.');
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}
		if(trim($_POST['contactSubject']) === '') {
			$subjectError = _k_('Lütfen konu giriniz.');
			$hasError = true;
		} else {
			$subject = trim($_POST['contactSubject']);
		}
		if(trim($_POST['email']) === '')  {
			$emailError = _k_('Lütfen e-posta adresinizi giriniz.');
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = _k_('Geçersiz E-Posta Adresi.');
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}
			
		if(trim($_POST['comments']) === '') {
			$commentError = _k_('Lütfen mesajınızı giriniz.');
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}
			
		if(!isset($hasError)) {
			$emailTo = keremiya_get_option('email');
			if (!isset($emailTo) || ($emailTo == '') ){
				$emailTo = get_option('admin_email');
			}

			$subject = '['.$subject.']';
			$body = _k_('Gönderenin Adı').": $name \n\n"._k_('E-Posta').": $email \n\n"._k_('Mesaj').": $comments";
			$headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
			
			wp_mail($emailTo, $subject, $body, $headers);
			//mail('keremiya@gmail.com', $subject, $body, $headers);
			$emailSent = true;
		}
	
} ?>
<div id="content">
<div class="content wrapper clearfix">

	<div class="content-left">
	<div class="single-content page">
			<h1 class="title"><span><?php the_title(); ?></span></h1>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<div class="contactform">
            <?php if(isset($emailSent) && $emailSent == true) { ?>
			<p class="message-success"><?php _k('Teşekkürler, iletişim isteğiniz başarıyla gönderildi. Anasayfaya yönlendiriliyorsunuz...'); ?></p>
            <?php $anadres = get_bloginfo('url'); ?>
			<?php header ( "Refresh:3; url=$anadres" );?>
            <?php } else { ?>
			
            <?php if(isset($hasError) || isset($captchaError)) { ?>
			<p class="message-error"><?php _k('Üzgünüz, bir hata ile karşılaştık. Formu eksiksiz doldurduğunuzdan emin olun.'); ?></p>
            <?php } ?>
			
            <form action="<?php the_permalink(); ?>" id="contactForm" method="post">
				<p>
				<label for="contactName" id="user"><?php _k('İsim'); ?></label>
				<input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="required requiredField" />
				<?php if($nameError != '') { ?>
					<span class="error">
					<?=$nameError;?>
					</span>
				<?php } ?>
				</p>
				
				<p>
				<label for="email" id="user"><?php _k('E-Posta'); ?></label>
				<input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="required requiredField email" />
				<?php if($emailError != '') { ?>
					<span class="error">
					<?=$emailError;?>
					</span>
				<?php } ?>
				</p>

				<p>
				<label for="contactSubject" id="user"><?php _k('Konu'); ?></label>
				<input type="text" name="contactSubject" id="contactSubject" value="<?php if(isset($_POST['contactSubject'])) echo $_POST['contactSubject'];?>" class="required requiredField" />
				<?php if($subjectError != '') { ?>
					<span class="error">
					<?=$subjectError;?>
					</span>
				<?php } ?>
				</p>

				<div class="textarea">
				<label for="commentsText" id="user"><?php _k('Söylemek istedikleriniz'); ?></label>
				<textarea name="comments" id="commentsText" rows="5" cols="30" class="required requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
				<p class="buttons">
				<input type="hidden" name="submitted" id="submitted" value="true" />
				<button type="submit" class="button" id="button-send"><?php _k('Gönder'); ?></button>
				</p>
				<?php if($commentError != '') { ?>
					
					<span class="error">
					<?=$commentError;?>
					</span>
				<?php } ?>
				
				</div>
				<?php wp_nonce_field( 'keremiya_contact', 'contactform' ); ?>
            <?php } ?>    
			</div>			
			<?php endwhile; endif; ?>
		</div>
		</div><!--content-left-->
	
	<div class="single-content sidebar">
		<?php get_sidebar(); ?>
	</div>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>