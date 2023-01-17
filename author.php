<?php get_header();

// KULLANICI BILGILERI
if(isset($_GET['author_name'])) :
	$profil = get_userdatabylogin($author_name);
	get_userdatabylogin(get_the_author_login());
	(get_the_author_login());
else :
	$profil = get_userdata(intval($author));
endif;
?>


<div id="content">
<div class="content wrapper clearfix">

	<div id="profile-header">
		<div class="profile-left">

			<div class="col-1">
				<div class="avatar">
				<?php echo get_avatar($profil->ID, '48', keremiya_custom_gravatar());?>
				</div>
			</div>

			<div class="col-2">
				<div class="title">
					<h1 class="title">
						<span><?php echo $profil->display_name; ?></span>
					</h1>
					<div class="social">
						<?php 

						$fb = get_user_meta($profil->ID, 'facebook', true);
						$tw = get_user_meta($profil->ID, 'twitter', true);
						$gp = get_user_meta($profil->ID, 'google', true);

						if($fb)
							echo '<a class="icon-facebook tooltip" target="_blank" href="https://www.facebook.com/'.$fb.'" title="'._k_('Facebook Sayfası').'"></a>';
						if($tw)
							echo '<a class="icon-twitter-bird tooltip" target="_blank" href="https://twitter.com/'.$tw.'" title="'._k_('Twitter Sayfası').'"></a>';
						?>
					</div>
				</div>
				<?php
					$description = $profil->user_description;
					$description = $description ? $description : _k_('Henüz kendisini bizlere anlatmamış.');
				?>
				<div class="excerpt"><?php echo $description; ?></div>
			</div>
	
			<div class="col-3">
				<div class="list-info align-center">
					<?php
						$fCount = keremiya_get_user_addto_count($profil->ID, 'addto_fav');
						if($fCount) {
							echo '<div class="info">'.$fCount.' <span>'._k_('Favorileri').'</span></div>';
						}
					?>
					<?php
						$wCount = keremiya_get_user_addto_count($profil->ID, 'addto_later');
						if($wCount) {
							echo '<div class="info">'.$wCount.' <span>'._k_('İzleyecekleri').'</span></div>';
						}
					?>
					<?php
						$yCount = get_user_meta($profil->ID,'total_comments', true);
						if($yCount) {
							echo '<div class="info">'.$yCount.' <span>'._k_('Yorumları').'</span></div>';
						}
					?>
					<?php
						/*$vCount = get_user_meta($profil->ID,'total_votes', true);
						if($yCount || $vCount) {
							echo '<div class="info">'.($vCount + $yCount).' <span>'._k_('Katkıları').'</span></div>';
						}*/
					?>
				</div>
			</div>
		</div>
	</div>

	<div class="single-content tabs">
		<div class="tab-buttons noselect">
				<li data-id="user-favori" class="tab comments-tab <?php $q = keremiya_get('q'); if($q == _k_('favoriler') || !$q) echo 'active'; ?>"><span class="icon-star iconfix"><?php _k('Favori Filmleri'); ?></span></li>
				<li data-id="user-list" class="tab images-tab <?php if($q == _k_('izleme-listesi')) echo 'active'; ?>"><span class="icon-clock iconfix"><?php _k('İzleme Listesi'); ?></span></li>
				<li data-id="user-comments" class="tab details-tab"><span class="icon-comment iconfix"><?php _k('Yorumları'); ?></span></li>
				<!--<li data-id="user-kk" class="tab images-tab"><span class="icon-right-open iconfix">Tüm Katkıları</span></li>-->
		</div>
	</div>
<div class="clear"></div>
<div class="film-content wrap <?php if($q == _k_('favoriler') || !$q) echo 'active'; ?>" id="user-favori">
<?php
$ids = unserialize(get_user_meta($profil->ID, 'addto_fav', true));
if($ids) {
	// İlgili Filmler Listelenir
    $args = array(
        'posts_per_page' => 12,
        'post__in'	=> $ids,
        'v_orderby' => 'desc',
    );

    // The Query
    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ) {
        echo '<div class="fix-author_item fix_author clearfix list_items">';
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            keremiya_get_layout('film');
        }
        echo '</div>';

    } else {
        echo '<p class="mt10 ml9">'._k_('Favori listesinde gösterilebilecek hiçbir film bulunamadı.').'</p>';
    }
    wp_reset_postdata();
} else {
	echo '<p class="mt10 ml9">'._k_('Favori listesinde gösterilebilecek hiçbir film bulunamadı.').'</p>';
}
?>
</div>


<div class="film-content wrap <?php if($q == _k_('izleme-listesi')) echo 'active'; ?>" id="user-list">
<?php
$ids = unserialize(get_user_meta($profil->ID, 'addto_later', true));
if($ids) {
	// İlgili Filmler Listelenir
    $args = array(
        'posts_per_page' => 12,
        'post__in'	=> $ids,
        'v_orderby' => 'desc',
    );

    // The Query
    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ) {
        echo '<div class="fix-author_item fix_author clearfix list_items">';
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            keremiya_get_layout('film');
        }
        echo '</div>';

    } else {
        echo '<p class="mt10 ml9">'._k_('İzleme listesinde gösterilebilecek hiçbir film bulunamadı.').'</p>';
    }
    wp_reset_postdata();
} else {
	echo '<p class="mt10 ml9">'._k_('İzleme listesinde gösterilebilecek hiçbir film bulunamadı.').'</p>';
}
?>
</div>

	<div class="single-content wrap" id="user-comments">
<?php

function keremiya_comment_new($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
   	$resim = keremiya_get_comment_image_url($comment->comment_post_ID, 'izlenen-resim');
 	$comm = '';
	$comm .= '<li><a href="' . get_permalink( $comment->comment_post_ID ) . '"><img alt="'.get_the_title( $comment->comment_post_ID ).'" src="'.$resim.'"></a>';
	$comm .= '<div class="title">'.sprintf(_k_('%s <span>için demiş ki;</span>'), get_the_title( $comment->comment_post_ID ) ).'</div>';
	$comm .= '<p>' . strip_tags( $comment->comment_content ) . '</p></li>';
	echo $comm;
}
	
$comments_per_page = 10;
$comments = get_comments(array(
	'user_id' => $profil->ID,
    'status' => 'approve' 
));

$page = intval( keremiya_get( 'comment-page' ) );
$page = (0 == $page) ? 1 : $page;
$found_comments = count($comments);
$max_num_pages = ceil($count / $comments_per_page);

if($found_comments):
echo '<ul>';
wp_list_comments(array(
        'page' => $page,
        'per_page' => $comments_per_page,
		'callback' => "keremiya_comment_new",
		'reverse_top_level' => false
    ), $comments);
echo '</ul>';
else:
echo '<p class="mt10">'._k_('Henüz hiç yorum yapmamış.').'</p>';
endif;

if($found_comments > $yCount) {
	update_user_meta($profil->ID,'total_comments', $found_comments);
}

?>
</div>

	<div class="single-content sidebar">
		<?php get_sidebar(); ?>
	</div>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer();?>