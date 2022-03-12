<?php
/**
* Plugin Name: Arcane types
* Plugin URI: https://skywarriorthemes.com/
* Description: Custom post types for Arcane theme.
* Version: 2.4
* Author: Skywarrior themes
* Author URI: https://www.skywarriorthemes.com/
* License: GPL
*/



class Arcane_Types {

    function __construct() {
        register_activation_hook( __FILE__,array( $this,'activate' ) );
        add_action( 'init', array( $this, 'arcane_create_post_types' ), 1 );
    }

    function activate() {
        $this->arcane_create_post_types();
    }

    function arcane_create_post_types() {

	if(class_exists('Arcane_TeamWars')){
		global $ArcaneWpTeamWars, $wpdb;

		$table_prefix = $wpdb->prefix;
        $dbstruct1 = '';
        $dbstruct2 = '';
        $dbstruct1 .= "CREATE TABLE IF NOT EXISTS {$table_prefix}cw_games (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `title` varchar(200) NOT NULL,
                      `abbr` varchar(20) DEFAULT NULL,
                      `icon` bigint(20) unsigned DEFAULT NULL,
                      `g_banner_file` bigint(20) unsigned DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `g_banner_file` (`g_banner_file`),
                      KEY `icon` (`icon`),
                      KEY `title` (`title`),
                      KEY `abbr` (`abbr`)
                    ) CHARACTER SET utf8;";

        $wpdb->query($dbstruct1);

        $dbstruct2 .= "CREATE TABLE IF NOT EXISTS {$table_prefix}cw_maps (
                      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `game_id` int(10) unsigned NOT NULL,
                      `title` varchar(200) NOT NULL,
                      `screenshot` bigint(20) unsigned DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `game_id` (`game_id`,`screenshot`)
                    ) CHARACTER SET utf8;";

        $wpdb->query($dbstruct2);



		if(!function_exists('on_admin_menu')) {
			function on_admin_menu() {
				global $ArcaneWpTeamWars, $current_user;

				$acl_table = array(
					'manage_matches' => 'manage_options',
					'manage_games'   => 'manage_options',
					'manage_teams'   => 'manage_options'
				);

				$keys = array_keys( $acl_table );
				$user_role = $current_user->roles[0];

				for ( $i = 0; $i < sizeof( $keys ); $i ++ ) {
					if ( $ArcaneWpTeamWars->acl_user_can( $keys[ $i ] ) ) {
						$acl_table[ $keys[ $i ] ] = $user_role;
					}
				}

				add_menu_page( esc_html__( 'TeamWars', 'arcane_custom_post_types' ), esc_html__( 'TeamWars', 'arcane_custom_post_types' ), 'subscriber', 'wp-teamwars.php', null, get_theme_file_uri( 'img/plugin-icon.png' ) );
				$ArcaneWpTeamWars->page_hooks['teams'] = add_submenu_page( 'wp-teamwars.php', esc_html__( 'Teams', 'arcane_custom_post_types' ), esc_html__( 'Teams', 'arcane_custom_post_types' ), $acl_table['manage_teams'], 'wp-teamwars-teams', array(
					$ArcaneWpTeamWars,
					'on_teams_redirect'
				) );
				$ArcaneWpTeamWars->page_hooks['games'] = add_submenu_page( 'wp-teamwars.php', esc_html__( 'Games', 'arcane_custom_post_types' ), esc_html__( 'Games', 'arcane_custom_post_types' ), $acl_table['manage_games'], 'wp-teamwars-games', array(
					$ArcaneWpTeamWars,
					'on_manage_games'
				) );

				$ArcaneWpTeamWars->page_hooks['tournaments'] = add_submenu_page( 'wp-teamwars.php', esc_html__( 'Tournaments', 'arcane_custom_post_types' ), esc_html__( 'Tournaments', 'arcane_custom_post_types' ), 'manage_options', 'wp-teamwars-tournaments', array(
					$ArcaneWpTeamWars,
					'on_tournaments'
				) );
				$ArcaneWpTeamWars->page_hooks['matches']     = add_submenu_page( 'wp-teamwars.php', esc_html__( 'Matches', 'arcane_custom_post_types' ), esc_html__( 'Matches', 'arcane_custom_post_types' ), $acl_table['manage_matches'], 'wp-teamwars-matches', array(
					$ArcaneWpTeamWars,
					'on_manage_matches'
				) );

				remove_submenu_page( 'wp-teamwars.php', 'wp-teamwars.php' );
				remove_menu_page( 'edit.php?post_type=team' );
				remove_submenu_page( 'wp-teamwars.php', 'edit.php?post_type=team' );
				add_action( 'load-' . $ArcaneWpTeamWars->page_hooks['matches'], array(
					$ArcaneWpTeamWars,
					'on_load_manage_matches'
				) );
				add_action( 'load-' . $ArcaneWpTeamWars->page_hooks['games'], array(
					$ArcaneWpTeamWars,
					'on_load_manage_games'
				) );

			}
		}

		add_shortcode('arcane-teamwars', array($ArcaneWpTeamWars,  'on_shortcode'));
		add_action('admin_menu', 'on_admin_menu');

     }

	//--------------------------------------------------
		//--------------First time init---------------------
		//--------------------------------------------------


			//code annotation
			$labels = array(
			    'name' => esc_html_x('Tournaments', 'post type general name','arcane_custom_post_types'),
			    'singular_name' => esc_html_x('Tournament', 'post type singular name','arcane_custom_post_types'),
			    'add_new' => esc_html_x('Add New', 'vendor item' ,'arcane_custom_post_types'),
			    'add_new_item' => esc_html__('Add New Tournament','arcane_custom_post_types'),
			    'edit_item' => esc_html__('Edit Tournament','arcane_custom_post_types'),
			    'new_item' => esc_html__('New Tournament','arcane_custom_post_types'),
			    'view_item' => esc_html__('View Tournament','arcane_custom_post_types'),
			    'search_items' => esc_html__('Search Tournaments','arcane_custom_post_types'),
			    'not_found' =>  esc_html__('Nothing found','arcane_custom_post_types'),
			    'not_found_in_trash' => esc_html__('Nothing found in Trash','arcane_custom_post_types'),
			    'parent_item_colon' => ''
			);
			$args = array(
			    'labels' => $labels,
			    'public' => true,
			    'publicly_queryable' => true,
			    'show_ui' => true,
			    'query_var' => true,
			    'menu_icon' => get_template_directory_uri() . '/img/account.png',
			    'rewrite' => ['slug' => 'tournament', 'with_front' => false],
			    'capability_type' => 'post',
			    'hierarchical' => false,
			    'menu_position' => null,
			    'supports' => array('title','author', 'editor', 'thumbnail'),
				'capabilities' => array(
					'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
				),
				'map_meta_cap' => true,
				'show_in_menu' => false,
			   );
			register_post_type( 'tournament' , $args );


	/******TEAM WARS************/


		 $labels = array(
        'name' => _x('Matches', 'post type general name','arcane_custom_post_types'),
        'singular_name' => _x('Match', 'post type singular name','arcane_custom_post_types'),
        'add_new' => _x('Add New', 'match item' ,'arcane_custom_post_types'),
        'add_new_item' => esc_html__('Add New Match','arcane_custom_post_types'),
        'edit_item' => esc_html__('Edit Match','arcane_custom_post_types'),
        'new_item' => esc_html__('New Match','arcane_custom_post_types'),
        'view_item' => esc_html__('View Match','arcane_custom_post_types'),
        'search_items' => esc_html__('Search Matches','arcane_custom_post_types'),
        'not_found' =>  esc_html__('Nothing found','arcane_custom_post_types'),
        'not_found_in_trash' => esc_html__('Nothing found in Trash','arcane_custom_post_types'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'matches', 'with_front' => false],
        'capability_type' => 'post',
          'capabilities' => array(
            'create_posts' => false, // Removes support for the "Add New" function
          ),
        'supports' => array('title','editor','thumbnail', 'comments'),
        'show_in_menu' => false

      );
    register_post_type( 'matches' , $args );


	  register_post_type( 'team',
        array(
            'labels' => array(
                'name' => esc_html__( 'Teams', 'arcane_custom_post_types' ),
                'singular_name' => esc_html__( 'Team', 'arcane_custom_post_types' )
            ),
        'supports' => array( 'title', 'editor', 'shortlinks', 'comments', 'author' ),
        'public' => true,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'has_archive' => false,
        'query_var' => true,
        'rewrite' => ['slug' => 'team', 'with_front' => false],
        'show_in_menu' => 'wp-teamwars.php',
        'menu_icon' => plugin_dir_url(__FILE__) . 'img/teams.png',
        )
    );


if(get_page_by_path('team-challenge') == ""){
//create my-projects page
$post = array(
  'post_name' => 'team-challenge',
  'post_status' => 'publish',
  'post_title' => esc_html__( 'Team challenge', 'arcane' ),
  'post_type' => 'page',
  'post_author'   => 1
);
$id_page = wp_insert_post( $post );
update_post_meta($id_page, "_wp_page_template", "tmp-team-challenge.php");
}

if(get_page_by_path('team-creation') == ""){
//create my-projects page
    $post = array(
	    'post_name' => 'team-creation',
	    'post_status' => 'publish',
	    'post_title' => esc_html__( 'Team creation', 'arcane' ),
	    'post_type' => 'page',
	    'post_author'   => 1
    );
    $id_page = wp_insert_post( $post );
    update_post_meta($id_page, "_wp_page_template", "tmp-team-creation.php");
}

if(get_page_by_path('tournament-creation') == ""){
//create my-projects page
    $post = array(
	    'post_name' => 'tournament-creation',
	    'post_status' => 'publish',
	    'post_title' => esc_html__( 'Tournament creation', 'arcane' ),
	    'post_type' => 'page',
	    'post_author'   => 1
    );
    $id_page = wp_insert_post( $post );
    update_post_meta($id_page, "_wp_page_template", "tmp-tournament-creation.php");
}

if(get_page_by_path('user-registration') == ""){
//create my-projects page
$post = array(
  'post_name' => 'user-registration',
  'post_status' => 'publish',
  'post_title' => esc_html__('Registration', 'arcane'),
  'post_type' => 'page',
  'post_author'   => 1
);
$id_page = wp_insert_post( $post );
update_post_meta($id_page, "_wp_page_template", "page-user-registration.php");
}

if(get_page_by_path('user-activation') == ""){
//create my-projects page
$post = array(
  'post_name' => 'user-activation',
  'post_status' => 'publish',
  'post_title' => esc_html__('Activation', 'arcane'),
  'post_type' => 'page',
  'post_author'   => 1
);
$id_page = wp_insert_post( $post );
update_post_meta($id_page, "_wp_page_template", "page-user-activation.php");
}

if(get_page_by_path('all-teams-for-game') == ""){
//create my-projects page
$post = array(
  'post_name' => 'all-teams-for-game',
  'post_status' => 'publish',
  'post_title' => esc_html__( 'List of all teams for selected game', 'arcane' ),
  'post_type' => 'page',
  'post_author'   => 1
);
$id_page = wp_insert_post( $post );
update_post_meta($id_page, "_wp_page_template", "page-all-teams-for-game.php");
}


if(get_page_by_path('lost-password') == ""){
//create my-projects page
$post = array(
  'post_name' => 'lost-password',
  'post_status' => 'publish',
  'post_title' => esc_html__('Reset your password', 'arcane'),
  'post_type' => 'page',
  'post_author'   => 1
);
$id_page = wp_insert_post( $post );
update_post_meta($id_page, "_wp_page_template", "page-user-lost-password.php");
}




}

static function arcane_send_email($user_email, $subject, $message, $headers){
    wp_mail( $user_email, $subject, $message, $headers );
}

}
$arcane_Types = new Arcane_Types();


function arcane_title_filter_fix(){
    remove_filter( 'posts_where', 'arcane_title_filter', 10);
}

/*add meta boxes*/
//  rating code start
add_action( 'add_meta_boxes', 'arcane_meta_box_add' );
function arcane_meta_box_add()
{
add_meta_box( 'my-meta-box-id', esc_html__('Review Info', 'arcane'), 'arcane_meta_box_cb', 'post', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'arcane_team_image_metabox' );
function arcane_team_image_metabox () {
    add_meta_box( 'teamimage', esc_html__( 'Team Images', 'arcane' ), 'arcane_team_image_metabox_callback', 'team', 'side', 'low');
}

/**************************************** /All teams page pagination and search**********************************************/

add_action( 'wp_ajax_addremove_friend', 'arcane_bp_legacy_theme_ajax_addremove_friend',1 );
add_action( 'wp_ajax_nopriv_addremove_friend', 'arcane_bp_legacy_theme_ajax_addremove_friend',1 );
/*buddypress friends ajax*/
if(!function_exists('arcane_bp_legacy_theme_ajax_addremove_friend')){
	function arcane_bp_legacy_theme_ajax_addremove_friend() {
		if (filter_has_var(INPUT_SERVER, "REQUEST_METHOD")) {
			$requestmethod = filter_input(INPUT_SERVER, "REQUEST_METHOD", FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE);
		} else {
			if (isset($_SERVER["REQUEST_METHOD"]))
				$requestmethod = filter_var($_SERVER["REQUEST_METHOD"], FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE);
			else
				$requestmethod = null;
		}
		// Bail if not a POST action
		if ( 'POST' !== strtoupper($requestmethod ) )
			return;

		// Cast fid as an integer
		$friend_id = (int) $_POST['fid'];

		// Trying to cancel friendship
		if ( 'is_friend' == BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id ) ) {
			check_ajax_referer( 'friends_remove_friend' );

			if ( ! friends_remove_friend( bp_loggedin_user_id(), $friend_id ) ) {
				echo esc_html__( 'Friendship could not be canceled.', 'arcane' );
			} else {
				echo '<a id="friend-' . esc_attr( $friend_id ) . '" class="add friendship-button add-friend" rel="add" title="' . esc_html__( 'Add as a Friend', 'arcane' ) . '" href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/add-friend/' . $friend_id, 'friends_add_friend' ) . '"><i class="fas fa-user"></i>' . esc_html__( 'Add as a friend!', 'arcane' ) . '</a>';
			}

			// Trying to request friendship
		} elseif ( 'not_friends' == BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id ) ) {
			check_ajax_referer( 'friends_add_friend' );

			if ( ! friends_add_friend( bp_loggedin_user_id(), $friend_id ) ) {
				echo esc_html__(' Friendship could not be requested.', 'arcane' );
			} else {
				echo '<a id="friend-' . esc_attr( $friend_id ) . '" class="remove friendship-button pending_friend requested add-friend" rel="remove" title="' . esc_html__( 'Cancel Request', 'arcane' ) . '" href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/requests/cancel/' . $friend_id . '/', 'friends_withdraw_friendship' ) . '" class="requested"><i class="fas fa-times" data-original-title="'.esc_html__("Cancel request!", "arcane").'" data-toggle="tooltip"></i>'.esc_html__(' Cancel request!', 'arcane').'</a>';
			}

			// Trying to cancel pending request
		} elseif ( 'pending' == BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id ) ) {
			check_ajax_referer( 'friends_withdraw_friendship' );

			if ( friends_withdraw_friendship( bp_loggedin_user_id(), $friend_id ) ) {
				echo '<a id="friend-' . esc_attr( $friend_id ) . '" class="add friendship-button add-friend" rel="add" title="' . esc_html__( 'Add as a friend', 'arcane' ) . '" href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/add-friend/' . $friend_id, 'friends_add_friend' ) . '"><i class="fas fa-user"></i>' . esc_html__( 'Add as a friend!', 'arcane' ) . '</a>';
			} else {
				echo esc_html__("Friendship request could not be cancelled.", 'arcane');
			}

			// Request already pending
		} else {
			echo esc_html__( 'Request Pending', 'arcane' );
		}

		exit;
	}
}


/*restrict admin access for non admins*/
if(!function_exists('arcane_restrict_admin')){
	function arcane_restrict_admin() {
		$user = new WP_User( get_current_user_id() );
		if (filter_has_var(INPUT_SERVER, "REQUEST_URI")) {
			$requesturi = filter_input(INPUT_SERVER, "REQUEST_URI", FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE);
		} else {
			if (isset($_SERVER["REQUEST_URI"]))
				$requesturi = filter_var($_SERVER["REQUEST_URI"], FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE);
			else
				$requesturi = null;
		}

		if (isset($_POST['_wp_http_referer'])) {
			$referer = $_POST['_wp_http_referer'];
		} else {
			$referer = "";
			if (strpos($requesturi, "action=edit") !== false) {
				$referer = '   post_type=team    ';
			}
		}

		if ( !current_user_can( 'manage_options' ) ) {
			if ( in_array( 'gamer', (array) $user->roles ) ) {
				//is a gamer

				$post_author_id = 0;
				if(isset($_GET['post_id'] ))
				$post_author_id = get_post_field( 'post_author', $_GET['post_id'] );

				if((strpos($_SERVER['REQUEST_URI'], "post_type=team") > 0 && $post_author_id != get_current_user_id())){
					wp_die( esc_html__('You are not allowed to edit this!', 'arcane'));
				}

				if((strpos($requesturi, "post_type=team") === false) && (strpos($referer, "post_type=team") === false) && (strpos($requesturi, "admin-ajax.php") === false)  && ((strpos($requesturi, "post.php") !== false) && (strpos($requesturi, "image-editor") !== false) ) ) {
					//default check for rest of shit
					wp_die( esc_html__('You are not allowed to access this part of the site', 'arcane').' ('.$requesturi.')');
				} else {
					$holder = explode ('/', $requesturi );
					if (strlen($holder[count($holder) -1 ]) < 3 ) {
						wp_die( esc_html__('You are not allowed to access this part of the site', 'arcane').' ('.$requesturi.')');
					} elseif ((strpos($holder[count($holder) -1 ], '-new') !== false) AND (strpos($referer, "post_type=team") === false)) {
						wp_die( esc_html__('You are not allowed to access this part of the site', 'arcane').' ('.$requesturi.')');
					}elseif(strpos($requesturi, "action=edit") !== false && get_post_type($_GET['post']) != 'team'){
						wp_die(esc_html__('You are not allowed to access this part of the site', 'arcane').' ('.$requesturi.')');
					}
					else{
						if(arcane_endsWith($requesturi, "edit.php") or arcane_endsWith($requesturi, "about.php") or arcane_endsWith($requesturi, "page") or arcane_endsWith($requesturi, "post") or arcane_endsWith($requesturi, "tournament")){
							wp_die( esc_html__('You are not allowed to access this part of the site', 'arcane').' ('.$requesturi.')');
						}elseif(arcane_endsWith($requesturi, "team") && $_GET['vc_action'] != 'vc_inline'){
							wp_die( esc_html__('You are not allowed to access this part of the site', 'arcane').' ('.$requesturi.')');
						}
					}
				}
			} elseif((strpos($requesturi, "post_type=team") === false) && (strpos($referer, "post_type=team") === false)&& (strpos($requesturi, "admin-ajax.php") === false)  && ((strpos($requesturi, "post.php") !== false) && (strpos($requesturi, "image-editor") !== false) ) ) {
				wp_die( esc_html__('You are not allowed to access this part of the site', 'arcane').' ('.$requesturi.')');
			}
		} else {
			if ( in_array( 'gamer', (array) $user->roles ) ) {
				//is a gamer
				if((strpos($requesturi, "post_type=team") === false) && (strpos($referer, "post_type=team") === false)&& (strpos($requesturi, "admin-ajax.php") === false)  && ((strpos($requesturi, "post.php") !== false) && (strpos($requesturi, "image-editor") !== false) ) ) {
					//default check for rest of shit
					wp_die( esc_html__('You are not allowed to access this part of the site', 'arcane').' ('.$requesturi.')');
				}
			}
		}
	}
}
add_action( 'admin_init', 'arcane_restrict_admin', 1 );


/*login*/
add_action( 'wp_login_failed', 'arcane_front_end_login_fail' );
if(!function_exists('arcane_front_end_login_fail')){
	function arcane_front_end_login_fail( $username ) {
		if (filter_has_var(INPUT_SERVER, "HTTP_REFERER")) {
			$referrer = filter_input(INPUT_SERVER, "HTTP_REFERER", FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE);
		} else {
			if (isset($_SERVER["HTTP_REFERER"]))
				$referrer = filter_var($_SERVER["HTTP_REFERER"], FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE);
			else
				$referrer = null;
		}


		// where did the post submission come from?
		// if there's a valid referrer, and it's not the default log-in screen
		if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
			$referrer = preg_replace('/\?.*/', '', $referrer);
			wp_redirect( $referrer . '?login=failed', $status = 302 );  // let's append some information (login=failed) to the URL for the theme to use
			exit;
		}
	}
}