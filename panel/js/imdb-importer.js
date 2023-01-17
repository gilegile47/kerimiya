jQuery("#imdb-id").bind('paste', function(e) {

	var el = jQuery(this);
	var pastedData = e.originalEvent.clipboardData.getData('text');

	var data = {
		action : "keremiya_imdb_importer",
		id: pastedData,
	};

	jQuery("#imdb-id-item").addClass('imdb-loader');

	jQuery.post(ajaxurl, data, function(s) {
		
		jQuery("#imdb-id-item").removeClass('imdb-loader');
		var obj = JSON.parse(s);

		if( obj.response ) {
			for (i = 0; i < obj.count; i++) {
				if( obj[i].id == 'category' ) {
					var cLen = obj[i].text.length;
					for (c = 0; c < cLen; c++) {
						check('in-category-'+obj[i].text[c]);
					}
				}
				else if( obj[i].id == 'resim' ) {
					if( obj[i].text ) {
						if( jQuery('#postimagediv .inside img').length>0 ) {
							//jQuery('#postimagediv .inside img').attr('src', obj[i].text);
						} else {
							//jQuery('#postimagediv .inside').append('<img src="'+ obj[i].text +'" />');
							loadImage(obj[i].text, '#postimagediv .inside');
						}
						jQuery('#' + obj[i].id + '-item input').val( obj[i].text );
					}
				}
				else if( obj[i].id == 'oyuncular' ) {
					if( obj[i].text != '' ) {
						jQuery('#' + obj[i].id + '-item input, #' + obj[i].id + '-item textarea').val( obj[i].text );
						jQuery(oyuncular).val( obj[i].text );
					}
				}
				else if( obj[i].id == 'yonetmen' ) {
					if( obj[i].text != '' ) {
						jQuery('#' + obj[i].id + '-item input, #' + obj[i].id + '-item textarea').val( obj[i].text );
						jQuery(yonetmen).val( obj[i].text );
					}
				}
				else if( obj[i].id == 'yapim' ) {
					if( obj[i].text != '' ) {
						jQuery('#' + obj[i].id + '-item input, #' + obj[i].id + '-item textarea').val( obj[i].text );
						jQuery(yapim).val( obj[i].text );
					}
				}
				else {
					if( obj[i].text != '' ) {
						jQuery('#' + obj[i].id + '-item input, #' + obj[i].id + '-item textarea').val( obj[i].text );
						jQuery('#tax-input-' + obj[i].id + '').html( obj[i].text );
					}
				}
			}
		}

	});

});

function check(id) {
    document.getElementById(id).checked = true;
}

function uncheck(id) {
    document.getElementById(id).checked = false;
}

function loadImage(path, target) {
    jQuery('<img src="'+ path +'">').load(function() {
      jQuery(this).appendTo(target);
    });
}