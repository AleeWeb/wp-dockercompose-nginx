<?php
/**
 * @Packge     : Zocker
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// Block direct access
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *
 * Define constant
 *
 */

// Base URI
if ( ! defined( 'ZOCKER_DIR_URI' ) ) {
    define('ZOCKER_DIR_URI', get_parent_theme_file_uri().'/' );
}

// Assist URI
if ( ! defined( 'ZOCKER_DIR_ASSIST_URI' ) ) {
    define( 'ZOCKER_DIR_ASSIST_URI', get_theme_file_uri('/assets/') );
}


// Css File URI
if ( ! defined( 'ZOCKER_DIR_CSS_URI' ) ) {
    define( 'ZOCKER_DIR_CSS_URI', get_theme_file_uri('/assets/css/') );
}

// Skin Css File
if ( ! defined( 'ZOCKER_DIR_SKIN_CSS_URI' ) ) {
    define( 'ZOCKER_DIR_SKIN_CSS_URI', get_theme_file_uri('/assets/css/skins/') );
}


// Js File URI
if (!defined('ZOCKER_DIR_JS_URI')) {
    define('ZOCKER_DIR_JS_URI', get_theme_file_uri('/assets/js/'));
}


// External PLugin File URI
if (!defined('ZOCKER_DIR_PLUGIN_URI')) {
    define('ZOCKER_DIR_PLUGIN_URI', get_theme_file_uri( '/assets/plugins/'));
}

// Base Directory
if (!defined('ZOCKER_DIR_PATH')) {
    define('ZOCKER_DIR_PATH', get_parent_theme_file_path() . '/');
}

//Inc Folder Directory
if (!defined('ZOCKER_DIR_PATH_INC')) {
    define('ZOCKER_DIR_PATH_INC', ZOCKER_DIR_PATH . 'inc/');
}

//ZOCKER framework Folder Directory
if (!defined('ZOCKER_DIR_PATH_FRAM')) {
    define('ZOCKER_DIR_PATH_FRAM', ZOCKER_DIR_PATH_INC . 'zocker-framework/');
}

//Classes Folder Directory
if (!defined('ZOCKER_DIR_PATH_CLASSES')) {
    define('ZOCKER_DIR_PATH_CLASSES', ZOCKER_DIR_PATH_INC . 'classes/');
}

//Hooks Folder Directory
if (!defined('ZOCKER_DIR_PATH_HOOKS')) {
    define('ZOCKER_DIR_PATH_HOOKS', ZOCKER_DIR_PATH_INC . 'hooks/');
}

//Demo Data Folder Directory Path
if( !defined( 'ZOCKER_DEMO_DIR_PATH' ) ){
    define( 'ZOCKER_DEMO_DIR_PATH', ZOCKER_DIR_PATH_INC.'demo-data/' );
}
    
//Demo Data Folder Directory URI
if( !defined( 'ZOCKER_DEMO_DIR_URI' ) ){
    define( 'ZOCKER_DEMO_DIR_URI', ZOCKER_DIR_URI.'inc/demo-data/' );
}