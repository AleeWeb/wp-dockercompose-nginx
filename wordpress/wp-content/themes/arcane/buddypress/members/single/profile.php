<?php
$a_id = bp_displayed_user_id();
$user = get_userdata($a_id);

 ?>
    <div class="col-8 block">
        <div class="title-wrapper">
        	<h3 class="widget-title"><i class="fas fa-bullhorn"></i> <?php esc_html_e('INTRODUCTION','arcane'); ?></h3>
        </div>
        <div class="wcontainer">
            <?php if(get_the_author_meta('description', $a_id)){
                    echo nl2br(get_the_author_meta('description', $a_id));
            } ?>
        </div>
    </div>


		<div class="col-4">
			<div class="block">
	        	<div class="title-wrapper">
	         		<h3 class="widget-title"><i class="fas fa-info-circle"></i> <?php esc_html_e('ABOUT ','arcane');  ?> </h3>
				</div>
				<ul class="about-profile">

	            <!--name-->
	            <?php if(get_the_author_meta('first_name', $a_id)){ ?>
	            	<li><strong><?php esc_html_e('NAME: ','arcane'); ?></strong>
	            	<?php echo get_the_author_meta('first_name', $a_id); ?>
	            <?php if(get_the_author_meta('last_name', $a_id)){
	                  echo get_the_author_meta('last_name', $a_id);
	            } ?> </li>
	            <?php } ?>
	            <!--name-->

	             <!--location-->
	              <li><strong> <?php esc_html_e('LOCATION: ','arcane');?></strong>
	            <?php
	            if(get_the_author_meta('usercountry_id', $a_id)){
	            $cid = get_the_author_meta('usercountry_id', $a_id);
	            $countries = arcane_registration_country_list();
	            foreach ($countries as $country) {
				if($cid == 1){
				          esc_html__( 'Afghanistan', 'arcane' );break;
				}elseif($cid == 2){
				         esc_html__( 'Albania', 'arcane');break;
				}elseif($cid == 3){
				          esc_html__( 'Algeria', 'arcane' );break;
				}elseif($cid == 4){
				          esc_html__( 'American Samoa', 'arcane' );break;
				}elseif($cid == 5){
				          esc_html__( 'Andorra', 'arcane' );break;
				}elseif($cid == 6){
				          esc_html__( 'Angola', 'arcane' );break;
				}elseif($cid == 7){
				          esc_html_e( 'Anguilla', 'arcane' );break;
				}elseif($cid == 8){
				          esc_html_e( 'Antarctica', 'arcane' );break;
				}elseif($cid == 9){
				          esc_html_e( 'Antigua and Barbuda', 'arcane' );break;
				}elseif($cid == 10){
				          esc_html_e( 'Argentina', 'arcane' );break;
				}elseif($cid == 11){
				          esc_html_e( 'Armenia', 'arcane' );break;
				}elseif($cid == 12){
				          esc_html_e( 'Aruba', 'arcane' );break;
				}elseif($cid == 13){
				          esc_html_e( 'Australia', 'arcane' );break;
				}elseif($cid == 14){
				          esc_html_e( 'Austria', 'arcane' );break;
				}elseif($cid == 15){
				          esc_html_e( 'Azerbaijan', 'arcane' );break;
				}elseif($cid == 16){
				          esc_html_e( 'Bahamas', 'arcane' );break;
				}elseif($cid == 17){
				          esc_html_e( 'Bahrain', 'arcane' );break;
				}elseif($cid == 18){
				          esc_html_e( 'Bangladesh', 'arcane' );break;
				}elseif($cid == 19){
				          esc_html_e( 'Barbados', 'arcane' );break;
				}elseif($cid == 20){
				          esc_html_e( 'Belarus', 'arcane' );break;
				}elseif($cid == 21){
				          esc_html_e( 'Belgium', 'arcane' );break;
				}elseif($cid == 22){
				          esc_html_e( 'Belize', 'arcane' );break;
				}elseif($cid == 23){
				          esc_html_e( 'Benin', 'arcane' );break;
				}elseif($cid == 24){
				          esc_html_e( 'Bermuda', 'arcane' );break;
				}elseif($cid == 25){
				          esc_html_e( 'Bhutan', 'arcane' );break;
				}elseif($cid == 26){
				          esc_html_e( 'Bolivia', 'arcane' );break;
				}elseif($cid == 27){
				          esc_html_e( 'Bosnia and Herzegowina', 'arcane' );break;
				}elseif($cid == 28){
				          esc_html_e( 'Botswana', 'arcane' );break;
				}elseif($cid == 29){
				          esc_html_e( 'Bouvet Island', 'arcane' );break;
				}elseif($cid == 30){
				          esc_html_e( 'Brazil', 'arcane' );break;
				}elseif($cid == 31){
				          esc_html_e( 'British Indian Ocean Territory', 'arcane' );break;
				}elseif($cid == 32){
				          esc_html_e( 'Brunei Darussalam', 'arcane' );break;
				}elseif($cid == 33){
				          esc_html_e( 'Bulgaria', 'arcane' );break;
				}elseif($cid == 34){
				          esc_html_e( 'Burkina Faso', 'arcane' );break;
				}elseif($cid == 35){
				          esc_html_e( 'Burundi', 'arcane' );break;
				}elseif($cid == 36){
				          esc_html_e( 'Cambodia', 'arcane' );break;
				}elseif($cid == 37){
				          esc_html_e( 'Cameroon', 'arcane' );break;
				}elseif($cid == 38){
				          esc_html_e( 'Canada', 'arcane' );break;
				}elseif($cid == 39){
				          esc_html_e( 'Cape Verde', 'arcane' );break;
				}elseif($cid == 40){
				          esc_html_e( 'Cayman Islands', 'arcane' );break;
				}elseif($cid == 41){
				          esc_html_e( 'Central African Republic', 'arcane' );break;
				}elseif($cid == 42){
				          esc_html_e( 'Chad', 'arcane' );break;
				}elseif($cid == 43){
				          esc_html_e( 'Chile', 'arcane' );break;
				}elseif($cid == 44){
				          esc_html_e( 'China', 'arcane' );break;
				}elseif($cid == 45){
				          esc_html_e( 'Christmas Island', 'arcane' );break;
				}elseif($cid == 46){
				          esc_html_e( 'Cocos (Keeling) Islands', 'arcane' );break;
				}elseif($cid == 47){
				          esc_html_e( 'Colombia', 'arcane' );break;
				}elseif($cid == 48){
				          esc_html_e( 'Comoros', 'arcane' );break;
				}elseif($cid == 49){
				          esc_html_e( 'Congo', 'arcane' );break;
				}elseif($cid == 50){
				          esc_html_e( 'Cook Islands', 'arcane' );break;
				}elseif($cid == 51){
				          esc_html_e( 'Costa Rica', 'arcane' );break;
				}elseif($cid == 52){
				          esc_html_e( 'Cote D\'Ivoire', 'arcane' );break;
				}elseif($cid == 53){
				          esc_html_e( 'Croatia', 'arcane' );break;
				}elseif($cid == 54){
				          esc_html_e( 'Cuba', 'arcane' );break;
				}elseif($cid == 55){
				          esc_html_e( 'Cyprus', 'arcane' );break;
				}elseif($cid == 56){
				          esc_html_e( 'Czech Republic', 'arcane' );break;
				}elseif($cid == 57){
				          esc_html_e( 'Denmark', 'arcane' );break;
				}elseif($cid == 58){
				          esc_html_e( 'Djibouti', 'arcane' );break;
				}elseif($cid == 59){
				          esc_html_e( 'Dominica', 'arcane' );break;
				}elseif($cid == 60){
				          esc_html_e( 'Dominican Republic', 'arcane' );break;
				}elseif($cid == 61){
				          esc_html_e( 'East Timor', 'arcane' );break;
				}elseif($cid == 62){
				          esc_html_e( 'Ecuador', 'arcane' );break;
				}elseif($cid == 63){
				          esc_html_e( 'Egypt', 'arcane' );break;
				}elseif($cid == 64){
				          esc_html_e( 'El Salvador', 'arcane' );break;
				}elseif($cid == 65){
				          esc_html_e( 'Equatorial Guinea', 'arcane' );break;
				}elseif($cid == 66){
				          esc_html_e( 'Eritrea', 'arcane' );break;
				}elseif($cid == 67){
				          esc_html_e( 'Estonia', 'arcane' );break;
				}elseif($cid == 68){
				          esc_html_e( 'Ethiopia', 'arcane' );break;
				}elseif($cid == 69){
				          esc_html_e( 'Falkland Islands (Malvinas)', 'arcane' );break;
				}elseif($cid == 70){
				          esc_html_e( 'Faroe Islands', 'arcane' );break;
				}elseif($cid == 71){
				          esc_html_e( 'Fiji', 'arcane' );break;
				}elseif($cid == 72){
				          esc_html_e( 'Finland', 'arcane' );break;
				}elseif($cid == 73){
				          esc_html_e( 'France', 'arcane' );break;
				}elseif($cid == 74){
				          esc_html_e( 'France, Metropolitan', 'arcane' );break;
				}elseif($cid == 75){
				          esc_html_e( 'French Guiana', 'arcane' );break;
				}elseif($cid == 76){
				          esc_html_e( 'French Polynesia', 'arcane' );break;
				}elseif($cid == 77){
				          esc_html_e( 'French Southern Territories', 'arcane' );break;
				}elseif($cid == 78){
				          esc_html_e( 'Gabon', 'arcane' );break;
				}elseif($cid == 79){
				          esc_html_e( 'Gambia', 'arcane' );break;
				}elseif($cid == 80){
				          esc_html_e( 'Georgia', 'arcane' );break;
				}elseif($cid == 81){
				          esc_html_e( 'Germany', 'arcane' );break;
				}elseif($cid == 82){
				          esc_html_e( 'Ghana', 'arcane' );break;
				}elseif($cid == 83){
				          esc_html_e( 'Gibraltar', 'arcane' );break;
				}elseif($cid == 84){
				          esc_html_e( 'Greece', 'arcane' );break;
				}elseif($cid == 85){
				          esc_html_e( 'Greenland', 'arcane' );break;
				}elseif($cid == 86){
				          esc_html_e( 'Grenada', 'arcane' );break;
				}elseif($cid == 87){
				          esc_html_e( 'Guadeloupe', 'arcane' );break;
				}elseif($cid == 88){
				          esc_html_e( 'Guam', 'arcane' );break;
				}elseif($cid == 89){
				          esc_html_e( 'Guatemala', 'arcane' );break;
				}elseif($cid == 90){
				          esc_html_e( 'Guinea', 'arcane' );break;
				}elseif($cid == 91){
				          esc_html_e( 'Guinea-bissau', 'arcane' );break;
				}elseif($cid == 92){
				          esc_html_e( 'Guyana', 'arcane' );break;
				}elseif($cid == 93){
				          esc_html_e( 'Haiti', 'arcane' );break;
				}elseif($cid == 94){
				          esc_html_e( 'Heard and Mc Donald Islands', 'arcane' );break;
				}elseif($cid == 95){
				          esc_html_e( 'Honduras', 'arcane' );break;
				}elseif($cid == 96){
				          esc_html_e( 'Hong Kong', 'arcane' );break;
				}elseif($cid == 97){
				          esc_html_e( 'Hungary', 'arcane' );break;
				}elseif($cid == 98){
				          esc_html_e( 'Iceland', 'arcane' );break;
				}elseif($cid == 99){
				          esc_html_e( 'India', 'arcane' );break;
				}elseif($cid == 100){
				          esc_html_e( 'Indonesia', 'arcane' );break;
				}elseif($cid == 101){
				          esc_html_e( 'Iran (Islamic Republic of)', 'arcane' );break;
				}elseif($cid == 102){
				          esc_html_e( 'Iraq', 'arcane' );break;
				}elseif($cid == 103){
				          esc_html_e( 'Ireland', 'arcane' );break;
				}elseif($cid == 104){
				          esc_html_e( 'Israel', 'arcane' );break;
				}elseif($cid == 105){
				          esc_html_e( 'Italy', 'arcane' );break;
				}elseif($cid == 106){
				          esc_html_e( 'Jamaica', 'arcane' );break;
				}elseif($cid == 107){
				          esc_html_e( 'Japan', 'arcane' );break;
				}elseif($cid == 108){
				          esc_html_e( 'Jordan', 'arcane' );break;
				}elseif($cid == 109){
				          esc_html_e( 'Kazakhstan', 'arcane' );break;
				}elseif($cid == 110){
				          esc_html_e( 'Kenya', 'arcane' );break;
				}elseif($cid == 111){
				          esc_html_e( 'Kiribati', 'arcane' );break;
				}elseif($cid == 112){
				          esc_html_e( 'Korea, Democratic People\'s Republic of', 'arcane' );break;
				}elseif($cid == 113){
				          esc_html_e( 'Korea, Republic of', 'arcane' );break;
				}elseif($cid == 114){
				          esc_html_e( 'Kuwait', 'arcane' );break;
				}elseif($cid == 115){
				          esc_html_e( 'Kyrgyzstan', 'arcane' );break;
				}elseif($cid == 116){
				          esc_html_e( 'Lao People\'s Democratic Republic', 'arcane' );break;
				}elseif($cid == 117){
				          esc_html_e( 'Latvia', 'arcane' );break;
				}elseif($cid == 118){
				          esc_html_e( 'Lebanon', 'arcane' );break;
				}elseif($cid == 119){
				          esc_html_e( 'Lesotho', 'arcane' );break;
				}elseif($cid == 120){
				          esc_html_e( 'Liberia', 'arcane' );break;
				}elseif($cid == 121){
				          esc_html_e( 'Libyan Arab Jamahiriya', 'arcane' );break;
				}elseif($cid == 122){
				          esc_html_e( 'Liechtenstein', 'arcane' );break;
				}elseif($cid == 123){
				          esc_html_e( 'Lithuania', 'arcane' );break;
				}elseif($cid == 124){
				          esc_html_e( 'Luxembourg', 'arcane' );break;
				}elseif($cid == 125){
				          esc_html_e( 'Macau', 'arcane' );break;
				}elseif($cid == 126){
				          esc_html_e( 'Macedonia, The Former Yugoslav Republic of', 'arcane' );break;
				}elseif($cid == 127){
				          esc_html_e( 'Madagascar', 'arcane' );break;
				}elseif($cid == 128){
				          esc_html_e( 'Malawi', 'arcane' );break;
				}elseif($cid == 129){
				          esc_html_e( 'Malaysia', 'arcane' );break;
				}elseif($cid == 130){
				          esc_html_e( 'Maldives', 'arcane' );break;
				}elseif($cid == 131){
				          esc_html_e( 'Mali', 'arcane' );break;
				}elseif($cid == 132){
				          esc_html_e( 'Malta', 'arcane' );break;
				}elseif($cid == 133){
				          esc_html_e( 'Marshall Islands', 'arcane' );break;
				}elseif($cid == 134){
				          esc_html_e( 'Martinique', 'arcane' );break;
				}elseif($cid == 135){
				          esc_html_e( 'Mauritania', 'arcane' );break;
				}elseif($cid == 136){
				          esc_html_e( 'Mauritius', 'arcane' );break;
				}elseif($cid == 137){
				          esc_html_e( 'Mayotte', 'arcane' );break;
				}elseif($cid == 138){
				          esc_html_e( 'Mexico', 'arcane' );break;
				}elseif($cid == 139){
				          esc_html_e( 'Micronesia, Federated States of', 'arcane' );break;
				}elseif($cid == 140){
				          esc_html_e( 'Moldova, Republic of', 'arcane' );break;
				}elseif($cid == 141){
				          esc_html_e( 'Monaco', 'arcane' );break;
				}elseif($cid == 142){
				          esc_html_e( 'Mongolia', 'arcane' );break;
				}elseif($cid == 143){
				          esc_html_e( 'Montserrat', 'arcane' );break;
				}elseif($cid == 144){
				          esc_html_e( 'Morocco', 'arcane' );break;
				}elseif($cid == 145){
				          esc_html_e( 'Mozambique', 'arcane' );break;
				}elseif($cid == 146){
				          esc_html_e( 'Myanmar', 'arcane' );break;
				}elseif($cid == 147){
				          esc_html_e( 'Namibia', 'arcane' );break;
				}elseif($cid == 148){
				          esc_html_e( 'Nauru', 'arcane' );break;
				}elseif($cid == 149){
				          esc_html_e( 'Nepal', 'arcane' );break;
				}elseif($cid == 150){
				          esc_html_e( 'Netherlands', 'arcane' );break;
				}elseif($cid == 151){
				          esc_html_e( 'Netherlands Antilles', 'arcane' );break;
				}elseif($cid == 152){
				          esc_html_e( 'New Caledonia', 'arcane' );break;
				}elseif($cid == 153){
				          esc_html_e( 'New Zealand', 'arcane' );break;
				}elseif($cid == 154){
				          esc_html_e( 'Nicaragua', 'arcane' );break;
				}elseif($cid == 155){
				          esc_html_e( 'Niger', 'arcane' );break;
				}elseif($cid == 156){
				          esc_html_e( 'Nigeria', 'arcane' );break;
				}elseif($cid == 157){
				          esc_html_e( 'Niue', 'arcane' );break;
				}elseif($cid == 158){
				          esc_html_e( 'Norfolk Island', 'arcane' );break;
				}elseif($cid == 159){
				          esc_html_e( 'Northern Mariana Islands', 'arcane' );break;
				}elseif($cid == 160){
				          esc_html_e( 'Norway', 'arcane' );break;
				}elseif($cid == 161){
				          esc_html_e( 'Oman', 'arcane' );break;
				}elseif($cid == 162){
				          esc_html_e( 'Pakistan', 'arcane' );break;
				}elseif($cid == 163){
				          esc_html_e( 'Palau', 'arcane' );break;
				}elseif($cid == 164){
				          esc_html_e( 'Panama', 'arcane' );break;
				}elseif($cid == 165){
				          esc_html_e( 'Papua New Guinea', 'arcane' );break;
				}elseif($cid == 166){
				          esc_html_e( 'Paraguay', 'arcane' );break;
				}elseif($cid == 167){
				          esc_html_e( 'Peru', 'arcane' );break;
				}elseif($cid == 168){
				          esc_html_e( 'Philippines', 'arcane' );break;
				}elseif($cid == 169){
				          esc_html_e( 'Pitcairn', 'arcane' );break;
				}elseif($cid == 170){
				          esc_html_e( 'Poland', 'arcane' );break;
				}elseif($cid == 171){
				          esc_html_e( 'Portugal', 'arcane' );break;
				}elseif($cid == 172){
				          esc_html_e( 'Puerto Rico', 'arcane' );break;
				}elseif($cid == 173){
				          esc_html_e( 'Qatar', 'arcane' );break;
				}elseif($cid == 174){
				          esc_html_e( 'Reunion', 'arcane' );break;
				}elseif($cid == 175){
				          esc_html_e( 'Romania', 'arcane' );break;
				}elseif($cid == 176){
				          esc_html_e( 'Russian Federation', 'arcane' );break;
				}elseif($cid == 177){
				          esc_html_e( 'Rwanda', 'arcane' );break;
				}elseif($cid == 178){
				          esc_html_e( 'Saint Kitts and Nevis', 'arcane' );break;
				}elseif($cid == 179){
				          esc_html_e( 'Saint Lucia', 'arcane' );break;
				}elseif($cid == 180){
				          esc_html_e( 'Saint Vincent and the Grenadines', 'arcane' );break;
				}elseif($cid == 181){
				          esc_html_e( 'Samoa', 'arcane' );break;
				}elseif($cid == 182){
				          esc_html_e( 'San Marino', 'arcane' );break;
				}elseif($cid == 183){
				          esc_html_e( 'Sao Tome and Principe', 'arcane' );break;
				}elseif($cid == 184){
				          esc_html_e( 'Saudi Arabia', 'arcane' );break;
				}elseif($cid == 185){
				          esc_html_e( 'Senegal', 'arcane' );break;
				}elseif($cid == 186){
				          esc_html_e( 'Serbia', 'arcane' );break;
				}elseif($cid == 187){
				          esc_html_e( 'Seychelles', 'arcane' );break;
				}elseif($cid == 188){
				          esc_html_e( 'Sierra Leone', 'arcane' );break;
				}elseif($cid == 189){
				          esc_html_e( 'Singapore', 'arcane' );break;
				}elseif($cid == 190){
				          esc_html_e( 'Slovakia', 'arcane' );break;
				}elseif($cid == 191){
				          esc_html_e( 'Slovenia', 'arcane' );break;
				}elseif($cid == 192){
				          esc_html_e( 'Solomon Islands', 'arcane' );break;
				}elseif($cid == 193){
				          esc_html_e( 'Somalia', 'arcane' );break;
				}elseif($cid == 194){
				          esc_html_e( 'South Africa', 'arcane' );break;
				}elseif($cid == 195){
				          esc_html_e( 'South Georgia and the South Sandwich Islands', 'arcane' );break;
				}elseif($cid == 196){
				          esc_html_e( 'Spain', 'arcane' );break;
				}elseif($cid == 197){
				          esc_html_e( 'Sri Lanka', 'arcane' );break;
				}elseif($cid == 198){
				          esc_html_e( 'St. Helena', 'arcane' );break;
				}elseif($cid == 199){
				          esc_html_e( 'St. Pierre and Miquelon', 'arcane' );break;
				}elseif($cid == 200){
				          esc_html_e( 'Sudan', 'arcane' );break;
				}elseif($cid == 201){
				          esc_html_e( 'Suriname', 'arcane' );break;
				}elseif($cid == 202){
				          esc_html_e( 'Svalbard and Jan Mayen Islands', 'arcane' );break;
				}elseif($cid == 203){
				          esc_html_e( 'Swaziland', 'arcane' );break;
				}elseif($cid == 204){
				          esc_html_e( 'Sweden', 'arcane' );break;
				}elseif($cid == 205){
				          esc_html_e( 'Switzerland', 'arcane' );break;
				}elseif($cid == 206){
				          esc_html_e( 'Syrian Arab Republic', 'arcane' );break;
				}elseif($cid == 207){
				          esc_html_e( 'Taiwan', 'arcane' );break;
				}elseif($cid == 208){
				          esc_html_e( 'Tajikistan', 'arcane' );break;
				}elseif($cid == 209){
				          esc_html_e( 'Tanzania, United Republic of', 'arcane' );break;
				}elseif($cid == 210){
				          esc_html_e( 'Thailand', 'arcane' );break;
				}elseif($cid == 211){
				          esc_html_e( 'Togo', 'arcane' );break;
				}elseif($cid == 212){
				          esc_html_e( 'Tokelau', 'arcane' );break;
				}elseif($cid == 213){
				          esc_html_e( 'Tonga', 'arcane' );break;
				}elseif($cid == 214){
				          esc_html_e( 'Trinidad and Tobago', 'arcane' );break;
				}elseif($cid == 215){
				          esc_html_e( 'Tunisia', 'arcane' );break;
				}elseif($cid == 216){
				          esc_html_e( 'Turkey', 'arcane' );break;
				}elseif($cid == 217){
				          esc_html_e( 'Turkmenistan', 'arcane' );break;
				}elseif($cid == 218){
				          esc_html_e( 'Turks and Caicos Islands', 'arcane' );break;
				}elseif($cid == 219){
				          esc_html_e( 'Tuvalu', 'arcane' );break;
				}elseif($cid == 220){
				          esc_html_e( 'Uganda', 'arcane' );break;
				}elseif($cid == 221){
				          esc_html_e( 'Ukraine', 'arcane' );break;
				}elseif($cid == 222){
				          esc_html_e( 'United Arab Emirates', 'arcane' );break;
				}elseif($cid == 223){
				          esc_html_e( 'United Kingdom', 'arcane' );break;
				}elseif($cid == 224){
				          esc_html_e( 'United States', 'arcane' );break;
				}elseif($cid == 225){
				          esc_html_e( 'United States Minor Outlying Islands', 'arcane' );break;
				}elseif($cid == 226){
				          esc_html_e( 'Uruguay', 'arcane' );break;
				}elseif($cid == 227){
				          esc_html_e( 'Uzbekistan', 'arcane' );break;
				}elseif($cid == 228){
				          esc_html_e( 'Vanuatu', 'arcane' );break;
				}elseif($cid == 229){
				          esc_html_e( 'Vatican City State (Holy See)', 'arcane' );break;
				}elseif($cid == 230){
				          esc_html_e( 'Venezuela', 'arcane' );break;
				}elseif($cid == 231){
				          esc_html_e( 'Viet Nam', 'arcane' );break;
				}elseif($cid == 232){
				          esc_html_e( 'Virgin Islands (British)', 'arcane' );break;
				}elseif($cid == 233){
				          esc_html_e( 'Virgin Islands (U.S.)', 'arcane' );break;
				}elseif($cid == 234){
				          esc_html_e( 'Wallis and Futuna Islands', 'arcane' );break;
				}elseif($cid == 235){
				          esc_html_e( 'Western Sahara', 'arcane' );break;
				}elseif($cid == 236){
				          esc_html_e( 'Yemen', 'arcane' );break;
				}elseif($cid == 237){
				          esc_html_e( 'Zaire', 'arcane' );break;
				}elseif($cid == 238){
				          esc_html_e( 'Zambia', 'arcane' );break;
				}elseif($cid == 239){
				          esc_html_e( 'Zimbabwe', 'arcane' );break;
				}

	            }
				?>
	           <?php
	                if(get_the_author_meta('city', $a_id))
	                {echo ', ';echo get_the_author_meta('city', $a_id);} ?>
	            </li>
                <?php } ?>
	            <!--location-->

	             <!--age-->
	            <?php if(get_the_author_meta('age', $a_id) && get_the_author_meta('age', $a_id) != 'none'){ ?>

	            <li><strong><?php esc_html_e('AGE: ','arcane');?></strong>
	                 <?php
                    $age = get_the_author_meta('age', $a_id);
					if(strlen($age) > 2){
                        echo  date_diff(date_create($age), date_create('today'))->y;
					}else{
						echo esc_attr($age);
					}
                } ?>
	            </li>


	            <!--age-->
					<li>
	            <!--joined-->
	           <strong> <?php esc_html_e('JOINED: ','arcane'); ?></strong>
	          <?php echo date_i18n("F, Y", strtotime(get_userdata($a_id)->user_registered)); ?>

	            <!--joined-->
					</li>
				  <?php
	            	global $wpdb;
					$custom_profile_fields = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'bp_xprofile_fields');

					if (is_array($custom_profile_fields)) {
						foreach ($custom_profile_fields as $field) {
							if($field->id == 1)continue;
							$query = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'bp_xprofile_data WHERE user_id= %s AND field_id= %s LIMIT 1' , $a_id, $field->id ));
							$visibility = $wpdb->get_row($wpdb->prepare('SELECT meta_value FROM '.$wpdb->prefix.'bp_xprofile_meta WHERE object_id= %s AND meta_key= "default_visibility" LIMIT 1' , $field->id ));
      if($visibility)
							if($visibility->meta_value == 'public' || ($visibility->meta_value == 'adminsonly' && $a_id == get_current_user_id()) || ($visibility->meta_value == 'loggedin' && is_user_logged_in()) || ($visibility->meta_value == 'friends' && friends_check_friendship(  $a_id,  get_current_user_id() ))){
								if (isset($query->value) && !empty($query->value)) {
									echo "<li>";
									echo "<strong>".strtoupper($field->name).": </strong>";
									$first = true;
									if (is_serialized($query->value)) {
										$row = unserialize($query->value);
										foreach ($row as $hold) {
											if ($first == true) {
												echo esc_attr($hold);
												$first = false;
											} else {
												echo ", ".esc_attr($hold);
											}
										}
									} else {
										if($field->type == 'url'){
											echo '<a target="_blank" href="'.esc_url($query->value).'">'.esc_attr($query->value).'</a>';
										}else{
											echo esc_attr($query->value);
										}
									}


									echo "</li>";
								}
							}

						}
					}



	            ?>
	            <!--website-->
	            <?php if(get_the_author_meta('user_url', $a_id)){ ?>
	            <li><strong><?php esc_html_e('WEBSITE: ','arcane');?></strong>
	               <a target="_blank" href=" <?php echo get_the_author_meta('user_url', $a_id); ?>">   <?php
                     echo get_the_author_meta('user_url', $a_id); ?>
                 </a>
	            </li>
                <?php } ?>
	            <!--website-->

			</ul>

	        </div>

	         <!--team-->
                <?php $postovi = arcane_get_user_teams_all($a_id);

                if(!empty($postovi)){ ?>

			<div class="block profile-teams">
	        	<div class="title-wrapper">
	         		<h3 class="widget-title"><i class="fas fa-crosshairs"></i> <?php esc_html_e('Teams ','arcane');  ?> </h3>
				</div>
				<ul class="about-profile">
                <?php
	                    foreach ($postovi as $pos) {
                                $pos = get_post($pos);
                                $photo = get_post_meta($pos->ID, 'team_photo',true);
                                $drft = '';
                                if(get_post_status($pos->ID) == 'draft'){
                                    $drft = '<span class="red_draft">['.get_post_status($pos->ID).']</span>';
                                }
                                if(empty($photo)) $photo = get_theme_file_uri('img/defaults/default-team-50x50.jpg');
                                if(get_post_status($pos->ID) == 'draft' && $a_id != get_current_user_id()){ continue; }else{	                             echo '<li>
    	                             <a href="'.esc_url(get_permalink($pos->ID)).'">
        	                             <div class="pteam-img">
        	                               <img alt="img" src="'.esc_url($photo).'"/>
        	                             </div>
    	                               <div class="pteam-title">'.esc_attr($pos->post_title).' '.wp_kses_post($drft).'</div>
	                                   <div class="clear"></div>
    	                             </a>
	                             </li>';
                                }
	                        }

	            }
	             ?>

	             </ul>
	            <!--team-->
	        </div>
       </div>
