<?php
/**
 * arcane - Groups Admin - Manage Members
 *
 * @package arcane
 * @subpackage bp-legacy
 */

?>

<h2 class="bp-screen-reader-text"><?php esc_html_e( 'Manage Members', 'arcane' ); ?></h2>

<?php

/**
 * Fires before the group manage members admin display.
 *
 * @since 1.1.0
 */
do_action( 'bp_before_group_manage_members_admin' ); ?>

<div aria-live="polite" aria-relevant="all" aria-atomic="true">

	<div class="bp-widget group-members-list group-admins-list">
		<h3 class="section-header"><?php esc_html_e( 'Administrators', 'arcane' ); ?></h3>

		<?php if ( bp_group_has_members( array( 'per_page' => 15, 'group_role' => array( 'admin' ), 'page_arg' => 'mlpage-admin' ) ) ) : ?>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

			<ul id="members-list-fn" class="item-list">
				<?php while ( bp_group_members() ) : bp_group_the_member(); ?>
					<li>
						<div class="item-avatar">
                           <a href="<?php bp_member_permalink(); ?>"><?php echo get_avatar(bp_get_member_user_id(), 50,null,'', ['class' => 'avatar user-1-avatar avatar-50 photo']); ?></a>
                        </div>

                        <div class="item">
                            <div class="item-title">
                                <a href="<?php echo esc_attr(bp_core_get_user_domain( bp_get_member_user_id() )); ?>"><?php echo esc_attr(bp_core_get_user_displayname( bp_get_member_user_id() )); ?></a>
                                <div >
                                    <span class="activity">
                                        <?php bp_group_member_joined_since(); ?>
                                    </span>
                                </div>
                                <?php do_action( 'bp_group_manage_members_admin_item', 'admins-list' ); ?>
                            </div>
                        </div>

						<div class="action">
							<?php if ( count( bp_group_admin_ids( false, 'array' ) ) > 1 ) : ?>
								<a class="button confirm admin-demote-to-member" href="<?php bp_group_member_demote_link(); ?>"><?php esc_html_e( 'Demote to Member', 'arcane' ); ?></a>
							<?php endif; ?>

							<?php

							/**
							 * Fires inside the action section of a member admin item in group management area.
							 *
							 * @since 2.7.0
							 *
							 * @param $section Which list contains this item.
							 */
							do_action( 'bp_group_manage_members_admin_actions', 'admins-list' ); ?>
						</div>
					</li>
				<?php endwhile; ?>
			</ul>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

		<?php else: ?>

		<div id="message" class="info">
			<p><?php esc_html_e( 'No group administrators were found.', 'arcane' ); ?></p>
		</div>

		<?php endif; ?>
		<div class="clear"></div>
	</div>

	<div class="bp-widget group-members-list group-mods-list">
		<h3 class="section-header"><?php esc_html_e( 'Moderators', 'arcane' ); ?></h3>

		<?php if ( bp_group_has_members( array( 'per_page' => 15, 'group_role' => array( 'mod' ), 'page_arg' => 'mlpage-mod' ) ) ) : ?>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

			<ul id="mods-list" class="item-list">

				<?php while ( bp_group_members() ) : bp_group_the_member(); ?>
					<li>
						<div class="item-avatar">
                           <a href="<?php bp_member_permalink(); ?>"><?php echo get_avatar(bp_get_member_user_id(), 50,null,'', ['class' => 'avatar user-1-avatar avatar-50 photo']); ?></a>
                        </div>

                        <div class="item">
                            <div class="item-title">
                                <a href="<?php echo esc_attr(bp_core_get_user_domain( bp_get_member_user_id() )); ?>"><?php echo esc_attr(bp_core_get_user_displayname( bp_get_member_user_id() )); ?></a>
                                <div >
                                    <span class="activity">
                                        <?php bp_group_member_joined_since(); ?>
                                    </span>
                                </div>
                                <?php do_action( 'bp_group_manage_members_admin_item', 'admins-list' ); ?>
                            </div>
                        </div>

						<div class="action">
							<a href="<?php bp_group_member_promote_admin_link(); ?>" class="button confirm mod-promote-to-admin"><?php esc_html_e( 'Promote to Admin', 'arcane' ); ?></a>
							<a class="button confirm mod-demote-to-member" href="<?php bp_group_member_demote_link(); ?>"><?php esc_html_e( 'Demote to Member', 'arcane' ); ?></a>

							<?php

							/**
							 * Fires inside the action section of a member admin item in group management area.
							 *
							 * @since 2.7.0
							 *
							 * @param $section Which list contains this item.
							 */
							do_action( 'bp_group_manage_members_admin_actions', 'mods-list' ); ?>

						</div>
					</li>
				<?php endwhile; ?>

			</ul>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

		<?php else: ?>

			<div id="message" class="info">
				<p><?php esc_html_e( 'No group moderators were found.', 'arcane' ); ?></p>
			</div>

		<?php endif; ?>
	</div>

	<div class="bp-widget group-members-list">
		<h3 class="section-header"><?php esc_html_e( "Members", 'arcane' ); ?></h3>

		<?php if ( bp_group_has_members( array( 'per_page' => 15, 'exclude_banned' => 0 ) ) ) : ?>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

			<ul id="members-list-fn" class="item-list" aria-live="assertive" aria-relevant="all">
				<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

					<li class="<?php bp_group_member_css_class(); ?>">

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
                                <?php do_action( 'bp_group_manage_members_admin_item', 'admins-list' ); ?>
                            </div>
                        </div>

						<div class="action">
							<?php if ( bp_get_group_member_is_banned() ) : ?>

								<a href="<?php bp_group_member_unban_link(); ?>" class="button confirm member-unban"><?php esc_html_e( 'Remove Ban', 'arcane' ); ?></a>

							<?php else : ?>

								<a href="<?php bp_group_member_ban_link(); ?>" class="button confirm member-ban"><?php esc_html_e( 'Kick &amp; Ban', 'arcane' ); ?></a>
								<a href="<?php bp_group_member_promote_mod_link(); ?>" class="button confirm member-promote-to-mod"><?php esc_html_e( 'Promote to Mod', 'arcane' ); ?></a>
								<a href="<?php bp_group_member_promote_admin_link(); ?>" class="button confirm member-promote-to-admin"><?php esc_html_e( 'Promote to Admin', 'arcane' ); ?></a>

							<?php endif; ?>

							<a href="<?php bp_group_member_remove_link(); ?>" class="button confirm"><?php esc_html_e( 'Remove from group', 'arcane' ); ?></a>

							<?php

							/**
							 * Fires inside the action section of a member admin item in group management area.
							 *
							 * @since 2.7.0
							 *
							 * @param $section Which list contains this item.
							 */
							do_action( 'bp_group_manage_members_admin_actions', 'members-list' ); ?>
						</div>
					</li>

				<?php endwhile; ?>
			</ul>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

		<?php else: ?>

			<div id="message" class="info">
				<p><?php esc_html_e( 'No group members were found.', 'arcane' ); ?></p>
			</div>

		<?php endif; ?>
	</div>

</div>

<?php

/**
 * Fires after the group manage members admin display.
 *
 * @since 1.1.0
 */
do_action( 'bp_after_group_manage_members_admin' );
