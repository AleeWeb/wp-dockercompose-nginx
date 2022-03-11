<?php
 /*
 * Template Name: Activation Template
 */
?>
<?php get_header(); ?>
<?php

if(isset($_GET['key']))
$hash = $_GET['key'];
if(isset($_GET['id']))
$uid = $_GET['id'];
if(isset($_GET['act_error']))
$error = $_GET['act_error'];
if(isset($_GET['uid']))
$ac_uid = $_GET['uid'];
?>
<div class="registration-login">
  <div class="page normal-page">
  	<div class="container ">

		<div class="col-lg-7 col-md-12 register-form-wrapper wcontainer">

			<?php
			if(isset($uid))
			$user_hash = get_user_meta($uid, 'hash', true);

			if($error == 1){ ?>
                <?php $udata = get_userdata($ac_uid); ?>
				<i class="fas fa-minus-circle"></i>
				<?php
				esc_html_e('Your account is not active yet, please check your email for activation link!', 'arcane'); ?>

                <a data-email="<?php echo esc_attr($udata->user_email); ?>" data-uid="<?php echo esc_attr($ac_uid); ?>" id="resend_activation"><i class="fas fa-retweet" aria-hidden="true"></i><?php esc_html_e(" Resend activation link!", "arcane"); ?> </a>
			<?php }else{
				if($user_hash == $hash){ update_user_meta($uid, 'active', 'true');

					?>
					<i class="fas fa-info-circle"></i>
					<?php
					esc_html_e('Congratulations, you can now log-in into your account!', 'arcane');

				}else{

					?>
					<i class="fas fa-minus-circle"></i>
					<?php
					esc_html_e('Ooops your hash code is invalid, cheating huh?!', 'arcane');

				}
			}
			?>

		</div>
	</div>
  </div>
</div>
<?php get_footer(); ?>