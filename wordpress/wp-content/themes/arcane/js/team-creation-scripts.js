"use strict";

/**Create team**/
let $createTeam = jQuery('#createTeam');
$createTeam.submit(function( event ) {
    event.preventDefault();

    jQuery(":submit", this).text(settingsTeam.pleaseWait);

    let ajaxData = new FormData(),
        formParams = $createTeam.serializeArray();

    jQuery.each(formParams, function(i, val) {
        ajaxData.append(val.name, val.value);
    });

    let games = jQuery('.tc-games-team li.active'),
        gamesLi = jQuery('.tc-games-team li'),
        gamesArray = [];

    gamesLi.removeClass('error');

    if(games.length === 0){
        gamesLi.addClass('error');
        jQuery("html, body").animate({ scrollTop: jQuery("#createTeam").offset().top }, "slow");
        jQuery(":submit", this).text(settingsTeam.create);

        return false;
    }

    games.each(function (index, value) {
        gamesArray.push(jQuery(this).attr('data-id'));
    });

    let about = tinymce.activeEditor.getContent();

    ajaxData.append('action', 'arcane_create_team');
    ajaxData.append('sec', settingsTeam.security);
    ajaxData.append('games', gamesArray);
    ajaxData.append('about',  about);

    jQuery.ajax({
        url: settingsTeam.ajaxurl,
        type: "POST",
        data: ajaxData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(data) {

            if(data.trim() === 'notunique'){
                jQuery("html, body").animate({ scrollTop: jQuery("#createTeam").offset().top }, "slow");
                jQuery('button[type="submit"]').text(settingsTeam.create);
                jQuery(".error-name").show();
            }else {
                window.location.href = data;
            }

        },
        error: function(xhr, status, error) {
            console.log(error);console.log(status);
        }
    });

});

/**Update team**/
let $updateTeam = jQuery('#updateTeam');
$updateTeam.submit(function( event ) {
    event.preventDefault();

    jQuery(":submit", this).text(settingsTeam.pleaseWait);

    let ajaxData = new FormData(),
        formParams = $updateTeam.serializeArray();

    jQuery.each(formParams, function(i, val) {
        ajaxData.append(val.name, val.value);
    });

    let games = jQuery('.tc-games-team li.active'),
        gamesArray = [];

    games.each(function (index, value) {
        gamesArray.push(jQuery(this).attr('data-id'));
    });

    let about = tinymce.activeEditor.getContent();

    ajaxData.append('action', 'arcane_update_team');
    ajaxData.append('pid',  jQuery(this).attr("data-pid"));
    ajaxData.append('sec', settingsTeam.security);
    ajaxData.append('about',  about);
    ajaxData.append('games', gamesArray);



    jQuery.ajax({
        url: settingsTeam.ajaxurl,
        type: "POST",
        data: ajaxData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(data) {

            window.location.href = data;

        },
        error: function(xhr, status, error) {
            console.log(error);console.log(status);
        }
    });

});

/**Delete team**/
let $deleteTeam = jQuery('#deleteTeam');
$deleteTeam.on( "click", function(event) {
    event.preventDefault();

    jQuery(this).text(settingsTeam.pleaseWait).attr('disabled', 'disabled');

    let ajaxData = new FormData();

    ajaxData.append('action', 'arcane_delete_team');
    ajaxData.append('pid',  jQuery(this).attr("data-pid"));
    ajaxData.append('sec', settingsTeam.security);

    jQuery.ajax({
        url: settingsTeam.ajaxurl,
        type: "POST",
        data: ajaxData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(data) {

            window.location.href = data;

        },
        error: function(xhr, status, error) {
            console.log(error);console.log(status);
        }
    });

});

/**Upload team logo **/
jQuery('#createTeam, #updateTeam').find('#teamLogo').change(function(e){
    let ajaxData = new FormData(),
        $input = jQuery(this),
        $progress = jQuery(".teamLogoWrap .progress-bar"),
        $progressWrap = jQuery('.teamLogoWrap #progress-wrp'),
        validImageTypes = ["image/jpg", "image/jpeg", "image/png"],
        $fakeInput = jQuery('.teamLogoWrap .fake-input'),
        $uploadButton = jQuery('.teamLogoWrap .upload-btn'),
        droppedFile = e.target.files[0];

    //disable fields
    $uploadButton.addClass('disabled');
    $input.prop('disabled', true);

    //null progress bar
    $progress.css("width", "0%");

    //set ajax data
    ajaxData.append( $input.attr('name'), droppedFile );
    ajaxData.append('action', 'arcane_team_logo');
    ajaxData.append('sec', settingsTeam.security);

    //add file name
    $fakeInput.html(droppedFile.name);

    //check file type
    if (jQuery.inArray(droppedFile.type, validImageTypes) < 1) {
        $fakeInput.text(settingsTeam.invalidType);
        $uploadButton.removeClass('disabled');
        $input.prop('disabled', false);
        return;
    }

    //ajax
    jQuery.ajax({
        url: settingsTeam.ajaxurl,
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
                    jQuery(".teamLogoWrap .status").text(percentText +"%");

                }
            }, false);

            return xhr;
        },
        success: function(data) {
            let $img = jQuery('.teamLogo.uploaded-files img');
            $img.attr("src", data);
            $img.css('display', 'block');

            jQuery('<input>').attr({
                type: 'hidden',
                name: 'teamLogo',
                value: data
            }).appendTo('#createTeam, #updateTeam');

        },
        error: function(xhr, status, error) {
            console.log(error);console.log(status);
        }
    });
});


/**Upload team banner **/
jQuery('#createTeam, #updateTeam').find('#teamBanner').change(function(e){
    let ajaxData = new FormData(),
        $input = jQuery(this),
        $progress = jQuery(".teamBannerWrap .progress-bar"),
        $progressWrap = jQuery('.teamBannerWrap #progress-wrp'),
        validImageTypes = ["image/jpg", "image/jpeg", "image/png"],
        $fakeInput = jQuery('.teamBannerWrap .fake-input'),
        $uploadButton = jQuery('.teamBannerWrap .upload-btn'),
        droppedFile = e.target.files[0];

    //disable fields
    $uploadButton.addClass('disabled');
    $input.prop('disabled', true);

    //null progress bar
    $progress.css("width", "0%");

    //set ajax data
    ajaxData.append( $input.attr('name'), droppedFile );
    ajaxData.append('action', 'arcane_team_banner');
    ajaxData.append('sec', settingsTeam.security);

    //add file name
    $fakeInput.html(droppedFile.name);

    //check file type
    if (jQuery.inArray(droppedFile.type, validImageTypes) < 1) {
        $fakeInput.text(settingsTeam.invalidType);
        $uploadButton.removeClass('disabled');
        $input.prop('disabled', false);
        return;
    }

    //ajax
    jQuery.ajax({
        url: settingsTeam.ajaxurl,
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
                    jQuery(".teamBannerWrap .status").text(percentText +"%");

                }
            }, false);

            return xhr;
        },
        success: function(data) {
            let $img = jQuery('.teamBanner.uploaded-files img');
            $img.attr("src", data);
            $img.css('display', 'block');

            jQuery('<input>').attr({
                type: 'hidden',
                name: 'teamBanner',
                value: data
            }).appendTo('#createTeam, #updateTeam');

        },
        error: function(xhr, status, error) {
            console.log(error);console.log(status);
        }
    });
});


let datep = jQuery('#teamFounded');
datep.flatpickr();

let gameTeam = jQuery('.game-team');
gameTeam.on('click', function(e) {
    e.preventDefault();

    gameTeam.removeClass('error');

    if(jQuery(this).hasClass('active')){
        jQuery(this).removeClass('active');
    }else{
        jQuery(this).addClass('active');
    }
});