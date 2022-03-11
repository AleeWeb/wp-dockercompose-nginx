<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "arcane_redux";

    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );



    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $theme_menu_icon = get_theme_file_uri('img/icons/wplogo.png');


    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        'disable_tracking' => true,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => 'Arcane',
        'page_title'           => 'Arcane Options',
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => false,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => false,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => false,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => 27,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => $theme_menu_icon,
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => '',
        // icon displayed in the admin panel next to your menu_title
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
        'footer_credit'     => ' ',                   // Disable the footer credit of Redux. Please leave if you can help it.

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


    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/skywarriorthemes/',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = '';
    } else {
         $args['intro_text'] = '';
    }

    // Add content after the form.
    $args['footer_text'] = '';

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /* EXT LOADER */
    if(!function_exists('redux_register_custom_extension_loader')) :
    function redux_register_custom_extension_loader($ReduxFramework) {
        $path = WP_PLUGIN_DIR.'/arcane_custom_post_types/extensions/';

	    if(file_exists($path)) {

		    $folders = scandir( $path, 1 );

		    foreach ( $folders as $folder ) {
			    if ( $folder === '.' or $folder === '..' or ! is_dir( $path . $folder ) ) {
				    continue;
			    }
			    $extension_class = 'ReduxFramework_Extension_' . $folder;


			    if ( ! class_exists( $extension_class ) ) {
				    // In case you wanted override your override, hah.
				    $class_file = $path . $folder . '/extension_' . $folder . '.php';
				    $class_file = apply_filters( 'redux/extension/' . $ReduxFramework->args['opt_name'] . '/' . $folder, $class_file );
				    if ( $class_file ) {

					    $arcane_activator = new Arcane_Product_Registration();
					    $is_registered    = $arcane_activator->is_registered();

					    if ( $extension_class == 'ReduxFramework_Extension_wbc_importer' && ! $is_registered ) {
						    continue;
					    }

					    require_once( $class_file );
					    $extension = new $extension_class( $ReduxFramework );
				    }
			    }
		    }
	    }
    }
    // Modify {$redux_opt_name} to match your opt_name
    add_action("redux/extensions/".$opt_name ."/before", 'redux_register_custom_extension_loader', 0);
    endif;


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*
        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for
     */

     #************************************************
    # General
    #************************************************

    Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'General Settings', 'arcane' ),
        'id'               => 'general-settings',
        'customizer_width' => '200px',
        'desc'             => esc_html__('Welcome to the Arcane options panel! You can switch between option groups by using the left-hand tabs.', 'arcane' ),
        'fields'           => array(
           )
    ) );

    Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'General Settings', 'arcane' ),
        'id'               => 'general-settings-options',
        'subsection'       => true,
        'fields'           => array(

         array(
                'id' => 'logo',
                'type' => 'media',
                'title' => esc_html__('Upload Your Logo', 'arcane'),
                'subtitle' => esc_html__('Upload your logo image.', 'arcane'),
                'default' => array(
                        'url'=> get_theme_file_uri('img/logo.png')
                    ),
            ),

             array(
                'id' => 'login_menu',
                'type' => 'switch',
                'title' => esc_html__('Header Login Button', 'arcane'),
                'subtitle' => esc_html__('Enable the login button in the right hand side of the header.', 'arcane'),
                'desc' => '',
                'default' => true
            )
        )
    ) );

    Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'News ticker', 'arcane' ),
        'id'               => 'news-ticker',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id' => 'newsticker',
                'type' => 'switch',
                'title' => esc_html__('Show News Ticker', 'arcane'),
                'subtitle' => esc_html__('Enable news ticker.', 'arcane'),
                'desc' => '',
                'default' => true
            ),
             array(
                'id' => 'tickertitle',
                'type' => 'text',
                'title' => esc_html__('Ticker Title', 'arcane'),
                'subtitle' => esc_html__('Add ticker title.', 'arcane'),
            ),
               array(
                'id' => 'tickeritems',
                'type' => 'textarea',
                'title' => esc_html__('Ticker Items', 'arcane'),
                'subtitle' => esc_html__('Add ticker items. Use || sign to separate items.', 'arcane'),
            ),
             array(
                'id' => 'tickerspeed',
                'type' => 'text',
                'title' => esc_html__('Ticker Speed', 'arcane'),
                'subtitle' => esc_html__('Add ticker speed. The movement speed in pixels per second, default is 50.', 'arcane'),
                'default' => '50',
                'validate' => 'numeric',
            ),
        )
    )
    );

    Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'Archive Page Template', 'arcane' ),
        'id'               => 'archive-page-template',
        'subsection'       => true,
        'fields'           => array(
            array(
            'type' => 'select',
            'id'     => 'archive_template',
            'title' => esc_html__('Choose Category/Archive Page Template', 'arcane'),
            'subtitle' => esc_html__('Choose template for your category/archive page.', 'arcane'),
            'options' => array(
                    "full" => "Full width",
                    "right" => "Right Sidebar",
                    "left" => "Left Sidebar",
                ),
            'default' => 'right',
             ),
        )
    )
    );


     Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'Posts', 'arcane' ),
        'id'               => 'posts-settings',
        'subsection'       => true,
        'fields'           => array(
            array(
            'type' => 'select',
            'id'     => 'rating_type',
            'title' => esc_html__('Rating Type', 'arcane'),
            'subtitle' => esc_html__('Choose rating type for your posts.', 'arcane'),
            'options' => array(
                    "numbers" => esc_html__("Numbers", 'arcane'),
                    "stars" => esc_html__("Stars", 'arcane'),
                ),
            'default' => 'stars',
             ),
             array(
            'type' => 'select',
            'id'     => 'posts_template',
            'title' => esc_html__('Single Post Template', 'arcane'),
            'subtitle' => esc_html__('Choose template for posts.', 'arcane'),
            'options' => array(
                    "right" => esc_html__("Right sidebar",'arcane'),
                    "left" => esc_html__("Left sidebar",'arcane'),
                    "full" => esc_html__("Full width",'arcane'),
                ),
            'default' => 'right',
             ),
        )
    )
    );

    Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'Registration page', 'arcane' ),
        'id'               => 'reg-page-settings',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id' => 'cpagetitle',
                'type' => 'text',
                'title' => esc_html__('Registration Page Title', 'arcane'),
                'subtitle' => esc_html__('Add text for registration page title.', 'arcane'),
            ),
            array(
                'id' => 'terms',
                'type' => 'text',
                'title' => esc_html__('Terms & Conditions', 'arcane'),
                'subtitle' => esc_html__('Add Terms & Conditions link.', 'arcane'),
                'validate' => 'url',
            ),
             array(
                'id' => 'eighteen',
                'type' => 'text',
                'title' => esc_html__('Confirmation Text', 'arcane'),
                'subtitle' => esc_html__('Add confirmation text.', 'arcane'),
                'default' => esc_html__('I certify that I\'m over 18 and I agree to the ', 'arcane'),

            ),
        )
    )
    );

    Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'Page preloader', 'arcane' ),
        'id'               => 'general-settings-styling',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id' => 'preloader',
                'type' => 'switch',
                'title' => esc_html__('Enable Page Preloading', 'arcane'),
                'subtitle' => esc_html__('Turn this on to enable page preloading.', 'arcane'),
                'desc' => '',
                'default' => true
            ),
             array(
            'type' => 'select',
            'id'     => 'preloader_icon',
            'title' => esc_html__('Loading Icon', 'arcane'),
            'subtitle' => esc_html__('Choose preferred loading icon styling.', 'arcane'),
            'options' => array(
                    "sk-folding-cube" => "Folding Cube",
                    "rotating-plane" => "Rotating Plane",
                    "double-bounce" => "Double Bounce",
                    "rectangle-bounce" => "Rectangle Bounce",
                    "wandering-cubes" => "Wandering Cubes",
                    "pulse" => "Pulse",
                    "chasing-dots" => "Chasing Dots",
                    "three-bounce" => "Three Bounce",
                    "sk-circle" => "Circle",
                    "sk-cube-grid" => "Cube Grid",
                    "sk-fading-circle" => "Fading Circle",

                ),
            'default' => 'sk-folding-cube',
            'required' => array( 'preloader', '=', '1' ),
             ),
        )
    )
    );


#************************************************
# Customize
#************************************************

Redux::set_section( $opt_name, array(
    'title'            => esc_html__( 'Customize', 'arcane' ),
    'id'               => 'customize-settings',
    'icon'              => 'fas fa-magic',
    'fields'           => array( )
));

Redux::set_section( $opt_name, array(
    'title'            => esc_html__( 'Backgrounds', 'arcane' ),
    'id'               => 'customize-settings-backgrounds',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id' => 'header_bg',
            'type' => 'media',
            'title' => esc_html__('Page Background Image', 'arcane'),
            'subtitle' => esc_html__('Background image of the site', 'arcane'),
            'default' => array(
                'url'=> get_theme_file_uri('img/header.jpg')
            ),
        ),
        array(
            'id' => 'bg_color',
            'type' => 'color',
            'title' => esc_html__('Page Background Colour', 'arcane'),
            'subtitle' => esc_html__("Background color of the site", 'arcane'),
            'transparent' => false,
            'default' => '#1b1f28',
            'output' => array('background-color' => 'html body, html, .se-pre-con',)
        ),

    )
) );


Redux::set_section( $opt_name, array(
    'title'            => esc_html__( 'Colours', 'arcane' ),
    'id'               => 'customize-settings-colours',
    'subsection'       => true,
    'fields'           => array(




        array(
            'id' => 'primary_color',
            'type' => 'color',
            'title' => esc_html__('Primary Color', 'arcane'),
            'subtitle' => esc_html__('Affects different background and items of the site like links, some buttons, etc.', 'arcane'),
            'transparent' => false,
            'default' => '#ff8800',

        ),
        array(
            'id' => 'secondary_color',
            'type' => 'color',
            'title' => esc_html__('Secondary Color', 'arcane'),
            'subtitle' => esc_html__('Mainly used for button hover color.', 'arcane'),
            'transparent' => false,
            'default' => '#ff5200',
        ),

        array(
            'id' => 'anchor_hover_color',
            'type' => 'color',
            'title' => esc_html__('Link Hover Color', 'arcane'),
            'subtitle' => esc_html__('Color for the links on hover.', 'arcane'),
            'transparent' => false,
            'default' => '#ffffff',

        ),


    )
) );





	#************************************************
	# Header
	#************************************************
	Redux::set_section( $opt_name, array(
		'title'            => esc_html__( 'Header', 'arcane' ),
		'id'               => 'header-settings',
		'customizer_width' => '450px',
		'icon'			   => 'fas fa-heading',
		'fields'           => array(
	
		)
	) );
	
	Redux::set_section( $opt_name, array(
		'title'            => esc_html__( 'Header General Settings', 'arcane' ),
		'id'               => 'header-general-settings-bckcolor',
		'subsection'       => true,
		'fields'           => array(
	
			array(
				'id' => 'header-settings-background-color-selector',
				'type' => 'select',
				'title' => esc_html__('Background Color Option', 'arcane'),
				'subtitle' => esc_html__('Choose background color option.', 'arcane'),
				'options' => array(
					'color' => esc_html__('Color', 'arcane'),
					'gradient' => esc_html__('Color Gradient', 'arcane')
				),
				'default' => 'gradient'
			),
			array(
				'id' => 'header-bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Background Color', 'arcane'),
				'subtitle' => esc_html__('Select background color for header.', 'arcane'),
				'desc' => '',
                'default' => array(
                    'color'     => '#161e2d',
                    'alpha'     => 0.98
                ),
				'output' => array('background-color' => '.navbar-wrapper'),
				'required' => array('header-settings-background-color-selector', '=', 'color')
			),
			array(
				'id' => 'header-bg-gradient_top',
				'type' => 'color_rgba',
				'title' => esc_html__('Background Color Gradient Top', 'arcane'),
				'subtitle' => esc_html__('Select background color gradient for header.', 'arcane'),
                'default' => array(
                    'color'     => '#161e2d',
                    'alpha'     => 0.98
                ),
				'required' => array('header-settings-background-color-selector', '=', 'gradient')
			),
			array(
				'id' => 'header-bg-gradient_bottom',
				'type' => 'color_rgba',
				'title' => esc_html__('Background Color Gradient Bottom', 'arcane'),
				'subtitle' => esc_html__('Select background color gradient for header.', 'arcane'),
                'default' => array(
                    'color'     => '#202c37',
                    'alpha'     => 0.94
                ),
				'required' => array('header-settings-background-color-selector', '=', 'gradient')
			),
			array(
				'id' => 'header-background-gradient-type',
				'type' => 'select',
				'title' => esc_html__('Background Color Gradient Type', 'arcane'),
				'subtitle' => esc_html__('Select background color gradient type for header.', 'arcane'),
				'required' => array('header-settings-background-color-selector', '=', 'gradient'),
				'options' => array(
					'vertical' => esc_html__('Vertical', 'arcane'),
					'horizontal' => esc_html__('Horizontal', 'arcane'),
					'radial' => esc_html__('Radial', 'arcane'),
					'diagonal' => esc_html__('Diagonal', 'arcane')
				),
				'default' => 'horizontal',
			),

	
		)
	) );

	Redux::set_section( $opt_name, array(
		'title'            => esc_html__( 'Menu Settings', 'arcane' ),
		'id'               => 'menu-settings',
		'subsection'       => true,
		'fields'           => array(

			array(
				'id' => 'menu_color',
				'type' => 'color',
                'output' => array('color' => '.navbar-inverse .nav > li > a'),
				'title' => esc_html__('Menu color', 'arcane'),
                'default' => '#ffffff'
			),
			array(
				'id' => 'bg_menu_color',
				'type' => 'color_rgba',
                'output' => array('background-color' => '.navbar-inverse'),
                'default' => array(
                    'color'     => '#191f2d',
                    'alpha'     => 0.97
                ),
				'title' => esc_html__('Background color', 'arcane'),
			),
		)
	) );



#************************************************
# Page Title
#************************************************
Redux::set_section( $opt_name, array(
    'title'            => esc_html__( 'Page Title', 'arcane' ),
    'id'               => 'page-header-general',
    'customizer_width' => '450px',
    'icon'			   => 'fas fa-file-alt',
    'fields'           => array(

    )
) );


Redux::set_section( $opt_name, array(
    'title'            => esc_html__( 'P. Title General Settings', 'arcane' ),
    'id'               => 'page-header-general-settings',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id' => 'header-settings-switch',
            'type' => 'switch',
            'title' => esc_html__('Show Page Title', 'arcane'),
            'subtitle' => esc_html__('Use this option to turn header on/off.', 'arcane'),
            'default' => true

        ),
        array(
            'id' => 'header-settings-default-image',
            'type' => 'media',
            'title' => esc_html__('Select Default Background Image', 'arcane'),
            'subtitle' => esc_html__('Select default background image for header.', 'arcane'),
            'default'  => array(
                'url'=> get_theme_file_uri('img/defaults/headerbg.jpg')
            ),
        ),

        array(
            'id' => 'page-title-subtitle',
            'type' => 'switch',
            'title' => esc_html__('Page Subtitle Breadcrumbs', 'arcane'),
            'subtitle' => esc_html__('Use this option to turn breadcrumbs on/off.', 'arcane'),
            'options' => array(
                "nothing" => esc_html__("Nothing", 'arcane'),
                "breadcrumbs" => esc_html__("Breadcrumbs", 'arcane'),
            )
        ),
        array(
            'id' => 'header-settings-tint-top',
            'type' => 'color_rgba',
            'title' => esc_html__('Page Header Tint Top', 'arcane'),
            'subtitle' => esc_html__('Adds a tint color to the page header so the title can be read better.', 'arcane'),
            'default' => array(
                'color'     => '#f85800',
                'alpha'     => 1
            ),
            'desc' => '',
        ),
        array(
            'id' => 'header-settings-tint-bottom',
            'type' => 'color_rgba',
            'title' => esc_html__('Page Header Tint Bottom', 'arcane'),
            'subtitle' => esc_html__('Adds a tint color to the page header so the title can be read better.', 'arcane'),
            'default' => array(
                'color'     => '#fb9400',
                'alpha'     => 1
            ),
            'desc' => '',
        ),
        array(
            'id' => 'header-general-settings-padding-top',
            'type' => 'text',
            'title' => esc_html__('Page Top Padding', 'arcane'),
            'subtitle' => esc_html__('Choose desired top padding. Don\'t include "px" in the string. e.g. 30.', 'arcane'),
            'desc' => '',
            'validate' => 'numeric',
            'default' => 100
        ),
        array(
            'id' => 'header-general-settings-padding-bottom',
            'type' => 'text',
            'title' => esc_html__('Page Bottom Padding', 'arcane'),
            'subtitle' => esc_html__('Choose desired bottom padding. Don\'t include "px" in the string. e.g. 30.', 'arcane'),
            'desc' => '',
            'validate' => 'numeric',
            'default' => 100
        ),
    )
) );


	#************************************************
	# Typography
	#************************************************

	Redux::set_section( $opt_name, array(
		'title'  => esc_html__( 'Typography', 'arcane' ),
		'id'     => 'typography',
		'desc'   => esc_html__( 'All typography related options are listed here', 'arcane' ),
		'icon'   => 'fas fa-font',
		'fields' => array(
		)
	) );


	Redux::set_section( $opt_name, array(
		'title'            => esc_html__( 'General HTML elements', 'arcane' ),
		'id'               => 'typography-general',
		'subsection'       => true,
		'fields'           => array(
			array(
				'id'       => 'body_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Body Font', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the body font properties.', 'arcane' ),
				'google'   => true,
				'fonts' =>  '',
				'all_styles'  => false,
				'letter-spacing' => true,
				'default'     => array(
					'color'       => '#f7f7f7',
					'font-weight'  => '400',
					'font-family' => 'Montserrat',
					'google'      => true,
					'font-size'   => '14px',
					'line-height' => '24px'
				),
				'output'   => array('body')

			),
			array(
				'id'       => 'h1_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Heading 1', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the H1 text properties.', 'arcane' ),
				'google'   => true,
				'all_styles'  => false,
				'letter-spacing' => true,
				'fonts' =>  '',
				'default'     => array(
					'font-weight'  => '700',
					'font-family' => 'Montserrat',
				),
				'output'   => array('h1')
			),

			array(
				'id'       => 'h2_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Heading 2', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the H2 text properties.', 'arcane' ),
				'google'   => true,
				'letter-spacing' => true,
				'fonts' =>  '',
				'all_styles'  => false,
				'default'     => array(
					'font-weight'  => '700',
					'font-family' => 'Montserrat',
				),
				'output'   => array('h2')
			),

			array(
				'id'       => 'h3_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Heading 3', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the H3 text properties.', 'arcane' ),
				'google'   => true,
				'all_styles'  => false,
				'letter-spacing' => true,
				'fonts' =>  '',
				'default'     => array(
					'font-weight'  => '700',
					'font-family' => 'Montserrat',
				),
				'output'   => array('h3')
			),

			array(
				'id'       => 'h4_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Heading 4', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the H4 text properties.', 'arcane' ),
				'google'   => true,
				'letter-spacing' => true,
				'all_styles'  => false,
				'fonts' =>  '',
				'default'     => array(
					'font-weight'  => '700',
					'font-family' => 'Montserrat',
				),
				'output'   => array('h4')
			),

			array(
				'id'       => 'h5_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Heading 5', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the H5 text properties.', 'arcane' ),
				'google'   => true,
				'letter-spacing' => true,
				'all_styles'  => false,
				'fonts' =>  '',
				'default'     => array(
					'font-weight'  => '600',
					'font-family' => 'Montserrat',
				),
				'output'   => array('h5')
			),

			array(
				'id'       => 'h6_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Heading 6', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the H6 text properties.', 'arcane' ),
				'google'   => true,
				'letter-spacing' => true,
				'all_styles'  => false,
				'fonts' =>  '',
				'default'     => array(
					'font-weight'  => '800',
					'font-family' => 'Montserrat',
				),
				'output'   => array('h6')
			),

			array(
				'id'       => 'i_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Italic', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the italic text properties.', 'arcane' ),
				'google'   => true,
				'letter-spacing' => true,
				'all_styles'  => false,
				'fonts' =>  '',
				'default'     => array(
					'font-weight'  => '300',
					'font-family' => 'Montserrat',
				),
				'output'   => array('i')
			),
			array(
				'id'       => 'strong_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Strong', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the strong text properties.', 'arcane' ),
				'google'   => true,
				'letter-spacing' => true,
				'all_styles'  => false,
				'fonts' =>  '',
				'default'  => array(),
				'output'   => array('strong')
			),

			array(
				'id'       => 'label_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Form Labels', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the form label properties.', 'arcane' ),
				'google'   => true,
				'letter-spacing' => true,
				'all_styles'  => false,
				'fonts' =>  '',
				'default'  => array(),
				'output'   => array('label')
			),

		)
	) );

	Redux::set_section( $opt_name, array(
		'title'            => esc_html__( 'Other elements', 'arcane' ),
		'id'               => 'typography-other',
		'subsection'       => true,
		'fields'           => array(
			array(
				'id'       => 'widget_title_font_family',
				'type'     => 'typography',
				'title'    => esc_html__( 'Widget Title', 'arcane' ),
				'subtitle' => esc_html__( 'Specify the widget title properties.', 'arcane' ),
				'google'   => true,
				'all_styles'  => false,
				'letter-spacing' => true,
				'fonts' =>  '',
				'default'  => array(),
				'output'   => array('.widget > h4,.meminfo h2')
			),
		)
	) );

    #************************************************
    # Footer
    #************************************************
    Redux::set_section( $opt_name, [
        'title'            => esc_html__( 'Footer', 'arcane' ),
        'id'               => 'footer-settings',
        'customizer_width' => '450px',
        'icon'             => 'far fa-file-alt',
        'fields'           => []
    ] );


    Redux::set_section( $opt_name, [
        'title'            => esc_html__( 'Footer Settings', 'arcane' ),
        'id'               => 'footer-general-settings',
        'subsection'       => true,
        'fields'           => [
            [
                'id' => 'copyright',
                'type' => 'textarea',
                'title' => esc_html__('Copyright', 'arcane'),
                'subtitle' => esc_html__('You can use HTML code in here.', 'arcane'),
                'default' => 'Copyright 2020. Made with <i class="far fa-heart"></i>  <a href="http://skywarriorthemes.com/" target="_blank">Skywarrior Studios</a>'
            ],
	        [
		        'id' => 'copyright-color',
		        'type' => 'color',
		        'title' => esc_html__('Copyright Background Color', 'arcane'),
		        'subtitle' => esc_html__('Add copyright background color color', 'arcane'),
		        'default' => '#0d0f13'
	        ],
	        [
		        'id' => 'footer-background',
		        'type' => 'media',
		        'title' => esc_html__('Background', 'arcane'),
		        'subtitle' => esc_html__('Add background image', 'arcane'),
                'default'  => array(
                    'url'=> get_theme_file_uri('img/defaults/footerbg.jpg')
                )
	        ],
	        [
		        'id' => 'footer-background-repeat',
		        'type' => 'select',
		        'title' => esc_html__('Background Repeat', 'arcane'),
		        'subtitle' => esc_html__('Add background repeat', 'arcane'),
		        'options' => [
			        'repeat' => esc_html__('Repeat', 'arcane'),
			        'no-repeat' => esc_html__('No repeat', 'arcane'),
		        ],
		        'default' => 'no-repeat'
	        ],
	        [
		        'id' => 'footer-background-size',
		        'type' => 'select',
		        'title' => esc_html__('Background Size', 'arcane'),
		        'subtitle' => esc_html__('Add background size', 'arcane'),
		        'options' => [
		        	'contain' => esc_html__('Contain', 'arcane'),
			        'cover' => esc_html__('Cover', 'arcane'),
			        'auto' => esc_html__('Auto', 'arcane'),
		        ],
		        'default' => 'cover'
	        ],
	        [
		        'id' => 'footer-color',
		        'type' => 'color',
		        'title' => esc_html__('Color', 'arcane'),
		        'subtitle' => esc_html__('Add footer color', 'arcane'),
	        ],

	        [
		        'id' => 'footer_columns',
		        'type' => 'image_select',
		        'title' => esc_html__('Footer Columns', 'arcane'),
		        'subtitle' => esc_html__('Please select the number of columns you would like for your footer.', 'arcane'),
		        'options' => [
			        '1' => ['title' => esc_html__('1 Column', 'arcane'), 'img' => get_theme_file_uri('img/1col.png')],
			        '2' => ['title' => esc_html__('2 Columns', 'arcane'), 'img' => get_theme_file_uri('img/2col.png')],
			        '3' => ['title' => esc_html__('3 Columns', 'arcane'), 'img' => get_theme_file_uri('img/3col.png')],
			        '4' => ['title' => esc_html__('4 Columns', 'arcane'), 'img' => get_theme_file_uri('img/4col.png')]
		        ],
		        'default' => '4'
	        ],
         ]
    ] );


    #************************************************
    # Social
    #************************************************

    Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'Social Media', 'arcane' ),
        'id'               => 'social_media',
        'customizer_width' => '400px',
        'icon'             => 'el el-share',
        'fields' => array(

            array(
                'id' => 'facebook-url',
                'type' => 'text',
                'title' => esc_html__('Facebook URL', 'arcane'),
                'subtitle' => esc_html__('Please enter your Facebook URL.', 'arcane'),
                'desc' => ''
            ),
            array(
                'id' => 'twitter-url',
                'type' => 'text',
                'title' => esc_html__('Twitter URL', 'arcane'),
                'subtitle' => esc_html__('Please enter your Twitter URL.', 'arcane'),
                'desc' => ''
            ),
            array(
                'id' => 'vimeo-url',
                'type' => 'text',
                'title' => esc_html__('Vimeo URL', 'arcane'),
                'subtitle' => esc_html__('Please enter your Vimeo URL.', 'arcane'),
                'desc' => ''
            ),
            array(
                'id' => 'dribbble-url',
                'type' => 'text',
                'title' => esc_html__('Dribbble URL', 'arcane'),
                'subtitle' => esc_html__('Please enter your Dribbble URL.', 'arcane'),
                'desc' => ''
            ),
            array(
                'id' => 'pinterest-url',
                'type' => 'text',
                'title' => esc_html__('Pinterest URL', 'arcane'),
                'subtitle' => esc_html__('Please enter your Pinterest URL.', 'arcane'),
                'desc' => ''
            ),
            array(
                'id' => 'youtube-url',
                'type' => 'text',
                'title' => esc_html__('Youtube URL', 'arcane'),
                'subtitle' => esc_html__('Please enter your Youtube URL.', 'arcane'),
                'desc' => ''
            ),

            array(
                'id' => 'rss-url',
                'type' => 'text',
                'title' => esc_html__('RSS URL', 'arcane'),
                'subtitle' => esc_html__('If you have an external RSS feed such as Feedburner, please enter it here. Will use built in Wordpress feed if left blank.', 'arcane'),
                'desc' => ''
            ),

            array(
                'id' => 'steam-url',
                'type' => 'text',
                'title' => esc_html__('Steam URL', 'arcane'),
                'subtitle' => esc_html__('Please enter your Steam URL.', 'arcane'),
                'desc' => ''
            ),
            array(
                'id' => 'instagram-url',
                'type' => 'text',
                'title' => esc_html__('Instagram URL', 'arcane'),
                'subtitle' => esc_html__('Please enter your Instagram URL.', 'arcane'),
                'desc' => ''
            ),
            array(
                'id' => 'twitch-url',
                'type' => 'text',
                'title' => esc_html__('Twitch URL', 'arcane'),
                'subtitle' => esc_html__('Please enter your Twitch URL.', 'arcane'),
                'desc' => ''
            ),
            array(
                'id' => 'discord-url',
                'type' => 'text',
                'title' => esc_html__('Discord URL', 'arcane'),
                'subtitle' => esc_html__('Please enter your Discord URL.', 'arcane'),
                'desc' => ''
            ),
            array(
                'id' => 'email-url',
                'type' => 'text',
                'title' => esc_html__('Email address', 'arcane'),
                'subtitle' => esc_html__('Please enter your email address.', 'arcane'),
                'desc' => ''
            )
        )
    ) );




#************************************************
# Team wars
#************************************************
Redux::set_section( $opt_name, array(
    'title'            => esc_html__( 'Team Wars', 'arcane' ),
    'id'               => 'team-wars',
    'customizer_width' => '450px',
    'icon'             => 'fas fa-shield-alt',
    'fields'           => array(

    )
) );

Redux::set_section( $opt_name, array(
    'title'            => esc_html__( 'Team Wars Settings', 'arcane' ),
    'id'               => 'team-wars-settings',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id' => 'team_creation',
            'type' => 'switch',
            'title' => esc_html__('Team Creation', 'arcane'),
            'subtitle' => esc_html__('Enable team creation.', 'arcane'),
            'desc' => '',
            'default' => true
        ),
        array(
            'id' => 'team_creation_number',
            'type' => 'switch',
            'title' => esc_html__('Number Of Teams', 'arcane'),
            'subtitle' => esc_html__('Enable more than one team per user.', 'arcane'),
            'desc' => '',
            'default' => true
        ),
        array(
            'id' => 'disable_team_challenges',
            'type' => 'switch',
            'title' => esc_html__('Disable Challenges', 'arcane'),
            'subtitle' => esc_html__('Disable team challenges.', 'arcane'),
            'desc' => '',
            'default' => false
        ),
        array(
            'id' => 'match_header_bg',
            'type' => 'media',
            'title' => esc_html__('Background', 'arcane'),
            'subtitle' => esc_html__('Default background for the match page.', 'arcane'),
            'default' => array(
                'url'=> get_theme_file_uri('img/defaults/matchbg.jpg')
            ),
        ),
    )
) );

require_once ( ABSPATH . 'wp-admin/includes/plugin.php');

if(!function_exists('arcane_admin_tournament_mapping')){
	function arcane_admin_tournament_mapping(){
		$arcane_tournament_mapping =
			[
				'knockout' => esc_html__('Knockout', 'arcane'),

			];

		if ( class_exists( 'Arcane_Tournament_Types' ) ) {
			$tournament_types = [
				'ladder' => esc_html__( 'Ladder', 'arcane' ),
				'rrobin' => esc_html__( 'Round Robin', 'arcane' ),
				'league' => esc_html__( 'League', 'arcane' )
			];

			$arcane_tournament_mapping = array_merge($arcane_tournament_mapping, $tournament_types);
		}

		if ( class_exists('Arcane_Battle_Royale')){
			$br = [
				'royale' => esc_html__( 'Battle Royale', 'arcane' ),
			];

			$arcane_tournament_mapping = array_merge($arcane_tournament_mapping, $br);
		}

		return $arcane_tournament_mapping;
	}
	add_action('admin_init', 'arcane_admin_tournament_mapping', 1);
}

Redux::set_section( $opt_name, array(
    'title'            => esc_html__( 'Tournaments', 'arcane' ),
    'id'               => 'tournaments-settings',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id' => 'tournament_creation',
            'type' => 'switch',
            'title' => esc_html__('Allow All Users To Create Tournaments', 'arcane'),
            'subtitle' => esc_html__('Enable tournaments creation.', 'arcane'),
            'desc' => '',
            'default' => true
        ),

        array(
            'id' => 'tournament_approve_user',
            'type' => 'switch',
            'title' => esc_html__('Approve Users', 'arcane'),
            'subtitle' => esc_html__('Approve users when they join tournament.', 'arcane'),
            'desc' => '',
            'default' => true
        ),
        array(
            'id' => 'admin_edit',
            'type' => 'switch',
            'title' => esc_html__('Allow Admin To Edit Tournaments', 'arcane'),
            'subtitle' => esc_html__('Please note that admin won\'t be able to join tournaments if this option is on!', 'arcane'),
            'desc' => '',
            'default' => true
        ),
        array(
            'id'       => 'choosen_types',
            'type'     => 'checkbox',
            'title'    => esc_html__('Tournament Types', 'arcane'),
            'subtitle' => esc_html__('Select which tournament types you want to include.', 'arcane'),
            'options'  => arcane_admin_tournament_mapping(),
	        'default'  => 'knockout'
        ),
        array(
            'id' => 'premium_button_text',
            'type' => 'text',
            'title' => esc_html__('Join Button For Premium Tournaments', 'arcane'),
            'subtitle' => esc_html__('Add text for join button in premium tournaments.', 'arcane'),
            'default' => esc_html__("Buy premium", 'arcane')
        ),
        array(
            'id' => 'premium_pending_button_text',
            'type' => 'text',
            'title' => esc_html__('Premium Pending Button', 'arcane'),
            'subtitle' => esc_html__('Add text for premium pending button in premium tournaments.', 'arcane'),
            'default' => esc_html__("Premium pending", 'arcane')
        ),
        array(
            'id' => 'game_modes',
            'type' => 'multi_text',
            'title' => esc_html__('Game Modes', 'arcane'),
            'subtitle' => esc_html__('Add game modes you want to display in tournament.', 'arcane'),
            'add_text' => esc_html__('Add More', 'arcane'),
        ),


    )
) );

Redux::set_section( $opt_name, array(
    'title'            => esc_html__( 'Frequency ', 'arcane' ),
    'id'               => 'tournaments-frequency ',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id' => 'cron1',
            'type' => 'text',
            'title' => esc_html__('Tournament Frequency', 'arcane'),
            'subtitle' => esc_html__('Use this command to set your server cron. Set cron timing to 1 day.', 'arcane'),
            'default' => "wget -qO ".get_home_url('/').'?cron_job=1'
        ),

    )
) );


Redux::set_section( $opt_name, array(
    'title'            => esc_html__( 'Ladder settings', 'arcane' ),
    'id'               => 'ladder-settings ',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id' => 'ladder_max_up',
            'type' => 'text',
            'title' => esc_html__('Ladder Challenges', 'arcane'),
            'subtitle' => esc_html__('How many places above you can you challenge in a ladder tournament.', 'arcane'),
            'default' => '2',
            'validate' => 'numeric'
        ),
        array(
            'id' => 'ladder_challenge_charges',
            'type' => 'text',
            'title' => esc_html__('Ladder Challenge Charges', 'arcane'),
            'subtitle' => esc_html__('How many times per 24hours can you challenge.', 'arcane'),
            'default' => '2',
            'validate' => 'numeric'
        ),
        array(
            'id' => 'ladder_challenge_decline_charges',
            'type' => 'text',
            'title' => esc_html__('Ladder Challenge Decline Charges', 'arcane'),
            'subtitle' => esc_html__('How many times per day can you decline a challenge.', 'arcane'),
            'default' => '2',
            'validate' => 'numeric'
        ),


    )
) );



#************************************************
    # WooCommerce
    #************************************************

    global $woocommerce;
    if ($woocommerce) {

         Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'WooCommerce', 'arcane' ),
        'id'               => 'woocommerce',
        'desc'             => esc_html__( 'All WooCommerce related options are listed here', 'arcane' ),
        'customizer_width' => '400px',
        'icon'             => 'fas fa-shopping-cart',
        'fields' => array()
        )
        );


         Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'Woo General Settings', 'arcane' ),
        'id'               => 'woo-functionality',
        'subsection'       => true,
        'fields'           => array(
                    array(
                        'id' => 'cart_every_page',
                        'type' => 'switch',
                        'title' => esc_html__('Show Cart On Every Page', 'arcane'),
                        'subtitle' => esc_html__('Show cart in the header on every page', 'arcane'),
                        'default' => '0',
                    ),
        )
    ) );

         Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'Layout', 'arcane' ),
        'id'               => 'woo-layout',
        'subsection'       => true,
        'fields'           => array(

                  array(
                        'id' => 'mainshop',
                        'type' => 'image_select',
                        'title' => esc_html__('Main Shop Page Layout', 'arcane'),
                        'sub_desc' => esc_html__('Choose page layout that you want to use on main WooCommerce page.', 'arcane'),
                        'options' => array(
                                        'no-sidebar' => array('title' => esc_html__('No Sidebar', 'arcane'), 'img' => get_theme_file_uri('img/redux/full.png')),
                                        'right-sidebar' => array('title' => esc_html__('Right Sidebar', 'arcane'), 'img' => get_theme_file_uri('img/redux/right.png')),
                                        'left-sidebar' => array('title' => esc_html__('Left Sidebar','arcane' ), 'img' => get_theme_file_uri('img/redux/left.png'))
                                    ),
                        'default' => 'right-sidebar'
                    ),
                    array(
                        'id' => 'singleprod',
                        'type' => 'image_select',
                        'title' => esc_html__('Single Product Page Layout', 'arcane'),
                        'sub_desc' => esc_html__('Choose page layout that you want to use on single product WooCommerce page.', 'arcane'),
                        'options' => array(
                                        'no-sidebar' => array('title' => esc_html__('No Sidebar', 'arcane'), 'img' => get_theme_file_uri('img/redux/full.png')),
                                        'right-sidebar' => array('title' => esc_html__('Right Sidebar', 'arcane'), 'img' => get_theme_file_uri('img/redux/right.png')),
                                        'left-sidebar' => array('title' => esc_html__('Left Sidebar','arcane' ), 'img' => get_theme_file_uri('img/redux/left.png'))
                                    ),
                        'default' => 'right-sidebar'
                    ),

            )
    ) );

}
	if(!function_exists( 'arcane_register_pay_to_join_product_type' ) && !class_exists( 'Arcane_Tournament_Types' )) {
		#************************************************
		# Premium
		#************************************************

		Redux::set_section( $opt_name, array(
				'title'            => esc_html__( 'Premium', 'arcane' ),
				'id'               => 'premium-settings',
				'customizer_width' => '400px',
				'icon'             => 'fas fa-star',
				'fields'           => array()
			)
		);


		Redux::set_section( $opt_name, array(
			'title'      => esc_html__( 'Go Premium', 'arcane' ),
			'id'         => 'go-premium',
			'subsection' => true,
			'fields'     => array(
				array(
					'id'    => 'premium_tournaments',
					'type'  => 'info',
					'style' => 'info_normal',
					'desc'  => '<a class="premium_link" target="_blank" href="https://www.skywarriorthemes.com/product/tournaments-premium/"><img alt="img" src="' . get_theme_file_uri( 'img/premium-button.jpg' ) . '" /></a>'
				),

				array(
					'id'    => 'premium_paytj',
					'type'  => 'info',
					'style' => 'info_normal',
					'desc'  => '<a class="premium_link" target="_blank" href="https://www.skywarriorthemes.com/product/pay-to-join-tournaments-wp-plugin/"><img alt="img" src="' . get_theme_file_uri() . '/img/pay-to-join.jpg" /></a>'
				),

                array(
                    'id'    => 'premium_paytj',
                    'type'  => 'info',
                    'style' => 'info_normal',
                    'desc'  => '<a class="premium_link" target="_blank" href="https://www.skywarriorthemes.com/product/battle-royale-tournaments-wp-plugin/"><img alt="img" src="' . get_theme_file_uri() . '/img/battleroyale.jpg" /></a>'
                )
			)
		) );
	}




    if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
        $section = array(
            'icon'   => 'el el-list-alt',
            'title'  => esc_html__( 'Documentation', 'arcane' ),
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
        Redux::set_section( $opt_name, $section );
    }
    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /*
    *
    * --> Action hook examples
    *
    */

    // If Redux is running as a plugin, this will remove the demo notice and links
    //add_action( 'redux/loaded', 'remove_demo' );

    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

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
                $return['error'] = $field;
                $field['msg']    = 'your custom error message';
            }

            if ( $warning == true ) {
                $return['warning'] = $field;
                $field['msg']      = 'your custom warning message';
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
                'title'  => esc_html__( 'Section via hook', 'arcane' ),
                'desc'   => esc_html__( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'arcane' ),
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