<?php
/**
 * Widget Name: Match Carousel
 * Description: A Match Carousel widget.
 * Version: 1.0
 */

class Arcane_Match_Carousel_Widget extends WP_Widget
{

    var $default_settings = [];

    function __construct()
    {
        $widget_ops = array('classname' => 'arcane_match_carousel_widget', 'description' => esc_html__('Displays team match carousel', 'arcane'));
        parent::__construct('Arcane_Match_Carousel_Widget', esc_html__('SW Match Carousel', 'arcane'), $widget_ops);
        $this->default_settings = array('title' => esc_html__('Match Carousel', 'arcane'), 'visible_games' => array(), 'speed' => '8000');
    }


    function widget($args, $instance)
    {
        global $ArcaneWpTeamWars;

        $arcane_allowed = wp_kses_allowed_html('post');
        extract($args);

        $timezone_string = arcane_timezone_string();
        $timezone = $timezone_string ? $timezone_string : 'UTC';
        $time_now = new DateTime("now", new DateTimeZone($timezone));

        $now = $time_now->getTimestamp();

        $instance = wp_parse_args((array)$instance, $this->default_settings);

        $title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('TeamWars', 'arcane') : $instance['title']);

        $matches = $games = [];

        $_games = $ArcaneWpTeamWars->get_game(array(
            'id' => empty($instance['visible_games']) ? 'all' : $instance['visible_games'],
            'orderby' => 'title',
            'order' => 'asc'
        ));


        if (empty($from_date)) $from_date = '';
        if (empty($instance['show_limit'])) $instance['show_limit'] = '';

        foreach ($_games as $g) {
            $m = $ArcaneWpTeamWars->get_match(
                [
                    'from_date' => $from_date,
                    'game_id' => $g->id,
                    'limit' => $instance['show_limit'],
                    'order' => 'desc',
                    'orderby' => 'date',
                    'sum_tickets' => true,
                    'status' => 'active'
                ]
            );

            if (sizeof($m)) {
                $games[] = $g;
                $matches = array_merge($matches, $m);
            }
        }

        usort($matches, 'arcane_other_matches_sort');

        ?>

        <?php echo wp_kses($before_widget, $arcane_allowed); ?>

        <div class="nextmatch_widget">
            <?php
            if (empty($instance['hide_title'])) $instance['hide_title'] = '';
            if ($title && !$instance['hide_title'])

            echo wp_kses($before_title . $title . $after_title, $arcane_allowed);

            $random_id = rand();

            foreach ($matches as $match) {

                if (isset($match->date_unix) && !empty($match->date_unix)) {
                    $timestamp = $match->date_unix;
                } else {
                    $timestamp = mysql2date('U', $match->date);
                }

                $is_upcoming = $timestamp > $now;

                if ($is_upcoming) {
                    $upcoming = true;
                    break;
                }

            }

            if (!isset($upcoming)) {

                echo '<div class="nextmatch_wrap">';
                    esc_html_e('No upcoming matches', 'arcane');
                echo '</div>';

            } else { ?>

                <div id="matchCarousel<?php echo esc_attr($random_id); ?>" class="carousel slide carousel-fade"
                     data-ride="carousel" data-interval="<?php echo esc_attr($instance['speed']); ?>">

                    <ul class="tournaments-list carousel-inner" role="listbox">

                        <?php

                        $i = $j = 0;

                        foreach ($matches as $match) :

                            $timestamp = mysql2date('U', $match->date);
                            $is_upcoming = $timestamp > $now;

                            if ($is_upcoming) {

                                $team1id = $match->team1;
                                $team2id = $match->team2;
                                $tparticipants = $match->tournament_participants;

                                if ($tparticipants == 'team') {
                                    $is_user_type = false;
                                } elseif ($tparticipants === NULL || empty($tparticipants)) {
                                    $is_user_type = false;
                                } else {
                                    $is_user_type = true;
                                }

                                if ($is_user_type) {

                                    $user1 = get_user_by('id', $team1id);

                                    if (isset($user1->display_name)) {
                                        $team1_title = $user1->display_name;
                                    } else {
                                        $team1_title = '';
                                    }

                                    $pf_url1 = get_user_meta($team1id, 'profile_photo', true);
                                    if (!empty($pf_url1)) {
                                        $imagebg = arcane_aq_resize($pf_url1, 188, 155, true, true, true); //resize & crop img
                                        if (!isset ($imagebg[0])) {
                                            $pf_url1 = $pfpic;
                                        } else {
                                            $pf_url1 = $imagebg;
                                        }
                                        $image1 = esc_url($pf_url1);
                                    } else {
                                        $image1 = get_theme_file_uri('img/defaults/default-team.jpg');
                                    }


                                    $user2 = get_user_by('id', $team2id);

                                    if (isset($user2->display_name)) {
                                        $team2_title = $user2->display_name;
                                    } else {
                                        $team2_title = '';
                                    }

                                    $pf_url2 = get_user_meta($team2id, 'profile_photo', true);

                                    if (!empty($pf_url2)) {
                                        $imagebg = arcane_aq_resize($pf_url2, 188, 155, true, true, true); //resize & crop img
                                        if (!isset ($imagebg[0])) {
                                            $pf_url2 = $pfpic;
                                        } else {
                                            $pf_url2 = $imagebg;
                                        }
                                        $image2 = esc_url($pf_url2);
                                    } else {
                                        $image2 = get_theme_file_uri('img/defaults/default-team.jpg');
                                    }


                                } else {


                                    $img_url1 = get_post_meta($team1id, 'team_photo', true);
                                    $image1 = arcane_aq_resize($img_url1, 150, 124, true); //resize & crop img

                                    $team2id = $match->team2;
                                    $img_url2 = get_post_meta($team2id, 'team_photo', true);
                                    $image2 = arcane_aq_resize($img_url2, 150, 124, true); //resize & crop img
                                    $team1_title = arcane_return_team_name_by_team_id($team1id);
                                    $team2_title = arcane_return_team_name_by_team_id($team2id);
                                    if (isset($match->id)) {
                                        $mtchid = $match->id;
                                    } else {
                                        $mtchid = 0;
                                    }
                                    $dates[] = array('dates' => $timestamp, 'id' => $mtchid, 'team1' => $team1_title, 'team2' => $team2_title);


                                    if (!isset($image1) or empty($image1)) {
                                        $image1 = get_theme_file_uri('img/defaults/default-team.jpg');
                                    }
                                    if (!isset($image2) or empty($image2)) {
                                        $image2 = get_theme_file_uri('img/defaults/default-team.jpg');
                                    }

                                } ?>

                                <li class="citem game_<?php if (isset($match->game_title)) echo esc_html(strtolower(str_replace(' ', '', $match->game_title))); ?> overlay item match-carousel-cover-box <?php if ($i == 0) echo 'active'; ?>">
                                    <div class="side-widget">
                                        <div class="nm-info">
                                            <a href="<?php echo esc_url(get_permalink($match->ID)); ?>"
                                               title="<?php echo esc_attr($match->post_title); ?>">
                                                <div class="nextmatch_wrap">
                                                    <div class="team12w">
                                                        <img src="<?php echo esc_url($image1); ?>"
                                                              class="team1"
                                                              alt="<?php esc_attr($team1_title); ?>">
                                                    </div>
                                                    <div class="team12w">
                                                        <img src="<?php echo esc_url($image2); ?>"
                                                              class="team2"
                                                              alt="<?php esc_attr($team2_title); ?>">
                                                    </div>
                                                    <div class="clear"></div>
                                                    <div class="nm-teams">
                                                        <div class="r-home-team">
                                                            <span><?php echo esc_attr($team1_title); ?></span>
                                                        </div>
                                                        <div class="versus"><?php esc_html_e("VS", "arcane"); ?></div>
                                                        <div class="r-opponent-team">
                                                            <span><?php echo esc_attr($team2_title); ?></span>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="nm-date">
                                                        <?php echo esc_attr($match->post_title) . ' - ' . date(get_option('date_format'), mysql2date('U', $match->date)) . ' <span>' . date(get_option('time_format'), mysql2date('U', $match->date)) . '</span>'; ?>
                                                    </div>

                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </li>

                            <?php $j++; }

                            $i++;
                        endforeach;
                        ?>


                    </ul>

                    <?php if($j > 1){ ?>
                    <a class="left3 carousel-control" href="#matchCarousel<?php echo esc_attr($random_id); ?>"
                       role="button" data-slide="prev">
                        <span class="fas fa-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only"><?php esc_html_e("Previous", "arcane"); ?></span>
                    </a>
                    <a class="right3 carousel-control" href="#matchCarousel<?php echo esc_attr($random_id); ?>"
                       role="button" data-slide="next">
                        <span class="fas fa-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only"><?php esc_html_e("Next", "arcane"); ?></span>
                    </a>
                    <?php } ?>
                </div>

            <?php } ?>

        </div>
        <?php echo wp_kses($after_widget, $arcane_allowed); ?>

        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance)
    {
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance)
    {
        global $ArcaneWpTeamWars;
        $instance = wp_parse_args((array)$instance, $this->default_settings);
        $title = esc_attr($instance['title']);
        $visible_games = $instance['visible_games'];
        $speed = $instance['speed'];
        $games = $ArcaneWpTeamWars->get_game('id=all&orderby=title&order=asc');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'arcane'); ?></label>
            <input class="widefat" name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                   id="<?php echo esc_attr($this->get_field_id('title')); ?>" value="<?php echo esc_attr($title); ?>"
                   type="text"/>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('speed')); ?>"><?php esc_html_e('Carousel speed in ms:', 'arcane'); ?></label>
            <input class="widefat" name="<?php echo esc_attr($this->get_field_name('speed')); ?>"
                   id="<?php echo esc_attr($this->get_field_id('speed')); ?>" value="<?php echo esc_attr($speed); ?>"
                   type="text"/>
        </p>

        <p><?php esc_html_e('Show games:', 'arcane'); ?></p>
        <p class="widefat">
            <?php foreach ($games as $item) : ?>
                <label for="<?php echo esc_attr($this->get_field_id('visible_games-' . $item->id)); ?>"><input
                            type="checkbox" name="<?php echo esc_attr($this->get_field_name('visible_games')); ?>[]"
                            id="<?php echo esc_attr($this->get_field_id('visible_games-' . $item->id)); ?>"
                            value="<?php echo esc_attr($item->id); ?>" <?php checked(true, in_array($item->id, $visible_games)); ?>/> <?php echo esc_html($item->title); ?>
                </label><br/>
            <?php endforeach; ?>
        </p>
        <?php
    }

}


function arcane_return_match_carousel_widget()
{
    register_widget("Arcane_Match_Carousel_Widget");
}

// register widget
add_action('widgets_init', 'arcane_return_match_carousel_widget');