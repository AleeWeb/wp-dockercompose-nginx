<?php
add_action( 'wp_ajax_nopriv_arcane_tournament_return_maps', 'arcane_tournament_return_maps' );
add_action( 'wp_ajax_arcane_tournament_return_maps', 'arcane_tournament_return_maps' );

function arcane_tournament_return_maps() {
	if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	global $ArcaneWpTeamWars;

	$game_id = $_POST['gid'];
	$maps    = $ArcaneWpTeamWars->get_map( array( 'game_id' => $game_id, 'order' => 'asc', 'orderby' => 'title' ) );

	$data = '';
	foreach ( $maps as $map ) {

		$image = wp_get_attachment_image_src( $map->screenshot, array( 800, 500 ) );
		if ( is_array( $image ) ) {
			$output_image = $image[0];
		} else {
			$output_image = esc_url( get_theme_file_uri( 'img/defaults/287x222.jpg' ) );
		}

		$data .= '<li data-id="' . esc_attr( $map->id ) . '" class="map_selector">
					<img alt="img" src="' . esc_url( $output_image ) . '" />
				<h4>' . esc_attr( $map->title ) . '</h4>
		</li>';

	}

	echo wp_kses_post( $data );

	wp_die();
}


add_action( 'wp_ajax_arcane_update_tournament', 'arcane_update_tournament' );
add_action( 'wp_ajax_nopriv_arcane_update_tournament', 'arcane_update_tournament' );
if ( ! function_exists( 'arcane_update_tournament' ) ) {
	function arcane_update_tournament() {

		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		$data = $_POST;

		if ( $_POST['pid'] > 0 ) {
			if ( ! ( current_user_can( 'edit_post', $_POST['pid'] ) ) ) {
				//cant edit it
				wp_die();
			}

			$args    = array(
				'ID'           => $_POST['pid'],
				'post_title'   => $data['tournament_title'],
				'post_content' => $data['desc'],
				'post_type'    => 'tournament',
				'post_status'  => $data['status']
			);
			$post_id = wp_update_post( $args );

		} else {

			$args    = array(
				'post_title'   => $data['tournament_title'],
				'post_content' => $data['desc'],
				'post_type'    => 'tournament',
				'post_status'  => $data['status']
			);
			$post_id = wp_insert_post( $args );
		}

		if ( $post_id > 0 ) {
			foreach ( $data as $single => $value ) {

				switch ( $single ) {
					case 'game_name':
						update_post_meta( $post_id, 'tournament_game', $value );
						break;
					case 'tournament_format':
						update_post_meta( $post_id, 'format', $value );
						break;
					case 'tournament_start_date':
						update_post_meta( $post_id, 'tournament_starts', $value );
						$date = new DateTime($value, new DateTimeZone($_POST['tournament_timezone']));
						update_post_meta( $post_id, 'tournament_starts_unix', $date->format('U') );
						break;
					case 'tournament_timezone':
						update_post_meta( $post_id, 'tournament_timezone', $value );
						break;
					case 'tournament_contestants':
						update_post_meta( $post_id, 'tournament_contestants', $value );
						break;
					case 'tournament_games_format':
						update_post_meta( $post_id, 'tournament_games_format', $value );
						break;
					case 'tournament_location':
						update_post_meta( $post_id, 'tournament_server', $value );
						break;
					case 'tournament_platform':
						update_post_meta( $post_id, 'tournament_platform', $value );
						break;
					case 'tournament_game_frequency':
						update_post_meta( $post_id, 'tournament_game_frequency', $value );
						break;
					case 'tournament_frequency':
						update_post_meta( $post_id, 'tournament_frequency', $value );
						break;
					case 'game_modes':
						update_post_meta( $post_id, 'game_modes', $value );
						break;
					case 'tournament_participants':
						$value = intval( $value );
						update_post_meta( $post_id, 'tournament_max_participants', $value );
						break;
					case 'tournament_prize':
						update_post_meta( $post_id, 'tournament_prizes', $value );
						break;
					case 'regulations':
						update_post_meta( $post_id, 'tournament_regulations', $value );
						break;
					case 'premium':
						if($value == 'true') {
							update_post_meta( $post_id, 'premium', true );
						}else{
							update_post_meta( $post_id, 'premium', false );
						}
						break;
					case 'maps':
						$value = explode( ',', $value );
						update_post_meta( $post_id, 'tournament_maps', $value );
				}
			}

			if ( function_exists( 'arcane_get_product_id_by_tournament_id' ) ) {
				if(!isset($data['premium']))
					update_post_meta( $post_id, 'premium', false );
			}


			echo "ok-_-" . get_permalink( $post_id );
		} else {
			echo "not ok-_-";
		}
		wp_die();
	}
}


/**
 * Create a team function
 */
function arcane_create_team() {

	if ( ! check_ajax_referer( 'arcane-security-nonce', 'sec' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	$name = $_POST['teamName'];

	$value = trim( sanitize_text_field( $name ) );
	$post  = get_page_by_title( $value, 'OBJECT', 'team' );

	if ( $post != null ) {
		echo json_encode( 'notunique' );
		die();
	}

	$location    = $_POST['teamLocation'];
	$language    = $_POST['teamLanguage'];
	$games       = $_POST['games'];
	$games       = explode( ",", $games );
	$about       = $_POST['about'];
	$team_logo   = $_POST['teamLogo'];
	$team_banner = $_POST['teamBanner'];
	$platforms   = $_POST['teamPlatforms'];

	$facebook      = $_POST['teamFacebook'];
	$instagram     = $_POST['teamInstagram'];
	$twitter       = $_POST['teamTwitter'];
	$twitch        = $_POST['teamTwitch'];
	$discordServer = $_POST['discordServer'];
	$youtube       = $_POST['teamYoutube'];
	$website       = $_POST['teamWebsite'];

	$user_id   = get_current_user_id();
	$post_data = array(
		'post_title'   => $name,
		'post_type'    => 'team',
		'post_content' => $about,
		'post_status'  => 'publish',
		'post_author'  => $user_id
	);

	// insert the post into the database
	$post_id = wp_insert_post( $post_data );

	if ( $post_id ) {
		$post_arr['super_admin'] = $user_id;
		$post_arr['admins']      = [];
		$arr['active']           = [];
		$arr['pending']          = [];
		$post_arr['users']       = $arr;
		add_post_meta( $post_id, 'team', $post_arr );
		add_user_meta( $user_id, 'team_post_id', array( $post_id, time() ) );

		update_post_meta( $post_id, 'games', $games );
		update_post_meta( $post_id, 'team_photo', $team_logo );
		update_post_meta( $post_id, 'team_bg', $team_banner );

		if ( ! empty( $platforms ) ) {
			update_post_meta( $post_id, 'platforms', $platforms );
		}

		if ( ! empty( $facebook ) ) {
			update_post_meta( $post_id, 'teamFacebook', $facebook );
		}

		if ( ! empty( $twitter ) ) {
			update_post_meta( $post_id, 'teamTwitter', $twitter );
		}

		if ( ! empty( $twitch ) ) {
			update_post_meta( $post_id, 'teamTwitch', $twitch );
		}

		if ( ! empty( $instagram ) ) {
			update_post_meta( $post_id, 'teamInstagram', $instagram );
		}

		if ( ! empty( $discordServer ) ) {
			update_post_meta( $post_id, 'discordServer', $discordServer );
		}

		if ( ! empty( $youtube ) ) {
			update_post_meta( $post_id, 'youtube', $youtube );
		}

		if ( ! empty( $website ) ) {
			update_post_meta( $post_id, 'website', $website );
		}

		if ( ! empty( $location ) ) {
			update_post_meta( $post_id, 'location', $location );
		}

		if ( ! empty( $language ) ) {
			update_post_meta( $post_id, 'language', $language );
		}


		echo json_encode( get_the_permalink( $post_id ) );
	}

	die();
}

add_action( 'wp_ajax_arcane_create_team', 'arcane_create_team' );
add_action( 'wp_ajax_nopriv_arcane_create_team', 'arcane_create_team' );


/**
 * Edit a team function
 */
function arcane_update_team() {

	if ( ! check_ajax_referer( 'arcane-security-nonce', 'sec' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	$pid  = $_POST['pid'];
	$name = $_POST['teamName'];

	$value = trim( sanitize_text_field( $name ) );
	$post  = get_page_by_title( $value, 'OBJECT', 'team' );

	$oldName = get_the_title( $pid );

	if ( $post != null && $oldName != $name ) {
		echo json_encode( 'notunique' );
		die();
	}

	$location    = $_POST['teamLocation'];
	$language    = $_POST['teamLanguage'];
	$games       = $_POST['games'];
	$games       = explode( ",", $games );
	$about       = $_POST['about'];
	$team_logo   = $_POST['teamLogo'];
	$team_banner = $_POST['teamBanner'];
	$platforms   = $_POST['teamPlatforms'];


	$facebook      = $_POST['teamFacebook'];
	$instagram     = $_POST['teamInstagram'];
	$twitter       = $_POST['teamTwitter'];
	$twitch        = $_POST['teamTwitch'];
	$discordServer = $_POST['discordServer'];
	$youtube       = $_POST['teamYoutube'];
	$website       = $_POST['teamWebsite'];


	$post_information = array(
		'ID'           => $pid,
		'post_title'   => $name,
		'post_content' => $about,
		'post_type'    => 'team',
		'post_author'  => get_current_user_id(),
	);
	$post_id          = wp_update_post( $post_information );

	if ( $post_id ) {

		update_post_meta( $post_id, 'games', $games );

		if ( ! empty( $team_logo ) ) {
			update_post_meta( $post_id, 'team_photo', $team_logo );
		}
		if ( ! empty( $team_banner ) ) {
			update_post_meta( $post_id, 'team_bg', $team_banner );
		}

		if ( ! empty( $facebook ) ) {
			update_post_meta( $post_id, 'teamFacebook', $facebook );
		}

		if ( ! empty( $twitter ) ) {
			update_post_meta( $post_id, 'teamTwitter', $twitter );
		}

		if ( ! empty( $twitch ) ) {
			update_post_meta( $post_id, 'teamTwitch', $twitch );
		}

		if ( ! empty( $instagram ) ) {
			update_post_meta( $post_id, 'teamInstagram', $instagram );
		}

		if ( ! empty( $discordServer ) ) {
			update_post_meta( $post_id, 'discordServer', $discordServer );
		}

		if ( ! empty( $location ) ) {
			update_post_meta( $post_id, 'location', $location );
		}

		if ( ! empty( $language ) ) {
			update_post_meta( $post_id, 'language', $language );
		}

		if ( ! empty( $platforms ) ) {
			update_post_meta( $post_id, 'platforms', $platforms );
		}

		if ( ! empty( $youtube ) ) {
			update_post_meta( $post_id, 'youtube', $youtube );
		}

		if ( ! empty( $website ) ) {
			update_post_meta( $post_id, 'website', $website );
		}
		echo json_encode( get_the_permalink( $post_id ) );
	}

	die();
}

add_action( 'wp_ajax_arcane_update_team', 'arcane_update_team' );
add_action( 'wp_ajax_nopriv_arcane_update_team', 'arcane_update_team' );


/**
 * Delete a team function
 */
function arcane_delete_team() {

	if ( ! check_ajax_referer( 'arcane-security-nonce', 'sec' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	$pid = $_POST['pid'];
	wp_delete_post( $pid );
	echo json_encode( home_url( '/' ) );
	die();
}

add_action( 'wp_ajax_arcane_delete_team', 'arcane_delete_team' );
add_action( 'wp_ajax_nopriv_arcane_delete_team', 'arcane_delete_team' );

/**
 * Add team logo
 */
function arcane_team_logo() {


	if ( ! check_ajax_referer( 'arcane-security-nonce', 'sec' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	$teamLogo          = $_FILES['teamLogo'];
	$new_file_path_url = '';

	if ( isset( $teamLogo ) && ! empty( $teamLogo ) ) {
		$wordpress_upload_dir = wp_upload_dir();
		$i                    = 1;
		$new_file_path        = $wordpress_upload_dir['path'] . '/' . $teamLogo['name'];
		$new_file_path_url    = $wordpress_upload_dir['url'] . '/' . $teamLogo['name'];
		$new_file_mime        = wp_check_filetype( $teamLogo['name'] );

		if ( empty( $teamLogo ) ) {
			die( 'File is not selected.' );
		}

		if ( $teamLogo['error'] ) {
			die( $teamLogo['error'] );
		}

		if ( $teamLogo['size'] > wp_max_upload_size() ) {
			die( 'It is too large than expected.' );
		}

		if ( ! in_array( $new_file_mime['type'], get_allowed_mime_types() ) ) {
			die( 'WordPress doesn\'t allow this type of uploads.' );
		}

		while ( file_exists( $new_file_path ) ) {
			$i ++;
			$new_file_path     = $wordpress_upload_dir['path'] . '/' . $i . '_' . $teamLogo['name'];
			$new_file_path_url = $wordpress_upload_dir['url'] . '/' . $i . '_' . $teamLogo['name'];
		}

		if ( move_uploaded_file( $teamLogo['tmp_name'], $new_file_path ) ) {


			$upload_id = wp_insert_attachment( array(
				'guid'           => $new_file_path,
				'post_mime_type' => $new_file_mime['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $teamLogo['name'] ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			), $new_file_path );

			// wp_generate_attachment_metadata() won't work if you do not include this file
			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			// Generate and save the attachment metas into the database
			wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );


		}

	}

	echo json_encode( $new_file_path_url );

	die();

}

add_action( 'wp_ajax_arcane_team_logo', 'arcane_team_logo' );
add_action( 'wp_ajax_nopriv_arcane_team_logo', 'arcane_team_logo' );


/**
 * Add team banner
 */
function arcane_team_banner() {


	if ( ! check_ajax_referer( 'arcane-security-nonce', 'sec' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	$teamBanner        = $_FILES['teamBanner'];
	$new_file_path_url = '';

	if ( isset( $teamBanner ) && ! empty( $teamBanner ) ) {
		$wordpress_upload_dir = wp_upload_dir();
		$i                    = 1;
		$new_file_path        = $wordpress_upload_dir['path'] . '/' . $teamBanner['name'];
		$new_file_path_url    = $wordpress_upload_dir['url'] . '/' . $teamBanner['name'];
		$new_file_mime        = wp_check_filetype( $teamBanner['name'] );

		if ( empty( $teamBanner ) ) {
			die( 'File is not selected.' );
		}

		if ( $teamBanner['error'] ) {
			die( $teamBanner['error'] );
		}

		if ( $teamBanner['size'] > wp_max_upload_size() ) {
			die( 'It is too large than expected.' );
		}

		if ( ! in_array( $new_file_mime['type'], get_allowed_mime_types() ) ) {
			die( 'WordPress doesn\'t allow this type of uploads.' );
		}

		while ( file_exists( $new_file_path ) ) {
			$i ++;
			$new_file_path     = $wordpress_upload_dir['path'] . '/' . $i . '_' . $teamBanner['name'];
			$new_file_path_url = $wordpress_upload_dir['url'] . '/' . $i . '_' . $teamBanner['name'];
		}

		if ( move_uploaded_file( $teamBanner['tmp_name'], $new_file_path ) ) {


			$upload_id = wp_insert_attachment( array(
				'guid'           => $new_file_path,
				'post_mime_type' => $new_file_mime['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $teamBanner['name'] ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			), $new_file_path );

			// wp_generate_attachment_metadata() won't work if you do not include this file
			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			// Generate and save the attachment metas into the database
			wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );


		}

	}

	echo json_encode( $new_file_path_url );

	die();

}

add_action( 'wp_ajax_arcane_team_banner', 'arcane_team_banner' );
add_action( 'wp_ajax_nopriv_arcane_team_banner', 'arcane_team_banner' );


if ( ! function_exists( 'arcane_challenge_acc_rej_single' ) ) {
	function arcane_challenge_acc_rej_single() {

		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		global $ArcaneWpTeamWars;
		$req          = filter_var( $_POST['req'], FILTER_SANITIZE_STRING );
		$challenge_id = filter_var( $_POST['cid'], FILTER_SANITIZE_NUMBER_INT ); //is actually a game id
		$cid          = get_current_user_id();
		$team_id      = 0;

		if ( arcane_is_user_in_game( $challenge_id, $cid ) or current_user_can( 'manage_options' ) ) {

			$match = $ArcaneWpTeamWars->get_match( array( 'id' => $challenge_id ) );

			$team1_id = $match->team1;
			$team2_id = $match->team2;
			if ( arcane_is_member( $team1_id, $cid ) ) {
				$team_id = $team2_id;
			}
			if ( arcane_is_member( $team2_id, $cid ) ) {
				$team_id = $team1_id;
			}
			$notify_user = arcane_return_super_admin( $team_id );

			if ( $req == "accept_challenge" ) {
				$data = 'accepted';
				echo json_encode( $data );
				$p = array( 'status' => 'active' );
				$ArcaneWpTeamWars->update_match( $challenge_id, $p );
				arcane_challenge_accepted( $notify_user, $challenge_id );
			}

			if ( $req == "reject_challenge" ) {
				$data = 'rejected';
				echo json_encode( $data );
				$p = array( 'status' => 'rejected_challenge' );
				$ArcaneWpTeamWars->update_match( $challenge_id, $p );
				arcane_challenge_rejected( $notify_user, $challenge_id );
			}
		}
		die();
	}
}
add_action( 'wp_ajax_nopriv_arcane_challenge_acc_rej_single', 'arcane_challenge_acc_rej_single' );
add_action( 'wp_ajax_arcane_challenge_acc_rej_single', 'arcane_challenge_acc_rej_single' );


if ( ! function_exists( 'arcane_match_score_acc_rej' ) ) {
	function arcane_match_score_acc_rej() {

		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		global $ArcaneWpTeamWars;
		$req       = filter_var( $_POST['req'], FILTER_SANITIZE_STRING );
		$match_id  = filter_var( $_POST['mid'], FILTER_SANITIZE_STRING );
		$cid       = get_current_user_id();
		$user_type = 'user';
		$data      = '';

		if ( arcane_is_user_in_game( $match_id, $cid ) or current_user_can( 'manage_options' ) ) {

			$match = $ArcaneWpTeamWars->get_match( array( 'id' => $match_id ) );

			$team_id    = 0;
			$teamdva_id = 0;

			if ( isset( $match->tournament_id ) ) {
				if ( $match->tournament_participants == 'user' ) {

					$team1_id = $match->team1;
					$team2_id = $match->team2;
					if ( $cid == $team1_id ) {
						$team_id    = $team2_id;
						$teamdva_id = $team1_id;
					}
					if ( $cid == $team2_id ) {
						$team_id    = $team1_id;
						$teamdva_id = $team2_id;
					}
					$notify_user = $team_id;
				} else {
					$user_type = 'team';
					$team1_id  = $match->team1;
					$team2_id  = $match->team2;
					if ( arcane_is_member( $team1_id, $cid ) ) {
						$team_id    = $team2_id;
						$teamdva_id = $team1_id;
					}
					if ( arcane_is_member( $team2_id, $cid ) ) {
						$team_id    = $team1_id;
						$teamdva_id = $team2_id;
					}
					$notify_user = arcane_return_super_admin( $team_id );
				}

			} else {
				$user_type = 'team';
				$team1_id  = $match->team1;
				$team2_id  = $match->team2;
				if ( arcane_is_member( $team1_id, $cid ) ) {
					$team_id    = $team2_id;
					$teamdva_id = $team1_id;
				}
				if ( arcane_is_member( $team2_id, $cid ) ) {
					$team_id    = $team1_id;
					$teamdva_id = $team2_id;
				}
				$notify_user = arcane_return_super_admin( $team_id );
			}

			if ( $req == "accept_score" ) {
				$data = 'accepted';
				$p    = array( 'status' => 'done' );
				$ArcaneWpTeamWars->update_match( $match_id, $p );
				arcane_score_accepted( $notify_user, $match_id, $teamdva_id, $user_type );

				if ( isset( $match->tournament_id ) ) {
					$tid     = $match->tournament_id;
					$user_id = get_post_field( 'post_author', $tid );
					arcane_notify_admin_tournament_score_submitted( $user_id, $tid, $match_id );

					arcane_create_match_third_place( $tid, $match_id );

				}
			}

			if ( $req == "reject_score" ) {
				$data    = 'rejected';
				$tickets = get_post_meta( $match_id, 'tickets', true );
				foreach ( $tickets as &$ticket ) {
					$ticket['tickets1'] = 0;
					$ticket['tickets2'] = 0;
				}

				$p = array( 'status' => 'active', 'team1_tickets' => 0, 'team2_tickets' => 0, 'tickets' => $tickets );
				$ArcaneWpTeamWars->update_match( $match_id, $p );
				arcane_score_rejected( $notify_user, $match_id, $teamdva_id, $user_type );
			}
			echo json_encode( $data );
		}
		die();

	}
}
add_action( 'wp_ajax_nopriv_arcane_match_score_acc_rej', 'arcane_match_score_acc_rej' );
add_action( 'wp_ajax_arcane_match_score_acc_rej', 'arcane_match_score_acc_rej' );


if ( ! function_exists( 'arcane_challenge_acc_rej' ) ) {
	function arcane_challenge_acc_rej() {

		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		global $ArcaneWpTeamWars;
		$req          = filter_var( $_POST['req'], FILTER_SANITIZE_STRING );
		$challenge_id = filter_var( $_POST['cid'], FILTER_SANITIZE_NUMBER_INT ); //is actually a game id
		$cid          = get_current_user_id();
		if ( arcane_is_user_in_game( $challenge_id, $cid ) or current_user_can( 'manage_options' ) ) {
			$data = [];

			$challenge_meta = get_post_meta( $challenge_id );
			$notify_team_id = $challenge_meta['team1'][0];
			$notify_user    = arcane_return_super_admin( $notify_team_id );

			if ( $req == "accept_challenge" ) {
				$data[0] = $challenge_id;
				$data[1] = 'accepted';
				echo json_encode( $data );
				$p = array( 'status' => 'active' );
				$ArcaneWpTeamWars->update_match( $challenge_id, $p );
				arcane_challenge_accepted( $notify_user, $challenge_id );
			}

			if ( $req == "reject_challenge" ) {
				$data[0] = $challenge_id;
				$data[1] = 'rejected';
				echo json_encode( $data );
				$p = array( 'status' => 'rejected_challenge' );
				$ArcaneWpTeamWars->update_match( $challenge_id, $p );
				arcane_challenge_rejected( $notify_user, $challenge_id );
			}
		}
		die();
	}
}
add_action( 'wp_ajax_nopriv_arcane_challenge_acc_rej', 'arcane_challenge_acc_rej' );
add_action( 'wp_ajax_arcane_challenge_acc_rej', 'arcane_challenge_acc_rej' );


if ( ! function_exists( 'arcane_edit_acc_rej' ) ) {
	function arcane_edit_acc_rej() {
		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}
		global $ArcaneWpTeamWars;
		$req          = filter_var( $_POST['req'], FILTER_SANITIZE_STRING );
		$challenge_id = filter_var( $_POST['cid'], FILTER_SANITIZE_NUMBER_INT ); //is actually a game id
		$cid          = get_current_user_id();
		$team_id      = 0;

		if ( arcane_is_user_in_game( $challenge_id, $cid ) or current_user_can( 'manage_options' ) ) {
			$data = [];

			$match = $ArcaneWpTeamWars->get_match( array( 'id' => $challenge_id ) );

			$team1_id = $match->team1;
			$team2_id = $match->team2;
			if ( arcane_is_member( $team1_id, $cid ) ) {
				$team_id = $team2_id;
			}
			if ( arcane_is_member( $team2_id, $cid ) ) {
				$team_id = $team1_id;
			}
			$notify_user = arcane_return_super_admin( $team_id );

			if ( $req == "accept_edit" ) {
				$data[0] = $challenge_id;
				$data[1] = 'accepted';
				echo json_encode( $data );
				$previous_status = get_post_meta( $challenge_id, 'status_backup', true );
				$p               = array( 'status' => $previous_status );
				$ArcaneWpTeamWars->update_match( $challenge_id, $p );
				arcane_edit_accepted( $notify_user, $challenge_id );
			}

			if ( $req == "reject_edit" ) {
				$data[0] = $challenge_id;
				$data[1] = 'rejected';
				echo json_encode( $data );
				$title           = get_post_meta( $challenge_id, 'title_edit', true );
				$description     = get_post_meta( $challenge_id, 'description_edit', true );
				$external_url    = get_post_meta( $challenge_id, 'external_url_edit', true );
				$previous_status = get_post_meta( $challenge_id, 'status_backup', true );
				$date            = get_post_meta( $challenge_id, 'date_edit', true );
				$match_status    = get_post_meta( $challenge_id, 'match_status_edit', true );
				$p               = array( 'status'       => $previous_status,
				                          'title'        => $title,
				                          'description'  => $description,
				                          'external_url' => $external_url,
				                          'date'         => $date,
				                          'match_status' => $match_status,
				);
				$ArcaneWpTeamWars->update_match( $challenge_id, $p );
				arcane_edit_rejected( $notify_user, $challenge_id );
			}
		}
		die();
	}
}
add_action( 'wp_ajax_nopriv_arcane_edit_acc_rej', 'arcane_edit_acc_rej' );
add_action( 'wp_ajax_arcane_edit_acc_rej', 'arcane_edit_acc_rej' );


if ( ! function_exists( 'arcane_edit_acc_rej_single' ) ) {
	function arcane_edit_acc_rej_single() {

		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		global $ArcaneWpTeamWars;
		$req          = filter_var( $_POST['req'], FILTER_SANITIZE_STRING );
		$challenge_id = filter_var( $_POST['cid'], FILTER_SANITIZE_NUMBER_INT ); //is actually a game id
		$cid          = get_current_user_id();
		$team_id      = 0;

		if ( arcane_is_user_in_game( $challenge_id, $cid ) or current_user_can( 'manage_options' ) ) {

			$match = $ArcaneWpTeamWars->get_match( array( 'id' => $challenge_id ) );

			$team1_id = $match->team1;
			$team2_id = $match->team2;
			if ( arcane_is_member( $team1_id, $cid ) ) {
				$team_id = $team2_id;
			}
			if ( arcane_is_member( $team2_id, $cid ) ) {
				$team_id = $team1_id;
			}
			$notify_user = arcane_return_super_admin( $team_id );

			if ( $req == "accept_edit" ) {
				$data = 'accepted';
				echo json_encode( $data );
				$previous_status = get_post_meta( $challenge_id, 'status_backup', true );
				$p               = array( 'status' => $previous_status );
				$ArcaneWpTeamWars->update_match( $challenge_id, $p );
				arcane_edit_accepted( $notify_user, $challenge_id );
			}

			if ( $req == "reject_edit" ) {
				$data = 'rejected';
				echo json_encode( $data );
				$title           = get_post_meta( $challenge_id, 'title_edit', true );
				$description     = get_post_meta( $challenge_id, 'description_edit', true );
				$external_url    = get_post_meta( $challenge_id, 'external_url_edit', true );
				$previous_status = get_post_meta( $challenge_id, 'status_backup', true );
				$date            = get_post_meta( $challenge_id, 'date_edit', true );
				$match_status    = get_post_meta( $challenge_id, 'match_status_edit', true );
				$p               = array( 'status'       => $previous_status,
				                          'title'        => $title,
				                          'description'  => $description,
				                          'external_url' => $external_url,
				                          'date'         => $date,
				                          'match_status' => $match_status,
				);
				$ArcaneWpTeamWars->update_match( $challenge_id, $p );
				arcane_edit_rejected( $notify_user, $challenge_id );

			}
		}
		die();
	}
}
add_action( 'wp_ajax_nopriv_arcane_edit_acc_rej_single', 'arcane_edit_acc_rej_single' );
add_action( 'wp_ajax_arcane_edit_acc_rej_single', 'arcane_edit_acc_rej_single' );


if ( ! function_exists( 'arcane_check_if_teamname_unique' ) ) {
	function arcane_check_if_teamname_unique() {
		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		$value = trim( sanitize_text_field( $_POST['currentText'] ) );
		$value = str_replace( ' ', '-', $value ); // Replaces all spaces with hyphens.
		$value = preg_replace( '/[^A-Za-z0-9\-]/', '', $value ); // Removes special chars.

		$post = get_page_by_title( $value, 'OBJECT', 'team' );

		if ( $post != null ) {
			echo "/*-notunique*-/";
		} else {
			echo esc_attr( $value );
		}


		wp_die();
	}
}
add_action( 'wp_ajax_arcane_check_if_teamname_unique', 'arcane_check_if_teamname_unique' );
add_action( 'wp_ajax_nopriv_arcane_check_if_teamname_unique', 'arcane_check_if_teamname_unique' );


if ( ! function_exists( 'arcane_change_membership_let_join' ) ) {
	function arcane_change_membership_let_join() {

		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		$pid      = filter_var( $_POST['pid'], FILTER_SANITIZE_NUMBER_INT ); //team id
		$req      = filter_var( $_POST['req'], FILTER_SANITIZE_STRING ); //wat du
		$uid      = filter_var( $_POST['uid'], FILTER_SANITIZE_NUMBER_INT ); //user id
		$is_admin = false;
		$is_mine  = false;

		if ( ! ( $uid > 0 ) ) {
			die( 'Would be a good idea to login first, woudn\'t it?' );
		}

		if ( ! ( $pid > 0 ) ) {
			die();
		}

		$c_id = get_current_user_id();
		$post = get_post( $pid );
		$a_id = $post->post_author;

		if ( arcane_is_member( $pid, $uid ) && ! arcane_is_pending_member( $pid, $uid ) ) {
			$data[2] = "error";
			$data[1] = $uid;
			echo json_encode( $data );
			die();
		}

		if ( ( current_user_can( 'manage_options' ) ) or arcane_is_admin( $pid, $c_id ) ) {
			$is_admin = true;
		}
		if ( $c_id == $a_id ) {
			$is_mine = true;
		}

		if ( ( $is_admin == true ) or ( $is_mine == true ) ) {
			$post_meta_arr = get_post_meta( $pid, 'team', true );
			$data          = [];
			if ( $req == "let_this_member_join" ) {
				$key = array_search( $uid, $post_meta_arr['users']['pending'] );

				if ( $key === false ) {
					$data[0] = "member_not_there";
					$data[1] = $uid;
					echo json_encode( $data );
					die();
				}

				if ( $key !== false ) {
					unset( $post_meta_arr['users']['pending'][ $key ] );
				}
				$post_meta_arr['users']['active'][] = $uid;
				$data[0]                            = "let_this_member_join";
				$data[1]                            = $uid;
				ArcaneUpdateUserTournaments( $uid );
				arcane_join_team_allow( $uid, $pid );
			}
			update_post_meta( $pid, 'team', $post_meta_arr );
			echo json_encode( $data );
		}
		die();
	}
}
add_action( 'wp_ajax_nopriv_arcane_change_membership_let_join', 'arcane_change_membership_let_join' );
add_action( 'wp_ajax_arcane_change_membership_let_join', 'arcane_change_membership_let_join' );

if ( ! function_exists( 'arcane_change_membership_remove_friend_admin' ) ) {
	function arcane_change_membership_remove_friend_admin() {

		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		$pid      = filter_var( $_POST['pid'], FILTER_SANITIZE_NUMBER_INT ); //team id
		$req      = filter_var( $_POST['req'], FILTER_SANITIZE_STRING ); //wat du
		$uid      = filter_var( $_POST['uid'], FILTER_SANITIZE_NUMBER_INT ); //user id
		$is_admin = false;
		$is_mine  = false;

		if ( ! ( $uid > 0 ) ) {
			die( 'Would be a good idea to login first, woudn\'t it?' );
		}

		if ( ! ( $pid > 0 ) ) {
			die();
		}

		$c_id = get_current_user_id();
		$post = get_post( $pid );
		$a_id = $post->post_author;

		if ( ( current_user_can( 'manage_options' ) ) or arcane_is_admin( $pid, $c_id ) ) {
			$is_admin = true;
		}
		if ( $c_id == $a_id ) {
			$is_mine = true;
		}


		if ( ( $is_admin == true ) or ( $is_mine == true ) ) {
			$post_meta_arr = get_post_meta( $pid, 'team', true );
			$data          = [];
			if ( $req == "remove_friend_admin" ) {

				if ( ! empty( $post_meta_arr['admins'] ) ) {
					$key = array_search( $uid, $post_meta_arr['admins'] );
					if ( $key !== false ) {
						unset( $post_meta_arr['admins'][ $key ] );
					}
				}

				if ( ! empty( $post_meta_arr['users']['active'] ) ) {
					$key = array_search( $uid, $post_meta_arr['users']['active'] );
					if ( $key !== false ) {
						unset( $post_meta_arr['users']['active'][ $key ] );
					}
				}

				if ( ! empty( $post_meta_arr['users']['pending'] ) ) {
					$key = array_search( $uid, $post_meta_arr['users']['pending'] );
					if ( $key !== false ) {
						unset( $post_meta_arr['users']['pending'][ $key ] );
					}
				}


				$timovi = get_user_meta( $uid, 'team_post_id' );

				if ( arcane_is_val_exists( $pid, $timovi ) ) {
					$index = array_search( $pid, array_column( $timovi, 0 ) );
					delete_user_meta( $uid, 'team_post_id', $timovi[ $index ] );
				}

				$data[0] = "remove_friend_admin";
				$data[1] = $uid;
			}
			update_post_meta( $pid, 'team', $post_meta_arr );
			echo json_encode( $data );
		}
		die();
	}
}
add_action( 'wp_ajax_nopriv_arcane_change_membership_remove_friend_admin', 'arcane_change_membership_remove_friend_admin' );
add_action( 'wp_ajax_arcane_change_membership_remove_friend_admin', 'arcane_change_membership_remove_friend_admin' );


if ( ! function_exists( 'arcane_change_membership_make_administrator' ) ) {
	function arcane_change_membership_make_administrator() {

		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		$pid      = filter_var( $_POST['pid'], FILTER_SANITIZE_NUMBER_INT ); //team id
		$req      = filter_var( $_POST['req'], FILTER_SANITIZE_STRING ); //wat du
		$uid      = filter_var( $_POST['uid'], FILTER_SANITIZE_NUMBER_INT ); //user id
		$is_admin = false;
		$is_mine  = false;

		if ( ! ( $uid > 0 ) ) {
			die( 'Would be a good idea to login first, woudn\'t it?' );
		}

		if ( ! ( $pid > 0 ) ) {
			die();
		}

		$c_id = get_current_user_id();
		$post = get_post( $pid );
		$a_id = $post->post_author;

		if ( ( current_user_can( 'manage_options' ) ) or arcane_is_admin( $pid, $c_id ) ) {
			$is_admin = true;
		}
		if ( $c_id == $a_id ) {
			$is_mine = true;
		}


		if ( ( $is_admin == true ) or ( $is_mine == true ) ) {
			$post_meta_arr = get_post_meta( $pid, 'team', true );
			$data          = [];
			if ( $req == "make_administrator" ) {
				$key = array_search( $uid, $post_meta_arr['users']['active'] );
				if ( $key !== false ) {
					unset( $post_meta_arr['users']['active'][ $key ] );
				}
				$post_meta_arr['admins'][] = $uid;
				$data[0]                   = "make_administrator";
				$data[1]                   = $uid;
			}
			update_post_meta( $pid, 'team', $post_meta_arr );
			echo json_encode( $data );
		}
		die();
	}
}
add_action( 'wp_ajax_nopriv_arcane_change_membership_make_administrator', 'arcane_change_membership_make_administrator' );
add_action( 'wp_ajax_arcane_change_membership_make_administrator', 'arcane_change_membership_make_administrator' );


if ( ! function_exists( 'arcane_change_membership_downgrade_to_user' ) ) {
	function arcane_change_membership_downgrade_to_user() {

		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		$pid = filter_var( $_POST['pid'], FILTER_SANITIZE_NUMBER_INT ); //team id
		$req = filter_var( $_POST['req'], FILTER_SANITIZE_STRING ); //wat du
		$uid = filter_var( $_POST['uid'], FILTER_SANITIZE_NUMBER_INT ); //user id

		$is_admin = false;
		$is_mine  = false;

		if ( ! ( $uid > 0 ) ) {
			die( 'Would be a good idea to login first, woudn\'t it?' );
		}

		if ( ! ( $pid > 0 ) ) {
			die();
		}

		$c_id = get_current_user_id();
		$post = get_post( $pid );
		$a_id = $post->post_author;

		if ( ( current_user_can( 'manage_options' ) ) or arcane_is_admin( $pid, $c_id ) ) {
			$is_admin = true;
		}
		if ( $c_id == $a_id ) {
			$is_mine = true;
		}


		if ( ( $is_admin == true ) or ( $is_mine == true ) ) {
			$post_meta_arr = get_post_meta( $pid, 'team', true );
			$data          = [];
			if ( $req == "downgrade_to_user" ) {
				$key = array_search( $uid, $post_meta_arr['admins'] );
				if ( $key !== false ) {
					unset( $post_meta_arr['admins'][ $key ] );
				}
				$post_meta_arr['users']['active'][] = $uid;
				$data[0]                            = "downgrade_to_user";
				$data[1]                            = $uid;
			}
			update_post_meta( $pid, 'team', $post_meta_arr );
			echo json_encode( $data );
		}
		die();
	}
}
add_action( 'wp_ajax_nopriv_arcane_change_membership_downgrade_to_user', 'arcane_change_membership_downgrade_to_user' );
add_action( 'wp_ajax_arcane_change_membership_downgrade_to_user', 'arcane_change_membership_downgrade_to_user' );


if ( ! function_exists( 'arcane_change_membership_block' ) ) {
	function arcane_change_membership_block() {

		if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
			wp_die();
		}

		$pid = filter_var( $_POST['pid'], FILTER_SANITIZE_NUMBER_INT ); //team id
		$req = filter_var( $_POST['req'], FILTER_SANITIZE_STRING ); //wat du
		$uid = get_current_user_id(); //my uid anyways

		if ( ! ( $uid > 0 ) ) {
			die( 'Would be a good idea to login first, woudn\'t it?' );
		}

		if ( ! ( $pid > 0 ) ) {
			die();
		}
		$post_meta_arr = get_post_meta( $pid, 'team', true );
		$data          = [];

		if ( $req == "remove_friend_user" ) {

			if ( ! empty( $post_meta_arr['admins'] ) ) {
				$key = array_search( $uid, $post_meta_arr['admins'] );
				if ( $key !== false ) {
					unset( $post_meta_arr['admins'][ $key ] );
				}
			}

			if ( ! empty( $post_meta_arr['users']['active'] ) ) {
				$key = array_search( $uid, $post_meta_arr['users']['active'] );
				if ( $key !== false ) {
					unset( $post_meta_arr['users']['active'][ $key ] );
				}
			}

			if ( ! empty( $post_meta_arr['users']['pending'] ) ) {
				$key = array_search( $uid, $post_meta_arr['users']['pending'] );
				if ( $key !== false ) {
					unset( $post_meta_arr['users']['pending'][ $key ] );
				}
			}

			$timovi = get_user_meta( $uid, 'team_post_id' );

			if ( arcane_is_val_exists( $pid, $timovi ) ) {
				$index = array_search( $pid, array_column( $timovi, 0 ) );
				delete_user_meta( $uid, 'team_post_id', $timovi[ $index ] );
			}

			$data[0] = "remove_friend_user";
			$data[1] = $uid;
		}

		if ( $req == "join_team" ) {
			$post_meta_arr['users']['pending'][] = $uid;
			add_user_meta( $uid, 'team_post_id', array( $pid, time() ) );
			$data[0] = "join_team";
			$ad_id   = arcane_return_super_admin( $pid );
			arcane_join_team_request( $ad_id, $pid, $uid );
		}


		if ( $req == "cancel_request" ) {
			if ( ! empty( $post_meta_arr['admins'] ) ) {
				$key = array_search( $uid, $post_meta_arr['admins'] );
				if ( $key !== false ) {
					unset( $post_meta_arr['admins'][ $key ] );
				}
			}

			if ( ! empty( $post_meta_arr['users']['active'] ) ) {
				$key = array_search( $uid, $post_meta_arr['users']['active'] );
				if ( $key !== false ) {
					unset( $post_meta_arr['users']['active'][ $key ] );
				}
			}

			if ( ! empty( $post_meta_arr['users']['pending'] ) ) {
				$key = array_search( $uid, $post_meta_arr['users']['pending'] );
				if ( $key !== false ) {
					unset( $post_meta_arr['users']['pending'][ $key ] );
				}
			}

			$timovi = get_user_meta( $uid, 'team_post_id' );

			if ( arcane_is_val_exists( $pid, $timovi ) ) {
				$index = array_search( $pid, array_column( $timovi, 0 ) );
				delete_user_meta( $uid, 'team_post_id', $timovi[ $index ] );
			}

			$data[0] = "cancel_request";
		}

		update_post_meta( $pid, 'team', $post_meta_arr );
		echo json_encode( $data );
		die();
	}
}
add_action( 'wp_ajax_nopriv_arcane_change_membership_block', 'arcane_change_membership_block' );
add_action( 'wp_ajax_arcane_change_membership_block', 'arcane_change_membership_block' );




/**
 * Add user logo
 */
function arcane_user_logo() {


	if ( ! check_ajax_referer( 'arcane-security-nonce', 'sec' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	$teamLogo          = $_FILES['profilePhoto'];
	$new_file_path_url = '';

	if ( isset( $teamLogo ) && ! empty( $teamLogo ) ) {
		$wordpress_upload_dir = wp_upload_dir();
		$i                    = 1;
		$new_file_path        = $wordpress_upload_dir['path'] . '/' . $teamLogo['name'];
		$new_file_path_url    = $wordpress_upload_dir['url'] . '/' . $teamLogo['name'];
		$new_file_mime        = wp_check_filetype( $teamLogo['name'] );

		if ( empty( $teamLogo ) ) {
			die( 'File is not selected.' );
		}

		if ( $teamLogo['error'] ) {
			die( $teamLogo['error'] );
		}

		if ( $teamLogo['size'] > wp_max_upload_size() ) {
			die( 'It is too large than expected.' );
		}

		if ( ! in_array( $new_file_mime['type'], get_allowed_mime_types() ) ) {
			die( 'WordPress doesn\'t allow this type of uploads.' );
		}

		while ( file_exists( $new_file_path ) ) {
			$i ++;
			$new_file_path     = $wordpress_upload_dir['path'] . '/' . $i . '_' . $teamLogo['name'];
			$new_file_path_url = $wordpress_upload_dir['url'] . '/' . $i . '_' . $teamLogo['name'];
		}

		if ( move_uploaded_file( $teamLogo['tmp_name'], $new_file_path ) ) {


			$upload_id = wp_insert_attachment( array(
				'guid'           => $new_file_path,
				'post_mime_type' => $new_file_mime['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $teamLogo['name'] ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			), $new_file_path );

			// wp_generate_attachment_metadata() won't work if you do not include this file
			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			// Generate and save the attachment metas into the database
			wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );

		}

	}

	echo json_encode( $new_file_path_url );

	die();

}
add_action( 'wp_ajax_nopriv_arcane_user_logo', 'arcane_user_logo' );
add_action( 'wp_ajax_arcane_user_logo', 'arcane_user_logo' );



/**
 * Add team banner
 */
function arcane_user_banner() {


	if ( ! check_ajax_referer( 'arcane-security-nonce', 'sec' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	$teamLogo          = $_FILES['bannerPhoto'];
	$new_file_path_url = '';

	if ( isset( $teamLogo ) && ! empty( $teamLogo ) ) {
		$wordpress_upload_dir = wp_upload_dir();
		$i                    = 1;
		$new_file_path        = $wordpress_upload_dir['path'] . '/' . $teamLogo['name'];
		$new_file_path_url    = $wordpress_upload_dir['url'] . '/' . $teamLogo['name'];
		$new_file_mime        = wp_check_filetype( $teamLogo['name'] );

		if ( empty( $teamLogo ) ) {
			die( 'File is not selected.' );
		}

		if ( $teamLogo['error'] ) {
			die( $teamLogo['error'] );
		}

		if ( $teamLogo['size'] > wp_max_upload_size() ) {
			die( 'It is too large than expected.' );
		}

		if ( ! in_array( $new_file_mime['type'], get_allowed_mime_types() ) ) {
			die( 'WordPress doesn\'t allow this type of uploads.' );
		}

		while ( file_exists( $new_file_path ) ) {
			$i ++;
			$new_file_path     = $wordpress_upload_dir['path'] . '/' . $i . '_' . $teamLogo['name'];
			$new_file_path_url = $wordpress_upload_dir['url'] . '/' . $i . '_' . $teamLogo['name'];
		}

		if ( move_uploaded_file( $teamLogo['tmp_name'], $new_file_path ) ) {


			$upload_id = wp_insert_attachment( array(
				'guid'           => $new_file_path,
				'post_mime_type' => $new_file_mime['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $teamLogo['name'] ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			), $new_file_path );

			// wp_generate_attachment_metadata() won't work if you do not include this file
			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			// Generate and save the attachment metas into the database
			wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );

		}

	}

	echo json_encode( $new_file_path_url );

	die();

}
add_action( 'wp_ajax_nopriv_arcane_user_banner', 'arcane_user_banner' );
add_action( 'wp_ajax_arcane_user_banner', 'arcane_user_banner' );

function arcane_update_email_notifications(){

	if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	$uid = bp_displayed_user_id();
	$email_matches = $_POST['email_matches'];
	$email_tournaments = $_POST['email_tournaments'];
	$email_team = $_POST['email_team'];

	if($email_matches == 'yes')
		update_user_meta($uid, 'email_matches_subscribed', true); else{
		update_user_meta($uid, 'email_matches_subscribed', false);
	}

	if($email_tournaments == 'yes')
		update_user_meta($uid, 'email_tournaments_subscribed', true); else{
		update_user_meta($uid, 'email_tournaments_subscribed', false);
	}

	if($email_team == 'yes')
		update_user_meta($uid, 'email_teams_subscribed', true); else{
		update_user_meta($uid, 'email_teams_subscribed', false);
	}


	wp_die();

}
add_action( 'wp_ajax_nopriv_arcane_update_email_notifications', 'arcane_update_email_notifications' );
add_action( 'wp_ajax_arcane_update_email_notifications', 'arcane_update_email_notifications' );


/**
 * BR plugin submit
 */
function arcane_br_submit_score(){

	if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	$tid = $_POST['tid'];
	$score = $_POST['br-score'];
	$round = $_POST['round'];
	$team = $_POST['team-id'];
	$screenshot = $_POST['br-screenshot'];
	$stream  = $_POST['br-stream'];
	$admin = $_POST['tournament-admin'];

	$participant_cache = get_post_meta( $tid, 'participant_cache', true );

	foreach ($participant_cache as &$participant){

		if($participant['id'] == $team){
			$participant['score'][$round]['score'] = $score;

			if(!empty($screenshot))
			$participant['score'][$round]['screenshot'] = $screenshot;

			if(!empty($stream))
			$participant['score'][$round]['stream'] = $stream;

			arcane_notify_user_score_submitted($admin,$tid);
		}

	}


	update_post_meta( $tid, 'participant_cache', $participant_cache );

	arcane_update_br_standing($tid);
	echo json_encode();

	wp_die();
}
add_action( 'wp_ajax_nopriv_arcane_br_submit_score', 'arcane_br_submit_score' );
add_action( 'wp_ajax_arcane_br_submit_score', 'arcane_br_submit_score' );

/**
 * Update br positions
 */
function arcane_update_br_positions(){
	if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}

	$positions = $_POST['positions'];
	$tid = $_POST['tid'];
	$participants = get_post_meta( $tid, 'participant_cache', true );

	foreach ($participants as &$participant){

		foreach ($positions as $key => $position){
			if($key == $participant['id']){
				$participant['position'] = $position;
			}
		}
	}

	update_post_meta( $tid, 'participant_cache', $participants );

	wp_die();
}
add_action( 'wp_ajax_nopriv_arcane_update_br_positions', 'arcane_update_br_positions' );
add_action( 'wp_ajax_arcane_update_br_positions', 'arcane_update_br_positions' );


/**
 * Update br score
 */
function arcane_update_br_score(){
	if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}


	$score = $_POST['score'];
	$tid = $_POST['tid'];
	$uid = $_POST['uid'];
	$round = $_POST['round'];

	$participants = get_post_meta( $tid, 'participant_cache', true );

	foreach ($participants as &$participant){

		if($uid == $participant['id']){
			$participant['score'][$round]['score'] = $score;
		}
	}

	update_post_meta( $tid, 'participant_cache', $participants );

	wp_die();
}
add_action( 'wp_ajax_nopriv_arcane_update_br_score', 'arcane_update_br_score' );
add_action( 'wp_ajax_arcane_update_br_score', 'arcane_update_br_score' );



if(!function_exists('arcane_stop_br_game')){
	function arcane_stop_br_game() {
		$tid = isset($_POST['tid']) ? (int)$_POST['tid'] : '';
		if ($tid) {
			update_post_meta($tid, 'game_stop', '1');
		}
		exit;
	}
}
add_action( 'wp_ajax_arcane_stop_br_game', 'arcane_stop_br_game' );
add_action( 'wp_ajax_nopriv_arcane_stop_br_game', 'arcane_stop_br_game' );