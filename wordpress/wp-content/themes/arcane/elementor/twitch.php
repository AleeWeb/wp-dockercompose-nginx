<?php

namespace Elementor;


if (!defined('ABSPATH')) {
	exit;
} // Exit if accessed directly


/**
 * News.
 *
 * Elementor widget that displays set of posts in different layouts.
 *
 * Class Widget_SW_News_Main
 * @package Elementor
 */
class Widget_SW_Twitch extends Widget_Base
{


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

	public function get_name()
	{
		return 'sw_twitch';
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

	public function get_title()
	{
		return esc_html__('Arcane - Twitch', 'arcane');
	}


	/**
	 * Get widget category
	 * @return array
	 */

	public function get_categories()
	{
		return ['skywarrior'];
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

	public function get_icon()
	{
		// Icon name from the Elementor font file, as per https://pojome.github.io/elementor-icons/
		return 'eicon-video-camera';
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

	public function get_keywords()
	{
		return ['twitch', 'social'];
	}


	/**
	 * Register news widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */

	protected function _register_controls()
	{

		$this->start_controls_section(
			'section_posts_list_content',
			[
				'label' => esc_html__('Content', 'arcane'),
			]
		);


		$this->add_control(
			'title',
			[
				'label' => esc_html__('Title', 'arcane'),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__('Add title text', 'arcane'),
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

		$this->add_control(
			'channel',
			[
				'label' => esc_html__('Channel', 'arcane'),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__('Add channel name', 'arcane'),
			]
		);

		$this->add_control(
			'channel_mute',
			[
				'label' => esc_html__('Mute', 'arcane'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'arcane' ),
				'label_off' => esc_html__( 'No', 'arcane' ),
				'default' => '',
				'title' => esc_html__('Mute channel', 'arcane'),
			]
		);

		$this->add_control(
			'height',
			[
				'label' => esc_html__('Height', 'arcane'),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__('Add player height', 'arcane'),
			]
		);

		$this->add_control(
			'width',
			[
				'label' => esc_html__('Width', 'arcane'),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__('Add player width', 'arcane'),
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

	protected function render()
	{

		//Get values (in array "settings")
		$settings = $this->get_settings();
		$title    = $settings['title'];
		$position = $settings['position'];
		$channel = $settings['channel'];
		$height = $settings['height'];
		$width = $settings['width'];

		$mute = 'false';
		if($settings['channel_mute'] == 'yes')
			$mute = 'true';

		$parent = parse_url(get_site_url());
		?>
		<?php if ( ! empty( $title ) ) { ?>
		<h3 class="block-title" style="text-align: <?php echo esc_attr( $position ); ?>">
			<?php echo esc_html( $title ); ?>
		</h3>
		<?php } ?>

		<iframe
			src="https://player.twitch.tv/?channel=<?php echo esc_attr($channel); ?>&muted=<?php echo esc_attr($mute); ?>&parent=<?php echo esc_attr($parent['host']); ?>"
			height="<?php echo esc_attr($height); ?>"
			width="<?php echo esc_attr($width); ?>"
		>
		</iframe>

		<?php
	}

}