<?php

namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * News.
 *
 * Elementor widget that displays set of posts in different layouts.
 *
 * Class Widget_SW_News
 * @package Elementor
 */
class Widget_SW_Matches extends Widget_Base {


	/**
	 * Get widget name.
	 *
	 * Retrieve news widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */

	public function get_name() {
		return 'sw_matches';
	}


	/**
	 * Get widget title.
	 *
	 * Retrieve news widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */

	public function get_title() {
		return esc_html__( 'Arcane - Matches', 'arcane' );
	}


	/**
	 * Get widget category
	 * @return array
	 */

	public function get_categories() {
		return [ 'skywarrior' ];
	}


	/**
	 * Get widget icon.
	 *
	 * Retrieve news widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */

	public function get_icon() {
		// Icon name from the Elementor font file, as per https://pojome.github.io/elementor-icons/
		return 'eicon-flash';
	}


	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 2.1.0
	 * @access public
	 *
	 */

	public function get_keywords() {
		return [ 'matches', 'tournaments' ];
	}


	/**
	 * Register news widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */

	protected function _register_controls() {

		$this->start_controls_section(
			'section_shop_content',
			[
				'label' => esc_html__( 'Content', 'arcane' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'arcane' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Games', 'arcane' ),
				'title'   => esc_html__( 'Add title text', 'arcane' ),
			]
		);

		$this->add_control(
			'position',
			[
				'label'   => esc_html__( 'Title position', 'arcane' ),
				'type'    => 'select',
				'options' => [
					'left'   => esc_html__( 'Left', 'arcane' ),
					'center' => esc_html__( 'Center', 'arcane' ),
					'right'  => esc_html__( 'Right', 'arcane' )
				],
				'default' => 'center',
			]
		);

		global $ArcaneWpTeamWars;
		$games = $ArcaneWpTeamWars->get_game( '' );
		$g     = [];
		foreach ( $games as $game ) {
			$g[ $game->id ] = $game->title;
		}

		$this->add_control(
			'games',
			[
				'label'   => esc_html__( 'Games', 'arcane' ),
				'type'    => 'multiselect',
				'options' => $g,
				'title'   => esc_html__( 'Select games', 'arcane' ),
			]
		);

		$this->add_control(
			'older_than',
			[
				'label'   => esc_html__( 'Hide older than', 'arcane' ),
				'type'    => 'select',
				'options' => [
					'all' => esc_html__( 'Show all', 'arcane' ),
					'1w'  => esc_html__( '1 week', 'arcane' ),
					'2w'  => esc_html__( '2 weeks', 'arcane' ),
					'3w'  => esc_html__( '3 weeks', 'arcane' ),
					'1m'  => esc_html__( '1 month', 'arcane' ),
					'2m'  => esc_html__( '2 months', 'arcane' ),
					'3m'  => esc_html__( '3 months', 'arcane' ),
					'6m'  => esc_html__( '6 months', 'arcane' ),
					'1y'  => esc_html__( '1 year', 'arcane' ),
				],
				'default' => 'center',
			]
		);

		$this->add_control(
			'matches_order',
			[
				'label'   => esc_html__( 'Order', 'arcane' ),
				'type'    => 'select',
				'options' => [
					'ASC'  => esc_html__( 'Ascending', 'arcane' ),
					'DESC' => esc_html__( 'Descending', 'arcane' ),
				],
				'title'   => esc_html__( 'Select', 'arcane' ),
			]
		);

		$this->add_control(
			'number',
			[
				'label'   => esc_html__( 'Number of items', 'arcane' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 5,
				'title'   => esc_html__( 'Add number of items', 'arcane' ),
			]

		);

		$this->end_controls_section();

	}


	/**
	 * Render news widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */

	protected function render() {

		global $ArcaneWpTeamWars;

		//Get values (in array "settings")
		$settings = $this->get_settings();

		$title      = $settings['title'];
		$position   = $settings['position'];
		$games      = $settings['games'];
		$older_than = $settings['older_than'];
		$order      = $settings['matches_order'];
		$number     = $settings['number'];

		$timezone_string = arcane_timezone_string();
		$timezone        = $timezone_string ? $timezone_string : 'UTC';
		$time_now        = new \DateTime( "now", new \DateTimeZone( $timezone ) );

		$now = $time_now->getTimestamp();

		$matches  = [];
		$game_ids = '';

		if ( $games != '' ) {

			if ( is_array( $games ) && ! empty( $games ) ) {
				foreach ( $games as $game ) {
					$g = $ArcaneWpTeamWars->get_game( 'id=' . $game );

					if ( empty( $game_ids ) ) {
						if ( isset( $g[0]->id ) ) {
							$game_ids = $g[0]->id;
						}
					} else {
						if ( isset( $g[0] ) ) {
							$game_ids = $game_ids . ', ' . $g[0]->id;
						}
					}
				}
			}
		}

		$games = $ArcaneWpTeamWars->get_games( 'id=' . $game_ids . '&orderby=title&order=asc' );

		$value = 0;
		if ( $older_than == 'all' ) {
			$value = 0;
		}
		if ( $older_than == '1w' ) {
			$value = 60 * 60 * 24 * 7;
		}
		if ( $older_than == '2w' ) {
			$value = 60 * 60 * 24 * 14;
		}
		if ( $older_than == '3w' ) {
			$value = 60 * 60 * 24 * 21;
		}
		if ( $older_than == '1m' ) {
			$value = 60 * 60 * 24 * 30;
		}
		if ( $older_than == '2m' ) {
			$value = 60 * 60 * 24 * 30 * 2;
		}
		if ( $older_than == '3m' ) {
			$value = 60 * 60 * 24 * 30 * 3;
		}
		if ( $older_than == '6m' ) {
			$value = 60 * 60 * 24 * 30 * 6;
		}
		if ( $older_than == '1y' ) {
			$value = 60 * 60 * 24 * 30 * 12;
		}

		$from_date = 0;

		if ( isset( $older_than ) ) {
			$age = (int) $value;
			// 0 means show all matches
			if ( $age > 0 ) {
				$from_date = $now - $age;
			}
		}

		if ( ! empty( $games ) ) {

			foreach ( $games as $g ) {
				$m = $ArcaneWpTeamWars->get_match(
					array(
						'status'      => array( 'active', 'done' ),
						'from_date'   => $from_date,
						'game_id'     => $g->id,
						'limit'       => $number,
						'order'       => $order,
						'orderby'     => 'date',
						'sum_tickets' => true
					),'', true );
				if ( sizeof( $m ) ) {
					$matches  = array_merge( $matches, $m );
				}
			}


			if ( function_exists( 'arcane_other_matches_sort' ) ) {
				usort( $matches, 'arcane_other_matches_sort' );
			}
			?>
			<?php if ( ! empty( $title ) ) { ?>
                <h3 class="block-title" style="text-align: <?php echo esc_attr( $position ); ?>">
					<?php echo esc_html( $title ); ?>
                </h3>
			<?php } ?>

            <div class="matches-tabw"> 

				<?php if ( count( $games ) > 1 ) { ?>
                    <ul class="matches-tab">
                        <li class="active">
                            <a class="custom-tabs-link" href="#all"><?php esc_html_e('All', 'arcane'); ?></a>
                        </li>
						<?php foreach ( $games as $game ) { ?>
                            <li>
                                <a href="#<?php echo esc_html( $game->id ); ?>"
                                   title="<?php echo esc_html( $game->title ); ?>"
                                   class="custom-tabs-link"><?php echo esc_html( $game->abbr ); ?></a>
                            </li>
						<?php } ?>

                    </ul>
				<?php } ?>


                <ul class="matches-wrapper">

					<?php if ( ! empty( $matches ) ) { ?>
						<?php foreach ( $matches as $match ) {

							$match = $ArcaneWpTeamWars->get_match( array( 'id' => $match->ID ) );

							$t1 = '';
							if ( isset( $match->team1_tickets ) ) {
								$t1 = $match->team1_tickets;
							}

							$t2 = '';
							if ( isset( $match->team2_tickets ) ) {
								$t2 = $match->team2_tickets;
							}

							$admin = false;
							if ( arcane_is_admin( $match->ID, get_current_user_id() ) || arcane_is_admin( $match->ID, get_current_user_id() ) ) {
								$admin = true;
							}

							$substatus = false;
							if ( $match->status == 'submitted1' || $match->status == 'submitted2' ) {
								$substatus = true;
							}

							$gameid  = $match->game_id;
							$gameabr = arcane_return_game_abbr( $gameid );

							$match_meta = get_post_meta( $match->ID );
							$date       = $match_meta['date'][0];
							$timestamp  = $match_meta['date_unix'][0];

							$is_upcoming = $timestamp > $now;
							$is_playing  = ( ( $now > $timestamp && $now < $timestamp + 3600 ) && ( $t1 == 0 && $t2 == 0 ) || ( $match->status == 'active' ) && ( $t1 == 0 && $t2 == 0 ) );

							$tparticipants = $match->tournament_participants;

							if ( $tparticipants == 'team' ) {
								$is_user_type = false;
							}  else {
								$is_user_type = true;
							}


							?>

                            <li data-type="#<?php echo esc_attr($gameid); ?>">
                                <a href="<?php echo esc_url( get_permalink( $match->ID ) ); ?>">
                                    <h3><?php echo esc_html( $match->title ); ?></h3>
                                    <div class="mw-wrapper">
                                        <div class="mw-left">

                                            <?php if($is_user_type){ ?>

                                                <?php
	                                            $user1    = get_user_by( 'id', $match->team1 );

	                                            $pfimage1 = get_user_meta( $match->team1, 'profile_photo', true );

	                                            if ( empty( $pfimage1 ) ) {
		                                            $pfimage1 = get_theme_file_uri( 'img/defaults/default-team.jpg' );
	                                            }

                                                ?>
                                                <img alt="img"
                                                     src="<?php echo esc_url($pfimage1); ?>">
                                                <div>
                                                    <small><?php echo esc_html( $gameabr ); ?></small>
                                                    <strong><?php echo esc_attr( $user1->display_name ); ?></strong>
                                                </div>
                                            <?php }else{ ?>
                                            <img alt="img"
                                                 src="<?php echo esc_url( arcane_return_team_image( $match->team1 ) ); ?>">
                                            <div>
                                                <small><?php echo esc_html( $gameabr ); ?></small>
                                                <strong><?php echo get_the_title( $match->team1 ); ?></strong>
                                            </div>
                                            <?php } ?>

											<?php if ( $match->status == 'done' ) { ?>

                                                <strong>
													<?php $r1 = $t1 == null ? '0' : $t1;
													echo esc_attr( $r1 ); ?>
                                                </strong>

											<?php } else { ?>

												<?php if ( $admin && $substatus ) { ?>
                                                    <strong>
														<?php $r1 = $t1 == null ? '0' : $t1;
														echo esc_attr( $r1 ); ?>
                                                    </strong>
												<?php } else { ?>
                                                    <strong>0</strong>
												<?php } ?>

											<?php } ?>
                                        </div>
                                        <div class="mw-mid">

                                        <?php
                                        if ( $is_upcoming ) :
                                            echo '<span class="upcoming">';
	                                        esc_html_e( 'Upcoming', 'arcane' );
	                                        echo '</span>';
                                        elseif ( $is_playing ) :
	                                        echo '<span  class="playing">';
	                                        esc_html_e( 'Playing', 'arcane' );
	                                        echo '</span>';
                                        else :
	                                        echo '<span  class="finished">';
	                                        esc_html_e( 'Finished', 'arcane' );
	                                        echo '</span>';
                                        endif;
                                        ?>

                                            <small>
												<?php
												if ( isset( $match->date_unix ) && ! empty( $match->date_unix ) ) {
													$timezone_string     = arcane_timezone_string();
													$tournament_timezone = $timezone_string ? $timezone_string : 'UTC';

													$currentTime = \DateTime::createFromFormat( 'U', $match->date_unix );
													$currentTime->setTimeZone( new \DateTimeZone( $tournament_timezone ) );
													$formattedString = $currentTime->format( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) );
													echo esc_attr( $formattedString );

												} else {
													echo esc_html( $date );
												}
												?>
                                            </small>
                                        </div>
                                        <div class="mw-right">
											<?php if ( $match->status == 'done' ) { ?>

                                                <strong>
													<?php $r2 = $t2 == null ? '0' : $t2;
													echo esc_attr( $r2 ); ?>
                                                </strong>

											<?php } else { ?>

												<?php if ( $admin && $substatus ) { ?>
                                                    <strong>
														<?php $r2 = $t2 == null ? '0' : $t2;
														echo esc_attr( $r2 ); ?>
                                                    </strong>
												<?php } else { ?>
                                                    <strong>0</strong>
												<?php } ?>

											<?php } ?>

					                        <?php if($is_user_type){ ?>
                                            <?php
                                            $user2    = get_user_by( 'id', $match->team2 );
                                            $pfimage2 = get_user_meta( $match->team2, 'profile_photo', true );

                                            if ( empty( $pfimage2 ) ) {
	                                            $pfimage2 = get_theme_file_uri( 'img/defaults/default-team.jpg' );
                                            }

						                        ?>
                                            <div>
                                                <small><?php echo esc_html( $gameabr ); ?></small>
                                                <strong><?php echo esc_attr( $user2->display_name ); ?></strong>
                                            </div>
                                            <img alt="img"
                                                 src="<?php echo esc_url( $pfimage2 ); ?>">
					                    <?php }else{ ?>
                                            <div>
                                                <small><?php echo esc_html( $gameabr ); ?></small>
                                                <strong><?php echo get_the_title( $match->team2 ); ?></strong>
                                            </div>
                                            <img alt="img"
                                                 src="<?php echo esc_url( arcane_return_team_image( $match->team2 ) ); ?>">

					                        <?php } ?>

                                        </div>
                                    </div>
                                </a>
                            </li>
						<?php } ?>
					<?php } ?>

                </ul>
            </div>
			<?php
		} else { ?>
            <div class="wcontainer">
                <ul class="gamesb">
                    <p class="no-matches-yet"><?php esc_html_e( 'This team doesn\'t have any matches yet!', 'arcane' ); ?></p>
                </ul>
            </div>
		<?php }

	}

}