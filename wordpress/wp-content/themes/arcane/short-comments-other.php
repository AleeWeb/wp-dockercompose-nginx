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
     <h2 class="comments-title">
            <?php
                $comments_number = get_comments_number();
                if ( '1' === $comments_number ) {
                    /* translators: %s: post title */
                    printf( _x( 'One thought on &ldquo;%s&rdquo;', 'comments title', 'arcane' ), get_the_title() );
                } else {
                    printf(
                        /* translators: 1: number of comments, 2: post title */
                        _nx(
                            '%1$s thought on &ldquo;%2$s&rdquo;',
                            '%1$s thoughts on &ldquo;%2$s&rdquo;',
                            $comments_number,
                            'comments title',
                            'arcane'
                        ),
                        number_format_i18n( $comments_number ),
                        get_the_title()
                    );
                }
            ?>
        </h2>
        <?php the_comments_navigation(); ?>
    <?php /* See IF there are comments and do the comments stuff! */ ?>
    <?php if ( have_comments() ) : ?>
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

    <?php /* An ordered list of our custom comments callback, custom_comments(), in functions.php   */ ?>
    <?php the_comments_navigation(); ?>

        <ul class="comment-list">
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
    <?php endif /* if ( $comments ) */ ?>

   <?php /* If comments are open, build the respond form */ ?>

<?php if ( 'open' == $post->comment_status ) : ?>

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
            <?php comment_form(); ?>
        </div><!-- .formcontainer -->

    <?php endif /* if ( get_option('comment_registration') && !$user_ID ) */ ?>

</div><!-- #respond -->

    <?php endif /* if ( 'open' == $post->comment_status ) */ ?>

</div><!-- #comments -->