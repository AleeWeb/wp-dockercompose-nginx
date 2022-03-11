<?php
 /*
 * Template Name: Team Challenge
 */
?>
<?php get_header();?>
<div class="page normal-page ">
      <div class="container">


<?php
$ArcaneWpTeamWars = new Arcane_TeamWars();
$status = '';
if ( isset($_GET['mid']) ) {    /***** UPDATE ******/

    $match_id = $_GET['mid'];
    $post_id = $_GET['mid'];
    $match = $ArcaneWpTeamWars->get_match(array('id' => $match_id));
    $match = (array) $match;

    $team1 = $ArcaneWpTeamWars->get_team(array('id' => $match['team1']));
    $team1 = (array) $team1;

    $team2 = $ArcaneWpTeamWars->get_team(array('id' => $match['team2']));
    $team2 = (array) $team2;

    $team1_id = $team1['ID'];
    $team2_id = $team2['ID'];

    $title = $match['title'];
    $description = $match['description'];
    $external_url = $match['external_url'];
    $match_status = $match['match_status'];


    $datum = arcane_parse_date_to_unix($match['date']);
    $status = $match['status'];

} elseif ( isset($_GET['pid']) ) {  /***** INSERT ******/
    $post_id = $_GET['pid'];
    $match_id  = 0;
    $title = "";
    $description = "";
    $external_url = "";
    $match_status = 0;
	$timezone_string = arcane_timezone_string();
	$timezone = $timezone_string ? $timezone_string : 'UTC';
	$time_now = new DateTime("now", new DateTimeZone($timezone));
    $datum = $time_now->getTimestamp();
    $team1_id = "";
	$team2_id = $_GET['pid'];

	$teams = arcane_get_user_teams_for_challenge(get_current_user_id());
	foreach($teams as $team){
		if(get_post_status($team) == 'publish'){
			$team1_id = $team;
			break;
		}
	}

}


if ($post_id==0 && $match_id!=0) {
	$match =(array) $ArcaneWpTeamWars->get_match(array('id' => $match_id));


	$team1 =(array) $ArcaneWpTeamWars->get_team(array('id' => $match['team1']));

	$team2 = (array)$ArcaneWpTeamWars->get_team(array('id' => $match['team2']));


	$team1_id = $team1['ID'];
    $team2_id = $team2['ID'];

	$title = $match['title'];
	$description = $match['description'];
	$external_url = $match['external_url'];
	$match_status = $match['match_status'];

	$datum = arcane_parse_date_to_unix($match['date']);

}



if(!empty($_GET['pid'])) $redurl = get_permalink($_GET['pid']);
if(!empty($_GET['mid'])) $redurl = get_permalink($_GET['mid']);
?>
<form id="challengeForm" class="tournament-creation-wrap col-9 challenge-form" onSubmit="return tinyMCE.triggerSave();" method="post" action="<?php echo esc_url($redurl); ?>" enctype="multipart/form-data">

	<input type="hidden" name="action" value="wp-teamwars-matches">
    <input type="hidden" name="match_id" value="<?php echo esc_attr($match_id); ?>">
    <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
    <input type="hidden" name="id" value="0">
	<input type="hidden" id="_wpnonce" name="_wpnonce" value="a27c5ee126">

    <div class="tc-forms">
        <div class="register-form-wrapper">
        <h3><i class="fas fa-crosshairs"></i> <?php esc_html_e('You are challenging', 'arcane'); ?> <span><?php echo esc_attr(get_the_title($post_id)); ?></span></h3>


			<?php if(!isset($_GET['mid'])){ ?>
            <p>
                <label for="tournament_title"><span><?php esc_html_e("Select one your team", "arcane") ?></span>
                    <select name="team1">
                        <?php foreach($teams as $team){  ?>
                            <?php if(get_post_status($team) == 'publish'){ ?>
                            <option value="<?php echo esc_attr($team); ?>" <?php if($team1_id==$team):?>selected<?php endif;?>><?php echo esc_attr(get_the_title($team)); ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </label>
            </p>
			<?php } ?>


            <p>
                <label for="tournament_title"><span><?php esc_html_e("Match Title", "arcane") ?></span>
				    <input name="m_title" id="m_title" type="text" value="<?php echo esc_attr($title); ?>" maxlength="200" autocomplete="off" aria-required="true" >
                </label>
            </p>

            <p>
                <label for="tournament_title"><span><?php esc_html_e("Match Description", "arcane") ?></span>

			    <?php $settings = array( 'textarea_name'=>'description', 'textarea_rows'=>4 );
	            wp_editor( $description, 'description', $settings ); ?>
                </label>
            </p>

            <p>
                <label for="tournament_title"><span><?php esc_html_e("Enter league URL or external match URL", "arcane") ?> <small><?php esc_html_e("(Optional)", "arcane") ?></small></span>

				<input type="text" name="external_url" id="external_url" value="<?php echo esc_url($external_url); ?>">

                </label>
            </p>

            <p>
                <label for="tournament_title">
                    <span><?php esc_html_e("Match Date", "arcane") ?></span>
                    <div class="tchall-date">
                        <?php if(!isset($status))$status = ''; ?>
                        <?php if($status != 'done' && $status != 'rejected_edit'){ ?>

                                <?php $ArcaneWpTeamWars->html_date_helper('date', $datum);?>

                        <?php } ?>
                    </div>
                </label>
            </p>



        </div>

        <?php if($status != 'done' && $status != 'rejected_edit' && $status != 'pending'){ ?>
            <div class="register-form-wrapper">
                <h3><i class="fas fa-gamepad"></i> <?php esc_html_e('Game Info', 'arcane'); ?></h3>
                <p>
                    <label for="tournament_title">
                        <span><?php esc_html_e("Select a game", "arcane") ?></span>

                        <select id="game_id" name="game_id">
                            <?php arcane_mutual_games_inter((int)$team1_id, (int)$team2_id); ?>
                        </select>
                    </label>
                </p>

                <p>
                    <label for="tournament_title">
                        <span><?php esc_html_e("Select maps", "arcane") ?></span>
                        <div class="match-results" id="matchsite">

                            <div id="mapsite"></div>

                            <div class="add-map" id="wp-cw-addmap">
                                <input type="button" class="btn required requiredField" value="<?php echo esc_html__('Add map', 'arcane'); ?>">
                            </div>
                        </div>
                    </label>
                </p>
            </div>
        <?php } ?>


    <?php if( isset($_GET['mid']) ){ ?>
  	   <p class="submit"><input type="submit" class="button-primary" id="wp-cw-submit" name="submit_add_match" value="<?php echo esc_html__('Update', 'arcane'); ?>"></p>
    <?php }else{ ?>
       <p class="submit"><input type="submit" class="button-primary" id="wp-cw-submit" name="submit_add_match" value="<?php echo esc_html__('Challenge', 'arcane'); ?>"></p>
    <?php } ?>


    </div>

    </form>

            </div>

</div>

<?php get_footer(); ?>