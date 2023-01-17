<?php
//add extra fields to category edit form hook
add_action ( 'edit_category_form_fields', 'keremiya_extra_category_fields');
//add extra fields to category edit form callback function
function keremiya_extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
?>
<tr class="form-field">
<th scope="row" valign="top"><label for="seo_title"><?php _kp("Başlık"); ?></label></th>
<td>
<input type="text" name="Cat_meta[seo_title]" id="Cat_meta[seo_title]" size="25" style="width:60%;" value="<?php echo $cat_meta['seo_title'] ? $cat_meta['seo_title'] : ''; ?>"><br />
            <span class="description"><?php _kp("Arama motorlarında kategori başlığı."); ?></span>
        </td>
</tr>
<tr class="form-field">
<th scope="row" valign="top"><label for="seo_description"><?php _kp("Açıklama"); ?></label></th>
<td>
            <textarea name="Cat_meta[seo_description]" id="Cat_meta[seo_description]" style="width:60%;"><?php echo $cat_meta['seo_description'] ? $cat_meta['seo_description'] : ''; ?></textarea><br />
            <span class="description"><?php _kp("Arama motorlarında açıklama."); ?></span>
        </td>
</tr>
<tr class="form-field">
<th scope="row" valign="top"><label for="seo_keywords"><?php _kp("Anahtar Kelimeler"); ?></label></th>
<td>
<input type="text" name="Cat_meta[seo_keywords]" id="Cat_meta[seo_keywords]" size="25" style="width:60%;" value="<?php echo $cat_meta['seo_keywords'] ? $cat_meta['seo_keywords'] : ''; ?>"><br />
            <span class="description"><?php _kp("(,) Virgün ile ayırın."); ?></span>
        </td>
</tr>
<?php
}

// save extra category extra fields hook
add_action ( 'edited_category', 'keremiya_save_extra_category_fileds');
   // save extra category extra fields callback function
function keremiya_save_extra_category_fileds( $term_id ) {
    if ( isset( $_POST['Cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['Cat_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['Cat_meta'][$key])){
                $cat_meta[$key] = $_POST['Cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }
}

?>