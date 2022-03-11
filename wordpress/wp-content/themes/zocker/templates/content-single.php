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

    zocker_setPostViews( get_the_ID() );

    ?>
    <div <?php post_class(); ?> >
    <?php
        if( class_exists('ReduxFramework') ) {
            $zocker_post_details_title_position = zocker_opt('zocker_post_details_title_position');
        } else {
            $zocker_post_details_title_position = 'header';
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
        echo '<div class="vs-blog">';
            // Blog Post Thumbnail
            do_action( 'zocker_blog_post_thumb' );

            if( $zocker_post_details_title_position != 'header' ) {
                echo '<h2 class="blog-title h1">'.wp_kses( get_the_title(), $allowhtml ).'</h2>';
            }
            // Blog Post Meta
            do_action( 'zocker_blog_post_meta' );

            if( get_the_content() ){
                echo '<div class="blog-content bg-smoke">';
                    echo '<div class="content-wrapper">';
                        the_content();
                        // Link Pages
                        zocker_link_pages();
                        
                    echo '</div>';
                    
                    $zocker_post_tag = get_the_tags();
            
                    if( class_exists('ReduxFramework') ) {
                        $zocker_post_details_share_options = zocker_opt('zocker_post_details_share_options');
                    } else {
                        $zocker_post_details_share_options = false;
                    }
                    
                    if( ! empty( $zocker_post_tag ) ){
                        echo '<!-- Share Links Area -->';
                        echo '<div class="blog-share-links d-md-flex align-items-center">';
                            
                            if( is_array( $zocker_post_tag ) && ! empty( $zocker_post_tag ) ){
                                if( count( $zocker_post_tag ) > 1 ){
                                    $tag_text = __( 'Tags:', 'zocker' );
                                }else{
                                    $tag_text = __( 'Tag:', 'zocker' );
                                }
                                echo '<h5 class="font-theme text-normal d-inline-block mb-3 mb-md-0 mr-20">'.$tag_text.'</h5>';
                                echo '<div class="tagcloud">';
                                    foreach( $zocker_post_tag as $tags ){
                                        echo '<a href="'.esc_url( get_tag_link( $tags->term_id ) ).'">'.esc_html( $tags->name ).'</a>';
                                    }
                                echo '</div>';
                            }
                        echo '</div>';
                    }
                    if( function_exists( 'zocker_social_sharing_buttons' ) && $zocker_post_details_share_options ){
                        /**
                        *
                        * Hook for Blog Details Share Options
                        *
                        * Hook zocker_blog_details_share_options
                        *
                        * @Hooked zocker_blog_details_share_options_cb 10
                        *
                        */
                        do_action( 'zocker_blog_details_share_options' );
                        echo '<!-- Share Links Area end -->';
                    }
                    
                echo '</div>';
            }
        echo '</div>';
        

        /**
        *
        * Hook for Blog Details Author Bio
        *
        * Hook zocker_blog_details_author_bio
        *
        * @Hooked zocker_blog_details_author_bio_cb 10
        *
        */
        do_action( 'zocker_blog_details_author_bio' );

        /**
        *
        * Hook for Blog Details Related Post
        *
        * Hook zocker_blog_details_related_post
        *
        * @Hooked zocker_blog_details_related_post_cb 10
        *
        */
        do_action( 'zocker_blog_details_related_post' );

        /**
        *
        * Hook for Blog Details Comments
        *
        * Hook zocker_blog_details_comments
        *
        * @Hooked zocker_blog_details_comments_cb 10
        *
        */
        do_action( 'zocker_blog_details_comments' );

    echo '</div>';