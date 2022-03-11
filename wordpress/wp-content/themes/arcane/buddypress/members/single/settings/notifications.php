<?php
/**
 * BuddyPress - Members Settings ( Notifications )
 *
 * @since 3.0.0
 * @version 3.0.0
 */

bp_nouveau_member_hook( 'before', 'settings_template' ); ?>
	<?php
	$uid = bp_displayed_user_id();
	$email_matches = get_user_meta($uid, 'email_matches_subscribed', true);
	$email_tournaments = get_user_meta($uid, 'email_tournaments_subscribed', true);
	$email_team = get_user_meta($uid, 'email_teams_subscribed', true);

	?>

	<h2 class="screen-heading email-settings-screen">
		<?php _e( 'Email Notifications', 'arcane' ); ?>
	</h2>

	<p class="bp-help-text email-notifications-info">
		<?php _e( 'Set your email notification preferences.', 'arcane' ); ?>
	</p>

	<form action="<?php echo esc_url( bp_displayed_user_domain() . bp_get_settings_slug() . '/notifications' ); ?>" method="post" class="standard-form" id="settings-form">

		<?php bp_nouveau_member_email_notice_settings(); ?>


		<table class="notification-settings" id="activity-notification-settings">
			<thead>
			<tr>
				<th class="icon">&nbsp;</th>
				<th class="title"><?php esc_html_e('Team Wars', 'arcane'); ?></th>
				<th class="yes"><?php esc_html_e('Yes', 'arcane'); ?></th>
				<th class="no"><?php esc_html_e('No', 'arcane'); ?></th>
			</tr>
			</thead>

			<tbody>
			<tr id="activity-notification-settings-mentions">
				<td>&nbsp;</td>
				<td>
					<?php esc_html_e('Matches related notifications', 'arcane'); ?>
				</td>
				<td class="yes">
					<input type="radio" name="matches_notifications" id="notification-activity-new-mention-yes" value="yes" <?php if($email_matches) echo 'checked="checked"'; ?>>
				</td>
				<td class="no">
					<input type="radio" name="matches_notifications" id="notification-activity-new-mention-no" value="no" <?php if(!$email_matches) echo 'checked="checked"'; ?>>
				</td>
			</tr>

			<tr id="activity-notification-settings-replies">
				<td>&nbsp;</td>
				<td>
					<?php esc_html_e('Team related notifications', 'arcane'); ?>
				</td>

				<td class="yes">
					<input type="radio" name="team_notifications" id="notification-activity-new-reply-yes" value="yes" <?php if($email_team) echo 'checked="checked"'; ?> >
				</td>
				<td class="no">
					<input type="radio" name="team_notifications" id="notification-activity-new-reply-no" value="no" <?php if(!$email_team) echo 'checked="checked"'; ?>>
				</td>
			</tr>

			<tr id="activity-notification-settings-replies">
				<td>&nbsp;</td>
				<td>
					<?php esc_html_e('Tournament related notifications', 'arcane'); ?>
				</td>
				<td class="yes">
					<input  type="radio" name="tournament_notifications" id="notification-activity-new-reply-yes" value="yes" <?php if($email_tournaments) echo 'checked="checked"'; ?>>
				</td>
				<td class="no">
					<input type="radio" name="tournament_notifications" id="notification-activity-new-reply-no" value="no" <?php if(!$email_tournaments) echo 'checked="checked"'; ?>>
				</td>
			</tr>

			</tbody>
		</table>

		<?php bp_nouveau_submit_button( 'member-notifications-settings' ); ?>

	</form>

<?php
bp_nouveau_member_hook( 'after', 'settings_template' );
