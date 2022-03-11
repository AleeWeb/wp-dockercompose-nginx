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
class Widget_SW_News_Main extends Widget_Base
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
        return 'sw_news_main';
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
        return esc_html__('Arcane - News Main Block', 'arcane');
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
        return 'eicon-info-box';
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
        return ['post', 'posts', 'news'];
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
			    'label' => esc_html__(' Title', 'arcane'),
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

	    $categories = get_categories(

		    array(
			    'type' => 'post',
			    'orderby' => 'name',
			    'order' => 'ASC',
			    'hide_empty' => 1,
			    'taxonomy' => 'category',

		    ));

	    foreach ($categories as $cat) {
		    $cats[$cat->cat_name] = $cat->cat_name;
	    }
	    if (!isset($cats)) $cats = '';

	    $this->add_control(
		    'categories',
		    [
			    'label' => esc_html__('Categories', 'arcane'),
			    'type' => 'multiselect',
			    'options' => $cats,
			    'title' => esc_html__('Select categories', 'arcane'),
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
	    $categories = $settings['categories'];

	    $ct = [];

	    if (is_array($categories)) {
		    foreach ($categories as $category) {
			    $cat_id = get_cat_ID($category);
			    array_push($ct, $cat_id);
		    }
	    }

	    $posts = new \WP_Query(array(
            'post_type' => 'post',
		    'post_status' => 'publish',
		    'posts_per_page' => 5,
            'ignore_sticky_posts' => 1,
		    'category__in' => $ct
	    ));

        ?>

	    <?php if ( ! empty( $title ) ) { ?>
        <h3 class="block-title" style="text-align: <?php echo esc_attr( $position ); ?>">
		    <?php echo esc_html( $title ); ?>
        </h3>
        <?php } ?>

        <ul class="news-main-list">

	    <?php while ($posts->have_posts()) : $posts->the_post(); ?>

		    <?php

            $background_image = '';
            if (has_post_thumbnail()) {
			    $thumb = get_post_thumbnail_id();
			    $img_url = wp_get_attachment_url($thumb); //get img URL
	            $background_image = "background-image:url(".esc_url($img_url).")";
            }

		    global $post;
		    $categories = wp_get_post_categories($post->ID);
            ?>

            <li style="<?php echo esc_attr($background_image); ?>">
                <a href="<?php the_permalink(); ?>"  >
                    <span><?php echo get_cat_name($categories[0]); ?></span>
                    <small><?php the_time(get_option('date_format')); ?></small>
                    <strong><?php the_title(); ?></strong>
                    <div class="nm-main">
                        <span><i class="far fa-user"></i> <?php the_author(); ?></span>
                        <span><i class="far fa-comment-alt"></i>  <?php comments_number('0', '1', '%') ?></span>
                    </div>
                </a>
            </li>
	    <?php endwhile;
	    wp_reset_postdata(); ?>
        </ul>

        <?php
    }

}