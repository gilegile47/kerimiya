jQuery(document).ready(function() {

    jQuery('.tooltip').tipsy({fade: true, gravity: 'se'});

// image Uploader Functions ##############################################
	function keremiya_set_uploader(field) {
		var button = "#upload_"+field+"_button";
		jQuery(button).click(function() {
			window.restore_send_to_editor = window.send_to_editor;
			tb_show('', 'media-upload.php?referer=keremiya-settings&amp;type=image&amp;TB_iframe=true&amp;post_id=0');
			keremiya_set_send_img(field);
			return false;
		});
		jQuery('#'+field).change(function(){
			jQuery('#'+field+'-preview').show();
			jQuery('#'+field+'-preview img').attr("src",jQuery('#'+field).val());
		});
	}
	function keremiya_set_send_img(field) {
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			
			if(typeof imgurl == 'undefined') // Bug fix By Fouad Badawy
				imgurl = jQuery(html).attr('src');
				
			jQuery('#'+field).val(imgurl);
			jQuery('#'+field+'-preview').show();
			jQuery('#'+field+'-preview img').attr("src",imgurl);
			tb_remove();
			window.send_to_editor = window.restore_send_to_editor;
		}
	};
	
	keremiya_set_uploader("logo");
	keremiya_set_uploader("favicon");
	keremiya_set_uploader("gravatar");
	keremiya_set_uploader("dashboard_logo");
	keremiya_set_uploader("footer_logo");
	keremiya_set_uploader("mobil_logo");

	
// image Uploader Functions ##############################################
	function keremiya_styling_uploader(field) {
		var button = "#upload_"+field+"_button";
		jQuery(button).click(function() {
			window.restore_send_to_editor = window.send_to_editor;
			tb_show('', 'media-upload.php?referer=keremiya-settings&amp;type=image&amp;TB_iframe=true&amp;post_id=0');
			styling_send_img(field);
			return false;
		});
		jQuery('#'+field).change(function(){
			jQuery('#'+field+'-preview img').attr("src",jQuery('#'+field).val());
		});
	}
	function styling_send_img(field) {
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			
			if(typeof imgurl == 'undefined') // Bug fix By Fouad Badawy
				imgurl = jQuery(html).attr('src');
				
			jQuery('#'+field+'-img').val(imgurl);
			jQuery('#'+field+'-preview').show();
			jQuery('#'+field+'-preview img').attr("src",imgurl);
			tb_remove();
			window.send_to_editor = window.restore_send_to_editor;
		}
	};	
	keremiya_styling_uploader("background");
	keremiya_styling_uploader("topbar_background");
	keremiya_styling_uploader("header_background");
	keremiya_styling_uploader("footer_background");

	
	jQuery(".del-img").live("click" , function() {
		jQuery(this).parent().fadeOut(function() {
			jQuery(this).hide();
			jQuery(this).parent().find('input[class="img-path"]').attr('value', '' );
		});
	});	
	
	var selected_radio = jQuery("input[name='keremiya_options[on_home]']:checked").val();
	if (selected_radio == 'latest') {	jQuery('#Home_Builder').hide();	}
	if (selected_radio == 'builder') {	jQuery('#Home_Latest').hide();	}
	jQuery("input[name='keremiya_options[on_home]']").change(function(){
		var selected_radio = jQuery("input[name='keremiya_options[on_home]']:checked").val();
		if (selected_radio == 'latest') {
			jQuery('#Home_Builder').slideToggle();
			jQuery('#Home_Latest').slideToggle();
		}else{
			jQuery('#Home_Latest').slideToggle();
			jQuery('#Home_Builder').slideToggle();
		}
	 });

	var slider = jQuery("#on_slider");
	var cat = jQuery("#slider-items");
	if( ! slider.is(':checked') ) {
		cat.hide();
	}
	jQuery(slider).change(function(){
		if( slider.is(':checked') ) {
			cat.slideDown();
		} else {
			cat.slideUp();
		}	
	});

	var status = jQuery("#post_status");
	var roles = jQuery("#post_status_roles-item");
	if( status.is(":checked") ) {
		roles.slideToggle();
	} else {
		roles.slideToggle();
	}
	jQuery(status).live("click" , function() {
	if( jQuery(this).val() == "publish" ) {
		roles.slideToggle();
	} else {
		roles.slideToggle();
	}
	});


	function ss_1( s ) {
		var on_news = jQuery("#news_on-item").parent(),
			on_today = jQuery("#today_movie_on-item").parent();

		if( s.is(":checked") ) {
			on_news.add(on_today).slideUp();
		} else {
			on_news.add(on_today).slideDown();
		}
	};

	var on_sidebar = jQuery("#on_sidebar");
	ss_1( on_sidebar );
	jQuery(on_sidebar).live("change" , function() {
		ss_1( on_sidebar );
	});


	function ss_2( s ) {
		var mm1 = jQuery(".mm1"),
			mm2 = jQuery(".mm2");

		if( s.is(":checked") ) {
			mm2.fadeIn('fast');
		} else {
			mm2.fadeOut('fast');
		}
	};
	var kp_sup = jQuery("#disable_keremiya_support");
	ss_2( kp_sup );
	jQuery(kp_sup).live("change" , function() {
		ss_2( kp_sup );
	});
	 
// Save Settings Alert	##############################################
	jQuery(".keremiyapanel-save").click( function() {
		jQuery('#save-alert').fadeIn();
	});

// HomeBuilder

	/*jQuery(".widget-head").bind('click', function() {
		jQuery(this).parent().parent().find(".widget-content").slideToggle(300);
    });*/
	
	jQuery(".toggle-open").live("click" ,function () {
		jQuery(this).parent().parent().find(".widget-content").slideToggle(300);
		jQuery(this).hide();
		jQuery(this).parent().find(".toggle-close").show();
    });

	
	
	jQuery(".toggle-close").live("click" ,function () {
		jQuery(this).parent().parent().find(".widget-content").slideToggle("fast");
		jQuery(this).hide();
		jQuery(this).parent().find(".toggle-open").show();
    });
	
	
	jQuery("#expand-all").live("click" ,function () {
		jQuery("#cat_sortable .widget-content").slideDown(300);
		jQuery("#cat_sortable .toggle-close").show();
		jQuery("#cat_sortable .toggle-open").hide();
    });
	jQuery("#collapse-all").live("click" ,function () {
		jQuery("#cat_sortable .widget-content").slideUp(300);
		jQuery("#cat_sortable .toggle-close").hide();
		jQuery("#cat_sortable .toggle-open").show();
    });
	
	
// Del Cats ##############################################
	jQuery(".del-cat").live("click" , function() {
		jQuery(this).parent().parent().addClass('removered').fadeOut(function() {
			jQuery(this).remove();
		});
	});

// Background Type ##############################################
	var bg_selected_radio = jQuery("input[name='keremiya_options[background_type]']:checked").val();
	if (bg_selected_radio == 'default') {	jQuery('#pattern-settings, #bg_image_settings').hide();	}
	if (bg_selected_radio == 'custom') {	jQuery('#pattern-settings').hide();	}
	if (bg_selected_radio == 'pattern') {	jQuery('#bg_image_settings').hide();	}
	jQuery("input[name='keremiya_options[background_type]']").change(function(){
		var bg_selected_radio = jQuery("input[name='keremiya_options[background_type]']:checked").val();
		if (bg_selected_radio == 'pattern') {
			jQuery('#pattern-settings').fadeIn();
			jQuery('#bg_image_settings').hide();
		}else if (bg_selected_radio == 'custom'){
			jQuery('#bg_image_settings').fadeIn();
			jQuery('#pattern-settings').hide();
		} else {
			jQuery('#pattern-settings, #bg_image_settings').fadeOut();
		}
	 });

	var header_bg_selected_radio = jQuery("input[name='keremiya_options[header_background_type]']:checked").val();
	if (header_bg_selected_radio == 'default') {	jQuery('#header-background').hide();	}
	if (header_bg_selected_radio == 'custom') {	jQuery('#header-background').show(); }
	jQuery("input[name='keremiya_options[header_background_type]']").change(function(){
		var header_bg_selected_radio = jQuery("input[name='keremiya_options[header_background_type]']:checked").val();
		if (header_bg_selected_radio == 'custom') {
			jQuery('#header-background').fadeIn();
		}else{
			jQuery('#header-background').fadeOut();
		}
	 });
 
	var footer_bg_selected_radio = jQuery("input[name='keremiya_options[footer_background_type]']:checked").val();
	if (footer_bg_selected_radio == 'default') {	jQuery('#footer-background').hide();	}
	if (footer_bg_selected_radio == 'custom') {	jQuery('#footer-background').show(); }
	jQuery("input[name='keremiya_options[footer_background_type]']").change(function(){
		var footer_bg_selected_radio = jQuery("input[name='keremiya_options[footer_background_type]']:checked").val();
		if (footer_bg_selected_radio == 'custom') {
			jQuery('#footer-background').fadeIn();
		}else{
			jQuery('#footer-background').fadeOut();
		}
	 });

	var logo_selected_radio = jQuery("input[name='keremiya_options[logo_setting]']:checked").val();
	if (logo_selected_radio == 'logo') {	jQuery('.title-setting').hide();	}
	if (logo_selected_radio == 'title') {	jQuery('.logo-setting').hide(); }
	jQuery("input[name='keremiya_options[logo_setting]']").change(function(){
		var logo_selected_radio = jQuery("input[name='keremiya_options[logo_setting]']:checked").val();
		if (logo_selected_radio == 'logo') {
			jQuery('.logo-setting').fadeIn();
			jQuery('.title-setting').hide();
		}else if (logo_selected_radio == 'title'){
			jQuery('.title-setting').fadeIn();
			jQuery('.logo-setting').hide();
		}
	 });	
			
		
	jQuery('a[rel=tooltip]').mouseover(function(e) {
		var tip = jQuery(this).attr('title');    
		jQuery(this).attr('title','');
		jQuery(this).append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + tip +'</div><div class="tipFooter"></div></div>');     
			 
		jQuery('#tooltip').css('top', e.pageY -10 );
		jQuery('#tooltip').css('left', e.pageX - 20 );
			 
		jQuery('#tooltip').fadeIn('500');
		jQuery('#tooltip').fadeTo('10',0.8);
					 
	}).mousemove(function(e) {
				 
		jQuery('#tooltip').css('top', e.pageY -10 );
		jQuery('#tooltip').css('left', e.pageX - 20 );
					 
	}).mouseout(function() {
				 
		jQuery(this).attr('title',jQuery('.tipBody').html());
		jQuery(this).children('div#tooltip').remove();
		 
	});
			

	jQuery(".tabs-wrap").hide();
	 if(window.location.hash) {
	      var hash = window.location.hash.substring(1);
		jQuery("."+hash+"").addClass("active").show();
		jQuery("#"+hash+"").show();
	  } else {
		jQuery(".keremiya-panel-tabs ul li:first").addClass("active").show();
		jQuery(".tabs-wrap:first").show();
	  }
	jQuery("li.tabs").click(function() {
		//jQuery(".keremiya-panel-tabs ul li").removeClass("active");
		jQuery("li.tabs").removeClass("active");
		jQuery(this).addClass("active");
		jQuery(".tabs-wrap").hide();
		var activeTab = jQuery(this).find("a").attr("href");
		jQuery(activeTab).fadeIn();
		return false;
	});

	jQuery(".sub-tabs-wrap").hide();
	jQuery("li.sub-tabs:first").addClass("active").show();
	jQuery(".sub-tabs-wrap:first").show(); 
	jQuery("li.sub-tabs").click(function() {
		//jQuery(".keremiya-panel-tabs ul li").removeClass("active");
		jQuery("li.sub-tabs").removeClass("active");
		jQuery(this).addClass("active");
		jQuery(".sub-tabs-wrap").hide();
		var activeTab = jQuery(this).find("a").attr("href");
		jQuery(activeTab).fadeIn();
		return false;
	});
	
	jQuery(".tabs-post-wrap").hide();
	jQuery(".keremiya-panel-post-tab li:first").addClass("active").show();
	jQuery(".tabs-post-wrap:first").show(); 
	jQuery("li.tab").click(function() {
		jQuery(".keremiya-panel-post-tab li").removeClass("active");
		jQuery(this).addClass("active");
		jQuery(".tabs-post-wrap").hide();
		var activeTab = jQuery(this).find("a").attr("href");
		jQuery(activeTab).fadeIn();
		return false;
	});
	
	jQuery("#theme-skins input:checked").parent().addClass("selected");
	jQuery("#theme-skins .checkbox-select").click(
		function(event) {
			event.preventDefault();
			jQuery("#theme-skins li").removeClass("selected");
			jQuery(this).parent().addClass("selected");
			jQuery(this).parent().find(":radio").attr("checked","checked");			 
		}
	);	
	
	
	jQuery("#theme-pattern input:checked").parent().addClass("selected");
	jQuery("#theme-pattern .checkbox-select").click(
		function(event) {
			event.preventDefault();
			jQuery("#theme-pattern li").removeClass("selected");
			jQuery(this).parent().addClass("selected");
			jQuery(this).parent().find(":radio").attr("checked","checked");			 
		}
	);	
		
	jQuery("#footer-widgets-options input:checked").parent().addClass("selected");
	jQuery("#footer-widgets-options .checkbox-select").click(
		function(event) {
			event.preventDefault();
			jQuery("#footer-widgets-options li").removeClass("selected");
			jQuery(this).parent().addClass("selected");
			jQuery(this).parent().find(":radio").attr("checked","checked");			 
		}
	);	

	
	jQuery("#home-layout-options input:checked").parent().addClass("selected");
	jQuery("#home-layout-options .checkbox-select").click(
		function(event) {
			event.preventDefault();
			jQuery("#home-layout-options li").removeClass("selected");
			jQuery(this).parent().addClass("selected");
			jQuery(this).parent().find(":radio").attr("checked","checked");			 
		}
	);	
	
	
	jQuery("#tabs_cats input:checked").parent().addClass("selected");
	jQuery("#tabs_cats span").click(
		function(event) {
			event.preventDefault();
			if( jQuery(this).parent().find(":checkbox").is(':checked') ){
				jQuery(this).parent().removeClass("selected");
				jQuery(this).parent().find(":checkbox").removeAttr("checked");			 
			}else{
				jQuery(this).parent().addClass("selected");
				jQuery(this).parent().find(":checkbox").attr("checked","checked");
			}				
		}
	);
	 

	 
	 
			
});
		
function toggleVisibility(id) {
	var e = document.getElementById(id);
    if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
}	
