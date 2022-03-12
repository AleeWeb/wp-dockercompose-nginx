<?php
/**
 * Widget Name: Tournament Carousel
 * Description: A Tournament Carousel widget.
 * Version: 1.0
 */

class Arcane_Tournament_Carousel_Widget extends WP_Widget {


	function __construct() {
		$widget_ops = array( 'classname'   => 'arcane_tournament_carousel_widget',
		                     'description' => esc_html__( 'Displays tournaments carousel', 'arcane' )
		);
		parent::__construct( 'Arcane_Tournament_Carousel_Widget', esc_html__( 'SW Tournament Carousel', 'arcane' ), $widget_ops );

		$this->default_settings = array( 'title'                  => esc_html__( 'Tournament Carousel', 'arcane' ),
		                                 'visible_games'          => array(),
		                                 'number'                 => '5',
		                                 'speed'                  => '8000',
		                                 'tournament_contestants' => 'all',
		                                 'tournament_format'      => 'all',
		                                 'tournament_type'        => 'all'
		);

	}


	function widget( $args, $instance ) {
		global $ArcaneWpTeamWars;
		$arcane_allowed = wp_kses_allowed_html( 'post' );
		extract( $args );

		$instance               = wp_parse_args( (array) $instance, $this->default_settings );
		$title                  = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'] );
		$tournament_contestants = $instance['tournament_contestants'];
		$tournament_type        = $instance['tournament_type'];
		$tournament_format      = $instance['tournament_format'];
		$games                  = $instance['visible_games'];
		$random_id              = rand();
		$options                = arcane_get_theme_options();
		if ( ! isset( $options['premium_button_text'] ) ) {
			$options['premium_button_text'] = esc_html__( "Buy premium", 'arcane' );
		}
		if ( ! isset( $options['premium_pending_button_text'] ) ) {
			$options['premium_pending_button_text'] = esc_html__( "Premium pending", 'arcane' );
		}
		?>

		<?php echo wp_kses( $before_widget, $arcane_allowed ); ?>
        <div class="nextmatch_widget">
			<?php if ( ! empty( $title ) ) {
				echo wp_kses( $before_title . $title . $after_title, $arcane_allowed );
			}
			?>

            <div id="tournamentCarousel<?php echo esc_attr( $random_id ); ?>"
                 class="tournamentCarousel carousel slide carousel-fade" data-ride="carousel"
                 data-interval="<?php echo esc_attr( $instance['speed'] ); ?>">

                    <ul class="tournaments-list carousel-inner" role="listbox">
					<?php
					$game_names =  [];

					foreach ( $games as $game ) {

						$g = $ArcaneWpTeamWars->get_game( 'id=' . $game );
						if ( isset( $g[0]->title ) ) {
							array_push( $game_names, $g[0]->title );
						}
					}


					if ( ( isset( $tournament_contestants ) && ! empty( $tournament_contestants ) && $tournament_contestants != 'all' ) && $tournament_format == 'all' ) {

						$meta_query = array(
							'relation' => 'AND',
							array(
								'key'     => 'tournament_game',
								'value'   => $game_names,
								'compare' => 'IN',
							),
							array(
								'key'     => 'tournament_contestants',
								'value'   => $tournament_contestants,
								'compare' => 'LIKE',
							)
						);

					} elseif ( ( isset( $tournament_format ) && ! empty( $tournament_format ) && $tournament_format != 'all' ) && $tournament_contestants == 'all' ) {
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

					} elseif ( ( isset( $tournament_format ) && ! empty( $tournament_format ) && $tournament_format != 'all' ) && ( isset( $tournament_contestants ) && ! empty( $tournament_contestants ) && $tournament_contestants != 'all' ) ) {
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
							),
							array(
								'key'     => 'tournament_contestants',
								'value'   => $tournament_contestants,
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


					$args = array(
						'order'          => 'DESC',
						'posts_per_page' => $instance['number'],
						'post_type'      => 'tournament',
						'orderby'        => 'meta_value',
						'meta_query'     => $meta_query
					);

					$pc       = new WP_Query( $args );
					$i        = 0;
					$none     = true;

					$games_tournaments =  $ArcaneWpTeamWars->get_games( $games );

					if ( $pc->have_posts() ) : while ( $pc->have_posts() ) : $pc->the_post();

						global $post;

						echo arcane_return_tournament_block( $post->ID, $games_tournaments, false );

					$i ++; endwhile; ?>
					<?php else : ?>
						<?php echo '<div class="nextmatch_wrap item match-carousel-cover-box active"><span class="error">';
						esc_html_e( 'No active tournaments', 'arcane' );
						echo '</span></div>';
						$none = false; ?>
					<?php endif;
					wp_reset_postdata(); ?>

                    </ul>

				<?php if ( $none ) { ?>
                    <a class="left3 carousel-control" href="#tournamentCarousel<?php echo esc_attr( $random_id ); ?>"
                       role="button" data-slide="prev">
                        <span class="fas fa-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only"><?php esc_html_e( "Previous", "arcane" ); ?></span>
                    </a>
                    <a class="right3 carousel-control" href="#tournamentCarousel<?php echo esc_attr( $random_id ); ?>"
                       role="button" data-slide="next">
                        <span class="fas fa-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only"><?php esc_html_e( "Next", "arcane" ); ?></span>
                    </a>
				<?php } ?>
            </div>

        </div>
		<?php echo wp_kses( $after_widget, $arcane_allowed ); ?>

		<?php
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance                           = $old_instance;
		$instance['title']                  = esc_attr( $new_instance['title'] );
		$instance['visible_games']          = $new_instance['visible_games'];
		$instance['tournament_contestants'] = $new_instance['tournament_contestants'];
		$instance['speed']                  = $new_instance['speed'];
		$instance['number']                 = $new_instance['number'];
		$instance['tournament_type']        = $new_instance['tournament_type'];
		$instance['tournament_format']      = $new_instance['tournament_format'];

		return $new_instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		global $ArcaneWpTeamWars;
		$instance               = wp_parse_args( (array) $instance, $this->default_settings );
		$title                  = esc_attr( $instance['title'] );
		$visible_games          = $instance['visible_games'];
		$tournament_contestants = $instance['tournament_contestants'];
		$tournament_type        = $instance['tournament_type'];
		$tournament_format      = $instance['tournament_format'];
		$speed                  = $instance['speed'];
		$number                 = $instance['number'];
		$games                  = $ArcaneWpTeamWars->get_game( 'id=all&orderby=title&order=asc' );
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'arcane' ); ?></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   value="<?php echo esc_attr( $title ); ?>" type="text"/></p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'speed' ) ); ?>"><?php esc_html_e( 'Carousel speed in ms:', 'arcane' ); ?></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'speed' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'speed' ) ); ?>"
                   value="<?php echo esc_attr( $speed ); ?>" type="text"/></p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of tournaments to show:', 'arcane' ); ?></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
                   value="<?php echo esc_attr( $number ); ?>" type="text"/></p>

        <p><?php esc_html_e( 'Show games:', 'arcane' ); ?></p>
        <p class="widefat">
			<?php foreach ( $games as $item ) : ?>
                <label for="<?php echo esc_attr( $this->get_field_id( 'visible_games-' . $item->id ) ); ?>"><input
                            type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'visible_games' ) ); ?>[]"
                            id="<?php echo esc_attr( $this->get_field_id( 'visible_games-' . $item->id ) ); ?>"
                            value="<?php echo esc_attr( $item->id ); ?>" <?php checked( true, in_array( $item->id, $visible_games ) ); ?>/> <?php echo esc_html( $item->title ); ?>
                </label><br/>
			<?php endforeach; ?>
        </p>


        <p><?php esc_html_e( 'Tournament contestants:', 'arcane' ); ?></p>

        <select name="<?php echo esc_attr( $this->get_field_name( 'tournament_contestants' ) ); ?>"
                id="<?php echo esc_attr( $this->get_field_id( 'tournament_contestants' ) ); ?>" class="postform">
            <option <?php selected( $tournament_contestants, 'all' ); ?>
                    value="all"><?php esc_html_e( 'All', 'arcane' ); ?></option>
            <option <?php selected( $tournament_contestants, 'user' ); ?> class="level-0"
                                                                          value="user"><?php esc_html_e( 'User', 'arcane' ); ?></option>
            <option <?php selected( $tournament_contestants, 'team' ); ?> class="level-0"
                                                                          value="team"><?php esc_html_e( 'Team', 'arcane' ); ?></option>
        </select>


        <p><?php esc_html_e( 'Tournament type:', 'arcane' ); ?></p>

        <select name="<?php echo esc_attr( $this->get_field_name( 'tournament_type' ) ); ?>"
                id="<?php echo esc_attr( $this->get_field_id( 'tournament_type' ) ); ?>" class="postform">
            <option <?php selected( $tournament_type, 'all' ); ?>
                    value="all"><?php esc_html_e( 'All', 'arcane' ); ?></option>
            <option <?php selected( $tournament_type, 'scheduled' ); ?> class="level-0"
                                                                        value="scheduled"><?php esc_html_e( 'Scheduled', 'arcane' ); ?></option>
            <option <?php selected( $tournament_type, 'started' ); ?> class="level-0"
                                                                      value="started"><?php esc_html_e( 'Started', 'arcane' ); ?></option>
            <option <?php selected( $tournament_type, 'finished' ); ?> class="level-0"
                                                                       value="finished"><?php esc_html_e( 'Finished', 'arcane' ); ?></option>
            <option <?php selected( $tournament_type, 'premium' ); ?> class="level-0"
                                                                      value="premium"><?php esc_html_e( 'Premium', 'arcane' ); ?></option>
        </select>


        <p><?php esc_html_e( 'Tournament format:', 'arcane' ); ?></p>

        <select name="<?php echo esc_attr( $this->get_field_name( 'tournament_format' ) ); ?>"
                id="<?php echo esc_attr( $this->get_field_id( 'tournament_format' ) ); ?>"
                class="postform">


            <option <?php selected( $tournament_format, 'all' ); ?>
                    value="all"><?php esc_html_e( 'All', 'arcane' ); ?>
            </option>

            <?php
            $arcane_tournament_mapping = arcane_tournament_mapping();

            if($arcane_tournament_mapping)
            foreach ($arcane_tournament_mapping as $key => $item){
                echo '<option '.selected( $tournament_format, $key ).' 
                        class="level-0"
                        value="'.esc_attr($key).'">'.esc_html($item).'
                </option>';
            } ?>
        </select>
		<?php
	}

}

function arcane_return_tournament_carousel_widget() {
	register_widget( "Arcane_Tournament_Carousel_Widget" );
}

// register widget
add_action( 'widgets_init', 'arcane_return_tournament_carousel_widget' );
?>