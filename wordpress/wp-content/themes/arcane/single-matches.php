<?php get_header(); ?>
<?php
global $ArcaneWpTeamWars;

$team = '';

$match_id = $post->ID;

$arcane_match = $matches = $ArcaneWpTeamWars->get_match( array( 'id' => $match_id, 'sum_tickets' => true ) );

if ( isset( $arcane_match->tournament_id ) ) {
	$tid = $arcane_match->tournament_id;

	$tournament_timezone = get_post_meta( $tid, 'tournament_timezone', true );
	if ( ! $tournament_timezone ) {
		$timezone_string     = arcane_timezone_string();
		$tournament_timezone = $timezone_string ? $timezone_string : 'UTC';
	}
	date_default_timezone_set( $tournament_timezone );
} else {
	$tid = 0;
}
if ( $tid > 0 ) {
	$tournament_game = true;
} else {
	$tournament_game = false;
}
$tparticipants = $arcane_match->tournament_participants;

if ( $tparticipants == 'team' ) {
	$is_user_type = false;
} elseif ( $tparticipants === null or empty( $tparticipants ) ) {
	$is_user_type = false;
} else {
	$is_user_type = true;
}

$m = $arcane_match;

if ( $tid > 0 ) {
	$obj_merged = (object) array_merge( (array) get_post( $m->ID ), (array) arcane_get_meta( $m->ID ) );
	$tickets    = $ArcaneWpTeamWars->SumTickets( $obj_merged->ID );
	$t1         = $tickets[0];
	$t2         = $tickets[1];
} else {

	$t1 = $m->team1_tickets;
	$t2 = $m->team2_tickets;

}


//$team2 = $match->team2;
$match_status    = $arcane_match->status;
$locked          = $arcane_match->locked;
$cid             = get_current_user_id();
$user_type_class = '';

if ( $is_user_type == false ) {

	$team1 = $ArcaneWpTeamWars->get_team( array( 'id' => $arcane_match->team1 ) );
	$team2 = $ArcaneWpTeamWars->get_team( array( 'id' => $arcane_match->team2 ) );

	if ( isset( $team1->ID ) && arcane_is_admin( $team1->ID, get_current_user_id() ) ) {
		$team = 'team1';
	} elseif ( isset( $team2->ID ) && arcane_is_admin( $team2->ID, get_current_user_id() ) ) {
		$team = 'team2';
	}

} else {
	$user_type_class = 'user_type';

	$team1     = new stdClass;
	$team1->ID = $arcane_match->team1;
	$team2     = new stdClass;
	$team2->ID = $arcane_match->team2;

	if ( $team1->ID == $cid ) {
		$team = 'team1';
	} elseif ( $team2->ID == $cid ) {
		$team = 'team2';
	}
}


$admin = false;
if ( $is_user_type == false ) {
	if ( ( isset( $team1->ID ) && arcane_is_admin( $team1->ID, get_current_user_id() ) ) || ( isset( $team2->ID ) && arcane_is_admin( $team2->ID, get_current_user_id() ) ) ) {
		$admin = true;
	}
} else {
	if ( is_super_admin( $cid ) || ( $team1->ID == $cid ) || ( $team2->ID == $cid ) ) {
		$admin = true;
	}
}


$options = arcane_get_theme_options();

if ( arcane_return_game_banner( $arcane_match->game_id ) ) {
	$bck_img = arcane_return_game_banner( $arcane_match->game_id );
} else {
	$bck_img = $options['match_header_bg']['url'];
}
?>
    <input type="hidden" id="game_id" value="<?php echo esc_attr( $arcane_match->game_id ); ?>">

    <div class="match-header <?php echo esc_attr( $user_type_class ); ?>">
        <h6 data-nick="<?php the_title_attribute(); ?>"><?php the_title(); ?></h6>
        <img alt="<?php esc_attr_e( 'match_bck', 'arcane' ); ?>" src="<?php echo esc_url( $bck_img ); ?>"/>

        <div class="match-extras">
			<?php
			//remove delete button for tournaments except admins
			if ( current_user_can( 'manage_options' ) || ! isset( $arcane_match->tournament_id ) ) {
				if ( $is_user_type == false ) {
					if ( $match_status != 'done' && isset( $admin ) && $admin ) {
						if ( $locked != '1' ) {
							if ( $match_status == 'pending' && isset( $admin ) && $admin && ( get_the_author_meta( 'ID' ) == arcane_get_author( $team1->ID ) || get_the_author_meta( 'ID' ) == arcane_get_author( $team2->ID ) ) ) {
								echo '<a data-mid="' . esc_attr( $post->ID ) . '" href="javascript:void(0);" class="ajaxdeletematch_single"><i data-original-title="' . esc_html__( "Delete Match", "arcane" ) . '" data-placement="right" data-toggle="tooltip" class="fas fa-times"></i></a>';
							} elseif ( ( $match_status == 'active' || $match_status == 'rejected_challenge' || $match_status == 'edited1' || $match_status == 'edited2' || $match_status == 'submitted1' || $match_status == 'submitted2' ) && ( get_the_author_meta( 'ID' ) == arcane_get_author( $team1->ID ) || get_the_author_meta( 'ID' ) == arcane_get_author( $team2->ID ) ) ) {
								echo '<a  data-mid="' . esc_attr( $post->ID ) . '" href="javascript:void(0);" class="ajaxdeletematch_single"><i data-original-title="' . esc_html__( "Delete Match", "arcane" ) . '" data-placement="right" data-toggle="tooltip" class="fas fa-times"></i></a>';
							}
						}
					}
				} else {
					if ( $match_status != 'done' && isset( $admin ) && $admin ) {
						if ( $locked != '1' ) {
							if ( $match_status == 'pending' && isset( $admin ) && ( $team1->ID == $cid ) || ( $team2->ID == $cid ) ) {
								echo '<a   data-mid="' . esc_attr( $post->ID ) . '"  href="javascript:void(0);" class="ajaxdeletematch_single"><i data-original-title="' . esc_html__( "Delete Match", "arcane" ) . '" data-placement="right" data-toggle="tooltip" class="fas fa-times"></i></a>';
							} elseif ( ( $match_status == 'active' or $match_status == 'rejected_challenge' || $match_status == 'edited1' || $match_status == 'edited2' || $match_status == 'submitted1' || $match_status == 'submitted2' ) && ( $team1->ID == $cid ) || ( $team2->ID == $cid ) ) {
								echo '<a  data-mid="' . esc_attr( $post->ID ) . '" href="javascript:void(0);" class="ajaxdeletematch_single"><i data-original-title="' . esc_html__( "Delete Match", "arcane" ) . '" data-placement="right" data-toggle="tooltip" class="fas fa-times"></i></a>';
							}
						}
					}
				}

			}

			?>


            <!-- modal report-->
			<?php
			if ( isset( $team ) && ( $team == 'team2' || $team == 'team1' ) ) { ?>
				<?php if ( $locked != '1' ) { ?>
                    <a href="#myModalLReport" class="flag-match"><i
                                data-original-title="<?php esc_html_e( "Flag match", "arcane" ); ?>"
                                data-placement="right" data-toggle="tooltip" class="fas fa-flag"></i></a>
				<?php }

				if ( ! empty( $_POST ) && isset( $_POST['report_score'] ) ) {

					if ( $is_user_type == false ) {

						if ( arcane_is_admin( $team1->ID, get_current_user_id() ) ) {
							$status = 'reported1';
						} elseif ( arcane_is_admin( $team2->ID, get_current_user_id() ) ) {
							$status = 'reported2';
						}
					} else {

						if ( $team1->ID == $cid ) {
							$status = 'reported1';
						} elseif ( $team2->ID == $cid ) {
							$status = 'reported2';
						}
					}
					$arr = array(
						'reported_reason' => isset( $_POST['reason'] ) ? $_POST['reason'] : '',
						'status'          => $status
					);
					$ArcaneWpTeamWars->update_match( $post->ID, $arr );
					$admin_email = get_option( 'admin_email' );
					$subject     = esc_html__( 'Match has been reported', 'arcane' );
					$url         = admin_url( 'admin.php?page=wp-teamwars-matches&act=edit&id=' ) . $post->ID;
					$message     = esc_html__( 'Match ', 'arcane' ) . '"' . get_the_title( $post->ID ) . '"' . esc_html__( ' has been reported. Please visit this link: ', 'arcane' ) . $url . esc_html__( ' to resolve the case.', 'arcane' );
					$headers     = 'From: ' . get_bloginfo() . ' <' . get_option( "admin_email" ) . '>' . "\r\n" . 'Reply-To: ' . $admin_email;

					if ( class_exists( 'Arcane_Types' ) ) {
						$arcane_types = new Arcane_Types();
						$arcane_types::arcane_send_email( $admin_email, $subject, $message, $headers );
					}

				}
				?>

			<?php } ?>
            <!-- /modal report-->

			<?php
			if ( $admin ) {
				if ( $locked != '1' && ( get_the_author_meta( 'ID' ) == arcane_get_author( $team1->ID ) || get_the_author_meta( 'ID' ) == arcane_get_author( $team2->ID ) ) && ( $match_status == 'done' || $match_status == 'pending' ) ) {
					echo '<a href="' . esc_url( get_permalink( get_page_by_path( 'team-challenge' ) ) ) . '?mid=' . $post->ID . '"><i data-original-title="' . esc_html__( 'Edit Match', 'arcane' ) . '" data-placement="right" data-toggle="tooltip" class="fas fa-cog"></i></a>';
				}
			}
			?>

        </div>

		<?php
		$timezone_string = arcane_timezone_string();
		$timezone        = $timezone_string ? $timezone_string : 'UTC';
		$time_now        = new DateTime( "now", new DateTimeZone( $timezone ) );
		?>

		<?php if ( $match_status == 'active' && ( (int) $m->date_unix < $time_now->getTimestamp() ) && ( isset( $team ) && ( $team == 'team2' || $team == 'team1' ) ) ) { ?>
			<?php if ( $locked != '1' ) { ?>

                <a href="#myModalLSubmit" role="button" class="btn submit-score"><i class="fas fa-share-square"></i>&nbsp;<?php esc_html_e( "Submit scores", 'arcane' ); ?>
                </a>

			<?php } ?>
		<?php } ?>

        <div class="container">


            <!--TEAM 1-->
            <div class="col-4">
				<?php if ( $is_user_type == false ) { ?>

					<?php if ( $team1->ID != '0' ) { ?>
                        <a href="<?php echo esc_url( get_post_permalink( $team1->ID ) ); ?>">
					<?php } ?>

					<?php if ( empty( $team1_img ) ) {
						$team1_img = get_theme_file_uri( 'img/defaults/defteam.png' );
					} ?>
                    <img alt="<?php esc_attr_e( 'team1_img', 'arcane' ); ?>"
                         src="<?php echo esc_url( arcane_return_team_image_big( $m->team1 ) ); ?>"/>
                    <h3 class="team1-title"><?php echo get_the_title( $team1->ID ); ?></h3>

					<?php if ( $team1->ID != '0' ) { ?>
                        </a>
					<?php } ?>

					<?php arcane_team_members_match_page( $team1->ID ); ?>


				<?php } else {

					//USER TYPE MATCH, ECHO USER DATA
					$user1    = get_user_by( 'id', $team1->ID );
					$pfimage1 = get_user_meta( $team1->ID, 'profile_photo', true );
					if ( empty( $pfimage1 ) ) {
						$pfimage1 = get_theme_file_uri( 'img/defaults/default-team.jpg' );
					} ?>

					<?php if ( $team1->ID != '0' ) { ?>
                        <a href="<?php echo esc_url( bp_core_get_user_domain( $team1->ID ) ); ?>">
					<?php } ?>

                    <img alt="img" src="<?php echo esc_url( $pfimage1 ); ?>"/>
                    <h3 class="team1-title"><?php echo esc_attr( $user1->display_name ); ?></h3>

					<?php if ( $team1->ID != '0' ) { ?>
                        </a>
					<?php } ?>


				<?php } ?>
            </div>


            <div class="col-4 ">

				<?php $game_img = arcane_return_game_image_nocrop( $arcane_match->game_id ); ?>
				<?php if ( isset( $game_img ) && ! empty( $game_img ) ) { ?>
                    <img alt="<?php esc_attr_e( 'game_img', 'arcane' ); ?>" src="<?php echo esc_url( $game_img ); ?>"/>
				<?php } ?>

				<?php
				if ( isset( $arcane_match->tournament_id ) ) { ?>
                    <h1>
                        <a href="<?php echo get_the_permalink( $arcane_match->tournament_id ); ?>"><?php the_title(); ?></a>
                    </h1>
				<?php } else { ?>
                    <h1><?php the_title(); ?></h1>
				<?php } ?>
                <div class="mscore"><?php echo esc_html( $t1 ); ?>
                    <span><?php esc_html_e( 'vs', 'arcane' ); ?></span> <?php echo esc_html( $t2 ); ?></div>
                <span class="mtime"> <?php arcane_echo_match_date( $m->date_unix ); ?></span>
            </div>

            <!--TEAM 2-->
            <div class="col-4">
				<?php if ( $is_user_type == false ) { ?>

					<?php if ( $team2->ID != '0' ) { ?>
                        <a href="<?php echo esc_url( get_post_permalink( $team2->ID ) ); ?>">
					<?php } ?>

					<?php if ( empty( $team2_img ) ) {
						$team2_img = get_theme_file_uri( 'img/defaults/defteam.png' );
					} ?>
                    <img alt="<?php esc_attr_e( 'team2_img', 'arcane' ); ?>"
                         src="<?php echo esc_url( arcane_return_team_image_big( $m->team2 ) ); ?>"/>
                    <h3 class="team2-title"><?php echo get_the_title( $team2->ID ); ?></h3>

					<?php if ( $team2->ID != '0' ) { ?>
                        </a>
					<?php } ?>

					<?php arcane_team_members_match_page( $team2->ID ); ?>


				<?php } else {

					//USER TYPE MATCH, ECHO USER DATA
					$user2    = get_user_by( 'id', $team2->ID );
					$pfimage2 = get_user_meta( $team2->ID, 'profile_photo', true );
					if ( empty( $pfimage2 ) ) {
						$pfimage2 = get_theme_file_uri( 'img/defaults/default-team.jpg' );
					} ?>

					<?php if ( $team2->ID != '0' ) { ?>
                        <a href="<?php echo esc_url( bp_core_get_user_domain( $team2->ID ) ); ?>">
					<?php } ?>

                    <img alt="img" src="<?php echo esc_url( $pfimage2 ); ?>"/>
                    <h3 class="team2-title"><?php echo esc_attr( $user2->display_name ); ?></h3>

					<?php if ( $team2->ID != '0' ) { ?>
                        </a>
					<?php } ?>


				<?php } ?>
            </div>


			<?php

			if ( $is_user_type == false ) {
				if ( isset( $team ) && $team == 'team1' ) {
					$name = get_the_title( $team2->ID );
					$link = $team2->ID;
				} elseif ( isset( $team ) && $team == 'team2' ) {
					$name = get_the_title( $team1->ID );
					$link = $team1->ID;
				}
			} else {
				if ( ! isset( $team ) ) {
					$team = "";
				}
				if ( $team == 'team1' ) {
					$name = $user2->display_name;
					$link = $team2->ID;
				} elseif ( $team == 'team2' ) {
					$name = $user1->display_name;
					$link = $team1->ID;
				}
			}
			?>
			<?php if ( $is_user_type == false ) { ?>

				<?php if ( ( $match_status == 'submitted1' && $team == 'team2' ) || ( $match_status == 'submitted2' && $team == 'team1' ) ) { ?>

                    <span id="score_fin" class="mcscalert col-12 ">
					        <?php esc_html_e( 'Score has been submitted by', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( get_post_permalink( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a> - <span><?php esc_html_e( 'Accept the score?', 'arcane' ); ?></span>
                            <a class="ajaxsubmitscore" href="javascript:void(0);" data-req="accept_score"
                               data-mid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-check"></i></a>
                            <a class="ajaxsubmitscore" href="javascript:void(0);" data-req="reject_score"
                               data-mid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-times"></i></a>
                        </span>
				<?php } ?>

				<?php if ( ( $match_status == 'submitted1' && $team == 'team1' ) || ( $match_status == 'submitted2' && $team == 'team2' ) ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					<?php if ( $team == 'team1' ) {
						$name1 = $team2->title;
						$link1 = $team2->ID;
						esc_html_e( 'Your score has been submitted! Waiting for', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( get_post_permalink( $link1 ) ); ?>"><?php echo esc_attr( $name1 ); ?> </a>&nbsp;<?php esc_html_e( 'to accept or reject.', 'arcane' ); ?></span>
					<?php } elseif ( $team == 'team2' ) {
						$name2 = $team1->title;
						$link2 = $team1->ID;
						esc_html_e( 'Your score has been submitted! Waiting for', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( get_post_permalink( $link2 ) ); ?>"><?php echo esc_attr( $name2 ); ?></a>&nbsp;<?php esc_html_e( 'to accept or reject.', 'arcane' ); ?></span>
					<?php } ?>

                    </span>
				<?php } ?>


				<?php if ( ( $match_status == 'deleted_request_team1' && $team == 'team2' ) || ( $match_status == 'deleted_request_team2' && $team == 'team1' ) ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Delete match request has been submitted by', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( get_post_permalink( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a> - <span><?php esc_html_e( 'Accept request?', 'arcane' ); ?></span>
                            <a class="ajaxdeletematchconfirmation" href="javascript:void(0);"
                               data-req="accept_delete_request" data-mid="<?php echo esc_attr( $post->ID ); ?>"><i
                                        class="fas fa-check"></i></a>
                            <a class="ajaxdeletematchconfirmation" href="javascript:void(0);"
                               data-req="reject_delete_request" data-mid="<?php echo esc_attr( $post->ID ); ?>"><i
                                        class="fas fa-times"></i></a>
                        </span>
				<?php } ?>

				<?php if ( ( $match_status == 'deleted_request_team1' && $team == 'team1' ) || ( $match_status == 'deleted_request_team2' && $team == 'team2' ) ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					<?php if ( $team == 'team1' ) {
						$name1 = $team2->title;
						$link1 = $team2->ID;
						esc_html_e( 'Your request to delete this match is currently pending! Waiting for', 'arcane' ); ?>&nbsp;
                        <a href="<?php echo esc_url( get_post_permalink( $link1 ) ); ?>"> <?php echo esc_attr( $name1 ); ?> </a>&nbsp;<?php esc_html_e( 'to accept or reject.', 'arcane' ); ?></span>
					<?php } elseif ( $team == 'team2' ) {
						$name2 = $team1->title;
						$link2 = $team1->ID;
						esc_html_e( 'Your request to delete this match is currently pending! Waiting for', 'arcane' ); ?>&nbsp;
                        <a href="<?php echo esc_url( get_post_permalink( $link2 ) ); ?>"> <?php echo esc_attr( $name2 ); ?> </a>&nbsp;<?php esc_html_e( 'to accept or reject.', 'arcane' ); ?></span>
					<?php } ?>

                    </span>
				<?php } ?>

				<?php if ( $match_status == 'pending' && $team == 'team2' ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Team', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( get_post_permalink( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a>&nbsp;<?php esc_html_e( 'has challenged you to a match! - Accept the challenge?', 'arcane' ); ?>
                            <a class="ajaxloadchlsingle" href="javascript:void(0);" data-req="accept_challenge"
                               data-cid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-check"></i></a>
                            <a class="ajaxloadchlsingle" href="javascript:void(0);" data-req="reject_challenge"
                               data-cid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-times"></i></a>
                        </span>
				<?php } ?>


				<?php if ( $match_status == 'edited2' && $team == 'team1' ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Team', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( bp_core_get_user_domain( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a>&nbsp;<?php esc_html_e( 'has edited a match! - Accept the changes?', 'arcane' ); ?>
                            <a class="ajaxloadeditsingle" href="javascript:void(0);" data-req="accept_edit"
                               data-cid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-check"></i></a>
                            <a class="ajaxloadeditsingle" href="javascript:void(0);" data-req="reject_edit"
                               data-cid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-times"></i></a>
                        </span>
				<?php } ?>


				<?php if ( $match_status == 'pending' && $team == 'team1' ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Your request to challenge', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( get_post_permalink( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a>&nbsp;<?php esc_html_e( 'team is currently pending!', 'arcane' ); ?>
                        </span>
				<?php } ?>


				<?php if ( $match_status == 'edited2' && $team == 'team2' ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Your request to edit this match is currently pending!', 'arcane' ); ?>
                        </span>
				<?php } ?>

				<?php if ( $match_status == 'edited1' && $team == 'team2' ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Team', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( bp_core_get_user_domain( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a>&nbsp;<?php esc_html_e( 'has edited a match! - Accept the changes?', 'arcane' ); ?>
                            <a class="ajaxloadeditsingle" href="javascript:void(0);" data-req="accept_edit"
                               data-cid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-check"></i></a>
                            <a class="ajaxloadeditsingle" href="javascript:void(0);" data-req="reject_edit"
                               data-cid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-times"></i></a>
                        </span>
				<?php } ?>


				<?php if ( $match_status == 'edited1' && $team == 'team1' ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Your request to edit this match is currently pending!', 'arcane' ); ?>
                        </span>
				<?php } ?>

			<?php } else {
				//usertype tourney, let's go
				?>
				<?php if ( ( $match_status == 'deleted_request_team1' && $team == 'team2' ) || ( $match_status == 'deleted_request_team2' && $team == 'team1' ) ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Delete match request has been submitted by', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( get_post_permalink( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a> - <span><?php esc_html_e( 'Accept request?', 'arcane' ); ?></span>
                            <a class="ajaxdeletematchconfirmation" href="javascript:void(0);"
                               data-req="accept_delete_request" data-mid="<?php echo esc_attr( $post->ID ); ?>"><i
                                        class="fas fa-check"></i></a>
                            <a class="ajaxdeletematchconfirmation" href="javascript:void(0);"
                               data-req="reject_delete_request" data-mid="<?php echo esc_attr( $post->ID ); ?>"><i
                                        class="fas fa-times"></i></a>
                        </span>
				<?php } ?>

				<?php if ( ( $match_status == 'deleted_request_team1' && $team == 'team1' ) || ( $match_status == 'deleted_request_team2' && $team == 'team2' ) ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					<?php if ( $team == 'team1' ) {
						$name1 = $team2->title;
						$link1 = $team2->ID;
						esc_html_e( 'Your request to delete this match is currently pending! Waiting for', 'arcane' ); ?>&nbsp;
                        <a href="<?php echo esc_url( get_post_permalink( $link1 ) ); ?>"> <?php echo esc_attr( $name1 ); ?> </a>&nbsp;<?php esc_html_e( 'to accept or reject.', 'arcane' ); ?></span>
					<?php } elseif ( $team == 'team2' ) {
						$name2 = $team1->title;
						$link2 = $team1->ID;
						esc_html_e( 'Your request to delete this match is currently pending! Waiting for', 'arcane' ); ?>&nbsp;
                        <a href="<?php echo esc_url( get_post_permalink( $link2 ) ); ?>"> <?php echo esc_attr( $name2 ); ?> </a>&nbsp;<?php esc_html_e( 'to accept or reject.', 'arcane' ); ?></span>
					<?php } ?>

                    </span>
				<?php } ?>


				<?php if ( ( $match_status == 'submitted1' && $team == 'team2' ) || ( $match_status == 'submitted2' && $team == 'team1' ) ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Score has been submitted by', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( bp_core_get_user_domain( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a> - <span><?php esc_html_e( 'Accept the score?', 'arcane' ); ?></span>
                            <a class="ajaxsubmitscore" href="javascript:void(0);" data-req="accept_score"
                               data-mid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-check"></i></a>
                            <a class="ajaxsubmitscore" href="javascript:void(0);" data-req="reject_score"
                               data-mid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-times"></i></a>
                        </span>
				<?php } ?>

				<?php if ( ( $match_status == 'submitted1' && $team == 'team1' ) || ( $match_status == 'submitted2' && $team == 'team2' ) ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					<?php if ( $team == 'team1' ) {
						$name1 = $user2->display_name;
						$link1 = $team2->ID;
						esc_html_e( 'Your score has been submitted! Waiting for', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( bp_core_get_user_domain( $link1 ) ); ?>"> <?php echo esc_attr( $name1 ); ?> </a>&nbsp;<?php esc_html_e( 'to accept or reject.', 'arcane' ); ?></span>
					<?php } elseif ( $team == 'team2' ) {
						$name2 = $user1->display_name;
						$link2 = $team1->ID;
						esc_html_e( 'Your score has been submitted! Waiting for', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( bp_core_get_user_domain( $link2 ) ); ?>"> <?php echo esc_attr( $name2 ); ?> </a>&nbsp;<?php esc_html_e( 'to accept or reject.', 'arcane' ); ?></span>
					<?php } ?>

                    </span>
				<?php } ?>

				<?php if ( $match_status == 'pending' && $team == 'team2' ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Team', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( bp_core_get_user_domain( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a>&nbsp;<?php esc_html_e( 'has challenged you to a match! - Accept the challenge?', 'arcane' ); ?>
                            <a class="ajaxloadchlsingle" href="javascript:void(0);" data-req="accept_challenge"
                               data-cid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-check"></i></a>
                            <a class="ajaxloadchlsingle" href="javascript:void(0);" data-req="reject_challenge"
                               data-cid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-times"></i></a>
                        </span>
				<?php } ?>

				<?php if ( $match_status == 'edited2' && $team == 'team2' ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Team', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( bp_core_get_user_domain( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a>&nbsp;<?php esc_html_e( 'has edited a match! - Accept the changes?', 'arcane' ); ?>
                            <a class="ajaxloadeditsingle" href="javascript:void(0);" data-req="accept_edit"
                               data-cid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-check"></i></a>
                            <a class="ajaxloadeditsingle" href="javascript:void(0);" data-req="reject_edit"
                               data-cid="<?php echo esc_attr( $post->ID ); ?>"><i class="fas fa-times"></i></a>
                        </span>
				<?php } ?>

				<?php if ( $match_status == 'pending' && $team == 'team1' ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Your request to challenge', 'arcane' ); ?>&nbsp;<a
                                href="<?php echo esc_url( bp_core_get_user_domain( $link ) ); ?>"> <?php echo esc_attr( $name ); ?> </a>&nbsp;<?php esc_html_e( 'team is currently pending!', 'arcane' ); ?>
                        </span>
				<?php } ?>


				<?php if ( $match_status == 'edited1' && $team == 'team1' ) { ?>

                    <span id="score_fin" class="mcscalert col-12">
					        <?php esc_html_e( 'Your request to edit this match is currently pending!', 'arcane' ); ?>
                        </span>
				<?php } ?>

			<?php } ?>


        </div>
    </div>

    <div class="mcontent">


		<?php
		$r = $ArcaneWpTeamWars->get_rounds( $match_id );

		$rounds = [];

		// group rounds by map
		foreach ( $r as $v ) {
			if ( ! isset( $rounds[ $v->group_n ] ) ) {
				$rounds[ $v->group_n ] = [];
			}
			$rounds[ $v->group_n ][] = $v;
		}
		?>

        <div class="col-6 msidebar">
            <section class="widget">
				<?php echo wp_kses_post( get_post_meta( $match_id, 'description', true ) ); ?>
            </section>


            <section class="widget">
                <h4><?php esc_html_e( 'match', 'arcane' ); ?> <span><?php esc_html_e( 'maps', 'arcane' ); ?></span></h4>
                <ul class="mmaps">
					<?php

					// render maps/rounds
					foreach ( $rounds as $map_group ) {

						$first = $map_group[0];
						$image = wp_get_attachment_image_src( $first->screenshot, $size = 'full' );
						if ( ! isset( $image[0] ) or empty( $image[0] ) ) {
							$image[0] = get_theme_file_uri( 'img/defaults/mapdef.jpg' );
						}
						?>
                        <li>
                            <img alt="<?php esc_attr_e( 'map_img', 'arcane' ); ?>"
                                 src="<?php echo esc_url( $image[0] ); ?>"/>
                            <h3><?php echo esc_html( $first->title ); ?></h3>
                            <div class="mapscore">
								<?php
								$map_group = array_reverse( $map_group );
								foreach ( $map_group as $round ) {
									$t1 = $round->tickets1;
									$t2 = $round->tickets2;
									?>


									<?php if ( $match_status == 'done' ) { ?>

                                        <span><?php echo sprintf( esc_html__( '%1$d:%2$d', 'arcane' ), $t1, $t2 ); ?></span>

									<?php } else { ?>

                                        <span><?php if ( isset( $admin ) && $admin ) {
												echo sprintf( esc_html__( '%1$d:%2$d', 'arcane' ), $t1, $t2 );
											} else {
												echo '0:0';
											} ?></span>

									<?php } ?>


								<?php } ?>
                            </div> <!-- mscore -->
                        </li>
						<?php
					}
					?>
                </ul>
            </section>

            <section class="widget">
                <h4><?php esc_html_e( 'Comments', 'arcane' ); ?>  </h4>
				<?php if ( comments_open() ) { ?>
					<?php comments_template( '/short-comments-matches.php' ); ?>
				<?php } ?>
            </section>

        </div>
        <div class="clear"></div>
    </div>
<?php
$datum = get_post_meta( $match_id, 'date_unix', true );

$args_prev = [
	'post_type'      => 'matches',
	'posts_per_page' => 1,
	'post_status'    => 'publish',
	'orderby'        => 'date_unix',
	'meta_key'       => 'date_unix',
	'order'          => 'desc',
	'meta_query'     => [
		[
			'key'     => 'date_unix',
			'value'   => $datum,
			'compare' => '<',
			'type'    => 'NUMERIC'
		],
	],
];

$posts_prev = get_posts( $args_prev );


$args_next = [
	'post_type'      => 'matches',
	'posts_per_page' => 1,
	'post_status'    => 'publish',
	'orderby'        => 'date_unix',
	'meta_key'       => 'date_unix',
	'order'          => 'asc',
	'meta_query'     => [
		[
			'key'     => 'date_unix',
			'value'   => $datum,
			'compare' => '>',
			'type'    => 'NUMERIC'
		],
	],
];

$posts_next = get_posts( $args_next );

$single_class = '';
if ( count( $posts_next ) == 0 || count( $posts_prev ) == 0 ) {
	$single_class = 'single_match_nav';
}

if ( count( $posts_prev ) == 1 || count( $posts_next ) == 1 ) { ?>

    <div class="playerpag <?php echo esc_attr( $single_class ); ?>">

		<?php
		$bck_img = '';

		if ( count( $posts_prev ) == 1 ) {
			$prev_match = $ArcaneWpTeamWars->get_match( array( 'id' => $posts_prev[0]->ID, 'sum_tickets' => true ) );
			if ( arcane_return_game_banner( $prev_match->game_id ) ) {
				$bck_img = arcane_return_game_banner( $prev_match->game_id );
			} elseif ( isset( $options['match_header_bg']['url'] ) ) {
				$bck_img = $options['match_header_bg']['url'];
			}
			?>
            <div class="col-6 prevmember" data-nick="WidowMaker">
                <img alt="<?php esc_attr_e( 'match_img', 'arcane' ); ?>" src="<?php echo esc_url( $bck_img ); ?>">
                <div class="meminfo">
                    <h3><?php esc_html_e( 'prev match', 'arcane' ); ?></h3>
                    <h2><?php echo get_the_title( $posts_prev[0]->ID ); ?></h2>
                    <a href="<?php echo get_the_permalink( $posts_prev[0]->ID ); ?>"
                       class="btn"><?php esc_html_e( 'View match', 'arcane' ); ?></a>
                    <div class="clear"></div>
                </div>
            </div>
		<?php }

		if ( count( $posts_next ) == 1 ) {
			$next_match = $ArcaneWpTeamWars->get_match( array( 'id' => $posts_next[0]->ID, 'sum_tickets' => true ) );
			if ( arcane_return_game_banner( $next_match->game_id ) ) {
				$bck_img = arcane_return_game_banner( $next_match->game_id );
			} elseif ( isset( $options['match_header_bg']['url'] ) ) {
				$bck_img = $options['match_header_bg']['url'];
			}
			?>
            <div class="col-6 nextmember" data-nick="WidowMaker">
                <div class="meminfo">
                    <h3><?php esc_html_e( 'next match', 'arcane' ); ?></h3>
                    <h2><?php echo get_the_title( $posts_next[0]->ID ); ?></h2>
                    <a href="<?php echo get_the_permalink( $posts_next[0]->ID ); ?>"
                       class="btn"><?php esc_html_e( 'View match', 'arcane' ); ?></a>
                </div>
                <img alt="<?php esc_attr_e( 'match_img', 'arcane' ); ?>" src="<?php echo esc_url( $bck_img ); ?>">
                <div class="clear"></div>
            </div>
		<?php } ?>

    </div>
	<?php
}

if ( isset( $_POST['submit_score'] ) ) {


	$scores   = $_POST['scores'];
	$match_id = $post->ID;

	if ( $match_status != 'submitted1' && $match_status != 'submitted2' ) {

		$tformat = get_post_meta( $tid, 'format', true );

		$j = 1;
		foreach ( $scores as $round_group => $r ) {

			for ( $i = 0; $i < sizeof( $r['team1'] ); $i ++ ) {
				$round_id   = $j;
				$round_data = array(
					'match_id' => $post->ID,
					'group_n'  => abs( $round_group ),
					'map_id'   => $r['map_id'],
					'tickets1' => $r['team1'][ $i ],
					'tickets2' => $r['team2'][ $i ]
				);

				$ArcaneWpTeamWars->update_round( $round_id, $round_data );
				$j ++;
			}
		}

		if ( $is_user_type == false ) {
			$type = 'team';
			if ( arcane_is_admin( $team1->ID, get_current_user_id() ) ) {
				$status      = 'submitted1';
				$notify_user = arcane_return_super_admin( $team2->ID );
			} elseif ( arcane_is_admin( $team2->ID, get_current_user_id() ) ) {
				$status      = 'submitted2';
				$notify_user = arcane_return_super_admin( $team1->ID );
			}
		} else {

			//usertype tourney
			$type = 'user';

			if ( $team1->ID == $cid ) {
				$status      = 'submitted1';
				$notify_user = $team2->ID;
			} elseif ( $team2->ID == $cid ) {
				$status      = 'submitted2';
				$notify_user = $team1->ID;
			}

		}
		$p   = array( 'status' => $status );
		$mid = $post->ID;
		$ArcaneWpTeamWars->update_match( $mid, $p );
		$ArcaneWpTeamWars->update_match_post( $post->ID );
		unset( $_POST['submit_score'] );

		arcane_notify_user_score_submitted( $notify_user, $post->ID );

	}

}
?>

    <!-- modal submit-->
<?php get_footer(); ?>