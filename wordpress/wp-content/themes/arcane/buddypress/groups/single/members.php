<?php if ( bp_group_has_members( bp_ajax_querystring( 'group_members' ) ) ) : ?>

	<?php do_action( 'bp_before_group_members_content' ); ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_group_members_list' ); ?>

    <div class="members friends members-block" id="buddypress">

   	<ul id="members-list-fn" class="item-list" role="main">

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

			<li>
			    <div class="member-list-wrapper">

                    <div class="item-avatar">
                       <a href="<?php bp_member_permalink(); ?>"><?php echo get_avatar(bp_get_member_user_id(), 50,null,'', ['class' => 'avatar user-1-avatar avatar-50 photo']); ?></a>
                    </div>

                    <div class="item">
                        <div class="item-title">
                            <a href="<?php echo esc_attr(bp_core_get_user_domain( bp_get_member_user_id() )); ?>"><?php echo esc_attr(bp_core_get_user_displayname( bp_get_member_user_id() )); ?></a>
                            <div>
                                <span class="activity">
                                    <?php bp_group_member_joined_since(); ?>
                                </span>
                            </div>
                        </div>
                    </div>

    				<?php do_action( 'bp_group_members_list_item' ); ?>

                    <?php if ( bp_is_active( 'friends' ) ) : ?>

                        <div class="action">

                            <?php bp_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() ); ?>

                            <?php do_action( 'bp_group_members_list_item_action' ); ?>

                        </div>

                    <?php endif; ?>

				</div>
			</li>

		<?php endwhile; ?>

	</ul>
    </div>
	<?php do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php esc_html_e( 'No members were found.', 'arcane' ); ?></p>
	</div>

<?php endif; ?>
