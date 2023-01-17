<?php get_header(); ?>
<div id="content">
<div class="content wrapper clearfix">

	<div class="single-content archive-news">
	<h1 class="title"><span><?php single_term_title(); ?></span></h1>
	<p class="mt10"><?php printf('%s etiketine ait haberler listeleniyor.', single_term_title()); ?></p>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="fix-news">
			<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<div class="info-bar">
				<div class="news-time"><span class="icon-clock"></span> <?php echo get_the_time("d M Y"); ?></div>
				<div class="news-category"><span class="icon-tags"></span> <?php keremiya_custom_category( keremiya_news_category_name(), true ); ?></div>
				<div class="news-author"><span class="icon-user"></span> <?php the_author(); ?></div>
			</div>

			<div class="news-content article-content">
				<?php the_content(); ?>
			</div>
			</div>
		<?php endwhile; else: ?>
			<p><?php _k('Henüz hiç haber eklenmemiş.'); ?></p>
		<?php endif; ?>

		<?php keremiya_the_pagenavi(); ?>

	</div>

	<div id="archive-sidebar" class="left-r">
		<div class="single-content sidebar">
			<?php get_sidebar('news'); ?>
		</div>
	</div>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>