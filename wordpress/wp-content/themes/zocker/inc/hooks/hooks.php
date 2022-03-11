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

	/**
	* Hook for preloader
	*/
	add_action( 'zocker_preloader_wrap', 'zocker_preloader_wrap_cb', 10 );

	/**
	* Hook for offcanvas cart
	*/
	add_action( 'zocker_main_wrapper_start', 'zocker_main_wrapper_start_cb', 10 );

	/**
	* Hook for Header
	*/
	add_action( 'zocker_header', 'zocker_header_cb', 10 );
	
	/**
	* Hook for Blog Start Wrapper
	*/
	add_action( 'zocker_blog_start_wrap', 'zocker_blog_start_wrap_cb', 10 );
	
	/**
	* Hook for Blog Column Start Wrapper
	*/
    add_action( 'zocker_blog_col_start_wrap', 'zocker_blog_col_start_wrap_cb', 10 );
	
	/**
	* Hook for Blog Column End Wrapper
	*/
    add_action( 'zocker_blog_col_end_wrap', 'zocker_blog_col_end_wrap_cb', 10 );
	
	/**
	* Hook for Blog Column End Wrapper
	*/
    add_action( 'zocker_blog_end_wrap', 'zocker_blog_end_wrap_cb', 10 );
	
	/**
	* Hook for Blog Pagination
	*/
    add_action( 'zocker_blog_pagination', 'zocker_blog_pagination_cb', 10 );
    
    /**
	* Hook for Blog Content
	*/
	add_action( 'zocker_blog_content', 'zocker_blog_content_cb', 10 );
    
    /**
	* Hook for Blog Sidebar
	*/
	add_action( 'zocker_blog_sidebar', 'zocker_blog_sidebar_cb', 10 );
    
    /**
	* Hook for Blog Details Sidebar
	*/
	add_action( 'zocker_blog_details_sidebar', 'zocker_blog_details_sidebar_cb', 10 );

	/**
	* Hook for Blog Details Wrapper Start
	*/
	add_action( 'zocker_blog_details_wrapper_start', 'zocker_blog_details_wrapper_start_cb', 10 );

	/**
	* Hook for Blog Details Post Meta
	*/
	add_action( 'zocker_blog_post_meta', 'zocker_blog_post_meta_cb', 10 );

	/**
	* Hook for Blog Details Post Share Options
	*/
	add_action( 'zocker_blog_details_share_options', 'zocker_blog_details_share_options_cb', 10 );

	/**
	* Hook for Blog Details Post Author Bio
	*/
	add_action( 'zocker_blog_details_author_bio', 'zocker_blog_details_author_bio_cb', 10 );

	/**
	* Hook for Blog Details Tags and Categories
	*/
	add_action( 'zocker_blog_details_tags_and_categories', 'zocker_blog_details_tags_and_categories_cb', 10 );

	/**
	* Hook for Blog Deatils Comments
	*/
	add_action( 'zocker_blog_details_comments', 'zocker_blog_details_comments_cb', 10 );

	/**
	* Hook for Blog Deatils Column Start
	*/
	add_action('zocker_blog_details_col_start','zocker_blog_details_col_start_cb');

	/**
	* Hook for Blog Deatils Column End
	*/
	add_action('zocker_blog_details_col_end','zocker_blog_details_col_end_cb');

	/**
	* Hook for Blog Deatils Wrapper End
	*/
	add_action('zocker_blog_details_wrapper_end','zocker_blog_details_wrapper_end_cb');
	
	/**
	* Hook for Blog Post Thumbnail
	*/
	add_action('zocker_blog_post_thumb','zocker_blog_post_thumb_cb');
    
	/**
	* Hook for Blog Post Content
	*/
	add_action('zocker_blog_post_content','zocker_blog_post_content_cb');
	
    
	/**
	* Hook for Blog Post Excerpt And Read More Button
	*/
	add_action('zocker_blog_postexcerpt_read_content','zocker_blog_postexcerpt_read_content_cb');
	
	/**
	* Hook for footer content
	*/
	add_action( 'zocker_footer_content', 'zocker_footer_content_cb', 10 );
	
	/**
	* Hook for main wrapper end
	*/
	add_action( 'zocker_main_wrapper_end', 'zocker_main_wrapper_end_cb', 10 );
	
	/**
	* Hook for Back to Top Button
	*/
	add_action( 'zocker_back_to_top', 'zocker_back_to_top_cb', 10 );

	/**
	* Hook for Page Start Wrapper
	*/
	add_action( 'zocker_page_start_wrap', 'zocker_page_start_wrap_cb', 10 );

	/**
	* Hook for Page End Wrapper
	*/
	add_action( 'zocker_page_end_wrap', 'zocker_page_end_wrap_cb', 10 );

	/**
	* Hook for Page Column Start Wrapper
	*/
	add_action( 'zocker_page_col_start_wrap', 'zocker_page_col_start_wrap_cb', 10 );

	/**
	* Hook for Page Column End Wrapper
	*/
	add_action( 'zocker_page_col_end_wrap', 'zocker_page_col_end_wrap_cb', 10 );

	/**
	* Hook for Page Column End Wrapper
	*/
	add_action( 'zocker_page_sidebar', 'zocker_page_sidebar_cb', 10 );

	/**
	* Hook for Page Content
	*/
	add_action( 'zocker_page_content', 'zocker_page_content_cb', 10 );