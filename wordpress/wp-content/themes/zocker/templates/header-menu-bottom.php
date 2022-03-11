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

    if( defined( 'CMB2_LOADED' )  ){
        if( !empty( zocker_meta('page_breadcrumb_area') ) ) {
            $zocker_page_breadcrumb_area  = zocker_meta('page_breadcrumb_area');
        } else {
            $zocker_page_breadcrumb_area = '1';
        }
    }else{
        $zocker_page_breadcrumb_area = '1';
    }
    
    $allowhtml = array(
        'p'         => array(
            'class'     => array()
        ),
        'span'      => array(
            'class'     => array(),
        ),
        'a'         => array(
            'href'      => array(),
            'title'     => array()
        ),
        'br'        => array(),
        'em'        => array(),
        'strong'    => array(),
        'b'         => array(),
        'sub'       => array(),
        'sup'       => array(),
    );
    
    if( class_exists( 'reduxframework' ) ){
        $zocker_padding_class = "pt-200 pb-50";
    }else{
        $zocker_padding_class   = "pt-100 pb-100";
    }
    
    if(  is_page() || is_page_template( 'template-builder.php' )  ) {
        if( $zocker_page_breadcrumb_area == '1' ) {
            echo '<!-- Page title -->';
            echo '<div class="breadcumb-wrapper breadcumb-layout1 '.esc_attr( $zocker_padding_class ).' bg-secondary">';
                echo '<div class="container">';
                    echo '<div class="breadcumb-content text-center">';
                        if( defined('CMB2_LOADED') || class_exists('ReduxFramework') ) {
                            if( !empty( zocker_meta('page_breadcrumb_settings') ) ) {
                                if( zocker_meta('page_breadcrumb_settings') == 'page' ) {
                                    $zocker_page_title_switcher = zocker_meta('page_title');
                                } else {
                                    $zocker_page_title_switcher = zocker_opt('zocker_page_title_switcher');
                                }
                            } else {
                                $zocker_page_title_switcher = '1';
                            }
                        } else {
                            $zocker_page_title_switcher = '1';
                        }

                        if( $zocker_page_title_switcher ){
                            if( class_exists( 'ReduxFramework' ) ){
                                $zocker_page_title_tag    = zocker_opt('zocker_page_title_tag');
                            }else{
                                $zocker_page_title_tag    = 'h1';
                            }

                            if( defined( 'CMB2_LOADED' )  ){
                                if( !empty( zocker_meta('page_title_settings') ) ) {
                                    $zocker_custom_title = zocker_meta('page_title_settings');
                                } else {
                                    $zocker_custom_title = 'default';
                                }
                            }else{
                                $zocker_custom_title = 'default';
                            }

                            if( $zocker_custom_title == 'default' ) {
                                echo zocker_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $zocker_page_title_tag ),
                                        "text"  => esc_html( get_the_title( ) ),
                                        'class' => 'breadcumb-title my-0'
                                    )
                                );
                            } else {
                                echo zocker_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $zocker_page_title_tag ),
                                        "text"  => esc_html( zocker_meta('custom_page_title') ),
                                        'class' => 'breadcumb-title my-0'
                                    )
                                );
                            }

                        }
                        if( defined('CMB2_LOADED') || class_exists('ReduxFramework') ) {

                            if( zocker_meta('page_breadcrumb_settings') == 'page' ) {
                                $zocker_breadcrumb_switcher = zocker_meta('page_breadcrumb_trigger');
                            } else {
                                $zocker_breadcrumb_switcher = zocker_opt('zocker_enable_breadcrumb');
                            }

                        } else {
                            $zocker_breadcrumb_switcher = '1';
                        }

                        if( $zocker_breadcrumb_switcher == '1' && (  is_page() || is_page_template( 'template-builder.php' ) )) {
                            zocker_breadcrumbs(
                                array(
                                    'breadcrumbs_classes' => 'nav',
                                )
                            );
                        }
                        echo '</div>';
                echo '</div>';
            echo '</div>';
            echo '<!-- End of Page title -->';
        }
    } else {
        
        echo '<!-- Page title -->';
        echo '<div class="breadcumb-wrapper breadcumb-layout1 bg-secondary '.esc_attr( $zocker_padding_class ).'">';
            echo '<div class="container z-index-common">';
                echo '<div class="breadcumb-content text-center">';
                    if( class_exists( 'ReduxFramework' )  ){
                        $zocker_page_title_switcher  = zocker_opt('zocker_page_title_switcher');
                    }else{
                        $zocker_page_title_switcher = '1';
                    }

                    if( $zocker_page_title_switcher ){
                        if( class_exists( 'ReduxFramework' ) ){
                            $zocker_page_title_tag    = zocker_opt('zocker_page_title_tag');
                        }else{
                            $zocker_page_title_tag    = 'h1';
                        }
                        if( class_exists('woocommerce') && is_shop() ) {
                            echo zocker_heading_tag(
                                array(
                                    "tag"   => esc_attr( $zocker_page_title_tag ),
                                    "text"  => wp_kses( woocommerce_page_title( false ), $allowhtml ),
                                    'class' => 'breadcumb-title my-0'
                                )
                            );
                        }elseif ( is_archive() ){
                            echo zocker_heading_tag(
                                array(
                                    "tag"   => esc_attr( $zocker_page_title_tag ),
                                    "text"  => wp_kses( get_the_archive_title(), $allowhtml ),
                                    'class' => 'breadcumb-title my-0'
                                )
                            );
                        }elseif ( is_home() ){
                            $zocker_blog_page_title_setting = zocker_opt('zocker_blog_page_title_setting');
                            $zocker_blog_page_title_switcher = zocker_opt('zocker_blog_page_title_switcher');
                            $zocker_blog_page_custom_title = zocker_opt('zocker_blog_page_custom_title');
                            if( class_exists('ReduxFramework') ){
                                if( $zocker_blog_page_title_switcher ){
                                    echo zocker_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $zocker_page_title_tag ),
                                            "text"  => !empty( $zocker_blog_page_custom_title ) && $zocker_blog_page_title_setting == 'custom' ? esc_html( $zocker_blog_page_custom_title) : esc_html__( 'Blog', 'zocker' ),
                                            'class' => 'breadcumb-title my-0'
                                        )
                                    );
                                }
                            }else{
                                echo zocker_heading_tag(
                                    array(
                                        "tag"   => "h1",
                                        "text"  => esc_html__( 'Blog', 'zocker' ),
                                        'class' => 'breadcumb-title my-0',
                                    )
                                );
                            }
                        }elseif( is_search() ){
                            echo zocker_heading_tag(
                                array(
                                    "tag"   => esc_attr( $zocker_page_title_tag ),
                                    "text"  => esc_html__( 'Search Result', 'zocker' ),
                                    'class' => 'breadcumb-title my-0'
                                )
                            );
                        }elseif( is_404() ){
                            echo zocker_heading_tag(
                                array(
                                    "tag"   => esc_attr( $zocker_page_title_tag ),
                                    "text"  => esc_html__( '404 PAGE', 'zocker' ),
                                    'class' => 'breadcumb-title my-0'
                                )
                            );
                        }elseif( is_singular( 'product' ) ){
                            $posttitle_position  = zocker_opt('zocker_product_details_title_position');
                            $postTitlePos = false;
                            if( class_exists( 'ReduxFramework' ) ){
                                if( $posttitle_position && $posttitle_position != 'header' ){
                                    $postTitlePos = true;
                                }
                            }else{
                                $postTitlePos = false;
                            }

                            if( $postTitlePos != true ){
                                echo zocker_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $zocker_page_title_tag ),
                                        "text"  => wp_kses( get_the_title( ), $allowhtml ),
                                        'class' => 'breadcumb-title my-0'
                                    )
                                );
                            } else {
                                if( class_exists( 'ReduxFramework' ) ){
                                    $zocker_post_details_custom_title  = zocker_opt('zocker_product_details_custom_title');
                                }else{
                                    $zocker_post_details_custom_title = __( 'Shop Details','zocker' );
                                }

                                if( !empty( $zocker_post_details_custom_title ) ) {
                                    echo zocker_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $zocker_page_title_tag ),
                                            "text"  => wp_kses( $zocker_post_details_custom_title, $allowhtml ),
                                            'class' => 'breadcumb-title my-0'
                                        )
                                    );
                                }
                            }
                        }else{
                            $posttitle_position  = zocker_opt('zocker_post_details_title_position');
                            $postTitlePos = false;
                            if( is_single() ){
                                if( class_exists( 'ReduxFramework' ) ){
                                    if( $posttitle_position && $posttitle_position != 'header' ){
                                        $postTitlePos = true;
                                    }
                                }else{
                                    $postTitlePos = false;
                                }
                            }
                            if( is_singular( 'product' ) ){
                                $posttitle_position  = zocker_opt('zocker_product_details_title_position');
                                $postTitlePos = false;
                                if( class_exists( 'ReduxFramework' ) ){
                                    if( $posttitle_position && $posttitle_position != 'header' ){
                                        $postTitlePos = true;
                                    }
                                }else{
                                    $postTitlePos = false;
                                }
                            }

                            if( $postTitlePos != true ){
                                echo zocker_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $zocker_page_title_tag ),
                                        "text"  => wp_kses( get_the_title( ), $allowhtml ),
                                        'class' => 'breadcumb-title my-0'
                                    )
                                );
                            } else {
                                if( class_exists( 'ReduxFramework' ) ){
                                    $zocker_post_details_custom_title  = zocker_opt('zocker_post_details_custom_title');
                                }else{
                                    $zocker_post_details_custom_title = __( 'Blog Details','zocker' );
                                }

                                if( !empty( $zocker_post_details_custom_title ) ) {
                                    echo zocker_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $zocker_page_title_tag ),
                                            "text"  => wp_kses( $zocker_post_details_custom_title, $allowhtml ),
                                            'class' => 'breadcumb-title my-0'
                                        )
                                    );
                                }
                            }
                        }
                    }
                    if( class_exists('ReduxFramework') ) {
                        $zocker_breadcrumb_switcher = zocker_opt( 'zocker_enable_breadcrumb' );
                    } else {
                        $zocker_breadcrumb_switcher = '1';
                    }
                    if( $zocker_breadcrumb_switcher == '1' ) {
                        zocker_breadcrumbs(
                            array(
                                'breadcrumbs_classes' => 'nav',
                            )
                        );
                    }
                echo '</div>';
            echo '</div>';
        echo '</div>';
        echo '<!-- End of Page title -->';
    }