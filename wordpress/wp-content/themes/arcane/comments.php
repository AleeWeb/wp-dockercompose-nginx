<?php
if ( post_password_required() ) { ?>
	<p class="nocomments"><?php esc_html_e('This post is password protected. Enter the password to view comments.','arcane'); ?></p>
	<?php
	return;
}
?>

<?php /* See IF there are comments and do the comments stuff! */ ?>
<?php if ( have_comments() ) : ?>
	<div class="comment-form">
		<h3 id="reply-title" class="comment-reply-title">
			<?php esc_html_e("Comments", 'arcane'); ?>
		</h3>
		<?php /* An ordered list of our custom comments callback, custom_comments(), in functions.php   */ ?>
		<ul  class="comment-list">

			<?php
			$args = array(
				'style'             => 'ul',
				'type'              => 'all',
				'avatar_size'       => 70,
				'reply_text'        => esc_html__('Reply', 'arcane'),
				'format'            => 'html5', // or 'xhtml' if no 'HTML5' theme support
				'short_ping'        => false,   // @since 3.6
				'echo'              => true     // boolean, default is true
			);
			?>
			<?php wp_list_comments($args); ?>
		</ul>

		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
	</div>
<?php endif /* if ( $comments ) */ ?>


<?php if ( comments_open() ) :
	comment_form();
endif; // if you delete this the sky will fall on your head ?>

