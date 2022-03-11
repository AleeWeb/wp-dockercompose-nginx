<?php
/*
 * Template name: All teams page
*/
?>
<?php get_header(); ?>
<div class="page normal-page all-teams-page">
      <div class="container">
        <div class="row">

            <form class="all-teams-form">
                <label>
                    <input type="text" autocomplete="off" name="team_name" id="team_name" placeholder="<?php esc_html_e('Search Teams...', 'arcane')?>">
                	<input type="button" class="btn" id="members_search_submit" name="members_search_submit" value="<?php esc_html_e('Search','arcane');?>">
                </label>
            </form>

            <div class="col-lg-12 col-md-12">

            <div id="buddypress">
                <div class="wpb_wrapper" id="members">
                    <?php arcane_all_teams_pagination_v2(); ?>
                </div>
            </div>
            <div class="clear"></div>
            </div>
        </div>
     </div>
</div>
<?php get_footer(); ?>