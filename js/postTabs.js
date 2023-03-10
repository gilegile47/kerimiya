
jQuery(document).ready(function() {
    jQuery('.postTabs_divs').fadeOut('fast');
    jQuery('.postTabs_curr_div').fadeIn('fast');
    jQuery('.postTabsLinks').each(function() {
        jQuery(this).click(function() {
            //alert(jQuery(this).attr('id'));
            var info = jQuery(this).attr('id').split('_');
            postTabs_show(info[1], info[0]);
        });
    });
    
    jQuery('.postTabs_count').html( jQuery('#postTabs_count').attr('data-count') );

    cookie_name = 'postTabs_' + postTabs.post_ID;
    
    if (postTabs.use_cookie && postTabs_getCookie(cookie_name)) {
        postTabs_show(postTabs_getCookie(cookie_name), postTabs.post_ID);
    }
});

function postTabs_show(tab,post){
		
		jQuery('.postTabs_divs').each(function() {
            jQuery(this).fadeOut('fast');
        });
        jQuery('#postTabs_ul_'+post + ' li').each(function() {
            jQuery(this).removeClass('postTabs_curr');
        });
        jQuery('#postTabs_li_'+tab+'_'+post).addClass('postTabs_curr');
		jQuery("#postTabs_"+tab+"_"+post).fadeIn('fast');
		self.focus();

		//Cookies
		var expire = new Date();
		var today = new Date();
		expire.setTime(today.getTime() + 3600000*24);
		document.cookie = "postTabs_"+post+"="+tab+";expires="+expire.toGMTString();

}

function posTabsShowLinks(tab){
	if (tab) window.status=tab;
	else window.status="";
}

function postTabs_getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
