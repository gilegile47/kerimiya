jQuery( document ).ready(function( $ ) {
	$(".gallery").each(function(){
		var el = $(this);
		var numNodes = el.attr('data-max');

		$('.thumb', el).click(function() {
			var num = $(this).attr('data-num'),
				href = $(this).find('a').attr('href'),
				next = '',
				prev = '';

			$('#gallery').show();
			$('.gallery-header').html('<div class="navi">'+num+' / '+numNodes+'</div><span class="icon-cancel close-gallery"></span>');
			$('.gallery-content').html('<span class="icon-left-open prev" data-num="'+num+'"></span><span class="image"><img src="'+href+'" /></span><span data-num="'+num+'" class="icon-right-open next"></span>');

			return false;
		});

			$('.prev').live('click', function(event){
				var numNodes = $('.gallery').attr('data-max');
				var num = $(this).attr('data-num');

				if(1 == num) {
					prevNum = numNodes;
				} else {
					prevNum = parseInt(num)-1;
				}

				var dd = $('.gallery').find("[data-num='" + prevNum + "']");
				var href = dd.find('a').attr('href');
				
				$(this).attr('data-num', prevNum);
				$('.next').attr('data-num', prevNum);
				$('.navi').html(''+prevNum+' / '+numNodes+'');
				$('.gallery-content img').attr('src', '');
				$('.gallery-content img').attr('src', href);
			});

			$('.next').live('click', function(event){

				var numNodes = $('.gallery').attr('data-max');
				var num = $(this).attr('data-num');

				if(numNodes == num) {
					nextNum = 1;
				} else {
					nextNum = parseInt(num)+1;
				}

				var dd = $('.gallery').find("[data-num='" + nextNum + "']");
				var href = dd.find('a').attr('href');
				
				$(this).attr('data-num', nextNum);
				$('.prev').attr('data-num', nextNum);
				$('.navi').html(''+nextNum+' / '+numNodes+'');
				$('.gallery-content img').attr('src', '');
				$('.gallery-content img').attr('src', href);
			});


		$('.close-gallery, .gallery-bg').live('click', function(event){
			$('#gallery').hide();
		});

		$(window).scroll(function() {
			$('#gallery').hide();
		});
	});
});