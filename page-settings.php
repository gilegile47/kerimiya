<?php
/*
Template Name: Ayarlar Sayfası
*/
if(!is_user_logged_in()) redirect_to_url( keremiya_pages_get_url('login') );
get_header(); 

$user_ID = get_current_user_id();

$q = $_GET['q'];
?>
<div id="content">
<div class="content wrapper clearfix">

	<?php if(!keremiya_is_activated($user_ID)): ?>
		<div class="message error resend-activation">
			<?php if($_GET['resend'] == '1'): ?>
				<?php echo keremiya_resend_activation_mail($user_ID); ?>
			<?php else: ?>
				<?php _k('E-posta adresinize aktivasyon bağlantısı gönderildi. Kaydınızı tamamlamak için e-postanızı kontrol edin.'); ?>
				<a class="resend-activation-button" href="<?php echo add_query_arg( array( 'resend' => '1' ) ); ?>"><?php _k('Tekrar Gönder'); ?></a>
			<?php endif;?>
		</div>
	<?php endif; ?>

<div class="single-content sideleft">
	<div class="sidebar-content clearfix">
		<div class="top"><span class="fix"><?php _k("Ayarlar"); ?></span></div>
		<ul>
			<li <?php if(!$q || $q == "edit") :?>class="active"<?php endif; ?>><a href="<?php echo get_permalink(); ?>"><?php _k("Bilgilerimi Güncelle"); ?></a>
			<?php
			function page_settings_menu() {
				$menu = array(
					_k_('sifre-degisikligi') => _k_("Şifre Değişikliği"),
					_k_('izleme-listem') => _k_("İzleme Listem"),
					_k_('favori-filmlerim') => _k_("Favori Filmlerim"),
					_k_('puanlarim') => _k_("Puanlarım"),
					_k_('katkilarim') => _k_("Katkılarım"),
					);

				foreach ($menu as $key => $v) {
					if($_GET['q'] == $key) {
						$active = ' class="active"';
					} else {
						$active = '';
					}

					$link = add_query_arg("q", $key);

					$li = '<li'.$active.'><a href="'.$link.'">'.$v.'</a>';

					echo $li;
				}
			}
			?>
			<?php page_settings_menu(); ?>
		</ul>
	</div>
</div>

<div class="single-content page-settings">
	<?php if(!$q): ?>
		
		<?php keremiya_update_user_form(); ?>

		<p class="mt10"><?php _k('Profil resminizi "<b>Gravatar.com</b>" üzerinden değiştirebilirsiniz.'); ?></p>

	<?php elseif($q == _k_('sifre-degisikligi')): ?>

		<?php keremiya_update_password_form(); ?>

	<?php elseif($q == _k_('izleme-listem')): ?>

	<h2 class="title"><span><?php _k('İzleme Listem'); ?></span></h2>

	<div class="film-content watchlist">
	<?php
	$ids = unserialize(get_user_meta($user_ID, 'addto_later', true));
	if($ids) {
		// İlgili Filmler Listelenir
	    $args = array(
	        'posts_per_page' => 100,
	        'post__in'	=> $ids,
	        'v_orderby' => 'desc',
	    );

	    // The Query
	    $the_query = new WP_Query( $args );

	    // The Loop
	    if ( $the_query->have_posts() ) {
	        echo '<div class="fix-series_item remove-item clearfix list_items" data-this="later">';
	        while ( $the_query->have_posts() ) {
	            $the_query->the_post();
	            keremiya_get_layout('series');
	        }
	        echo '</div>';

	    } else {
	        echo '<p class="mt10 ml9">'._k_('İzleme listesinde gösterilebilecek hiçbir film bulunamadı.').'</p>';
	    }
	    wp_reset_postdata();
	} else {
		echo '<p class="mt10 ml9">'._k_('İzleme listesinde gösterilebilecek hiçbir film bulunamadı.').'</p>';
	}
	?>
	</div>

	<?php elseif($q == _k_('favori-filmlerim')): ?>

	<h2 class="title"><span><?php _k('Favori Filmlerim'); ?></span></h2>
	<div class="film-content watchlist">
	<?php
	$ids = unserialize(get_user_meta($user_ID, 'addto_fav', true));
	if($ids) {
		// İlgili Filmler Listelenir
	    $args = array(
	        'posts_per_page' => 100,
	        'post__in'	=> $ids,
	        'v_orderby' => 'desc',
	    );

	    // The Query
	    $the_query = new WP_Query( $args );

	    // The Loop
	    if ( $the_query->have_posts() ) {
	        echo '<div class="fix-series_item remove-item clearfix list_items" data-this="fav">';
	        while ( $the_query->have_posts() ) {
	            $the_query->the_post();
	            keremiya_get_layout('series');
	        }
	        echo '</div>';

	    } else {
	        echo '<p class="mt10 ml9">'._k_('Favori listesinde gösterilebilecek hiçbir film bulunamadı.').'</p>';
	    }
	    wp_reset_postdata();
	} else {
		echo '<p class="mt10 ml9">'._k_('Favori listesinde gösterilebilecek hiçbir film bulunamadı.').'</p>';
	}
	?>
	</div>

	<?php elseif($q == _k_('puanlarim')): ?>

	<h2 class="title"><span><?php _k('Puanlarım'); ?></span></h2>
	<div class="film-content watchlist">
	<?php

	$ids = unserialize(get_user_meta($user_ID, 'my_ratings', true));

	if($ids) {

		foreach ($ids as $key => $value) {
			$post_in[] = $key;
		}

		// İlgili Filmler Listelenir
	    $args = array(
	        'posts_per_page' => 100,
	        'post__in'	=> $post_in,
	        'v_orderby' => 'desc',
	    );

	    // The Query
	    $the_query = new WP_Query( $args );

	    // The Loop
	    if ( $the_query->have_posts() ) {
	        echo '<div class="clearfix list_items" data-this="fav">';
	        while ( $the_query->have_posts() ) {
	            $the_query->the_post();
	            echo '
	            <div class="movie-preview normal_item res_item col-7">
	            	<div class="movie-preview-content">
	            	<div class="movie-poster">
	            		<a href="'.get_the_permalink().'">
	            ';
	            
	            keremiya_afis_sistemi();
	            keremiya_get_image( array('size' => 'anasayfa-resim') );

	            echo '
	            	</a>
					</div>

					<div class="movie-details">
					<span class="movie-title">
						<a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>
					</span>

					<span class="movie-release">
						'.keremiya_get_meta('yapim').'

						<span class="icon-star my-rate">'.$ids[ get_the_ID() ].'</span>
					</span>
					</div>
					</div>
				</div>
	            ';
	        }
	        echo '</div>';

	    } else {
	        echo '<p class="mt10 ml9">'._k_('Puanlarım sayfanızda gösterilebilecek hiçbir film bulunamadı.').'</p>';
	    }
	    wp_reset_postdata();
	} else {
		echo '<p class="mt10 ml9">'._k_('Puanlarım sayfanızda gösterilebilecek hiçbir film bulunamadı.').'</p>';
	}
	?>
	</div>

	<?php elseif($q == _k_('katkilarim')): ?>

	<h2 class="title"><span><?php _k('Katkılarım'); ?></span></h2>
	<div class="my-posts">
	<?php
	// Post Query	
	$args=array(
	'post_type' => array('post', 'news'),
	'author' => $user_ID,
	'post_status' => array( 'pending', 'trash', 'publish' ),
	'caller_get_posts'=> 1,
	'paged' => $paged,
	'posts_per_page' => 10
	);
	?>
	
		<?php query_posts($args); ?>
		<?php if( have_posts() ) : ?>
		<table class="my_account_videos">

		<thead>
			<tr>
				<th class="videos-action"><?php _k("Yönet"); ?></th>
				<th class="videos-title"><span class="nobr"><?php _k("Başlık"); ?></span></th>
				<th class="videos-date"><span class="nobr"><?php _k("Tarih"); ?></span></th>
				<th class="videos-status"><span class="nobr"><?php _k("Durum"); ?></span></th>
			</tr>
		</thead>

		<tbody>
		<?php while ( have_posts() ) : the_post(); ?>
		<tr class="videos">
					<td class="videos-action">
					<a href="<?php echo add_query_arg( array( 'q' => 'edit', 'id' => $post->ID ) ); ?>"><button disabled="disabled" class="button"><?php _k("Düzenle"); ?></button></a>
					</td>
					<td class="videos-title">
					<a href="<?php the_permalink(); ?>">
						<span class="img"></span>
					</a>
					<div>
						<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
						</a>
						<?php 
							$post_type = get_post_type();
							if ($post_type == "post") {
								echo '<div class="post-type-movie">('._k_("Film").')</div>';
							} elseif ($post_type == "news") {
								echo '<div class="post-type-news">('._k_("Haber").')</div>';
							}
						?>
					</div>
					

					</td>
					<td class="videos-date">
					<?php the_time("d M Y"); ?><br/><?php the_time('G:i'); ?>
					</td>
					<td class="videos-status">
					<?php $status = get_post_status($post->ID);
					if ($status == "publish") {
						echo '<div class="status publish">'._k_("Yayınlandı").'</div>';
					} elseif ($status == "pending") {
						echo '<div class="status pending">'._k_("Onay bekliyor").'</div>';
					} elseif ($status == "trash") {
						echo '<div class="status trash">'._k_("Silindi").'</div>';
					}
					?>
					</td>
		</tr>
		<?php endwhile; ?>
		</tbody>

		</table>
		<?php else: ?>
		<p class='mt10'><?php _k('Katkılarım sayfanızda gösterilebilecek hiçbir içerik bulunamadı.'); ?></p>
		<?php endif; ?>

		<?php keremiya_pagenavi(); ?>

		<?php wp_reset_query(); ?>
	</div>
	<?php endif; ?>

</div>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>