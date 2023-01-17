jQuery( document ).ready(function( $ ) {
	// Delay
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		}
	})();

	// Request
	var searchRequest = false,
		enterActive = true;
		
	// Input Change Event
	$('#s').on("input", function() {
		var s = this.value;

		// 500 SS Delay
		delay(function(){

			// If input value
			if( s.length <= 2 ) {

				$(sL10n.area).hide();
				$(sL10n.button).find('span').removeClass('icon-spinner1').removeClass('animate-spin');

				return;
			}

		// Request
		if(!searchRequest) {
	    	// True
	    	searchRequest = true;

			// Loader
			$(sL10n.button).find('span').addClass('icon-spinner1').addClass('animate-spin');
			$(sL10n.area).find('ul').addClass('process').addClass('noselect');

			// GET AJAX
			$.ajax({
		      type:'GET',
		      url: sL10n.api,
		      data: 'keyword=' + s + '&nonce=' + sL10n.nonce,
		      dataType: "json",
		      success: function(data){
				if( data['error'] ) {
					$(sL10n.area).hide();
					return;
				}

				// Show Content
				$(sL10n.area).show();
					// More Text
					var res = '<span class="icon-search-1">' + s + '</span>',
						moreReplace = sL10n.more.replace('%s', res),
						moreText = '<a class="more" href="javascript:;" onclick="document.getElementById(\'search-form\').submit();">' + moreReplace + '</a>';
					// Items Array
					var items = [];

					$.each( data, function( key, val ) {
					  	name = '';
					  	date = '';
					  	imdb = '';

					  	if( val['extra']['date'] !== false )
					  		date = "<span class='release'>(" + val['extra']['date'] + ")</span>";

					  	if( val['extra']['names'] !== false )
					  		name = val['extra']['names'];

					  	if( val['extra']['imdb'] !== false )
					  		imdb = "<div class='imdb'><span class='icon-star'>IMDB: " + val['extra']['imdb'] + "</span></div>";

					   	items.push( "<li id='" + key + "'><a href='" + val['url'] + "' class='clearfix'><div class='poster'><img src='" + val['img'] + "' /></div><div class='title'>" + val['title'] + "</div><div class='other-name'>" + name + date + "</div>" + imdb + "</a></li>" );
					});

					// Add content
					$(sL10n.area).html('<ul>' + items.join( "" ) + moreText + '</ul>');
				},
				complete: function() {
					// False
			      	searchRequest = false;
			      	// Enter active
			      	enterActive = false;
			      	// Remove Loader
					$(sL10n.button).find('span').removeClass('icon-spinner1').removeClass('animate-spin');
					$(sL10n.area).find('ul').removeClass('process').removeClass('noselect');
				}
		   	});
		} // Search Request
		}, 500 ); // Delay
	}); // Input

	$(document).on("keypress", "#search-form", function(event) { 
		if( enterActive ) {
			return event.keyCode != 13;
		}
	});

	// Close Event
	$(document).click(function() {
		var target = $(event.target);

		if ($(event.target).closest("#s").length == 0) {
			$(sL10n.area).hide();
		} else {
			$(sL10n.area).show();
		}
	});
});