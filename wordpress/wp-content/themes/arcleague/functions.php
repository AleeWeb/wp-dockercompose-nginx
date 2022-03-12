<?php
add_action( 'wp_enqueue_scripts', 'twentynineteen_child_scripts' );
/**
 * Loads child styles
 */
function arcleague_scripts(){
    wp_enqueue_style( 'child-styles', get_stylesheet_uri(), array( 'arcane-custom-style2' ) );
}