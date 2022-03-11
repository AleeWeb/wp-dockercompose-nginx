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
 * Class Widget_SW_Tournaments
 * @package Elementor
 */
class Widget_SW_Tournaments extends Widget_Base {


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
		return 'sw_tournaments';
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
		return esc_html__( 'Arcane - Tournaments', 'arcane' );
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
		return 'eicon-rating';
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
		return [ 'tournaments', 'team wars', 'team' ];
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
				'label'   => esc_html__( ' Title', 'arcane' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
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


		if ( function_exists( 'arcane_get_product_id_by_tournament_id' ) ) {
			$ttypes = [
				'all'       => esc_html__( 'All', 'arcane' ),
				'scheduled' => esc_html__( 'Scheduled', 'arcane' ),
				'started'   => esc_html__( 'Started', 'arcane' ),
				'finished'  => esc_html__( 'Finished', 'arcane' ),
				'premium'   => esc_html__( 'Premium', 'arcane' )
			];
		} else {
			$ttypes = [
				'all'       => esc_html__( 'All', 'arcane' ),
				'scheduled' => esc_html__( 'Scheduled', 'arcane' ),
				'started'   => esc_html__( 'Started', 'arcane' ),
				'finished'  => esc_html__( 'Finished', 'arcane' )
			];
		}

		$this->add_control(
			'tournament_type',
			[
				'label'   => esc_html__( 'Type of tournaments to show', 'arcane' ),
				'type'    => 'select',
				'options' => $ttypes,
				'title'   => esc_html__( 'Select', 'arcane' ),
			]
		);


		$this->add_control(
			'tournament_format',
			[
				'label'   => esc_html__( 'Tournament format', 'arcane' ),
				'type'    => 'select',
				'options' => arcane_tournament_mapping(),
				'title'   => esc_html__( 'Select', 'arcane' ),
			]
		);


		$this->add_control(
			'tournament_sort',
			[
				'label'   => esc_html__( 'Sort tournaments', 'arcane' ),
				'type'    => 'select',
				'options' => [
					'tip'   => esc_html__( 'Type', 'arcane' ),
					'cont'  => esc_html__( 'Contestants', 'arcane' ),
					'game'  => esc_html__( 'Game', 'arcane' ),
					'rand'  => esc_html__( 'Random', 'arcane' ),
					'start' => esc_html__( 'Start time', 'arcane' )
				],
				'title'   => esc_html__( 'Select', 'arcane' ),
			]
		);

		$this->add_control(
			'tournament_sort_type',
			[
				'label'   => esc_html__( 'Sort type', 'arcane' ),
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

		$this->add_control(
			'number_columns',
			[
				'label'   => esc_html__( 'Number of columns', 'arcane' ),
				'type'    => 'select',
				'options' => [
					'one' => esc_html__( 'One', 'arcane' ),
					'two' => esc_html__( 'Two', 'arcane' ),
				],
				'title'   => esc_html__( 'Add number of columns', 'arcane' ),
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

		//Get values (in array "settings")
		$settings = $this->get_settings();

		global $ArcaneWpTeamWars;

		$title                = $settings['title'];
		$position             = $settings['position'];
		$t_games              = $settings['games'];
		$tournament_sort      = $settings['tournament_sort'];
		$tournament_sort_type = $settings['tournament_sort_type'];
		$tournament_format    = $settings['tournament_format'];
		$number_columns       = $settings['number_columns'];

		$column_class = 'tsingle-column';

		if ( $number_columns == 'two' ) {
			$column_class = 'tdouble-column';
		}
		?>

        <div id="tournaments" class="tab-pane teampage-tournaments <?php echo esc_attr( $column_class ); ?>">

			<?php if ( ! empty( $title ) ) { ?>
                <h3 class="block-title" style="text-align: <?php echo esc_attr( $position ); ?>">
					<?php echo esc_html( $title ); ?>
                </h3>
			<?php } ?>

            <ul class="tournaments-list">

				<?php
				$args = $game_names = [];

				foreach ( $t_games as $game ) {
					$g = $ArcaneWpTeamWars->get_game( 'id=' . $game );
					if ( isset( $g[0]->title ) ) {
						array_push( $game_names, $g[0]->title );
					}
				}

				if ( isset( $tournament_format ) && ! empty( $tournament_format ) && $tournament_format != 'all' ) {
					$meta_query = array(
						'relation' => 'AND',
						array(
							'key'     => 'tournament_game',
							'value'   => $game_names,
							'compare' => 'IN',
						),
						array(
							'key'     => 'format',
							'value'   => $tournament_format,
							'compare' => 'LIKE',
						)
					);

				} else {
					$meta_query = array(
						array(
							'key'     => 'tournament_game',
							'value'   => $game_names,
							'compare' => 'IN',
						)
					);
				}

				if ( $tournament_sort == 'start' || $tournament_sort == '' ) {
					$args = array(
						'post_type'      => 'tournament',
						'posts_per_page' => - 1,
						'order'          => $tournament_sort_type,
						'orderby'        => 'meta_value',
						'meta_key'       => 'tournament_starts_unix',
						'meta_query'     => $meta_query
					);

				} elseif ( $tournament_sort == 'tip' ) {
					$args = array(
						'post_type'      => 'tournament',
						'posts_per_page' => - 1,
						'order'          => $tournament_sort_type,
						'orderby'        => 'meta_value',
						'meta_key'       => 'format',
						'meta_query'     => $meta_query
					);
				} elseif ( $tournament_sort == 'cont' ) {
					$args = array(
						'post_type'      => 'tournament',
						'posts_per_page' => - 1,
						'order'          => $tournament_sort_type,
						'orderby'        => 'meta_value',
						'meta_key'       => 'tournament_contestants',
						'meta_query'     => $meta_query
					);
				} elseif ( $tournament_sort == 'game' ) {
					$args = array(
						'post_type'      => 'tournament',
						'posts_per_page' => - 1,
						'order'          => $tournament_sort_type,
						'orderby'        => 'meta_value',
						'meta_key'       => 'tournament_game',
						'meta_query'     => $meta_query
					);
				} elseif ( $tournament_sort == 'rand' ) {
					$args = array(
						'post_type'      => 'tournament',
						'posts_per_page' => - 1,
						'order'          => $tournament_sort_type,
						'orderby'        => 'rand',
						'meta_query'     => $meta_query
					);
				}


				$the_query = new \WP_Query( $args );

				$found = false;

				if ( $the_query->have_posts() ) {

					$games = $ArcaneWpTeamWars->get_game( '' );

					$i = 1;

					while ( $the_query->have_posts() ) {
						$the_query->the_post();

						global $post;

						$tournament_timezone = get_post_meta( $post->ID, 'tournament_timezone', true );
						$cur_time            = new \DateTime( "now", new \DateTimeZone( $tournament_timezone ) );
						$cur_time            = $cur_time->getTimestamp();



						if ( $settings['tournament_type'] == 'scheduled' ) {

							$t_start_unix = get_post_meta( $post->ID, "tournament_starts_unix", true );

							if ( isset( $t_start_unix ) ) {
								$tournament_starts = $t_start_unix;
							} else {
								$t_start_meta      = get_post_meta( $post->ID, 'tournament_starts', true );
								$tournament_starts = strtotime( $t_start_meta );
							}


							if ( $tournament_starts <= $cur_time ) {
								continue;
							}


						} elseif ( $settings['tournament_type'] == 'started' ) {

							$t_start_unix = get_post_meta( $post->ID, "tournament_starts_unix", true );

							if ( isset( $t_start_unix ) ) {
								$tournament_starts = $t_start_unix;
							} else {
								$t_start_meta      = get_post_meta( $post->ID, 'tournament_starts', true );
								$tournament_starts = strtotime( $t_start_meta );
							}

							/*for finished check*/
							$rounds    = get_post_meta( $post->ID, 'game_cache', true );
							$game_stop = get_post_meta( $post->ID, 'game_stop', true );
							$tformat   = get_post_meta( $post->ID, 'format', true );

							if ( strtolower( $tformat ) == 'ladder' ) {
								if ( $game_stop == 1 ) {
									$finished = true;
								}

							} elseif ( ! empty( $rounds ) ) {
								$finished = true;

								if ( strtolower( $tformat ) == 'ladder' ) {
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

									$playoffs_started = get_post_meta( $post->ID, 'playoffs_started', true );
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

							if ( $tournament_starts >= $cur_time || $finished ) {
								continue;
							}

						} elseif ( $settings['tournament_type'] == 'finished' ) {

							$rounds    = get_post_meta( $post->ID, 'game_cache', true );
							$game_stop = get_post_meta( $post->ID, 'game_stop', true );
							$tformat   = get_post_meta( $post->ID, 'format', true );


							if ( strtolower( $tformat ) == 'ladder' ) {
								$finished = false;
								if ( $game_stop == 1 ) {
									$finished = true;
								}

							} elseif ( ! empty( $rounds ) ) {
								$finished = true;

								if ( strtolower( $tformat ) == 'ladder' ) {
									if ( $game_stop == 1 ) {
										$finished = true;
									}

								} elseif ( ! empty( $rounds ) ) {
									$finished = true;

									if ( strtolower( $tformat ) == 'ladder' ) {
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

										$playoffs_started = get_post_meta( $post->ID, 'playoffs_started', true );
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

							} else {
								$finished = false;
							}

							if ( ! $finished ) {
								continue;
							}

						} elseif ( $settings['tournament_type'] == 'premium' ) {
							$premium = get_post_meta( $post->ID, 'premium', true );
							if ( ! $premium ) {
								continue;
							}

						}


						$found = true;
						echo arcane_return_tournament_block( $post->ID, $games, false );
						if ( $i == $settings['number'] ) {
							break;
						}
						$i ++;
					}

				} else { ?>
                    <p class="no-tour-games">
						<?php echo esc_html__( "There are no active tournaments for selected games.", "arcane" ); ?>
                    </p>
					<?php

                    $found = true;
				}

				if(!$found){
					?>
                    <p class="no-tour-games">
						<?php echo esc_html__( "There are no active tournaments.", "arcane" ); ?>
                    </p>
					<?php
                }
				?>
            </ul>

        </div>
		<?php
	}
}