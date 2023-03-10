<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title><?php keremiya_titles(); ?></title>
<?php keremiya_seo(); ?>

<?php wp_head(); ?>

</head>
<body <?php keremiya_body_class(); ?>>

<?php do_action('keremiya_body_after'); ?>

<div id="wrap">
<div id="header-wrapper">
	<div id="header" class="dark">
		<div class="header-content wrapper">
			<div class="header-left">
				<div class="menu-toogle icon-menu fix-absolute"></div>
				<div class="header-logo">
					<?php keremiya_get_logo(); ?>
				</div>
				<div class="search-toogle icon-search fix-absolute"></div>
				
				<div class="header-search">
					<?php get_search_form(); ?>
				</div>
			</div>
			
			<div class="header-right">
				<div class="header-user">
					<?php keremiya_user_menu(); ?>
				</div>
				
				<div class="header-social">
					<div class="header-social-icons">
						<?php do_action('keremiya_social_icons'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>


<div id="navbar" class="dark dark-border flexcroll">
	<div class="navbar-in-border">
		<div class="navbar-content wrapper">
			<div id="nav">
			<ul>
				<li class="menu-item-home <?php if(is_home()) { echo 'current-menu-item'; }; ?>"><a href="<?php echo get_bloginfo('url'); ?>"><?php _k('Anasayfa'); ?></a></li>
				<?php if ( has_nav_menu( 'header-nav' ) ) : ?>
					<?php wp_nav_menu(array('theme_location' => 'header-nav', 'items_wrap' => '%3$s', 'container' => 'false')); ?>
				<?php else: ?>
					<?php
					$archive_id = keremiya_pages_get_id('archive');
					if($archive_id) { ?>
					<li class="menu-item-film-archive <?php if(is_page($archive_id) || is_category()) { echo 'current-menu-item'; }; ?>"><a href="<?php echo keremiya_pages_get_url('archive'); ?>"><?php echo keremiya_pages_get_title('archive'); ?></a></li>
					<?php }; ?>
				<?php endif; ?>
			</ul>
			</div>
		</div>
	</div>
</div>
</div>
	
<div class="clear"></div>

<?php do_action('keremiya_header_after'); ?>