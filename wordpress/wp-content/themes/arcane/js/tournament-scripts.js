"use strict";

let addPrize = jQuery("#add_prize"),
    removePrize = jQuery("#remove_prize"),
    loader = settingsTournaments.loader,
    tid = jQuery('.tournament-header').attr('data-tid');

addPrize.on('click', function(e) {

    let prizesTable = jQuery('#prizes_table tr'),
        lngth = prizesTable.length,
        KeepThis = jQuery(this),
        TempText = jQuery(this).html()

    lngth = lngth === 2 ? 0 : lngth - 2;


    jQuery(this).html('');
    jQuery(this).html('<img alt="img" src='+loader+' />');
    jQuery.post( settingsTournaments.ajaxurl , { action: "get_next_prize_template", currentprize: lngth }, function (data) {
        //get prize template
        KeepThis.html('');
        KeepThis.html(TempText);
        jQuery('#prizes_table tr:last').before(data);
    });

});


removePrize.on('click', function(e) {

    let prizesTable = jQuery('#prizes_table tr'),
        lngth = prizesTable.length;

    lngth = lngth === 2 ? 0 : lngth - 2;

    if (lngth > 0) {
        jQuery('#prizes_table tr:last').prev().slideUp("fast", function() {
            jQuery('#prizes_table tr:last').prev().remove();
        });
    }

});

let game = jQuery('.game'),
    gamesWrap = jQuery('.tc-games'),
    tcForm = jQuery('.tc-forms'),
    mapsWrap = jQuery('.tbmaps');

game.on('click', function(e) {
    e.preventDefault();

    game.removeClass('active');
    jQuery(this).addClass('active');

    gamesWrap.addClass('tc-games-hide');
    gamesWrap.removeClass('tc-games-show');

    tcForm.addClass('tc-form-show');
    tcForm.removeClass('tc-form-hide');

    let gameId = jQuery(this).attr('data-id');

    jQuery.ajax({
        type: "POST",
        url: settingsTournaments.ajaxurl,
        dataType:"html",
        data: {"action": "arcane_tournament_return_maps", 'security' : settingsTournaments.security, gid: gameId },
        success: function(data) {
            let wrap = jQuery('.tbmaps');
            wrap.html(data);

            if(settingsTournaments.editMode) {
                setTimeout(function(){
                    let maps =  jQuery.makeArray(settingsTournaments.maps);
                    jQuery(maps).each(function( index, val ) {
                        mapsWrap.find("li[data-id='" + val + "']").click();
                    });
                }, 2000);

            }
        }
    });

});

let differentGame = jQuery('.different-game');
differentGame.on('click', function(e) {
    gamesWrap.removeClass('tc-games-hide');
    gamesWrap.addClass('tc-games-show');
    tcForm.removeClass('tc-form-show');
    tcForm.addClass('tc-form-hide');
});

let publishTournament = jQuery('.publish_tournament'),
    tournamentForm = jQuery('#tournament_create');

publishTournament.on('click', function(e) {

    let proceed = true;

    let title = jQuery('input[name="tournament_title"]');
    if(!title.val()){
        NotifyMe(settingsTournaments.tournamentNameEmpty, "error",'','','',2000);
        proceed = false;
    }

    if(!tinyMCE.get('tournament_desc').getContent()){
        NotifyMe(settingsTournaments.tournamentDescEmpty, "error",'','','',2000);
        proceed = false;
    }

    let startDate = jQuery('input[name="tournament_start_date"]');
    if(!startDate.val()){
        NotifyMe(settingsTournaments.tournamentDateEmpty, "error",'','','',2000);
        proceed = false;
    }

    let timezone = jQuery('select[name="tournament_timezone"]');
    if(!timezone.val()){
        NotifyMe(settingsTournaments.tournamentZoneEmpty, "error",'','','',2000);
        proceed = false;
    }

    let location = jQuery('input[name="tournament_location"]');
    if(!location.val()){
        NotifyMe(settingsTournaments.tournamentLocationEmpty, "error",'','','',2000);
        proceed = false;
    }

    let format = jQuery('select[name="tournament_format"]');
    if(!format.val()){
        NotifyMe(settingsTournaments.tournamentFormatEmpty, "error",'','','',2000);
        proceed = false;
    }

    let participants = jQuery('input[name="tournament_participants"]');
    if(!participants.val()){
        NotifyMe(settingsTournaments.tournamentParticipantsNumEmpty, "error",'','','',2000);
        proceed = false;
    }

    if(participants.val() < 3){
        NotifyMe(settingsTournaments.tournamentParticipantsLess, "error",'','','',2000);
        proceed = false;
    }

    let platform = jQuery('select[name="tournament_platform"]');
    if(!platform.val()){
        NotifyMe(settingsTournaments.tournamentPlatformEmpty, "error",'','','',2000);
        proceed = false;
    }

    let contestants = jQuery('select[name="tournament_contestants"]');
    if(!contestants.val()){
        NotifyMe(settingsTournaments.tournamentParticipantsEmpty, "error",'','','',2000);
        proceed = false;
    }

    let gameFormat = jQuery('select[name="tournament_games_format"]');
    if(!gameFormat.val()){
        NotifyMe(settingsTournaments.tournamentGameFormatEmpty, "error",'','','',2000);
        proceed = false;
    }

    let gameFrequency = jQuery('select[name="tournament_game_frequency"]');
    if(!gameFrequency.val()){
        NotifyMe(settingsTournaments.tournamentGameFrequencyEmpty, "error",'','','',2000);
        proceed = false;
    }

    let tournamentFrequency = jQuery('select[name="tournament_frequency"]');
    if(!tournamentFrequency.val()){
        NotifyMe(settingsTournaments.tournamentFrequencyEmpty, "error",'','','',2000);
        proceed = false;
    }

    let maps = jQuery('.tbmaps li.selected');
    if(!maps){
        NotifyMe(settingsTournaments.tournamentMapsEmpty, "error",'','','',2000);
        proceed = false;
    }

    if(proceed) {

        jQuery(this).html('');
        jQuery(this).html('<img alt="img" src='+loader+' />');

        let ajaxData = new FormData(),
            formParams = tournamentForm.serializeArray();

        jQuery.each(formParams, function (i, val) {
            ajaxData.append(val.name, val.value);
        });

        let gamesWrap = jQuery('ul.tc-games'),
            mapsArray = [];

        maps.each(function (index, value) {
            mapsArray.push(jQuery(this).attr('data-id'));
        });

        ajaxData.append('action', 'arcane_update_tournament');
        ajaxData.append('security', settingsTournaments.security);
        ajaxData.append('game_id', gamesWrap.find('li.active').attr('data-id'));
        ajaxData.append('game_name', gamesWrap.find('li.active').attr('data-name'));
        ajaxData.append('maps', mapsArray);
        ajaxData.append('status', jQuery(this).attr('data-action'));
        ajaxData.append('desc', tinyMCE.get('tournament_desc').getContent());
        ajaxData.append('regulations', tinyMCE.get('tournament_regulations').getContent());


        jQuery.ajax({
            type: "POST",
            url: settingsTournaments.ajaxurl,
            data: ajaxData,
            contentType: false,
            processData: false,
            success: function (data) {
                let tempdata = data.split('-_-');
                if (tempdata[0] === "ok") {
                    window.location.href =  tempdata[1];
                }
            }
        });
    }

});



/**Team creation datepicker**/
let datpicker = jQuery(".datepicker");
datpicker.flatpickr({
    enableTime : true,
    minDate: "today"
});

/**Map selector**/
mapsWrap.on('click', '.map_selector', function(e) {

    if(jQuery(this).hasClass('selected')){
        jQuery(this).removeClass('selected');
    }else{
        jQuery(this).addClass('selected');
    }

});


let showMore = jQuery('#show_more');
showMore.on('click', function(e) {
    let $collapse = jQuery('#collapseOne'),
        $this =jQuery(this);

    if($collapse.hasClass('in')){
        $this.text(settingsTournaments.tournaments_more);
    } else {
        $this.text(settingsTournaments.tournaments_hide);
    }
});


/** Countdown**/
jQuery(document).ready(function($) {

    if(settingsTournaments.isSingle) {
        // Set the date we're counting down to
        let countDownDate = settingsTournaments.tournamentStartsUnix;

        // Update the count down every 1 second
        let x = setInterval(function () {

            // Get today's date and time
            let now = moment().tz(settingsTournaments.tournamentTimezone).format('X'),

                // Find the distance between now and the count down date
                distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            let days = Math.floor(distance / (60 * 60 * 24)),
                hours = Math.floor((distance % (60 * 60 * 24)) / (60 * 60)),
                minutes = Math.floor((distance % (60 * 60)) / (60)),
                seconds = Math.floor((distance % (60)));

            // Output the result in an element with id="demo"
            if (document.getElementById('tournament_countdown') && (days > 0 || hours > 0 || minutes > 0 || seconds > 0)) {
                document.getElementById("tournament_countdown").innerHTML = days + " " + settingsTournaments.days + " " + hours +
                    " " + settingsTournaments.h + " " + minutes + " " + settingsTournaments.m + " " + seconds + " " + settingsTournaments.s;
            }

        }, 1000);
    }

});


/** Join/Leave tournament **/
let joinTournament = jQuery('#join_tournament_single'),
    joinTournamentB = jQuery('.jtournamentb'),
    leaveTournament = jQuery('#leave_tournament'),
    leaveTournamentBlock = jQuery('.leave_tournament'),
    rejectUser = jQuery('.rejects'),
    joinTeamModal = jQuery('.single-tournament .members-list .join_team');

//Leave
leaveTournament.on('click', function(e) {
    jQuery(this).html('');
    jQuery(this).html('<img alt="loader" src='+loader+' />');
    NotifyMe(settingsTournaments.leavingTournament, "information");
    jQuery.post( settingsTournaments.ajaxurl , { action: "leave_tournament", tid: tid }, function (data) {
        NotifyMe(settingsTournaments.tournamentLeft, "error");
        location.reload();
    });
});

joinTeamModal.on('click', function(e) {
    NotifyMe(settingsTournaments.joiningTournament, "information");
    jQuery.post( ajaxurl ,
        { action: "join_tournament",
            tid: settingsTournaments.tID,
            pid:jQuery(this).data('team_id') }, function (data) {
            location.reload();
        });
});

leaveTournamentBlock.on('click', function(e) {
    jQuery(this).html('');
    jQuery(this).html('<img alt="loader" src='+loader+' />');
    NotifyMe(settingsTournaments.leavingTournament, "information");
    jQuery.post( settingsTournaments.ajaxurl , { action: "leave_tournament", tid: $sw(this).attr('data-tid') }, function (data) {
        NotifyMe(settingsTournaments.tournamentLeft, "error");
        location.reload();
    });
});

//Join

if(settingsTournaments.tourContestants === 'user'){
    joinTournament.one('click', function(e) {
        jQuery(this).html('');
        jQuery(this).html('<img alt="loader" src='+loader+' />');
        jQuery.post( settingsTournaments.ajaxurl , { action: "join_tournament", tid: $sw(this).attr('data-tid'), pid: $sw(this).attr('data-cid') }, function (data) {
            NotifyMe(settingsTournaments.tournamentJoined, "success");
            location.reload();
        });
    });
}else if((parseInt(settingsTournaments.countTeams) === 1) && (settingsTournaments.foundGameId !== false) && (settingsTournaments.inarray)){
    joinTournamentB.one('click', function(e) {
        e.preventDefault();
        NotifyMe(settingsTournaments.joiningTournament, "information");
        jQuery(this).html('');
        jQuery(this).html('<img alt="loader" src='+settingsTournaments.loader+' />');
        jQuery.post( settingsTournaments.ajaxurl , { action: "join_tournament", tid: tid, pid: settingsTournaments.teamsArray }, function (data) {
            location.reload();
        });
    });
}else{
    joinTournamentB.one('click', function(e) {
        arcane_filter_teams(jQuery(this).data("gid"));
    });

}
//
//
// rejectUser.on('click', function(e) {
//     let ub = jQuery(this).closest('li'),
//         uid = ub.data('team_id');
//
//     ub.html('<img alt="loeader" src='+loader+' />');
//     NotifyMe(settingsTournaments.rejected, "information");
//
//     jQuery.post( settingsTournaments.ajaxurl , { action: "rejects_user", tid: tid, uid: uid }, function (data) {
//         ub.remove();
//     });
// });


jQuery(document).ready(function ($) {
    let CTournament = 0;
    let listJoin = jQuery('.tlist-join');
    if(!settingsTournaments.tournamentPage){
        let lastRunJoin = null;
        let lastRunJoined = null;
        listJoin.on('click', '.join_tournament', function(e) {
            e.preventDefault();
            CTournament = jQuery(this).data('tid');
            jQuery(this).html('');
            jQuery(this).html('<img alt="loader" src='+loader+' />');
            let KeepThis = jQuery(this);

            if (jQuery(this).data('ttype') === 'team') {
                jQuery('.join_team').on('click', function(e) {

                    if (lastRunJoin === null || new Date().getTime() - lastRunJoin > 500) {
                        NotifyMe(settingsTournaments.joiningTournament, "information");
                    }
                    lastRunJoin = new Date().getTime();

                    jQuery.post( settingsTournaments.ajaxurl , { action: "join_tournament", tid: CTournament, pid:jQuery(this).data('team_id') }, function (data) {

                        KeepThis.fadeOut('slow', function() {
                            if (lastRunJoined === null || new Date().getTime() - lastRunJoined > 500) {
                                NotifyMe(settingsTournaments.tournamentJoined, "success");
                            }
                            lastRunJoined = new Date().getTime();

                            KeepThis.html(settingsTournaments.leave);

                            KeepThis.removeClass('join_tournament');
                            KeepThis.addClass('leave_tournament');
                            KeepThis.fadeIn('slow');
                        });
                    });
                });
                if (arcane_filter_teams(jQuery(this).data('gid'))) {
                    let teamChooserModalFooter = jQuery('#TeamChooserModalFooter');
                    teamChooserModalFooter.dialog({
                        autoOpen: false,
                        show: {
                            effect: "blind",
                            duration: 500
                        },
                    });

                    teamChooserModalFooter.dialog( "open" );
                    jQuery(this).html(settingsTournaments.join);
                }
            } else {
                NotifyMe(settingsTournaments.joiningTournament, "information");
                jQuery(this).html('');
                jQuery(this).html('<img alt="loader" src='+loader+' />');

                jQuery.post( settingsTournaments.ajaxurl , { action: "join_tournament", tid: CTournament, pid: settingsTournaments.cId }, function (data) {
                    KeepThis.fadeOut('slow', function() {
                        NotifyMe(settingsTournaments.tournamentJoined, "success");
                        KeepThis.html(settingsTournaments.leave);
                        KeepThis.removeClass('join_tournament');
                        KeepThis.addClass('leave_tournament');
                        KeepThis.fadeIn('slow');
                    });
                });
            }


        });

        listJoin.on('click', '.leave_tournament',function() {
            NotifyMe(settingsTournaments.leavingTournament, "information");
            jQuery(this).html('');
            jQuery(this).html('<img alt="loader"  src='+loader+' />');
            let KeepThis = jQuery(this);
            jQuery.post( settingsTournaments.ajaxurl , { action: "leave_tournament", tid: jQuery(this).data('tid'), pid: settingsTournaments.cId }, function (data) {
                KeepThis.fadeOut('slow', function() {
                    NotifyMe(settingsTournaments.tournamentLeft, "error");
                    KeepThis.html(settingsTournaments.join);
                    KeepThis.removeClass('leave_tournament');
                    KeepThis.addClass('join_tournament');
                    KeepThis.fadeIn('slow');
                });
            });
        });
    }

});


/**Accept/Reject user in tournament**/
let candidateListing = jQuery('.candidate_listing'),
    user_confirm = jQuery('.u_confirm');

user_confirm.one('click', function(e) {

    let ub = jQuery(this).closest('li'),
        ubin = ub.clone(),
        uid = ub.data('team_id'),
        con = jQuery(this).data('con');

    jQuery(this).closest('li').addClass('acc_loader');
    ub.html('<img alt="loader" src='+loader+' />');

    jQuery.ajax({
        type: "POST",
        url: settingsTournaments.ajaxurl,
        dataType:"json",
        data: {
            action: "confirm_user",
            tid: tid,
            uid: uid,
            con: con
        },
        success: function(data) {
            if(data === "accepted"){
                NotifyMe(settingsTournaments.userAccepted, "information");
                ubin.appendTo(jQuery(".competitor-list")).removeClass('candidate_listing').addClass('competitor_listing').find('.confirmation').remove();
            }else if(data === "rejected"){
                NotifyMe(settingsTournaments.userRejected, "information");
            }
            candidateListing.removeClass('acc_loader');
            ub.remove();
            if(candidateListing.length === 0) {
                location.reload();
            }
        }
    });

});

/**Kick user from tournament**/
let userKick = jQuery('.u_kick');
userKick.one('click', function(e) {

    let ub = jQuery(this).closest('li'),
        uid = ub.data('team_id'),
        con = jQuery(this).data('con');

    ub.html('<img alt="loader" class="kick_load" src='+loader+' />');

    jQuery.ajax({
        type: "POST",
        url: settingsTournaments.ajaxurl,
        dataType:"json",
        data: {
            action: "kick_user",
            tid: tid,
            uid: uid,
            con: con
        },
        success: function(data) {
            if(data === "accepted"){
                NotifyMe(settingsTournaments.userKicked, "information");
                ub.remove();
                location.reload();
            }
        }
    });

});


/**User profile tournament tabs**/
jQuery( function() {
    let tabs = jQuery('.profile-tournaments-selector');
    tabs.tabs();
} );


if(settingsTournaments.editMode){
    gamesWrap.find("li[data-id='"+settingsTournaments.gameId+"']").click();
}


/**Filter teams**/
function arcane_filter_teams(gid) {
    let leftteams = 0,
        testz = gid.toString(),
        holderz = '',
        joinTeam = jQuery('.join_team');


    joinTeam.each(function (i) {
        var test = jQuery(this).data('games').indexOf(testz);
        if (test < 0) {
            jQuery(this).fadeOut('fast');
        } else {
            jQuery(this).fadeIn('fast');
            leftteams ++;
            holderz = this;
        }

    });
    if (leftteams === 1) {
            jQuery(holderz).click();
        return false;
    } else if (leftteams === 0) {
        //tusi
        NotifyMe(settingsTournaments.noTeamsPlay, "error");
        return false;
    } else {
        return true;
    }
}

/**Premium*/
let removePremium = jQuery("#remove_premium"),
    makePremium = jQuery("#make_premium"),
    premiumWrap = jQuery(".register-form-wrapper");

makePremium.on("click", function(event){
    jQuery.ajax({
        type: "POST",
        url: settingsTournaments.ajaxurl,
        dataType:"json",
        data: {"action": "arcane_add_to_premium", 'security' : settingsTournaments.security, tid: settingsTournaments.data_pid },
        success: function(data) {
            NotifyMe(settingsTournaments.tournamentIsPremium, "information");
            makePremium.remove();
            premiumWrap.prepend("<a id='remove_premium' class='btn'>"+settingsTournaments.removePremium+"</a>");
            return false;
        }
    });
});


removePremium.on("click", function(event){
    jQuery.ajax({
        type: "POST",
        url: settingsTournaments.ajaxurl,
        dataType:"json",
        data: {"action": "arcane_remove_premium", 'security' : settingsTournaments.security, tid: settingsTournaments.data_pid },
        success: function(data) {
            NotifyMe(settingsTournaments.removedPremium, "information");
            removePremium.remove();
            premiumWrap.prepend("<a id='make_premium' class='button-small'>"+settingsTournaments.make_premium+"</a>");
            return false;
        }
    });
});

let restartTournament = jQuery('.restart_tournament');
restartTournament.on('click', function(e) {
    e.preventDefault();
    NotifyMe(settingsTournaments.restarting, "information");
    jQuery.post( settingsTournaments.ajaxurl , { action: "arcane_ajax_restart_tournament", data: settingsTournaments.restartData}, function (data) {

        let tempdata = data.split('-_-');
        if (tempdata[0] === "ok") {
            window.location.href =  tempdata[1];
        }
    });
});