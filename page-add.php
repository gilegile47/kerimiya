<?php
/**
 * @package WordPress
 * @subpackage Keremiya v5
 
 Template Name: İçerik Ekle
 
 */

if ( $_POST['add-post'] == 'movie') {
	// CSS
	$movieHide = 'display:block';
	$movieActive = 'active';

	$metas = keremiya_custom_meta_keys();
	unset($metas[_k_('konu')], $metas[_k_('resim')]);
	foreach ($metas as $key => $title) {
		$meta[$key] = $_POST['add-'.$key];
	}

	$data = array(
		'title' => $_POST['add-title'],
		'textarea' => $_POST['movie-textarea'],
		'tags' => $_POST['add-tags'],
		'image' => $_POST['add-poster'],
		'category' => $_POST['post_category'],
		'meta' => $meta,
	);

}
elseif ( $_POST['add-post'] == 'news') {

	// CSS
	$newsHide = 'display:block';
	$newsActive = 'active';

	$data = array(
		'title' => $_POST['news-title'],
		'textarea' => $_POST['news-textarea'],
		'tags' => $_POST['news-tags'],
		'image' => $_POST['news-image'],
		'category' => $_POST['post_category'],
		'type' => $_POST['add-post'],
	);

}

if ( wp_verify_nonce( $_POST['add-nonce'], 'add-post' ) ) { 
	$message = keremiya_add_post($data);
}

get_header(); ?>
<div id="content">
<div class="content wrapper clearfix">

<?php if( ! keremiya_get_option('add_post') ): ?>

	<div class="olmayansayfa">
		<?php _k('Kullanıma Kapalı'); ?>		
		<p><?php _k('Şuan için bu sayfanın kullanılmasına izin verilmiyor.'); ?></p>
	</div>

<?php elseif( ! is_user_logged_in() ): ?>

<div class="single-content add-content">
	<div class="center">
		<h1><span><?php the_title(); ?></span></h1>
		<p class="mt10"><?php _k('İçerik ekleyebilmek için üye girişi yapmalısınız.'); ?></p>

		<div class="loginForm" style="text-align:left;width:400px;margin:30px auto 0">

			<form action="<?php echo site_url('wp-login.php', 'login_post') ?>" method="post">
				<p><label for="log" id="user"><?php _k('Kullanıcı Adı'); ?></label>
				<input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="22" /></p>
				
				<p><label for="pwd" id="password"><?php _k('Parola'); ?></label>
				<input type="password" name="pwd" id="pwd" size="22" /></p>
				
				<input type="submit" name="submit" value="<?php _k('Giriş Yap'); ?>" class="button" />
				<input type="hidden" name="redirect_to" value="<?php the_permalink(); ?>"/>
			</form>
		</div>
	</div>
</div>

<?php elseif( ! keremiya_is_activated($user_ID) ): ?>

<div class="single-content add-content">
	<div class="center">
		<h1><span><?php the_title(); ?></span></h1>
		<p class="mt10"><?php _k('İçerik ekleyebilmek için aktivasyon yapmalısınız.'); ?></p>

	</div>
</div>

<?php else: ?>
	<div class="single-content add-content">

		<div class="center">
			<h1>
				<span>
					<?php 
						if($_GET['complete'])
							_k('İçerik başarıyla gönderildi.');
						else
							the_title();
					?>
				</span>
			</h1>
			<p class="mt10">
					<?php 
						if($_GET['complete'])
							_k('Yönetici onayından sonra içeriğiniz yayınlanacaktır.');
						else
							_k('Haydi şimdi eklemek istediğin içeriğin türünü seçerek başla.');
					?>
			</p>


			<a href="javascript:void(0);" class="select-type <?php echo $newsActive; ?>" data-open=".post-news">
				<div class="anw">
					<span class="icon-pencil"></span>
					<?php _k('Haber Ekle'); ?>
				</div>
			</a>

			<a href="javascript:void(0);" class="select-type <?php echo $movieActive; ?>" data-open=".post-movie">
				<div class="anw">
					<span class="icon-video"></span>
					<?php _k('Film Ekle'); ?>
				</div>
			</a>
		</div>

		<div id="message">
			<?php

				$movieHide = $movieHide ? $movieHide : 'display:none';
				$newsHide = $newsHide ? $newsHide : 'display:none';

				// Get Message
				if( $message ) {
					if( is_wp_error( $message ) ) {
						echo '<div class="message error"><span class="icon-attention-circled"></span>'.$message->get_error_message().'</div>';
					}
					else {
						if( isset( $message['complete'] ) ) {
							echo $message['content'];
							$none = true;
						} else {
							echo '<div class="message error">'.$message.'</div>';
						}
					}
				}
			?>
		</div>

	<?php if( ! $none ): ?>
	<div class="add-new-content">
		<form class="post-movie" method="post" action="<?php echo get_permalink(); ?>" style="<?php echo $movieHide; ?>">
		<div class="left">
			<input id="add-title" name="add-title" aria-required="true" value="<?php if(isset($_POST['add-title'])) echo $_POST['add-title'];?>" placeholder="<?php _k('Başlık'); ?> *" type="text" tabindex="1">

			<div class="KR-editor">
				<div class="KR-head" data-area="movie-textarea">
					<button type="button" data-format="b" class="button tooltip BB-b" title="<?php _k('Kalınlaştır'); ?>"><?php _k('B'); ?></button>
					<button type="button" data-format="u" class="button tooltip BB-u" title="<?php _k('Altını Çiz'); ?>"><u><?php _k('U'); ?></u></button>
					<button type="button" data-format="i" class="button tooltip BB-i" title="<?php _k('Yana Yasla'); ?>"><i><?php _k('I'); ?></i></button>
				</div>
				<div class="KR-textarea">
					<textarea id="movie-textarea" class="flexcroll" name="movie-textarea" aria-required="true" placeholder="<?php _k('Özet'); ?> *" tabindex="2"><?php if(isset($_POST['movie-textarea'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['movie-textarea']); } else { echo $_POST['movie-textarea']; } } ?></textarea>
				</div>
				<div class="KR-footer">
					<div id="textarea-feedback"><?php _k('Kelime Sayısı'); ?>: <span>0</span></div>
				</div>
			</div>

			<?php
			$metas = keremiya_custom_meta_keys();
			unset($metas[_k_('konu')], $metas[_k_('resim')]);
			$i = 2;
				foreach ($metas as $key => $title) {
					$i++;

					$placeholder = '';
					if($key == _k_('yapim'))
						$placeholder = _k_("Filmin yapım yılı");
					if($key == _k_('imdb'))
						$placeholder = _k_("IMDB üzerinden aldığı puan. Örneğin; 8.6");
					if($key == _k_('oyuncular'))
						$placeholder = _k_("Birden fazla ise virgül (,) ile ayırın.");
					if($key == _k_('diger-adlari'))
						$placeholder = _k_("Birden fazla adı varsa");

					echo '<label for="add-'.$key.'">'.$title.':</label>';
					echo '<input id="add-'.$key.'" name="add-'.$key.'" aria-required="true" value="'.$_POST['add-'.$key].'" placeholder="'.$placeholder.'" type="text" tabindex="'.$i.'">';
				}
			?>


		</div>

		<div class="right">
			<div class="sidebar-content">
				<button class="button submit-content"><?php _k('Filmi Gönder'); ?></button>
			</div>

			<div class="sidebar-content select-categories">
				<h4 class="title"><span><?php _k('Tür seç'); ?></span></h4>
				<div class="flexcroll">
				<?php
					keremiya_categories_checklist( 'category' );
				?>
				</div>
			</div>

			<div class="sidebar-content">
				<h4 class="title"><span><?php _k('Etiket ekle'); ?></span></h4><br>
				<input id="add-tags" name="add-tags" aria-required="true" value="<?php if(isset($_POST['add-tags'])) echo $_POST['add-tags'];?>" placeholder="<?php _k('Etiketler'); ?>" type="text">
				<p class="add-tags-excerpt"><?php _k('Etiketleri virgül (,) ile ayırın.'); ?></p>
			</div>

			<div class="sidebar-content">
				<h4 class="title"><span><?php _k('Afiş ekle'); ?></span></h4><br>
				<input id="add-poster" name="add-poster" value="<?php if(isset($_POST['add-poster'])) echo $_POST['add-poster'];?>" aria-required="true" placeholder="<?php _k('URL'); ?> *" type="text">
				<p class="add-tags-excerpt"><?php _k('Resmin url adresini belirtin.'); ?></p>
			</div>
		</div>

		<input type="hidden" name="add-post" value="movie">
		<input type="hidden" name="add-nonce" value="<?php echo wp_create_nonce('add-post'); ?>">
		</form>

		<form class="post-news" method="post" action="<?php echo get_permalink(); ?>" style="<?php echo $newsHide; ?>">
		<div class="left">
			<input id="news-title" name="news-title" aria-required="true" value="<?php if(isset($_POST['news-title'])) echo $_POST['news-title'];?>" placeholder="<?php _k('Başlık'); ?> *" type="text" tabindex="1">

			<div class="KR-editor">
				<div class="KR-head" data-area="news-textarea">
					<button type="button" data-format="b" class="button tooltip BB-b" title="<?php _k('Kalınlaştır'); ?>"><?php _k('B'); ?></button>
					<button type="button" data-format="u" class="button tooltip BB-u" title="<?php _k('Altını Çiz'); ?>"><u><?php _k('U'); ?></u></button>
					<button type="button" data-format="i" class="button tooltip BB-i" title="<?php _k('Yana Yasla'); ?>"><i><?php _k('I'); ?></i></button>
					<button type="button" data-format="img" class="button tooltip BB-img" title="<?php _k('Resim Ekle'); ?>"><?php _k('Resim'); ?></button>
					<button type="button" data-format="url" class="button tooltip BB-url" title="<?php _k('Link Ekle'); ?>"><?php _k('URL'); ?></button>
					<button type="button" data-format="video" class="button tooltip BB-video" title="<?php _k('Video Ekle'); ?>"><?php _k('Video'); ?></button>
				</div>
				<div class="KR-textarea">
					<textarea id="news-textarea" class="flexcroll" name="news-textarea" aria-required="true" placeholder="<?php _k('İçerik'); ?> *" tabindex="2"><?php if(isset($_POST['news-textarea'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['news-textarea']); } else { echo $_POST['news-textarea']; } } ?></textarea>
				</div>
				<div class="KR-footer">
					<div id="textarea-feedback"><?php _k('Kelime Sayısı'); ?>: <span>0</span></div>
				</div>
			</div>

			<input id="news-tags" name="news-tags" aria-required="true" value="<?php if(isset($_POST['news-tags'])) echo $_POST['news-tags'];?>" placeholder="<?php _k('Etiketler'); ?>" type="text" tabindex="3">
			<p class="add-tags-excerpt"><?php _k('Etiketleri virgül (,) ile ayırın.'); ?></p>

		</div>

		<div class="right">
			<div class="sidebar-content">
				<button class="button submit-content"><?php _k('Yazıyı Gönder'); ?></button>
			</div>
			<div class="sidebar-content select-categories">
				<h4 class="title"><span><?php _k('Kategori seç'); ?></span></h4>
				<?php
					keremiya_categories_checklist( keremiya_news_category_name() );
				?>
			</div>

			<div class="sidebar-content">
				<h4 class="title"><span><?php _k('Öne Çıkan Görsel'); ?></span></h4><br>
				<input id="news-image" name="news-image" aria-required="true" value="<?php if(isset($_POST['news-image'])) echo $_POST['news-image'];?>" placeholder="<?php _k('URL'); ?> *" type="text">
				<p class="add-tags-excerpt"><?php _k('Resmin url adresini belirtin.'); ?></p>
			</div>
		</div>
		<input type="hidden" name="add-post" value="news">
		<input type="hidden" name="add-nonce" value="<?php echo wp_create_nonce('add-post'); ?>">
		</form>
	</div>
	<?php endif; ?>


	</div><!--.add-content-->
<?php endif; ?>

<div class="clear"></div>
</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>