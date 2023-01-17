<div class="slider-content">
<div class="top"><span><?php _k('Editörün Önerileri'); ?></span></div>
<div class="old-style owl-carousel normal">

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
<div class="slide-item">
	<?php
		// GET IMAGE
		$image = keremiya_get_image( array('size' => 'anasayfa-resim', 'type' => 'url') ); 
	?>
<div class="movie-preview movie-<?php the_ID(); ?> normal_item res_item col-1">
<div class="movie-preview-content">
	<div class="movie-poster">
		<?php keremiya_addto_watchlist(); ?>
		<a href="<?php the_permalink() ?>">
			<?php keremiya_afis_sistemi(); ?>
			<?php keremiya_get_image( array('size' => 'anasayfa-resim') ); ?>
		</a>
	</div>
</div>
</div>
</div><!--.slide-item-->
<?php endwhile; ?>

</div><!--.owl-carousel-->
</div>

<script type="text/javascript">
jQuery( document ).ready(function( $ ) {
	$('.old-style').owlCarousel({
	    loop:true,
	    nav:true,
	    //animateOut: 'fadeOut',
	    navText: ["<span class='prev icon-angle-left'></span>","<span class='next icon-angle-right'></span>"],
	    margin:0,
	    slideBy:2,
	    autoplay: true,
		autoplayTimeout:5000,
    	autoplayHoverPause:true,
	    responsive:{
	        0:{
	            items:3,
	            stagePadding: 0,
	        },
	        600:{
	            items:4,
	            stagePadding: 0,
	        },
	        800:{
	            items:6,
	            stagePadding: 0,
	        },
	        1000:{
	            items:7,
	            stagePadding: 0,
	        }
	    }
	});
});
</script>