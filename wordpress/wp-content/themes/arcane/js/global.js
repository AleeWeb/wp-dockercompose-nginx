"use strict";
let $sw = jQuery.noConflict(),
    $document = $sw(document),
    $window = $sw(window);


function shopping_cart_dropdown() {
    'use strict';
    !$sw(".widget_shopping_cart .widget_shopping_cart_content .cart_list .empty").length && $sw(".widget_shopping_cart .widget_shopping_cart_content .cart_list").length > 0 && $sw(".cart-menu-wrap").addClass("has_products");
}
//----Unique team name start-----
function GetURLParameter(sParam) {
    //var sPageURL = window.location.search.substring(1);
    'use strict';
    let sPageURL = (window.location !== window.parent.location)
            ? document.referrer
            : document.location + '';
    let sURLVariables = sPageURL.split('&');
    for (let i = 0; i < sURLVariables.length; i++)
    {
        let sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] === sParam)
        {
            return sParameterName[1];
        }
    }
}


function shopping_cart_dropdown_show(t) {
    clearTimeout(timeout), !$sw(".widget_shopping_cart .widget_shopping_cart_content .cart_list .empty").length && $sw(".widget_shopping_cart .widget_shopping_cart_content .cart_list").length > 0 && "undefined" != typeof t.type && ($sw(".container .cart-menu-wrap").hasClass("has_products") ? $sw(".container .cart-notification").is(":visible") ? $sw(".container .cart-notification").show() : $sw(".container .cart-notification").fadeIn(400) : setTimeout(function() {
        $sw(".container .cart-notification").fadeIn(400)
    }, 400), timeout = setTimeout(hideCart, 2700))
}

function hideCart() {
    $sw(".container .cart-notification").stop(!0, !0).fadeOut()
}

function NotifyMe(t, e, sticky) {
    "use strict";
    let temptime = 5e3;
    if (sticky) temptime = false;

    noty({
        text: t,
        type: e,
        animation: {
            open: "animated fadeInUp",
            close: "animated fadeOutDown",
            easing: "swing",
            speed: 500
        },
        dismissQueue: !0,
        maxVisible: 10,
        timeout: temptime,
        layout: "topLeft"
    })
}


 $document.ready(function() {
    "use strict";
    let t = $sw("ul.sub-menu");
    t.parent().addClass("dropdown"), t.addClass("dropdown-menu");
    let e = $sw("dropdown-menu.children");
    e.parent().addClass("dropdown"), e.addClass("dropdown-menu");
    let r = $sw("dropdown-menu.children");
    r.parent().addClass("dropdown");
    let o = $sw(".menu ul");
    o.parent().addClass("dropdown");
    let i = $sw(".dropdown .dropdown-menu");
    i.removeClass("children");
    let n = $sw(".dropdown > a br");
    n.before('<b class="caret"></b>'), r.hover(function() {
        $sw(this).parent().addClass("active")
    }, function() {
        $sw(this).parent().removeClass("active")
    });
    t.hover(function() {
        $sw(this).parent().addClass("active")
    }, function() {
        $sw(this).parent().removeClass("active")
    });
    let a = $sw(".menu ul");

    a.addClass("nav"); e.removeClass("nav");
});

/**
 * Add menu toggle
 * @type {jQuery|HTMLElement}
 */
let navbarToogle = jQuery('.navbar-toggle'),
    navbarCollapse = jQuery('.navbar-collapse');

navbarToogle.on("click", function(){
    if(navbarCollapse.hasClass('in')){
        navbarCollapse.removeClass('in');
    }else{
        navbarCollapse.addClass('in');
    }
});



$document.ready(function() {
    "use strict";
    let t = $sw("#searchform #searchsubmit");
    t.remove();
    let c = $sw("#searchform input[type='text']");
    c.remove();
    let e = $sw("#searchform .screen-reader-text");
    e.remove();
    let r = $sw("#searchform div");
    r.append('<input type="hidden" placeholder="'+settingsGlobal.searchFor+'" value="post" name="post_type">');
    r.append('<input type="text" placeholder="'+settingsGlobal.searchFor+'" name="s" id="s">');
    let o = $sw("#searchform div h3");
    o.remove()
});




let timeout, productToAdd;
$sw(".product-wrap .add_to_cart_button").on('click', function(e) {
    productToAdd = $sw(this).parents("li").find("h3").text(), $sw(".container .cart-notification span.item-name").html(productToAdd)
}), $sw(".container .cart-notification").hover(function() {
    $sw(this).fadeOut(400), $sw(".container .widget_shopping_cart").stop(!0, !0).fadeIn(400), $sw(".container .cart_list").stop(!0, !0).fadeIn(400), clearTimeout(timeout)
}), $sw(".container div.cart-outer").hover(function() {
    $sw(".container .widget_shopping_cart").stop(!0, !0).fadeIn(400), $sw(".container .cart_list").stop(!0, !0).fadeIn(400), clearTimeout(timeout), $sw(".container .cart-notification").fadeOut(300)
}, function() {
    $sw(".container .widget_shopping_cart").stop(!0, !0).fadeOut(300), $sw(".container .cart_list").stop(!0, !0).fadeOut(300)
}), $sw("body").bind("added_to_cart", shopping_cart_dropdown_show), $sw("body").bind("added_to_cart", shopping_cart_dropdown), setTimeout(shopping_cart_dropdown, 550);


let mcontainer = $sw(".mcontainer script");
mcontainer.unwrap();

let bbip = $sw(".bbp-author-ip");
bbip.remove();


let ticker = $sw("#webTicker");
ticker.webTicker({
	speed: settingsGlobal.tickerspeed,
    height: '38px'
});


$document.ready(function() {
    "use strict";
    let t = $sw(".login-tooltip"),
        e = $sw(".login-btn"),
        r = $sw("#login_tooltip .closeto"),
        o = $sw(".navbar-wrapper");
    e.on('click', function(e) {
        t.fadeTo("fast", 1, function() {
            $sw(this).css("top", "50%"), o.css("z-index", "1000")
        })
    });
    r.on("click", function() {
        t.fadeTo("fast", 0, function() {
            $sw(this).css("top", "-5000px"), o.css("z-index", "99999999")
        })
    })
});


let tabs = jQuery('.tabs-wrap');
tabs.tabs();


$document.ready(function() {
    "use strict";
    //scroll to top
    let $back_to_top = $sw('#back-to-top');
    if ($back_to_top.length) {
        let $scrollTrigger = 100, // px
            backToTop = function () {
                "use strict";
                var $scrollTop = $sw(window).scrollTop();
                if ($scrollTop > $scrollTrigger) {
                    $sw('#back-to-top').addClass('show');
                } else {
                    $sw('#back-to-top').removeClass('show');
                }
            };
        backToTop();
        let $win = $sw(window),
            $html_body = $sw('html, body');
        $win.on('scroll', function () {
            backToTop();
        });
        $back_to_top.on('click', function (e) {
            "use strict";

            e.preventDefault();
            $html_body.animate({
                scrollTop: 0
            }, 800);
        });
    }
});



$document.ready(function() {
    "use strict";
    let t = $sw("#members-dir-search"),
        e = $sw("#subnav");
        e.before(t)
});

$document.ready(function() {
let editImage = $sw('.edit-attachment'),
    samaSlika = $sw('.js--select-attachment');

    samaSlika.on( "click", function() {
        editImage.remove();
    });
});



	/*all teams for selected game*/
	$document.ready(function($) {

	 	if(settingsGlobal.gamePage === 'yes'){
			let $members_search_submit = $sw('#members_search_submit');
			    $members_search_submit.attr('id', 'members_search_submit_games');

			let $members = $sw('#members');
			    $members.attr('id', 'members_games');
		}

        let membersGames =  $sw('#members_games'),
            membersSearchSubmit = $sw('#members_search_submit_games'),
            teamName = $sw('#team_name');

        membersSearchSubmit.on('click', function(event){
	        event.preventDefault();
            membersGames.load(settingsGlobal.ajaxurl, {
	            "action":"arcane_list_all_teams_for_selected_game_ajax",
				"href": '&page=1&term='+teamName.val(),
                'security' : settingsGlobal.security
	            });
	    });

        teamName.keypress(function (e) {
            let key = e.which;
            if(key === 13)  // the enter key code
            {
                e.preventDefault();
                $sw('#members_search_submit').click();
                return false;
            }
        });

        membersGames.on('click', '.pagination a', function(event){
	        event.preventDefault();
            membersGames.load(settingsGlobal.ajaxurl, {
	            "action":"arcane_list_all_teams_for_selected_game_ajax",
	            "href":$sw(this).attr('href'),
                'security' : settingsGlobal.security
	            });
	    });

	});

    let membersSearchSubmit = $sw('#members_search_submit'),
        members = $sw('#members');

    membersSearchSubmit.on('click', function(event){
        event.preventDefault();
        members.load(settingsGlobal.ajaxurl, {
            "action":"arcane_all_teams_pagination_v2_ajax",
			"href": '?page=1&term='+$sw('#team_name').val(),
            'security' : settingsGlobal.security
            });
    });

    members.on('click', '.pagination a', function(event){
        event.preventDefault();
        members.load(settingsGlobal.ajaxurl, {
            "action":"arcane_all_teams_pagination_v2_ajax",
            "href":$sw(this).attr('href'),
            'security' : settingsGlobal.security
            });
    });

    let tournamentSearchSubmit = $sw('#tournaments_search_submit'),
        tournamentMembers = $sw('#tournament_members');

    tournamentSearchSubmit.on('click', function(event){
        event.preventDefault();
        tournamentMembers.load(settingsGlobal.ajaxurl, {
            "action":"arcane_all_tournaments_pagination_v2_ajax",
			"href": '?page=1&term='+$sw('#tournament_name').val(),
            'security' : settingsGlobal.security
            });
    });

    let tournamentSearchForm = $sw('.all-tournaments-form');

    tournamentSearchForm.submit(function() {
        event.preventDefault();
        tournamentMembers.load(settingsGlobal.ajaxurl, {
            "action":"arcane_all_tournaments_pagination_v2_ajax",
            "href": '?page=1&term='+$sw('#tournament_name').val(),
            'security' : settingsGlobal.security
        });
    });

    tournamentMembers.on('click', '.pagination a', function(event){
        event.preventDefault();
            tournamentMembers.load(settingsGlobal.ajaxurl, {
            "action":"arcane_all_tournaments_pagination_v2_ajax",
            "href":$sw(this).attr('href'),
            'security' : settingsGlobal.security
            });
    });



/*change profile image in single team page*/
$document.ready(function($) {

      /*members pagination*/
    let bp = $sw('#buddypress');
    bp.on('click', '#pag-top.pagination a, #pag-bottom.pagination a', function(event){
        event.preventDefault();
        $sw('.single-team #buddypress').load(settingsGlobal.ajaxurl, {
            "action":"arcane_team_members_ajax",
            "page":$sw(this).attr('href'),
            "pid": settingsGlobal.postID,
            "uid": settingsGlobal.cid,
            'security' : settingsGlobal.security
            });
    });


	/*notify about challenge*/
	if(settingsGlobal.challengeSent === 'yes'){
		NotifyMe(settingsGlobal.challengeRequestSent, "information");
	}


	/*change forum page title*/

	if(settingsGlobal.bbHasForums === 'yes'){
		let forumtitle = $sw('.bbpress .title_wrapper h1');
		forumtitle.html(settingsGlobal.newforumtitle);
	}


	/*registration successful*/

	if(settingsGlobal.registrationSuccessful === 'yes')
		NotifyMe(settingsGlobal.registrationSuccessfulMsg, "information");

	/*sa single strane*/
	if(settingsGlobal.submitted === 'submitted'){

		NotifyMe(settingsGlobal.submittedString, "information");
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
        setTimeout(function(){ location.reload(); }, 500);
    }


	if(settingsGlobal.reported === 'reported')
		NotifyMe(settingsGlobal.reportedString, "information");


	if(settingsGlobal.alreadySubmitted === 'submittedalready')
		NotifyMe(settingsGlobal.alreadySubmittedString, "information");


		if(settingsGlobal.singularMatches === 'yes'){
			let data = settingsGlobal.encodedData;
			if(settingsGlobal.pid === 'no')
			    data = JSON.parse(data);


			if(data !== null && data !== '[]'){
		        $.each(data, function (i, item) {
		            let m = wpMatchManager.addMap(i, item.map_id);
		            for(let j = 0; j < item.team1.length; j++) {
		                m.addRound(item.team1[j], item.team2[j]);
		            }
                    let img = $('#mapsite .leftcol img'),
                        title = $('#mapsite .map .title span');
                        img.prop("src", item.map_pic);
                        title.text(item.map_title);
		        });
		    }
	   }

	/*clan challenge*/
	if(settingsGlobal.mid === 'yes'){

		 let chl = $('#wp-cw-submit'),
             title = $('#m_title');

         chl.bind('click', function(event) {
            let error = $sw('.ermaps');


	        if(error.length ===1)error.remove();

            if(!title.val()){
                 title.after('<label for="m_title" class="ermaps tit">'+settingsGlobal.errmsg+'</label>');
                 return false;
            }

            });

	}else{

		let  chl = $('#wp-cw-submit'),
             mapsite = $('#mapsite'),
             addmap = $('#wp-cw-addmap'),
             title = $('#m_title');

		chl.bind('click', function(event) {
        let addmaplabel = $('#wp-cw-addmap label'),
             titlelabel = $('.form-required .tit'),
             error = $('.ermaps'),
             greska = 0;

        if(error.length ===1)error.remove();
        if(mapsite.has('.map').length === 0){
            if(addmaplabel.length === 0){
            addmap.append('<label for="maps" class="ermaps">'+settingsGlobal.errmsg+'</label>');
        }
		 greska = 1;
        }else if(addmaplabel.length !== 0){
            addmap.find('.ermaps').remove();
        }


        if(title.val()) {
	        let inputs = $('.roundval');
	        inputs.val('0');
	        inputs.removeAttr('disabled');
        }else{
			  if(titlelabel.length === 0){
             	title.after('<label for="m_title" class="ermaps tit">'+settingsGlobal.errmsg+'</label>');
            }
             greska = 1;

        }

       /* if(!title.val() || mapsite.has('.map').length === 0){
         event.preventDefault();
        }
		zakomentarisano zbog submit score jer ne radi*/

		let game_dropdown = $('#game_id');

		if(game_dropdown.val() === ""){
			 event.preventDefault();
			 game_dropdown.after('<label for="maps" class="ermaps">'+settingsGlobal.errmsg+'</label>');
			  greska = 1;
		}

		if(settingsGlobal.singularMatches === 'no'){
			if(greska === 1) return false;
		}
        });

        addmap.bind('click', function() {
            let challenge_inputs = $('#mapsite .round input');
            challenge_inputs.prop('readonly', true);
            addmap.find('.ermaps').remove();

            let addround = $('.add-round');
            addround.bind('click', function() {

            let inputs = $('.roundval');
            inputs.val('0');
            inputs.prop('disabled', true);
            inputs.prop('readonly', true);
        });
        });



	}


	 $('#team1').change(function(){
        	var mapa = $('.map');
        	mapa.remove();
            $('#game_id').load(settingsGlobal.ajaxurl, {"action":"arcane_mutual_games", 'security' : settingsGlobal.security, "team1":$(this).val(), "team2":settingsGlobal.team2Id} );
        });

});



$document.ready(function($) {

	/*Mobile menu parent click with submenu fix*/
	if ($sw(window).width() < 960) {
	$sw('.menu-main-container .nav li a').on('click', function(e) {
	    if(!$sw(this).parent().hasClass('active')) {
	        $sw('.menu-main-container .nav li').removeClass('active');
	        $sw(this).parent().addClass('active');
	        e.preventDefault();
	    } else {
	        return true;
	    }
	});

	let logged_info = $sw(".logged-info a");
	    logged_info.addClass('prvi');


        $document.on('click touchstart', '.logged-info a', function(event) {
	    return !logged_info.hasClass('prvi');
	});


	}

});

$document.ready(function() {
	if(settingsGlobal.singularMatches === 'yes'){
	    "use strict";
	    var t = $sw(".add-round"),
	        e = $sw(".submit-score");
	    t.remove(), e.bind("click", function() {
	        var t = $sw(".remove");
	        t.remove()
	    })
   }
});



/**************** Preloader ***************/
$sw(window).load(function() {
	var preloader = $sw(".se-pre-con");
	preloader.fadeOut("slow");;
});


/**************** Failed login ***************/
$document.ready(function() {
	window.$_GET = new URLSearchParams(location.search);
	var value1 = $_GET.get('login');
	var value2 = $_GET.get('captcha');
	var value3 = $_GET.get('captchavalue');

	if( value2 == 'yes'){
		if(!value3)
		NotifyMe(settingsNoty.login_failed_captcha, "error",'','','',2000);
		if( value1 == 'failed')
		NotifyMe(settingsNoty.login_failed, "error",'','','',2000);
	}else if( value1 == 'failed')
		NotifyMe(settingsNoty.login_failed, "error",'','','',2000);
});



$sw('#LoginWithAjax_Form').submit(function() {
    if ($sw.trim($sw("#lwa_user_login").val()) === "" || $sw.trim($sw("#lwa_user_pass").val()) === "") {
        NotifyMe(settingsNoty.login_empty, "error");
        return false;
    }

});


/*force numeric values*/
$sw.fn.forceNumeric = function () {
     return this.each(function () {
         $sw(this).keydown(function (e) {
             let key = e.which || e.keyCode;

             return !e.shiftKey && !e.altKey && !e.ctrlKey &&
                 // numbers
                 key >= 48 && key <= 57 ||
                 // Numeric keypad
                 key >= 96 && key <= 105 ||
                 // comma, period and minus, . on keypad
                 key === 190 || key === 188 || key === 109 || key === 110 ||
                 // Backspace and Tab and Enter
                 key === 8 || key === 9 || key === 13 ||
                 // Home and End
                 key === 35 || key === 36 ||
                 // left and right arrows
                 key === 37 || key === 39 ||
                 // Del and Ins
                 key === 46 || key === 45;


         });
     });
 }

$document.ready(function () {
     let roundVal = $sw(".roundval"),
         wooCardTitle = $sw('.widget_shopping_cart h2.widgettitle');

     roundVal.forceNumeric();
     if(wooCardTitle.val() === '')
         wooCardTitle.remove();
});

/**Search functionality**/
$document.ready(function () {

    'use strict';


    let mainContainer = $sw('.container'),
        openCtrl = $sw('#btn-search'),
        closeCtrl = $sw('#btn-search-close'),
        searchContainer = $sw('.headsearch'),
        inputSearch = null,
        $main = $sw('#main_wrapper');

    if(searchContainer != null && searchContainer.length > 0){
        inputSearch = searchContainer.find('.search__input');
    }


    function init() {

        if(openCtrl != null && closeCtrl != null && searchContainer != null && inputSearch != null){
            initEvents();
        }
    }

    function initEvents() {
        openCtrl.on('click', openSearch);
        closeCtrl.on('click', closeSearch);
        $document.on('keyup', function(ev) {
            // escape key.
            if( ev.keyCode === 27 ) {
                closeSearch();
            }
        });
    }

    function openSearch() {
        mainContainer.addClass('main-wrap--move');
        searchContainer.addClass('search--open');
        $main.addClass('search-opn');
        setTimeout(function() {
            inputSearch.focus();
        }, 800);
    }

    function closeSearch() {
        mainContainer.removeClass('main-wrap--move');
        searchContainer.removeClass('search--open');
        $main.removeClass('search-opn');
        inputSearch.blur();
        inputSearch.value = '';
    }

    init();

});


//Disable round field
$document.ready(function() {
    let teamChallengeTmp = $sw('.page-template-tmp-team-challenge-php');

    teamChallengeTmp.on('click', ' #mapsite input[type="button"]', function (e) {
        let roundval = $sw('.roundval');
        roundval.val('0');
        roundval.prop( "disabled", true );
        roundval.prop( "readonly", true );
    });
});


let resendActivation = $sw("#resend_activation");
resendActivation.on("click", function(event){
    "use strict";
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: {"action": "arcane_resend_activation_link",'security' : settingsGlobal.security, "uid":jQuery(this).attr("data-uid"), "email":jQuery(this).attr("data-email")},
        success: function(data) {
            NotifyMe(settingsGlobal.activationLinkSent, "information");
            return false;
        }
    });
});




/**Upload user photo **/
let addUser = jQuery('#adduser');
addUser.find('#profilePhoto').change(function(e){
    let ajaxData = new FormData(),
        $input = jQuery(this),
        $progress = jQuery(".profilePhotoWrap .progress-bar"),
        $progressWrap = jQuery('.profilePhotoWrap #progress-wrp'),
        validImageTypes = ["image/jpg", "image/jpeg", "image/png"],
        $fakeInput = jQuery('.profilePhotoWrap .fake-input'),
        $uploadButton = jQuery('.profilePhotoWrap .upload-btn'),
        droppedFile = e.target.files[0];

    //disable fields
    $uploadButton.addClass('disabled');
    $input.prop('disabled', true);

    //null progress bar
    $progress.css("width", "0%");

    //set ajax data
    ajaxData.append( $input.attr('name'), droppedFile );
    ajaxData.append('action', 'arcane_user_logo');
    ajaxData.append('sec', settingsGlobal.security);

    //add file name
    $fakeInput.html(droppedFile.name);

    //check file type
    if (jQuery.inArray(droppedFile.type, validImageTypes) < 1) {
        $fakeInput.text(settingsGlobal.invalidType);
        $uploadButton.removeClass('disabled');
        $input.prop('disabled', false);
        return;
    }

    //ajax
    jQuery.ajax({
        url: settingsGlobal.ajaxurl,
        type: "POST",
        data: ajaxData,
        dataType: 'json',
        contentType: false,
        processData: false,
        xhr: function(){
            let xhr = new window.XMLHttpRequest();
            $progressWrap.show();
            // Upload progress
            xhr.upload.addEventListener("progress", function(evt){
                if (evt.lengthComputable) {
                    let percentComplete = evt.loaded / evt.total;
                    //Do something with upload progress
                    let percentText = Math.round(percentComplete * 100);
                    $progress.css('width', percentText + "%");
                    jQuery(".profilePhotoWrap .status").text(percentText +"%");

                }
            }, false);

            return xhr;
        },
        success: function(data) {
            let $img = jQuery('.profilePhoto.uploaded-files img');
            $img.attr("src", data);
            $img.css('display', 'block');

            jQuery('<input>').attr({
                type: 'hidden',
                name: 'profilePhoto',
                value: data
            }).appendTo('#adduser');

        },
        error: function(xhr, status, error) {
            console.log(error);console.log(status);
        }
    });
});



/**Upload user banner **/
addUser.find('#bannerPhoto').change(function(e){
    let ajaxData = new FormData(),
        $input = jQuery(this),
        $progress = jQuery(".bannerPhotoWrap .progress-bar"),
        $progressWrap = jQuery('.bannerPhotoWrap #progress-wrp'),
        validImageTypes = ["image/jpg", "image/jpeg", "image/png"],
        $fakeInput = jQuery('.bannerPhotoWrap .fake-input'),
        $uploadButton = jQuery('.bannerPhotoWrap .upload-btn'),
        droppedFile = e.target.files[0];

    //disable fields
    $uploadButton.addClass('disabled');
    $input.prop('disabled', true);

    //null progress bar
    $progress.css("width", "0%");

    //set ajax data
    ajaxData.append( $input.attr('name'), droppedFile );
    ajaxData.append('action', 'arcane_user_banner');
    ajaxData.append('sec', settingsGlobal.security);

    //add file name
    $fakeInput.html(droppedFile.name);

    //check file type
    if (jQuery.inArray(droppedFile.type, validImageTypes) < 1) {
        $fakeInput.text(settingsGlobal.invalidType);
        $uploadButton.removeClass('disabled');
        $input.prop('disabled', false);
        return;
    }

    //ajax
    jQuery.ajax({
        url: settingsGlobal.ajaxurl,
        type: "POST",
        data: ajaxData,
        dataType: 'json',
        contentType: false,
        processData: false,
        xhr: function(){
            let xhr = new window.XMLHttpRequest();
            $progressWrap.show();
            // Upload progress
            xhr.upload.addEventListener("progress", function(evt){
                if (evt.lengthComputable) {
                    let percentComplete = evt.loaded / evt.total;
                    //Do something with upload progress
                    let percentText = Math.round(percentComplete * 100);
                    $progress.css('width', percentText + "%");
                    jQuery(".bannerPhotoWrap .status").text(percentText +"%");

                }
            }, false);

            return xhr;
        },
        success: function(data) {
            let $img = jQuery('.bannerPhoto.uploaded-files img');
            $img.attr("src", data);
            $img.css('display', 'block');

            jQuery('<input>').attr({
                type: 'hidden',
                name: 'bannerPhoto',
                value: data
            }).appendTo('#adduser');

        },
        error: function(xhr, status, error) {
            console.log(error);console.log(status);
        }
    });
});

let tabLink = jQuery('.custom-tabs-link'),
    matchesWrapper = jQuery('.matches-wrapper'),
    matchestabList = jQuery('.matches-tab li');

tabLink.on("click", function(event){
    let href = jQuery(this).attr('href');

    if(href === '#all'){
        matchesWrapper.find('li').show();
        matchestabList.removeClass('active');
        jQuery(this).parent().addClass('active');
    }else{
        matchestabList.removeClass('active');
        jQuery(this).parent().addClass('active');
        matchesWrapper.find('li').hide();
        matchesWrapper.find('*[data-type="'+href+'"]').show();
    }
});


feather.replace();

/**
 * Tooltip
 */
$document.ready(function() {
    $document.tooltip({
        items: "[data-tooltip]",
        content: function () {
            return jQuery(this).data("tooltip");
        }
    });
});

/**
 * Carousel
 * @type {*|jQuery|HTMLElement}
 */
let tournamentCarouselListFirst = jQuery('.tournaments-list li:first-child');
tournamentCarouselListFirst.addClass('active');

let notificationsForm = jQuery('.notifications #settings-form');
notificationsForm.submit(function() {

    "use strict";
    jQuery.ajax({
        type: "POST",
        url: settingsGlobal.ajaxurl,
        data: {
            "action": "arcane_update_email_notifications",
            'security': settingsGlobal.security,
            "email_matches": jQuery('input[name=matches_notifications]:checked', jQuery(this)).val(),
            "email_tournaments": jQuery('input[name=tournament_notifications]:checked', jQuery(this)).val(),
            "email_team": jQuery('input[name=team_notifications]:checked', jQuery(this)).val(),
        },
        success: function(data) {

        }
    });
});

let standingsTable = jQuery('.standings-table');
standingsTable.sortable({
    items: 'tr:not(tr:first-child)',
    cursor: 'pointer',
    axis: 'y',
    dropOnEmpty: false,
    start: function (e, ui) {
        ui.item.addClass("selected");
    },
    stop: function (e, ui) {
        ui.item.removeClass("selected");
        let positions = {};
        jQuery(this).find("tr").each(function (index) {
            if (index > 0) {
                jQuery(this).find('td').eq(0).html(index);
                let teamId = jQuery(this).find('td').eq(1).find('a').attr('data-id');
                positions[teamId] = index;
            }
        });


        jQuery.ajax({
            type: "POST",
            url: settingsGlobal.ajaxurl,
            data: {
                "action": "arcane_update_br_positions",
                'security': settingsGlobal.security,
                'tid' : standingsTable.attr('data-tid'),
                "positions": positions,
            },
            success: function(data) {

            }
        });
    }
});

let editScore = jQuery(".edit-score");
editScore.each(function (e) {
    jQuery(this).editable("click", function(e){
        if(e.value !== e.old_value && jQuery.isNumeric( e.value )) {
            jQuery.ajax({
                type: "POST",
                url: settingsGlobal.ajaxurl,
                data: {
                    'action': "arcane_update_br_score",
                    'security': settingsGlobal.security,
                    'tid': jQuery(e.target).attr('data-tid'),
                    'score': e.value,
                    'uid': jQuery(e.target).attr('data-uid'),
                    'round': jQuery(e.target).attr('data-round'),
                },
                success: function (data) {

                }
            });
        }
    });
})


jQuery(document).ready(function() {
    let datep = jQuery('#datepicker_'+settingsGlobal.field_id);
    datep.datepicker({
        dateFormat: settingsGlobal.date_format,
        changeMonth: true,
        changeYear: true,
        yearRange:  "-100:+0",
        maxDate: '-1Y',
        minDate: '-70Y'
    });

    if(settingsGlobal.dodaj == 'true'){
        var queryDate = settingsGlobal.date_added;
        jQuery('#datepicker_'+settingsGlobal.field_id).datepicker({dateFormat: settingsGlobal.date_format});
        jQuery('#datepicker_'+settingsGlobal.field_id).datepicker('setDate', queryDate);
    }

    let birthday_filed = jQuery('#birthday_field');

    birthday_filed.datepicker({
        dateFormat: settingsGlobal.date_format,
        altFormat: 'yy-mm-dd',
        altField: '#alt-birthday-date',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        maxDate: 'now',
        minDate: '-70Y'
    });



});