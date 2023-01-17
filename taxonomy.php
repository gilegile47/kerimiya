<?php get_header(); ?>
<div id="content">
<div class="content wrapper clearfix">

	<div class="content-left">
		<div class="film-content">
		<h1 class="title"><span><?php single_term_title(); ?></span></h1>
		<?php 
			$description = term_description();
			if( $description ) {
				echo '<div class="mt10 ml9 mb10">';
				echo $description;
				echo '</div>';
			}
		?>
			<div class="fix-<?php echo keremiya_get_option('home_layout'); ?>_item fix_taxonomy clearfix list_items">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php keremiya_get_layout(keremiya_get_option('home_layout')); ?>
				<?php endwhile; else: ?>
					<p class="mt10 ml9"><?php _k('Henüz hiç film eklenmemiş.'); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="content-right">
		<div class="single-content sidebar">
			<?php get_sidebar(); ?>
		</div>
	</div>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>