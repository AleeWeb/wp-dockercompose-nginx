<?php
 /*
 * Template Name: Lost password
 */
?>
<?php

if(get_option( 'users_can_register' ) == '0'){wp_redirect( home_url('/'), $status = 302);}
?>
<?php get_header(); ?>
<?php
$captcha_class = '';
if (function_exists( 'gglcptch_admin_init' )){
$captcha_class = 'captcha_enabled';
}
?>
<div class="registration-login">
  <div class="page normal-page">
    <div class="container ">

        <div class="col-lg-7 col-md-12 register-form-wrapper wcontainer">

<?php
        $update_user = '';
        if(isset($_GET['action']) && $_GET['action'] == 'rp' ){

         if( isset( $_POST['action'] ) && 'reset_pass' == $_POST['action'] )
         {

              if(isset($_POST['new_password']))
              {
                  $user = get_user_by( 'login', $_GET['login'] );

	              $key = trim( $_GET['key'] );
	              $login = trim( $_GET['login'] );

	              $check_user = check_password_reset_key( $key, $login );

	              if ( is_wp_error( $check_user ) ) {

		              if ( $check_user->get_error_code() === 'expired_key' ) {
			              $error = esc_html__( 'That key has expired. Please reset your password again.', 'arcane' );
		              } else {
			              $error = esc_html__( 'That key is no longer valid. Please reset your password again.', 'arcane' );
		              }

	              } else {
		              $update_user = wp_update_user( array(
				              'ID'        => $user->ID,
				              'user_pass' => $_POST['new_password']
			              )
		              );
	              }
              }else{
                  $error = esc_html__('Password filed cannot be empty!', 'arcane');
              }
         }

         if( ! empty( $error ) )
            echo '<div class="message"><p class="error"><strong>'.esc_html__('ERROR:', 'arcane').'</strong> '. esc_attr($error) .'</p></div>';

        ?>
        <?php if($update_user){

            echo '<div class="error_login"><p class="success">'. esc_html__('Password successfully changed!', 'arcane') .'</p></div>';

        }else{ ?>
         <!--html code-->
        <form method="post">
            <fieldset>
                <p><?php esc_html_e('Enter your new password below.', 'arcane'); ?></p>
                <p><label for="new_password"><?php esc_html_e('New password', 'arcane'); ?></label>
                    <?php $new_password = isset( $_POST['new_password'] ) ? $_POST['new_password'] : ''; ?>
                    <input type="password" name="new_password" id="new_password" value="<?php echo esc_attr($new_password); ?>" /></p>
                    <label for="new_password"><?php esc_html_e('Hint: The password should be at least twelve characters long.
                    To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).', 'arcane'); ?></label>
                <p>
                    <input type="hidden" name="action" value="reset_pass" />
                    <input type="submit" value="<?php esc_html_e('Reset Password','arcane');?>" class="button" id="submit" />
                </p>
            </fieldset>
        </form>

        <?php } ?>

    <?php

        }else{

        $error = '';
        $success = '';
        $message = '';

        // check if we're in reset form
        if( isset( $_POST['action'] ) && 'reset' == $_POST['action'] )
        {
            $user_login = trim($_POST['user_login']);

            if( empty( $user_login ) ) {
                $error = esc_html__('Enter a username or e-mail address..', 'arcane');
            }   else if( is_email($user_login) && ! email_exists( $user_login ) ) {
                $error = esc_html__('There is no user registered with that email address.', 'arcane');
            }   else if(function_exists( 'gglcptch_admin_init' ) && $gglcptch_options['reset_pwd_form'] == 1 && (!isset($_POST['g-recaptcha-response']) or empty($_POST['g-recaptcha-response']))){
                $error = esc_html__('Captcha value not valid.', 'arcane');
            }   else {

                $random_password = wp_generate_password( 12, false );
                if(is_email($user_login)){
                    $user = get_user_by( 'email', $user_login );
                    $email = $user_login;
                }else{
                    $user = get_user_by( 'login', $user_login );
                     $email = $user->user_email;
                }

                if(!isset($user->ID)){
                     $error = esc_html__('User with that username doesn\'t exist.', 'arcane');

                }


                  $key = get_password_reset_key($user);


                  if(is_wp_error($key) && isset($key->errors['captcha_error'][0]))
                  {
                    $error = str_replace ('<strong>ERROR</strong>:','',$key->errors['captcha_error'][0]);
                  }

                 if(  empty( $error ) )
                 {


                  $subject = esc_html__('From ','arcane').get_bloginfo();
                  $message .= esc_html__('Someone has requested a password reset for the following account:','arcane'). "\r\n\r\n";
                  $message .= "<strong><u>".$user->user_login. "</u></strong>. ";
                  $message .= esc_html__('If this was a mistake, just ignore this email and nothing will happen.','arcane'). "\r\n\r\n";
                  $message .= esc_html__('To reset your password, visit the following address:','arcane'). "\r\n\r\n";
                  $message .=  get_permalink( get_page_by_path('lost-password')).'?action=rp&key='.$key.'&login='. $user->user_login;

                  $headers = 'From: '.get_bloginfo().' <'.get_option("admin_email").'>' . "\r\n" . 'Reply-To: ' . $user_email;

                  $mail = false;

                  if (class_exists( 'Arcane_Types' )){					
                     $arcane_types = new Arcane_Types();
                     $mail = $arcane_types::arcane_send_email( $email, $subject, $message, $headers);
                      $mail = true;
                  }

                if( $mail )
                    $success = esc_html__('Check your email address for your new password.', 'arcane');
                }


            }

            if( ! empty( $error ) )
                echo '<div class="message"><p class="error"><strong>'.esc_html__('ERROR:', 'arcane').'</strong> '. esc_attr($error) .'</p></div>';

            if( ! empty( $success ) )
                echo '<div class="error_login"><p class="success">'. esc_attr($success) .'</p></div>';
        }
    ?>

    <!--html code-->
    <form id="lostpasswordform" name="lostpasswordform" action="" method="post">
        <fieldset>
            <p><?php esc_html_e('Please enter your username or email address. You will receive a link to create a new password via email.', 'arcane'); ?></p>

            <p><label for="user_login"><?php esc_html_e('Username or E-mail:', 'arcane'); ?></label>
            <?php $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : ''; ?>
            <input type="text" name="user_login" id="user_login" value="<?php echo esc_attr($user_login); ?>" />
            </p>

        <?php do_action( 'lostpassword_form' ); ?>
            <div class="captcha-lostpass">
                <input type="hidden" name="action" value="reset" />
                <input type="submit" value="<?php esc_html_e('Get New Password','arcane');?>" class="button" id="wp-submit" name="wp-submit" />
            </div>
        </fieldset>
    </form>




         <?php } ?>
        </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>