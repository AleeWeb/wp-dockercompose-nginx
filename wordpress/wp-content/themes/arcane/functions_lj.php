<?php

if(isset($_POST['submit_add_match'])) {

	$timezone_string = arcane_timezone_string();
	$timezone = $timezone_string ? $timezone_string : 'UTC';
	date_default_timezone_set($timezone);

    $ArcaneWpTeamWars = new Arcane_TeamWars();
    $date = $_POST['date'];
    $ENTRY_CREATED = mktime($date['hh'],$date['mn'],0,$date['mm'],$date['jj'],$date['yy']);

	$ENTRY_CREATED_ZONE = date(get_option('date_format').' '.get_option('time_format'), $ENTRY_CREATED );

	$ENTRY_CREATED_UNIX = arcane_parse_date_to_unix($ENTRY_CREATED_ZONE);


    if ($ENTRY_CREATED !== FALSE) $ENTRY_CREATED = date(get_option('date_format').' '.get_option('time_format'), $ENTRY_CREATED );
    else $ENTRY_CREATED = current_time( 'mysql',1 );

	$match_status = '';
	$notify_team = '';


    $match = $ArcaneWpTeamWars->get_match(array('id' => $_POST['match_id']));
	if(isset($match->status))
	$match_status = $match->status;

    if($_POST['match_id']!=0){ /*UPDATE*/

    if(arcane_is_admin($match->team1, get_current_user_id())){
		$status = 'edited1';$notify_team = $match->team2;
	}elseif(arcane_is_admin($match->team2, get_current_user_id())){
		$status = 'edited2';$notify_team = $match->team1;
	}

	update_post_meta($_POST['match_id'], 'status_backup', $match_status);
    $notify_user = arcane_return_super_admin($notify_team);
    arcane_edit_sent($notify_user,$_POST['match_id'] );
	    if($match_status == 'done' or $match_status == 'pending' ){

		if($match_status == 'done'){
	     $arr = array(
	     		'title' => isset($_POST['m_title']) ? $_POST['m_title'] : '',
	     		'description' => isset($_POST['description']) ? $_POST['description'] : '',
	            'external_url' => isset($_POST['external_url']) ? $_POST['external_url'] : '',
	            'status' => $status
	    );
		}else{
			$date = $_POST['date'];
    		$ENTRY_CREATED = mktime($date['hh'],$date['mn'],0,$date['mm'],$date['jj'],$date['yy']);
			if ($ENTRY_CREATED !== FALSE) $ENTRY_CREATED = date(get_option('date_format').' '.get_option('time_format'), $ENTRY_CREATED );
			 $arr = array(
	     		'title' => isset($_POST['m_title']) ? $_POST['m_title'] : '',
	     		'description' => isset($_POST['description']) ? $_POST['description'] : '',
	            'external_url' => isset($_POST['external_url']) ? $_POST['external_url'] : '',
	            'match_status' => isset($_POST['match_status']) ? $_POST['match_status'] : 0,
	            'date' => $ENTRY_CREATED,
	            'date_unix' => $ENTRY_CREATED_UNIX,
	            'status' => $status
	            );
		}

		update_post_meta($_POST['match_id'], 'date_edit', $match->date);
		update_post_meta($_POST['match_id'], 'match_status_edit', $match_status);
		update_post_meta($_POST['match_id'], 'title_edit', $match->title);
		update_post_meta($_POST['match_id'], 'description_edit', $match->description);
		update_post_meta($_POST['match_id'], 'external_url_edit', $match->external_url);
	    }else{

	     $arr = array(
	            'title' => isset($_POST['m_title']) ? $_POST['m_title'] : '',
	            'date' => $ENTRY_CREATED,
	            'date_unix' => $ENTRY_CREATED_UNIX,
	            'game_id' => isset($_POST['game_id']) ? $_POST['game_id'] : 0,
	            'match_status' => isset($_POST['match_status']) ? $_POST['match_status'] : 0,
	            'description' => isset($_POST['description']) ? $_POST['description'] : '',
	            'external_url' => isset($_POST['external_url']) ? $_POST['external_url'] : '',
	            'status' => $status
	    );
	    }
    }else{ /*INSERT*/

    $arr = array(
            'title' => isset($_POST['m_title']) ? $_POST['m_title'] : '',
            'date' => $ENTRY_CREATED,
            'date_unix' => $ENTRY_CREATED_UNIX,
            /*'post_id' => 0,*/
            'team1' => $_POST['team1'],
            'team2' => $_POST['post_id'],
            'scores' => array(),
            'game_id' => isset($_POST['game_id']) ? $_POST['game_id'] : 0,
            'match_status' => isset($_POST['match_status']) ? $_POST['match_status'] : 0,
            'description' => isset($_POST['description']) ? $_POST['description'] : '',
            'external_url' => isset($_POST['external_url']) ? $_POST['external_url'] : '',
            'status' => "pending"
    );


    }

    if ($_POST['match_id']==0) {   /****  INSERT ****/

         $match_id = $ArcaneWpTeamWars->add_match($arr);
         $ArcaneWpTeamWars->update_match_post($match_id);
    if($arr) {
            $scores = $_POST['scores'];

            foreach($scores as $round_group => $r) {

			if(isset($r['team1'])) {
				for ( $i = 0; $i < sizeof( $r['team1'] ); $i ++ ) {

					$map_id = 0;
					if ( isset( $r['map_id'] ) ) {
						$map_id = $r['map_id'];
					}

					$ArcaneWpTeamWars->add_round( array(
						'match_id' => $match_id,
						'group_n'  => abs( $round_group ),
						'map_id'   => $map_id,
						'tickets1' => 0,
						'tickets2' => 0
					) );
				}
			}

        }
    }

    global $arcane_challenge_sent;
    $arcane_challenge_sent = 'yes';
    }
    elseif ($_POST['match_id']!=0) { /****  UPDATE ****/
        $ArcaneWpTeamWars->update_match($_POST['match_id'], $arr);
    }
}
