<?php


function panel_options() { 

	$categories_obj = get_categories('hide_empty=0');
	$categories = array(
		'' => _kp_('Kategori Seç'),
	);
	foreach ($categories_obj as $pn_cat) {
		$categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
	}
	
$save='
	<div class="keremiyapanel-submit">
		<input type="hidden" name="action" value="test_theme_data_save" />
        <input type="hidden" name="security" value="'. wp_create_nonce("keremiya-data").'" />
		<input name="save" class="keremiyapanel-save" type="submit" value="'._kp_("Ayarları Kaydet").'" />    
	</div>'; 
?>
		
<div class="clear"></div>
<div id="save-alert"></div>

<div class="keremiya-panel">
	<div class="keremiya-panel-header-bg"></div>
	<div class="keremiya-panel-tabs">
		<div onclick="window.location.href='http://www.keremiya.com'" class="logo"></div>
		<ul>
			<li class="tabs general tab1"><a id="asd" href="#tab1"><span class="dashicons-before"></span><?php _kp('Genel Ayarlar'); ?></a></li>
			<li class="tabs homepage tab2"><a href="#tab2"><span class="dashicons-before"></span><?php _kp('Anasayfa'); ?></a></li>
			<li class="tabs header tab9"><a href="#tab9"><span class="dashicons-before"></span><?php _kp('Üst Kısım'); ?></a></li>
			<li class="tabs article tab6"><a href="#tab6"><span class="dashicons-before"></span><?php _kp('İçerik'); ?></a></li>
			<li class="tabs archives tab12"><a href="#tab12"><span class="dashicons-before"></span><?php _kp('Arşiv'); ?></a></li>
			<li class="tabs footer tab7"><a href="#tab7"><span class="dashicons-before"></span><?php _kp('Alt Kısım'); ?></a></li>
			<li class="tabs player tab11"><a href="#tab11"><span class="dashicons-before"></span><?php _kp('Player'); ?></a></li>
			<li class="tabs banners tab8"><a href="#tab8"><span class="dashicons-before"></span><?php _kp('Reklam'); ?></a></li>
			<li class="tabs skins tab3"><a href="#tab3"><span class="dashicons-before"></span><?php _kp('Görünüm'); ?></a></li>
			<li class="tabs styling tab13"><a href="#tab13"><span class="dashicons-before"></span><?php _kp('Styling'); ?></a></li>
			<li class="tabs seo tab4"><a href="#tab4"><span class="dashicons-before"></span><?php _kp('SEO'); ?></a></li>
			<li class="tabs bot tab14"><a href="#tab14"><span class="dashicons-before"></span><?php _kp('Bot'); ?></a></li>
			<li class="tabs advanced tab10"><a href="#tab10"><span class="dashicons-before"></span><?php _kp('Gelişmiş'); ?></a></li>
		</ul>
		<div class="clear"></div>
	</div> <!-- .keremiya-panel-tabs -->
	
	
	<div class="keremiya-panel-content">
		<div class="keremiya-panel-content-center">
	<form action="/" name="keremiya_form" id="keremiya_form">
	<?php echo $save ?>
	
		<div id="tab1" class="tabs-wrap">
			<h2><?php _kp('Genel Ayarlar'); ?></h2> 
			
			<div class="keremiyapanel-item">
				<h3><?php _kp('Lisans Bilgileri'); ?> <span style="float: right;opacity: .7;">Keremiya v<?php echo theme_ver; ?></span></h3>
				<?php
					iframe_licence();
				?>
			</div>

			<div class="keremiyapanel-item">

				<h3><?php _kp('İletişim Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("E-posta"),
								"id" => "email",
								"type" => "text",
								"help" => _kp_("İletişim sayfasından mesaj gönderilebilmesi için mail() fonksiyonunuz açık olmalıdır. Hosting sağlayıcınız ile iletişime geçerek öğrenebilirsiniz.")));
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Favicon'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Özel Favicon"),
								"id" => "favicon",
								"type" => "upload"));
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Varsayılan Gravatar'); ?></h3>
				
				<?php
					keremiya_options(
						array(	"name" => _kp_("Özel Gravatar"),
								"id" => "gravatar",
								"type" => "upload"));
				?>
			</div>	

			<div class="keremiyapanel-item">
				<h3><?php _kp('Pagenavi Ayarları'); ?></h3>
				
				<?php
					keremiya_options(
						array(	"name" => _kp_("Görünümler"),
								"id" => "pagenavi",
								"type" => "radio",
								"options" => array(
									'pagination' => _kp_('Sayfalamalı'),
									'loadnavi' => _kp_('Daha Fazla Yükle'))));
				?>
			</div>
		</div>
		
		<div id="tab14" class="tabs-wrap">
			<h2><?php _kp('Bot Ayarları'); ?></h2> 
			
			<div class="keremiyapanel-item">

				<h3><?php _kp('IMDB Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("IMDB Botu"),
								"id" => "imdb_importer",
								"type" => "checkbox"));
					keremiya_options(
						array(	"name" => _kp_("Poster Çekici"),
								"id" => "auto_thumbnail",
								"type" => "checkbox"));
				?>
				<div class="keremiyapanel-item">
					<h3><?php _kp('Kategori Ayarları'); ?></h3>
					<?php
						keremiya_options(
							array(	"name" => _kp_("Kategori Seçici"),
									"id" => "auto_category",
									"type" => "checkbox"));

						$genres = imdb_genres();
						foreach ($genres as $key => $name) {
							keremiya_options(
								array(	"name" => $name,
										"id" => "imdb_{$name}",
										"type" => "select",
										"options" => $categories));
						}
					?>
				</div>
			</div>
		</div>

		<div id="tab2" class="tabs-wrap">
			<h2><?php _kp('Anasayfa Ayarları'); ?></h2> 
		
			<div class="keremiyapanel-item">
				<h3><?php _kp('Slider'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Slider"),
								"id" => "on_slider",
								"type" => "checkbox"));
					echo '<div id="slider-items">';
					keremiya_options(
						array(	"name" => _kp_("Kategori"),
								"id" => "slider_cat",
								"type" => "select",
								"options" => $categories ));
					keremiya_options(
						array(	"name" => _kp_("Gösterilecek Film Sayısı"),
								"id" => "slider_number",
								"type" => "number",
								"options" => array( 
									"min"=> '1',
									"max"=> '99',
								)));
					keremiya_options(
						array( 	"name" => _kp_("Görünüm"),
								"id" => "slider_style",
								"type" => "radio",
								"options" => array( "1"=> sprintf(_kp_("Görünüm %s"), '#1') ,
													"2"=> sprintf(_kp_("Görünüm %s"), '#2') )));
					echo '</div>';
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Anasayfa Görünümü'); ?></h3>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Anasayfa görünümü"),
								"id" => "on_home",
								"type" => "radio",
								"options" => array( "latest"=> _kp_("En Yeni Filmler") ,
													"builder"=> _kp_("Home Builder") )));
				?>
			</div>	
				
		<div id="Home_Builder">
					<script type="text/javascript">
					jQuery(document).ready(function() {
						var htm1l = jQuery('#cats_ids').html();
						
						jQuery("#add-cat").click(function() {
							jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head"> <?php _kp("Film Kutusu"); ?> <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div style="display:block" class="widget-content"><label><span><?php _kp("Kategori"); ?> : </span><select name="keremiya_home_cats['+ nextCell +'][id]" id="keremiya_home_cats['+ nextCell +'][id]">'+htm1l+'</select></label><label for="keremiya_home_cats['+ nextCell +'][title]"><span><?php _kp("Başlık"); ?> :</span><input id="keremiya_home_cats['+ nextCell +'][title]" name="keremiya_home_cats['+ nextCell +'][title]" value="" type="text" /></label><label for="keremiya_home_cats['+ nextCell +'][number]"><span><?php _kp("Gösterilecek Film Sayısı"); ?> :</span><input style="width:50px;" id="keremiya_home_cats['+ nextCell +'][number]" name="keremiya_home_cats['+ nextCell +'][number]" value="4" type="text" /></label><label for="keremiya_home_cats['+ nextCell +'][display]"><span><?php _kp("Görünüm"); ?>:</span><select id="keremiya_home_cats['+ nextCell +'][display]" name="keremiya_home_cats['+ nextCell +'][display]"><option value="film"><?php _kp("Varsayılan Stil"); ?></option><option value="series"><?php _kp("Dizi Stili"); ?></option></select></label><label for="keremiya_home_cats['+ nextCell +'][query]"><span><?php _kp("Query Sıralaması"); ?> :</span><select id="keremiya_home_cats['+ nextCell +'][query]" name="keremiya_home_cats['+ nextCell +'][query]"><option value="date" selected="selected"><?php _kp("Tarih"); ?></option><option value="views"><?php _kp("İzlenme"); ?></option></select></label><input id="keremiya_home_cats['+ nextCell +'][type]" name="keremiya_home_cats['+ nextCell +'][type]" value="n" type="hidden" /><a class="del-cat"><?php _kp("Sil"); ?></a></div></li>');
							jQuery('#listItem_'+ nextCell).hide().fadeIn();
							nextCell ++ ;
						});
						/*jQuery("#add-slider").click(function() {
							jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head"> <?php _kp("Slider"); ?> <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div class="widget-content" style="display:block"><label><span><?php _kp("Kategori"); ?> : </span><select name="keremiya_home_cats['+ nextCell +'][id]" id="keremiya_home_cats['+ nextCell +'][id]">'+htm1l+'</select></label><label for="keremiya_home_cats['+ nextCell +'][title]"><span><?php _kp("Başlık"); ?> :</span><input id="keremiya_home_cats['+ nextCell +'][title]" name="keremiya_home_cats['+ nextCell +'][title]" value="<?php _kp("Slider"); ?>" type="text" /></label><label for="keremiya_home_cats['+ nextCell +'][number]"><span><?php _kp("Gösterilecek Film Sayısı"); ?> :</span><input style="width:50px;" id="keremiya_home_cats['+ nextCell +'][number]" name="keremiya_home_cats['+ nextCell +'][number]" value="12" type="text" /></label><label for="keremiya_home_cats['+ nextCell +'][query]"><span><?php _kp("Query Sıralaması"); ?> :</span><select id="keremiya_home_cats['+ nextCell +'][query]" name="keremiya_home_cats['+ nextCell +'][query]"><option value="date" selected="selected"><?php _kp("Tarih"); ?></option><option value="views"><?php _kp("İzlenme"); ?></option></select></label><input id="keremiya_home_cats['+ nextCell +'][type]" name="keremiya_home_cats['+ nextCell +'][type]" value="s" type="hidden" /><a class="del-cat"><?php _kp("Sil"); ?></a></div></li>');
							jQuery('#listItem_'+ nextCell).hide().fadeIn();
							nextCell ++ ;
						});*/
						jQuery("#add-recent").click(function() {
							jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head"> <?php _kp("Son Filmler"); ?>  <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div style="display:block" class="widget-content"><label><span style="float:left;"><?php _kp("Bu Kategorileri hariç tut"); ?> : </span><select multiple="multiple" name="keremiya_home_cats['+ nextCell +'][exclude][]" id="keremiya_home_cats['+ nextCell +'][exclude][]">'+htm1l+'</select></label><label for="keremiya_home_cats['+ nextCell +'][title]"><span><?php _kp("Başlık"); ?> :</span><input id="keremiya_home_cats['+ nextCell +'][title]" name="keremiya_home_cats['+ nextCell +'][title]" value="<?php _kp("Son Filmler"); ?>" type="text" /></label><label for="keremiya_home_cats['+ nextCell +'][number]"><span><?php _kp("Gösterilecek Film Sayısı"); ?> :</span><input style="width:50px;" id="keremiya_home_cats['+ nextCell +'][number]" name="keremiya_home_cats['+ nextCell +'][number]" value="8" type="text" /></label><label for="keremiya_home_cats[<?php echo $i ?>][display]"><span><?php _kp("Görünüm"); ?>:</span><select id="keremiya_home_cats['+ nextCell +'][display]" name="keremiya_home_cats['+ nextCell +'][display]"><option value="film"><?php _kp("Varsayılan Stil"); ?></option><option value="series"><?php _kp("Dizi Stili"); ?></option></select></label><label for="keremiya_home_cats['+ nextCell +'][pagenavi]"><span>Pagenavi :</span><select id="keremiya_home_cats['+ nextCell +'][pagenavi]" name="keremiya_home_cats['+ nextCell +'][pagenavi]"><option value="on"><?php _kp("Açık");?></option><option value="off"><?php _kp("Kapalı");?></option></select></label><input id="keremiya_home_cats['+ nextCell +'][type]" name="keremiya_home_cats['+ nextCell +'][type]" value="recent" type="hidden" /><a class="del-cat"><?php _kp("Sil"); ?></a></div></li>');
							jQuery('#listItem_'+ nextCell).hide().fadeIn();
							nextCell ++ ;
						});
						jQuery("#add-popular").click(function() {
							jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head"> <?php _kp("Popüler Filmler"); ?>  <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div style="display:block" class="widget-content"><label><span style="float:left;"><?php _kp("Kategori"); ?> : </span><select multiple="multiple" name="keremiya_home_cats['+ nextCell +'][include][]" id="keremiya_home_cats['+ nextCell +'][include][]">'+htm1l+'</select></label><label for="keremiya_home_cats['+ nextCell +'][title]"><span><?php _kp("Başlık"); ?> :</span><input id="keremiya_home_cats['+ nextCell +'][title]" name="keremiya_home_cats['+ nextCell +'][title]" value="" type="text" /></label><label for="keremiya_home_cats['+ nextCell +'][number]"><span><?php _kp("Gösterilecek Film Sayısı"); ?> :</span><input style="width:50px;" id="keremiya_home_cats['+ nextCell +'][number]" name="keremiya_home_cats['+ nextCell +'][number]" value="8" type="text" /></label><label for="keremiya_home_cats[<?php echo $i ?>][display]"><span><?php _kp("Görünüm"); ?>:</span><select id="keremiya_home_cats['+ nextCell +'][display]" name="keremiya_home_cats['+ nextCell +'][display]"><option value="film"><?php _kp("Varsayılan Stil"); ?></option><option value="series"><?php _kp("Dizi Stili"); ?></option></select></label><label for="keremiya_home_cats['+ nextCell +'][query]"><span><?php _kp("Query Sıralaması"); ?> :</span><select id="keremiya_home_cats['+ nextCell +'][query]" name="keremiya_home_cats['+ nextCell +'][query]"><option value="views" selected="selected"><?php _kp("İzlenme"); ?></option><option value="comment"><?php _kp("Yorum"); ?></option><option value="imdb"><?php _kp("IMDB Puanı"); ?></option></select></label><input id="keremiya_home_cats['+ nextCell +'][type]" name="keremiya_home_cats['+ nextCell +'][type]" value="popular" type="hidden" /><a class="del-cat"><?php _kp("Sil"); ?></a></div></li>');
							jQuery('#listItem_'+ nextCell).hide().fadeIn();
							nextCell ++ ;
						});

						jQuery("#add-ads").click(function() {
							jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head"> <?php _kp("Reklam"); ?> <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div class="widget-content" style="display:block"><textarea name="keremiya_home_cats['+ nextCell +'][text]" id="keremiya_home_cats['+ nextCell +'][text]"></textarea><input id="keremiya_home_cats['+ nextCell +'][type]" name="keremiya_home_cats['+ nextCell +'][type]" value="ads" type="hidden" /><a class="del-cat"><?php _kp("Sil"); ?></a></div></li>');
							jQuery('#listItem_'+ nextCell).hide().fadeIn();
							nextCell ++ ;
						});

						jQuery("#add-cats").click(function() {
							jQuery('#cat_sortable').append('<li id="listItem_'+ nextCell +'" class="ui-state-default"><div class="widget-head"> <?php _kp("Kategoriler"); ?> <a style="display:none" class="toggle-open">+</a><a style="display:block" class="toggle-close">-</a></div><div class="widget-content" style="display:block"><label><span style="float:left;"><?php _kp("Bu Kategorileri hariç tut"); ?> : </span><select multiple="multiple" name="keremiya_home_cats['+ nextCell +'][exclude][]" id="keremiya_home_cats['+ nextCell +'][exclude][]">'+htm1l+'</select></label><input id="keremiya_home_cats['+ nextCell +'][type]" name="keremiya_home_cats['+ nextCell +'][type]" value="cats" type="hidden" /><a class="del-cat"><?php _kp("Sil"); ?></a></div></li>');
							jQuery('#listItem_'+ nextCell).hide().fadeIn();
							nextCell ++ ;
						});
					});
					</script>
				
			<div class="keremiyapanel-item"  style=" overflow: visible; ">
				<h3><?php _kp('Home Builder'); ?></h3>
				<div class="option-item">

					<select style="display:none" id="cats_ids">
						<?php foreach ($categories as $key => $option) { ?>
						<option value="<?php echo $key ?>"><?php echo $option; ?></option>
						<?php } ?>
					</select>
					
					<div style="clear:both"></div>
					<div class="home-builder-buttons">
						<a id="add-cat"><?php _kp('Film Kutusu'); ?></a>
						<a id="add-recent"><?php _kp('Son Filmler'); ?></a>
						<a id="add-popular"><?php _kp('Popüler'); ?></a>
						<a id="add-cats"><?php _kp('Kategoriler'); ?></a>
						<!--<a id="add-slider"><?php _kp('Slider'); ?></a>-->
						<a id="add-ads"><?php _kp('Reklam'); ?></a>
					</div>
					
					<div class="clear"></div>
					
					<ul id="cat_sortable">
						<?php
							$cats = get_option( 'keremiya_home_cats' ) ;
							if($cats){
							$i=0;
								foreach ($cats as $cat) { 
									$i++;
									?>
									<li id="listItem_<?php echo $i ?>" class="ui-state-default">
			
								<?php 
									if( $cat['type'] == 'n' ) :	?>
										<div class="widget-head"> <?php _kp('Film Kutusu'); ?> : <?php echo get_the_category_by_ID($cat['id']) ?>
											<a class="toggle-open">+</a>
											<a class="toggle-close">-</a>
										</div>
										<div class="widget-content">
											<label><span><?php _kp('Kategori'); ?> : </span><select name="keremiya_home_cats[<?php echo $i ?>][id]" id="keremiya_home_cats[<?php echo $i ?>][id]">
												<?php foreach ($categories as $key => $option) { ?>
												<option value="<?php echo $key ?>" <?php if ( $cat['id']  == $key) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
												<?php } ?>
											</select></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][title]"><span><?php _kp('Başlık'); ?> :</span><input id="keremiya_home_cats[<?php echo $i ?>][title]" name="keremiya_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" /></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][number]"><span><?php _kp('Gösterilecek Film Sayısı'); ?> :</span><input style="width:50px;" type="number" id="keremiya_home_cats[<?php echo $i ?>][number]" name="keremiya_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" /></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][display]"><span><?php _kp('Görünüm'); ?> :</span>
												<select id="keremiya_home_cats[<?php echo $i ?>][display]" name="keremiya_home_cats[<?php echo $i ?>][display]">
													<option value="film" <?php if ( $cat['display'] == 'default') { echo ' selected="selected"' ; } ?>><?php _kp('Varsayılan Stil'); ?></option>
													<option value="series" <?php if ( $cat['display'] == 'blog') { echo ' selected="selected"' ; } ?>><?php _kp('Dizi Stili'); ?></option>
												</select>
											</label>

											<label for="keremiya_home_cats[<?php echo $i ?>][query]"><span><?php _kp('Query Sıralaması'); ?> :</span>
												<select id="keremiya_home_cats[<?php echo $i ?>][query]" name="keremiya_home_cats[<?php echo $i ?>][query]">
													<option value="date" <?php if ( $cat['query'] == 'date') { echo ' selected="selected"' ; } ?>><?php _kp('Tarih'); ?></option>
													<option value="views" <?php if ( $cat['query'] == 'views') { echo ' selected="selected"' ; } ?>><?php _kp('İzlenme'); ?></option>
												</select>
											</label>

								
								<?php 
									elseif( $cat['type'] == 'recent' ) :	?>
										<div class="widget-head"> <?php _kp('Son Filmler'); ?> 
											<a class="toggle-open">+</a>
											<a class="toggle-close">-</a>
										</div>
										<div class="widget-content">
											<label><span style="float:left;"><?php _kp('Bu Kategorileri hariç tut'); ?> : </span><select multiple="multiple" name="keremiya_home_cats[<?php echo $i ?>][exclude][]" id="keremiya_home_cats[<?php echo $i ?>][exclude][]">
												<?php foreach ($categories as $key => $option) { ?>
												<option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['exclude'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
												<?php } ?>
											</select></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][title]"><span><?php _kp('Başlık'); ?> :</span><input id="keremiya_home_cats[<?php echo $i ?>][title]" name="keremiya_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" /></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][number]"><span><?php _kp('Gösterilecek Film Sayısı'); ?> :</span><input style="width:50px;" id="keremiya_home_cats[<?php echo $i ?>][number]" name="keremiya_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" /></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][display]"><span><?php _kp('Görünüm'); ?> :</span>
												<select id="keremiya_home_cats[<?php echo $i ?>][display]" name="keremiya_home_cats[<?php echo $i ?>][display]">
													<option value="film" <?php if ( $cat['display'] == 'default') { echo ' selected="selected"' ; } ?>><?php _kp('Varsayılan Stil'); ?></option>
													<option value="series" <?php if ( $cat['display'] == 'blog') { echo ' selected="selected"' ; } ?>><?php _kp('Varsayılan Stil'); ?></option>
												</select>
											</label>
											<label for="keremiya_home_cats[<?php echo $i ?>][pagenavi]"><span><?php _kp('Sayfalama'); ?> :</span>
												<select id="keremiya_home_cats[<?php echo $i ?>][pagenavi]" name="keremiya_home_cats[<?php echo $i ?>][pagenavi]">
													<option value="on" <?php if ( $cat['pagenavi'] == 'on') { echo ' selected="selected"' ; } ?>><?php _kp('Açık'); ?></option>
													<option value="off" <?php if ( $cat['pagenavi'] == 'off') { echo ' selected="selected"' ; } ?>><?php _kp('Kapalı'); ?></option>
												</select>
											</label>

									<?php 
									elseif( $cat['type'] == 'popular' ) :	?>
										<div class="widget-head"> <?php _kp('Popüler Filmler'); ?> 
											<a class="toggle-open">+</a>
											<a class="toggle-close">-</a>
										</div>
										<div class="widget-content">
											<label><span style="float:left;"><?php _kp('Kategori'); ?> : </span><select multiple="multiple" name="keremiya_home_cats[<?php echo $i ?>][include][]" id="keremiya_home_cats[<?php echo $i ?>][include][]">
												<?php foreach ($categories as $key => $option) { ?>
												<option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['include'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
												<?php } ?>
											</select></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][title]"><span><?php _kp('Başlık'); ?> :</span><input id="keremiya_home_cats[<?php echo $i ?>][title]" name="keremiya_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" /></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][number]"><span><?php _kp('Gösterilecek Film Sayısı'); ?> :</span><input style="width:50px;" id="keremiya_home_cats[<?php echo $i ?>][number]" name="keremiya_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" /></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][display]"><span><?php _kp('Görünüm'); ?> :</span>
												<select id="keremiya_home_cats[<?php echo $i ?>][display]" name="keremiya_home_cats[<?php echo $i ?>][display]">
													<option value="film" <?php if ( $cat['display'] == 'default') { echo ' selected="selected"' ; } ?>><?php _kp('Varsayılan Stil'); ?></option>
													<option value="series" <?php if ( $cat['display'] == 'blog') { echo ' selected="selected"' ; } ?>><?php _kp('Varsayılan Stil'); ?></option>
												</select>
											</label>
											<label for="keremiya_home_cats[<?php echo $i ?>][query]"><span><?php _kp('Query Sıralaması'); ?> :</span>
												<select id="keremiya_home_cats[<?php echo $i ?>][query]" name="keremiya_home_cats[<?php echo $i ?>][query]">
													<option value="views" <?php if ( $cat['query'] == 'views') { echo ' selected="selected"' ; } ?>><?php _kp('İzlenme'); ?></option>
													<option value="date" <?php if ( $cat['query'] == 'comment') { echo ' selected="selected"' ; } ?>><?php _kp('Yorum'); ?></option>
													<option value="imdb" <?php if ( $cat['query'] == 'imdb') { echo ' selected="selected"' ; } ?>><?php _kp('IMDB Puanı'); ?></option>
												</select>
											</label>

									<?php elseif( $cat['type'] == 's' ) : ?>
										<div class="widget-head scrolling-box"> <?php _kp('Slider'); ?> : <?php echo get_the_category_by_ID($cat['id']) ?>
											<a class="toggle-open">+</a>
											<a class="toggle-close">-</a>
										</div>
										<div class="widget-content">
											<label><span><?php _kp('Kategori'); ?> : </span><select name="keremiya_home_cats[<?php echo $i ?>][id]" id="keremiya_home_cats[<?php echo $i ?>][id]">
												<?php foreach ($categories as $key => $option) { ?>
												<option value="<?php echo $key ?>" <?php if ( $cat['id']  == $key) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
												<?php } ?>
											</select></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][title]"><span><?php _kp('Başlık'); ?> :</span><input id="keremiya_home_cats[<?php echo $i ?>][title]" name="keremiya_home_cats[<?php echo $i ?>][title]" value="<?php  echo $cat['title']  ?>" type="text" /></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][number]"><span><?php _kp('Gösterilecek Film Sayısı'); ?> :</span><input style="width:50px;" id="keremiya_home_cats[<?php echo $i ?>][number]" name="keremiya_home_cats[<?php echo $i ?>][number]" value="<?php  echo $cat['number']  ?>" type="text" /></label>
											<label for="keremiya_home_cats[<?php echo $i ?>][query]"><span><?php _kp('Query Sıralaması'); ?> :</span>
												<select id="keremiya_home_cats[<?php echo $i ?>][query]" name="keremiya_home_cats[<?php echo $i ?>][query]">
													<option value="date" <?php if ( $cat['query'] == 'date') { echo ' selected="selected"' ; } ?>><?php _kp('Tarih'); ?></option>
													<option value="views" <?php if ( $cat['query'] == 'views') { echo ' selected="selected"' ; } ?>><?php _kp('İzlenme'); ?></option>
												</select>
											</label>

											
									<?php elseif( $cat['type'] == 'ads' ) : ?>
										<div class="widget-head n-ads-box"> <?php _kp('Reklam'); ?>
											<a class="toggle-open">+</a>
											<a class="toggle-close">-</a>
										</div>
										<div class="widget-content">
											<textarea cols="36" rows="5" name="keremiya_home_cats[<?php echo $i ?>][text]" id="keremiya_home_cats[<?php echo $i ?>][text]"><?php echo stripslashes($cat['text']) ; ?></textarea>
										
									<?php 
									elseif( $cat['type'] == 'cats' ) :	?>
										<div class="widget-head"> <?php _kp('Kategoriler'); ?> 
											<a class="toggle-open">+</a>
											<a class="toggle-close">-</a>
										</div>
										<div class="widget-content">
											<label><span style="float:left;"><?php _kp('Bu Kategorileri hariç tut'); ?> : </span><select multiple="multiple" name="keremiya_home_cats[<?php echo $i ?>][exclude][]" id="keremiya_home_cats[<?php echo $i ?>][exclude][]">
												<?php foreach ($categories as $key => $option) { ?>
												<option value="<?php echo $key ?>" <?php if ( @in_array( $key , $cat['exclude'] ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
												<?php } ?>
											</select></label>
									<?php endif; ?>
									
									
											<input id="keremiya_home_cats[<?php echo $i ?>][type]" name="keremiya_home_cats[<?php echo $i ?>][type]" value="<?php  echo $cat['type']  ?>" type="hidden" />
											<a class="del-cat">Sil</a>
										
										</div>
									</li>
							<?php } 
							} else{?>
							<?php } ?>
					</ul>

					<script> var nextCell = <?php echo $i+1 ?> ;</script>
				</div>	
			</div>
			
		</div>

		<div id="Home_List">
			<div class="keremiyapanel-item">
				<h3><?php _kp('Anasayfa Liste Düzeni'); ?></h3>
				<div class="option-item">
					<?php
						$checked = 'checked="checked"';
						$keremiya_home_layout = keremiya_get_option('home_layout');
					?>
					<ul id="home-layout-options" class="keremiya-options">
						<li>
							<input id="keremiya_home_layout"  name="keremiya_options[home_layout]" type="radio" value="film" <?php if($keremiya_home_layout == 'film' || !$keremiya_home_layout) echo $checked; ?> />
							<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/2c.png" /></a>
						</li>
						<li>
							<input id="keremiya_home_layout"  name="keremiya_options[home_layout]" type="radio" value="film-v2" <?php if($keremiya_home_layout == 'film-v2') echo $checked; ?> />
							<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/3c.png" /></a>
						</li>
						<li>
							<input id="keremiya_home_layout"  name="keremiya_options[home_layout]" type="radio" value="series" <?php if($keremiya_home_layout == 'series') echo $checked; ?> />
							<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/1c.png" /></a>
						</li>
					</ul>
				</div>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Sütun Sayısı"),
								"id" => "columns",
								"type" => "number",
								"options" => array( 
									"min"=> '2',
									"max"=> '8',
								),
								"help" => _kp_('En fazla 8 adet sıralanabilir.'),
							)
						);
					keremiya_options(
						array(	"name" => _kp_("Gösterilecek Film Sayısı"),
								"id" => "movies_number",
								"type" => "number",
								"options" => array( 
									"min"=> '1',
									"max"=> '99',
								)));
					keremiya_options(
						array(	"name" => _kp_("Yüksek Resim Çözünürlüğü"),
								"id" => "full_size",
								"type" => "checkbox",
								));
				?>
				<div class="keremiyapanel-item">
					<h3><?php _kp('Günün Filmi'); ?></h3>
					<?php
					keremiya_options(
						array(	"name" => _kp_("Etkinleştir"),
								"id" => "today_movie_on",
								"type" => "checkbox",
								));
					keremiya_options(
						array( 	"name" => _kp_("Post ID"),
								"id" => "today_movie",
								"type" => "short-text",
								"help" => _kp_('Filmin ID numarası.'),
							)
						);
					?>
				</div>

				<div class="keremiyapanel-item">
					<h3><?php _kp('Haberler'); ?></h3>
					<?php
					keremiya_options(
						array(	"name" => _kp_("Etkinleştir"),
								"id" => "news_on",
								"type" => "checkbox",
								));
					keremiya_options(
						array( 	"name" => _kp_("Gösterilecek haber sayısı"),
								"id" => "home_news_number",
								"type" => "number",
							)
						);
					?>
				</div>

				<div class="keremiyapanel-item">
					<h3><?php _kp('Sidebar Ayarları'); ?></h3>
					
					<?php
						keremiya_options(
							array(	"name" => _kp_("Anasayfa Sidebarı"),
									"id" => "on_sidebar",
									"type" => "checkbox"));
					?>
				</div>
			</div>
		</div>

		</div> <!-- Homepage Settings -->
		
		
		<div id="tab3" class="tab_content tabs-wrap">
			<h2><?php _kp('Görünüm Ayarları'); ?></h2>
			<div class="keremiyapanel-item">
				<h3><?php _kp('Bir skin seçin'); ?></h3>
				<?php
					$checked = 'checked="checked"';
					$theme_color = keremiya_get_option('theme_skin');
				?>
				<ul id="theme-skins" class="keremiya-options">
				<?php
				$skins = array(
					'modern' => 'Modern',
					'boxed' => 'Boxed',
					'oval' => 'Oval',
					'full' => 'Full',
				);

				foreach ($skins as $skin => $title) {
					?>
					<li title="<?php echo $title; ?>">
						<input id="keremiya_theme_skin" name="keremiya_options[theme_skin]" type="radio" value="<?php echo $skin; ?>" <?php if($theme_color == $skin) echo $checked; ?> />
						<span class="checkbox-select"><?php echo $title; ?></span>
						<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/<?php echo $skin; ?>-skin.png" width="80px" height="80px" /></a>
					</li>
					<?php
				}
				?>
				</ul>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Tema Genişliği'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_('"Width" Oranı'),
								"id" => "theme_width",
								"type" => "short-text",
								"help" => _kp_('Genişlik oranı')));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_('"Padding" Oranı'),
								"id" => "theme_padding",
								"type" => "short-text",
								"help" => _kp_('Kenar boşluğu oranı')));
				?>
			</div>

		</div> <!-- Skins Settings -->

		<div id="tab4" class="tab_content tabs-wrap">
			<h2><?php _kp('SEO Ayarları'); ?></h2>
			<?php
			// YOAST & AIOSP
			$seo_plugins = keremiya_is_active_seo_plugins();
			if($seo_plugins):
				echo '<div class="keremiyapanel-item"><h3>'._kp_('Genel Ayarlar').'</h3><p style="margin: 0px 16px;">'.sprintf(_kp_('%s SEO eklentisi aktif olduğu için Keremiya Seo Ayarları devre dışı bırakıldı.'), $seo_plugins).'</p></div>';
			else: ?>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Genel Ayarlar'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Keremiya Seo Aktif"),
								"id" => "seo_active",
								"type" => "checkbox",
								"help" => _kp_("Herhangi bir SEO eklentisi kullanıyorsanız Keremiya SEO seçeneklerini kapatın.")));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Standart URL"),
								"id" => "canonical_url_active",
								"type" => "checkbox"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Kategori Seo Metaları"),
								"id" => "category_seo_active",
								"type" => "checkbox"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Open Graph Meta Etiketleri"),
								"id" => "og_active",
								"type" => "checkbox"));
				?>
			</div>
			<div class="keremiyapanel-item">
				<h3><?php _kp('Anasayfa Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Anasayfa Başlığı"),
								"id" => "seo_home_title",
								"type" => "textarea"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Anasayfa Açıklaması"),
								"id" => "seo_home_description",
								"type" => "textarea"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Anasayfa Anahtar Kelimeleri"),
								"id" => "seo_home_keywords",
								"type" => "textarea"));
				?>
			</div>
			<div class="keremiyapanel-item">
				<h3><?php _kp('Başlık Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Başlıkları biçimlendir"),
								"id" => "title_format",
								"type" => "checkbox"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Yazı Format"),
								"id" => "page_title_format",
								"extra_text" => _kp_("{page_title} = Yazı Başlığı <br> {blog_title} = Blog Başlığı"),
								"type" => "text"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Kategori Format"),
								"id" => "category_title_format",
								"extra_text" => _kp_("{category_title} = Kategori Başlığı <br> {blog_title} = Blog Başlığı"),
								"type" => "text"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Etiket Format"),
								"id" => "tag_title_format",
								"extra_text" => _kp_("{tag_title} = Etiket Başlığı <br> {blog_title} = Blog Başlığı"),
								"type" => "text"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Arama Format"),
								"id" => "search_title_format",
								"extra_text" => _kp_("{search_title} = Arama Başlığı <br> {blog_title} = Blog Başlığı"),
								"type" => "text"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Arşiv Format"),
								"id" => "archive_title_format",
								"extra_text" => _kp_("{archive_title} = Arşiv Başlığı <br> {blog_title} = Blog Başlığı"),
								"type" => "text"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Üye Format"),
								"id" => "author_title_format",
								"extra_text" => _kp_("{author_title} = Üye Başlığı <br> {blog_title} = Blog Başlığı"),
								"type" => "text"));
				?>
			</div>
			<div class="keremiyapanel-item">
				<h3><?php _kp('Oluşturucu Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Meta Açıklamaları otomatik oluştur"),
								"id" => "seo_auto_description",
								"type" => "checkbox"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Meta Anahtar kelimeleri için etiket kullan."),
								"id" => "seo_auto_tags",
								"type" => "checkbox"));
				?>
			</div>
			<div class="keremiyapanel-item">
				<h3><?php _kp('Web Yöneticisi Doğrulama'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Google Site Doğrulaması"),
								"id" => "google_meta_code",
								"type" => "text"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Bing Site Doğrulaması"),
								"id" => "bing_meta_code",
								"type" => "text"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Pinterest Site Doğrulaması"),
								"id" => "pinterest_meta_code",
								"type" => "text"));
				?>
			</div>
			<?php endif; ?>
		</div> <!--tab4-->
		

		<div id="tab9" class="tabs-wrap">
			<h2><?php _kp('Header Ayarları'); ?></h2> 
			
			<div class="keremiyapanel-item">
				<h3><?php _kp('Logo'); ?></h3>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Logo Ayarları"),
								"id" => "logo_setting",
								"type" => "radio",
								"options" => array( "logo"=>_kp_("Resimli Logo Göster") ,
													"title"=>_kp_("Site Başlığını Göster") )));
				?>
				<div class="logo-setting">		
				<?php
					keremiya_options(
						array(	"name" => _kp_("Logo Yükle"),
								"id" => "logo",
								"type" => "upload"));
				?>
				</div>

				<div class="title-setting">	
				<?php
					keremiya_options(					
						array(	"name" => _kp_("Site Başlığı"),
								"id" => "logo_title",
								"extra_text" => _kp_("Örn: Vidyo<i>{color}</i>max<i>{end}</i>"),
								"type" => "text"));
				?>
				</div>
			</div>
			

			<div class="keremiyapanel-item">
				<h3><?php _kp('Görünüm'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Yapışkan Header"),
								"id" => "header_fixed",
								"type" => "checkbox"));
			
				?>	
				<?php
					keremiya_options(
						array(	"name" => _kp_("Admin Bar"),
								"id" => "admin_bar",
								"type" => "checkbox"));
			
				?>	
			</div>
					
			<div class="keremiyapanel-item">
				<h3><?php _kp('Arama Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Canlı Arama"),
								"id" => "live_search",
								"type" => "checkbox"));
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Sosyal Linkler'); ?></h3>
					<?php
					keremiya_options(
						array(	"name" => _kp_("Etkinleştir"),
								"id" => "header_social",
								"type" => "checkbox")); 
					keremiya_options(				
						array(	"name" => "Facebook",
								"id" => "facebook",
								"help" => _kp_("Link'in başına 'http://' eklemeyi unutmayın."),
								"type" => "text")); 	
					keremiya_options(			
						array(	"name" => "Twitter",
								"id" => "twitter",
								"help" => _kp_("Link'in başına 'http://' eklemeyi unutmayın."),
								"type" => "text")); 
					keremiya_options(					
						array(	"name" => "Google Plus",
								"id" => "google",
								"help" => _kp_("Link'in başına 'http://' eklemeyi unutmayın."),
								"type" => "text")); 
					keremiya_options(					
						array(	"name" => "Youtube",
								"id" => "youtube",
								"help" => _kp_("Link'in başına 'http://' eklemeyi unutmayın."),
								"type" => "text")); 
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Header Kodu'); ?></h3>
				<div class="option-item">
					<small><?php _kp('Aşağıdaki kutucuğa ekleyeceğiniz kodlar &lt;head&gt; etiketi içerisinde yer alır. CSS yada JS kodlarınızı buraya eklemeniz önerilir.'); ?></small>
					<textarea id="header_code" name="keremiya_options[header_code]" style="width:100%" rows="7"><?php echo htmlspecialchars_decode(keremiya_get_option('header_code'));  ?></textarea>				
				</div>
			</div>

		</div> <!-- Header Settings -->
				
		<div id="tab6" class="tab_content tabs-wrap">
			<h2><?php _kp('İçerik Ayarları'); ?></h2> 
				<div class="keremiyapanel-item">
				<h3><?php _kp('Özet Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Özet Alanı"),
								"id" => "excerpt_hide",
								"type" => "radio",
								"options" => array(
									'show' => _kp_('Özetin tamamını göster'),
									'hide' => _kp_('Özetin bir kısmını göster'))));
				keremiya_options(
						array(	"name" => _kp_("Daha fazla Göster Özelliği"),
								"id" => "showmore",
								"type" => "checkbox"));
				?>
				</div>
			<div class="keremiyapanel-item">
				<h3><?php _kp('Görünüm Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("İzleme Alanı"),
								"id" => "videoarea",
								"type" => "radio",
								"options" => array(
									'standart' => _kp_('Standart'),
									'autosize' => _kp_('Sadece Video'),
									'center' => _kp_('Herşeyi Ortala')),
								"help" => _kp_("Sadece Video seçeneği videoları otomatik boyutlandırır.")));
				?>

				<div class="keremiyapanel-item">
					<h3><?php _kp('Sidebar Ayarları'); ?></h3>
					
				<?php
					keremiya_options(
							array(	"name" => _kp_("Geniş Sidebar"),
									"id" => "large_sidebar",
									"type" => "checkbox"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Kayan Sidebar"),
								"id" => "sticky_sidebar",
								"type" => "checkbox"));
				?>
				</div>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Buton Ayarları'); ?></h3>
				<div class="keremiyapanel-item">
				<h3><?php _kp('Part Sistemi'); ?></h3>
				<?php
				keremiya_options(
						array(	"name" => _kp_("Yeni Part Sistemi"),
								"id" => "new_part_system",
								"help" => _kp_("Gelişmiş part sistemi sayesinde 'Dil' ve 'Kalite' gibi ekstra bilgiler ekleyebilirsiniz."),
								"type" => "checkbox"));
				keremiya_options(
						array(	"name" => _kp_("Kaynak adını gizle"),
								"id" => "active_part",
								"help" => _kp_("''Kaynak: Aktif Part'' şeklinde detaylandır."),
								"type" => "checkbox"));
				?>
				</div>
				<div class="keremiyapanel-item">
				<h3><?php _kp('Extra Part Sistemi'); ?></h3>
				<?php
				keremiya_options(
						array(	"name" => _kp_("Görünüm"),
								"id" => "tabs_linktype",
								"type" => "radio",
								"options" => array(
									"hideshow"=>_kp_("Gizle Göster"),
									"permalink"=>_kp_("Linklendir")
									),
								)
						);
				?>
				<p style="margin-left: 16px;">
					<?php printf(_k_('Extra Part Sistemi kullanımı hakkında detaylı bilgi için bu %s ziyaret et.'), '<a href="https://www.keremiya.com/destek/dokumantasyon/Keremiya-v5/#extra-part-system">'._k_("bağlantı").'</a>'); ?></p>
				</div>
				<div class="keremiyapanel-item">
				<h3><?php _kp('Diğer'); ?></h3>
				<?php
				keremiya_options(
						array(	"name" => _kp_("Ekle"),
								"id" => "addto",
								"help" => _kp_("Daha Sonra izle, Favoriler."),
								"type" => "checkbox"));
				keremiya_options(
						array(	"name" => _kp_("Paylaş"),
								"id" => "share",
								"help" => _kp_("Sosyal paylaş butonları."),
								"type" => "checkbox"));
				keremiya_options(
						array(	"name" => _kp_("Bildir"),
								"id" => "report",
								"help" => _kp_("Bildir (report) sistemi."),
								"type" => "checkbox"));
				keremiya_options(
						array(	"name" => _kp_("Geniş"),
								"id" => "wide",
								"help" => _kp_("Tam Ekran İzleme Özelliği"),
								"type" => "checkbox"));
				?>
				</div>
			</div>

			<div class="keremiyapanel-item">

				<h3><?php _kp('Benzer Film Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Benzer Filmler"),
								"id" => "similar",
								"type" => "checkbox")); 
								
					keremiya_options(
						array(	"name" => _kp_("Gösterilecek Film Sayısı"),
								"id" => "similar_number",
								"type" => "short-text"));
								
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Oylama Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Film Puanlaması"),
								"id" => "vote",
								"options" => array( "user"=>_kp_("Sadece Üyeler"),
													"public"=>_kp_("Herkes")
													),
								"type" => "radio"));
					keremiya_options(
						array(	"name" => _kp_("Yorum Beğenileri"),
								"id" => "comment_like",
								"options" => array( "user"=>_kp_("Sadece Üyeler"),
													"public"=>_kp_("Herkes")
													),
								"type" => "radio"));
				?>
			</div>

			<div class="keremiyapanel-item">

				<h3><?php _kp('İçerik Ekle Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Etkinleştir"),
								"id" => "add_post",
								"type" => "checkbox")); 
								
					keremiya_options(
						array(	"name" => _kp_("İçerik Durumu"),
								"id" => "post_status",
								"options" => array( "publish"=>_kp_("Yayınlansın"),
													"pending"=>_kp_("Onay beklesin")),
								"type" => "radio"));


					global $wp_roles;
					if ( isset( $wp_roles ) ) {
						$wp_roles = new WP_Roles();
						$roles = $wp_roles->get_names();
					}

					keremiya_options(
						array(	"name" => _kp_("Kimler için"),
								"id" => "post_status_roles",
								"options" => $roles,
								"type" => "multiple"));

				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('İzlenme Sayacı'); ?></h3>
				<?php
				keremiya_options(
						array(	"name" => _kp_("Cache ile uyumlu çalıştır"),
								"id" => "views_cache",
								"type" => "checkbox",
								"help" => _kp_("Sadece cache eklentisi varsa etkinleştirin")));
				?>
			</div>
		</div> <!-- Article Settings -->
		
		
		<div id="tab7" class="tabs-wrap">
			<h2><?php _kp('Footer Ayarları'); ?></h2> 
			
			<div class="keremiyapanel-item">
				<h3><?php _kp('Footer Logo'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Logo Yükle"),
								"id" => "footer_logo",
								"type" => "upload"));
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Footer Düzeni'); ?></h3>
					<div class="option-item">

					<?php
						$checked = 'checked="checked"';
						$keremiya_footer_widgets = keremiya_get_option('footer_widgets');
					?>
					<ul id="footer-widgets-options" class="keremiya-options">
						<li>
							<input id="keremiya_footer_widgets"  name="keremiya_options[footer_widgets]" type="radio" value="default" <?php if($keremiya_footer_widgets == 'default' || !$keremiya_footer_widgets ) echo $checked; ?> />
							<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/footer-2c-wide-narrow.png" /></a>
						</li>
						<li>
							<input id="keremiya_footer_widgets"  name="keremiya_options[footer_widgets]" type="radio" value="right" <?php if($keremiya_footer_widgets == 'right') echo $checked; ?> />
							<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/footer-2c-narrow-wide.png" /></a>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="keremiyapanel-item">
				<h3><?php _kp('Footer Görünür Kısım'); ?></h3>
				<div class="option-item">
					<textarea id="keremiya_footer_left" name="keremiya_options[footer_left]" style="width:100%" rows="4"><?php echo htmlspecialchars_decode(keremiya_get_option('footer_left'));  ?></textarea>				
				</div>
			</div>
			
			<div class="keremiyapanel-item">
				<h3><?php _kp('Footer Gizli Kısım'); ?></h3>
				<div class="option-item">
					<small><?php _kp('Aşağıdaki kutucuğa ekleyeceğiniz kodlar görünmez olarak yerleştirilir.'); ?></small>
					<textarea id="keremiya_footer_right" name="keremiya_options[footer_right]" style="width:100%" rows="4"><?php echo htmlspecialchars_decode(keremiya_get_option('footer_right'));  ?></textarea>				
				</div>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Footer Kodu'); ?></h3>
				<div class="option-item">
					<small><?php _kp('Aşağıdaki kutucuğa ekleyeceğiniz kodlar &lt;/body&gt; etiketi dışında yer alır. JS kodlarınızı buraya eklemeniz önerilir.'); ?></small>

					<textarea id="footer_code" name="keremiya_options[footer_code]" style="width:100%" rows="7"><?php echo htmlspecialchars_decode(keremiya_get_option('footer_code'));  ?></textarea>				
				</div>
			</div>	

		</div><!-- Footer Settings -->

		
		<div id="tab8" class="tab_content tabs-wrap">
			<h2><?php _kp('Reklam Ayarları'); ?></h2> 
			
			<div class="keremiyapanel-item">
				<h3><?php _kp('ArkaPlan Reklam'); ?></h3>
				<?php	
					keremiya_options(				
						array(	"name" => _kp_("Etkinleştir"),
								"id" => "banner_background",
								"type" => "checkbox",)); 
					keremiya_options(					
						array(	"name" => _kp_("Reklam Kodu"),
								"id" => "banner_background_code",
								"type" => "textarea",
								"help" => _kp_("Reklam kodunuz <body> etiketinin hemen sonrasına eklenir.") )); 
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Header menü altı reklam'); ?></h3>
				<?php	
					keremiya_options(				
						array(	"name" => _kp_("Etkinleştir"),
								"id" => "banner_top",
								"type" => "checkbox",
								"help" =>  _kp_('Tavsiye edilen reklam boyutları: 728x90'))); 
					keremiya_options(					
						array(	"name" => _kp_("Reklam Kodu"),
								"id" => "banner_top_code",
								"type" => "textarea")); 
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Video Reklamları'); ?></h3>

				<div class="keremiyapanel-item">
					<h3><?php _kp('Video önü reklam'); ?></h3>
					<?php	
						keremiya_options(				
							array(	"name" => _kp_("Etkinleştir"),
									"id" => "banner_before_video",
									"type" => "checkbox",
									"help" =>  _kp_('Tavsiye edilen reklam boyutları: 336x280, 300x250, Max Genişlik: 800px'))); 
						keremiya_options(					
							array(	"name" => _kp_("Reklam Kodu"),
									"id" => "banner_before_video_code",
									"type" => "textarea")); 
						keremiya_options(					
							array(	"name" => _kp_("Reklam Süresi"),
									"id" => "banner_before_video_time",
									"type" => "number"));
					?>
				</div>

				<div class="keremiyapanel-item">
					<h3><?php _kp('Video Üstü & Altı Reklamları'); ?></h3>
					<?php
						keremiya_options(				
							array(	"name" => _kp_("Etkinleştir"),
									"id" => "banner_video_top",
									"type" => "checkbox",
									"help" => _kp_("Max: 900px genişliğinde reklam önerilmektedir.") ));
						keremiya_options(					
							array(	"name" => _kp_("Üst Reklam Kodu"),
									"id" => "banner_video_top_code",
									"type" => "textarea")); 
						keremiya_options(				
							array(	"name" => _kp_("Etkinleştir"),
									"id" => "banner_video_bottom",
									"type" => "checkbox",
									"help" => _kp_("Max: 900px genişliğinde reklam önerilmektedir.") ));
						keremiya_options(					
							array(	"name" => _kp_("Alt Reklam Kodu"),
									"id" => "banner_video_bottom_code",
									"type" => "textarea")); 
					?>
				</div>
			</div>

				<div class="keremiyapanel-item">
					<h3><?php _kp('Arşiv & Sidebar Reklamları'); ?></h3>
				<div class="keremiyapanel-item">
					<h3><?php _kp('Arşiv Reklam'); ?></h3>
					<?php
						keremiya_options(				
							array(	"name" => _kp_("Etkinleştir"),
									"id" => "banner_728",
									"type" => "checkbox",
									"help" => _kp_("728x90px genişliğinde reklam önerilmektedir.") ));
						keremiya_options(					
							array(	"name" => _kp_("Alt Reklam Kodu"),
									"id" => "banner_728_code",
									"type" => "textarea",
									"help" => _kp_("Her sayfanın en alt kısmında yer alır.")));  
					?>
				</div>
				<div class="keremiyapanel-item">
					<h3><?php _kp('Sidebar Reklam'); ?></h3>
					<?php
						keremiya_options(				
							array(	"name" => _kp_("Etkinleştir"),
									"id" => "banner_sidebar",
									"type" => "checkbox",
									"help" => _kp_("250x250 reklam boyutu önerilir. Responsive uyumlu reklam eklemeniz önerilir.") ));
						keremiya_options(					
							array(	"name" => _kp_("Reklam Kodu"),
									"id" => "banner_sidebar_code",
									"type" => "textarea")); 
					?>
				</div>
				</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Kayan Reklamlar'); ?></h3>
				<div class="keremiyapanel-item">
					<h3><?php _kp('Footer Kayan Reklam'); ?></h3>
					<?php
						keremiya_options(				
							array(	"name" => _kp_("Etkinleştir"),
									"id" => "banner_footer",
									"type" => "checkbox",
									"help" => _kp_("728x90 reklam boyutu önerilir. Responsive uyumlu reklam eklemeniz önerilir.") ));
						keremiya_options(					
							array(	"name" => _kp_("Reklam Kodu"),
									"id" => "banner_footer_code",
									"type" => "textarea")); 
					?>
				</div>
				<div class="keremiyapanel-item">
					<h3><?php _kp('Splash Reklam'); ?></h3>
					<?php
						keremiya_options(				
							array(	"name" => _kp_("Etkinleştir"),
									"id" => "banner_splash",
									"type" => "checkbox",
									"help" => _kp_("Bu reklam belirli bir süre boyunca tüm ekranı kaplar.") ));
						keremiya_options(					
							array(	"name" => _kp_("Reklam Kodu"),
									"id" => "banner_splash_code",
									"type" => "textarea")); 
						keremiya_options(					
							array(	"name" => _kp_("Reklam Süresi"),
									"id" => "banner_splash_time",
									"type" => "number"))
					?>
				</div>

			</div>
		</div> <!-- Banners Settings -->
		
		
		<div id="tab12" class="tab_content tabs-wrap">
			<h2><?php _kp('Arşiv Ayarları'); ?></h2>		

			<div class="keremiyapanel-item">
				<h3><?php _kp('Kullanıcı Sayfası Ayarları'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Kullanıcı URL"),
								"id" => "author_base",
								"help" => _kp_("URL Değişikliğin geçerli olabilmesi için Kalıcı Bağlantı Ayarlarınızı'da güncelleyin."),
								"type" => "text"));
				?>
			</div>
			<div class="keremiyapanel-item">
				<h3><?php _kp('Kategori Liste Düzeni'); ?></h3>
				<div class="option-item">
					<?php
						$checked = 'checked="checked"';
						$keremiya_category_layout = keremiya_get_option('category_layout');
					?>
					<ul id="home-layout-options" class="keremiya-options">
						<li>
							<input id="keremiya_category_layout"  name="keremiya_options[category_layout]" type="radio" value="film" <?php if($keremiya_category_layout == 'film' || !$keremiya_category_layout) echo $checked; ?> />
							<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/2c.png" /></a>
						</li>
						<li>
							<input id="keremiya_category_layout"  name="keremiya_options[category_layout]" type="radio" value="film-v2" <?php if($keremiya_category_layout == 'film-v2') echo $checked; ?> />
							<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/3c.png" /></a>
						</li>
						<li>
							<input id="keremiya_category_layout"  name="keremiya_options[category_layout]" type="radio" value="series" <?php if($keremiya_category_layout == 'series') echo $checked; ?> />
							<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/1c.png" /></a>
						</li>
					</ul>
				</div>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Sütun Sayısı"),
								"id" => "category_columns",
								"type" => "number",
								"options" => array( 
									"min"=> '2',
									"max"=> '8',
								),
								"help" => _kp_('En fazla 8 adet sıralanabilir.'),
							)
						);
					keremiya_options(
						array(	"name" => _kp_("Gösterilecek Film Sayısı"),
								"id" => "category_movies_number",
								"type" => "number",
								"options" => array( 
									"min"=> '1',
									"max"=> '99',
								)));
				?>
				<div class="keremiyapanel-item">
					<h3><?php _kp('Kategori Sidebarı'); ?></h3>
					<?php
						keremiya_options(
							array( 	"name" => _kp_("Film Dili"),
									"id" => "category_list_lang",
									"type" => "checkbox",
								)
							);
					?>
					<?php
						keremiya_options(
							array( 	"name" => _kp_("Yapım Yılı"),
									"id" => "category_list_year",
									"type" => "checkbox",
								)
							);
					?>
					<?php
						keremiya_options(
							array( 	"name" => _kp_("Tek Sütun"),
									"id" => "category_list_one",
									"type" => "checkbox",
									"help" => _kp_("Kategorileri yan yana sıralamayı devre dışı bırakır."),
								)
							);
					?>
				</div>
			</div>
			<div class="keremiyapanel-item">
				<h3><?php _kp('Taxonomy Liste Düzeni'); ?></h3>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Sütun Sayısı"),
								"id" => "tax_columns",
								"type" => "number",
								"options" => array( 
									"min"=> '2',
									"max"=> '8',
								),
								"help" => _kp_('En fazla 8 adet sıralanabilir.'),
							)
						);
					keremiya_options(
						array(	"name" => _kp_("Gösterilecek Film Sayısı"),
								"id" => "tax_movies_number",
								"type" => "number",
								"options" => array( 
									"min"=> '1',
									"max"=> '99',
								)));
				?>
			</div>
		</div> <!-- Archives -->
				
		<div id="tab11" class="tab_content tabs-wrap">
		<h2><?php _kp('Player Ayarları'); ?></h2>
		<div class="clear"></div>
			<div class="keremiyapanel-item">
				<h3><?php _kp('Player Ayarları'); ?></h3>
				<div class="keremiyapanel-item"><div class="option-item"><?php _kp("Bu alandaki ayarlar stabil çalışmayabilir."); ?></div></div>
				<div class="keremiyapanel-item">
				<h3><?php _kp('Genel Ayarlar'); ?></h3>

				<div class="option-item">
					<textarea id="customize_player" name="keremiya_options[customize_player]" style="width:100%" rows="3"><?php echo htmlspecialchars_decode(keremiya_get_option('custom_player'));  ?></textarea>				
					<?php _kp('IFRAME veya Embed kodlarına müdahale eden JS kodlarınızı buraya eklemeniz önerilir.'); ?>
				</div>
				</div>
				<div class="keremiyapanel-item">
				<h3><?php _kp('Fragman Player'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Otomatik Oynat"),
								"id" => "player_autoplay",
								"type" => "checkbox",
								"help" => _kp_("Videoları otomatik oynatmaya zorla")));
					keremiya_options(
						array(	"name" => _kp_("Genel Player"),
								"id" => "general_player",
								"options" => array(	"mediaelement"=> _kp_("MediaElement"),
													"jwplayer"=> _kp_("Özel Player"),
													),
								"type" => "radio"));
				?>

			</div>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Özel Player Ayarları'); ?></h3>
				<div class="option-item">
					<small>
					<?php _kp('JW Player ayarları hakkında daha fazla bilgi için bu sayfayı ziyaret edin.'); ?>
					(<a href="http://support.jwplayer.com/customer/portal/articles/1413113-configuration-options-reference">JW Player Configuration</a>)</small>
					</small>
					<textarea id="jw_player" name="keremiya_options[jw_player]" style="width:100%" rows="7"><?php echo htmlspecialchars_decode(keremiya_get_option('jw_player'));  ?></textarea>				
					<?php _kp('<b>%URL%</b> = Video URL <br> <b>%IMAGE%</b> = Video Resmi <br> <b>%AUTOPLAY%</b> = Otomatik oynat'); ?>
				</div>
			</div>

		</div>

		<div id="tab13" class="tab_content tabs-wrap">
		<h2><?php _kp('Stil Ayarları'); ?></h2>
		<div class="clear"></div>
		<div class="keremiya-panel-sub-tabs">
			<ul>
				<li class="sub-tabs sub1"><a href="#sub1"><?php _kp('Body'); ?></a></li>
				<li class="sub-tabs sub3"><a href="#sub3"><?php _kp('Header'); ?></a></li>
				<!--<li class="sub-tabs sub2"><a href="#sub2"><?php _kp('Content'); ?></a></li>
				<li class="sub-tabs sub5"><a href="#sub5"><?php _kp('Sidebar'); ?></a></li>-->
				<li class="sub-tabs sub4"><a href="#sub4"><?php _kp('Footer'); ?></a></li>
			</ul>
			<div class="clear"></div>
		</div> <!-- .keremiya-panel-sub-tabs -->

		<div id="sub1" class="sub-tabs-wrap">		
			<div class="keremiyapanel-item">

				<h3><?php _kp('Arka plan'); ?></h3>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Arka Plan Tipi"),
								"id" => "background_type",
								"type" => "radio",
								"options" => array( "default"=>_kp_("Varsayılan") ,
													"pattern"=>_kp_("Model") ,
													"custom"=>_kp_("Özel Arka Plan") )));
				?>
			

			<div class="keremiyapanel-item" id="pattern-settings">
				<h3><?php _kp('Bir model seçin'); ?></h3>
				
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Arka Plan Rengi"),
								"id" => "background_pattern_color",
								"type" => "color" ));
				?>
				
				<?php
					$checked = 'checked="checked"';
					$theme_pattern = keremiya_get_option('background_pattern');
				?>
				<ul id="theme-pattern" class="keremiya-options">
					<?php for($i=1 ; $i<=23 ; $i++ ){ 
					 $pattern = 'body-bg'.$i; ?>
					<li>
						<input id="keremiya_background_pattern"  name="keremiya_options[background_pattern]" type="radio" value="<?php echo $pattern ?>" <?php if($theme_pattern == $pattern ) echo $checked; ?> />
						<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/pattern<?php echo $i ?>.png" /></a>
					</li>
					<?php } ?>
				</ul>
			</div>

			<div class="keremiyapanel-item" id="bg_image_settings">
				<h3><?php _kp('Özel Arka Plan'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Arka Plan Rengi"),
								"id" => "background",
								"type" => "background"));
				?>

			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Genel Yazı Renkleri'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Normal Yazı"),
								"id" => "global_color",
								"type" => "color"));

					keremiya_options(
						array(	"name" => _kp_("Link Rengi"),
								"id" => "links_color",
								"type" => "color"));

					keremiya_options(
						array(	"name" => _kp_("Link Rengi Vurgulu"),
								"id" => "links_color_hover",
								"type" => "color"));
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Çizgi Renkleri'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Genel Çizgi Rengi"),
								"id" => "global_line_color",
								"type" => "color"));
					keremiya_options(
						array(	"name" => _kp_("Sidebar Çizgi Rengi"),
								"id" => "sidebar_line_color",
								"type" => "color"));
				?>
			</div>

		</div>
		</div>

		<div id="sub2" class="sub-tabs-wrap">
			
			<div class="keremiyapanel-item">
				<h3><?php _kp('Üst'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Arka Plan Rengi"),
								"id" => "post_top_background_color",
								"type" => "color"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Yazı Rengi"),
								"id" => "post_top_color",
								"type" => "color"));
				?>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Styling'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Link Rengi"),
								"id" => "post_links_color",
								"type" => "color"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Link Rengi Vurgulu"),
								"id" => "post_links_color_hover",
								"type" => "color"));
				?>
			</div>	
				<div class="prepared center">
					<?php _kp('Henüz geliştiriliyor'); ?>
				</div>	
			</div>

		</div>

		<div id="sub3" class="sub-tabs-wrap">
			<div class="keremiyapanel-item">
				<h3><?php _kp('Arka plan'); ?></h3>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Arka Plan Tipi"),
								"id" => "header_background_type",
								"type" => "radio",
								"options" => array( "default"=>_kp_("Varsayılan") ,
													"custom"=>_kp_("Özel Arka Plan") )));
				?>

				<div id="header-background">
				<?php
					keremiya_options(
						array(	"name" => _kp_("Arka Plan Rengi"),
								"id" => "header_background",
								"gradient" => "true",
								"type" => "background"));
						
					keremiya_options(
						array(	"name" => _kp_("Menü Arka Plan Rengi"),
								"id" => "menu_background",
								"type" => "color"));
				?>
				</div>
				<div class="keremiyapanel-item">
					<h3><?php _kp('Styling'); ?></h3>
					<?php
						keremiya_options(
							array(	"name" => _kp_("Link Rengi"),
									"id" => "header_links_color",
									"type" => "color"));
						keremiya_options(
							array(	"name" => _kp_("Link Rengi Vurgulu"),
									"id" => "header_links_color_hover",
									"type" => "color"));
						keremiya_options(
							array(	"name" => _kp_("Logo Başlık Rengi"),
									"id" => "logo_color",
									"type" => "color"));
						keremiya_options(
							array(	"name" => _kp_("Logo {color} Rengi"),
									"id" => "logo_span_color",
									"type" => "color"));
						keremiya_options(
							array(	"name" => _kp_("İkon Rengi"),
									"id" => "header_icon_color",
									"type" => "color"));
					?>
				</div>
			</div>
		</div>

		<div id="sub4" class="sub-tabs-wrap">
			<div class="keremiyapanel-item">
				<h3><?php _kp('Arka plan'); ?></h3>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Arka Plan Tipi"),
								"id" => "footer_background_type",
								"type" => "radio",
								"options" => array( "default"=>_kp_("Varsayılan") ,
													"custom"=>_kp_("Özel Arka Plan") )));
				?>

				<div id="footer-background">
				<?php
					keremiya_options(
						array(	"name" => _kp_("Arka Plan Rengi"),
								"id" => "footer_background",
								"type" => "background"));
					keremiya_options(
						array(	"name" => _kp_("Menü Arka Plan Rengi"),
								"id" => "footer_menu_background",
								"type" => "color"));
				?>
				</div>
				<div class="keremiyapanel-item">
					<h3><?php _kp('Styling'); ?></h3>
					<?php
						keremiya_options(
							array(	"name" => _kp_("Yazı Rengi"),
									"id" => "footer_color",
									"type" => "color"));
					?>
					<?php
						keremiya_options(
							array(	"name" => _kp_("Bağlantılar Rengi"),
									"id" => "footer_links_color",
									"type" => "color"));
					?>
					<?php
						keremiya_options(
							array(	"name" => _kp_("Bağlantılar Rengi Vurgulu"),
									"id" => "footer_links_color_hover",
									"type" => "color"));
					?>
				</div>	
			</div>		
		</div>	

		<div id="sub5" class="sub-tabs-wrap">
			<div class="keremiyapanel-item">
				<h3><?php _kp('Üst'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Arka Plan Rengi"),
								"id" => "sidebar_top_background_color",
								"type" => "color"));
				?>
			<div class="keremiyapanel-item">
				<h3><?php _kp('Styling'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Yazı Rengi"),
								"id" => "sidebar_top_color",
								"type" => "color"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Bağlantılar Rengi"),
								"id" => "sidebar_top_link_color",
								"type" => "color"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Bağlantılar Rengi Vurgulu"),
								"id" => "sidebar_top_link_color_hover",
								"type" => "color"));
				?>
			</div>
				<div class="prepared center">
					<?php _kp('Henüz geliştiriliyor'); ?>
				</div>
			</div>
			
		</div>	
			<div class="keremiyapanel-item">
				<h3><?php _kp('Özel Yazı Tipi'); ?></h3>	
				<?php
					keremiya_options(
						array(	"name" => _kp_("Yazı tipi Seçin"),
								"id" => "font",
								"help" => _kp_("Popüler Google Web Font'ları"),
								"type" => "typography"));
				?>
			</div>	
			<div class="keremiyapanel-item">
				<h3><?php _kp('Özel CSS'); ?></h3>	
				<div class="option-item">
					<textarea id="keremiya_css" name="keremiya_options[css]" style="width:100%" rows="7"><?php echo keremiya_get_option('css');  ?></textarea>				
				</div>	
			</div>	

		</div> <!-- Styling -->

		<div id="tab10" class="tab_content tabs-wrap">
			<h2><?php _kp('Gelişmiş Ayarlar'); ?></h2>		
			
			<div class="keremiyapanel-item">
				<h3><?php _kp('Wordpress Giriş Sayfası'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Yeni giriş sayfası tasarımı"),
								"id" => "dashboard_custom_css",
								"type" => "checkbox"));
				?>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Logo"),
								"id" => "dashboard_logo",
								"type" => "upload"));
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('CSS & JS Sıkıştırması'); ?></h3>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("CSS Minify"),
								"id" => "css_minify",
								"type" => "checkbox",
							)
						);
					keremiya_options(
						array( 	"name" => _kp_("JS Minify"),
								"id" => "js_minify",
								"type" => "checkbox",
							)
						);
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Güncelleme Uyarısı'); ?></h3>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Devre Dışı Bırak"),
								"id" => "disable_notifier",
								"type" => "checkbox",
							)
						);
				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Keremiya Destek Linki'); ?></h3>
				<?php
					keremiya_options(
						array( 	"name" => _kp_("Devre Dışı Bırak"),
								"id" => "disable_keremiya_support",
								"type" => "checkbox",
							)
						);
				?>
				<p style="padding: 10px 16px;">
					<span class="mm1">
						<?php _kp("Alt kısımda yer alan link ile bizi destekleyebilirsiniz. Desteğiniz için teşekkür ederiz."); ?>
					</span>
					<span class="mm2" style="display:none">
						<span class="uzgun">:(</span>
					</span>
				</p>
			</div>

			<?php
				global $array_options;
				
				$current_options = array();
				foreach( $array_options as $option ){
					if( get_option( $option ) )
						$current_options[$option] =  get_option( $option ) ;
				}
			?>
			
			<div class="keremiyapanel-item">
				<h3><?php _kp('Dışa Aktar'); ?></h3>
				<div class="option-item">
					<textarea style="width:100%" rows="7"><?php echo $currentsettings = base64_encode( serialize( $current_options )); ?></textarea>
				</div>
			</div>
			<div class="keremiyapanel-item">
				<h3><?php _kp('İçe Aktar'); ?></h3>
				<div class="option-item">
					<textarea id="keremiya_import" name="keremiya_import" style="width:100%" rows="7"></textarea>
				</div>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Özel Alan Meta & Taxonomy ID isimleri'); ?></h3>
				<?php
					keremiya_options(
						array(	"name" => _kp_("Etkinleştir"),
								"id" => "change_meta_keys",
								"type" => "checkbox",
								"help" => _kp_('Tema değişikliği yaptıysanız eski temanızdaki özel alan yada taxonomy ID isimlerini tanımlayabilirsiniz. Örneğin: Yönetmen için yonetmen')));
					foreach (keremiya_custom_meta_keys() as $key => $title) {
					keremiya_options(
						array(	"name" => $title,
								"id" => "meta_{$key}",
								"type" => "text"));
  					}

				?>
			</div>

			<div class="keremiyapanel-item">
				<h3><?php _kp('Tema Dili'); ?></h3>
				<?php
				$langs = array(
					'tr_TR' => _kp_('Türkçe'),
					'en_EN' => _kp_('İngilizce'),
				);

					keremiya_options(
						array(	"name" => _kp_("Diller"),
								"id" => "lang",
								"options" => $langs,
								"type" => "select"));
				?>
				<div class="prepared center">
					<?php _kp('Henüz geliştiriliyor'); ?>
				</div>
			</div>
	
		</div> <!-- Advanced -->
		
	
		</div><!-- .keremiya-panel-content center -->
	</div><!-- .keremiya-panel-content -->
</div><!-- .keremiya-panel -->


		<div class="keremiya-footer">
			

			<?php echo $save; ?>

			
		</form>

			<form method="post">
				<div class="keremiyapanel-reset">
					<input onClick="return confirm('<?php _kp("Ayarları sıfırlamak istediğinizden emin misiniz?"); ?>')" name="reset" class="keremiyapanel-reset-button" type="submit" value="<?php _kp('Ayarları Sıfırla'); ?>" />
					<input type="hidden" name="action" value="reset" />
				</div>
			</form>
		</div>

<?php
}
?>