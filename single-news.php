<?php get_header(); ?>

<div id="content">
<div class="content wrapper clearfix">
	<div class="left-c">
		<?php while (have_posts()) : the_post(); ?>
		<div class="single-content article">
			<h1><?php the_title(); ?></h1>
			<div class="info-bar">
				<div class="news-time"><span class="icon-clock"></span> <?php echo get_the_time("d M Y"); ?></div>
				<div class="news-category"><span class="icon-tags"></span> <?php keremiya_custom_category( keremiya_news_category_name() ); ?></div>
				<div class="news-author"><span class="icon-user"></span> <?php the_author(); ?></div>
			</div>

			<div class="news-content article-content">
				<?php the_content(); ?>
			</div>

			<div class="tags">
				<?php
					$tags = get_the_term_list( $post->ID, 'news_tag', '<div class="tags info"><h4>'._k_('Etiketler').'</h4>: ', ', ', '</div>' );
					echo $tags;
				?>
			</div>
		</div>

		<div class="single-content article-tabs">
			<div class="tab-buttons noselect">
				<li data-id="comments" class="tab comments-tab active"><span class="icon-chat iconfix"><?php _k('Yorumlar'); ?></span></li>

				<div class="social-buttons">
					<?php keremiya_facebook_like_button( get_the_ID() ); ?>
				</div>
				<div class="social-buttons">
					<?php keremiya_twitter_share_button( get_the_ID() ); ?>
				</div>
				<div class="social-buttons">
					<?php keremiya_google_share_button( get_the_ID() ); ?>
				</div>
			</div>
		</div>

		<div class="single-content detail">
			<div id="comments" class="wrap active">
				<?php comments_template(); ?>
			</div>
		</div>

		<?php endwhile; ?>
	</div>

	<div class="left-r">
		<div class="single-content sidebar">
			<?php get_sidebar('news'); ?>
		</div>
	</div>

</div>
</div>
<?php get_footer();?>