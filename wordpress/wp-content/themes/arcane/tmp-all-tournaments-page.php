<?php
/*
 * Template name: All tournaments page
*/
?>
<?php get_header(); ?>
<div class="page normal-page all-tournaments-page">
      <div class="container">
        <div class="row">

            <form  class="all-tournaments-form">
                <label> <input type="text" autocomplete="off" name="tournament_name" id="tournament_name" placeholder="<?php esc_html_e('Search Tournaments...', 'arcane')?>">
                    <input type="button" class="btn" id="tournaments_search_submit" name="tournaments_search_submit" value="<?php esc_html_e('Search','arcane');?>">
                </label>
            </form>

            <div class="col-lg-12 col-md-12">

            <div id="buddypress">
                <div class="wpb_wrapper tournaments_block" id="tournament_members">
                    <?php arcane_all_tournaments_pagination_v2()?>
                </div>
            </div>
            <div class="clear"></div>
            </div>
        </div>
     </div>
</div>
<?php get_footer(); ?>