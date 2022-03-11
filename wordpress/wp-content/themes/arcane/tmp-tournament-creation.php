<?php
/*
 * Template name: Tournament creation
*/
?>
<?php
if ( !is_user_logged_in() ) {
	header( 'Location: ' . home_url() );
	exit;
}

if ( isset( $_GET['edit'] ) ) {

	$p = get_post( $_GET['edit'] );

	if ( $p->post_author != get_current_user_id() && ! current_user_can( 'administrator' ) ) {
		header( 'Location: ' . home_url() );
		exit;
	}
}

?>
<?php get_header(); ?>

<?php

global $post, $arcane_t_data, $ArcaneWpTeamWars;
$games    = $ArcaneWpTeamWars->get_game( '' );
$options  = arcane_get_theme_options();
$usermeta = get_user_meta( get_current_user_id() );

if ( isset($options['tournament_creation']) && $options['tournament_creation'] == '0' && $post->post_name == 'tournament-creation' && ! current_user_can( 'administrator' ) && ( (isset($usermeta['_checkbox_tournament_user'][0]) && $usermeta['_checkbox_tournament_user'][0] == "no") || (isset($usermeta['_checkbox_tournament_user'][0]) && $usermeta['_checkbox_tournament_user'][0] == null )) ) {
	header( 'Location: ' . home_url() );
	exit;
}

$edit_mode = $premium_plugin = false;
$title     = $game_id = $start_date = $timezone = $tournament_server = $tournament_format = $tournament_max_participants = $tournament_platform = $premium_tournament =
$prizes = $participants = $tournament_games_format = $tournament_game_frequency = $tournament_frequency = $game_modes = $tournament_regulations = $tournament_desc = $premium = '';

if ( function_exists( 'arcane_get_product_id_by_tournament_id' ) ) {
	$premium_plugin = true;
}

if ( isset( $_GET['edit'] ) ) {

	$edit_mode = true;

	$title                       = get_the_title( $_GET['edit'] );
	$post_content                = get_post( $_GET['edit'] );
	$tournament_desc             = $post_content->post_content;
	$game                        = get_post_meta( $_GET['edit'], 'tournament_game', true );
	$game_id                     = arcane_return_game_id_by_game_name( $game );
	$start_date                  = get_post_meta( $_GET['edit'], 'tournament_starts', true );
	$timezone                    = get_post_meta( $_GET['edit'], 'tournament_timezone', true );
	$tournament_server           = get_post_meta( $_GET['edit'], 'tournament_server', true );
	$tournament_format           = get_post_meta( $_GET['edit'], 'format', true );
	$tournament_max_participants = get_post_meta( $_GET['edit'], 'tournament_max_participants', true );
	$tournament_platform         = get_post_meta( $_GET['edit'], 'tournament_platform', true );
	$participants                = get_post_meta( $_GET['edit'], 'tournament_contestants', true );
	$tournament_games_format     = get_post_meta( $_GET['edit'], 'tournament_games_format', true );
	$tournament_game_frequency   = get_post_meta( $_GET['edit'], 'tournament_game_frequency', true );
	$tournament_frequency        = get_post_meta( $_GET['edit'], 'tournament_frequency', true );
	$game_modes                  = get_post_meta( $_GET['edit'], 'game_modes', true );
	$tournament_regulations      = get_post_meta( $_GET['edit'], 'tournament_regulations', true );
	$prizes                      = get_post_meta( $_GET['edit'], 'tournament_prizes', true );
	$premium                     = get_post_meta( $_GET['edit'], 'premium', true );

	if ( get_post_meta( $_GET['edit'], 'premium', true ) ) {
		$premium_tournament = get_post_meta( $_GET['edit'], 'premium', true );
	}

}

$arcane_tournament_mapping = arcane_tournament_mapping();
?>

    <div class="container">
        <form id="tournament_create" class="tournament-creation-wrap col-9 ">
            <ul class="tc-games">
                <h2>
					<?php esc_html_e( "Choose a game", "arcane" ) ?>
                </h2>

				<?php if ( ! empty( $games ) ) { ?>
					<?php foreach ( $games as $game ) { ?>
                        <li class="game game_<?php echo esc_attr( $game->id ); ?> <?php if ( $game->id == $game_id ) {
							echo 'active';
						} ?>" data-id="<?php echo esc_attr( $game->id ); ?>"
                            data-name="<?php echo esc_attr( $game->title ); ?>">
                            <span><?php echo esc_html( esc_attr( $game->title ) ); ?></span>
                        </li>
					<?php } ?>
				<?php } ?>

            </ul>
            <div class="tc-forms">
                <span class="different-game"><?php esc_html_e( "Choose another game", "arcane" ) ?> <i
                            class="fas fa-angle-up"></i></span>
                <div class="register-form-wrapper">
                    <h3><i class="fas fa-trophy"></i> <?php esc_html_e( "The basics", "arcane" ) ?></h3>
                    <p>
                        <label for="tournament_title"><span><?php esc_html_e( "Tournament Title", "arcane" ) ?></span>
                            <span class="cust_input">
                            <input type="text" name="tournament_title" class="input" size="20" tabindex="10"
                                   value="<?php echo esc_attr( $title ); ?>">
                        </span>
                        </label>
                    </p>
                    <p>
                        <label for="tournament_desc"><span><?php esc_html_e( "Tournament Description", "arcane" ) ?></span>
                            <span class="cust_input">
                            <?php

                            $wp_editor_settings = array(
	                            'textarea_name' => 'tournament_desc',
	                            'media_buttons' => true,
	                            'editor_class'  => 'widefat',
	                            'textarea_rows' => 10,
	                            'teeny'         => true,
	                            'quicktags'     => false
                            );

                            wp_editor( $tournament_desc, "tournament_desc", $wp_editor_settings );
                            ?>
                        </span>
                        </label>
                    </p>
                    <div class="form-half">
                        <p>
                            <label for="tournament_start_date"><span><?php esc_html_e( "Start date", "arcane" ) ?></span>
                                <span class="cust_input">
                                    <input type="text" name="tournament_start_date" class="input datepicker"
                                           value="<?php echo esc_attr( $start_date ); ?>">
                                </span>
                            </label>
                        </p>
                        <p>
                            <label for="tournament_timezone"><span><?php esc_html_e( "Timezone", "arcane" ) ?></span>
                                <span class="cust_input">
                                <?php $tournament_timezones = timezone_identifiers_list(); ?>
                                <select name="tournament_timezone">
                                    <option value="" selected
                                            disabled><?php esc_html_e( 'Please choose', 'arcane' ); ?></option>
                                    <?php
                                    foreach ( $tournament_timezones as $t ) {

	                                    $selected = '';
	                                    if ( $timezone == $t ) {
		                                    $selected = 'selected';
	                                    }

	                                    echo '<option ' . esc_attr( $selected ) . ' value="' . esc_html( $t ) . '">' . esc_html( $t ) . '</option>';
                                    }
                                    ?>
                                </select>
                            </span>
                            </label>
                        </p>
                    </div>
                </div>

                <div class="register-form-wrapper">
                    <h3><i class="far fa-address-card"></i> <?php esc_html_e( "About", "arcane" ) ?></h3>
                    <p>
                        <label for="tournament_location"><span><?php esc_html_e( "Location", "arcane" ) ?></span>
                            <span class="cust_input">
                            <input type="text" name="tournament_location" class="input"
                                   value="<?php echo esc_attr( $tournament_server ); ?>">
                        </span>
                        </label>
                    </p>
                    <p>
                        <label><span><?php esc_html_e( "Tournament Format", "arcane" ) ?></span>
                            <span class="cust_input">
                            <?php
                            $formats = $options['choosen_types'];

                            ?>
                            <select name="tournament_format">
                                <option value="" selected
                                        disabled><?php esc_html_e( 'Please choose', 'arcane' ); ?></option>

                                <?php
                                if ( is_array( $formats ) ) {

	                                foreach ( $formats as $key => $format ) {
		                                $selected = '';
		                                if ( $key == $tournament_format ) {
			                                $selected = 'selected';
		                                }

		                                if ( $format === '1' ) {
			                                $name = $arcane_tournament_mapping[$key];
			                                echo '<option ' . esc_attr( $selected ) . ' value="' . esc_html( $key ) . '">' . esc_html( $name ) . '</option>';
		                                }

	                                }

                                }
                                ?>
                            </select>
                        </span>
                        </label>
                    </p>

                    <div class="form-half">
                        <p>
                            <label for="tournament_participants"><span><?php esc_html_e( "Number of participants", "arcane" ) ?></span>
                                <span class="cust_input">
                                <input type="number" name="tournament_participants" class="input" min="3"
                                       value="<?php echo esc_attr( $tournament_max_participants ); ?>">
                            </span>
                            </label>
                        </p>

                        <p>
                            <label for="tournament_platform"><span><?php esc_html_e( "Platform", "arcane" ) ?></span>
                                <span class="cust_input">
                                <select name="tournament_platform">
                                    <option value="" selected
                                            disabled><?php esc_html_e( 'Please choose', 'arcane' ); ?></option>
                                    <option <?php if ( $tournament_platform == 'ps5' ) {
                                        echo 'selected';
                                    } ?> value="ps5"><?php esc_html_e( 'PlayStation5', 'arcane' ); ?></option>
                                    <option <?php if ( $tournament_platform == 'ps' ) {
	                                    echo 'selected';
                                    } ?> value="ps"><?php esc_html_e( 'PlayStation4', 'arcane' ); ?></option>
                                    <option <?php if ( $tournament_platform == 'pc' ) {
	                                    echo 'selected';
                                    } ?> value="pc"><?php esc_html_e( 'PC', 'arcane' ); ?></option>
                                    <option <?php if ( $tournament_platform == 'xbox' ) {
	                                    echo 'selected';
                                    } ?> value="xbox"><?php esc_html_e( 'Xbox', 'arcane' ); ?></option>
                                    <option <?php if ( $tournament_platform == 'wii' ) {
	                                    echo 'selected';
                                    } ?> value="wii"><?php esc_html_e( 'Wii', 'arcane' ); ?></option>
                                    <option <?php if ( $tournament_platform == 'nin' ) {
	                                    echo 'selected';
                                    } ?> value="nin"><?php esc_html_e( 'Nintendo', 'arcane' ); ?></option>
                                    <option <?php if ( $tournament_platform == 'mobile' ) {
	                                    echo 'selected';
                                    } ?> value="mobile"><?php esc_html_e( 'Mobile', 'arcane' ); ?></option>
                                    <option <?php if ( $tournament_platform == 'cross' ) {
	                                    echo 'selected';
                                    } ?> value="cross"><?php esc_html_e( 'Cross platform', 'arcane' ); ?></option>
                                </select>
                            </span>
                            </label>
                        </p>
                    </div>
                </div>

                <div class="register-form-wrapper">
                    <h3><i class="fas fa-info-circle"></i> <?php esc_html_e( "Details", "arcane" ) ?></h3>

                    <div class="form-half">
                        <p>
                            <label for="tournament_contestants"><span><?php esc_html_e( "Participants", "arcane" ) ?></span>
                                <span class="cust_input">
                                <select name="tournament_contestants" class="form-control input-sm">
                                    <option value="" selected
                                            disabled><?php esc_html_e( 'Please choose', 'arcane' ); ?></option>
                                    <option <?php if ( $participants == 'team' ) {
	                                    echo 'selected';
                                    } ?> value="team"><?php esc_html_e( "Teams", "arcane" ) ?></option>
                                    <option <?php if ( $participants == 'user' ) {
	                                    echo 'selected';
                                    } ?> value="user"><?php esc_html_e( "Users", "arcane" ) ?></option>
                                </select>
                            </span>
                            </label>
                        </p>

                        <p>
                            <label for="tournament_games_format"><span><?php esc_html_e( "Game Format", "arcane" ) ?></span>
                                <span class="cust_input">
                                <select name="tournament_games_format" class="form-control input-sm">
                                    <option value="" selected
                                            disabled><?php esc_html_e( 'Please choose', 'arcane' ); ?></option>
                                    <option <?php if ( $tournament_games_format == 'bo1' ) {
	                                    echo 'selected';
                                    } ?> value="bo1"><?php esc_html_e( "Best of 1", "arcane" ) ?></option>
                                     <option <?php if ( $tournament_games_format == 'bo2' ) {
	                                     echo 'selected';
                                     } ?> value="bo2"><?php esc_html_e( "Best of 2", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_games_format == 'bo3' ) {
	                                    echo 'selected';
                                    } ?> value="bo3"><?php esc_html_e( "Best of 3", "arcane" ) ?></option>
                                     <option <?php if ( $tournament_games_format == 'bo4' ) {
	                                     echo 'selected';
                                     } ?> value="bo4"><?php esc_html_e( "Best of 4", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_games_format == 'bo5' ) {
	                                    echo 'selected';
                                    } ?> value="bo5"><?php esc_html_e( "Best of 5", "arcane" ) ?></option>
                                </select>
                            </span>
                            </label>
                        </p>
                    </div>
                    <div class="form-half">
                        <p>
                            <label for="tournament_game_frequency"><span><?php esc_html_e( "Game Frequency", "arcane" ) ?></span>
                                <span class="cust_input">
                                <select name="tournament_game_frequency" class="form-control input-sm">
                                    <option value="" selected
                                            disabled><?php esc_html_e( 'Please choose', 'arcane' ); ?></option>
                                     <option <?php if ( $tournament_game_frequency == '5 minutes' ) {
                                         echo 'selected';
                                     } ?> value="5 minutes"><?php esc_html_e( "Every 5 minutes", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '15 minutes' ) {
	                                    echo 'selected';
                                    } ?> value="15 minutes"><?php esc_html_e( "Every 15 minutes", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '30 minutes' ) {
	                                    echo 'selected';
                                    } ?> value="30 minutes"><?php esc_html_e( "Every 30 minutes", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '60 minutes' ) {
	                                    echo 'selected';
                                    } ?> value="60 minutes"><?php esc_html_e( "Every hour", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '1' ) {
	                                    echo 'selected';
                                    } ?> value="1"><?php esc_html_e( "Daily", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '2' ) {
	                                    echo 'selected';
                                    } ?> value="2"><?php esc_html_e( "Every 2 days", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '3' ) {
	                                    echo 'selected';
                                    } ?> value="3"><?php esc_html_e( "Every 3 days", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '4' ) {
	                                    echo 'selected';
                                    } ?> value="4"><?php esc_html_e( "Every 4 days", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '5' ) {
	                                    echo 'selected';
                                    } ?> value="5"><?php esc_html_e( "Every 5 days", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '6' ) {
	                                    echo 'selected';
                                    } ?> value="6"><?php esc_html_e( "Every 6 days", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '7' ) {
	                                    echo 'selected';
                                    } ?> value="7"><?php esc_html_e( "Every 7 days", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '14' ) {
	                                    echo 'selected';
                                    } ?> value="14"><?php esc_html_e( "Every two weeks", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_game_frequency == '30' ) {
	                                    echo 'selected';
                                    } ?> value="30"><?php esc_html_e( "Monthly", "arcane" ) ?></option>
                                </select>
                            </span>
                            </label>
                        </p>
                        <p>
                            <label for="tournament_frequency"><span><?php esc_html_e( "Tournament frequency", "arcane" ) ?></span>
                                <span class="cust_input">
                                <select name="tournament_frequency" class="form-control input-sm">
                                    <option value="" selected
                                            disabled><?php esc_html_e( 'Please choose', 'arcane' ); ?></option>
                                    <option <?php if ( $tournament_frequency == 'daily' ) {
	                                    echo 'selected';
                                    } ?> value="daily"><?php esc_html_e( "Daily", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_frequency == 'weekly' ) {
	                                    echo 'selected';
                                    } ?> value="weekly"><?php esc_html_e( "Weekly", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_frequency == 'monthly' ) {
	                                    echo 'selected';
                                    } ?> value="monthly"><?php esc_html_e( "Monthly", "arcane" ) ?></option>
                                    <option <?php if ( $tournament_frequency == 'yearly' ) {
	                                    echo 'selected';
                                    } ?> value="yearly"><?php esc_html_e( "Yearly", "arcane" ) ?></option>
                                </select>
                            </span>
                            </label>
                        </p>
                    </div>

					<?php
					if ( isset( $options['game_modes'] ) && ! empty( $options['game_modes'][0] ) ) { ?>
                        <div class="form-half">
                            <p>
                                <label for="game_modes"><span><?php esc_html_e( "Game modes", "arcane" ); ?></span>
                                    <span class="cust_input">
                                        <select name="game_modes" class="form-control input-sm">
                                            <option value="" selected
                                                    disabled><?php esc_html_e( 'Please choose', 'arcane' ); ?></option>
                                            <?php foreach ( $options['game_modes'] as $mode ) {
	                                            $selected = '';
	                                            if ( $game_modes == $mode ) {
		                                            $selected = 'selected';
	                                            }
	                                            ?>
                                                <option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $mode ); ?>"><?php echo esc_html( $mode ); ?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </label>
                            </p>
                        </div>
						<?php
					}
					?>
                </div>

                <div class="register-form-wrapper">
                    <h3><i class="far fa-map"></i> <?php esc_html_e( "Maps", "arcane" ) ?></h3>

                    <ul class="tbmaps">

                    </ul>
                </div>
                <div class="register-form-wrapper">
                    <h3><i class="fas fa-award"></i> <?php esc_html_e( "Prizes", "arcane" ) ?>  </h3>
                    <div class="prizes_wrapper tbprice">
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
                                    <img alt="cup"
                                         src="<?php echo esc_url( get_theme_file_uri( 'img/ticons/cup.png' ) ); ?>"/>
                                    <span>
                                    <?php esc_html_e( "Reward", "arcane" ); ?>
                                </span>
                                </th>
                            </tr>

							<?php if ( ! empty( $prizes ) ) { ?>
								<?php
								$i = 1;
								foreach ( $prizes as $prize ) {

									switch ( $i ) {
										case 1:
											$class = "tfirstw";
											$image = esc_url( get_theme_file_uri( 'img/ticons/1st.png' ) );
											break;
										case 2:
											$class = "tsecondw";
											$image = esc_url( get_theme_file_uri( 'img/ticons/2nd.png' ) );
											break;
										case 3:
											$class = "tthirdw";
											$image = esc_url( get_theme_file_uri( 'img/ticons/3rd.png' ) );
											break;
										default:
											$class = "";
											$image = "";
											break;
									}
									?>
                                    <tr>
                                        <td class="<?php echo esc_attr( $class ); ?>">
                                        <span>
                                            <?php if ( $image ) { ?>
                                                <img alt="img" src="<?php echo esc_url( $image ); ?>">
                                            <?php } ?>
	                                        <?php echo esc_html( $i );
	                                        esc_html_e( ' place:', 'arcane' ); ?>
                                        </span>
                                        </td>
                                        <td class="trcell">
                                        <span class="tournament-table_prize">
                                            <input type="text" name="tournament_prize[]"
                                                   value="<?php echo esc_attr( $prize ); ?>">
                                        </span>
                                        </td>
                                    </tr>
									<?php $i ++;
								} ?>
							<?php } ?>

                            <tr id="prizes_controller">
                                <td colspan="2">
                                    <div id="add_prize" class="tournaments_add_prize btn">
										<?php esc_html_e( "Add a prize", "arcane" ) ?>
                                    </div>
                                    <div id="remove_prize" class="tournaments_remove_prize btn">
										<?php esc_html_e( "Remove a prize", "arcane" ) ?>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="register-form-wrapper">
                    <h3><i class="fas fa-bullhorn"></i> <?php esc_html_e( "Regulations", "arcane" ) ?></h3>
					<?php

					$wp_editor_settings = array(
						'textarea_name' => 'tournament_regulations',
						'media_buttons' => true,
						'editor_class'  => 'widefat',
						'textarea_rows' => 10,
						'teeny'         => true,
						'quicktags'     => false
					);
					wp_editor( $tournament_regulations, "tournament_regulations", $wp_editor_settings );
					?>
                </div>

				<?php

				if ( $premium_plugin ) { ?>

                    <div class="register-form-wrapper">
                        <h3><i class="fas fa-trophy"></i> <?php esc_html_e( "Premium Tournament", "arcane" ) ?></h3>

						<?php
						$checked = '';
						if ( $premium_tournament ) {
							$checked = 'checked';
						}
						?>
                        <div class="makeptour">
                            <input <?php echo esc_attr( $checked ); ?>
                                    type="checkbox"
                                    class="input-text"
                                    name="premium"
                                    value="true">
                            <label for="premium"><?php esc_html_e( "Make this tournament Premium", "arcane" ); ?></label>
                        </div>


                    </div>
				<?php } ?>


                <div data-action="publish"
                     class="publish_tournament btn"><?php esc_html_e( 'Publish tournament', 'arcane' ); ?></div>
                <div data-action="draft"
                     class="publish_tournament btn"><?php esc_html_e( 'Save as draft', 'arcane' ); ?></div>

				<?php if ( $edit_mode ) { ?>
                    <input name="pid" type="hidden" value="<?php echo esc_attr( $_GET['edit'] ); ?> ">
				<?php } ?>
            </div>


        </form>

    </div>

<?php get_footer(); ?>