<div class="comment-form">
    <?php /* Run some checks for bots and password protected posts */ ?>
    <?php global $post;
        $req = get_option('require_name_email'); // Checks if fields are required.
        if ( ! empty($post->post_password) ) :
            if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) :
    ?>
            <div class="nopassword"><?php esc_html_e('This post is password protected. Enter the password to view any comments.', 'arcane') ?></div>
    <?php
            return;
            endif;
            endif;
    ?>
    <?php /* See IF there are comments and do the comments stuff! */ ?>
    <?php if ( have_comments() ){ ?>
    <?php
            $allowed_tags = array(
            'span' => array(
                'class' => array()
            ),
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'em' => array()
            );
    ?>
    <?php  /* An ordered list of our custom comments callback, custom_comments(), in functions.php   */ ?>
    <?php the_comments_navigation(); ?>
        <ul class="comment-list">
            <?php
            $ccount = wp_count_comments($post->ID);
            if($ccount->total_comments == 0){
             echo '<li class="comment byuser  bypostauthor even thread-even depth-1" >
                        <div class="comment-body">
                            <p>'.esc_html__('There are no comments at the moment!', 'arcane').'</p>
                        </div>
                   </li>';
            }
            ?>
            <?php
                wp_list_comments( array(
                    'style'       => 'ul',
                    'short_ping'  => true,
                    'avatar_size' => 42,
                ) );
            ?>
            <div class="clear"></div>
        </ul><!-- .comment-list -->
        <?php the_comments_navigation(); ?>
    <?php }else{ ?>
        <ul class="comment-list comment-list-no">
            <li class="comment byuser  bypostauthor even thread-even depth-1" >
                <div class="comment-body">
                    <span><?php echo esc_html__('There are no comments at the moment!', 'arcane'); ?></span>
                </div>
            </li>
        <div class="clear"></div>
        </ul><!-- .comment-list -->
    <?php }  /* if ( $comments ) */ ?>
   <?php /* If comments are open, build the respond form */ ?>
<?php global $arcane_match,$ArcaneWpTeamWars;
$team1 = $ArcaneWpTeamWars->get_team(array('id' => $arcane_match->team1));
$team2 = $ArcaneWpTeamWars->get_team(array('id' => $arcane_match->team2));
$cid = get_current_user_id();
$tparticipants = $arcane_match->tournament_participants;
if ($tparticipants == 'team') {
	$is_user_type = false;
} elseif($tparticipants === NULL or empty($tparticipants)){
	$is_user_type = false;
}else{
	$is_user_type = true;
}

 ?>
<?php if ( 'open' == $post->comment_status && ((arcane_is_member($team1->ID,$cid) or arcane_is_member($team2->ID,$cid)) or ($is_user_type == true && ($cid == $team1->ID or $cid == $team2->ID) )) ) : ?>
<div id="respond_comments">
    <div id="cancel-comment-reply"><?php cancel_comment_reply_link() ?></div>
	<?php if ( get_option('comment_registration') && !is_user_logged_in()) : ?>

        <div class="wcontainer boxed">
            <p id="login-req">
				<?php echo esc_html__('You must be ', 'arcane').'<a href="'.get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink().'" title="Log in">'.esc_html__('logged in', 'arcane').'</a>'.esc_html__(' to post a comment.', 'arcane'); ?>
            </p>
        </div>

	<?php else :  ?>
        <div class="formcontainer">
            <form id="commentform" action="<?php echo esc_url(get_option('siteurl')); ?>/wp-comments-post.php" method="post">
            <?php if ( is_user_logged_in() ) : ?>
            <?php else : ?>
                <p id="comment-notes"><?php wp_kses(_e('Your email is <em>never</em> published nor shared.', 'arcane'), $allowed_tags ); ?> <?php if ($req) wp_kses(_e('Required fields are marked <span class="required">*</span>', 'arcane'), $allowed_tags ); ?></p>
                  <div id="form-section-author" class="form-section">
                    <div class="form-label">
                        <label for="author"><?php esc_html_e('Name', 'arcane') ?> <?php if ($req) wp_kses(_e('<span class="required">*</span>', 'arcane'), $allowed_tags ); ?></label>
                    </div>
                    <div class="form-input">
                        <input id="author" name="author" type="text" value="" size="30" maxlength="20" tabindex="3" />
                    </div>
                  </div>
                  <div id="form-section-email" class="form-section">
                    <div class="form-label">
                        <label for="email"><?php esc_html_e('Email', 'arcane') ?> <?php if ($req) wp_kses(_e('<span class="required">*</span>', 'arcane'), $allowed_tags ); ?></label>
                    </div>
                    <div class="form-input"><input id="email" name="email" type="text" value="" size="30" maxlength="50" tabindex="4" /></div>
                  </div>
                  <div id="form-section-url" class="form-section">
                  <div class="form-label">
                      <label for="url"><?php esc_html_e('Website', 'arcane') ?></label>
                  </div>
                  <div class="form-input">
                      <input id="url" name="url" type="text" value="" size="30" maxlength="50" tabindex="5" />
                  </div>
                  </div>
             <?php endif /* if ( $user_ID ) */ ?>
              <div id="form-section-comment" class="form-section">
                  <div class="form-textarea">
                    <div class="form-label">
                        <label for="author"><?php esc_html_e('Comment', 'arcane') ?></label>
                    </div>
                  <textarea id="comment" name="comment" cols="45" rows="8" tabindex="6"></textarea>
                  </div>
              </div>
              <?php if ( !is_user_logged_in() ) : ?>
              <div id="form-section-consent" class="form-section input-prepend">
                  <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"  />
                  <?php esc_html_e( 'Save my name, email, and website in this browser for the next time I comment.', 'arcane'); ?>
              </div><!-- #form-section-comment .form-section -->
              <?php endif; ?>
              <?php do_action('comment_form_after_fields'); ?>
              <div class="form-submit"><input id="submit" name="submit"  class="button-small button-green" type="submit" value="<?php esc_html_e('Post', 'arcane') ?>" tabindex="7" /><input type="hidden" name="comment_post_ID" value="<?php echo esc_attr($id); ?>" /></div>
              <?php comment_id_fields(); ?>
            </form>
        </div><!-- .formcontainer -->
    <?php endif /* if ( get_option('comment_registration') && !$user_ID ) */ ?>
</div><!-- #respond -->
    <?php endif /* if ( 'open' == $post->comment_status ) */ ?>
</div><!-- #comments -->