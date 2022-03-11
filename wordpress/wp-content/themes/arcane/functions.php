<?php
//translatable theme
load_theme_textdomain( 'arcane', get_parent_theme_file_path('langs'));

if ( class_exists('ReduxFrameworkPlugin')) {
    require_once get_theme_file_path('options-config.php');
}

if(class_exists('Arcane_Types')){
    require_once (WP_PLUGIN_DIR.'/arcane_custom_post_types/smartmetabox/SmartMetaBox.php');
    require_once (WP_PLUGIN_DIR.'/arcane_custom_post_types/wp-owl-carousel/wp_owl_carousel.php');
}

require_once get_theme_file_path('elementor/init.php');
require_once get_theme_file_path('ajax.php');

/* Custom code goes below this line. */


global $arcane_version;
$arcane_theme = wp_get_theme();
$arcane_version = $arcane_theme->get( 'Version' );

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

if(!function_exists('arcane_tournament_mapping')){
function arcane_tournament_mapping( $new_var='' ) {
    static $var;
    if ( !empty( $new_var ) ) {
        $var = $new_var;
    }
    return $var;
}
arcane_tournament_mapping( $arcane_tournament_mapping );
}

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


add_action( 'after_setup_theme', 'arcane_theme_setup' );
if(!function_exists('arcane_theme_setup')){
function arcane_theme_setup() {
    /* Add filters, actions, and theme-supported features. */

    /*****ACTIONS*****/

    /*menu*/
    add_action( 'init', 'arcane_register_my_menus' );
    add_action( 'admin_menu', 'arcane_remove_menus' );


    /*styles*/
    add_action( 'wp_enqueue_scripts', 'arcane_style' );
    add_action( 'wp_enqueue_scripts', 'arcane_fonts' );
    add_action( 'wp_enqueue_scripts', 'arcane_external_styles' );
    add_action( 'admin_enqueue_scripts', 'arcane_styles_admin', 1 );


    /*scripts*/
    add_action( 'wp_enqueue_scripts', 'arcane_my_scripts' );
    add_action( 'admin_enqueue_scripts', 'arcane_scripts_admin' );

    /*plugin activation*/
    add_action( 'arcane_tgmpa_register', 'arcane_register_required_plugins' );

    /*custom post type*/
    add_action( 'before_delete_post', 'arcane_onteam_delete');

    /*metaboxes*/
    add_action( 'save_post', 'arcane_change_team_admin' );


    /*user registration countries*/
    add_action( 'arcane_registration_clist', 'arcane_registration_country_list' );

    /*buddypress*/
    add_action( 'wp', 'arcane_remove_profile_subnav', 2 );
    add_action( 'bp_setup_nav', 'arcane_mb_profile_menu_tabs', 201 );
    remove_action( 'bp_init', 'bp_core_wpsignup_redirect');

    add_action('xprofile_avatar_uploaded', 'arcane_update_avatar_admin');
    remove_action( 'admin_notices', 'bp_core_print_admin_notices');
    add_action('after_switch_theme', 'arcane_xprofile_channel_fields');

    /*ajax calls*/



    add_action( 'wp_ajax_nopriv_arcane_team_members_ajax', 'arcane_team_members_ajax' );
    add_action( 'wp_ajax_arcane_team_members_ajax', 'arcane_team_members_ajax' );



    add_action( 'wp_ajax_nopriv_arcane_team_delete', 'arcane_team_delete' );
    add_action( 'wp_ajax_arcane_team_delete', 'arcane_team_delete' );
    add_action( 'wp_ajax_nopriv_arcane_match_delete_single', 'arcane_match_delete_single' );
    add_action( 'wp_ajax_arcane_match_delete_single', 'arcane_match_delete_single' );
    add_action( 'wp_ajax_nopriv_arcane_match_delete', 'arcane_match_delete' );
    add_action( 'wp_ajax_arcane_match_delete', 'arcane_match_delete' );
    add_action( 'wp_ajax_nopriv_arcane_match_delete_confirmation', 'arcane_match_delete_confirmation' );
    add_action( 'wp_ajax_arcane_match_delete_confirmation', 'arcane_match_delete_confirmation' );
    add_action( 'wp_ajax_nopriv_arcane_mutual_games', 'arcane_mutual_games' );
    add_action( 'wp_ajax_arcane_mutual_games', 'arcane_mutual_games' );
    add_action( 'wp_ajax_nopriv_arcane_all_teams_pagination_v2_ajax', 'arcane_all_teams_pagination_v2_ajax' );
    add_action( 'wp_ajax_arcane_all_teams_pagination_v2_ajax', 'arcane_all_teams_pagination_v2_ajax' );
    add_action( 'wp_ajax_nopriv_arcane_all_tournaments_pagination_v2_ajax', 'arcane_all_tournaments_pagination_v2_ajax' );
    add_action( 'wp_ajax_arcane_all_tournaments_pagination_v2_ajax', 'arcane_all_tournaments_pagination_v2_ajax' );
    add_action( 'wp_ajax_nopriv_arcane_list_all_teams_for_selected_game_ajax', 'arcane_list_all_teams_for_selected_game_ajax' );
    add_action( 'wp_ajax_arcane_list_all_teams_for_selected_game_ajax', 'arcane_list_all_teams_for_selected_game_ajax' );



    add_action( 'wp_ajax_nopriv_arcane_resend_activation_link', 'arcane_resend_activation_link' );
    add_action( 'wp_ajax_arcane_resend_activation_link', 'arcane_resend_activation_link' );
    add_action( 'wp_ajax_nopriv_arcane_add_to_premium', 'arcane_add_to_premium' );
    add_action( 'wp_ajax_arcane_add_to_premium', 'arcane_add_to_premium' );
    add_action( 'wp_ajax_nopriv_arcane_remove_premium', 'arcane_remove_premium' );
    add_action( 'wp_ajax_arcane_remove_premium', 'arcane_remove_premium' );

    /*media library*/
    add_action( 'pre_get_posts','arcane_restrict_media_library' );

    /*post templates*/
    add_action( 'template_redirect', 'arcane_tournament_creation_template_redirect' );

    /*user profiles*/
    add_action( 'personal_options_update', 'arcane_save_team_extra_user_profile_fields' );
    add_action( 'edit_user_profile_update', 'arcane_save_team_extra_user_profile_fields' );
    add_action( 'show_user_profile', 'arcane_team_extra_user_profile_fields' );
    add_action( 'edit_user_profile', 'arcane_team_extra_user_profile_fields' );

    /*remove users from team*/
    add_action( 'delete_user', 'arcane_remove_user_from_team_on_delete' );

    /*add nickname column*/
    add_filter( 'manage_users_custom_column', 'arcane_new_modify_user_table_row', 10, 3 );
    add_filter( 'manage_users_columns', 'arcane_new_modify_user_table' );

    /*roles*/
    add_action( 'admin_init', 'arcane_add_theme_caps');

    /*check if user is active*/
    add_action( 'wp_login', 'arcane_user_active_check', '', 2 );


    /*premiums*/
    if (!class_exists( 'Arcane_Tournament_Types' )){
        add_action( 'admin_notices', 'arcane_premium_notice_tournaments' );
    }

    if (!function_exists( 'arcane_register_pay_to_join_product_type' )){
        add_action( 'admin_notices', 'arcane_premium_notice_pay_to_join' );
        add_action( 'init', 'arcane_check_expired_membership' );
    }

    if (!class_exists( 'Arcane_Battle_Royale' )){
        add_action( 'admin_notices', 'arcane_premium_notice_br' );
    }
 add_action( 'admin_notices', 'arcane_premium_notice_br' );
    /*bar*/
    add_action( 'wp_before_admin_bar_render', 'arcane_bar_render' );


    /*tournaments*/
    add_action( 'manage_tournament_posts_custom_column', 'arcane_manage_tournament_columns', 10, 2 );

    /*template redirects*/
    add_action( 'template_redirect', 'arcane_trigger_check' );

    /*lost password redirection*/
    add_action( 'login_form_lostpassword', 'arcane_lost_pass_redirection' );
    add_action( 'login_form_retrievepassword', 'arcane_lost_pass_redirection' );



    /*categories*/
    add_action( 'category_edit_form_fields','arcane_extra_category_fields');
    add_action( 'category_add_form_fields', 'arcane_category_form_custom_field_add', 10 );

    /*importer*/
    add_action( 'wbc_importer_after_content_import', 'arcane_import_additional_resources', 10, 2 );


    /*****FILTERS*****/
    /*sidebars*/
    add_filter('dynamic_sidebar_params','arcane_widget_first_last_classes');

    /*excerpt*/
    add_filter( 'excerpt_length', 'arcane_excerpt_length', 999 );
    add_filter( 'excerpt_length', 'arcane_excerpt_length_pro', 999 );
    add_filter( 'excerpt_more', 'arcane_excerpt_more' );


    /*woocommerce*/
    add_filter( 'woocommerce_add_to_cart_fragments', 'arcane_woocommerce_header_add_to_cart_fragment' );


    /*gravatar*/
    add_filter( 'get_avatar', 'arcane_be_gravatar_filter', 1, 6 );
    add_filter( 'bp_core_mysteryman_src', 'arcane_be_gravatar_filter_admin', 1, 5 );

    /*buddypress*/
    add_filter( 'bp_get_add_friend_button', 'arcane_add_friend_link_text' );
    add_filter( 'bp_get_activity_secondary_avatar', 'arcane_turn_secondary_avatars_to_links' );
    add_filter( 'bp_core_fetch_avatar', 'arcane_filter_bp_core_fetch_avatar', 10, 3 );
    add_filter( 'bp_before_members_cover_image_settings_parse_args', 'arcane_cover_image_css', 10, 1 );
    add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'arcane_cover_image_css', 10, 1 );

    /*tournaments*/
    add_filter( 'manage_edit-tournament_columns', 'arcane_set_custom_edit_tournament_columns' );

    /*role*/
    add_filter( 'pre_option_default_role', 'arcane_defaultrole' );

    /*comments*/
    add_filter( 'comments_open', 'arcane_comments_open', 10, 2 );
    add_filter( 'wp_insert_post_data', 'arcane_comments_on' );
    add_filter( 'wp_insert_post_data', 'arcane_matches_comments_on');
    add_filter( 'save_post', 'arcane_matches_edit_comments_on' );
    add_filter( 'user_has_cap', 'arcane_fix_comments', 10, 3 );

    /*register page*/
    add_filter( 'register_url', 'arcane_register_page' );

    /*query vars*/
    add_filter( 'query_vars','arcane_query_vars' );


    /*****THEME-SUPPORTED FEATURES*****/

    /*add custom menu support*/
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'yoast-seo-breadcrumbs' );


    $defaults = array(
    'default-repeat'         => 'no-repeat',
    'default-position-x'     => 'left',
    'default-position-y'     => 'top',
    );
    add_theme_support( 'custom-background', $defaults );

    $defaults = array(
    'width'                  => 0,
    'height'                 => 0,
    'flex-height'            => false,
    'flex-width'             => false,
    'uploads'                => false,
    'random-default'         => false,
    'header-text'            => false,
    );
    add_theme_support( 'custom-header', $defaults );

   if ( ! isset( $content_width ) ) {$content_width = 1328;}

}
}

/*register sidebars*/

/**
 * Register sidebars
 */
add_action( 'widgets_init', 'arcane_widgets_init' );
if(!function_exists('arcane_widgets_init')){
function arcane_widgets_init()
{
    if (function_exists('register_sidebar')) {

        register_sidebar(array('name' => 'Blog Sidebar', 'id' => 'blog', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>'));
        register_sidebar(array('name' => 'General sidebar', 'id' => 'general', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>'));
        register_sidebar(array('name' => 'Home sidebar', 'id' => 'homeside', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>'));
        register_sidebar(array('name' => 'TeamWars sidebar', 'id' => 'teamwars', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>'));

        if (class_exists('WooCommerce')) {
            register_sidebar(array('name' => 'WooCommerce Sidebar', 'id' => 'woo', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>'));
        }

        register_sidebar(array('name' => 'Footer Widget Area 1', 'id' => 'footer_widget_one', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>'));
        register_sidebar(array('name' => 'Footer Widget Area 2', 'id' => 'footer_widget_two', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>'));
        register_sidebar(array('name' => 'Footer Widget Area 3', 'id' => 'footer_widget_three', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>'));
        register_sidebar(array('name' => 'Footer Widget Area 4', 'id' => 'footer_widget_four', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>'));

    }
}
}

add_action( 'after_setup_theme', 'arcane_theme_tweak' );
if(!function_exists('arcane_theme_tweak')){
    function arcane_theme_tweak(){

    /*register theme location menu*/
    if(!function_exists('arcane_register_my_menus')){
        function arcane_register_my_menus() {
          register_nav_menus(
            array(
              'header-menu' => esc_html__( 'Header Menu' , 'arcane'),
              )
          );
        }
    }
    }
}


/*custom excerpt lenght*/
if(!function_exists('arcane_excerpt_length')){
function arcane_excerpt_length( $length ){
    return 55;
}
}

if(!function_exists('arcane_excerpt_length_pro')){
function arcane_excerpt_length_pro( $length ) {
    return 40;
}
}


/*pagination*/
if(!function_exists('arcane_kriesi_pagination')){
function arcane_kriesi_pagination($pages = '', $range = 1){
$showitems = ($range * 1)+1;
global $paged;
global $paginate;
$stranica = $paged;
if(empty($stranica)) {$stranica = 1;}
if($pages == '')
{
global $wp_query;
$pages = $wp_query->max_num_pages;
if(!$pages)
{
$pages = 1;
}
}
if(1 != $pages){

$leftpager= '&laquo;';
$rightpager= '&raquo;';
if($stranica > 2 && $stranica > $range+1 && $showitems < $pages) {$paginate.=  "";}
if($stranica > 1 ) {$paginate.=  "<a class='page-selector' href='".get_pagenum_link($stranica - 1)."'>". $leftpager. "</a>";}
for ($i=1; $i <= $pages; $i++){
if (1 != $pages &&( !($i >= $stranica+$range+1 || $i <= $stranica-$range-1) || $pages <= $showitems )){
$paginate.=  ($stranica == $i)? "<li class='active'><a href='".get_pagenum_link($i)."'>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a></li>";
}
}
if ($stranica < $pages ) {$paginate.=  "<li><a class='page-selector' href='".get_pagenum_link($stranica + 1)."' >". $rightpager. "</a></li>";}
}
return $paginate;
}
}

/*color converter*/
if(!function_exists('arcane_hex2rgb')){
function arcane_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}
}


/**
* Include the TGM_Plugin_Activation class.
 */
if(!function_exists('arcane_register_required_plugins')){
function arcane_register_required_plugins() {
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = [
        [
            'name'                  => esc_html__('BBpress', 'arcane'),
            'slug'                  => 'bbpress', 
            'required'              => false, 
        ],
        [
            'name'                  => esc_html__('BuddyPress', 'arcane'),
            'slug'                  => 'buddypress', 
            'required'              => true, 
        ],
        [
            'name'                  => esc_html__('WooCommerce', 'arcane'),
            'slug'                  => 'woocommerce', 
            'required'              => false, 
        ],
        [
            'name'                  => esc_html__('Arcane types', 'arcane'),
            'slug'                  => 'arcane_custom_post_types',
            'source'                => get_theme_file_uri('plugins/arcane_custom_post_types.zip'),
            'required'              => true,
            'version'               => '2.4',
        ],
        [
            'name'                  => esc_html__('Meta Box – WordPress Custom Fields Framework', 'arcane'),
            'slug'                  => 'meta-box', 
            'required'              => true, 
        ],
        [
            'name'                  => esc_html__('Contact form 7', 'arcane'),
            'slug'                  => 'contact-form-7', 
            'required'              => true, 
        ],
        [
            'name'                  => esc_html__('Redux framework', 'arcane'),
            'slug'                  => 'redux-framework', 
            'required'              => true, 
        ],
        [
            'name'                  => esc_html__('YotuWP - YouTube Gallery', 'arcane'),
            'slug'                  => 'yotuwp-easy-youtube-embed', 
            'required'              => false,
        ],
        [
            'name'                  => esc_html__('Arcane updates checker', 'arcane'),
            'slug'                  => 'plugin-update-checker', 
            'source'                => get_theme_file_uri('plugins/plugin-update-checker.zip'),
            'required'              => true, 
            'version'               => '4.4',
        ],
        [
            'name'                  => esc_html__('Elementor Website Builder', 'arcane'),
            'slug'                  => 'elementor', 
            'required'              => true, 
        ],
         [
            'name'                  => 'Layer Slider', // The plugin name
            'slug'                  => 'LayerSlider', // The plugin slug (typically the folder name)
            'source'                => get_theme_file_uri('plugins/LayerSlider.zip'), // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ],
    ];

    tgmpa( $plugins );
}
}


/*theme styles*/
if(!function_exists('arcane_style')){
function arcane_style() {
  global $post, $ArcaneWpTeamWars, $wp_query, $arcane_version;

  wp_enqueue_style('flatpickr', get_theme_file_uri('css/flatpickr.min.css'), [], $arcane_version);
  wp_enqueue_style('flatpickr-dark', get_theme_file_uri('css/flatpickr.dark.min.css'), [], $arcane_version);

  wp_enqueue_style( 'arcane_style',  get_bloginfo( 'stylesheet_url' ), [], $arcane_version );
  
   if ( is_rtl() )
    {
        wp_enqueue_style('arcane-rtl',  get_theme_file_uri('css/rtl.css') , [], $arcane_version);
    }


    wp_enqueue_style('simple-line-icons',  get_theme_file_uri('css/simple-line-icons.css') , [], $arcane_version);
    wp_enqueue_style('arcane-main',  get_theme_file_uri('css/scss/index.css') , [], $arcane_version);

    wp_enqueue_style( 'fontawesome',  'https://use.fontawesome.com/releases/v5.1.0/css/all.css',  [], $arcane_version);
    wp_enqueue_style('shadowbox',  get_theme_file_uri('css/shadowbox.css'), [], $arcane_version);
    wp_enqueue_style('easy-slider',  get_theme_file_uri('css/easy-slider.css'), [], $arcane_version);
    wp_enqueue_style('tooltip',  get_theme_file_uri('css/tooltip.css'), [], $arcane_version);

    $arcane_colors_css = '';
    $arcane_custom_css = '';

    require_once (get_theme_file_path('css/colours.php'));
    wp_add_inline_style( 'arcane_style', $arcane_colors_css );

    require_once (get_theme_file_path('css/custom-css.php'));
    wp_add_inline_style( 'arcane_style', $arcane_custom_css );

   // wp_enqueue_style('jquery-tipsy', get_theme_file_uri('addons/team-wars/js/tipsy/tipsy.css'), [], $arcane_version);


    /*header images*/
    if (get_the_post_thumbnail_url())
         {$custombck = get_the_post_thumbnail_url(get_the_ID(), 'full');}

    if(!empty($custombck) && is_page()){

        $data = "
            body.page, body.woocommerce, body.page-template-tmp-no-title-php .normal-page{
            background-image:url(".esc_url($custombck).") !important;
            background-position: center top !important;
            background-repeat:  no-repeat !important;
        }";
        wp_add_inline_style( 'arcane_style', $data );
    }


    if(!empty($custombck) && is_single()){

        $data = "
            header {
            background:url(".esc_url($custombck).") no-repeat center top !important;
            background-size: cover !important;
            }";
        wp_add_inline_style( 'arcane_style', $data );
    }

    add_editor_style();

    $c_id =get_current_user_id();

    $myteams = arcane_get_user_teams($c_id);
    if (is_array($myteams) and (!empty($myteams))) {

      foreach ($myteams as $team) {

        $post = get_post($team);

        $custombck = get_post_meta($team, 'team_bg',true);
        if(empty($custombck)){$custombck = get_theme_file_uri('img/defaults/default-banner.jpg');}

        $custombck = esc_url($custombck);
        $team = esc_attr($team);
        $data = "
        #TeamChooserModalFooter .tim_bck$team{
        background:url($custombck);
        }";

        wp_add_inline_style( 'arcane_style', $data );

      }
    }

    $categories = get_categories();

    foreach ($categories as $category) {
        $cat_data = get_option("category_$category->term_id");
        if(isset($cat_data['catBG'])){

            $cat_data_bg1 = $cat_data['catBG'];
            $cat_data_bg = str_replace("#","",$cat_data['catBG']);

            $data = "

            .cat_color_".esc_attr($cat_data_bg)."_text_shadow{
             text-shadow: 0px 0px 10px ".esc_attr($cat_data_bg1).";
            }

            .cat_color_".esc_attr($cat_data_bg)."_background{
             background: ".esc_attr($cat_data_bg1).";
            }

            .cat_color_".esc_attr($cat_data_bg)."_background_color{
             background-color: ".esc_attr($cat_data_bg1)." !important;
            }

            .cat_color_".esc_attr($cat_data_bg)."_color{
             color: ".esc_attr($cat_data_bg1).";
            }

            ";

            wp_add_inline_style( 'arcane_style', $data );

        }
    }
    if(isset($wp_query->post->ID)){
        $r = $ArcaneWpTeamWars->get_rounds($wp_query->post->ID);
    }else{
        $r = '';
    }
    if(isset($r) && !empty($r)){
        $rounds = [];

        // group rounds by map
       foreach($r as $v) {
        if(!isset($rounds[$v->group_n]))
        {$rounds[$v->group_n] = [];}
        $rounds[$v->group_n][] = $v;
       }

       foreach($rounds as $map_group) {

        $first = $map_group[0];
        $image = wp_get_attachment_image_src($first->screenshot, $size = 'full' );
        $name = strtolower(str_replace(' ', '', preg_replace("/[^a-zA-Z0-9]+/", "", $first->title)));
        if(!empty($image)){
            $img_rod = $image[0];
            $data = "
            .class_$name{
               background-image: url($img_rod) !important;
            }
            ";
            wp_add_inline_style( 'arcane_style', $data );

        }
       }
    }

    $el_games = $ArcaneWpTeamWars->get_games('');

    if(!empty($el_games)){

       foreach ( $el_games as $game ) {
        $image = arcane_return_game_image($game->id);
        if(!empty($image)){
            $data = "
            .game_$game->id{
               background-image: url($image) !important;
            }
            ";
            wp_add_inline_style( 'arcane_style', $data );

        }
       }    }

    if(isset($wp_query->post->ID) && get_post_type($wp_query->post->ID) == 'tournament'){

        $igra = get_post_meta($wp_query->post->ID,'tournament_game', true);
        $igra_id = arcane_return_game_id_by_game_name($igra);
        $image = arcane_return_game_banner_nocrop ((int)$igra_id);

        if (!empty($image)) {
            $output_image = $image;
        } else {
            $output_image = get_theme_file_uri('img/defaults/287x222.jpg');
        }

        $data = "
        .header-background-image{
           background: url($output_image) no-repeat center center;
        }
        ";
        wp_add_inline_style( 'arcane_style', $data );
    }

    wp_reset_postdata();


    if(isset($options['footer-background']['url'])){
      $background_image = $options['footer-background']['url'];

        $data = "
        footer{ background-image: url($background_image)!important;}
        ";
        wp_add_inline_style( 'arcane_style', $data );
    }

    if(isset($options['footer-background-repeat'])){
      $repeat = $options['footer-background-repeat'];

        $data = "footer{ background-repeat: $repeat !important;}";
        wp_add_inline_style( 'arcane_style', $data );
    }

    if(isset($options['footer-background-size'])){
      $size = $options['footer-background-size'];

        $data = "footer{ background-size: $size !important;}";
        wp_add_inline_style( 'arcane_style', $data );
    }

    if(isset($options['copyright-color'])){
      $cc = $options['copyright-color'];

        $data = ".copyright{ background-color: $cc !important;}";
        wp_add_inline_style( 'arcane_style', $data );
    }


    $page_custombck = get_post_meta(get_the_ID(), 'page_header_image', true);
    if(!empty($page_custombck)){
        $img = wp_get_attachment_url($page_custombck);
        $data = "header{  background-image: url( $img ) !important}";
        wp_add_inline_style( 'arcane_style', $data );
    }


    if(!empty($options['header-settings-default-image'])){
        $img = $options['header-settings-default-image']['url'];
        $data = "header{  background-image: url( $img )}";
        wp_add_inline_style( 'arcane_style', $data );
    }

    $page_header = get_post_meta(get_the_ID(), 'header-color-selector', true);
    if(!empty($page_header)){

        if($page_header == 'gradient'){

            $gradient_top = get_post_meta(get_the_ID(), 'header-gradient-top', true);
            $gradient_bottom = get_post_meta(get_the_ID(), 'header-gradient-bottom', true);

            if(!empty($gradient_top) && !empty($gradient_bottom)){
                $data = ".navbar-wrapper{background: linear-gradient(90deg, $gradient_bottom 0%, $gradient_top 100%);}";
                wp_add_inline_style( 'arcane_style', $data );
            }
        }

        if($page_header == 'color'){

            $color = get_post_meta(get_the_ID(), 'header-color', true);

            if(!empty($color)){
                $data = ".navbar-wrapper{  background: $color}";
                wp_add_inline_style( 'arcane_style', $data );
            }
        }
    }

    $menu_color = get_post_meta(get_the_ID(), 'menu-color', true);

    if(!empty($menu_color)){
        $data = ".navbar-inverse{background-color: $menu_color !important;}";
        wp_add_inline_style( 'arcane_style', $data );
    }


    $remove_header_image = get_post_meta(get_the_ID(), 'page_header_image_remove', true);

    if($remove_header_image === '1'){
        $data = "header{background-image: none !important;}";
        wp_add_inline_style( 'arcane_style', $data );
    }

    $page_background_image = get_post_meta(get_the_ID(), 'page_background_image', true);
    if(!empty($page_background_image)){
        $img = wp_get_attachment_url($page_background_image);
        $data = "body{background-image: url( $img )}";
        wp_add_inline_style( 'arcane_style', $data );
    }

    $page_bck_color = get_post_meta(get_the_ID(), 'page-bck-color', true);

    if(!empty($page_bck_color)){
        $data = "body{background-color: $page_bck_color !important;}";
        wp_add_inline_style( 'arcane_style', $data );
    }

     $remove_page_image = get_post_meta(get_the_ID(), 'page_image_remove', true);

    if($remove_page_image === '1'){
        $data = "body{background-image: none !important;}";
        wp_add_inline_style( 'arcane_style', $data );
    }

}
}

if(!function_exists('arcane_fonts')){
function arcane_fonts() {
    global $arcane_version;
    wp_enqueue_style( 'arcane-fonts',arcane_fonts_url(), [],$arcane_version);
}
}




if(!function_exists('arcane_external_styles')){
function arcane_external_styles(){
  global $arcane_version;
  wp_enqueue_style('arcane-custom-style2',  get_theme_file_uri('css/jquery.bxslider.css'),  [], $arcane_version);
  wp_enqueue_style('arcane-animatecss',  get_theme_file_uri('css/animate.css'),  [], $arcane_version);
}
}

/*theme scripts*/
if(!function_exists('arcane_my_scripts')){
function arcane_my_scripts(){
global $arcane_version, $post, $wpdb, $arcane_challenge_sent, $arcane_noviuser, $arcane_error_msg, $arcane_submittedalready, $ArcaneWpTeamWars;

$options = arcane_get_theme_options();

wp_enqueue_script( 'flatpickr', get_theme_file_uri('js/flatpickr.min.js'),'',$arcane_version,true);
wp_enqueue_script( 'jquery-noty-packaged', get_theme_file_uri('js/noty/packaged/jquery.noty.packaged.min.js'),'',$arcane_version,true);
wp_enqueue_script( 'twitch-embed','https://embed.twitch.tv/embed/v1.js','',$arcane_version,true);


if(arcane_get_permalink_for_template('tmp-team-creation.php')){
    $date_format = arcane_dateFormatTojQuery(get_option('date_format'));

    $settingsTeam = array(
        'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' )),
        'security' => wp_create_nonce('arcane-security-nonce'),
        'dateFormat' => $date_format,
        'pleaseWait' => esc_html__('Please Wait...', 'arcane'),
        'create' => esc_html__('Create', 'arcane'),
        'invalidType' => esc_html__('Invalid file type', 'arcane'),
    );

wp_enqueue_script( 'arcane-team-creation', get_theme_file_uri('js/team-creation-scripts.js'),'',$arcane_version,true);
wp_localize_script('arcane-team-creation', 'settingsTeam', $settingsTeam);
}

wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-ui-dialog');
wp_enqueue_script('jquery-ui-tooltip');
wp_enqueue_script('jquery-ui-datepicker');

wp_enqueue_script( 'jquery-ui-editable',  get_theme_file_uri('js/jqueryui-editable.min.js'),'',$arcane_version,true);


$now = current_time('timestamp');

wp_enqueue_script( 'arcane-cw',   get_theme_file_uri('js/matches.js'),'',$arcane_version,true);
wp_localize_script('arcane-cw',
    'wpCWL10n',
    array(
    'noMapImg' =>  get_theme_file_uri('img/defaults/mapdef.jpg'),
    'addRound' => esc_html__('Add Round', 'arcane'),
    'excludeMap' => esc_html__('Exclude map from match', 'arcane'),
    'removeRound' => esc_html__('Remove round', 'arcane'),
    'now' => $now
    )
);

wp_enqueue_script( 'jquery-webticker',  get_theme_file_uri('js/jquery.webticker.js'),'',$arcane_version,true);
wp_enqueue_script( 'mobile-drag-ui',   get_theme_file_uri('js/mobile.drag.ui.min.js'),'',$arcane_version,true);



/*Tournaments*/
wp_enqueue_script( 'moment',  get_theme_file_uri('js/moment.js'),'',$arcane_version,true);
wp_enqueue_script( 'moment-timezones',  get_theme_file_uri('js/moment_timezones.js'),'',$arcane_version,true);

/*feather icons*/
wp_enqueue_script( 'feathericons',  get_theme_file_uri('js/feather.min.js'),'',$arcane_version,true);


//Meta data
$tour_con = get_post_meta(get_the_ID(), 'tournament_contestants', true);
$game_name = get_post_meta(get_the_ID(), 'tournament_game', true);

if(get_option('time_format') == 'G \h i \m\i\n'){
	$tf = 'H:i';
}else{
	$tf = get_option('time_format');
}
 if($tf == 'H:i'){$tf = 'H:i:s';}

$php_timeformat = get_option('date_format')." ".$tf;
$moment_format = arcane_convertPHPToMomentFormat($php_timeformat);


$found_game_id = false;

$games = array();
if(class_exists('Arcane_Types'))
{$games = $ArcaneWpTeamWars->get_game('');}

if($games)
{foreach ($games as $game) {
    if ($game->title == $game_name) {
        $found_game_id = $game->id;
    }
}}


$teams = arcane_get_user_teams(get_current_user_id());

$teamarray = $ArcaneWpTeamWars->get_team(array('id' => $teams));
$timovi_admin = [];
foreach($teamarray as $team_jedan){
   $post_meta_arr = get_post_meta( $team_jedan->ID, 'team', true );
   if( (isset( $post_meta_arr['super_admin'] ) && $post_meta_arr['super_admin'] == get_current_user_id()) or (isset($post_meta_arr['admins']) && in_array(get_current_user_id(), $post_meta_arr['admins']))){
       $timovi_admin[] = $team_jedan->ID;
   }
}


$in_array = '';
if(isset($teamarray[0]->games) && is_array($teamarray[0]->games)){
    $in_array = in_array($found_game_id, $teamarray[0]->games);
}

$tim_niz = [];
if(isset($timovi_admin[0]))
{$tim_niz = $timovi_admin[0];}


$edit_mode = false;
$game_id = $maps = '';
if(isset($_GET['edit'])){
    $edit_mode = true;
    $game = get_post_meta($_GET['edit'], 'tournament_game', true);
	$game_id = arcane_return_game_id_by_game_name($game);
	$maps = get_post_meta($_GET['edit'], 'tournament_maps', true);
}

$c_id = get_current_user_id();

$tournamentpage = false;
if ( is_singular( 'tournament' )) {
    $tournamentpage = true;
}


$tournament_timezone            = get_post_meta( get_the_ID(), 'tournament_timezone', true );
$game_name                      = get_post_meta( get_the_ID(), 'tournament_game', true );
$max_number_of_participants     = get_post_meta( get_the_ID(), 'tournament_max_participants', true );
$tournament_starts              = get_post_meta( get_the_ID(), 'tournament_starts', true );
$tournament_starts_unix         = get_post_meta( get_the_ID(), 'tournament_starts_unix', true );
$tformat                        = get_post_meta( get_the_ID(), 'format', true );
$game_modes                     = get_post_meta( get_the_ID(), 'game_modes', true );
$contestants                    = get_post_meta( get_the_ID(), 'tournament_contestants', true );
$tournament_server              = get_post_meta( get_the_ID(), 'tournament_server', true );
$tournament_platform            = get_post_meta( get_the_ID(), 'tournament_platform', true );
$games_format                   = get_post_meta( get_the_ID(), 'tournament_games_format', true );
$game_frequency                 = get_post_meta( get_the_ID(), 'tournament_game_frequency', true );
$tournament_frequency           = get_post_meta( get_the_ID(), 'tournament_frequency', true );
$tournament_maps                = get_post_meta( get_the_ID(), 'tournament_maps', true );
$tournament_regulations         = get_post_meta( get_the_ID(), 'tournament_regulations', true );
$prizes                         = get_post_meta( get_the_ID(), 'tournament_prizes', true );
$tournament_new_start_unix      = strtotime('+1 years', time());
$tournament_new_start           = date(get_option('date_format') . ', ' . get_option('time_format'), $tournament_new_start_unix);

$content = '';
$post_object = get_post( get_the_ID() );
if(isset($post_object->post_content))
{$content = $post_object->post_content;}

$restart_data = [
    'pid' => 0,
    'title' => get_the_title(get_the_ID()),
    'description' => $content,
    'participants' => 0,
    'max_participants' => $max_number_of_participants,
    'tournament_format' => $tformat,
    'tournament_server' => $tournament_server,
    'tournament_platform' => $tournament_platform,
    'game' => $game_name,
    'maps' => $tournament_maps,
    'prizes' => $prizes,
    'tournament_starts' => $tournament_new_start,
    'tournament_starts_unix' => $tournament_new_start_unix,
    'tournament_timezone' => $tournament_timezone,
    'tournament_contestants' => $contestants,
    'tournament_games_format' => $games_format,
    'tournament_game_frequency' => $game_frequency,
    'tournament_frequency' => $tournament_frequency,
    'game_modes' => $game_modes,
    'regulations' => $tournament_regulations,
];

$tid = get_the_ID();

$settingsTournaments = array(
    'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' )),
    'security' => wp_create_nonce('arcane-security-nonce'),
    'tournamentNameEmpty' => esc_html__('Tournament name cannot be empty!', 'arcane'),
    'tournamentDescEmpty' => esc_html__('Tournament description cannot be empty!', 'arcane'),
    'tournamentDateEmpty' => esc_html__('Start date cannot be empty!', 'arcane'),
    'tournamentZoneEmpty' => esc_html__('Timezone cannot be empty!', 'arcane'),
    'tournamentLocationEmpty' => esc_html__('Location cannot be empty!', 'arcane'),
    'tournamentFormatEmpty' => esc_html__('Tournament format cannot be empty!', 'arcane'),
    'tournamentParticipantsNumEmpty' => esc_html__('Number of participants cannot be empty!', 'arcane'),
    'tournamentPlatformEmpty' => esc_html__('Platform cannot be empty!', 'arcane'),
    'tournamentParticipantsEmpty' => esc_html__('Participants cannot be empty!', 'arcane'),
    'tournamentParticipantsLess' => esc_html__('Number of participants cannot be less than 3!', 'arcane'),
    'tournamentGameFormatEmpty' => esc_html__('Game format cannot be empty!', 'arcane'),
    'tournamentGameFrequencyEmpty' => esc_html__('Game frequency cannot be empty!', 'arcane'),
    'tournamentFrequencyEmpty' => esc_html__('Tournament frequency cannot be empty!', 'arcane'),
    'tournamentMapsEmpty' => esc_html__('Please select maps!', 'arcane'),
    'loader' => get_theme_file_uri('img/loadingb.svg'),
    'tournamentsMore' => esc_html__('MORE ABOUT TOURNAMENT', 'arcane'),
    'tournamentsHide' => esc_html__('HIDE TOURNAMENT DETAILS', 'arcane'),
    'tournamentStarts' => $tournament_starts,
    'tournamentStartsUnix' => $tournament_starts_unix,
    'tournamentTimezone' => $tournament_timezone,
    'momentFormat' => $moment_format,
    'days' => esc_html__("days", "arcane"),
    'h' => esc_html__("H", "arcane"),
    'm' => esc_html__("M", "arcane"),
    's' => esc_html__("S", "arcane"),
    'tourContestants' => $tour_con,
    'joiningTournament' => esc_html__('Joining tournament, please wait!', 'arcane'),
    'tournamentJoined' => esc_html__('Tournament joined!', 'arcane'),
    'countTeams' => arcane_return_number_of_team_admin(),
    'foundGameId' => $found_game_id,
    'inarray' => $in_array,
    'teamsArray' => $tim_niz,
    'leavingTournament' => esc_html__('Leaving tournament, please wait!', 'arcane'),
    'tournamentLeft' => esc_html__('Tournament left!', 'arcane'),
    'userAccepted' => esc_html__('The user is accepted!', 'arcane'),
    'userRejected' => esc_html__('The user is rejected!', 'arcane'),
    'userKicked' => esc_html__('User is kicked out of the tournament', 'arcane'),
    'editMode' => $edit_mode,
    'gameId' => $game_id,
    'maps' => $maps,
    'noTeamsPlay' => esc_html__('No teams you created play the tournament game!', 'arcane'),
    'leave' => esc_html__('Leave now!', 'arcane'),
    'join' => esc_html__('Join now', 'arcane'),
    'tournamentPage' => $tournamentpage,
    'cId' => $c_id,
    'removePremium' => esc_html__('Remove premium', 'arcane'),
    'premiumTournament' => esc_html__('Premium tournament', 'arcane'),
    'tournamentIsPremium' => esc_html__('Tournament is now premium!', 'arcane'),
    'removedPremium' => esc_html__('Tournament removed from premium!', 'arcane'),
    'makePremium' => esc_html__('Make this tournament premium', 'arcane'),
    'restarting' => esc_html__('Restarting tournament, please wait!', 'arcane'),
    'restartData' => $restart_data,
    'tID' => $tid,
    'isSingle' => is_singular('tournament'),


    'you_want_delete' => esc_html__("Are you sure you want to delete this tournament?", 'arcane'),
    'deleting_tournament' => esc_html__('Deleting the tournament!', 'arcane'),
     );

wp_enqueue_script( 'arcane_tournaments',   get_theme_file_uri('js/tournament-scripts.js'),'',$arcane_version,true);
wp_localize_script('arcane_tournaments', 'settingsTournaments', $settingsTournaments);

$home_url = home_url('/');

$footer_ajax_calls = array(
        'homeUrl' => $home_url,
        'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' )),
        'security' => wp_create_nonce('arcane-security-nonce'),
        'teamDeleted' => esc_html__('Team has been deleted!', 'arcane'),
        'challengeAccepted' => esc_html__('Challenge accepted!', 'arcane'),
        'challengeRejected' => esc_html__('Challenge rejected!', 'arcane'),
        'pendingRequest' => esc_html__('Your request to join team is pending!', 'arcane'),
        'removedFromTeam' => esc_html__('Removed from team!', 'arcane'),
        'cancelRequest' => esc_html__('Request canceled!', 'arcane'),
        'joinTeam' => esc_html__('Your request to join team has been sent!', 'arcane'),
        'letThisMemberJoin' => esc_html__('User joined your team!', 'arcane'),
        'userJoined'=> esc_html__('User joined!', 'arcane'),
        'memberNotThere' => esc_html__('User canceled his join request!', 'arcane'),
        'alreadyJoined' => esc_html__('User already joined your team!', 'arcane'),
        'makeAdministrator' => esc_html__('Added as administrator!', 'arcane'),
        'downgradeToUser' => esc_html__('Admin downgraded!', 'arcane'),
        'matchDeletedRequest' => esc_html__('Delete request sent!', 'arcane'),
        'matchDeleted' => esc_html__('Match deleted!', 'arcane'),
        'matchDeleteRejected' => esc_html__('Match delete rejected!', 'arcane'),
        'goingTo' => $home_url,
        'scoreAccepted' => esc_html__('Score accepted!', 'arcane'),
        'scoreRejected' => esc_html__('Score rejected!', 'arcane'),
        'editAccepted' => esc_html__('Edit accepted!', 'arcane'),
        'editRejected' => esc_html__('Edit rejected!', 'arcane'),
        'deleteAccepted' => esc_html__('Delete accepted!', 'arcane'),
        'deleteRejected' => esc_html__('Delete rejected!', 'arcane')
);
wp_enqueue_script('arcane-footer-ajax-calls',   get_theme_file_uri('js/footer-ajax-calls.js'),'',$arcane_version,true);
wp_localize_script('arcane-footer-ajax-calls', 'footerAjaxCalls', $footer_ajax_calls);


$tournament_knockout = array(
'round' => esc_html__('Round', 'arcane'),
);
wp_enqueue_script('tournament-knockout',  get_theme_file_uri('js/jquery.bracket.min.js'), ['jquery']);
wp_localize_script('tournament-knockout', 'tournamentKnockout', $tournament_knockout);



$postID = '';
if(isset($post))
{$postID = $post->ID;}

$a_id = '';
if(isset($post))
{$a_id = $post->post_author;}

if(function_exists('bbp_has_forums')){
    if ( bbp_has_forums() ){
        $bb_has_forums = 'yes';
        $newforumtitle = esc_html__('Forums', 'arcane');
    }
}else{
    $bb_has_forums = '';
    $newforumtitle = '';
}

if(!isset($bb_has_forums)){$bb_has_forums = '';}
if(!isset($newforumtitle)){$newforumtitle = '';}


$registration_successful = '';
if(isset($arcane_noviuser) && $arcane_noviuser == 'sub' && empty($arcane_error_msg)){
    $registration_successful = 'yes';
}

$pid = 'no';
if( isset($_GET['pid']) ){
    $pid = 'yes';
}

if( isset($_GET['mid']) ){
    $mid = 'yes';
    $match_id = $_GET['mid'];
    $match = $ArcaneWpTeamWars->get_match(array('id' => $match_id));
    $match = (array) $match;
    $team2 = $ArcaneWpTeamWars->get_team(array('id' => $match['team2']));
    $team2 = (array) $team2;
    $team2_id = $team2['ID'];
}else{
    $mid = 'no';
    if(isset($_GET['pid'])){
        $team2_id = $_GET['pid'];
    }else{
        $team2_id =  0;
    }
}

$m = '';
if(is_single()){
     $data = [];
        if($postID > 0) {
            $m = $ArcaneWpTeamWars->get_match(array('id' => $postID));

        if(!empty($m)){
            $data = (array)$m;

            $data['scores'] = [];

            $ID = $data['ID'];

            $rounds = $ArcaneWpTeamWars->get_rounds($ID);

            foreach($rounds as $round) {
                $data['scores'][$round->group_n]['map_pic'] = arcane_return_map_pic($round->map_id);
                $data['scores'][$round->group_n]['map_title'] = arcane_return_map_title($round->map_id);
                $data['scores'][$round->group_n]['map_id'] = $round->map_id;
                if(isset($round->id))
                {$data['scores'][$round->group_n]['round_id'][] = $round->id;}
                $data['scores'][$round->group_n]['team1'][] = $round->tickets1;
                $data['scores'][$round->group_n]['team2'][] = $round->tickets2;
            }

        }

    }

  $encoded_data = json_encode($data['scores']);
}

if(!isset($encoded_data)) {$encoded_data ='';}

 $game_page = '';
if( isset($_GET['gid']) ){
    $game_page = 'yes';
}

$bp_id = 0;
if ( class_exists('BuddyPress')){
    $bp_id = bp_displayed_user_id();
}

$is_admin = false;

if((current_user_can( 'manage_options' ))){$is_admin = true;}
if($bp_id == $c_id){$is_mine = true;}else{$is_mine = false;}
if($a_id == $c_id){$is_mine_team = true;}else{$is_mine_team = false;}

$singular_matches = 'no';
if ( is_singular( 'matches' ) ) {
    $singular_matches = 'yes';
}

$tickerspeed = 50;
if(isset($options['tickerspeed']))
    {$tickerspeed = $options['tickerspeed'];}

$arcane_report = '';
if(!empty($_POST) && isset($_POST['report_score'])){
    $arcane_report = 'reported';
}

$arcane_submitted = $arcane_submittedalready = '';

if(!empty($m)){
$match_status = get_post_meta( $m->ID, 'status', true );
    if(!empty($_POST) && isset($_POST['submit_score'])){
        if($match_status != 'submitted1' && $match_status != 'submitted2' ) {
            $arcane_submitted = 'submitted';
        }else{
            $arcane_submittedalready = 'submittedalready';
        }
    }
}


$custom_profile_fields = array();
if(class_exists('BuddyPress'))
$custom_profile_fields = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'bp_xprofile_fields');

$date_added = '';

if(isset($_POST['custom_fields'])){
    $posted_customs = $_POST['custom_fields'];
    foreach ($custom_profile_fields as $tempfield) {
        foreach ($_POST['custom_fields'] as $field){
            if (isset($tempfield->id) && isset($field->id) && ($tempfield->id == $field->id)) {
               $date_added = $posted_customs[$field->id];
            }
        }
    }
}

$fieldid = '';
if (is_array($custom_profile_fields)) {
    foreach ($custom_profile_fields as $field) {
        if($field->id == '1') continue;
        if ($field->type == "datebox") {
            $fieldid = $field->id;
        }
    }
}
$datead = '';
$dodaj = '';
 if(isset($date_added) && !empty($date_added)){
     $dodaj = 'true';
     $datead = $date_added;
 }


$settingsGlobal = array(
    'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' )),
    'security' => wp_create_nonce('arcane-security-nonce'),
    'postID' => $postID,
    'pid' => $pid,
    'cid' => $c_id,
    'challengeSent' => $arcane_challenge_sent,
    'challengeRequestSent' => esc_html__('Challenge request sent!', 'arcane'),
    'bbHasForums' => $bb_has_forums,
    'newforumtitle' => $newforumtitle,
    'registrationSuccessful' => $registration_successful,
    'registrationSuccessfulMsg' => esc_html__('Registration successful!', 'arcane'),
    'reported' => $arcane_report,
    'submitted' => $arcane_submitted,
    'alreadySubmitted' => $arcane_submittedalready,
    'submittedString' => esc_html__('Score has been submitted!', 'arcane'),
    'alreadySubmittedString' => esc_html__('Score has already been submitted by other team!', 'arcane'),
    'reportedString' => esc_html__('Match has been reported!', 'arcane'),
    'mid' => $mid,
    'errmsg' => esc_html__("This field is required!","arcane"),
    'team2Id' => $team2_id,
    'encodedData' => $encoded_data,
    'gamePage' => $game_page,
    'admin' => $is_admin,
    'mine' => $is_mine,
    'mineTeam' => $is_mine_team,
    'singularMatches' => $singular_matches,
    'field_id' => $fieldid,
    'date_added' => $datead,
    'dodaj' => $dodaj,
    'tickerspeed' => $tickerspeed,
    'searchFor' => esc_html__('Search for...', 'arcane'),
    'activationLinkSent' => esc_html__('Activation link sent!', 'arcane'),
    'invalidType' => esc_html__('Invalid file type', 'arcane'),
 );

wp_enqueue_script( 'arcane-global',   get_theme_file_uri('js/global.js'), ['jquery-ui-sortable'],$arcane_version,true);
wp_localize_script('arcane-global', 'settingsGlobal', $settingsGlobal);

wp_enqueue_script( 'carousel', get_theme_file_uri('js/carousel.min.js'),'',$arcane_version,true);

    if(isset($_GET['login']) && $_GET['login'] == 'failed'){
        wp_add_inline_script( 'arcane-global', 'NotifyMe("'.esc_html__('Incorrect Username or Password!', 'arcane').'", "error","","","",2000);');
    }
}
}



/*admin sctipts*/
if(!function_exists('arcane_scripts_admin')){
function arcane_scripts_admin(){

global $ArcaneWpTeamWars, $arcane_version;

$arcaneAdminVars = [];
$current_screen = get_current_screen();

if($current_screen->base == 'teamwars_page_wp-teamwars-matches'){
$id = 0;
if(isset($_GET['id']))
{$id = $_GET['id'];}
$data = [];
$defaults = array(
    'scores' => [],
);

if($id > 0) {

    $t = $ArcaneWpTeamWars->get_match(array('id' => $id));


    if(!empty($t)){
        $data = (array)$t;

        $data['id'] = $data['ID'];

        $data['scores'] = [];

        $post_id = $data['id'];
        $rounds = $ArcaneWpTeamWars->get_rounds($data['id']);

        if (is_array($rounds)) {
        foreach($rounds as $round) {
            $data['scores'][$round->group_n]['map_id'] = $round->map_id;
            $data['scores'][$round->group_n]['round_id'][] = $round->id;
            $data['scores'][$round->group_n]['team1'][] = $round->tickets1;
            $data['scores'][$round->group_n]['team2'][] = $round->tickets2;
        }
      }
    }
}
$scores = '';
extract( $ArcaneWpTeamWars->extract_args(stripslashes_deep($_POST),  $ArcaneWpTeamWars->extract_args($data, $defaults)));

$arcaneAdminVars = ['scores' => $scores];
}

wp_enqueue_script( 'arcane-admin',   get_theme_file_uri('js/admin.js'),'',$arcane_version,true);
wp_localize_script('arcane-admin', 'arcaneAdminVars', $arcaneAdminVars);



}
}

/*admin styles*/
if(!function_exists('arcane_styles_admin')){
    function arcane_styles_admin(){
        global $arcane_version;
        wp_enqueue_style( 'fontawesome',  'https://use.fontawesome.com/releases/v5.1.0/css/all.css',  [], $arcane_version);
        wp_enqueue_style( 'arcane_redux_admin_style',   get_theme_file_uri('css/arcane-redux-styling.css'),  [], $arcane_version);
        wp_enqueue_style('arcane-admin',  get_theme_file_uri('css/admin.css'),  [], $arcane_version);
        wp_enqueue_style('wp-discord',  get_theme_file_uri('css/wp-discord-admin.css'),  [], $arcane_version);

        $now = current_time('timestamp');
        /*team wars*/
        wp_enqueue_script('arcane-wp-cw-matches', get_theme_file_uri('js/matches.js'), ['jquery'], $arcane_version);
        wp_localize_script('arcane-wp-cw-matches',
            'wpCWL10n',
            array(
            'noMapImg' =>  get_theme_file_uri('img/defaults/mapdef.jpg'),
            'plugin_url' => get_theme_file_uri('addons/team-wars'),
            'addRound' => esc_html__('Add Round', 'arcane'),
            'excludeMap' => esc_html__('Exclude map from match', 'arcane'),
            'removeRound' => esc_html__('Remove round', 'arcane'),
            'now' => $now
            )
        );
    }
}


/*add last item in footer sidebar class*/
if(!function_exists('arcane_widget_first_last_classes')){
function arcane_widget_first_last_classes($params) {
    global $arcane_widget_num; // Global a counter array
    $this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
    $arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets
    if(!$arcane_widget_num) {// If the counter array doesn't exist, create it
        $arcane_widget_num = [];
    }
    if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
        return $params; // No widgets in this sidebar... bail early.
    }
    if(isset($arcane_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
        $arcane_widget_num[$this_id] ++;
    } else { // If not, create it starting with 1
        $arcane_widget_num[$this_id] = 1;
    }
    $class = 'class="widget-' . $arcane_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options
    if($arcane_widget_num[$this_id] == 1) { // If this is the first widget
        $class .= 'first ';
    } elseif($arcane_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
        $class .= 'last ';
    }
    $params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"
    return $params;
}
}

/*custom comments*/
if(!function_exists('arcane_custom_comments')){
function arcane_custom_comments($comment, $args, $depth) {  ?>
    <li class="comment">
        <div class="wcontainer"><img alt="img" class="photo avatar" src="<?php echo arcane_commenter_avatar($comment->user_id); ?>" />
  <?php if ($comment->comment_approved == '0'){ ?><span class='unapproved'><?php esc_html_e("Your comment is awaiting moderation.", 'arcane'); ?></span> <?php } ?>
          <div class="comment-body">
             <div class="comment-author"><?php arcane_commenter_link() ?> <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
             <i><small><?php comment_time('M j, Y @ G:i a'); ?></small> </i><br />
             <?php comment_text(); ?>

        </div>
        <div class="clear"></div>
        </div>
    </li>
<?php }
}

/*custom pings*/
if(!function_exists('arcane_custom_pings')){
function arcane_custom_pings($comment, $args, $depth) {
        ?>
         <div class="project-comment row">
                <div class="comment-author"><?php printf(esc_html__('By %1$s on %2$s at %3$s', 'arcane'),
                        get_comment_author_link(),
                        get_comment_date(),
                        get_comment_time() );
                        edit_comment_link(esc_html__('Edit', 'arcane'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
    <?php if ($comment->comment_approved == '0'){ ?><span class="unapproved"><?php esc_html_e('Your trackback is awaiting moderation.', 'arcane');?> </span><?php } ?>
            <div class="comment-content span6">
                <?php comment_text(); ?>
            </div>
            </div>
<?php
}
}


/*Produces an avatar image with the hCard-compliant photo class */
if(!function_exists('arcane_commenter_link')){
function arcane_commenter_link() {
   $commenter = get_comment_author_link();
    if ( preg_match( '/<a[^>]* class=[^>]+>/', $commenter ) ) {
        $commenter = preg_replace( '/(<a[^>]* class=[\'"]?)/', '\\1url ' , $commenter );
    } else {
        $commenter = preg_replace( '/(<a )/', '\\1class="url "/' , $commenter );
    }
    echo ' <span class="comment-info">' . wp_kses_post($commenter) . '</span>';
}
}


/*Commenter avatar*/
if(!function_exists('arcane_commenter_avatar')){
function arcane_commenter_avatar($uid) {
     $url0 = get_user_meta($uid, 'profile_photo', true);
     if(!empty($url0)){
       $url1 = arcane_aq_resize( $url0, 100, 100, true, '', true );
       $url = $url1[0];  //resize & crop img
     }
     if(empty($url)){ $url = get_theme_file_uri('img/defaults/default_profile55x55.png'); }
     return $url;
}
}

if(!function_exists('arcane_change_team_admin')){
function arcane_change_team_admin( $post_id ) {
  if (isset($_POST['post_type'])) {
    if ('team' == $_POST['post_type'] ) {

      $post_arr['super_admin'] = $_POST['post_author'];
      $post_arr['admins'] = [];
      $post_arr['users'] = [];

      $meta = get_post_meta($post_id, 'team', true);


      if(isset($meta['admins']))
      {$post_arr['admins'] = $meta['admins'];}
      if(isset($meta['users']))
      {$post_arr['users'] = $meta['users'];}

      if(isset($meta['super_admin']))
      {array_push($post_arr['admins'], $meta['super_admin']);}

      if(isset($post_arr['users']['active'])){
      $pos = array_search($post_arr['super_admin'], $post_arr['users']['active']);
         if(is_int($pos)){
             unset($post_arr['users']['active'][$pos]);
         }
      }

      if(isset($post_arr['users']['pending'])){
      $pos2 = array_search($post_arr['super_admin'], $post_arr['users']['pending']);
         if(is_int($pos2)){
             unset($post_arr['users']['pending'][$pos2]);
         }
      }

     if(isset($post_arr['admins'])){
     $pos3 = array_search($post_arr['super_admin'], $post_arr['admins']);
         if(is_int($pos3)){
             unset($post_arr['admins'][$pos3]);
         }
     }


      update_post_meta($post_id, 'team', $post_arr);
      $currentteams = get_user_meta($_POST['post_author'], 'team_post_id' );
      if(!arcane_is_val_exists($post_id,$currentteams)){
        add_user_meta($_POST['post_author'],'team_post_id', array($post_id, time()));
      }
    }
  }
}
}

if(!function_exists('arcane_return_categories')){
function arcane_return_categories(){

    $categories = get_categories(
    array(
            'type'          => 'post',
            'child_of'      => 0,
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    => 1,
            'hierarchical'  => 1,
            'taxonomy'      => 'category',
            'pad_counts'    => false
    ) );

    foreach ($categories as $cat) {
        $cats[$cat->cat_ID] = $cat->cat_name;
    }

    if(!isset($cats)){$cats= [];}

    $kategorije = [];
    foreach($cats as $opt_value=>$opt_name){
        // array describing our fields
        $kategorije[] = array(
            'name' => $opt_name,
            'id' => 'blog_cats'.$opt_value,
            'type' => 'checkbox',
            'default' => ''
        );
    }

    return $kategorije;
}
}

if(function_exists('arcane_add_smart_meta_box')){
arcane_add_smart_meta_box('my-meta-box10', array(
'title' => esc_html__('Blog categories','arcane' ), // the title of the meta box
'pages' => array('page'),  // post types on which you want the metabox to appear
'context' => 'normal', // meta box context (see above)
'priority' => 'high', // meta box priority (see above)
'fields' => arcane_return_categories()));
}


/*image resize*/
if(!function_exists('arcane_aq_resize')){
function arcane_aq_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {

    // Validate inputs.
    if ( ! $url || ( ! $width && ! $height ) ) {return false;}

    // Caipt'n, ready to hook.
    if ( true === $upscale ) {add_filter( 'image_resize_dimensions', 'arcane_aq_upscale', 10, 6 );}

    // Define upload path & dir.
    $upload_info = wp_upload_dir();
    $upload_dir = $upload_info['basedir'];
    $upload_url = $upload_info['baseurl'];

    $http_prefix = "http://";
    $https_prefix = "https://";

    /* if the $url scheme differs from $upload_url scheme, make them match
       if the schemes differe, images don't show up. */
    if(!strncmp($url,$https_prefix,strlen($https_prefix))){ //if url begins with https:// make $upload_url begin with https:// as well
        $upload_url = str_replace($http_prefix,$https_prefix,$upload_url);
    }
    elseif(!strncmp($url,$http_prefix,strlen($http_prefix))){ //if url begins with http:// make $upload_url begin with http:// as well
        $upload_url = str_replace($https_prefix,$http_prefix,$upload_url);
    }


    // Check if $img_url is local.
    if ( false === strpos( $url, $upload_url ) ) {return false;}

    // Define path of image.
    $rel_path = str_replace( $upload_url, '', $url );
    $img_path = $upload_dir . $rel_path;

    // Check if img path exists, and is an image indeed.
    if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) ) {return false;}

    // Get image info.
    $info = pathinfo( $img_path );
    $ext = $info['extension'];
    list( $orig_w, $orig_h ) = getimagesize( $img_path );

    // Get image size after cropping.
    $dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
    $dst_w = $dims[4];
    $dst_h = $dims[5];

    // Return the original image only if it exactly fits the needed measures.
    if ( ! $dims && ( ( ( null === $height && $orig_w == $width ) xor ( null === $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) ) {
        $img_url = $url;
        $dst_w = $orig_w;
        $dst_h = $orig_h;
    } else {
        // Use this to check if cropped image already exists, so we can return that instead.
        $suffix = "{$dst_w}x{$dst_h}";
        $dst_rel_path = str_replace( '.' . $ext, '', $rel_path );
        $destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

        if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
            // Can't resize, so return false saying that the action to do could not be processed as planned.
            return false;
        }
        // Else check if cache exists.
        elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
            $img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
        }
        // Else, we resize the image and return the new resized image url.
        else {

            // Note: This pre-3.5 fallback check will edited out in subsequent version.
            if ( function_exists( 'wp_get_image_editor' ) ) {

                $editor = wp_get_image_editor( $img_path );

                if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
                    {return false;}

                $resized_file = $editor->save();

                if ( ! is_wp_error( $resized_file ) ) {
                    $resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
                    $img_url = $upload_url . $resized_rel_path;
                } else {
                    return false;
                }

            } else {

                $resized_img_path = wp_get_image_editor( $img_path, $width, $height, $crop ); // Fallback foo.
                if ( ! is_wp_error( $resized_img_path ) ) {
                    $resized_rel_path = str_replace( $upload_dir, '', $resized_img_path );
                    $img_url = $upload_url . $resized_rel_path;
                } else {
                    return false;
                }

            }

        }
    }

    // Return the output.
    if ( $single ) {
        // str return.
        $image = $img_url;
    } else {
        // array return.
        $image = array (
            0 => $img_url,
            1 => $dst_w,
            2 => $dst_h
        );
    }

    return $image;
}
}


if(!function_exists('arcane_aq_upscale')){
function arcane_aq_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
    if ( ! $crop ) {return null;} // Let the wordpress default function handle this.

    // Here is the point we allow to use larger image size than the original one.
    $aspect_ratio = $orig_w / $orig_h;
    $new_w = $dest_w;
    $new_h = $dest_h;

    if ( ! $new_w ) {
        $new_w = intval( $new_h * $aspect_ratio );
    }

    if ( ! $new_h ) {
        $new_h = intval( $new_w / $aspect_ratio );
    }

    $size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

    $crop_w = round( $new_w / $size_ratio );
    $crop_h = round( $new_h / $size_ratio );

    $s_x = floor( ( $orig_w - $crop_w ) / 2 );
    $s_y = floor( ( $orig_h - $crop_h ) / 2 );

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}
}

//woocommerce
if(!function_exists('arcane_woocommerce_header_add_to_cart_fragment')){
function arcane_woocommerce_header_add_to_cart_fragment( $fragments ) {
    global $woocommerce;
 ?>
    <a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>"><div class="cart-icon-wrap"><i class="fas fa-shopping-cart"></i> <div class="cart-wrap"><span><?php echo esc_attr($woocommerce->cart->cart_contents_count); ?> </span></div> </div></a>
    <?php

    $fragments['a.cart-contents'] = ob_get_clean();

    return $fragments;
}
}

if(!function_exists('arcane_be_gravatar_filter')){
function arcane_be_gravatar_filter($avatar, $id_or_email, $size = 150, $default = true, $alt = false, $args = false) {
    $return = '';
    if (is_int($id_or_email) && $id_or_email != 0) {
        $user_data = get_user_meta( $id_or_email, 'thechamp_large_avatar', true );

        if(isset($user_data) && !empty($user_data)){
            $custom_avatar = $user_data;

        }else{
            $custom_avatar = get_the_author_meta('profile_photo', $id_or_email);

        }
    }elseif(is_object($id_or_email)){
        $custom_avatar = '';
        $user = get_user_by( 'email', $id_or_email->comment_author_email );
        if(isset($user->ID))
        {$custom_avatar = get_the_author_meta('profile_photo', $user->ID);}

    }

    $class = '';
    if($args)
        {$class = $args['class'];}

    if (isset($custom_avatar) && !empty($custom_avatar))
        {
             $custom_avatar = arcane_aq_resize( $custom_avatar, $size, $size, true, true, true );
            $return = '<img src="'.esc_url($custom_avatar).'" width="'.esc_attr($size).'" height="'.esc_attr($size).'" alt="'.esc_attr($alt).'" class="'.esc_attr($class).'" />';}
    elseif ($avatar)
        {$return = '<img src="'.get_theme_file_uri('img/defaults/default-profile.jpg').'" width="'.esc_attr($size).'" height="'.esc_attr($size).'" alt="'.esc_attr($alt).'"  class="'.esc_attr($class).'" />';}


    return $return;
}
}

if(!function_exists('arcane_be_gravatar_filter_admin')){
function arcane_be_gravatar_filter_admin($avatar, $id_or_email, $size = 150, $default = true, $alt = false) {

    $return = '';
    if (is_int($id_or_email)) {
        if(function_exists('wsl_get_stored_hybridauth_user_profiles_by_user_id'))
        {$user_data = wsl_get_stored_hybridauth_user_profiles_by_user_id($id_or_email);}

        if(isset($user_data[0]->photourl) && !empty($user_data[0]->photourl)){
            $custom_avatar = $user_data[0]->photourl;

        }else{
            $custom_avatar = get_the_author_meta('profile_photo', $id_or_email);

        }
    } else {
        $custom_avatar = get_the_author_meta('profile_photo');
    }

    if ($custom_avatar)
        {$return = $custom_avatar;}
    elseif ($avatar)
        {$return = get_theme_file_uri('img/defaults/default-profile.jpg');}

    return $return;
}
}

//register custom pages, prep function
if(!function_exists('arcane_get_ID_by_slug')){
function arcane_get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}
}
/***** add country option to profile *****/
add_action('after_setup_theme', 'arcane_country');
if(!function_exists('arcane_country')){
function arcane_country() {
      include_once(get_theme_file_path('theme-functions/usercountry.php'));
}
}

/** add country field to registration page **/
if(!function_exists('arcane_registration_country_list')){
function arcane_registration_country_list() {
    global $wpdb;
    $table = $wpdb->prefix."user_countries";
    $countries = $wpdb->get_results( "SELECT * FROM $table ORDER BY `name`");
    return $countries;
}
}

/*remove profile tab from subnav*/
if(!function_exists('arcane_remove_profile_subnav')){
function arcane_remove_profile_subnav() {
    if ( class_exists('BuddyPress') && function_exists( 'bp_core_remove_subnav_item' ) ) {
        global $bp;
        if(isset($bp->current_component) && isset($bp->settings->slug))
        {if ( $bp->current_component == $bp->settings->slug ) {
            bp_core_remove_subnav_item( $bp->settings->slug, 'profile' );
        }}
    }
}
}

/*ajax calls for user activation link*/
if(!function_exists('arcane_resend_activation_link')){
function arcane_resend_activation_link(){

    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }

     $uid = $_POST['uid'];
     $user_email = $_POST['email'];
     $hash = get_user_meta($uid, 'hash', true);

     $message = '';
     $subject = esc_html__('From ','arcane').get_bloginfo();
     $message .= esc_html__('Please click this link to activate your account: ','arcane');
     $message .= esc_url(get_permalink( get_page_by_path('user-activation'))).'?id='.$uid.'&key='.$hash;
     $headers = 'From: '.get_bloginfo().' <'.get_option("admin_email").'>' . "\r\n" . 'Reply-To: ' . $user_email;

     if (class_exists( 'Arcane_Types' )){
         $arcane_types = new Arcane_Types();
         $arcane_types::arcane_send_email($user_email, $subject, $message, $headers);
     }

     wp_die();
}
}


/*ajax call for add to premium tournament*/
if(!function_exists('arcane_add_to_premium')){
function arcane_add_to_premium(){

    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
    $tid = $_POST['tid'];
    update_post_meta($tid, 'premium', true);

    die();
}
}

/*ajax call for remove from premium tournament*/
if(!function_exists('arcane_remove_premium')){
function arcane_remove_premium(){

    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }

    $tid = $_POST['tid'];
    update_post_meta($tid, 'premium', false);

    die();
}
}





/*Add friend button classes and text*/
if(!function_exists('arcane_add_friend_link_text')){
function arcane_add_friend_link_text($button) {
    $fricon_remove = '<i class="fas fa-times" data-original-title="'.esc_html__("Remove friend", "arcane").'" data-toggle="tooltip"></i>';
    $fricon_add = '<i class="fas fa-user"></i>';
    $fricon_cancel = '<i class="fas fa-times" data-original-title="'.esc_html__("Cancel request!", "arcane").'" data-toggle="tooltip"></i>';

    switch ( $button['id'] ) {
        case 'pending' :
            $button['link_text'] = $fricon_cancel.esc_html__(' Cancel request!', 'arcane');
            $button['link_title'] = 'Cancel friend request';
            $button['link_class'] .= ' add-friend';
        break;

        case 'is_friend' :
            $button['link_text'] = $fricon_remove.esc_html__(' Remove friend!', 'arcane');;
            $button['link_class'] .= ' add-friend';
        break;

        default:
            $button['link_text'] = $fricon_add.esc_html__(' Add as a friend!', 'arcane');
            $button['link_title'] = 'Add as a friend';
            $button['link_class'] .= ' add-friend';
    }
    return $button;
}
}

/*add font awesome into buddypress navigation*/
if(!function_exists('arcane_mb_profile_menu_tabs')){
function arcane_mb_profile_menu_tabs(){
global $bp;
$profile_icon = '<i class="fas fa-user"></i>';
$notifications_icon = '<i class="fas fa-flag"></i>';
$messages_icon = '<i class="far fa-comments"></i>';
$friends_icon = '<i class="fas fa-users"></i>';
$settings_icon = '<i class="fas fa-cog"></i>';
$activity_icon = '<i class="fas fa-bolt"></i>';
$groups_icon = '<i class="fas fa-users"></i>';
$tournaments_icon = '<i class="fas fa-trophy"></i>';
$matches_icon = '<i class="fas fa-crosshairs"></i>';
$twitch_icon = '<i class="fab fa-twitch"></i>';
$yt_icon = '<i class="fab fa-youtube"></i>';

bp_core_new_nav_item(
array(
    'name' => $profile_icon.esc_html__(' profile', 'arcane'),
    'slug' => $bp->profile->slug,
    'position' => 10,
));

if ( bp_is_active( 'activity' ) )
{bp_core_new_nav_item(
array(
    'name' => $activity_icon.esc_html__(' activity', 'arcane'),
    'slug' => $bp->activity->slug,
    'position' => 20,
));}

if ( bp_is_active( 'notifications' ) && bp_is_my_profile() )
{bp_core_new_nav_item(
array(
    'name' => $notifications_icon.esc_html__(' notifications', 'arcane'),
    'slug' => $bp->notifications->slug,
    'position' => 30,
));}

if ( bp_is_active( 'messages' ) && bp_is_my_profile() )
{bp_core_new_nav_item(
array(
    'name' => $messages_icon.esc_html__(' messages', 'arcane'),
    'slug' => $bp->messages->slug,
    'position' => 40,
));}

if ( bp_is_active( 'friends' ) )
{bp_core_new_nav_item(
array(
    'name' => $friends_icon.esc_html__(' friends', 'arcane'),
    'slug' => $bp->friends->slug,
    'position' => 50,
));}

if ( bp_is_active( 'settings' ) && bp_is_my_profile() )
{bp_core_new_nav_item(
array(
    'name' => $settings_icon.esc_html__(' settings', 'arcane'),
    'slug' => $bp->settings->slug,
    'position' => 80,
));}

if ( bp_is_active( 'groups' ) )
{bp_core_new_nav_item(
array(
    'name' => $groups_icon.esc_html__(' groups', 'arcane'),
    'slug' => $bp->groups->slug,
    'position' => 60,
));}



    bp_core_new_nav_item( array(
    'name' => $tournaments_icon.esc_html__(' Tournaments', 'arcane'),
     'slug' => 'tournaments',
     'position' => 50,
     'show_for_displayed_user' => false,
     'screen_function' => 'arcane_load_tourney_profile_page',
     'default_subnav_slug' => 'tournaments'
    ));

    bp_core_new_nav_item(array(
    'name' => $matches_icon.esc_html__(' T. Matches', 'arcane'),
     'slug' => 'matches',
     'position' => 49,
     'show_for_displayed_user' => false,
     'screen_function' => 'arcane_load_matches_profile_page',
     'default_subnav_slug' => 'matches'
    ));
    $twitch = '';
    if(function_exists('xprofile_get_field_data'))
    {$twitch = xprofile_get_field_data( 'Twitch Channel', bp_displayed_user_id() );}
    if(!empty($twitch))
    {bp_core_new_nav_item( array(
    'parent_url'      => bp_loggedin_user_domain() . '/twitchtv/',
    'parent_slug'     => $bp->profile->slug,
    'default_subnav_slug' => 'twtchtv',
    'name' => $twitch_icon.esc_html__(' Twitch', 'arcane'),
    'slug' => 'twitchtv',
    'screen_function' => 'arcane_twitch_tv_screen',
    'position' => 65,
    ) );}

    $youtube = '';
    if(function_exists('xprofile_get_field_data'))
    {$youtube = xprofile_get_field_data( 'YTCName', bp_displayed_user_id() );}
    if(!empty($youtube))
    {bp_core_new_nav_item( array(
    'parent_url'      => bp_loggedin_user_domain() . '/youtube/',
    'parent_slug'     => $bp->profile->slug,
    'default_subnav_slug' => 'ytube',
    'name' => $yt_icon.esc_html__(' YouTube', 'arcane'),
    'slug' => 'youtube',
    'screen_function' => 'arcane_youtube_screen',
    'position' => 66,
    ) );}
 }
}

if(!function_exists('arcane_load_matches_profile_page')){
function arcane_load_matches_profile_page() {
  add_action( 'bp_template_content', 'arcane_profile_show_matches' );
  bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}
}

if(!function_exists('arcane_profile_show_matches')){
function arcane_profile_show_matches(){
      global $ArcaneWpTeamWars;
      $user_id = get_current_user_id();

      $matches =  arcane_return_all_user_matches($user_id); ?>

      <div id="matches" class="user_matches tab-pane teampage-matches active">
      <?php
        if(empty($matches)){
          echo '<div class="error">'.esc_html__("Currently you don't have any tournament matches", 'arcane').'</div>';
        }
        ?>
            <ul class="cmatchesw nochallenges">

               <?php

                foreach ($matches as $match) {

                $match = $ArcaneWpTeamWars->get_match(array('id' => $match->ID));
                $results_matches = $match;

                if(isset($results_matches->team1_tickets)){
                    $t1 = $results_matches->team1_tickets;
                }else{
                    $t1 = '';
                }

                if(isset($results_matches->team2_tickets)){
                    $t2 = $results_matches->team2_tickets;
                }else{
                    $t2 = '';
                }

                if($match->status == 'active' || $match->status == 'submitted1' || $match->status == 'submitted2'){

                    $status = 'notsubmitted';
                }else{

                $status = $t1 == $t2 ? 'mtie' : ($t1 > $t2 ? 'mwin' : 'mlose');
                }

                $tim1 = $match->team1;
                $tim2 = $match->team2;

                if($tim1 == $user_id && ( (int)$t1 > (int)$t2)){
                    $side_color = 'swin';
                }elseif($tim1 == $user_id && ( (int)$t1 < (int)$t2)){
                    $side_color = 'slose';
                }

                if($tim2 == $user_id && ( (int)$t1 < (int)$t2)){
                    $side_color = 'swin';
                }elseif($tim2 == $user_id && ( (int)$t1 > (int)$t2)){
                    $side_color = 'slose';
                }


                if($tim1 ==  get_current_user_id() || $tim2 ==  get_current_user_id()){
                  $admin = true; }else{ $admin = false; }

                if($match->status == 'submitted1' || $match->status == 'submitted2'){
                  $substatus = true; }else{ $substatus = false; }
                ?>
                 <li class="<?php echo esc_attr($status); echo ' '; echo esc_attr($side_color); ?>">

                    <a href="<?php echo esc_url(get_permalink($match->ID)); ?>">
                        <?php if(!isset($match->team1)){$match = new stdClass();$match->team1 = '';} ?>

                          <?php if(($tim1 ==  get_current_user_id() || $tim2 ==  get_current_user_id()) &&
                          ($match->status == 'submitted2' || $match->status == 'submitted1' || $match->status == 'deleted_request_team1' || $match->status == 'deleted_request_team2')){ ?>
                            <i class="deletematch">!</i>
                            <?php } ?>
                        <div class="teama">
                            <?php echo get_avatar((int)$match->team1, 55); ?>

                           <?php if($match->status == 'done') { ?>

                           <span><?php $r1 = $t1 == null ? '0' : $t1; echo esc_attr($r1); ?></span>

                           <?php }else{ ?>


                            <?php if($admin && $substatus){ ?>
                            <span><?php $r1 = $t1 == null ? '0' : $t1; echo esc_attr($r1); ?></span>
                            <?php }else{ ?>
                            <span>0</span>
                            <?php } ?>

                             <?php } ?>

                        </div>
                        <strong><?php esc_html_e('VS', 'arcane'); ?></strong>
                        <div class="teamb">
                           <?php echo get_avatar((int)$match->team2, 55); ?>

                            <?php if($match->status == 'done') { ?>

                             <span><?php $r2 = $t2 == null ? '0' : $t2; echo esc_attr($r2); ?></span>

                            <?php }else{ ?>

                             <?php if($admin && $substatus){ ?>
                            <span><?php $r2 = $t2 == null ? '0' : $t2; echo esc_attr($r2); ?></span>
                            <?php }else{ ?>
                            <span>0</span>
                            <?php } ?>

                            <?php } ?>


                        </div>
                        <div class="minfo">
                            <strong><?php echo esc_attr($match->title); ?></strong>
                            <i class="fas fa-calendar-alt"></i>

                            <?php
                                if(isset($match->date_unix) && !empty($match->date_unix)){
                                    $timezone_string = arcane_timezone_string();
                                    $tournament_timezone = $timezone_string ? $timezone_string : 'UTC';

                                    $currentTime = DateTime::createFromFormat( 'U', $match->date_unix );
                                    $currentTime->setTimeZone(new DateTimeZone($tournament_timezone));
                                    $formattedString = $currentTime->format( get_option('date_format') . ', ' . get_option('time_format'));
                                    echo esc_html($formattedString);
                                }else{
                                   $date = mysql2date(get_option('date_format') . ', ' . get_option('time_format'), $match->date); echo esc_html($date);
                                }
                            ?>

                        </div>
                        <div class="matchgame"><img alt="img" src="<?php echo esc_url(arcane_return_game_image($match->game_id)); ?>" class="mgame" /></div>
                        <div class="matchstatus"></div>
                        <div class="clear"></div>
                    </a>
                </li>

                <?php }  ?>
            </ul>
            </div>

<?php
}
}


/*on team delete update metas*/
if(!function_exists('arcane_onteam_delete')){
function arcane_onteam_delete($post_id) {
    global $ArcaneWpTeamWars,  $post_type;


    if($post_type == 'team'){

    $post_meta_arr = get_post_meta( $post_id, 'team', true );
    if (isset($post_meta_arr['users']['active'])) {
      if (is_array($post_meta_arr['users']['active'])) {
        foreach ($post_meta_arr['users']['active'] as $single) {
          ArcaneUpdateUserTournaments($single);
        }
      }
    }

    $users = get_users(array(
    'meta_key' => 'team_post_id',
    'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'team_post_id',
                'value'   =>  ':'.$post_id.';',
                'compare' => 'LIKE',
            ),
            array(
                'key'     => 'team_post_id',
                'value'   =>  ':"'.$post_id.'";',
                'compare' => 'LIKE',
            ),
        )
        )
    );



        if(!empty($users)){
            foreach ($users as $user) {

            $timovi = get_user_meta($user->ID, 'team_post_id');
                if(empty($timovi)){$timovi = [];}

                if(arcane_is_val_exists($post_id, $timovi)){
                    $index = array_search($post_id, array_column($timovi, 0));
          if ($index !== false) {
            delete_user_meta($user->ID, 'team_post_id', $timovi[$index]);
          }
                }


            }
        }

    $ArcaneWpTeamWars->delete_team($post_id, true);

    }

  arcane_remove_team_from_tournaments($post_id);
  return true;
}
}


if(!function_exists('arcane_return_all_user_matches')){
function arcane_return_all_user_matches($user_id){
  $args = array(
    'post_type' => 'matches',
    'posts_per_page' => -1,
    'post_status' => 'any',
     'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array(
       'relation' => 'AND',
        array(
        'key' => 'tournament_participants',
        'value' => 'user',
        'compare' => 'LIKE',
        ),

      array(
      'relation' => 'OR',
      array(
        'key' => 'team1',
        'value' => $user_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
      array(
        'key' => 'team2',
        'value' => $user_id,
        'compare' => '=',
        'type' => 'numeric',
      )
      )
    )
  );
  $posts = get_posts($args);
  return $posts;
}
}

if(!function_exists('arcane_return_all_team_matches')){
function arcane_return_all_team_matches($team_id){
  $args = array(
    'post_type' => 'matches',
    'posts_per_page' => -1,
    'post_status' => 'any',
     'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array(
      'relation' => 'OR',
      array(
        'key' => 'team1',
        'value' => $team_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
      array(
        'key' => 'team2',
        'value' => $team_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
    )
  );
  $posts = get_posts($args);
  return $posts;
}
}

/*CHALLENGES FUNCTIONS*/
if(!function_exists('arcane_return_all_team_challenges')){
function arcane_return_all_team_challenges($team_id){
     $args = array(
    'post_type' => 'matches',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array(
      'relation' => 'AND',
      array(
       'relation' => 'OR',
      array(
        'key' => 'team1',
        'value' => $team_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
      array(
        'key' => 'team2',
        'value' => $team_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
    ),
    array(
            'key' => 'status',
            'value' => 'pending',
            'compare' => '='
        )
    )
  );

  $posts = get_posts($args);
  return $posts;
}
}

if(!function_exists('arcane_return_all_team_edits')){
function arcane_return_all_team_edits($team_id){
       $args = array(
    'post_type' => 'matches',
    'posts_per_page' => -1,
     'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array(
    'relation' => 'AND',
    array(
      'relation' => 'OR',
      array(
        'key' => 'team1',
        'value' => $team_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
      array(
        'key' => 'team2',
        'value' => $team_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
    ),
    array(
      'relation' => 'OR',
      array(
        'key' => 'status',
        'value' => 'edited1',
        'compare' => '=',
      ),
      array(
        'key' => 'status',
        'value' => 'edited2',
        'compare' => '='
      ),
    )
    )
  );
  $posts = get_posts($args);
  return $posts;
}
}


if(!function_exists('arcane_return_all_team_deletes')){
function arcane_return_all_team_deletes($team_id){
       $args = array(
    'post_type' => 'matches',
    'posts_per_page' => -1,
     'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array(
    'relation' => 'AND',
    array(
      'relation' => 'OR',
      array(
        'key' => 'team1',
        'value' => $team_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
      array(
        'key' => 'team2',
        'value' => $team_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
    ),
    array(
      'relation' => 'OR',
      array(
        'key' => 'status',
        'value' => 'deleted_request_team1',
        'compare' => '=',
      ),
      array(
        'key' => 'status',
        'value' => 'deleted_request_team2',
        'compare' => '='
      ),
    )
    )
  );
  $posts = get_posts($args);
  return $posts;
}
}


/*TEAM FUNCTIONS*/
if(!function_exists('arcane_team_members')){
function arcane_team_members($post_id=false, $currentPage=1){
    global $current_user;

    $membersa = [];
    $membersu = [];

    $post_meta_arr = get_post_meta( $post_id, 'team', true );


    $su_admin = ( isset( $post_meta_arr['super_admin'] ) && $post_meta_arr['super_admin'] == $current_user->ID ) ? true : false;
    if ( $su_admin || ( ( isset( $post_meta_arr['admins'] ) && is_array( $post_meta_arr['admins'] ) && in_array( $current_user->ID, $post_meta_arr['admins'] ) ) || ( isset( $post_meta_arr['admins'] ) && $current_user->ID == $post_meta_arr['admins'] ) ) ) {

        if(!isset($post_meta_arr['users']['pending']))  {$post_meta_arr['users']['pending'] = [];}
        if ( is_array( $post_meta_arr['users']['pending'] ) ) {
            foreach( $post_meta_arr['users']['pending'] as $item ) {
                $membersu[] = $item;
            }
        } elseif ( ! empty( $post_meta_arr['users']['pending'] ) ) {
            $membersu[] = $post_meta_arr['users']['pending'];
        }
    }

    if ( isset( $post_meta_arr['super_admin'] ) ) {
        $membersa[0] = $post_meta_arr['super_admin'];
    }

    if ( isset( $post_meta_arr['admins'] ) ) {
        if ( is_array( $post_meta_arr['admins'] ) ) {
            foreach( $post_meta_arr['admins'] as $item ){
                $membersu[] = $item;
            }
        } elseif ( ! empty( $post_meta_arr['admins'] ) ) {
            $membersu[] = $post_meta_arr['admins'];
        }
    }

    if ( isset( $post_meta_arr['users']['active'] ) ) {
        if ( is_array( $post_meta_arr['users']['active'] ) ) {
            foreach( $post_meta_arr['users']['active'] as $item ) {
                $membersu[] = $item;
            }
        } elseif ( ! empty( $post_meta_arr['users']['active'] ) ) {
            $membersu[] = $post_meta_arr['users']['active'];
        }
    }

    $members = array_merge($membersa,$membersu);
    end($members);
    $last_key = key($members);
    reset($members);

    $members_count = count($members);
    $members_per_page = 20;

    if ($members_count==0) {return false;}

    include_once "addons/pagination/pagination.class.php";
    $p = new Arcane_Pagination;
    $p->items($members_count);
    $p->limit($members_per_page);
    $p->parameterName('members_list_page');
    $p->currentPage($currentPage);
    $p->nextLabel(esc_html__('Next','arcane'));
    $p->prevLabel(esc_html__('Previous','arcane'));
    ?>

    <?php if(arcane_is_admin($post_id,$current_user->ID)){ ?>
    <ul id="members-list-fn" class="item-list">
    <?php }else{ ?>
     <ul id="members-list-fn" class="item-list third_user" >
    <?php } ?>
    <?php
        for ($x=0; $x<$members_per_page; $x++) {
            $position = (int) ($currentPage-1) * $members_per_page + $x;

            $member = get_userdata($members[$position]);

            arcane_team_members_links($member,$post_meta_arr, $post_id, $su_admin);

            if ( $position >= $last_key ) {break;}
        }
    ?>


    </ul>
 <div class="clear"></div>
    <?php  if ($members_count > $members_per_page) : ?>
    <div id="pag-bottom" class="pagination">
            <?php $p->show(); ?>
    </div>
   <?php  endif ; ?>
<?php }
}

if(!function_exists('arcane_team_members_match_page')){
function arcane_team_members_match_page($post_id=false){
    global $current_user;

    $membersa = [];
    $membersu = [];

    $post_meta_arr = get_post_meta( $post_id, 'team', true );


    $su_admin = ( isset( $post_meta_arr['super_admin'] ) && $post_meta_arr['super_admin'] == $current_user->ID ) ? true : false;
    if ( $su_admin || ( ( isset( $post_meta_arr['admins'] ) && is_array( $post_meta_arr['admins'] ) && in_array( $current_user->ID, $post_meta_arr['admins'] ) ) || ( isset( $post_meta_arr['admins'] ) && $current_user->ID == $post_meta_arr['admins'] ) ) ) {

        if(!isset($post_meta_arr['users']['pending']))  {$post_meta_arr['users']['pending'] = [];}
        if ( is_array( $post_meta_arr['users']['pending'] ) ) {
            foreach( $post_meta_arr['users']['pending'] as $item ) {
                $membersu[] = $item;
            }
        } elseif ( ! empty( $post_meta_arr['users']['pending'] ) ) {
            $membersu[] = $post_meta_arr['users']['pending'];
        }
    }

    if ( isset( $post_meta_arr['super_admin'] ) ) {
        $membersa[0] = $post_meta_arr['super_admin'];
    }

    if ( isset( $post_meta_arr['admins'] ) ) {
        if ( is_array( $post_meta_arr['admins'] ) ) {
            foreach( $post_meta_arr['admins'] as $item ){
                $membersu[] = $item;
            }
        } elseif ( ! empty( $post_meta_arr['admins'] ) ) {
            $membersu[] = $post_meta_arr['admins'];
        }
    }

    if ( isset( $post_meta_arr['users']['active'] ) ) {
        if ( is_array( $post_meta_arr['users']['active'] ) ) {
            foreach( $post_meta_arr['users']['active'] as $item ) {
                $membersu[] = $item;
            }
        } elseif ( ! empty( $post_meta_arr['users']['active'] ) ) {
            $membersu[] = $post_meta_arr['users']['active'];
        }
    }

    $members = array_merge($membersa,$membersu); ?>
     <div class="mtmembers">
         <?php foreach ($members as $member){
             $author_obj = get_user_by('id', $member); ?>
            <a href="<?php echo bp_core_get_user_domain($member); ?>" data-tooltip="<?php echo esc_attr($author_obj->display_name); ?>">
                <img alt="player_img" src="<?php echo arcane_return_player_image_fn($member, 100, 100); ?>" />
            </a>
         <?php } ?>
    </div>
<?php }
}

if(!function_exists('arcane_team_members_links')){
function arcane_team_members_links($member, $post_meta_arr, $post_id, $su_admin){ global $current_user; ?>

    <?php if ( isset( $post_meta_arr['users']['pending'] ) && ( ( is_array( $post_meta_arr['users']['pending'] ) && in_array( $member->ID, $post_meta_arr['users']['pending'] ) ) || $member->ID == $post_meta_arr['users']['pending'] ) ) : ?>
        <li class="pending <?php echo esc_attr($member->ID); ?>">
    <?php else : ?>
           <?php if($member->ID == $current_user->ID || ( isset( $post_meta_arr['super_admin'] ) && $member->ID == $post_meta_arr['super_admin'] ) ){ ?>

           <li class="<?php echo esc_attr($member->ID); ?> third_user">

            <?php }else{ ?>
           <li class="<?php echo esc_attr($member->ID); ?>">
            <?php } ?>
    <?php endif;?>

        <?php    $url = get_user_meta($member->ID, 'profile_photo', true);
                 $url1 = get_user_meta($member->ID, 'wsl_current_user_image', true);
                 if(empty($url)) {$url = $url1;}
                 if(empty($url) && empty($url1)){ $url = get_theme_file_uri('img/defaults/default_profile55x55.png'); }

         ?>
        <div class="member-list-wrapper">
            <div class="item-avatar">
               <a href="<?php echo esc_url(bp_core_get_user_domain( $member->ID )); ?>">
                <img alt="img" src="<?php echo esc_url($url); ?>" class="avatar">
               </a>
            </div>

            <div class="item">
                <div class="item-title">
                    <a href="<?php echo esc_attr(bp_core_get_user_domain( $member->ID )); ?>"><?php echo esc_attr($member->display_name); ?></a>
                    <div class="item-meta">
                        <span class="activity"><?php esc_html_e("Joined: ","arcane"); ?>
                            <?php

                            $timovi = get_user_meta($member->ID, 'team_post_id');

                            if(isset($post_id) && is_array($timovi)){
                                $index = array_search($post_id, array_column($timovi, 0));
                                echo esc_attr(date("M, Y", $timovi[$index][1]));
                            }

                            ?>
                        </span></div>
                </div>
            </div>
            <?php if(!is_array($post_meta_arr['admins'])) {$post_meta_arr['admins']= [];} ?>
            <?php if(isset($post_meta_arr['admins']) && in_array($member->ID, $post_meta_arr['admins']) or ($member->ID == $post_meta_arr['super_admin'])):?>
                <div class="is-admin" data-original-title="Admin" data-toggle="tooltip" ><i class="fa-star fas"></i></div>
            <?php endif;?>
            <?php if(isset($post_meta_arr['users']['pending'])){ ?>
                <?php if(in_array($member->ID, $post_meta_arr['users']['pending'])):?>
                    <div class="pending-text"><?php esc_html_e("Pending","arcane"); ?></div>
                <?php endif;?>
            <?php } ?>

            <div class="clear"></div>
        </div>

    <div class="member-list-more">

    <?php if($su_admin): ?>

        <?php /* SUPER ADMIN BEGIN */?>
        <?php if(!isset($post_meta_arr['admins'])){$post_meta_arr['admins'] = [];}if(!isset($post_meta_arr['users']['active'])){$post_meta_arr['users']['active'] = [];} ?>
        <?php if( ( isset($post_meta_arr['admins']) && in_array($member->ID, $post_meta_arr['admins'])) || (isset($post_meta_arr['users']['active']) && in_array($member->ID, $post_meta_arr['users']['active'])) ):?>
            <div class="mlm1">
                <a class="ajaxloadremoveadmin" href="javascript:void(0);" data-req="remove_friend_admin" data-pid="<?php echo esc_attr($post_id); ?>" data-uid="<?php echo esc_attr($member->ID); ?>">
                    <i class="fas fa-times"></i> <?php esc_html_e('Remove user','arcane');?>
                </a>
            </div>
        <?php endif;?>

        <?php if(isset($post_meta_arr['users']['active']) && in_array($member->ID, $post_meta_arr['users']['active'])):?>
            <div class="mlm2 u">
                <a class="ajaxloadmakeadmin" href="javascript:void(0);" data-req="make_administrator" data-pid="<?php echo esc_attr($post_id); ?>" data-uid="<?php echo esc_attr($member->ID); ?>">
                    <i class="fas fa-chevron-up"></i> <?php esc_html_e('Make administrator','arcane');?>
                </a>
            </div>
        <?php elseif(isset($post_meta_arr['admins']) && in_array($member->ID, $post_meta_arr['admins'])): ?>
            <div class="mlm2">
                <a class="ajaxloaddowngrade" href="javascript:void(0);" data-req="downgrade_to_user" data-pid="<?php echo esc_attr($post_id); ?>" data-uid="<?php echo esc_attr($member->ID); ?>">
                    <i class="fas fa-chevron-down"></i> <?php esc_html_e('Downgrade to user','arcane');?>
                </a>
            </div>
        <?php endif;?>

        <?php if(isset($post_meta_arr['users']['pending']) && in_array($member->ID, $post_meta_arr['users']['pending'])):?>
            <div class="mlm1 mj"><?php esc_html_e('Let this member join?','arcane');?>
                <a class="ajaxloadletjoin" href="javascript:void(0);" data-req="let_this_member_join" data-pid="<?php echo esc_attr($post_id); ?>" data-uid="<?php echo esc_attr($member->ID); ?>"><i class="fas fa-check"></i></a>
                <a class="ajaxloadremoveadmin" href="javascript:void(0);" data-req="remove_friend_admin" data-pid="<?php echo esc_attr($post_id); ?>" data-uid="<?php echo esc_attr($member->ID); ?>"><i class="fas fa-times"></i></a>
            </div>
        <?php endif;?>
        <?php /* SUPER ADMIN END */?>



    <?php elseif(!$su_admin && isset($post_meta_arr['admins']) && in_array($current_user->ID, $post_meta_arr['admins']) ):?>

        <?php /* ADMIN BEGIN */?>
        <?php if(isset($post_meta_arr['users']['active']) && in_array($member->ID, $post_meta_arr['users']['active']) ):?>
            <div class="mlm1">
                <a class="ajaxloadremoveadmin" href="javascript:void(0);" data-req="remove_friend_admin" data-pid="<?php echo esc_attr($post_id); ?>" data-uid="<?php echo esc_attr($member->ID); ?>">
                    <i class="fas fa-times"></i> <?php esc_html_e('Remove user','arcane');?>
                </a>
            </div>
            <div class="mlm2 u">
                <a class="ajaxloadmakeadmin" href="javascript:void(0);" data-req="make_administrator" data-pid="<?php echo esc_attr($post_id); ?>" data-uid="<?php echo esc_attr($member->ID); ?>">
                    <i class="fas fa-chevron-up"></i> <?php esc_html_e('Make administrator','arcane');?>
                </a>
            </div>
        <?php endif;?>


        <?php if(isset($post_meta_arr['users']['pending']) && in_array($member->ID, $post_meta_arr['users']['pending'])):?>
            <div class="mlm1 mj"><?php esc_html_e('Let this member join?','arcane');?>
                <a class="ajaxloadletjoin" href="javascript:void(0);" data-req="let_this_member_join" data-pid="<?php echo esc_attr($post_id); ?>" data-uid="<?php echo esc_attr($member->ID); ?>"><i class="fas fa-check"></i></a>
                <a class="ajaxloadremoveadmin" href="javascript:void(0);" data-req="remove_friend_admin" data-pid="<?php echo esc_attr($post_id); ?>" data-uid="<?php echo esc_attr($member->ID); ?>"><i class="fas fa-times"></i></a>
            </div>
        <?php endif;?>
        <?php /* ADMIN END */?>


    <?php endif;?>
    </div>
    </li>
<?php }
}

if(!function_exists('arcane_is_member')){
function arcane_is_member($post_id=false, $user_id=false){
    if ($post_id==false && $user_id==false) {return false;}

    $usr_meta = get_user_meta( $user_id, 'team_post_id');
    if(isset($var) && !is_array($var)){$usr_meta = [];}
    $is_member = (arcane_is_val_exists($post_id,$usr_meta)) ? true : false;
    return $is_member;
}
}

if(!function_exists('arcane_is_member_of_any_team')){
function arcane_is_member_of_any_team($user_id=false){
    if ($user_id==false) {return false;}
    $usr_meta = get_user_meta( $user_id, 'team_post_id');
    foreach ($usr_meta as $usr_m) {
        if(arcane_is_super_admin($usr_m[0],$user_id) or arcane_is_user($usr_m[0],$user_id) or arcane_is_admin($usr_m[0],$user_id)){
            $teamje = true;
        }
    }
    if(!isset($teamje)) {$teamje = false;}
    $is_member = $teamje ? true : false;
    return $is_member;
}
}


if(!function_exists('arcane_is_admin_of_any_team')){
function arcane_is_admin_of_any_team($user_id=false){
    if ($user_id==false) {return false;}
    $usr_meta = get_user_meta( $user_id, 'team_post_id');
    foreach ($usr_meta as $usr_m) {
        if(arcane_is_super_admin($usr_m[0],$user_id) or arcane_is_admin($usr_m[0],$user_id)){
            $teamje = true;
        }
    }
    if(!isset($teamje)) {$teamje = false;}
    $is_member = $teamje ? true : false;
    return $is_member;
}
}

if(!function_exists('arcane_is_pending_member')){
function arcane_is_pending_member($post_id=false, $user_id=false){
    if ($post_id==false && $user_id==false) {return false;}
    $post_meta_arr = get_post_meta( $post_id, 'team', true );
    if( ( ! isset( $post_meta_arr['users'] ) ) || ( ! isset( $post_meta_arr['users']['pending'] ) ) || $post_meta_arr['users']['pending'] === null) {
        $is_member = false;
    } else if ( isset( $post_meta_arr['users']['pending'] ) && is_array( $post_meta_arr['users']['pending'] ) ) {
        $is_member = (in_array($user_id, $post_meta_arr['users']['pending'])) ? true : false;
    } else {
        $is_member = false;
    }
    return $is_member;
}
}

if(!function_exists('arcane_is_user')){
function arcane_is_user($post_id=false, $user_id=false){
    if ($post_id==false && $user_id==false) {return false;}
    if (in_array(arcane_membership_status($post_id,$user_id), array('user') ) ) {return true;}
    else {return false;}
}
}

if(!function_exists('arcane_is_super_admin')){
function arcane_is_super_admin($post_id=false, $user_id=false){
    if ($post_id==false && $user_id==false) {return false;}
    if (in_array(arcane_membership_status($post_id,$user_id), array('super_admin') ) ) {return true;}
    else {return false;}
}
}

if(!function_exists('arcane_return_super_admin')){
function arcane_return_super_admin($post_id=false){
    $post_meta_arr = get_post_meta( $post_id, 'team', true );

    if(isset($post_meta_arr['super_admin'])){
        return $post_meta_arr['super_admin'];
    }else{
        return false;
    }
}
}

if(!function_exists('arcane_is_admin')){
function arcane_is_admin($post_id=false, $user_id=false){
    if ($post_id==false && $user_id==false) {return false;}
    if (in_array(arcane_membership_status($post_id,$user_id), array('super_admin', 'admin') ) )
    {return true;}
    else {return false;}
}
}

if(!function_exists('arcane_membership_status')){
function arcane_membership_status($post_id=false, $user_id=false){
    if ($post_id==false && $user_id==false) {return false;}
    if ($post_id && $user_id) {
        $post_meta_arr = get_post_meta($post_id, 'team', true);

        if ($post_meta_arr=='') {return false;}
        if($post_meta_arr['super_admin']==$user_id) {return 'super_admin';}

        if(!isset($post_meta_arr['admins'])){$post_meta_arr['admins'] = [];}
        if(!is_array($post_meta_arr['admins'])) {$post_meta_arr['admins'] = (array) $post_meta_arr['admins'];}
        if(in_array($user_id, $post_meta_arr['admins'])) {return 'admin';}

        if(!isset($post_meta_arr['users']['active'])){$post_meta_arr['users']['active'] = [];}
        if(!is_array($post_meta_arr['users']['active'])) {$post_meta_arr['users']['active'] = (array) $post_meta_arr['users']['active'];}
        if(in_array($user_id, $post_meta_arr['users']['active'])) {return 'user';}

        if(!isset($post_meta_arr['users']['pending'])){$post_meta_arr['users']['pending'] = [];}
        if(!is_array($post_meta_arr['users']['pending'])) {$post_meta_arr['users']['pending'] = (array) $post_meta_arr['users']['pending'];}
        if (isset($post_meta_arr['users']['pending']) and (in_array($user_id, $post_meta_arr['users']['pending']))) {return 'user_pending';}

    } else {return false;}

}
}

if(!function_exists('arcane_games_for_postid')){
function arcane_games_for_postid ($pid) { return get_post_meta( $pid, 'games'); }
}

if(!function_exists('arcane_get_user_teams')){
function arcane_get_user_teams($user_id){
  if ($user_id == 0) {
    return [];
  }
    $teams = get_user_meta( $user_id, 'team_post_id');

    $active_teams = [];
    if(!empty($teams)){
    foreach ($teams as $team) {
        if((arcane_is_user($team[0], $user_id) or arcane_is_super_admin($team[0], $user_id) or arcane_is_admin($team[0], $user_id)) && (get_post_status($team[0]) == 'publish' || get_post_status($team[0]) == 'draft' ) ){
            $active_teams[]=$team[0];
        }
    }
    }else{
        return false;
    }
    return $active_teams;
}
}

if(!function_exists('arcane_get_user_teams_all')){
function arcane_get_user_teams_all($user_id){
  if ($user_id == 0) {
    return [];
  }
    $teams = get_user_meta( $user_id, 'team_post_id');

    $active_teams = [];
    if(!empty($teams)){
    foreach ($teams as $team) {
        if((arcane_is_user($team[0], $user_id) or arcane_is_super_admin($team[0], $user_id) or arcane_is_admin($team[0], $user_id))){
            $active_teams[]=$team[0];
        }
    }
    }else{
        return false;
    }
    return $active_teams;
}
}

if(!function_exists('arcane_get_user_teams_for_challenge')){
function arcane_get_user_teams_for_challenge($user_id){
  if ($user_id == 0) {
    return [];
  }
    $teams = get_user_meta( $user_id, 'team_post_id');

    $active_teams = [];
    if(!empty($teams)){
    foreach ($teams as $team) {
        if((arcane_is_super_admin($team[0], $user_id) or arcane_is_admin($team[0], $user_id)) && get_post_status($team[0]) == 'publish' ){
            $active_teams[]=$team[0];
        }
    }
    }else{
        return [];
    }
    return $active_teams;
}
}

if(!function_exists('arcane_return_team_image')){
function arcane_return_team_image($team_id){
    $imag = get_post_meta ((int)$team_id, 'team_photo', true);

    if(!empty($imag)){
     $image = arcane_aq_resize( $imag, 210, 178, true, true, true ); //resize & crop img
    }

    if(empty($image)){ $image = get_theme_file_uri('img/defaults/default-team.jpg');  }
    return $image;
}
}

if(!function_exists('arcane_return_team_image_big')){
function arcane_return_team_image_big($team_id){

  $imag = get_post_meta ((int)$team_id, 'team_photo', true);

    if(isset($imag)){
     $image = arcane_aq_resize( $imag, 210, 178, true, true, true ); //resize & crop img
    }

  if(empty($image)){ $image = get_theme_file_uri('img/defaults/default-team.jpg');  }
  return $image;
}
}

if(!function_exists('arcane_return_team_name_by_team_id')){
function arcane_return_team_name_by_team_id($team_id){
    return get_the_title($team_id);
}
}


if(!function_exists('arcane_return_post_id_by_team_id')){
function arcane_return_post_id_by_team_id($team_id){
  return $team_id;
}
}

if(!function_exists('arcane_return_number_of_members')){
function arcane_return_number_of_members($team_id){
    $members = get_post_meta( $team_id, 'team', true );
    $ad = ( is_array( $members ) && isset( $members['admins'] ) && is_array( $members['admins'] )  ) ? $members['admins'] : [];
    $us = ( is_array( $members ) && isset( $members['users'] ) && is_array( $members['users'] ) && isset( $members['users']['active'] ) ) ? $members['users']['active'] : [];
    $total = count($ad) + count($us) + 1;
    return  $total;
}
}

if(!function_exists('arcane_return_number_of_team_admin')){
function arcane_return_number_of_team_admin(){
    global $post;
    $c_id =get_current_user_id();
    $teams = [];
    $myteams = arcane_get_user_teams($c_id);
    if (is_array($myteams) and (!empty($myteams))) {
      $gid = 0;
      $tgame = get_post_meta($post->ID, 'tournament_game', true);
      if(isset($tgame))
      {$gid = arcane_return_game_id_by_game_name($tgame);}

      foreach ($myteams as $team) {
        $tim_post = get_post($team);
        $tim_games = get_post_meta($tim_post->ID, 'games', true);
        $tim_members = get_post_meta($tim_post->ID, 'team', true);

        $key = false;
        if(isset($tim_members) && is_array($tim_members))
        {$key = array_search($c_id, array_map('intval',$tim_members['admins']));}
        if(!isset($tim_games) or empty($tim_games)){$tim_games = [];}
        if (((intval($tim_post->post_author) == intval($c_id)) || (is_numeric($key)) ) && in_array($gid, $tim_games)) {
          array_push($teams, $tim_post->ID);
        }
      }

    }

    return count($teams);
}
}

/*OTHER CW FUNCTIONS*/

if(!function_exists('arcane_delete_usermeta_on_team_delete')){
function arcane_delete_usermeta_on_team_delete($post_id){
     global $wpdb;
     $usermeta = $wpdb->prefix."usermeta";
     $wpdb->query($wpdb->prepare("DELETE FROM $usermeta WHERE `meta_key` = 'team_post_id' && `meta_value` LIKE %s;", '%i:'.$wpdb->esc_like($post_id).';%'));
}
}


if(!function_exists('arcane_return_map_pic')){
function arcane_return_map_pic($map_id){
     global $wpdb;
     $maps = $wpdb->prefix."cw_maps";
     $rslt = $wpdb->get_results($wpdb->prepare("SELECT `screenshot` FROM $maps where `id` = %s", $map_id));
     if(isset($rslt[0])){
     $thumb = $rslt[0]->screenshot;

     $img_url = wp_get_attachment_url( $thumb);

          if(!$img_url)
            {return  get_theme_file_uri('img/defaults/mapdef.jpg');}

     return $img_url;
     }else{
     return  get_theme_file_uri('img/defaults/mapdef.jpg');
     }
}
}

if(!function_exists('arcane_return_map_title')){
function arcane_return_map_title($map_id){
     global $wpdb;
     $maps = $wpdb->prefix."cw_maps";
     $rslt = $wpdb->get_results($wpdb->prepare("SELECT `title` FROM $maps where `id` = %s", $map_id));

     if(isset($rslt[0])){
        return $rslt[0]->title;
     }else{
        return esc_html__('No title', 'arcane');
     }
}
}


if(!function_exists('arcane_return_game_image')){
function arcane_return_game_image($game_id){
    global $wpdb;
    $games = $wpdb->prefix."cw_games";
    $game_img = $wpdb->get_results($wpdb->prepare("SELECT `icon` FROM $games WHERE `id`= %s", $game_id));

    if(!empty($game_img)){
    $img_url = wp_get_attachment_url( $game_img[0]->icon); //get img URL
    $image = arcane_aq_resize( $img_url, 175, 200, true, true, true ); //resize & crop img
    }
    if(empty($image)){ $image = get_theme_file_uri('img/defaults/gamedefault.jpg');  }
    return $image;

}
}

if(!function_exists('arcane_return_game_id_by_game_name')){
function arcane_return_game_id_by_game_name($game_name){
    global $wpdb;
    $games = $wpdb->prefix."cw_games";

    $game_name = $wpdb->get_results($wpdb->prepare("SELECT `id` FROM $games WHERE `title`= %s",$game_name));

    if(isset($game_name[0])){
         return $game_name[0]->id;
    }else{
         return '';
    }

}
}

if(!function_exists('arcane_return_game_abbr_by_game_id')){
function arcane_return_game_abbr_by_game_id($game){
    global $wpdb;
    $games = $wpdb->prefix."cw_games";

    $game_abbr = $wpdb->get_results($wpdb->prepare("SELECT `abbr` FROM $games WHERE `id`= %s",$game));

    if(isset($game_abbr[0])){
         return $game_abbr[0]->abbr;
    }else{
         return '';
    }

}
}

if(!function_exists('arcane_return_game_name_by_game_id')){
function arcane_return_game_name_by_game_id($gid){
    global $wpdb;
    $games = $wpdb->prefix."cw_games";

    $game_name = $wpdb->get_results($wpdb->prepare("SELECT `title` FROM $games WHERE `id`= %s",$gid));

    if(isset($game_name[0])){
         return $game_name[0]->title;
    }else{
         return '';
    }

}
}



if(!function_exists('arcane_return_game_abbr')){
function arcane_return_game_abbr($game_id){
    global $wpdb;
    $games = $wpdb->prefix."cw_games";
    $game_abbr = $wpdb->get_results($wpdb->prepare("SELECT abbr FROM $games WHERE `id`= %s",$game_id));

    if(!empty($game_abbr)){
        return $game_abbr[0]->abbr;
    }
     return false;
}
}


if(!function_exists('arcane_return_game_banner')){
function arcane_return_game_banner($game_id){
    global $wpdb;
    $games = $wpdb->prefix."cw_games";
    $game_img = $wpdb->get_results($wpdb->prepare("SELECT g_banner_file FROM $games WHERE `id`= %s",$game_id));

     if(!empty($game_img)){
    $thumb = $game_img[0]->g_banner_file;
    $img_url = wp_get_attachment_url( $thumb); //get img URL
    $image = arcane_aq_resize( $img_url, 1168, 230, true, true, true ); //resize & crop img
    }
    if(empty($image)){ $image = get_theme_file_uri('img/defaults/gamebanner.jpg');  }
    return $image;

}
}

/**
 * Return game image
 *
 * @param $game_id
 *
 * @return false|string
 */
if ( ! function_exists( 'arcane_return_game_banner_nocrop' ) ) {
    function arcane_return_game_banner_nocrop($game_id){
         global $wpdb;
        $games = $wpdb->prefix."cw_games";
        $game_img = $wpdb->get_results($wpdb->prepare("SELECT g_banner_file FROM $games WHERE `id`= %s",$game_id));

         if(!empty($game_img)){
            $thumb = $game_img[0]->g_banner_file;
            $image = wp_get_attachment_url( $thumb); //get img URL
        }
        if(empty($image)){ $image = get_theme_file_uri('img/defaults/gamebanner.jpg');  }
        return $image;

    }
}

if(!function_exists('arcane_mutual_games_inter')){

function arcane_mutual_games_inter($team1, $team2){
    $games1 = arcane_games_for_postid($team1);
    $games2 = arcane_games_for_postid($team2);
    $ArcaneWpTeamWars = new Arcane_TeamWars();
    if( isset($games1[0]) && is_array($games1[0]) && isset($games2[0]) && is_array($games2[0]) ){
        foreach($games1[0] as $item) {
            if (in_array($item, $games2[0])) {
                $game = $ArcaneWpTeamWars->get_game(array('id'=>$item));
                $ret[$item]=$game[0]->title;
            }
        }
        if(empty($ret)){ ?>
            <option value=""><?php esc_html_e('No mutual games', 'arcane'); ?></option>
        <?php }else{
        foreach($ret as $key=>$val): ?>
            <option value="<?php echo esc_attr($key);?>"><?php echo esc_attr($val);?></option>
        <?php endforeach; }
    }else{ ?>
         <option value=""><?php esc_html_e('No mutual games', 'arcane'); ?></option>
   <?php }
}
}


if(!function_exists('arcane_team_members_ajax')){
function arcane_team_members_ajax(){

    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }

    $_POST['page'] = str_replace('?','', $_POST['page']);
    parse_str($_POST['page'], $page);
    arcane_team_members($_POST['pid'], $page['members_list_page'] );
    die();
}
}





if(!function_exists('arcane_team_delete')){
function arcane_team_delete(){

    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }

        $post_id = filter_var($_POST['pid'], FILTER_SANITIZE_NUMBER_INT);
        if ($post_id > 0) {
          $post = get_post($post_id);
          if (empty($post)) {
            die();
          }
          $cid = get_current_user_id();
          if (($post->post_author == $cid) or current_user_can( 'manage_options' ))  {
            $post_meta_arr = get_post_meta( $post_id, 'team', true );
            if (isset($post_meta_arr['users']['active'])) {
              if (is_array($post_meta_arr['users']['active'])) {
                foreach ($post_meta_arr['users']['active'] as $single) {
                  ArcaneUpdateUserTournaments($single);
                }
              }
            }
            arcane_remove_team_from_tournaments($post_id);
            arcane_delete_usermeta_on_team_delete($post_id);
            wp_delete_post($post_id);
          }
        }
     //updatetourneys
die();
}
}

if(!function_exists('arcane_match_delete_single')){
function arcane_match_delete_single(){
    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }

        global $ArcaneWpTeamWars;
        $mid = filter_var($_POST['mid'], FILTER_SANITIZE_NUMBER_INT);
        $cid = get_current_user_id();
        $team_id = 0;

        if (arcane_is_user_in_game($mid, $cid) or  current_user_can( 'manage_options' )) {


        $match = $ArcaneWpTeamWars->get_match(array('id' => $mid));

        $team1_id = $match->team1;
        $team2_id = $match->team2;

        if(arcane_is_member($team1_id,$cid)){$team = 'deleted_request_team1';$team_id = $team2_id; }
        if(arcane_is_member($team2_id,$cid)){$team = 'deleted_request_team2';$team_id = $team1_id; }
        $previous_status = get_post_meta($mid, 'status', true);
        update_post_meta($mid, 'status_backup', $previous_status);
        $p = array('status' => $team);
        $ArcaneWpTeamWars->update_match($mid, $p);
        $other_user = arcane_return_super_admin($team_id);
        arcane_delete_sent($other_user,$mid);
        }
        die();
}
}


if(!function_exists('arcane_match_delete')){
function arcane_match_delete(){

    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
        global $ArcaneWpTeamWars;
        $mid = filter_var($_POST['mid'], FILTER_SANITIZE_NUMBER_INT);
        $req = filter_var($_POST['req'], FILTER_SANITIZE_STRING);
        $cid = get_current_user_id();
        $team_id = 0;

        if (arcane_is_user_in_game($mid, $cid) or  current_user_can( 'manage_options' )) {

         $data = [];

         $match = $ArcaneWpTeamWars->get_match(array('id' => $mid));

         $team1_id = $match->team1;
         $team2_id = $match->team2;
         if(arcane_is_member($team1_id,$cid)){$team_id = $team2_id; }
         if(arcane_is_member($team2_id,$cid)){$team_id = $team1_id; }
         $notify_user = arcane_return_super_admin($team_id);

          if($req=="accept_delete"){
             $data[0] = $mid;
             $data[1] = 'accepted';
             echo json_encode($data);
             arcane_delete_accepted($notify_user,$mid );
             $ArcaneWpTeamWars->delete_match($mid);

          }

          if($req=="reject_delete"){
             $data[0] = $mid;
             $data[1] = 'rejected';
             echo json_encode($data);
             $previous_status = get_post_meta($mid, 'status_backup', true);
             $p = array('status' => $previous_status);
             $ArcaneWpTeamWars->update_match($mid, $p);
             arcane_delete_rejected($notify_user,$mid );
          }


        }
        die();
}
}

if(!function_exists('arcane_match_delete_confirmation')){
function arcane_match_delete_confirmation(){

    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }

        global $ArcaneWpTeamWars;
        $match_id = filter_var($_POST['mid'],FILTER_SANITIZE_NUMBER_INT);
        $req = filter_var($_POST['req'], FILTER_SANITIZE_STRING);
        $cid = get_current_user_id();
        $team_id = 0;

        if (arcane_is_user_in_game($match_id, $cid) or  current_user_can( 'manage_options' )) {

         $match = $ArcaneWpTeamWars->get_match(array('id' => $match_id));

         $team1_id = $match->team1;
         $team2_id = $match->team2;
         if(arcane_is_member($team1_id,$cid)){$team_id = $team2_id; }
         if(arcane_is_member($team2_id,$cid)){$team_id = $team1_id; }
         $notify_user = arcane_return_super_admin($team_id);

          if($req=="accept_delete_request"){
              $data = 'accepted';
              echo json_encode($data);
              arcane_delete_accepted($notify_user,$match_id );
              $ArcaneWpTeamWars->delete_match($match_id);

          }

          if($req=="reject_delete_request"){
             $data = 'rejected';
             echo json_encode($data);
             $previous_status = get_post_meta($match_id, 'status_backup', true);
             $p = array('status' => $previous_status);
             $ArcaneWpTeamWars->update_match($match_id, $p);
             arcane_delete_rejected($notify_user,$match_id );
          }
    die();
    }
}
}

if(!function_exists('arcane_mutual_games')){
function arcane_mutual_games(){

    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }

    if( $_POST['team1']=='' || $_POST['team2']=='' ) {die();}
    arcane_mutual_games_inter(filter_var($_POST['team1'], FILTER_SANITIZE_NUMBER_INT), filter_var($_POST['team2'], FILTER_SANITIZE_NUMBER_INT));
    die();
}
}


if(!function_exists('arcane_change_membership_remove_friend_admin_by_id')){
function arcane_change_membership_remove_friend_admin_by_id($uid, $pid ){

    $post_meta_arr = get_post_meta($pid, 'team', true );

    if(isset($post_meta_arr['admins'])){
        $key = array_search($uid,$post_meta_arr['admins']);
        if($key!==false) {unset($post_meta_arr['admins'][$key]);}
    }

    if(isset($post_meta_arr['users']['active'])){
        $key = array_search($uid,$post_meta_arr['users']['active']);
        if($key!==false) {unset($post_meta_arr['users']['active'][$key]);}
    }

    if(isset($post_meta_arr['users']['pending'])){
        $key = array_search($uid,$post_meta_arr['users']['pending']);
        if($key!==false) {unset($post_meta_arr['users']['pending'][$key]);}
    }

    $timovi = get_user_meta($uid, 'team_post_id');

    if(arcane_is_val_exists($pid, $timovi)){
        $index = array_search($pid, array_column($timovi, 0));
        delete_user_meta($uid, 'team_post_id', $timovi[$index]);
    }

    update_post_meta($pid, 'team', $post_meta_arr);

}
}

/*remove user from team on delete*/
if(!function_exists('arcane_remove_user_from_team_on_delete')){
function arcane_remove_user_from_team_on_delete( $user_id ) {
    $teams = get_user_meta($user_id,'team_post_id');

    foreach ($teams as $team) {
        $number_of_members = arcane_return_number_of_members($team[0]);
        if($number_of_members == 1){
            arcane_onteam_delete($team[0]);
        }
        arcane_change_membership_remove_friend_admin_by_id($user_id, $team[0]);
    }
}
}

if(!function_exists('arcane_remove_team_from_tournaments')){
function arcane_remove_team_from_tournaments($team_id){
    $posts_array = get_posts( array('posts_per_page'   => -1,'post_type' => 'tournament') );

    $uid = $team_id;

    foreach ($posts_array as $post_single) {

        $tid = $post_single->ID;
        $competitors = get_post_meta($tid, 'tournament_competitors', true);

                if (isset($competitors[$uid])) {

                    $delete_competitors = get_post_meta($tid, 'tournament_delete_competitors', true);
                    if (!is_array($delete_competitors)) {
                        $delete_competitors = [];
                    }
                    $delete_competitors[$uid] = $uid;
                    update_post_meta($tid, 'tournament_delete_competitors', $delete_competitors);

                    unset($competitors[$uid]);
                    update_post_meta($tid, 'tournament_competitors', $competitors);

                    $all = false;
                    $format = get_post_meta($tid, 'format', true);
                    if((strtolower($format) == 'league') || ($format == 'rrobin')) {
                        $all = 1;
                    }
                    arcane_update_match_ut($uid, $tid, $all );

                    if(strtolower($format) == 'ladder') {
                        $participants = get_post_meta($tid, 'participant_cache', true);
                        if($participants) {
                            $del = false;
                            foreach ($participants as $key => $one) {
                                if ($one['id'] == $uid) {
                                    unset($participants[$key]);
                                    $del = true;
                                }
                            }
                            if($del) {
                                $participants = array_values($participants);
                                array_unshift($participants,"");
                                unset($participants[0]);
                                update_post_meta($tid, 'participant_cache', $participants);
                            }
                        }
                    }
                }
    }
}
}


if(!function_exists('arcane_set_custom_edit_tournament_columns')){
function arcane_set_custom_edit_tournament_columns(){
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => esc_html__( "Title", 'arcane' ),
        "author" => esc_html__( "Author", 'arcane' ),

        "format" => esc_html__( "Tournament format", 'arcane' ),
         "id" => esc_html__( "Tournament ID", 'arcane' ),
        'date' => esc_html__( 'Date', 'arcane' ),
    );
    return $columns;
}
}

if(!function_exists('arcane_manage_tournament_columns')){
function arcane_manage_tournament_columns( $column, $post_id ) {
    global $post;
    switch( $column ) {
        case 'id' :
            echo esc_attr($post_id);
        break;
        case 'format' :
            $format = get_post_meta($post_id, 'format', true);
            echo esc_attr($format);
        break;
        /* Just break out of the switch statement for everything else. */
        default :
            break;
    }
}
}

if(!function_exists('arcane_is_user_in_game')){
function arcane_is_user_in_game ($gid, $uid) {
  global $ArcaneWpTeamWars;
  $game = $ArcaneWpTeamWars->get_match(array('id' => $gid));

  if ($game->tournament_participants == "team" or $game->tournament_participants == null)  {
    //team game
    $user_teams = arcane_get_user_teams($uid);
    foreach ($user_teams as $single) {
      if (($single == $game->team1) or  ($single == $game->team2)) {
        return true;
      }
    }
  } else {
    //user game

    if (($game->team1 == $uid) or ($game->team2 == $uid)) {
      return true;
    }
  }
  return false;
}
}



/******** force permalinks structure******/
add_action('after_setup_theme', 'arcane_force_permalinks');
if(!function_exists('arcane_force_permalinks')){
function arcane_force_permalinks(){
if (get_option('permalink_structure') != '/%postname%/') {
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
}
}
}


/***** limit media to logged in user *****/
if(!function_exists('arcane_restrict_media_library')){
function arcane_restrict_media_library( $wp_query_obj ) {
    global $current_user, $pagenow;
    if( !is_a( $current_user, 'WP_User') )
    {return;}
    if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
    {return;}
    if( !current_user_can('manage_media_library') )
    {if(!current_user_can('administrator')){
        $wp_query_obj->set('author', $current_user->ID );
    }}
    return;
}
 }

/**default landing tab bpress**/
define('BP_DEFAULT_COMPONENT', 'profile' );


/****************************************All teams page pagination and search**********************************************/
if(!function_exists('arcane_title_filter')){
function arcane_title_filter($where, &$wp_query){
    global $wpdb;

    if($search_term = $wp_query->get( 'team_name' )){
        $search_term = $wpdb->esc_like($search_term);
        $search_term = ' \'%' . $search_term . '%\'';
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE '.$search_term;
    }

    return $where;
}
}

if(!function_exists('arcane_all_teams_pagination')){
function arcane_all_teams_pagination(){
    global $post;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
          'post_type' => 'team',
          'orderby' => 'name',
          'order' => 'ASC',
          'showposts' => 1,
          'paged'  => $paged
          );

    if (isset($_POST['team_name']) && $_POST['team_name']!='') {
        $args['team_name'] = $_POST['team_name'];
        add_filter( 'posts_where', 'arcane_title_filter', 10, 2 );
        $the_query = new WP_Query($args);
        if(class_exists('Arcane_Types')) {arcane_title_filter_fix();}

    } else {
        $the_query = new WP_Query($args);
    }


    if ( $the_query->have_posts() ) { ?>

       <ul class="members-list item-list" >

       <?php while ( $the_query->have_posts() ) { $the_query->the_post(); ?>
            <li>
                    <div class="team-list-wrapper">
                        <div class="item-avatar">
                           <a href="<?php echo esc_url(get_permalink($post->ID)); ?> ">
                             <?php $img = get_post_meta( $post->ID, 'team_photo', true );
                                   $image = arcane_aq_resize( $img, 50, 50, true, true, true );
                                   if(empty($image)){
                                       $image = get_theme_file_uri('img/defaults/default-team-50x50.jpg');
                                   }
                                   ?>
                            <img alt="img" src="<?php echo esc_url($image); ?>" class="avatar" >
                           </a>
                        </div>

                        <div class="item">
                            <div class="item-title">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?> "> <?php if(strlen($post->post_title) > 25){ $dot= '...'; echo esc_attr( mb_substr($post->post_title, 0 ,25).$dot); }else{ echo esc_attr($post->post_title); } ?></a>
                                <div class="item-meta"><span class="activity">
                                    <?php $members = arcane_return_number_of_members($post->ID); ?>
            <?php if($members == 1){ echo esc_attr($members); ?>&nbsp;<?php esc_html_e('Member','arcane'); }else{ echo esc_attr($members); ?>&nbsp;<?php esc_html_e('Members','arcane'); } ?></span></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>

                </li>
       <?php } ?>

       </ul>
<div class="clear"></div>
    <?php } else { ?>
        <div class="error_msg"><span><?php  esc_html_e('There are no teams at the moment!', 'arcane'); ?> </span></div>
    <?php } ?>

    <?php wp_reset_postdata(); ?>

    <?php previous_posts_link('&laquo; ' . esc_html__('Previous page', 'arcane')) ?>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <?php next_posts_link(esc_html__('Next page', 'arcane') . ' &raquo;', $the_query->max_num_pages) ?>

<?php }
}

if(!function_exists('arcane_all_tournaments_pagination_v2_ajax')){
function arcane_all_tournaments_pagination_v2_ajax(){
    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
    $_POST['href'] = str_replace('?','', $_POST['href']);
    parse_str($_POST['href'], $_GET);
    arcane_all_tournaments_pagination_v2($_GET['page'], $_GET['term']);
    die();
}
}

if(!function_exists('arcane_all_tournaments_pagination_v2')){
function arcane_all_tournaments_pagination_v2($page=1, $term=''){
    global $wpdb, $ArcaneWpTeamWars;

    $post_per_page = 40;
    $offset = ($page -1) * $post_per_page;

    $query = "
    SELECT SQL_CALC_FOUND_ROWS *
    FROM {$wpdb->posts} WHERE 1=1
    AND post_type = 'tournament'
    AND post_name LIKE '%{$term}%'
    AND post_status = 'publish'
    ORDER BY post_name ASC
    LIMIT {$offset}, {$post_per_page}
    ";

    $results = $wpdb->get_results( $query);

    $res_count = (int) $wpdb->get_var( "SELECT FOUND_ROWS()" );

    include "addons/pagination/pagination.class.php";
    $p = new Arcane_Pagination;
    $p->items($res_count);
    $p->limit($post_per_page);

    $p->parameterName("page");
    $p->currentPage(isset($_GET['page'])?$_GET['page']:1);
    unset($_GET['page']);
    $_GET['term'] = $term;
    $p->target("?".http_build_query($_GET));

    $p->nextLabel(esc_html__('Next','arcane'));
    $p->prevLabel(esc_html__('Previous','arcane'));


    if ( $results ) { ?>

       <ul class="tournaments-list" >
       <?php foreach ( $results as $post ) {
            $games = $ArcaneWpTeamWars->get_game( '' );
            echo arcane_return_tournament_block( $post->ID, $games, false );
        } ?>
    </ul>
<div class="clear"></div>
    <?php } else { ?>
        <div class="error_msg"><span><?php esc_html_e('There are no tournaments at the moment!', 'arcane'); ?> </span></div>
    <?php } ?>


    <?php $p->show();

}
}


if(!function_exists('arcane_all_teams_pagination_v2_ajax')){
function arcane_all_teams_pagination_v2_ajax(){
    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
    $_POST['href'] = str_replace('?','', $_POST['href']);
    parse_str($_POST['href'], $_GET);
    arcane_all_teams_pagination_v2($_GET['page'], $_GET['term']);
    die();
}
}

if(!function_exists('arcane_all_teams_pagination_v2')){
function arcane_all_teams_pagination_v2($page=1, $term=''){
    global $wpdb;

    $post_per_page = 40;
    $offset = ($page -1) * $post_per_page;

    $query = "
    SELECT SQL_CALC_FOUND_ROWS *
    FROM {$wpdb->posts} WHERE 1=1
    AND post_type = 'team'
    AND post_name LIKE '%{$term}%'
    AND (post_status = 'publish' OR post_status = 'closed')
    ORDER BY post_name ASC
    LIMIT {$offset}, {$post_per_page}
    ";

    $results = $wpdb->get_results( $query);

    $res_count = (int) $wpdb->get_var( "SELECT FOUND_ROWS()" );

    include "addons/pagination/pagination.class.php";
    $p = new Arcane_Pagination;
    $p->items($res_count);
    $p->limit($post_per_page);

    $p->parameterName("page");
    $p->currentPage(isset($_GET['page'])?$_GET['page']:1);
    unset($_GET['page']);
    $_GET['term'] = $term;
    $p->target("?".http_build_query($_GET));

    #$p->parameterName('paged');
    #$p->currentPage($paged);
    #$p->target("?term={$term}");


    $p->nextLabel(esc_html__('Next','arcane'));
    $p->prevLabel(esc_html__('Previous','arcane'));


    if ( $results ) { ?>

        <ul class="teams-list" >
            <?php foreach ( $results as $post ) { ?>
                <li>
                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?> ">
                        <?php $img = get_post_meta( $post->ID, 'team_photo', true );
                        $image = arcane_aq_resize( $img, 50, 50, true, true, true );
                        if(empty($image)){
                        $image = get_theme_file_uri('img/defaults/default-team-50x50.jpg');
                        } ?>

                        <img alt="img" src="<?php echo esc_url($image); ?>" class="avatar" >

                        <div>
                            <strong>
                                <?php echo esc_attr($post->post_title); ?>
                            </strong>
                            <span>
                            <?php $members = arcane_return_number_of_members($post->ID); ?>
                                <?php if($members == 1){
                                echo esc_attr($members); ?>&nbsp;<?php esc_html_e('Member','arcane');
                                }else{
                                echo esc_attr($members); ?>&nbsp;<?php esc_html_e('Members','arcane');
                                } ?>
                            </span>
                        </div>
                    </a>
                </li>
            <?php } ?>
        </ul>

    <?php } else { ?>
        <div class="error_msg"><span><?php esc_html_e('There are no teams at the moment!', 'arcane'); ?> </span></div>
    <?php } ?>


    <?php $p->show();

}
}


/*add team creation field to user profile*/
if(!function_exists('arcane_team_extra_user_profile_fields')){
function arcane_team_extra_user_profile_fields( $user ) { ?>
<?php if ( !current_user_can( 'edit_user', $user ) ) { return false; } ?>
<h3><?php esc_html_e("Team creation", "arcane"); ?></h3>
<table class="form-table">
<tr>
<th><label for="team_account"><?php esc_html_e("Allow user to create teams", 'arcane'); ?></label>
</th>
<td>
<input type="checkbox" name="_checkbox_team_user" id="_checkbox_team_user" value="yes" <?php if (esc_attr( get_the_author_meta( "_checkbox_team_user", $user->ID )) == "yes") {echo "checked";} ?> />
<br />
</td>
</tr>
<tr>
<th><label for="team_no_limit"><?php esc_html_e("Ignore 1 team per user limit", 'arcane'); ?></label>
</th>
<td>
<input type="checkbox" name="team_no_limit" id="team_no_limit" value="yes" <?php if (esc_attr( get_the_author_meta( "team_no_limit", $user->ID )) == "yes") {echo "checked";} ?> />
<br />
</td>
</tr>
</table>

<h3><?php esc_html_e("Tournament creation", "arcane"); ?></h3>
<table class="form-table">
<tr>
<th><label for="team_account"><?php esc_html_e("Allow user to create tournaments", 'arcane'); ?></label>
</th>
<td>
<input type="checkbox" name="_checkbox_tournament_user" id="_checkbox_tournament_user" value="yes" <?php if (esc_attr( get_the_author_meta( "_checkbox_tournament_user", $user->ID )) == "yes") {echo "checked";} ?> />
<br />
</td>
</tr>
</table>


<h3><?php esc_html_e("Activate user", "arcane"); ?></h3>
<table class="form-table">
<tr>
<th><label for="user_active"><?php esc_html_e("Make this user active", 'arcane'); ?></label>
</th>
<td>
<input type="checkbox" name="_checkbox_user_active" id="_checkbox_user_active" value="true" <?php if (esc_attr( get_the_author_meta( "active", $user->ID )) == "true") {echo "checked";} ?> />
<br />
</td>
</tr>
</table>


<h3><?php esc_html_e("Premium", "arcane"); ?></h3>
<table class="form-table">
<tr>
<th><label for="user_active"><?php esc_html_e("Make this user premium", 'arcane'); ?></label>
</th>
<td>
<input type="checkbox" name="_checkbox_user_premium" id="_checkbox_user_premium" value="true" <?php if (esc_attr( get_the_author_meta( "premium", $user->ID )) == "true") {echo "checked";} ?> />
<br />
</td>
</tr>
<tr>
<th><label for="user_active">
<?php esc_html_e("Premium tournament id", 'arcane'); ?>

</label>
</th>
<td>
<input type="text" class="text" name="_user_premium_tournament" id="_user_premium_tournament" value="<?php if ( get_the_author_meta( "user_premium_tournament", $user->ID )) {echo esc_attr( get_the_author_meta( "user_premium_tournament", $user->ID ));} ?>" />
<p class="description">
<?php esc_html_e("Add tournaments comma-separated. If added user will have access only to these premium tournaments", 'arcane'); ?>
</p>
<br />
</td>
</tr>
</table>

<?php }
}

if(!function_exists('arcane_save_team_extra_user_profile_fields')){
function arcane_save_team_extra_user_profile_fields( $user_id ) {
if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

if(empty($_POST['_checkbox_team_user'])){$_POST['_checkbox_team_user']= '';}
update_user_meta( $user_id, '_checkbox_team_user', $_POST['_checkbox_team_user'] );

if(empty($_POST['team_no_limit'])){$_POST['team_no_limit']= '';}
update_user_meta( $user_id, 'team_no_limit', $_POST['team_no_limit'] );

if(empty($_POST['_checkbox_tournament_user'])){$_POST['_checkbox_tournament_user']= 'false';}
update_user_meta( $user_id, '_checkbox_tournament_user', $_POST['_checkbox_tournament_user'] );

if(empty($_POST['_checkbox_user_active'])){$_POST['_checkbox_user_active']= 'false';}
update_user_meta( $user_id, 'active', $_POST['_checkbox_user_active'] );

if(empty($_POST['_checkbox_user_premium'])){$_POST['_checkbox_user_premium']= 'false';}
update_user_meta( $user_id, 'premium', $_POST['_checkbox_user_premium'] );

if(empty($_POST['_user_premium_tournament'])){$_POST['_user_premium_tournament']= '';}
update_user_meta( $user_id, 'user_premium_tournament', $_POST['_user_premium_tournament'] );

}
}

if(!function_exists('arcane_turn_secondary_avatars_to_links')){
function arcane_turn_secondary_avatars_to_links( $avatar ) {
global $activities_template;

switch ( $activities_template->activity->component ) {
case 'groups' :
$item_id = $activities_template->activity->item_id;
$group = groups_get_group( array( 'group_id' => $item_id ) );
$url = apply_filters( 'bp_get_group_permalink', trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' ) );
break;
case 'blogs' :
break;
case 'friends' :
$item_id = $activities_template->activity->secondary_item_id;
$url = bp_core_get_user_domain($item_id);
$avatar = get_avatar((int)$activities_template->activity->secondary_item_id, 20,null,'', ['class' => 'avatar user-1-avatar avatar-50 photo']);
break;
default :
break;

}
if ( !empty( $url ) ) {
$avatar = '' . $avatar . '';
}
return $avatar;
}
}

// define the bp_core_fetch_avatar callback
if(!function_exists('arcane_filter_bp_core_fetch_avatar')){
function arcane_filter_bp_core_fetch_avatar( $jfb_bp_avatar, $number, $number1 )
{


    if($number['object'] == 'group'){
    $avatar_options = array ( 'item_id' => $number['item_id'], 'object' => $number['object'], 'type' => $number['type'], 'avatar_dir' => $number['avatar_dir'], 'alt' => $number['alt'], 'class' => $number['class'], 'width' => $number['width'], 'height' => $number['height'], 'html' => false );
    $result = bp_core_fetch_avatar($avatar_options);

    return '<img  src="'.esc_url($result).'" width="'.esc_attr($number['width']).'" height="'.esc_attr($number['height']).'" alt="'.esc_attr($number['alt']).'" />';
    }else{

    $uid = $number['item_id'];

    $user_data = get_user_meta( $uid, 'thechamp_large_avatar', true );
    $flag = false;
    if(isset($user_data) && !empty($user_data)){
        $custom_avatar = $user_data;
        $flag = true;

    }else{
        $custom_avatar = get_the_author_meta('profile_photo', $uid);

    }

    $check_new = get_the_author_meta('check_profile_photo', $uid);

    if ($check_new == true) {
        $src = (string) reset(simplexml_import_dom(DOMDocument::loadHTML($jfb_bp_avatar))->xpath("//img/@src"));
        $custom_avatar = $src;
        delete_user_meta($uid, 'check_profile_photo');
        update_user_meta($uid, 'profile_photo', $src);
    }

    if ($number['html'] == 1) {
        if (strlen($custom_avatar) > 1){
            if($flag){
                $image = $custom_avatar;
            }else{
                $image = arcane_aq_resize( $custom_avatar, 50, 50, true, true, true );
            }

            $returner = '<img src="'.esc_url($image).'" width="'.esc_attr($number['width']).'" height="'.esc_attr($number['height']).'" alt="'.esc_attr($number['alt']).'" />';
        }else{
            if(function_exists('wsl_get_stored_hybridauth_user_profiles_by_user_id'))
            {$user_data = wsl_get_stored_hybridauth_user_profiles_by_user_id($uid);}
            if(isset($user_data[0]->photourl)){
                $returner = '<img src="'.esc_url($user_data[0]->photourl).'" width="'.esc_attr($number['width']).'" height="'.esc_attr($number['height']).'" alt="'.esc_attr($number['alt']).'" />';

            }else{
                $returner = '<img src="'.get_theme_file_uri('img/defaults/default-profile.jpg').'" width="'.esc_attr($number['width']).'" height="'.esc_attr($number['height']).'" alt="'.esc_attr($number['alt']).'" />';
            }
        }
    } else {
        if (strlen($custom_avatar) > 1)
            {$returner = $custom_avatar;}
        else
            {$returner = get_theme_file_uri('img/defaults/default-profile.jpg');}
    }
    return $returner;
    }
}
}

/*avatars fix*/
if(!function_exists('arcane_update_avatar_admin')){
function arcane_update_avatar_admin(){
    $userid = get_current_user_id();
    update_user_meta($userid,'check_profile_photo',true);
}
}

/*return author using post id*/
if(!function_exists('arcane_get_author')){
function arcane_get_author( $post_id = 0 ){
     $post = get_post( $post_id );
     return $post->post_author;
}
}



/*************************************ROLES****************************************************/



/*remove menus for non admins*/
if(!function_exists('arcane_remove_menus')){
function arcane_remove_menus(){

  if(current_user_can( 'administrator' ) ){


  }elseif( current_user_can( 'author' ) ){

  remove_menu_page( 'edit.php?post_type=page' );        //Pages
  remove_menu_page( 'edit.php?post_type=team' );        //Teams
  remove_menu_page( 'themes.php' );                     //Appearance
  remove_menu_page( 'plugins.php' );                    //Plugins
  remove_menu_page( 'users.php' );                      //Users
  remove_menu_page( 'options-general.php' );
  remove_menu_page( 'edit.php?post_type=wp_owl' );          //Settings
  }elseif(!current_user_can( 'activate_plugins' )) {
  remove_menu_page( 'index.php' );                      //Dashboard
  remove_menu_page( 'edit.php' );                       //Posts
  remove_menu_page( 'upload.php' );                     //Media
  remove_menu_page( 'edit.php?post_type=page' );        //Pages
  remove_menu_page( 'edit.php?post_type=team' );        //Teams
  remove_menu_page( 'edit-comments.php' );              //Comments
  remove_menu_page( 'themes.php' );                     //Appearance
  remove_menu_page( 'plugins.php' );                    //Plugins
  remove_menu_page( 'users.php' );                      //Users
  remove_menu_page( 'tools.php' );                      //Tools
  remove_menu_page( 'options-general.php' );
  remove_menu_page( 'edit.php?post_type=wp_owl' );          //Settings
  }
}
}


if(!function_exists('arcane_endsWith')){
function arcane_endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}
}




/*Set default role on new users register*/
if(!function_exists('arcane_defaultrole')){
function arcane_defaultrole($default_role){
    return 'gamer'; // This is changed
}
}

if(!function_exists('arcane_add_theme_caps')){
function arcane_add_theme_caps() {
    // gets the author role
    $role = get_role( 'gamer' );
    $role->add_cap( 'delete_posts' );
    $role->add_cap( 'delete_published_posts ' );
    $role->add_cap( 'edit_pages' );
    $role->add_cap( 'edit_posts' );
    $role->add_cap( 'edit_published_pages' );
    $role->add_cap( 'edit_others_pages' );
    $role->add_cap( 'edit_others_posts' );
    $role->add_cap( 'level_0' );
    $role->add_cap( 'level_1' );
    $role->add_cap( 'level_2' );
    $role->add_cap( 'level_3' );
    $role->add_cap( 'level_4' );
    $role->add_cap( 'publish_pages' );
    $role->add_cap( 'publish_posts' );
    $role->add_cap( 'read' );
    $role->add_cap( 'upload_files' );
}
}


add_role( 'gamer', 'Gamer',  array(
        'read'         => true,  // true allows this capability
        'edit_posts'   => true,
        'delete_posts' => true,
        'publish_posts' => true,
        'upload_files'  => true,
        'edit_others_pages' => true,
        'edit_published_posts' => true,
        'delete_published_posts' => true,
    ) );


/*******open comments *********/
if(!function_exists('arcane_comments_open')){
function arcane_comments_open( $open, $post_id ) {

    $post = get_post( $post_id );
    if(! empty($post) && is_a($post, 'WP_Post')){
    if ( 'team' == $post->post_type or 'matches' == $post->post_type)
        {$open = true;}
    return $open;
    }
}
}

if(!function_exists('arcane_comments_on')){
function arcane_comments_on( $data ) {
    if( $data['post_type'] == 'team' ) {
        $data['comment_status'] = 'open';
    }

    return $data;
}
}


if(!function_exists('arcane_matches_comments_on')){
function arcane_matches_comments_on( $data ) {
    if( $data['post_type'] == 'matches' ) {
        $data['comment_status'] = 'open';
    }

    return $data;
}
}

if(!function_exists('arcane_matches_edit_comments_on')){
function arcane_matches_edit_comments_on( $data ) {
    if( isset($data['post_type']) && $data['post_type'] == 'matches' ) {
        $data['comment_status'] = 'open';
    }

    return $data;
}
}

if(!function_exists('arcane_new_modify_user_table')){
function arcane_new_modify_user_table( $column ) {
    $column['nickname'] = esc_html__('Nickname', 'arcane');
    $column['registered'] = esc_html__('Date Registered', 'arcane');
    return $column;
}
}


if(!function_exists('arcane_new_modify_user_table_row')){
function arcane_new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'nickname' :
            return get_the_author_meta( 'nickname', $user_id );
            break;
        case 'registered' :
            $udata = get_userdata( $user_id);
            $registered = $udata->user_registered;
            return $registered;
            break;
        default:
    }
    return $val;
}
}


/*
Register Fonts
*/
if(!function_exists('arcane_fonts_url')){
function arcane_fonts_url() {
    $font_url = '';

    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'arcane' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Montserrat:200,300,400,500,500,600,700,800
        |Roboto:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic'
        ), "//fonts.googleapis.com/css" );
    }
    return esc_url($font_url);
}
}


/*check if user is active*/
if(!function_exists('arcane_user_active_check')){
function arcane_user_active_check($user_login, $user){
    $active = get_user_meta($user->ID, 'active', true);
    if(!in_array('administrator',$user->roles)){
        if( $active == 'false'){
            wp_logout();
            wp_redirect(esc_url(get_permalink( get_page_by_path('user-activation'))).'?act_error=1&uid='.$user->ID.'', $status = 302);
            exit();
        }
    }

}
}


/**
 * Your theme callback function
 *
 * @see bp_legacy_theme_cover_image() to discover the one used by BP Legacy
 */
 if(!function_exists('arcane_cover_image_callback')){
function arcane_cover_image_callback( $params = [] ) {
    if ( empty( $params ) ) {
        return;
    }
        if(!empty($params["height"])){
        echo '<style>
            /* Cover image - Do not forget this part */
            #buddypress #header-cover-image {
                height: ' . esc_attr($params["height"]) . 'px;
                background-image: url(' . esc_url($params['cover_image']) . ');
                background-position: center top;
                background-repeat: no-repeat;
                background-size: cover;
                border: 0;
                display: block;
                left: 0;
                margin: 0;
                padding: 0;
                position: absolute;
                top: 0;
                width: 100%;
            }

            .group-create  #buddypress #header-cover-image {
                position: relative !important;
            }
            </style>
        ';
    }
}
}

if(!function_exists('arcane_cover_image_css')){
function arcane_cover_image_css( $settings = [] ) {
    $settings['callback'] = 'arcane_cover_image_callback';

    return $settings;
}
}

/*registration page redirection*/
if(!function_exists('arcane_register_page')){
function arcane_register_page( $register_url ) {
return home_url( '/user-registration/' );
}
}



if(!function_exists('arcane_excerpt_more')){
function arcane_excerpt_more($more) {
    return '...';
}
}


if(!function_exists('arcane_ordinal')){
function arcane_ordinal($number) {
    if(get_locale() != 'en_EN'){
        return $number;
    }else{

    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        {return $number. 'th';}
    else
        {return $number. $ends[$number % 10];}
    }
}
}

if(!function_exists('arcane_sort_objects_by_score')){
function arcane_sort_objects_by_score($a, $b)
{

    if($a->score == $b->score){ return 0 ; }
    return ($a->score > $b->score) ? -1 : 1;
}
}

if(!function_exists('arcane_sort_objects_by_date')){
function arcane_sort_objects_by_date($a, $b)
{

    if($a->post_date == $b->post_date){ return 0 ; }
    return ($a->post_date > $b->post_date) ? -1 : 1;
}
}

if(!function_exists('arcane_return_team_win_lose_score')){
function arcane_return_team_win_lose_score($team_id){
  global $ArcaneWpTeamWars;
  $args = array(
    'post_type' => 'matches',
    'posts_per_page' => -1,
    'post_status' => 'any',
    'meta_query' => array(
      'relation' => 'OR',
      array(
        'key' => 'team1',
        'value' => $team_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
      array(
        'key' => 'team2',
        'value' => $team_id,
        'compare' => '=',
        'type' => 'numeric',
      ),
    )
    );
    $won = 0;
    $lost = 0;
    $overall = 0;

    $posts = get_posts ($args);
    if (is_array($posts)) {
      foreach ($posts as $post) {
        $match = $ArcaneWpTeamWars->get_match(array('id' => $post->ID));
        if ($match->team1 == $team_id) {
          if ((isset($match->team1_tickets) && isset($match->team2_tickets)) && ($match->team1_tickets > $match->team2_tickets)){
            $won++;
          } else {
            $lost++;
          }
        }else {
          if ((isset($match->team1_tickets) && isset($match->team2_tickets)) && ($match->team2_tickets > $match->team1_tickets)){
            $won++;
          } else {
            $lost++;
          }
        }
      }
    }


    $overall = $won - $lost;
    return $overall;

}
}

if(!function_exists('arcane_load_tourney_profile_page')){
function arcane_load_tourney_profile_page() {
  add_action( 'bp_template_content', 'arcane_profile_show_tourneys' );
  bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}
}

if(!function_exists('arcane_list_all_teams_for_selected_game')){
function arcane_list_all_teams_for_selected_game($gid,$page=1, $term=''){

    $post_per_page = 40;

    $args = array(
        'post_type'  => 'team',
        'posts_per_page'   => -1,
        'meta_key'   => 'games',
        'meta_query' => array(
            array(
                'key'     => 'games',
                'value'   => $gid,
                'compare' => 'LIKE',
            ),
        ),
    );

    $my_posts = get_posts($args);


    include "addons/pagination/pagination.class.php";
    $p = new Arcane_Pagination;
    $p->items(count($my_posts));
    $p->limit($post_per_page);

    $p->parameterName("page");
    $p->currentPage(isset($_GET['page'])?$_GET['page']:1);
    unset($_GET['page']);
    $_GET['term'] = $term;
    $p->target("?".http_build_query($_GET));


    $p->nextLabel(esc_html__('Next','arcane'));
    $p->prevLabel(esc_html__('Previous','arcane'));


    if ( $my_posts ) { ?>

       <ul class="teams-list" >
       <?php foreach ( $my_posts as $post ) { ?>
             <li>
                <a href="<?php echo esc_url(get_permalink($post->ID)); ?> ">
                    <?php $img = get_post_meta( $post->ID, 'team_photo', true );
                    $image = arcane_aq_resize( $img, 50, 50, true, true, true );
                    if(empty($image)){
                    $image = get_theme_file_uri('img/defaults/default-team-50x50.jpg');
                    } ?>

                    <img alt="img" src="<?php echo esc_url($image); ?>" class="avatar" >

                    <div>
                        <strong>
                            <?php echo esc_attr($post->post_title); ?>
                        </strong>
                        <span>
                        <?php $members = arcane_return_number_of_members($post->ID); ?>
                            <?php if($members == 1){
                            echo esc_attr($members); ?>&nbsp;<?php esc_html_e('Member','arcane');
                            }else{
                            echo esc_attr($members); ?>&nbsp;<?php esc_html_e('Members','arcane');
                            } ?>
                        </span>
                    </div>
                </a>
            </li>
       <?php } ?>

       </ul>
<div class="clear"></div>
    <?php } else { ?>
        <div class="error_msg"><span><?php esc_html_e('There are no teams that play this game at the moment!', 'arcane'); ?> </span></div>
    <?php } ?>


    <?php $p->show();
    wp_reset_postdata();
}
}

if(!function_exists('arcane_list_all_teams_for_selected_game_search')){
function arcane_list_all_teams_for_selected_game_search($page=1, $term=''){

    global $wpdb;

    $post_per_page = 40;
    $offset = ($page -1) * $post_per_page;

    $query = "
    SELECT SQL_CALC_FOUND_ROWS *
    FROM {$wpdb->posts} WHERE 1=1
    AND post_type = 'team'
    AND post_name LIKE '%{$term}%'
    AND (post_status = 'publish' OR post_status = 'closed')
    ORDER BY post_name ASC
    LIMIT {$offset}, {$post_per_page}
    ";

    $results = $wpdb->get_results( $query);

    $res_count = (int) $wpdb->get_var( "SELECT FOUND_ROWS()" );

    include "addons/pagination/pagination.class.php";
    $p = new Arcane_Pagination;
    $p->items($res_count);
    $p->limit($post_per_page);

    $p->parameterName("page");
    $p->currentPage(isset($_GET['page'])?$_GET['page']:1);
    unset($_GET['page']);
    $_GET['term'] = $term;
    $p->target("?".http_build_query($_GET));


    $p->nextLabel(esc_html__('Next','arcane'));
    $p->prevLabel(esc_html__('Previous','arcane'));


    if ( $results ) { ?>

       <ul class="members-list item-list" >
       <?php foreach ( $results as $post ) { ?>
            <li>
                    <div class="team-list-wrapper">
                        <div class="item-avatar">
                           <a href="<?php echo esc_url(get_permalink($post->ID)); ?> ">
                             <?php $img = get_post_meta( $post->ID, 'team_photo', true );
                                   $image = arcane_aq_resize( $img, 50, 50, true, true, true );
                                   if(empty($image)){
                                       $image = get_theme_file_uri('img/defaults/default-team-50x50.jpg');
                                   }
                                   ?>
                            <img alt="img" src="<?php echo esc_url($image); ?>" class="avatar" >
                           </a>
                        </div>

                        <div class="item">
                            <div class="item-title">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?> "> <?php if(strlen($post->post_title) > 25){ $dot= '...'; echo esc_attr( mb_substr($post->post_title, 0 ,25).$dot); }else{ echo esc_attr($post->post_title); } ?></a>

                                <div class="item-meta">
                                    <span class="activity">
                                        <?php $members = arcane_return_number_of_members($post->ID); ?>
                                        <?php if($members == 1){
                                            echo esc_attr($members); ?>&nbsp;<?php esc_html_e('Member','arcane');
                                        }else{
                                            echo esc_attr($members); ?>&nbsp;<?php esc_html_e('Members','arcane');
                                        } ?>
                                    </span></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>

                </li>
       <?php } ?>

       </ul>
<div class="clear"></div>
    <?php } else { ?>
        <div class="error_msg"><span><?php esc_html_e('We couldn\'t find any results for your search.', 'arcane'); ?> </span></div>
    <?php } ?>


    <?php $p->show();
}
}


if(!function_exists('arcane_list_all_teams_for_selected_game_ajax')){
function arcane_list_all_teams_for_selected_game_ajax(){
    if ( ! check_ajax_referer( 'arcane-security-nonce', 'security' ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
    $_POST['href'] = str_replace('?','', $_POST['href']);
    parse_str($_POST['href'], $_GET);
    arcane_list_all_teams_for_selected_game_search($_GET['page'], $_GET['term']);
    die();
}
}

if(!function_exists('arcane_is_val_exists')){
function arcane_is_val_exists($needle, $haystack) {
     if(!is_array($haystack))   {return false;}

     if(in_array($needle, $haystack)) {
          return true;
     }

     foreach($haystack as $element) {
          if(is_array($element) && arcane_is_val_exists($needle, $element))
               {return true;}
     }
   return false;
}
}

if(!function_exists('arcane_mass_update_post_meta')){
function arcane_mass_update_post_meta($pid, $data) {

  if (($pid > 0) and (is_array($data))) {
    foreach ($data as $key=>$value) {
      try {
        update_post_meta ($pid, $key, $value);

      } catch (Exception $e) {
        return false;
      }
    }
    return true;
  } else {
    return false;
  }
}
}

if(!function_exists('arcane_get_meta')){
function arcane_get_meta($pid) {
  $metadata = get_post_meta($pid);
  if (is_array($metadata) and (count($metadata) > 0)) {
    $data = [];
    foreach ($metadata as $key=>$value) {
      if (strpos($value[0],"{") !== false ) {
        try {
          $tester = unserialize($value[0]);
          if (is_array($tester)) {
            $data[$key] = $tester;
          } else {
            $data['key'] = $value[0];
          }
        } catch (Exception $e) {
          $data['key'] = $value[0];
        }
      } else {
        $data[$key] = $value[0];
      }
    }
    return $data;
  } else {
    return false;
  }
}
}


if(!function_exists('arcane_convertPHPToMomentFormat')){

function arcane_convertPHPToMomentFormat($format)
{
    $replacements = array(
        'd' => 'DD',
        'D' => 'ddd',
        'j' => 'D',
        'l' => 'dddd',
        'N' => 'E',
        'S' => 'o',
        'w' => 'e',
        'z' => 'DDD',
        'W' => 'W',
        'F' => 'MMMM',
        'm' => 'MM',
        'M' => 'MMM',
        'n' => 'M',
        't' => '', // no equivalent
        'L' => '', // no equivalent
        'o' => 'YYYY',
        'Y' => 'YYYY',
        'y' => 'YY',
        'a' => 'a',
        'A' => 'A',
        'B' => '', // no equivalent
        'g' => 'h',
        'G' => 'H',
        'h' => 'hh',
        'H' => 'HH',
        'i' => 'mm',
        's' => 'ss',
        'u' => 'SSS',
        'e' => 'zz', // deprecated since version 1.6.0 of moment.js
        'I' => '', // no equivalent
        'O' => '', // no equivalent
        'P' => '', // no equivalent
        'T' => '', // no equivalent
        'Z' => '', // no equivalent
        'c' => '', // no equivalent
        'r' => '', // no equivalent
        'U' => 'X',
    );
    $momentFormat = strtr($format, $replacements);
    return $momentFormat;
}
}

//filter out the admin bar
if(!function_exists('arcane_bar_render')){
function arcane_bar_render() {
    global $wp_admin_bar;
    if (current_user_can('gamer')) {
      $wp_admin_bar->remove_menu('comments');
      $wp_admin_bar->remove_menu('site-name');
      $wp_admin_bar->remove_menu('new-content');
      $wp_admin_bar->remove_menu('edit');
    }
}
}

if(!function_exists('arcane_premium_notice_tournaments')){
function arcane_premium_notice_tournaments() {

    $current_screen = get_current_screen();
    if($current_screen->base == 'teamwars_page_wp-teamwars-settings' ||
    $current_screen->base == 'teamwars_page_wp-teamwars-games' ||
    $current_screen->base == 'teamwars_page_wp-teamwars-matches' ||
    $current_screen->base == 'teamwars_page_wp-teamwars-import' ||
    $current_screen->post_type == 'tournament'){
        ?>
        <div class="notice premium-tournaments">
        <a href="https://www.skywarriorthemes.com/product/tournaments-premium/"><img alt="img" src="<?php echo get_theme_file_uri('img/pb-t-img.png'); ?>" /> <span><?php esc_html_e( 'Premium tournaments plugin', 'arcane' ); ?></span> <i>//</i> <strong><?php esc_html_e( 'Including ladder, round robin and league!', 'arcane' ); ?></strong></a>
        </div>
        <?php
    }
}
}

if(!function_exists('arcane_premium_notice_pay_to_join')){
function arcane_premium_notice_pay_to_join() {

    $current_screen = get_current_screen();
    if($current_screen->base == 'teamwars_page_wp-teamwars-settings' ||
    $current_screen->base == 'teamwars_page_wp-teamwars-games' ||
    $current_screen->base == 'teamwars_page_wp-teamwars-matches' ||
    $current_screen->base == 'teamwars_page_wp-teamwars-import' ||
    $current_screen->post_type == 'tournament'){
        ?>
        <div class="notice premium-tournaments">
        <a href="https://www.skywarriorthemes.com/product/pay-to-join-tournaments-wp-plugin/"><img alt="img" src="<?php echo get_theme_file_uri('img/pb-ptp-img.png'); ?>" /> <span><?php esc_html_e( 'Premium "Pay to join" tournament plugin', 'arcane' ); ?></span> <i>//</i> <strong><?php esc_html_e( 'Including game, single tournament or general membership!', 'arcane' ); ?></strong></a>
        </div>
        <?php
    }
}
}

if(!function_exists('arcane_premium_notice_br')){
function arcane_premium_notice_br() {

    $current_screen = get_current_screen();
    if($current_screen->base == 'teamwars_page_wp-teamwars-settings' ||
    $current_screen->base == 'teamwars_page_wp-teamwars-games' ||
    $current_screen->base == 'teamwars_page_wp-teamwars-matches' ||
    $current_screen->base == 'teamwars_page_wp-teamwars-import' ||
    $current_screen->post_type == 'tournament'){
        ?>
        <div class="notice premium-tournaments">
        <a href="https://skywarriorthemes.com/product/battle-royale-tournaments-wp-plugin/"><img alt="img" src="<?php echo get_theme_file_uri('img/pb-br-img.png'); ?>" /> <span><?php esc_html_e( 'Premium Battle Royale tournament plugin', 'arcane' ); ?></span> <i>//</i> <strong><?php esc_html_e( 'Adds Battle Royale Tournament type to Arcane!', 'arcane' ); ?></strong></a>
        </div>
        <?php
    }
}
}

if(!function_exists('arcane_do_output_buffer')){
function arcane_do_output_buffer() {
        ob_start();
}
}

if(!function_exists('arcane_dateFormatTojQuery')){
function arcane_dateFormatTojQuery($dateFormat) {
    $chars = array(
        'd' => 'dd', 'j' => 'd', 'l' => 'DD', 'D' => 'D',
        'm' => 'mm', 'n' => 'm', 'F' => 'MM', 'M' => 'M',
        'Y' => 'yy', 'y' => 'y',
    );
    return strtr((string)$dateFormat, $chars);
}
}

if(!function_exists('arcane_FormatTimestampLikeWordpress')){
function arcane_FormatTimestampLikeWordpress($timestamp) {
    $date = get_option('date_format');
    $time = get_option('time_format');
    return date($date." ".$time, $timestamp);
}
}

if(!function_exists('arcane_fix_comments')){
function arcane_fix_comments( $allcaps, $cap, $args ) {

  if ($args[0] == "edit_comment") {
    if (current_user_can('manage_options')) {
      $allcaps[$cap[0]] = true;
    }
  }
  return $allcaps;
}
}


if(!function_exists('arcane_tournament_creation_template_redirect')){
function arcane_tournament_creation_template_redirect(){
    if( is_page('create-tournament') && ! is_user_logged_in() ){
        wp_redirect( home_url(), $status = 302);
        exit();
    }
}
}


if(!function_exists('arcane_check_if_notification_exists')){
function arcane_check_if_notification_exists($user_id, $item_id, $component_action = false){
    global $wpdb;
    $notifications = $wpdb->prefix."bp_notifications";

    if(!$component_action){
        $rslt = $wpdb->get_results($wpdb->prepare("SELECT `user_id` FROM $notifications where `user_id` = %s AND `item_id` = %s", $user_id, $item_id));
    }else{
        $rslt = $wpdb->get_results($wpdb->prepare("SELECT `user_id` FROM $notifications where `user_id` = %s AND `item_id` = %s AND `component_action` = %s", $user_id, $item_id, $component_action));
    }

    return count($rslt);
}
}

if(!function_exists('arcane_query_vars')){
function arcane_query_vars($vars) {
    $vars[] = 'cron_job';
    return $vars;
}
}

if(!function_exists('arcane_trigger_check')){
function arcane_trigger_check() {

    if(intval(get_query_var('cron_job')) == 1) {
    arcane_trigger_tournament_frequency_daily();
    arcane_trigger_tournament_frequency_weekly();
    arcane_trigger_tournament_frequency_monthly();
    arcane_trigger_tournament_frequency_yearly();
      exit;
    }
}
}

if(!function_exists('arcane_trigger_tournament_frequency_daily')){
function arcane_trigger_tournament_frequency_daily(){
    $posts_array = get_posts( array('posts_per_page'   => -1,'post_type' => 'tournament') );
    foreach ($posts_array as $post_single) {

     $frequencies = get_post_meta($post_single->ID, 'tournament_frequency', true);
     $start_time =  get_post_meta($post_single->ID, 'tournament_starts', true);
     $restarted =  get_post_meta($post_single->ID, 'restarted', true);
     $timezone_string =  get_post_meta($post_single->ID, 'tournament_timezone', true);

     $cur_time = new DateTime("now", new DateTimeZone($timezone_string) );
     $cur_time = $cur_time->getTimestamp();

     $tournament_starts = strtotime($start_time);

     if ($tournament_starts <= $cur_time) {

         if(!empty($frequencies) && $frequencies == 'daily' && $restarted !== '1'){
            arcane_restart_tournament($post_single->ID);
            add_post_meta($post_single->ID, 'restarted', true);
         }

     }

    }
}
}

if(!function_exists('arcane_trigger_tournament_frequency_weekly')){
function arcane_trigger_tournament_frequency_weekly(){
    $posts_array = get_posts( array('posts_per_page'   => -1,'post_type' => 'tournament') );

    foreach ($posts_array as $post_single) {

     $frequencies = get_post_meta($post_single->ID, 'tournament_frequency', true);
     $start_time =  get_post_meta($post_single->ID, 'tournament_starts', true);
     $restarted =  get_post_meta($post_single->ID, 'restarted', true);
     $timezone_string =  get_post_meta($post_single->ID, 'tournament_timezone', true);

      $cur_time = new DateTime("now", new DateTimeZone($timezone_string) );
      $cur_time = $cur_time->getTimestamp();
     $tournament_starts = strtotime($start_time);

     if ($tournament_starts <= $cur_time) {

         if(!empty($frequencies) && $frequencies == 'monthly' && $restarted !== '1'){
            $datediff = $cur_time - $tournament_starts;
            $n_days = floor($datediff / (60 * 60 * 24));
            if(abs($n_days) >= 7)
            {arcane_restart_tournament($post_single->ID);}
            add_post_meta($post_single->ID, 'restarted', true);
         }

     }

    }
}
}

if(!function_exists('arcane_trigger_tournament_frequency_monthly')){
function arcane_trigger_tournament_frequency_monthly(){
    $posts_array = get_posts( array('posts_per_page'   => -1,'post_type' => 'tournament') );

    foreach ($posts_array as $post_single) {

     $frequencies = get_post_meta($post_single->ID, 'tournament_frequency', true);
     $start_time =  get_post_meta($post_single->ID, 'tournament_starts', true);
     $restarted =  get_post_meta($post_single->ID, 'restarted', true);
     $timezone_string =  get_post_meta($post_single->ID, 'tournament_timezone', true);

     $cur_time = new DateTime("now", new DateTimeZone($timezone_string) );
     $cur_time = $cur_time->getTimestamp();

     $tournament_starts = strtotime($start_time);

     if ($tournament_starts <= $cur_time) {

         if(!empty($frequencies) && $frequencies == 'monthly' && $restarted !== '1'){
            $datediff = $cur_time - $tournament_starts;
            $n_days = floor($datediff / (60 * 60 * 24));
            if(abs($n_days) >= 30)
            {arcane_restart_tournament($post_single->ID);}
            add_post_meta($post_single->ID, 'restarted', true);
         }

     }

    }
}
}

if(!function_exists('arcane_trigger_tournament_frequency_yearly')){
function arcane_trigger_tournament_frequency_yearly(){
    $posts_array = get_posts( array('posts_per_page'   => -1,'post_type' => 'tournament') );


    foreach ($posts_array as $post_single) {

     $frequencies = get_post_meta($post_single->ID, 'tournament_frequency', true);
     $start_time =  get_post_meta($post_single->ID, 'tournament_starts', true);
     $restarted =  get_post_meta($post_single->ID, 'restarted', true);
     $timezone_string =  get_post_meta($post_single->ID, 'tournament_timezone', true);

     $cur_time = new DateTime("now", new DateTimeZone($timezone_string) );
     $cur_time = $cur_time->getTimestamp();

     $tournament_starts = strtotime($start_time);

     if ($tournament_starts <= $cur_time) {

         if(!empty($frequencies) && $frequencies == 'monthly' && $restarted !== '1'){
            $datediff = $cur_time - $tournament_starts;
            $n_days = floor($datediff / (60 * 60 * 24));
            if(abs($n_days) >= 365)
            {arcane_restart_tournament($post_single->ID);}
            add_post_meta($post_single->ID, 'restarted', true);
         }

     }

    }
}
}

if(!function_exists('arcane_restart_tournament')){
function arcane_restart_tournament($tid) {

    $post = get_post($tid);


        $args = array(
            'post_title' => $post->post_title,
            'post_content' => $post->post_content,
            'post_author' => $post->post_author,
            'post_type' => 'tournament',
            'post_status' => 'publish'
        );
        $post_id =  wp_insert_post ( $args );

    if ($post_id > 0) {

        $tournament_game = get_post_meta($post->ID, 'tournament_game', true);
        update_post_meta($post_id, 'tournament_game', $tournament_game);

        $tournament_format = get_post_meta($post->ID, 'format', true);
        update_post_meta($post_id, 'format', $tournament_format);

        $tournament_time_zone = get_post_meta($post->ID, 'tournament_timezone', true);
        update_post_meta($post_id, 'tournament_timezone', $tournament_time_zone);

        $tournament_contestants = get_post_meta($post->ID, 'tournament_contestants', true);
        update_post_meta($post_id, 'tournament_contestants', $tournament_contestants);

        $tournament_games_format = get_post_meta($post->ID, 'tournament_games_format', true);
        update_post_meta($post_id, 'tournament_games_format', $tournament_games_format);

        $tournament_server = get_post_meta($post->ID, 'tournament_server', true);
        update_post_meta($post_id, 'tournament_server', $tournament_server);

        $tournament_platform = get_post_meta($post->ID, 'tournament_platform', true);
        update_post_meta($post_id, 'tournament_platform', $tournament_platform);

        $tournament_game_frequency = get_post_meta($post->ID, 'tournament_game_frequency', true);
        update_post_meta($post_id, 'tournament_game_frequency', $tournament_game_frequency);

        $tournament_frequency = get_post_meta($post->ID, 'tournament_frequency', true);
        update_post_meta($post_id, 'tournament_frequency', $tournament_frequency);

        $date = get_post_meta($post->ID, 'tournament_starts', true);
        $timestamp = get_post_meta($post->ID, 'tournament_starts_unix', true);

        update_post_meta($post_id, 'tournament_starts', $date);
        update_post_meta($post_id, 'tournament_starts_unix', $timestamp);

        $tournament_max_participants = get_post_meta($post->ID, 'max_participants', true);
        update_post_meta($post_id, 'max_participants', $tournament_max_participants);

        $tournament_prizes = get_post_meta($post->ID, 'tournament_prizes', true);
        update_post_meta($post_id, 'tournament_prizes', $tournament_prizes);

        $tournament_regulations = get_post_meta($post->ID, 'tournament_regulations', true);
        update_post_meta($post_id, 'tournament_regulations', $tournament_regulations);

        $tournament_maps = get_post_meta($post->ID, 'tournament_maps', true);
        update_post_meta($post_id, 'tournament_maps', $tournament_maps);

    }

}
}

/*set menus and front page for importer*/
if(!function_exists('arcane_import_additional_resources')){
function arcane_import_additional_resources($demo_active_import, $demo_directory_path) {
    reset( $demo_active_import );
    $current_key = key( $demo_active_import );

    /************************************************************************
     * Import slider
     *************************************************************************/

    if ( class_exists( 'LS_Config' ) ) {
        include LS_ROOT_PATH.'/classes/class.ls.importutil.php';

        //If it's demo3 or demo5
        $wbc_sliders_array = array(
            'Arcane' => 'homepage.zip', //Set slider zip name
        );

        if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_sliders_array ) ) {
            $wbc_slider_import = $wbc_sliders_array[$demo_active_import[$current_key]['directory']];

            if( is_array( $wbc_slider_import ) ){
                foreach ($wbc_slider_import as $slider_zip) {
                    if ( !empty($slider_zip) && file_exists( $demo_directory_path.$slider_zip ) ) {
                        $slider = new LS_ImportUtil($demo_directory_path.$slider_zip );
                    }
                }
            }else{
                if ( file_exists( $demo_directory_path.$wbc_slider_import ) ) {
                    $slider = new LS_ImportUtil($demo_directory_path . $wbc_slider_import);
                }
            }
        }

    }

    /************************************************************************
    * Setting Menus
    *************************************************************************/
    // If it's demo1 - demo6
    $wbc_menu_array = array( 'Arcane');
    if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && in_array( $demo_active_import[$current_key]['directory'], $wbc_menu_array ) ) {
        $top_menu = get_term_by( 'name', 'Main', 'nav_menu' );
        if ( isset( $top_menu->term_id ) ) {
            set_theme_mod( 'nav_menu_locations', array(
                    'header-menu' => $top_menu->term_id,
                )
            );
        }
    }

    /************************************************************************
    * Set HomePage
    *************************************************************************/
    // array of demos/homepages to check/select from
    $wbc_home_pages = array(
        'Arcane' => esc_html__('Homepage - Slider', 'arcane'),
    );
    if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_home_pages ) ) {
        $page = get_page_by_title( $wbc_home_pages[$demo_active_import[$current_key]['directory']] );
        if ( isset( $page->ID ) ) {
            update_option( 'page_on_front', $page->ID );
            update_option( 'show_on_front', 'page' );
        }
    }

    /************************************************************************
     * Import games
     *************************************************************************/
    global $wpdb;
    $games = $wpdb->prefix."cw_games";
    $result = $wpdb->get_results("SELECT `id` from $games WHERE `id` IS NOT NULL");

    if(count($result) == 0)
    {
        $wpdb->query( $wpdb->prepare("INSERT INTO $games (`id`, `title`, `abbr`, `icon`, `g_banner_file`, `store_id`) VALUES 
          ( %d, %s, %s, %d, %d, %s),
          ( %d, %s, %s, %d, %d, %s),
          ( %d, %s, %s, %d, %d, %s),
          ( %d, %s, %s, %d, %d, %s),
          ( %d, %s, %s, %d, %d, %s)",
            1, 'Counter Strike: GO', 'CS:GO', 2987, 3231, '5b7b08775cab30001458827e',
            2, 'Fortnite', 'FN', 3016, 73467, '5b7b08385cab30001458827a',
            3, 'Dota 2', 'Dota 2', 2985, 2986, '5b7b081e5cab300014588278',
            4, 'League of Legends', 'LoL', 2990, 3086, '',
            5, 'Overwatch', 'OW', 2992, 3087, ''));

    }

    /************************************************************************
     * Import maps
     *************************************************************************/
    $maps = $wpdb->prefix."cw_maps";
    $result = $wpdb->get_results("SELECT `id` from $maps WHERE `id` IS NOT NULL");

    if(count($result) == 0)
    {
        $wpdb->query( $wpdb->prepare("INSERT INTO $maps (`id`, `game_id`, `title`, `screenshot`) VALUES 
          ( %d, %d, %s, %d),( %d, %d, %s, %d),( %d, %d, %s, %d),( %d, %d, %s, %d),
          ( %d, %d, %s, %d),( %d, %d, %s, %d),( %d, %d, %s, %d),( %d, %d, %s, %d),
          ( %d, %d, %s, %d),( %d, %d, %s, %d),( %d, %d, %s, %d),( %d, %d, %s, %d)",
            1, 1, 'Office', 2967, 2, 1, 'Cache', 2968, 3, 1, 'Cobblestone', 2969,4, 1, 'Dust2', 2970,5, 1, 'Inferno', 2971,
            6, 1, 'Mirage', 2973,7, 1, 'Nuke', 2974,9, 1, 'Overpass', 2976,18, 4, 'Howling Abyss', 3924,17, 2, 'Snow', 3018,
            12, 1, 'Train', 2978,13, 3, 'Main', 2981,14, 4, 'Summoner\'s Rift', 2994,15, 5, 'Hanamura', 2995,16, 5, 'Busan', 2996));
    }

}
}





if(!function_exists('arcane_lost_pass_redirection')){
function arcane_lost_pass_redirection()
{
    wp_redirect(get_permalink( get_page_by_path('lost-password')), $status = 302);
    exit(); // always call `exit()` after `wp_redirect`
}
}

#-----------------------------------------------------------------#
# Options panel
#-----------------------------------------------------------------#

function arcane_get_theme_options() {
    $current_options = get_option('arcane_redux');
    return $current_options;
}



if ( class_exists('Puc_v4_Factory')){
    $arcane_update_checker = Puc_v4_Factory::buildUpdateChecker(
        'https://skywarriorthemes.com/updates/arcane/info.json',
        __FILE__,
        'arcane'
    );
}

if(!function_exists('arcane_upcoming_matches_sort')){
function arcane_upcoming_matches_sort($a, $b){
    $t1 = mysql2date("U", $a->date);
    $t2 = mysql2date("U", $b->date);

    if($t1 == $t2) {return 0;}

    return $t1 < $t2 ? -1 : 1;
}
}

if(!function_exists('arcane_other_matches_sort')){
function arcane_other_matches_sort($a, $b){
    $t1 = mysql2date("U", $a->date);
    $t2 = mysql2date("U", $b->date);

    if($t1 == $t2) {return 0;}

    return $t1 > $t2 ? -1 : 1;
}
}

if(!function_exists('arcane_table_prefix')){
function arcane_table_prefix($t){
    global $table_prefix;
    return $table_prefix . $t;
}
}



function arcane_timezone_string() {
    // If site timezone string exists, return it.
    if ( $timezone = get_option( 'timezone_string' ) ) {
        return $timezone;
    }

    // Get UTC offset, if it isn't set then return UTC.
    if ( 0 === ( $utc_offset = intval( get_option( 'gmt_offset', 0 ) ) ) ) {
        return 'UTC';
    }

    // Adjust UTC offset from hours to seconds.
    $utc_offset *= 3600;

    // Attempt to guess the timezone string from the UTC offset.
    if ( $timezone = timezone_name_from_abbr( '', $utc_offset ) ) {
        return $timezone;
    }

    // Last try, guess timezone string manually.
    foreach ( timezone_abbreviations_list() as $abbr ) {
        foreach ( $abbr as $city ) {
            if ( (bool) date( 'I' ) === (bool) $city['dst'] && $city['timezone_id'] && intval( $city['offset'] ) === $utc_offset ) {
                return $city['timezone_id'];
            }
        }
    }

    // Fallback to UTC.
    return 'UTC';
}


if(!function_exists('arcane_create_match_third_place')){
function arcane_create_match_third_place($tid, $match_id){
   /*mec za trece mesto*/
 $gamesz = get_post_meta($tid, 'game_cache', true);
 $i = 1;
 $broj_igara = count($gamesz);
 $broj_rundi = $broj_igara - 2;
 global $ArcaneWpTeamWars;
 $match = $ArcaneWpTeamWars->get_match(array('id' => $match_id));

 foreach ($gamesz as $key => $games) {

       if(($broj_rundi - 1) == $i){
           if (is_array($games)) {
                $j = 1;
                foreach ($games as $game) {

                    if (isset($game['match_post_id']) && $game['match_post_id'] > 1) {
                       if($j == 1){
                           if($game['teams'][0]['score'] > $game['teams'][1]['score']){
                               $first_third_place = $game['teams'][1]['id'];
                           }elseif($game['teams'][0]['score'] < $game['teams'][1]['score']){
                               $first_third_place = $game['teams'][0]['id'];
                           }else{

                           }
                           $gamesz_third = get_post_meta($tid, 'game_cache_third', true);


                           if(isset($first_third_place) && (!isset($gamesz_third['match_post_id']) or $gamesz_third['match_post_id'] == 'unset')){
                                $contestants = get_post_meta($tid, 'tournament_contestants', true);
                                if($contestants == 'team') {
                                    $t1 = get_post($first_third_place);
                                    $t1_name = $t1->post_title;
                                    $url1 = get_permalink($first_third_place);
                                } else {
                                    $t1 = get_user_by('id', $first_third_place);
                                    $t1_name = $t1->display_name;
                                    $url1 = bp_core_get_user_domain($first_third_place);

                                }

                               $games = array(
                                'match_post_id' => 'unset',
                                'teams' => array(
                                    array(
                                        'id' => $first_third_place,
                                        'name' => $t1_name,
                                        'url' => esc_url($url1),
                                        'score' => -1
                                    ),
                                    array(
                                        'id' => 'tbd',
                                        'name' => 'tbd',
                                        'url' => 'tbd',
                                        'score' => -1
                                    )
                                ),
                                'time' => ''
                               );
                               update_post_meta($tid, 'game_cache_third', $games);
                           }
                       }

                       if($j == 2){

                           if($game['teams'][0]['score'] > $game['teams'][1]['score']){
                               $second_third_place = $game['teams'][1]['id'];
                           }elseif($game['teams'][0]['score'] < $game['teams'][1]['score']){
                               $second_third_place = $game['teams'][0]['id'];
                           }else{

                           }

                           $gamesz_third = get_post_meta($tid, 'game_cache_third', true);

                           if(isset($second_third_place) && (!isset($gamesz_third['match_post_id']) or $gamesz_third['match_post_id'] == 'unset')){
                                $contestants = get_post_meta($tid, 'tournament_contestants', true);
                                if($contestants == 'team') {
                                    $t1 = get_post($second_third_place);
                                    $t1_name = $t1->post_title;
                                    $url1 = get_permalink($second_third_place);
                                } else {
                                    $t1 = get_user_by('id', $second_third_place);
                                    $t1_name = $t1->display_name;
                                    $url1 = bp_core_get_user_domain($second_third_place);
                                }

                               $games = array(
                                'match_post_id' => 'unset',
                                'teams' => array(

                                    array(
                                        'id' => 'tbd',
                                        'name' => 'tbd',
                                        'url' => 'tbd',
                                        'score' => -1
                                    ),
                                     array(
                                        'id' => $second_third_place,
                                        'name' => $t1_name,
                                        'url' => esc_url($url1),
                                        'score' => -1
                                    ),
                                ),
                                'time' => ''
                               );
                               update_post_meta($tid, 'game_cache_third', $games);
                           }
                       }

                    }
                    $j++;
                }

           }
       }

      if($broj_rundi == $i){
        $vreme = $games[0]['time'];
      }

       $i++;
   }


        $gamesz_third = get_post_meta($tid, 'game_cache_third', true);

        if(isset($first_third_place) && isset($second_third_place) && $gamesz_third['match_post_id'] == 'unset'){

            $tournament = new Arcane_Tournaments();
            $tehpost = get_post($tid);
                  $dataz = array(
                    'pid' => $tehpost->ID,
                    'title' => $tehpost->post_title,
                    'description' => $tehpost->post_content,
                    'participants' => 0,
                    'tournament_format' => esc_html__('Knockout', 'arcane'),
                    'tournament_slug' => 'knockout',
                    'game' => get_post_meta($tehpost->ID, 'tournament_game', true),
                    'maps' =>  get_post_meta($tehpost->ID, 'tournament_maps', true),
                    'tournament_contestants' => get_post_meta($tehpost->ID, 'tournament_contestants', true),
                    'tournament_games_format'=> get_post_meta($tehpost->ID, 'tournament_games_format', true),
                  );

                $mid = $tournament->ScheduleMatch($tid, $dataz['tournament_contestants'],$first_third_place,$second_third_place, $tehpost->post_title." - ".esc_html__ ("Third place", "arcane"), $vreme,  $dataz['game'], $dataz['description']."<br />".esc_html__ ("Third place match", "arcane"), $dataz['maps'], $dataz['tournament_games_format']);

                $contestants = get_post_meta($tid, 'tournament_contestants', true);
                if($contestants == 'team') {
                    $t1 = get_post($first_third_place);
                    $t1_name = $t1->post_title;
                    $url1 = get_permalink($first_third_place);

                    $t2 = get_post($second_third_place);
                    $t2_name = $t2->post_title;
                    $url2 = get_permalink($second_third_place);
                } else {
                    $t1 = get_user_by('id', $first_third_place);
                    $t1_name = $t1->display_name;
                    $url1 =  bp_core_get_user_domain($first_third_place);

                    $t2 = get_user_by('id', $second_third_place);
                    $t2_name = $t2->display_name;
                    $url2 =  bp_core_get_user_domain($second_third_place);
                }

                   $games = array(
                    'match_post_id' => $mid,
                    'teams' => array(

                     array(
                        'id' => $first_third_place,
                        'name' => $t1_name,
                        'url' => esc_url($url1),
                        'score' => -1
                    ),
                     array(
                        'id' => $second_third_place,
                        'name' => $t2_name,
                        'url' => esc_url($url2),
                        'score' => -1
                    ),
                ),
                'time' => $vreme
                );
                update_post_meta($tid, 'game_cache_third', $games);


        }



    if(isset($gamesz_third['match_post_id']) && $gamesz_third['match_post_id'] == $match_id){
        $contestants = get_post_meta($tid, 'tournament_contestants', true);
        if($contestants == 'team') {
            $t1 = get_post($first_third_place);
            $t1_name = $t1->post_title;
            $url1 = get_permalink($first_third_place);

            $t2 = get_post($second_third_place);
            $t2_name = $t2->post_title;
            $url2 = get_permalink($second_third_place);
        } else {
            $t1 = get_user_by('id', $first_third_place);
            $t1_name = $t1->display_name;
            $url1 =  bp_core_get_user_domain($first_third_place);

            $t2 = get_user_by('id', $second_third_place);
            $t2_name = $t2->display_name;
            $url2 =  bp_core_get_user_domain($second_third_place);
        }

        $games_final = array(
                    'match_post_id' => $match_id,
                    'teams' => array(

                     array(
                        'id' => $first_third_place,
                        'name' => $t1_name,
                        'url' => esc_url($url1),
                        'score' => $match->team1_tickets
                    ),
                     array(
                        'id' => $second_third_place,
                        'name' => $t2_name,
                        'url' => esc_url($url2),
                        'score' => $match->team2_tickets
                    ),
                ),
                'time' => $vreme
                );
           update_post_meta($tid, 'game_cache_third', $games_final);
    }
    /*/mec za trece mesto*/
}
}


if (!function_exists( 'arcane_check_expired_membership' )){
function arcane_check_expired_membership(){
if (class_exists( 'WC_Product_Pay_To_Join' )){

    /****global membership****/
    $users_global = get_users(array(
        'meta_key'     => 'membership_all_tournament',
    ));

    foreach($users_global as $usr){


        $time = get_user_meta($usr->ID, 'membership_all_tournament_date_added', true);
        $current_time = current_time( 'timestamp',1);
        $pids = arcane_get_product_id_for_general_membership();
        foreach($pids as $pid){
            $expire_days = get_post_meta($pid->post_id, '_join_tournament_expire', true);
            if(isset($expire_days) && !empty($expire_days)){
                $diff = $current_time - $time;
                $total_diff = round($diff/86400,3);
                if($total_diff >= (int)$expire_days ){
                    delete_user_meta( $usr->ID, 'membership_all_tournament' );
                    delete_user_meta( $usr->ID, 'membership_all_tournament_date_added' );
                }
            }
        }

    }


    /****tournament membership****/
    $users_games = get_users(array(
        'meta_key'     => 'paid_games',
    ));

    foreach($users_games as $usr){

        $paid_games = get_user_meta($usr->ID, 'paid_games', true);

        if(isset($paid_games) && is_array($paid_games)){

            foreach($paid_games as $paid_game){
                $time = get_user_meta($usr->ID, 'paid_games_date_added'.$paid_game, true);

                $timezone_string = arcane_timezone_string();
                $timezone = $timezone_string ? $timezone_string : 'UTC';
                $time_now = new DateTime("now", new DateTimeZone($timezone));
                $current_time = $time_now->getTimestamp();

                $pids = arcane_get_product_id_by_game_id($paid_game);
                foreach($pids as $pid){
                    $expire_days = get_post_meta($pid->post_id, '_join_tournament_expire', true);
                    if(isset($expire_days) && !empty($expire_days)){
                        $diff = $current_time - $time;
                        $total_diff = round($diff/86400,3);
                        if($total_diff >= (int)$expire_days ){
                            if(count($paid_games) == 1){
                                 delete_user_meta( $usr->ID, 'paid_games');
                            }else{
                                if (($key = array_search($paid_game, $paid_games)) !== false) {
                                    unset($paid_games[$key]);
                                    update_user_meta($usr->ID, 'paid_games', $paid_games);
                                }
                            }
                            delete_user_meta( $usr->ID, 'paid_games_date_added'.$paid_game );
                        }
                    }
                }
            }

        }
    }
}

}
}



function arcane_twitch_tv_screen() {
    //add title and content here - last is to call the members plugin.php template
    add_action( 'bp_template_title', 'arcane_twitch_tv_title' );
    add_action( 'bp_template_content', 'arcane_twitch_tv_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function arcane_twitch_tv_title() {

    esc_html_e('Twitch Tv Channel','arcane');

}



function arcane_twitch_tv_content() { ?>
    <div id="twitch-embed"></div>
    <?php
        $channelName = bp_get_profile_field_data('field=Twitch Channel&user_id=' . bp_get_member_user_id() );
        wp_add_inline_script( 'arcane-global', 'let embed = new Twitch.Embed("twitch-embed", {
        width: "100%",
        height: 480,
        channel: "'.$channelName.'",
        theme: "dark",
        autoplay: false
    });
    
    embed.addEventListener(Twitch.Embed.VIDEO_READY, () => {
        var player = embed.getPlayer();
        player.play();
    });' );

}



function arcane_youtube_screen() {

    //add title and content here - last is to call the members plugin.php template
    add_action( 'bp_template_title', 'arcane_youtube_channel_title' );
    add_action( 'bp_template_content', 'arcane_youtube_channel_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );

}

function arcane_youtube_channel_title() {

    esc_html_e('YouTube Channel','arcane');

}



function arcane_youtube_channel_content() {

	$ytcName = bp_get_profile_field_data('field=YTCName&user_id=' . bp_get_member_user_id() );

	if( !empty($ytcName) ) {

		?>

		<div class="wpb_column vc_column_container vc_col-sm-6">

	    <?php echo do_shortcode('[yotuwp type="channel" id="'. esc_attr($ytcName).'"]');?>

		</div>

	<?php

	}

}

//add youtube and twitch channel fields
function arcane_xprofile_channel_fields()
{

   $xfield_args =  array (
       'field_group_id'  => 1,
       'name'            => esc_html__('YTCName','arcane'),
       'can_delete'     => true,
       'description'    =>  esc_html__('Add your YouTube channel here. Channel will show automatically in your profile. Example: If your Youtube URL looks like this "https://www.youtube.com/yourchannel" then enter just "yourchannel" part.', 'arcane'),
       'field_order'     => 1,
       'is_required'     => false,
       'type'            => 'textbox'
    );

   if(function_exists('xprofile_insert_field')){
       if(!is_int(xprofile_get_field_id_from_name('YTCName'))){
            xprofile_insert_field( $xfield_args );
       }
   }

  $xfield_args =  array (
       'field_group_id'  => 1,
       'name'            => esc_html__('Twitch Channel','arcane'),
       'can_delete'     => true,
       'description'    =>  esc_html__('Add your Twitch channel here. Channel will show automatically in your profile. Example: If your Twitch URL looks like this "https://www.twitch.tv/yourchannel" then enter just "yourchannel" part.', 'arcane'),
       'field_order'     => 1,
       'is_required'     => false,
       'type'            => 'textbox'
    );

   if(function_exists('xprofile_insert_field')){
       if(!is_int(xprofile_get_field_id_from_name('Twitch Channel'))){
            xprofile_insert_field( $xfield_args );
       }
   }

}

add_filter( 'wp_kses_allowed_html', 'arcane_add_source_tag', 10, 2 );
function arcane_add_source_tag( $tags, $context ) {
    if ( 'post' === $context ) {
        $tags['iframe'] = array(
            'src'    => true,
            'srcdoc' => true,
            'width'  => true,
            'height' => true,
        );
    }
    return $tags;
}

/**
 * Print match date
*
* @param $match_unix
 */
function arcane_echo_match_date($match_unix){

    $timezone_string = arcane_timezone_string();
    $timezone = $timezone_string ? $timezone_string : 'UTC';

    $currentTime = DateTime::createFromFormat( 'U', $match_unix);
    $currentTime->setTimeZone(new DateTimeZone($timezone));
    $formattedString = $currentTime->format( get_option('date_format') . ', ' . get_option('time_format'));


    echo esc_html($formattedString);

}

/**
 * Parse date to unix format
*
* @param $date
 *
 *@return false|int
*/
function arcane_parse_date_to_unix($date){

    $timezone_string = arcane_timezone_string();
	$timezone = $timezone_string ? $timezone_string : 'UTC';
	date_default_timezone_set($timezone);

    if(get_option('date_format') == 'd/m/Y'){
		$timedatenew=  date('Y-m-d'.' '.get_option('time_format'), strtotime(str_replace('/', '-', $date)));
		$returnUnix = strtotime($timedatenew);
	}elseif(get_option('date_format') == 'm/d/Y'){
		$timedatenew=  date('Y-d-m'.' '.get_option('time_format'), strtotime(str_replace('/', '-', $date)));
		$returnUnix = strtotime($timedatenew);
	}else{
		$returnUnix = strtotime($date);
	}

    return $returnUnix;

}


/*include important files*/
//used sylesheet_directory for child theme
require_once  'addons/team-wars/wp-teamwars.php';
require_once 'inc/lib/class-tgm-plugin-activation.php';
require_once 'addons/tournaments/main.php';
require_once 'functions_lj.php';
require_once 'inc/lib/class-arcane-updater.php';
require_once 'inc/lib/class-arcane-envato-api.php';
require_once 'inc/lib/class-arcane-product-activation.php';


if(class_exists('Arcane_Types')){
    require_once ( WP_PLUGIN_DIR . '/arcane_custom_post_types/widgets/info/info-widget.php');
    require_once ( WP_PLUGIN_DIR . '/arcane_custom_post_types/widgets/rating/popular-widget.php');
    require_once ( WP_PLUGIN_DIR . '/arcane_custom_post_types/widgets/latest_posts/latest_posts.php');
    require_once ( WP_PLUGIN_DIR . '/arcane_custom_post_types/widgets/tournament_carousel/tournament_carousel.php');
    require_once ( WP_PLUGIN_DIR . '/arcane_custom_post_types/widgets/match_carousel/match_carousel.php');
    require_once ( WP_PLUGIN_DIR . '/arcane_custom_post_types/rating/rating-include.php');
}




if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
}

if ( ! function_exists( 'arcane_enqueue_comments_reply' ) ) {
    function arcane_enqueue_comments_reply() {
        if( get_option( 'thread_comments' ) )  {
            wp_enqueue_script( 'comment-reply' );
        }
    }
    add_action( 'comment_form_before', 'arcane_enqueue_comments_reply' );
}

/**
 * Custom excerpt
 *
 * @param $limit
 * @param null $post_id
 *
 * @return string
 */
if ( ! function_exists( 'arcane_excerpt' ) ) {
    function arcane_excerpt($limit, $post_id = null) {

        if(has_excerpt($post_id)) {
            $the_excerpt = get_the_excerpt($post_id);
            $the_excerpt = preg_replace('/\[[^\]]+\]/', '', $the_excerpt);
            return wp_trim_words($the_excerpt, $limit);
        } else {
            $the_content = get_the_content($post_id);
            $the_content = preg_replace('/\[[^\]]+\]/', '', $the_content);
            return wp_trim_words($the_content, $limit);
        }
    }
}

/**
 * Return game image
 *
 * @param $game_id
 *
 * @return false|string
 */
if ( ! function_exists( 'arcane_return_game_image_nocrop' ) ) {
    function arcane_return_game_image_nocrop($game_id){
        global $wpdb;
        $games = $wpdb->prefix."cw_games";
        if(class_exists('Arcane_TeamWars')){
            $game_img = $wpdb->get_results($wpdb->prepare("SELECT `icon` FROM $games WHERE `id`= %s", $game_id));
        }

        if(isset($game_img[0]->icon))
            {$image = wp_get_attachment_url( $game_img[0]->icon);} //get img URL

        if(empty($image)){ $image = get_theme_file_uri('img/defaults/gamedef.png');  }
        return $image;

    }
}



/**
 * Page metaboxes
 *
 * @param $meta_boxes
 *
 * @return array
 */
/*metaboxes*/

add_filter( 'rwmb_meta_boxes', 'arcane_page_meta_boxes' );
function arcane_page_meta_boxes($meta_boxes ) {

    /*MIXED*/
    $meta_boxes[] = array(
        'title'      => esc_html__( 'Page Header', 'arcane' ),
        'post_types' => array('page', 'post'),
        'fields'     => array(
            array(
                'type'    => 'select',
                'id'      => 'page_header',
                'name'    => esc_html__( 'Show Page Header', 'arcane' ),
                'options' => array(
                    '' => '',
                    'yes' => esc_html__( 'Yes', 'arcane' ),
                    'no' => esc_html__( 'No', 'arcane' )
                ),
                'flatten' => false,
                'desc'   => esc_html__( 'Use this option to turn header on/off.', 'arcane' ),
            ),
            array(
                'type'    => 'image_advanced',
                'id'      => 'page_header_image',
                'name'    => esc_html__( 'Header Image', 'arcane' ),
                'desc'      => esc_html__( 'Choose header image.', 'arcane' ),

            ),
             array(
                'type'    => 'checkbox',
                'id'      => 'page_header_image_remove',
                'name'    => esc_html__( 'Remove Header Image', 'arcane' ),
            ),

        ),
    );


     $meta_boxes[] = array(
        'title'      => esc_html__( 'Header', 'arcane' ),
        'post_types' => array('page'),
        'fields'     => array(

                  array(
                'type'    => 'select',
                'id'      => 'header-color-selector',
                'name'    => esc_html__( 'Background Color Option', 'arcane' ),
                'options' => array(
                    '' => '',
                    'color' => esc_html__('Color', 'arcane'),
					'gradient' => esc_html__('Color Gradient', 'arcane')
                ),
                'flatten' => false,
                'desc'   => esc_html__( 'Choose color option.', 'arcane' ),
            ),
             array(
                'type'    => 'color',
                'id'      => 'header-color',
                'name'    => esc_html__( 'Color', 'arcane' ),
                'alpha_channel' => true
            ),
             array(
                'type'    => 'color',
                'id'      => 'header-gradient-top',
                'name'    => esc_html__( 'Color Gradient Top', 'arcane' ),
                'alpha_channel' => true
            ),
            array(
                'type'    => 'color',
                'id'      => 'header-gradient-bottom',
                'name'    => esc_html__( 'Color Gradient Bottom', 'arcane' ),
                'alpha_channel' => true
            ),
             array(
                'type'    => 'checkbox',
                'id'      => 'page_header_ticker_remove',
                'name'    => esc_html__( 'Remove News Ticker', 'arcane' ),
            ),
        ),
    );

     $meta_boxes[] = array(
        'title'      => esc_html__( 'Page', 'arcane' ),
        'post_types' => array('page'),
        'fields'     => array(
            array(
                'type'    => 'image_advanced',
                'id'      => 'page_background_image',
                'name'    => esc_html__( 'Page Background Image', 'arcane' ),
                'desc'      => esc_html__( 'Choose page background image.', 'arcane' ),

            ),
            array(
                'type'    => 'color',
                'id'      => 'page-bck-color',
                'name'    => esc_html__( 'Background Color', 'arcane' ),
                'alpha_channel' => true
            ),
            array(
                'type'    => 'checkbox',
                'id'      => 'page_image_remove',
                'name'    => esc_html__( 'Remove Page Image', 'arcane' ),
            ),
        ),
    );

     $meta_boxes[] = array(
        'title'      => esc_html__( 'Menu', 'arcane' ),
        'post_types' => array('page'),
        'fields'     => array(

             array(
                'type'    => 'color',
                'id'      => 'menu-color',
                'name'    => esc_html__( 'Menu color', 'arcane' ),
                'alpha_channel' => true
            ),
       ),
    );


    return $meta_boxes;
}


if (!function_exists('arcane_get_permalink_for_template')) {
    function arcane_get_permalink_for_template($name){

        $args = [
            'post_type' => 'page',
            'fields' => 'ids',
            'nopaging' => true,
            'meta_key' => '_wp_page_template',
            'meta_value' => $name
        ];

        $pages = get_posts($args);

        $page_id = '';

        if (isset($pages[0]))
            {$page_id = $pages[0];}

        $link = get_permalink($page_id);

        return esc_url($link);
    }
}


/**
 * Return player image
 *
 * @param $player_id
 * @param $width
 * @param $height
 *
 * @return array|bool|string
 */
if(!function_exists('arcane_return_player_image_fn')){
function arcane_return_player_image_fn($player_id, $width, $height){

    	$pf_url = get_user_meta( $player_id, 'profile_photo', true );
    	$pfimage = get_theme_file_uri( 'img/defaults/default-team.jpg' );

        if ( ! empty( $pf_url ) ) {
            $imagebg = arcane_aq_resize( $pf_url, $width, $height, true, true, true ); //resize & crop img
            if ( $imagebg ) {
                $pfimage = $imagebg;
            }
        }

    return $pfimage;
}
}

/**
* Convert teams from old Arcane structure
 */
if(!function_exists('arcane_convert_teams')){
function arcane_convert_teams(){

    $team_converted = get_option('team_converted');

    if(!$team_converted){
        $args = [
            'post_type'      => 'team',
            'posts_per_page' => -1,
        ];

        $team_posts = get_posts( $args );

        foreach ($team_posts as $team_post){
            $content = arcane_get_string_between($team_post->post_content, 'el_text_title="About us"]', '[/vc_column_boxed_text]');
            $country = arcane_get_string_between($team_post->post_content, 'vc_members_team_page el_countries="', '" el_languages="');
            $language = arcane_get_string_between($team_post->post_content, 'el_languages="', '" el_link_text1');

            $my_post = [
              'ID'           => $team_post->ID,
              'post_content' => $content,
          ];

        wp_update_post( $my_post );
        update_post_meta($team_post->ID, 'language', $language );
        update_post_meta($team_post->ID, 'location', $country );
        }

        update_option('team_converted', 1, true);
    }

}
add_action('after_switch_theme', 'arcane_convert_teams');
}

/**
 * Find value between two strings
*
* @param $string
* @param $start
* @param $end
 *
 * @return false|string
 */
if(!function_exists('arcane_get_string_between')){
function arcane_get_string_between($string, $start, $end){
	$string = ' ' . $string;
	$ini = strpos($string, $start);
	if ($ini == 0) {return '';}
	$ini += strlen($start);
	$len = strpos($string, $end, $ini) - $ini;
	return substr($string, $ini, $len);
}
}

/**
* Add notification if theme not activated
 */
if(!function_exists('arcane_activation_admin_notice')){
function arcane_activation_admin_notice(){
    global $pagenow;

    $arcane_activator = new Arcane_Product_Registration();
    $is_registered    = $arcane_activator->is_registered();

    if ( ($pagenow == 'admin.php' || $pagenow == 'plugins.php' || $pagenow == 'themes.php') && !$is_registered && class_exists('Redux_Framework_Plugin') ) {
         echo '<div class="notice notice-error notice-activatetheme">
             <p><a href="'.get_admin_url().'admin.php?page=Arcane&tab=32">Please activate Arcane in Theme Options to unlock all the features!</a></p>
         </div>';
    }
}
add_action('admin_notices', 'arcane_activation_admin_notice');
}


/**
 * Set menus and front page for importer
 *
 * @param $demo_active_import
 * @param $demo_directory_path
 */
function arcane_import_additional_resources($demo_active_import, $demo_directory_path) {
    reset( $demo_active_import );
    $current_key = key( $demo_active_import );

    /************************************************************************
     * Import slider
     *************************************************************************/

    if ( class_exists( 'LS_Config' ) ) {
        include LS_ROOT_PATH.'/classes/class.ls.importutil.php';


        $wbc_sliders_array = array(
            'Arcane' => ['homepage.zip','homepage-mobile.zip', 'player.zip', 'player-mobile.zip' ] //Set slider zip name
        );

        if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_sliders_array ) ) {
            $wbc_slider_import = $wbc_sliders_array[$demo_active_import[$current_key]['directory']];

            if( is_array( $wbc_slider_import ) ){
                foreach ($wbc_slider_import as $slider_zip) {
                    if ( !empty($slider_zip) && file_exists( $demo_directory_path.$slider_zip ) ) {
                        $slider = new LS_ImportUtil($demo_directory_path.$slider_zip );
                    }
                }
            }else{
                if ( file_exists( $demo_directory_path.$wbc_slider_import ) ) {
                    $slider = new LS_ImportUtil($demo_directory_path . $wbc_slider_import);
                }
            }
        }

    }

    /************************************************************************
    * Setting Menus
    *************************************************************************/
    // If it's demo1 - demo6
    $wbc_menu_array = array( 'Arcane');
    if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && in_array( $demo_active_import[$current_key]['directory'], $wbc_menu_array ) ) {
        $top_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
        if ( isset( $top_menu->term_id ) ) {
            set_theme_mod( 'nav_menu_locations', array(
                    'header-menu' => $top_menu->term_id,
                )
            );
        }
    }
    /************************************************************************
    * Set HomePage
    *************************************************************************/
    // array of demos/homepages to check/select from
    $wbc_home_pages = array(
        'Arcane' => esc_html__('Homepage', 'arcane'),
    );
    if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_home_pages ) ) {
        $page = get_page_by_title( $wbc_home_pages[$demo_active_import[$current_key]['directory']] );
        if ( isset( $page->ID ) ) {
            update_option( 'page_on_front', $page->ID );
            update_option( 'show_on_front', 'page' );
        }
    }


}
add_action( 'wbc_importer_after_content_import', 'arcane_import_additional_resources', 10, 2 );

if(!function_exists('arcane_team_image_metabox_callback')){
function arcane_team_image_metabox_callback ( $post ) {

    wp_nonce_field( basename( __FILE__ ), 'teamimage_nonce' );

    $team_bg_url = get_post_meta( $post->ID, 'team_bg', true );
    if(empty($team_bg_url))$team_bg_url = get_theme_file_uri('img/defaults/default-banner.jpg');

    $team_photo = get_post_meta( $post->ID, 'team_photo', true );
    if(empty($team_photo))$team_photo = get_theme_file_uri('img/defaults/default-team.jpg');

    if ( !did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }
?>
    <p class="upload"><label><?php esc_html_e('Team Photo', 'arcane'); ?></label><br />
        <img alt="img" src="<?php echo esc_url($team_photo); ?>" class="preview-upload"/>
        <input type="text" class="hidden" name="team_photo" value="<?php echo esc_url($team_photo); ?>"/>
        <button type="submit" class="upload_image_button upload_image_button_admin button"><?php esc_html_e("Upload Image", "arcane"); ?></button>
        <button type="submit" class="remove_image_button remove_image_button_admin button">&times;</button>
    </p>
    <p class="upload"><label><?php esc_html_e('Team Background', 'arcane'); ?></label><br />
        <img alt="img" src="<?php echo esc_url($team_bg_url); ?>" class="preview-upload"/>
        <input type="text" class="hidden" name="team_bg" value="<?php echo esc_url($team_bg_url); ?>"/>
        <button type="submit" class="upload_image_button upload_image_button_admin button "><?php esc_html_e("Upload Image", "arcane"); ?></button><!--imgid sklonjeno -->
        <button type="submit" class="remove_image_button remove_image_button_admin button">&times;</button>
    </p>

<?php

}
}

if(!function_exists('arcane_team_image_metabox_save')){
function arcane_team_image_metabox_save($post_id) {

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;

    if ( ! isset( $_POST['teamimage_nonce'] ) || ! wp_verify_nonce( $_POST['teamimage_nonce'], basename( __FILE__ ) ) )
        return;

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return;

    $pos = get_post($post_id);
    if ($pos->post_type == 'team' && (strpos($_POST['_wp_http_referer'], '&action=edit') !== false) or strpos($_POST['_wp_http_referer'], 'post-new.php') !== false) {
        update_post_meta($post_id, 'team_photo', esc_url($_POST['team_photo']));
        update_post_meta($post_id, 'team_bg', esc_url($_POST['team_bg']));
    }
    return $post_id;
}
}