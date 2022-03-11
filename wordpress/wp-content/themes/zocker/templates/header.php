<?php
/**
 * @Packge     : Zocker
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

    // Block direct access
    if( ! defined( 'ABSPATH' ) ){
        exit();
    }

    if( class_exists( 'ReduxFramework' ) && defined('ELEMENTOR_VERSION') ) {
        if( is_page() || is_page_template('template-builder.php') ) {
            $zocker_post_id = get_the_ID();

            // Get the page settings manager
            $zocker_page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

            // Get the settings model for current post
            $zocker_page_settings_model = $zocker_page_settings_manager->get_model( $zocker_post_id );

            // Retrieve the color we added before
            $zocker_header_style = $zocker_page_settings_model->get_settings( 'zocker_header_style' );
            $zocker_header_builder_option = $zocker_page_settings_model->get_settings( 'zocker_header_builder_option' );

            if( $zocker_header_style == 'header_builder'  ) {

                if( !empty( $zocker_header_builder_option ) ) {
                    $zockerheader = get_post( $zocker_header_builder_option );
                    echo '<header class="header">';
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $zockerheader->ID );
                    echo '</header>';
                }
            } else {
                // global options
                $zocker_header_builder_trigger = zocker_opt('zocker_header_options');
                if( $zocker_header_builder_trigger == '2' ) {
                    echo '<header>';
                    $zocker_global_header_select = get_post( zocker_opt( 'zocker_header_select_options' ) );
                    $header_post = get_post( $zocker_global_header_select );
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $header_post->ID );
                    echo '</header>';
                } else {
                    // wordpress Header
                    zocker_global_header_option();
                }
            }
        } else {
            $zocker_header_options = zocker_opt('zocker_header_options');
            if( $zocker_header_options == '1' ) {
                zocker_global_header_option();
            } else {
                $zocker_header_select_options = zocker_opt('zocker_header_select_options');
                $zockerheader = get_post( $zocker_header_select_options );
                echo '<header class="header">';
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $zockerheader->ID );
                echo '</header>';
            }
        }
    } else {
        zocker_global_header_option();
    }