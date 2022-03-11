<?php

namespace Elementor;


if (!defined('ABSPATH')) exit; // Exit if accessed directly


/**
 * Blog posts slider.
 *
 * Elementor widget that displays set of posts in different layounts.
 *
 * Class Widget_post_slider
 * @package Elementor
 */
class Widget_SW_Shop extends Widget_Base
{


    /**
     * Get widget name.
     *
     * Retrieve posts simple widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */

    public function get_name()
    {
        return 'sw_shop';
    }


    /**
     * Get widget title.
     *
     * Retrieve posts simple widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */

    public function get_title()
    {
        return esc_html__('SW - Shop', 'arcane');
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
     * Retrieve posts simple widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */

    public function get_icon()
    {
        // Icon name from the Elementor font file, as per https://pojome.github.io/elementor-icons/
        return 'eicon-cart-light';
    }


    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */

    public function get_keywords()
    {
        return ['post', 'posts'];
    }


    /**
     * Register posts simple widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */

    protected function _register_controls()
    {

        $this->start_controls_section(
            'section_shop_content',
            [
                'label' => esc_html__('Content', 'arcane'),
            ]
        );

        $categories = get_categories(

            array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => 1,
                'taxonomy' => 'product_cat',
            ));


        foreach ($categories as $cat) {
            $cats[$cat->cat_ID] = $cat->cat_name;
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


        $this->add_control(
            'show',
            ['label' => esc_html__('Show', 'arcane'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'all',
                'options' => array(
                    'all' => esc_html__('All products', 'arcane'),
                    'featured' => esc_html__('Featured products', 'arcane'),
                    'onsale' => esc_html__('On-sale products', 'arcane'),
                ),
            ]
        );

        $this->add_control(
            'orderby',
            ['label' => esc_html__('Order by', 'arcane'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => array(
                    'date' => esc_html__('Date', 'arcane'),
                    'price' => esc_html__('Price', 'arcane'),
                    'rand' => esc_html__('Random', 'arcane'),
                    'sales' => esc_html__('Sales', 'arcane'),
                ),
            ]
        );

        $this->add_control(
            'order',
            ['label' => esc_html__('Order', 'arcane'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'asc',
                'options' => array(
                    'asc' => esc_html__('ASC', 'arcane'),
                    'desc' => esc_html__('DESC', 'arcane'),
                ),
            ]
        );

        $this->add_control(
            'hide_free',
            [
                'label' => esc_html__('Hide free products', 'arcane'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'arcane'),
                'label_off' => esc_html__('No', 'arcane'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_hidden',
            [
                'label' => esc_html__('Show hidden products', 'arcane'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'arcane'),
                'label_off' => esc_html__('No', 'arcane'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'section_shop_style',
            [
                'label' => esc_html__('Style', 'arcane'),
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => esc_html__('Number of items', 'arcane'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'title' => esc_html__('Add number of items', 'arcane'),
            ]

        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'arcane'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
            ]
        );

        $this->add_control(
            'spacing',
            [
                'label' => esc_html__('Spacing', 'arcane'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'arcane'),
                'label_off' => esc_html__('No', 'arcane'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

    }


    /**
     * Render shop simple widget output on the frontend.
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

        $number = !empty($settings['number']) ? absint($settings['number']) : 5;
        $show = !empty($settings['show']) ? sanitize_title($settings['show']) : 'all';
        $orderby = !empty($settings['orderby']) ? sanitize_title($settings['orderby']) : 'date';
        $order = !empty($settings['order']) ? sanitize_title($settings['order']) : 'asc';

        $product_visibility_term_ids = '';
        $currency = '';

        if ( class_exists( 'WooCommerce' ) ) {
            $product_visibility_term_ids = wc_get_product_visibility_term_ids();
            $currency = get_woocommerce_currency_symbol();
        }

        $query_args = array(
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'post_type' => 'product',
            'no_found_rows' => 1,
            'order' => $order,
            'meta_query' => array(),
            'tax_query' => array(
                'relation' => 'AND',
            ),
        );

        if (!empty($settings['categories'])) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $settings['categories'],
            );
        }

        if (empty($settings['show_hidden'])) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field' => 'term_taxonomy_id',
                'terms' => is_search() ? $product_visibility_term_ids['exclude-from-search'] : $product_visibility_term_ids['exclude-from-catalog'],
                'operator' => 'NOT IN',
            );
            $query_args['post_parent'] = 0;
        }

        if ($settings['hide_free'] == 'yes') {
            $query_args['meta_query'][] = array(
                'key' => '_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'DECIMAL',
            );
        }

        if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'term_taxonomy_id',
                    'terms' => $product_visibility_term_ids['outofstock'],
                    'operator' => 'NOT IN',
                ),
            ); // WPCS: slow query ok.
        }


        switch ($show) {
            case 'featured':
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'term_taxonomy_id',
                    'terms' => $product_visibility_term_ids['featured'],
                );
                break;
            case 'onsale':
                $product_ids_on_sale = wc_get_product_ids_on_sale();
                $product_ids_on_sale[] = 0;
                $query_args['post__in'] = $product_ids_on_sale;
                break;
        }


        switch ($orderby) {
            case 'price':
                $query_args['meta_key'] = '_price'; // WPCS: slow query ok.
                $query_args['orderby'] = 'meta_value_num';
                break;
            case 'rand':
                $query_args['orderby'] = 'rand';
                break;
            case 'sales':
                $query_args['meta_key'] = 'total_sales'; // WPCS: slow query ok.
                $query_args['orderby'] = 'meta_value_num';
                break;
            default:
                $query_args['orderby'] = 'date';
        }


        $products = new \WP_Query($query_args);


        $column = '';

        switch ($settings['columns']) {
            case '1':
                $column = 'c1';
                break;
            case '2':
                $column = 'c2';
                break;
            case '3':
                $column = 'c3';
                break;
            case '4':
                $column = 'c4';
                break;
            case '5':
                $column = 'c5';
                break;

        }

        $spacing = '';
        if ($settings['spacing'] == 'yes') {
            $spacing = 'cspacing';
        }

        ?>

        <div class="product-list <?php echo esc_attr($column);
        echo ' ';
        echo esc_attr($spacing); ?>">

            <ul class="single-product">

                <?php while ($products->have_posts()) : $products->the_post(); ?>

                    <?php

                    $_product = '';
                    if ( class_exists( 'WooCommerce' ) ) {
                    $_product = wc_get_product(get_the_ID());
                    }

                    $image_link = '';
                    if (has_post_thumbnail(get_the_ID())) {
                        $thumb = get_post_thumbnail_id();
                        $img_url = wp_get_attachment_url($thumb); //get img URL
                        $image_link = arcane_aq_resize($img_url, 500, 620, true, true, true);

                    }
                    ?>

                    <li>

                        <?php if ( class_exists( 'WooCommerce' ) ) { ?>
                            <?php if ($_product->is_on_sale()) { ?>
                                <div class="product-tag">
                                    <span class="tag-sale"><?php esc_html_e('sale', 'arcane'); ?></span>
                                </div>
                            <?php } ?>
                        <?php } ?>

                        <?php if(!empty($image_link)){ ?>
                            <a href="<?php the_permalink(); ?>" class="pr-image">
                                <img src="<?php echo esc_url($image_link); ?>" alt="<?php the_title_attribute(); ?>">
                            </a>
                        <?php } ?>

                        <div class="product">
                            <div class="product-wrapper">

                                <a href="<?php the_permalink(); ?>" ><h5 class="product-title"><?php the_title(); ?></h5></a>

                                <div class="product-price">

                                    <?php if ($_product->is_on_sale()) { ?>

                                        <span class="regular-price"><del><?php echo esc_html($currency); ?><?php echo esc_html($_product->get_regular_price()); ?></del></span>

                                        <span class="current-price"><?php echo esc_html($currency); ?><?php echo esc_html($_product->get_sale_price()); ?></span>

                                    <?php } else { ?>

                                        <span class="current-price"><?php echo esc_html($currency); ?><?php echo esc_html($_product->get_regular_price()); ?></span>

                                    <?php } ?>

                                </div>
                                <a class="btn" href="?add-to-cart=<?php echo esc_attr(get_the_ID()); ?>"><?php esc_html_e('add to cart', 'arcane'); ?></a>
                            </div>
                         </div>

                    </li>

                <?php endwhile;
                wp_reset_postdata(); ?>

            </ul>

        </div>

        <?php

    }

}