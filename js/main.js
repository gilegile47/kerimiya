jQuery( document ).ready(function( $ ) {

	/*
	 * LOAD MORE
	*/
	var ldnaviRequest = false;
	$('.loadnavi').click(function() {

	var el = $(this);
	var pageNum = parseInt(el.attr('data-paged')) + 1;
	var max = parseInt(el.attr('data-max'));
	var nextLink = el.attr('href');

	var contentClass = 'keremiya-loadnavi-page-'+ pageNum +'';
	var postClassSelector = '.film-content .list_items';

    if(!ldnaviRequest) {
    ldnaviRequest = true;

		if(pageNum <= max) {

		var firstNum = pageNum;
		keremiya_modal_process(true, $('.loader', el));

			$('.'+contentClass).load(nextLink + ' ' + postClassSelector,
				function() {
					
					$('.current', el).text(pageNum);
					el.attr('data-paged', pageNum);

					pageNum++;
					nextLink = nextLink.replace(/\/page\/[0-9]*/, '/page/'+ pageNum);
					
					el.attr('href', nextLink);
					
					$('.loadnavi')
						.before('<div class="keremiya-loadnavi-page-'+ pageNum +'"></div>');

					keremiya_modal_process(false, $('.loader', el));
					ldnaviRequest = false;

					if(firstNum == max) {
						$(el).fadeOut();
					}
				}
			);
		} else {
			$(el).fadeOut();
		}
	}
		
		return false;
	});

	/* 
	* MORE
	*/
	var showChar = 315;
	var ellipsestext = "...";
	var moretext = kL10n.more;
	var lesstext = kL10n.less;
	var excerptdiv = ".excerpt";
	$('.more').each(function() {
		var content = $(this).html();

		if(content.length > showChar) {

			var c = content.substr(0, showChar);
			var h = content.substr(showChar, content.length - showChar);

			var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';

			$(this).html(html);
		}

	});

	$(".morelink").click(function(){
		if($(this).hasClass("less")) {
			$(this).removeClass("less");
			$(excerptdiv).removeClass("less");
			$(this).html(moretext);
		} else {
			$(this).addClass("less");
			$(excerptdiv).addClass("less");
			$(this).html(lesstext);
		}
		$(this).parent().prev().toggle();
		$(this).prev().toggle();
		return false;
	});


 // process the form
    var mdformRequest = false;
    $('.modal-form form').submit(function(e) {
    	e.preventDefault();
  		
  		var el = $(this);
    	var success = checkform(this);
    	var ss = false;
	
    	if( success ) {
    		if(!mdformRequest) {
    		mdformRequest = true;
    		keremiya_modal_process(true, el);

			var data = {
				action : "keremiya_user_action",
				form: el.serialize()
			};

			$.ajax({
				type: "post",
				url: kL10n.ajax_url,
				data: data,
					success: function(r){
						if(r == 1) {  
							keremiya_direct( $('#redirect-url', el).val() );
							ss = true;
						} else {
							$('.display-message', el).fadeIn();
							$('.display-message', el).html(r);
						}
					},
					complete: function() {
						if( ! ss ) {
							mdformRequest = false;
							keremiya_modal_process( false, el );
							keremiya_pop_fix('.modal-inner');
						}
					}
				});
			}
    	}

    });

    var ppformRequest = false;
    $('.popup form').submit(function(e) {
    	e.preventDefault();
  		
  		var el = $(this);
    	var success = checkform(this);

    	if( success ) {
    		if(!ppformRequest) {
    		ppformRequest = true;
    		keremiya_modal_process(true, el);

			var data = {
				action : "keremiya_report",
				form: el.serialize()
			};

			$.ajax({
				type: "post",
				url: kL10n.ajax_url,
				data: data,
				dataType: "json",
					success: function(e){
						if(e['error']) {
							ppformRequest = false;
							keremiya_show_message( e['title'], e['content'], e['footer'] );
						} else {
							el.append('<div class="success">'+e['html']+'</div>');
						}
					},
					complete: function() {
						//ppformRequest = false;
						keremiya_modal_process( false, el );
					}
				});
			}
    	}

    });

    $('#commentform').submit(function(e) {

  		var el = $(this);
    	var success = checkform(this);

    	if( success ) {
    		e.stopPropagation();
    	} else {
    		e.preventDefault();
    	}

    });

    function keremiya_modal_process( is, id ) {
    	
    	var inner = $(id).parent();

    	if(!is) {
    		$(inner).removeClass('process').removeClass('noselect');
    		//$(''+inner+' input').prop( "disabled", false ); //Disable
    	} else {
        	$(inner).addClass('process').addClass('noselect');
    		//$(''+inner+' input').prop( "disabled", true ); //Disable		
    	}
    }


    /*
     * COMMENT LIKE
     */
	$(".comment-vote").on('click', function(){
		keremiya_add_spin($(this));
		keremiya_comment_vote( $(this).attr('data-id'), $(this).attr('data-type'), $(this) );
	});

	function keremiya_comment_vote( postid, what, c ){
		var cookie = getCookie("cvote_"+postid);
		var data = {
			action : "keremiya_comment_vote",
			id: postid,
			w: what,
			c: cookie
		};

		$.post(kL10n.ajax_url, data, function(sonuc) {

			if(sonuc == 1) {
				var number = $('.count', c);
				var text = $(number).text();
				text++;
				$(number).text(text);
				$(c).addClass("active");

			setCookie("cvote_"+postid, postid, 360);
			} else {
				var split = sonuc.split('|');
				keremiya_show_message( split[0], split[1], split[2] );
			}
			keremiya_remove_spin($(c));
		});
	}

	/*
	 * USER UPDATE
	 */
	var usformRequest = false;
	$('#update-user').submit(function(e) {
    	e.preventDefault();
  		
  		var el = $(this);

    	if(!usformRequest) {
    		usformRequest = true;
    		keremiya_modal_process(true, el);

			var data = {
				action : "keremiya_update_user",
				form: el.serialize()
			};

			$.ajax({
				type: "post",
				url: kL10n.ajax_url,
				data: data,
					success: function(e){
						$(".upd").html(e);
					},
					complete: function() {
						usformRequest = false;
						keremiya_modal_process( false, el );
					}
			});
		}
    });

	/*
	 * STICKY HEADER
	*/
	var delay = (function(){
	  var timer = 0;
	  return function(callback, ms){
	    clearTimeout (timer);
	    timer = setTimeout(callback, ms);
	  };
	})();

	$(window).scroll(function() {
	
	if ($(this).scrollTop() > 115){
		if( window.innerWidth > 959 ) {
		    $('#header').addClass("sticky");
		   	delay(function(){
		   		$('#header').addClass("animate");
		    }, 10 );
		}
	    //$('#navbar').addClass("sticky");
	}

	if ($(this).scrollTop() < 115){
	    delay(function(){
	    	$('#header').removeClass("animate");
	    	delay(function(){
	    		$('#header').removeClass("sticky");
	    		
	    		if( window.innerWidth > 959 ) {
		    		$('#navbar').removeClass("sticky");
		    		$('.menu-toogle').removeClass("active");
		    	}
	    	}, 5 );
	    }, 1 );

	    //$('#navbar').removeClass("sticky");
	  }
	});

	/*
	 * KEREMIYA SHARE
	*/
	$(".keremiya-share").on('click', function(){
		keremiya_share( $(this).attr('data-type') );
	});

	function keremiya_share( type ) {
		var url = '',
			w = 480,
			h = 380,
			x = (screen.width / 2) - (w / 2),
			y = (screen.height / 2) - (h / 2),
			u = encodeURIComponent( location.href ),
			t = encodeURIComponent( document.title ),
			opts = 'toolbar=0,status=0' + ',width=' + w + ',height=' + h + ',top=' + y + ',left=' + x;

			if(type == 'fb') {
				url = 'http://www.facebook.com/sharer.php?u='+u+'&t='+t;
			}
			if(type == 'tw') {
				url = 'http://twitter.com/share?url='+u+'&text='+t;
			}
			if(type == 'gp') {
				url = 'https://plus.google.com/share?url='+u;
			}
			window.open(url, 'sharer', opts);
			return false;
	}

	/*
	 * CATEGORY ARG DIRECTS
	*/
	$(".arg").on('click', function(e){
		keremiya_direct( $('.remove', this).data('url') );
	});

	function keremiya_direct(e) {
		if(e) {
			window.location.href = e;
		}
	}

	/*
	 * NOTE REMOVE
	*/
	$("#note .remove").click(function(){
		$(this).parent().slideUp("fast");
	});

	/*
	 * ARCHIVE MOBILE ICONS
	*/
	$(".archive-icons").click(function(){
		var sidebar = ".c-sidebar",
			filmcon = ".film-content";

		$(this).toggleClass('change');
		$(sidebar).toggleClass('show');
		$(filmcon).toggleClass('hide');
	});

	/*
	 * WIDE POPUP TOOGLE
	*/

	$(".wide-button").on('click', function(){
		$('.single-content.video').toggleClass('wide-popup');
		$('body').toggleClass('wide-popup');
	});

	/*
	 * NAV MENU TOOGLE
	*/

	$(".menu-toogle").on('click', function(event){
		$(this).toggleClass('active');
		$('#navbar').toggleClass('sticky');
		event.stopPropagation();
	});

	$(".search-toogle").on('click', function(event){
		$(this).toggleClass('active');
		$('.header-search').toggleClass('active');
		$('#s').focus();
		event.stopPropagation();
	});

	/*
	 * USER MENU TOOGLE
	*/

	$(".user-my-account").live('click', function(event){
		$(this).toggleClass('active');
		keremiya_remove_spin($(this).find('span'));
		event.stopPropagation();
	});

	$(document).click(function() {
		$('.dropdown').removeClass('active');
	});

	/**
	* WATCHLIST
	*/
	function keremiya_add_spin( icon ) {
		icon.addClass('icon-spinner1').addClass('animate-spin');
	}

	function keremiya_remove_spin( icon ) {
		icon.removeClass('icon-spinner1').removeClass('animate-spin');
	}

	/**
	*	ADD TO
	*/
	var addtoRequest = false;
	$('.addto').live("click", function(){
	if(!addtoRequest) {
		addtoRequest = true;

		var addto = $(this),
			is = addto.data('is');

		if(is == 2) {
			var t = addto.parent().parent().parent().attr('data-this'),
				id = addto.attr('data-id'),
				icon = addto;
		} else {
			var t = addto.attr('data-this'),
				id = addto.attr('data-id'),
				icon = addto.find('span');
		}

		keremiya_add_spin( icon );

		jQuery.ajax({
			type: "post",
			url: kL10n.ajax_url,
			data: "action=keremiya_addto&this="+t+"&nonce="+kL10n.nonce+"&post_id="+id,
			dataType: "json",
				success: function(e){
					if(e['error']) {
						keremiya_show_message( e['title'], e['content'], e['footer'] );
					} else {
						if(is == 1) {
							addto.addClass('active');
							addto.removeClass('addto');
							icon.removeClass('icon-plus');
							icon.addClass('icon-ok');
						} else if(is == 2) {
							addto.parent().parent().hide();
						} else {
							addto.html(e['html']);
						}
					}
				},
				complete: function() {
					addtoRequest = false;
					keremiya_remove_spin( icon );
				}
		});
	}
		return false;
	});

	function keremiya_show_message( title, content, footer ) {
		var s = {
			modal:  '.modal',
			bg: 	'.modal-bg',
			head: 	'.modal-header',
			inner: 	'.modal-inner',
			form: 	'.modal-form',
		}

		var m = {
			id: 		'.modal-message',
			header: 	'.message-header',
			content: 	'.message-content',
			footer: 	'.message-footer',
			close: 		'.message-close'
		}

		// All Popup Hide
		$(s.modal).hide();
		$(s.head).hide();
		$(s.form).hide();

		// Popup Active
		$(s.modal).show();

		// Popup Deactive
		$(''+s.bg+', '+m.close+'').click(function() {
			$(s.modal).hide();
		});	

		$(m.id).show();
		$(m.header).html( title );
		$(m.content).html( content );
		$(m.footer).html( footer );

		$('.show-modal').live("click", function(){
			keremiya_show_popup( this );
			return false;
		});

		// Fix Popup
		keremiya_pop_fix(s.inner);
	}

	/**
	*	LOGIN AND REGISTER POPUPS
	*/
	$('.comment-reply-login').on("click", function(){
		keremiya_show_popup( this, '#popup', '#login-form' );
		return false;
	});

	$('.show-modal').live("click", function(){
		keremiya_show_popup( this );
		
		return false;
	});

	function keremiya_show_popup( data, is, id ) {
		var s = {
			popup: 	!id ? $(data).attr('data-id') : id,
			is:  	!is ? $(data).attr('data-is') : is,
			modal:  '.modal',
			bg: 	'.modal-bg',
			inner: 	'.modal-inner',
			form: 	'.modal-form',
			message:'.modal-message',
			header: '.modal-header',
		}

		// All Popup Hide
		$(s.modal).hide();
		$(s.message).hide();
		$('body').addClass('hidden');

		// Duo Content
		if(s.is) {
			$(s.form).hide();
			$(s.is).show();
		}

		// Popup Active
		$(s.popup).show();
		$(s.header).show().html( $('.header-logo').html() );

		// Popup Deactive
		$(s.bg).click(function() {
			if(s.is) {
				$(s.is).hide();
			} else {
				$(s.popup).hide();
			}
			$('body').removeClass('hidden');
		});	
		$(s.header).click(function() {
			$(s.is).hide();
			$('body').removeClass('hidden');
			return false;
		});

		// Fix Popup
		keremiya_pop_fix(s.inner);
	}

	function keremiya_pop_fix( div ) {
		// Fix Popup
		var popHeight = $(div).height();	
		var mtop = popHeight/2;

		$(div).css('margin-top', '-'+mtop+'px');
	}
	
	/**
	* TRIGGER POPUPS
	*/

	$(".action").each(function(){
	
		var s = {
			d: null,
			f: false,
			e: false,
			b: 250,
			c: 100,
			a: 10,
			i: $(".trigger", this).outerHeight(true) + 18,
			g: $(".trigger", this),
			h: $(".popup", this),
		}

		$([s.g.get(0)]).toggle(function() {
			s.h.removeClass('m-hide');			
		}, function() {
			s.h.addClass('m-hide');
		});

		$([s.g.get(0), s.h.get(0)]).mouseover(function(){

			if(s.d)
				clearTimeout(s.d);

			if(s.e||s.f){
				return;
			}else{
				t = setTimeout(function(){
					t=null;
					s.e=true;
					s.h.css({display:"block", top:""+s.i+"px" }).animate({top:"-="+s.a+"px",opacity:1},s.b,"swing",function(){
						s.e=false;
						s.f=true;
					})
				},300)}

				return false;
			}).mouseout(function(){
				if(s.d)
					clearTimeout(s.d);
				clearTimeout(t);
				s.d=setTimeout(function(){
					s.d=null;
					s.h.animate({top:"-="+s.a+"px",opacity:0},s.b,"swing",function(){
						s.f=false;
						s.h.removeClass('m-hide m-active');
						s.h.css("display","none")
					})
				},s.c);
				return false;
			});
	});

	/**
	* ACTIVE PART NAME
	*/		
	$("#action-parts").each(function(){
		var active_name = $('.active .part-name', this).text(),
			active_part = $('.active-part', this);
		
		active_part.html(active_name);
	});

	/**
	* VIDEO TABS
	*/		

	$(".tabs").each(function(){

		$("li.tab", this).click(function() {
			$(".tabs li").removeClass("active");
			$(this).addClass("active");
			$(".wrap").hide();

			var activeTab = $(this).attr("data-id");
			$("#" + activeTab).fadeIn("fast");
			return false;
		});
	});

	/**
	* VIDEO RATINGS
	*/

	$(".stars").each(function(){
            var el = $(this),
            	your_vote = '.your-vote span',
                stars = $('a', el);
            
            stars.bind('mouseenter', function(e){
                // add tmp class when mouse enter
                $(this)
                    .addClass('tmp_fs')
                    .prevAll()
                    .addClass('tmp_fs');
                
                $(this).nextAll()
                    .addClass('tmp_es');
                    
            });
            
            stars.bind('mouseleave', function(e){
                // remove all tmp class when mouse leave
                $(this)
                    .removeClass('tmp_fs')
                    .prevAll()
                    .removeClass('tmp_fs');
                
                $(this).nextAll()
                    .removeClass('tmp_es');
            });

            var starsRequest = false;
            stars.bind('click', function(e){
           		var rate = $(this).text(),
		        	post_id = $(this).parent().attr("data-id"),
		        	nonce = $(this).parent().attr("data-nonce"),
		        	cookie = getCookie("post_rate_"+post_id);

		        if(!starsRequest) {
		        starsRequest = true;
           		stars.removeClass('fullStar');

                $(this)
                    .addClass('fullStar')
                    .prevAll()
                    .addClass('fullStar');

	            $(your_vote)
	                .html(rate);

	            keremiya_modal_process(true, '.vote');
			        // Ajax call
			        jQuery.ajax({
			            type: "post",
			            url: kL10n.ajax_url,
			            data: "action=keremiya_ratings&nonce="+nonce+"&rate="+rate+"&post_id="+post_id,
			            dataType: "json",
			            success: function(e){
							if(e['error']) {
								//starsRequest = false;
								stars.removeClass('fullStar');
								$(your_vote).html('');
								keremiya_show_message( e['title'], e['content'], e['footer'] );
							} else {
			            		$('.icon-star .average, .details .average').html(e['html']);
							   	el.append('<div class="success">'+e['message']+'</div>');
							   	delay(function(){
							   		$('.success', el).fadeOut('fast');
							    }, 2000 );


			            		var total = $('.vote .total').text();
				            	if(total) {
				            		total++;
				            		$('.vote .total').html(total);
				            		//setCookie("post_rate_"+post_id, rate, 360);
				            	} else {
				            		$('.details').html(e['message']);
				            	}
							}
			            },
			            complete: function() {
							starsRequest = false;
							keremiya_modal_process(false, '.vote');
						}
			        });
		        }
		        return false;
            });

	});

	/**
	* Footer Sticky Hide
	*/
	$('.footer-sticky .close').on("click", function(){
		$('.footer-sticky').hide();
		return false;
	});

	/**
	* Responive Menu
	*/
	if( window.innerWidth < 959 ) {
		$(".header-user").each(function(){
			var g = $(this).html();
			$('.navbar-content').prepend('<div class="menu-user">'+g+'</div>');
		});
	} else {
	/*
	 * TOOLTIP
	*/
	$('.tooltip-w').tipsy({live: true, gravity: 'w', opacity: 0.95});
	$('.tooltip').tipsy({live: true, gravity: 'n', opacity: 0.95});
	$('.tooltip-s').tipsy({live: true, gravity: 's', opacity: 0.95});
	$('.tooltip-sw').tipsy({live: true, gravity: 'sw', opacity: 0.95});
	$('.existing_item').tipsy({
		className: 'kepsy',
		kepsy: true,
	    fade: false,     // fade tooltips in/out?
	    html: true,     // is tooltip content HTML?
	    live: true,     // use live event support?
	    opacity: 0.95,    // opacity of tooltip
	    gravity: $.fn.tipsy.autoWE,
	    title: function(){
	    		//var title = $('.movie-title').html();
	    		//var content = $('.movie-specials').html();
	    		var html = $(this).find('.existing-details').html();
				return html;
			},  // attribute/callback containing tooltip text
		});
	}

	$( window ).resize(function() { // scroll event  
		if( window.innerWidth > 959 ) {
			$('.menu-user').hide();
			$('#navbar').removeClass("sticky");
			$('.menu-toogle').removeClass("active");
		} else {
			$('.menu-user').show();
		}
	});

	$('.c-sidebar').each(function(){
		var list_width = $(this).outerWidth();

		if(list_width < 225) {
			$(this).addClass('full');
		}
	});

	/**
	* ADD POST
	*/
	$(".select-type").click(function(){
		var t = $(this).attr('data-open');

		// Defaults
		$('.select-type').removeClass('active');
		$('.add-content form').hide();
		$('.add-content #message').hide();

		//Process
		$(this).addClass('active');
		$(t).slideDown(300, function() {
		    $('html, body').animate({
		    scrollTop: $(this).offset().top - 80
		    }, 200);
		});

     	return false;
	});

	$(".submit-content").click(function(){

		keremiya_add_spin( $(this) );
		
	});

	/**
	* HTML EDITOR
	*/
	function formatText(tag, end, textarea) {
	   var Field = document.getElementById(textarea);
	   var val = Field.value;

	   var selected_txt = val.substring(Field.selectionStart, Field.selectionEnd);
	   var before_txt = val.substring(0, Field.selectionStart);
	   var after_txt = val.substring(Field.selectionEnd, val.length);

	   Field.value = before_txt + '[' + tag + ']' + selected_txt + '[/' + tag + ']' + after_txt;

	   Field.focus();
	}

	$(".KR-head button").click(function(){
		var t = $(this).attr('data-format'),
			r = $(this).parent().attr('data-area');

		formatText(t, t, r);
	});

   	$('.KR-textarea textarea').keyup(function() {
        //var text_length = $(this).val().length;
        var wordcount = countWords( $(this).val() );
        var parent = $(this).closest('.KR-editor');

        $('#textarea-feedback span', parent).html(wordcount);
    });

	function countWords(string){
            var counter = 1;

            // Change multiple spaces for one space
            string=string.replace(/[\s]+/gim, ' ');

            // Lets loop through the string and count the words
            string.replace(/(\s+)/g, function (a) {
               // For each word found increase the counter value by 1
               counter++;
            });

            if( string == 0 )
            	counter = 0;

            return counter;
	}

	/**
	* COOKIES
	*/
	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+d.toUTCString();
	    document.cookie = cname + "=" + cvalue + "; " + expires;
	}
	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0; i<ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1);
	        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
	    }
	    return "";
	}

	/**
	* CHECK FORMS
	*/
	function validateEmail(email) {
	    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email);
	}

	function checkform(data) {

		var error = '';
		var id = data.keremiya_action.value;

			if(id == 'register') {
				if(data.register_username.value == '')
					error = data.register_username;
				
				else if(!validateEmail(data.register_email.value))
					error = data.register_email;

				else if(data.register_email.value != data.register_re_email.value)
					error = data.register_re_email;

				else if(data.register_password.value == '')
					error = data.register_password;
			}

			if(id == 'login') {
				if(data.login_username.value == '')
					error = data.login_username;

				else if(data.login_password.value == '')
					error = data.login_password;
			}

			if(id == 'report') {
				if(!validateEmail(data.report_email.value))
					error = data.report_email;

				else if(data.report_excerpt.value == '')
					error = data.report_excerpt;
			}

			if(id == 'comment') {
				if(data.comment.value == '')
					error = data.comment;

				else if(data.author.value == '')
					error = data.author;

				else if(!validateEmail(data.email.value))
					error = data.email;
			}

			if( error ) {
				error.focus();
				return false;
			}

		return true;
	}

	/**
	* STICKY SIDEBAR
	*/
	var divs = {
		stickyID: "#sticky-sidebar",
		contentID: ".detail",
		footerID: "#footer",
		offtop: offtop ? offtop : 50,
	}

	if($(divs.stickyID).length > 0 && sticky_sidebar ) {
		var stickyTop = $(divs.stickyID).offset().top - divs.offtop; // returns number 

		$(window).scroll(function(){ // scroll event 
			if( window.innerWidth > 959 ) {
				keremiya_sticky( stickyTop, divs );
			}
		});
	}


	function keremiya_sticky(stickyTop, divs) {
		var windowTop = $(window).scrollTop(); // returns number

		if (stickyTop < windowTop) {
		    
		    var s = {
		    	contentHeight: 	$(divs.contentID).outerHeight(true),
		    	contentWidth: 	$(divs.contentID).outerWidth() + $(divs.contentID).offset().left,
		    	stickyHeight: 	$(divs.stickyID).outerHeight(true),
		    	stickyTop: 		$(divs.stickyID).offset().top,
		    	stickyWidth: 	$(divs.stickyID).outerWidth(),
		    	footerTop: 		$(divs.footerID).offset().top,
		    }

			if(s.stickyHeight < s.contentHeight ) {

			    $(divs.stickyID).css({ position: 'fixed', top: divs.offtop, left: $(divs.stickyID).offset().left, width: s.stickyWidth });

			    var limit = s.footerTop - s.stickyHeight - divs.offtop;
		        if (limit < windowTop) {
		          var diff = limit - windowTop + divs.offtop;
		          $(divs.stickyID).css({top: diff});
		        }
		    }

		} else {
			$(divs.stickyID).css({ position: 'static', top: '', left: '', width: '' });
		}
	}

});