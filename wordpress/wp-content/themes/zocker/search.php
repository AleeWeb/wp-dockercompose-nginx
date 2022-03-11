<?php
/**
 * @Packge     : Zocker
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// Block direct access
if ( ! defined('ABSPATH') ) {
    exit;
}   
    // Header
    get_header();

    /**
    * 
    * Hook for Blog Start Wrapper
    *
    * Hook zocker_blog_start_wrap
    *
    * @Hooked zocker_blog_start_wrap_cb 10
    *  
    */
    do_action( 'zocker_blog_start_wrap' );

    /**
    * 
    * Hook for Blog Column Start Wrapper
    *
    * Hook zocker_blog_col_start_wrap
    *
    * @Hooked zocker_blog_col_start_wrap_cb 10
    *  
    */
    do_action( 'zocker_blog_col_start_wrap' );

    echo '<div class="row">';
        if( have_posts() ) {
            while( have_posts() ) {
                the_post();
                if ( is_active_sidebar( 'zocker-blog-sidebar' ) ) {
                    $column_class = 'col-md-6';
                }else{
                    $column_class = 'col-md-4';
                }
                echo '<div class="'.esc_attr( $column_class ).'">';
                    echo '<div class="vs-blog blog-grid">';
                        if( has_post_thumbnail() ){
                            echo '<div class="blog-img image-scale-hover">';
                                echo '<a href="'.esc_url( get_the_permalink() ).'">';
                                    the_post_thumbnail( );
                                echo '</a>';
                            echo '</div>';
                        }
                        echo '<div class="blog-content">';

                            if( get_the_title() ){
                                echo '<!-- Post Title -->';
                                echo '<h4 class="blog-title fw-semibold"><a href="'.esc_url( get_permalink() ).'">'.esc_html( get_the_title( ) ).'</a></h4>';
                                echo '<!-- End Post Title -->';
                            }

                            echo '<div class="blog-meta">';
                                echo '<span class="pr-15"><a href="'.esc_url( zocker_blog_date_permalink() ).'">';
                                    echo '<time datetime="'.esc_attr( get_the_date( DATE_W3C ) ).'">'.esc_html( get_the_date() ).'</time>';
                                echo '</a></span>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
            wp_reset_postdata();
        } else{
            get_template_part('templates/content','none');
        }
    echo '</div>';

    /**
    * 
    * Hook for Blog Pagination
    *
    * Hook zocker_blog_pagination
    *
    * @Hooked zocker_blog_pagination_cb 10
    *  
    */
    do_action( 'zocker_blog_pagination' ); 

    /**
    * 
    * Hook for Blog Column End Wrapper
    *
    * Hook zocker_blog_col_end_wrap
    *
    * @Hooked zocker_blog_col_end_wrap_cb 10
    *  
    */
    do_action( 'zocker_blog_col_end_wrap' ); 

    /**
    * 
    * Hook for Blog Sidebar
    *
    * Hook zocker_blog_sidebar
    *
    * @Hooked zocker_blog_sidebar_cb 10
    *  
    */
    do_action( 'zocker_blog_sidebar' );     
        
    /**
    * 
    * Hook for Blog End Wrapper
    *
    * Hook zocker_blog_end_wrap
    *
    * @Hooked zocker_blog_end_wrap_cb 10
    *  
    */
    do_action( 'zocker_blog_end_wrap' );

    //footer
    get_footer();