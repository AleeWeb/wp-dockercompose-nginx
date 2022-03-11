<?php

/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme ecohost for publication on ThemeForest
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */



/**
 * Include the TGM_Plugin_Activation class.
 */
require_once ZOCKER_DIR_PATH_FRAM . 'plugins-activation/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'zocker_register_required_plugins' );
function zocker_register_required_plugins() {

    /*
    * Array of plugin arrays. Required keys are name and slug.
    * If the source is NOT from the .org repo, then source is also required.
    */

    $plugins = array(

        array(
            'name'                  => esc_html__( 'Zocker Core', 'zocker' ),
            'slug'                  => 'zocker-core',
            'version'               => '1.0',
            'source'                => ZOCKER_DIR_PATH_FRAM . 'plugins/zocker-core.zip',
            'required'              => true,
        ),

        array(
            'name'                  => esc_html__( 'LayerSlider', 'zocker' ),
            'slug'                  => 'LayerSlider',
            'version'               => '1.0',
            'source'                => ZOCKER_DIR_PATH_FRAM . 'plugins/LayerSlider.zip',
            'required'              => true,
        ),

        array(
            'name'                  => esc_html__( 'One Click Demo Importer', 'zocker' ),
            'slug'                  => 'one-click-demo-import',
            'required'              => true,
        ),

        array(
            'name'      => esc_html__( 'Elementor', 'zocker' ),
            'slug'      => 'elementor',
            'version'   => '',
            'required'  => true,
        ),

        array(
            'name'      => esc_html__( 'Redux Framework', 'zocker' ),
            'slug'      => 'redux-framework',
            'version'   => '',
            'required'  => true,
        ),

        array(
            'name'      => esc_html__( 'CMB2', 'zocker' ),
            'slug'      => 'cmb2',
            'required'  => true,
        ),

        array(
            'name'      => esc_html__( 'Contact Form 7', 'zocker' ),
            'slug'      => 'contact-form-7',
            'version'   => '',
            'required'  => true,
        ),

        array(
            'name'      => esc_html__( 'WooCommerce', 'zocker' ),
            'slug'      => 'woocommerce',
            'version'   => '',
            'required'  => true,
        ),

        array(
            'name'      => esc_html__( 'YITH WooCommerce Wishlist', 'zocker' ),
            'slug'      => 'yith-woocommerce-wishlist',
            'version'   => '',
            'required'  => true,
        ),

        array(
            'name'      => esc_html__( 'GTranslate', 'zocker' ),
            'slug'      => 'gtranslate',
            'version'   => '',
            'required'  => true,
        ),

        array(
            'name'      => esc_html__( 'WPC Smart Compare for WooCommerce', 'zocker' ),
            'slug'      => 'woo-smart-compare',
            'version'   => '',
            'required'  => true,
        ),

        array(
            'name'      => esc_html__( 'WPC Smart Quick View for WooCommerce', 'zocker' ),
            'slug'      => 'woo-smart-quick-view',
            'version'   => '',
            'required'  => true,
        ),

        array(
            'name'      => esc_html__( 'Max Mega Menu', 'zocker' ),
            'slug'      => 'megamenu',
            'version'   => '',
            'required'  => true,
        ),

    );

    $config = array(
        'id'           => 'zocker',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
    );

    tgmpa( $plugins, $config );
}