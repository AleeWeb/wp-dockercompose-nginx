<?php

require_once get_theme_file_path('addons/tournaments/extended_functions.php');
if (!class_exists('Arcane_Tournaments')) {
    class Arcane_Tournaments
    {
        //define vars
        public $DevMode = true; // SET TO FALSE WHEN IN PRODUCTION
        public $thispluginpath = '';
        public $version = '2';
        public $optionsName = 'Arcane_Tournaments';
        public $tournament_formats = array();
        public $tournament_loaded = false;
        public $tournament = '';
        // Arcane tournaments constructor
        public function __construct()
        {
            $this->thispluginpath = get_theme_file_path('addons/tournaments/');
            //Get options
            $this->GetSettings();
            if ($this->options['reinit'] == '1') {
                $this->FirstTimeInit();
            }

            add_action('wp_ajax_create_a_tournament', array(&$this, 'ajax_create_tournament'));
        }

        //--------------------------------------------------
        //--------------First time init---------------------
        //--------------------------------------------------

        public function FirstTimeInit()
        {
            $this->options['reinit'] = 0;
            $this->saveAdminOptions();
        }

        //--------------------------------------------------
        //--------------------------------------------------
        //--------------------------------------------------

        //--------------------------------------------------
        //----------------Init settings---------------------
        //--------------------------------------------------
        public function GetSettings()
        {
            if (!$theOptions = get_option($this->optionsName)) {
                $theOptions = array(
                 'minimum_capability' => 'manage_options',
                 'minimum_capability_create' => 'manage_options',
                 );
                update_option($this->optionsName, $theOptions);
            }
            $this->options = $theOptions;
            //minimum capability to access tournament module
            if (!isset($this->options['minimum_capability'])) {
                $this->options['minimum_capability'] = 'manage_options';
                $this->saveAdminOptions();
            }
            if (!isset($this->options['minimum_capability_create'])) {
                $this->options['minimum_capability_create'] = 'manage_options';
                $this->saveAdminOptions();
            }
            if (!isset($this->options['version'])) {
                //first time construct
                $this->options['reinit'] = '1';
                $this->options['version'] = $this->version;
                $this->saveAdminOptions();
            } else {
                if ($this->options['version'] < $this->version) {
                    //older version, still reinit
                    $this->options['reinit'] = '1';
                    $this->options['version'] = $this->version;
                    $this->saveAdminOptions();
                }
            }
            if ($this->DevMode == 'true') {
                $this->options['reinit'] = '1';
                $this->options['minimum_capability'] = 'manage_options';
                $this->options['version'] = $this->version;
                $this->saveAdminOptions();
            }

            $this->FetchTournamentFormats();
        }
        public function saveAdminOptions()
        {
            return update_option($this->optionsName, $this->options);
        }

        //--------------------------------------------------
        //-------------Load a tournament to class-----------
        //--------------------------------------------------
        public function LoadTournament($tid)
        {

            if ((is_numeric($tid)) && ($tid > 0)) {

              $this->FetchTournamentFormats();

                $format = get_post_meta($tid, 'format', true);


                    if (strlen($format) > 0) {
                    $format = strtolower($format);

                    foreach ($this->tournament_formats as $check) {
                        if ((strtolower($check['slug']) == strtolower($format)) || (strtolower($check['name']) == strtolower($format))) {
                            $this->tournament = new $check['class']();
                            $this->tournament->InitData($tid);
                            $this->tournament_loaded = true;

                            return true;
                        }
                    }
                } else {
                    return false;
                }
            }
        }
        public function DeleteTournamentMatches($tid)
        {
            $args = array(
                'meta_key' => 'tournament_id',
                'meta_value' => $tid,
                'post_type' => 'matches',
                'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
                'numberposts' => -1,
            );
            $postz = get_posts($args);

            foreach ($postz as $post) {
                wp_delete_post($post->ID, true);
            }
            //wp_reset_postdata();
            update_post_meta($tid, 'games_created', 0);

            return true;
        }
        public function ScheduleMatch($tid, $participants, $team1, $team2, $title, $time, $game, $desc, $maps, $rounds)
        {
            global $ArcaneWpTeamWars;
            $games = $ArcaneWpTeamWars->get_game('');
            foreach ($games as $gamez) {
                if ($gamez->title == $game) {
                    $game_id = $gamez->id;
                }
			}

            if (strpos(strtolower($participants), 'user') !== false) {
                $participants = 'user';
            } else {
                $participants = 'team';
            }

            $tournament_timezone = get_post_meta($tid, 'tournament_timezone' , true);
            if(!$tournament_timezone) {
                $timezone_string = arcane_timezone_string();
                $tournament_timezone = $timezone_string ? $timezone_string : 'UTC';
            }
            date_default_timezone_set($tournament_timezone);

            $output_time = date('Y-m-d H:i:s', $time);

            $arr = array(
                'title' => $title,
                'date' => $output_time,
                'date_unix' => $time,
                'team1' => $team1,
                'team2' => $team2,
                'scores' => array(),
                'game_id' => $game_id,
                'match_status' => 1,
                'description' => $desc,
                'external_url' => '',
                'status' => 'active',
            );
            $match_id = $ArcaneWpTeamWars->add_match($arr);
            if ($rounds == 'bo1') {
                $rounds = 1;
            } elseif ($rounds == 'bo2') {
                $rounds = 2;
            } elseif ($rounds == 'bo3') {
                $rounds = 3;
            } elseif ($rounds == 'bo4') {
                $rounds = 4;
            } elseif ($rounds == 'bo5') {
                $rounds = 5;
            } else {
                $rounds = 1;
            }
            //$pid = $ArcaneWpTeamWars->update_match_post($match_id, $tid, $participants);
      update_post_meta($match_id, 'tournament_id', $tid);
            update_post_meta($match_id, 'tournament_participants', $participants);
            $pid = $match_id;
            if (is_array($maps) && (!empty($maps) && (count($maps) > 0))) {
                $bigcounter = 0;

                foreach ($maps as $map) {
                    $counter = 0;

                    while ($counter < $rounds) {
                        $ArcaneWpTeamWars->add_round(array('match_id' => $match_id,
                            'group_n' => $bigcounter,
                            'map_id' => $map,
                            'tickets1' => 0,
                            'tickets2' => 0,
                        ));
                        ++$counter;
                    }
                    ++$bigcounter;
                }
            } elseif ((is_numeric($maps)) && ($maps > 0)) {
                $counter = 0;
                while ($counter < $rounds) {
                    $ArcaneWpTeamWars->add_round(array('match_id' => $match_id,
                    'group_n' => $counter,
                    'map_id' => $maps,
                    'tickets1' => 0,
                    'tickets2' => 0,
                    ));
                    ++$counter;
                }
            } else {
                //no maps so just add all, cause you know, life
        $maps = $ArcaneWpTeamWars->get_map(array('game_id' => $game_id, 'order' => 'asc', 'orderby' => 'title'));

                $bigcounter = 0;
                foreach ($maps as $map) {
                    $counter = 0;
                    while ($counter < $rounds) {
                        $ArcaneWpTeamWars->add_round(array('match_id' => $match_id,
                            'group_n' => $bigcounter,
                            'map_id' => $map->id,
                            'tickets1' => 0,
                            'tickets2' => 0,
                        ));
                        ++$counter;
                    }
                    ++$bigcounter;
                }
            }

            return $pid;
        }

        //--------------------------------------------------
        //--------------Admin menus and options-------------
        //--------------------------------------------------
        public function admin_menu_link()
        {
		}

        public function admin_options_page()
        {
            include 'inc/admin/tournament_cp.php';
        }

        //--------------------------------------------------
        //--------------------------------------------------
        //------------Tournaments listing-------------------
        //-----------....and handling-----------------------
        //------------------...and stuff--------------------
        //--------------------------------------------------
        //--------------------------------------------------

            //--------------------------------------------------
            //----------------Fetch tournament formats----------
            //--------------------------------------------------
            private function FetchTournamentFormats()
            {
                 $options = arcane_get_theme_options();

                if(!isset($options['choosen_types']))$options['choosen_types'] = [];
                $theme_options_formats = $options['choosen_types'];

                $formats = array();
                $theme_types = array();

                if (!empty($theme_options_formats) && is_array($theme_options_formats)) {
                    foreach ($theme_options_formats as $key => $value) {
                        if ($value == 1) {
	                        $theme_types[] = $key;
                        }
                    }
                }else{
	                $theme_types[] = $theme_options_formats;
                }

                $array = get_option('enabled_tournament_types');
                $foundknockout  =false;

				if(isset($array) && !empty($array)) {
					foreach ( $array as $one ) {
						if ( strtolower( $one['name'] ) == 'knockout' ) $foundknockout = true;
					}

					if ( ! $foundknockout ) {
						arcane_TournamentTypeEditOptions( 'Knockout', 'knockout', get_theme_file_path( 'addons/tournaments/types/define-knockout.php' ), 'add' );
						$array = get_option( 'enabled_tournament_types' );
					}

					foreach ( $array as $single ) {

						if ( in_array( strtolower($single['id']), $theme_types ) ) {
							if ( isset( $single['file'] ) && ( ! empty( $single['file'] ) ) ) {
								if ( strpos( $single['file'], 'http://' ) === false ) {
									if ( file_exists( $single['file'] ) ) {
										require $single['file'];
									}
								}
							}
						}
					}
				}

                $this->tournament_formats = array();

                foreach ($formats as $format) {
                    $test = new $format();
                    $data = $test->GetDesc();
                    $data['class_name'] = $format;
                    $data['class'] = $test;
                    array_push($this->tournament_formats, $data);
                }
            }

        public function TournamentFormats()
        {
            return $this->tournament_formats;
        }


        public function ajax_create_tournament()
        {

            $test2 = urldecode($_POST['data']);
            $test2 = str_replace('[', '', $test2);
            $test2 = str_replace(']', '', $test2);
            parse_str($test2, $result);
                $problem = false;
            foreach ($result as $single) {
                if (is_string($single)) {
                    if (!(strlen($single) > 0)) {
                        $problem = true;
                    }
                }
            }
            if ($problem == true) {
                echo 'error';
                die();
            } else {
                //everything fine, let's go!
                    $my_post = array(
                        'post_title' => wp_strip_all_tags($result['tournament_name']),
                        'post_content' => $result['tournament_desc'],
                        'post_status' => 'Pending',
                        'post_type' => 'tournament',
                    );
                $post = wp_insert_post($my_post);
                if ($post == 0) {
                    echo 'error';
                    die();
                } else {
                    //insert was a success, save all data!
                        foreach ($result as $key => $value) {
                            update_post_meta($post, $key, $value);
                        }
                }
                echo 'ok';
            }
            die();
        }


    }
} //End if class exists statement
//instantiate the class
if (class_exists('Arcane_Tournaments')) {
    $arcane_tournaments = new Arcane_Tournaments();
}
