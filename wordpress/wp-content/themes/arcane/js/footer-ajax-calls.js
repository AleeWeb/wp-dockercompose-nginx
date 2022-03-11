jQuery(document).ready(function ($) {


    let submitBrModal =  $('#modalSubmitBR'),
        brModalLink = $('.br-modal');

    submitBrModal.dialog({
        autoOpen: false,
        show: {
            effect: "blind",
            duration: 500
        },
    });

    brModalLink.on( "click", function() {
        let teamName = $(this).parent().parent().find('.team-link').text(),
            teamID = $(this).parent().parent().find('.team-link').attr('data-id'),
            round = $(this).parent().parent().find('.team-link').attr('data-round'),
            modalHeader = $('#modalSubmitBR .modal-header h3 span'),
            modalBody = $('#modalSubmitBR .modal-body form');

        modalHeader.empty();
        modalHeader.append(teamName);
        modalBody.find('input[name="team-id"]').remove();
        modalBody.find('input[name="round"]').remove();
        modalBody.append('<input type="hidden" name="team-id" value="'+teamID+'" />');
        modalBody.append('<input type="hidden" name="round" value="'+round+'" />');

        submitBrModal.dialog( "open" );
    });



    let brForm = jQuery('#br-submit');
    brForm.submit(function(e) {
        "use strict";

        e.preventDefault();

        let ajaxData = new FormData(),
            formParams = brForm.serializeArray();

        jQuery.each(formParams, function(i, val) {
            ajaxData.append(val.name, val.value);
        });

        ajaxData.append('tid', $(this).attr('data-tid'));
        ajaxData.append('action', 'arcane_br_submit_score');
        ajaxData.append('security', footerAjaxCalls.security);

        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            data: ajaxData,
            processData: false,
            contentType: false,
            success: function(data) {

                let round = ajaxData.get('round'),
                    team = ajaxData.get('team-id'),
                    score = ajaxData.get('br-score');
                $('#roundtab-'+round).find('.tid-'+team).html(score);

                submitBrModal.dialog( "close" );
            }
        });

    });


    let deleteTeamModal =  $('#myModalDeleteTeam');
    deleteTeamModal.on('click', 'a.ajaxdeleteteam', function(event){
        "use strict";
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: footerAjaxCalls.ajaxurl,
            data: {"action": "arcane_team_delete",'security' : footerAjaxCalls.security, "pid":$(this).attr('data-pid') },
            success: function(data) {
                let modal = $('#myModalDeleteTeam');
                modal.remove();
                NotifyMe(footerAjaxCalls.teamDeleted, "information");
                parent.window.location.href = footerAjaxCalls.homeUrl;
                return false;
            }
        });

    });

    deleteTeamModal.dialog({
        autoOpen: false,
        show: {
            effect: "blind",
            duration: 500
        },
    });

    let teamChooserModal = $('#TeamChooserModalFooter');
    teamChooserModal.dialog({
        autoOpen: false,
        show: {
            effect: "blind",
            duration: 500
        },
    });

    let teamDescription = jQuery('.thdescriptionleft');
    teamDescription.on( "click", ".team-chooser", function() {
        teamChooserModal.dialog( "open" );
    });

    let deleteTeam = jQuery( ".delete-team" )
    deleteTeam.on( "click", function() {
        deleteTeamModal.dialog( "open" );
    });

    let closeModal = jQuery('.close');
    closeModal.on('click', function(){
        deleteTeamModal.dialog("close");
        reportModal.dialog("close");
        submitScoreModal.dialog("close");
        teamChooserModal.dialog( "close" );
        submitBrModal.dialog( "close" );
        return false;
    });

    let reportModal =  $('#myModalLReport');
    reportModal.dialog({
        autoOpen: false,
        show: {
            effect: "blind",
            duration: 500
        },
    });

    let reportTeam = jQuery('.flag-match');
    reportTeam.on( "click", function() {
        reportModal.dialog( "open" );
    });

    let submitScoreModal = jQuery('#myModalLSubmit');
    submitScoreModal.dialog({
        autoOpen: false,
        show: {
            effect: "blind",
            duration: 500
        },
    });

    let submitScore = jQuery('.submit-score');
    submitScore.on( "click", function() {
        submitScoreModal.dialog( "open" );
    });


    let matches = jQuery("#matches");
    matches.on("click", "a.ajaxloadchl", function(event){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_challenge_acc_rej", 'security' : footerAjaxCalls.security, "req":$(this).attr("data-req"), "cid":$(this).attr("data-cid") },
            success: function(data) {

                if(data[1] === "accepted"){

                    NotifyMe(footerAjaxCalls.challengeAccepted, "information");
                    let challenge = $( ".mj" ).find("[data-cid=" + data[0] + "]");
                    challenge.parent().empty().html(footerAjaxCalls.challengeAccepted);

                }else if(data[1] === "rejected"){

                    NotifyMe(footerAjaxCalls.challengeRejected, "information");
                    let challenge = $( ".mj" ).find("[data-cid=" + data[0] + "]");
                    challenge.parent().empty().html(footerAjaxCalls.challengeRejected);

                }

                return false;
            }
        });

    });

    let pmiTitle = $('.pmi_title');
    pmiTitle.one("click", "a.ajaxloadblock", function(event){

        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_change_membership_block", 'security' : footerAjaxCalls.security, "req": $(this).attr("data-req"), "pid": $(this).attr("data-pid"),"uid": $(this).attr("data-uid") },
            success: function(data) {

                if(data[0] === "join_team"){
                    NotifyMe(footerAjaxCalls.joinTeam, "information");
                    let join_button = $(".ajaxloadblock");
                    let join_container = $(".team-members-app");
                    join_button.remove();
                    join_container.append("<div id='score_fin' class='error_msg'>"+footerAjaxCalls.pendingRequest+"</div>").fadeIn("slow");

                }else if(data[0] === "remove_friend_user"){
                    NotifyMe(footerAjaxCalls.removedFromTeam, "information");
                    let leave_button = $(".ajaxloadblock");
                    let members_area = $(".team-members-app");
                    leave_button.remove();
                    members_area.append("<div id='score_fin' class='error_msg'>"+footerAjaxCalls.removedFromTeam+"</div>");

                }else if(data[0] === "cancel_request"){
                    NotifyMe(footerAjaxCalls.removedFromTeam, "information");
                    let members_area = $(".team-members-app");
                    let noti = $("#score_fin");
                    let leave_button = $(".ajaxloadblock");
                    leave_button.remove();
                    noti.remove();
                    members_area.append("<div id='score_fin' class='error_msg'>"+footerAjaxCalls.cancelRequest+"</div>");

                }
                return false;
            }
        });

    });


    let membersList = $('#members-list-fn');
    membersList.on("click", "a.ajaxloadremoveadmin", function(event){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_change_membership_remove_friend_admin",'security' : footerAjaxCalls.security,  "req": $(this).attr("data-req"), "pid": $(this).attr("data-pid"),"uid": $(this).attr("data-uid") },
            success: function(data) {

                if(data[0] === "remove_friend_admin"){
                    NotifyMe(footerAjaxCalls.removedFromTeam, "information");
                    let user = $("."+data[1]+" .member-list-more");
                    let user_pen = $("."+data[1]+"");
                    let user_pen_text = $("."+data[1]+" .pending-text");
                    user.empty().html("<div class='mlm1 mj'>"+footerAjaxCalls.removedFromTeam+"</div>");
                    user_pen.removeClass("pending");
                    user_pen_text.remove();

                }

                return false;
            }
        });

    });



    membersList.on("click", "a.ajaxloadletjoin", function(event){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_change_membership_let_join", 'security' : footerAjaxCalls.security,  "req": $(this).attr("data-req"), "pid": $(this).attr("data-pid"),"uid": $(this).attr("data-uid") },
            success: function(data) {

                if(data[0] === "let_this_member_join"){arcane_get_user_teams
                    NotifyMe(footerAjaxCalls.letThisMemberJoin, "information");
                    let user = $("."+data[1]+" .member-list-more");
                    let user_pen = $("."+data[1]+"");
                    let user_pen_text = $("."+data[1]+" .pending-text");
                    user.empty().html("<div class='mlm1 mj'>"+footerAjaxCalls.userJoined+"</div>");
                    user_pen.removeClass("pending");
                    user_pen_text.remove();

                }else if(data[0] === 'member_not_there'){
                    NotifyMe(footerAjaxCalls.memberNotThere, "information");
                    $('li.'+data[1]).remove();
                }

                if(data[2] === "error"){
                    NotifyMe(footerAjaxCalls.alreadyJoined, "information");
                    let user = $("."+data[1]+" .member-list-more");
                    let user_pen = $("."+data[1]+"");
                    let user_pen_text = $("."+data[1]+" .pending-text");
                    user.empty().html("<div class='mlm1 mj'>"+footerAjaxCalls.alreadyJoined+"</div>");
                    user_pen.removeClass("pending");
                    user_pen_text.remove();
                }

                return false;
            }
        });

    });



    $("#members-list-fn .ajaxloadmakeadmin").on("click", function(event){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_change_membership_make_administrator", 'security' : footerAjaxCalls.security, "req": $(this).attr("data-req"), "pid": $(this).attr("data-pid"),"uid": $(this).attr("data-uid") },
            success: function(data) {

                if(data[0] === "make_administrator"){
                    NotifyMe(footerAjaxCalls.makeAdministrator, "information");
                    let user = $("."+data[1]+" .member-list-more");
                    user.empty().html("<div class='mlm1 mj'>"+footerAjaxCalls.makeAdministrator+"</div>");

                }

                return false;
            }
        });
    });


    $("#members-list-fn .ajaxloaddowngrade").on("click", function(event){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_change_membership_downgrade_to_user",'security' : footerAjaxCalls.security,  "req": $(this).attr("data-req"), "pid": $(this).attr("data-pid"),"uid": $(this).attr("data-uid") },
            success: function(data) {

                if(data[0] === "downgrade_to_user"){
                    NotifyMe(footerAjaxCalls.downgradeToUser, "information");
                    let user = $("."+data[1]+" .member-list-more");
                    user.empty().html("<div class='mlm1 mj'>"+footerAjaxCalls.downgradeToUser+"</div>");

                }

                return false;
            }
        });
    });


    //
    // $(".single-team .ajaxdeletebck").on("click", function(event){
    //     "use strict";
    //     $.ajax({
    //         type: "POST",
    //         url: ajaxurl,
    //         data: {"action": "arcane_delete_page_background", "pid": $(this).attr("data-pid"), 'security' : footerAjaxCalls.security },
    //         success: function(data) {
    //             let delbck_button = jQuery(".ajaxdeletebck");
    //             delbck_button.remove();
    //             NotifyMe(footerAjaxCalls.deletePageBackground, "information");
    //             return false;
    //         }
    //     });
    // });
    //
    //

    let singleMatch = $(".match-header");
    singleMatch.on("click", "a.ajaxdeletematch_single", function(){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            data: {"action": "arcane_match_delete_single", 'security' : footerAjaxCalls.security, "mid":$(this).attr("data-mid")},
            success: function(data) {
                NotifyMe(footerAjaxCalls.matchDeletedRequest, "information");
                location.reload();
                return false;
            }
        });
    });



    let scoreFin = $("#score_fin");
    scoreFin.on("click", "a.ajaxdeletematchconfirmation", function(event){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_match_delete_confirmation",'security' : footerAjaxCalls.security, "req":$(this).attr("data-req"), "mid":$(this).attr("data-mid")},
            success: function(data) {
                if(data === "accepted"){
                    NotifyMe(footerAjaxCalls.matchDeleted, "information");
                    window.location.replace(footerAjaxCalls.goingTo);
                }else if(data === "rejected"){
                    NotifyMe(footerAjaxCalls.matchDeleteRejected, "information");
                    location.reload();
                }

                return false;
            }
        });

    });


    scoreFin.one("click", "a.ajaxloadchlsingle", function(){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_challenge_acc_rej_single", 'security' : footerAjaxCalls.security, "req":$(this).attr("data-req"), "cid":$(this).attr("data-cid")},
            success: function(data) {
                if(data === "accepted"){
                    NotifyMe(footerAjaxCalls.challengeAccepted, "information");
                    scoreFin.empty().html(footerAjaxCalls.challengeAccepted);

                }else if(data === "rejected"){
                    NotifyMe(footerAjaxCalls.challengeRejected, "information");
                    scoreFin.empty().html(footerAjaxCalls.challengeRejected);

                }
                location.reload();
                return false;
            }
        });
    });

    scoreFin.one("click", "a.ajaxsubmitscore", function(event){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_match_score_acc_rej", 'security' : footerAjaxCalls.security,  "req":$(this).attr("data-req"), "mid":$(this).attr("data-mid") },
            success: function(data) {
                if(data === "accepted"){
                    NotifyMe(footerAjaxCalls.scoreAccepted, "information");
                }else if(data === "rejected"){
                    NotifyMe(footerAjaxCalls.scoreRejected, "information");
                }
                location.reload();
                return false;
            }
        });

    });

    scoreFin.one("click", "a.ajaxloadeditsingle", function(event){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_edit_acc_rej_single",'security' : footerAjaxCalls.security,  "req":$(this).attr("data-req"), "cid":$(this).attr("data-cid") },
            success: function(data) {
                if(data === "accepted"){
                    NotifyMe(footerAjaxCalls.editAccepted, "information");
                }else if(data === "rejected"){
                    NotifyMe(footerAjaxCalls.editRejected, "information");
                }
                location.reload();
                return false;
            }
        });

    });




    matches.one("click", "a.ajaxloadedit", function(event){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_edit_acc_rej", 'security' : footerAjaxCalls.security, "req":$(this).attr("data-req"), "cid":$(this).attr("data-cid") },
            success: function(data) {
                if(data[1] === "accepted"){
                    NotifyMe(footerAjaxCalls.editAccepted, "information");
                    let challenge = $( ".mj" ).find("[data-cid=" + data[0] + "]");
                    challenge.parent().empty().html(footerAjaxCalls.editAccepted);
                }else if(data[1] === "rejected"){
                    NotifyMe(footerAjaxCalls.editRejected, "information");
                    let challenge = $( ".mj" ).find("[data-cid=" + data[0] + "]");
                    challenge.parent().empty().html(footerAjaxCalls.editRejected);
                }
                return false;
            }
        });

    });

    matches.one("click", "a.ajaxdeletematch", function(event){
        "use strict";
        $.ajax({
            type: "POST",
            url: footerAjaxCalls.ajaxurl,
            dataType:"json",
            data: {"action": "arcane_match_delete", 'security' : footerAjaxCalls.security, "req":$(this).attr("data-req"),  "mid":$(this).attr("data-mid")},
            success: function(data) {
                if(data[1] === "accepted"){
                    NotifyMe(footerAjaxCalls.deleteAccepted, "information" );
                    let delete_match = $( ".mj" ).find("[data-mid=" + data[0] + "]");
                    delete_match.parent().empty().html(footerAjaxCalls.deleteAccepted);
                }else if(data[1] === "rejected"){
                    NotifyMe(footerAjaxCalls.deleteRejected, "information");
                    let delete_match = $( ".mj" ).find("[data-mid=" + data[0] + "]");
                    delete_match.parent().empty().html(footerAjaxCalls.deleteRejected);
                }
                return false;
            }
        });

    });
});