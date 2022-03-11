<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( );
}
/**
 * @Packge    : zocker
 * @version   : 1.0
 * @Author    : Vecurosoft
 * @Author URI: https://www.vecurosoft.com/
 */

// demo import file
function zocker_import_files() {

	$demoImg = '<img src="'. ZOCKER_DEMO_DIR_URI  .'screen-image.png" alt="'.esc_attr__('Demo Preview Imgae','zocker').'" />';

    return array(
        array(
            'import_file_name'             => esc_html__('Zocker Demo','zocker'),
            'local_import_file'            =>  ZOCKER_DEMO_DIR_PATH  . 'zocker-demo.xml',
            'local_import_widget_file'     =>  ZOCKER_DEMO_DIR_PATH  . 'zocker-widgets-demo.json',
            'local_import_redux'           => array(
                array(
                    'file_path'   =>  ZOCKER_DEMO_DIR_PATH . 'redux_options_demo.json',
                    'option_name' => 'zocker_opt',
                ),
            ),
            'import_notice' => $demoImg,
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'zocker_import_files' );

// demo import setup
function zocker_after_import_setup() {
	// Assign menus to their locations.
	$main_menu   = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
	$mobile_menu = get_term_by( 'name', 'Mobile Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'primary-menu'   => $main_menu->term_id,
			'mobile-menu'    => $mobile_menu->term_id,
		)
	);

	// Assign front page and posts page (blog page).
	$front_page_id 	= get_page_by_title( 'Home' );
	$blog_page_id  	= get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	update_option( 'page_for_posts', $blog_page_id->ID );

    if( class_exists( 'LS_Sliders' ) ){
		include LS_ROOT_PATH.'/classes/class.ls.importutil.php';
		new LS_ImportUtil( ZOCKER_DEMO_DIR_PATH  . 'slider/homeone.zip');
		new LS_ImportUtil( ZOCKER_DEMO_DIR_PATH  . 'slider/hometwo.zip');
		new LS_ImportUtil( ZOCKER_DEMO_DIR_PATH  . 'slider/homethree.zip');
	}

}
add_action( 'pt-ocdi/after_import', 'zocker_after_import_setup' );


//disable the branding notice after successful demo import
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

//change the location, title and other parameters of the plugin page
function zocker_import_plugin_page_setup( $default_settings ) {
	$default_settings['parent_slug'] = 'themes.php';
	$default_settings['page_title']  = esc_html__( 'Zocker Demo Import' , 'zocker' );
	$default_settings['menu_title']  = esc_html__( 'Import Demo Data' , 'zocker' );
	$default_settings['capability']  = 'import';
	$default_settings['menu_slug']   = 'zocker-demo-import';

	return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'zocker_import_plugin_page_setup' );

// Enqueue scripts
function zocker_demo_import_custom_scripts(){
	if( isset( $_GET['page'] ) && $_GET['page'] == 'zocker-demo-import' ){
		// style
		wp_enqueue_style( 'zocker-demo-import', ZOCKER_DEMO_DIR_URI.'css/zocker.demo.import.css', array(), '1.0', false );
	}
}
add_action( 'admin_enqueue_scripts', 'zocker_demo_import_custom_scripts' );