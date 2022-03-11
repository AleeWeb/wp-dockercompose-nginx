<?php

/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

 /**
 * Only return default value if we don't have a post ID (in the 'post' query variable)
 *
 * @param  bool  $default On/Off (true/false)
 * @return mixed          Returns true or '', the blank default
 */
function zocker_set_checkbox_default_for_new_post( $default ) {
	return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}

add_action( 'cmb2_admin_init', 'zocker_register_metabox' );

/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */

function zocker_register_metabox() {

	$prefix = '_zocker_';

	$prefixpage = '_zockerpage_';
	
	$zocker_team_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'team_page_control',
		'title'         => esc_html__( 'Team Page Controller', 'zocker' ),
		'object_types'  => array( 'zocker_team' ), // Post type
		'closed'        => true
	) );
	
	$zocker_team_meta->add_field( array(
		'name' => esc_html__( 'Team Player Number', 'zocker' ),
	   	'desc' => esc_html__( 'Write Team Player Number', 'zocker' ),
	   	'id'   => $prefix . 'zocker_team_player',
		'type' => 'text',
    ) );
	
	$zocker_player_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'player_page_control',
		'title'         => esc_html__( 'Player Page Controller', 'zocker' ),
		'object_types'  => array( 'zocker_player' ), // Post type
		'closed'        => true
	) );
	
	$zocker_player_meta->add_field( array(
		'name' => esc_html__( 'Player Designation', 'zocker' ),
	   	'desc' => esc_html__( 'Write Player Designation', 'zocker' ),
	   	'id'   => $prefix . 'zocker_player_designation',
		'type' => 'text',
    ) );
	
	$player_group_field_id = $zocker_player_meta->add_field( array(
		'id'          => $prefix .'player_social_group',
		'type'        => 'group',
		'description' => __( 'Social Profile', 'zocker' ),
		'options'     => array(
			'group_title'       => __( 'Social Profile {#}', 'zocker' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'        => __( 'Add Another Social Profile', 'zocker' ),
			'remove_button'     => __( 'Remove Social Profile', 'zocker' ),
			'closed'         => true
		),
	) );

	$zocker_player_meta->add_group_field( $player_group_field_id, array(
		'name'        => __( 'Select Icon', 'zocker' ),
		'id'          => $prefix .'player_social_profile_icon',
		'type'        => 'fontawesome_icon', // This field type
	) );

	$zocker_player_meta->add_group_field( $player_group_field_id, array(
		'desc'       => esc_html__( 'Set social profile link.', 'zocker' ),
		'id'         => $prefix . 'player_social_profile_link',
		'name'       => esc_html__( 'Social Profile link', 'zocker' ),
		'type'       => 'text'
	) );
	
	$zocker_post_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'blog_post_control',
		'title'         => esc_html__( 'Post Thumb Controller', 'zocker' ),
		'object_types'  => array( 'post' ), // Post type
		'closed'        => true
	) );
	$zocker_post_meta->add_field( array(
		'name' => esc_html__( 'Post Format Video', 'zocker' ),
		'desc' => esc_html__( 'Use This Field When Post Format Video', 'zocker' ),
		'id'   => $prefix . 'post_format_video',
        'type' => 'text_url',
    ) );
	$zocker_post_meta->add_field( array(
		'name' => esc_html__( 'Post Format Audio', 'zocker' ),
		'desc' => esc_html__( 'Use This Field When Post Format Audio', 'zocker' ),
		'id'   => $prefix . 'post_format_audio',
        'type' => 'oembed',
    ) );
	$zocker_post_meta->add_field( array(
		'name' => esc_html__( 'Post Thumbnail For Slider', 'zocker' ),
		'desc' => esc_html__( 'Use This Field When You Want A Slider In Post Thumbnail', 'zocker' ),
		'id'   => $prefix . 'post_format_slider',
        'type' => 'file_list',
    ) );
	
	$zocker_page_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'page_meta_section',
		'title'         => esc_html__( 'Page Meta', 'zocker' ),
		'object_types'  => array( 'page' ), // Post type
        'closed'        => true
    ) );

    $zocker_page_meta->add_field( array(
		'name' => esc_html__( 'Page Breadcrumb Area', 'zocker' ),
		'desc' => esc_html__( 'check to display page breadcrumb area.', 'zocker' ),
		'id'   => $prefix . 'page_breadcrumb_area',
        'type' => 'select',
        'default' => '1',
        'options'   => array(
            '1'   => esc_html__('Show','zocker'),
            '2'     => esc_html__('Hide','zocker'),
        )
    ) );


    $zocker_page_meta->add_field( array(
		'name' => esc_html__( 'Page Breadcrumb Settings', 'zocker' ),
		'id'   => $prefix . 'page_breadcrumb_settings',
        'type' => 'select',
        'default'   => 'global',
        'options'   => array(
            'global'   => esc_html__('Global Settings','zocker'),
            'page'     => esc_html__('Page Settings','zocker'),
        )
	) );

    $zocker_page_meta->add_field( array(
		'name' => esc_html__( 'Page Title', 'zocker' ),
		'desc' => esc_html__( 'check to display Page Title.', 'zocker' ),
		'id'   => $prefix . 'page_title',
        'type' => 'select',
        'default' => '1',
        'options'   => array(
            '1'   => esc_html__('Show','zocker'),
            '2'     => esc_html__('Hide','zocker'),
        )
	) );

    $zocker_page_meta->add_field( array(
		'name' => esc_html__( 'Page Title Settings', 'zocker' ),
		'id'   => $prefix . 'page_title_settings',
        'type' => 'select',
        'options'   => array(
            'default'  => esc_html__('Default Title','zocker'),
            'custom'  => esc_html__('Custom Title','zocker'),
        ),
        'default'   => 'default'
    ) );

    $zocker_page_meta->add_field( array(
		'name' => esc_html__( 'Custom Page Title', 'zocker' ),
		'id'   => $prefix . 'custom_page_title',
        'type' => 'text'
    ) );

    $zocker_page_meta->add_field( array(
		'name' => esc_html__( 'Breadcrumb', 'zocker' ),
		'desc' => esc_html__( 'Select Show to display breadcrumb area', 'zocker' ),
		'id'   => $prefix . 'page_breadcrumb_trigger',
        'type' => 'switch_btn',
        'default' => zocker_set_checkbox_default_for_new_post( true ),
    ) );

    $zocker_layout_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'page_layout_section',
		'title'         => esc_html__( 'Page Layout', 'zocker' ),
        'context' 		=> 'side',
        'priority' 		=> 'high',
        'object_types'  => array( 'page' ), // Post type
        'closed'        => true
	) );

	$zocker_layout_meta->add_field( array(
		'desc'       => esc_html__( 'Set page layout container,container fluid,fullwidth or both. It\'s work only in template builder page.', 'zocker' ),
		'id'         => $prefix . 'custom_page_layout',
		'type'       => 'radio',
        'options' => array(
            '1' => esc_html__( 'Container', 'zocker' ),
            '2' => esc_html__( 'Container Fluid', 'zocker' ),
            '3' => esc_html__( 'Fullwidth', 'zocker' ),
        ),
	) );

}

add_action( 'cmb2_admin_init', 'zocker_register_taxonomy_metabox' );
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function zocker_register_taxonomy_metabox() {

    $prefix = '_zocker_';
	/**
	 * Metabox to add fields to categories and tags
	 */
	$zocker_term_meta = new_cmb2_box( array(
		'id'               => $prefix.'term_edit',
		'title'            => esc_html__( 'Category Metabox', 'zocker' ),
		'object_types'     => array( 'term' ),
		'taxonomies'       => array( 'category'),
	) );
	$zocker_term_meta->add_field( array(
		'name'     => esc_html__( 'Extra Info', 'zocker' ),
		'id'       => $prefix.'term_extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );
	$zocker_term_meta->add_field( array(
		'name' => esc_html__( 'Category Image', 'zocker' ),
		'desc' => esc_html__( 'Set Category Image', 'zocker' ),
		'id'   => $prefix.'term_avatar',
        'type' => 'file',
        'text'    => array(
			'add_upload_file_text' => esc_html__('Add Image','zocker') // Change upload button text. Default: "Add or Upload File"
		),
	) );


	/**
	 * Metabox for the user profile screen
	 */
	$zocker_user = new_cmb2_box( array(
		'id'               => $prefix.'user_edit',
		'title'            => esc_html__( 'User Profile Metabox', 'zocker' ), // Doesn't output for user boxes
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );
	$zocker_user->add_field( array(
		'name'     => esc_html__( 'Social Profile', 'zocker' ),
		'id'       => $prefix.'user_extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );

	$group_field_id = $zocker_user->add_field( array(
        'id'          => $prefix .'social_profile_group',
        'type'        => 'group',
        'description' => __( 'Social Profile', 'zocker' ),
        'options'     => array(
            'group_title'       => __( 'Social Profile {#}', 'zocker' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => __( 'Add Another Social Profile', 'zocker' ),
            'remove_button'     => __( 'Remove Social Profile', 'zocker' ),
            'closed'         => true
        ),
    ) );

    $zocker_user->add_group_field( $group_field_id, array(
        'name'        => __( 'Select Icon', 'zocker' ),
        'id'          => $prefix .'social_profile_icon',
        'type'        => 'fontawesome_icon', // This field type
    ) );

    $zocker_user->add_group_field( $group_field_id, array(
        'desc'       => esc_html__( 'Set social profile link.', 'zocker' ),
        'id'         => $prefix . 'lawyer_social_profile_link',
        'name'       => esc_html__( 'Social Profile link', 'zocker' ),
        'type'       => 'text'
    ) );
}