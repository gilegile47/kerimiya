<?php
/**
 * Keremiya 5
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="movie-preview movie-<?php the_ID(); ?> normal_item res_item col-<?php echo keremiya_get_col_movies(); ?>">
<div class="movie-preview-content">
	<div class="movie-poster">
		<?php keremiya_addto_watchlist(); ?>
		<a href="<?php the_permalink() ?>">
			<?php keremiya_afis_sistemi(); ?>
			<?php keremiya_get_image( array('size' => 'large-resim') ); ?>
		</a>
	</div>

	<div class="movie-details">
		<span class="movie-title">
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</span>

		<span class="movie-release">
		<?php 
			$release = keremiya_get_meta('yapim');
			if ( $release ):
				echo $release;
			endif;

			$imdb_rating = keremiya_get_meta('imdb');
			if($imdb_rating)
				echo '<span class="icon-star align-right tooltip" title="'._k_('IMDB Puanı').'">'.$imdb_rating.'</span>';
		?>
		</span>
	</div>
</div>
</div>