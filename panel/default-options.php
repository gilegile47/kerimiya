<?php
// Theme Name
define ('theme_name', 'Keremiya v5' );
// Theme Version
define ('theme_ver' , "5.6" );

// Data
$default_data = array(
	"keremiya_options"	=> array(
		'pagenavi' 					=> 		'loadnavi',
		'logo_setting' 				=> 		'title',
		'on_home' 					=> 		'latest',
		'columns'					=> 		'7',
		'movies_number' 			=> 		'14',
		'category_columns'			=> 		'5',
		'category_movies_number' 	=> 		'10',
		'home_news_number' 			=> 		'5',
		'home_layout' 				=> 		'film',
		'category_layout' 			=> 		'film',
		'header_social'				=> 		true,
		'header_fixed'				=> 		true,
		'sticky_sidebar'			=> 		true,
		'footer_widgets' 			=> 		'default',
		'background_type' 			=> 		'default',
		'header_background_type' 	=> 		'default',
		'footer_background_type' 	=> 		'default',
		'author_base'				=>		_k_('profil'),
		'author_social'				=> 		true,
		'seo_active' 				=> 		true,
		'og_active'					=>		true,
		'canonical_url_active' 		=> 		true,
		'category_seo_active' 		=> 		true,
		'title_format' 				=> 		true,
		'page_title_format' 		=> 		'{page_title} | {blog_title}',
		'category_title_format' 	=> 		'{category_title} | {blog_title}',
		'tag_title_format' 			=> 		'{tag_title} | {blog_title}',
		'search_title_format' 		=> 		'{search_title} | {blog_title}',
		'archive_title_format' 		=> 		'{archive_title} | {blog_title}',
		'author_title_format' 		=> 		'{author_title} | {blog_title}',
		'seo_auto_description' 		=> 		true,
		'similar' 					=> 		true,
		'similar_number' 			=> 		'6',
		'vote'						=>		"user",
		'comment_like'				=>		"user",
		'live_search'				=>		true,
		'general_player'			=>		'jwplayer',
		'jw_player'					=>		jw_player_encode_html(),
		'theme_skin'				=>		'modern',
		'theme_width' 				=> 		'1064',
		'theme_padding' 			=> 		'20',
		'new_part_system' 			=> 		true,
		'addto' 					=> 		true,
		'share' 					=> 		true,
		'report'					=> 		true,
		'wide' 						=> 		true,
		'videoarea' 				=> 		'autosize',
		'banner_before_video_time' 	=> 		'10',
		'lang' 						=> 		'tr_TR',
		'excerpt_hide' 				=> 		'hide',
		'showmore' 					=> 		true,
		'category_list_lang'		=>		true,
		'category_list_year'		=>		true,
		'add_post'					=>		true,
		'post_status'				=>		'pending',
		'imdb_importer'				=>		true,
		'auto_thumbnail'			=>		true,
		'admin_bar'					=>		true,
		'today_movie_on'			=>		true,
		'news_on'					=>		true,
		'slider_number'				=>		'14',
		'slider_style'				=>		'1',
		'tax_columns'				=> 		'5',
		'tax_movies_number' 		=> 		'10',
		'css_minify'				=>		true,
		'js_minify'					=>		true,
		'tabs_linktype'				=>		'hideshow',
		'banner_splash_time' 		=> 		'10',
		'dashboard_custom_css'		=>		true,
	)
);

function jw_player_encode_html() {
return '&lt;script type=&#39;text/javascript&#39; src=&#39;http://p.jwpcdn.com/6/12/jwplayer.js?ver=4.1.1&#39;&gt;&lt;/script&gt;
&lt;script type=&quot;text/javascript&quot;&gt;jwplayer.defaults = { &quot;ph&quot;: 2 };&lt;/script&gt;
            &lt;script type=&quot;text/javascript&quot;&gt;
            if (typeof(jwp6AddLoadEvent) == &#39;undefined&#39;) {
                function jwp6AddLoadEvent(func) {
                    var oldonload = window.onload;
                    if (typeof window.onload != &#39;function&#39;) {
                        window.onload = func;
                    } else {
                        window.onload = function() {
                            if (oldonload) {
                                oldonload();
                            }
                            func();
                        }
                    }
                }
            }
            &lt;/script&gt;
&lt;div class=&#39;jwplayer&#39; id=&#39;jwplayer-max&#39;&gt;&lt;/div&gt;
&lt;script type=&#39;text/javascript&#39;&gt;if(typeof(jQuery)==&quot;function&quot;){(function($){$.fn.fitVids=function(){}})(jQuery)};
jwplayer(&#39;jwplayer-max&#39;).setup({
&quot;file&quot;: &quot;%URL%&quot;,
&quot;image&quot;: &quot;%IMAGE%&quot;,
&quot;autostart&quot;: &quot;%AUTOPLAY%&quot;
});
&lt;/script&gt;';
}

function v5_theme_url() {
	$lang = get_theme_notifier_lang();

	if( $lang == 'en' )
		return "http://en.keremiya.com/product/keremiya-v5/";

	return "https://www.keremiya.com/urun/keremiya-v5/";
}

?>