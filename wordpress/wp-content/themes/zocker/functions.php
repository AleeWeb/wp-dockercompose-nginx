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
 * Include File
 *
 */

// Constants
require_once get_parent_theme_file_path() . '/inc/zocker-constants.php';

//theme setup
require_once ZOCKER_DIR_PATH_INC . 'theme-setup.php';

//essential scripts
require_once ZOCKER_DIR_PATH_INC . 'essential-scripts.php';

// Woo Hooks
require_once ZOCKER_DIR_PATH_INC . 'woo-hooks/zocker-woo-hooks.php';

// Woo Hooks Functions
require_once ZOCKER_DIR_PATH_INC . 'woo-hooks/zocker-woo-hooks-functions.php';

// plugin activation
require_once ZOCKER_DIR_PATH_FRAM . 'plugins-activation/zocker-active-plugins.php';

// meta options
require_once ZOCKER_DIR_PATH_FRAM . 'zocker-meta/zocker-config.php';

// page breadcrumbs
require_once ZOCKER_DIR_PATH_INC . 'zocker-breadcrumbs.php';

// sidebar register
require_once ZOCKER_DIR_PATH_INC . 'zocker-widgets-reg.php';

//essential functions
require_once ZOCKER_DIR_PATH_INC . 'zocker-functions.php';

// theme dynamic css
require_once ZOCKER_DIR_PATH_INC . 'zocker-commoncss.php';

// helper function
require_once ZOCKER_DIR_PATH_INC . 'wp-html-helper.php';

// Demo Data
require_once ZOCKER_DEMO_DIR_PATH . 'demo-import.php';

// pagination
require_once ZOCKER_DIR_PATH_INC . 'wp_bootstrap_pagination.php';

// zocker options
require_once ZOCKER_DIR_PATH_FRAM . 'zocker-options/zocker-options.php';

// hooks
require_once ZOCKER_DIR_PATH_HOOKS . 'hooks.php';

// hooks funtion
require_once ZOCKER_DIR_PATH_HOOKS . 'hooks-functions.php';