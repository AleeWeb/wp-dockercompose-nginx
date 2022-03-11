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
class Widget_SW_Tournaments_Single extends Widget_Base {


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
		return 'sw_tournaments_single';
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
		return esc_html__( 'Arcane - Tournaments Single', 'arcane' );
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
		return 'eicon-star-o';
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

		$args        = array(
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_type'      => 'tournament',
			'post_status'    => 'publish',

		);
		$posts_array = get_posts( $args );
		$titles      = [];
		foreach ( $posts_array as $single_post ) {
			$titles[ $single_post->ID ] = $single_post->post_title;
		}
		wp_reset_postdata();

		$this->add_control(
			'tournament',
			[
				'label'   => esc_html__( 'Tournament', 'arcane' ),
				'type'    => 'select',
				'options' => $titles,
				'title'   => esc_html__( 'Select tournament', 'arcane' ),
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

		$title      = $settings['title'];
		$position   = $settings['position'];
		$tournament = $settings['tournament'];
		?>

        <div id="tournaments" class="tab-pane teampage-tournaments">

			<?php if ( ! empty( $title ) ) { ?>
                <h3 class="block-title" style="text-align: <?php echo esc_attr( $position ); ?>">
					<?php echo esc_html( $title ); ?>
                </h3>
			<?php } ?>
	        <ul class="tournaments-list">
				<?php
				if(!empty($tournament)) {
					$games = $ArcaneWpTeamWars->get_game( '' );
					echo arcane_return_tournament_block( $tournament, $games, false );
				}
				?>
	        </ul>
        </div>
		<?php
	}
}