<?php
/*
Template Name: Keremiya Film Arşivi
*/
get_header(); ?>

<div id="content">
<div class="content wrapper clearfix">

	<?php do_action('film_archive_before'); ?>

	<div class="single-content c-sidebar list-categories">
		<?php get_sidebar('list'); ?>
	</div>

	<div class="film-content category">
		<?php keremiya_category_list_movies(); ?>
	</div>

	<?php do_action('film_archive_after'); ?>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>