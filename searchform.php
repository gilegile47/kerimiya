<div class="hide-overflow">
<form id="search-form" method="get" action="<?php echo home_url( '/' ); ?>">
	<button type="submit" id="search-button"><span class="icon-search-1"></span></button>
	<div id="search-box">
		<input type="text" value="<?php _k('Ara..'); ?>" id="s" name="s" onfocus="if (this.value == '<?php _k('Ara..'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _k('Ara..'); ?>';}" autocomplete="off" />
	</div>
</form>
</div>
<?php if(keremiya_get_option('live_search')): ?>
<div id="live-search"></div>
<?php endif; ?>