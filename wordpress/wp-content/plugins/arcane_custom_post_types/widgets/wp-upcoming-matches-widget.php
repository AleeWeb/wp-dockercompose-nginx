<?php

class Arcane_Upcoming_Matches_Widget extends WP_Widget {

	var $default_settings = array();

	function __construct() {
		$widget_ops = array( 'classname'   => 'widget_other_matches',
		                     'description' => esc_html__( 'Upcoming Matches widget', 'arcane' )
		);
		parent::__construct( 'upcoming_matches', esc_html__( 'SW Upcoming Matches', 'arcane' ), $widget_ops );

		$this->default_settings = array(
			'title'           => esc_html__( 'Upcoming Matches', 'arcane' ),
			'show_limit'      => 10,
			'hide_older_than' => '1m',
			'visible_games'   => array()
		);


	}

	function current_time_fixed( $type, $gmt = 0 ) {
		$t = ( $gmt ) ? gmdate( 'Y-m-d H:i:s' ) : gmdate( 'Y-m-d H:i:s', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) );
		switch ( $type ) {
			case 'mysql':
				return $t;
				break;
			case 'timestamp':
				return strtotime( $t );
				break;
		}
	}

	function widget( $args, $instance ) {
		global $ArcaneWpTeamWars;
		$arcane_allowed = wp_kses_allowed_html( 'post' );
		extract( $args );

		$now = $this->current_time_fixed( 'timestamp' );

		$instance = wp_parse_args( (array) $instance, $this->default_settings );

		$title      = apply_filters( 'widget_title', $instance['title'] );
		$show_limit = isset( $instance['show_limit'] ) ? absint( $instance['show_limit'] ) : 10;

		$matches = array();
		$games   = array();
		$_games  = $ArcaneWpTeamWars->get_game( array(
			'id'      => empty( $instance['visible_games'] ) ? 'all' : $instance['visible_games'],
			'orderby' => 'title',
			'order'   => 'asc'
		) );

		foreach ( $_games as $g ) {

			$m = $ArcaneWpTeamWars->get_match( array( 'status'      => array( 'active' ),
			                                          'from_date'   => $now,
			                                          'game_id'     => $g->id,
			                                          'limit'       => $show_limit,
			                                          'order'       => 'desc',
			                                          'orderby'     => 'date',
			                                          'sum_tickets' => true
			), false, true );

			if ( sizeof( $m ) ) {
				$games[] = $g;
				$matches = array_merge( $matches, $m );
			}
		}

		usort( $matches, 'arcane_upcoming_matches_sort' );

		echo wp_kses( $before_widget, $arcane_allowed );

		if ( $title ) {
			echo wp_kses( $before_title . $title . $after_title, $arcane_allowed );
		}
		?>

        <div class="teamwar-list">

            <ul class="matches-tab">
				<?php
				if ( count( $games ) > 1 ) {
					$obj        = new stdClass();
					$obj->id    = 0;
					$obj->title = esc_html__( 'All', 'arcane' );
					$obj->abbr  = esc_html__( 'All', 'arcane' );
					$obj->icon  = 0;

					array_unshift( $games, $obj );

				}

				for ( $i = 0; $i < sizeof( $games ); $i ++ ) :
					$game = $games[ $i ];
					$link = ( $game->id == 0 ) ? 'all' : $game->id;
					$p = array( 'game_id' => $game->id, 'status' => array( 'active' ) );
					$matches_tab = $ArcaneWpTeamWars->get_match( $p, false, true );

					if ( ! empty( $matches_tab ) ) {
						?>
                        <li <?php if ( $i == 0 ) {
							echo 'class="active"';
						} ?>><a class="custom-tabs-link" href="#<?php echo esc_attr( $link ); ?>"
                                title="<?php echo esc_attr( $game->title ); ?>"><?php echo esc_attr( $game->abbr ); ?></a>
                        </li>
					<?php } endfor; ?>
            </ul>

            <ul class="matches-wrapper">
				<?php foreach ( $matches as $i => $match ) :

					if ( $match->status == 'active' ) {

						$timestamp = $match->date_unix;
						$date      = date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ),  $timestamp);

						$gameid = $match->game_id;

						$team1id = $match->team1;
						$team2id = $match->team2;

						$pid  = $match->ID;
						$type = get_post_meta( $pid, 'tournament_participants', true );
						if ( $type == 'user' ) {
							$pf_url = get_user_meta( $team1id, 'profile_photo', true );
							if ( ! empty( $pf_url ) ) {
								$image1 = arcane_aq_resize( $pf_url, 25, 25, true, true, true );
							} else {
								$image1 = get_theme_file_uri( 'img/defaults/default-team.jpg' );
							}

							$pf_url2 = get_user_meta( $team2id, 'profile_photo', true );
							if ( ! empty( $pf_url2 ) ) {
								$image2 = arcane_aq_resize( $pf_url2, 25, 25, true, true, true );

							} else {
								$image2 = get_theme_file_uri( 'img/defaults/default-team.jpg' );
							}
						} else {
							$team1id  = $match->team1;
							$img_url1 = get_post_meta( $team1id, 'team_photo', true );
							$image1   = arcane_aq_resize( $img_url1, 25, 25, true, true, true );

							$team2id  = $match->team2;
							$img_url2 = get_post_meta( $team2id, 'team_photo', true );
							$image2   = arcane_aq_resize( $img_url2, 25, 25, true, true, true );
						}

						$gameabr = arcane_return_game_abbr( $gameid );


						?>
                        <li data-type="#<?php echo esc_attr( $match->game_id ); ?>"
                            class="teamwar-item">

                            <div class="wrap">
								<?php echo '<a href="' . esc_url( get_permalink( $match->ID ) ) . '" data-tooltip="' . esc_attr( $match->title ) . '">'; ?>

                                <div class="upcoming"><?php esc_html_e( 'Upcoming', 'arcane' ); ?></div>

								<?php if ( ! isset( $image1 ) or empty( $image1 ) ) {
									$image1 = get_theme_file_uri('img/defaults/team25x25.jpg') ;
								} ?>
								<?php if ( ! isset( $image2 ) or empty( $image2 ) ) {
									$image2 = get_theme_file_uri('img/defaults/team25x25.jpg') ;
								} ?>
                                <div class="match-wrap">

                                    <img src="<?php echo esc_url( $image1 ); ?>" class="team1img"
                                         alt="<?php echo esc_attr( $match->title ); ?>"/>

                                    <span class="vs"><?php esc_html_e( "vs", "arcane" ); ?></span>
                                    <div class="opponent-team">
                                        <img src="<?php echo esc_url( $image2 ); ?>" class="team1img"
                                             alt="<?php echo esc_attr( $match->title ); ?>"/>
                                    </div>
                                </div>
                                <div class="date"><?php echo esc_html( $gameabr ); ?>
                                    - <?php echo esc_html( $date ); ?></div>

                                </a>
                            </div>

                        </li>
					<?php } endforeach; ?>
            </ul>
        </div>

		<?php echo wp_kses( $after_widget, $arcane_allowed ); ?>

		<?php
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		global $ArcaneWpTeamWars;

		$instance = wp_parse_args( (array) $instance, $this->default_settings );

		$show_limit    = (int) $instance['show_limit'];
		$title         = esc_attr( $instance['title'] );
		$visible_games = $instance['visible_games'];

		$games = $ArcaneWpTeamWars->get_game( 'id=all&orderby=title&order=asc' );
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'arcane' ); ?></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   value="<?php echo esc_attr( $title ); ?>" type="text"/></p>


        <p><?php esc_html_e( 'Show games:', 'arcane' ); ?></p>
        <p class="widefat">
			<?php foreach ( $games as $item ) : ?>
                <label for="<?php echo esc_attr( $this->get_field_id( 'visible_games-' . $item->id ) ); ?>"><input
                            type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'visible_games' ) ); ?>[]"
                            id="<?php echo esc_attr( $this->get_field_id( 'visible_games-' . $item->id ) ); ?>"
                            value="<?php echo esc_attr( $item->id ); ?>" <?php checked( true, in_array( $item->id, $visible_games ) ); ?>/> <?php echo esc_attr( $item->title ); ?>
                </label><br/>
			<?php endforeach; ?>
        </p>
        <p class="description"><?php esc_html_e( 'Do not check any game if you want to show all games.', 'arcane' ); ?></p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_limit' ) ); ?>"><?php esc_html_e( 'Show matches:', 'arcane' ); ?></label>
            <input class="cetpet" name="<?php echo esc_attr( $this->get_field_name( 'show_limit' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'show_limit' ) ); ?>"
                   value="<?php echo esc_attr( $show_limit ); ?>" type="text"/></p>

		<?php
	}
}


function arcane_return_upcoming_matches_widget() {
	register_widget( "Arcane_Upcoming_Matches_Widget" );
}

// register widget
add_action( 'widgets_init', 'arcane_return_upcoming_matches_widget' );
?>