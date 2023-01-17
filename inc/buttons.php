<?php

function keremiya_parts_header() {
	$output = '<div class="parts-top clearfix">';
	$output .= '<div class="parts-name">'._k_('Kaynak').'</div>';
	$output .= '<div class="parts-lang">'._k_('Dil').'</div>';
	$output .= '<div class="parts-quality">'._k_('Kalite').'</div>';
	$output .= '</div>'; // .parts-top
	return $output;
}

function keremiya_parts($echo = 1) {
	$parts = wp_link_pages_titled(array('echo' => 0));

	$active_part = !keremiya_get_option('active_part') ? ': <span class="active-part"></span>' : '';

	$output = '';
	$output .= '<button class="button source-button arrow-bottom trigger" type="button"><span class="icon-menu iconfix">'._k_('Kaynak').$active_part.'</span></button>';
	$output .= '<div class="popup source-popup">';
	$output .= '<div class="flexcroll">';
	$output .= keremiya_parts_header();
	$output .= '<div class="parts-middle">';
	if($parts):
		$output .= $parts;
	else:
		$output .= '<p class="no-parts">'._k_('Şuan için kullanılabilir bir kaynak yok.').'</p>';
	endif;
	$output .= '</div>'; // .parts-middle
	$output .= '</div>'; // .flexcroll
	$output .= '</div>'; // .popup

	if (!$echo) 
		return $output;
	
	echo $output;
}

function keremiya_addto($echo = 1) {
	global $post;

	$output = '';
	$output .= '<button class="button addto-button arrow-bottom trigger" type="button"><span class="icon-plus iconfix">'._k_('Ekle').'</span></button>';
	$output .= '<div class="popup addto-popup">';
	$output .= '<ul>';
	$output .= '<li class="addto-later addto noselect" data-id="'.$post->ID.'" data-this="later">'.keremiya_addto_button($post->ID, 'addto_later', _k_('İzleme Listesi'), _k_('Listeden Çıkar'), 'icon-clock').'</li>';
	$output .= '<li class="addto-favori addto noselect" data-id="'.$post->ID.'" data-this="fav">'.keremiya_addto_button($post->ID, 'addto_fav', _k_('Favoriler'), _k_('Favorilerden Çıkar'), 'icon-star').'</li>';
	$output .= '</ul>';
	$output .= '</div>';

	if (!$echo) 
		return $output;
	
	echo $output;
}

function keremiya_share($echo = 1) {
	$output = '';
	$output .= '<button class="button share-button arrow-bottom trigger" type="button"><span class="icon-share-1 iconfix">'._k_('Paylaş').'</span></button>';
	$output .= '<div class="popup share-popup">';
	$output .= '<ul>';
	$output .= '<li class="share-facebook keremiya-share noselect" data-type="fb"><span class="icon-facebook"></span>'._k_("Facebook'ta Paylaş").'</li>';
	$output .= '<li class="share-twitter keremiya-share noselect" data-type="tw"><span class="icon-twitter-bird"></span>'._k_("Twitter'da Paylaş").'</li>';
	$output .= '<li class="share-gplus keremiya-share noselect" data-type="gp"><span class="icon-gplus"></span>'._k_("Google+'da Paylaş").'</li>';
	$output .= '</ul>';
	$output .= '</div>';

	if (!$echo) 
		return $output;
	
	echo $output;
}

function keremiya_report() {
	?>
	<button class="button report-button trigger" type="button">
		<span class="icon-flag iconfix"><?php _k('Bildir'); ?></span>
	</button>

	<div class="popup report-popup">
		<?php keremiya_report_form(); ?>
	</div>
	<?php
}

function keremiya_report_form() {
	?>
	<form id="keremiya-report" class="report-form">
		<input id="report_email" class="report-form-email" name="report_email" value="" aria-required="true" placeholder="<?php _k('E-Posta adresiniz'); ?>" type="text">
		<p><?php _k('E-postanız sadece moderatörler tarafından görünür.'); ?></p>
		<textarea id="report_excerpt" class="report-form-excerpt" name="report_excerpt" value="" aria-required="true" placeholder="<?php _k('Sorun nedir? Lütfen açıklayın..'); ?>"></textarea>
		<input type="hidden" name="keremiya_action" value="report">
		<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("report_ajax-nonce"); ?>">

		<button id="submit" class="button report-submit-button trigger" type="submit">
			<span class="icon-right-open iconfix"><?php _k('Gönder'); ?></span>
		</button>
	</form>
	<?php
}

function keremiya_wide() {
	echo '<button id="wide" class="button wide-button" type="button"><span class="icon-resize-horizontal iconfix">'._k_('Geniş').'</span></button>';
}



?>