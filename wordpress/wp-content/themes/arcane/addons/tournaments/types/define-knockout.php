<?php

if (!class_exists('Arcane_Tournaments_Knockout')) {
    class Arcane_Tournaments_Knockout {
		var $options = array();
		var $initialized = false;
		var $privateoptions = array();
		public function __construct() {
		   //echo "groups";
           $script = get_theme_file_uri('addons/tournaments/');

		}

		public function GetDesc() {
			$new = array();
			$new['slug'] = "knockout";
			$new['desc'] = esc_html__("A single-elimination tournament — also called an Olympic system tournament, a knockout or sudden death tournament — is a type of elimination tournament where the loser of each bracket is immediately eliminated.","arcane");
			$new['name'] = "Knockout";
			return $new;
		}

		public function GetPhaseAdminHtml() {
			return false;
		}
		public function OnResultSubmit($data) {
			global $ArcaneWpTeamWars;
			$rounds = get_post_meta($data['tid'], 'game_cache', true);

      if (is_array($rounds) AND (!empty($rounds))) {
        foreach ($rounds as $key_round => $round) {
          //big iterator
          foreach ($round as $key_game => $game) {
            if ($game['match_post_id'] == $data['match_id']) {
              //here we are
              $rounds[$key_round][$key_game]['teams'][0]['score'] = $data['team1_score'];
              $rounds[$key_round][$key_game]['teams'][1]['score'] = $data['team2_score'];
              $rounds[$key_round][$key_game]['score'] = $data['team1_score'].":".$data['team2_score'];
              //updated scores, let's propagate
              $nextround = $key_round + 1;
              $nextgame = floor($key_game / 2);
              if ($key_game & 1 ) {
  							//odd, moves to pos 2
  							$nextpos = 1;
  							$otherpos = 0;
  						} else {
  							//even, going to pos 1
  							$nextpos = 0;
  							$otherpos = 1;
  						}

              if ($data['team1_score'] > $data['team2_score']) {
					//team 1 won, move them up
					$nextteam = $rounds[$key_round][$key_game]['teams'][0];
				} else {
					//team 2 moves up
					$nextteam = $rounds[$key_round][$key_game]['teams'][1];
				}
              $nextteam['score'] = "";
              if (isset($rounds[$nextround][$nextgame])) {
                $tournament = new Arcane_Tournaments();
                //check if tournament finished, don't make it endless
                if (is_array($nextteam)) {
                  $nextteam['score'] = -1;
                }
                $rounds[$nextround][$nextgame]['teams'][$nextpos] = $nextteam;
                //team moved, check if other team is here
                if (isset($rounds[$nextround][$nextgame]['teams'][$otherpos])) {
                  if (isset($rounds[$nextround][$nextgame]['teams'][$otherpos]['id'])) {
                    if (is_numeric ($rounds[$nextround][$nextgame]['teams'][$otherpos]['id'])) {
                      //other team set, schedule match

                        $tournament_timezone = get_post_meta($data['tid'], 'tournament_timezone' , true);
                        if(!$tournament_timezone) {
                            $timezone_string = arcane_timezone_string();
                            $tournament_timezone = $timezone_string ? $timezone_string : 'UTC';
                        }

					   $fromwhen = get_post_meta($data['tid'], 'tournament_starts_unix', true);
                       $howoften = get_post_meta($data['tid'], 'tournament_game_frequency' , true);

                      if ($howoften == '5 minutes') {
                        $minutes = 5;
                      } elseif ($howoften == '15 minutes') {
                        $minutes = 15;
                      } elseif ($howoften == '30 minutes') {
                        $minutes = 30;
                      } elseif ($howoften == '60 minutes') {
                        $minutes = 60;
                      } elseif ($howoften == 1) {
                        $minutes = 24*60;
                      } else {
                        $minutes = $howoften*24*60;
                      }
                      $multi = $nextround * $minutes;
                      $time = strtotime("+".$multi.' minutes', $fromwhen);

                      $tehpost = get_post($data['tid']);
                      $dataz = array(
                        'pid' => $tehpost->ID,
                        'title' => $tehpost->post_title,
                        'description' => $tehpost->post_content,
                        'participants' => 0,
                        'tournament_format' => esc_html__('Knockout', 'arcane'),
                        'tournament_slug' => 'knockout',
                        'game' => get_post_meta($tehpost->ID, 'tournament_game', true),
                        'maps' =>  get_post_meta($tehpost->ID, 'tournament_maps', true),
                        'tournament_contestants' => get_post_meta($tehpost->ID, 'tournament_contestants', true),
                        'tournament_games_format'=> get_post_meta($tehpost->ID, 'tournament_games_format', true),
                      );
                      $rounds[$nextround][$nextgame]['match_post_id'] = $tournament->ScheduleMatch($data['tid'], $dataz['tournament_contestants'], $rounds[$nextround][$nextgame]['teams'][0]['id'], $rounds[$nextround][$nextgame]['teams'][1]['id'], $tehpost->post_title." - ".esc_html__ ("Round", "arcane")." ".($nextround + 1) , $time, $dataz['game'], $dataz['description']."<br />".esc_html__ ("Round", "arcane")." ".($nextround + 1)." ".esc_html__ ("game", "arcane"), $dataz['maps'], $dataz['tournament_games_format']);
                      $rounds[$nextround][$nextgame]['time'] = $time;

                    }
                  }
                }
              }
              //next scheduled

              update_post_meta($data['tid'], 'game_cache', $rounds);
              //data updated
            }
          }
        }
      }
			return true;
		}
		public function OnTournamentStart($tid) {
			//schedule all matches
			$created = get_post_meta($tid, 'games_created', true);

			if ($created != 1) {

                $tournament_timezone = get_post_meta($tid, 'tournament_timezone' , true);
                if(!$tournament_timezone) {
                    $timezone_string = arcane_timezone_string();
                    $tournament_timezone = $timezone_string ? $timezone_string : 'UTC';
                }
                date_default_timezone_set($tournament_timezone);

                $competitors = get_post_meta($tid, 'tournament_competitors' , true);
				$starts = get_post_meta($tid, 'tournament_starts_unix', true);
				$howoften = get_post_meta($tid, 'tournament_game_frequency' , true);

				if (is_array($competitors) AND (count($competitors) >= 2 )) {
					$this->define_tournament($competitors, $starts, $howoften, $tid);
				}
			}
		}


		/**
		 * Runs to make a schedule.
		 */

		private function define_tournament($players, $fromwhen, $howoften, $tid)
		{

			$players = array_values($players);
			$n = count($players);
			$temp = $players;
            $p = 1;
          $z = 0;
            while ($z < count($players)) {
                $p++;
                $z =pow(2,$p);
            }

            $first_round_byes = $z - $n;

            $numrounds = $p;
			//$contestants = get_post_meta($tid, 'tournament_contestants', true);
			$tehpost = get_post($tid);
			$data = array(
				'pid' => $tehpost->ID,
				'title' => $tehpost->post_title,
				'description' => $tehpost->post_content,
				'participants' => 0,
				'tournament_format' => 'Knockout',
				'tournament_slug' => 'knockout',
				'game' => get_post_meta($tehpost->ID, 'tournament_game', true),
				'maps' =>  get_post_meta($tehpost->ID, 'tournament_maps', true),
				'tournament_contestants' => get_post_meta($tehpost->ID, 'tournament_contestants', true),
				'tournament_games_format'=> get_post_meta($tehpost->ID, 'tournament_games_format', true),
			);

			foreach ($players as $player) {

				$participants = $data['tournament_contestants'];
				if (strpos(strtolower($participants), 'user') !== false) {
					$participants = 'user';
				}else {
					$participants = 'team';
				}

				$participant_cache[$player]['id'] =$player;
                $participant_cache[$player]['seed'] ='0';
				if ($participants == 'team') {
					$participant_cache[$player]['name'] =  get_the_title($player);
					$participant_cache[$player]['url'] =  get_permalink($player);
				} else {
					$user1 = get_user_by('id',$player);
					$participant_cache[$player]['name'] =$user1->display_name;
					$participant_cache[$player]['url'] = bp_core_get_user_domain($player);
				}
			}

            shuffle($temp);
            $created = 0;

            $temp_secondrounders = array();
            $popped = 0;

            while ($popped < $first_round_byes) {
                $temp_secondrounders[] = array_pop($temp);
                $popped++;
            }
            $temp_firstrounders = $temp;

            $tournament = new Arcane_Tournaments();
            $seed = 1;
            $popped = 0;
            while ($created < ($numrounds )) {
                if ($created == 0) {
                    //schedule first round
                    $minicount = 0;
                    while (count($temp_firstrounders) > 1) {
                        $team1 = array_pop($temp_firstrounders);
                        $team2 = array_pop($temp_firstrounders);
                        $popped++;
                        if ($participant_cache[$team1]['seed'] == '0') {
                            $participant_cache[$team1]['seed'] = $seed;
                            $seed++;
                        }
                         if ($participant_cache[$team2]['seed'] == '0') {
                            $participant_cache[$team2]['seed'] = $seed;
                            $seed++;
                        }

                        if ($howoften == '5 minutes') {
                            $minutes = 5;
                        } elseif ($howoften == '15 minutes') {
                            $minutes = 15;
                        } elseif ($howoften == '30 minutes') {
                            $minutes = 30;
                        } elseif ($howoften == '60 minutes') {
                            $minutes = 60;
                        } elseif ($howoften == 1) {
                            $minutes = 24*60;
                        } else {
                            $minutes = $howoften*24*60;
                        }
                        $multi = $created * $minutes;
                        $time = strtotime("+".$multi.' minutes', $fromwhen);

                        $tourneygames[$created][$minicount]['match_post_id'] = $tournament->ScheduleMatch($data['pid'], $data['tournament_contestants'], $team1, $team2, $data['title']." - ".esc_html__ ("Round", "arcane")." ".($created + 1) , $time, $data['game'], $data['description']."<br />".esc_html__ ("Round", "arcane")." ".($created + 1)." ".esc_html__("game", "arcane"), $data['maps'], $data['tournament_games_format']);

                        $tourneygames[$created][$minicount]['teams'][0]['id'] = $team1;
                        $tourneygames[$created][$minicount]['teams'][1]['id'] = $team2;

                        $tourneygames[$created][$minicount]['teams'][0]['name'] = $participant_cache[$team1]['name'];
                        $tourneygames[$created][$minicount]['teams'][1]['name'] = $participant_cache[$team2]['name'];

                        $tourneygames[$created][$minicount]['teams'][0]['url'] = $participant_cache[$team1]['url'];
                        $tourneygames[$created][$minicount]['teams'][1]['url'] =$participant_cache[$team2]['url'];

                        $tourneygames[$created][$minicount]['time'] = $time;
                        $tourneygames[$created][$minicount]['teams'][0]['score'] = "0";
                        $tourneygames[$created][$minicount]['teams'][1]['score'] = "0";
                        $minicount++;
                    }
                } else {

                    //schedule rest
                    $modifier = $numrounds - ($created +1 );
                    $matches_to_make = pow(2, $modifier);
                    $minicount = 0;
                    $taken = 0;
                    while ($minicount < $matches_to_make) {
                        if (is_array($temp_secondrounders)) {
                            if (count($temp_secondrounders) > 0) {
                                if ($taken < $popped) {
                                    $team1 = 'tbd';
                                    $taken ++;
                                } else {
                                    $team1 = array_pop($temp_secondrounders);
                                     if ($participant_cache[$team1]['seed'] == '0') {
                                        $participant_cache[$team1]['seed'] = $seed;
                                        $seed++;
                                    }
                                }

                                if ($taken < $popped) {
                                    $team2 = 'tbd';
                                    $taken ++;
                                } else {
                                    $team2 = array_pop($temp_secondrounders);
                                     if ($participant_cache[$team2]['seed'] == '0') {
                                        $participant_cache[$team2]['seed'] = $seed;
                                        $seed++;
                                    }
                                }

                                if ($howoften == '5 minutes') {
                                    $minutes = 5;
                                } elseif ($howoften == '15 minutes') {
                                    $minutes = 15;
                                } elseif ($howoften == '30 minutes') {
                                    $minutes = 30;
                                } elseif ($howoften == '60 minutes') {
                                    $minutes = 60;
                                } elseif ($howoften == 1) {
                                    $minutes = 24*60;
                                } else {
                                    $minutes = $howoften*24*60;
                                }
                                $multi = $created * $minutes;
                                $time = strtotime("+".$multi.' minutes', $fromwhen);

                                 $placeholder = false;
                            }else {
                                $placeholder = true;
                            }
                        } else {
                             $placeholder = true;
                        }

                        if ($placeholder == true){
                            $tourneygames[$created][$minicount]['match_post_id'] = 'unset'; //$tournament->ScheduleMatch($data['pid'], $data['tournament_contestants'], $team1, $team2, $data['title']." - ".esc_html__ ("Round", "arcane")." ".($created + 1) , $time, $data['game'], $data['description']."<br />".esc_html__ ("Round", "arcane")." ".($created + 1)." ".esc_html__ ("game", "arcane"), $data['maps'], $data['tournament_games_format']);
                            $tourneygames[$created][$minicount]['teams'][0]['id'] = 'tbd';
                            $tourneygames[$created][$minicount]['teams'][1]['id'] = 'tbd';

                            $tourneygames[$created][$minicount]['teams'][0]['name'] = 'tbd';
                            $tourneygames[$created][$minicount]['teams'][1]['name'] = 'tbd';

                            $tourneygames[$created][$minicount]['teams'][0]['url'] =  'tbd';
                            $tourneygames[$created][$minicount]['teams'][1]['url'] = 'tbd';

                            $tourneygames[$created][$minicount]['time'] = '';
                            $tourneygames[$created][$minicount]['teams'][0]['score'] = "0";
							$tourneygames[$created][$minicount]['teams'][1]['score'] = "0";
                        } else {
                            $tourneygames[$created][$minicount]['match_post_id'] = 'unset'; //$tournament->ScheduleMatch($data['pid'], $data['tournament_contestants'], $team1, $team2, $data['title']." - ".esc_html__ ("Round", "arcane")." ".($created + 1) , $time, $data['game'], $data['description']."<br />".esc_html__ ("Round", "arcane")." ".($created + 1)." ".esc_html__ ("game", "arcane"), $data['maps'], $data['tournament_games_format']);


                            if ($team1 == 'tbd'){
                                $tourneygames[$created][$minicount]['teams'][0]['id'] = 'tbd';
                                $tourneygames[$created][$minicount]['teams'][0]['name'] = 'tbd';
                                $tourneygames[$created][$minicount]['teams'][0]['url'] = 'tbd';
                            } else {
                                $tourneygames[$created][$minicount]['teams'][0]['id'] = $team1;
                                $tourneygames[$created][$minicount]['teams'][0]['url'] = $participant_cache[$team1]['url'];
                                $tourneygames[$created][$minicount]['teams'][0]['name'] = $participant_cache[$team1]['name'];

                            }



                            if ($team2 == 'tbd'){
                                $tourneygames[$created][$minicount]['teams'][1]['id'] = 'tbd';
                                $tourneygames[$created][$minicount]['teams'][1]['name'] = 'tbd';
                                $tourneygames[$created][$minicount]['teams'][1]['url'] = 'tbd';
                            } else {
                                $tourneygames[$created][$minicount]['teams'][1]['id'] = $team2;
                                $tourneygames[$created][$minicount]['teams'][1]['url'] = $participant_cache[$team2]['url'];
                                $tourneygames[$created][$minicount]['teams'][1]['name'] = $participant_cache[$team2]['name'];
                                //

                            }

                            if (($team1 != 'tbd') AND ($team2 != 'tbd')) {
                                $tourneygames[$created][$minicount]['match_post_id'] = $tournament->ScheduleMatch($data['pid'], $data['tournament_contestants'], $team1, $team2, $data['title']." - ".esc_html__ ("Round", "arcane")." ".($created + 1) , $time, $data['game'], $data['description']."<br />".esc_html__ ("Round", "arcane")." ".($created + 1)." ".esc_html__ ("game", "arcane"), $data['maps'], $data['tournament_games_format']);
                            }
                            $tourneygames[$created][$minicount]['time'] = $time;
                            $tourneygames[$created][$minicount]['score'] = "";
                        }


                        $minicount++;
                    }
                }


                $created ++;
            }
            if ($popped > 2) {
                $tourneygames['showfirstnum'] = floor($popped / 2);
                $tourneygames['skipfirst'] = true;
            } elseif ($popped = 1) {
                 $tourneygames['showfirstnum'] =0;
                $tourneygames['skipfirst'] = true;
            } else {
                $tourneygames['showfirstnum'] =0;
                $tourneygames['skipfirst'] = false;
            }
            update_post_meta($tid, 'games_created', 1);
			update_post_meta($tid, 'game_cache', $tourneygames);
			update_post_meta($tid, 'participant_cache', $participant_cache);
            return true;


		}

		public function InitData($tid) {
			$options = $this->GetCreationOptions();
			$ending = array();
			foreach ($options as $option) {
				if (($option['type'] != "info" ) AND ($option['type'] != "heading" )) {
					array_push($ending, $option);
				}
			}
			$values = array();
			$postmeta = get_post_meta($tid);
			if (is_array($ending)) {
				foreach ($ending as $single) {

					if (isset($postmeta[$single['id']])) {
						if ($single['type'] == "checkbox"){
							$single['val'] = "off";
						}
						$single['val'] = $postmeta[$single['id']][0];
					}
					//$single['val'] = get_post_meta($tid, $single['id'], true);
					array_push($values, $single);
				}
			}
			$privates = array();

			if (isset($postmeta['tournament_state'])) {
				$privates["state"] = $postmeta['tournament_state'][0];
			}
			$privates["pid"] =$tid;

			if (isset($postmeta['tournament_admins'])) {
				$privates["admins"] = $postmeta['tournament_admins'][0];
			}
			$output = array();
			foreach ($values as $option) {
				//rearrange options so they have ID instead of random numerics
				$output[$option['id']] = $option;
			}
			$this->options = $output;
			$this->privateoptions= $privates;
			unset ($single);
			unset ($ending);
			unset ($options);
			$this->initialized = true;
		}

		public function PrintStatus($tid) {


			$created = get_post_meta($tid, 'games_created', true);

			if ($created == 1) {
				$gamesz = get_post_meta($tid, 'game_cache', true);
				$participants = get_post_meta($tid, 'participant_cache', true);
                $contestants = get_post_meta($tid, 'tournament_contestants', true);
                $gamesz_third = get_post_meta($tid, 'game_cache_third', true);

                $return='';
                $knockout_script = '';

                $return .= '
                <div id="knockout_holder">

                    <div id="thebracket" class="draggable"></div>
                    <div class="clear"></div>
                ';

                if(count($participants) > 3){

                $return .= '    <div id="thebracket_third" class="draggable">
                        <table class="g_round_label thirdp" >
                            <tr><td>
                                <div class="g_round_label thirdp">'.esc_html__("Match for 3rd place", "arcane").'</div>
                            </td></tr>
                        </table>
                    </div>

                    <div id="knockout_holder_third"></div>';


                    $knockout_script .=  ' jQuery(document).ready(function() {
                          jQuery("#thebracket_third").gracket({ src : KnockOutDataThird, canvasLineColor: "#fff", canvasLineWidth: 2, canvasId : "g_canvas", canvasClass : "g_canvas" });
                    });';

                }

                $return .= '
                </div>
                ';
                $knockout_script .= '
                var KnockOutData = [';
                $lastkey = -1;
				$i = 0;
				foreach ($gamesz as $key => $games) {
                    if (is_array($games)) {
                      $lastkey = $key;
                        $knockout_script .= '[
                        ';

                        foreach ($games as $game) {

                            $knockout_script .=  '[';

	                        $url = '';

							if (isset($game['match_post_id'])) {
								if (strlen($game['match_post_id']) > 1) {
                                    $url = '"url": "'.esc_url(get_permalink($game['match_post_id'])).'",';
								}
							}


                            if ($game['teams'][0]['id'] == "tbd") {
                                $knockout_script .= '{"name" : "'.esc_html__('To be decided', 'arcane').'"},';
                            } else {
								if (isset($game['teams'][0]['score']) AND ($game['teams'][0]['score'] > 0)){
									$score = '"score": '.$game['teams'][0]['score'].',';
								} else {
									$score ='"score": 0,';
								}
                                $t1_id = $game['teams'][0]['id'];
                                if($contestants == 'team') {
                                    $t1 = get_post($t1_id);
                                    $t1_name = $t1->post_title;
                                } else {
                                    $t1 = get_user_by('id', $t1_id);
                                    $t1_name = $t1->display_name;
                                }
                                $knockout_script .= '{"name" : "'.$t1_name.'", "id" : "'.$t1_id.'", '.$url.' '.$score.' "seed" : '.$participants[$game['teams'][0]['id']]['seed'].' },';
                            }
                            if ($game['teams'][1]['id'] == "tbd") {
                                $knockout_script .= '{"name" : "'.esc_html__('To be decided', 'arcane').'"}';
                            } else {
								if (isset($game['teams'][1]['score']) AND ($game['teams'][1]['score'] > 0)){
									$score = '"score": '.$game['teams'][1]['score'].',';
								} else {
									$score ='"score": 0,';
								}
                                $t2_id = $game['teams'][1]['id'];
                                if($contestants == 'team') {
                                    $t2 = get_post($t2_id);
                                    $t2_name = $t2->post_title;
                                } else {
                                    $t2 = get_user_by('id', $t2_id);
                                    $t2_name = $t2->display_name;
                                }
                                $knockout_script .= '{"name" : "'.$t2_name.'", "id" : "'.$t2_id.'", '.$url.' '.$score.' "seed" : '.$participants[$game['teams'][1]['id']]['seed'].' }';
                            }
                            $knockout_script .=  '],';
							$i++;
                        }
                        $knockout_script .= '

                        ],
                        ';
                    }

                }
                 $gamesz['showfirstnum'];
                if ($lastkey > -1) {
                  if (($gamesz[$lastkey][0]['score'] != ":") AND (!empty($gamesz[$lastkey][0]['score']))) {
                    $knockout_script .= ' [
                        [';
                    if ($gamesz[$lastkey][0]['teams'][0]['score'] > $gamesz[$lastkey][0]['teams'][1]['score']) {
                      $knockout_script .= '{"name" : "'.$participants[$gamesz[$lastkey][0]['teams'][0]['id']]['name'].'", "id" : "'.$gamesz[$lastkey][0]['teams'][0]['id'].'", "url" : "'.$gamesz[$lastkey][0]['teams'][0]['url'].'",  "seed" : '.$participants[$gamesz[$lastkey][0]['teams'][0]['id']]['seed'].' },';
                    } else {
                      $knockout_script .= '{"name" : "'.$participants[$gamesz[$lastkey][0]['teams'][1]['id']]['name'].'", "id" : "'.$gamesz[$lastkey][0]['teams'][1]['id'].'",  "url" : "'.$gamesz[$lastkey][0]['teams'][1]['url'].'", "seed" : '.$participants[$gamesz[$lastkey][0]['teams'][1]['id']]['seed'].' },';
                    }

                $knockout_script .= '],
                        ],];';
                  } else {
                    $knockout_script .= ' [
                        [{"name" : "'.esc_html__('To be decided', 'arcane').'"}],
                        ],];';
                  }
                }else {
                  $knockout_script .= ' [
                      [{"name" : "'.esc_html__('To be decided', 'arcane').'"}],
                      ],];';
                }



                $id_third1 = '';
                $seed_third1 = '';
                $score_third1 = '';

                if(isset($gamesz_third['teams'][0]['name']) && !empty($gamesz_third['teams'][0]['name']) && $gamesz_third['teams'][0]['name']!= 'tbd'){
                    $name_third1 = $gamesz_third['teams'][0]['name'];
                    $id_third1 = $gamesz_third['teams'][0]['id'];
                    $seed_third1 = 'seed : '.$participants[$id_third1]['seed'];
                    if($gamesz_third['teams'][0]['score'] > -1)
                    $score_third1 = 'score : '.$gamesz_third['teams'][0]['score'].', ';
                }else{
                    $name_third1 = esc_html__('To be decided', 'arcane');
                }

                $id_third2 = '';
                $seed_third2 = '';
                $score_third2 = '';

                if(isset($gamesz_third['teams'][1]['name']) && !empty($gamesz_third['teams'][1]['name']) && $gamesz_third['teams'][1]['name']!= 'tbd'){
                    $name_third2 = $gamesz_third['teams'][1]['name'];
                    $id_third2 = $gamesz_third['teams'][1]['id'];
                    $seed_third2 = 'seed : '.$participants[$id_third2]['seed'];
                    if($gamesz_third['teams'][1]['score'] > -1)
                    $score_third2 = 'score : '.$gamesz_third['teams'][1]['score'].', ';
                }else{
                    $name_third2 = esc_html__('To be decided', 'arcane');
                }

                $url = '#';
                if(isset($gamesz_third['match_post_id']) && !empty($gamesz_third['match_post_id']))
                $url =  get_permalink($gamesz_third['match_post_id']);

                if(isset($gamesz_third['teams'][0]['score']) && $gamesz_third['teams'][0]['score'] > -1 && isset($gamesz_third['teams'][1]['score']) && $gamesz_third['teams'][1]['score'] > -1){
                    if($gamesz_third['teams'][0]['score'] > $gamesz_third['teams'][1]['score']){
                        $winner = 'seed : "'.$participants[$gamesz_third['teams'][0]['id']]['seed'].'","name" : "'.esc_html($gamesz_third['teams'][0]['name']).'"';
                    }elseif($gamesz_third['teams'][0]['score'] < $gamesz_third['teams'][1]['score']){
                        $winner = 'seed : "'.$participants[$gamesz_third['teams'][1]['id']]['seed'].'","name" : "'.esc_html($gamesz_third['teams'][1]['name']).'"';
                    }
                }else{
                    $winner = '"name" : "'.esc_html__('To be decided', 'arcane').'"';
                }

                $knockout_script .= '
                var KnockOutDataThird = [

                [
                [{'.esc_html($score_third1).' "name" : "'.esc_html($name_third1).'", "url": "'.esc_url($url).'", "id" : "'.esc_html($id_third1).'", '.esc_html($seed_third1).'},{'.esc_html($score_third2).' "name" : "'.esc_html($name_third2).'","url": "'.esc_url($url).'",  "id" : "'.esc_html($id_third2).'", '.esc_html($seed_third2).'}],
                ],
                [
                [{'.$winner.'}],
                ],

                ];';




                    if ($gamesz['skipfirst'] == true) {
                        $knockout_script .= 'var SkipFirst = 1;';
                    } else {
                         $knockout_script .= 'var SkipFirst = 0;';
                    }

                   $knockout_script .=  'var ShowFirst = '.$gamesz['showfirstnum'].';';
                 $knockout_script .=  ' jQuery(document).ready(function() {
                       jQuery("#thebracket").gracket({ src : KnockOutData, canvasLineColor: "#fff", canvasLineWidth: 2, canvasId : "g_canvas", canvasClass : "g_canvas" });
                       jQuery(".g_game").on("click", function(e) {
							var hasurl = "";
							jQuery(this).children().each(function (index) {
								jQuery(this).children().each(function (index) {
									var tempurl = jQuery(this).data("url");
									if (tempurl !== undefined) {
										hasurl = tempurl;
									}
								});
							});
							if (hasurl.length > 1) {
								var win = window.open(hasurl, "_blank");
							}
					   });

                        var childz = jQuery("#thebracket");
                        var parentz = jQuery("#knockout_holder");
                        var parentPos = parentz.offset();
                        var childPos = childz.offset();


                        jQuery( "#thebracket" ).draggable({
                            drag: function(event, ui) {

                                 if (ui.position.top > 0) {
                                        ui.position.top = 0;
                                  }
                                  if (ui.position.top < 0) {
                                        ui.position.top = 0;
                                  }

                                  if (ui.position.left < (parentz.width() - childz.width())) {
                                      ui.position.left= parentz.width() - childz.width();
                                  }
                                   if (ui.position.left > 0) {
                                        ui.position.left= 0;
                                  }


                              },

                            scroll: false });
                    });';


                wp_add_inline_script( 'arcane-global', $knockout_script);
				return $return;
			} else {
				return false;
			}

		}


		public function GetCreationOptions() {
			//kindoffunny but let's create options for ourselves
			$options = array(
				array( "name" => esc_html__("Dates", 'arcane'),
                        "type" => "heading"),
                array( "name" => esc_html__("Dates", 'arcane'),
                        "type" => "info"),
				array(
					"name" => esc_html__("Tournament starts", 'arcane'),
					"desc" => esc_html__("Date when tournament starts", 'arcane'),
					 "id" => "tournament_starts",
					 "type" => "date"
				),
				array( "name" => esc_html__("Participants and games", 'arcane'),
                        "type" => "heading"),
                array( "name" => esc_html__("Participant and games data", 'arcane'),
                        "type" => "info"),
				array(
					"name" => esc_html__("Participant types", 'arcane'),
					"desc" => esc_html__("Single user or a team", 'arcane'),
					 "id" => "participant_type",
					 "type" => "select",
					"options" => array("user","team")
				),
				array(
					"name" => esc_html__("Participants", 'arcane'),
					"desc" => esc_html__("Minimal number of participants needed for tournament", 'arcane'),
					 "id" => "num_participants",
					 "type" => "number"
				),
				array(
					"name" => esc_html__("Games number", 'arcane'),
					"desc" => esc_html__("Choose how many games each team plays (Best of X games)", 'arcane'),
					 "id" => "games_number",
					 "type" => "select",
					"options" => array("BO1","BO2","BO3","BO4","BO5")
				),

				array(
					"name" => esc_html__("Games played", 'arcane'),
					"desc" => esc_html__("Schedule a game every X days (1 means daily, 7 means one weekly)", 'arcane'),
					 "id" => "schedule_every_x_days",
					 "type" => "number"
				),
			);
			return $options;
		}
	}
}
array_push($formats, 'Arcane_Tournaments_Knockout');
