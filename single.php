<?php get_header(); ?>

<div id="content">
<div class="content wrapper clearfix">
	<?php while (have_posts()) : the_post(); ?>
	<div class="single-content movie">

		<div class="info-left">
			<div class="poster">
				<?php keremiya_afis_sistemi(); ?>
				<?php keremiya_get_image( array('size' => 'anasayfa-resim') ); ?>
			</div>
			<?php edit_post_link(_k_('Filmi Düzenle')); ?>
		</div>

			<div class="rating">

				<div class="vote">
				<?php echo keremiya_ratings_html(); ?>
				</div>

				<div class="rating-bottom">
				<?php
					$imdb_rating = keremiya_get_meta('imdb');
					if ( $imdb_rating ):
						echo '<span class="imdb-rating">';
						echo "{$imdb_rating} <small>"._k_('IMDB Puanı')."</small>";
						echo '</span>';
					endif;
				?>
				<?php
					$num_comments = get_comments_number();
					if ( $num_comments ):
						echo '<span class="comments-number">';
						echo "{$num_comments} <small>"._k_('Yorum')."</small>";
						echo '</span>';
					endif;
				?>
				<?php
					$views = keremiya_izlenme();
					if ( $views ):
						echo '<span class="views-number">';
						echo "{$views} <small>"._k_('Görüntülenme')."</small>";
						echo '</span>';
					endif;
				?>
				</div>

				<?php keremiya_breadcrumbs(); ?>
			</div>

		<div class="info-right">
			<div class="title">
				<h1><?php the_title(); ?></h1>
				<?php
					$release = get_the_term_list( $post->ID, _k_('yapim'), '', ' - ' );
					$release = $release ? $release : keremiya_get_meta('yapim');
					if ( $release ):
						echo '<div class="release">';
						echo "({$release})";
						echo '</div>';
					endif;
				?>
			</div>

			<div class="categories">
				<?php the_category(' ') ?>
			</div>

			<div class="cast">
				<?php
					$director = get_the_term_list( $post->ID, _k_('yonetmen'), '', ', ' );
					$director = $director ? $director : keremiya_get_meta('yonetmen');
					if ( $director ):
						echo '<div class="director">';
						echo "<h4>"._k_('Yönetmen').":</h4> {$director}";
						echo '</div>';
					endif;
				?>
				<?php
					$actor = get_the_term_list( $post->ID, _k_('oyuncular'), '', ', ' );
					$actor = $actor ? $actor : keremiya_get_meta('oyuncular');
					if ( $actor ):
						echo '<div class="actor">';
						echo "<h4>"._k_('Oyuncular').":</h4> {$actor}";
						echo '</div>';
					endif;
				?>
			</div>

			<?php
				$excerpt_hide = keremiya_get_option('excerpt_hide');
				$showmore = keremiya_get_option('showmore');
				$excerpt_class = 'excerpt';

				if($excerpt_hide == 'hide') {
					if($showmore)
						$excerpt_class .= ' more line-hide';
					else
						$excerpt_class .= ' line-clamp line-hide';
				}
				elseif($excerpt_hide == '') {
					$excerpt_class .= ' line-clamp line-hide';
				}
			?>

			<div class="<?php echo $excerpt_class; ?>">
				<?php echo keremiya_get_story(); ?>
			</div>
		</div>

		
	</div>

	<?php 
		$large_sidebar = keremiya_get_option('large_sidebar');

		// IF LARGE SIDEBAR
		if($large_sidebar)
			echo '<div class="content-left">';
	?>

	<div class="single-content video">
		
		<?php do_action('keremiya_content_before'); ?>
		
		<div class="action-buttons clearfix">
			<?php
			$new_part_system = keremiya_get_option('new_part_system');

			if($new_part_system) {
				echo '<div id="action-parts" class="action">';
				keremiya_parts();
				echo '</div>';
			} else {
				echo '<div id="action-parts" class="old-part-system">';
				keremiya_old_parts();
				echo '</div>';
			}
			?>

			<?php if(!$new_part_system) echo '<div class="align-right">'; ?>

			<?php if(keremiya_get_option('addto')): ?>
			<div id="action-addto" class="action">
				<?php keremiya_addto(); ?>
			</div>
			<?php endif; ?>

			<?php if(keremiya_get_option('share')): ?>
			<div id="action-share" class="action">
				<?php keremiya_share(); ?>
			</div>
			<?php endif; ?>
			
			<?php if(keremiya_get_option('report')): ?>
			<div id="action-report" class="action">
				<?php keremiya_report(); ?>
			</div>
			<?php endif; ?>

			<?php if(keremiya_get_option('wide')): ?>
			<?php keremiya_wide(); ?>
			<?php endif; ?>

			<?php if(!$new_part_system) echo '</div>'; ?>

		</div><!--action-buttons-->

		<div class="video-content">
			<?php
				// İçeriği Getir
				$content = keremiya_get_content();
				// İzleme Alanı Varsayılan Class Değeri
				$videoarea = keremiya_get_option('videoarea');
				$front_ad = keremiya_get_option('banner_before_video');

				if($videoarea == 'autosize') {
					$container_class = 'autosize-container';
				}
				elseif($videoarea == 'center') {
					$container_class = 'center-container';
				}
				else {
					$container_class = 'video-container';
				}

				if(!$content)
					$container_class = $container_class . ' no-video';

				if($front_ad) {
					$div_id = 'id="cn-content"';
					keremiya_video_front_ads();
				}

				echo '<div '.$div_id.' class="'.$container_class.'">';
					if($content)
						echo $content;
					else
						echo keremiya_no_content( $autosize );
				echo '</div>';
				?>
		</div>

		<?php do_action('keremiya_content_after'); ?>

	</div>

	<div class="single-content tabs">
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
		<div class="tab-buttons noselect">
			<li data-id="comments" class="tab comments-tab active"><span class="icon-chat iconfix"><?php _k('Yorumlar'); ?></span></li>
			<li data-id="details" class="tab details-tab"><span class="icon-th-list iconfix"><?php _k('Detaylar'); ?></span></li>
			<?php if($images > 1): ?><li data-id="images" class="tab images-tab"><span class="icon-picture iconfix"><?php _k('Resimler'); ?></span></li><?php endif; ?>
			<li data-id="sidebar" class="tab sidebar-tab"><span class="icon-video iconfix"><?php _k('Filmler'); ?></span></li>
		</div>
	</div>

	<div class="single-content detail" id="sss">

		<div id="details" class="wrap">
			<?php
				$review = keremiya_get_review();
				$rClass = 'no-sr';
			?>

			<h2 class="title">
				<span>
					<?php if($review): ?><?php _k('Özet'); ?> &<?php endif; ?> <?php _k('Detaylar'); ?>
				</span>
			</h2>

			<?php
				if($review) {
					$rClass = '';
					echo '<div class="storyline">';
					echo $review;
					echo '</div>';
				}
			?>
			<div class="others <?php echo $rClass; ?>">

				<?php
					$metas = array(
						'diger-adlari' => _k_('Diğer Adları'),
						'yayin-tarihi' => _k_('Yayın Tarihi'),
						'senaryo' => _k_('Senaryo'),
						'oduller' => _k_('Ödüller'),
						'yapim-sirketi' => _k_('Yapım Şirketi'),
						'butce' => _k_('Bütçe'),
						'box-office' => _k_('Box Office'),
					);
					$i = 0;
					foreach ($metas as $key => $name) {
						$details = keremiya_get_details_meta($key, $name);
						if($details)
							$i++;
					}

					if($i === 0 && !$review) {
						echo '<p class="mt10 info" style="">'._k_('Henüz detay eklenmemiş.').'</p>';
					}
				?>

				<?php the_tags('<div class="tags info"><h4>'._k_('Etiketler').'</h4>: ', ', ', '</div>'); ?>
			</div>
		</div>
		
		<?php if($images > 1): ?>
		<?php wp_enqueue_script( 'gallery', get_template_directory_uri() . '/js/gallery.min.js', array(), null, false ); // True ?>
		<div id="images" class="wrap">

			<h2 class="title">
				<span><?php _k('Resimler'); ?></span>
			</h2>

			<p class="images-excerpt">
				<?php printf( _k_( '"%1$s" filmine ait %2$s adet görsel bulundu.'), get_the_title(), $images ); ?>
			</p>

			<div class="gallery" data-max="<?php echo $images; ?>">
			<?php $i = 0; foreach ( $attachments as $attachment_id => $attachment ) { $i++; ?>
				<div class="thumb" data-num="<?php echo $i; ?>">
					<?php the_attachment_link( $attachment_id , false ); ?>
				</div>
			<?php }; ?>
			</div>
		</div>
		<?php endif; ?>

		<div id="comments" class="wrap active">
			<?php comments_template(); ?>
		</div>

	</div>
	<?php endwhile; ?>

	<?php 
		// IF LARGE SIDEBAR
		if($large_sidebar)
			echo '
				</div>
				<div class="content-right">
			';

		$sidebar_ID = 'sidebar';
		if(keremiya_get_option('sticky_sidebar'))
			$sidebar_ID .= ' sticky-sidebar';
	?>

	<div class="single-content sidebar" id="<?php echo $sidebar_ID; ?>">
		<?php get_sidebar('single'); ?>
	</div>

	<?php 
		// IF LARGE SIDEBAR
		if($large_sidebar)
			echo '</div>';
	?>

</div>
</div>
<?php get_footer();?>