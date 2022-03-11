<?php
$logged = false;
$singleteam = false;
  if (is_user_logged_in()) {
    $logged = true;
    $teams = arcane_get_user_teams(get_current_user_id());
    if (is_array($teams) AND (isset($teams[0]))) {
      if (count($teams) == 1) {
          $singleteam = true;
      }

    }
  }
$options = arcane_get_theme_options();
if(!isset($options['copyright']))$options['copyright'] = "Made by Skywarrior Themes.";


    $footer_column_class = '';
    $footer_first_column = '';
    $footer_second_column = '';
    $footer_third_column = '';
    $footer_fourth_column = '';

    if(!isset($options['footer_columns'])) $options['footer_columns'] = '4';
    if($options['footer_columns'] == '1'){
        $footer_column_class = 'footer_onecolumns';
        $footer_first_column = true;
    }elseif($options['footer_columns'] == '2'){
        $footer_column_class = 'footer_twocolumns';
        $footer_second_column = true;
    }elseif($options['footer_columns'] == '3'){
        $footer_column_class = 'footer_threecolumns';
        $footer_third_column = true;
    }elseif($options['footer_columns'] == '4'){
        $footer_column_class = 'footer_fourcolumns';
        $footer_fourth_column = true;
    }

?>

<footer id="footer" class="main_footer <?php echo esc_attr($footer_column_class); ?>">
    <div class="container">
        <?php if($footer_first_column or $footer_second_column or $footer_third_column or  $footer_fourth_column){ ?>
            <div class="footer_column">
                <div class="fc_inner">
                    <!-- Footer widget area 1 -->
                     <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Widget Area 1') ) : endif; ?>
                </div>
            </div>
        <?php } ?>
        <?php if($footer_second_column or $footer_third_column or  $footer_fourth_column){ ?>
        <div class="footer_column">
            <div class="fc_inner">
                <!-- Footer widget area 2 -->
                 <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Widget Area 2') ) : endif; ?>
            </div>
        </div>
        <?php } ?>
        <?php if($footer_third_column or $footer_fourth_column){ ?>
        <div class="footer_column">
            <div class="fc_inner">
                <!-- Footer widget area 3 -->
                 <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Widget Area 3') ) : endif; ?>
            </div>
        </div>
        <?php } ?>
        <?php if($footer_fourth_column){ ?>
        <div class="footer_column">
            <div class="fc_inner">
                <!-- Footer widget area 4 -->
                 <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Widget Area 4') ) : endif; ?>
            </div>
        </div>
        <?php } ?>


    </div>
</footer>

<div class="copyright ">
        <div class="container">
           &copy; <?php echo date("Y"); ?>&nbsp;<?php if($options['copyright']!=""){ echo wp_kses_post($options['copyright']);} ?>
        </div>
</div>

<a id="back-to-top" title="Back to top" >
    <i class="fas fa-angle-double-up" aria-hidden="true"></i>
</a>


<!-- modal submit -->

<div id="myModalLSubmit" class="modal" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close">×</button>
                <h3><?php esc_html_e("Submit match scores", 'arcane'); ?> </h3>
            </div>
            <div class="modal-body">
              <form id="submit_score" method="post"  enctype="multipart/form-data">
                  <div id="mapsite"></div>
                  <input type="submit" class="button-primary" id="wp-cw-submit" name="submit_score" value="<?php esc_html_e('Submit scores', 'arcane'); ?>">
              </form>
            </div>
        </div>
    </div>
</div>
<!-- /modal submit -->


<!-- modal report -->
    <div id="myModalLReport" class="modal" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close">×</button>
                    <h3><?php esc_html_e("Flag match", 'arcane'); ?> </h3>
                </div>
                <div class="modal-body">
                  <form  method="post" enctype="multipart/form-data">
                       <textarea name="reason" id="reason"  placeholder="<?php esc_html_e('Please type your reason here...','arcane'); ?>" cols="50" rows="10" aria-required="true" ></textarea>
                      <input type="submit" class="button-primary" id="wp-cw-report" name="report_score" value="<?php esc_html_e('Report', 'arcane'); ?>">
                  </form>
                </div>
            </div>
        </div>
    </div>
<!-- modal report -->

<!-- modal delete team -->
    <div id="myModalDeleteTeam" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close">×</button>
                    <h3><?php esc_html_e("Are you sure you want to delete team?", 'arcane'); ?> </h3>
                </div>
                <div class="modal-body">
                  <a rel="nofollow" target="_self" data-pid="<?php if(isset($post->ID)) echo esc_attr($post->ID); ?>"  class="ajaxdeleteteam btn"><?php esc_html_e('Yes', 'arcane'); ?></a>
                  <a rel="nofollow" target="_self" class="btn close"><?php esc_html_e('No', 'arcane'); ?></a>
                </div>
            </div>
        </div>
    </div>
<!-- modal delete team -->
</div> <!-- End of container -->


<div class="headsearch">
    <button id="btn-search-close" class="btn btn--search-close" aria-label="Close search form"><i class="fas fa-times"></i></button>
    <form  method="get" class="search__form" action="<?php echo esc_url( site_url( '/' ) ); ?>" >
        <div class="search__form-inner">
            <input class="search__input" name="s" type="search" placeholder="<?php esc_attr_e('Search...', 'arcane'); ?>" autocomplete="off" autocapitalize="off" spellcheck="false" />
            <input type="hidden" name="post_type[]" value="post" />
            <input type="hidden" name="post_type[]" value="page" />
            <input type="hidden" name="post_type[]" value="matches" />
            <input type="hidden" name="post_type[]" value="players" />
            <input type="hidden" name="post_type[]" value="team" />
        </div>
        <span class="search__info"><?php esc_html_e('Hit enter to search or ESC to close', 'arcane'); ?></span>
    </form>
</div><!-- /search -->


<!-- modal submit br -->
<div id="modalSubmitBR" class="modal" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close">×</button>
                <h3><?php esc_html_e("Submit score for - ", 'arcane'); ?><span></span> </h3>
            </div>
            <div class="modal-body">
                <form id="br-submit" name="br-submit-form" data-tid="<?php if(isset($post->ID)) echo esc_attr($post->ID); ?>">
                    <label for="br-stream"><?php esc_html_e('Stream link', 'arcane'); ?></label>
                    <input  name="br-stream" type="url" placeholder="<?php esc_attr_e('Stream link', 'arcane'); ?>" />

                    <label for="br-screenshot"><?php esc_html_e('Score screenshot', 'arcane'); ?></label>
                    <input  name="br-screenshot" type="url" placeholder="<?php esc_attr_e('Score screenshot', 'arcane'); ?>" />

                    <label for="br-score"><?php esc_html_e('Score', 'arcane'); ?></label>
                    <input  name="br-score" type="number" placeholder="<?php esc_attr_e('Score', 'arcane'); ?>" required />

                    <?php
                    global $post;
                    $pid = 0;
                    if(isset($post->ID))$pid = $post->ID;
                    $author_id = get_post_field( 'post_author', $pid);
                    ?>
                    <input name="tournament-admin" type="hidden" value="<?php echo esc_attr($author_id); ?>" />
                    <input type="submit" class="button-primary" id="wp-cw-report" name="submit-br" value="<?php esc_html_e('Submit', 'arcane'); ?>">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal submit br -->

<div class="modal" id="TeamChooserModalFooter">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close">×</button>
                <h3><?php esc_html_e("Choose a team", "arcane"); ?></h3>
            </div>
            <div class="modal-body">
                <ul class="members-list item-list" >
					<?php
					$c_id =get_current_user_id();

					$teams = array();
					$myteams = arcane_get_user_teams($c_id);
					if (is_array($myteams) AND (!empty($myteams))) {
						foreach ($myteams as $team) {
							$post = get_post($team);
							if (intval($post->post_author) == intval($c_id)) {
								array_push($teams, $post->ID);
							}
						}

						foreach ($teams as $team) {

							$pf_url =  get_post_meta($team, 'team_photo',true);

							if(!empty($pf_url))
							{
								$imagebg = arcane_aq_resize($pf_url,  50, 50, true, true, true ); //resize & crop img
								if (isset ($imagebg))
								{
									$pfimage = $imagebg;
								}

							}else{
								$pfimage = get_theme_file_uri('img/defaults/default-team.jpg');
							}
							//pfimage gotten
							$link = get_permalink($team);
							$post = get_post($team);
							$games = get_post_meta($team, 'games', true);

							echo '
                            <li class="join_team tim_bck'.esc_attr($team).'" data-games=';
                                echo "'";
                                echo json_encode($games);
                                echo "'";
                                echo ' data-team_id="'.esc_attr($team).'">
                                    <div class="team-list-wrapper">
                                    <div class="item-avatar">
                                    <a>
                                    <img alt="img" src="'.esc_url($pfimage).'" class="avatar">
                                    </a>
                                    </div>
                            
                                    <div class="item-title">
                                    <a>'.esc_attr($post->post_title).'</a>
                            
                                    <div class="item-meta">
                                      <span class="activity">
                                        '.arcane_return_number_of_members($team).' '.esc_html__("Members", "arcane").'</span></div>
                                      </div>
                                      <div class="clear"></div>
                            
                                    </div>
                              </li>';
						}

					}

					?>
                    <li class="clear"></li>
                </ul>
            </div>

        </div>
    </div>
</div>
 

<?php wp_reset_postdata(); ?>
<?php wp_footer(); ?>
</body></html>