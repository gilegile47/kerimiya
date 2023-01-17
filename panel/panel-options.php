<?php
/*-----------------------------------------------------------------------------------*/
# Add Options
/*-----------------------------------------------------------------------------------*/
function keremiya_options($value){
	global $options_fonts;
?>
	<div class="option-item" id="<?php echo $value['id'] ?>-item">
		<span class="label"><?php  echo $value['name']; ?></span>
	<?php
	switch ( $value['type'] ) {
	
		case 'text': ?>
			<input  name="keremiya_options[<?php echo $value['id']; ?>]" id="<?php  echo $value['id']; ?>" type="text" value="<?php echo keremiya_get_option( $value['id'] ); ?>" />
			<?php if( isset( $value['extra_text'] ) ) : ?><span class="extra-text"><?php echo $value['extra_text'] ?></span><?php endif; ?>		
		<?php 
		break;

		case 'arrayText':  $currentValue = keremiya_get_option( $value['id'] );?>
			<input  name="keremiya_options[<?php echo $value['id']; ?>][<?php echo $value['key']; ?>]" id="<?php  echo $value['id']; ?>[<?php echo $value['key']; ?>]" type="text" value="<?php echo $currentValue[$value['key']] ?>" />	
		<?php 
		break;

		case 'short-text': ?>
			<input style="width:50px" name="keremiya_options[<?php echo $value['id']; ?>]" id="<?php  echo $value['id']; ?>" type="text" value="<?php echo keremiya_get_option( $value['id'] ); ?>" />
		<?php 
		break;

		case 'number': ?>
			<input style="width:50px" type="number" min="<?php echo $value['options']['min']; ?>" max="<?php echo $value['options']['max']; ?>" name="keremiya_options[<?php echo $value['id']; ?>]" id="<?php  echo $value['id']; ?>" type="text" value="<?php echo keremiya_get_option( $value['id'] ); ?>" />
		<?php 
		break;
		
		case 'checkbox':
			if(keremiya_get_option($value['id'])){$checked = "checked=\"checked\"";  } else{$checked = "";} ?>
				<input class="on-of" type="checkbox" name="keremiya_options[<?php echo $value['id'] ?>]" id="<?php echo $value['id'] ?>" value="true" <?php echo $checked; ?> />			
		<?php	
		break;


		case 'radio':
		?>
			<div style="float:left; width: 295px;">
				<?php foreach ($value['options'] as $key => $option) { ?>
				<label style="display:block; margin-bottom:8px;"><input name="keremiya_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="radio" value="<?php echo $key ?>" <?php if ( keremiya_get_option( $value['id'] ) == $key) { echo ' checked="checked"' ; } ?>> <?php echo $option; ?></label>
				<?php } ?>
			</div>
		<?php
		break;
		
		case 'select':
		?>
			<select name="keremiya_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>">
				<?php foreach ($value['options'] as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( keremiya_get_option( $value['id'] ) == $key) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
		<?php
		break;

		case 'multiple':
		?>
		<select multiple="multiple" name="keremiya_options[<?php echo $value['id']; ?>][]" id="<?php echo $value['id']; ?>">
				<?php foreach ($value['options'] as $key => $option) { ?>
					<option value="<?php echo $key ?>" <?php if ( @in_array( $key , keremiya_get_option( $value['id'] ) ) ) { echo ' selected="selected"' ; } ?>><?php echo _kp_($option); ?></option>
				<?php } ?>
		</select>
		<?php
		break;

		case 'textarea':
		?>
			<textarea style="direction:ltr; text-align:left;" name="keremiya_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="textarea" cols="100%" rows="3" tabindex="4"><?php echo keremiya_get_option( $value['id'] );  ?></textarea>
		<?php
		break;

		case 'upload':
		?>
				<input id="<?php echo $value['id']; ?>" class="img-path" type="text" size="56" style="direction:ltr; text-laign:left" name="keremiya_options[<?php echo $value['id']; ?>]" value="<?php echo keremiya_get_option($value['id']); ?>" />
				<input id="upload_<?php echo $value['id']; ?>_button" type="button" class="small_button" value="<?php _kp('Yükle'); ?>" />
					
				<div id="<?php echo $value['id']; ?>-preview" class="img-preview" <?php if(!keremiya_get_option( $value['id'] )) echo 'style="display:none;"' ?>>
					<img src="<?php if(keremiya_get_option( $value['id'] )) echo keremiya_get_option( $value['id'] ); else echo get_template_directory_uri().'/panel/images/spacer.png'; ?>" alt="" />
					<a class="del-img" title="<?php _kp("Sil"); ?>"></a>
				</div>
		<?php
		break;

		case 'background':
			$current_value = keremiya_get_option($value['id']);
		?>
				<div style="display:inline-block">
				<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo $current_value['color'] ; ?>"></div></div>
				<input style="width:80px; margin-right:5px;"  name="keremiya_options[<?php echo $value['id']; ?>][color]" id="<?php  echo $value['id']; ?>color" type="text" value="<?php echo $current_value['color'] ; ?>" />
				</div>

				<?php if($value[gradient]): ?>
				<div style="display:inline-block">
				<div id="<?php echo $value['id']; ?>gradientcolorSelector" class="color-pic"><div style="background-color:<?php echo $current_value['color'] ; ?>"></div></div>
				<input style="width:80px; margin-right:5px;"  name="keremiya_options[<?php echo $value['id']; ?>][gradient_color]" id="<?php  echo $value['id']; ?>gradientcolor" type="text" value="<?php echo $current_value['gradient_color'] ; ?>" />
				</div>
				<?php endif; ?>

				<?php if($value[border]): ?>
				<div style="margin-top:15px; clear:both; border-top: 1px solid rgba(221, 221, 221, 0.42);padding-top: 10px;">
					<span class="label"><?php _kp('Kenar Rengi'); ?></span>
					<div id="<?php echo $value['id']; ?>bordercolorSelector" class="color-pic"><div style="background-color:<?php echo $current_value['border_color'] ; ?>"></div></div>
					<input style="width:80px; margin-right:5px;"  name="keremiya_options[<?php echo $value['id']; ?>][border_color]" id="<?php  echo $value['id']; ?>bordercolor" type="text" value="<?php echo $current_value['border_color'] ; ?>" />
				</div>
				<?php endif; ?>

				<div style="margin-top:10px;padding-top:10px;border-top:1px solid #f0f0f0;">
				<span class="label"><?php _kp('Arka Plan Resmi'); ?></span>
				
				<input id="<?php echo $value['id']; ?>-img" class="img-path" type="text" size="56" style="direction:ltr; text-align:left" name="keremiya_options[<?php echo $value['id']; ?>][img]" value="<?php echo $current_value['img']; ?>" />
				<input id="upload_<?php echo $value['id']; ?>_button" type="button" class="small_button" value="<?php _kp('Yükle'); ?>" />
				
				<div style="margin-top:15px; clear:both;">

					<select name="keremiya_options[<?php echo $value['id']; ?>][repeat]" id="<?php echo $value['id']; ?>[repeat]" style="width:132px;">
						<option value="" <?php if ( !$current_value['repeat'] ) { echo ' selected="selected"' ; } ?>><?php _kp('Repeat'); ?></option>
						<option value="repeat" <?php if ( $current_value['repeat']  == 'repeat' ) { echo ' selected="selected"' ; } ?>>repeat</option>
						<option value="no-repeat" <?php if ( $current_value['repeat']  == 'no-repeat') { echo ' selected="selected"' ; } ?>>no-repeat</option>
						<option value="repeat-x" <?php if ( $current_value['repeat'] == 'repeat-x') { echo ' selected="selected"' ; } ?>>repeat-x</option>
						<option value="repeat-y" <?php if ( $current_value['repeat'] == 'repeat-y') { echo ' selected="selected"' ; } ?>>repeat-y</option>
					</select>

					<select name="keremiya_options[<?php echo $value['id']; ?>][attachment]" id="<?php echo $value['id']; ?>[attachment]" style="width:132px;">
						<option value="" <?php if ( !$current_value['attachment'] ) { echo ' selected="selected"' ; } ?>><?php _kp('Attachment'); ?></option>
						<option value="fixed" <?php if ( $current_value['attachment']  == 'fixed' ) { echo ' selected="selected"' ; } ?>>Fixed</option>
						<option value="scroll" <?php if ( $current_value['attachment']  == 'scroll') { echo ' selected="selected"' ; } ?>>scroll</option>
					</select>
					
					<select name="keremiya_options[<?php echo $value['id']; ?>][hor]" id="<?php echo $value['id']; ?>[hor]" style="width:134px;">
						<option value="" <?php if ( !$current_value['hor'] ) { echo ' selected="selected"' ; } ?>><?php _kp('Position'); ?></option>
						<option value="left" <?php if ( $current_value['hor']  == 'left' ) { echo ' selected="selected"' ; } ?>>Left</option>
						<option value="right" <?php if ( $current_value['hor']  == 'right') { echo ' selected="selected"' ; } ?>>Right</option>
						<option value="center" <?php if ( $current_value['hor'] == 'center') { echo ' selected="selected"' ; } ?>>Center</option>
					</select>
					
					<select name="keremiya_options[<?php echo $value['id']; ?>][ver]" id="<?php echo $value['id']; ?>[ver]" style="width:134px;">
						<option value="" <?php if ( !$current_value['ver'] ) { echo ' selected="selected"' ; } ?>><?php _kp('Position'); ?></option>
						<option value="top" <?php if ( $current_value['ver']  == 'top' ) { echo ' selected="selected"' ; } ?>>Top</option>
						<option value="center" <?php if ( $current_value['ver'] == 'center') { echo ' selected="selected"' ; } ?>>Center</option>
						<option value="bottom" <?php if ( $current_value['ver']  == 'bottom') { echo ' selected="selected"' ; } ?>>Bottom</option>

					</select>
				</div>

				<div id="<?php echo $value['id']; ?>-preview" class="img-preview" <?php if( !$current_value['img']  ) echo 'style="display:none;"' ?>>
					<img src="<?php if( $current_value['img'] ) echo $current_value['img'] ; else echo get_template_directory_uri().'/panel/images/spacer.png'; ?>" alt="" />
					<a class="del-img" title="Delete"></a>
				</div>
				</div>
					
				<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '<?php echo $current_value['color'] ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>color').val('#'+hex);
					}
				});
				<?php if($value[border]): ?>
				jQuery('#<?php echo $value['id']; ?>bordercolorSelector').ColorPicker({
					color: '<?php echo $current_value['border_color'] ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>bordercolorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>bordercolor').val('#'+hex);
					}
				});
				<?php endif; ?>
				<?php if($value[gradient]): ?>
				jQuery('#<?php echo $value['id']; ?>gradientcolorSelector').ColorPicker({
					color: '<?php echo $current_value['gradient_color'] ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>gradientcolorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>gradientcolor').val('#'+hex);
					}
				});
				<?php endif; ?>
				</script>
		<?php
		break;
		
		
		case 'color':
		?>
			<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo keremiya_get_option($value['id']) ; ?>"></div></div>
			<input style="width:80px; margin-right:5px;"  name="keremiya_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="text" value="<?php echo keremiya_get_option($value['id']) ; ?>" />
							
			<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '<?php echo keremiya_get_option($value['id']) ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>').val('#'+hex);
					}
				});
				</script>
		<?php
		break;

		
		case 'typography':
			$current_value = keremiya_get_option($value['id']);
		?>

					<select name="keremiya_options[<?php echo $value['id']; ?>][font]" id="<?php echo $value['id']; ?>[font]">
					<?php foreach( keremiya_get_googlefonts() as $font => $font_name ){ ?>
						<option value="<?php echo $font ?>" <?php if ( $current_value['font']  == $font ) { echo ' selected="selected"' ; } ?>><?php echo $font_name ?></option>
					<?php } ?>
					</select>

		<?php
		break;
	
	}
	
	?>
	
	<?php if( isset( $value['help'] ) ) : ?>
		<a class="keremiya-help tooltip"  title="<?php echo $value['help'] ?>"></a>
		<?php endif; ?>
	</div>
			
<?php
}
?>