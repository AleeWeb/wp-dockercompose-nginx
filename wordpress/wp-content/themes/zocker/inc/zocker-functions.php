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
    exit;
}

 // theme option callback
function zocker_opt( $id = null, $url = null ){
    global $zocker_opt;

    if( $id && $url ){

        if( isset( $zocker_opt[$id][$url] ) && $zocker_opt[$id][$url] ){
            return $zocker_opt[$id][$url];
        }
    }else{
        if( isset( $zocker_opt[$id] )  && $zocker_opt[$id] ){
            return $zocker_opt[$id];
        }
    }
}


// theme logo
function zocker_theme_logo() {
    // escaping allow html
    $allowhtml = array(
        'a'    => array(
            'href' => array()
        ),
        'span' => array(),
        'i'    => array(
            'class' => array()
        )
    );
    $siteUrl = home_url('/');
    if( has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $siteLogo = '';
        $siteLogo .= '<a class="logo" href="'.esc_url( $siteUrl ).'">';
        $siteLogo .= zocker_img_tag( array(
            "class" => "img-fluid logo-img",
            "url"   => esc_url( wp_get_attachment_image_url( $custom_logo_id, 'full') )
        ) );
        $siteLogo .= '</a>';

        return $siteLogo;
    } elseif( !zocker_opt('zocker_text_title') && zocker_opt('zocker_site_logo', 'url' )  ){

        $siteLogo = '<img class="img-fluid logo-img" src="'.esc_url( zocker_opt('zocker_site_logo', 'url' ) ).'" alt="'.esc_attr__( 'logo', 'zocker' ).'" />';
        return '<a class="logo" href="'.esc_url( $siteUrl ).'">'.wp_kses_post( $siteLogo ).'</a>';


    }elseif( zocker_opt('zocker_text_title') ){
        return '<h2 class="mb-0"><a class="logo" href="'.esc_url( $siteUrl ).'">'.wp_kses( zocker_opt('zocker_text_title'), $allowhtml ).'</a></h2>';
    }else{
        return '<h2 class="mb-0"><a class="logo" href="'.esc_url( $siteUrl ).'">'.esc_html( get_bloginfo('name') ).'</a></h2>';
    }
}

// custom meta id callback
function zocker_meta( $id = '' ){
    $value = get_post_meta( get_the_ID(), '_zocker_'.$id, true );
    return $value;
}


// Blog Date Permalink
function zocker_blog_date_permalink() {
    $year  = get_the_time('Y');
    $month_link = get_the_time('m');
    $day   = get_the_time('d');
    $link = get_day_link( $year, $month_link, $day);
    return $link;
}

//audio format iframe match
function zocker_iframe_match() {
    $audio_content = zocker_embedded_media( array('audio', 'iframe') );
    $iframe_match = preg_match("/\iframe\b/i",$audio_content, $match);
    return $iframe_match;
}


//Post embedded media
function zocker_embedded_media( $type = array() ){
    $content = do_shortcode( apply_filters( 'the_content', get_the_content() ) );
    $embed   = get_media_embedded_in_content( $content, $type );


    if( in_array( 'audio' , $type) ){
        if( count( $embed ) > 0 ){
            $output = str_replace( '?visual=true', '?visual=false', $embed[0] );
        }else{
           $output = '';
        }

    }else{
        if( count( $embed ) > 0 ){
            $output = $embed[0];
        }else{
           $output = '';
        }
    }
    return $output;
}


// WP post link pages
function zocker_link_pages(){
    wp_link_pages( array(
        'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'zocker' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
        'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'zocker' ) . ' </span>%',
        'separator'   => '<span class="screen-reader-text">, </span>',
    ) );
}


// Data Background image attr
function zocker_data_bg_attr( $imgUrl = '' ){
    return 'data-bg-img="'.esc_url( $imgUrl ).'"';
}

// image alt tag
function zocker_image_alt( $url = '' ){
    if( $url != '' ){
        // attachment id by url
        $attachmentid = attachment_url_to_postid( esc_url( $url ) );
       // attachment alt tag
        $image_alt = get_post_meta( esc_html( $attachmentid ) , '_wp_attachment_image_alt', true );
        if( $image_alt ){
            return $image_alt ;
        }else{
            $filename = pathinfo( esc_url( $url ) );
            $alt = str_replace( '-', ' ', $filename['filename'] );
            return $alt;
        }
    }else{
       return;
    }
}


// Flat Content wysiwyg output with meta key and post id

function zocker_get_textareahtml_output( $content ) {
    global $wp_embed;

    $content = $wp_embed->autoembed( $content );
    $content = $wp_embed->run_shortcode( $content );
    $content = wpautop( $content );
    $content = do_shortcode( $content );

    return $content;
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */

function zocker_pingback_header() {
    if ( is_singular() && pings_open() ) {
        echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
    }
}
add_action( 'wp_head', 'zocker_pingback_header' );


// Excerpt More
function zocker_excerpt_more( $more ) {
    return '...';
}

add_filter( 'excerpt_more', 'zocker_excerpt_more' );


// zocker comment template callback
function zocker_comment_callback( $comment, $args, $depth ) {
        $add_below = 'comment';
    ?>
    <li <?php comment_class( array('vs-comment') ); ?>>
        <div id="comment-<?php comment_ID() ?>" class="vs-post-comment">
            <?php
                if( get_avatar( $comment, 100 )  ) :
            ?>
            <!-- Author Image -->
            <div class="author-img">
                <?php
                    if ( $args['avatar_size'] != 0 ) {
                        echo get_avatar( $comment, 100 );
                    }
                ?>
            </div>
            <!-- Author Image -->
            <?php
                endif;
            ?>
            <!-- Comment Content -->
            <div class="comment-content">
                <div class="comment-top">
                    <div class="comment-author">
                        <h4 class="name"><?php echo esc_html( ucwords( get_comment_author() ) ); ?></h4>
                        <span class="commented-on mb-10"> <?php printf( esc_html__('%1$s', 'zocker'), get_comment_date() ); ?> </span>
                    </div>
                    <div class="reply_and_edit">
                        <?php
                            comment_reply_link(array_merge( $args, array( 'add_below' => $add_below, 'depth' => 1, 'max_depth' => 5, 'reply_text' => '<i class="fas fa-reply"></i>Reply' ) ) );
                        ?>
                        <span class="comment-edit-link pl-10"><?php edit_comment_link( esc_html__( '(Edit)', 'zocker' ), '  ', '' ); ?></span>
                    </div>
                </div>
                <p class="text"><?php echo get_comment_text(); ?></p>
                <?php if ( $comment->comment_approved == '0' ) : ?>
                <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'zocker' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Comment Content -->
<?php
}

//body class
add_filter( 'body_class', 'zocker_body_class' );
function zocker_body_class( $classes ) {
    if( class_exists('ReduxFramework') ) {
        $zocker_blog_single_sidebar = zocker_opt('zocker_blog_single_sidebar');
        if( ($zocker_blog_single_sidebar != '2' && $zocker_blog_single_sidebar != '3' ) || ! is_active_sidebar('zocker-blog-sidebar') ) {
            $classes[] = 'no-sidebar';
        }
    } else {
        if( !is_active_sidebar('zocker-blog-sidebar') ) {
            $classes[] = 'no-sidebar';
        }
    }
    return $classes;
}


function zocker_footer_global_option(){
    // Zocker Footer Widget Column Seclector
    $zocker_footer_widget_col = zocker_opt( 'zocker_footercol_switch' );

    if( class_exists( 'ReduxFramework' ) ){
        $zocker_footer_widget_style = zocker_opt( 'zocker_footerwidget_style' );
    }else{
        $zocker_footer_widget_style = '2';
    }

    $zocker_footer_one = '';
    $zocker_footer_two = '';
    $zocker_footer_four = '';
    $zocker_footer_widget_col_val = "";
	if( $zocker_footer_widget_col == '1' ) {
        $zocker_footer_widget_col_val = '6';
    }elseif( $zocker_footer_widget_col == '2' ) {
        $zocker_footer_widget_col_val = '4';
    }else{
        if( $zocker_footer_widget_style == '2' ){
            $zocker_footer_one   = '4';
            $zocker_footer_two   = '2';
            $zocker_footer_four = '3';
        }else{
            $zocker_footer_widget_col_val = '3';
        }
    }
    // Zocker Widget Enable Disable
    if( class_exists( 'ReduxFramework' ) && ( is_active_sidebar( 'zocker-footer-1' ) || is_active_sidebar( 'zocker-footer-2' ) || is_active_sidebar( 'zocker-footer-3' ) || is_active_sidebar( 'zocker-footer-4' ) ) ){
        $zocker_footer_widget_enable = zocker_opt( 'zocker_footerwidget_enable' );
    }else{
        $zocker_footer_widget_enable = '';
    }
    // Zocker Footer Bottom Enable Disable
    if( class_exists( 'ReduxFramework' ) ){
        $zocker_footer_bottom_active = zocker_opt( 'zocker_disable_footer_bottom' );
        $zocker_newsletter_active    = zocker_opt( 'zocker_newsletter_enable' );
    }else{
        $zocker_footer_bottom_active = '1';
        $zocker_newsletter_active    = '';
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
            'title'     => array(),
            'class'     => array(),
        ),
        'br'        => array(),
        'em'        => array(),
        'strong'    => array(),
        'b'         => array(),
    );
    if( $zocker_newsletter_active == '1' ){
        echo '<section class="vs-newsletter-wrapper bg-dark z-index-step1  ">';
            echo '<div class="container">';
                echo '<div class="position-relative">';
                    echo '<div class="newsletter-bg inner-wrapper position-absolute top-50 start-50 translate-middle w-100 px-60 py-40">';
                        echo '<div class="row align-items-center justify-content-center">';
                            echo '<div class="col-xl-6 text-center text-xl-start mb-3 mb-xl-0">';
                                if( ! empty( zocker_opt( 'zocker_newsletter_subtitle' ) ) ){
                                    echo '<span class="sub-title2 mt-2">'.esc_html( zocker_opt( 'zocker_newsletter_subtitle' ) ).'</span>';
                                }
                                if( ! empty( zocker_opt( 'zocker_newsletter_title' ) ) ){
                                    echo '<h2 class="mb-0 text-white">'.esc_html( zocker_opt( 'zocker_newsletter_title' ) ).'</h2>';
                                }
                            echo '</div>';
                            echo '<div class="col-md-10 col-lg-8 col-xl-6">';
                                echo '<form action="#" class="newsletter-style1  newsletter-form">';
                                    echo '<div class="newsletter-wrapper d-md-flex">';
                                        echo '<input required type="email" class="form-control" placeholder="'.esc_attr__( 'Enter email address', 'zocker' ).'">';
                                        echo '<button class="vs-btn gradient-btn">'.esc_html__( 'Subscribe Now', 'zocker' ).'</button>';
                                    echo '</div>';
                                echo '</form>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</section>';
    }
    if( $zocker_footer_widget_enable == '1' || $zocker_footer_bottom_active == '1' ){
        echo '<div class="footer-wrapper footer-layout1 bg-fluid bg-major-black position-relative">';
            echo '<div class="footer-background bg-fluid d-none d-none d-xl-block position-absolute start-0 top-0 w-100 h-100"></div>';
            echo '<!-- Footer -->';
            
            if( $zocker_footer_widget_enable == '1' ):
                echo '<div class="footer-widget-wrapper dark-style1 z-index-common">';
                    echo '<div class="container">';
                        echo '<div class="widget-area">';
                            echo '<div class="row justify-content-between">';
                                // Footer One
                                if( $zocker_footer_widget_col == '1' || $zocker_footer_widget_col == '2' || $zocker_footer_widget_col == '3' ) :
                                    if( is_active_sidebar( 'zocker-footer-1' ) ) :
                                        echo '<div class="col-xl-'.esc_attr( $zocker_footer_one.$zocker_footer_widget_col_val ).' col-lg-3 col-sm-6 col-md-6">';
                                             dynamic_sidebar( 'zocker-footer-1' );
                                        echo '</div>';
                                    endif;
                                endif;
                                // Footer Two
                                if( $zocker_footer_widget_col == '1' || $zocker_footer_widget_col == '2' || $zocker_footer_widget_col == '3' ) :
                                    if( is_active_sidebar( 'zocker-footer-2' ) ) :
                                        echo '<div class="col-xl-'.esc_attr( $zocker_footer_two.$zocker_footer_widget_col_val ).'  col-lg-3 col-sm-6 col-md-6">';
                                             dynamic_sidebar( 'zocker-footer-2' );
                                        echo '</div>';
                                    endif;
                                endif;
                                // Footer Three
                                if( $zocker_footer_widget_col != '1' ) :
                                    if( is_active_sidebar( 'zocker-footer-3' ) ) :
                                        echo '<div class="col-xl-'.esc_attr( $zocker_footer_two.$zocker_footer_widget_col_val ).'  col-lg-3 col-sm-6 col-md-6">';
                                             dynamic_sidebar( 'zocker-footer-3' );
                                        echo '</div>';
                                    endif;
                                endif;
                                // Footer Four
                                if( $zocker_footer_widget_col == '3' ) :
                                    if( is_active_sidebar( 'zocker-footer-4' ) ) :
                                        echo '<div class="col-xl-'.esc_attr( $zocker_footer_four.$zocker_footer_widget_col_val ).'  col-lg-3 col-sm-6 col-md-6">';
                                            dynamic_sidebar( 'zocker-footer-4' );
                                        echo '</div>';
                                    endif;
                                endif;
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            endif;
            if( $zocker_footer_bottom_active == '1' ){
                echo '<div class="footer-copyright bg-black z-index-step1">';
                    echo '<div class="container">';
                        echo '<div class="row">';
                            echo '<div class="col-xl-7 d-none d-xl-block">';
                                if( has_nav_menu( 'footer-menu' ) ){
                                    echo '<div class="footer-menu">';
                                        wp_nav_menu( array(
                                            "theme_location"    => 'footer-menu',
                                            "container"         => '',
                                            "menu_class"        => ''
                                        ) );
                                    echo '</div>';
                                }
                            echo '</div>';
                            echo '<div class="col-xl-5 align-self-center text-center py-3 text-xl-end">';
                                if( ! empty( zocker_opt( 'zocker_copyright_text' ) ) ){
                                    echo '<p class="text-light fw-bold text-bold mb-0">'.wp_kses( zocker_opt( 'zocker_copyright_text' ), $allowhtml ).'</p>';
                                }
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        echo '</div>';
    }
}

function zocker_social_icon(){
    $zocker_social_icon = zocker_opt( 'zocker_social_links' );
    if( ! empty( $zocker_social_icon ) && isset( $zocker_social_icon ) ){
        foreach( $zocker_social_icon as $social_icon ){
            if( ! empty( $social_icon['title'] ) ){
                echo '<li><a href="'.esc_url( $social_icon['url'] ).'"><i class="'.esc_attr( $social_icon['title'] ).'"></i>'.esc_html( $social_icon['description'] ).'</a></li>';
            }
        }
    }
}

// global header
function zocker_global_header_option() {
    zocker_global_header();
    
    if( class_exists( 'ReduxFramework' ) ){
        $zocker_language_show           = zocker_opt( 'zocker_language_switcher' );
        $zocker_search_show             = zocker_opt( 'zocker_search_switcher' );
        $zocker_offcanvas_show          = zocker_opt( 'zocker_offcanvas_switcher' );
    }else{
        $zocker_language_show           = '';
        $zocker_search_show             = '';
        $zocker_offcanvas_show = '';
    }
    if( class_exists( 'ReduxFramework' ) ){
        echo '<div class="header-wrapper header-layout1 w-100 z-index-step1">';
            zocker_header_topbar();
            echo '<div class="header-main" data-overlay="black" data-opacity="10">';
                echo '<div class="container position-relative">';
                    echo '<div class="row align-items-center justify-content-between">';
                        echo '<div class="col-auto">';
                            echo '<div class="header-logo">';
                                echo zocker_theme_logo();
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="col-auto">';
                            echo '<nav class="main-menu menu-style1 d-none d-lg-block" data-expand="992">';
                                if( has_nav_menu('primary-menu') ) {
                                    wp_nav_menu( array(
                                        "theme_location"    => 'primary-menu',
                                        "container"         => '',
                                        "menu_class"        => ''
                                    ) );
                                }
                            echo '</nav>';
                            echo '<button type="button" class="vs-menu-toggle text-white d-block d-lg-none ms-auto"> <i class="far fa-bars"></i></button>';
                        echo '</div>';
                        
                        echo '<div class="col-auto d-none d-xl-block">';
                            echo '<div class="header-right d-flex align-items-center justify-content-end">';
                                if( ! empty( zocker_opt( 'zocker_live_stream_text' ) ) ){
                                    echo '<a href="'.esc_url( zocker_opt( 'zocker_live_stream_url' ) ).'" class="vs-btn outline1 d-none d-xl-inline-block"><i class="fab fa-twitch"></i><strong>'.esc_html( zocker_opt( 'zocker_live_stream_text' ) ).'</strong></a>';
                                }
                                echo '<ul class="header-list1 list-style-none ml-30">';
                                    if( class_exists( 'GTranslate' ) && $zocker_language_show ){
                                        echo '<li>';
                                            echo '<button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                                                echo '<i class="fas fa-globe flag radius-circle"></i>';
                                            echo '</button>';
                                            echo '<ul class="dropdown-menu">';
                                                echo '<li>';
                                                    echo do_shortcode('[gtranslate]');
                                                echo '</li>';
                                            echo '</ul>';
                                        echo '</li>';
                                    }
                                    if( $zocker_search_show ){
                                        echo '<li>';
                                            echo '<button class="searchBoxTggler"><i class="far fa-search"></i></button>';
                                        echo '</li>';
                                    }
                                    if( $zocker_offcanvas_show && is_active_sidebar( 'zocker-offcanvas-sidebar' ) ){
                                        echo '<li>';
                                            echo '<button class="sideMenuToggler"><i class="fal fa-grip-horizontal fs-2"></i></button>';
                                        echo '</li>';
                                    }
                                echo '</ul>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }else{
        echo '<div class="header-wrapper header-layout1 w-100 z-index-step1">';
            echo '<div class="header-main" data-overlay="black" data-opacity="10">';
                echo '<div class="container position-relative">';
                    echo '<div class="row align-items-center justify-content-between">';
                        echo '<div class="col-auto py-3 py-xl-0">';
                            echo '<div class="header-logo">';
                                echo zocker_theme_logo();
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="col-auto text-end text-xl-start">';
                            if( has_nav_menu( 'primary-menu' ) ) {
                                echo '<nav class="main-menu menu-style1 d-none d-lg-block" data-expand="992">';
                                    wp_nav_menu( array(
                                        "theme_location"    => 'primary-menu',
                                        "container"         => '',
                                        "menu_class"        => ''
                                    ) );
                                echo '</nav>';
                            }
                            if( has_nav_menu( 'mobile-menu' ) ) {
                                echo '<button type="button" class="vs-menu-toggle text-white d-block d-lg-none ms-auto"> <i class="far fa-bars"></i></button>';
                            }
                        echo '</div>';
                        echo '<div class="col-auto d-none d-lg-block">';
                            echo '<div class="header-right d-flex align-items-center justify-content-end">';
                                echo '<ul class="header-list1 list-style-none ml-30">';
                                    echo '<li>';
                                        echo '<button class="searchBoxTggler"><i class="far fa-search"></i></button>';
                                    echo '</li>';
                                echo '</ul>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
}

// zocker woocommerce breadcrumb
function zocker_woo_breadcrumb( $args ) {
    return array(
        'delimiter'   => '',
        'wrap_before' => '<ul class="breadcumb-menu-style1 mx-auto fs-xs text-black">',
        'wrap_after'  => '</ul>',
        'before'      => '<li>',
        'after'       => '</li>',
        'home'        => _x( 'Home', 'breadcrumb', 'zocker' ),
    );
}

add_filter( 'woocommerce_breadcrumb_defaults', 'zocker_woo_breadcrumb' );

function zocker_custom_search_form( $class ) {
    echo '<!-- Search Form -->';
    echo '<form role="search" method="get" action="'.esc_url( home_url( '/' ) ).'" class="'.esc_attr( $class ).'">';
        echo '<label class="searchIcon">';
            echo zocker_img_tag( array(
                "url"   => esc_url( get_theme_file_uri( '/assets/img/search-2.svg' ) ),
                "class" => "svg"
            ) );
            echo '<input value="'.esc_html( get_search_query() ).'" name="s" required type="search" placeholder="'.esc_attr__('What are you looking for?', 'zocker').'">';
        echo '</label>';
    echo '</form>';
    echo '<!-- End Search Form -->';
}



//Fire the wp_body_open action.
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

//Remove Tag-Clouds inline style
add_filter( 'wp_generate_tag_cloud', 'zocker_remove_tagcloud_inline_style',10,1 );
function zocker_remove_tagcloud_inline_style( $input ){
   return preg_replace('/ style=("|\')(.*?)("|\')/','',$input );
}

// password protected form
add_filter('the_password_form','zocker_password_form',10,1);
function zocker_password_form( $output ) {
    $output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post"><div class="theme-input-group">
        <input name="post_password" type="password" class="theme-input-style" placeholder="'.esc_attr__( 'Enter Password','zocker' ).'">
        <button type="submit" class="submit-btn btn-fill">'.esc_html__( 'Enter','zocker' ).'</button></div></form>';
    return $output;
}

function zocker_setPostViews( $postID ) {
    $count_key  = 'post_views_count';
    $count      = get_post_meta( $postID, $count_key, true );
    if( $count == '' ){
        $count = 0;
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
    }else{
        $count++;
        update_post_meta( $postID, $count_key, $count );
    }
}

function zocker_getPostViews( $postID ){
    $count_key  = 'post_views_count';
    $count      = get_post_meta( $postID, $count_key, true );
    if( $count == '' ){
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
        return __( '0', 'zocker' );
    }
    return $count;
}


/* This code filters the Categories archive widget to include the post count inside the link */
add_filter( 'wp_list_categories', 'zocker_cat_count_span' );
function zocker_cat_count_span( $links ) {
    $links = str_replace('</a> (', '</a> <span class="category-number">(', $links);
    $links = str_replace(')', ')</span>', $links);
    return $links;
}

/* This code filters the Archive widget to include the post count inside the link */
add_filter( 'get_archives_link', 'zocker_archive_count_span' );
function zocker_archive_count_span( $links ) {
    $links = str_replace('</a>&nbsp;(', '</a> <span class="category-number">(', $links);
    $links = str_replace(')', ')</span>', $links);
	return $links;
}

// Zocker Default Header
if( ! function_exists( 'zocker_global_header' ) ){
    function zocker_global_header(){
        // Mobile Menu
        echo '<div class="vs-menu-wrapper">';
            echo '<div class="vs-menu-area bg-dark">';
                echo '<button class="vs-menu-toggle"><i class="fal fa-times"></i></button>';
                echo '<div class="mobile-logo">';
                    echo zocker_theme_logo();
                echo '</div>';
                if( has_nav_menu( 'mobile-menu' ) ){
                    echo '<div class="vs-mobile-menu link-inherit">';
                        wp_nav_menu( array(
                            "theme_location"    => 'mobile-menu',
                            "container"         => '',
                            "menu_class"        => ''
                        ) );
                    echo '</div>';
                }
            echo '</div>';
        echo '</div>';
        // Search Popup
        echo '<div class="popup-search-box d-none d-lg-block">';
            echo '<button class="searchClose border-theme text-theme"><i class="fal fa-times"></i></button>';
            echo '<form action="'.esc_url( home_url( '/' ) ).'">';
                echo '<input name="s" value="'.get_search_query().'" type="text" class="border-theme" placeholder="'.esc_attr__( 'What are you looking for', 'zocker' ).'">';
                echo '<button type="submit"><i class="fal fa-search"></i></button>';
            echo '</form>';
        echo '</div>';
        // Offcanvas Menu
        if( is_active_sidebar( 'zocker-offcanvas-sidebar' ) ){
            echo '<div class="sidemenu-wrapper d-none d-lg-block  ">';
                echo '<div class="sidemenu-content bg-light-dark">';
                    echo '<button class="closeButton border-theme text-theme bg-theme-hover sideMenuCls"><i class="far fa-times"></i></button>';
                    dynamic_sidebar( 'zocker-offcanvas-sidebar' );
                echo '</div>';
            echo '</div>';
        }
    }
}

if( ! function_exists( 'zocker_header_topbar' ) ){
    function zocker_header_topbar(){
        if( class_exists( 'ReduxFramework' ) ){
            $zocker_show_header_topbar = zocker_opt( 'zocker_header_topbar_switcher' );
            $zocker_show_social_icon   = zocker_opt( 'zocker_header_topbar_social_icon_switcher' );
        }else{
            $zocker_show_header_topbar = '';
            $zocker_show_social_icon   = '';
        }

        if( $zocker_show_header_topbar ){
            $allowhtml = array(
                'a'    => array(
                    'href' => array(),
                    'class' => array()
                ),
                'u'    => array(
                    'class' => array()
                ),
                'span' => array(
                    'class' => array()
                ),
                'i'    => array(
                    'class' => array()
                )
            );
            echo '<div class="header-top">';
                echo '<div class="container">';
                    echo '<div class="row py-md-2">';
                        echo '<div class="col-sm-6 d-none d-md-block">';
                            if( ! empty( zocker_opt( 'zocker_topbar_left_text' ) ) ){
                                echo '<p class="mb-0 fs-xs text-white">'.wp_kses( zocker_opt( 'zocker_topbar_left_text' ), $allowhtml ).'</p>';
                            }
                        echo '</div>';
                        echo '<div class="col-sm-6 text-end d-none d-md-block">';
                            if( $zocker_show_social_icon ){
                                echo '<ul class="social-links fs-xs text-white">';
                                    $zocker_social_icon = zocker_opt( 'zocker_social_links' );
                                    if( ! empty( $zocker_social_icon ) && isset( $zocker_social_icon ) ){
                                        foreach( $zocker_social_icon as $social_icon ){
                                            if( ! empty( $social_icon['title'] ) ){
                                                echo '<li><a href="'.esc_url( $social_icon['url'] ).'"><i class="'.esc_attr( $social_icon['title'] ).'"></i></a></li>';
                                            }
                                        }
                                    }
                                echo '</ul>';
                            }
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }

    }
}

// Add Extra Class On Comment Reply Button
function zocker_custom_comment_reply_link( $content ) {
    $extra_classes = 'vs-btn';
    return preg_replace( '/comment-reply-link/', 'comment-reply-link ' . $extra_classes, $content);
}

add_filter('comment_reply_link', 'zocker_custom_comment_reply_link', 99);

// Add Extra Class On Edit Comment Link
function zocker_custom_edit_comment_link( $content ) {
    $extra_classes = 'vs-btn';
    return preg_replace( '/comment-edit-link/', 'comment-edit-link ' . $extra_classes, $content);
}

add_filter('edit_comment_link', 'zocker_custom_edit_comment_link', 99);


function zocker_post_classes( $classes, $class, $post_id ) {
    if ( get_post_type() === 'post' ) {
        if( ! is_single() ){
            $classes[] = "vs-blog";
        }else{
            $classes[] = "vs-blog-single";
        }
    }elseif( get_post_type() === 'product' ){
        // Return Class
    }elseif( get_post_type() === 'page' ){
        $classes[] = "page--item";
    }
    
    return $classes;
}
add_filter( 'post_class', 'zocker_post_classes', 10, 3 );

function zocker_megamenu_add_theme_1625721418($themes) {
    $themes["zocker_1625721418"] = array(
        'title' => 'Zocker',
        'container_background_from' => 'rgba(0, 0, 0, 0)',
        'container_background_to' => 'rgba(0, 0, 0, 0)',
        'arrow_up' => 'dash-f343',
        'arrow_down' => 'dash-f347',
        'arrow_left' => 'dash-f341',
        'arrow_right' => 'dash-f345',
        'menu_item_background_from' => 'rgba(0, 0, 0, 0)',
        'menu_item_background_to' => 'rgba(0, 0, 0, 0)',
        'menu_item_background_hover_from' => 'rgba(0, 0, 0, 0)',
        'menu_item_background_hover_to' => 'rgba(255, 255, 255, 0)',
        'menu_item_link_height' => '100px',
        'menu_item_link_color' => 'rgb(138, 145, 166)',
        'menu_item_link_color_hover' => 'rgb(255, 255, 255)',
        'menu_item_link_padding_left' => '0px',
        'menu_item_link_padding_right' => '30px',
        'menu_item_highlight_current' => 'off',
        'menu_item_divider_color' => 'rgba(0, 0, 0, 0.1)',
        'panel_background_from' => 'rgb(255, 255, 255)',
        'panel_background_to' => 'rgb(255, 255, 255)',
        'panel_width' => '.mega-menu-inner',
        'panel_inner_width' => '.container',
        'panel_border_color' => 'rgb(221, 221, 221)',
        'panel_header_color' => 'rgb(156, 85, 235)',
        'panel_header_font_size' => '14px',
        'panel_header_font_weight' => 'normal',
        'panel_header_border_color' => 'rgb(156, 85, 235)',
        'panel_header_border_right' => '5px',
        'panel_header_border_bottom' => '1px',
        'panel_padding_left' => '18px',
        'panel_padding_right' => '18px',
        'panel_widget_padding_left' => '20px',
        'panel_widget_padding_right' => '20px',
        'panel_widget_padding_top' => '20px',
        'panel_widget_padding_bottom' => '20px',
        'panel_font_size' => '14px',
        'panel_font_color' => 'rgb(255, 255, 255)',
        'panel_font_family' => 'inherit',
        'panel_second_level_font_color' => 'rgb(156, 85, 235)',
        'panel_second_level_font_color_hover' => 'rgb(156, 85, 235)',
        'panel_second_level_text_transform' => 'uppercase',
        'panel_second_level_font' => 'inherit',
        'panel_second_level_font_size' => '14px',
        'panel_second_level_font_weight' => 'normal',
        'panel_second_level_font_weight_hover' => 'normal',
        'panel_second_level_text_decoration' => 'none',
        'panel_second_level_text_decoration_hover' => 'none',
        'panel_second_level_padding_bottom' => '10px',
        'panel_second_level_margin_bottom' => '15px',
        'panel_second_level_border_color' => 'rgb(156, 85, 235)',
        'panel_second_level_border_color_hover' => 'rgb(156, 85, 235)',
        'panel_second_level_border_bottom' => '1px',
        'panel_third_level_font_color' => 'rgb(0, 0, 0)',
        'panel_third_level_font_color_hover' => 'rgb(156, 85, 235)',
        'panel_third_level_font' => 'inherit',
        'panel_third_level_font_size' => '14px',
        'panel_third_level_padding_bottom' => '7px',
        'flyout_width' => '200px',
        'flyout_menu_background_from' => 'rgb(255, 255, 255)',
        'flyout_menu_background_to' => 'rgb(255, 255, 255)',
        'flyout_padding_top' => '18px',
        'flyout_padding_right' => '7px',
        'flyout_padding_bottom' => '18px',
        'flyout_padding_left' => '7px',
        'flyout_link_padding_left' => '16px',
        'flyout_link_padding_right' => '16px',
        'flyout_link_height' => '34px',
        'flyout_background_from' => 'rgb(255, 255, 255)',
        'flyout_background_to' => 'rgb(255, 255, 255)',
        'flyout_background_hover_from' => 'rgb(255, 255, 255)',
        'flyout_background_hover_to' => 'rgb(255, 255, 255)',
        'flyout_link_size' => '14px',
        'flyout_link_color' => 'rgb(0, 0, 0)',
        'flyout_link_color_hover' => 'rgb(156, 85, 235)',
        'flyout_link_family' => 'inherit',
        'responsive_breakpoint' => '991px',
        'line_height' => '26px',
        'z_index' => '9999',
        'shadow' => 'on',
        'shadow_vertical' => '5px',
        'shadow_blur' => '10px',
        'transitions' => 'on',
        'toggle_background_from' => '#222',
        'toggle_background_to' => '#222',
        'mobile_background_from' => '#222',
        'mobile_background_to' => '#222',
        'mobile_menu_item_link_font_size' => '14px',
        'mobile_menu_item_link_color' => '#ffffff',
        'mobile_menu_item_link_text_align' => 'left',
        'mobile_menu_item_link_color_hover' => '#ffffff',
        'mobile_menu_item_background_hover_from' => '#333',
        'mobile_menu_item_background_hover_to' => '#333',
        'disable_mobile_toggle' => 'on',
        'custom_css' => '/** Push menu onto new line **/ 
#{$wrap} { 
    clear: both;
	z-index:99;
	font-family:\"Montserrat\";
}',
    );
    return $themes;
}
add_filter("megamenu_themes", "zocker_megamenu_add_theme_1625721418");