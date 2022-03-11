<?php
if(!function_exists('arcane_CreateTournamentOptions')){
	function arcane_CreateTournamentOptions() {
		arcane_TournamentTypeEditOptions('Knockout', 'knockout', get_theme_file_path('addons/tournaments/types/define-knockout.php'),'add');
	}
}
add_action('after_switch_theme', 'arcane_CreateTournamentOptions');

if(!function_exists('arcane_TournamentTypeEditOptions')){
	function arcane_TournamentTypeEditOptions($name, $id, $file, $action) {
		$formats = get_option('enabled_tournament_types');

		if ($action == "add") {
			$found = false;

			if (is_array($formats) AND (!empty($formats))){
				foreach ($formats as $key => $single) {
					if ($single['name'] == $name) {
						//exists don't add
						$formats[$key]['name'] = $name;
						$formats[$key]['id'] = $id;
						$formats[$key]['file'] = $file;
						$found = true;
					}
				}
				if ($found == false) {
					$formats[] = array('name' => $name, 'id' => $id, 'file' => $file);
				}
            } else {
				$formats = array();
				$formats[] = array('name' => $name, 'id' => $id, 'file' => $file);

            }
            update_option('enabled_tournament_types', $formats);
            return true;
        } elseif ($action == "remove") {
			$newformats = array();
			if (is_array($formats) AND (!empty($formats))){
				foreach ($formats as $single) {
					if ($single['name'] != $name) {
						$newformats[] = $single;
					}
				}
				update_option('enabled_tournament_types', $newformats);
				return true;
			} else {
				return true;
			}
		} elseif ($action == "update") {
			if (is_array($formats) AND (!empty($formats))){
				foreach ($formats as $key => $single) {
					if ($single['name'] == $name) {
						$formats[$key]['file'] = $file;
						update_option('enabled_tournament_types', $formats);
						return true;
					}
				}
            }
            return false;
        }
	}
}

if(!function_exists('ArcaneUpdateUserTournaments')){
	function ArcaneUpdateUserTournaments($uid) {
		$tournaments = array();
		$posts = array(
			'post_type'=> 'tournament',
			'posts_per_page' => -1,
			'post_status' => 'any'
		);
		$tourneys = get_posts($posts);
		if (is_array($tourneys)) {
			foreach($tourneys as $single) {//tusi
				if (arcane_get_my_ID_in_tournament($single->ID, $uid)) {
					$tournaments[$single->ID] = $single->ID;
				}
			}
			update_user_meta($uid, 'tournaments_in', $tournaments);
		}
		//wp_reset_postdata();
	}
}


if(!function_exists('arcane_TournamentsCanEdit')){
	function arcane_TournamentsCanEdit($pid) {
		$cid = get_current_user_id();
		$pos = get_post($pid);
		if (isset($pos) && $pos->post_author == $cid) {
			return true;
		} else {
			return current_user_can('manage_options', $pid);
		}
	}
}

if(!function_exists('arcane_clean_challenges')){
	function arcane_clean_challenges($challenges, $tid) {

		$tournament_timezone = get_post_meta($tid, 'tournament_timezone', true );
		$current_time = new DateTime("now", new DateTimeZone($tournament_timezone) );
		$current_time = $current_time->getTimestamp();

		if (isset($challenges['sent']) && is_array($challenges['sent'])) {
			$newchallenges = array();
			if(!empty($challenges['sent']))
			foreach ($challenges['sent'] as $key => $single) {
				if (isset($single['time'])) {
					if ($current_time > ($single['time'] + (24*60*60))) {
						$newchallenges[] = $single;
						unset($challenges['sent'][$key]);
					}
				}
			}


		} else {
			$challenges['sent'] = [];
		}
		if ((isset($challenges['declined'])) && (is_array($challenges['declined']))) {
			if(!empty($challenges['declined']))
			foreach ($challenges['declined'] as $key => $single) {
				if ($current_time < ($single - (24*60*60))) {
					unset($challenges['declined'][$key]);
				}
			}

		} else {
			$challenges['declined'] = [];
		}

		if (!isset($challenges['received'])) {
			$challenges['received'] = [];
		}

		if ((isset($challenges['challenged'])) && (is_array($challenges['challenged']))) {
			if(!empty($challenges['challenged']))
				foreach ($challenges['challenged'] as $key => $single) {
				    //zapamti da ovde nema time
					if (isset($single)) {
						if ($single < ($current_time - (24*60*60))) {
							unset($challenges['challenged'][$key]);
						}
					}
				}


		} else {
			$challenges['challenged'] = [];
		}

		return ($challenges);
	}
}

if(!function_exists('arcane_ladder_accept_forcibly')){
	function arcane_ladder_accept_forcibly($myid, $target=0, $tid=0) {
		global $ArcaneWpTeamWars;

		if ($target == 0) {
			$target = get_current_user_id();
		}

		if ($tid == 0) {
			$tid = get_the_id();
		}
		if ((!is_numeric($target)) OR (!is_numeric($tid)) OR (!is_numeric($myid)) )  {
			//has to be numerics
			return;
		}

		$tournament_timezone = get_post_meta($tid, 'tournament_timezone', true );
		$current_time = new DateTime("now", new DateTimeZone($tournament_timezone) );
		$current_time = $current_time->getTimestamp();

		$participants = get_post_meta($tid, 'participant_cache', true);
		$tourneygames = get_post_meta($tid, 'game_cache', true);

		foreach ($participants as $key => $single) {
			if ($single['id'] == $myid) {
				$receiver = $single; //receiver is the person who accepts challenge
				$receiver_key = $key;
			} elseif ($single['id'] == $target) {
				$sender = $single;
				$sender_key = $key;
			}
		}

		if ((isset($receiver['challenges']['received'])) && (is_array($receiver['challenges']['received'])) && (count($receiver['challenges']['received']) > 0)) {
			$temp_received = array();
			foreach ($receiver['challenges']['received'] as $single) {
				if ($single['from'] != $sender['id'])  {
					$temp_received[] = $single;
				}
			}
			$receiver['challenges']['received'] = $temp_received;
		}

		$receiver_declined = $receiver['challenges']['declined'];
		if ($receiver_declined) {
			$newdeclines = array();
			foreach ($receiver_declined as $dtime) {
				if ($dtime > ($current_time - (24*60*60))) {
					$newdeclines[] = $dtime;
				}
			}
			$receiver['challenges']['declined'] = $newdeclines;
		} else {
			$receiver['challenges']['declined'] = array();
		}

		$participants[$receiver_key] = $receiver;


		if ((isset($sender['challenges']['sent'])) && (is_array($sender['challenges']['sent'])) && (count($sender['challenges']['sent']) > 0)) {
			$temp_received = array();
			foreach ($sender['challenges']['sent'] as $single) {
				if ($single['to'] != $receiver['id'])  {
					$temp_received[] = $single;
				}
			}
			$sender['challenges']['sent'] = $temp_received;
		}

		$sender_challenged = $sender['challenges']['challenged'];
		if ($sender_challenged) {
			$newchallenges = array();
			foreach ($sender_challenged as $dtime) {
				if ($dtime > ($current_time - (24*60*60))) {
					$newchallenges[] = $dtime;
				}
			}
			$sender['challenges']['challenged'] = $newchallenges;
		} else {
			$sender['challenges']['challenged'] = array();
		}

		$participants[$sender_key] = $sender;

		$tournament = new Arcane_Tournaments();
		$tehpost = get_post($tid);
		$data = array(
			'pid' => $tehpost->ID,
			'title' => $tehpost->post_title,
			'description' => $tehpost->post_content,
			'participants' => 0,
			'game' => get_post_meta($tehpost->ID, 'tournament_game', true),
			'maps' =>  get_post_meta($tehpost->ID, 'tournament_maps', true),
			'tournament_contestants' => get_post_meta($tehpost->ID, 'tournament_contestants', true),
			'tournament_games_format'=> get_post_meta($tehpost->ID, 'tournament_games_format', true),
		);
		if (!isset($data['maps']) || empty($data['maps']) || !is_array($data['maps']) || (count($data['maps']) < 1)) {
			$maps = $ArcaneWpTeamWars->get_map(array('game_id' => $data['game'], 'order' => 'asc', 'orderby' => 'title'));
			$temparray = array();
			foreach ($maps as $map) {
				$temparray[$map->id] = $map->id;
			}
			$data['maps'] = $temparray;
		}
		if (!is_array($tourneygames)) {
			$tourneygames = array();
		}
		$new_pos = count($tourneygames);
		$time = $current_time;
		$tourneygames[$new_pos]['match_post_id'] = $tournament->ScheduleMatch($tid, $data['tournament_contestants'], $receiver['id'], $sender['id'], $data['title']." - ".esc_html__ ("Challenge for position ", "arcane")." ".($receiver_key) , $time, $data['game'], $data['description']."<br />".esc_html__ ("Challenge for position ", "arcane")." ".($receiver_key), $data['maps'], $data['tournament_games_format']);
		$tourneygames[$new_pos]['teams'][0]['id'] = $receiver['id'];
		$tourneygames[$new_pos]['teams'][1]['id'] = $sender['id'];

		$tourneygames[$new_pos]['teams'][0]['name'] = $receiver['name'];
		$tourneygames[$new_pos]['teams'][1]['name'] = $sender['name'];
		$tourneygames[$new_pos]['teams'][0]['url'] =  $receiver['url'];
		$tourneygames[$new_pos]['teams'][1]['url'] = $sender['url'];

		$tourneygames[$new_pos]['time'] = $time;
		$tourneygames[$new_pos]['teams'][0]['score'] = "0";
		$tourneygames[$new_pos]['teams'][1]['score'] = "1";
		$tourneygames[$new_pos]['score'] = '0:1';
		$match_id = $tourneygames[$new_pos]['match_post_id'];
		$tickets = array();
		$tickets[0] = array('match_id' => $match_id,
		                    'group_n' => 0,
		                    'map_id' => 0,
		                    'tickets1' => 0,
		                    'tickets2' => 1,
		);
		update_post_meta($match_id, 'tickets', $tickets);


		$scores = $ArcaneWpTeamWars->SumTickets($match_id);
		update_post_meta($match_id, 'team1_tickets', $scores[0]);
		update_post_meta($match_id, 'team2_tickets', $scores[1]);
		$games = $ArcaneWpTeamWars->get_game(array('id' => get_post_meta($match_id, 'game_id', true)));
		if (isset($games[0])) {
			update_post_meta($match_id, 'game_title', $games[0]->title);
			update_post_meta($match_id, 'game_icon', $games[0]->icon);
		}


		$participants[$receiver_key] = $receiver;
		update_post_meta($tid, 'participant_cache', $participants);
		update_post_meta($tid, 'game_cache', $tourneygames);

		$p = array(
			'status' => 'done',
		);
		$ArcaneWpTeamWars->update_match($match_id, $p);
//  wp_die();
	}
}


if(!function_exists('arcane_update_match_ut')){
	function arcane_update_match_ut($uid, $tid, $all=false ) {
		global $ArcaneWpTeamWars;
		$all = $all ? 1 : false;
		$match_ut = arcane_get_match_ut($uid, $tid, $all );


		if($match_ut) {

			$rounds = get_post_meta($tid, 'game_cache', true);

			foreach ($match_ut as $match_id) {

				foreach ($rounds as $key_round => $round) {
					foreach ($round as $key_game => $game) {

						if ($game['match_post_id'] == $match_id) {
							$team1_id = $game['teams'][0]['id'];
							$team2_id = $game['teams'][1]['id'];
							if($team1_id == $uid) {

								$team1_score = '0';
								$team2_score = '1';

							} elseif($team2_id == $uid) {

								$team1_score = '1';
								$team2_score = '0';

							}

							$tickets = array();
							$tickets[0] = array('match_id' => $match_id,
							                    'group_n' => 0,
							                    'map_id' => 0,
							                    'tickets1' => $team1_score,
							                    'tickets2' => $team2_score,
							);
							update_post_meta($match_id, 'tickets', $tickets);

							$p = array(
								'status' => 'done',
							);
							$ArcaneWpTeamWars->update_match($match_id, $p);

						}

					}
				}

			}

		}
	}
}

if(!function_exists('arcane_get_match_ut')){
	function arcane_get_match_ut($uid, $tid, $all=false ) {
		$all = $all ? -1 : 1;
		$args = array(
			'post_type' => 'matches',
			'posts_per_page' => $all,
			'post_status' => 'publish',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'tournament_id',
					'value' => $tid,
				),
				array(
					'relation' => 'OR',
					array(
						'key' => 'team1',
						'value' => $uid,
					),
					array(
						'key' => 'team2',
						'value' => $uid,
					),
				),
			),
		);
		$matches = get_posts($args);
		$match_ut = array();
		foreach( $matches as $m) {
			$match_ut[] = $m->ID;
		}
		return $match_ut;
	}
}


if(!function_exists('arcane_custom_filter_tournament_notifications')){
	function arcane_custom_filter_tournament_notifications( $component_names = array() ) {
		if ( ! is_array( $component_names ) ) {
			$component_names = array();
		}
		array_push( $component_names, 'tournaments', 'teams' );
		return $component_names;
	}
}
add_filter( 'bp_notifications_get_registered_components', 'arcane_custom_filter_tournament_notifications' );


if(!function_exists('arcane_custom_format_buddypress_notifications')){
	function arcane_custom_format_buddypress_notifications( $content, $item_id, $secondary_item_id, $action_item_count, $format, $component_action_name, $component_name, $id ) {
		// Bail if not the notification action we are looking for) {

		if ( 'tournaments_started' === $component_action_name ) {
			$text = '';
			$custom_link  = get_permalink($item_id);
			$title_attr = esc_html__('Tournament started!', 'arcane');

			$text .= esc_html__('Tournament ', 'arcane');
			$post = get_post($item_id);
			$text .= '&#39;';
			if(isset($post->post_title))
				$text .= $post->post_title;
			$text .= '&#39;';
			$text .= esc_html__(' has started! View the schedule on tournament page!', 'arcane');

			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_tournament_started_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_tournament_started_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('tournament_join_accepted' === $component_action_name ){
			$text = '';
			$custom_link  = get_permalink($item_id);
			$title_attr = esc_html__('Join accepted!', 'arcane');

			$text .= esc_html__('Your request to join ', 'arcane');
			$post = get_post($item_id);
			$text .= '&#39;';
			if(isset($post->post_title))
				$text .= $post->post_title;
			$text .= '&#39;';
			$text .= esc_html__(' tournament, has been accepted!', 'arcane');

			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_tournament_join_accepted_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_tournament_join_accepted_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;


		}elseif('tournament_join_request' === $component_action_name ){
			$text = '';
			$custom_link  = get_permalink($item_id);
			$title_attr = esc_html__('New join request!', 'arcane');
			$type = get_post_meta($item_id, 'tournament_contestants', true);
			if ($type == 'user') {
				$text .= esc_html__('User ', 'arcane');
				$user = get_user_by('id', $secondary_item_id);
				$text .= '&#39;';
				if(isset($user->display_name))
					$text .= $user->display_name;
				$text .= '&#39;';
				$text .= esc_html__(' has requested to join ', 'arcane');
			}else{
				$text .= esc_html__('Team ', 'arcane');
				$text .= '&#39;';
				$text .= get_the_title($secondary_item_id);
				$text .= '&#39;';
				$text .= esc_html__(' has requested to join ', 'arcane');
			}

			$post = get_post($item_id);
			$text .= '&#39;';
			if(isset($post->post_title))
				$text .= $post->post_title;
			$text .= '&#39;';
			$text .= esc_html__(' tournament!', 'arcane');

			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_tournament_join_request_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_tournament_join_request_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('tournament_left' === $component_action_name ){
			$text = '';
			$custom_link  = get_permalink($item_id);
			$title_attr = esc_html__('Tournament abandoned!', 'arcane');

			$type = strtolower(get_post_meta($item_id, 'tournament_contestants', true) );

			if ($type == "team") {
				$text .= esc_html__('Team ', 'arcane');
				$text .= '&#39;';
				$text .= get_the_title($secondary_item_id);
				$text .= '&#39;';
				$text .= esc_html__(' has left ', 'arcane');
			}else{
				$text .= esc_html__('User ', 'arcane');
				$user = get_user_by('id', $secondary_item_id);
				$text .= '&#39;';
				$text .= $user->display_name;
				$text .= '&#39;';
				$text .= esc_html__(' has left ', 'arcane');

			}
			$title_attr = esc_html__('New join request!', 'arcane');

			$post = get_post($item_id);
			$text .= '&#39;';
			if(isset($post->post_title))
				$text .= $post->post_title;
			$text .= '&#39;';
			$text .= esc_html__(' tournament!', 'arcane');

			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_tournament_left_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_tournament_left_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('admin_tournament_score_submitted' === $component_action_name ){

			$text = '';
			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('New score submit!', 'arcane');

			$text .= esc_html__('Score submitted in ', 'arcane');

			$post = get_post($item_id);
			$text .= '&#39;';
			if(isset($post->post_title))
				$text .= $post->post_title;
			$text .= '&#39;';
			$text .= esc_html__(' tournament match! ', 'arcane');

			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_admin_tournament_score_submitted_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_admin_tournament_score_submitted_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('user_score_submitted' === $component_action_name ){

			$text = '';
			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('New score submit!', 'arcane');

			$text .= esc_html__('New score submit for ', 'arcane');

			$post = get_post($item_id);
			$text .= '&#39;';
			if(isset($post->post_title))
				$text .= $post->post_title;
			$text .= '&#39;';
			$text .= esc_html__(' match!', 'arcane');

			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_user_score_submitted_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_user_score_submitted_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('arcane_score_rejected' === $component_action_name ){

			$text = '';
			$team_title = '';
			$custom_link  = get_permalink($item_id);

			$type_of = esc_html__(" user! ","arcane");

			$user_data2 = get_userdata($secondary_item_id);
			if(isset($user_data2->display_name))
				$team_title = $user_data2->display_name;

			$title_attr = esc_html__('Score rejected!', 'arcane');
			global $ArcaneWpTeamWars;
			$match = $ArcaneWpTeamWars->get_match(array('id' => $item_id));



			if(isset($match->tournament_id)){
				if($match->tournament_participants == 'user'){

				}else{
					$user_type = esc_html__(" team! ","arcane");
					$team_title = get_the_title($secondary_item_id);
				}

			}else{
				$user_type = esc_html__(" team! ","arcane");
				$team_title = get_the_title($secondary_item_id);
			}

			$text .= esc_html__("Your score submit was rejected by ","arcane");
			$text .= '&#39;';
			$text .= esc_html($team_title);
			$text .= '&#39;';
			$text .= esc_attr($type_of);


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_arcane_score_rejected_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_arcane_score_rejected_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('arcane_score_accepted' === $component_action_name ){

			$text = '';
			$team_title = '';
			$custom_link  = get_permalink($item_id);

			$type_of = esc_html__(" user! ","arcane");

			$user_data2 = get_userdata($secondary_item_id);
			if(isset($user_data2->display_name))
				$team_title = $user_data2->display_name;

			$title_attr = esc_html__('Score accepted!', 'arcane');
			global $ArcaneWpTeamWars;
			$match = $ArcaneWpTeamWars->get_match(array('id' => $item_id));



			if(isset($match->tournament_id)){
				if($match->tournament_participants == 'user'){

				}else{
					$user_type = esc_html__(" team! ","arcane");
					$team_title = get_the_title($secondary_item_id);
				}

			}else{
				$user_type = esc_html__(" team! ","arcane");
				$team_title = get_the_title($secondary_item_id);
			}

			$text .= esc_html__("Your score submit was accepted by ","arcane");
			$text .= '&#39;';
			$text .= esc_html($team_title);
			$text .= '&#39;';
			$text .= esc_attr($type_of);


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_score_accepted_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_score_accepted_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('join_team_request' === $component_action_name ){

			$text = '';
			$custom_link  = get_permalink($item_id);

			$user_data2 = get_userdata($secondary_item_id);
			$post = get_post($item_id);
			$team_title = get_the_title($post->ID);

			$title_attr = esc_html__('New join request!', 'arcane');

			$text .= esc_attr($user_data2->display_name);
			$text .= esc_html__(" requested to join your ","arcane");
			$text .= '&#39;';
			$text .= esc_html($team_title);
			$text .= '&#39;';
			$text .= esc_html__(" team! ","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_join_team_request_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_join_team_request_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('join_team_allow' === $component_action_name ){

			$text = '';
			$custom_link  = get_permalink($item_id);

			$team_title = get_the_title($item_id);

			$title_attr = esc_html__('Join request accepted!', 'arcane');

			$text .= esc_html__("Your requested to join","arcane");
			$text .= '&#39;';
			$text .= esc_html($team_title);
			$text .= '&#39;';
			$text .= esc_html__(" team has been accepted! ","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_join_team_allow_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_join_team_allow_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('challenge_sent' === $component_action_name ){

			$text = '';

			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('New challenge!', 'arcane');

			$text .= esc_html__("New challenge! Click here to view this challenge","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_challenge_sent_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_challenge_sent_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('challenge_accepted' === $component_action_name ){

			$text = '';

			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('Challenge accepted!', 'arcane');

			$text .= esc_html__("Challenge accepted! Click here to view this challenge","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_challenge_accepted_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_challenge_accepted_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('challenge_rejected' === $component_action_name ){

			$text = '';

			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('Challenge accepted!', 'arcane');

			$text .= esc_html__("Challenge accepted! Click here to view this challenge","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_challenge_rejected_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_challenge_rejected_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('edit_sent' === $component_action_name ){

			$text = '';

			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('New edit request!', 'arcane');

			$text .= esc_html__("New edit request! Click here to view this match","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_edit_sent_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_edit_sent_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('edit_rejected' === $component_action_name ){

			$text = '';

			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('Edit rejected!', 'arcane');

			$text .= esc_html__("Edit rejected! Click here to view this match","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_edit_rejected_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_edit_rejected_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('edit_accepted' === $component_action_name ){

			$text = '';

			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('Edit accepted!', 'arcane');

			$text .= esc_html__("Edit accepted! Click here to view this match","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_edit_accepted_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_edit_accepted_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('delete_sent' === $component_action_name ){

			$text = '';

			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('New delete request!', 'arcane');

			$text .= esc_html__("New delete request! Click here to view this match","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_delete_sent_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_delete_sent_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('delete_rejected' === $component_action_name ){

			$text = '';

			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('Delete rejected!', 'arcane');

			$text .= esc_html__("Delete rejected! Click here to view this match","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_delete_rejected_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_delete_rejected_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif('delete_accepted' === $component_action_name ){

			$text = '';

			$custom_link  = get_permalink($item_id);

			$title_attr = esc_html__('Delete accepted!', 'arcane');

			$text .= esc_html__("Delete accepted! Click here to view this match","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_delete_accepted_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_delete_accepted_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;

		}elseif ( 'bbp_new_reply' === $component_action_name ) {
			$topic_id    = bbp_get_reply_topic_id( $item_id );
			$topic_title = bbp_get_topic_title( $topic_id );
			$topic_link  = wp_nonce_url( add_query_arg( array( 'action' => 'bbp_mark_read', 'topic_id' => $topic_id ), bbp_get_reply_url( $item_id ) ), 'bbp_mark_topic_' . $topic_id );
			$title_attr  = esc_html__( 'Topic Replies', 'arcane' );

			if ( (int) $total_items > 1 ) {
				$text   = sprintf( esc_html__( 'You have %d new replies', 'arcane' ), (int) $action_item_count );
				$filter = 'bbp_multiple_new_subscription_notification';
			} else {
				if ( !empty( $secondary_item_id ) ) {
					$text = sprintf( esc_html__( 'You have %d new reply to %2$s from %3$s', 'arcane' ), (int) $action_item_count, $topic_title, bp_core_get_user_displayname( $secondary_item_id ) );
				} else {
					$text = sprintf( esc_html__( 'You have %d new reply to %s',             'arcane' ), (int) $action_item_count, $topic_title );
				}
				$filter = 'bbp_single_new_subscription_notification';
			}

			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( $filter, '<a href="' . esc_url( $topic_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $topic_link );

				// Deprecated BuddyBar
			} else {
				$return = apply_filters( $filter, array(
					'text' => $text,
					'link' => $topic_link
				), $topic_link, (int) $action_item_count, $text, $topic_title );
			}

			do_action( 'bbp_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );

			return $return;
		}elseif ( 'invite_sent' === $component_action_name ) {
			$text = '';

			$custom_link  = get_permalink($item_id);
			$titleteam = get_the_title($item_id);
			$title_attr = esc_html__('New invitation!', 'arcane');

			$text .= esc_html__("Hi, you just got an invitation to join ", "arcane").$titleteam.esc_html__(" team","arcane");


			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'arcane_invite_sent_filter', '<a class="add_user_to_team" data-tid="'.esc_attr($item_id).'" href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $custom_link );
				// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'arcane_invite_sent_filter', array(
					'text' => $text,
					'link' => $custom_link
				), $custom_link, (int) $action_item_count, $text, $title_attr );
			}
			do_action( 'arcane_custom_format_buddypress_notifications', $component_action_name, $item_id, $secondary_item_id, $action_item_count );
			return $return;
		}
		return $action;

	}
}
add_filter( 'bp_notifications_get_notifications_for_user', 'arcane_custom_format_buddypress_notifications', 10, 8);


if(!function_exists('arcane_tournament_notify_start_all')){
	function arcane_tournament_notify_start_all($tid) {
		$type = get_post_meta($tid, 'tournament_contestants', true);
		$competitors = get_post_meta($tid, 'tournament_competitors' , true);

		if (!empty($competitors)){
			if (strpos(strtolower($type), 'user') === false) {
				//team type
				foreach ($competitors as $team) {
					$post_meta_arr = get_post_meta( $team, 'team', true );
					//notify active users
					if (isset($post_meta_arr['users']['active'])) {
						if (is_array($post_meta_arr['users']['active'])) {
							foreach ($post_meta_arr['users']['active'] as $singlez) {
								if (function_exists ('bp_notifications_add_notification')) {
									$count = arcane_check_if_notification_exists($singlez,$tid, 'tournaments_started');
									if($count==0){
										bp_notifications_add_notification( array(
											'user_id'           => $singlez,
											'item_id'           => $tid,
											'component_name'        => 'tournaments',
											'component_action'  => 'tournaments_started',
											'date_notified'     => bp_core_current_time(),
											'is_new'            => 1,
										) );
									}
								}
							}
						}
					}
					//admins
					if (isset($post_meta_arr['admins'])) {
						if (is_array($post_meta_arr['admins'])) {
							foreach ($post_meta_arr['admins'] as $singlez) {
								if (function_exists ('bp_notifications_add_notification')) {
									$count = arcane_check_if_notification_exists($singlez,$tid, 'tournaments_started');
									if($count==0){
										bp_notifications_add_notification( array(
											'user_id'           => $singlez,
											'item_id'           => $tid,
											'component_name'        => 'tournaments',
											'component_action'  => 'tournaments_started',
											'date_notified'     => bp_core_current_time(),
											'is_new'            => 1,
										) );
									}
								}
							}
						}
					}

					if (isset($post_meta_arr['super_admin'])) {
						if (function_exists ('bp_notifications_add_notification')) {
							$count = arcane_check_if_notification_exists($post_meta_arr['super_admin'],$tid, 'tournaments_started');
							if($count==0){
								bp_notifications_add_notification( array(
									'user_id'           => $post_meta_arr['super_admin'],
									'item_id'           => $tid,
									'component_name'        => 'tournaments',
									'component_action'  => 'tournaments_started',
									'date_notified'     => bp_core_current_time(),
									'is_new'            => 1,
								) );
							}
						}
					}
				}
			} else {
				//Users
				foreach ($competitors as $competitor) {
					if (function_exists ('bp_notifications_add_notification')) {
						if(!isset($competitor))$competitor = 0;
						$count = arcane_check_if_notification_exists($competitor,$tid, 'tournaments_started');
						if($count==0){
							bp_notifications_add_notification( array(
								'user_id'           => $competitor,
								'item_id'           => $tid,
								'component_name'        => 'tournaments',
								'component_action'  => 'tournaments_started',
								'date_notified'     => bp_core_current_time(),
								'is_new'            => 1,
							) );
						}

					}
				}
			}
		}
	}
}

if(!function_exists('arcane_unpublish_tournament')){
	function arcane_unpublish_tournament($tid) {
		$current_post = get_post( $tid, 'ARRAY_A' );
		$current_post['post_status'] = 'draft';
		wp_update_post($current_post);
	}
}


//on tournament deletion
add_action( 'before_delete_post', 'arcane_tournament_deletor' );
if(!function_exists('arcane_tournament_deletor')){
	function arcane_tournament_deletor( $postid ){
		// We check if the global post type isn't ours and just return
		global $post_type;
		if ( $post_type != 'tournament' ) return;

		// My custom stuff for deleting my custom post type here
		$tournament = new Arcane_Tournaments();
		$tournament->DeleteTournamentMatches($postid);
		return;
	}
}

if(!function_exists('ArcaneTournamentFormatTime')){
	function ArcaneTournamentFormatTime($timestamp) {
		$date_format = get_option('date_format');
		$time_format = get_option('time_format');

		$date = date(get_option('date_format').' '.get_option('time_format'), $timestamp);
		return $date;
	}
}

add_action( 'wp_ajax_delete_tournament', 'arcane_delete_tournament' );
add_action( 'wp_ajax_nopriv_delete_tournament', 'arcane_delete_tournament' );


if(!function_exists('arcane_delete_tournament')){
	function arcane_delete_tournament() {
		$tid = $_POST['tid'];
		if (is_numeric($tid) AND ($tid > 0) AND (current_user_can('delete_post', $tid))) {
			$users = get_post_meta($tid, 'tournament_competitors', true);
			delete_post_meta($tid, 'tournament_competitors');
			if (isset($users) AND is_array($users)) {
				foreach ($users as $single) {
					ArcaneUpdateUserTournaments($single);
				}
			}
			wp_delete_post($tid);
		} else {
			wp_die;
		}
	}
}
add_action( 'wp_ajax_tournament_load_maps', 'arcane_tournament_load_maps' );
add_action( 'wp_ajax_nopriv_tournament_load_maps', 'arcane_tournament_load_maps' );


if(!function_exists('arcane_tournament_load_maps')){
	function arcane_tournament_load_maps () {
		global $ArcaneWpTeamWars;
		$game_id = $_POST['game_id'];
		$maps = $ArcaneWpTeamWars->get_map(array('game_id' =>$game_id, 'order' => 'asc', 'orderby' => 'title'));
		$maps_count = count($maps);
		if ($maps_count == 2) {
			$additional_class = "two_maps";
		} elseif ($maps_count == 3) {
			$additional_class = "three_maps";
		} elseif ($maps_count > 3) {
			$additional_class = "more_than_three_maps";
		} else {
			$additional_class= "single_map";
		}
		$output = '';
		$output .='<ul class="tbmapsi '.esc_attr($additional_class).'">';

		foreach ($maps as $map) {
			$image = wp_get_attachment_image_src ($map->screenshot, array(800,500));

			if (is_array($image)) {
				$output_image = $image[0];
			} else {
				$output_image = get_theme_file_uri('img/defaults/305x305.jpg');
			}
			//check if map selected
			$output .='
            <li data-map-id="'.esc_attr($map->id).'" class="map_selector">
                <div>
                    <div>
                        <h3>'.esc_attr($map->title).'</h3>
                        <img alt="img" src="'.esc_url($output_image).'" />
                    </div>
                </div>
            </li>
            ';
		}

		$output .='</ul>';

		$allowed_html = array(
			'li' => array(
				'data-map-id' => array(),
				'class' => array(),
				'data'=>array()
			),
			'h3' => array(),
			'img' => array(
				'data-src' => array(),
				'src' => array()
			),
			'ul' => array(
				'class' => array(),
			),
			'div' =>array()
		);
		echo wp_kses($output, $allowed_html);
		wp_die();
	}
}
add_action( 'wp_ajax_arcane_ladder_decline', 'arcane_ladder_decline' );
add_action( 'wp_ajax_nopriv_arcane_ladder_decline', 'arcane_ladder_decline' );


if(!function_exists('arcane_ladder_decline')){
	function arcane_ladder_decline() {
		$target = str_replace('decline_challenge_', '', sanitize_text_field($_POST['target_id']));
		$myid = sanitize_text_field($_POST['myid']);
		$tid = sanitize_text_field($_POST['tid']);

		$tournament_timezone = get_post_meta($tid, 'tournament_timezone', true );
		$current_time = new DateTime("now", new DateTimeZone($tournament_timezone) );
		$current_time = $current_time->getTimestamp();

		if ((!is_numeric($target)) OR (!is_numeric($tid)) OR (!is_numeric($myid)) )  {
			//has to be numerics
			wp_die();
		}
		$participants = get_post_meta($tid, 'participant_cache', true);
		foreach ($participants as $key => $single) {
			if ($single['id'] == $myid) {
				$receiver = $single; //receiver is the person who denies challenge
				$receiver_key = $key;
			} elseif ($single['id'] == $target) {
				$sender = $single;
				$sender_key = $key;
			}
		}

		if ((isset($receiver['challenges']['received'])) && (is_array($receiver['challenges']['received'])) && (count($receiver['challenges']['received']) > 0)) {
			$temp_received = array();
			foreach ($receiver['challenges']['received'] as $single) {
				if ($single['from'] != $sender['id'])  {
					$temp_received[] = $single;
				}
			}
			$receiver['challenges']['received'] = $temp_received;
		}
		$receiver['challenges']['declined'][] = $current_time;
		$participants[$receiver_key] = $receiver;

		if ((isset($sender['challenges']['sent'])) && (is_array($sender['challenges']['sent'])) && (count($sender['challenges']['sent']) > 0)) {
			$temp_received = array();
			foreach ($sender['challenges']['sent'] as $single) {
				if ($single['to'] != $receiver['id'])  {
					$temp_received[] = $single;
				}
			}
			$sender['challenges']['sent'] = $temp_received;
		}

		$sender_challenged = $sender['challenges']['challenged'];
		if ($sender_challenged) {
			$newchallenges = array();
			foreach ($sender_challenged as $dtime) {
				if ($dtime > ($current_time - (24*60*60))) {
					$newchallenges[] = $dtime;
				}
			}
			$sender['challenges']['challenged'] = $newchallenges;
		} else {
			$sender['challenges']['challenged'] = array();
		}

		$participants[$sender_key] = $sender;

		update_post_meta($tid, 'participant_cache', $participants);
		wp_die();
	}
}

add_action( 'wp_ajax_arcane_ladder_accept', 'arcane_ladder_accept' );
add_action( 'wp_ajax_nopriv_arcane_ladder_accept', 'arcane_ladder_accept' );
if(!function_exists('arcane_ladder_accept')){
	function arcane_ladder_accept() {
		global $ArcaneWpTeamWars;
		$target = str_replace('accept_challenge_', '', sanitize_text_field($_POST['target_id']));
		$myid = sanitize_text_field($_POST['myid']);
		$tid = sanitize_text_field($_POST['tid']);

		$tournament_timezone = get_post_meta($tid, 'tournament_timezone', true );
		$current_time = new DateTime("now", new DateTimeZone($tournament_timezone) );
		$current_time = $current_time->getTimestamp();

		if ((!is_numeric($target)) OR (!is_numeric($tid)) OR (!is_numeric($myid)) )  {
			//has to be numerics
			wp_die();
		}
		$participants = get_post_meta($tid, 'participant_cache', true);
		$tourneygames = get_post_meta($tid, 'game_cache', true);

		foreach ($participants as $key => $single) {
			if ($single['id'] == $myid) {
				$receiver = $single; //receiver is the person who accepts challenge
				$receiver_key = $key;
			} elseif ($single['id'] == $target) {
				$sender = $single;
				$sender_key = $key;
			}
		}

		if ((isset($receiver['challenges']['received'])) && (is_array($receiver['challenges']['received'])) && (count($receiver['challenges']['received']) > 0)) {
			$temp_received = array();
			foreach ($receiver['challenges']['received'] as $single) {
				if ($single['from'] != $sender['id'])  {
					$temp_received[] = $single;
				}
			}
			$receiver['challenges']['received'] = $temp_received;
		}

		#   $receiver['challenges']['declined'] = array();
		$receiver_declined = $receiver['challenges']['declined'];
		if ($receiver_declined) {
			$newdeclines = array();
			foreach ($receiver_declined as $dtime) {
				if ($dtime > ($current_time - (24*60*60))) {
					$newdeclines[] = $dtime;
				}
			}
			$receiver['challenges']['declined'] = $newdeclines;
		} else {
			$receiver['challenges']['declined'] = array();
		}

		$participants[$receiver_key] = $receiver;


		if ((isset($sender['challenges']['sent'])) && (is_array($sender['challenges']['sent'])) && (count($sender['challenges']['sent']) > 0)) {
			$temp_received = array();
			foreach ($sender['challenges']['sent'] as $single) {
				if ($single['to'] != $receiver['id'])  {
					$temp_received[] = $single;
				} else {
					//$temp_received[] = $single['time'];
				}
			}
			$sender['challenges']['sent'] = $temp_received;
		}

		$sender_challenged = $sender['challenges']['challenged'];
		if ($sender_challenged) {
			$newchallenges = array();
			foreach ($sender_challenged as $dtime) {
				if ($dtime > ($current_time - (24*60*60))) {
					$newchallenges[] = $dtime;
				}
			}
			$sender['challenges']['challenged'] = $newchallenges;
		} else {
			$sender['challenges']['challenged'] = array();
		}

		$participants[$sender_key] = $sender;

		$tournament = new Arcane_Tournaments();
		$tehpost = get_post($tid);
		$data = array(
			'pid' => $tehpost->ID,
			'title' => $tehpost->post_title,
			'description' => $tehpost->post_content,
			'participants' => 0,
			'game' => get_post_meta($tehpost->ID, 'tournament_game', true),
			'maps' =>  get_post_meta($tehpost->ID, 'tournament_maps', true),
			'tournament_contestants' => get_post_meta($tehpost->ID, 'tournament_contestants', true),
			'tournament_games_format'=> get_post_meta($tehpost->ID, 'tournament_games_format', true),
		);
		if (!isset($data['maps']) OR empty($data['maps']) OR !is_array($data['maps']) OR (count($data['maps']) < 1)) {
			$maps = $ArcaneWpTeamWars->get_map(array('game_id' => $data['game'], 'order' => 'asc', 'orderby' => 'title'));
			$temparray = array();
			foreach ($maps as $map) {
				$temparray[$map->id] = $map->id;
			}
			$data['maps'] = $temparray;
		}
		if (!is_array($tourneygames)) {
			$tourneygames = array();
		}
		$new_pos = count($tourneygames);
		$time = $current_time;
		$tourneygames[$new_pos]['match_post_id'] = $tournament->ScheduleMatch($tid, $data['tournament_contestants'], $receiver['id'], $sender['id'], $data['title']." - ".esc_html__ ("Challenge for position ", "arcane")." ".($receiver_key) , $time, $data['game'], $data['description']."<br />".esc_html__ ("Challenge for position ", "arcane")." ".($receiver_key), $data['maps'], $data['tournament_games_format']);
		$tourneygames[$new_pos]['teams'][0]['id'] = $receiver['id'];
		$tourneygames[$new_pos]['teams'][1]['id'] = $sender['id'];

		$tourneygames[$new_pos]['teams'][0]['name'] = $receiver['name'];
		$tourneygames[$new_pos]['teams'][1]['name'] = $sender['name'];
		$tourneygames[$new_pos]['teams'][0]['url'] =  $receiver['url'];
		$tourneygames[$new_pos]['teams'][1]['url'] = $sender['url'];

		$tourneygames[$new_pos]['time'] = $time;
		$tourneygames[$new_pos]['teams'][0]['score'] = "0";
		$tourneygames[$new_pos]['teams'][1]['score'] = "0";


		$participants[$receiver_key] = $receiver;
		arcane_challenge_accepted($sender['id'], $tid);
		update_post_meta($tid, 'participant_cache', $participants);
		update_post_meta($tid, 'game_cache', $tourneygames);
		wp_die();
	}
}
add_action( 'wp_ajax_arcane_stop_ladder_game', 'arcane_stop_ladder_game' );
add_action( 'wp_ajax_nopriv_arcane_stop_ladder_game', 'arcane_stop_ladder_game' );
if(!function_exists('arcane_stop_ladder_game')){
	function arcane_stop_ladder_game() {
		$tid = isset($_POST['tid']) ? (int)$_POST['tid'] : '';
		if ($tid) {
			update_post_meta($tid, 'game_stop', '1');
		}
		exit;
	}
}

add_action( 'wp_ajax_arcane_send_challenge', 'arcane_send_challenge' );
add_action( 'wp_ajax_nopriv_arcane_send_challenge', 'arcane_send_challenge' );
if(!function_exists('arcane_send_challenge')){
	function arcane_send_challenge() {

		$target = str_replace('challenge_', '', sanitize_text_field($_POST['target_id']));
		$myid = sanitize_text_field($_POST['myid']);
		$tid = sanitize_text_field($_POST['tid']);

		$tournament_timezone = get_post_meta($tid, 'tournament_timezone', true );
		$current_time = new DateTime("now", new DateTimeZone($tournament_timezone) );
		$current_time = $current_time->getTimestamp();

		if ((!is_numeric($target)) OR (!is_numeric($tid)) OR (!is_numeric($myid)) )  {
			//has to be numerics
			wp_die();
		}
		//all numerics, let's move on
		$participants = get_post_meta($tid, 'participant_cache', true);
		foreach ($participants as $key => $single) {
			if ($single['id'] == $target) {
				$receiver = $single;
				$receiver_key = $key;
			} elseif ($single['id'] == $myid) {
				$sender = $single;
				$sender_key = $key;
			}
		}
		$found = false;
		//update receiver
		if ((isset($receiver['challenges']['received'])) && (is_array($receiver['challenges']['received'])) && (count($receiver['challenges']['received']) > 0)) {
			foreach ($receiver['challenges']['received'] as $keyz => $received) {
				if ($received['from'] == $myid) {
					//already challenged, refresh ?
					$receiver['challenges']['received'][$keyz] = array("from" => $myid, "time" => $current_time);
					$found = true;
				}
			}
			if ($found == false) {
				$receiver['challenges']['received'][] = array("from" => $myid, "time" => $current_time);
			}
		} else {
			$receiver['challenges']['received'][] = array("from" => $myid, "time" => $current_time);
		}
		$found = false;
		//update sender

		if (isset($sender['challenges']['sent']) && is_array($sender['challenges']['sent']) && count($sender['challenges']['sent']) > 0) {
			foreach ($sender['challenges']['sent'] as $kez => $sent) {
				if ($sent['to'] == $target) {
					$sender['challenges']['sent'][$kez] = array("to" => $target, "time" => $current_time);
					$sender['challenges']['challenged'][$kez] = $current_time;
					$found = true;
				}
			}
			if ($found == false) {
				$sender['challenges']['sent'][] = array("to" => $target, "time" => $current_time);
				$sender['challenges']['challenged'][] = $current_time;
			}
		} else {
			$sender['challenges']['sent'][] = array("to" => $target, "time" => $current_time);
			$sender['challenges']['challenged'][] = $current_time;
		}



		$participants[$sender_key] = $sender;
		$participants[$receiver_key] = $receiver;

		arcane_challenge_sent($target, $tid);
		update_post_meta($tid, 'participant_cache', $participants);

		wp_die();
	}
}



add_action( 'wp_ajax_get_next_prize_template', 'arcane_get_next_prize_template' );
add_action( 'wp_ajax_nopriv_get_next_prize_template', 'arcane_get_next_prize_template' );
if(!function_exists('arcane_get_next_prize_template')){
	function arcane_get_next_prize_template () {

		if (is_numeric($_POST['currentprize'])) {
			//check if no fiddling
			$gettingprize = $_POST['currentprize'] + 1;
			switch ($gettingprize) {
				case 1:
					$class="tfirstw";
					$image = get_theme_file_uri('img/ticons/1st.png');
					break;
				case 2:
					$class="tsecondw";
					$image = get_theme_file_uri('img/ticons/2nd.png');
					break;
				case 3:
					$class="tthirdw";
					$image = get_theme_file_uri('img/ticons/3rd.png');
					break;
				default:
					$class="";
					$image = "";
					break;
			}
			echo '<tr>
            <td class="'.esc_attr($class).'">
                <span>';
			if (strlen ($image) > 1) {
				echo '<img alt="img" src="'.esc_url($image).'" />';
			}
			echo '
                '.arcane_ordinal($gettingprize).' '.esc_html__("place:", "arcane").'
                </span>
            </td>
            <td class="trcell">
                <span class="tournament-table_prize"><input type="text" name="tournament_prize[]" /></span>
            </td>
        </tr>';

		}

		wp_die();
	}
}


add_action( 'wp_ajax_remove_regulation_template', 'arcane_remove_regulation_template' );
add_action( 'wp_ajax_nopriv_remove_regulation_template', 'arcane_remove_regulation_template' );
if(!function_exists('arcane_remove_regulation_template')){
	function arcane_remove_regulation_template () {
		if ($_POST['pk'] > 0)  {
			$currentprizes = get_post_meta($_POST['pk'], 'tournament_regulations', true);
			if (!is_array($currentprizes)) {
				$currentprizes = unserialize($currentprizes);
			}
			if (!is_array($currentprizes)) {
				$currentprizes = array();
			}
			array_pop($currentprizes);
			update_post_meta($_POST['pk'], 'tournament_regulations', $currentprizes);
		}
		wp_die();
	}
}


add_action( 'wp_ajax_change_selected_maps', 'arcane_change_selected_maps' );
add_action( 'wp_ajax_nopriv_change_selected_maps', 'arcane_change_selected_maps' );
if(!function_exists('arcane_change_selected_maps')){
	function arcane_change_selected_maps () {
		$pid = $_POST['tid'];
		if (!($pid > 0)) {
			wp_die();
		} else {
			$current_maps = get_post_meta($pid, 'tournament_maps', true);
			if ((!(is_array($current_maps))) && (strlen($current_maps) > 1)) {
				$current_maps = unserialize($current_maps);
			}
			if (!is_array($current_maps)) {
				//no current maps
				if ($_POST['whatdo'] == "add") {
					//add a new map
					$insert = array($_POST['mapid'] =>$_POST['mapid']);
					update_post_meta($pid, 'tournament_maps', $insert);
				}
			} else {

				if ($_POST['whatdo'] == "add") {
					//add a new map
					$current_maps[$_POST['mapid']] = $_POST['mapid'];
					update_post_meta($pid, 'tournament_maps', $current_maps);
				} else {
					if (isset($current_maps[$_POST['mapid']])) {
						unset($current_maps[$_POST['mapid']]);
					}
					update_post_meta($pid, 'tournament_maps', $current_maps);
				}
			}
		}
	}
}

if(!function_exists('arcane_get_my_ID_in_tournament')){
	function arcane_get_my_ID_in_tournament($tid, $uid = 0) {
		if ($uid == 0) {
			$uid = get_current_user_id();
		}

		$current = get_post_meta($tid, 'tournament_competitors', true);


		if(empty($current)){
			$current = get_post_meta($tid, 'tournament_candidate', true);
		}else{
			$current1 = get_post_meta($tid, 'tournament_candidate', true);

			if(!empty($current1)) $current = array_merge($current1, $current);
		}

		if (is_array($current)) {
			//now we check if it's teams
			$type = get_post_meta($tid, 'tournament_contestants', true);
			if ($type=='team') {
				foreach ($current as $single) {
					//single je team
					$post_meta_arr = get_post_meta( $single, 'team', true );
					if (isset($post_meta_arr['users']['active'])) {
						if (is_array($post_meta_arr['users']['active'])) {
							foreach ($post_meta_arr['users']['active'] as $singlez) {
								if ($singlez == $uid) {
									return $single;
								}
							}
						}
					}

					if (isset($post_meta_arr['admins'])) {
						if (is_array($post_meta_arr['admins'])) {
							foreach ($post_meta_arr['admins'] as $singlez) {
								if ($singlez == $uid) {
									return $single;
								}
							}
						}
					}

					if (isset($post_meta_arr['super_admin'])) {
						if ($post_meta_arr['super_admin'] == $uid) {
							return $single;
						}
					}
					$post = get_post($single);
					if (is_object($post)) {
						if ($post->post_author == $uid) {
							return $post->ID;
						}
					}
				}
				return false;
			} else {
				//users
				if (in_array($uid, $current)) {
					return $uid;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
}

add_action( 'wp_ajax_join_tournament', 'arcane_ajax_join_tournament' );
add_action( 'wp_ajax_nopriv_join_tournament', 'arcane_ajax_join_tournament' );


if(!function_exists('arcane_ajax_join_tournament')){
	function arcane_ajax_join_tournament() {
		global $ArcaneWpTeamWars;
		$tid = $_POST['tid'];
		$pid = $_POST['pid'];

		$options = arcane_get_theme_options();
		if(!isset($options['tournament_approve_user']))$options['tournament_approve_user'] = true;
		if ((!isset($tid)) OR (!isset($pid)) OR (!is_numeric($tid))OR (!is_numeric($pid))) {
			wp_die();
		}

		$type = strtolower(get_post_meta($tid, 'tournament_contestants', true) );
		if (($type == "team") or ($type == "teams")) {

			$teams = arcane_get_user_teams(get_current_user_id());
			if (!in_array($pid, $teams)) {
				//user is not in requesting team, like wat, just give up
				wp_die();
			}

			$tid_game = get_post_meta($tid, 'tournament_game', true);
			$games = $ArcaneWpTeamWars->get_game('');
			$foundid = false;
			foreach ($games as $game) {
				if ($game->title == $tid_game) {
					$foundid = $game->id;
				}
			}
			if ($foundid !== false) {
				//game exists
				$teamarray = $ArcaneWpTeamWars->get_team(array('id' => $pid));
				if (!in_array($foundid, $teamarray->games)) {
					wp_die(); //game not there
				}
			} else {
				wp_die();
			}
			//game exists, team there, check if current user in that team

		} else {
			//user type, might as well make him do it
			$pid = get_current_user_id();
		}

		if($options['tournament_approve_user']){
			$current = get_post_meta($tid, 'tournament_candidate', true);
		}else{
			$current = get_post_meta($tid, 'tournament_competitors', true);
		}

		if (!is_array($current)) {
			$current = array();
		}
		$current[$pid] = $pid;
		if($options['tournament_approve_user']){
			update_post_meta($tid, 'tournament_candidate', $current);
		}else{
			update_post_meta($tid, 'tournament_competitors', $current);

			//moze da bude problm
			ArcaneUpdateUserTournaments(get_current_user_id());
		}
		$author_id = get_post_field( 'post_author', $tid );
		arcane_user_joined_tournament($author_id, $tid, $pid, $type);

		if (function_exists ('bp_notifications_add_notification')) {

			bp_notifications_add_notification( array(
				'user_id'           => $author_id,
				'item_id'           => $tid,
				'secondary_item_id' => $pid,
				'component_name'    => 'tournaments',
				'component_action'  => 'tournament_join_request',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );

		}

		//ladder new user joind after start
		$t_type = get_post_meta($tid, 'format', true);
		$t_start = get_post_meta($tid,"tournament_starts", true);
		$t_start_unix = get_post_meta($tid,"tournament_starts_unix",true);

		$tournament_timezone = get_post_meta($tid, 'tournament_timezone', true );
		$cur_time = new DateTime("now", new DateTimeZone($tournament_timezone) );
		$cur_time = $cur_time->getTimestamp();

		if(isset($t_start_unix) && !empty($t_start_unix)){
			$timestamp = $t_start_unix;
		}else{
			$timestamp = mysql2date('U', $t_start);
		}
		if(strtolower($t_type) == 'ladder' && $timestamp <= $cur_time){
			$participant_cache = get_post_meta($tid, 'participant_cache', true);

			if (!is_array($participant_cache)) {
				$participant_cache = array();
			}
			$new_pos = count($participant_cache) +1;



			if (($type == "team") or ($type == "teams")) {
				$participant_cache[$new_pos]['id'] = $pid;
				$participant_cache[$new_pos]['name'] =  get_the_title($pid);
				$participant_cache[$new_pos]['url'] =  get_permalink($pid);
			} else {
				$user = get_user_by('id',get_current_user_id());
				$participant_cache[$new_pos]['id'] = get_current_user_id();
				$participant_cache[$new_pos]['name'] =$user->display_name;
				$participant_cache[$new_pos]['url'] = bp_core_get_user_domain(get_current_user_id());
			}

			$participant_cache[$new_pos]['score'] = array(
				'games' => 0,
				'victories' => 0,
				'defeats' => 0,
				'draws' => 0,
				'points' => 0
			);

			update_post_meta($tid, 'participant_cache', $participant_cache);
		}


		wp_die();
	}
}


add_action( 'wp_ajax_blank_tournament', 'arcane_ajax_blank_tournament' );
add_action( 'wp_ajax_nopriv_blank_tournament', 'arcane_ajax_blank_tournament' );
if(!function_exists('arcane_ajax_blank_tournament')){
	function arcane_ajax_blank_tournament() {
		$tid = $_POST['tid'];
		if ((!isset($tid))  OR (!is_numeric($tid)) OR !(current_user_can('delete_post', $tid))) {
			//if you can't delete it you can't blank it
			wp_die();
		}

		$users = get_post_meta($tid, 'tournament_competitors', true);
		delete_post_meta($tid, 'tournament_competitors');
		if (isset($users) AND is_array($users)) {
			foreach ($users as $single) {
				ArcaneUpdateUserTournaments($single);
			}
		}
		wp_die();
	}
}

add_action( 'wp_ajax_arcane_ajax_restart_tournament', 'arcane_ajax_restart_tournament' );
add_action( 'wp_ajax_nopriv_arcane_ajax_restart_tournament', 'arcane_ajax_restart_tournament' );
if(!function_exists('arcane_ajax_restart_tournament')){
	function arcane_ajax_restart_tournament() {
		global $ArcaneWpTeamWars;
		$data = $_POST['data'];

		$args = array(
			'post_title' => $data['title'],
			'post_content' => $data['description'],
			'post_type' => 'tournament',
			'post_status' => 'publish'
		);
		$post_id =  wp_insert_post ( $args );


		if ($post_id > 0) {
			foreach ($data as $single => $value) {


				switch ($single) {
					case 'game':
						update_post_meta($post_id, 'tournament_game', $value);
						break;
					case 'tournament_format':
						update_post_meta($post_id, 'format', $value);
						break;
					case 'tournament_timezone':
						update_post_meta($post_id, 'tournament_timezone', $value);
						break;
					case 'tournament_contestants':
						$value = strtolower($value);
						update_post_meta($post_id, 'tournament_contestants', $value);
						break;
					case 'tournament_games_format':
						switch ($value) {
							case esc_html__('Best of', 'arcane')." 1":
								$value = "bo1";
								break;
							case esc_html__('Best of', 'arcane')." 2":
								$value = "bo2";
								break;
							case esc_html__('Best of', 'arcane')." 3":
								$value = "bo3";
								break;
							case esc_html__('Best of', 'arcane')." 4":
								$value = "bo4";
								break;
							case esc_html__('Best of', 'arcane')." 5":
								$value = "bo5";
								break;
						}
						update_post_meta($post_id, 'tournament_games_format', $value);
						break;
					case 'tournament_platform':
						switch ($value) {
							case esc_html__('PS4', 'arcane'):
								$value = 'ps';
								break;
                            case esc_html__('PS5', 'arcane'):
                                $value = 'ps5';
                                break;
							case esc_html__('PC', 'arcane'):
								$value = 'pc';
								break;
							case esc_html__('Xbox', 'arcane'):
								$value = 'xbox';
								break;
							case esc_html__('Wii', 'arcane'):
								$value = 'wii';
								break;
							case esc_html__('Nintendo', 'arcane'):
								$value = 'nin';
								break;
							case esc_html__('Mobile', 'arcane'):
								$value = 'mobile';
								break;
							case esc_html__('Cross platform', 'arcane'):
								$value = 'cross';
								break;
						}
						update_post_meta($post_id, 'tournament_platform', $value);
						break;
					case 'tournament_server':
						update_post_meta($post_id, 'tournament_server', $value);
						break;
					case 'tournament_game_frequency':
						switch ($value) {
                            case esc_html__('Every 5 minutes', 'arcane'):
                                $value = '5 minutes';
                                break;
							case esc_html__('Every 15 minutes', 'arcane'):
								$value = '15 minutes';
								break;
							case esc_html__('Every 30 minutes', 'arcane'):
								$value = '30 minutes';
								break;
							case esc_html__('Every hour', 'arcane'):
								$value = '60 minutes';
								break;
							case esc_html__('Daily', 'arcane'):
								$value = 1;
								break;
							case esc_html__('Every 2 days', 'arcane'):
								$value = 2;
								break;
							case esc_html__('Every 3 days', 'arcane'):
								$value = 3;
								break;
							case esc_html__('Every 4 days', 'arcane'):
								$value = 4;
								break;
							case esc_html__('Every 5 days', 'arcane'):
								$value = 5;
								break;
							case esc_html__('Every 6 days', 'arcane'):
								$value = 6;
								break;
							case esc_html__('Every 7 days', 'arcane'):
								$value = 7;
								break;
							case esc_html__('Every two weeks', 'arcane'):
								$value = 14;
								break;
							case esc_html__('Monthly', 'arcane'):
								$value = 30;
								break;
						}
						update_post_meta($post_id, 'tournament_game_frequency', $value);
						break;
					case 'tournament_frequency':
						switch ($value) {
							case esc_html__('Daily', 'arcane'):
								$value = 'daily';
								break;
							case esc_html__('Weekly', 'arcane'):
								$value = 'weekly';
								break;
							case esc_html__('Monthly', 'arcane'):
								$value = 'monthly';
								break;
							case esc_html__('Yearly', 'arcane'):
								$value = 'yearly';
								break;
						}
						update_post_meta($post_id, 'tournament_frequency', $value);
						break;
					case 'game_modes':
						update_post_meta($post_id, 'game_modes', $value);
						break;

					case 'tournament_starts':
						update_post_meta($post_id, 'tournament_starts', $value);
						break;
					case 'tournament_starts_unix':
						$value = intval($value);
						if(strlen($value) == 13){
							$value = $value /1000;
						}
						$newTimestamp = strtotime('+1 years', $value);
						update_post_meta($post_id, 'tournament_starts_unix', $newTimestamp);
						break;
					case 'max_participants':
						$value = intval($value);
						update_post_meta($post_id, 'tournament_max_participants', $value);

						break;
					case 'prizes':

						if (is_array($value)) {
							array_shift($value);
						}
						update_post_meta($post_id, 'tournament_prizes', $value);
						break;
					case 'regulations':

						update_post_meta($post_id, 'tournament_regulations', $value);
						break;

					case 'maps':

						if (is_array($value)) {
							$current_maps = get_post_meta($post_id, 'tournament_maps', true);
							if ((!(is_array($current_maps))) && (strlen($current_maps) > 1)) {
								$current_maps = unserialize($current_maps);
							}
							if (!is_array($current_maps)) {
								$current_maps = array();
							}
							foreach ($value as $single) {
								$current_maps[$single] = $single;
							}
							update_post_meta($post_id, 'tournament_maps', $current_maps);
						} else {

							$games = $ArcaneWpTeamWars->get_game('');
							$gid = false;
							foreach ($games as $game) {
								if ($game == $data['game_name']) {
									$gid = $game->id;
								}
							}
							if ($gid != false) {
								$maps = $ArcaneWpTeamWars->get_map(array('game_id' =>$gid, 'order' => 'asc', 'orderby' => 'title'));
								$mapz[$maps[0]->id] = $maps[0]->id;
								update_post_meta($post_id, 'tournament_maps', $mapz);
							}

							//no maps selected, select first one, tusi

						}
				}
			}
			//check if we made maps
			if (!isset($data['maps'])) {
				$games = $ArcaneWpTeamWars->get_game('');
				$gid = false;
				foreach ($games as $game) {
					if ($game->title == $data['game_name']) {
						$gid = $game->id;
					}
				}
				if ($gid != false) {
					$maps = $ArcaneWpTeamWars->get_map(array('game_id' =>$gid, 'order' => 'asc', 'orderby' => 'title'));
					$mapz[$maps[0]->id] = $maps[0]->id;
					update_post_meta($post_id, 'tournament_maps', $mapz);
				}
			}
			echo "ok-_-".get_permalink($post_id);
		} else {
			echo "not ok-_-";
		}
		wp_die();
	}
}
add_action( 'wp_ajax_leave_tournament', 'arcane_ajax_leave_tournament' );
add_action( 'wp_ajax_nopriv_leave_tournament', 'arcane_ajax_leave_tournament' );


if(!function_exists('arcane_ajax_leave_tournament')){
	function arcane_ajax_leave_tournament() {
		$tid = filter_var($_POST['tid'], FILTER_SANITIZE_NUMBER_INT);

		if (!$tid) {
			exit;
		}
		$uid = arcane_get_my_ID_in_tournament($tid);
		if (!$uid) {
			exit;
		}

		$options = arcane_get_theme_options();
		if(!isset($options['tournament_approve_user']))$options['tournament_approve_user'] = true;

		if($options['tournament_approve_user']){
			$candidates = get_post_meta($tid, 'tournament_candidate', true);

			if (isset($candidates[$uid])) {
				unset($candidates[$uid]);
				update_post_meta($tid, 'tournament_candidate', $candidates);
			}

		}


        $competitors = get_post_meta($tid, 'tournament_competitors', true);

		if (isset($competitors[$uid])) {

            unset($competitors[$uid]);
            update_post_meta($tid, 'tournament_competitors', $competitors);

            $format = get_post_meta($tid, 'format', true);

            if(strtolower($format) == 'ladder') {
                $participants = get_post_meta($tid, 'participant_cache', true);
                if($participants) {
                    $del = false;
                    foreach ($participants as $key => $one) {
                        if ($one['id'] == $uid) {
                            unset($participants[$key]);
                            $del = true;
                        }

                        //delete his challenges
                        if(!empty($one['challenges']['received'])){
                            foreach ($one['challenges']['received'] as $key1 => $received){
                                if($received['from'] == $uid)
                                    unset($participants[$key]['challenges']['received'][$key1]);
                            }

                        }

                    }
                    if($del) {
                        $participants = array_values($participants);
                        array_unshift($participants,"");
                        unset($participants[0]);
                        update_post_meta($tid, 'participant_cache', $participants);
                    }
                }
            }

        }

		//Remove tournament from user meta
		$contestants = get_post_meta($tid, 'tournament_contestants', true);
		if($contestants == ' user'){
		    $tournaments_in = get_user_meta(get_current_user_id(), 'tournaments_in', true);
			unset($tournaments_in[$tid]);
			update_user_meta(get_current_user_id(), 'tournaments_in', $tournaments_in);
        }

		$type = strtolower(get_post_meta($tid, 'tournament_contestants', true) );
		$author_id = get_post_field( 'post_author', $tid );
		arcane_user_left_tournament($author_id, $tid, $uid, $type);

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $author_id,
				'item_id'           => $tid,
				'secondary_item_id' => $uid,
				'component_name'    => 'tournaments',
				'component_action'  => 'tournament_left',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );

		}

		exit;
	}
}


add_action( 'wp_ajax_rejects_user', 'arcane_ajax_rejects_user' );
add_action( 'wp_ajax_nopriv_rejects_user', 'arcane_ajax_rejects_user' );

if(!function_exists('arcane_ajax_rejects_user')){
	function arcane_ajax_rejects_user () {
		$tid = filter_var($_POST['tid'], FILTER_SANITIZE_NUMBER_INT);
		$uid = filter_var($_POST['uid'], FILTER_SANITIZE_NUMBER_INT);

		if (empty($tid)) {
			wp_die();
		}

		$type = strtolower(get_post_meta($tid, 'tournament_contestants', true) );

		if (($type == "team") or ($type == "teams")) {
			$teams = arcane_get_user_teams($uid);
		}

		$current = get_post_meta($tid, 'tournament_competitors', true);

		if (!is_array($current)) {
			$current = array();
		}

		$my_id = $uid;


		if (($type == "team") or ($type == "teams")) {
			if (!in_array($my_id, $teams)) {
				//user is not in requesting team, like wat, just give up
				wp_die();
			}
		}

		if ($my_id) {
			if (isset($current[$my_id])) {
				unset($current[$my_id]);
			}
		}

		update_post_meta($tid, 'tournament_competitors', $current);

		ArcaneUpdateUserTournaments($uid);
		wp_die();
	}
}

add_action( 'wp_ajax_confirm_user', 'arcane_ajax_confirm_user' );
add_action( 'wp_ajax_nopriv_confirm_user', 'arcane_ajax_confirm_user' );

if(!function_exists('arcane_ajax_confirm_user')){
	function arcane_ajax_confirm_user(){
		$con = filter_var($_POST['con'], FILTER_SANITIZE_STRING);
		$tid = filter_var($_POST['tid'], FILTER_SANITIZE_NUMBER_INT);
		$uid = filter_var($_POST['uid'], FILTER_SANITIZE_NUMBER_INT);

		if (arcane_TournamentsCanEdit($tid)) {

			$candidate = get_post_meta($tid, 'tournament_candidate', true);

			if($con=="accept"){

				$competitors = get_post_meta($tid, 'tournament_competitors', true);
				if (!is_array($competitors)) {
					$competitors = array();
				}
				$competitors[$uid] = $uid;
				update_post_meta($tid, 'tournament_competitors', $competitors);
				ArcaneUpdateUserTournaments($uid);

				//games_created
				$format = get_post_meta($tid, 'format', true);
				if(strtolower($format) == 'ladder') {
					$participants = get_post_meta($tid, 'participant_cache', true);
					if($participants) {
					    foreach ($participants as $participant){
						    if($participant['id'] == $uid){
							    if (isset($candidate[$uid])) {
								    unset($candidate[$uid]);
								    update_post_meta($tid, 'tournament_candidate', $candidate);
							    }
							    die();
                            }

                        }
						$contestants = get_post_meta($tid, 'tournament_contestants', true);
						$new_participants['id'] = $uid;
						$new_participants['position'] = 'unset';
						$new_participants['score']['games'] = '0';
						$new_participants['score']['victories'] = '0';
						$new_participants['score']['defeats'] = '0';
						$new_participants['score']['draws'] = '0';
						$new_participants['score']['points'] = '0';
						if($contestants == 'team') {
							$new_participants['name'] =  get_the_title($uid);
							$new_participants['url'] =  get_permalink($uid);
							$notify_user = arcane_return_super_admin($uid);

							arcane_user_accept_in_tournament($notify_user, $tid);

							if (function_exists ('bp_notifications_add_notification')) {
								bp_notifications_add_notification( array(
									'user_id'           => $notify_user,
									'item_id'           => $tid,
									'component_name'        => 'tournaments',
									'component_action'  => 'tournament_join_accepted',
									'date_notified'     => bp_core_current_time(),
									'is_new'            => 1,
								) );
							}


						} else {
							$user1 = get_user_by('id',$uid);
							arcane_user_accept_in_tournament($uid, $tid);
							$new_participants['name'] =$user1->display_name;
							$new_participants['url'] = bp_core_get_user_domain($uid);
							if (function_exists ('bp_notifications_add_notification')) {
								bp_notifications_add_notification( array(
									'user_id'           => $uid,
									'item_id'           => $tid,
									'component_name'        => 'tournaments',
									'component_action'  => 'tournament_join_accepted',
									'date_notified'     => bp_core_current_time(),
									'is_new'            => 1,
								) );
							}
						}
						$participants[] = $new_participants;
						update_post_meta($tid, 'participant_cache', $participants);
					}
				}

				$contestants = get_post_meta($tid, 'tournament_contestants', true);
				if($contestants == 'team') {
					$notify_user = arcane_return_super_admin($uid);
					arcane_user_accept_in_tournament($notify_user, $tid);
					if (function_exists ('bp_notifications_add_notification')) {
						bp_notifications_add_notification( array(
							'user_id'           => $notify_user,
							'item_id'           => $tid,
							'component_name'        => 'tournaments',
							'component_action'  => 'tournament_join_accepted',
							'date_notified'     => bp_core_current_time(),
							'is_new'            => 1,
						) );
					}
				} else {
					arcane_user_accept_in_tournament($uid, $tid);
					if (function_exists ('bp_notifications_add_notification')) {
						bp_notifications_add_notification( array(
							'user_id'           => $uid,
							'item_id'           => $tid,
							'component_name'        => 'tournaments',
							'component_action'  => 'tournament_join_accepted',
							'date_notified'     => bp_core_current_time(),
							'is_new'            => 1,
						) );
					}
				}
				$data = 'accepted';

			} elseif($con=="reject"){

				$data = 'rejected';

			}

			if (isset($candidate[$uid])) {
				unset($candidate[$uid]);
				update_post_meta($tid, 'tournament_candidate', $candidate);
			}

			echo json_encode($data);
		}
		die();

	}
}


add_action( 'wp_ajax_kick_user', 'arcane_ajax_kick_user_lt' );
add_action( 'wp_ajax_nopriv_kick_user', 'arcane_ajax_kick_user_lt' );
if(!function_exists('arcane_ajax_kick_user_lt')){
	function arcane_ajax_kick_user_lt(){
		//global $ArcaneWpTeamWars;
		$con = filter_var($_POST['con'], FILTER_SANITIZE_STRING);
		$tid = filter_var($_POST['tid'], FILTER_SANITIZE_NUMBER_INT);
		$uid = filter_var($_POST['uid'], FILTER_SANITIZE_NUMBER_INT);

		if (arcane_TournamentsCanEdit($tid)) {

			if($con=="kick"){

				$competitors = get_post_meta($tid, 'tournament_competitors', true);
				if (isset($competitors[$uid])) {

					$delete_competitors = get_post_meta($tid, 'tournament_delete_competitors', true);
					if (!is_array($delete_competitors)) {
						$delete_competitors = array();
					}
					$delete_competitors[$uid] = $uid;
					update_post_meta($tid, 'tournament_delete_competitors', $delete_competitors);

					unset($competitors[$uid]);
					update_post_meta($tid, 'tournament_competitors', $competitors);

					$all = false;
					$format = get_post_meta($tid, 'format', true);
					if((strtolower($format) == 'league') || ($format == 'rrobin')) {
						$all = 1;
					}
					arcane_update_match_ut($uid, $tid, $all );

					if(strtolower($format) == 'ladder') {
						$participants = get_post_meta($tid, 'participant_cache', true);
						if($participants) {
							$del = false;
							foreach ($participants as $key => $one) {
								if ($one['id'] == $uid) {
									unset($participants[$key]);
									$del = true;
								}

								//delete his challenges
								if(!empty($one['challenges']['received'])){
									foreach ($one['challenges']['received'] as $key1 => $received){
										if($received['from'] == $uid)
											unset($participants[$key]['challenges']['received'][$key1]);
									}

								}

							}
							if($del) {
								$participants = array_values($participants);
								array_unshift($participants,"");
								unset($participants[0]);
								update_post_meta($tid, 'participant_cache', $participants);
							}
						}
					}

					if(strtolower($format) == 'royale') {
						$participants = get_post_meta($tid, 'participant_cache', true);
						if($participants) {
							$del = false;
							foreach ($participants as $key => $one) {
								if ($one['id'] == $uid) {
									unset($participants[$key]);
									$del = true;
								}
							}
							if($del) {
								$participants = array_values($participants);
								array_unshift($participants,"");
								unset($participants[0]);
								update_post_meta($tid, 'participant_cache', $participants);
							}
						}
					}

					$args = array(
						'meta_key' => 'tournament_id',
						'meta_value' => $tid,
						'post_type' => 'matches',
						'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
						'numberposts' => -1,
					);
					$postz = get_posts($args);

					foreach ($postz as $p){
						$team1 = get_post_meta($p->ID, 'team1', true);
						$team2 = get_post_meta($p->ID, 'team2', true);

						if($team1 == $uid || $team2 == $uid){
							wp_delete_post($p->ID);

							$games = get_post_meta($tid, 'game_cache', true);
							if(!empty($games))
								foreach ($games as $key => $game){
									if($game['match_post_id'] == $p->ID){
										unset($games[$key]);
										update_post_meta($tid, 'game_cache', $games );
                                    }

								}
                        }

					}

					$data = 'accepted';
					echo json_encode($data);
				}

			}

		}

		exit;
	}
}


//removes quick edit from custom post type list
if(!function_exists('arcane_remove_quick_edit')){
	function arcane_remove_quick_edit( $actions ) {
		global $post;
		if( $post->post_type == 'tournament' ) {
			unset($actions['inline hide-if-no-js']);
		}
		return $actions;
	}

	if (is_admin()) {
		add_filter('post_row_actions','arcane_remove_quick_edit',10,2);
	}
}


if(!function_exists('arcane_filter_function_name')){
	function arcane_filter_function_name( $content, $post_id ) {
		// Process content here
		$post = get_post($post_id);

		if ((isset($_GET['action'])) && ($_GET['action'] == "edit")){
			if ($post->post_type == "tournament") {
				$link = get_permalink ($post_id);
				header('Location: '.$link.'?edit');

			}
		}

		return $content;
	}
}
add_filter( 'content_edit_pre', 'arcane_filter_function_name', 10, 2 );

if(!function_exists('arcane_profile_show_tourneys')){
	function arcane_profile_show_tourneys() {
		global $ArcaneWpTeamWars;
		$cuser = get_current_user_id();
		$games = $ArcaneWpTeamWars->get_game('');
		$options['tournament_creation'] = true;
		$options = arcane_get_theme_options();
		if(!isset($options['tournament_creation']))$options['tournament_creation'] = true;

		$u = wp_get_current_user();
		if(isset($u->ID))
			$usermeta = get_user_meta($u->ID);
		?>
        <div class="profile-tournaments-selector">
            <ul class="nav nav-tabs " role="tablist">
				<?php if($options['tournament_creation'] == '1' || (isset($usermeta['_checkbox_tournament_user'][0]) && $usermeta['_checkbox_tournament_user'][0] == "yes")){ ?>
                    <li role="presentation" class="active"><a href="#my_created_tournaments" aria-controls="my_created_tournaments" role="tab" data-toggle="tab"><?php esc_html_e ("Published", "arcane"); ?></a></li>
                    <li role="presentation"><a href="#my_draft_tournaments" aria-controls="my_draft_tournaments" role="tab" data-toggle="tab"><?php esc_html_e ("Drafts", "arcane"); ?></a></li>
				<?php } ?>
                <li role="presentation"><a href="#my_joined_tournaments" aria-controls="my_joined_tournaments" role="tab" data-toggle="tab"><?php esc_html_e ("Joined", "arcane"); ?></a></li>
            </ul>

            <div class='tab-content'>
		        <?php if($options['tournament_creation'] == '1' || (isset($usermeta['_checkbox_tournament_user'][0]) && $usermeta['_checkbox_tournament_user'][0] == "yes")){ ?>
                    <div class="tab-pane active" role="tabpanel"  id="my_created_tournaments">
				        <?php
				        $args = array(
					        'post_type' => 'tournament',
					        'posts_per_page' => -1,
					        'author' => $cuser,
					        'post_status' => 'publish',

				        );
				        $posts = get_posts($args);
				        if (is_array($posts)) {
					        echo '<ul class="tournaments-list">';
					        foreach ($posts as $single) {
						        echo arcane_return_tournament_block($single->ID, $games, true);
					        }
					        echo  '</ul>';
				        }

				        if(empty($posts)){
					        echo '<div class="error">';
					        esc_html_e("There are no published tournaments at the moment!", 'arcane');
					        echo '</div>';
				        }
				        wp_reset_postdata();
				        ?>
                    </div>

                    <div class="tab-pane" role="tabpanel"  id="my_draft_tournaments">
				        <?php
				        $args = array(
					        'post_type' => 'tournament',
					        'posts_per_page' => -1,
					        'post_status' => 'draft',
					        'author' =>$cuser
				        );
				        $posts = get_posts($args);
				        if (is_array($posts)) {
					        echo '<ul class="tournaments-list">';
					        foreach ($posts as $single) {
						        echo arcane_return_tournament_block($single->ID, $games, true);
					        }
					        echo  '</ul>';
				        }

				        if(empty($posts)){
					        echo '<div class="error">';
					        esc_html_e("You haven't saved any drafts!", 'arcane');
					        echo '</div>';
				        }
				        wp_reset_postdata();
				        ?>
                    </div>
		        <?php } ?>
                <div class="tab-pane" role="tabpanel"  id="my_joined_tournaments">
			        <?php
			        echo '<ul class="tournaments-list">';
			        $mymeta = get_user_meta($cuser, 'tournaments_in', true);
			        if (is_array($mymeta)) {
				        foreach ($mymeta as $single) {
					        if(get_post_meta($single,'tournament_contestants', true) == 'user')
						        echo arcane_return_tournament_block($single, $games, false);
				        }

			        }
			        echo  '</ul>';
			        if(empty($mymeta)){
				        echo '<div class="error">';
				        esc_html_e("You haven't joined any tournaments!", 'arcane');
				        echo '</div>';
			        }
			        ?>
                </div>

            </div>
        </div>

		<?php

	}
}


if(!function_exists('arcane_return_tournament_block')){
	function arcane_return_tournament_block($tid,$games, $ismine=false) {

		$tournament_timezone = get_post_meta($tid, 'tournament_timezone', true );
		date_default_timezone_set($tournament_timezone);
		$current_time = new DateTime("now", new DateTimeZone($tournament_timezone) );
		$current_time = $current_time->getTimestamp();

		$return = '';
		$pmeta = get_post_meta($tid);
		$finished = false;
		$premium_plugin = false;
		$gamez =  get_post_meta($tid, 'tournament_game', true);


		$options = arcane_get_theme_options();
		if(!isset($options['premium_button_text']))$options['premium_button_text'] = esc_html__("Buy premium", 'arcane');
		if(!isset($options['premium_pending_button_text']))$options['premium_pending_button_text'] = esc_html__("Premium pending", 'arcane');
		$join_any = false;
		$go_ahead = false;
		$game_name = get_post_meta($tid, 'tournament_game', true);
		$product_id = $user_pay = $product_game_id = $general_product_id = false;
		$link = '';
		$link_game = '';
		$pending = false;
		if (function_exists( 'arcane_get_product_id_by_tournament_id' ) && class_exists( 'WooCommerce' )  ) {
			$product_id = arcane_get_product_id_by_tournament_id( $tid );
			$product_game_id = arcane_get_product_id_by_game_id(arcane_return_game_id_by_game_name($game_name));
			if(count($product_game_id) > 1){
				$terms = get_the_terms ( $product_game_id[0]->post_id, 'product_cat' );
				$cat_id = $terms[0]->term_id;
				$link_game = get_term_link($cat_id);
			}else{
				if(isset($product_game_id[0]->post_id))
					$link_game = get_permalink($product_game_id[0]->post_id);
			}
			$user_pay = arcane_check_payment_to_join($tid);
			$general_product_id = arcane_get_product_id_for_general_membership();
			if(count($general_product_id) > 1){
				$terms = get_the_terms ( $general_product_id[0]->post_id, 'product_cat' );
				$cat_id = $terms[0]->term_id;
				$link = get_term_link($cat_id);
			}else{
				if(isset($general_product_id[0]->post_id))
					$link = get_permalink($general_product_id[0]->post_id);
			}

			$customer_orders = wc_get_orders( array(
				'meta_key' => '_customer_user',
				'meta_value' => get_current_user_id(),
				'post_status' => array('wc-on-hold', 'wc-processing'),
				'numberposts' => -1
			) );

			foreach($customer_orders as $customer_order){
				$items = $customer_order->get_items();
				foreach($items as $item){
					if($item['product_id'] == (int)$product_id){
						$pending = true;
					}

					if(isset($product_game_id[0]->post_id) && ($item['product_id'] == (int)$product_game_id[0]->post_id)){
						$pending = true;
					}

					if(isset($general_product_id[0]->post_id) && ($item['product_id'] == (int)$general_product_id[0]->post_id)){
						$pending = true;
					}


				}

			}
		}

		if (function_exists('arcane_register_pay_to_join_product_type')){
			$premium_plugin = true;
		}


		$premium_tournament = '';

		if(get_post_meta($tid, 'premium', true))
			$premium_tournament = get_post_meta($tid, 'premium', true);


		$premium_user = get_user_meta(get_current_user_id(),'premium',true);
		$premium_user_tournament = get_user_meta(get_current_user_id(),'user_premium_tournament',true);


		$proceed = false;
		if(isset($premium_user_tournament) && !empty($premium_user_tournament)){
			$premium_user_tournament = explode(',', $premium_user_tournament);
			if(in_array($tid,$premium_user_tournament) && $premium_user == 'true'){
				$proceed = true;
			}
		}elseif($premium_user == 'true' && (empty($premium_user_tournament))){
			$proceed = true;
		}

		//$starts = get_post_meta($tid)
		$t_start_unix = get_post_meta($tid,"tournament_starts_unix",true);

		if(isset($t_start_unix)){
			$strtime = $t_start_unix;
		}elseif(isset($pmeta['tournament_starts'])){
			$strtime = strtotime($pmeta['tournament_starts'][0]);
		}else{
			$strtime = $current_time;
        }


		if (isset($pmeta['tournament_prizes'][0])) {
			$prizes = unserialize($pmeta['tournament_prizes'][0]);//$prizes = get_post_meta($tid,"tournament_prizes", true);
		}

		//$competitors = count(get_post_meta($tid,"tournament_competitors", true));
		if (isset($pmeta["tournament_competitors"][0]) AND (is_array(unserialize($pmeta["tournament_competitors"][0])))){
			$competitors_number = count(unserialize($pmeta["tournament_competitors"][0]));
		} else {
			$competitors_number = 0;
		}
		$contestant = $pmeta["tournament_contestants"][0];


		//$max_competitors = get_post_meta($tid,"tournament_max_participants", true);
		if (isset($pmeta["tournament_max_participants"][0])){
			$max_competitors_number = $pmeta["tournament_max_participants"][0];
		} else {
			$max_competitors_number = 1;
		}


		if (isset($pmeta['tournament_game'][0])) {
			$elgame = $pmeta['tournament_game'][0];
		} else {
			$elgame = 1;
		}

		$output_image = "";
		$g_id = 0;
		foreach ($games as $game) {
			if ($game->title == $elgame) {

				$img_url = wp_get_attachment_url ($game->icon);
				$image = arcane_aq_resize( $img_url, 85, 118, true, true, true ); //resize & crop img
				$g_id = $game->id;

				if (!empty($image)) {
					$output_image = $image;
				} else {
					$output_image = get_theme_file_uri('img/defaults/gamedefault.jpg');
				}
			}
		}

		if(empty($output_image))$output_image = get_theme_file_uri('img/defaults/gamedefault.jpg');
		$return.= '<li class="citem">';

		/*check if tournament finished*/

		$rounds = get_post_meta($tid, 'game_cache', true);
		$game_stop =  get_post_meta($tid, 'game_stop', true);
		$tformat =  get_post_meta($tid, 'format', true);

		if(strtolower($tformat) == 'ladder'){
			if($game_stop == 1) $finished = true;

		}elseif(!empty($rounds)){
			$finished = true;

			if(strtolower($tformat) == 'ladder'){
				if($game_stop != 1) $finished = false;
			}elseif(strtolower($tformat) == 'knockout'){

				foreach ($rounds as $round) {
					if(is_array($round)){
						foreach ((array)$round as $single_game) {
							if(empty($single_game['score']))$finished = false;
						}
					}
				}

			}elseif(strtolower($tformat) == 'league'){

				foreach ($rounds as $round) {
					foreach ($round as $single_game) {
						if(is_array($single_game)){
							foreach ($single_game as $team) {
								if(is_array($team)){
									foreach ($team as $team_score) {
										if(!isset($team_score['score']))$finished = false;
									}
								}
							}
						}
					}
				}

			}elseif(strtolower($tformat) == 'rrobin'){

				$playoffs_started =  get_post_meta($tid, 'playoffs_started', true);
				if(!isset($playoffs_started) or $playoffs_started != '1'){
					$finished = false;
				}else{
					foreach ($rounds['playoffs'] as $round) {
						if(is_array($round)){
							foreach ((array)$round as $single_game) {
								if(empty($single_game['score']))$finished = false;
							}
						}
					}
				}

			}else{
				foreach ($rounds as $round) {
					foreach ($round as $single_game) {
						if(is_array($single_game)){
							foreach ($single_game as $team) {

								if(is_array($team) && !isset($team['match_post_id'])){
									foreach ($team as $team_score) {
										if(!isset($team_score['score']))$finished = false;
									}
								}else{
									if(is_array($team) ){
										foreach($team as $s_team){
											if(is_array($s_team) ){
												foreach($s_team as $stim){
													if(!isset($stim['score']))$finished = false;
												}
											}

										}
									}

								}
							}
						}
					}
				}

			}

		}else{
			$finished = false;
		}



		$return.= '<div class="tlinfow">';

		$return.= '<a href="'.get_permalink($tid).'">';
		$return.= '<img alt="img" src="'.esc_url($output_image).'" />';
		$return.= '</a>';

		$return.= '<div class="tlist-info">';

            $return.= '<a href="'.get_permalink($tid).'"';
            if($premium_plugin && $premium_tournament){
                $return .= 'class="ptour-title"';
            }
        $return.= '>';
            $return.= get_the_title($tid);

        if($premium_plugin && $premium_tournament){
            $return .= '<i class="fas fa-star" aria-hidden="true"></i>';
        }
        if($finished){
            $return.= '<div class="tournament-finished">';
            $return.= esc_html__('Finished', 'arcane');
            $return.= '</div>';

        }

            $return.= '</a>';

            $return.= '<span>';
            $return.= $gamez;
            $return.= '</span>';

            $return.= '<small>';
            $return.= '<i data-feather="users"></i>';
            $return.= $competitors_number.'/'.$max_competitors_number;
            $return.= ' '.ucfirst($contestant).esc_html__('s Registered', 'arcane');
            $return.= '</small>';

		$return.= '</div>';


		$return.= '<div class="tlist-join">';

		    $return.= '<small>';
		    $return.= esc_html__('Start date:', 'arcane');
		    $return.= '</small>';

		    $return.= '<strong>';
		    $return.= get_post_meta($tid, 'tournament_starts', true);
		    $return.= '</strong>';


		$myid = arcane_get_my_ID_in_tournament($tid);

		$contestantz =  get_post_meta($tid, 'tournament_contestants', true);

		if (($strtime > $current_time && is_user_logged_in())){

			if ($myid) {
				if ($contestantz == "team" ) {
					$post = get_post($myid);
					//check if user is admin of the team
					if(arcane_is_admin($post->ID,get_current_user_id())) {
						$return .= '<a class="btn leave_tournament"  data-gid="' . esc_attr( $g_id ) . '" data-tid="' . esc_attr( $tid ) . '" data-ttype="' . esc_attr( $contestantz ) . '"> ' . esc_html__( "Leave Now", "arcane" ) . '</a>';
					}
				} else {
					$return.=  '<a class="btn leave_tournament" data-gid="'.esc_attr($g_id).'" data-tid="'.esc_attr($tid).'" data-ttype="'.esc_attr($contestantz).'"> '.esc_html__("Leave Now","arcane").'</a>';
				}
			} else {

				$candidate = get_post_meta($tid, 'tournament_candidate', true);
				if  ( isset($candidate) && is_array($candidate) && (!empty($candidate)) && (in_array(get_current_user_id(), $candidate)) ) {
					$return.= '<span class="btn jtournamentb disabled" data-gid="'.$g_id.'" data-tid="'.esc_attr($tid).'" data-ttype="'.esc_attr($contestantz).'" data-toggle="tooltip" data-placement="top" title="'.esc_html__("Your application is under consideration","arcane").'">'.esc_html__("under consideration","arcane").'</span>';

				} else {

					if($contestantz == "team"){
						global $ArcaneWpTeamWars;
						$teams = arcane_get_user_teams(get_current_user_id());
						$teamarray = $ArcaneWpTeamWars->get_team(array('id' => $teams));

						$foundid = false;

						foreach ($games as $game) {
							if ($game->title == $gamez) {
								$foundid = $game->id;
							}
						}
						$tempteamsarray = array();
						$tempteams = array();
						foreach ($teamarray as  $single) { if(!isset($single->games)) continue;
							if (in_array($foundid, $single->games)) {
								$tempteamsarray[] = $single;
								$tempteams[] = $single->ID;
							}
						}

						$teams = $tempteams;


						if (is_array($teams) AND (isset($teams[0]))) {

							if($premium_plugin && $premium_tournament) {

								if($proceed){
									$go_ahead = true;
								}elseif($product_id && $user_pay == false){
									if($pending){
										$return.= '<a class="btn"> '.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.get_permalink($product_id).'"> '.$options['premium_button_text'].'</a>';
									}
								}elseif($product_game_id && $user_pay == false){
									if($pending){
										$return.= '<a class="btn"> '.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.esc_url($link_game).'"> '.$options['premium_button_text'].'</a>';
									}
								}elseif($general_product_id && $join_any == false && !$user_pay){
									if($pending){
										$return.= '<a class="btn"> '.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.esc_url($link).'"> '.$options['premium_button_text'].'</a>';
									}
								}else{
									$go_ahead = true;
								}

							}

							//check if user is admin or super admin
							$teamsForChallenge = arcane_get_user_teams_for_challenge(get_current_user_id());

							if(strtolower($tformat) == 'ladder' && !$premium_plugin ) $go_ahead = true;

							if(!$premium_plugin || $go_ahead || !$premium_tournament AND ($competitors_number < $max_competitors_number) AND !$finished AND count($teamsForChallenge) > 0) {
								$return.= '<a  class="btn join_tournament" data-gid="'.$g_id.'" data-tid="'.esc_attr($tid).'" data-ttype="'.esc_attr($contestantz).'" > '.esc_html__("Join now", "arcane").'</a>';
							}

						}else{

							if($premium_plugin && $premium_tournament) {
								if($proceed){
									$go_ahead = true;
								}elseif($product_id && $user_pay == false){
									if($pending){
										$return.= '<a class="btn">'.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.get_permalink($product_id).'"> '.$options['premium_button_text'].'</a>';
									}
								}elseif($product_game_id && $user_pay == false){
									if($pending){
										$return.= '<a class="btn">'.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.esc_url($link_game).'">'.$options['premium_button_text'].'</a>';
									}
								}elseif($general_product_id && $join_any == false && !$user_pay){
									if($pending){
										$return.= '<a class="btn">'.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.esc_url($link).'">'.$options['premium_button_text'].'</a>';
									}
								}else{
									$go_ahead = true;
								}

							}

							//check if user is admin or super admin
							$teamsForChallenge = arcane_get_user_teams_for_challenge(get_current_user_id());

							if(strtolower($tformat) == 'ladder' && !$premium_plugin) $go_ahead = true;

							if(!$premium_plugin || $go_ahead || !$premium_tournament AND ($competitors_number < $max_competitors_number) AND !$finished AND count($teamsForChallenge) > 0) {

								$return.=  '<a class="btn jtournamentb disabled" data-gid="'.$g_id.'" data-tid="'.esc_attr($tid).'" data-ttype="'.esc_attr($contestantz).'" data-toggle="tooltip" data-placement="top" title="'.esc_html__("To join this tournament you must be an active member of a team that plays this game!","arcane").'">'.esc_html__("Join now","arcane").'</a>';

							}
						}

					} else {
						if(is_user_logged_in()){

							if($premium_plugin && $premium_tournament) {

								if($proceed){
									$go_ahead = true;
								}elseif($product_id && $user_pay == false){
									if($pending){
										$return.= '<a class="btn">'.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.get_permalink($product_id).'">'.$options['premium_button_text'].'</a>';
									}
								}elseif($product_game_id && $user_pay == false){
									if($pending){
										$return.= '<a class="btn">'.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.esc_url($link_game).'">'.$options['premium_button_text'].'</a>';
									}
								}elseif($general_product_id && $join_any == false && !$user_pay){
									if($pending){
										$return.= '<a class="btn">'.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.esc_url($link).'">'.$options['premium_button_text'].'</a>';
									}
								}else{
									$go_ahead = true;
								}

							}



							if(strtolower($tformat) == 'ladder' && !$premium_plugin) $go_ahead = true;

							if(!$premium_plugin || $go_ahead || !$premium_tournament AND ($competitors_number < $max_competitors_number) AND !$finished) {
								$return.= '<a  class="btn join_tournament" data-gid="'.$g_id.'" data-tid="'.esc_attr($tid).'" data-ttype="'.esc_attr($contestantz).'" >'.esc_html__("Join now","arcane").'</a>';
							}

						}else{


							if($premium_plugin && $premium_tournament) {
								if($proceed){
									$go_ahead = true;
								}elseif($product_id && $user_pay == false){
									if($pending){
										$return.= '<a class="btn">'.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.get_permalink($product_id).'">'.$options['premium_button_text'].'</a>';
									}
								}elseif($product_game_id && $user_pay == false){
									if($pending){
										$return.= '<a class="btn">'.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.esc_url($link_game).'">'.$options['premium_button_text'].'</a>';
									}
								}elseif($general_product_id && $join_any == false && !$user_pay){
									if($pending){
										$return.= '<a class="btn">'.$options['premium_pending_button_text'].'</a>';
									}else{
										$return.= '<a class="btn" href="'.esc_url($link).'">'.$options['premium_button_text'].'</a>';
									}
								}else{
									$go_ahead = true;
								}

							}

							if(strtolower($tformat) == 'ladder' && !$premium_plugin) $go_ahead = true;

							if(!$premium_plugin || $go_ahead || !$premium_tournament AND ($competitors_number < $max_competitors_number) AND !$finished) {
								$return.= '<a class="btn jtournamentb disabled" data-toggle="tooltip" data-placement="top" title="'.esc_html__("To join this tournament you must be logged in!","arcane").'"> '.esc_html__("Join now","arcane").'</a>';
							}
						}
					}

				}
			}
		}elseif(is_user_logged_in()){

			$go_ahead = false;
			if(strtolower($tformat) == 'ladder' && !$premium_plugin) $go_ahead = true;
			if($go_ahead && ($competitors_number < $max_competitors_number) && !$finished && !$myid) {
				$return .= '<a  class="btn join_tournament" data-gid="'.esc_attr($g_id).'" data-tid="'.esc_attr($tid).'" data-ttype="'.esc_attr($contestantz).'" >'.esc_html__("Join now","arcane").'</a>';
			}
		}


		$return.= '</div>';

		$arcane_tournament_mapping = arcane_tournament_mapping();
		$fortm = $arcane_tournament_mapping[$pmeta['format'][0]];

		$return.= '<div class="tlinfoextra">';

		    $return.= '<div class="tlist-join">';
            $return.= '<i data-feather="git-branch"></i>';
		    $return.= '<small>';
		    $return.= esc_html__('Tournament type', 'arcane');
		    $return.= '</small>';
            $return.= '<strong>';
            $return.= $fortm;
            $return.= '</strong>';
		    $return.= '</div>';

            $return.= '<div class="tlist-join">';
            $return.= '<i data-feather="monitor"></i>';
            $return.= '<small>';
            $return.= esc_html__('Platform', 'arcane');
            $return.= '</small>';
            $return.= '<strong>';

		    $tournament_platform = get_post_meta($tid, 'tournament_platform', true);
            switch ($tournament_platform) {
                case "ps" :
	                $return.=esc_html__("PS4",'arcane');
                    break;
                case "ps5" :
                    $return.=esc_html__("PS5",'arcane');
                    break;
                case "pc" :
	                $return.=esc_html__("PC",'arcane');
                    break;
                case "xbox" :
	                $return.=esc_html__("Xbox",'arcane');
                    break;
                case "wii" :
	                $return.=esc_html__("Wii",'arcane');
                    break;
                case "nin" :
	                $return.=esc_html__("Nintendo",'arcane');
                    break;
                case "cross" :
	                $return.=esc_html__("Cross platform",'arcane');
                    break;
                case "mobile" :
	                $return.=esc_html__("Mobile",'arcane');
                    break;
            }


            $return.= '</strong>';
            $return.= '</div>';

            if(!empty($prizes)) {
                $return.= '<div class="tlist-join tlist-prize">';
                $return.= '<i data-feather="gift"></i>';
                $return.= '<small>';
                $return.= esc_html__('Prize', 'arcane');
                $return.= '</small>';
                $return.= '<strong>';
                $return .= esc_attr( implode( ", ", array_filter( $prizes ) ) );
                $return.= '</strong>';
                $return.= '</div>';
            }

            $return.= '<div class="tlist-join">';
            $return.= '<i data-feather="crosshair"></i>';
            $return.= '<small>';
            $return.= esc_html__('Game format', 'arcane');
            $return.= '</small>';
            $return.= '<strong>';

		    $games_format = get_post_meta($tid, 'tournament_games_format', true);

            if ($games_format == "bo1") {
	            $return .=esc_html__("Best of 1",'arcane');
            } elseif ($games_format == "bo2") {
	            $return .=esc_html__("Best of 2",'arcane');
            } elseif ($games_format == "bo3") {
	            $return .=esc_html__("Best of 3",'arcane');
            } elseif ($games_format == "bo4") {
	            $return .=esc_html__("Best of 4",'arcane');
            } elseif ($games_format == "bo5") {
	            $return .=esc_html__("Best of 5",'arcane');
            }

            $return.= '</strong>';
            $return.= '</div>';

		$return.= '</div>';


    $return.= '</div>';






		$return.= '</li>';
		return $return;


	}
}



#-----------------------------------------------------------------#
# Notifications
#-----------------------------------------------------------------#
if(!function_exists('arcane_set_content_type')){
	function arcane_set_content_type(){
		return "text/html";
	}
}
add_filter( 'wp_mail_content_type','arcane_set_content_type' );
if(!function_exists('arcane_challenge_sent')){
	function arcane_challenge_sent($user_id, $post_id){
		$message = '';
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$subject = esc_html__("New challenge! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Please visit this link to view and manage this challenge: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);
		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}


		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'challenge_sent',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_challenge_accepted')){
	function arcane_challenge_accepted($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$message = '';
		$subject = esc_html__("Challenge accepted! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Please visit this link to view this challenge: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);
		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}


		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'challenge_accepted',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_challenge_rejected')){
	function arcane_challenge_rejected($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$message = '';
		$subject = esc_html__("Challenge rejected! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Please visit this link to view this challenge: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'challenge_rejected',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_edit_sent')){
	function arcane_edit_sent($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$message = '';
		$subject = esc_html__("New edit request! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Please visit this link to view and manage this match: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'edit_sent',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_edit_accepted')){
	function arcane_edit_accepted($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$message = '';
		$subject = esc_html__("Edit accepted! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Please visit this link to view this match: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'edit_accepted',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_edit_rejected')){
	function arcane_edit_rejected($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$message = '';
		$subject = esc_html__("Edit rejected! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Please visit this link to view this match: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;

		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'edit_rejected',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_delete_sent')){
	function arcane_delete_sent($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$message = '';
		$subject = esc_html__("New delete request! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Please visit this link to view and manage this match: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'delete_sent',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_delete_accepted')){
	function arcane_delete_accepted($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$match_title = get_the_title($post_id);
		$message = '';
		$subject = esc_html__("Delete accepted! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Match ","arcane");
		$message .= '&#39;';
		$message .= esc_attr($match_title);
		$message .= '&#39;';
		$message .= esc_html__(" has been deleted! ","arcane");
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'delete_accepted',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_delete_rejected')){
	function arcane_delete_rejected($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$message = '';
		$subject = esc_html__("Delete rejected! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Please visit this link to view this match: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'delete_rejected',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_join_team_allow')){
	function arcane_join_team_allow($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$team_title = get_the_title($post_id);
		$message = '';
		$subject = esc_html__("Join request accepted! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Your requested to join ","arcane");
		$message .= '&#39;';
		$message .= esc_attr($team_title);
		$message .= '&#39;';
		$message .= esc_html__(" team has been accepted! ","arcane");
		$message .= esc_html__("Please visit this link to view team page: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_teams_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'join_team_allow',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_join_team_request')){
	function arcane_join_team_request($user_id, $post_id, $user_requesting){
		$user_data = get_userdata($user_id);
		$user_data2 = get_userdata($user_requesting);
		$pageurl = get_permalink($post_id);
		$team_title = get_the_title($post_id);
		$message = '';
		$subject = esc_html__("New join request! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_attr($user_data2->display_name);
		$message .= esc_html__(" requested to join your ","arcane");
		$message .= '&#39;';
		$message .= esc_attr($team_title);
		$message .= '&#39;';
		$message .= esc_html__(" team! ","arcane");
		$message .= esc_html__("Please visit this link to view team page: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_teams_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'secondary_item_id' => $user_requesting,
				'component_name'    => 'tournaments',
				'component_action'  => 'join_team_request',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_score_accepted')){
	function arcane_score_accepted($user_id, $post_id , $user2_id, $user_type){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$message = '';
		if($user_type == 'team'){
			$team_title = get_the_title($user2_id);
			$type_of = esc_html__(" team! ","arcane");
		}else{
			$user_data2 = get_userdata($user2_id);
			$team_title = $user_data2->display_name;
			$type_of = esc_html__(" user! ","arcane");
		}
		$subject = esc_html__("Score accepted! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Your score submit was accepted by ","arcane");
		$message .= '&#39;';
		$message .= esc_attr($team_title);
		$message .= '&#39;';
		$message .= esc_attr($type_of);
		$message .= esc_html__("Please visit this link to view this match: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'secondary_item_id' => $user2_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'arcane_score_accepted',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_score_rejected')){
	function arcane_score_rejected($user_id, $post_id, $user2_id, $user_type){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$secondary_item = $message = '';
		if($user_type == 'team'){
			$team_title = get_the_title($user2_id);
			$type_of = esc_html__(" team! ","arcane");
		}else{
			$user_data2 = get_userdata($user2_id);
			$team_title = $user_data2->display_name;
			$type_of = esc_html__(" user! ","arcane");
		}
		$subject = esc_html__("Score rejected! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Your score submit was rejected by ","arcane");
		$message .= '&#39;';
		$message .= esc_attr($team_title);
		$message .= '&#39;';
		$message .= esc_attr($type_of);
		$message .= esc_html__("Please visit this link to view this match: ","arcane");
		$message .= esc_url($pageurl);

		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}


		$secondary_item .= esc_attr($team_title);
		$secondary_item .= '&#39;';
		$secondary_item .= esc_attr($type_of);

		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'secondary_item_id' => $user2_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'arcane_score_rejected',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}


if(!function_exists('arcane_user_joined_tournament')){
	function arcane_user_joined_tournament($user_id, $post_id, $user2_id, $team_user){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$team_title = get_the_title($user2_id);
		$subject = $message = '';
		if($team_user == 'user'){ $contestant = esc_html__("user","arcane"); $user_data2 = get_userdata($user2_id); $team_title = $user_data2->display_name; }
		if($team_user == 'team'){ $contestant = esc_html__("team","arcane"); $team_title = get_the_title($user2_id); }
		$subject .= esc_html__("New ","arcane");
		$subject .= esc_attr($contestant);
		$subject .=  esc_html__(" joined! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= ucfirst($contestant).' ';
		$message .= '&#39;';
		$message .= esc_attr($team_title);
		$message .= '&#39;';
		$message .= esc_html__(" joined your tournament! ","arcane");
		$message .= esc_html__("Please visit this link to view tournament: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_tournaments_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}
	}
}

if(!function_exists('arcane_user_left_tournament')){
	function arcane_user_left_tournament($user_id, $post_id, $user2_id, $team_user){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$team_title = get_the_title($user2_id);
		$subject = $message = '';
		if($team_user == 'user'){ $contestant = esc_html__("user","arcane"); $user_data2 = get_userdata($user2_id); $team_title = $user_data2->display_name; }
		if($team_user == 'team'){ $contestant = esc_html__("team","arcane"); $team_title = get_the_title($user2_id); }
		$subject .= ucfirst($contestant);
		$subject .=  esc_html__(" left! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= ucfirst($contestant).' ';
		$message .= '&#39;';
		$message .= esc_attr($team_title);
		$message .= '&#39;';
		$message .= esc_html__(" left your tournament! ","arcane");
		$message .= esc_html__("Please visit this link to view tournament: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_tournaments_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}
	}
}

if(!function_exists('arcane_user_accept_in_tournament')){
	function arcane_user_accept_in_tournament($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$tournament_title = get_the_title($post_id);
		$subject = $message = '';
		$subject .=  esc_html__("Request accepted! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("Your request to join ","arcane");
		$message .= '&#39;';
		$message .= esc_attr($tournament_title);
		$message .= '&#39;';
		$message .= esc_html__(" tournament has been accepted! ","arcane");
		$message .= esc_html__("Please visit this link to view tournament: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_tournaments_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}
	}
}

if(!function_exists('arcane_notify_user_score_submitted')){
	function arcane_notify_user_score_submitted($user_id, $post_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$match_title = get_the_title($post_id);
		$subject = $message = '';
		$subject .= esc_html__("New score submit! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("New score submit for ","arcane");
		$message .= '&#39;';
		$message .= esc_attr($match_title);
		$message .= '&#39;';
		$message .= esc_html__(" match!","arcane");
		$message .= esc_html__(" Please visit this link to view this match: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;

		$subscribed = get_user_meta($user_id, 'email_matches_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}
		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $post_id,
				'component_name'    => 'tournaments',
				'component_action'  => 'user_score_submitted',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

if(!function_exists('arcane_notify_admin_tournament_score_submitted')){
	function arcane_notify_admin_tournament_score_submitted($user_id, $post_id, $match_id){
		$user_data = get_userdata($user_id);
		$pageurl = get_permalink($post_id);
		$match_title = get_the_title($match_id);
		$subject = $message = '';
		$subject .= esc_html__("New score submit! On: ","arcane").get_bloginfo();
		$message .= "\n\n";
		$message .= esc_html__("New score submit for ","arcane");
		$message .= '&#39;';
		$message .= esc_attr($match_title);
		$message .= '&#39;';
		$message .= esc_html__(" match!","arcane");
		$message .= esc_html__(" Please visit this link to view tournament: ","arcane");
		$message .= esc_url($pageurl);
		$headers = "From: ".get_bloginfo()." <".get_option('admin_email').">\r\n Reply-To: ". $user_data->user_email;
		$subscribed = get_user_meta($user_id, 'email_tournaments_subscribed', true);

		if (class_exists('Arcane_Types') && $subscribed){
			$arcane_types = new Arcane_Types();
			$arcane_types::arcane_send_email( $user_data->user_email, $subject, $message, $headers );
		}
		if (function_exists ('bp_notifications_add_notification')) {
			bp_notifications_add_notification( array(
				'user_id'           => $user_id,
				'item_id'           => $match_id,
				'component_name'        => 'tournaments',
				'component_action'  => 'admin_tournament_score_submitted',
				'date_notified'     => bp_core_current_time(),
				'is_new'            => 1,
			) );
		}
	}
}

function arcane_update_br_standing($tid){

	$participant_cache = get_post_meta( $tid, 'participant_cache', true );
	$standings = [];

	foreach ($participant_cache as $participant){

		$total = 0;
		foreach ($participant['score'] as $score){
			$total = $total + (int)$score['score'];
		}

		$standings[$participant['id']] = $total;
	}

	asort($standings);
	$standings = array_reverse($standings, true);


	$counter = 1;
	foreach ( $standings as $key => $standing){

		foreach ($participant_cache as &$participant){
			if($participant['id'] == $key)
				$participant['position'] = $counter;

		}

		$counter++;
	}

	update_post_meta( $tid, 'participant_cache', $participant_cache );


}

?>