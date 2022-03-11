jQuery(document).ready(function ($) {
    var data = arcaneAdminVars.scores;

    $.each(data, function (i, item) {
        var m = wpMatchManager.addMap(i, item.map_id);
        for(var j = 0; j < item.team1.length; j++) {
            m.addRound(item.team1[j], item.team2[j], item.round_id[j]);
        };
    });
    $('.matchgametype').change(function(e) {
       if ($(this).val() == 'user') {
            $('#teamselector').slideUp();
            $('#userselector').slideDown();
       } else {
            $('#teamselector').slideDown();
            $('#userselector').slideUp();
       }
    });

    var redux_logo = jQuery('.display_header h2');
    redux_logo.html('<a href="https://skywarriorthemes.com" target="_blank"><img alt="user_img" src="https://skywarriorthemes.com/arcane/wp-content/themes/arcane/img/logo.png"></a>');



});


/*matches*/
function GoSubmit(event) {
    if ( event.which === 13 ) {
        event.preventDefault();
        jQuery('#matches-search-submit').click();
    }
}
jQuery(document).ready(function() {
    jQuery('#matches-search-submit').on('click', function(e) {
        e.preventDefault();
        document.location.search = insertParam('s', jQuery("#matches-search-input").val());
    });
});
function insertParam(key, value) {
    key = escape(key); value = escape(value);

    var kvp = document.location.search.substr(1).split('&');
    if (kvp == '') {
        document.location.search = '?' + key + '=' + value;
    }
    else {

        var i = kvp.length; var x; while (i--) {
            x = kvp[i].split('=');

            if (x[0] == key) {
                x[1] = value;
                kvp[i] = x.join('=');
                break;
            }
        }

        if (i < 0) { kvp[kvp.length] = [key, value].join('='); }

        //this will reload the page, it's likely better to store this until finished
        //document.location.search
        var keep = kvp.join('&');
        return keep;
    }
}



jQuery(function($) {

	// the upload image button, saves the id and outputs a preview of the image
 $('.upload_image_button_admin').click(function(){
	var button = $(this);
	var myuploader = wp.media(
	{
		title : 'Select Image',
		button : {
			text : 'Insert',
		},
		multiple : false
	})

	.on('select', function()
	{
    attachment = myuploader.state().get('selection').first().toJSON();

			if($(button).hasClass("imgid")) {
                $(button).parent('.upload').find('input[type=text]').val(attachment.id);
			} else {
                $(button).parent('.upload').find('input[type=text]').val(attachment.url);
			}
            $(button).parent('.upload').find('img').attr('src', attachment.url);
            $(button).parent('.upload').find('img').show();

	})
	.open(button);

	return false;
});

	// the remove image link, removes the image id from the hidden field and replaces the image preview

	$('.remove_image_button_admin').click(function(){

			$(this).parent('.upload').find('img').attr('src', '');
            $(this).parent('.upload').find('img').hide();
			$(this).parent('.upload').find('input[type=text]').val('');

		return false;
	});


});