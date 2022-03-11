<?php
global $current_user, $wp_roles, $arcane_error;
wp_get_current_user();
$a_id  = bp_displayed_user_id();
$aboutMe = get_user_meta( $a_id, 'description', true );

?>


<div id="post-<?php the_ID(); ?>">
    <div class="entry-content entry settings_user">
		<?php //the_content(); ?>
		<?php if ( ! is_user_logged_in() ) : ?>
            <p class="warning">
				<?php esc_html_e( 'You must be logged in to edit your profile.', 'arcane' ); ?>
            </p><!-- .warning -->
		<?php else : ?>

				<?php
				if ( ( is_array( $arcane_error ) ) and ( count( $arcane_error ) > 0 ) ) {
					echo '<p class="error">' . implode( "<br />", $arcane_error ) . '</p>';
				}
				?>
                <form method="post" id="adduser">
                    <fieldset class="form-username">
                        <p><label for="first-name"><?php esc_html_e( 'First Name', 'arcane' ); ?></label>
                            <span class="cust_input">
                            <input class="text-input" name="first-name" type="text" id="first-name"
                                   value="<?php the_author_meta( 'first_name', $a_id ); ?>"/>
                            	</span>
                        </p></fieldset><!-- .form-username -->
                    <fieldset class="form-username">
                        <p><label for="last-name"><?php esc_html_e( 'Last Name', 'arcane' ); ?></label>
                            <span class="cust_input">
                            <input class="text-input" name="last-name" type="text" id="last-name"
                                   value="<?php the_author_meta( 'last_name', $a_id ); ?>"/>
                            	</span>
                        </p></fieldset><!-- .form-username -->
                    <fieldset class="form-username">
                        <p><label for="last-name"><?php esc_html_e( 'Nickname', 'arcane' ); ?></label>
                            <span class="cust_input">
                            <input class="text-input" name="nickname" type="text" id="nickname"
                                   value="<?php the_author_meta( 'nickname', $a_id ); ?>"/>
                            	</span>
                        </p></fieldset><!-- .form-username -->
                    <fieldset class="user_display_name">
                        <p>
                            <label for="user_display_name"><?php esc_html_e( 'Display name publicly as', 'arcane' ) ?></label>
                            <span class="cust_input">
							<select name="user_display_name" id="user_display_name">
							<?php
							$profileuser                        = get_user_by( 'id', $a_id );
							$public_display                     = array();
							$public_display['display_username'] = $profileuser->user_login;
							$public_display['display_nickname'] = $profileuser->nickname;

							if ( ! empty( $profileuser->first_name ) ) {
								$public_display['display_firstname'] = $profileuser->first_name;
							}

							if ( ! empty( $profileuser->last_name ) ) {
								$public_display['display_lastname'] = $profileuser->last_name;
							}

							if ( ! empty( $profileuser->first_name ) && ! empty( $profileuser->last_name ) ) {
								$public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
								$public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
							}

							if ( ! in_array( $profileuser->display_name, $public_display ) ) {
								$public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;
							}

							$public_display = array_map( 'trim', $public_display );
							$public_display = array_unique( $public_display );

							foreach ( $public_display as $id => $item ) { ?>
                                <option id="<?php echo esc_attr( $id ); ?>"
                                        value="<?php echo esc_attr( $item ); ?>"<?php selected( $profileuser->display_name, $item ); ?>><?php echo esc_attr( $item ); ?></option>
								<?php
							}
							?>
							</select>
							</span>
                        </p>
                    </fieldset>
                    <fieldset class="form-age">
                        <p>
                            <label for="age"><?php esc_html_e( 'Birthday', 'arcane' ); ?></label>
							<?php $age = get_user_meta( $current_user->ID, 'age', true ); ?>
                            <span class="cust_input">
               					<?php if ( ! isset( $age ) ) {
					                $age = '';
				                } ?>
				                <?php echo '<input value="' . esc_attr( $age ) . '" type ="text" id="birthday_field" name="birthday_field">';
				                echo '<input type ="hidden" id="alt-birthday-date" name="alt-birthday-date">';
				                ?>
                            </span>
                        </p>
                    </fieldset><!-- .form-username -->

                    <div class="regrest-upload-wrapper profilePhotoWrap">
                        <span>  <?php esc_html_e("Profile photo", "arcane"); ?></span>
                        <div class="profilePhoto uploaded-files">
			                <?php
			                $profilePhoto = get_user_meta( $a_id, 'profile_photo', true );

			                if(!empty($profilePhoto)){ ?>
                                <img style="display: block !important;" class="profilePhoto" alt="logo" src="<?php echo esc_url($profilePhoto); ?>" />
			                <?php }else{ ?>
                                <img class="profilePhoto" alt="profilePhoto" src="" />
			                <?php } ?>
                        </div>
                        <label class="fake-upload-btn">
                            <input type="file" class="ws-file-upload input-text"  accept="image/*" name="profilePhoto" id="profilePhoto" placeholder="<?php esc_html_e("Profile photo", "arcane"); ?>">
                            <div id="progress-wrp"><div class="progress-bar"></div ><div class="status">0%</div></div>
                            <div id="output"><!-- error or success results --></div>
                            <div  class="upload-btn"><i class="fa fa-upload"></i> <?php esc_html_e('Browse','arcane'); ?></div>
                            <div class="fake-input"><?php esc_html_e("No file selected", "arcane"); ?></div>
                        </label>
                    </div>

                    <div class="regrest-upload-wrapper bannerPhotoWrap">
                        <span>  <?php esc_html_e("Banner photo", "arcane"); ?></span>
                        <div class="bannerPhoto uploaded-files">
			                <?php
			                $bannerPhoto = get_user_meta( $a_id, 'profile_bg', true );

			                if(!empty($bannerPhoto)){ ?>
                                <img style="display: block !important;" class="bannerPhoto" alt="logo" src="<?php echo esc_url($bannerPhoto); ?>" />
			                <?php }else{ ?>
                                <img class="bannerPhoto" alt="banner" src="" />
			                <?php } ?>
                        </div>
                        <label class="fake-upload-btn">
                            <input type="file" class="ws-file-upload input-text"  accept="image/*" name="bannerPhoto" id="bannerPhoto" placeholder="<?php esc_html_e("Banner photo", "arcane"); ?>">
                            <div id="progress-wrp"><div class="progress-bar"></div ><div class="status">0%</div></div>
                            <div id="output"><!-- error or success results --></div>
                            <div  class="upload-btn"><i class="fa fa-upload"></i> <?php esc_html_e('Browse','arcane'); ?></div>
                            <div class="fake-input"><?php esc_html_e("No file selected", "arcane"); ?></div>
                        </label>
                    </div>

                    <fieldset class="form-email">
                        <p><label for="email"><?php esc_html_e( 'E-mail *', 'arcane' ); ?></label>
                            <span class="cust_input">
                            <input class="text-input" name="email" type="text" id="email"
                                   value="<?php the_author_meta( 'user_email', $a_id ); ?>"/>
                            </span>
                        </p></fieldset><!-- .form-email -->
                    <fieldset class="form-url">
                        <p><label for="user_url"><?php esc_html_e( 'Website', 'arcane' ); ?></label>
                            <span class="cust_input">
                            <input class="text-input" name="user_url" type="text" id="user_url"
                                   value="<?php the_author_meta( 'user_url', $a_id ); ?>"/>
                            </span>
                        </p></fieldset><!-- .form-url -->
                    <fieldset class="form-password">
                        <p><label for="passs1"><?php esc_html_e( 'Password *', 'arcane' ); ?> </label>
                            <span class="cust_input">
                            <input class="text-input" name="passs1" type="password" id="passs1"/>
                            </span>
                        </p></fieldset><!-- .form-password -->
                    <fieldset class="form-password">
                        <p><label for="passs2"><?php esc_html_e( 'Repeat Password *', 'arcane' ); ?></label>
                            <span class="cust_input">
                            <input class="text-input" name="passs2" type="password" id="passs2"/>
                            </span>
                        </p></fieldset><!-- .form-password -->
                    <fieldset class="form-textarea aboutmew">
                        <p><label for="description"><?php esc_html_e( 'About me', 'arcane' ) ?></label>


                            <?php

                            $wp_editor_settings = array(
	                            'textarea_name' => 'aboutMe',
	                            'media_buttons' => true,
	                            'editor_class' => 'widefat',
	                            'textarea_rows' => 10,
	                            'teeny' => true
                            );

                            add_filter( 'user_can_richedit', '__return_true' ); // anyone can add media
                            add_filter( 'is_user_logged_in', 'the_returner' );

                            function the_returner() {
	                            return true;
                            }

                            wp_editor( $aboutMe, "aboutMe", $wp_editor_settings ); ?>
                        </p>
                        </fieldset><!-- .form-textarea -->
                    <fieldset>


                        <p>

                            <?php
                            $id             = $a_id;
                            $usercountry_id = get_user_meta( $id, 'usercountry_id', true ); ?>
                         <label for="usercountry_id"><?php esc_html_e( 'Country', 'arcane' ); ?></label>
							<?php
							$countries = arcane_registration_country_list();
							?>
                            <span class="cust_input">
                            <select name="usercountry_id">
                                <option value="0"><?php esc_html_e( '- Select -', 'arcane' ) ?></option>
								<?php
								foreach ( $countries as $country ) {
									$selected = "";
									if ( $usercountry_id == $country->id_country ) {
										$selected = "selected";
									}
									if ( $country->name == 'Afghanistan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Afghanistan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Albania' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Albania', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Algeria' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Algeria', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'American Samoa' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'American Samoa', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Andorra' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Andorra', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Angola' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Angola', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Anguilla' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Anguilla', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Antarctica' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Antarctica', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Antigua and Barbuda' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Antigua and Barbuda', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Argentina' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Argentina', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Armenia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Armenia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Aruba' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Aruba', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Australia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Australia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Austria' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Austria', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Azerbaijan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Azerbaijan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Bahamas' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Bahamas', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Bahrain' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Bahrain', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Bangladesh' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Bangladesh', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Barbados' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Barbados', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Belarus' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Belarus', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Belgium' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Belgium', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Belize' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Belize', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Benin' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Benin', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Bermuda' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Bermuda', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Bhutan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Bhutan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Bolivia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Bolivia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Bosnia and Herzegowina' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Bosnia and Herzegowina', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Botswana' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Botswana', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Bouvet Island' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Bouvet Island', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Brazil' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Brazil', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'British Indian Ocean Territory' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'British Indian Ocean Territory', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Brunei Darussalam' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Brunei Darussalam', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Bulgaria' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Bulgaria', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Burkina Faso' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Burkina Faso', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Burundi' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Burundi', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Cambodia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Cambodia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Cameroon' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Cameroon', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Canada' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Canada', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Cape Verde' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Cape Verde', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Cayman Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Cayman Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Central African Republic' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Central African Republic', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Chad' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Chad', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Chile' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Chile', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'China' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'China', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Christmas Island' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Christmas Island', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Cocos (Keeling) Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Cocos (Keeling) Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Colombia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Colombia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Comoros' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Comoros', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Congo' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Congo', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Cook Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Cook Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Costa Rica' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Costa Rica', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Cote D\'Ivoire' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Cote D\'Ivoire', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Croatia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Croatia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Cuba' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Cuba', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Cyprus' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Cyprus', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Czech Republic' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Czech Republic', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Denmark' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Denmark', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Djibouti' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Djibouti', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Dominica' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Dominica', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Dominican Republic' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Dominican Republic', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'East Timor' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'East Timor', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Ecuador' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Ecuador', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Egypt' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Egypt', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'El Salvador' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'El Salvador', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Equatorial Guinea' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Equatorial Guinea', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Eritrea' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Eritrea', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Estonia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Estonia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Ethiopia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Ethiopia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Falkland Islands (Malvinas)' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Falkland Islands (Malvinas)', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Faroe Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Faroe Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Fiji' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Fiji', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Finland' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Finland', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'France' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'France', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'France, Metropolitan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'France, Metropolitan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'French Guiana' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'French Guiana', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'French Polynesia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'French Polynesia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'French Southern Territories' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'French Southern Territories', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Gabon' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Gabon', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Gambia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Gambia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Georgia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Georgia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Germany' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Germany', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Ghana' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Ghana', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Gibraltar' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Gibraltar', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Greece' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Greece', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Greenland' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Greenland', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Grenada' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Grenada', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Guadeloupe' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Guadeloupe', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Guam' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Guam', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Guatemala' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Guatemala', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Guinea' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Guinea', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Guinea-bissau' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Guinea-bissau', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Guyana' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Guyana', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Haiti' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Haiti', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Heard and Mc Donald Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Heard and Mc Donald Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Honduras' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Honduras', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Hong Kong' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Hong Kong', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Hungary' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Hungary', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Iceland' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Iceland', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'India' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'India', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Indonesia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Indonesia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Iran (Islamic Republic of)' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Iran (Islamic Republic of)', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Iraq' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Iraq', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Ireland' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Ireland', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Israel' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Israel', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Italy' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Italy', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Jamaica' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Jamaica', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Japan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Japan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Jordan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Jordan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Kazakhstan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Kazakhstan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Kenya' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Kenya', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Kiribati' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Kiribati', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Korea, Democratic People\'s Republic of' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Korea, Democratic People\'s Republic of', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Korea, Republic of' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Korea, Republic of', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Kuwait' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Kuwait', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Kyrgyzstan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Kyrgyzstan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Lao People\'s Democratic Republic' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Lao People\'s Democratic Republic', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Latvia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Latvia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Lebanon' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Lebanon', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Lesotho' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Lesotho', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Liberia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Liberia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Libyan Arab Jamahiriya' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Libyan Arab Jamahiriya', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Liechtenstein' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Liechtenstein', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Lithuania' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Lithuania', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Luxembourg' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Luxembourg', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Macau' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Macau', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Macedonia, The Former Yugoslav Republic of' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Macedonia, The Former Yugoslav Republic of', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Madagascar' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Madagascar', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Malawi' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Malawi', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Malaysia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Malaysia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Maldives' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Maldives', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Mali' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Mali', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Malta' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Malta', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Marshall Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Marshall Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Martinique' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Martinique', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Mauritania' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Mauritania', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Mauritius' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Mauritius', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Mayotte' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Mayotte', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Mexico' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Mexico', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Micronesia, Federated States of' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Micronesia, Federated States of', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Moldova, Republic of' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Moldova, Republic of', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Monaco' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Monaco', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Mongolia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Mongolia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Montserrat' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Montserrat', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Morocco' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Morocco', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Mozambique' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Mozambique', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Myanmar' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Myanmar', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Namibia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Namibia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Nauru' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Nauru', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Nepal' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Nepal', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Netherlands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Netherlands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Netherlands Antilles' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Netherlands Antilles', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'New Caledonia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'New Caledonia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'New Zealand' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'New Zealand', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Nicaragua' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Nicaragua', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Niger' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Niger', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Nigeria' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Nigeria', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Niue' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Niue', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Norfolk Island' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Norfolk Island', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Northern Mariana Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Northern Mariana Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Norway' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Norway', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Oman' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Oman', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Pakistan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Pakistan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Palau' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Palau', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Panama' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Panama', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Papua New Guinea' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Papua New Guinea', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Paraguay' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Paraguay', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Peru' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Peru', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Philippines' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Philippines', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Pitcairn' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Pitcairn', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Poland' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Poland', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Portugal' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Portugal', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Puerto Rico' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Puerto Rico', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Qatar' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Qatar', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Reunion' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Reunion', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Romania' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Romania', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Russian Federation' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Russian Federation', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Rwanda' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Rwanda', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Saint Kitts and Nevis' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Saint Kitts and Nevis', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Saint Lucia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Saint Lucia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Saint Vincent and the Grenadines' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Saint Vincent and the Grenadines', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Samoa' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Samoa', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'San Marino' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'San Marino', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Sao Tome and Principe' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Sao Tome and Principe', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Saudi Arabia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Saudi Arabia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Senegal' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Senegal', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Serbia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Serbia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Seychelles' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Seychelles', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Sierra Leone' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Sierra Leone', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Singapore' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Singapore', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Slovakia (Slovak Republic)' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Slovakia (Slovak Republic)', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Slovenia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Slovenia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Solomon Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Solomon Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Somalia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Somalia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'South Africa' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'South Africa', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'South Georgia and the South Sandwich Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'South Georgia and the South Sandwich Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Spain' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Spain', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Sri Lanka' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Sri Lanka', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'St. Helena' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'St. Helena', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'St. Pierre and Miquelon' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'St. Pierre and Miquelon', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Sudan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Sudan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Suriname' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Suriname', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Svalbard and Jan Mayen Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Svalbard and Jan Mayen Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Swaziland' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Swaziland', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Sweden' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Sweden', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Switzerland' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Switzerland', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Syrian Arab Republic' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Syrian Arab Republic', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Taiwan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Taiwan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Tajikistan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Tajikistan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Tanzania, United Republic of' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Tanzania, United Republic of', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Thailand' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Thailand', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Togo' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Togo', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Tokelau' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Tokelau', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Tonga' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Tonga', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Trinidad and Tobago' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Trinidad and Tobago', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Tunisia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Tunisia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Turkey' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Turkey', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Turkmenistan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Turkmenistan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Turks and Caicos Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Turks and Caicos Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Tuvalu' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Tuvalu', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Uganda' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Uganda', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Ukraine' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Ukraine', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'United Arab Emirates' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'United Arab Emirates', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'United Kingdom' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'United Kingdom', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'United States' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'United States', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'United States Minor Outlying Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'United States Minor Outlying Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Uruguay' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Uruguay', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Uzbekistan' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Uzbekistan', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Vanuatu' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Vanuatu', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Vatican City State (Holy See)' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Vatican City State (Holy See)', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Venezuela' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Venezuela', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Viet Nam' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Viet Nam', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Virgin Islands (British)' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Virgin Islands (British)', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Virgin Islands (U.S.)' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Virgin Islands (U.S.)', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Wallis and Futuna Islands' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Wallis and Futuna Islands', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Western Sahara' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Western Sahara', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Yemen' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Yemen', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Zaire' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Zaire', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Zambia' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Zambia', 'arcane' ) . '</option>';
									} elseif ( $country->name == 'Zimbabwe' ) {
										echo '<option ' . $selected . ' value=' . esc_attr( $country->id_country ) . '>' . esc_html__( 'Zimbabwe', 'arcane' ) . '</option>';

									}
								}
								?>
                            </select>
                            </span>
                        </p>
                    </fieldset>
                    <fieldset class="form-city">
                        <p><label for="city"><?php esc_html_e( 'City', 'arcane' ); ?></label>
                            <span class="cust_input">
                            <input class="text-input" name="city" type="text" id="city"
                                   value="<?php the_author_meta( 'city', $a_id ); ?>"/>
                            </span>
                        </p></fieldset>
					<?php
					//tusi
					global $wpdb;
					$custom_profile_fields = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_fields' );
					$required_marker       = " *";
					if ( is_array( $custom_profile_fields ) ) {
						foreach ( $custom_profile_fields as $field ) {
							if ( $field->type == "textbox" ) {
								if ( $field->is_required == 1 ) {
									$additional_text = $required_marker;
								} else {
									$additional_text = "";
								}
								$query = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_data WHERE user_id=%s AND field_id=%s LIMIT 1', $a_id, $field->id ) );

								?>
                                <fieldset class="form-textbox">
                                    <p>
                                        <label for="custom_fields[<?php echo esc_attr( $field->id ); ?>]"><?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $additional_text ); ?></label>
                                        <span class="cust_input">
											<input type="text"
                                                   name="custom_fields[<?php echo esc_attr( $field->id ); ?>]"
                                                   id="custom_fields[<?php echo esc_attr( $field->id ); ?>]"
                                                   class="text-input" size="20"
                                                   value="<?php if(isset( $query->value)) echo esc_attr( $query->value ); ?>"/>
										 	</span>
                                    </p></fieldset>
								<?php
								unset( $query );
							} elseif ( $field->type == "checkbox" ) {
								if ( $field->is_required == 1 ) {
									$additional_text = $required_marker;
								} else {
									$additional_text = "";
								}
								$query = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_data WHERE user_id=%s AND field_id=%s LIMIT 1', $a_id, $field->id ) );
								?>
                                <fieldset class="form-checkbox">
                                    <p>
                                        <label for="custom_fields[<?php echo esc_attr( $field->id ); ?>]"><?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $additional_text ); ?></label>
                                        <span class="cust_input">
											<?php
											foreach ( $custom_profile_fields as $tempfield ) {
												if ( $tempfield->parent_id == $field->id ) {
													echo '<input type ="checkbox" name="custom_fields[' . $field->id . '][' . $tempfield->id . ']"';
													if ( is_array( unserialize( $query->value ) ) && ( in_array( $tempfield->name, unserialize( $query->value ) ) ) ) {
														echo ' checked="yes"';
													}
													echo '>' . $tempfield->name . "<br />";
												}
											}

											?>
											</span>
                                    </p></fieldset>

								<?php
								unset( $query );
							} elseif ( $field->type == "selectbox" ) {
								if ( $field->is_required == 1 ) {
									$additional_text = $required_marker;
								} else {
									$additional_text = "";
								}
								$query = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_data WHERE user_id=%s AND field_id=%s LIMIT 1', $a_id, $field->id ) );

								?>
                                <fieldset class="form-selectbox">
                                    <p>
                                        <label for="custom_fields[<?php echo esc_attr( $field->id ); ?>]"><?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $additional_text ); ?></label>
                                        <span class="cust_input">
											<select name="<?php echo 'custom_fields[' . $field->id . ']'; ?>">
											<?php
											foreach ( $custom_profile_fields as $tempfield ) {
												if ( $tempfield->parent_id == $field->id ) {
													//selected
													$selected = "";
													if ( $tempfield->name == $query->value ) {
														$selected = 'selected';
													}
													echo '<option ' . $selected . ' value=' . $tempfield->id . '>' . $tempfield->name . '</option>';

												}
											}

											?>
											</select>
											</span>
                                    </p></fieldset>

								<?php
								unset( $query );
							} elseif ( $field->type == "radio" ) {
								if ( $field->is_required == 1 ) {
									$additional_text = $required_marker;
								} else {
									$additional_text = "";
								}
								$query = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_data WHERE user_id=%s AND field_id=%s LIMIT 1', $a_id, $field->id ) );

								?>
                                <fieldset class="form-radio">
                                    <p>
                                        <label for="custom_fields[<?php echo esc_attr( $field->id ); ?>]"><?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $additional_text ); ?></label>
                                        <span class="cust_input">
											<?php
											foreach ( $custom_profile_fields as $tempfield ) {
												if ( $tempfield->parent_id == $field->id ) {
													//selected
													$selected = "";
													if ( $tempfield->name == $query->value ) {
														$selected = 'checked="yes"';
													}
													echo '<input type="radio"  value=' . esc_attr( $tempfield->id ) . ' name="custom_fields[' . esc_attr( $field->id ) . ']" ' . esc_attr( $selected ) . '>' . esc_attr( $tempfield->name ) . '<br />';

												}
											}

											?>
											</span>
                                    </p></fieldset>

								<?php
								unset( $query );
							} elseif ( $field->type == "telephone" ) {
								if ( $field->is_required == 1 ) {
									$additional_text = $required_marker;
								} else {
									$additional_text = "";
								}
								$query = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_data WHERE user_id=%s AND field_id=%s LIMIT 1', $a_id, $field->id ) );

								?>
                                <fieldset class="form-number">
                                    <p>
                                        <label for="custom_fields[<?php echo esc_attr( $field->id ); ?>]"><?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $additional_text ); ?></label>
                                        <span class="cust_input">
											            <input type="text"
                                                               name="custom_fields[<?php echo esc_attr( $field->id ); ?>]"
                                                               id="custom_fields[<?php echo esc_attr( $field->id ); ?>]"
                                                               class="text-input limit_to_numbers" size="20"
                                                               value="<?php echo esc_attr( $query->value ); ?>"/>
										 	        </span>
                                    </p>
                                </fieldset>
								<?php
								unset( $query );
							} elseif ( $field->type == "number" ) {
								if ( $field->is_required == 1 ) {
									$additional_text = $required_marker;
								} else {
									$additional_text = "";
								}
								$query = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_data WHERE user_id=%s AND field_id=%s LIMIT 1', $a_id, $field->id ) );

								?>
                                <fieldset class="form-number">
                                    <p>
                                        <label for="custom_fields[<?php echo esc_attr( $field->id ); ?>]"><?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $additional_text ); ?></label>
                                        <span class="cust_input">
											<input type="text"
                                                   name="custom_fields[<?php echo esc_attr( $field->id ); ?>]"
                                                   id="custom_fields[<?php echo esc_attr( $field->id ); ?>]"
                                                   class="text-input limit_to_numbers" size="20"
                                                   value="<?php echo esc_attr( $query->value ); ?>"/>
										 	</span>
                                    </p></fieldset>
								<?php
								unset( $query );
							} elseif ( $field->type == "url" ) {
								if ( $field->is_required == 1 ) {
									$additional_text = $required_marker;
								} else {
									$additional_text = "";
								}
								$query = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_data WHERE user_id=%s AND field_id=%s LIMIT 1', $a_id, $field->id ) );

								?>
                                <fieldset class="form-url">
                                    <p>
                                        <label for="custom_fields[<?php echo esc_attr( $field->id ); ?>]"><?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $additional_text ); ?></label>
                                        <span class="cust_input">
											<input type="text"
                                                   name="custom_fields[<?php echo esc_attr( $field->id ); ?>]"
                                                   id="custom_fields[<?php echo esc_attr( $field->id ); ?>]"
                                                   class="text-input limit_to_url" size="20"
                                                   value="<?php echo esc_attr( $query->value ); ?>"/>
										 	</span>
                                    </p></fieldset>
								<?php
								unset( $query );
							} elseif ( $field->type == "textarea" ) {
								if ( $field->is_required == 1 ) {
									$additional_text = $required_marker;
								} else {
									$additional_text = "";
								}
								$query = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_data WHERE user_id=%s AND field_id=%s LIMIT 1', $a_id, $field->id ) );

								?>
                                <fieldset class="form-textarea">
                                    <p>
                                        <label for="custom_fields[<?php echo esc_attr( $field->id ); ?>]"><?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $additional_text ); ?></label>
                                        <span class="cust_input">
											<textarea name="custom_fields[<?php echo esc_attr( $field->id ); ?>]"
                                                      id="custom_fields[<?php echo esc_attr( $field->id ); ?>]"
                                                      class="input textarea"
                                                      size="20"/><?php echo esc_attr( $query->value ); ?></textarea>
                                            <br/>
										 	</span>
                                    </p></fieldset>
								<?php
								unset( $query );
							} elseif ( $field->type == "multiselectbox" ) {
								if ( $field->is_required == 1 ) {
									$additional_text = $required_marker;
								} else {
									$additional_text = "";
								}
								$query = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_data WHERE user_id=%s AND field_id=%s LIMIT 1', $a_id, $field->id ) );;
								?>
                                <fieldset class="form-multiselect">
                                    <p>
                                        <label for="custom_fields[<?php echo esc_attr( $field->id ); ?>]"><?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $additional_text ); ?></label>
                                        <span class="cust_input">
											<?php
											foreach ( $custom_profile_fields as $tempfield ) {
												if ( $tempfield->parent_id == $field->id ) {
													echo '<input type ="checkbox" name="custom_fields[' . $field->id . '][' . $tempfield->id . ']"';
													if ( is_array( unserialize( $query->value ) ) && ( in_array( $tempfield->name, unserialize( $query->value ) ) ) ) {
														echo ' checked="yes"';
													}
													echo '>' . esc_attr( $tempfield->name ) . "<br />";
												}
											}

											?>
											</span>
                                    </p></fieldset>

								<?php
								unset( $query );
							}
						}
					}

					?>


                    <fieldset><p>
                        <p class="form-submit">
                            <input name="updateuser" type="submit" id="updateuser"
                                   class="submit button button-green button-small"
                                   value="<?php esc_html_e( 'Update', 'arcane' ); ?>"/>
							<?php wp_nonce_field( 'update-user' ) ?>
                            <input name="action" type="hidden" id="action" value="update-user"/>
                        </p><!-- .form-submit -->
                        </p></fieldset>
                </form><!-- #adduser -->

		<?php endif; ?>
    </div><!-- .entry-content -->
</div><!-- .hentry .post -->