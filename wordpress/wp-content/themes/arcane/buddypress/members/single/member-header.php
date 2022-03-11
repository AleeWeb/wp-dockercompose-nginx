<?php
//ubacis ovde obradu
$author = get_queried_object();
$a_id   = bp_displayed_user_id();
$c_id   = get_current_user_id();

$currentlang  = apply_filters( "wpml_home_url", home_url( '/' ) );
$current_time = strtotime( gmdate( 'Y-m-d H:i:s', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) ) );
global $arcane_error;
$arcane_error = array();
//
if ( ( current_user_can( 'manage_options' ) ) ) {
	$is_admin = true;
}

if ( $a_id == $c_id ) {

	global $current_user, $wp_roles;
	if ( is_user_logged_in() != 1 ) {
		wp_redirect( esc_url( $currentlang ), $status = 302 );
	}
	$c_id = get_current_user_id();

	if ( ! isset( $_POST['profilePhoto'] ) ) {
		$_POST['profilePhoto'] = '';
	}
	$profilePhoto = $_POST['profilePhoto'];

	if ( ! isset( $_POST['bannerPhoto'] ) ) {
		$_POST['bannerPhoto'] = '';
	}
	$profileBanner = $_POST['bannerPhoto'];

	$post_id               = $post->ID;

	$about = get_user_meta( $a_id, 'description', true );
	wp_get_current_user();

	global $wpdb;
	$custom_profile_fields = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bp_xprofile_fields' );

	/* If profile was saved, update profile. */
	if ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {
		$posted_customs   = $_POST['custom_fields'];
		$additional_error = false;
		$counter          = 0;
		if ( isset( $_POST['custom_fields'] ) ) {
			foreach ( $custom_profile_fields as $thefield ) {
				if ( $thefield->is_required == 1 ) {
					$therequiredfields[ $counter ] = $thefield->id;
					$counter ++;
				}
			}
		}

		$customs_error = array();
		if ( is_array( $therequiredfields ) ) {
			foreach ( $therequiredfields as $findfield ) {
				if ( ! isset( $posted_customs[ $findfield ] ) ) {
					$additional_error = true;
					$customs_error[]  = esc_html__( "Please fill out all required fields. ", 'arcane' );
				} else {
					if ( ( ! ( is_array( $posted_customs[ $findfield ] ) ) ) and ( ! ( is_string( $posted_customs[ $findfield ] ) ) ) ) {
						$additional_error = true;
						$customs_error[]  = esc_html__( "Please fill out all required fields. ", 'arcane' );
					} elseif ( is_array( $posted_customs[ $findfield ] ) ) {
						if ( ! ( count( $posted_customs[ $findfield ] ) > 0 ) ) {
							$additional_error = true;
							$customs_error[]  = esc_html__( "Please fill out all required fields. ", 'arcane' );
						}
					} elseif ( is_string( $posted_customs[ $findfield ] ) ) {
						if ( ! ( strlen( $posted_customs[ $findfield ] ) > 0 ) ) {
							$additional_error = true;
							$customs_error[]  = esc_html__( "Please fill out all required fields. ", 'arcane' );
						}
					}
				}
			}
		}
		/* Update user password. */
		if ( ! empty( $_POST['passs1'] ) && ! empty( $_POST['passs2'] ) ) {
			if ( $_POST['passs1'] == $_POST['passs2'] ) {
				wp_update_user( array( 'ID' => $a_id, 'user_pass' => esc_attr( $_POST['passs1'] ) ) );
			} else {
				$arcane_error[] = esc_html__( 'The passwords you entered do not match.  Your password was not updated.', 'arcane' );
			}
		}
		/* Update user information. */
		//website

		wp_update_user( array( 'ID' => $a_id, 'user_url' => esc_url( $_POST['user_url'] ) ) );
		if ( ! empty( $_POST['email'] ) ) {

			if ( ! is_email( esc_attr( $_POST['email'] ) ) ) {
				$arcane_error[] = esc_html__( 'The Email you entered is not valid.  please try again.', 'arcane' );
			} elseif ( trim( email_exists( esc_attr( $_POST['email'] ) ) ) != "" && email_exists( esc_attr( $_POST['email'] ) ) != $a_id ) {
				$arcane_error[] = esc_html__( 'This email is already used by another user.  try a different one.', 'arcane' );
			} else {
				wp_update_user( array( 'ID' => $a_id, 'user_email' => esc_attr( $_POST['email'] ) ) );
			}
		}
		//here we add photo, if the photo was set
		if ( $profilePhoto != '' ) {
			update_user_meta( $a_id, 'profile_photo', $profilePhoto );
		}

		if ( $profileBanner != '' ) {
			update_user_meta( $a_id, 'profile_bg', $profileBanner );
		}


		update_user_meta( $a_id, 'usercountry_id', esc_attr( $_POST['usercountry_id'] ) );
		if ( ! empty( $_POST['first-name'] ) ) {
			update_user_meta( $a_id, 'first_name', esc_attr( $_POST['first-name'] ) );
		}
		if ( ! empty( $_POST['last-name'] ) ) {
			update_user_meta( $a_id, 'last_name', esc_attr( $_POST['last-name'] ) );
		}
		if ( ! empty( $_POST['nickname'] ) ) {
			update_user_meta( $a_id, 'nickname', esc_attr( $_POST['nickname'] ) );
		}
		if ( ! empty( $_POST['user_display_name'] ) ) {
			wp_update_user( array( 'ID' => $a_id, 'display_name' => esc_attr( $_POST['user_display_name'] ) ) );
		}
		if ( ! empty( $_POST['city'] ) ) {
			update_user_meta( $a_id, 'city', esc_attr( $_POST['city'] ) );
		}
		if ( ! empty( $_POST['alt-birthday-date'] ) ) {
			update_user_meta( $a_id, 'age', esc_attr( $_POST['alt-birthday-date'] ) );
		}

		if ( ! empty( $_POST['aboutMe'] ) ) {
			update_user_meta( $a_id, 'description', $_POST['aboutMe'] );
		}

		$counter = 0;
		if ( isset( $posted_customs ) and ( is_array( $posted_customs ) ) and ( count( $customs_error ) == 0 ) ) {
			foreach ( $posted_customs as $akey => $acustom ) {
				//$custom_profile_fields = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'bp_xprofile_fields');
				$holdfield = '';
				foreach ( $custom_profile_fields as $thefield ) {
					if ( $thefield->id == $akey ) {
						$holdfield = $thefield;
					}
				}
				if ( ( $holdfield->type == "textbox" ) or ( $holdfield->type == "number" ) or ( $holdfield->type == "telephone" ) or ( $holdfield->type == "url" ) or ( $holdfield->type == "textarea" ) ) {
					$preppedvalue = $acustom;
				} elseif ( ( $holdfield->type == "checkbox" ) ) {
					$counter = 0;
					unset( $preppedvalue );
					foreach ( $acustom as $tehkey1 => $holdthecustom1 ) {
						foreach ( $custom_profile_fields as $thefield1 ) {
							if ( $thefield1->id == $tehkey1 ) {
								$preppedvalue[ $counter ] = $thefield1->name;
								$counter ++;
							}
						}
					}
				} elseif ( ( $holdfield->type == "multiselectbox" ) ) {
					$counter = 0;
					unset( $preppedvalue );

					foreach ( $acustom as $tehkey => $holdthecustom ) {
						foreach ( $custom_profile_fields as $thefield ) {
							if ( $thefield->id == $tehkey ) {
								$preppedvalue[ $counter ] = $thefield->name;
								$counter ++;
							}
						}
					}
				} elseif ( ( $holdfield->type == "radio" ) ) {
					//($holdfield->type == "selectbox") OR
					foreach ( $custom_profile_fields as $thefield ) {
						if ( $thefield->id == $acustom ) {
							$preppedvalue = $thefield->name;
						}
					}

				} elseif ( ( $holdfield->type == "selectbox" ) ) {
					//($holdfield->type == "selectbox") OR


					foreach ( $custom_profile_fields as $thefield ) {
						if ( $thefield->id == $acustom ) {
							$preppedvalue = $thefield->name;
						}
					}
				}

				if ( is_array( $preppedvalue ) ) {
					$theholder = serialize( $preppedvalue );
				} else {
					$theholder = $preppedvalue;
				}


				$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "bp_xprofile_data WHERE field_id='%s' AND user_id='%s'", $akey, $a_id ) );
				$finishedids[ $akey ] = $akey;

				$wpdb->insert(
					$wpdb->prefix . "bp_xprofile_data",
					array(
						'field_id'     => $akey,
						'user_id'      => $a_id,
						'value'        => $theholder,
						'last_updated' => $current_time
					),
					array(
						'%d',
						'%d',
						'%s',
						'%s',
					)
				);


			}

			foreach ( $custom_profile_fields as $holdit ) {
				$allids[ $holdit->id ] = $holdit->id;
			}
			foreach ( $finishedids as $done ) {
				if ( in_array( $done, $allids ) ) {
					unset( $allids[ $done ] );
				}
			}
			foreach ( $allids as $remainder ) {
				$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "bp_xprofile_data WHERE field_id=%s AND user_id=%s", $remainder, $a_id ) );
			}
		} else {
			$arcane_error[] = esc_html__( "Please fill out all profile fields, custom profile fields were NOT updated", 'arcane' );
		}
	}
}
?>

<div class="profile">

    <div class="profile-info row">
        <div class="container">
			<?php if ( bp_is_active( 'friends' ) ) { ?>
                <div class="friendswrapper">
                    <div class="friends-count"><i class="fas fa-users"></i>
						<?php if ( friends_get_total_friend_count( $a_id ) == 0 ) {
							esc_html_e( '0 friends', 'arcane' );
						} elseif ( friends_get_total_friend_count( $a_id ) == 1 ) {
							esc_html_e( '1 friend', 'arcane' );
						} else {
							echo friends_get_total_friend_count( $a_id );
							esc_html_e( ' friends', 'arcane' );
						} ?></div>
					<?php bp_member_add_friend_button(); ?>

                    <?php if ( bp_is_active( 'messages' ) && ( $a_id != $c_id ) ) { ?>
                        <div class="messagewrapper">
                            <?php if ( is_user_logged_in() ) {
                                echo '<a href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . get_the_author_meta( 'user_login', $a_id ) ) . '" title="' . esc_html__( "Send a private message to this user", "arcane" ) . '" class="btn"><i class="far fa-envelope" aria-hidden="true"></i> ' . esc_html__( "send a message", "arcane" ) . '</a>';
                            } ?>
                        </div>
                    <?php } ?>

                </div>
			<?php } ?>




            <div class="avatar-card">
				<?php echo get_avatar( $a_id, 210 ); ?>

            </div>

            <div class="pmi_title">
                <h1>
					<?php
					if ( get_the_author_meta( 'display_name', $a_id ) ) {
						echo get_the_author_meta( 'display_name', $a_id );
					}
					?>
                </h1>
            </div>

			<?php echo get_the_author_meta( 'country', $a_id ); ?>

        </div>
        <div class="profile-fimage profile-media">

			<?php

			$bgpic = get_user_meta( $a_id, 'profile_bg', true );

			if ( ! empty( $bgpic ) ) {
				$imagebg = arcane_aq_resize( $bgpic, 1920, 280, true, true, true ); //resize & crop img
				if ( ! isset ( $imagebg[0] ) ) {
					$bgimage = $bgpic;
				} else {
					$bgimage = $imagebg;
				}
				?>
                <div class="hiddenoverflow"><img alt="img" src="<?php echo esc_url( $bgimage ); ?>"/></div>
			<?php } else { ?>
                <div class="hiddenoverflow"><img alt="img" class="attachment-small wp-post-image"
                                                 src="<?php echo get_theme_file_uri( 'img/defaults/default-banner.jpg' ); ?> "/>
                </div>
			<?php } ?>


        </div>

        <div class="col-lg-12 col-md-12 nav-top-divider"></div>

    </div>
</div>