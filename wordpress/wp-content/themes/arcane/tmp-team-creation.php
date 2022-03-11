<?php
/*
 * Template Name: Manage team
 */
?>
<?php
if ( ! is_user_logged_in()) {
	wp_redirect( home_url( '/' ), $status = 302 );
}

$team_id = false;
if(isset($_GET['p_id'])){
	$team_id = $_GET['p_id'];
	$c_id = get_current_user_id();

	if(!arcane_is_admin( $team_id, $c_id )){
		wp_redirect( home_url( '/' ), $status = 302 );
		exit();
    }

}

?>
<?php get_header(); ?>
    <div class="container">
        <div class="team-creation-wrap col-9 ">
				<div class=" register-form-wrapper wcontainer">

						<h3><i class="fas fa-users" aria-hidden="true"></i>
							<?php esc_html_e('Team Information', 'arcane');	?>
						</h3>


					<?php
					if($team_id){ ?>
					<form data-pid="<?php echo esc_attr($team_id); ?>" id="updateTeam" action="" method="POST" enctype="multipart/form-data">
						<?php }else{ ?>
						<form id="createTeam" action="" method="POST" enctype="multipart/form-data">
							<?php } ?>



							<div class="register-candidate__input-wrapper">

								<div class="register-candidate__input-cell">
									<p class="form-row form-row-wide">
										<label for="teamName">
											<span> <?php esc_html_e("Team name", "arcane"); ?></span>
											<?php
											$value = '';
											if($team_id)
												$value = get_the_title($team_id);
											?>
											<input type="text" class="input-text" name="teamName" id="teamName"  value="<?php echo esc_attr($value); ?>" required>
											<span style="display: none;" class="error-name"><?php esc_html_e("Team name is taken! Please select a unique team name!", "arcane"); ?></span>
										</label>
									</p>
								</div>

								<div class="form register-candidate__about">
									<div class="select">
										<label for="games"><span> <?php esc_html_e("Team games", "arcane"); ?></span></label>
                                        <ul class="tc-games-team">

										<?php
										global $ArcaneWpTeamWars;
										$games = $ArcaneWpTeamWars->get_game('id=all&orderby=title&order=asc');

										$gmeta = [];
										if($team_id)
											$gmeta = get_post_meta($team_id, 'games', true);

										foreach ($games as $key => $game){
											$active = '';

											if(is_array($gmeta) && in_array($game->id,$gmeta ))
												$active = 'active';

											echo ' <li data-id="'.esc_attr($game->id).'" class="'.esc_attr($active).'  game-team game_'.esc_attr($game->id).'">  <span> ' .esc_html(esc_attr($game->title)). '</span><input type="checkbox" name="games[]" value="'.esc_attr($game->id).'"> </li>';
										}
										?>
                                        </ul>

									</div>
								</div>

								<div class="register-candidate__input-cell">
									<p class="form-row form-row-wide">
										<label for="about">
											<span> <?php esc_html_e("About", "arcane"); ?></span>
											<?php

											$content = '';
											if($team_id) {
												$post_object = get_post( $team_id );
												$content = $post_object->post_content;
											}

											$wp_editor_settings = array(
												'textarea_name' => 'about',
												'media_buttons' => true,
												'editor_class' => 'widefat',
												'textarea_rows' => 10,
												'teeny' => true
											);

											wp_editor($content, "about", $wp_editor_settings);
											?>

										</label>
									</p>
								</div>


								<div class="regrest-upload-wrapper teamLogoWrap">
									<span> <?php esc_html_e("Team logo", "arcane"); ?></span>
									<div class="teamLogo uploaded-files">

										<?php
										$teamLogo = '';
										if($team_id) {
											$teamLogo = get_post_meta( $team_id, 'team_photo', true );
										}

										if(!empty($teamLogo)){ ?>
											<img style="display: block !important;" class="teamLogo" alt="logo" src="<?php echo esc_url($teamLogo); ?>" />
										<?php }else{ ?>
											<img class="teamLogo" alt="logo" />
										<?php } ?>
									</div>
									<label class="fake-upload-btn">
										<input type="file" class="ws-file-upload input-text"  accept="image/*" name="teamLogo" id="teamLogo" placeholder="<?php esc_html_e("Team logo", "arcane"); ?>">
										<div id="progress-wrp"><div class="progress-bar"></div ><div class="status">0%</div></div>
										<div id="output"><!-- error or success results --></div>
										<div  class="upload-btn"><i class="fa fa-upload"></i> <?php esc_html_e('Browse','arcane'); ?></div>
										<div class="fake-input"><?php esc_html_e("No file selected", "arcane"); ?></div>
									</label>
								</div>


                                <div class="regrest-upload-wrapper teamBannerWrap">
                                    <span>  <?php esc_html_e("Team banner", "arcane"); ?></span>
                                    <div class="teamBanner uploaded-files">
										<?php
										$teamBanner = '';
										if($team_id) {
											$teamBanner = get_post_meta( $team_id, 'team_bg', true );
										}

										if(!empty($teamBanner)){ ?>
                                            <img style="display: block !important;" class="teamBanner" alt="logo" src="<?php echo esc_url($teamBanner); ?>" />
										<?php }else{ ?>
                                            <img class="teamBanner" alt="banner" src="" />
										<?php } ?>
                                    </div>
                                    <label class="fake-upload-btn">
                                        <input type="file" class="ws-file-upload input-text"  accept="image/*" name="teamBanner" id="teamBanner" placeholder="<?php esc_html_e("Team banner", "arcane"); ?>">
                                        <div id="progress-wrp"><div class="progress-bar"></div ><div class="status">0%</div></div>
                                        <div id="output"><!-- error or success results --></div>
                                        <div  class="upload-btn"><i class="fa fa-upload"></i> <?php esc_html_e('Browse','arcane'); ?></div>
                                        <div class="fake-input"><?php esc_html_e("No file selected", "arcane"); ?></div>
                                    </label>
                                </div>


								<div class="register-candidate__input-cell reg-team-platform">
									<p class="form-row form-row-wide">

                                        <?php
                                        $teamPlatforms = [];
                                        if($team_id) {
	                                        $teamPlatforms = get_post_meta( $team_id, 'platforms', true );
                                        }
                                        ?>
                                        <span>  <?php esc_html_e("Platforms", "arcane"); ?></span>

                                        <div>
                                            <input <?php if(in_array('ps',$teamPlatforms) )echo 'checked'; ?> type="checkbox" class="input-text" name="teamPlatforms[]" id="ps"  value="ps">
                                            <label for="ps"><?php esc_html_e( 'PS4', 'arcane' ); ?></label>
                                        </div>
                                    <div>
                                        <input <?php if(in_array('ps5',$teamPlatforms) )echo 'checked'; ?> type="checkbox" class="input-text" name="teamPlatforms[]" id="ps5"  value="ps5">
                                        <label for="ps5"><?php esc_html_e( 'PS5', 'arcane' ); ?></label>
                                    </div>
                                    <div>
                                            <input <?php if(in_array('pc',$teamPlatforms) )echo 'checked'; ?> type="checkbox" class="input-text" name="teamPlatforms[]" id="pc"  value="pc" >
                                            <label for="pc"><?php esc_html_e( 'PC', 'arcane' ); ?></label>
                                        </div> <div>
                                            <input <?php if(in_array('xbox',$teamPlatforms) )echo 'checked'; ?> type="checkbox" class="input-text" name="teamPlatforms[]" id="xbox"  value="xbox" >
                                            <label for="xbox"><?php esc_html_e( 'Xbox', 'arcane' ); ?></label>
                                        </div> <div>
                                            <input <?php if(in_array('wii',$teamPlatforms) )echo 'checked'; ?> type="checkbox" class="input-text" name="teamPlatforms[]" id="wii"  value="wii" >
                                            <label for="wii"><?php esc_html_e( 'Wii', 'arcane' ); ?></label>
                                        </div> <div>
                                            <input <?php if(in_array('nin',$teamPlatforms) )echo 'checked'; ?> type="checkbox" class="input-text" name="teamPlatforms[]" id="nin"  value="nin" >
                                            <label for="nin"><?php esc_html_e( 'Nintendo', 'arcane' ); ?></label>
                                        </div> <div>
                                            <input <?php if(in_array('mobile',$teamPlatforms) )echo 'checked'; ?> type="checkbox" class="input-text" name="teamPlatforms[]" id="mobile"  value="mobile" >
                                            <label for="mobile"><?php esc_html_e( 'Mobile', 'arcane' ); ?></label>
                                        </div> <div>
                                            <input <?php if(in_array('cross',$teamPlatforms) )echo 'checked'; ?> type="checkbox" class="input-text" name="teamPlatforms[]" id="cross"  value="cross" >
                                            <label for="cross"><?php esc_html_e( 'Cross platform', 'arcane' ); ?></label>
                                        </div>
                                        </p>
								</div>

								<div class="register-candidate__input-cell">
									<p class="form-row form-row-wide">
										<label for="teamLocation">
											<span>  <?php esc_html_e("Team location", "arcane"); ?></span>
											<?php
											$country = '';
											if($team_id) {
												$country = get_post_meta( $team_id, 'location', true );
											}
											?>
											<select name="teamLocation">
												<option <?php if ( $country == 'Afghanistan' ) {
													echo 'selected';
												} ?> class="Afghanistan" value="Afghanistan">Afghanistan</option>
												<option <?php if ( $country == 'Albania' ) {
													echo 'selected';
												} ?> class="Albania" value="Albania">Albania</option>
												<option <?php if ( $country == 'Algeria' ) {
													echo 'selected';
												} ?> class="Algeria" value="Algeria">Algeria</option>
												<option <?php if ( $country == 'Andorra' ) {
													echo 'selected';
												} ?> class="Andorra" value="Andorra">Andorra</option>
												<option <?php if ( $country == 'Angola' ) {
													echo 'selected';
												} ?> class="Angola" value="Angola">Angola</option>
												<option <?php if ( $country == 'Antigua and Barbuda' ) {
													echo 'selected';
												} ?> class="Antigua and Barbuda" value="Antigua and Barbuda">Antigua and Barbuda</option>
												<option <?php if ( $country == 'Argentina' ) {
													echo 'selected';
												} ?> class="Argentina" value="Argentina">Argentina</option>
												<option <?php if ( $country == 'Armenia' ) {
													echo 'selected';
												} ?> class="Armenia" value="Armenia">Armenia</option>
												<option <?php if ( $country == 'Aruba' ) {
													echo 'selected';
												} ?> class="Aruba" value="Aruba">Aruba</option>
												<option <?php if ( $country == 'Australia' ) {
													echo 'selected';
												} ?> class="Australia" value="Australia">Australia</option>
												<option <?php if ( $country == 'Austria' ) {
													echo 'selected';
												} ?> class="Austria" value="Austria">Austria</option>
												<option <?php if ( $country == 'Azerbaijan' ) {
													echo 'selected';
												} ?> class="Azerbaijan" value="Azerbaijan">Azerbaijan</option>
												<option <?php if ( $country == 'Bahamas, The' ) {
													echo 'selected';
												} ?> class="Bahamas, The" value="Bahamas, The">Bahamas, The</option>
												<option <?php if ( $country == 'Bahrain' ) {
													echo 'selected';
												} ?> class="Bahrain" value="Bahrain">Bahrain</option>
												<option <?php if ( $country == 'Bangladesh' ) {
													echo 'selected';
												} ?> class="Bangladesh" value="Bangladesh">Bangladesh</option>
												<option <?php if ( $country == 'Barbados' ) {
													echo 'selected';
												} ?> class="Barbados" value="Barbados">Barbados</option>
												<option <?php if ( $country == 'Belarus' ) {
													echo 'selected';
												} ?> class="Belarus" value="Belarus">Belarus</option>
												<option <?php if ( $country == 'Belgium' ) {
													echo 'selected';
												} ?> class="Belgium" value="Belgium">Belgium</option>
												<option <?php if ( $country == 'Belize' ) {
													echo 'selected';
												} ?> class="Belize" value="Belize">Belize</option>
												<option <?php if ( $country == 'Benin' ) {
													echo 'selected';
												} ?> class="Benin" value="Benin">Benin</option>
												<option <?php if ( $country == 'Bhutan' ) {
													echo 'selected';
												} ?> class="Bhutan" value="Bhutan">Bhutan</option>
												<option <?php if ( $country == 'Bolivia' ) {
													echo 'selected';
												} ?> class="Bolivia" value="Bolivia">Bolivia</option>
												<option <?php if ( $country == 'Bosnia and Herzegovin' ) {
													echo 'selected';
												} ?> class="Bosnia and Herzegovina" value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
												<option <?php if ( $country == 'Botswana' ) {
													echo 'selected';
												} ?> class="Botswana" value="Botswana">Botswana</option>
												<option <?php if ( $country == 'Brazil' ) {
													echo 'selected';
												} ?> class="Brazil" value="Brazil">Brazil</option>
												<option <?php if ( $country == 'Brunei' ) {
													echo 'selected';
												} ?> class="Brunei" value="Brunei">Brunei</option>
												<option <?php if ( $country == 'Bulgaria' ) {
													echo 'selected';
												} ?> class="Bulgaria" value="Bulgaria">Bulgaria</option>
												<option <?php if ( $country == 'Burkina Faso' ) {
													echo 'selected';
												} ?> class="Burkina Faso" value="Burkina Faso">Burkina Faso</option>
												<option <?php if ( $country == 'Burma' ) {
													echo 'selected';
												} ?> class="Burma" value="Burma">Burma</option>
												<option <?php if ( $country == 'Burundi' ) {
													echo 'selected';
												} ?> class="Burundi" value="Burundi">Burundi</option>
												<option <?php if ( $country == 'Cambodia' ) {
													echo 'selected';
												} ?> class="Cambodia" value="Cambodia">Cambodia</option>
												<option <?php if ( $country == 'Cameroon' ) {
													echo 'selected';
												} ?> class="Cameroon" value="Cameroon">Cameroon</option>
												<option <?php if ( $country == 'Canada' ) {
													echo 'selected';
												} ?> class="Canada" value="Canada">Canada</option>
												<option <?php if ( $country == 'Cape Verde' ) {
													echo 'selected';
												} ?> class="Cape Verde" value="Cape Verde">Cape Verde</option>
												<option <?php if ( $country == 'Central African Republic' ) {
													echo 'selected';
												} ?> class="Central African Republic"
												     value="Central African Republic">Central African Republic</option>
												<option <?php if ( $country == 'Chad' ) {
													echo 'selected';
												} ?> class="Chad" value="Chad">Chad</option>
												<option <?php if ( $country == 'Chile' ) {
													echo 'selected';
												} ?> class="Chile" value="Chile">Chile</option>
												<option <?php if ( $country == 'China' ) {
													echo 'selected';
												} ?> class="China" value="China">China</option>
												<option <?php if ( $country == 'Colombia' ) {
													echo 'selected';
												} ?> class="Colombia" value="Colombia">Colombia</option>
												<option <?php if ( $country == 'Comoros' ) {
													echo 'selected';
												} ?> class="Comoros" value="Comoros">Comoros</option>
												<option <?php if ( $country == 'Congo, Democratic Republic of the' ) {
													echo 'selected';
												} ?> class="Congo, Democratic Republic of the" value="Congo, Democratic Republic of the">Congo, Democratic Republic of the</option>
												<option <?php if ( $country == 'Congo, Republic of the' ) {
													echo 'selected';
												} ?> class="Congo, Republic of the" value="Congo, Republic of the">Congo, Republic of the</option>
												<option <?php if ( $country == 'Costa Rica' ) {
													echo 'selected';
												} ?> class="Costa Rica" value="Costa Rica">Costa Rica</option>
												<option <?php if ( $country == 'Cote d\'Ivoire' ) {
													echo 'selected';
												} ?> class="Cote d&amp;hash-039;Ivoire" value="Cote d'Ivoire">Cote d'Ivoire</option>
												<option <?php if ( $country == 'Croatia' ) {
													echo 'selected';
												} ?> class="Croatia" value="Croatia">Croatia</option>
												<option <?php if ( $country == 'Cuba' ) {
													echo 'selected';
												} ?> class="Cuba" value="Cuba">Cuba</option>
												<option <?php if ( $country == 'Curacao' ) {
													echo 'selected';
												} ?> class="Curacao" value="Curacao">Curacao</option>
												<option <?php if ( $country == 'Cyprus' ) {
													echo 'selected';
												} ?> class="Cyprus" value="Cyprus">Cyprus</option>
												<option <?php if ( $country == 'Czech Republic' ) {
													echo 'selected';
												} ?> class="Czech Republic" value="Czech Republic">Czech Republic</option>
												<option <?php if ( $country == 'Denmark' ) {
													echo 'selected';
												} ?> class="Denmark" value="Denmark">Denmark</option>
												<option <?php if ( $country == 'Djibouti' ) {
													echo 'selected';
												} ?> class="Djibouti" value="Djibouti">Djibouti</option>
												<option <?php if ( $country == 'Dominica' ) {
													echo 'selected';
												} ?> class="Dominica" value="Dominica">Dominica</option>
												<option <?php if ( $country == 'Dominican Republic' ) {
													echo 'selected';
												} ?> class="Dominican Republic" value="Dominican Republic">Dominican Republic</option>
												<option <?php if ( $country == 'Ecuador' ) {
													echo 'selected';
												} ?> class="Ecuador" value="Ecuador">Ecuador</option>
												<option <?php if ( $country == 'Egypt' ) {
													echo 'selected';
												} ?> class="Egypt" value="Egypt">Egypt</option>
												<option <?php if ( $country == 'El Salvador' ) {
													echo 'selected';
												} ?> class="El Salvador" value="El Salvador">El Salvador</option>
												<option <?php if ( $country == 'Equatorial Guinea' ) {
													echo 'selected';
												} ?> class="Equatorial Guinea" value="Equatorial Guinea">Equatorial Guinea</option>
												<option <?php if ( $country == 'Eritrea' ) {
													echo 'selected';
												} ?> class="Eritrea" value="Eritrea">Eritrea</option>
												<option <?php if ( $country == 'Estonia' ) {
													echo 'selected';
												} ?> class="Estonia" value="Estonia">Estonia</option>
												<option <?php if ( $country == 'Ethiopia' ) {
													echo 'selected';
												} ?> class="Ethiopia" value="Ethiopia">Ethiopia</option>
												<option <?php if ( $country == 'Fiji' ) {
													echo 'selected';
												} ?> class="Fiji" value="Fiji">Fiji</option>
												<option <?php if ( $country == 'Finland' ) {
													echo 'selected';
												} ?> class="Finland" value="Finland">Finland</option>
												<option <?php if ( $country == 'France' ) {
													echo 'selected';
												} ?> class="France" value="France">France</option>
												<option <?php if ( $country == 'Gabon' ) {
													echo 'selected';
												} ?> class="Gabon" value="Gabon">Gabon</option>
												<option <?php if ( $country == 'Gambia, The' ) {
													echo 'selected';
												} ?> class="Gambia, The" value="Gambia, The">Gambia, The</option>
												<option <?php if ( $country == 'Georgia' ) {
													echo 'selected';
												} ?> class="Georgia" value="Georgia">Georgia</option>
												<option <?php if ( $country == 'Germany' ) {
													echo 'selected';
												} ?> class="Germany" value="Germany">Germany</option>
												<option <?php if ( $country == 'Ghana' ) {
													echo 'selected';
												} ?> class="Ghana" value="Ghana">Ghana</option>
												<option <?php if ( $country == 'Greece' ) {
													echo 'selected';
												} ?> class="Greece" value="Greece">Greece</option>
												<option <?php if ( $country == 'Grenada' ) {
													echo 'selected';
												} ?> class="Grenada" value="Grenada">Grenada</option>
												<option <?php if ( $country == 'Guatemala' ) {
													echo 'selected';
												} ?> class="Guatemala" value="Guatemala">Guatemala</option>
												<option <?php if ( $country == 'Guinea' ) {
													echo 'selected';
												} ?> class="Guinea" value="Guinea">Guinea</option>
												<option <?php if ( $country == 'Guinea-Bissau' ) {
													echo 'selected';
												} ?> class="Guinea-Bissau" value="Guinea-Bissau">Guinea-Bissau</option>
												<option <?php if ( $country == 'Guyana' ) {
													echo 'selected';
												} ?> class="Guyana" value="Guyana">Guyana</option>
												<option <?php if ( $country == 'Haiti' ) {
													echo 'selected';
												} ?> class="Haiti" value="Haiti">Haiti</option>
												<option <?php if ( $country == 'Holy See' ) {
													echo 'selected';
												} ?> class="Holy See" value="Holy See">Holy See</option>
												<option <?php if ( $country == 'Honduras' ) {
													echo 'selected';
												} ?> class="Honduras" value="Honduras">Honduras</option>
												<option <?php if ( $country == 'Hong Kong' ) {
													echo 'selected';
												} ?> class="Hong Kong" value="Hong Kong">Hong Kong</option>
												<option <?php if ( $country == 'Hungary' ) {
													echo 'selected';
												} ?> class="Hungary" value="Hungary">Hungary</option>
												<option <?php if ( $country == 'Iceland' ) {
													echo 'selected';
												} ?> class="Iceland" value="Iceland">Iceland</option>
												<option <?php if ( $country == 'India' ) {
													echo 'selected';
												} ?> class="India" value="India">India</option>
												<option <?php if ( $country == 'Indonesia' ) {
													echo 'selected';
												} ?> class="Indonesia" value="Indonesia">Indonesia</option>
												<option <?php if ( $country == 'Iran' ) {
													echo 'selected';
												} ?> class="Iran" value="Iran">Iran</option>
												<option <?php if ( $country == 'Iraq' ) {
													echo 'selected';
												} ?> class="Iraq" value="Iraq">Iraq</option>
												<option <?php if ( $country == 'Ireland' ) {
													echo 'selected';
												} ?> class="Ireland" value="Ireland">Ireland</option>
												<option <?php if ( $country == 'Israel' ) {
													echo 'selected';
												} ?> class="Israel" value="Israel">Israel</option>
												<option <?php if ( $country == 'Italy' ) {
													echo 'selected';
												} ?> class="Italy" value="Italy">Italy</option>
												<option <?php if ( $country == 'Jamaica' ) {
													echo 'selected';
												} ?> class="Jamaica" value="Jamaica">Jamaica</option>
												<option <?php if ( $country == 'Japan' ) {
													echo 'selected';
												} ?> class="Japan" value="Japan">Japan</option>
												<option <?php if ( $country == 'Jordan' ) {
													echo 'selected';
												} ?> class="Jordan" value="Jordan">Jordan</option>
												<option <?php if ( $country == 'Kazakhstan' ) {
													echo 'selected';
												} ?> class="Kazakhstan" value="Kazakhstan">Kazakhstan</option>
												<option <?php if ( $country == 'Kenya' ) {
													echo 'selected';
												} ?> class="Kenya" value="Kenya">Kenya</option>
												<option <?php if ( $country == 'Kiribati' ) {
													echo 'selected';
												} ?> class="Kiribati" value="Kiribati">Kiribati</option>
												<option <?php if ( $country == 'Korea, North' ) {
													echo 'selected';
												} ?> class="Korea, North" value="Korea, North">Korea, North</option>
												<option <?php if ( $country == 'Korea, South' ) {
													echo 'selected';
												} ?> class="Korea, South" value="Korea, South">Korea, South</option>
												<option <?php if ( $country == 'Kuwait' ) {
													echo 'selected';
												} ?> class="Kuwait" value="Kuwait">Kuwait</option>
												<option <?php if ( $country == 'Kyrgyzstan' ) {
													echo 'selected';
												} ?> class="Kyrgyzstan" value="Kyrgyzstan">Kyrgyzstan</option>
												<option <?php if ( $country == 'Laos' ) {
													echo 'selected';
												} ?> class="Laos" value="Laos">Laos</option>
												<option <?php if ( $country == 'Latvia' ) {
													echo 'selected';
												} ?> class="Latvia" value="Latvia">Latvia</option>
												<option <?php if ( $country == 'Lebanon' ) {
													echo 'selected';
												} ?> class="Lebanon" value="Lebanon">Lebanon</option>
												<option <?php if ( $country == 'Lesotho' ) {
													echo 'selected';
												} ?> class="Lesotho" value="Lesotho">Lesotho</option>
												<option <?php if ( $country == 'Liberia' ) {
													echo 'selected';
												} ?> class="Liberia" value="Liberia">Liberia</option>
												<option <?php if ( $country == 'Libya' ) {
													echo 'selected';
												} ?> class="Libya" value="Libya">Libya</option>
												<option <?php if ( $country == 'Liechtenstein' ) {
													echo 'selected';
												} ?> class="Liechtenstein" value="Liechtenstein">Liechtenstein</option>
												<option <?php if ( $country == 'Lithuania' ) {
													echo 'selected';
												} ?> class="Lithuania" value="Lithuania">Lithuania</option>
												<option <?php if ( $country == 'Luxembourg' ) {
													echo 'selected';
												} ?> class="Luxembourg" value="Luxembourg">Luxembourg</option>
												<option <?php if ( $country == 'Macau' ) {
													echo 'selected';
												} ?> class="Macau" value="Macau">Macau</option>
												<option <?php if ( $country == 'Macedonia' ) {
													echo 'selected';
												} ?> class="Macedonia" value="Macedonia">Macedonia</option>
												<option <?php if ( $country == 'Madagascar' ) {
													echo 'selected';
												} ?> class="Madagascar" value="Madagascar">Madagascar</option>
												<option <?php if ( $country == 'Malawi' ) {
													echo 'selected';
												} ?> class="Malawi" value="Malawi">Malawi</option>
												<option <?php if ( $country == 'Malaysia' ) {
													echo 'selected';
												} ?> class="Malaysia" value="Malaysia">Malaysia</option>
												<option <?php if ( $country == 'Maldives' ) {
													echo 'selected';
												} ?> class="Maldives" value="Maldives">Maldives</option>
												<option <?php if ( $country == 'Mali' ) {
													echo 'selected';
												} ?> class="Mali" value="Mali">Mali</option>
												<option <?php if ( $country == 'Malta' ) {
													echo 'selected';
												} ?> class="Malta" value="Malta">Malta</option>
												<option <?php if ( $country == 'Marshall Islands' ) {
													echo 'selected';
												} ?> class="Marshall Islands" value="Marshall Islands">Marshall Islands</option>
												<option <?php if ( $country == 'Mauritania' ) {
													echo 'selected';
												} ?> class="Mauritania" value="Mauritania">Mauritania</option>
												<option <?php if ( $country == 'Mauritius' ) {
													echo 'selected';
												} ?> class="Mauritius" value="Mauritius">Mauritius</option>
												<option <?php if ( $country == 'Mexico' ) {
													echo 'selected';
												} ?> class="Mexico" value="Mexico">Mexico</option>
												<option <?php if ( $country == 'Micronesia' ) {
													echo 'selected';
												} ?> class="Micronesia" value="Micronesia">Micronesia</option>
												<option <?php if ( $country == 'Moldova' ) {
													echo 'selected';
												} ?> class="Moldova" value="Moldova">Moldova</option>
												<option <?php if ( $country == 'Monaco' ) {
													echo 'selected';
												} ?> class="Monaco" value="Monaco">Monaco</option>
												<option <?php if ( $country == 'Mongolia' ) {
													echo 'selected';
												} ?> class="Mongolia" value="Mongolia">Mongolia</option>
												<option <?php if ( $country == 'Montenegro' ) {
													echo 'selected';
												} ?> class="Montenegro" value="Montenegro">Montenegro</option>
												<option <?php if ( $country == 'Morocco' ) {
													echo 'selected';
												} ?> class="Morocco" value="Morocco">Morocco</option>
												<option <?php if ( $country == 'Mozambique' ) {
													echo 'selected';
												} ?> class="Mozambique" value="Mozambique">Mozambique</option>
												<option <?php if ( $country == 'Namibia' ) {
													echo 'selected';
												} ?> class="Namibia" value="Namibia">Namibia</option>
												<option <?php if ( $country == 'Nauru' ) {
													echo 'selected';
												} ?> class="Nauru" value="Nauru">Nauru</option>
												<option <?php if ( $country == 'Nepal' ) {
													echo 'selected';
												} ?> class="Nepal" value="Nepal">Nepal</option>
												<option <?php if ( $country == 'Netherlands' ) {
													echo 'selected';
												} ?> class="Netherlands" value="Netherlands">Netherlands</option>
												<option <?php if ( $country == 'Netherlands Antilles' ) {
													echo 'selected';
												} ?> class="Netherlands Antilles" value="Netherlands Antilles">Netherlands Antilles</option>
												<option <?php if ( $country == 'New Zealand' ) {
													echo 'selected';
												} ?> class="New Zealand" value="New Zealand">New Zealand</option>
												<option <?php if ( $country == 'Nicaragua' ) {
													echo 'selected';
												} ?> class="Nicaragua" value="Nicaragua">Nicaragua</option>
												<option <?php if ( $country == 'Niger' ) {
													echo 'selected';
												} ?> class="Niger" value="Niger">Niger</option>
												<option <?php if ( $country == 'Nigeria' ) {
													echo 'selected';
												} ?> class="Nigeria" value="Nigeria">Nigeria</option>
												<option <?php if ( $country == 'North Korea' ) {
													echo 'selected';
												} ?> class="North Korea" value="North Korea">North Korea</option>
												<option <?php if ( $country == 'Norway' ) {
													echo 'selected';
												} ?> class="Norway" value="Norway">Norway</option>
												<option <?php if ( $country == 'Oman' ) {
													echo 'selected';
												} ?> class="Oman" value="Oman">Oman</option>
												<option <?php if ( $country == 'Pakistan' ) {
													echo 'selected';
												} ?> class="Pakistan" value="Pakistan">Pakistan</option>
												<option <?php if ( $country == 'Palau' ) {
													echo 'selected';
												} ?> class="Palau" value="Palau">Palau</option>
												<option <?php if ( $country == 'Palestinian Territories' ) {
													echo 'selected';
												} ?> class="Palestinian Territories"
												     value="Palestinian Territories">Palestinian Territories</option>
												<option <?php if ( $country == 'Panama' ) {
													echo 'selected';
												} ?> class="Panama" value="Panama">Panama</option>
												<option <?php if ( $country == 'Papua New Guinea' ) {
													echo 'selected';
												} ?> class="Papua New Guinea" value="Papua New Guinea">Papua New Guinea</option>
												<option <?php if ( $country == 'Paraguay' ) {
													echo 'selected';
												} ?> class="Paraguay" value="Paraguay">Paraguay</option>
												<option <?php if ( $country == 'Peru' ) {
													echo 'selected';
												} ?> class="Peru" value="Peru">Peru</option>
												<option <?php if ( $country == 'Philippines' ) {
													echo 'selected';
												} ?> class="Philippines" value="Philippines">Philippines</option>
												<option <?php if ( $country == 'Poland' ) {
													echo 'selected';
												} ?> class="Poland" value="Poland">Poland</option>
												<option <?php if ( $country == 'Portugal' ) {
													echo 'selected';
												} ?> class="Portugal" value="Portugal">Portugal</option>
												<option <?php if ( $country == 'Qatar' ) {
													echo 'selected';
												} ?> class="Qatar" value="Qatar">Qatar</option>
												<option <?php if ( $country == 'Romania' ) {
													echo 'selected';
												} ?> class="Romania" value="Romania">Romania</option>
												<option <?php if ( $country == 'Russia' ) {
													echo 'selected';
												} ?> class="Russia" value="Russia">Russia</option>
												<option <?php if ( $country == 'Rwanda' ) {
													echo 'selected';
												} ?> class="Rwanda" value="Rwanda">Rwanda</option>
												<option <?php if ( $country == 'Saint Kitts and Nevis' ) {
													echo 'selected';
												} ?> class="Saint Kitts and Nevis" value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
												<option <?php if ( $country == 'Saint Lucia' ) {
													echo 'selected';
												} ?> class="Saint Lucia" value="Saint Lucia">Saint Lucia</option>
												<option <?php if ( $country == 'Saint Vincent and the Grenadines' ) {
													echo 'selected';
												} ?> class="Saint Vincent and the Grenadines" value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
												<option <?php if ( $country == 'Samoa' ) {
													echo 'selected';
												} ?> class="Samoa" value="Samoa">Samoa</option>
												<option <?php if ( $country == 'San Marino' ) {
													echo 'selected';
												} ?> class="San Marino" value="San Marino">San Marino</option>
												<option <?php if ( $country == 'ao Tome and Principe' ) {
													echo 'selected';
												} ?> class="Sao Tome and Principe" value="Sao Tome and Principe">Sao Tome and Principe</option>
												<option <?php if ( $country == 'Saudi Arabia' ) {
													echo 'selected';
												} ?> class="Saudi Arabia" value="Saudi Arabia">Saudi Arabia</option>
												<option <?php if ( $country == 'Senegal' ) {
													echo 'selected';
												} ?> class="Senegal" value="Senegal">Senegal</option>
												<option <?php if ( $country == 'Serbia' ) {
													echo 'selected';
												} ?> class="Serbia" value="Serbia">Serbia</option>
												<option <?php if ( $country == 'Seychelles' ) {
													echo 'selected';
												} ?> class="Seychelles" value="Seychelles">Seychelles</option>
												<option <?php if ( $country == 'Sierra Leone' ) {
													echo 'selected';
												} ?> class="Sierra Leone" value="Sierra Leone">Sierra Leone</option>
												<option <?php if ( $country == 'Singapore' ) {
													echo 'selected';
												} ?> class="Singapore" value="Singapore">Singapore</option>
												<option <?php if ( $country == 'Sint Maarten' ) {
													echo 'selected';
												} ?> class="Sint Maarten" value="Sint Maarten">Sint Maarten</option>
												<option <?php if ( $country == 'Slovakia' ) {
													echo 'selected';
												} ?> class="Slovakia" value="Slovakia">Slovakia</option>
												<option <?php if ( $country == 'Slovenia' ) {
													echo 'selected';
												} ?> class="Slovenia" value="Slovenia">Slovenia</option>
												<option <?php if ( $country == 'Solomon Islands' ) {
													echo 'selected';
												} ?> class="Solomon Islands" value="Solomon Islands">Solomon Islands</option>
												<option <?php if ( $country == 'Somalia' ) {
													echo 'selected';
												} ?> class="Somalia" value="Somalia">Somalia</option>
												<option <?php if ( $country == 'South Africa' ) {
													echo 'selected';
												} ?> class="South Africa" value="South Africa">South Africa</option>
												<option <?php if ( $country == 'South Korea' ) {
													echo 'selected';
												} ?> class="South Korea" value="South Korea">South Korea</option>
												<option <?php if ( $country == 'South Sudan' ) {
													echo 'selected';
												} ?> class="South Sudan" value="South Sudan">South Sudan</option>
												<option <?php if ( $country == 'Spain' ) {
													echo 'selected';
												} ?> class="Spain" value="Spain">Spain</option>
												<option <?php if ( $country == 'Sri Lanka' ) {
													echo 'selected';
												} ?> class="Sri Lanka" value="Sri Lanka">Sri Lanka</option>
												<option <?php if ( $country == 'Sudan' ) {
													echo 'selected';
												} ?> class="Sudan" value="Sudan">Sudan</option>
												<option <?php if ( $country == 'Suriname' ) {
													echo 'selected';
												} ?> class="Suriname" value="Suriname">Suriname</option>
												<option <?php if ( $country == 'Swaziland' ) {
													echo 'selected';
												} ?> class="Swaziland" value="Swaziland">Swaziland</option>
												<option <?php if ( $country == 'Sweden' ) {
													echo 'selected';
												} ?> class="Sweden" value="Sweden">Sweden</option>
												<option <?php if ( $country == 'Switzerland' ) {
													echo 'selected';
												} ?> class="Switzerland" value="Switzerland">Switzerland</option>
												<option <?php if ( $country == 'Syria' ) {
													echo 'selected';
												} ?> class="Syria" value="Syria">Syria</option>
												<option <?php if ( $country == 'Taiwan' ) {
													echo 'selected';
												} ?> class="Taiwan" value="Taiwan">Taiwan</option>
												<option <?php if ( $country == 'Tajikistan' ) {
													echo 'selected';
												} ?> class="Tajikistan" value="Tajikistan">Tajikistan</option>
												<option <?php if ( $country == 'Tanzania' ) {
													echo 'selected';
												} ?> class="Tanzania" value="Tanzania">Tanzania</option>
												<option <?php if ( $country == 'Thailand' ) {
													echo 'selected';
												} ?> class="Thailand" value="Thailand">Thailand</option>
												<option <?php if ( $country == 'Timor-Leste' ) {
													echo 'selected';
												} ?> class="Timor-Leste" value="Timor-Leste">Timor-Leste</option>
												<option <?php if ( $country == 'Togo' ) {
													echo 'selected';
												} ?> class="Togo" value="Togo">Togo</option>
												<option <?php if ( $country == 'Tonga' ) {
													echo 'selected';
												} ?> class="Tonga" value="Tonga">Tonga</option>
												<option <?php if ( $country == 'Trinidad and Tobago' ) {
													echo 'selected';
												} ?> class="Trinidad and Tobago" value="Trinidad and Tobago">Trinidad and Tobago</option>
												<option <?php if ( $country == 'Tunisia' ) {
													echo 'selected';
												} ?> class="Tunisia" value="Tunisia">Tunisia</option>
												<option <?php if ( $country == 'Turkey' ) {
													echo 'selected';
												} ?> class="Turkey" value="Turkey">Turkey</option>
												<option <?php if ( $country == 'Turkmenistan' ) {
													echo 'selected';
												} ?> class="Turkmenistan" value="Turkmenistan">Turkmenistan</option>
												<option <?php if ( $country == 'Tuvalu' ) {
													echo 'selected';
												} ?> class="Tuvalu" value="Tuvalu">Tuvalu</option>
												<option <?php if ( $country == 'Uganda' ) {
													echo 'selected';
												} ?> class="Uganda" value="Uganda">Uganda</option>
												<option <?php if ( $country == 'Ukraine' ) {
													echo 'selected';
												} ?> class="Ukraine" value="Ukraine">Ukraine</option>
												<option <?php if ( $country == 'United Arab Emirates' ) {
													echo 'selected';
												} ?> class="United Arab Emirates" value="United Arab Emirates">United Arab Emirates</option>
												<option <?php if ( $country == 'United Kingdom' ) {
													echo 'selected';
												} ?> class="United Kingdom" value="United Kingdom">United Kingdom</option>
												<option <?php if ( $country == 'United States of America' ) {
													echo 'selected';
												} ?> class="United States of America"
												     value="United States of America">United States of America</option>
												<option <?php if ( $country == 'Uruguay' ) {
													echo 'selected';
												} ?> class="Uruguay" value="Uruguay">Uruguay</option>
												<option <?php if ( $country == 'Uzbekistan' ) {
													echo 'selected';
												} ?> class="Uzbekistan" value="Uzbekistan">Uzbekistan</option>
												<option <?php if ( $country == 'Vanuatu' ) {
													echo 'selected';
												} ?> class="Vanuatu" value="Vanuatu">Vanuatu</option>
												<option <?php if ( $country == 'Venezuela' ) {
													echo 'selected';
												} ?> class="Venezuela" value="Venezuela">Venezuela</option>
												<option <?php if ( $country == 'Vietnam' ) {
													echo 'selected';
												} ?> class="Vietnam" value="Vietnam">Vietnam</option>
												<option <?php if ( $country == 'Yemen' ) {
													echo 'selected';
												} ?> class="Yemen" value="Yemen">Yemen</option>
												<option <?php if ( $country == 'Zambia' ) {
													echo 'selected';
												} ?> class="Zambia" value="Zambia">Zambia</option>
												<option <?php if ( $country == 'Zimbabwe' ) {
													echo 'selected';
												} ?> class="Zimbabwe" value="Zimbabwe">Zimbabwe</option>
											</select>

										</label>
									</p>
								</div>

								<div class="register-candidate__input-cell">
									<p class="form-row form-row-wide">
										<label for="teamLanguage">
											<span> <?php esc_html_e("Team language", "arcane"); ?></span>
											<?php
											$language = '';
											if($team_id) {
												$language = get_post_meta( $team_id, 'language', true );
											}
											?>
											<select name="teamLanguage">
												<option <?php if ( $language == 'Abkhaz' ) {
													echo 'selected';
												} ?> class="Abkhaz" value="Abkhaz">Abkhaz</option>
												<option <?php if ( $language == 'Adyghe' ) {
													echo 'selected';
												} ?> class="Adyghe" value="Adyghe">Adyghe</option>
												<option <?php if ( $language == 'Afrikaans' ) {
													echo 'selected';
												} ?> class="Afrikaans" value="Afrikaans">Afrikaans</option>
												<option <?php if ( $language == 'Akan' ) {
													echo 'selected';
												} ?> class="Akan" value="Akan">Akan</option>
												<option <?php if ( $language == 'American Sign Language' ) {
													echo 'selected';
												} ?> class="American Sign Language"
												     value="American Sign Language">American Sign Language</option>
												<option <?php if ( $language == 'Amharic' ) {
													echo 'selected';
												} ?> class="Amharic" value="Amharic">Amharic</option>
												<option <?php if ( $language == 'Ancient Greek' ) {
													echo 'selected';
												} ?> class="Ancient Greek" value="Ancient Greek">Ancient Greek</option>
												<option <?php if ( $language == 'Arabic' ) {
													echo 'selected';
												} ?> class="Arabic" value="Arabic">Arabic</option>
												<option <?php if ( $language == 'Aragonese' ) {
													echo 'selected';
												} ?> class="Aragonese" value="Aragonese">Aragonese</option>
												<option <?php if ( $language == 'Armenian' ) {
													echo 'selected';
												} ?> class="Armenian" value="Armenian">Armenian</option>
												<option <?php if ( $language == 'Aymara' ) {
													echo 'selected';
												} ?> class="Aymara" value="Aymara">Aymara</option>
												<option <?php if ( $language == 'Balinese' ) {
													echo 'selected';
												} ?> class="Balinese" value="Balinese">Balinese</option>
												<option <?php if ( $language == 'Basque' ) {
													echo 'selected';
												} ?> class="Basque" value="Basque">Basque</option>
												<option <?php if ( $language == 'Betawi' ) {
													echo 'selected';
												} ?> class="Betawi" value="Betawi">Betawi</option>
												<option <?php if ( $language == 'Bosnian' ) {
													echo 'selected';
												} ?> class="Bosnian" value="Bosnian">Bosnian</option>
												<option <?php if ( $language == 'Breton' ) {
													echo 'selected';
												} ?> class="Breton" value="Breton">Breton</option>
												<option <?php if ( $language == 'Bulgarian' ) {
													echo 'selected';
												} ?> class="Bulgarian" value="Bulgarian">Bulgarian</option>
												<option <?php if ( $language == 'Cantonese' ) {
													echo 'selected';
												} ?> class="Cantonese" value="Cantonese">Cantonese</option>
												<option <?php if ( $language == 'Catalan' ) {
													echo 'selected';
												} ?> class="Catalan" value="Catalan">Catalan</option>
												<option <?php if ( $language == 'Cherokee' ) {
													echo 'selected';
												} ?> class="Cherokee" value="Cherokee">Cherokee</option>
												<option <?php if ( $language == 'Chickasaw' ) {
													echo 'selected';
												} ?> class="Chickasaw" value="Chickasaw">Chickasaw</option>
												<option <?php if ( $language == 'Chinese' ) {
													echo 'selected';
												} ?> class="Chinese" value="Chinese">Chinese</option>
												<option <?php if ( $language == 'Coptic' ) {
													echo 'selected';
												} ?> class="Coptic" value="Coptic">Coptic</option>
												<option <?php if ( $language == 'Cornish' ) {
													echo 'selected';
												} ?> class="Cornish" value="Cornish">Cornish</option>
												<option <?php if ( $language == 'Corsican' ) {
													echo 'selected';
												} ?> class="Corsican" value="Corsican">Corsican</option>
												<option <?php if ( $language == 'Crimean Tatar' ) {
													echo 'selected';
												} ?> class="Crimean Tatar" value="Crimean Tatar">Crimean Tatar</option>
												<option <?php if ( $language == 'Croatian' ) {
													echo 'selected';
												} ?> class="Croatian" value="Croatian">Croatian</option>
												<option <?php if ( $language == 'Czech' ) {
													echo 'selected';
												} ?> class="Czech" value="Czech">Czech</option>
												<option <?php if ( $language == 'Danish' ) {
													echo 'selected';
												} ?> class="Danish" value="Danish">Danish</option>
												<option <?php if ( $language == 'Dawro' ) {
													echo 'selected';
												} ?> class="Dawro" value="Dawro">Dawro</option>
												<option <?php if ( $language == 'Dutch' ) {
													echo 'selected';
												} ?> class="Dutch" value="Dutch">Dutch</option>
												<option <?php if ( $language == 'English' ) {
													echo 'selected';
												} ?> class="English" value="English">English</option>
												<option <?php if ( $language == 'Esperanto' ) {
													echo 'selected';
												} ?> class="Esperanto" value="Esperanto">Esperanto</option>
												<option <?php if ( $language == 'Estonian' ) {
													echo 'selected';
												} ?> class="Estonian" value="Estonian">Estonian</option>
												<option <?php if ( $language == 'Ewe' ) {
													echo 'selected';
												} ?> class="Ewe" value="Ewe">Ewe</option>
												<option <?php if ( $language == 'Fiji Hindi' ) {
													echo 'selected';
												} ?> class="Fiji Hindi" value="Fiji Hindi">Fiji Hindi</option>
												<option <?php if ( $language == 'Filipino' ) {
													echo 'selected';
												} ?> class="Filipino" value="Filipino">Filipino</option>
												<option <?php if ( $language == 'Finnish' ) {
													echo 'selected';
												} ?> class="Finnish" value="Finnish">Finnish</option>
												<option <?php if ( $language == 'French' ) {
													echo 'selected';
												} ?> class="French" value="French">French</option>
												<option <?php if ( $language == 'Galician' ) {
													echo 'selected';
												} ?> class="Galician" value="Galician">Galician</option>
												<option <?php if ( $language == 'Georgian' ) {
													echo 'selected';
												} ?> class="Georgian" value="Georgian">Georgian</option>
												<option <?php if ( $language == 'German' ) {
													echo 'selected';
												} ?> class="German" value="German">German</option>
												<option <?php if ( $language == 'Greek, Modern' ) {
													echo 'selected';
												} ?> class="Greek, Modern" value="Greek, Modern">Greek, Modern</option>
												<option <?php if ( $language == 'Greenlandic' ) {
													echo 'selected';
												} ?> class="Greenlandic" value="Greenlandic">Greenlandic</option>
												<option <?php if ( $language == 'Haitian Creole' ) {
													echo 'selected';
												} ?> class="Haitian Creole" value="Haitian Creole">Haitian Creole</option>
												<option <?php if ( $language == 'Hawaiian' ) {
													echo 'selected';
												} ?> class="Hawaiian" value="Hawaiian">Hawaiian</option>
												<option <?php if ( $language == 'Hebrew' ) {
													echo 'selected';
												} ?> class="Hebrew" value="Hebrew">Hebrew</option>
												<option <?php if ( $language == 'Hindi' ) {
													echo 'selected';
												} ?> class="Hindi" value="Hindi">Hindi</option>
												<option <?php if ( $language == 'Hungarian' ) {
													echo 'selected';
												} ?> class="Hungarian" value="Hungarian">Hungarian</option>
												<option <?php if ( $language == 'Icelandic' ) {
													echo 'selected';
												} ?> class="Icelandic" value="Icelandic">Icelandic</option>
												<option <?php if ( $language == 'Indonesian' ) {
													echo 'selected';
												} ?> class="Indonesian" value="Indonesian">Indonesian</option>
												<option <?php if ( $language == 'Interlingua' ) {
													echo 'selected';
												} ?> class="Interlingua" value="Interlingua">Interlingua</option>
												<option <?php if ( $language == 'Inuktitut' ) {
													echo 'selected';
												} ?> class="Inuktitut" value="Inuktitut">Inuktitut</option>
												<option <?php if ( $language == 'Irish' ) {
													echo 'selected';
												} ?> class="Irish" value="Irish">Irish</option>
												<option <?php if ( $language == 'Italian' ) {
													echo 'selected';
												} ?> class="Italian" value="Italian">Italian</option>
												<option <?php if ( $language == 'Japanese' ) {
													echo 'selected';
												} ?> class="Japanese" value="Japanese">Japanese</option>
												<option <?php if ( $language == 'Kabardian' ) {
													echo 'selected';
												} ?> class="Kabardian" value="Kabardian">Kabardian</option>
												<option <?php if ( $language == 'Kannada' ) {
													echo 'selected';
												} ?> class="Kannada" value="Kannada">Kannada</option>
												<option <?php if ( $language == 'Kashubian' ) {
													echo 'selected';
												} ?> class="Kashubian" value="Kashubian">Kashubian</option>
												<option <?php if ( $language == 'Khmer' ) {
													echo 'selected';
												} ?> class="Khmer" value="Khmer">Khmer</option>
												<option <?php if ( $language == 'Kinyarwanda' ) {
													echo 'selected';
												} ?> class="Kinyarwanda" value="Kinyarwanda">Kinyarwanda</option>
												<option <?php if ( $language == 'Korean' ) {
													echo 'selected';
												} ?> class="Korean" value="Korean">Korean</option>
												<option <?php if ( $language == 'Kurdish/Kurdî' ) {
													echo 'selected';
												} ?> class="Kurdish/Kurdî" value="Kurdish/Kurdî">Kurdish/Kurdî</option>
												<option <?php if ( $language == 'Ladin' ) {
													echo 'selected';
												} ?> class="Ladin" value="Ladin">Ladin</option>
												<option <?php if ( $language == 'Latgalian' ) {
													echo 'selected';
												} ?> class="Latgalian" value="Latgalian">Latgalian</option>
												<option <?php if ( $language == 'Latin' ) {
													echo 'selected';
												} ?> class="Latin" value="Latin">Latin</option>
												<option <?php if ( $language == 'Lingala' ) {
													echo 'selected';
												} ?> class="Lingala" value="Lingala">Lingala</option>
												<option <?php if ( $language == 'Livonian' ) {
													echo 'selected';
												} ?> class="Livonian" value="Livonian">Livonian</option>
												<option <?php if ( $language == 'Lojban' ) {
													echo 'selected';
												} ?> class="Lojban" value="Lojban">Lojban</option>
												<option <?php if ( $language == 'Low German' ) {
													echo 'selected';
												} ?> class="Low German" value="Low German">Low German</option>
												<option <?php if ( $language == 'Lower Sorbian' ) {
													echo 'selected';
												} ?> class="Lower Sorbian" value="Lower Sorbian">Lower Sorbian</option>
												<option <?php if ( $language == 'Macedonian' ) {
													echo 'selected';
												} ?> class="Macedonian" value="Macedonian">Macedonian</option>
												<option <?php if ( $language == 'Malay' ) {
													echo 'selected';
												} ?> class="Malay" value="Malay">Malay</option>
												<option <?php if ( $language == 'Malayalam' ) {
													echo 'selected';
												} ?> class="Malayalam" value="Malayalam">Malayalam</option>
												<option <?php if ( $language == 'Mandarin' ) {
													echo 'selected';
												} ?> class="Mandarin" value="Mandarin">Mandarin</option>
												<option <?php if ( $language == 'Manx' ) {
													echo 'selected';
												} ?> class="Manx" value="Manx">Manx</option>
												<option <?php if ( $language == 'Maori' ) {
													echo 'selected';
												} ?> class="Maori" value="Maori">Maori</option>
												<option <?php if ( $language == 'Mauritian Creole' ) {
													echo 'selected';
												} ?> class="Mauritian Creole" value="Mauritian Creole">Mauritian Creole</option>
												<option <?php if ( $language == 'Min Nan' ) {
													echo 'selected';
												} ?> class="Min Nan" value="Min Nan">Min Nan</option>
												<option <?php if ( $language == 'Mongolian' ) {
													echo 'selected';
												} ?> class="Mongolian" value="Mongolian">Mongolian</option>
												<option <?php if ( $language == 'Norwegian' ) {
													echo 'selected';
												} ?> class="Norwegian" value="Norwegian">Norwegian</option>
												<option <?php if ( $language == 'Old Armenian' ) {
													echo 'selected';
												} ?> class="Old Armenian" value="Old Armenian">Old Armenian</option>
												<option <?php if ( $language == 'Old English' ) {
													echo 'selected';
												} ?> class="Old English" value="Old English">Old English</option>
												<option <?php if ( $language == 'Old French' ) {
													echo 'selected';
												} ?> class="Old French" value="Old French">Old French</option>
												<option <?php if ( $language == 'Old Norse' ) {
													echo 'selected';
												} ?> class="Old Norse" value="Old Norse">Old Norse</option>
												<option <?php if ( $language == 'Old Prussian' ) {
													echo 'selected';
												} ?> class="Old Prussian" value="Old Prussian">Old Prussian</option>
												<option <?php if ( $language == 'Oriya' ) {
													echo 'selected';
												} ?> class="Oriya" value="Oriya">Oriya</option>
												<option <?php if ( $language == 'Pangasinan' ) {
													echo 'selected';
												} ?> class="Pangasinan" value="Pangasinan">Pangasinan</option>
												<option <?php if ( $language == 'Papiamentu' ) {
													echo 'selected';
												} ?> class="Papiamentu" value="Papiamentu">Papiamentu</option>
												<option <?php if ( $language == 'Pashto' ) {
													echo 'selected';
												} ?> class="Pashto" value="Pashto">Pashto</option>
												<option <?php if ( $language == 'Persian' ) {
													echo 'selected';
												} ?> class="Persian" value="Persian">Persian</option>
												<option <?php if ( $language == 'Pitjantjatjara' ) {
													echo 'selected';
												} ?> class="Pitjantjatjara" value="Pitjantjatjara">Pitjantjatjara</option>
												<option <?php if ( $language == 'Polish' ) {
													echo 'selected';
												} ?> class="Polish" value="Polish">Polish</option>
												<option <?php if ( $language == 'Portuguese' ) {
													echo 'selected';
												} ?> class="Portuguese" value="Portuguese">Portuguese</option>
												<option <?php if ( $language == 'Proto-Slavic' ) {
													echo 'selected';
												} ?> class="Proto-Slavic" value="Proto-Slavic">Proto-Slavic</option>
												<option <?php if ( $language == 'Rapa Nui' ) {
													echo 'selected';
												} ?> class="Rapa Nui" value="Rapa Nui">Rapa Nui</option>
												<option <?php if ( $language == 'Romanian' ) {
													echo 'selected';
												} ?> class="Romanian" value="Romanian">Romanian</option>
												<option <?php if ( $language == 'Russian' ) {
													echo 'selected';
												} ?> class="Russian" value="Russian">Russian</option>
												<option <?php if ( $language == 'Sanskrit' ) {
													echo 'selected';
												} ?> class="Sanskrit" value="Sanskrit">Sanskrit</option>
												<option <?php if ( $language == 'Scots' ) {
													echo 'selected';
												} ?> class="Scots" value="Scots">Scots</option>
												<option <?php if ( $language == 'Scottish Gaelic' ) {
													echo 'selected';
												} ?> class="Scottish Gaelic" value="Scottish Gaelic">Scottish Gaelic</option>
												<option <?php if ( $language == 'Serbian' ) {
													echo 'selected';
												} ?> class="Serbian" value="Serbian">Serbian</option>
												<option <?php if ( $language == 'Serbo-Croatian' ) {
													echo 'selected';
												} ?> class="Serbo-Croatian" value="Serbo-Croatian">Serbo-Croatian</option>
												<option <?php if ( $language == 'Sina Bidoyoh' ) {
													echo 'selected';
												} ?> class="Sina Bidoyoh" value="Sina Bidoyoh">Sina Bidoyoh</option>
												<option <?php if ( $language == 'Sinhalese' ) {
													echo 'selected';
												} ?> class="Sinhalese" value="Sinhalese">Sinhalese</option>
												<option <?php if ( $language == 'Slovak' ) {
													echo 'selected';
												} ?> class="Slovak" value="Slovak">Slovak</option>
												<option <?php if ( $language == 'Slovene' ) {
													echo 'selected';
												} ?> class="Slovene" value="Slovene">Slovene</option>
												<option <?php if ( $language == 'Spanish' ) {
													echo 'selected';
												} ?> class="Spanish" value="Spanish">Spanish</option>
												<option <?php if ( $language == 'Swahili' ) {
													echo 'selected';
												} ?> class="Swahili" value="Swahili">Swahili</option>
												<option <?php if ( $language == 'Swedish' ) {
													echo 'selected';
												} ?> class="Swedish" value="Swedish">Swedish</option>
												<option <?php if ( $language == 'Tagalog' ) {
													echo 'selected';
												} ?> class="Tagalog" value="Tagalog">Tagalog</option>
												<option <?php if ( $language == 'Tajik' ) {
													echo 'selected';
												} ?> class="Tajik" value="Tajik">Tajik</option>
												<option <?php if ( $language == 'Tamil' ) {
													echo 'selected';
												} ?> class="Tamil" value="Tamil">Tamil</option>
												<option <?php if ( $language == 'Tarantino' ) {
													echo 'selected';
												} ?> class="Tarantino" value="Tarantino">Tarantino</option>
												<option <?php if ( $language == 'Telugu' ) {
													echo 'selected';
												} ?> class="Telugu" value="Telugu">Telugu</option>
												<option <?php if ( $language == 'Thai' ) {
													echo 'selected';
												} ?> class="Thai" value="Thai">Thai</option>
												<option <?php if ( $language == 'Tok Pisin' ) {
													echo 'selected';
												} ?> class="Tok Pisin" value="Tok Pisin">Tok Pisin</option>
												<option <?php if ( $language == 'Turkish' ) {
													echo 'selected';
												} ?> class="Turkish" value="Turkish">Turkish</option>
												<option <?php if ( $language == 'Twi' ) {
													echo 'selected';
												} ?> class="Twi" value="Twi">Twi</option>
												<option <?php if ( $language == 'Ukrainian' ) {
													echo 'selected';
												} ?> class="Ukrainian" value="Ukrainian">Ukrainian</option>
												<option <?php if ( $language == 'Upper Sorbian' ) {
													echo 'selected';
												} ?> class="Upper Sorbian" value="Upper Sorbian">Upper Sorbian</option>
												<option <?php if ( $language == 'Urdu' ) {
													echo 'selected';
												} ?> class="Urdu" value="Urdu">Urdu</option>
												<option <?php if ( $language == 'Uzbek' ) {
													echo 'selected';
												} ?> class="Uzbek" value="Uzbek">Uzbek</option>
												<option <?php if ( $language == 'Venetian' ) {
													echo 'selected';
												} ?> class="Venetian" value="Venetian">Venetian</option>
												<option <?php if ( $language == 'Vietnamese' ) {
													echo 'selected';
												} ?> class="Vietnamese" value="Vietnamese">Vietnamese</option>
												<option <?php if ( $language == 'Vilamovian' ) {
													echo 'selected';
												} ?> class="Vilamovian" value="Vilamovian">Vilamovian</option>
												<option <?php if ( $language == 'Volapük' ) {
													echo 'selected';
												} ?> class="Volapük" value="Volapük">Volapük</option>
												<option <?php if ( $language == 'Võro' ) {
													echo 'selected';
												} ?> class="Võro" value="Võro">Võro</option>
												<option <?php if ( $language == 'Welsh' ) {
													echo 'selected';
												} ?> class="Welsh" value="Welsh">Welsh</option>
												<option <?php if ( $language == 'Xhosa' ) {
													echo 'selected';
												} ?> class="Xhosa" value="Xhosa">Xhosa</option>
												<option <?php if ( $language == 'Yiddish' ) {
													echo 'selected';
												} ?> class="Yiddish" value="Yiddish">Yiddish</option>
											</select>
										</label>
									</p>
								</div>

								<div class="register-candidate__input-cell">
									<p class="form-row form-row-wide">
										<label for="teamFacebook">
											<span>  <?php esc_html_e("Facebook link", "arcane"); ?></span>
											<?php
											$facebook = '';
											if($team_id) {
												$facebook = get_post_meta( $team_id, 'teamFacebook', true );
											}
											?>
											<input name="teamFacebook" type="url" value="<?php echo esc_attr($facebook); ?>">
										</label>
									</p>
								</div>

								<div class="register-candidate__input-cell">
									<p class="form-row form-row-wide">
										<label for="teamInstagram">
											<span>  <?php esc_html_e("Instagram link", "arcane"); ?></span>
											<?php
											$instagram = '';
											if($team_id) {
												$instagram = get_post_meta( $team_id, 'teamInstagram', true );
											}
											?>
											<input name="teamInstagram" type="url" value="<?php echo esc_attr($instagram); ?>">
										</label>
									</p>
								</div>

								<div class="register-candidate__input-cell">
									<p class="form-row form-row-wide">
										<label for="teamTwitter">
											<span>  <?php esc_html_e("Twitter link", "arcane"); ?></span>
											<?php
											$twitter = '';
											if($team_id) {
												$twitter = get_post_meta( $team_id, 'teamTwitter', true );
											}
											?>
											<input name="teamTwitter" type="url" value="<?php echo esc_attr($twitter); ?>">
										</label>
									</p>
								</div>

								<div class="register-candidate__input-cell">
									<p class="form-row form-row-wide">
										<label for="teamTwitch">
											<span>  <?php esc_html_e("Twitch link", "arcane"); ?></span>
											<?php
											$twitch = '';
											if($team_id) {
												$twitch = get_post_meta( $team_id, 'teamTwitch', true );
											}
											?>
											<input name="teamTwitch" type="url" value="<?php echo esc_attr($twitch); ?>">
										</label>
									</p>
								</div>

								<div class="register-candidate__input-cell">
									<p class="form-row form-row-wide">
										<label for="discordServer">
											<span> <?php esc_html_e("Discord link", "arcane"); ?></span>
											<?php
											$discord = '';
											if($team_id) {
												$discord = get_post_meta( $team_id, 'discordServer', true );
											}
											?>
											<input name="discordServer" type="text" value="<?php echo esc_attr($discord); ?>">
										</label>
									</p>
								</div>

                                <div class="register-candidate__input-cell">
                                    <p class="form-row form-row-wide">
                                        <label for="teamYoutube">
                                            <span> <?php esc_html_e("Youtube link", "arcane"); ?></span>
											<?php
											$youtube = '';
											if($team_id) {
												$youtube = get_post_meta( $team_id, 'youtube', true );
											}
											?>
                                            <input name="teamYoutube" type="text" value="<?php echo esc_attr($youtube); ?>">
                                        </label>
                                    </p>
                                </div>

                                <div class="register-candidate__input-cell">
                                    <p class="form-row form-row-wide">
                                        <label for="teamWebsite">
                                            <span> <?php esc_html_e("Website link", "arcane"); ?></span>
											<?php
											$website = '';
											if($team_id) {
												$website = get_post_meta( $team_id, 'website', true );
											}
											?>
                                            <input name="teamWebsite" type="text" value="<?php echo esc_attr($website); ?>">
                                        </label>
                                    </p>
                                </div>

								<?php
								if($team_id){ ?>
									<button type="submit" class="btn" ><?php esc_html_e("Update", "arcane"); ?></button>

									<button data-pid="<?php echo esc_attr($team_id); ?>" id="deleteTeam" type="delete" class="btn btn-red" ><?php esc_html_e("Delete", "arcane"); ?></button>
								<?php }else{ ?>
									<button type="submit" class="btn" ><?php esc_html_e("Create", "arcane"); ?></button>
								<?php } ?>

							</div>

						</form>

				</div>
			</div>
		</div>



<?php get_footer(); ?>