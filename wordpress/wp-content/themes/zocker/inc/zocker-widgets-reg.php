<?php
/**
 * @Packge     : Zocker
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */


// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit;
}

function zocker_widgets_init() {

    if( class_exists('ReduxFramework') ) {
        $zocker_sidebar_widget_title_heading_tag = zocker_opt('zocker_sidebar_widget_title_heading_tag');
    } else {
        $zocker_sidebar_widget_title_heading_tag = 'h3';
    }

    //sidebar widgets register
    register_sidebar( array(
        'name'          => esc_html__( 'Blog Sidebar', 'zocker' ),
        'id'            => 'zocker-blog-sidebar',
        'description'   => esc_html__( 'Add Blog Sidebar Widgets Here.', 'zocker' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<'.esc_attr($zocker_sidebar_widget_title_heading_tag).' class="sidebox-title-v2 h5">',
        'after_title'   => '</'.esc_attr($zocker_sidebar_widget_title_heading_tag).'>',
    ) );

    // page sidebar widgets register
    register_sidebar( array(
        'name'          => esc_html__( 'Page Sidebar', 'zocker' ),
        'id'            => 'zocker-page-sidebar',
        'description'   => esc_html__( 'Add Page Sidebar Widgets Here.', 'zocker' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget_title">',
        'after_title'   => '</h3>',
    ) );
    if( class_exists( 'ReduxFramework' ) && defined('ELEMENTOR_VERSION') ){
        // footer widgets register
        register_sidebar( array(
           'name'          => esc_html__( 'Footer One', 'zocker' ),
           'id'            => 'zocker-footer-1',
           'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
           'after_widget'  => '</div>',
           'before_title'  => '<h3 class="widget_title">',
           'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
           'name'          => esc_html__( 'Footer Two', 'zocker' ),
           'id'            => 'zocker-footer-2',
           'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
           'after_widget'  => '</div>',
           'before_title'  => '<h3 class="widget_title">',
           'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
           'name'          => esc_html__( 'Footer Three', 'zocker' ),
           'id'            => 'zocker-footer-3',
           'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
           'after_widget'  => '</div>',
           'before_title'  => '<h3 class="widget_title">',
           'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
           'name'          => esc_html__( 'Footer Four', 'zocker' ),
           'id'            => 'zocker-footer-4',
           'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
           'after_widget'  => '</div>',
           'before_title'  => '<h3 class="widget_title">',
           'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
           'name'          => esc_html__( 'Offcanvas Sidebar', 'zocker' ),
           'id'            => 'zocker-offcanvas-sidebar',
           'before_widget' => '<div id="%1$s" class="widget offcanvas-widget %2$s">',
           'after_widget'  => '</div>',
           'before_title'  => '<h3 class="sidebox-title text-white h5">',
           'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
           'name'          => esc_html__( 'Player Details Sidebar', 'zocker' ),
           'id'            => 'zocker-player-sidebar',
           'before_widget' => '<div id="%1$s" class="widget player-widget %2$s">',
           'after_widget'  => '</div>',
           'before_title'  => '<h3 class="sidebox-title-v2 h5">',
           'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
           'name'          => esc_html__( 'Team Details Sidebar', 'zocker' ),
           'id'            => 'zocker-team-sidebar',
           'before_widget' => '<div id="%1$s" class="widget team-widget %2$s">',
           'after_widget'  => '</div>',
           'before_title'  => '<h3 class="sidebox-title-v2 h5">',
           'after_title'   => '</h3>',
        ) );
    }
    if( class_exists('woocommerce') ) {
        register_sidebar(
            array(
                'name'          => esc_html__( 'WooCommerce Sidebar', 'zocker' ),
                'id'            => 'zocker-woo-sidebar',
                'description'   => esc_html__( 'Add widgets here to appear in your woocommerce page sidebar.', 'zocker' ),
                'before_widget' => '<div class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="widget-title"><h4>',
                'after_title'   => '</h4></div>',
            )
        );
    }

}

add_action( 'widgets_init', 'zocker_widgets_init' );