<!DOCTYPE html>
<html  <?php language_attributes(); ?>>
    <head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php global $page, $paged, $woocommerce, $arcane_current_time, $wp; ?>

<?php
    $currentlang = apply_filters( "wpml_home_url", esc_url(home_url('/')));
    $actual_current_link = home_url( $wp->request );

    if ( class_exists('BuddyPress'))
        if(bp_is_my_profile())
            $actual_current_link = home_url('/');

    $arcane_current_time = strtotime(gmdate( 'Y-m-d H:i:s', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) ));

    /**
     * init options
     */
    $options = arcane_get_theme_options();
    if(!isset($options['newsticker']))$options['newsticker'] = false;
    if(!isset($options['logo']['url']))$options['logo']['url'] = get_theme_file_uri('img/logo.png');
    if(!isset($options['login_menu']))$options['login_menu'] = true;
    if(!isset($options['tickertitle']))$options['tickertitle'] = '';
    if(!isset($options['tickeritems']))$options['tickeritems'] = '';
    if(!isset($options['preloader']))$options['preloader'] = '';
    if(!isset($options['preloader_icon']))$options['preloader_icon'] = '';
    if(!isset($options['team_creation']))$options['team_creation'] = '';
    if(!isset($options['team_creation_number']))$options['team_creation_number'] = '';
    if(!isset($options['tournament_creation']))$options['tournament_creation'] = true;
    if(!isset($options['cart_every_page']))$options['cart_every_page'] = false;
    ?>
<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php
    $u = wp_get_current_user();
    if(isset($u->ID))
        $usermeta = get_user_meta($u->ID);


	if($options['preloader'] == '1')
	    include_once 'inc/header/preloader.php';
?>
<div id="main_wrapper">
    <!-- NAVBAR
    ================================================== -->
    <header>
      <div class="navbar-wrapper ">

			<div class="col-4">
				<div class="social-top">
                    <?php  if(!empty($options['twitter-url'])) { ?> <a rel="nofollow" target="_blank" href="<?php echo esc_url($options['twitter-url']); ?>"><i class="fab fa-twitter"></i> </a><?php } ?>
                    <?php  if(!empty($options['facebook-url'])) { ?> <a rel="nofollow" target="_blank" href="<?php echo esc_url($options['facebook-url']); ?>"><i class="fab fa-facebook-f"></i> </a> <?php } ?>
                    <?php  if(!empty($options['vimeo-url'])) { ?> <a rel="nofollow" target="_blank" href="<?php echo esc_url($options['vimeo-url']); ?>"> <i class="fab fa-vimeo-v"></i> </a> <?php } ?>
                    <?php  if(!empty($options['pinterest-url']) ) { ?> <a rel="nofollow" target="_blank" href="<?php echo esc_url($options['pinterest-url']); ?>"><i class="fab fa-pinterest"></i> </a> <?php } ?>
                    <?php  if(!empty($options['youtube-url'])) { ?> <a rel="nofollow" target="_blank" href="<?php echo esc_url($options['youtube-url']); ?>"><i class="fab fa-youtube"></i> </a> <?php } ?>
                    <?php  if(!empty($options['dribbble-url'])) { ?><a rel="nofollow" target="_blank" href="<?php echo esc_url($options['dribbble-url']); ?>"><i class="fab fa-dribbble"></i> </a> <?php } ?>
                    <?php  if(!empty($options['rss-url'])) { ?> <a rel="nofollow" target="_blank" href="<?php echo (!empty($options['rss-url'])) ? esc_url($options['rss-url']) : get_bloginfo('rss_url'); ?>"><i class="fas fa-rss"></i> </a><?php } ?>
                    <?php  if(!empty($options['instagram-url'])) { ?> <a rel="nofollow" target="_blank" href="<?php echo esc_url($options['instagram-url']); ?>"><i class="fab fa-instagram"></i></a> <?php } ?>
                    <?php  if(!empty($options['steam-url'])) { ?> <a rel="nofollow" target="_blank" href="<?php echo esc_url($options['steam-url']); ?>"><i class="fab fa-steam-symbol"></i></a> <?php } ?>
                    <?php  if(!empty($options['twitch-url'])) { ?> <a rel="nofollow" target="_blank" href="<?php echo esc_url($options['twitch-url']); ?>"><i class="fab fa-twitch"></i></a> <?php } ?>
                    <?php  if(!empty($options['discord-url'])) { ?> <a rel="nofollow" target="_blank" href="<?php echo esc_url($options['discord-url']); ?>"><i class="fab fa-discord"></i></a> <?php } ?>
                    <?php  if(!empty($options['email-url'])) { ?> <a rel="nofollow" target="_blank" href="mailto:<?php echo esc_attr($options['email-url']); ?>"><i class="fas fa-envelope-square"></i></a> <?php } ?></div>
                </div>

	    <?php $lg = $options['logo']['url']; ?>
		<?php if(!empty($lg)){ ?>
          <div class="logo col-4">
            <a rel="nofollow" class="brand" href="<?php  echo esc_url(site_url('/')); ?>"> <img src="<?php echo esc_url($lg); ?>" alt="logo"  /> </a>
          </div>
        <?php }

            if ( is_user_logged_in() ) {
                global $user_level,$wpmu_version;

   				if($options['team_creation'] == '0'){
   					if( (isset($usermeta['_checkbox_team_user'][0]) && $usermeta['_checkbox_team_user'][0] == "") || (isset($usermeta['_checkbox_team_user'][0]) && $usermeta['_checkbox_team_user'][0] == NULL) || (isset($usermeta['_checkbox_team_user'][0]) && $usermeta['_checkbox_team_user'][0] == "no")){
   						$noteamclass = 'no-team';
					}
				};

               $teams = arcane_get_user_teams(get_current_user_id());

               if($teams === false or empty($teams))$teams = 0;

				if(!isset($noteamclass))$noteamclass = '';
            ?>

                <div class=" col-4">
                    <div class="user-wrap <?php echo esc_attr($noteamclass); ?> r">
                         <?php
                            if (function_exists( 'bp_notifications_get_unread_notification_count' ) ) {
                              $count = bp_notifications_get_unread_notification_count( get_current_user_id() );
                              if (!($count > 0)) {
                                $count = 0;
                              }
                              $link = bp_loggedin_user_domain() . bp_get_notifications_slug();

                              if($count > 0){
                          ?>
                           <a rel="nofollow" href="<?php echo esc_url($link); ?>" > ! </a>
                            <?php
                              }
                          } ?>
                        <div class="user-avatar"><a rel="nofollow" class="username" href="<?php echo get_edit_user_link();?>"><?php echo get_avatar( get_current_user_id(), 50 );  ?></a></div>
                        <div class="logged-info">

                        <a rel="nofollow" class="username" href="<?php echo esc_url(get_edit_user_link());?>"><?php echo esc_attr($u->nickname);?> <i class="fas fa-fw fa-angle-down"></i> <br /><span><?php esc_html_e('Member since ','arcane');setlocale(LC_TIME, get_locale()); echo strftime('%b, %Y', strtotime(get_userdata(get_current_user_id( ))->user_registered)); ?></span></a>
                        </div>

                        <div class="clear"></div>
                        <ul class="dropdown">
                            <?php if ( class_exists('BuddyPress') && function_exists( 'bp_is_active' ) && bp_is_active( 'messages' )){ $link = bp_loggedin_user_domain() . bp_get_messages_slug() . '/inbox'; ?>
                            <li><a rel="nofollow" class="username" href="<?php echo esc_url(get_edit_user_link());?>"> <i class="fas fa-user"></i> <?php esc_html_e( 'Profile', 'arcane' ); ?></a></li>

                            <li><a rel="nofollow" href="<?php echo esc_url($link);?>">
                            <i class="far fa-comments"></i> <?php if(bp_get_total_unread_messages_count( bp_loggedin_user_id() ) > 0){ ?>

                           <i class="msg_ntf"><?php echo bp_get_total_unread_messages_count( bp_loggedin_user_id() ); ?></i> <?php esc_html_e( 'Messages', 'arcane' ); ?>

                         <?php    }else{?> <?php esc_html_e( 'Messages', 'arcane' ); ?> <?php } ?> </a></li> <?php  } ?>

                         <?php  if(class_exists('Arcane_Types') && in_array('administrator',$u->roles) || in_array('gamer',$u->roles)){ ?>


                         <?php if($options['team_creation'] == '1'){ ?>

                            <?php if(($options['team_creation_number'] == '1') || ( $options['team_creation_number'] == '0' && get_user_meta( get_current_user_id(), 'team_no_limit', true) ) || ($options['team_creation_number'] == '0'  && $teams == 0)){ ?>

                                <li><a rel="nofollow" href="<?php echo esc_url(arcane_get_permalink_for_template('tmp-team-creation.php')); ?>"  ><i class="fas fa-crosshairs"></i> <?php esc_html_e('Create a team', 'arcane'); ?></a></li>

                            <?php } ?>

                         <?php	}elseif((isset($options['team_creation']) && $options['team_creation'] == '0') &&  (isset($usermeta['_checkbox_team_user'][0]) && $usermeta['_checkbox_team_user'][0] == "yes")){ ?>

                            <?php if(($options['team_creation_number'] == '1') || ($options['team_creation_number'] == '0' && get_user_meta( get_current_user_id(), 'team_no_limit', true) ) || ($options['team_creation_number'] == '0'  && $teams == 0)){ ?>

                                <li><a rel="nofollow" href="<?php echo esc_url(arcane_get_permalink_for_template('tmp-team-creation.php')); ?>" ><i class="fas fa-crosshairs"></i> <?php esc_html_e('Create a team', 'arcane'); ?></a></li>

                            <?php } ?>

                        <?php } ?>


	                         <?php $timovi = arcane_get_user_teams($u->ID);
	                         if(!empty($timovi) && (in_array('administrator',$u->roles) || in_array('gamer',$u->roles)) ){ ?>

                                 <li>
                                     <a rel="nofollow"><i class="fas fa-users" aria-hidden="true"></i> <?php esc_html_e('My teams', 'arcane'); ?></a>

                                     <ul>
				                         <?php foreach ($timovi as $tim) {
					                         $tim = get_post($tim);
					                         echo '<li><a href="'.esc_url(get_permalink($tim->ID)).'"><i class="fas fa-crosshairs" aria-hidden="true"></i>'.esc_attr($tim->post_title).'</a></li>';
				                         }
				                         ?>
                                     </ul>
                                 </li>


	                         <?php } ?>



                         <?php } /*roles check*/ ?>

                            <?php if((class_exists('Arcane_Types') &&  $options['tournament_creation'] == '1' || (isset($usermeta['_checkbox_tournament_user'][0]) && $usermeta['_checkbox_tournament_user'][0] == "yes")) && current_user_can('publish_posts')){
                                $linkz =  get_permalink(arcane_get_ID_by_slug('tournament-creation'));
                                ?>
                                <li><a rel="nofollow" href="<?php echo esc_url($linkz); ?>" ><i class="fas fa-trophy"></i> <?php esc_html_e('Create a tournament', 'arcane'); ?></a></li>

	                            <?php
	                            $tournaments_link = '#';
	                            if (function_exists( 'bp_notifications_get_unread_notification_count' ) ) {
		                            $tournaments_link = bp_loggedin_user_domain().'tournaments';
	                            }
	                            ?>
                                <li>
                                    <a href="<?php echo esc_url($tournaments_link); ?>" rel="nofollow"><i class="fas fa-trophy" aria-hidden="true"></i> <?php esc_html_e('My tournaments', 'arcane'); ?></a>
                                </li>

                            <?php } ?>


                            <?php
                            if (function_exists( 'bp_notifications_get_unread_notification_count' ) ) {
                              $count = bp_notifications_get_unread_notification_count( get_current_user_id() );
                              if (!($count > 0)) {
                                $count = 0;
                              }
                              $link = bp_loggedin_user_domain() . bp_get_notifications_slug();
                          ?>
                        <li><a rel="nofollow" href="<?php echo esc_url($link); ?>" ><i class="fas  fa-exclamation-circle"></i> <?php esc_html_e('Notifications', 'arcane'); ?> (<?php echo esc_attr($count); ?>)</a></li>
                        <?php
                      } ?>

                        <li><a rel="nofollow" href="<?php echo wp_logout_url( esc_url( $actual_current_link )); ?>"><i class="fas fa-times"></i> <?php esc_html_e( 'Log out', 'arcane' ); ?></a></li>

                        </ul>
                    </div>

                    <?php
                    if ($woocommerce) {
                        $cart_every_page = $options['cart_every_page'];
                        if(is_woocommerce() || $cart_every_page ){ ?>
                            <div class="cart-outer">
                                <div class="cart-menu-wrap">
                                    <div class="cart-menu">
                                        <a rel="nofollow" class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>"><div class="cart-icon-wrap"><i class="fas fa-shopping-basket"></i> <div class="cart-wrap"><span><?php echo esc_attr($woocommerce->cart->cart_contents_count); ?> </span></div> </div></a>
                                    </div>
                                </div>

                                <div class="cart-notification">
                                    <span class="item-name"></span> <?php  esc_html_e('was successfully added to your cart.', 'arcane'); ?>
                                </div>
                            </div>
                        <?php }
                    } ?>


                </div>



            <?php
                }else{
            ?>

    <div class="col-4 login-info">

    <?php if(get_option( 'users_can_register' ) == '1'){ ?>
    <a rel="nofollow" class="register-btn" href="<?php echo esc_url(get_permalink( get_page_by_path('user-registration'))); ?>"><i class="far fa-edit"></i> <span><?php esc_html_e('Register', 'arcane'); ?></span></a>
        <?php if ($options['login_menu']){ ?>
            <i><?php esc_html_e('or','arcane'); ?></i>
        <?php } ?>
    <?php } ?>


    <?php if ($options['login_menu']){ ?>
    <a rel="nofollow" class="login-btn"><i class="fas fa-lock"></i> <span><?php esc_html_e('Sign in', 'arcane'); ?></span></a>
	<?php } ?>
        <div id="mcTooltipWrapper" class="login-tooltip" >
            <div id="mcTooltip" >
                <div id="login_tooltip"><div class="closeto"> <span><i class="fas fa-times-circle"></i> </span></div>
                    <form name="LoginWithAjax_Form" id="LoginWithAjax_Form" action="<?php  echo esc_url(wp_login_url()); ?>" method="post">


                            <input type="text" name="log" placeholder="<?php esc_html_e('Username', 'arcane');?>" id="lwa_user_login" class="input" value="" />
                            <input type="password" placeholder="<?php esc_html_e('Password', 'arcane');?>" name="pwd" id="lwa_user_pass" class="input" value="" />



                            <input name="rememberme" type="checkbox" id="lwa_rememberme" value="forever">
                            <label><?php esc_html_e("Remember Me","arcane");?></label>
                            <a rel="nofollow" id="LoginWithAjax_Links_Remember" href="<?php echo esc_url(get_permalink( get_page_by_path('lost-password'))); ?>" title="<?php esc_html_e('Password Lost and Found','arcane');?>"><?php esc_html_e("Lost your password?","arcane");?></a>
                            <?php do_action('login_form'); ?>

                            <button type="submit"  class="button-small"  name="wp-submit" id="lwa_wp-submit" value="<?php esc_html_e('GO', 'arcane'); ?>" tabindex="100" ><?php esc_html_e('GO', 'arcane'); ?> </button>

                            <input type="hidden" name="redirect_to" value="<?php echo esc_url( $currentlang ); ?>" />
                            <input type="hidden" name="lwa_profile_link" value="<?php echo esc_url($lwa_data['profile_link']) ?>" />


                    </form>
                    <?php if(get_option( 'users_can_register' ) == '1'){ ?>
                    <a rel="nofollow" class="register-link" href="<?php echo esc_url(get_permalink(arcane_get_ID_by_slug('user-registration'))); ?>"><?php esc_html_e(" Sign up ", "arcane"); ?></a>
                    <?php } ?>
                </div>
            </div>
            <div id="mcttCo"></div>
        </div>

        <?php
        if ($woocommerce) {
            $cart_every_page = $options['cart_every_page'];
            if(is_woocommerce() || $cart_every_page ){ ?>
                <div class="cart-outer">
                    <div class="cart-menu-wrap">
                        <div class="cart-menu">
                            <a rel="nofollow" class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>"><div class="cart-icon-wrap"><i class="fas fa-shopping-basket"></i> <div class="cart-wrap"><span><?php echo esc_attr($woocommerce->cart->cart_contents_count); ?> </span></div> </div></a>
                        </div>
                    </div>

                    <div class="cart-notification">
                        <span class="item-name"></span> <?php  esc_html_e('was successfully added to your cart.', 'arcane'); ?>
                    </div>
                </div>
            <?php }
        } ?>

    </div>

<?php } ?>




</div>

<div class="navbar navbar-inverse navbar-static-top " role="navigation">
     	<div class="container">
            <div class="navbar-header">

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only"><?php esc_html_e('Toggle navigation', 'arcane'); ?></span>
                    <span class="fas fa-bars"></span>
                    <strong><?php esc_html_e('MENU', 'arcane'); ?></strong>
                </button>

              <form method="get" id="sformm" action="<?php echo esc_url( site_url( '/' ) ); ?>">
	                <input type="search" placeholder="<?php esc_html_e('Search for...', 'arcane'); ?>" autocomplete="off" name="s">
	                <input type="hidden" name="post_type[]" value="post" />
	                <input type="hidden" name="post_type[]" value="page" />
	                <i class="fas fa-search"></i>
	          </form>

            </div>

            <div class="navbar-collapse collapse">

			 <?php
            if ( is_user_logged_in() ) {
                global $user_level, $wpmu_version;


   				if(isset($options['team_creation']) && $options['team_creation'] == '0'){
   					if((isset($usermeta['_checkbox_team_user'][0]) && $usermeta['_checkbox_team_user'][0] == "") || (isset($usermeta['_checkbox_team_user'][0]) &&$usermeta['_checkbox_team_user'][0] == NULL) || (isset($usermeta['_checkbox_team_user'][0]) && $usermeta['_checkbox_team_user'][0] == "no")){
   						$noteamclass = 'no-team';
					}
				};

	            $teams = arcane_get_user_teams(get_current_user_id());

	            if($teams === false or empty($teams))$teams = 0;

				if(!isset($noteamclass))$noteamclass = '';
            ?>

                <div class=" user-wrap <?php echo esc_attr($noteamclass); ?> user-wrap-double r">
                      <?php
                        if (function_exists( 'bp_notifications_get_unread_notification_count' ) ) {
                          $count = bp_notifications_get_unread_notification_count( get_current_user_id() );
                          if (!($count > 0)) {
                            $count = 0;
                          }
                          $link = bp_loggedin_user_domain() . bp_get_notifications_slug();

                          if($count > 0){
                      ?>
                       <a rel="nofollow" href="<?php echo esc_url($link); ?>" > ! </a>
                        <?php
                          }
                      } ?>
                    <div class="user-avatar"><a rel="nofollow" class="username" href="<?php echo get_edit_user_link();?>"><?php echo get_avatar( get_current_user_id(), 50 );  ?></a></div>
                    <div class="logged-info">

                    <a rel="nofollow" class="username" href="<?php echo esc_url(get_edit_user_link());?>"><?php echo esc_attr($u->nickname);?> <i class="fas fa-fw fa-angle-down"></i> <br /><span><?php esc_html_e('Member since ','arcane');setlocale(LC_TIME, get_locale()); echo strftime('%b, %Y', strtotime(get_userdata(get_current_user_id( ))->user_registered)); ?></span></a>
                    </div>

                    <div class="clear"></div>
                    <ul class="dropdown">
                    	<?php if ( class_exists('BuddyPress') && function_exists( 'bp_is_active' ) && bp_is_active( 'messages' )){ $link = bp_loggedin_user_domain() . bp_get_messages_slug() . '/inbox'; ?>
                    	<li><a rel="nofollow" class="username" href="<?php echo esc_url(get_edit_user_link());?>"> <i class="fas fa-user"></i> <?php esc_html_e( 'Profile', 'arcane' ); ?></a></li>

                    	<li><a rel="nofollow" href="<?php echo esc_url($link);?>">
                        <i class="far fa-comments"></i> <?php if(bp_get_total_unread_messages_count( bp_loggedin_user_id() ) > 0){ ?>

                       <i class="msg_ntf"><?php echo bp_get_total_unread_messages_count( bp_loggedin_user_id() ); ?></i> <?php esc_html_e( 'Messages', 'arcane' ); ?>

                     <?php    }else{ ?> <?php esc_html_e( 'Messages', 'arcane' ); ?> <?php } ?> </a></li> <?php  } ?>


                     <?php  if(class_exists('Arcane_Types') && in_array('administrator',$u->roles) || in_array('gamer',$u->roles)){ ?>

                         <?php if($options['team_creation'] == '1'){ ?>

                            <?php if($options['team_creation_number'] == '1' || ( $options['team_creation_number'] == '0' && get_user_meta( get_current_user_id(), 'team_no_limit', true) ) || ($options['team_creation_number'] == '0'  && $teams == 0)){ ?>

                                <li><a rel="nofollow" href="<?php echo esc_url(arcane_get_permalink_for_template('tmp-team-creation.php')); ?>"><i class="fas fa-crosshairs"></i> <?php esc_html_e('Create a team', 'arcane'); ?></a></li>

                            <?php } ?>

                         <?php	}elseif($options['team_creation'] == '0' &&  (isset($usermeta['_checkbox_team_user'][0]) && $usermeta['_checkbox_team_user'][0] == "yes")){ ?>

                            <?php if($options['team_creation_number'] == '1' || ( $options['team_creation_number'] == '0' && get_user_meta( get_current_user_id(), 'team_no_limit', true) ) ||  ($options['team_creation_number'] == '0'  && $teams == 0)){ ?>

                                <li><a rel="nofollow" href="<?php echo esc_url(arcane_get_permalink_for_template('tmp-team-creation.php')); ?>"><i class="fas fa-crosshairs"></i> <?php esc_html_e('Create a team', 'arcane'); ?></a></li>

                            <?php } ?>

                        <?php } ?>

                    <?php } ?>

					<?php $timovi = arcane_get_user_teams($u->ID);
					  if(!empty($timovi) && (in_array('administrator',$u->roles) || in_array('gamer',$u->roles)) ){ ?>

					<li>
						<a rel="nofollow"><i class="fas fa-users" aria-hidden="true"></i> <?php esc_html_e('My teams', 'arcane'); ?></a>

					 	<ul>
					 		<?php foreach ($timovi as $tim) {
                                $tim = get_post($tim);
	                             echo '<li><a href="'.esc_url(get_permalink($tim->ID)).'"><i class="fas fa-crosshairs" aria-hidden="true"></i>'.esc_attr($tim->post_title).'</a></li>';
	                        }
	                        ?>
					 	</ul>

					</li>



					<?php } ?>

					 <?php if((class_exists('Arcane_Types') &&  $options['tournament_creation'] == '1' or (isset($usermeta['_checkbox_tournament_user'][0]) && $usermeta['_checkbox_tournament_user'][0] == "yes")) && current_user_can('publish_posts') ){
						$linkz =  get_permalink(arcane_get_ID_by_slug('tournament-creation'));
						?>
                      <li><a rel="nofollow" href="<?php echo esc_url($linkz); ?>" ><i class="fas fa-trophy"></i> <?php esc_html_e('Create a tournament', 'arcane'); ?></a></li>

                     <?php
                     $tournaments_link = '#';
                     if (function_exists( 'bp_notifications_get_unread_notification_count' ) ) {
                         $tournaments_link = bp_loggedin_user_domain().'tournaments';
                     }
                     ?>
                     <li>
                         <a href="<?php echo esc_url($tournaments_link); ?>" rel="nofollow"><i class="fas fa-trophy" aria-hidden="true"></i> <?php esc_html_e('My tournaments', 'arcane'); ?></a>
                     </li>

                     <?php } ?>

                     <?php
                        if (function_exists( 'bp_notifications_get_unread_notification_count' ) ) {
                          $count = bp_notifications_get_unread_notification_count( get_current_user_id() );
                          if (!($count > 0)) {
                            $count = 0;
                          }
                          $link = bp_loggedin_user_domain() . bp_get_notifications_slug();
                      ?>
                    <li><a rel="nofollow" href="<?php echo esc_url($link); ?>" ><i class="fas fa-exclamation-circle"></i> <?php esc_html_e('Notifications', 'arcane'); ?> (<?php echo esc_attr($count); ?>)</a></li>
                    <?php
                  } ?>

                    <li><a rel="nofollow" href="<?php echo wp_logout_url( esc_url( $actual_current_link )); ?>"><i class="fas fa-times"></i> <?php esc_html_e( 'Log out', 'arcane' ); ?></a></li>

                    </ul>
                </div>
                <?php }?>

                <!--MENU-->
                <?php if(has_nav_menu('header-menu')) {
                    wp_nav_menu( array( 'theme_location'  => 'header-menu', 'depth' => 0,'sort_column' => 'menu_order', 'items_wrap' => '<ul  class="nav navbar-nav">%3$s</ul>') );
                }else { ?>
                    <ul  class="nav">
                        <li>
                            <a rel="nofollow" href=""><?php esc_html_e('No menu assigned!', 'arcane'); ?></a>
                        </li>
                    </ul>
                <?php } ?>

        </div><!--/.nav-collapse -->
      </div><!-- /.navbar-inner -->
   </div>


<?php
if(class_exists('BuddyPress') && function_exists( 'bp_current_component' ) ){
    $component = bp_current_component();
    if($component == 'members'){ ?>
     <div class="title_wrapper">
         <div class="container">
            <div class="col-12">
                <h1><?php esc_html_e('Search members', 'arcane'); ?></h1>
            </div>
         </div>
    </div>
        <?php if($options['newsticker']){ include_once 'inc/header/newsticker.php'; } ?>
    <?php }
}else{
    $component = false;
}

if((isset($post) && ($post->post_type == 'tournament')) || is_singular('matches') || is_singular('team') || $component || is_front_page() || is_page_template('single-tournament.php') ){}elseif(is_search()){  ?>

    <div class="title_wrapper">
    <div class="container">
        <div class="col-12">
            <h1><?php esc_html_e('Search: ', 'arcane');  echo get_search_query(); ?></h1>
            <span class="breadcrumbs">
                <?php
                    if ( function_exists('yoast_breadcrumb') ) {
                        yoast_breadcrumb();
                    }
                ?>
            </span>
        </div>
    </div>
</div>

<?php }elseif(is_page_template('tmp-team-creation.php') && isset($_GET['p_id'])){ ?>
    <div class="title_wrapper">
        <div class="container">
            <div class="col-12">
                <h1><?php esc_html_e('Team edit', 'arcane'); ?></h1>
            </div>
        </div>
    </div>
    <?php
    }else{

	$single_page_header = get_post_meta(get_the_ID(), 'page_header', true);

	if(!isset($options['header-settings-switch']))
		$options['header-settings-switch'] = 1;

	if($options['header-settings-switch'] == '1' && ($single_page_header == 'yes' || empty($single_page_header)) ){ ?>
        <div class="title_wrapper" data-type="<?php the_title(); ?>">

            <div class="container">
                    <div class="col-12">

                        <?php
                        if(is_single() && ( get_post_type($post->ID) == 'post')){
                            $categories = wp_get_post_categories($post->ID);
                            echo "<div class='cat-single'>";
                                foreach ($categories as $category) { ?>
                                    <a href="<?php echo esc_url(get_category_link($category)); ?>" class="ncategory">
                                          <?php echo esc_attr(get_cat_name($category)); ?>
                                    </a>
                                <?php }
                            echo "</div>";
                        }
                        ?>

                        <h1>
                            <?php include_once 'inc/header/header-archive.php'; ?>
                        </h1>

                        <span class="breadcrumbs">
                            <?php if ( function_exists('yoast_breadcrumb') ) yoast_breadcrumb(); ?>
                        </span>


                    </div>

            </div>
        </div>

    <?php } ?>
<?php } ?>
<?php $single_page_ticker = get_post_meta(get_the_ID(), 'page_header_ticker_remove', true); ?>
<?php if($options['newsticker'] && $single_page_ticker != '1' ){ include_once 'inc/header/newsticker.php'; } ?>
</header>