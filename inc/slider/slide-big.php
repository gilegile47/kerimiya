<div class="big-style owl-carousel full">

<?php 
	$args = array(
		'post_type' => 'post',
		'showposts' => keremiya_get_option('slider_number'),
		'cat' => keremiya_get_option('slider_cat'),
	);

	$keremiya_slide = new WP_Query($args);
	// QUERY
	while ($keremiya_slide->have_posts()) : $keremiya_slide->the_post(); 
?>
<div class="slide-item full">
	<?php
		// GET IMAGE
		$image = keremiya_get_image( array('size' => 'anasayfa-resim', 'type' => 'url') ); 
	?>
	<div class="slide-bg">
		<img src="<?php echo $image; ?>" />
	</div>

	<div class="slide-shadow"></div>

	<div class="slide-content">

		<div class="poster" style="position:relative">
			<a href="<?php the_permalink(); ?>"><img src="<?php echo $image; ?>" alt="<?php echo get_the_title(); ?>" /></a>
		</div>

		<div class="info-left">

			<div class="title">

				<div class="rating">
					<div class="vote">
						<div class="site-vote">
							<span class="icon-star"><span><?php echo keremiya_get_meta('imdb'); ?></span></span>
						</div>
					</div>
				</div>

				<span class="ellipsis"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>

				<span class="release-year">
	            <?php 
					$release = keremiya_get_meta('yapim');
					if ( $release ):
						echo "({$release})";
					endif;
				?>
				</span>

			</div><!--.title-->

			<div class="extras">

				<div class="extra-category">
				<?php
					// GET CATEGORY
					$categories = get_the_category();
					$separator = ', ';
					$output = '';
					if ( ! empty( $categories ) ) {
					    foreach( $categories as $category ) {
					        $output .= esc_html( $category->name ) . $separator;
					    }
					    echo trim( $output, $separator );
					}
				?>
				</div>

				<div class="release-date">
	            <?php 
					$release_date = keremiya_get_meta('yayin-tarihi');
					if ( $release_date ):
						echo '<span class="release">';
						echo "({$release_date})";
						echo '</span>';
					endif;
				?>
				</div>

			</div><!--.extras-->

			<div class="excerpt">
				<?php
					$story = keremiya_get_excerpt();
					if ( $story ):
						echo '<span class="title">'._k_('Özet').'</span>';
						echo '<p class="story">';
						echo "{$story}";
						echo '</p>';
					endif;
				?>
			</div>

			<div class="cast">
			<?php
				$director = keremiya_get_meta('yonetmen');
				if ( $director ):
					echo '<span class="director">';
					echo "<strong>"._k_('Yönetmen').":</strong> {$director}";
					echo '</span>';
				endif;

				$actor = keremiya_get_meta('oyuncular');
				if ( $actor ):
					echo '<span class="actor">';
					echo "<strong>"._k_('Oyuncular').":</strong> {$actor}";
					echo '</span>';
				endif;
			?>
			</div><!--.cast-->
		</div><!--.info-left-->
	</div><!--.slide-content-->
</div><!--.slide-item-->
<?php endwhile; ?>

</div><!--.owl-carousel-->

<script type="text/javascript">
jQuery( document ).ready(function( $ ) {
	$('.big-style').owlCarousel({
	    center: true,
	    loop:true,
	    nav:true,
	    //animateOut: 'fadeOut',
	    navText: ["<span class='prev icon-angle-left'></span>","<span class='next icon-angle-right'></span>"],
	    margin:0,
	    autoplay: true,
		autoplayTimeout:5000,
    	autoplayHoverPause:true,
	    responsive:{
	        0:{
	            items:1,
	            stagePadding: 0,
	        }
	    }
	});
});
</script>