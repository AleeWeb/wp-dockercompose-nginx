<?php
$options = arcane_get_theme_options();

$display = '';
if(isset($options['disable_team_challenges']) && $options['disable_team_challenges'] == '1')
$display = 'none';


$arcane_custom_css = "
.team-page .challenge-team, .submit-score{
    display: $display;
}
";
