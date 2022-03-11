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
class Widget_SW_Games extends Widget_Base {


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
		return 'sw_games';
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
		return esc_html__( 'Arcane - Games', 'arcane' );
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
		return 'eicon-nerd';
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
		return [ 'games' ];
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
		$games    = $settings['games'];
		?>

		<?php if ( ! empty( $title ) ) { ?>
            <h3 class="block-title" style="text-align: <?php echo esc_attr( $position ); ?>">
				<?php echo esc_html( $title ); ?>
            </h3>
		<?php } ?>

		<?php if ( ! empty( $games ) ) { ?>
            <ul class="game-list">
				<?php foreach ( $games as $game ) { ?>
                    <li>
                        <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'all-teams-for-game' ) ) . "?gid=" . $game ); ?>">
							<?php $name = arcane_return_game_name_by_game_id( $game ); ?>
                            <span><?php echo esc_html( $name ); ?></span>
							<?php
							$img   = arcane_return_game_image_nocrop( $game );
							$image = arcane_aq_resize( $img, 310, 215, true, true, true ); //resize & crop img

							if ( $image ) { ?>
                                <img alt="<?php echo esc_attr( $name ); ?>" src="<?php echo esc_url( $image ); ?>"/>
							<?php } ?>
                        </a>
                    </li>
				<?php } ?>
            </ul>
		<?php } ?>
		<?php
	}

}