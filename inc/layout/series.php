<?php
/**
 * Keremiya 5
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="series-preview serie-<?php the_ID(); ?> small_item existing_item res_item col-<?php echo keremiya_get_col_series(); ?>">
<a href="<?php the_permalink() ?>">
<div class="series-preview-content clearfix">
	<div class="series-poster">
		<?php keremiya_get_image( array('size' => 'izlenen-resim') ); ?>
	</div>

	<div class="series-details existing-details">
		<span class="series-title">
			<?php the_title(); ?>
		</span>
		<span class="movie-release">
		<?php 
			$release = keremiya_get_meta('yapim');
			if ( $release ):
				echo $release;
			endif;
		?>
		</span>

		<div class="movie-specials">
			<?php
				$story = keremiya_get_excerpt();
				if ( $story ):
					echo '<div class="movie-excerpt">';
					echo "<p class='story'>{$story}</p>";
					echo '</div>';
				endif;
			?>
			<div class="movie-cast">
				<?php
					$director = keremiya_get_meta('yonetmen');
					if ( $director ):
						echo '<p class="director">';
						echo "<span>"._k_('Yönetmen').":</span> {$director}";
						echo '</p>';
					endif;
				?>
				<?php
					$actor = keremiya_get_meta('oyuncular');
					if ( $actor ):
						echo '<p class="stars">';
						echo "<span>"._k_('Oyuncular').":</span> {$actor}";
						echo '</p>';
					endif;
				?>
			</div>
			<div class="movie-info">
				<?php
					$imdb_rating = keremiya_get_meta('imdb');
					if ( $imdb_rating ):
						echo '<span class="icon-star imdb tooltip">';
						echo "{$imdb_rating} <span class='flear'></span><small>"._k_('IMDB Puanı')."</small>";
						echo '</span>';
					endif;
				?>
				<?php
					$views = keremiya_izlenme();
					if ( $views ):
						echo '<span class="icon-eye views tooltip">';
						echo "{$views} <span class='flear'></span><small>"._k_('İzlenme')."</small>";
						echo '</span>';
					endif;
				?>
				<?php
					$num_comments = get_comments_number();
					if ( $num_comments ):
						echo '<span class="icon-comment comments tooltip">';
						echo "{$num_comments} <span class='flear'></span><small>"._k_('Yorum')."</small>";
						echo '</span>';
					endif;
				?>
				<?php
					$likes = keremiya_get_meta('likes');
					if ( $likes ):
						echo '<span class="icon-heart likes tooltip">';
						echo "{$likes} <span class='flear'></span><small>"._k_('Beğeni')."</small>";
						echo '</span>';
					endif;
				?>
			</div>

		</div>
	</div>
</div>
<?php if(is_user_logged_in()): ?>
<span class="addto rmv tooltip-s" original-title="<?php _k('Çıkar'); ?>" data-id="<?php the_ID(); ?>" data-is="2"><span class="icon-cancel"></span></span>
<?php endif; ?>
</a>
</div>