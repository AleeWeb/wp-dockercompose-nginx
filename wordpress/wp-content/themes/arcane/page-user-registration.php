<?php
 /*
 * Template Name: Login Template
 */
?>
<?php

if(get_option( 'users_can_register' ) == '0'){wp_redirect( home_url('/'), $status = 302);}
?>
<?php get_header(); ?>

<?php

global $arcane_noviuser, $arcane_error_msg, $wpdb;
$arcane_noviuser = '';
$arcane_error_msg = '';
if(isset($_POST['wp-submit']))$submit = $_POST['wp-submit'];
if(isset($_POST['user_login']))$user_id = $_POST['user_login'];
if(isset($_POST['user_email']))$user_email = $_POST['user_email'];
if(isset($_POST['user-password']))$userpassword = $_POST['user-password'];
if(isset($_POST['confirm-password']))$confirmpassword = $_POST['confirm-password'];
if(isset($_POST['usercountry_id']))$user_country = $_POST['usercountry_id'];
if(isset($_POST['user_city']))$user_city = $_POST['user_city'];
if(isset($_POST['agree']))$term_agree = $_POST['agree'];
if(isset($_POST['premium']))$premium = $_POST['premium'];
if(isset($_POST['custom_fields']))$_POST['custom_fields'][1] = $_POST['user_login'];
if(isset($_POST['g-recaptcha-response']))$captcha = $_POST['g-recaptcha-response'];

$custom_profile_fields = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'bp_xprofile_fields WHERE group_id = 1');

if(isset($submit)) {

	 $arcane_error_msg = "";
    if (function_exists( 'gglcptch_init' ) || class_exists('ANR')){
        if($captcha == ''){
            $arcane_error_msg .= '<p>'.esc_html__("Captcha value not valid. ",'arcane').'</p>';
        }
    }
	if($term_agree == '') {
		$arcane_error_msg .= '<p>'.esc_html__("You must agree with terms and conditions. ",'arcane').'</p>';
    	}
	if($user_id == '') {
		$arcane_error_msg .= '<p>'.esc_html__("Please enter username. ",'arcane').'</p>';

	}
	if($user_email == '') {
		$arcane_error_msg .= '<p>'.esc_html__("Please enter email. ",'arcane').'</p>';

	}
    if( !is_email( $user_email ) ) {
        $arcane_error_msg .= '<p>'.esc_html__("Please enter valid email. ", 'arcane' ).'</p>';
    }

	if(strlen($userpassword) < 8) {
		$arcane_error_msg .= '<p>'.esc_html__("Password should be at least 8 characters long. ",'arcane').'</p>';
	}

    if($userpassword == '') {
        $arcane_error_msg .= '<p>'.esc_html__("Please enter password. ",'arcane').'</p>';
    }

    if($userpassword != $confirmpassword) {
		$arcane_error_msg .= '<p>'.esc_html__("Passwords do not match. ",'arcane').'</p>';
	}


	$posted_customs = $_POST['custom_fields'];
	$additional_error =false;
	$counter = 0;

	if (isset($posted_customs)) {
		foreach ($custom_profile_fields as $thefield) {
			if ($thefield->is_required == 1) {
				$therequiredfields[$counter] = $thefield->id;
				$counter ++;
			}
		}
	}
	if (is_array($therequiredfields)) {

		foreach ($therequiredfields as $findfield) {
			if (!isset($posted_customs[$findfield])) {
				$additional_error = true;
				$arcane_error_msg .= '<p>'.esc_html__("Please fill out all required fields. ",'arcane').'</p>';
			} else {
				if ((!(is_array($posted_customs[$findfield]))) AND (!(is_string($posted_customs[$findfield])))) {
					$additional_error = true;
					$arcane_error_msg .= '<p>'.esc_html__("Please fill out all required fields. ",'arcane').'</p>';
				} elseif(is_array($posted_customs[$findfield])) {
					if (!(count($posted_customs[$findfield]) > 0)) {
						$additional_error = true;
						$arcane_error_msg .= '<p>'.esc_html__("Please fill out all required fields. ",'arcane').'</p>';
					}
				} elseif (is_string($posted_customs[$findfield])) {
					if (!(strlen($posted_customs[$findfield]) > 0)) {
						$additional_error = true;
						$arcane_error_msg .= '<p>'.esc_html__("Please fill out all required fields. ",'arcane').'</p>';
					}
				}
			}
		}
	}





	if($user_id != '' & $user_email != '' & $term_agree != '' & (is_email( $user_email ) !== false ) &  strlen($userpassword) >= 8 & $userpassword != '' & ($userpassword == $confirmpassword) & ($additional_error == false)) {
        $arcane_noviuser = 'sub';
		global $wpdp, $post;
        $getuser_login = get_user_by( 'login', $user_id );
		if(!empty($getuser_login)) {
	 		$arcane_error_msg .= '<p>'.esc_html__("Username ",'arcane'). $getuser_login->user_login . esc_html__(" is already in use. ",'arcane').'</p>';
		}
		$getuser_email = get_user_by( 'email', $user_email );
		if(!empty($getuser_email)) {
	 		$arcane_error_msg .= '<p>'.esc_html__("Email ",'arcane'). $user_email. esc_html__(" is already in use. ","arcane").'</p>';
		}

		if (empty($getuser_login) & empty($getuser_email) & empty($arcane_error_msg) ) {
			$userdata = array(
			'user_login' => $user_id,
			'user_pass'  => $userpassword,
			'user_email' => $user_email
			);
			$new_user_id = wp_insert_user( $userdata );
			wp_new_user_notification($new_user_id);
            $random_number = rand();
			$hash = md5($random_number);
			add_user_meta( $new_user_id, 'hash', $hash );
			add_user_meta( $new_user_id, 'active', 'false' );
			update_user_meta($new_user_id, 'email_matches_subscribed', true);
            update_user_meta($new_user_id, 'email_tournaments_subscribed', true);
            update_user_meta($new_user_id, 'email_teams_subscribed', true);
			$subject = esc_html__('From ','arcane').get_bloginfo();
			$message = esc_html__("Username:",'arcane')." $user_id \n\n". esc_html__("Password: ",'arcane'). "$userpassword";
			$message .= "\n\n";
			$message .= esc_html__('Please click this link to activate your account: ','arcane');
			$message .= esc_url(get_permalink( get_page_by_path('user-activation'))).'?id='.$new_user_id.'&key='.$hash;
			$headers = 'From: '.get_bloginfo().' <'.get_option("admin_email").'>' . "\r\n" . 'Reply-To: ' . $user_email;

            if (class_exists( 'Arcane_Types' )){
             $arcane_types = new Arcane_Types();
             $arcane_types::arcane_send_email( $user_email, $subject, $message, $headers );
            }

			if(!empty($user_country))
				update_user_meta($new_user_id, 'usercountry_id', esc_attr($user_country));
			if (!empty($user_city))
				update_user_meta($new_user_id, 'city', esc_attr($user_city));
			if( is_wp_error($new_user_id) ) {
				$arcane_error_msg .= $user_id->get_error_message();
			}

			//tusi2
			global $wpdb;
            $preppedvalue = '';

			if (isset($posted_customs) AND (is_array($posted_customs))) {
				foreach ($posted_customs as $akey => $acustom) {
					//$custom_profile_fields = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'bp_xprofile_fields');
					$holdfield = '';
					foreach ($custom_profile_fields as $thefield) {
						if ($thefield->id == $akey) {
							$holdfield = $thefield;
						}
					}

					if (($holdfield->type == "textbox") OR ($holdfield->type == "datebox") OR ($holdfield->type == "number") OR ($holdfield->type == "url") OR ($holdfield->type == "textarea")) {
						$preppedvalue = $acustom;
					} elseif(($holdfield->type == "checkbox")) {
						$counter = 0;
						unset($preppedvalue);
						foreach ($acustom as $tehkey1 => $holdthecustom1) {
							foreach ($custom_profile_fields as $thefield1) {
								if ($thefield1->id == $tehkey1) {
									$preppedvalue[$counter] = $thefield1->name;
									$counter ++;
								}
							}
						}
					}elseif(($holdfield->type == "multiselectbox")) {
						$counter = 0;
						unset($preppedvalue);
						foreach ($acustom as $tehkey => $holdthecustom) {
							foreach ($custom_profile_fields as $thefield) {
								if ($thefield->id == $tehkey) {
									$preppedvalue[$counter] = $thefield1->name;
									$counter ++;
								}
							}
						}

					} elseif(($holdfield->type == "radio")) {
						//($holdfield->type == "selectbox") OR
						foreach ($custom_profile_fields as $thefield) {
							if ($thefield->id == $acustom) {
								$preppedvalue = $thefield->name;
							}
						}

					}elseif(($holdfield->type == "selectbox")) {
						//($holdfield->type == "selectbox") OR
						foreach ($custom_profile_fields as $thefield) {
							if ($thefield->id == $acustom) {
								$preppedvalue = $thefield->name;
							}
						}
					}

					if (isset($preppedvalue) && is_array($preppedvalue)) {
						$theholder = serialize($preppedvalue);
					} else {
						$theholder = $preppedvalue;
					}

					$wpdb->insert(
						$wpdb->prefix."bp_xprofile_data",
						array(
							'field_id' => $akey,
							'user_id' => $new_user_id,
							'value' =>  $theholder,
							'last_updated' => $arcane_current_time
						),
						array(
							'%d',
							'%d',
							'%s',
							'%s',
						)
					);


				}
			}
		}
	}
}

?>

<div class="registration-login">
  <div class="page normal-page">
  	<div class="container ">


		<div class="col-8 register-form-wrapper wcontainer">
        <?php if(isset($arcane_noviuser) && $arcane_noviuser == 'sub' && empty($arcane_error_msg)){ ?>

            <i class="fas fa-info-circle"></i>
            <?php esc_html_e(" Registration successful. Activation email has been sent!", "arcane"); ?>
            <a data-email="<?php echo esc_attr($user_email); ?>" data-uid="<?php echo esc_attr($new_user_id); ?>" id="resend_activation"><i class="fas fa-retweet" aria-hidden="true"></i><?php esc_html_e(" Resend activation link!", "arcane"); ?> </a>
		<?php } else{ ?>

						<div class="title-wrapper">
							<h3 class="widget-title">
							<?php
							$options['cpagetitle'] = '';
                            $options['terms'] = '';
							$options = arcane_get_theme_options();
							if(!empty($options['cpagetitle'])){
								echo esc_attr($options['cpagetitle']);
							}else{
						 	esc_html_e("JOIN arcane TODAY FOR free!", 'arcane');
							} ?>
							</h3>
							<div class="clear"></div>
						</div>


                        <?php if(!empty($arcane_error_msg)) {  ?><div class="error_msg"><span><?php
                        $allowed_tags = array(
							'p' => array(),
							);
                             echo wp_kses($arcane_error_msg, $allowed_tags ); ?></span></div><?php } ?>
							<form method="post">
								<p>
									<label><?php esc_html_e("Username:", 'arcane'); ?></label>
									<span class="cust_input">
									<input type="text" name="user_login" id="user_login" class="input" size="20" tabindex="10" value="<?php if(isset($_POST['user_login'])){echo esc_attr($_POST['user_login']); } ?>" />
									</span>
								</p>
								<p>
									<label><?php esc_html_e("Email:", 'arcane'); ?></label>
									<span class="cust_input">
									<input type="text" name="user_email" id="user_email" class="input" size="25" tabindex="20" value="<?php if(isset($_POST['user_email'])){echo esc_attr($_POST['user_email']); } ?>"  />
									</span>
								</p>
								<p>
									<label><?php esc_html_e("Password:", 'arcane'); ?></label>
									<span class="cust_input">
									<input type="password" name="user-password" id="user-password" class="input" size="25" tabindex="30" />
									</span>
								</p>
								<p>
									<label><?php esc_html_e("Confirm Password:", 'arcane'); ?></label>
									<span class="cust_input">
									<input type="password" name="confirm-password" id="confirm-password" class="input" size="25" tabindex="30" />
									</span>
								</p>
								<p>

									<label for="usercountry_id"><?php esc_html_e('Country', 'arcane'); ?></label>
                                    <?php
									$countries = arcane_registration_country_list()
									?>
									<span class="cust_input">
									<select name="usercountry_id">
										<option value="0"><?php esc_html_e('- Select -','arcane') ?></option>
										<?php
                                        foreach ($countries as $country) {
                                            $selected="";
                                            if ($_POST['usercountry_id']==$country->id_country) { $selected="selected";}

											if($country->name == 'Afghanistan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Afghanistan', 'arcane' ).'</option>';
										}elseif($country->name == 'Albania'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Albania', 'arcane' ).'</option>';
										}elseif($country->name == 'Algeria'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Algeria', 'arcane' ).'</option>';
										}elseif($country->name == 'American Samoa'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'American Samoa', 'arcane' ).'</option>';
										}elseif($country->name == 'Andorra'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Andorra', 'arcane' ).'</option>';
										}elseif($country->name == 'Angola'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Angola', 'arcane' ).'</option>';
										}elseif($country->name == 'Anguilla'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Anguilla', 'arcane' ).'</option>';
										}elseif($country->name == 'Antarctica'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Antarctica', 'arcane' ).'</option>';
										}elseif($country->name == 'Antigua and Barbuda'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Antigua and Barbuda', 'arcane' ).'</option>';
										}elseif($country->name == 'Argentina'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Argentina', 'arcane' ).'</option>';
										}elseif($country->name == 'Armenia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Armenia', 'arcane' ).'</option>';
										}elseif($country->name == 'Aruba'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Aruba', 'arcane' ).'</option>';
										}elseif($country->name == 'Australia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Australia', 'arcane' ).'</option>';
										}elseif($country->name == 'Austria'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Austria', 'arcane' ).'</option>';
										}elseif($country->name == 'Azerbaijan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Azerbaijan', 'arcane' ).'</option>';
										}elseif($country->name == 'Bahamas'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Bahamas', 'arcane' ).'</option>';
										}elseif($country->name == 'Bahrain'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Bahrain', 'arcane' ).'</option>';
										}elseif($country->name == 'Bangladesh'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Bangladesh', 'arcane' ).'</option>';
										}elseif($country->name == 'Barbados'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Barbados', 'arcane' ).'</option>';
										}elseif($country->name == 'Belarus'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Belarus', 'arcane' ).'</option>';
										}elseif($country->name == 'Belgium'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Belgium', 'arcane' ).'</option>';
										}elseif($country->name == 'Belize'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Belize', 'arcane' ).'</option>';
										}elseif($country->name == 'Benin'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Benin', 'arcane' ).'</option>';
										}elseif($country->name == 'Bermuda'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Bermuda', 'arcane' ).'</option>';
										}elseif($country->name == 'Bhutan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Bhutan', 'arcane' ).'</option>';
										}elseif($country->name == 'Bolivia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Bolivia', 'arcane' ).'</option>';
										}elseif($country->name == 'Bosnia and Herzegowina'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Bosnia and Herzegowina', 'arcane' ).'</option>';
										}elseif($country->name == 'Botswana'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Botswana', 'arcane' ).'</option>';
										}elseif($country->name == 'Bouvet Island'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Bouvet Island', 'arcane' ).'</option>';
										}elseif($country->name == 'Brazil'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Brazil', 'arcane' ).'</option>';
										}elseif($country->name == 'British Indian Ocean Territory'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'British Indian Ocean Territory', 'arcane' ).'</option>';
										}elseif($country->name == 'Brunei Darussalam'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Brunei Darussalam', 'arcane' ).'</option>';
										}elseif($country->name == 'Bulgaria'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Bulgaria', 'arcane' ).'</option>';
										}elseif($country->name == 'Burkina Faso'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Burkina Faso', 'arcane' ).'</option>';
										}elseif($country->name == 'Burundi'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Burundi', 'arcane' ).'</option>';
										}elseif($country->name == 'Cambodia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Cambodia', 'arcane' ).'</option>';
										}elseif($country->name == 'Cameroon'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Cameroon', 'arcane' ).'</option>';
										}elseif($country->name == 'Canada'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Canada', 'arcane' ).'</option>';
										}elseif($country->name == 'Cape Verde'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Cape Verde', 'arcane' ).'</option>';
										}elseif($country->name == 'Cayman Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Cayman Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Central African Republic'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Central African Republic', 'arcane' ).'</option>';
										}elseif($country->name == 'Chad'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Chad', 'arcane' ).'</option>';
										}elseif($country->name == 'Chile'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Chile', 'arcane' ).'</option>';
										}elseif($country->name == 'China'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'China', 'arcane' ).'</option>';
										}elseif($country->name == 'Christmas Island'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Christmas Island', 'arcane' ).'</option>';
										}elseif($country->name == 'Cocos (Keeling) Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Cocos (Keeling) Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Colombia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Colombia', 'arcane' ).'</option>';
										}elseif($country->name == 'Comoros'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Comoros', 'arcane' ).'</option>';
										}elseif($country->name == 'Congo'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Congo', 'arcane' ).'</option>';
										}elseif($country->name == 'Cook Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Cook Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Costa Rica'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Costa Rica', 'arcane' ).'</option>';
										}elseif($country->name == 'Cote D\'Ivoire'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Cote D\'Ivoire', 'arcane' ).'</option>';
										}elseif($country->name == 'Croatia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Croatia', 'arcane' ).'</option>';
										}elseif($country->name == 'Cuba'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Cuba', 'arcane' ).'</option>';
										}elseif($country->name == 'Cyprus'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Cyprus', 'arcane' ).'</option>';
										}elseif($country->name == 'Czech Republic'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Czech Republic', 'arcane' ).'</option>';
										}elseif($country->name == 'Denmark'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Denmark', 'arcane' ).'</option>';
										}elseif($country->name == 'Djibouti'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Djibouti', 'arcane' ).'</option>';
										}elseif($country->name == 'Dominica'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Dominica', 'arcane' ).'</option>';
										}elseif($country->name == 'Dominican Republic'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Dominican Republic', 'arcane' ).'</option>';
										}elseif($country->name == 'East Timor'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'East Timor', 'arcane' ).'</option>';
										}elseif($country->name == 'Ecuador'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Ecuador', 'arcane' ).'</option>';
										}elseif($country->name == 'Egypt'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Egypt', 'arcane' ).'</option>';
										}elseif($country->name == 'El Salvador'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'El Salvador', 'arcane' ).'</option>';
										}elseif($country->name == 'Equatorial Guinea'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Equatorial Guinea', 'arcane' ).'</option>';
										}elseif($country->name == 'Eritrea'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Eritrea', 'arcane' ).'</option>';
										}elseif($country->name == 'Estonia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Estonia', 'arcane' ).'</option>';
										}elseif($country->name == 'Ethiopia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Ethiopia', 'arcane' ).'</option>';
										}elseif($country->name == 'Falkland Islands (Malvinas)'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Falkland Islands (Malvinas)', 'arcane' ).'</option>';
										}elseif($country->name == 'Faroe Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Faroe Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Fiji'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Fiji', 'arcane' ).'</option>';
										}elseif($country->name == 'Finland'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Finland', 'arcane' ).'</option>';
										}elseif($country->name == 'France'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'France', 'arcane' ).'</option>';
										}elseif($country->name == 'France, Metropolitan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'France, Metropolitan', 'arcane' ).'</option>';
										}elseif($country->name == 'French Guiana'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'French Guiana', 'arcane' ).'</option>';
										}elseif($country->name == 'French Polynesia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'French Polynesia', 'arcane' ).'</option>';
										}elseif($country->name == 'French Southern Territories'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'French Southern Territories', 'arcane' ).'</option>';
										}elseif($country->name == 'Gabon'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Gabon', 'arcane' ).'</option>';
										}elseif($country->name == 'Gambia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Gambia', 'arcane' ).'</option>';
										}elseif($country->name == 'Georgia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Georgia', 'arcane' ).'</option>';
										}elseif($country->name == 'Germany'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Germany', 'arcane' ).'</option>';
										}elseif($country->name == 'Ghana'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Ghana', 'arcane' ).'</option>';
										}elseif($country->name == 'Gibraltar'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Gibraltar', 'arcane' ).'</option>';
										}elseif($country->name == 'Greece'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Greece', 'arcane' ).'</option>';
										}elseif($country->name == 'Greenland'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Greenland', 'arcane' ).'</option>';
										}elseif($country->name == 'Grenada'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Grenada', 'arcane' ).'</option>';
										}elseif($country->name == 'Guadeloupe'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Guadeloupe', 'arcane' ).'</option>';
										}elseif($country->name == 'Guam'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Guam', 'arcane' ).'</option>';
										}elseif($country->name == 'Guatemala'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Guatemala', 'arcane' ).'</option>';
										}elseif($country->name == 'Guinea'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Guinea', 'arcane' ).'</option>';
										}elseif($country->name == 'Guinea-bissau'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Guinea-bissau', 'arcane' ).'</option>';
										}elseif($country->name == 'Guyana'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Guyana', 'arcane' ).'</option>';
										}elseif($country->name == 'Haiti'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Haiti', 'arcane' ).'</option>';
										}elseif($country->name == 'Heard and Mc Donald Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Heard and Mc Donald Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Honduras'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Honduras', 'arcane' ).'</option>';
										}elseif($country->name == 'Hong Kong'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Hong Kong', 'arcane' ).'</option>';
										}elseif($country->name == 'Hungary'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Hungary', 'arcane' ).'</option>';
										}elseif($country->name == 'Iceland'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Iceland', 'arcane' ).'</option>';
										}elseif($country->name == 'India'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'India', 'arcane' ).'</option>';
										}elseif($country->name == 'Indonesia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Indonesia', 'arcane' ).'</option>';
										}elseif($country->name == 'Iran (Islamic Republic of)'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Iran (Islamic Republic of)', 'arcane' ).'</option>';
										}elseif($country->name == 'Iraq'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Iraq', 'arcane' ).'</option>';
										}elseif($country->name == 'Ireland'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Ireland', 'arcane' ).'</option>';
										}elseif($country->name == 'Israel'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Israel', 'arcane' ).'</option>';
										}elseif($country->name == 'Italy'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Italy', 'arcane' ).'</option>';
										}elseif($country->name == 'Jamaica'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Jamaica', 'arcane' ).'</option>';
										}elseif($country->name == 'Japan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Japan', 'arcane' ).'</option>';
										}elseif($country->name == 'Jordan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Jordan', 'arcane' ).'</option>';
										}elseif($country->name == 'Kazakhstan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Kazakhstan', 'arcane' ).'</option>';
										}elseif($country->name == 'Kenya'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Kenya', 'arcane' ).'</option>';
										}elseif($country->name == 'Kiribati'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Kiribati', 'arcane' ).'</option>';
										}elseif($country->name == 'Korea, Democratic People\'s Republic of'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Korea, Democratic People\'s Republic of', 'arcane' ).'</option>';
										}elseif($country->name == 'Korea, Republic of'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Korea, Republic of', 'arcane' ).'</option>';
										}elseif($country->name == 'Kuwait'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Kuwait', 'arcane' ).'</option>';
										}elseif($country->name == 'Kyrgyzstan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Kyrgyzstan', 'arcane' ).'</option>';
										}elseif($country->name == 'Lao People\'s Democratic Republic'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Lao People\'s Democratic Republic', 'arcane' ).'</option>';
										}elseif($country->name == 'Latvia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Latvia', 'arcane' ).'</option>';
										}elseif($country->name == 'Lebanon'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Lebanon', 'arcane' ).'</option>';
										}elseif($country->name == 'Lesotho'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Lesotho', 'arcane' ).'</option>';
										}elseif($country->name == 'Liberia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Liberia', 'arcane' ).'</option>';
										}elseif($country->name == 'Libyan Arab Jamahiriya'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Libyan Arab Jamahiriya', 'arcane' ).'</option>';
										}elseif($country->name == 'Liechtenstein'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Liechtenstein', 'arcane' ).'</option>';
										}elseif($country->name == 'Lithuania'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Lithuania', 'arcane' ).'</option>';
										}elseif($country->name == 'Luxembourg'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Luxembourg', 'arcane' ).'</option>';
										}elseif($country->name == 'Macau'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Macau', 'arcane' ).'</option>';
										}elseif($country->name == 'Macedonia, The Former Yugoslav Republic of'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Macedonia, The Former Yugoslav Republic of', 'arcane' ).'</option>';
										}elseif($country->name == 'Madagascar'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Madagascar', 'arcane' ).'</option>';
										}elseif($country->name == 'Malawi'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Malawi', 'arcane' ).'</option>';
										}elseif($country->name == 'Malaysia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Malaysia', 'arcane' ).'</option>';
										}elseif($country->name == 'Maldives'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Maldives', 'arcane' ).'</option>';
										}elseif($country->name == 'Mali'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Mali', 'arcane' ).'</option>';
										}elseif($country->name == 'Malta'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Malta', 'arcane' ).'</option>';
										}elseif($country->name == 'Marshall Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Marshall Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Martinique'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Martinique', 'arcane' ).'</option>';
										}elseif($country->name == 'Mauritania'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Mauritania', 'arcane' ).'</option>';
										}elseif($country->name == 'Mauritius'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Mauritius', 'arcane' ).'</option>';
										}elseif($country->name == 'Mayotte'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Mayotte', 'arcane' ).'</option>';
										}elseif($country->name == 'Mexico'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Mexico', 'arcane' ).'</option>';
										}elseif($country->name == 'Micronesia, Federated States of'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Micronesia, Federated States of', 'arcane' ).'</option>';
										}elseif($country->name == 'Moldova, Republic of'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Moldova, Republic of', 'arcane' ).'</option>';
										}elseif($country->name == 'Monaco'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Monaco', 'arcane' ).'</option>';
										}elseif($country->name == 'Mongolia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Mongolia', 'arcane' ).'</option>';
										}elseif($country->name == 'Montserrat'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Montserrat', 'arcane' ).'</option>';
										}elseif($country->name == 'Morocco'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Morocco', 'arcane' ).'</option>';
										}elseif($country->name == 'Mozambique'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Mozambique', 'arcane' ).'</option>';
										}elseif($country->name == 'Myanmar'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Myanmar', 'arcane' ).'</option>';
										}elseif($country->name == 'Namibia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Namibia', 'arcane' ).'</option>';
										}elseif($country->name == 'Nauru'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Nauru', 'arcane' ).'</option>';
										}elseif($country->name == 'Nepal'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Nepal', 'arcane' ).'</option>';
										}elseif($country->name == 'Netherlands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Netherlands', 'arcane' ).'</option>';
										}elseif($country->name == 'Netherlands Antilles'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Netherlands Antilles', 'arcane' ).'</option>';
										}elseif($country->name == 'New Caledonia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'New Caledonia', 'arcane' ).'</option>';
										}elseif($country->name == 'New Zealand'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'New Zealand', 'arcane' ).'</option>';
										}elseif($country->name == 'Nicaragua'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Nicaragua', 'arcane' ).'</option>';
										}elseif($country->name == 'Niger'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Niger', 'arcane' ).'</option>';
										}elseif($country->name == 'Nigeria'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Nigeria', 'arcane' ).'</option>';
										}elseif($country->name == 'Niue'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Niue', 'arcane' ).'</option>';
										}elseif($country->name == 'Norfolk Island'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Norfolk Island', 'arcane' ).'</option>';
										}elseif($country->name == 'Northern Mariana Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Northern Mariana Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Norway'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Norway', 'arcane' ).'</option>';
										}elseif($country->name == 'Oman'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Oman', 'arcane' ).'</option>';
										}elseif($country->name == 'Pakistan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Pakistan', 'arcane' ).'</option>';
										}elseif($country->name == 'Palau'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Palau', 'arcane' ).'</option>';
										}elseif($country->name == 'Panama'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Panama', 'arcane' ).'</option>';
										}elseif($country->name == 'Papua New Guinea'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Papua New Guinea', 'arcane' ).'</option>';
										}elseif($country->name == 'Paraguay'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Paraguay', 'arcane' ).'</option>';
										}elseif($country->name == 'Peru'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Peru', 'arcane' ).'</option>';
										}elseif($country->name == 'Philippines'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Philippines', 'arcane' ).'</option>';
										}elseif($country->name == 'Pitcairn'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Pitcairn', 'arcane' ).'</option>';
										}elseif($country->name == 'Poland'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Poland', 'arcane' ).'</option>';
										}elseif($country->name == 'Portugal'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Portugal', 'arcane' ).'</option>';
										}elseif($country->name == 'Puerto Rico'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Puerto Rico', 'arcane' ).'</option>';
										}elseif($country->name == 'Qatar'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Qatar', 'arcane' ).'</option>';
										}elseif($country->name == 'Reunion'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Reunion', 'arcane' ).'</option>';
										}elseif($country->name == 'Romania'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Romania', 'arcane' ).'</option>';
										}elseif($country->name == 'Russian Federation'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Russian Federation', 'arcane' ).'</option>';
										}elseif($country->name == 'Rwanda'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Rwanda', 'arcane' ).'</option>';
										}elseif($country->name == 'Saint Kitts and Nevis'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Saint Kitts and Nevis', 'arcane' ).'</option>';
										}elseif($country->name == 'Saint Lucia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Saint Lucia', 'arcane' ).'</option>';
										}elseif($country->name == 'Saint Vincent and the Grenadines'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Saint Vincent and the Grenadines', 'arcane' ).'</option>';
										}elseif($country->name == 'Samoa'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Samoa', 'arcane' ).'</option>';
										}elseif($country->name == 'San Marino'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'San Marino', 'arcane' ).'</option>';
										}elseif($country->name == 'Sao Tome and Principe'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Sao Tome and Principe', 'arcane' ).'</option>';
										}elseif($country->name == 'Saudi Arabia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Saudi Arabia', 'arcane' ).'</option>';
										}elseif($country->name == 'Senegal'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Senegal', 'arcane' ).'</option>';
										}elseif($country->name == 'Serbia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Serbia', 'arcane' ).'</option>';
										}elseif($country->name == 'Seychelles'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Seychelles', 'arcane' ).'</option>';
										}elseif($country->name == 'Sierra Leone'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Sierra Leone', 'arcane' ).'</option>';
										}elseif($country->name == 'Singapore'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Singapore', 'arcane' ).'</option>';
										}elseif($country->name == 'Slovakia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Slovakia', 'arcane' ).'</option>';
										}elseif($country->name == 'Slovenia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Slovenia', 'arcane' ).'</option>';
										}elseif($country->name == 'Solomon Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Solomon Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Somalia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Somalia', 'arcane' ).'</option>';
										}elseif($country->name == 'South Africa'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'South Africa', 'arcane' ).'</option>';
										}elseif($country->name == 'South Georgia and the South Sandwich Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'South Georgia and the South Sandwich Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Spain'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Spain', 'arcane' ).'</option>';
										}elseif($country->name == 'Sri Lanka'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Sri Lanka', 'arcane' ).'</option>';
										}elseif($country->name == 'St. Helena'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'St. Helena', 'arcane' ).'</option>';
										}elseif($country->name == 'St. Pierre and Miquelon'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'St. Pierre and Miquelon', 'arcane' ).'</option>';
										}elseif($country->name == 'Sudan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Sudan', 'arcane' ).'</option>';
										}elseif($country->name == 'Suriname'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Suriname', 'arcane' ).'</option>';
										}elseif($country->name == 'Svalbard and Jan Mayen Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Svalbard and Jan Mayen Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Swaziland'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Swaziland', 'arcane' ).'</option>';
										}elseif($country->name == 'Sweden'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Sweden', 'arcane' ).'</option>';
										}elseif($country->name == 'Switzerland'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Switzerland', 'arcane' ).'</option>';
										}elseif($country->name == 'Syrian Arab Republic'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Syrian Arab Republic', 'arcane' ).'</option>';
										}elseif($country->name == 'Taiwan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Taiwan', 'arcane' ).'</option>';
										}elseif($country->name == 'Tajikistan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Tajikistan', 'arcane' ).'</option>';
										}elseif($country->name == 'Tanzania, United Republic of'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Tanzania, United Republic of', 'arcane' ).'</option>';
										}elseif($country->name == 'Thailand'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Thailand', 'arcane' ).'</option>';
										}elseif($country->name == 'Togo'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Togo', 'arcane' ).'</option>';
										}elseif($country->name == 'Tokelau'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Tokelau', 'arcane' ).'</option>';
										}elseif($country->name == 'Tonga'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Tonga', 'arcane' ).'</option>';
										}elseif($country->name == 'Trinidad and Tobago'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Trinidad and Tobago', 'arcane' ).'</option>';
										}elseif($country->name == 'Tunisia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Tunisia', 'arcane' ).'</option>';
										}elseif($country->name == 'Turkey'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Turkey', 'arcane' ).'</option>';
										}elseif($country->name == 'Turkmenistan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Turkmenistan', 'arcane' ).'</option>';
										}elseif($country->name == 'Turks and Caicos Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Turks and Caicos Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Tuvalu'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Tuvalu', 'arcane' ).'</option>';
										}elseif($country->name == 'Uganda'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Uganda', 'arcane' ).'</option>';
										}elseif($country->name == 'Ukraine'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Ukraine', 'arcane' ).'</option>';
										}elseif($country->name == 'United Arab Emirates'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'United Arab Emirates', 'arcane' ).'</option>';
										}elseif($country->name == 'United Kingdom'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'United Kingdom', 'arcane' ).'</option>';
										}elseif($country->name == 'United States'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'United States', 'arcane' ).'</option>';
										}elseif($country->name == 'United States Minor Outlying Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'United States Minor Outlying Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Uruguay'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Uruguay', 'arcane' ).'</option>';
										}elseif($country->name == 'Uzbekistan'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Uzbekistan', 'arcane' ).'</option>';
										}elseif($country->name == 'Vanuatu'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Vanuatu', 'arcane' ).'</option>';
										}elseif($country->name == 'Vatican City State (Holy See)'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Vatican City State (Holy See)', 'arcane' ).'</option>';
										}elseif($country->name == 'Venezuela'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Venezuela', 'arcane' ).'</option>';
										}elseif($country->name == 'Viet Nam'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Viet Nam', 'arcane' ).'</option>';
										}elseif($country->name == 'Virgin Islands (British)'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Virgin Islands (British)', 'arcane' ).'</option>';
										}elseif($country->name == 'Virgin Islands (U.S.)'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Virgin Islands (U.S.)', 'arcane' ).'</option>';
										}elseif($country->name == 'Wallis and Futuna Islands'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Wallis and Futuna Islands', 'arcane' ).'</option>';
										}elseif($country->name == 'Western Sahara'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Western Sahara', 'arcane' ).'</option>';
										}elseif($country->name == 'Yemen'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Yemen', 'arcane' ).'</option>';
										}elseif($country->name == 'Zaire'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Zaire', 'arcane' ).'</option>';
										}elseif($country->name == 'Zambia'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Zambia', 'arcane' ).'</option>';
										}elseif($country->name == 'Zimbabwe'){
										           echo '<option '.$selected.' value='.esc_attr($country->id_country).'>'. esc_html__( 'Zimbabwe', 'arcane' ).'</option>';

										}
									}
                                        ?>
                                    </select>
                                    </span>
								</p>
								<p>
									<label><?php esc_html_e("City:", 'arcane'); ?></label>
									<span class="cust_input">
									<input type="text" name="user_city" id="user_city" class="input" size="25"  value="<?php if(isset($_POST['user_city'])){echo esc_attr($_POST['user_city']); } ?>" />
									</span>
								</p>

								<?php
								//tusi


								$required_marker= " *";
								if (is_array($custom_profile_fields)) {
									//print_r ($custom_profile_fields);
									foreach ($custom_profile_fields as $field) {

										if($field->id == '1') continue;
										if ($field->type == "textbox") {
											if ($field->is_required == 1) {
												$additional_text = $required_marker;
											} else {
												$additional_text ="";
											}
											?>
											<p>
												<label><?php echo esc_attr($field->name); ?><?php echo esc_attr($additional_text); ?></label>
												<span class="cust_input">
												<input type="text" name="custom_fields[<?php echo esc_attr($field->id); ?>]" id="custom_fields[<?php echo esc_attr($field->id); ?>]" class="input" size="20"  />
												</span>
											</p>
											<?php
										} elseif ($field->type == "checkbox") {
											if ($field->is_required == 1) {
												$additional_text = $required_marker;
											} else {
												$additional_text ="";
											}
											?>
											<p>
												<label><?php echo esc_attr($field->name); ?><?php echo esc_attr($additional_text); ?></label>
												<span class="cust_input">
												<?php
													foreach ($custom_profile_fields as $tempfield) {
														if ($tempfield->parent_id == $field->id) {
															echo '<input type ="checkbox" name="custom_fields['.esc_attr($field->id).']['.esc_attr($tempfield->id).']"';
															if ($tempfield->is_default_option == 1) {
																echo ' checked="yes"';
															}
															echo '>'.esc_attr($tempfield->name)."<br />";
														}
													}

												?>
												</span>
											</p>

											<?php
										}elseif ($field->type == "selectbox") {
											if ($field->is_required == 1) {
												$additional_text = $required_marker;
											} else {
												$additional_text ="";
											}
											?>
											<p>
												<label><?php echo esc_attr($field->name); ?><?php echo esc_attr($additional_text); ?></label>
												<span class="cust_input">
												<select name="<?php echo 'custom_fields['.$field->id.']'; ?>">
												<?php
													foreach ($custom_profile_fields as $tempfield) {
														if ($tempfield->parent_id == $field->id) {
															//selected
															$selected="";
															if ($tempfield->is_default_option == 1) {
																$selected = 'selected';
															}
															echo '<option '.esc_attr($selected).' value='.esc_attr($tempfield->id).'>'.esc_attr($tempfield->name).'</option>';

														}
													}

												?>
												</select>
												</span>
											</p>

											<?php
										}elseif ($field->type == "radio") {
											if ($field->is_required == 1) {
												$additional_text = $required_marker;
											} else {
												$additional_text ="";
											}
											?>
											<p>
												<label><?php echo esc_attr($field->name); ?><?php echo esc_attr($additional_text); ?></label>
												<span class="cust_input">
												<?php
													foreach ($custom_profile_fields as $tempfield) {
														if ($tempfield->parent_id == $field->id) {
															//selected
															$selected="";
															if ($tempfield->is_default_option == 1) {
																$selected = 'checked="yes"';
															}
															echo '<input type="radio"  value='.esc_attr($tempfield->id).' name="custom_fields['.esc_attr($field->id).']" '.esc_attr($selected).'>'.esc_attr($tempfield->name).'<br />';

														}
													}

												?>
												</span>
											</p>

											<?php
										}elseif ($field->type == "number") {
											if ($field->is_required == 1) {
												$additional_text = $required_marker;
											} else {
												$additional_text ="";
											}
											?>
											<p>
												<label><?php echo esc_attr($field->name); ?><?php echo esc_attr($additional_text); ?></label>
												<span class="cust_input">
												<input type="text" name="custom_fields[<?php echo esc_attr($field->id); ?>]" id="custom_fields[<?php echo esc_attr($field->id); ?>]" class="input limit_to_numbers" size="20"  />
												</span>
											</p>
											<?php
										} elseif ($field->type == "url") {
											if ($field->is_required == 1) {
												$additional_text = $required_marker;
											} else {
												$additional_text ="";
											}
											?>
											<p>
												<label><?php echo esc_attr($field->name); ?><?php echo esc_attr($additional_text); ?></label>
												<span class="cust_input">
												<input type="text" name="custom_fields[<?php echo esc_attr($field->id); ?>]" id="custom_fields[<?php echo esc_attr($field->id); ?>]" class="input limit_to_url" size="20"  />
												</span>
											</p>
											<?php
										}  elseif ($field->type == "textarea") {
											if ($field->is_required == 1) {
												$additional_text = $required_marker;
											} else {
												$additional_text ="";
											}
											?>
											<p>
												<label><?php echo esc_attr($field->name); ?><?php echo esc_attr($additional_text); ?></label>
												<span class="cust_input">
												<textarea name="custom_fields[<?php echo esc_attr($field->id); ?>]" id="custom_fields[<?php echo esc_attr($field->id); ?>]" class="input textarea" size="20"  /></textarea><br />
												</span>
											</p>
											<?php
										}elseif ($field->type == "multiselectbox") {
											if ($field->is_required == 1) {
												$additional_text = $required_marker;
											} else {
												$additional_text ="";
											}
											?>
											<p>
												<label><?php echo esc_attr($field->name); ?><?php echo esc_attr($additional_text); ?></label>
												<span class="cust_input">
												<?php
													foreach ($custom_profile_fields as $tempfield) {
														if ($tempfield->parent_id == $field->id) {
															echo '<input type ="checkbox" name="custom_fields['.esc_attr($field->id).']['.esc_attr($tempfield->id).']"';
															if ($tempfield->is_default_option == 1) {
																echo ' checked="yes"';
															}
															echo '>'.esc_attr($tempfield->name)."<br />";
														}
													}

												?>
												</span>
											</p>

											<?php
										}elseif ($field->type == "datebox") {


                                            if ($field->is_required == 1) {
												$additional_text = $required_marker;
											} else {
												$additional_text ="";
											}
                      ?>
                      <p>
                        <label><?php echo esc_attr($field->name); ?><?php echo esc_attr($additional_text); ?></label>
                        <span class="cust_input">
                        <?php
                              echo '<input autocomplete="off" type ="text" id="datepicker_'.esc_attr($field->id).'" name="custom_fields['.esc_attr($field->id).']">';
                        ?>
                        </span>
                      </p>

                      <?php
                    }
									}
								}
								?>

                                <?php wp_nonce_field('arcane_new_user','arcane_new_user_nonce', true, true ); ?>
								<p class="checkbox-reg">

								<label><input type="checkbox" name="agree" id="signupAgree" /> <span for="signupAgree"><?php echo esc_html($options['eighteen']); ?>
								    <a href="<?php if(!empty($options['terms'])){echo esc_url($options['terms']);}else{echo "#";} ?>" target="_blank"><?php esc_html_e("Terms & Conditions!", 'arcane') ?></a></span></label>

								</p>

								<p class="captcha-reg">
								    <?php do_action('register_form'); ?>
                                </p>

								<p class="submit"><button name="wp-submit" type="submit" id="wp-submit" class="button-green button-small" > <i class="fas fa-sign-in-alt"></i> <?php esc_html_e('Sign up today!', 'arcane'); ?></button></p>

								<input type="hidden" name="lwa" value="1" />
							</form>
            <?php } ?>

			</div>
		</div>
	</div>

</div>


<?php get_footer(); ?>