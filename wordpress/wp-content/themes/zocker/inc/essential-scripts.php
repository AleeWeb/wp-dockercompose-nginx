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

/**
 * Enqueue scripts and styles.
 */
function zocker_essential_scripts() {

    $map_apikey = zocker_opt('zocker_map_apikey');
    
    // google map
    if( ! empty( $map_apikey ) ){
        wp_enqueue_script( 'google-map', "https://maps.googleapis.com/maps/api/js?key={$map_apikey}", array(), wp_get_theme()->get( 'Version' ), true );
    }

	wp_enqueue_style( 'zocker-style', get_stylesheet_uri() ,array(), wp_get_theme()->get( 'Version' ) );

    // google font
    wp_enqueue_style( 'zocker-fonts', zocker_google_fonts() ,array(), wp_get_theme()->get( 'Version' ) );

    // Font Awesome Five
    wp_enqueue_style( 'fontawesome', get_theme_file_uri( '/assets/css/fontawesome.min.css' ) ,array(), '5.9.0' );

    // Slick css
    wp_enqueue_style( 'slick', get_theme_file_uri( '/assets/css/slick.min.css' ) ,array(), '4.0.13' );

    // Bootstrap Min
    wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ) ,array(), '4.3.1' );

    // Magnific Popup
    wp_enqueue_style( 'magnific-popup', get_theme_file_uri( '/assets/css/magnific-popup.min.css' ), array(), '1.0' );

    // zocker app style
    wp_enqueue_style( 'zocker-main-style', get_theme_file_uri('/assets/css/style.css') ,array(), wp_get_theme()->get( 'Version' ) );

    // zocker app style
    wp_enqueue_style( 'theme-color1', get_theme_file_uri('/assets/css/theme-color1.css') ,array(), wp_get_theme()->get( 'Version' ) );

    // Load Js

    // Bootstrap
    wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/bootstrap.min.js' ), array( 'jquery' ), '4.3.1', true );

    // Slick
    wp_enqueue_script( 'slick', get_theme_file_uri( '/assets/js/slick.min.js' ), array('jquery'), '1.0.0', true );

    // magnific popup
    wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/jquery.magnific-popup.min.js' ), array('jquery'), '1.0.0', true );

    // cursor Menu
    // wp_enqueue_script( 'vs-cursor', get_theme_file_uri( '/assets/js/vs-cursor.min.js' ), array('jquery'), '1.0.0', true );

    // Isotope
    wp_enqueue_script( 'isototpe-pkgd', get_theme_file_uri( '/assets/js/isotope.pkgd.min.js' ), array( 'jquery' ), '1.0.0', true );

    // Isotope Imagesloaded
    wp_enqueue_script( 'imagesloaded' );

    // main script
    wp_enqueue_script( 'zocker-main-script', get_theme_file_uri( '/assets/js/main.js' ), array('jquery'), wp_get_theme()->get( 'Version' ), true );

    // comment reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'zocker_essential_scripts',99 );


function zocker_block_editor_assets( ) {
    // Add custom fonts.
	wp_enqueue_style( 'zocker-editor-fonts', zocker_google_fonts(), array(), null );
}

add_action( 'enqueue_block_editor_assets', 'zocker_block_editor_assets' );

function zocker_google_fonts() {
    $font_families = array(
        'DM Sans:400,500,700',
        'Montserrat:700',
        'Roboto:400,700',
    );

    $familyArgs = array(
        'family' => urlencode( implode( '|', $font_families ) ),
        'subset' => urlencode( 'latin,latin-ext' ),
    );

    $fontUrl = add_query_arg( $familyArgs, '//fonts.googleapis.com/css' );

    return esc_url_raw( $fontUrl );
}