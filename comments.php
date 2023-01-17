<?php
/**
 * @package WordPress
 * @subpackage Keremiya_5
 * @since Keremiya v5
 */

if ( post_password_required() )
	return;

if ( !comments_open() ) {
	keremiya_no_comments('open');
	return;
}
if ( get_option('comment_registration') && !is_user_logged_in() ) :
	keremiya_no_comments('user');
	$nocomment = true;
else: ?>
<div id="respond">
	<div class="col-left">
		<div class="comment-avatar">
			<?php echo get_avatar($comment, '64');?>
		</div>
	</div>

	<div class="col-right">
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		
		
		<div id="cancel-comment-reply-link" style="display:none">
				<a rel="nofollow" href="#respond" class="tooltip" title="<?php _k('İptal Et'); ?>"><span class="icon-cancel"></span></a>
		</div>
		
		<div class="comment-form-comment">
			<textarea id="comment-textarea" name="comment" aria-required="true" placeholder="<?php _k("Hadi hemen bir yorum paylaş."); ?>"></textarea>
		</div>
			
		<div class="comment-form-inputs clearfix">
		<?php if ( is_user_logged_in() ): ?>

		<?php do_action( 'comment_form_logged_in_after' ); ?>

		<?php else: ?>
			<div class="comment-input-hide">
				<div class="comment-form-author">
					<input id="author" name="author" value="" aria-required="true" placeholder="<?php _k("İsim"); ?>" type="text">
				</div>

				<div class="comment-form-email">
					<input id="email" name="email" value="" aria-required="true" placeholder="<?php _k("E-Posta"); ?>" type="text">
				</div>
			
				<div class="clear"></div>

				<?php do_action( 'comment_form_after_fields' ); ?>
			</div>
		<?php endif; ?>

		<?php do_action( 'comment_form', get_the_ID() ); ?>
		
			<div class="comment-form-submit">
				<button name="submit" id="submit" class="button submit-button" value="<?php _k("Gönder"); ?>" type="submit"><span class="icon-right-open"><?php _k('Gönder'); ?></span></button>
				<?php comment_id_fields(); ?>
			</div>

		</div><!-- .comment-form-inputs -->
	</form>
	</div><!-- .col-right -->
</div><!-- #respond -->
<?php endif; ?>

	<div class="clear"></div>


		<?php if ( have_comments() ) : ?>
		
		<?php
		if(get_comments_number() > 10):
			//Gather comments for a specific page/post 
			$popular = get_comments(array(
				'post_id' => get_the_ID(),
				'number' => 2,
				'meta_key' => 'votes_up',
				'orderby' => 'meta_value_num',
				'status' => 'approve' //Change this to the type of comments to be displayed
			));
			if($popular) {
				echo '<div class="popular-comments clearfix">';
				echo '<div class="top"><span>'._k_('Popüler Yorumlar').' ('.count($popular).')</span></div>';
				echo '<ol class="comment-list">';
				foreach ($popular as $comment) {
					$offset[] = $comment->comment_ID;
					keremiya_list_comments($comment, array(), '1' );
				}
				echo '</ol>';
				echo '</div>';
			}
		endif;
		?>
		<!--<style type="text/css">
		<?php foreach ($offset as $key => $value) { echo '.all-comments #li-comment-'.$value.' { display: none; }'; }?>
		</style>-->

		<h2 class="title">
			<span><?php comments_number( _k_("Henüz Yorum Bulunmuyor"), _k_("Yorumlar (1)"), _k_("Yorumlar (%)")); ?></span>
		</h2>

		<ol class="comment-list all-comments">
			<?php
				wp_list_comments( array(
					'style'    => 'ol',
					'callback' => 'keremiya_list_comments',
				) );
			?>
		</ol><!-- .comment-list -->
		<?php
			// Are there comments to navigate through?
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
		?>
	
		<div class="navigation comment-navigation keremiya-pagenavi">
			<?php paginate_comments_links( $args ); ?>
		</div><!-- .comment-navigation -->
		<?php endif; // Check for comment navigation ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="nocomment"><?php _k( 'Yoruma Kapalı.' ); ?></p>
		<?php endif; ?>

	<?php else : ?>
		<?php if( ! $nocomment ) { ?>
		<div class="nocomment">
			<p><?php _k('Henüz hiç yorum yapılmamış.'); ?> <br> <?php _k('İlk yorumu yapan sen olmak istemez misin?'); ?></p>
		</div>
		<?php }; ?>
	<?php endif; // have_comments() ?>