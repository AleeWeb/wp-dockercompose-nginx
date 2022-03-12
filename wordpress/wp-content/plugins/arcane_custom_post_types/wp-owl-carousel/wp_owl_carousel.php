<?php
/**
 * Plugin Name: WP Owl Carousel
 * Description: Owl Carousel integration for Wordpress
 * Version: 1.1.0
 * Author: Tanel Kollamaa
 * Text Domain: wp_owl
 * License: GPL2
 */
defined('ABSPATH') or die('No script kiddies please!');

/*  Copyright 2015  Tanel Kollamaa  (email : tanelkollamaa@gmail.com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (is_admin()) {
        require_once WP_PLUGIN_DIR . '/arcane_custom_post_types/wp-owl-carousel/CMB2/init.php';
}


include_once WP_PLUGIN_DIR . '/arcane_custom_post_types/wp-owl-carousel/owl_settings.php';

class Arcane_Wp_Owl_Carousel {
    protected $dir;
    protected $url;
    const prefix = 'wp_owl_';

    function __construct() {
        $this -> url = plugin_dir_url( __FILE__ );
        $this -> dir = plugin_dir_path( __FILE__ );

        add_action('wp_enqueue_scripts',array($this,'load_assets'));
        add_action('admin_enqueue_scripts',array($this,'load_assets_admin'));
        add_action('init',array($this,'create_post_type'));
        add_action('edit_form_after_title', array($this, 'render_shortcode_helper'));

        add_shortcode('wp_owl',array($this,'shortcode'));

        add_action('cmb2_init', array($this, 'create_metaboxes'));


    }

    function load_assets(){

        if(!apply_filters('wp_owl_carousel_enqueue_assets',true) == true) return;

        if(apply_filters('wp_owl_carousel_enqueue_css',true) == true)
            wp_enqueue_style('owl-style',$this->url . 'owl-carousel/owl.carousel.css',array(),false);

        if(apply_filters('wp_owl_carousel_enqueue_theme_css',true) == true)
            wp_enqueue_style('owl-theme',$this->url .'owl-carousel/owl.theme.css',array('owl-style'),false);

        if(apply_filters('wp_owl_carousel_enqueue_owl_js',true) == true)
            wp_enqueue_script('owl-carousel',$this->url .'owl-carousel/owl.carousel.min.js',array('jquery'),false,true);

        if(apply_filters('wp_owl_carousel_enqueue_plugin_js',true) == true)
            wp_enqueue_script('wp-owl-carousel',$this->url .'js/wp-owl-carousel.js',array('owl-carousel'),false,true);

    }

    function load_assets_admin(){
        $min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        if(!isset($scripts))$scripts='';


        wp_enqueue_style( 'cmb2-styles', plugin_dir_url( __FILE__ )."CMB2/css/cmb2.min.css", array( 'wp-color-picker' ) );

        wp_enqueue_script( 'cmb2-scripts',plugin_dir_url( __FILE__ )."CMB2/js/cmb2{$min}.js", $scripts, '2.0' );
        wp_localize_script( 'cmb2-scripts', 'cmb2_l10', apply_filters( 'cmb2_localized_data', array(
            'ajax_nonce'       => wp_create_nonce( 'ajax_nonce' ),
            'ajaxurl'          => admin_url( '/admin-ajax.php' ),
            'script_debug'     => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG,
            'up_arrow_class'   => 'dashicons dashicons-arrow-up-alt2',
            'down_arrow_class' => 'dashicons dashicons-arrow-down-alt2',
            'defaults'         => array(
                'color_picker' => false,
                'date_picker'  => array(
                    'changeMonth'     => true,
                    'changeYear'      => true,
                    'dateFormat'      => esc_html__( 'mm/dd/yy', 'arcane' ),
                    'dayNames'        => explode( ',', esc_html__( 'Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday', 'arcane' ) ),
                    'dayNamesMin'     => explode( ',', esc_html__( 'Su, Mo, Tu, We, Th, Fr, Sa', 'arcane' ) ),
                    'dayNamesShort'   => explode( ',', esc_html__( 'Sun, Mon, Tue, Wed, Thu, Fri, Sat', 'arcane' ) ),
                    'monthNames'      => explode( ',', esc_html__( 'January, February, March, April, May, June, July, August, September, October, November, December', 'arcane' ) ),
                    'monthNamesShort' => explode( ',', esc_html__( 'Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec', 'arcane' ) ),
                    'nextText'        => esc_html__( 'Next', 'arcane' ),
                    'prevText'        => esc_html__( 'Prev', 'arcane' ),
                    'currentText'     => esc_html__( 'Today', 'arcane' ),
                    'closeText'       => esc_html__( 'Done', 'arcane' ),
                    'clearText'       => esc_html__( 'Clear', 'arcane' ),
                ),
                'time_picker'  => array(
                    'timeOnlyTitle' => esc_html__( 'Choose Time', 'arcane' ),
                    'timeText'      => esc_html__( 'Time', 'arcane' ),
                    'hourText'      => esc_html__( 'Hour', 'arcane' ),
                    'minuteText'    => esc_html__( 'Minute', 'arcane' ),
                    'secondText'    => esc_html__( 'Second', 'arcane' ),
                    'currentText'   => esc_html__( 'Now', 'arcane' ),
                    'closeText'     => esc_html__( 'Done', 'arcane' ),
                    'timeFormat'    => esc_html__( 'hh:mm TT', 'arcane' ),
                    'controlType'   => 'select',
                    'stepMinute'    => 5,
                ),
            ),
            'strings' => array(
                'upload_file'  => esc_html__( 'Use this file', 'arcane' ),
                'remove_image' => esc_html__( 'Remove Image', 'arcane' ),
                'remove_file'  => esc_html__( 'Remove', 'arcane' ),
                'file'         => esc_html__( 'File:', 'arcane' ),
                'download'     => esc_html__( 'Download', 'arcane' ),
                'check_toggle' => esc_html__( 'Select / Deselect All', 'arcane' ),
            ),
        ) ) );
    }

    function create_post_type(){
        register_post_type( 'wp_owl',
            array(
                'labels' => array(
                    'name' => __( 'Owl carousel','wp_owl'),
                    'singular_name' => __( 'Owl Carousel', 'wp_owl' )
                ),
                'public' => false,
                'has_archive' => false,
                'publicaly_queryable' => false,
                'query_var' => false,
                'show_ui' => true,
                'supports' => array(
                    'title',
                    'custom-fields'
                )

            )
        );
    }

    function create_metaboxes() {
        global $owl_settings;

        $carousel_metabox = new_cmb2_box(array('id' => 'wp_owl_metabox', 'title' => esc_html__('Owl Carousel', 'arcane'), 'object_types' => array('wp_owl', ), // Post type
        'context' => 'normal', 'priority' => 'high', 'show_names' => true, 'closed' => false));

        $categories = get_categories(array('type' => 'post', 'child_of' => 0, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 1, 'hierarchical' => 1, 'taxonomy' => 'category', 'pad_counts' => false));
        $cats[0] = esc_html__('None', 'arcane');
        $cats[999] = esc_html__('All posts', 'arcane');
        foreach ($categories as $cat) {
            $cats[$cat -> cat_ID] = $cat -> cat_name;
        }
        if (!isset($cats))
            $cats = '';
        $carousel_metabox -> add_field(array('name' => esc_html__('Posts', 'arcane'), 'desc' => esc_html__('Choose post category you want to display', 'arcane'), 'id' => self::prefix . 'post_cat', 'type' => 'select', 'default' => 'none', 'options' => $cats));

        $carousel_metabox -> add_field(array('name' => esc_html__('Images', 'arcane'), 'desc' => esc_html__('Images to use', 'arcane'), 'id' => self::prefix . 'images', 'type' => 'file_list'));

        $image_sizes = get_intermediate_image_sizes();
        $carousel_metabox -> add_field(array('name' => esc_html__('Select size', 'arcane'), 'desc' => esc_html__('Select image size to use', 'arcane'), 'id' => self::prefix . 'image_size', 'type' => 'select', 'show_option_none' => false, 'default' => 'custom', 'options' => $image_sizes));

        $carousel_metabox -> add_field(array('name' => esc_html__('Rel attribute', 'arcane'), 'desc' => esc_html__('Used to open images in a lightbox, see the documentation of your lightbox plugin for this value', 'arcane'), 'default' => 'lightbox', 'type' => 'text', 'id' => self::prefix . 'rel'));

        $carousel_metabox -> add_field(array('name' => esc_html__('Link to image size', 'arcane'), 'desc' => esc_html__('Generates link to specified image size', 'arcane'), 'type' => 'select', 'id' => self::prefix . 'link_to_size', 'options' => array_merge(array('none'), $image_sizes)));

        $carousel_metabox -> add_field(array('name' => esc_html__('Post order', 'arcane'), 'desc' => esc_html__('Choose post sort order', 'arcane'), 'type' => 'select', 'id' => self::prefix . 'post_order', 'options' => array('desc' => 'Desc', 'asc' => 'Asc')));

        foreach ($owl_settings as $id => $setting) {
            if ($setting['cmb_type'] == 'checkbox') {
                $def = $this -> set_checkbox_default($setting['default']);
            } else {
                $def = $setting['default'];
            }
            $carousel_metabox -> add_field(array('name' => $setting['name'], 'description' => $setting['desc'], 'id' => self::prefix . $id, 'type' => $setting['cmb_type'], 'default' => $def));
        }
    }

    function render_shortcode_helper() {
        global $post;
        if ($post -> post_type != 'wp_owl')
            return;

        echo '<p>' . esc_html__('Paste this shortcode into a post or a page: ', 'arcane');
        echo ' <b> [wp_owl id="' . $post -> ID . '"] </b>';
        echo '</p>';
    }

    function shortcode($atts, $content = null) {
        $attributes = shortcode_atts(array('id' => ""), $atts);

        $html = $this -> generate_owl_html(esc_attr($attributes['id']));

        return $html;
    }

    public static function generate_owl_html($id) {
        $owl = new Arcane_Wp_Owl_Carousel;
        $files = $owl -> get_owl_items($id);
        $category = get_post_meta($id, self::prefix . 'post_cat', 1);

        if ($category == 0) {

            if (empty($files))
                return;

            $size_id = get_post_meta($id, self::prefix . 'image_size', true);
            $sizes = get_intermediate_image_sizes();

            $settings = $owl->generate_settings_array($id);
            $settings = json_encode($settings);
            $lazyLoad = get_post_meta($id, self::prefix . 'lazyLoad', true);
            $link_to_size = get_post_meta($id, self::prefix . 'link_to_size', true);
            $rel = get_post_meta($id, self::prefix . 'rel', true);
            $html = '<div id="owl-carousel-' . $id . '" class="owl-carousel" data-owloptions=\'' . $settings . '\'>';
            foreach ($files as $id => $url) {
                $html .= '<div>';
                $img = wp_get_attachment_image_src($id, $sizes[$size_id]);

                if ($link_to_size != 0) {
                    $img_link = wp_get_attachment_image_src($id, $sizes[$link_to_size]);

                    $html .= '<a href="' . esc_url($img_link[0]) . '"';
                    $html .= (!empty($rel)) ? ' rel="' . $rel . '"' : '';
                    $html .= ' >';
                }

                $html .= '<img alt="" src="' . $img[0] . '" ';

                if ($lazyLoad == 'on') {
                    $html .= 'class="lazyOwl" ';
                    $html .= 'data-src="' . $img[0] . '" ';
                }
                $html .= '/>';

                $html .= ($link_to_size != 0) ? ' </a>' : '';

                $html .= '</div>';
            }

            $html .= '</div>';

            return $html;

        } else {

            $settings = $owl -> generate_settings_array($id);
            $settings = json_encode($settings);
            $lazyLoad = get_post_meta($id, self::prefix . 'lazyLoad', true);
            $link_to_size = get_post_meta($id, self::prefix . 'link_to_size', true);
            $rel = get_post_meta($id, self::prefix . 'rel', true);
            $html = '<div id="owl-carousel-' . $id . '" class="owl-carousel" data-owloptions=\'' . $settings . '\'>';
            $sort_order = get_post_meta($id, self::prefix . 'post_order', true);

            if ($category == 999) {$args = array('orderby' => 'date','order' => $sort_order, 'posts_per_page' => -1);
            } else {$args = array('orderby' => 'date', 'order' => $sort_order, 'category' => $category, 'posts_per_page' => -1);
            }


            $options = arcane_get_theme_options();
            if(!isset($options['rating_type']))$options['rating_type'] = '';
            $myposts = get_posts($args);
            foreach ($myposts as $post) {
                $categories = wp_get_post_categories($post -> ID);
                $cat_data = get_option("category_$categories[0]");

                if(!$cat_data) $cat_data['catBG'] = '#ff8800';

                if($options['rating_type'] == 'numbers'){
                    $html .= '<div class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_background">';
                    if (get_post_meta($post -> ID, 'overall_rating', true) != 0) {
                        $html .= '<div class="carousel_rating carousel_rating_number">';
                        // overall stars
                        $overall_rating = get_post_meta($post -> ID, 'overall_rating', true);


                        if ($overall_rating != "0" && $overall_rating == "0.5") {
                            $html .= '<b class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color"><i class="fas fa-trophy"></i> 0.5</b>/5';
                        }

                        if ($overall_rating != "0" && $overall_rating == "1") {
                            $html .= '<b class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color"><i class="fas fa-trophy"></i> 1</b>/5';
                        }

                        if ($overall_rating != "0" && $overall_rating == "1.5") {
                            $html .= '<b class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color"><i class="fas fa-trophy"></i> 1.5</b>/5';
                        }

                        if ($overall_rating != "0" && $overall_rating == "2") {
                            $html .= '<b class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color"><i class="fas fa-trophy"></i> 2</b>/5';
                        }

                        if ($overall_rating != "0" && $overall_rating == "2.5") {
                            $html .= '<b class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color"><i class="fas fa-trophy"></i> 2.5</b>/5';
                        }

                        if ($overall_rating != "0" && $overall_rating == "3") {
                            $html .= '<b class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color"><i class="fas fa-trophy"></i> 3</b>/5';
                        }

                        if ($overall_rating != "0" && $overall_rating == "3.5") {
                            $html .= '<b class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color"><i class="fas fa-trophy"></i> 3.5</b>/5';
                        }

                        if ($overall_rating != "0" && $overall_rating == "4") {
                            $html .= '<b class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color"><i class="fas fa-trophy"></i> 4</b>/5';
                        }

                        if ($overall_rating != "0" && $overall_rating == "4.5") {
                            $html .= '<b class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color"><i class="fas fa-trophy"></i> 4.5</b>/5';
                        }

                        if ($overall_rating != "0" && $overall_rating == "5") {
                            $html .= '<b class="cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color"><i class="fas fa-trophy"></i> 5</b>/5';
                        }
                        $html .= '</div>';
                    }
                }else{
                    $html .= '<div>';
                    if (get_post_meta($post -> ID, 'overall_rating', true) != 0) {
                        $html .= '<div class="carousel_rating cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_color" >';
                        // overall stars
                        $overall_rating = get_post_meta($post -> ID, 'overall_rating', true);


                        if ($overall_rating != "0" && $overall_rating == "0.5") {
                            $html .= '
                         <i class="fas fa-star-half"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                         ';
                        }

                        if ($overall_rating != "0" && $overall_rating == "1") {
                            $html .= '
                         <i class="fas fa-star"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                        ';
                        }

                        if ($overall_rating != "0" && $overall_rating == "1.5") {
                            $html .= '
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star-half"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                        ';
                        }

                        if ($overall_rating != "0" && $overall_rating == "2") {
                            $html .= '
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                        ';
                        }

                        if ($overall_rating != "0" && $overall_rating == "2.5") {
                            $html .= '
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star-half"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                        ';
                        }

                        if ($overall_rating != "0" && $overall_rating == "3") {
                            $html .= '
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="far fa-star"></i>
                         <i class="far fa-star"></i>
                        ';
                        }

                        if ($overall_rating != "0" && $overall_rating == "3.5") {
                            $html .= '
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star-half"></i>
                         <i class="far fa-star"></i>
                        ';
                        }

                        if ($overall_rating != "0" && $overall_rating == "4") {
                            $html .= '
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="far fa-star"></i>
                        ';
                        }

                        if ($overall_rating != "0" && $overall_rating == "4.5") {
                            $html .= '
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star-half"></i>
                        ';
                        }

                        if ($overall_rating != "0" && $overall_rating == "5") {
                            $html .= '
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                        ';
                        }
                        $html .= '</div>';
                    }
                }

                $post_thumbnail_id = get_post_thumbnail_id($post -> ID);
                if (!isset($size_id))
                    $size_id = '';
                if (!isset($sizes[$size_id]))
                    $sizes[$size_id] = '';
                $img = wp_get_attachment_image_src($post_thumbnail_id, $sizes[$size_id]);

                if ($link_to_size != 0) {
                    $img_link = wp_get_attachment_image_src($post_thumbnail_id, $sizes[$link_to_size]);

                    $html .= '<a href="' . esc_url($img_link[0]) . '"';
                    $html .= (!empty($rel)) ? ' rel="' . $rel . '"' : '';
                    $html .= ' >';
                }

                $thumb = get_post_thumbnail_id($post -> ID);
                $img_url = wp_get_attachment_url($thumb, 'full');
                //get img URL
                $image = arcane_aq_resize($img_url, 230, 325, true, '', true);
                //resize & crop img
                $ran = rand();
                if(empty($image[0]))$image[0]= get_theme_file_uri('img/defaults/305x305.jpg');
                $html .= '<a class="car_image_'.$ran.'" href="' . get_the_permalink($post -> ID) . '"><img alt="" src="' . esc_url($image[0]) . '" ';

                if ($lazyLoad == 'on') {
                    $html .= 'class="lazyOwl" ';
                    $html .= 'data-src="' . $image[0] . '" ';
                }
                $html .= '/></a>';

                $rgb = arcane_hex2rgb($cat_data["catBG"]);
                $data = '.car_image_'.$ran.':after{background-color: rgba('.$rgb[0].', '.$rgb[1].', '.$rgb[2].',0.3)}';
                wp_add_inline_style( 'arcane_style', $data );
                $html .= ($link_to_size != 0) ? ' </a>' : '';

                $html .= '<div class="car_title"><a href="' . esc_url(get_category_link($categories[0])) . '" class="ncategory cat_color_'.esc_attr(str_replace("#","",$cat_data['catBG'])).'_background_color">';
                $html .= get_cat_name($categories[0]);
                $html .= '</a>';

                $html .= '<a class="car_inner_title" href="' . get_the_permalink($post -> ID) . '">' . get_the_title($post -> ID) . '</a>';
                $author_id=$post->post_author;
                $html .= esc_html__('by ', 'arcane');
                $html .= '<a data-original-title="' . esc_html__("View all posts by ", 'arcane') . get_the_author_meta( 'display_name' , $author_id ) . '" href="' . esc_url(get_author_posts_url($author_id) ) . '">' . get_the_author_meta( 'display_name' , $author_id ) . '</a></div>';

                $html .= '</div>';
            }
            wp_reset_postdata();
            $html .= '</div>';

            return $html;

        }
    }

    function get_owl_items($id) {
        $files = get_post_meta($id, self::prefix . 'images', 1);
        return $files;
    }

    function generate_settings_array($id) {
        global $owl_settings;
        $new_settings = array();

        foreach ($owl_settings as $k => $v) {
            $saved = get_post_meta($id, self::prefix . $k, true);

            if ($owl_settings[$k]['cmb_type'] == 'checkbox') {
                if ($saved == 'on') {
                    $new_settings[$k] = true;
                } else {
                    $new_settings[$k] = false;
                }
            } else {
                if ($owl_settings[$k]['type'] == 'number') {
                    $saved = (int)$saved;
                }
                $new_settings[$k] = $saved;
            }
        }
        return $new_settings;
    }

    function set_checkbox_default($default) {
        return isset($_GET['post']) ? '' : ($default ? (string)$default : '');
    }

}

new Arcane_Wp_Owl_Carousel();
