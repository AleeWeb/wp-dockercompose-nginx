<?php get_header(); ?>
<?php
global $post, $ArcaneWpTeamWars;

$u = wp_get_current_user();
$tournament_mapping = arcane_tournament_mapping();

$usermeta = '';
if ( isset( $u->ID ) ) {
	$usermeta = get_user_meta( $u->ID );
}

$post_id = get_the_ID();

$author_id = get_post_field( 'post_author', $post_id );


$options = arcane_get_theme_options();


/**Get meta**/
$tournament_timezone        = get_post_meta( $post_id, 'tournament_timezone', true );
$game_name                  = get_post_meta( $post_id, 'tournament_game', true );
$gid                        = arcane_return_game_id_by_game_name( $game_name );
$max_number_of_participants = get_post_meta( $post_id, 'tournament_max_participants', true );
$tournament_starts          = get_post_meta( $post_id, 'tournament_starts', true );
$tournament_starts_unix     = get_post_meta( $post_id, 'tournament_starts_unix', true );
$tformat                    = get_post_meta( $post_id, 'format', true );
$rounds                     = get_post_meta( $post_id, 'game_cache', true );
$game_stop                  = get_post_meta( $post_id, 'game_stop', true );
$game_modes                 = get_post_meta( $post_id, 'game_modes', true );
$contestants                = get_post_meta( $post_id, 'tournament_contestants', true );
$candidate                  = get_post_meta( $post_id, 'tournament_candidate', true );
$delete_competitors         = get_post_meta( $post_id, 'tournament_delete_competitors', true );
$tournament_competitors     = get_post_meta( $post_id, 'tournament_competitors', true );
if ( empty( $tournament_competitors ) ) {
	$tournament_competitors = [];
}
$current_number_of_participants = count( $tournament_competitors );
$tournament_server              = get_post_meta( $post_id, 'tournament_server', true );
$tournament_platform            = get_post_meta( $post_id, 'tournament_platform', true );
$games_format                   = get_post_meta( $post_id, 'tournament_games_format', true );
$game_frequency                 = get_post_meta( $post_id, 'tournament_game_frequency', true );
$tournament_frequency           = get_post_meta( $post_id, 'tournament_frequency', true );
$tournament_maps                = get_post_meta( $post_id, 'tournament_maps', true );
$tournament_regulations         = get_post_meta( $post_id, 'tournament_regulations', true );
$prizes                         = get_post_meta( $post_id, 'tournament_prizes', true );

$can_edit = arcane_TournamentsCanEdit( $post_id );

$cur_time = '';
if ( ! empty( $tournament_timezone ) ) {
	$cur_time = new DateTime( "now", new DateTimeZone( $tournament_timezone ) );
	$cur_time = $cur_time->getTimestamp();
}


$could_edit = $finished = $premium_plugin = $premium_tournament = $join_any = $shown_delete = $product_id = $user_pay = $product_game_id = $general_product_id = $pending = false;

/**premium*/
if ( function_exists( 'arcane_get_product_id_by_tournament_id' ) ) {
	$premium_plugin = true;
}

if ( get_post_meta( $post_id, 'premium', true ) ) {
	$premium_tournament = get_post_meta( $post_id, 'premium', true );
}

$premium_user            = get_user_meta( get_current_user_id(), 'premium', true );
$premium_user_tournament = get_user_meta( get_current_user_id(), 'user_premium_tournament', true );

$proceed = false;
if ( isset( $premium_user_tournament ) && ! empty( $premium_user_tournament ) ) {
	$premium_user_tournament = explode( ',', $premium_user_tournament );
	if ( in_array( $post_id, $premium_user_tournament ) && $premium_user == 'true' ) {
		$proceed = true;
	}
} elseif ( $premium_user == 'true' && ( empty( $premium_user_tournament ) ) ) {
	$proceed = true;
}
if ( ! isset( $options['tournament_creation'] ) ) {
	$options['tournament_creation'] = true;
}
if ( ! isset( $options['premium_button_text'] ) ) {
	$options['premium_button_text'] = esc_html__( "Buy premium", 'arcane' );
}
if ( ! isset( $options['premium_pending_button_text'] ) ) {
	$options['premium_pending_button_text'] = esc_html__( "Premium pending", 'arcane' );
}


$link      = '';
$link_game = '';

if ( function_exists( 'arcane_get_product_id_by_tournament_id' ) && class_exists( 'WooCommerce' ) ) {
	$product_id      = arcane_get_product_id_by_tournament_id( $post_id );
	$product_game_id = arcane_get_product_id_by_game_id( $gid );
	if ( count( $product_game_id ) > 1 ) {
		$terms     = get_the_terms( $product_game_id[0]->post_id, 'product_cat' );
		$cat_id    = $terms[0]->term_id;
		$link_game = get_term_link( $cat_id );
	} else {
		if ( isset( $product_game_id[0]->post_id ) ) {
			$link_game = get_permalink( $product_game_id[0]->post_id );
		}
	}

	$user_pay           = arcane_check_payment_to_join( $post_id );
	$general_product_id = arcane_get_product_id_for_general_membership();
	if ( count( $general_product_id ) > 1 ) {
		$terms  = get_the_terms( $general_product_id[0]->post_id, 'product_cat' );
		$cat_id = $terms[0]->term_id;
		$link   = get_term_link( $cat_id );
	} else {
		if ( isset( $general_product_id[0]->post_id ) ) {
			$link = get_permalink( $general_product_id[0]->post_id );
		}
	}


	$customer_orders = wc_get_orders( array(
		'meta_key'    => '_customer_user',
		'meta_value'  => get_current_user_id(),
		'post_status' => array( 'wc-on-hold', 'wc-processing' ),
		'numberposts' => - 1
	) );

	foreach ( $customer_orders as $customer_order ) {
		$items = $customer_order->get_items();
		foreach ( $items as $item ) {
			if ( $item['product_id'] == (int) $product_id ) {
				$pending = true;
			}

			if ( isset( $product_game_id[0] ) && $item['product_id'] == (int) $product_game_id[0]->post_id ) {
				$pending = true;
			}

			if ( $item['product_id'] == (int) $general_product_id[0]->post_id ) {
				$pending = true;
			}

		}

	}

}


if ( get_user_meta( get_current_user_id(), 'membership_all_tournament', true ) ) {
	$join_any = get_user_meta( get_current_user_id(), 'membership_all_tournament', true );
}

$premium_user            = get_user_meta( get_current_user_id(), 'premium', true );
$premium_user_tournament = get_user_meta( get_current_user_id(), 'user_premium_tournament', true );

$proceed = false;
if ( isset( $premium_user_tournament ) && ! empty( $premium_user_tournament ) ) {
	$premium_user_tournament = explode( ',', $premium_user_tournament );
	if ( in_array( $post_id, $premium_user_tournament ) && $premium_user == 'true' ) {
		$proceed = true;
	}
} elseif ( $premium_user == 'true' && ( empty( $premium_user_tournament ) ) ) {
	$proceed = true;
}

$games = $ArcaneWpTeamWars->get_game( '' );

$started = (int) $tournament_starts_unix <= $cur_time;
?>


    <div class="page normal-page">
        <div class="tournament-header" data-tid="<?php echo esc_attr( $post_id ); ?>">

			<?php
			/*check if tournament finished*/

			if ( strtolower( $tformat ) == 'ladder' || strtolower( $tformat ) == 'royale' ) {
				if ( $game_stop == 1 ) {
					$finished = true;
				}

			} elseif ( ! empty( $rounds ) ) {
				$finished = true;

				if ( strtolower( $tformat ) == 'ladder' || strtolower( $tformat ) == 'royale' ) {
					if ( $game_stop != 1 ) {
						$finished = false;
					}
				} elseif ( strtolower( $tformat ) == 'knockout' ) {

					foreach ( $rounds as $round ) {
						if ( is_array( $round ) ) {
							foreach ( (array) $round as $single_game ) {
								if ( empty( $single_game['score'] ) ) {
									$finished = false;
								}
							}
						}
					}

				} elseif ( strtolower( $tformat ) == 'league' ) {

					foreach ( $rounds as $round ) {
						foreach ( $round as $single_game ) {
							if ( is_array( $single_game ) ) {
								foreach ( $single_game as $team ) {
									if ( is_array( $team ) ) {
										foreach ( $team as $team_score ) {
											if ( ! isset( $team_score['score'] ) ) {
												$finished = false;
											}
										}
									}
								}
							}
						}
					}

				} elseif ( strtolower( $tformat ) == 'rrobin' ) {

					$playoffs_started = get_post_meta( $post_id, 'playoffs_started', true );
					if ( ! isset( $playoffs_started ) or $playoffs_started != '1' ) {
						$finished = false;
					} else {
						foreach ( $rounds['playoffs'] as $round ) {
							if ( is_array( $round ) ) {
								foreach ( (array) $round as $single_game ) {
									if ( empty( $single_game['score'] ) ) {
										$finished = false;
									}
								}
							}
						}
					}

				} else {
					foreach ( $rounds as $round ) {
						foreach ( $round as $single_game ) {
							if ( is_array( $single_game ) ) {
								foreach ( $single_game as $team ) {

									if ( is_array( $team ) && ! isset( $team['match_post_id'] ) ) {
										foreach ( $team as $team_score ) {
											if ( ! isset( $team_score['score'] ) ) {
												$finished = false;
											}
										}
									} else {
										if ( is_array( $team ) ) {
											foreach ( $team as $s_team ) {
												if ( is_array( $s_team ) ) {
													foreach ( $s_team as $stim ) {
														if ( ! isset( $stim['score'] ) ) {
															$finished = false;
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

				}

			} else {
				$finished = false;
			}

			if ( $finished ) { ?>
                <div class="tournament-finished">
					<?php esc_html_e( 'This tournament has ended!', 'arcane' ); ?>
                </div>
			<?php } ?>

            <div class="header-background-image" id="header_background_image"></div>
            <div class="dots"></div>
            <div class="container">
                <div class="thdescriptionw">
                    <div class="thdescriptionleft">
						<?php
						if ( $premium_plugin && $premium_tournament ) { ?>
                            <div class="premium-tag"><i class="fas fa-money-bill-alt"
                                                        aria-hidden="true"></i> <?php esc_html_e( "Premium tournament", "arcane" ); ?>
                            </div>
						<?php } ?>
                        <h1>

							<?php
							foreach ( $games as $game ) {
								if ( $game->title == $game_name ) {
									$imagesc = wp_get_attachment_image_src( $game->icon, "full" );

									if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
										$image = $imagesc;
									} else {
										$image = arcane_aq_resize( $imagesc[0], 100, 135, true, '', true );
									}
								}

							}
							if ( is_array( $image ) ) {
								$image = $image[0];
							}
							if ( strlen( $image ) > 0 ) {
								echo '<img alt="img" src="' . esc_url( $image ) . '" />';
							}

							echo esc_html( get_the_title( $post_id ) );

							?>
                            <span><?php esc_html_e( 'Created by', 'arcane' ); ?> <a
                                        href="<?php echo get_author_posts_url( $author_id ); ?>"><?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?></a></span>
                        </h1>
						<?php

						if ( $finished && ( (int) $author_id == get_current_user_id() ) ) {
							echo '<a class="btn restart_tournament"> <i class="fas fa-sync"></i> ' . esc_html__( "Restart Tournament", "arcane" ) . '</a>';
						}


						if ( ( $started == false ) || ( $started && ( (strtolower( $tformat ) == 'ladder' && $game_stop != 1) || (strtolower( $tformat ) == 'royale' && $game_stop != 1 ))) ) {

							if ( $started == false ) {
								?>
                                <div class="thdregistration">
                                    <p><?php esc_html_e( "Tournament starts in:", "arcane" ); ?></p>
                                    <span><div id="tournament_countdown"></div></span>
                                </div>
								<?php
							}


							if ( arcane_get_my_ID_in_tournament( $post_id ) ) {

								if ( $contestants == "team" ) {
									$pos = get_post( arcane_get_my_ID_in_tournament( $post_id ) );
									if ( arcane_is_admin( $pos->ID, get_current_user_id() ) ) {
										echo '<a class="btn ltournamentb" id="leave_tournament"><i class="fas fa-trophy"></i> ' . esc_html__( "Leave Now", "arcane" ) . ' (' . esc_attr( $pos->post_title ) . ')</a>';
									}
								} else {
									echo '<a class="btn ltournamentb" id="leave_tournament"><i class="fas fa-trophy"></i> ' . esc_html__( "Leave Now", "arcane" ) . '</a>';
								}
							} else {
								//im not in tournament like in any way

								if ( is_user_logged_in() ) {
									$go_ahead = false;

									if ( $premium_plugin && $premium_tournament ) {
										if ( $proceed ) {
											$go_ahead = true;
										} elseif ( $product_id && $user_pay == false ) {
											if ( $pending ) {
												echo '<a class="btn"><i class="fas fa-trophy"></i> ' . esc_attr( $options['premium_pending_button_text'] ) . '</a>';
											} else {
												echo '<a class="btn" href="' . get_permalink( $product_id ) . '"><i class="fas fa-trophy"></i> ' . esc_attr( $options['premium_button_text'] ) . '</a>';
											}
										} elseif ( $product_game_id && $user_pay == false ) {
											if ( $pending ) {
												echo '<a class="btn"><i class="fas fa-trophy"></i> ' . esc_attr( $options['premium_pending_button_text'] ) . '</a>';
											} else {
												echo '<a class="btn" href="' . esc_url( $link_game ) . '"><i class="fas fa-trophy"></i> ' . esc_attr( $options['premium_button_text'] ) . '</a>';
											}
										} elseif ( $general_product_id && $join_any == false && ! $user_pay ) {
											if ( $pending ) {
												echo '<a class="btn"><i class="fas fa-trophy"></i> ' . esc_attr( $options['premium_pending_button_text'] ) . '</a>';
											} else {
												echo '<a class="btn" href="' . esc_url( $link ) . '"><i class="fas fa-trophy"></i> ' . esc_attr( $options['premium_button_text'] ) . '</a>';
											}
										} else {
											$go_ahead = true;
										}

									}

									if ( ! $premium_plugin || $go_ahead || ! $premium_tournament ) {

										if ( $contestants == "team" ) {

											$teams = arcane_get_user_teams( get_current_user_id() );

											$teamarray = [];
											if ( $teams ) {
												$teamarray = $ArcaneWpTeamWars->get_team( array( 'id' => $teams ) );
											}


											$tempteamsarray = [];
											$tempteams      = [];
											if ( is_array( $teamarray ) ) {
												foreach ( $teamarray as $single ) {
													if ( ! isset( $single->games ) ) {
														continue;
													}
													if ( in_array( $gid, $single->games ) ) {
														$tempteamsarray[] = $single;
														$tempteams[]      = $single->ID;
													}
												}
											}
											$teamarray = $tempteamsarray;
											$teams     = $tempteams;


											if ( is_array( $teams ) && ( isset( $teams[0] ) ) ) {

												if ( isset( $delete_competitors ) && is_array( $delete_competitors ) && ( ! empty( $delete_competitors ) ) && ( in_array( $teamarray[0]->ID, $delete_competitors ) ) ) {

												} elseif ( ! empty( $candidate ) && in_array( $teamarray[0]->ID, $candidate ) ) {

													echo '<span class="btn jtournamentb disabled"  data-gid="' . esc_attr( $gid ) . '" data-tid="' . esc_attr( $post_id ) . '" data-toggle="tooltip" data-placement="top" title="' . esc_html__( "Your application is pending request", "arcane" ) . '"> <i class="fas fa-trophy"></i>' . esc_html__( "pending request", "arcane" ) . '</span>';

												} elseif ( ( arcane_return_number_of_team_admin() == 1 ) && ( $gid !== false ) && ( in_array( $gid, $teamarray[0]->games ) ) && $current_number_of_participants < $max_number_of_participants ) {
													echo '<a class="btn jtournamentb" data-gid="' . esc_attr( $gid ) . '" data-tid="' . esc_attr( $post_id ) . '"> <i class="fas fa-trophy"></i>' . esc_html__( "Join Now", "arcane" ) . '</a>';

												} elseif ( ( arcane_return_number_of_team_admin() > 1 ) && ( $gid !== false ) && ( in_array( $gid, $teamarray[0]->games ) ) && $current_number_of_participants < $max_number_of_participants ) {
													echo '<a class="btn jtournamentb team-chooser" data-gid="' . esc_attr( $gid ) . '" href="#TeamChooserModalFooter"> <i class="fas fa-trophy"></i>' . esc_html__( "Join Now", "arcane" ) . '</a>';
												}


											} elseif ( $current_number_of_participants < $max_number_of_participants ) {
												echo '<a class="btn jtournamentb disabled"  data-gid="' . esc_attr( $gid ) . '" data-tid="' . esc_attr( $post_id ) . '" data-toggle="tooltip" data-placement="top" title="' . esc_html__( "To join this tournament you must be an active member of a team that plays this game!", "arcane" ) . '"> <i class="fas fa-trophy"></i>' . esc_html__( "Join Now", "arcane" ) . '</a>';
											}


										} else {

											if ( isset( $delete_competitors ) && is_array( $delete_competitors ) && ( ! empty( $delete_competitors ) ) && ( in_array( get_current_user_id(), $delete_competitors ) ) ) {

											} elseif ( isset( $candidate ) && is_array( $candidate ) && ( ! empty( $candidate ) ) && ( in_array( get_current_user_id(), $candidate ) ) ) {
												echo '<span class="btn jtournamentb disabled" data-gid="' . esc_attr( $gid ) . '" data-tid="' . esc_attr( $post_id ) . '" data-toggle="tooltip" data-placement="top" title="' . esc_html__( "Your application is pending request", "arcane" ) . '"> <i class="fas fa-trophy"></i>' . esc_html__( "pending request", "arcane" ) . '</span>';
											} else {
												if ( $current_number_of_participants < $max_number_of_participants ) {
													echo '<a class="btn jtournamentb" id="join_tournament_single" data-cid="' . esc_attr( get_current_user_id() ) . '"  data-tid="' . esc_attr( $post_id ) . '"> <i class="fas fa-trophy"></i>' . esc_html__( "Join Now", "arcane" ) . '</a>';
												}
											}
										}

									}

								}
							}
							?>

                            <div class="clear"></div>

							<?php if ( current_user_can( 'administrator' ) || $can_edit ) { ?>
                                <a href="<?php echo get_permalink( arcane_get_ID_by_slug( 'tournament-creation' ) ) . '?edit=' . get_the_ID(); ?>"
                                   class="btn edit"> <i
                                            class="fas fa-edit"></i><?php esc_html_e( "Edit Tournament", "arcane" ); ?>
                                </a>
							<?php } ?>
						<?php } ?>
                    </div>
                    <div class="thdescriptionright">
                        <div class="thdtext">
							<?php while ( have_posts() ) : the_post(); ?>
								<?php the_content(); ?>
							<?php endwhile;
							wp_reset_postdata(); // end of the loop. ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>


                <div class="thinfo">
                    <div class="thinfoleft">
                        <h2 id="time_date_anchor">
							<?php if ( $started == false ) {
								esc_html_e( 'Starts', 'arcane' );
							} else {
								esc_html_e( 'Started on', 'arcane' );
							} ?>
                        </h2>
                        <div id="tournament_starts">
                            <?php
                            $date = new DateTime($tournament_starts);
                            echo esc_html( $date->format(get_option( 'date_format' ) . ', ' . get_option( 'time_format' )));
                            ?>
                        </div>
                        <div id="site_zone"><?php echo esc_html( $tournament_timezone ); ?></div>

                    </div>

                    <div class="thinforight">
                        <h2><?php esc_html_e( "About", "arcane" ); ?></h2>
                        <ul>
                            <li class="tbparticipants">
                                <img alt="img"
                                     src="<?php echo esc_url( get_theme_file_uri( 'img/ticons/team.png' ) ); ?>"/>
                                <h3>
									<?php
									echo esc_html( $current_number_of_participants );
									esc_html_e( " out of ", "arcane" );
									echo esc_html( $max_number_of_participants );
									?>
                                </h3>
                                <span><?php esc_html_e( "Participants", "arcane" ); ?></span>
                            </li>
                            <li>
                                <img alt="img"
                                     src="<?php echo esc_url( get_theme_file_uri( 'img/ticons/diagram.png' ) ); ?>"/>
                                <h3>
									<?php
									echo esc_html($tournament_mapping[$tformat]);
									?>
                                </h3>
                                <span><?php esc_html_e( "Tournament type", "arcane" ); ?></span>
                            </li>

                            <li class="tblocation">
                                <img alt="img"
                                     src="<?php echo esc_url( get_theme_file_uri( 'img/ticons/location.png' ) ); ?>"/>
                                <h3>
									<?php echo esc_attr( $tournament_server ); ?>
                                </h3>
                                <span><?php esc_html_e( "Location", "arcane" ); ?></span>
                            </li>

                            <li class="tblocation">
                                <img alt="img"
                                     src="<?php echo esc_url( get_theme_file_uri( 'img/ticons/platform.png' ) ); ?>"/>
                                <h3><?php

									switch ( $tournament_platform ) {
										case "ps" :
											esc_html_e( "PS4", 'arcane' );
											break;
                                        case "ps5" :
                                            esc_html_e( "PS5", 'arcane' );
                                            break;
										case "pc" :
											esc_html_e( "PC", 'arcane' );
											break;
										case "xbox" :
											esc_html_e( "Xbox", 'arcane' );
											break;
										case "wii" :
											esc_html_e( "Wii", 'arcane' );
											break;
										case "nin" :
											esc_html_e( "Nintendo", 'arcane' );
											break;
										case "cross" :
											esc_html_e( "Cross platform", 'arcane' );
											break;
										case "mobile" :
											esc_html_e( "Mobile", 'arcane' );
											break;
									}

									?></h3>
                                <span><?php esc_html_e( "Platform", "arcane" ); ?></span>
                            </li>

                        </ul>
                    </div>
                    <div class="clear"></div>
                </div>

				<?php
				if ( $author_id == get_current_user_id() && ! empty( $candidate ) ) { ?>
                    <div id="buddypress" class="tbteams candidates-wrap">
                        <div class="title-wrapper">
                            <h3 class="widget-title"><i
                                        class="fas fa-users"></i> <?php esc_html_e( "PENDING REQUESTS", "arcane" ); ?>
                            </h3>
                            <div class="clear"></div>
                        </div>

                        <div class="wpb_wrapper" id="members">

                            <ul class="members-list item-list">
								<?php
								$allowed_html =
									array(
										'span' => [
											'class'       => [],
											'data-con'    => [],
											'data-toggle' => [],
											'title'       => [],
										],
										'i'    => [
											'class' => [],
										],
										'div'  => [
											'class' => [],
										]
									);
								$confirmation =
									'<div class="confirmation">
					                    <span class="u_confirm" data-con="accept" data-toggle="tooltip" title="Accept candidate"><i  class="fas fa-check"></i> ' . esc_html__( "Accept", "arcane" ) . '</span>
					                    <span class="u_confirm" data-con="reject" data-toggle="tooltip" title="Reject candidate"><i  class="fas fa-times"></i> ' . esc_html__( "Reject", "arcane" ) . '</span>
					                    </div>';


								if ( $contestants == "team" ) {
									foreach ( $candidate as $team ) {

										$pf_url = get_post_meta( $team, 'team_photo', true );

										if ( ! empty( $pf_url ) ) {
											$imagebg = arcane_aq_resize( $pf_url, 50, 50, true, true, true ); //resize & crop img
											if ( isset ( $imagebg ) ) {
												$pfimage = $imagebg;
											}

										} else {
											$pfimage = esc_url( get_theme_file_uri( 'img/defaults/default-team.jpg' ) );
										}
										//pfimage gotten
										$link = get_permalink( $team );
										$pos  = get_post( $team );
										echo '
                                               <li class="candidate_listing" data-team_id="' . esc_attr( $team ) . '">
                                                <div class="team-list-wrapper">
                                                    <div class="item-avatar">
                                                       <a href="' . esc_url( $link ) . '">
                                                         <img alt="img" src="' . esc_url( $pfimage ) . '" class="avatar">
                                                       </a>
                                                    </div>
                        
                                                    <div class="item">
                                                        <div class="item-title">
                                                            <a href="' . esc_url( $link ) . '">' . esc_attr( $pos->post_title ) . '</a>
                        
                                                            <div class="item-meta">
                                                            <span class="activity">' . arcane_return_number_of_members( $team ) . '&nbsp;' . esc_html__( "Members", "arcane" ) . '</span>
                                                            </div>
                                                            ' . wp_kses( $confirmation, $allowed_html ) . '
                                                        </div>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                        
                                            </li>';
									}
								} else {
									//users tournament
									foreach ( $candidate as $single_user ) {

										$pf_url = get_user_meta( $single_user, 'profile_photo', true );

										if ( ! empty( $pf_url ) ) {
											$imagebg = arcane_aq_resize( $pf_url, 50, 50, true, true, true ); //resize & crop img
											if ( ! isset ( $imagebg[0] ) ) {
												if ( ! isset( $pfpic ) ) {
													$pfpic = '';
												}
												$pfimage = $pfpic;
											} else {
												$pfimage = $imagebg;
											}
											$pfimage = esc_url( $pfimage );
										} else {
											$pfimage = esc_url( get_theme_file_uri( 'img/defaults/default-team.jpg' ) );
										}
										//pfimage gotten
										$link             = bp_core_get_user_domain( $single_user );
										$single_user_data = get_user_by( 'id', $single_user );

										echo '
			                               <li class="candidate_listing" data-team_id="' . esc_attr( $single_user_data->ID ) . '">
			                                <div class="team-list-wrapper">
			                                    <div class="item-avatar">
			                                       <a href="' . esc_url( $link ) . '">
			                                             <img alt="img" src="' . esc_url( $pfimage ) . '" class="avatar">
			                                       </a>
			                                    </div>
			
			                                    <div class="item">
			                                        <div class="item-title">
			                                            <a href="' . esc_url( $link ) . '">' . esc_attr( $single_user_data->display_name ) . '</a>
			
			                                            <div class="item-meta">
			                                                <span class="activity"></span>
			                                            </div>
			                                            ' . wp_kses( $confirmation, $allowed_html ) . '
			                                        </div>
			                                    </div>
			                                    <div class="clear"></div>
			                                </div>
			
			                            </li>';
									}
								}

								?>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
					<?php
				}
				?>


                <div class="clear"></div>

            </div>
            <div class="clear"></div>

        </div>
        <div class="col-lg-12 col-md-12 nav-top-divider"></div>

        <div class="container mtournament-body">


            <div class="tbinfo">
                <h2><?php esc_html_e( "Tournament details", "arcane" ); ?></h2>
                <ul>
                    <li>
                        <h3><?php esc_html_e( "Game", "arcane" ); ?></h3>

						<?php echo '<span>' . esc_html( $game_name ) . '</span>'; ?>

                    </li>
                    <li>
                        <h3><?php esc_html_e( "Contestants", "arcane" ); ?></h3>
                        <span>
						                    <?php ( $contestants == "team" ) ? esc_html_e( "Teams", 'arcane' ) : esc_html_e( "Users", 'arcane' ); ?>
					                    </span>
                    </li>
                    <li>
                        <h3><?php esc_html_e( "Game format", "arcane" ); ?></h3>
                        <span><?php
							if ( $games_format == "bo1" ) {
								esc_html_e( "Best of 1", 'arcane' );
							} elseif ( $games_format == "bo2" ) {
								esc_html_e( "Best of 2", 'arcane' );
							} elseif ( $games_format == "bo3" ) {
								esc_html_e( "Best of 3", 'arcane' );
							} elseif ( $games_format == "bo4" ) {
								esc_html_e( "Best of 4", 'arcane' );
							} elseif ( $games_format == "bo5" ) {
								esc_html_e( "Best of 5", 'arcane' );
							}

							?></span>
                    </li>
					<?php if ( $tformat != ( 'ladder' && 'royale' ) ) { ?>
                        <li>
                            <h3><?php esc_html_e( "Game frequency", "arcane" ); ?></h3>
                            <span>
                                <?php
                                if ( $game_frequency == "5 minutes" ) {
                                    esc_html_e( "Every 5 minutes", 'arcane' );
                                } elseif ( $game_frequency == "15 minutes" ) {
                                    esc_html_e( "Every 15 minutes", 'arcane' );
                                } elseif ( $game_frequency == "30 minutes" ) {
                                    esc_html_e( "Every 30 minutes", 'arcane' );
                                } elseif ( $game_frequency == "60 minutes" ) {
                                    esc_html_e( "Every hour", 'arcane' );
                                } elseif ( $game_frequency == "1" ) {
                                    esc_html_e( "Daily", 'arcane' );
                                } elseif ( $game_frequency == "2" ) {
                                    esc_html_e( "Every 2 days", 'arcane' );
                                } elseif ( $game_frequency == "3" ) {
                                    esc_html_e( "Every 3 days", 'arcane' );
                                } elseif ( $game_frequency == "4" ) {
                                    esc_html_e( "Every 4 days", 'arcane' );
                                } elseif ( $game_frequency == "5" ) {
                                    esc_html_e( "Every 5 days", 'arcane' );
                                } elseif ( $game_frequency == "6" ) {
                                    esc_html_e( "Every 6 days", 'arcane' );
                                } elseif ( $game_frequency == "7" ) {
                                    esc_html_e( "Every 7 days", 'arcane' );
                                } elseif ( $game_frequency == "14" ) {
                                    esc_html_e( "Every two weeks", 'arcane' );
                                } elseif ( $game_frequency == "30" ) {
                                    esc_html_e( "Monthly", 'arcane' );
                                }
                                ?>
                            </span>
                        </li>
					<?php } ?>

                    <li>
                        <h3><?php esc_html_e( "Tournament frequency", "arcane" ); ?></h3>
                        <span>
					                        <?php
					                        if ( $tournament_frequency == "daily" ) {
						                        esc_html_e( "Daily", 'arcane' );
					                        } elseif ( $tournament_frequency == "weekly" ) {
						                        esc_html_e( "Weekly", 'arcane' );
					                        } elseif ( $tournament_frequency == "monthly" ) {
						                        esc_html_e( "Monthly", 'arcane' );
					                        } elseif ( $tournament_frequency == "yearly" ) {
						                        esc_html_e( "Yearly", 'arcane' );
					                        }
					                        ?>
					                    </span>
                    </li>

					<?php if ( isset( $options['game_modes'] ) && ! empty( $options['game_modes'][0] ) ) { ?>
                        <li>
                            <h3><?php esc_html_e( "Game modes", "arcane" ); ?></h3>
                            <span>
						                        <?php echo esc_html( $game_modes ); ?>
						                    </span>
                        </li>
					<?php } ?>

                </ul>
                <div class="clear"></div>
            </div>
			<?php
			$maps_count = count( $tournament_maps );

			if ( $maps_count == 2 ) {
				$additional_class = "two_maps";
			} elseif ( $maps_count == 3 ) {
				$additional_class = "three_maps";
			} elseif ( $maps_count > 3 ) {
				$additional_class = "more_than_three_maps";
			} else {
				$additional_class = "single_map";
			}
			?>
            <div class="tbmaps">
                <h2><?php esc_html_e( "Maps", "arcane" ); ?></h2>
                <ul class="tbmapsi <?php echo esc_attr( $additional_class ); ?>" id="maps_holder">
					<?php
					foreach ( $tournament_maps as $map ) {
						$output_image = arcane_return_map_pic( $map );
						if ( empty( $image ) ) {
							$output_image = esc_url( get_theme_file_uri( 'img/defaults/287x222.jpg' ) );
						}
						?>
                        <li>
                            <h3><?php echo esc_html( arcane_return_map_title( $map ) ); ?></h3>
                            <img alt="img" src="<?php echo esc_url( $output_image ); ?>"/>
                        </li>
						<?php
					}
					?>
                </ul>
                <div class="clear"></div>
            </div>

			<?php
			if ( ! empty( $tournament_competitors ) ) { ?>
                <div id="buddypress" class="tbteams">
                    <h2><?php esc_html_e( "Competitors", "arcane" ); ?> </h2>
                    <div class="wpb_wrapper" id="members">
                        <ul class="members-list item-list competitor-list">
							<?php
							$allowed_html =
								array(
									'span' => array(
										'class'       => [],
										'data-con'    => [],
										'data-toggle' => [],
										'title'       => [],
									),
									'i'    => array(
										'class' => [],
									),
									'div'  => array(
										'class' => [],
									)
								);

							$kick_user = '';
							if ( $author_id == get_current_user_id() ) {
								$kick_user = '
                                                            <div class="edit_meta">
                                                            <span class="u_kick" data-con="kick"><i class="fas fa-times"></i></span>
                                                            </div>';
							}


							if ( $contestants == "team" ) {
								foreach ( $tournament_competitors as $team ) {

									$pf_url = get_post_meta( $team, 'team_photo', true );

									if ( ! empty( $pf_url ) ) {
										$imagebg = arcane_aq_resize( $pf_url, 50, 50, true, true, true ); //resize & crop img
										if ( isset ( $imagebg ) ) {
											$pfimage = $imagebg;
										}

									} else {
										$pfimage = esc_url( get_theme_file_uri( 'img/defaults/default-team.jpg' ) );
									}
									//pfimage gotten
									$link = get_permalink( $team );
									$pos  = get_post( $team );
									echo '
                                                           <li class="competitor_listing" data-team_id="' . esc_attr( $team ) . '">
                                                            <div class="team-list-wrapper">
                                                                <div class="item-avatar">
                                                                   <a href="' . esc_url( $link ) . '">
                                                                        <img alt="img" src="' . esc_url( $pfimage ) . '" class="avatar">
                                                                   </a>
                                                                </div>
                                    
                                                                <div class="item">
                                                                    <div class="item-title">
                                                                        <a href="' . esc_url( $link ) . '">' . esc_attr( $pos->post_title ) . '</a>
                                    
                                                                        <div class="item-meta">
                                                                            <span class="activity">' . arcane_return_number_of_members( $team ) . '&nbsp;Members</span>
                                                                        </div>' . wp_kses( $kick_user, $allowed_html ) . '
                                                                    </div>
                                                                </div>
                                                                <div class="clear"></div>
                                                            </div>
                                                        </li>';
								}
							} else {
								//users tournament
								foreach ( $tournament_competitors as $user ) {

									$pf_url = get_user_meta( $user, 'profile_photo', true );

									if ( ! empty( $pf_url ) ) {
										$imagebg = arcane_aq_resize( $pf_url, 50, 50, true, true, true ); //resize & crop img
										if ( ! isset ( $imagebg[0] ) ) {
											if ( ! isset( $pfpic ) ) {
												$pfpic = '';
											}
											$pfimage = $pfpic;
										} else {
											$pfimage = $imagebg;
										}
										$pfimage = esc_url( $pfimage );
									} else {
										$pfimage = esc_url( get_theme_file_uri( 'img/defaults/default-team.jpg' ) );
									}
									//pfimage gotten
									$link = bp_core_get_user_domain( $user );
									$uid  = $user;
									$user = get_user_by( 'id', $user );

									echo '
                                                           <li class="competitor_listing" data-team_id="' . esc_attr( $uid ) . '">
                                                            <div class="team-list-wrapper">
                                                                <div class="item-avatar">
                                                                   <a href="' . esc_url( $link ) . '">
                                                                        <img alt="img" src="' . esc_url( $pfimage ) . '" class="avatar">
                                                                   </a>
                                                                </div>
                            
                                                                <div class="item">
                                                                    <div class="item-title">
                                                                        <a href="' . esc_url( $link ) . '">' . esc_attr( $user->display_name ) . '</a>
                            
                                                                        <div class="item-meta">
                                                                            <span class="activity"></span>
                                                                        </div>' . wp_kses( $kick_user, $allowed_html ) . '
                                                                    </div>
                                                                </div>
                                                                <div class="clear"></div>
                                                            </div>
                            
                                                        </li>';
								}
							}

							?>
                        </ul>
                        <div class="clear"></div>
                    </div>
                </div>
			<?php } ?>
			<?php


			if ( ! empty( $tournament_regulations ) ) { ?>

                <div class="tbregulations news_tabbed">

                    <h2><?php esc_html_e( "Regulations", "arcane" ); ?></h2>
					<?php echo wp_kses_post( $tournament_regulations ); ?>
                </div>
				<?php
			}
			?>

            <!--close acordion -->

			<?php
			if ( ! empty( $prizes ) ) { ?>
                <div class="tbprice">

	                <?php
	                $winner = '';
	                $second = '';
	                $third = '';

	                if($finished) {

		                if($tformat == 'league' || $tformat == 'ladder'){ ?>

                            <script>
                                let counter = 1;
                                setTimeout(function(){
                                    jQuery('.tbbrakets table tr').each(function() {
                                        if(counter === 2){
                                            jQuery('.prizes_wrapper .tfirstw span').append(jQuery(this).find("td:nth-child(2)").text());
                                        }

                                        if(counter === 3){
                                            jQuery('.prizes_wrapper .tsecondw span').append(jQuery(this).find("td:nth-child(2)").text());
                                        }

                                        if(counter === 4){
                                            jQuery('.prizes_wrapper .tthirdw span').append(jQuery(this).find("td:nth-child(2)").text());
                                        }

                                        counter++;
                                    });
                                }, 500);

                            </script>

			                <?php

		                }else{
			                $index = count( $rounds ) - 3;
			                $final_score = $rounds[ $index ][0]['score'];
			                $final_score = explode( ":", $final_score );

			                $gamesz_third = get_post_meta($post_id, 'game_cache_third', true);
			                $final_score_third1 = $gamesz_third['teams'][0]['score'];
			                $final_score_third2 = $gamesz_third['teams'][1]['score'];

			                if ( $final_score_third1 > $final_score_third2 ) {
				                $third =$gamesz_third['teams'][0]['name'];
			                }else{
				                $third =$gamesz_third['teams'][1]['name'];
			                }


			                if ( $final_score[0] > $final_score[1] ) {

				                $winner = $rounds[ $index ][0]['teams'][0]['name'];
				                $second = $rounds[ $index ][0]['teams'][1]['name'];
			                } else {
				                $winner = $rounds[ $index ][0]['teams'][1]['name'];
				                $second = $rounds[ $index ][0]['teams'][0]['name'];
			                }

		                }
	                }
	                ?>

                    <h2><?php esc_html_e( "Winner Prizes", "arcane" ); ?></h2>
                    <div class="prizes_wrapper">
                        <table id="prizes_table">
                            <tbody>
                            <tr>
                                <th>
                                    <img alt="img"
                                         src="<?php echo esc_url( get_theme_file_uri( 'img/ticons/laurel.png' ) ); ?>"/>
                                    <span>
                                    <?php esc_html_e( "Place", "arcane" ); ?>
                            </span>
                                </th>
                                <th>
                                    <img alt="img"
                                         src="<?php echo esc_url( get_theme_file_uri( 'img/ticons/cup.png' ) ); ?>"/>
                                    <span>
                                <?php esc_html_e( "Reward", "arcane" ); ?>
                            </span>
                                </th>
                            </tr>
							<?php


							$counter = 0;
							foreach ( $prizes as $prize ) {
								$counter ++;
								switch ( $counter ) {
									case 1:
										$class = "tfirstw";
										$image = esc_url( get_theme_file_uri( 'img/ticons/1st.png' ) );
										$win = $winner;
										break;
									case 2:
										$class = "tsecondw";
										$image = esc_url( get_theme_file_uri( 'img/ticons/2nd.png' ) );
										$win = $second;
										break;
									case 3:
										$class = "tthirdw";
										$image = esc_url( get_theme_file_uri( 'img/ticons/3rd.png' ) );
										$win = $third;
										break;
									default:
										$class = "";
										$image = "";
										$win = '';
										break;
								}
								echo '<tr>
                                    <td class="' . esc_attr( $class ) . '">
                                        <span>';
                                        if ( strlen( $image ) > 1 ) {
                                            echo '<img alt="img" src="' . esc_url( $image ) . '" />';
                                        }
                                        echo '
                                        ' . esc_attr( arcane_ordinal( $counter ) ) . ' ' . esc_html_e( "place", "arcane" ) . ':  <strong>'.esc_html($win).'</strong>
                                        </span>
                                    </td>
                                    <td class="trcell">
                                            <span class="tournament-table_prize">' . esc_attr( $prize ) . '</span>
                                    </td>
                                </tr>';

							}
							?>
                            </tbody>
                        </table>
                    </div>
                </div>
				<?php
			}

			$tournament = new Arcane_Tournaments();
			$tournament->LoadTournament( $post_id );

			if ( $started ) {
				if ( isset( $tournament->tournament ) && !empty( $tournament->tournament ) ) {
					$tournament->tournament->OnTournamentStart( $post_id );
					arcane_tournament_notify_start_all( $post_id );
				}
			}

			if ( ( $started == true ) && ( isset( $tournament->tournament ) && ! empty( $tournament->tournament ) ) ) { ?>

                <h2><?php esc_html_e( "Tournament brackets", "arcane" ); ?></h2>
				<?php $arcane_t_data = $tournament->tournament->PrintStatus( $post_id );
				$arcane_t_data       = str_replace( '&gt;', '>', $arcane_t_data );
				$arcane_t_data       = str_replace( '&lt;', '<', $arcane_t_data );
				$arcane_t_data       = str_replace( '#smallerthan#', '<', $arcane_t_data );

				echo wp_kses( $arcane_t_data,
					[
						'div'   => [
							'class' => [],
							'id'    => [],
							'role'  => [],

						],
						'a'     => [
							'href'          => [],
							'class'         => [],
							'target'        => [],
							'aria-controls' => [],
							'role'          => [],
							'data-toggle'   => [],
							'data-id'       => [],
                            'data-round'    => [],
						],
						'table' => [
							'cellpadding' => [],
							'cellspacing' => [],
                            'class'       => [],
                            'data-tid'    => [],
						],
						'tbody' => [],
						'tr'    => [
							'class' => [],
						],
						'td'    => [
							'class' => [],
							'data-tid' => [],
							'data-uid' => [],
							'data-round' => [],

						],
						'th'    => [],
						'h3'    => [
							'class' => [],
						],
						'h2'    => [
							'class' => [],
						],

						'ul'     => [
							'class' => [],
						],
						'script' => [],
						'img'    => [
							'src' => [],
						],
						'li'     => [
							'class' => [],
							'role'  => [],
						],
					]
				);


			}
			?>
        </div>
    </div>

<?php get_footer(); ?>