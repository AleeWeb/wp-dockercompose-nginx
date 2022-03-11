<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( );
}
/**
 * @Packge    : Zocker
 * @version   : 1.0
 * @Author    : Vecurosoft
 * @Author URI: https://www.vecurosoft.com/
 * Template Name: Template Builder
 */

//Header
get_header();

// Container or wrapper div
$zocker_layout = zocker_meta( 'custom_page_layout' );

if( $zocker_layout == '1' ){
	echo '<div class="zocker-main-wrapper">';
		echo '<div class="container">';
			echo '<div class="row">';
				echo '<div class="col-sm-12">';
}elseif( $zocker_layout == '2' ){
    echo '<div class="zocker-main-wrapper">';
		echo '<div class="container-fluid">';
			echo '<div class="row">';
				echo '<div class="col-sm-12">';
}else{
	echo '<div class="zocker-fluid">';
}
	echo '<div class="builder-page-wrapper">';
	// Query
	if( have_posts() ){
		while( have_posts() ){
			the_post();
			the_content();
		}
        wp_reset_postdata();
	}

	echo '</div>';
if( $zocker_layout == '1' ){
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}elseif( $zocker_layout == '2' ){
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}else{
	echo '</div>';
}

//footer
get_footer();