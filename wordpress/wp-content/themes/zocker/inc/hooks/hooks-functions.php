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


    // preloader hook function
    if( ! function_exists( 'zocker_preloader_wrap_cb' ) ) {
        function zocker_preloader_wrap_cb() {
            $preloader_display              =  zocker_opt('zocker_display_preloader');

            if( class_exists('ReduxFramework') ){
                if( $preloader_display ){
                    echo '<div class="preloader">';
                        echo '<button class="vs-btn preloaderCls">'.esc_html__( 'Cancel Preloader', 'zocker' ).'</button>';
                        echo '<div class="preloader-inner">';
                            if( ! empty( zocker_opt( 'zocker_preloader_img','url' ) ) ){
                                echo '<div class="loader-logo">';
                                    echo zocker_img_tag( array(
                                        'url'   => esc_url( zocker_opt( 'zocker_preloader_img','url' ) ),
                                    ) );
                                echo '</div>';
                            }
                            echo '<div class="loader-wrap pt-4">';
                                echo '<span class="loader"></span>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            }else{
                echo '<div class="preloader">';
                    echo '<button class="vs-btn preloaderCls">'.esc_html__( 'Cancel Preloader', 'zocker' ).'</button>';
                    echo '<div class="preloader-inner">';
                        echo '<div class="loader-wrap pt-4">';
                            echo '<span class="loader"></span>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        }
    }

    // Header Hook function
    if( !function_exists('zocker_header_cb') ) {
        function zocker_header_cb( ) {
            get_template_part('templates/header');
            get_template_part('templates/header-menu-bottom');
        }
    }

    // back top top hook function
    if( ! function_exists( 'zocker_back_to_top_cb' ) ) {
        function zocker_back_to_top_cb( ) {
            $backtotop_trigger = zocker_opt('zocker_display_bcktotop');
            $custom_bcktotop   = zocker_opt('zocker_custom_bcktotop');
            $custom_bcktotop_icon   = zocker_opt('zocker_custom_bcktotop_icon');
            if( class_exists( 'ReduxFramework' ) ) {
                if( $backtotop_trigger ) {
                    if( $custom_bcktotop ) {
                        echo '<!-- Back to Top Button -->';
                        echo '<a href="#" class="scrollToTop icon-btn3">';
                            echo '<i class="fa '.esc_attr( $custom_bcktotop_icon ).'"></i>';
                        echo '</a>';
                        echo '<!-- End of Back to Top Button -->';
                    } else {
                        echo '<!-- Back to Top Button -->';
                        echo '<a href="#" class="scrollToTop icon-btn3">';
                            echo '<i class="far fa-angle-up"></i>';
                        echo '</a>';
                        echo '<!-- End of Back to Top Button -->';
                    }
                }
            }

        }
    }

    // Blog Start Wrapper Function
    if( !function_exists('zocker_blog_start_wrap_cb') ) {
        function zocker_blog_start_wrap_cb() {
            echo '<section class="vs-blog-wrapper blog-single-layout1 space-top newsletter-pb arrow-wrap">';
                echo '<div class="container">';
                    if( is_active_sidebar( 'zocker-blog-sidebar' ) ){
                        $zocker_gutter_class = 'gutters-40';
                    }else{
                        $zocker_gutter_class = '';
                    }
                    echo '<div class="row '.esc_attr( $zocker_gutter_class ).'">';
        }
    }

    // Blog End Wrapper Function
    if( !function_exists('zocker_blog_end_wrap_cb') ) {
        function zocker_blog_end_wrap_cb() {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // Blog Column Start Wrapper Function
    if( !function_exists('zocker_blog_col_start_wrap_cb') ) {
        function zocker_blog_col_start_wrap_cb() {
            if( class_exists('ReduxFramework') ) {
                $zocker_blog_sidebar = zocker_opt('zocker_blog_sidebar');
                if( $zocker_blog_sidebar == '2' && is_active_sidebar('zocker-blog-sidebar') ) {
                    echo '<div class="col-lg-8 order-lg-last">';
                } elseif( $zocker_blog_sidebar == '3' && is_active_sidebar('zocker-blog-sidebar') ) {
                    echo '<div class="col-lg-8">';
                } else {
                    echo '<div class="col-lg-12">';
                }

            } else {
                if( is_active_sidebar('zocker-blog-sidebar') ) {
                    echo '<div class="col-lg-8">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            }
        }
    }
    // Blog Column End Wrapper Function
    if( !function_exists('zocker_blog_col_end_wrap_cb') ) {
        function zocker_blog_col_end_wrap_cb() {
            echo '</div>';
        }
    }

    // Blog Sidebar
    if( !function_exists('zocker_blog_sidebar_cb') ) {
        function zocker_blog_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $zocker_blog_sidebar = zocker_opt('zocker_blog_sidebar');
            } else {
                $zocker_blog_sidebar = 2;
            }
            if( $zocker_blog_sidebar != 1 && is_active_sidebar('zocker-blog-sidebar') ) {
                // Sidebar
                get_sidebar();
            }
        }
    }


    if( !function_exists('zocker_blog_details_sidebar_cb') ) {
        function zocker_blog_details_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $zocker_blog_single_sidebar = zocker_opt('zocker_blog_single_sidebar');
            } else {
                $zocker_blog_single_sidebar = 4;
            }
            if( $zocker_blog_single_sidebar != 1 ) {
                // Sidebar
                get_sidebar();
            }

        }
    }

    // Blog Pagination Function
    if( !function_exists('zocker_blog_pagination_cb') ) {
        function zocker_blog_pagination_cb( ) {
            get_template_part('templates/pagination');
        }
    }

    // Blog Content Function
    if( !function_exists('zocker_blog_content_cb') ) {
        function zocker_blog_content_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $zocker_blog_grid = zocker_opt('zocker_blog_grid');
            } else {
                $zocker_blog_grid = '1';
            }

            if( $zocker_blog_grid == '1' ) {
                $zocker_blog_grid_class = 'col-lg-12';
            } elseif( $zocker_blog_grid == '2' ) {
                $zocker_blog_grid_class = 'col-sm-6';
            } else {
                $zocker_blog_grid_class = 'col-lg-4 col-sm-6';
            }

            echo '<div class="row">';
                if( have_posts() ) {
                    while( have_posts() ) {
                        the_post();
                        echo '<div class="'.esc_attr($zocker_blog_grid_class).'">';
                            get_template_part('templates/content',get_post_format());
                        echo '</div>';
                    }
                    wp_reset_postdata();
                } else{
                    get_template_part('templates/content','none');
                }
            echo '</div>';
        }
    }

    // footer content Function
    if( !function_exists('zocker_footer_content_cb') ) {
        function zocker_footer_content_cb( ) {

            if( class_exists('ReduxFramework') && did_action( 'elementor/loaded' )  ){
                if( is_page() || is_page_template('template-builder.php') ) {
                    $post_id = get_the_ID();

                    // Get the page settings manager
                    $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

                    // Get the settings model for current post
                    $page_settings_model = $page_settings_manager->get_model( $post_id );

                    // Retrieve the Footer Style
                    $footer_settings = $page_settings_model->get_settings( 'zocker_footer_style' );

                    // Footer Local
                    $footer_local = $page_settings_model->get_settings( 'zocker_footer_builder_option' );

                    // Footer Enable Disable
                    $footer_enable_disable = $page_settings_model->get_settings( 'zocker_footer_choice' );

                    if( $footer_enable_disable == 'yes' ){
                        if( $footer_settings == 'footer_builder' ) {
                            // local options
                            $zocker_local_footer = get_post( $footer_local );
                            echo '<footer>';
                            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $zocker_local_footer->ID );
                            echo '</footer>';
                        } else {
                            // global options
                            $zocker_footer_builder_trigger = zocker_opt('zocker_footer_builder_trigger');
                            if( $zocker_footer_builder_trigger == 'footer_builder' ) {
                                echo '<footer>';
                                $zocker_global_footer_select = get_post( zocker_opt( 'zocker_footer_builder_select' ) );
                                $footer_post = get_post( $zocker_global_footer_select );
                                echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_post->ID );
                                echo '</footer>';
                            } else {
                                // wordpress widgets
                                zocker_footer_global_option();
                            }
                        }
                    }
                } else {
                    // global options
                    $zocker_footer_builder_trigger = zocker_opt('zocker_footer_builder_trigger');
                    if( $zocker_footer_builder_trigger == 'footer_builder' ) {
                        echo '<footer>';
                        $zocker_global_footer_select = get_post( zocker_opt( 'zocker_footer_builder_select' ) );
                        $footer_post = get_post( $zocker_global_footer_select );
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_post->ID );
                        echo '</footer>';
                    } else {
                        // wordpress widgets
                        zocker_footer_global_option();
                    }
                }
            } else {
                echo '<div class="footer-copyright bg-black z-index-common">';
                    echo '<div class="container">';
                        echo '<div class="row">';
                            if( has_nav_menu( 'footer-menu' ) ){
                                echo '<div class="col-xl-7 d-none d-xl-block">';
                                    echo '<div class="footer-menu">';
                                        wp_nav_menu( array(
                                            "theme_location"    => 'footer-menu',
                                            "container"         => '',
                                            "menu_class"        => ''
                                        ) );
                                    echo '</div>';
                                echo '</div>';
                            }
                            if( has_nav_menu( 'footer-menu' ) ){
                                $column_class = "col-xl-5 text-xl-end";
                            }else{
                                $column_class = "col-xl-12";
                            }
                            echo '<div class="'.esc_attr( $column_class ).' align-self-center text-center py-3">';
                                echo '<p class="mb-0 text-white">'.sprintf( 'Copyright <i class="fal fa-copyright"></i> %s <a href="%s">%s</a> All Rights Reserved by <a href="%s">%s</a>',date('Y'),esc_url('#'),__( 'Zocker.','zocker' ),esc_url('#'),__( 'Vecuro', 'zocker' ) ).'</p>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }

        }
    }

    // blog details wrapper start hook function
    if( !function_exists('zocker_blog_details_wrapper_start_cb') ) {
        function zocker_blog_details_wrapper_start_cb( ) {
            echo '<section class="vs-blog-wrapper blog-single-layout1 space-top  newsletter-pb">';
                echo '<div class="container">';
                    if( is_active_sidebar( 'zocker-blog-sidebar' ) ){
                        $zocker_gutter_class = 'gutters-40';
                    }else{
                        $zocker_gutter_class = '';
                    }
                    echo '<div class="row '.esc_attr( $zocker_gutter_class ).'">';
        }
    }

    // blog details column wrapper start hook function
    if( !function_exists('zocker_blog_details_col_start_cb') ) {
        function zocker_blog_details_col_start_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $zocker_blog_single_sidebar = zocker_opt('zocker_blog_single_sidebar');
                if( $zocker_blog_single_sidebar == '2' && is_active_sidebar('zocker-blog-sidebar') ) {
                    echo '<div class="col-lg-8 order-last">';
                } elseif( $zocker_blog_single_sidebar == '3' && is_active_sidebar('zocker-blog-sidebar') ) {
                    echo '<div class="col-lg-8">';
                } else {
                    echo '<div class="col-lg-12">';
                }

            } else {
                if( is_active_sidebar('zocker-blog-sidebar') ) {
                    echo '<div class="col-lg-8">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            }
        }
    }

    // blog details post meta hook function
    if( !function_exists('zocker_blog_post_meta_cb') ) {
        function zocker_blog_post_meta_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $zocker_display_post_date      =  zocker_opt('zocker_display_post_date');
                $zocker_display_post_views     =  zocker_opt('zocker_display_post_views');
                $zocker_display_post_comment   =  zocker_opt('zocker_display_post_comment');
                $zocker_display_post_category  =  zocker_opt('zocker_display_post_category');
            } else {
                $zocker_display_post_date      = '1';
                $zocker_display_post_views     = '';
                $zocker_display_post_comment   = '1';
                $zocker_display_post_category  = '1';
            }

            echo '<!-- Blog Meta -->';
            echo '<div class="blog-meta bg-smoke has-border">';
                if( $zocker_display_post_date ){
                    echo '<a href="'.esc_url( zocker_blog_date_permalink() ).'"><i class="fal fa-calendar-alt"></i>';
                        echo '<time datetime="'.esc_attr( get_the_date( DATE_W3C ) ).'">'.esc_html( get_the_date() ).'</time>';
                    echo '</a>';
                }
                if( $zocker_display_post_comment ){
                    if( get_comments_number() == 1 ){
                        $comment_text = __( ' Comment', 'zocker' );
                    }else{
                        $comment_text = __( ' Comments', 'zocker' );
                    }
                    echo '<a href="'.esc_url( get_comments_link( get_the_ID() ) ).'"><i class="far fa-comments"></i>'.esc_html( get_comments_number() ).''.$comment_text.'</a>';
                }
                if( $zocker_display_post_category ){
                    $zocker_post_categories = get_the_category();
                    if( is_array( $zocker_post_categories ) && ! empty( $zocker_post_categories ) ){
                        echo '<div class="cat-list">';
                            echo '<i class="far fa-folder-open"></i>';
                            foreach( $zocker_post_categories as $single_category ){
                                echo '<a href="'.esc_url( get_term_link( $single_category->term_id ) ).'">'.esc_html( $single_category->name ).'</a>';
                            }
                        echo '</div>';
                    }
                }
                
            echo '</div>';

        }
    }

    // blog details share options hook function
    if( !function_exists('zocker_blog_details_share_options_cb') ) {
        function zocker_blog_details_share_options_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $zocker_post_details_share_options = zocker_opt('zocker_post_details_share_options');
            } else {
                $zocker_post_details_share_options = false;
            }
            if( function_exists( 'zocker_social_sharing_buttons' ) && $zocker_post_details_share_options ) {
                echo '<div class="blog-social-links">';
                    echo '<ul class="nav nav-fill">';
                        echo zocker_social_sharing_buttons();
                    echo '</ul>';
                    echo '<!-- End Social Share -->';
                echo '</div>';
            }
        }
    }

    // blog details author bio hook function
    if( !function_exists('zocker_blog_details_author_bio_cb') ) {
        function zocker_blog_details_author_bio_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $postauthorbox =  zocker_opt( 'zocker_post_details_author_desc_trigger' );
            } else {
                $postauthorbox = '1';
            }
            if( !empty( get_the_author_meta('description')  ) && $postauthorbox == '1' ) {
                echo '<!-- Post Author -->';
                echo '<div class="blog-written-author d-md-flex bg-smoke px-60 pb-60 pt-55 my-40">';
                    echo '<!-- Author Thumb -->';
                    echo '<div class="media-img mb-10 mb-md-0 mr-40 align-self-center">';
                        echo zocker_img_tag( array(
                            "url"   => esc_url( get_avatar_url( get_the_author_meta('ID'), array(
                            "size"  => 150
                            ) ) ),
                            "class" => "rounded-circle",
                        ) );
                    echo '</div>';
                    echo '<!-- End of Author Thumb -->';
                    echo '<div class="media-body text-center text-md-start">';
                        if( function_exists( 'zocker_get_user_role_name' ) ){
                            echo '<span class="fs-xs text-theme2">'.esc_html( zocker_get_user_role_name( get_the_author_meta('ID') ) ).'</span>';
                        }
                        echo zocker_heading_tag( array(
                            "tag"   => "h3",
                            "text"  => zocker_anchor_tag( array(
                                "text"  => esc_html( ucwords( get_the_author() ) ),
                                "url"   => esc_url( get_author_posts_url( get_the_author_meta('ID') ) )
                            ) ),
                            'class' => 'font-theme text-normal mb-1',
                        ) );

                        if( ! empty( get_the_author_meta('description') ) ) {
                            echo '<p>';
                                echo esc_html( get_the_author_meta('description') );
                            echo '</p>';
                        }

                        $zocker_social_icons = get_user_meta( get_the_author_meta('ID'), '_zocker_social_profile_group',true );

                        if( is_array( $zocker_social_icons ) && !empty( $zocker_social_icons ) ) {
                            echo '<div class="d-flex gap-2 text-white">';
                            foreach( $zocker_social_icons as $singleicon ) {
                                if( ! empty( $singleicon['_zocker_social_profile_icon'] ) ) {
                                    echo '<a class="icon-btn3 size-40" href="'.esc_url( $singleicon['_zocker_lawyer_social_profile_link'] ).'"><i class="fab '.esc_attr( $singleicon['_zocker_social_profile_icon'] ).'"></i></a>';
                                }
                            }
                            echo '</div>';
                        }
                    echo '</div>';
                echo '</div>';
                echo '<!-- End of Post Author -->';
            }

        }
    }

    // Blog Details Comments hook function
    if( !function_exists('zocker_blog_details_comments_cb') ) {
        function zocker_blog_details_comments_cb( ) {
            if ( ! comments_open() ) {
                echo '<div class="blog-comment-area">';
                    echo zocker_heading_tag( array(
                        "tag"   => "h3",
                        "text"  => esc_html__( 'Comments are closed', 'zocker' ),
                        "class" => "inner-title"
                    ) );
                echo '</div>';
            }

            // comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
        }
    }

    // Blog Details Column end hook function
    if( !function_exists('zocker_blog_details_col_end_cb') ) {
        function zocker_blog_details_col_end_cb( ) {
            echo '</div>';
        }
    }

    // Blog Details Wrapper end hook function
    if( !function_exists('zocker_blog_details_wrapper_end_cb') ) {
        function zocker_blog_details_wrapper_end_cb( ) {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // page start wrapper hook function
    if( !function_exists('zocker_page_start_wrap_cb') ) {
        function zocker_page_start_wrap_cb( ) {
            if( is_page( 'cart' ) ){
                $section_class = "vs-cart-wrapper space-top newsletter-pb";
            }elseif( is_page( 'checkout' ) ){
                $section_class = "vs-checkout-wrapper space-top newsletter-pb";
            }else{
                $section_class = "space-top newsletter-pb";
            }
            echo '<section class="'.esc_attr( $section_class ).'">';
                echo '<div class="container">';
                    echo '<div class="row">';
        }
    }

    // page wrapper end hook function
    if( !function_exists('zocker_page_end_wrap_cb') ) {
        function zocker_page_end_wrap_cb( ) {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // page column wrapper start hook function
    if( !function_exists('zocker_page_col_start_wrap_cb') ) {
        function zocker_page_col_start_wrap_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $zocker_page_sidebar = zocker_opt('zocker_page_sidebar');
            }else {
                $zocker_page_sidebar = '1';
            }
            if( $zocker_page_sidebar == '2' && is_active_sidebar('zocker-page-sidebar') ) {
                echo '<div class="col-lg-8 order-last">';
            } elseif( $zocker_page_sidebar == '3' && is_active_sidebar('zocker-page-sidebar') ) {
                echo '<div class="col-lg-8">';
            } else {
                echo '<div class="col-lg-12">';
            }

        }
    }

    // page column wrapper end hook function
    if( !function_exists('zocker_page_col_end_wrap_cb') ) {
        function zocker_page_col_end_wrap_cb( ) {
            echo '</div>';
        }
    }

    // page sidebar hook function
    if( !function_exists('zocker_page_sidebar_cb') ) {
        function zocker_page_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $zocker_page_sidebar = zocker_opt('zocker_page_sidebar');
            }else {
                $zocker_page_sidebar = '1';
            }

            if( class_exists('ReduxFramework') ) {
                $zocker_page_layoutopt = zocker_opt('zocker_page_layoutopt');
            }else {
                $zocker_page_layoutopt = '3';
            }

            if( $zocker_page_layoutopt == '1' && $zocker_page_sidebar != 1 ) {
                get_sidebar('page');
            } elseif( $zocker_page_layoutopt == '2' && $zocker_page_sidebar != 1 ) {
                get_sidebar();
            }
        }
    }

    // page content hook function
    if( !function_exists('zocker_page_content_cb') ) {
        function zocker_page_content_cb( ) {
            if(  class_exists('woocommerce') && ( is_woocommerce() || is_cart() || is_checkout() || is_page('wishlist') || is_account_page() )  ) {
                echo '<div class="woocommerce--content">';
            } else {
                echo '<div class="page--content clearfix">';
            }

                the_content();

                // Link Pages
                zocker_link_pages();

            echo '</div>';
            // comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }

        }
    }

    if( !function_exists('zocker_blog_post_thumb_cb') ) {
        function zocker_blog_post_thumb_cb( ) {
            if( get_post_format() ) {
                $format = get_post_format();
            }else{
                $format = 'standard';
            }

            $zocker_post_slider_thumbnail = zocker_meta( 'post_format_slider' );

            if( !empty( $zocker_post_slider_thumbnail ) ){
                echo '<div class="blog-img-slider slick-carousel blog-image arrow-white">';
                    foreach( $zocker_post_slider_thumbnail as $single_image ){
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                        echo zocker_img_tag( array(
                            'url'   => esc_url( $single_image )
                        ) );
                        if( ! is_single() ){
                            echo '</a>';
                        }
                    }
                echo '</div>';
            }elseif( has_post_thumbnail() && $format == 'standard' ) {
                echo '<!-- Post Thumbnail -->';
                echo '<div class="blog-image image-scale-hover">';
                    if( ! is_single() ){
                        echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                    }
                        the_post_thumbnail();
                    if( ! is_single() ){
                        echo '</a>';
                    }
                echo '</div>';
                echo '<!-- End Post Thumbnail -->';
            }elseif( $format == 'video' ){
                if( has_post_thumbnail() && ! empty ( zocker_meta( 'post_format_video' ) ) ){
                    echo '<div class="blog-video blog-image">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                            the_post_thumbnail();
                        if( ! is_single() ){
                            echo '</a>';
                        }
                        echo '<a href="'.esc_url( zocker_meta( 'post_format_video' ) ).'" class="play-btn popup-video">';
                          echo '<i class="fas fa-play"></i>';
                        echo '</a>';
                    echo '</div>';
                }elseif( ! has_post_thumbnail() && ! is_single() ){
                    echo '<div class="blog-video">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                            echo zocker_embedded_media( array( 'video', 'iframe' ) );
                        if( ! is_single() ){
                            echo '</a>';
                        }
                    echo '</div>';
                }
            }elseif( $format == 'audio' ){
                $zocker_audio = zocker_meta( 'post_format_audio' );
                if( !empty( $zocker_audio ) ){
                    echo '<div class="blog-audio blog-image">';
                        echo wp_oembed_get( $zocker_audio );
                    echo '</div>';
                }elseif( ! is_single() ){
                    echo '<div class="blog-audio blog-image">';
                        echo zocker_embedded_media( array( 'audio', 'iframe' ) );
                    echo '</div>';
                }
            }

        }
    }

    if( !function_exists( 'zocker_blog_post_content_cb' ) ) {
        function zocker_blog_post_content_cb( ) {
            $allowhtml = array(
                'p'         => array(
                    'class'     => array()
                ),
                'span'      => array(),
                'a'         => array(
                    'href'      => array(),
                    'title'     => array()
                ),
                'br'        => array(),
                'em'        => array(),
                'strong'    => array(),
                'b'         => array(),
                'sup'       => array(),
                'sub'       => array(),
            );

            if( ! is_single() ){
                echo '<!-- Post Title -->';
                echo '<h2 class="blog-title h4 font-theme"><a href="'.esc_url( get_permalink() ).'">'.wp_kses( get_the_title(), $allowhtml ).'</a></h2>';
                echo '<!-- End Post Title -->';
            }

        }
    }

    if( ! function_exists( 'zocker_blog_postexcerpt_read_content_cb') ) {
        function zocker_blog_postexcerpt_read_content_cb( ) {
            if( class_exists( 'ReduxFramework' ) ) {
                $zocker_excerpt_length = zocker_opt('zocker_blog_postExcerpt');
            } else {
                $zocker_excerpt_length = '24';
            }
            $allowhtml = array(
                'p'         => array(
                    'class'     => array()
                ),
                'span'      => array(),
                'a'         => array(
                    'href'      => array(),
                    'title'     => array()
                ),
                'br'        => array(),
                'em'        => array(),
                'strong'    => array(),
                'b'         => array(),
            );

            echo '<div class="blog-content bg-smoke">';
            
                // Blog Title
                do_action( 'zocker_blog_post_content' );
                
                if( class_exists( 'ReduxFramework' ) ) {
                    $zocker_blog_admin = zocker_opt( 'zocker_blog_post_author' );
                    $zocker_blog_readmore_setting_val = zocker_opt('zocker_blog_readmore_setting');
                    if( $zocker_blog_readmore_setting_val == 'custom' ) {
                        $zocker_blog_readmore_setting = zocker_opt('zocker_blog_custom_readmore');
                    } else {
                        $zocker_blog_readmore_setting = __( 'Read More', 'zocker' );
                    }
                } else {
                    $zocker_blog_readmore_setting = __( 'Read More', 'zocker' );
                    $zocker_blog_admin = true;
                }

                echo '<!-- Post Summary -->';
                    echo zocker_paragraph_tag( array(
                        "text"  => wp_kses( wp_trim_words( get_the_excerpt(), $zocker_excerpt_length, '' ), $allowhtml ),
                        "class" => 'blog-text',
                    ) );
                echo '<!-- End Post Summary -->';

                if( $zocker_blog_admin || !empty( $zocker_blog_readmore_setting ) ){
                    if( !empty( $zocker_blog_readmore_setting ) ){
                        echo '<!-- Button -->';
                            echo '<a href="'.esc_url( get_permalink() ).'" class="link-btn">'.esc_html( $zocker_blog_readmore_setting ).' <i class="fal fa-long-arrow-right"></i></a>';
                        echo '<!-- End Button -->';
                    }
                }
            echo '</div>';
        }
    }