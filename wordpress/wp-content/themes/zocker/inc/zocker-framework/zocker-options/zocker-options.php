<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "zocker_opt";

    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );

    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();

        global $wp_filesystem;

        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }


    $alowhtml = array(
        'p' => array(
            'class' => array()
        ),
        'span' => array()
    );


    // Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
    $sample_patterns      = array();

    if ( is_dir( $sample_patterns_path ) ) {

        if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
            $sample_patterns = array();

            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                    $name              = explode( '.', $sample_patterns_file );
                    $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                    $sample_patterns[] = array(
                        'alt' => $name,
                        'img' => $sample_patterns_url . $sample_patterns_file
                    );
                }
            }
        }
    }

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => esc_html__( 'Zocker Options', 'zocker' ),
        'page_title'           => esc_html__( 'Zocker Options', 'zocker' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => false,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => true,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );


    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => esc_html__( 'Theme Information 1', 'zocker' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'zocker' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => esc_html__( 'Theme Information 2', 'zocker' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'zocker' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = esc_html__( '<p>This is the sidebar content, HTML is allowed.</p>', 'zocker' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */


    // -> START General Fields

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'General', 'zocker' ),
        'id'               => 'zocker_general',
        'customizer_width' => '450px',
        'icon'             => 'el el-cog',
        'fields'           => array(
            array(
                'id'       => 'zocker_theme_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Theme Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Theme Color', 'zocker' )
            ),
            array(
                'id'       => 'zocker_map_apikey',
                'type'     => 'text',
                'title'    => esc_html__( 'Map Api Key', 'zocker' ),
                'subtitle' => esc_html__( 'Set Map Api Key', 'zocker' ),
            ),
        )

    ) );

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Back To Top', 'zocker' ),
        'id'               => 'zocker_backtotop',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'zocker_display_bcktotop',
                'type'     => 'switch',
                'title'    => esc_html__( 'Back To Top Button', 'zocker' ),
                'subtitle' => esc_html__( 'Switch On to Display back to top button.', 'zocker' ),
                'default'  => true,
                'on'       => esc_html__( 'Enabled', 'zocker' ),
                'off'      => esc_html__( 'Disabled', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_bcktotop_bg_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Back To Top Button Background Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Back to top button Background Color.', 'zocker' ),
                'required' => array('zocker_display_bcktotop','equals','1'),
                'output'   => array( 'background-color' =>'.scrollToTop' ),
            ),
            array(
                'id'       => 'zocker_bcktotop_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Back To Top Icon Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Back to top Icon Color.', 'zocker' ),
                'required' => array('zocker_display_bcktotop','equals','1'),
                'output'   => array( '.scrollToTop i' ),
            ),
            array(
                'id'       => 'zocker_bcktotop_border_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Back To Top Button Border Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Back to top button border Color.', 'zocker' ),
                'required' => array('zocker_display_bcktotop','equals','1'),
                'output'   => array( 'border-color' =>'.border-before-theme:before' ),
            )
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Preloader', 'zocker' ),
        'id'               => 'zocker_preloader',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'zocker_display_preloader',
                'type'     => 'switch',
                'title'    => esc_html__( 'Preloader', 'zocker' ),
                'subtitle' => esc_html__( 'Switch Enabled to Display Preloader.', 'zocker' ),
                'default'  => true,
                'on'       => esc_html__('Enabled','zocker'),
                'off'      => esc_html__('Disabled','zocker'),
            ),

            array(
                'id'       => 'zocker_preloader_img',
                'type'     => 'media',
                'title'    => esc_html__( 'Preloader Image', 'zocker' ),
                'subtitle' => esc_html__( 'Set Preloader Image.', 'zocker' ),
                'required' => array( "zocker_display_preloader","equals",true )
            ),
        )
    ));

    /* End General Fields */

    /* Admin Lebel Fields */
    Redux::setSection( $opt_name, array(
        'title'             => esc_html__( 'Admin Label', 'zocker' ),
        'id'                => 'zocker_admin_label',
        'customizer_width'  => '450px',
        'subsection'        => true,
        'fields'            => array(
            array(
                'title'     => esc_html__( 'Admin Login Logo', 'zocker' ),
                'subtitle'  => esc_html__( 'It belongs to the back-end of your website to log-in to admin panel.', 'zocker' ),
                'id'        => 'zocker_admin_login_logo',
                'type'      => 'media',
            ),
            array(
                'title'     => esc_html__( 'Custom CSS For admin', 'zocker' ),
                'subtitle'  => esc_html__( 'Any CSS your write here will run in admin.', 'zocker' ),
                'id'        => 'zocker_theme_admin_custom_css',
                'type'      => 'ace_editor',
                'mode'      => 'css',
                'theme'     => 'chrome',
                'full_width'=> true,
            ),
        ),
    ) );

    // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Header', 'zocker' ),
        'id'               => 'zocker_header',
        'customizer_width' => '400px',
        'icon'             => 'el el-credit-card',
        'fields'           => array(
            array(
                'id'       => 'zocker_header_options',
                'type'     => 'button_set',
                'default'  => '1',
                'options'  => array(
                    "1"   => esc_html__('Prebuilt','zocker'),
                    "2"      => esc_html__('Header Builder','zocker'),
                ),
                'title'    => esc_html__( 'Header Options', 'zocker' ),
                'subtitle' => esc_html__( 'Select header options.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_header_select_options',
                'type'     => 'select',
                'data'     => 'posts',
                'args'     => array(
                    'post_type'     => 'zocker_header'
                ),
                'title'    => esc_html__( 'Header', 'zocker' ),
                'subtitle' => esc_html__( 'Select header.', 'zocker' ),
                'required' => array( 'zocker_header_options', 'equals', '2' )
            ),
            array(
                'id'       => 'zocker_header_topbar_switcher',
                'type'     => 'switch',
                'default'  => '0',
                'on'       => esc_html__( 'Show', 'zocker' ),
                'off'      => esc_html__( 'Hide', 'zocker' ),
                'title'    => esc_html__( 'Header Topbar?', 'zocker' ),
                'subtitle' => esc_html__( 'Control Header Topbar By Show Or Hide System.', 'zocker'),
                'required' => array( 'zocker_header_options', 'equals', '1' )
            ),
            array(
                'id'       => 'zocker_header_topbar_bg_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Topbar Background Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Topbar Background Color', 'zocker' ),
                'output'   => array( 'background-color'    =>  '.header-top' ),
                'required' => array( 'zocker_header_topbar_switcher', 'equals', '1' )
            ),
            array(
                'id'       => 'zocker_topbar_left_text',
                'type'     => 'text',
                'validate' => 'html',
                'title'    => esc_html__( 'Topbar Left Text', 'zocker' ),
                'subtitle' => esc_html__( 'Write Topbar Left Text', 'zocker' ),
                'required' => array( 'zocker_header_topbar_switcher', 'equals', '1' )
            ),
            array(
                'id'       => 'zocker_header_topbar_social_icon_switcher',
                'type'     => 'switch',
                'default'  => 1,
                'on'       => esc_html__( 'Show', 'zocker' ),
                'off'      => esc_html__( 'Hide', 'zocker' ),
                'title'    => esc_html__( 'Header Social Icon?', 'zocker' ),
                'subtitle' => esc_html__( 'Click Show To Display Social Icon?', 'zocker'),
                'required' => array( 'zocker_header_topbar_switcher', 'equals', '1' )
            ),
            array(
                'id'       => 'zocker_header_social_icon_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Header Social Icon Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Header Social Icon Color', 'zocker' ),
                'output'   => array( 'color'    =>  '.social-links li a i' ),
                'required' => array( 'zocker_header_topbar_social_icon_switcher', 'equals', '1' )
            ),
            array(
                'id'       => 'zocker_topbar_section_divider',
                'type'     => 'divide',
            ),
            array(
                'id'       => 'zocker_live_stream_text',
                'type'     => 'text',
                'validate' => 'html',
                'title'    => esc_html__( 'Button Text', 'zocker' ),
                'subtitle' => esc_html__( 'Set Button Text', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_live_stream_url',
                'type'     => 'text',
                'title'    => esc_html__( 'Button URL?', 'zocker' ),
                'subtitle' => esc_html__( 'Set Button URL Here', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_language_switcher',
                'type'     => 'switch',
                'default'  => 1,
                'on'       => esc_html__( 'Show', 'zocker' ),
                'off'      => esc_html__( 'Hide', 'zocker' ),
                'title'    => esc_html__( 'Language?', 'zocker' ),
                'subtitle' => esc_html__( 'Show Or Hide Language Switcher?', 'zocker'),
            ),
            array(
                'id'       => 'zocker_search_switcher',
                'type'     => 'switch',
                'default'  => 1,
                'on'       => esc_html__( 'Show', 'zocker' ),
                'off'      => esc_html__( 'Hide', 'zocker' ),
                'title'    => esc_html__( 'Search?', 'zocker' ),
                'subtitle' => esc_html__( 'Show Or Hide Search Switcher?', 'zocker'),
            ),
            array(
                'id'       => 'zocker_offcanvas_switcher',
                'type'     => 'switch',
                'default'  => 1,
                'on'       => esc_html__( 'Show', 'zocker' ),
                'off'      => esc_html__( 'Hide', 'zocker' ),
                'title'    => esc_html__( 'Offcanvas?', 'zocker' ),
                'subtitle' => esc_html__( 'Show Or Hide Offcanvas Switcher?', 'zocker'),
            ),

        ),
    ) );
    // -> START Header Logo
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Header Logo', 'zocker' ),
        'id'               => 'zocker_header_logo_option',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'zocker_site_logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__( 'Logo', 'zocker' ),
                'compiler' => 'true',
                'subtitle' => esc_html__( 'Upload your site logo for header ( recommendation png format ).', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_site_logo_dimensions',
                'type'     => 'dimensions',
                'units'    => array('px'),
                'title'    => esc_html__('Logo Dimensions (Width/Height).', 'zocker'),
                'output'   => array('.header-logo .logo img'),
                'subtitle' => esc_html__('Set logo dimensions to choose width, height, and unit.', 'zocker'),
            ),
            array(
                'id'       => 'zocker_site_logomargin_dimensions',
                'type'     => 'spacing',
                'mode'     => 'margin',
                'output'   => array('.header-logo .logo img'),
                'units_extended' => 'false',
                'units'    => array('px'),
                'title'    => esc_html__('Logo Top and Bottom Margin.', 'zocker'),
                'left'     => false,
                'right'    => false,
                'subtitle' => esc_html__('Set logo top and bottom margin.', 'zocker'),
                'default'            => array(
                    'units'           => 'px'
                )
            ),
            array(
                'id'       => 'zocker_text_title',
                'type'     => 'text',
                'validate' => 'html',
                'title'    => esc_html__( 'Text Logo', 'zocker' ),
                'subtitle' => esc_html__( 'Write your logo text use as logo ( You can use span tag for text color ).', 'zocker' ),
            )
        )
    ) );
    // -> End Header Logo

    // -> START Header Menu
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Header Menu', 'zocker' ),
        'id'               => 'zocker_header_menu_option',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'zocker_header_menu_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Menu Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Menu Color', 'zocker' ),
                'output'   => array( 'color'    =>  '.menu-style1 > ul > li > a' ),
            ),
            array(
                'id'       => 'zocker_header_menu_hover_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Menu Hover Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Menu Hover Color', 'zocker' ),
                'output'   => array( 'color'    =>  '.menu-style1 > ul > li > a:hover' ),
            ),
            array(
                'id'       => 'zocker_header_submenu_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Submenu Color', 'zocker' ),
                'output'   => array( 'color'    =>  '.main-menu ul li ul.sub-menu li a' ),
            ),
            array(
                'id'       => 'zocker_header_submenu_hover_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Hover Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Submenu Hover Color', 'zocker' ),
                'output'   => array( 'color'    =>  '.main-menu ul li ul.sub-menu li a:hover' ),
            ),
            array(
                'id'       => 'zocker_header_submenu_icon_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Icon Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Submenu Icon Color', 'zocker' ),
                'output'   => array( 'color'    =>  '.main-menu ul li ul.sub-menu li a:after' ),
            ),
            array(
                'id'       => 'zocker_header_submenu_border_top_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Border Top Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Submenu Border Top Color', 'zocker' ),
                'output'   => array( 'border-top-color'    =>  '.main-menu ul.sub-menu' ),
            ),
        )
    ) );
    // -> End Header Menu

     // -> START Mobile Menu
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Offcanvas', 'zocker' ),
        'id'               => 'zocker_offcanvas_panel',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'zocker_offcanvas_panel_bg',
                'type'     => 'background',
                'title'    => esc_html__( 'Offcanvas Panel Background', 'zocker' ),
                'output'   => array('.sidemenu-wrapper .sidemenu-content'),
                'subtitle' => esc_html__( 'Set Offcanvas Panel Background Color', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_offcanvas_title_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Offcanvas Title Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Offcanvas Title color.', 'zocker' ),
                'output'   => array( '.sidemenu-content h3.sidebox-title' )
            ),
        )
    ) );
    // -> End Mobile Menu

    // -> START Blog Page
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Blog', 'zocker' ),
        'id'         => 'zocker_blog_page',
        'icon'  => 'el el-blogger',
        'fields'     => array(

            array(
                'id'       => 'zocker_blog_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Layout', 'zocker' ),
                'subtitle' => esc_html__( 'Choose blog layout from here. If you use this option then you will able to change three type of blog layout ( Default Left Sidebar Layour ). ', 'zocker' ),
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','zocker'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '3'
            ),
            array(
                'id'       => 'zocker_blog_grid',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Post Column', 'zocker' ),
                'subtitle' => esc_html__( 'Select your blog post column from here. If you use this option then you will able to select three type of blog post layout ( Default Two Column ).', 'zocker' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','zocker'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/1column.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2column.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3column.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'zocker_blog_page_title_switcher',
                'type'     => 'switch',
                'default'  => 1,
                'on'       => esc_html__('Show','zocker'),
                'off'      => esc_html__('Hide','zocker'),
                'title'    => esc_html__('Blog Page Title', 'zocker'),
                'subtitle' => esc_html__('Control blog page title show / hide. If you use this option then you will able to show / hide your blog page title ( Default Setting Show ).', 'zocker'),
            ),
            array(
                'id'       => 'zocker_blog_page_title_setting',
                'type'     => 'button_set',
                'title'    => esc_html__('Blog Page Title Setting', 'zocker'),
                'subtitle' => esc_html__('Control blog page title setting. If you use this option then you can able to show default or custom blog page title ( Default Blog ).', 'zocker'),
                'options'  => array(
                    "predefine"   => esc_html__('Default','zocker'),
                    "custom"      => esc_html__('Custom','zocker'),
                ),
                'default'  => 'predefine',
                'required' => array("zocker_blog_page_title_switcher","equals","1")
            ),
            array(
                'id'       => 'zocker_blog_page_custom_title',
                'type'     => 'text',
                'title'    => esc_html__('Blog Custom Title', 'zocker'),
                'subtitle' => esc_html__('Set blog page custom title form here. If you use this option then you will able to set your won title text.', 'zocker'),
                'required' => array('zocker_blog_page_title_setting','equals','custom')
            ),
            array(
                'id'            => 'zocker_blog_postExcerpt',
                'type'          => 'slider',
                'title'         => esc_html__('Blog Posts Excerpt', 'zocker'),
                'subtitle'      => esc_html__('Control the number of characters you want to show in the blog page for each post.. If you use this option then you can able to control your blog post characters from here ( Default show 10 ).', 'zocker'),
                "default"       => 46,
                "min"           => 0,
                "step"          => 1,
                "max"           => 100,
                'resolution'    => 1,
                'display_value' => 'text',
            ),
            array(
                'id'       => 'zocker_blog_readmore_setting',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Read More Text Setting', 'zocker' ),
                'subtitle' => esc_html__( 'Control read more text from here.', 'zocker' ),
                'options'  => array(
                    "default"   => esc_html__('Default','zocker'),
                    "custom"    => esc_html__('Custom','zocker'),
                ),
                'default'  => 'default',
            ),
            array(
                'id'       => 'zocker_blog_custom_readmore',
                'type'     => 'text',
                'title'    => esc_html__('Read More Text', 'zocker'),
                'subtitle' => esc_html__('Set read moer text here. If you use this option then you will able to set your won text.', 'zocker'),
                'required' => array('zocker_blog_readmore_setting','equals','custom')
            ),
            array(
                'id'       => 'zocker_blog_title_color',
                'output'   => array( '.vs-blog .blog-title a'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Title Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Blog Title Color.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_blog_title_hover_color',
                'output'   => array( '.vs-blog .blog-title a:hover'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Title Hover Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Blog Title Hover Color.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_blog_contant_color',
                'output'   => array( '.blog-content p'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Excerpt / Content Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Blog Excerpt / Content Color.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_blog_read_more_button_color',
                'output'   => array( '.blog-content .link-btn'),
                'type'     => 'color',
                'title'    => esc_html__( 'Read More Button Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Read More Button Color.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_blog_read_more_button_hover_color',
                'output'   => array( '.blog-content .link-btn:hover'),
                'type'     => 'color',
                'title'    => esc_html__( 'Read More Button Hover Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Read More Button Hover Color.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_blog_pagination_color',
                'output'   => array( '.pagination li a,.pagination a i'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Pagination Color', 'zocker'),
                'subtitle' => esc_html__('Set Blog Pagination Color.', 'zocker'),
            ),
            array(
                'id'       => 'zocker_blog_pagination_active_color',
                'output'   => array( '.pagination li span.current'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Pagination Active Color', 'zocker'),
                'subtitle' => esc_html__('Set Blog Pagination Active Color.', 'zocker'),
                'required'  => array('zocker_blog_pagination', '=', '1')
            ),
            array(
                'id'       => 'zocker_blog_pagination_hover_color',
                'output'   => array( '.pagination li a:hover,.pagination a i:hover'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Pagination Hover Color', 'zocker'),
                'subtitle' => esc_html__('Set Blog Pagination Hover Color.', 'zocker'),
            ),
        ),
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Single Blog Page', 'zocker' ),
        'id'         => 'zocker_post_detail_styles',
        'subsection' => true,
        'fields'     => array(

            array(
                'id'       => 'zocker_blog_single_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Layout', 'zocker' ),
                'subtitle' => esc_html__( 'Choose blog single page layout from here. If you use this option then you will able to change three type of blog single page layout ( Default Left Sidebar Layour ). ', 'zocker' ),
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','zocker'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '3'
            ),
            array(
                'id'       => 'zocker_post_details_title_position',
                'type'     => 'button_set',
                'default'  => 'header',
                'options'  => array(
                    'header'        => esc_html__('On Header','zocker'),
                    'below'         => esc_html__('Below Thumbnail','zocker'),
                ),
                'title'    => esc_html__('Blog Post Title Position', 'zocker'),
                'subtitle' => esc_html__('Control blog post title position from here.', 'zocker'),
            ),
            array(
                'id'       => 'zocker_post_details_custom_title',
                'type'     => 'text',
                'title'    => esc_html__('Blog Details Custom Title', 'zocker'),
                'subtitle' => esc_html__('This title will show in Breadcrumb title.', 'zocker'),
                'required' => array('zocker_post_details_title_position','equals','below')
            ),
            array(
                'id'       => 'zocker_display_post_tags',
                'type'     => 'switch',
                'title'    => esc_html__( 'Tags', 'zocker' ),
                'subtitle' => esc_html__( 'Switch On to Display Tags.', 'zocker' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','zocker'),
                'off'       => esc_html__('Disabled','zocker'),
            ),
            array(
                'id'       => 'zocker_post_details_share_options',
                'type'     => 'switch',
                'title'    => esc_html__('Share Options', 'zocker'),
                'subtitle' => esc_html__('Control post share options from here. If you use this option then you will able to show or hide post share options.', 'zocker'),
                'on'        => esc_html__('Show','zocker'),
                'off'       => esc_html__('Hide','zocker'),
                'default'   => '0',
            ),
            array(
                'id'       => 'zocker_post_details_author_desc_trigger',
                'type'     => 'switch',
                'title'    => esc_html__('Biography Info', 'zocker'),
                'subtitle' => esc_html__('Control biography info from here. If you use this option then you will able to show or hide biography info ( Default setting Show ).', 'zocker'),
                'on'        => esc_html__('Show','zocker'),
                'off'       => esc_html__('Hide','zocker'),
                'default'   => '0',
            ),
            array(
                'id'       => 'zocker_post_details_post_navigation',
                'type'     => 'switch',
                'title'    => esc_html__('Post Navigation', 'zocker'),
                'subtitle' => esc_html__('Control post navigation from here. If you use this option then you will able to show or hide post navigation ( Default setting Show ).', 'zocker'),
                'on'        => esc_html__('Show','zocker'),
                'off'       => esc_html__('Hide','zocker'),
                'default'   => true,
            ),
            array(
                'id'       => 'zocker_post_details_related_post',
                'type'     => 'switch',
                'title'    => esc_html__('Related Post', 'zocker'),
                'subtitle' => esc_html__('Control related post from here. If you use this option then you will able to show or hide related post ( Default setting Show ).', 'zocker'),
                'on'        => esc_html__('Show','zocker'),
                'off'       => esc_html__('Hide','zocker'),
                'default'   => false,
            ),
        )
    ));

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Meta Data', 'zocker' ),
        'id'         => 'zocker_common_meta_data',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'zocker_blog_meta_icon_color',
                'output'   => array( '.blog-meta span i'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Meta Icon Color', 'zocker'),
                'subtitle' => esc_html__('Set Blog Meta Icon Color.', 'zocker'),
            ),
            array(
                'id'       => 'zocker_blog_meta_text_color',
                'output'   => array( '.blog-meta a,.blog-meta span'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Meta Text Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Blog Meta Text Color.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_blog_meta_text_hover_color',
                'output'   => array( '.blog-meta a:hover'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Meta Hover Text Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Blog Meta Hover Text Color.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_display_post_date',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post Date', 'zocker' ),
                'subtitle' => esc_html__( 'Switch On to Display Post Date.', 'zocker' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','zocker'),
                'off'       => esc_html__('Disabled','zocker'),
            ),
            array(
                'id'       => 'zocker_display_post_views',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post Views', 'zocker' ),
                'subtitle' => esc_html__( 'Switch On to Display Post Views.', 'zocker' ),
                'default'  => false,
                'on'        => esc_html__( 'Enabled', 'zocker'),
                'off'       => esc_html__( 'Disabled', 'zocker'),
            ),
            array(
                'id'       => 'zocker_display_post_comment',
                'type'     => 'switch',
                'title'    => esc_html__( 'Comment Count', 'zocker' ),
                'subtitle' => esc_html__( 'Switch On to Display Comment Count.', 'zocker' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','zocker'),
                'off'       => esc_html__('Disabled','zocker'),
            ),
            array(
                'id'       => 'zocker_display_post_category',
                'type'     => 'switch',
                'title'    => esc_html__( 'Category', 'zocker' ),
                'subtitle' => esc_html__( 'Switch On to Display Category.', 'zocker' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','zocker'),
                'off'       => esc_html__('Disabled','zocker'),
            ),
        )
    ));

    /* Sidebar Start */
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Sidebar Options', 'zocker' ),
        'id'         => 'zocker_sidebar_options',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'      => 'zocker_sidebar_bg_color',
                'type'    => 'color',
                'title'   => esc_html__('Widgets Background Color', 'zocker'),
                'output'  => array('background-color'   => '.blog-sidebar')
            ),
            array(
                'id'      => 'zocker_sidebar_padding_margin_box_shadow_trigger',
                'type'    => 'switch',
                'title'   => esc_html__('Widgets Custom Box Shadow/Padding/Margin/border', 'zocker'),
                'on'      => esc_html__('Show','zocker'),
                'off'     => esc_html__('Hide','zocker'),
                'default' => false
            ),
            array(
                'id'      => 'box-shadow',
                'type'    => 'box_shadow',
                'title'   => esc_html__('Box Shadow', 'zocker'),
                'units'   => array( 'px', 'em', 'rem' ),
                'output'  => ( '.blog-sidebar .widget' ),
                'opacity' => true,
                'rgba'    => true,
                'required'=> array( 'zocker_sidebar_padding_margin_box_shadow_trigger', 'equals' , '1' )
            ),
            array(
                'id'      => 'zocker_sidebar_widget_margin',
                'type'    => 'spacing',
                'title'   => esc_html__('Widget Margin', 'zocker'),
                'units'   => array('em', 'px'),
                'output'  => ( '.blog-sidebar .widget' ),
                'mode'    => 'margin',
                'required'=> array( 'zocker_sidebar_padding_margin_box_shadow_trigger', 'equals' , '1' )
            ),
            array(
                'id'      => 'zocker_sidebar_widget_padding',
                'type'    => 'spacing',
                'title'   => esc_html__('Widget Padding', 'zocker'),
                'units'   => array('em', 'px'),
                'output'  => ( '.blog-sidebar .widget' ),
                'mode'    => 'padding',
                'required'=> array( 'zocker_sidebar_padding_margin_box_shadow_trigger', 'equals' , '1' )
            ),
            array(
                'id'      => 'zocker_sidebar_widget_border',
                'type'    => 'border',
                'title'   => esc_html__('Widget Border', 'zocker'),
                'units'   => array('em', 'px'),
                'output'  => ( '.blog-sidebar .widget' ),
                'all'     => false,
                'required'=> array( 'zocker_sidebar_padding_margin_box_shadow_trigger', 'equals' , '1' )
            ),
            array(
                'id'      => 'zocker_sidebar_widget_title_heading_tag',
                'type'     => 'select',
                'options'  => array(
                    'h1'        => esc_html__('H1','zocker'),
                    'h2'        => esc_html__('H2','zocker'),
                    'h3'        => esc_html__('H3','zocker'),
                    'h4'        => esc_html__('H4','zocker'),
                    'h5'        => esc_html__('H5','zocker'),
                    'h6'        => esc_html__('H6','zocker'),
                ),
                'default'  => 'h4',
                'title'   => esc_html__('Widget Title Tag', 'zocker'),
            ),
            array(
                'id'      => 'zocker_sidebar_widget_title_margin',
                'type'    => 'spacing',
                'title'   => esc_html__('Widget Title Margin', 'zocker'),
                'mode'    => 'margin',
                'output'  => array('.blog-sidebar .widget .widget_title'),
                'units'   => array('em', 'px'),
            ),
            array(
                'id'      => 'zocker_sidebar_widget_title_padding',
                'type'    => 'spacing',
                'title'   => esc_html__('Widget Title Padding', 'zocker'),
                'mode'    => 'padding',
                'output'  => array('.blog-sidebar .widget .widget_title'),
                'units'   => array('em', 'px'),
            ),
            array(
                'id'       => 'zocker_sidebar_widget_title_color',
                'output'   =>  array('.blog-sidebar .widget .widget_title h1,.blog-sidebar .widget .widget_title h2,.blog-sidebar .widget .widget_title h3,.blog-sidebar .widget .widget_title h4,.blog-sidebar .widget .widget_title h5,.blog-sidebar .widget .widget_title h6'),
                'type'     => 'color',
                'title'    => esc_html__('Widget Title Color', 'zocker'),
                'subtitle' => esc_html__('Set Widget Title Color.', 'zocker'),
            ),
            array(
                'id'       => 'zocker_sidebar_widget_text_color',
                'output'   => array('.blog-sidebar .widget'),
                'type'     => 'color',
                'title'    => esc_html__('Widget Text Color', 'zocker'),
                'subtitle' => esc_html__('Set Widget Text Color.', 'zocker'),
            ),
            array(
                'id'       => 'zocker_sidebar_widget_anchor_color',
                'type'     => 'color',
                'output'   => array('.blog-sidebar .widget a'),
                'title'    => esc_html__('Widget Anchor Color', 'zocker'),
                'subtitle' => esc_html__('Set Widget Anchor Color.', 'zocker'),
            ),
            array(
                'id'       => 'zocker_sidebar_widget_anchor_hover_color',
                'type'     => 'color',
                'output'   => array('.blog-sidebar .widget a:hover'),
                'title'    => esc_html__('Widget Hover Color', 'zocker'),
                'subtitle' => esc_html__('Set Widget Anchor Hover Color.', 'zocker'),
            )
        )
    ));
    /* Sidebar End */

    /* End blog Page */

    // -> START Page Option
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Page', 'zocker' ),
        'id'         => 'zocker_page_page',
        'icon'  => 'el el-file',
        'fields'     => array(
            array(
                'id'       => 'zocker_page_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Select layout', 'zocker' ),
                'subtitle' => esc_html__( 'Choose your page layout. If you use this option then you will able to choose three type of page layout ( Default no sidebar ). ', 'zocker' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','zocker'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'zocker_page_layoutopt',
                'type'     => 'button_set',
                'title'    => esc_html__('Sidebar Settings', 'zocker'),
                'subtitle' => esc_html__('Set page sidebar. If you use this option then you will able to set three type of sidebar ( Default no sidebar ).', 'zocker'),
                //Must provide key => value pairs for options
                'options' => array(
                    '1' => esc_html__( 'Page Sidebar', 'zocker' ),
                    '2' => esc_html__( 'Blog Sidebar', 'zocker' )
                 ),
                'default' => '1',
                'required'  => array('zocker_page_sidebar','!=','1')
            ),
            array(
                'id'       => 'zocker_page_title_switcher',
                'type'     => 'switch',
                'title'    => esc_html__('Title', 'zocker'),
                'subtitle' => esc_html__('Switch enabled to display page title. Fot this option you will able to show / hide page title.  Default setting Enabled', 'zocker'),
                'default'  => '1',
                'on'        => esc_html__('Enabled','zocker'),
                'off'       => esc_html__('Disabled','zocker'),
            ),
            array(
                'id'       => 'zocker_page_title_tag',
                'type'     => 'select',
                'options'  => array(
                    'h1'        => esc_html__('H1','zocker'),
                    'h2'        => esc_html__('H2','zocker'),
                    'h3'        => esc_html__('H3','zocker'),
                    'h4'        => esc_html__('H4','zocker'),
                    'h5'        => esc_html__('H5','zocker'),
                    'h6'        => esc_html__('H6','zocker'),
                ),
                'default'  => 'h1',
                'title'    => esc_html__( 'Title Tag', 'zocker' ),
                'subtitle' => esc_html__( 'Select page title tag. If you use this option then you can able to change title tag H1 - H6 ( Default tag H1 )', 'zocker' ),
                'required' => array("zocker_page_title_switcher","equals","1")
            ),
            array(
                'id'       => 'zocker_allHeader_title_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Title Color', 'zocker' ),
                'subtitle' => esc_html__( 'Set Title Color', 'zocker' ),
                'output'   => array( 'color' => '.breadcumb-title' ),
            ),
            array(
                'id'       => 'zocker_allHeader_bg',
                'type'     => 'background',
                'title'    => esc_html__( 'Background', 'zocker' ),
                'output'   => array('.breadcumb-wrapper'),
                'subtitle' => esc_html__( 'Setting page header background. If you use this option then you will able to set Background Color, Background Image, Background Repeat, Background Size, Background Attachment, Background Position.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_enable_breadcrumb',
                'type'     => 'switch',
                'title'    => esc_html__( 'Breadcrumb Hide/Show', 'zocker' ),
                'subtitle' => esc_html__( 'Hide / Show breadcrumb from all pages and posts ( Default settings hide ).', 'zocker' ),
                'default'  => '1',
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'zocker_allHeader_breadcrumbtextcolor',
                'type'     => 'color',
                'title'    => esc_html__( 'Breadcrumb Color', 'zocker' ),
                'subtitle' => esc_html__( 'Choose page header breadcrumb text color here.If you user this option then you will able to set page breadcrumb color.', 'zocker' ),
                'required' => array("zocker_page_title_switcher","equals","1"),
                'output'   => array( 'color' => '.breadcumb-layout1 .breadcumb-content ul li a' ),
            ),
            array(
                'id'       => 'zocker_allHeader_breadcrumbtextactivecolor',
                'type'     => 'color',
                'title'    => esc_html__( 'Breadcrumb Active Color', 'zocker' ),
                'subtitle' => esc_html__( 'Choose page header breadcrumb text active color here.If you user this option then you will able to set page breadcrumb active color.', 'zocker' ),
                'required' => array( "zocker_page_title_switcher", "equals", "1" ),
                'output'   => array( 'color' => '.breadcumb-layout1 .breadcumb-content ul li:last-child' ),
            ),
            array(
                'id'       => 'zocker_allHeader_dividercolor',
                'type'     => 'color',
                'output'   => array( 'color'=>'.breadcumb-layout1 .breadcumb-content ul li:after' ),
                'title'    => esc_html__( 'Breadcrumb Divider Color', 'zocker' ),
                'subtitle' => esc_html__( 'Choose breadcrumb divider color.', 'zocker' ),
            ),
        ),
    ) );
    /* End Page option */

    // -> START 404 Page

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( '404 Page', 'zocker' ),
        'id'         => 'zocker_404_page',
        'icon'       => 'el el-ban-circle',
        'fields'     => array(
            array(
               'id'       => 'zocker_404_image',
               'type'     => 'media',
               'compiler' => true,
               'title'    => esc_html__( '404 Image', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_fof_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Page Title', 'zocker' ),
                'subtitle' => esc_html__( 'Set Page Title', 'zocker' ),
                'default'  => esc_html__( '404', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_fof_subtitle',
                'type'     => 'text',
                'title'    => esc_html__( 'Page Subtitle', 'zocker' ),
                'subtitle' => esc_html__( 'Set Page Subtitle ', 'zocker' ),
                'default'  => esc_html__( 'The page you\'ve requested is not available.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_fof_btn_text',
                'type'     => 'text',
                'title'    => esc_html__( 'Button Text', 'zocker' ),
                'subtitle' => esc_html__( 'Set Button Text ', 'zocker' ),
                'default'  => esc_html__( 'Return To Home', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_fof_text_color',
                'type'     => 'color',
                'output'   => array( '.vs-error-wrapper h2' ),
                'title'    => esc_html__( 'Title Color', 'zocker' ),
                'subtitle' => esc_html__( 'Pick a title color', 'zocker' ),
                'validate' => 'color'
            ),
            array(
                'id'       => 'zocker_fof_subtitle_color',
                'type'     => 'color',
                'output'   => array( '.vs-error-wrapper p' ),
                'title'    => esc_html__( 'Subtitle Color', 'zocker' ),
                'subtitle' => esc_html__( 'Pick a subtitle color', 'zocker' ),
                'validate' => 'color'
            ),
            array(
                'id'       => 'zocker_fof_btn_color',
                'type'     => 'color',
                'output'   => array( '.vs-btn.gradient-btn' ),
                'title'    => esc_html__('Button Color', 'zocker'),
                'subtitle' => esc_html__('Pick Button Color', 'zocker'),
                'validate' => 'color'
            ),
            array(
                'id'       => 'zocker_fof_btn_color_hover',
                'type'     => 'color',
                'output'   => array( '.vs-btn.gradient-btn:hover' ),
                'title'    => esc_html__( 'Button Hover Color', 'zocker'),
                'subtitle' => esc_html__( 'Pick Button Hover Color', 'zocker'),
                'validate' => 'color'
            ),
        ),
    ) );

    /* End 404 Page */
    // -> START Woo Page Option

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Woocommerce Page', 'zocker' ),
        'id'         => 'zocker_woo_page_page',
        'icon'  => 'el el-shopping-cart',
        'fields'     => array(
            array(
                'id'       => 'zocker_woo_shoppage_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Set Shop Page Sidebar.', 'zocker' ),
                'subtitle' => esc_html__( 'Choose shop page sidebar', 'zocker' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','zocker'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'zocker_woo_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Product Column', 'zocker' ),
                'subtitle' => esc_html__( 'Set your woocommerce product column.', 'zocker' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '2' => array(
                        'alt' => esc_attr__('2 Columns','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('3 Columns','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '4' => array(
                        'alt' => esc_attr__('4 Columns','zocker'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '5' => array(
                        'alt' => esc_attr__('5 Columns','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/5col.png')
                    ),
                    '6' => array(
                        'alt' => esc_attr__('6 Columns','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),
                    '5' => array(
                        'alt' => esc_attr__('5 Columns','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/5col.png')
                    ),
                    '6' => array(
                        'alt' => esc_attr__('6 Columns','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),),
                'default'  => '3'
            ),
			array(
                'id'       => 'zocker_woo_product_perpage',
                'type'     => 'text',
                'title'    => esc_html__( 'Product Per Page', 'zocker' ),
				'default' => '10'
            ),
            array(
                'id'       => 'zocker_woo_singlepage_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Product Single Page sidebar', 'zocker' ),
                'subtitle' => esc_html__( 'Choose product single page sidebar.', 'zocker' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','zocker'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'zocker_product_details_title_position',
                'type'     => 'button_set',
                'default'  => 'below',
                'options'  => array(
                    'header'        => esc_html__('On Header','zocker'),
                    'below'         => esc_html__('Below Thumbnail','zocker'),
                ),
                'title'    => esc_html__('Product Details Title Position', 'zocker'),
                'subtitle' => esc_html__('Control product details title position from here.', 'zocker'),
            ),
            array(
                'id'       => 'zocker_product_details_custom_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Product Details Title', 'zocker' ),
                'default'  => esc_html__( 'Shop Details', 'zocker' ),
                'required' => array('zocker_product_details_title_position','equals','below'),
            ),
            array(
                'id'       => 'zocker_product_details_custom_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Product Details Title', 'zocker' ),
                'default'  => esc_html__( 'Shop Details', 'zocker' ),
                'required' => array('zocker_product_details_title_position','equals','below'),
            ),
            array(
                'id'       => 'zocker_woo_relproduct_display',
                'type'     => 'switch',
                'title'    => esc_html__( 'Related product Hide/Show', 'zocker' ),
                'subtitle' => esc_html__( 'Hide / Show related product in single page (Default Settings Show)', 'zocker' ),
                'default'  => '1',
                'on'       => esc_html__('Show','zocker'),
                'off'      => esc_html__('Hide','zocker')
            ),
			array(
                'id'       => 'zocker_woo_relproduct_num',
                'type'     => 'text',
                'title'    => esc_html__( 'Related products number', 'zocker' ),
                'subtitle' => esc_html__( 'Set how many related products you want to show in single product page.', 'zocker' ),
                'default'  => 3,
                'required' => array('zocker_woo_relproduct_display','equals',true)
            ),

            array(
                'id'       => 'zocker_woo_related_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Related Product Column', 'zocker' ),
                'subtitle' => esc_html__( 'Set your woocommerce related product column.', 'zocker' ),
                'required' => array('zocker_woo_relproduct_display','equals',true),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '6' => array(
                        'alt' => esc_attr__('2 Columns','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '4' => array(
                        'alt' => esc_attr__('3 Columns','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '3' => array(
                        'alt' => esc_attr__('4 Columns','zocker'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('6 Columns','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),

                ),
                'default'  => '4'
            ),
            array(
                'id'       => 'zocker_woo_upsellproduct_display',
                'type'     => 'switch',
                'title'    => esc_html__( 'Upsell product Hide/Show', 'zocker' ),
                'subtitle' => esc_html__( 'Hide / Show upsell product in single page (Default Settings Show)', 'zocker' ),
                'default'  => '1',
                'on'       => esc_html__('Show','zocker'),
                'off'      => esc_html__('Hide','zocker'),
            ),
            array(
                'id'       => 'zocker_woo_upsellproduct_num',
                'type'     => 'text',
                'title'    => esc_html__( 'Upsells products number', 'zocker' ),
                'subtitle' => esc_html__( 'Set how many upsells products you want to show in single product page.', 'zocker' ),
                'default'  => 3,
                'required' => array('zocker_woo_upsellproduct_display','equals',true),
            ),

            array(
                'id'       => 'zocker_woo_upsell_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Upsells Product Column', 'zocker' ),
                'subtitle' => esc_html__( 'Set your woocommerce upsell product column.', 'zocker' ),
                'required' => array('zocker_woo_upsellproduct_display','equals',true),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '6' => array(
                        'alt' => esc_attr__('2 Columns','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '4' => array(
                        'alt' => esc_attr__('3 Columns','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '3' => array(
                        'alt' => esc_attr__('4 Columns','zocker'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('6 Columns','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),

                ),
                'default'  => '4'
            ),
            array(
                'id'       => 'zocker_woo_crosssellproduct_display',
                'type'     => 'switch',
                'title'    => esc_html__( 'Cross sell product Hide/Show', 'zocker' ),
                'subtitle' => esc_html__( 'Hide / Show cross sell product in single page (Default Settings Show)', 'zocker' ),
                'default'  => '1',
                'on'       => esc_html__( 'Show', 'zocker' ),
                'off'      => esc_html__( 'Hide', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_woo_crosssellproduct_num',
                'type'     => 'text',
                'title'    => esc_html__( 'Cross sell products number', 'zocker' ),
                'subtitle' => esc_html__( 'Set how many cross sell products you want to show in single product page.', 'zocker' ),
                'default'  => 3,
                'required' => array('zocker_woo_crosssellproduct_display','equals',true),
            ),

            array(
                'id'       => 'zocker_woo_crosssell_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Cross sell Product Column', 'zocker' ),
                'subtitle' => esc_html__( 'Set your woocommerce cross sell product column.', 'zocker' ),
                'required' => array( 'zocker_woo_crosssellproduct_display', 'equals', true ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '6' => array(
                        'alt' => esc_attr__('2 Columns','zocker'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '4' => array(
                        'alt' => esc_attr__('3 Columns','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '3' => array(
                        'alt' => esc_attr__('4 Columns','zocker'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('6 Columns','zocker'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),

                ),
                'default'  => '4'
            ),
        ),
    ) );

    /* End Woo Page option */
    // -> START Gallery
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Gallery', 'zocker' ),
        'id'         => 'zocker_gallery_widget',
        'icon'       => 'el el-gift',
        'fields'     => array(
            array(
                'id'          => 'zocker_gallery_image_widget',
                'type'        => 'slides',
                'title'       => esc_html__('Add Gallery Image', 'zocker'),
                'subtitle'    => esc_html__('Add gallery Image and url.', 'zocker'),
                'show'        => array(
                    'title'          => false,
                    'description'    => false,
                    'progress'       => false,
                    'icon'           => false,
                    'facts-number'   => false,
                    'facts-title1'   => false,
                    'facts-title2'   => false,
                    'facts-number-2' => false,
                    'facts-title3'   => false,
                    'facts-number-3' => false,
                    'url'            => true,
                    'project-button' => false,
                    'image_upload'   => true,
                ),
            ),
        ),
    ) );
    // -> START Subscribe
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Subscribe', 'zocker' ),
        'id'         => 'zocker_subscribe_page',
        'icon'       => 'el el-eject',
        'fields'     => array(

            array(
                'id'       => 'zocker_subscribe_apikey',
                'type'     => 'text',
                'title'    => esc_html__( 'Mailchimp API Key', 'zocker' ),
                'subtitle' => esc_html__( 'Set mailchimp api key.', 'zocker' ),
            ),
            array(
                'id'       => 'zocker_subscribe_listid',
                'type'     => 'text',
                'title'    => esc_html__( 'Mailchimp List ID', 'zocker' ),
                'subtitle' => esc_html__( 'Set mailchimp list id.', 'zocker' ),
            ),
        ),
    ) );

    /* End Subscribe */

    // -> START Social Media

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Social', 'zocker' ),
        'id'         => 'zocker_social_media',
        'icon'      => 'el el-globe',
        'desc'      => esc_html__( 'Social', 'zocker' ),
        'fields'     => array(
            array(
                'id'          => 'zocker_social_links',
                'type'        => 'slides',
                'title'       => esc_html__('Social Profile Links', 'zocker'),
                'subtitle'    => esc_html__('Add social icon and url.', 'zocker'),
                'show'        => array(
                    'title'          => true,
                    'description'    => true,
                    'progress'       => false,
                    'facts-number'   => false,
                    'facts-title1'   => false,
                    'facts-title2'   => false,
                    'facts-number-2' => false,
                    'facts-title3'   => false,
                    'facts-number-3' => false,
                    'url'            => true,
                    'project-button' => false,
                    'image_upload'   => false,
                ),
                'placeholder'   => array(
                    'icon'          => esc_html__( 'Icon (example: fa fa-facebook) ','zocker'),
                    'title'         => esc_html__( 'Social Icon Class', 'zocker' ),
                    'description'   => esc_html__( 'Social Icon Title', 'zocker' ),
                ),
            ),
        ),
    ) );

    /* End social Media */


    // -> START Footer Media
    Redux::setSection( $opt_name , array(
       'title'            => esc_html__( 'Footer', 'zocker' ),
       'id'               => 'zocker_footer',
       'desc'             => esc_html__( 'zocker Footer', 'zocker' ),
       'customizer_width' => '400px',
       'icon'              => 'el el-photo',
   ) );

   Redux::setSection( $opt_name, array(
       'title'      => esc_html__( 'Pre-built Footer / Footer Builder', 'zocker' ),
       'id'         => 'zocker_footer_section',
       'subsection' => true,
       'fields'     => array(
            array(
               'id'       => 'zocker_footer_builder_trigger',
               'type'     => 'button_set',
               'default'  => 'prebuilt',
               'options'  => array(
                   'footer_builder'        => esc_html__('Footer Builder','zocker'),
                   'prebuilt'              => esc_html__('Pre-built Footer','zocker'),
               ),
               'title'    => esc_html__( 'Footer Builder', 'zocker' ),
            ),
            array(
               'id'       => 'zocker_footer_builder_select',
               'type'     => 'select',
               'required' => array( 'zocker_footer_builder_trigger','equals','footer_builder'),
               'data'     => 'posts',
               'args'     => array(
                   'post_type'     => 'zocker_footer'
               ),
               'on'       => esc_html__( 'Enabled', 'zocker' ),
               'off'      => esc_html__( 'Disable', 'zocker' ),
               'title'    => esc_html__( 'Select Footer', 'zocker' ),
               'subtitle' => sprintf( wp_kses_post('First make your footer from footer custom types then select it from here. To make footer <a href="%sedit.php?post_type=zocker_footer" target="_blank">Click Here</a>', 'zocker' ) , esc_url( admin_url() ) )
            ),
            array(
               'id'       => 'zocker_newsletter_enable',
               'type'     => 'switch',
               'title'    => esc_html__( 'Footer Newsletter?', 'zocker' ),
               'default'  => 0,
               'on'       => esc_html__( 'Enabled', 'zocker' ),
               'off'      => esc_html__( 'Disable', 'zocker' ),
               'required' => array( 'zocker_footer_builder_trigger','equals','prebuilt'),
            ),
            array(
               'id'       => 'zocker_footer_newsletter_background',
               'type'     => 'background',
               'title'    => esc_html__( 'Newsletter Background', 'zocker' ),
               'subtitle' => esc_html__( 'Set Newsletter background.', 'zocker' ),
               'output'   => array( '.newsletter-bg' ),
               'required' => array( 'zocker_newsletter_enable','=','1' ),
            ),
            array(
               'id'       => 'zocker_newsletter_subtitle',
               'type'     => 'text',
               'title'    => esc_html__( 'Newsletter Subtitle Text', 'zocker' ),
               'subtitle' => esc_html__( 'Add Newsletter Subtitle Text', 'zocker' ),
               'default'  => esc_html__( 'NEWSLETTER', 'zocker' ),
               'required' => array( 'zocker_newsletter_enable','equals','1' ),
            ),
            array(
               'id'       => 'zocker_newsletter_title',
               'type'     => 'text',
               'title'    => esc_html__( 'Newsletter Title Text', 'zocker' ),
               'subtitle' => esc_html__( 'Add Newsletter Title Text', 'zocker' ),
               'default'  => esc_html__( 'GET MONTHLY UPDATES', 'zocker' ),
               'required' => array( 'zocker_newsletter_enable','equals','1' ),
            ),
            array(
               'id'       => 'zocker_footerwidget_enable',
               'type'     => 'switch',
               'title'    => esc_html__( 'Footer Widget', 'zocker' ),
               'default'  => 0,
               'on'       => esc_html__( 'Enabled', 'zocker' ),
               'off'      => esc_html__( 'Disable', 'zocker' ),
               'required' => array( 'zocker_footer_builder_trigger','equals','prebuilt'),
            ),
            array(
               'id'       => 'zocker_footerwidget_style',
               'type'     => 'button_set',
               'title'    => esc_html__( 'Footer Style Type', 'zocker'),
               'subtitle' => esc_html__( 'Choose Footer Style', 'zocker'),
               'required' => array( 'zocker_footerwidget_enable' , '=', '1'),
               //Must provide key => value pairs for options
               'options' => array(
                   '1'     => esc_html__( 'Style One', 'zocker' ),
                   '2'     => esc_html__( 'Style Two', 'zocker' ),
                ),
               'default' => '2'
            ),
            array(
               'id'       => 'zocker_footercol_switch',
               'type'     => 'image_select',
               'title'    => esc_html__( 'Select Widget Column', 'zocker' ),
               //Must provide key => value(array:title|img) pairs for radio options
               'options'  => array(
                   '1' => array(
                       'alt' => esc_attr__('2 Column Left','zocker'),
                       'img' => ReduxFramework::$_url . 'assets/img/2-col-portfolio.png'
                   ),
                   '2' => array(
                       'alt' => esc_attr__('3 Column Right','zocker'),
                       'img' => ReduxFramework::$_url . 'assets/img/3-col-portfolio.png'
                   ),

                   '3' => array(
                       'alt' => esc_attr__('4 Column Right','zocker'),
                       'img' => ReduxFramework::$_url . 'assets/img/4-col-portfolio.png'
                   ),

               ),
               'default'  => '3',
               'required' => array('zocker_footerwidget_enable','=','1')
            ),
            array(
               'id'       => 'zocker_footer_background',
               'type'     => 'background',
               'title'    => esc_html__( 'Footer Background', 'zocker' ),
               'subtitle' => esc_html__( 'Set footer background.', 'zocker' ),
               'output'   => array( '.footer-background' ),
               'required' => array( 'zocker_footerwidget_enable','=','1' ),
            ),
            array(
               'id'       => 'zocker_footer_widget_title_color',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Widget Title Color', 'zocker' ),
               'required' => array('zocker_footerwidget_enable','=','1'),
               'output'   => array( '.footer-layout1 .footer-wid-wrap .footer-widget h3.widget_title' ),
           ),
           array(
               'id'       => 'zocker_footer_widget_color',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Widget Text Color', 'zocker' ),
               'required' => array( 'zocker_footerwidget_enable','=','1' ),
               'output'   => array( '.footer-layout1 .footer-wid-wrap .widget_contact p' ),
           ),
           array(
               'id'       => 'zocker_footer_widget_anchor_color',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Widget Anchor Color', 'zocker' ),
               'required' => array('zocker_footerwidget_enable','=','1'),
               'output'   => array( '.footer-layout1 .footer-wid-wrap .widget_contact p a,.footer-layout1 .footer-wid-wrap .widget-links ul li a' ),
           ),
           array(
               'id'       => 'zocker_footer_widget_anchor_hov_color',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Widget Anchor Hover Color', 'zocker' ),
               'required' => array('zocker_footerwidget_enable','=','1'),
               'output'   => array( '.footer-layout1 .footer-wid-wrap .widget_contact p a:hover,.footer-layout1 .footer-wid-wrap .widget-links ul li a:hover' ),
           ),
           array(
               'id'       => 'zocker_disable_footer_bottom',
               'type'     => 'switch',
               'title'    => esc_html__( 'Footer Bottom?', 'zocker' ),
               'default'  => 1,
               'on'       => esc_html__('Enabled','zocker'),
               'off'      => esc_html__('Disable','zocker'),
               'required' => array('zocker_footer_builder_trigger','equals','prebuilt'),
            ),
            array(
               'id'       => 'zocker_footer_bottom_background',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Bottom Background Color', 'zocker' ),
               'required' => array( 'zocker_disable_footer_bottom','=','1' ),
               'output'   => array( 'background-color'   =>   '.footer-copyright' ),
            ),
            array(
               'id'       => 'zocker_copyright_text',
               'type'     => 'text',
               'title'    => esc_html__( 'Copyright Text', 'zocker' ),
               'subtitle' => esc_html__( 'Add Copyright Text', 'zocker' ),
               'default'  => sprintf( 'Copyright <i class="fal fa-copyright"></i> %s <a href="%s">%s</a> All Rights Reserved by <a href="%s">%s</a>',date('Y'),esc_url('#'),__( 'Zocker.','zocker' ),esc_url('#'),__( 'Vecuro', 'zocker' ) ),
               'required' => array( 'zocker_disable_footer_bottom','equals','1' ),
            ),
            array(
               'id'       => 'zocker_footer_copyright_color',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Copyright Text Color', 'zocker' ),
               'subtitle' => esc_html__( 'Set footer copyright text color', 'zocker' ),
               'required' => array( 'zocker_disable_footer_bottom','equals','1'),
               'output'   => array( '.footer-copyright p' ),
            ),
            array(
               'id'       => 'zocker_footer_copyright_acolor',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Copyright Ancor Color', 'zocker' ),
               'subtitle' => esc_html__( 'Set footer copyright ancor color', 'zocker' ),
               'required' => array( 'zocker_disable_footer_bottom','equals','1'),
               'output'   => array( '.footer-copyright p a' ),
            ),
            array(
               'id'       => 'zocker_footer_copyright_a_hover_color',
               'type'     => 'color',
               'title'    => esc_html__( 'Footer Copyright Ancor Hover Color', 'zocker' ),
               'subtitle' => esc_html__( 'Set footer copyright ancor Hover color', 'zocker' ),
               'required' => array( 'zocker_disable_footer_bottom','equals','1'),
               'output'   => array( '.footer-copyright p a:hover' ),
            ),

       ),
   ) );


    /* End Footer Media */

    // -> START Custom Css
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Custom Css', 'zocker' ),
        'id'         => 'zocker_custom_css_section',
        'icon'  => 'el el-css',
        'fields'     => array(
            array(
                'id'       => 'zocker_css_editor',
                'type'     => 'ace_editor',
                'title'    => esc_html__('CSS Code', 'zocker'),
                'subtitle' => esc_html__('Paste your CSS code here.', 'zocker'),
                'mode'     => 'css',
                'theme'    => 'monokai',
            )
        ),
    ) );

    /* End custom css */



    if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
        $section = array(
            'icon'   => 'el el-list-alt',
            'title'  => __( 'Documentation', 'zocker' ),
            'fields' => array(
                array(
                    'id'       => '17',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
        );
        Redux::setSection( $opt_name, $section );
    }
    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $field['msg']    = 'your custom error message';
                $return['error'] = $field;
            }

            if ( $warning == true ) {
                $field['msg']      = 'your custom warning message';
                $return['warning'] = $field;
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => __( 'Section via hook', 'zocker' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'zocker' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }