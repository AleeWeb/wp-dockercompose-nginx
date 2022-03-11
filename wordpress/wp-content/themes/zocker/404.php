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
        exit();
    }

    if( class_exists( 'ReduxFramework' ) ) {
        $zocker404title     = zocker_opt( 'zocker_fof_title' );
        $zocker404subtitle  = zocker_opt( 'zocker_fof_subtitle' );
        $zocker404btntext   = zocker_opt( 'zocker_fof_btn_text' );
    } else {
        $zocker404title     = __( 'Oops! That page can\'t be found', 'zocker' );
        $zocker404subtitle  = __( 'The page you\'ve requested is not available.', 'zocker' );
        $zocker404btntext   = __( 'Return To Home', 'zocker' );
    }

    // get header
    get_header();

    echo '<section class="vs-error-wrapper space-top newsletter-pb">';
        echo '<div class="container">';
            echo '<div class="row text-center justify-content-center">';
                
                if( ! empty( zocker_opt( 'zocker_404_image', 'url' ) ) ){
                    echo '<div class="col-12 mb-3 mb-xl-5">';
                        echo zocker_img_tag( array(
                            'url'   => esc_url( zocker_opt( 'zocker_404_image', 'url' ) ),
                        ) );
                    echo '</div>';
                }
                echo '<div class="col-lg-10 col-xl-8 mb-30">';
                    if( !empty( $zocker404title ) ){
                        echo '<h2 class="text-normal">'.esc_html( $zocker404title ).'</h2>';
                    }
                    if( !empty( $zocker404subtitle ) ){
                        echo '<p class="px-xl-5 mx-lg-5 pb-2 mb-xl-4">'.esc_html( $zocker404subtitle ).'</p>';
                    }
                    echo '<a href="'.esc_url( home_url('/') ).'" class="vs-btn gradient-btn">'.esc_html( $zocker404btntext ).'</a>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</section>';

    //footer
    get_footer();