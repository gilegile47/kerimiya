<?php
add_action('wp_ajax_keremiya_comment_vote', 'keremiya_comment_vote');
add_action('wp_ajax_nopriv_keremiya_comment_vote', 'keremiya_comment_vote');
// Herşey bunda biter
function keremiya_comment_vote(){
	$w = $_POST['w'];
	$id = $_POST['id'];

		switch($w){
			case 'like':
				if(!is_user_logged_in() && keremiya_get_option('comment_like') == 'public') {
					_likeThisComment($id,'like');
				} else {
					_keremiya_comment_like($id);
				}
			break;
			
			case 'dislike':
				if(!is_user_logged_in() && keremiya_get_option('comment_like') == 'public') {
					_likeThisComment($id,'dislike');
				} else {
					_keremiya_comment_dislike($id);
				}
			break;

			default:
			break;
		}
}

function _likeThisComment($comment_id, $action) {

	if(!is_numeric($comment_id))
		return;

	switch($action) {
	
	case 'like':
		if($_POST['c']) {
			echo _k_('Hay Aksi').'|'._k_('Bu yorumu daha önce oylamışsınız.').'|..';
			die();
		}

		$currentValue = get_comment_meta($comment_id, 'votes_up');
		
		if(!is_numeric($currentValue[0])) {
			$currentValue[0] = 0;
			add_comment_meta($comment_id, 'votes_up', '1', true);
		} //if
		
		$currentValue[0]++;
		update_comment_meta($comment_id, 'votes_up', $currentValue[0]);
		
		echo "1";
		die;
	break;
	
	case 'dislike':

		if($_POST['c']) {
			echo _k_('Hay Aksi').'|'._k_('Bu yorumu daha önce oylamışsınız.').'|..';
			die();
		}
		
		$currentValue = get_comment_meta($comment_id, 'votes_down');
		
		if(!is_numeric($currentValue[0])) {
			$currentValue[0] = 0;
			add_comment_meta($comment_id, 'votes_down', '1', true);
		} //if
		
		$currentValue[0]++;
		update_comment_meta($comment_id, 'votes_down', $currentValue[0]);

		echo "1";
		die();
	break;

	} //switch

} //likeThis

function _keremiya_comment_like($id) {

	if (!is_user_logged_in()) {
		echo _k_('Hay Aksi').'|'._k_('Yorumları sadece üyeler oylayabilir.').'|'.keremiya_popup_login_url();
		die();
	}
	
	$past_votes = (array) get_user_meta(get_current_user_id(), 'comment_past_votes', true);
			
		$comment_id = $id;
		
		if ($comment_id && !in_array( $comment_id, $past_votes )) {
			
			$past_votes[] = $comment_id;
			update_user_meta(get_current_user_id(), 'comment_past_votes', $past_votes);

			$past_votes_up = (array) get_user_meta(get_current_user_id(), 'comment_past_votes_up', true);
			$past_votes_up[] = $comment_id;
			update_user_meta(get_current_user_id(), 'comment_past_votes_up', $past_votes_up);
			
			$post_votes = (int) get_comment_meta($comment_id, 'votes_up', true);
			$post_votes++;
			update_comment_meta($comment_id, 'votes_up', $post_votes);

			echo "1";
			
		} else {

			echo _k_('Hay Aksi').'|'._k_('Bu yorumu daha önce oylamışsınız.').'|..';

		}
die;
}

function _keremiya_comment_dislike($id) {

	if (!is_user_logged_in()) {
		echo _k_('Hay Aksi').'|'._k_('Yorumları sadece üyeler oylayabilir.').'|'.keremiya_popup_login_url();
		die();
	}
	
	$past_votes = (array) get_user_meta(get_current_user_id(), 'comment_past_votes', true);
			
		$comment_id = $id;
		
		if ($comment_id && !in_array( $comment_id, $past_votes )) {
			
			$past_votes[] = $comment_id;
			update_user_meta(get_current_user_id(), 'comment_past_votes', $past_votes);

			$past_votes_up = (array) get_user_meta(get_current_user_id(), 'comment_past_votes_down', true);
			$past_votes_up[] = $comment_id;
			update_user_meta(get_current_user_id(), 'comment_past_votes_down', $past_votes_up);
			
			$post_votes = (int) get_comment_meta($comment_id, 'votes_down', true);
			$post_votes++;
			update_comment_meta($comment_id, 'votes_down', $post_votes);

			echo "1";
			
		} else {

			echo _k_('Hay Aksi').'|'._k_('Bu yorumu daha önce oylamışsınız.').'|..';

		}
die;
}

function keremiya_comment_vote_button($comment) {

	$comment_id = $comment->comment_ID;
	$like = get_comment_meta($comment_id, 'votes_up', true);
	$dislike = get_comment_meta($comment_id, 'votes_down', true);

	$output = '
		<button class="button comment-vote like-button tooltip cl_'.$comment_id.'" type="button" data-id="'.$comment_id.'" data-type="like" data-this=".cl_'.$comment_id.'" title="'._k_('Bu yorumu beğendim').'">
			<span class="count">'.$like.'</span>
			<span class="icon-thumbs-up-alt"></span>
		</button>
		<button class="button comment-vote dislike-button tooltip cd_'.$comment_id.'" type="button" data-id="'.$comment_id.'" data-type="dislike" data-this=".cd_'.$comment_id.'" title="'._k_('Bu yorumu beğenmedim').'">
 			<span class="icon-thumbs-down-alt"></span>
			<span class="count">'.$dislike.'</span>
		</button>
	';

echo $output;
}