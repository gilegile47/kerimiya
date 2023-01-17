<?php

function keremiya_custom_login_page_head() {
	// IF
	if( ! keremiya_get_option('dashboard_custom_css') )
		return;

	// Custom CSS
	$css = '
		body, html {
			background: #171717;
			color: #ccc;
		}
		a {
	    	color: #45afe2;
	    }
	    .login #backtoblog a:focus, .login #nav a:focus, .login h1 a:focus {
	    	color: #444;
	    	box-shadow: none;
	    }

		.login form {
		    background: #1f1e1e;
		}
		.login label {
			color: #999;
		}
		.login #backtoblog a, .login #nav a, .login h1 a {
			color: #777;
		}
		.login #backtoblog, .login #nav {
			color: #444;
		}

		input[type=text], input[type=search], input[type=radio], input[type=tel], input[type=time], input[type=url], input[type=week], input[type=password], input[type=checkbox], input[type=color], input[type=date], input[type=datetime], input[type=datetime-local], input[type=email], input[type=month], input[type=number], select, textarea {
		    padding: 3px 10px !important;
		    border: 0 !important;
		    border-radius: 3px !important;
		    background: #212121 !important;
		    background: linear-gradient(to bottom, #252525, #272727 100%) !important;
		    box-shadow: 0px 2px 1px rgba(0, 0, 0, 0.15) !important;
		    color: #fff !important;
		}
		.wp-core-ui .button-primary {
			background: #252525;
		    background: linear-gradient(to bottom, #262727, #252525 100%);
		    border: 0;
		    border-radius: 2px;
		    padding: 4px 14px;
		    color: #DEDEDE;
		    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.29), inset 0px 3px 2px rgba(0, 0, 0, 0.02);
		    cursor: pointer;
		    height: 36px;
		    outline: none;
		    text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.59);
		}
		.wp-core-ui .button-primary:hover {
	    	background: linear-gradient(to bottom, #262626, #3A3A3A 100%);
		}
		.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary:focus {
		    -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.24),0 0 2px 1px rgba(0, 0, 0, 0.3);
		    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.24),0 0 2px 1px rgba(0, 0, 0, 0.3);
		}
		.wp-core-ui .button-primary.active, .wp-core-ui .button-primary.active:focus, .wp-core-ui .button-primary.active:hover, .wp-core-ui .button-primary:active {
			-webkit-box-shadow: inset 0 2px 0 #444546;
			box-shadow: inset 0 2px 0 #444546;
			border-color: #1b1b1b;
		}
		.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover {
		    background: #3d3d3e;
		}

		input[type=radio], input[type=checkbox] {
		    padding: 2px 3px !important;
		    width: 19px;
		    height: 19px;
			border: 1px solid #464444;
	    	background: #2f2f2f;
	    	color: #ccc;
		}

		.login h1 a {
			height: auto;
			width: auto;
			text-indent: 0;
			font-size: 36px;
			color: #fff;
	    	background-size: initial;
	        background-image: none;
	        background-position: center center;
		}

		.login #login_error, .login .message {
			background-color: #1f1f1f;
		}
	';

	echo '<style type="text/css">'.$css.'</style>';
}
add_action('login_head',  'keremiya_custom_login_page_head'); 

?>