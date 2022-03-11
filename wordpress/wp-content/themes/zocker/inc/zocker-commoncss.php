<?php
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit();
}
/**
 * @Packge     : Zocker
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// enqueue css
function zocker_common_custom_css(){
	wp_enqueue_style( 'zocker-color-schemes', get_template_directory_uri().'/assets/css/color.schemes.css' );

    $CustomCssOpt  = zocker_opt( 'zocker_css_editor' );
	if( $CustomCssOpt ){
		$CustomCssOpt = $CustomCssOpt;
	}else{
		$CustomCssOpt = '';
	}

    $customcss = "";

	// theme color
	$zockerthemecolor = zocker_opt('zocker_theme_color');

    list($r, $g, $b) = sscanf( $zockerthemecolor, "#%02x%02x%02x");

    $zocker_real_color = $r.','.$g.','.$b;
	if( !empty( $zockerthemecolor ) ) {
		$customcss .= ":root {
		  --theme-color: {$zocker_real_color};
		}";
	}

	if( !empty( $CustomCssOpt ) ){
		$customcss .= $CustomCssOpt;
	}

    wp_add_inline_style( 'zocker-color-schemes', $customcss );
}
add_action( 'wp_enqueue_scripts', 'zocker_common_custom_css', 100 );