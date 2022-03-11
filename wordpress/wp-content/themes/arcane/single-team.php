<?php get_header(); ?>
<?php

global $post, $current_user, $arcane_challenge_sent, $ArcaneWpTeamWars;
wp_get_current_user();

$a_id     = $post->post_author;
$c_id     = $current_user->ID;
$p_id     = $post->ID;
$is_mine  = false;
$is_admin = false;


$facebook      = get_post_meta( $p_id, 'teamFacebook', true );
$twitter       = get_post_meta( $p_id, 'teamTwitter', true );
$twitch        = get_post_meta( $p_id, 'teamTwitch', true );
$instagram     = get_post_meta( $p_id, 'teamInstagram', true );
$discordServer = get_post_meta( $p_id, 'discordServer', true );
$youtube       = get_post_meta( $p_id, 'youtube', true );
$website       = get_post_meta( $p_id, 'website', true );
$location      = get_post_meta( $p_id, 'location', true );
$language      = get_post_meta( $p_id, 'language', true );
$games         = get_post_meta( $p_id, 'games', true );
$platforms     = get_post_meta( $p_id, 'platforms', true );

$options = arcane_get_theme_options();

if ( ( current_user_can( 'manage_options' ) ) ) {
	$is_admin = true;
}
if ( $c_id == $a_id ) {
	$is_mine = true;
}

$now = current_time( 'timestamp', 1 );
?>

    <div id="mainwrap" class=" team-page normal-page">
        <div class="container relative">

			<?php if ( ! arcane_is_user( $p_id, $c_id ) && ! arcane_is_admin( $p_id, $c_id ) && is_user_logged_in() && arcane_is_admin_of_any_team( $c_id ) ) { ?>
                <a href="<?php echo get_permalink( get_page_by_path( 'team-challenge' ) ); ?>?pid=<?php echo esc_attr( $p_id ); ?>"
                   class="btn challenge-team"><i
                            class="fas fa-crosshairs"></i> <?php esc_html_e( 'Challenge ', 'arcane' );
					the_title(); ?></a>
			<?php } ?>

            <div class="team-avatar-card">
				<?php $pf_url = get_post_meta( $p_id, 'team_photo', true );

				if ( ! empty( $pf_url ) ) {
					$imagebg = arcane_aq_resize( $pf_url, 211, 174, true, true, true ); //resize & crop img

					if ( $imagebg ) {
						$pfimage = $imagebg;
					} else {
						$pfimage = get_theme_file_uri( 'img/defaults/default-team.jpg' );
					}
					?>
                    <div class="hiddenoverflow"><img alt="img" src="<?php echo esc_url( $pfimage ); ?>"/></div>
				<?php } else { ?>
                    <div class="hiddenoverflow"><img alt="img" class="attachment-small wp-post-image"
                                                     src="<?php echo get_theme_file_uri( 'img/defaults/default-team.jpg' ); ?> "/>
                    </div>
				<?php } ?>
            </div>

            <div class="pmi_title">
                <h1>  <?php the_title(); ?> <?php if ( is_user_logged_in() ) {

						$teams = arcane_get_user_teams( get_current_user_id() );
						if ( $teams === false ) {
							$teams = 0;
						}


						if ( arcane_is_super_admin( $post->ID, get_current_user_id() ) ) {
							?>

						<?php } elseif ( arcane_is_pending_member( $post->ID, get_current_user_id() ) ) { ?>

                            <div id='score_fin'
                                 class='error_msg'> <?php esc_html_e( "Your request to join team is pending!", "arcane" ); ?></div>
                            <a href="javascript:void(0)" class="btn ajaxloadblock" data-req="cancel_request"
                               data-pid="<?php echo esc_attr( $post->ID ); ?>"
                               data-uid="<?php echo get_current_user_id(); ?>">
                                <i class="fas fa-user"></i> <?php esc_html_e( 'Cancel request', 'arcane' ); ?> </a>

						<?php } elseif ( arcane_is_member( $post->ID, get_current_user_id() ) or arcane_is_admin( $post->ID, get_current_user_id() ) ) { ?>

                            <a href="javascript:void(0)" class="btn ajaxloadblock" data-req="remove_friend_user"
                               data-pid="<?php echo esc_attr( $post->ID ); ?>"
                               data-uid="<?php echo get_current_user_id(); ?>">
                                <i class="fas fa-user"></i> <?php esc_html_e( 'Leave team', 'arcane' ); ?> </a>

						<?php } else { ?>

							<?php if ( $options['team_creation_number'] == '1' ) { ?>

                                <a href="javascript:void(0)" class="btn ajaxloadblock" data-req="join_team"
                                   data-pid="<?php echo esc_attr( $post->ID ); ?>"
                                   data-uid="<?php echo get_current_user_id(); ?>">
                                    <i class="fas fa-user"></i> <?php esc_html_e( 'Request to join', 'arcane' ); ?> </a>
							<?php } elseif ( $options['team_creation_number'] == '0' && get_user_meta( get_current_user_id(), 'team_no_limit', true ) ) { ?>

                                <a href="javascript:void(0)" class="btn ajaxloadblock" data-req="join_team"
                                   data-pid="<?php echo esc_attr( $post->ID ); ?>"
                                   data-uid="<?php echo get_current_user_id(); ?>">
                                    <i class="fas fa-user"></i> <?php esc_html_e( 'Request to join', 'arcane' ); ?> </a>
							<?php } elseif ( $options['team_creation_number'] == '0' && $teams == 0 ) { ?>

                                <a href="javascript:void(0)" class="btn ajaxloadblock" data-req="join_team"
                                   data-pid="<?php echo esc_attr( $post->ID ); ?>"
                                   data-uid="<?php echo get_current_user_id(); ?>">
                                    <i class="fas fa-user"></i> <?php esc_html_e( 'Request to join', 'arcane' ); ?> </a>

							<?php } ?>

						<?php } ?>

					<?php } ?></h1>

                <div class="teamsocial">
					<?php if ( ! empty( $facebook ) ) { ?>
                        <a class="facebook" target="_blank" href="<?php echo esc_url( $facebook ); ?>"><i
                                    class="fab fa-facebook-f"></i></a>
					<?php } ?>
					<?php if ( ! empty( $twitter ) ) { ?>
                        <a class="twitter" target="_blank" href="<?php echo esc_url( $twitter ); ?>"><i
                                    class="fab fa-twitter"></i></a>
					<?php } ?>
					<?php if ( ! empty( $twitch ) ) { ?>
                        <a class="twitch" target="_blank" href="<?php echo esc_url( $twitch ); ?>"><i
                                    class="fab fa-twitch"></i></i></a>
					<?php } ?>
					<?php if ( ! empty( $instagram ) ) { ?>
                        <a class="instagram" target="_blank" href="<?php echo esc_url( $instagram ); ?>"><i
                                    class="fab fa-instagram"></i></a>
					<?php } ?>
					<?php if ( ! empty( $discordServer ) ) { ?>
                        <a class="discord" target="_blank" href="<?php echo esc_url( $discordServer ); ?>"><i
                                    class="fab fa-discord"></i></a>
					<?php } ?>
					<?php if ( ! empty( $youtube ) ) { ?>
                        <a class="youtube" target="_blank" href="<?php echo esc_url( $youtube ); ?>"><i
                                    class="fab fa-youtube"></i></a>
					<?php } ?>
					<?php if ( ! empty( $website ) ) { ?>
                        <a class="website" target="_blank" href="<?php echo esc_url( $website ); ?>"><i
                                    class="fas fa-link"></i></a>
					<?php } ?>

                </div>
				<?php if ( arcane_is_super_admin( $p_id, $c_id ) ) { ?>
                    <a href="#myModalDeleteTeam" class="delete-team"><i class="fas fa-times"></i></a>
				<?php } ?>
            </div>
        </div>

        <div class="profile-fimage profile-media-team">

			<?php
			$bg_url = get_post_meta( $p_id, 'team_bg', true );

			if ( ! empty( $bg_url ) && $bg_url != get_theme_file_uri( 'img/defaults/default-banner.jpg' ) ) {
				$imagebg = arcane_aq_resize( $bg_url, 1920, 259, true, true, true ); //resize & crop img

				if ( isset ( $imagebg ) ) {
					$bgimage = $imagebg;
				}
				?>

                <div class="hiddenoverflow"><img alt="img" src="<?php echo esc_url( $bgimage ); ?>"/></div>
			<?php } else { ?>
                <div class="hiddenoverflow"><img alt="img" class="attachment-small wp-post-image"
                                                 src="<?php echo get_theme_file_uri( 'img/defaults/default-banner.jpg' ); ?> "/>
                </div>
			<?php } ?>
			<?php if ( $is_mine or $is_admin ) { ?>
                <div id="change_bg_pic"><?php esc_html_e( "Click to change", "arcane" ); ?></div>
			<?php } ?>

            <h6 data-nick="<?php the_title(); ?>"><?php the_title(); ?></h6>
        </div>

        <div class="container relative">
            <div class="project_page">
                <div class="row">

                    <div class="tabs-wrap">
                        <ul class="nav nav-tabs team-nav">
                            <li>
                                <a data-toggle="tab" href="#team">
                                    <i class="fas fa-flag"></i>&nbsp;<?php esc_html_e( 'Team Page', 'arcane' ); ?>
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#members_tab">
                                    <i class="fas fa-users"></i><?php esc_html_e( ' Members', 'arcane' ); ?>
									<?php
									if ( arcane_is_admin( $p_id, $c_id ) ) {
										$post_meta_arr = get_post_meta( $p_id, 'team', true );

										$pending_users = 0;
										if ( isset( $post_meta_arr['users']['pending'] ) ) {
											$pending_users = $post_meta_arr['users']['pending'];
											$pending_users = count( $pending_users );
										}

										if ( $pending_users > 0 ) { ?>
                                            <a class="msg_ntf"
                                               data-original-title="<?php esc_html_e( "# of new member requests: ", 'arcane' ); ?><?php echo esc_attr( $pending_users ); ?>"
                                               data-toggle="tooltip"><?php echo esc_attr( $pending_users ); ?></a>
										<?php } ?>
									<?php } ?>
                                </a>

								<?php if ( arcane_is_admin( $p_id, $c_id ) ) {
									$post_meta_arr = get_post_meta( $p_id, 'team', true );

									$pending_users = 0;
									if ( isset( $post_meta_arr['users']['pending'] ) ) {
										$pending_users = $post_meta_arr['users']['pending'];
										$pending_users = count( $pending_users );
									}


									if ( $pending_users > 0 ) { ?>
                                        <a class="msg_ntf"
                                           data-original-title="<?php esc_html_e( "# of new member requests: ", 'arcane' ); ?><?php echo esc_attr( $pending_users ); ?>"
                                           data-toggle="tooltip"><?php echo esc_attr( $pending_users ); ?></a>
									<?php } ?>


								<?php } ?>
                            </li>


							<?php
							$matches = [];
							if ( isset( $p_id ) ) {
								$matches = arcane_return_all_team_matches( $p_id );
							}

							$br_notifikacija = 0;


							foreach ( $matches as $key => $match ) {
								$match           = $ArcaneWpTeamWars->get_match( array( 'id' => $match->ID ) );
								$matches[ $key ] = $match;
								if ( $p_id != $match->team1 && arcane_is_admin( $p_id, get_current_user_id() ) &&
								     ( $match->status == 'submitted2' || $match->status == 'submitted1' || $match->status == 'deleted_request_team1' || $match->status == 'deleted_request_team2' ) ) {
									$br_notifikacija ++;
								}
							} ?>


                            <li>
                                <a data-toggle="tab" href="#matches">
                                    <i class="fas fa-crosshairs"></i> <?php esc_html_e( 'Matches', 'arcane' ); ?>
                                </a>

								<?php if ( arcane_is_admin( $p_id, $c_id ) ) {

									if ( isset( $p_id ) ) {
										$challenges = arcane_return_all_team_challenges( $p_id );
									}

									if ( ! isset( $challenges ) ) {
										$challenges = null;
									}

									if ( isset( $p_id ) ) {
										$edits = arcane_return_all_team_edits( $p_id );
									}
									if ( ! isset( $edits ) ) {
										$edits = null;
									}
									if ( isset( $edits ) && count( $edits ) > 0 ) {
										$br_notifikacija = $br_notifikacija + count( $edits );
									}


									if ( count( $challenges ) > 0 && $br_notifikacija > 0 ) { ?>

                                        <a class="msg_ntf"
                                           data-original-title="<?php esc_html_e( "# of notifications: ", 'arcane' ); ?><?php echo esc_attr( count( $challenges ) ) + esc_attr( $br_notifikacija ); ?>"
                                           data-toggle="tooltip"><?php echo esc_attr( count( $challenges ) ) + esc_attr( $br_notifikacija ); ?></a>
									<?php } elseif ( count( $challenges ) > 0 ) { ?>

                                        <a class="msg_ntf"
                                           data-original-title="<?php esc_html_e( "# of challenges: ", 'arcane' ); ?><?php echo esc_attr( count( $challenges ) ); ?>"
                                           data-toggle="tooltip"><?php echo esc_attr( count( $challenges ) ); ?></a>

									<?php } elseif ( $br_notifikacija > 0 ) { ?>
                                        <a class="msg_ntf"
                                           data-original-title="<?php esc_html_e( "# of notifications: ", 'arcane' ); ?><?php echo esc_attr( $br_notifikacija ); ?>"
                                           data-toggle="tooltip"><?php echo esc_attr( $br_notifikacija ); ?></a>


									<?php } ?>


								<?php } ?>
                            </li><!-- / Matches -->


                            <li>
                                <a data-toggle="tab" href="#tournaments">
                                    <i class="fas fa-trophy"></i> <?php esc_html_e( 'Tournaments', 'arcane' ); ?>
                                </a>
                            </li><!-- / Tournaments -->

							<?php if ( arcane_is_admin( $p_id, $c_id ) ) { ?>
                                <a class="team-setting"
                                   href="<?php echo arcane_get_permalink_for_template( 'tmp-team-creation.php' ) . '?p_id=' . esc_attr( $p_id ); ?>">
                                    <i class="fas fa-cog"></i>&nbsp;<?php esc_html_e( 'Settings', 'arcane' ); ?>
                                </a>
							<?php } ?>
                        </ul>
                        <div class="tab-content">
                            <!--team-->
                            <div id="team" class="tab-pane active">
                                <div class="p_main_info">

                                    <div class="container">
                                        <div class="col-8">

                                            <ul class="matchstats">

												<?php if ( ! empty( $platforms ) ) { ?>
                                                    <li>
                                                        <h3>
															<?php
															$i     = 1;
															$total = count( $platforms );
															foreach ( $platforms as $platform ) {
																switch ( $platform ) {
																	case 'pc':
																		esc_html_e( 'PC', 'arcane' );
																		break;
																	case 'ps':
																		esc_html_e( 'PS4', 'arcane' );
																		break;
                                                                    case 'ps5':
                                                                        esc_html_e( 'PS5', 'arcane' );
                                                                        break;
																	case 'xbox':
																		esc_html_e( 'Xbox', 'arcane' );
																		break;
																	case 'wii':
																		esc_html_e( 'Wii', 'arcane' );
																		break;
																	case 'nin':
																		esc_html_e( 'Nintendo', 'arcane' );
																		break;
																	case 'mobile':
																		esc_html_e( 'Mobile', 'arcane' );
																		break;
																	case 'cross':
																		esc_html_e( 'Cross', 'arcane' );
																		break;
																}
																if ( $i != $total ) {
																	echo ', ';
																}

																$i ++;
															}
															?>
                                                        </h3>
                                                        <span><i class="fas fa-gamepad"></i> <?php esc_html_e( 'Platforms', 'arcane' ); ?></span>
                                                    </li>
												<?php } ?>

												<?php if ( ! empty( $location ) ) { ?>
                                                    <li>
                                                        <h3><?php echo esc_html( $location ); ?></h3>
                                                        <span><i class="fas fa-globe-americas"></i> <?php esc_html_e( 'Location', 'arcane' ); ?></span>
                                                    </li>
												<?php } ?>

												<?php if ( ! empty( $language ) ) { ?>
                                                    <li>
                                                        <h3><?php echo esc_html( $language ); ?></h3>
                                                        <span><i class="fas fa-language"></i> <?php esc_html_e( 'Language', 'arcane' ); ?></span>
                                                    </li>
												<?php } ?>


                                            </ul>

											<?php while ( have_posts() ) : the_post(); ?>
												<?php the_content();
												wp_reset_postdata(); ?>
											<?php endwhile; // end of the loop. ?>
                                        </div>
                                        <div class="col-4">

                                            <div class="title-wrapper"><?php esc_html_e( 'Matches', 'arcane' ); ?></div>
                                            <div class="wpb_wrapper widget matches_block widget_other_matches">
												<?php

												$matches = arcane_return_all_team_matches( $p_id );
												$games1  = [];
												foreach ( $matches as $i => $match ) {

													if ( $match->status == 'active' || $match->status == 'done' ) {
														$match_meta = get_post_meta( $match->ID );
														$games1 []  = $match_meta['game_id'][0];
													}
												}

												?>

                                                <div class="teamwar-list">

													<?php

													if ( ! empty( $games1 ) ) {
														$games1 = array_unique( $games1 ); ?>

                                                        <ul class="matches-tab">
															<?php
                                                            if(count($games1) > 1){ ?>
                                                                <li class="active">
                                                                    <a class="custom-tabs-link" href="#all"><?php esc_html_e('All', 'arcane'); ?></a>
                                                                </li>
                                                            <?php
	                                                            foreach ( $games1 as $g ) { ?>


                                                                    <li>
                                                                        <a class="custom-tabs-link"
                                                                           href="#<?php echo esc_attr( $g ); ?>"
                                                                           title="<?php echo esc_attr( arcane_return_game_name_by_game_id( $g ) ); ?>">
				                                                            <?php echo esc_html( arcane_return_game_abbr_by_game_id( $g ) ); ?>
                                                                        </a>
                                                                    </li>
		                                                            <?php
	                                                            }
                                                            }


															?>
                                                        </ul>

													<?php }

													if ( function_exists( 'arcane_other_matches_sort' ) ) {
														usort( $matches, 'arcane_other_matches_sort' );
													}


													// generate table content
													$j = 0;

													if ( ! empty( $matches ) ) { ?>
                                                    <ul class="matches-wrapper">

															<?php

															foreach ( $matches as $i => $match ) {

																$match_meta = get_post_meta( $match->ID );
																$match_tip  = array();
																if ( isset( $match_meta['tournament_participants'] ) ) {
																	$match_tip = $match_meta['tournament_participants'];
																}

																if ( $match->status == 'active' || $match->status == 'done' ) {

																	$date      = $match_meta['date'][0];
																	$timestamp = $match_meta['date_unix'][0];

																	if ( $match->team1 == $p_id or $match->team2 == $p_id ) {

																		$t1 = '';
																		if ( isset( $match->team1_tickets ) ) {
																			$t1 = $match->team1_tickets;
																		}

																		$t2 = '';
																		if ( isset( $match->team2_tickets ) ) {
																			$t2 = $match->team2_tickets;
																		}
																		$wld_class = '';
																		if ( isset( $match->team1_tickets ) || isset( $match->team2_tickets ) ) {
																			$wld_class = $t1 == $t2 ? 'draw' : ( $t1 > $t2 ? 'win' : 'loose' );
																		}

																		$gameid  = $match->game_id;
																		$gameabr = arcane_return_game_abbr( $gameid );


																		$team1id = $match->team1;
																		$team2id = $match->team2;

																		if ( $match_tip[0] == 'team' or $match_tip[0] == null ) {
																			$img_url1 = get_post_meta( $team1id, 'team_photo', true );
																			$img_url2 = get_post_meta( $team2id, 'team_photo', true );
																		} else {
																			$img_url1 = get_user_meta( $team1id, 'profile_photo', true );
																			$img_url2 = get_user_meta( $team2id, 'profile_photo', true );
																		}

																		$image1 = arcane_aq_resize( $img_url1, 25, 25, true ); //resize & crop img
																		$image2 = arcane_aq_resize( $img_url2, 25, 25, true ); //resize & crop img

																		$is_upcoming = $timestamp > $now;
																		$is_playing  = ( ( $now > $timestamp && $now < $timestamp + 3600 ) && ( $t1 == 0 && $t2 == 0 ) || ( $match->status == 'active' ) && ( $t1 == 0 && $t2 == 0 ) );
																		?>
                                                                        <!--team-->

                                                                        <li data-type="#<?php echo esc_attr( $match->game_id ); ?>"
                                                                             class="teamwar-item">

                                                                            <div class="wrap">
																				<?php echo '<a href="' . get_permalink( $match->ID ) . '" data-toggle="tooltip" data-original-title="' . esc_attr( $match->title ) . '">'; ?>
																				<?php if ( $is_upcoming ) : ?>
                                                                                    <div class="upcoming"><?php esc_html_e( 'Upcoming', 'arcane' ); ?></div>
																				<?php elseif ( $is_playing ) : ?>
                                                                                    <div class="playing"><?php esc_html_e( 'Playing', 'arcane' ); ?></div>
																				<?php else : ?>
                                                                                    <div class="scores <?php echo esc_attr( $wld_class ); ?>"><?php echo sprintf( esc_html__( '%1$d:%2$d', 'arcane' ), $t1, $t2 ); ?></div>
																				<?php endif; ?>
																				<?php if ( ! isset( $image1 ) or empty( $image1 ) ) {
																					$image1 = get_theme_file_uri( 'img/defaults/team25x25.jpg' );
																				} ?>
																				<?php if ( ! isset( $image2 ) or empty( $image2 ) ) {
																					$image2 = get_theme_file_uri( 'img/defaults/team25x25.jpg' );
																				} ?>
                                                                                <div class="match-wrap">

                                                                                    <img src="<?php echo esc_url( $image1 ); ?>"
                                                                                         class="team1img"
                                                                                         alt="<?php echo esc_attr( $match->title ); ?>"/>

                                                                                    <span class="vs"><?php esc_html_e( "vs", "arcane" ); ?></span>
                                                                                    <div class="opponent-team">
                                                                                        <img src="<?php echo esc_url( $image2 ); ?>"
                                                                                             class="team1img"
                                                                                             alt="<?php echo esc_attr( $match->title ); ?>"/>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="date"><?php echo esc_html( $gameabr ); ?>
                                                                                    - <?php echo esc_html( $date ); ?></div>
                                                                                </a>
                                                                            </div>

                                                                        </li>

																		<?php
																		$j ++;
																	}

																}
															}

															if ( $j == 0 ) { ?>
                                                                <div class="wcontainer">
                                                                    <ul class="gamesb">
                                                                        <p class="no-matches-yet"><?php esc_html_e( 'This team doesn\'t have any matches yet!', 'arcane' ); ?></p>
                                                                    </ul>
                                                                </div>

																<?php
															}

															?>
                                                        </ul>
														<?php
													} else { ?>
                                                        <div class="wcontainer">
                                                            <ul class="gamesb">
                                                                <p class="no-matches-yet"><?php esc_html_e( 'This team doesn\'t have any matches yet!', 'arcane' ); ?></p>
                                                            </ul>
                                                        </div>

													<?php } ?>

                                                </div>
                                            </div>


                                            <div class="title-wrapper"><?php esc_html_e( 'Games', 'arcane' ); ?></div>
                                            <ul class="gamesb">
												<?php foreach ( $games as $game ) {

													$game_icon = arcane_return_game_image( $game );
													$game_name = arcane_return_game_name_by_game_id( $game );
													?>
                                                    <li class="overlay game_<?php echo esc_attr( $game ); ?>">
                                                        <a href="<?php echo arcane_get_permalink_for_template( 'tmp-all-teams-page.php' ) . '?gid=' . esc_attr( $game ); ?>">
                                                            <img alt="img" src="<?php echo esc_url( $game_icon ); ?>">
                                                            <strong><?php echo esc_html( $game_name ); ?></strong>
                                                        </a>
                                                    </li>
												<?php } ?>
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/team-->

                        <!--members-->
                        <div id="members_tab" class="tab-pane">
                            <div class="members friends members-block" id="buddypress">
								<?php arcane_team_members( $p_id ); ?>
                            </div>
                        </div>
                        <!--/members-->

                        <!--matches-->
                        <div id="matches" class="tab-pane teampage-matches">


							<?php if ( arcane_is_admin( $p_id, get_current_user_id() ) ) { ?>
                                <!-- CHALLENGES -->
								<?php
								$challenges = [];
								if ( isset( $p_id ) ) {
									$challenges = arcane_return_all_team_challenges( $p_id );
								}

								if ( ! empty( $challenges ) ) { ?>

                                    <div class="title-wrapper"><h3
                                                class="widget-title"><?php esc_html_e( 'Challenges!', 'arcane' ); ?></h3>
                                        <div class="clear"></div>
                                    </div>
                                    <ul class="matches-wrapper">

										<?php foreach ( $challenges as $challenge ) {
											$challenge_match = $ArcaneWpTeamWars->get_match( array( 'id' => $challenge->ID ) );
											$gameid          = $challenge_match->game_id;
											$gameabr         = arcane_return_game_abbr( $gameid );
											?>
                                            <li>
                                                <a href="<?php echo esc_url( get_permalink( $challenge_match->ID ) ); ?>">
                                                    <h3><?php echo esc_html( $challenge_match->title ); ?></h3>
                                                    <div class="mw-wrapper">
                                                        <div class="mw-left">
                                                            <img alt="img"
                                                                 src="<?php echo esc_url( arcane_return_team_image( $challenge_match->team1 ) ); ?>">
                                                            <div>
                                                                <small><?php echo esc_html( $gameabr ); ?></small>
                                                                <strong><?php echo get_the_title( $challenge_match->team1 ); ?></strong>
                                                            </div>
                                                            <strong>0</strong>
                                                        </div>
                                                        <div class="mw-mid">
                                                            <span class="upcoming"><?php esc_html_e( 'upcoming', 'arcane' ); ?></span>
                                                            <small>
																<?php
																if ( isset( $challenge_match->date_unix ) && ! empty( $challenge_match->date_unix ) ) {
																	$timezone_string     = arcane_timezone_string();
																	$tournament_timezone = $timezone_string ? $timezone_string : 'UTC';

																	$currentTime = DateTime::createFromFormat( 'U', $challenge_match->date_unix );
																	$currentTime->setTimeZone( new DateTimeZone( $tournament_timezone ) );
																	$formattedString = $currentTime->format( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) );
																	echo esc_attr( $formattedString );

																} else {
																	$date = mysql2date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), $challenge_match->date );
																	echo esc_attr( $date );
																}
																?>
                                                            </small>
                                                        </div>
                                                        <div class="mw-right">
                                                            <strong>0</strong>
                                                            <div>
                                                                <small><?php echo esc_html( $gameabr ); ?></small>
                                                                <strong><?php echo get_the_title( $challenge_match->team2 ); ?></strong>
                                                            </div>
                                                            <img alt="img"
                                                                 src="<?php echo esc_url( arcane_return_team_image( $challenge_match->team2 ) ); ?>">
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="member-list-more">
                                                    <div class="mlm1 mj">

														<?php if ( $p_id == $challenge_match->team1 ) { ?>
															<?php esc_html_e( 'Pending challenge!', 'arcane' ); ?>
														<?php } else { ?>
                                                            <a href="<?php echo get_permalink( $challenge_match->team1 ); ?>"><?php $n = get_the_title( $challenge_match->team1 );
																echo esc_attr( $n ); ?></a>
                                                            &nbsp;<?php esc_html_e( 'has challenged you to a match! - Accept the challenge?', 'arcane' ); ?>
                                                            <a class="ajaxloadchl" data-req="accept_challenge"
                                                               data-cid="<?php echo esc_attr( $challenge_match->ID ); ?>"><i
                                                                        class="fas fa-check"></i></a>
                                                            <a class="ajaxloadchl" data-req="reject_challenge"
                                                               data-cid="<?php echo esc_attr( $challenge_match->ID ); ?>"><i
                                                                        class="fas fa-times"></i></a>
														<?php } ?>

                                                    </div>
                                                </div>
                                            </li>
										<?php } ?>
                                    </ul>
                                    <div class="nav-divider-wrapper">
                                        <div class="col-lg-12 col-md-12 nav-top-divider"></div>
                                    </div>
                                    <!-- /CHALLENGES -->
                                    </ul>
								<?php } ?>


                                <!-- /EDITS -->
								<?php
								$edits = [];
								if ( isset( $p_id ) ) {
									$edits = arcane_return_all_team_edits( $p_id );
								}

								if ( ! empty( $edits ) ) { ?>


                                    <div class="title-wrapper"><h3
                                                class="widget-title"><?php esc_html_e( 'Edits!', 'arcane' ); ?></h3>
                                        <div class="clear"></div>
                                    </div>
                                    <ul class="matches-wrapper">

										<?php foreach ( $edits as $edit ) {
											$edit_match = $ArcaneWpTeamWars->get_match( array( 'id' => $edit->ID ) );
											$gameid     = $edit_match->game_id;
											$gameabr    = arcane_return_game_abbr( $gameid );

											$match_meta = get_post_meta( $edit_match->ID );
											$date       = $match_meta['date'][0];
											$timestamp  = $match_meta['date_unix'][0];

											$t1 = '';
											if ( isset( $edit_match->team1_tickets ) ) {
												$t1 = $edit_match->team1_tickets;
											}

											$t2 = '';
											if ( isset( $edit_match->team2_tickets ) ) {
												$t2 = $edit_match->team2_tickets;
											}

											$is_upcoming = $timestamp > $now;
											$is_playing  = ( ( $now > $timestamp && $now < $timestamp + 3600 ) && ( $t1 == 0 && $t2 == 0 ) || ( $edit_match->status == 'active' ) && ( $t1 == 0 && $t2 == 0 ) );
											?>

                                            <li>
                                                <a href="<?php echo esc_url( get_permalink( $edit_match->ID ) ); ?>">
                                                    <h3><?php echo esc_html( $edit_match->title ); ?></h3>
                                                    <div class="mw-wrapper">

                                                        <div class="mw-left">
                                                            <img alt="img"
                                                                 src="<?php echo esc_url( arcane_return_team_image( $edit_match->team1 ) ); ?>">
                                                            <div>
                                                                <small><?php echo esc_html( $gameabr ); ?></small>
                                                                <strong><?php echo get_the_title( $edit_match->team1 ); ?></strong>
                                                            </div>
                                                            <strong>0</strong>
                                                        </div>
                                                        <div class="mw-mid">

															<?php
															if ( $is_upcoming ) :

																?> <span
                                                                    class="upcoming"> <?php esc_html_e( 'Upcoming', 'arcane' ); ?> </span> <?php
                                                            elseif ( $is_playing ) :
																?> <span
                                                                    class="playing"> <?php esc_html_e( 'Playing', 'arcane' ); ?> </span> <?php
															else :
																?> <span
                                                                    class="finished"> <?php esc_html_e( 'Finished', 'arcane' ); ?> </span> <?php
															endif;
															?>
                                                            </span>
                                                            <small>
																<?php
																if ( isset( $edit_match->date_unix ) && ! empty( $edit_match->date_unix ) ) {
																	$timezone_string     = arcane_timezone_string();
																	$tournament_timezone = $timezone_string ? $timezone_string : 'UTC';

																	$currentTime = DateTime::createFromFormat( 'U', $edit_match->date_unix );
																	$currentTime->setTimeZone( new DateTimeZone( $tournament_timezone ) );
																	$formattedString = $currentTime->format( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) );
																	echo esc_attr( $formattedString );

																} else {
																	$date = mysql2date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), $edit_match->date );
																	echo esc_attr( $date );
																}
																?>
                                                            </small>
                                                        </div>
                                                        <div class="mw-right">
                                                            <strong>0</strong>
                                                            <div>
                                                                <small><?php echo esc_html( $gameabr ); ?></small>
                                                                <strong><?php echo get_the_title( $edit_match->team2 ); ?></strong>
                                                            </div>
                                                            <img alt="img"
                                                                 src="<?php echo esc_url( arcane_return_team_image( $edit_match->team2 ) ); ?>">
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="member-list-more">
                                                    <div class="mlm1 mj">

														<?php if ( $edit_match->status == 'edited2' && arcane_is_member( $edit_match->team2, $c_id ) ) { ?>
															<?php esc_html_e( 'Pending edit!', 'arcane' ); ?>
														<?php } elseif ( $edit_match->status == 'edited2' && arcane_is_member( $edit_match->team1, $c_id ) ) { ?>
                                                            <a href="<?php echo get_permalink( $edit_match->ID ); ?>"><?php $n = get_the_title( $edit_match->team2 );
																echo esc_attr( $n ); ?></a>
                                                            &nbsp;<?php esc_html_e( 'has edited a challenge! - Accept the changes?', 'arcane' ); ?>
                                                            <a class="ajaxloadedit" data-req="accept_edit"
                                                               data-cid="<?php echo esc_attr( $edit_match->ID ); ?>"><i
                                                                        class="fas fa-check"></i></a>
                                                            <a class="ajaxloadedit" data-req="reject_edit"
                                                               data-cid="<?php echo esc_attr( $edit_match->ID ); ?>"><i
                                                                        class="fas fa-times"></i></a>
														<?php } ?>

														<?php if ( $edit_match->status == 'edited1' && arcane_is_member( $edit_match->team1, $c_id ) ) { ?>
															<?php esc_html_e( 'Pending edit!', 'arcane' ); ?>
														<?php } elseif ( $edit_match->status == 'edited1' && arcane_is_member( $edit_match->team2, $c_id ) ) { ?>
                                                            <a href="<?php echo get_permalink( $edit_match->ID ); ?>"><?php $n = get_the_title( $edit_match->team1 );
																echo esc_attr( $n ); ?></a>
                                                            &nbsp;<?php esc_html_e( 'has edited a challenge! - Accept the changes?', 'arcane' ); ?>
                                                            <a class="ajaxloadedit" data-req="accept_edit"
                                                               data-cid="<?php echo esc_attr( $edit_match->ID ); ?>"><i
                                                                        class="fas fa-check"></i></a>
                                                            <a class="ajaxloadedit" data-req="reject_edit"
                                                               data-cid="<?php echo esc_attr( $edit_match->ID ); ?>"><i
                                                                        class="fas fa-times"></i></a>
														<?php } ?>

                                                    </div>
                                                </div>
                                            </li>
										<?php } ?>
                                    </ul>
                                    <div class="nav-divider-wrapper">
                                        <div class="col-lg-12 col-md-12 nav-top-divider"></div>
                                    </div>

                                    <!-- /EDITS -->
								<?php } ?>


                                <!-- /DELETES -->
								<?php
								$deletes = [];
								if ( isset( $p_id ) ) {
									$deletes = arcane_return_all_team_deletes( $p_id );
								}

								if ( ! empty( $deletes ) ) { ?>


                                    <div class="title-wrapper"><h3
                                                class="widget-title"><?php esc_html_e( 'Deletes!', 'arcane' ); ?></h3>
                                        <div class="clear"></div>
                                    </div>
                                    <ul class="matches-wrapper">
										<?php foreach ( $deletes as $delete ) { ?>
											<?php
											$delete_match = $ArcaneWpTeamWars->get_match( array( 'id' => $delete->ID ) );
											$gameid       = $delete_match->game_id;
											$gameabr      = arcane_return_game_abbr( $gameid );

											$match_meta = get_post_meta( $delete_match->ID );
											$date       = $match_meta['date'][0];
											$timestamp  = $match_meta['date_unix'][0];

											$t1 = '';
											if ( isset( $delete_match->team1_tickets ) ) {
												$t1 = $delete_match->team1_tickets;
											}

											$t2 = '';
											if ( isset( $delete_match->team2_tickets ) ) {
												$t2 = $delete_match->team2_tickets;
											}

											$is_upcoming = $timestamp > $now;
											$is_playing  = ( ( $now > $timestamp && $now < $timestamp + 3600 ) && ( $t1 == 0 && $t2 == 0 ) || ( $delete_match->status == 'active' ) && ( $t1 == 0 && $t2 == 0 ) );

											?>

                                            <li>
                                                <a href="<?php echo esc_url( get_permalink( $delete_match->ID ) ); ?>">
                                                    <h3><?php echo esc_html( $delete_match->title ); ?></h3>
                                                    <div class="mw-wrapper">
                                                        <div class="mw-left">
                                                            <img alt="img"
                                                                 src="<?php echo esc_url( arcane_return_team_image( $delete_match->team1 ) ); ?>">
                                                            <div>
                                                                <small><?php echo esc_html( $gameabr ); ?></small>
                                                                <strong><?php echo get_the_title( $delete_match->team1 ); ?></strong>
                                                            </div>
                                                            <strong>0</strong>
                                                        </div>
                                                        <div class="mw-mid">
                                                            <span>
                                                               <?php
                                                               if ( $is_upcoming ) :

	                                                               ?> <span
                                                                       class="upcoming"> <?php esc_html_e( 'Upcoming', 'arcane' ); ?> </span> <?php
                                                               elseif ( $is_playing ) :
	                                                               ?> <span
                                                                       class="playing"> <?php esc_html_e( 'Playing', 'arcane' ); ?> </span> <?php
                                                               else :
	                                                               ?> <span
                                                                       class="finished"> <?php esc_html_e( 'Finished', 'arcane' ); ?> </span> <?php
                                                               endif;
                                                               ?>
                                                            </span>
                                                            <small>
																<?php
																if ( isset( $delete_match->date_unix ) && ! empty( $delete_match->date_unix ) ) {
																	$timezone_string     = arcane_timezone_string();
																	$tournament_timezone = $timezone_string ? $timezone_string : 'UTC';

																	$currentTime = DateTime::createFromFormat( 'U', $delete_match->date_unix );
																	$currentTime->setTimeZone( new DateTimeZone( $tournament_timezone ) );
																	$formattedString = $currentTime->format( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) );
																	echo esc_attr( $formattedString );

																} else {
																	$date = mysql2date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), $delete_match->date );
																	echo esc_attr( $date );
																}
																?>
                                                            </small>
                                                        </div>
                                                        <div class="mw-right">
                                                            <strong>0</strong>
                                                            <div>
                                                                <small><?php echo esc_html( $gameabr ); ?></small>
                                                                <strong><?php echo get_the_title( $delete_match->team2 ); ?></strong>
                                                            </div>
                                                            <img alt="img"
                                                                 src="<?php echo esc_url( arcane_return_team_image( $delete_match->team2 ) ); ?>">
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="member-list-more">
                                                    <div class="mlm1 mj">
														<?php if ( $delete_match->status == 'deleted_request_team1' && arcane_is_member( $delete_match->team1, $c_id ) ) { ?>
															<?php esc_html_e( 'Pending delete!', 'arcane' ); ?>
														<?php } elseif ( $delete_match->status == 'deleted_request_team1' && arcane_is_member( $delete_match->team2, $c_id ) ) { ?>
                                                            <a href="<?php echo get_permalink( $delete_match->ID ); ?>"><?php $n = get_the_title( $delete_match->team1 );
																echo esc_attr( $n ); ?></a>
                                                            &nbsp;<?php esc_html_e( 'has submitted delete request! -  Accept?', 'arcane' ); ?>
                                                            <a class="ajaxdeletematch" data-req="accept_delete"
                                                               data-mid="<?php echo esc_attr( $delete_match->ID ); ?>"><i
                                                                        class="fas fa-check"></i></a>
                                                            <a class="ajaxdeletematch" data-req="reject_delete"
                                                               data-mid="<?php echo esc_attr( $delete_match->ID ); ?>"><i
                                                                        class="fas fa-times"></i></a>
														<?php } ?>

														<?php if ( $delete_match->status == 'deleted_request_team2' && arcane_is_member( $delete_match->team2, $c_id ) ) { ?>
															<?php esc_html_e( 'Pending delete!', 'arcane' ); ?>
														<?php } elseif ( $delete_match->status == 'deleted_request_team2' && arcane_is_member( $delete_match->team1, $c_id ) ) { ?>
                                                            <a href="<?php echo get_permalink( $delete_match->ID ); ?>"><?php $n = get_the_title( $delete_match->team2 );
																echo esc_attr( $n ); ?></a>
                                                            &nbsp;<?php esc_html_e( 'has submitted delete request! -  Accept?', 'arcane' ); ?>
                                                            <a class="ajaxdeletematch" data-req="accept_delete"
                                                               data-mid="<?php echo esc_attr( $delete_match->ID ); ?>"><i
                                                                        class="fas fa-check"></i></a>
                                                            <a class="ajaxdeletematch" data-req="reject_delete"
                                                               data-mid="<?php echo esc_attr( $delete_match->ID ); ?>"><i
                                                                        class="fas fa-times"></i></a>
														<?php } ?>
                                                    </div>
                                                </div>
                                            </li>

										<?php } ?>
                                    </ul>
                                    <div class="nav-divider-wrapper">
                                        <div class="col-lg-12 col-md-12 nav-top-divider"></div>
                                    </div>
                                    <!-- /DELETES -->
								<?php } ?>
							<?php } ?>

							<?php

							if ( ! empty( $challenges ) ) {
								$challenge_ids = array();
								foreach ( $challenges as $challenge ) {
									$challenge_ids[] = $challenge->ID;
								}
								foreach ( $matches as $key => $match ) {
									foreach ( $challenge_ids as $challenge_id ) {
										if ( $challenge_id == $match->ID ) {
											unset( $matches[ $key ] );
										}
									}
								}
							}

							if ( ! empty( $edits ) ) {
								$edit_ids = array();
								foreach ( $edits as $edit ) {
									$edit_ids[] = $edit->ID;
								}
								foreach ( $matches as $key => $match ) {
									foreach ( $edit_ids as $edit_id ) {
										if ( $edit_id == $match->ID ) {
											unset( $matches[ $key ] );
										}
									}
								}
							}

							if ( ! empty( $deletes ) ) {
								$delete_ids = array();
								foreach ( $deletes as $delete ) {
									$delete_ids[] = $delete->ID;
								}
								foreach ( $matches as $key => $match ) {
									foreach ( $delete_ids as $delete_id ) {
										if ( $delete_id == $match->ID ) {
											unset( $matches[ $key ] );
										}
									}
								}
							}
							?>

							<?php if ( ! empty( $matches ) ) { ?>
                                <div class="title-wrapper">
                                    <h3 class="widget-title"><?php esc_html_e( 'Matches', 'arcane' ); ?></h3>
                                    <div class="clear"></div>
                                </div>

                                <ul class="matches-wrapper">

									<?php

									foreach ( $matches as $match ) {
										$match = $ArcaneWpTeamWars->get_match( array( 'id' => $match->ID ) );

										$results_matches = $match;

										$t1 = '';
										if ( isset( $results_matches->team1_tickets ) ) {
											$t1 = $results_matches->team1_tickets;
										}

										$t2 = '';
										if ( isset( $results_matches->team2_tickets ) ) {
											$t2 = $results_matches->team2_tickets;
										}

										if ( arcane_is_admin( $p_id, get_current_user_id() ) || arcane_is_admin( $p_id, get_current_user_id() ) ) {
											$admin = true;
										} else {
											$admin = false;
										}

										if ( $match->status == 'submitted1' || $match->status == 'submitted2' ) {
											$substatus = true;
										} else {
											$substatus = false;
										}

										$gameid  = $match->game_id;
										$gameabr = arcane_return_game_abbr( $gameid );

										$match_meta = get_post_meta( $results_matches->ID );
										$date       = $match_meta['date'][0];
										$timestamp  = $match_meta['date_unix'][0];

										$is_upcoming = $timestamp > $now;
										$is_playing  = ( ( $now > $timestamp && $now < $timestamp + 3600 ) && ( $t1 == 0 && $t2 == 0 ) || ( $results_matches->status == 'active' ) && ( $t1 == 0 && $t2 == 0 ) );

										?>

                                        <li>
                                            <a href="<?php echo esc_url( get_permalink( $match->ID ) ); ?>">
                                                <h3><?php echo esc_html( $match->title ); ?></h3>
                                                <div class="mw-wrapper">
                                                    <div class="mw-left">
                                                        <img alt="img"
                                                             src="<?php echo esc_url( arcane_return_team_image( $match->team1 ) ); ?>">
                                                        <div>
                                                            <small><?php echo esc_html( $gameabr ); ?></small>
                                                            <strong><?php echo get_the_title( $match->team1 ); ?></strong>
                                                        </div>
														<?php if ( $match->status == 'done' ) { ?>

                                                            <strong><?php $r1 = $t1 == null ? '0' : $t1;
																echo esc_attr( $r1 ); ?></strong>

														<?php } else { ?>


															<?php if ( $admin && $substatus ) { ?>
                                                                <strong><?php $r1 = $t1 == null ? '0' : $t1;
																	echo esc_attr( $r1 ); ?></strong>
															<?php } else { ?>
                                                                <strong>0</strong>
															<?php } ?>

														<?php } ?>
                                                    </div>
                                                    <div class="mw-mid">
                                                        <span>
                                                            <?php
                                                            if ( $is_upcoming ) :

	                                                            ?> <span
                                                                    class="upcoming"> <?php esc_html_e( 'Upcoming', 'arcane' ); ?> </span> <?php
                                                            elseif ( $is_playing ) :
	                                                            ?> <span
                                                                    class="playing"> <?php esc_html_e( 'Playing', 'arcane' ); ?> </span> <?php
                                                            else :
	                                                            ?> <span
                                                                    class="finished"> <?php esc_html_e( 'Finished', 'arcane' ); ?> </span> <?php
                                                            endif;
                                                            ?>
                                                        </span>
                                                        <small>
															<?php
															if ( isset( $match->date_unix ) && ! empty( $match->date_unix ) ) {
																$timezone_string     = arcane_timezone_string();
																$tournament_timezone = $timezone_string ? $timezone_string : 'UTC';

																$currentTime = DateTime::createFromFormat( 'U', $match->date_unix );
																$currentTime->setTimeZone( new DateTimeZone( $tournament_timezone ) );
																$formattedString = $currentTime->format( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) );
																echo esc_attr( $formattedString );

															} else {
																echo esc_attr( $date );
															}
															?>
                                                        </small>
                                                    </div>
                                                    <div class="mw-right">
														<?php if ( $match->status == 'done' ) { ?>

                                                            <strong><?php $r2 = $t2 == null ? '0' : $t2;
																echo esc_attr( $r2 ); ?></strong>

														<?php } else { ?>

															<?php if ( $admin && $substatus ) { ?>
                                                                <strong><?php $r2 = $t2 == null ? '0' : $t2;
																	echo esc_attr( $r2 ); ?></strong>
															<?php } else { ?>
                                                                <strong>0</strong>
															<?php } ?>

														<?php } ?>

                                                        <div>
                                                            <small><?php echo esc_html( $gameabr ); ?></small>
                                                            <strong><?php echo get_the_title( $match->team2 ); ?></strong>
                                                        </div>
                                                        <img alt="img"
                                                             src="<?php echo esc_url( arcane_return_team_image( $match->team2 ) ); ?>">
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
									<?php } ?>
                                </ul>
							<?php } ?>

							<?php
							$matc = [];
							if ( isset( $p_id ) ) {
								$matc = arcane_return_all_team_matches( $p_id );
							}
							?>
							<?php if ( empty( $matc ) && empty( $challenges ) && arcane_is_admin( $p_id, get_current_user_id() ) ) { ?>
                                <div class="error_msg">
                                    <span><?php esc_html_e( 'Currently you don\'t have any matches, go and challenge someone!', 'arcane' ); ?></span>
                                </div>
							<?php } elseif ( empty( $matc ) && empty( $challenges ) && ! arcane_is_admin( $p_id, get_current_user_id() ) ) { ?>
                                <div class="error_msg">
                                    <span><?php esc_html_e( 'This team currently doesn\'t have any matches!', 'arcane' ); ?></span>
                                </div>
							<?php } ?>


                            </ul>
                        </div>
                        <!--/matches-->


                        <!--tournaments-->
                        <div id="tournaments" class="tab-pane teampage-tournaments">

							<?php
							$games = $ArcaneWpTeamWars->get_game( '' );
							$args  = array(
								'post_type'      => 'tournament',
								'posts_per_page' => - 1,
								'meta_query'     => array(
									'relation' => 'AND',
									array(
										'relation' => 'OR',
										array(
											'key'     => 'tournament_competitors',
											'value'   => ':' . $p_id . ';',
											'compare' => 'LIKE',
										),
										array(
											'key'     => 'tournament_competitors',
											'value'   => ':"' . $p_id . '";',
											'compare' => 'LIKE',
										),
									),
									array(
										'key'     => 'tournament_contestants',
										'value'   => 'team',
										'compare' => 'LIKE',
									),
								)
							);

							$posts = get_posts( $args );
							if ( is_array( $posts ) ) {
								echo '<ul class="tournaments-list">';
								foreach ( $posts as $single ) {
									echo arcane_return_tournament_block( $single->ID, $games, true );
								}
								echo '</ul>';
							}

							if ( empty( $posts ) ) {
								echo '<div class="error">';
								esc_html_e( "This team doesn't have any tournaments yet!", 'arcane' );
								echo '</div>';
							}

							?>

                        </div>

                    </div><!-- tab content -->
                </div><!-- tab wrap -->
            </div><!-- row -->
        </div> <!-- /container -->
    </div>
    </div><!-- /mainwrap -->

<?php
$other_user = arcane_return_super_admin( $p_id );
if ( $arcane_challenge_sent == 'yes' ) {
	$arcane_challenge_sent = '';
	arcane_challenge_sent( $other_user, $p_id );
}
?>
<?php get_footer(); ?>