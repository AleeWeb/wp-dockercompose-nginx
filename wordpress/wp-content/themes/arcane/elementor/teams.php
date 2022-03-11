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
 * Class Widget_SW_Teams
 * @package Elementor
 */
class Widget_SW_Teams extends Widget_Base {


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
		return 'sw_teams';
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
		return esc_html__( 'Arcane - Teams', 'arcane' );
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
		return 'eicon-person';
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
		return [ 'post', 'posts', 'news' ];
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

		$this->add_control(
			'order_by',
			[
				'label'   => esc_html__( 'Order by', 'arcane' ),
				'type'    => 'select',
				'options' => [
					'date'   => esc_html__( 'Date created', 'arcane' ),
					'scores' => esc_html__( 'Scores', 'arcane' ),
					'random' => esc_html__( 'Random', 'arcane' ),
				],
				'title'   => esc_html__( 'Select', 'arcane' ),
			]
		);

		$this->add_control(
			'order_type',
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

		$title    = $settings['title'];
		$position = $settings['position'];
		$order_type = $settings['order_type'];
		$selected_games = $settings['games'];
		$order_by = $settings['order_by'];
		$number = $settings['number'];

		if(empty($selected_games))
			$selected_games = [];

		?>

		<?php if ( ! empty( $title ) ) { ?>
            <h3 class="block-title" style="justify-content: <?php echo esc_attr( $position ); ?>">
				<?php echo esc_html( $title ); ?>
            </h3>
		<?php } ?>

		<?php
		$args = array(
			'post_type'      => 'team',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => $order_type
		);

		$team_posts = get_posts( $args );


		if ( isset( $team_posts ) && is_array( $team_posts ) ) {
			foreach ( $team_posts as $key => $cln ) {

				$games = get_post_meta( $cln->ID, 'games', true );

				if ( empty( $games ) ) {
					unset( $team_posts[ $key ] );
				}

				if ( is_array( $games ) ) {
					$intersect = array_intersect( $games, $selected_games );
					if ( empty( $intersect ) ) {
						unset( $team_posts[ $key ] );
					}
				}

				$tid        = $cln->ID;
				$team_id    = $tid;
				$score      = arcane_return_team_win_lose_score( $team_id );
				$cln->score = $score;
			}

			if ( $order_by == 'scores' ) {
				usort( $team_posts, "arcane_sort_objects_by_score" );
			} elseif ( $order_by == 'random' ) {
				shuffle( $team_posts );
			} else {
				usort( $team_posts, "arcane_sort_objects_by_date" );
			}
		}


		if ( empty( $team_posts ) ) { ?>
            <div class="error_msg"><span><?php esc_html_e( 'There are no teams at the moment!', 'arcane' ); ?> </span>
            </div>
		<?php } else {

			$i = 0;
			if ( empty( $number ) ) {
				$number = 99999;
			} ?>

            <ul class="teams-list">

			<?php foreach ($team_posts as $team_post) {
				if($i == $number) break; ?>
                <li>
                    <a href="<?php echo get_permalink($team_post->ID); ?>">
	                    <?php $img = get_post_meta( $team_post->ID, 'team_photo', true );
	                    $image = arcane_aq_resize( $img, 63, 63, true, true, true );
	                    if(!$image){
		                    $image = get_theme_file_uri('img/defaults/default-team-50x50.jpg');
	                    }
	                    ?>
                        <img alt="team_img" src="<?php echo esc_url($image); ?>">
                        <div>
                            <strong><?php echo esc_attr($team_post->post_title); ?></strong>
	                        <?php $members = arcane_return_number_of_members($team_post->ID); ?>
                            <span>
                                <?php if($members == 1){
                                    echo esc_attr($members); ?>&nbsp;<?php esc_html_e('Member','arcane');
                                }else{
                                    echo esc_attr($members); ?>&nbsp;<?php esc_html_e('Members','arcane');
                                } ?>
                            </span>
                        </div>
                    </a>
                </li>
				<?php	$i++;
			} ?>
            </ul>

			<?php
		}

	}
}