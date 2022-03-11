<?php
/**
 * @Packge     : Zocker
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
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

    /**
    * 
    * Hook for Blog Content
    *
    * Hook zocker_blog_content
    *
    * @Hooked zocker_blog_content_cb 10
    *  
    */
    do_action( 'zocker_blog_content' );

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