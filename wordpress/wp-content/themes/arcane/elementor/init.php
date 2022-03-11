<?php

final class ElementorCustomElement {

    private static $instance = null;

    public static function get_instance() {
        if ( ! self::$instance )
            self::$instance = new self;
        return self::$instance;
    }

    public function init(){
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'widgets_registered' ] );
    }

    public function widgets_registered() {

        if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {

            //REGISTER WIDGETS

            if ( class_exists( 'Elementor\Plugin' ) && class_exists('arcane_Types') ) {
                if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
                    $elementor = Elementor\Plugin::instance();

                    if ( isset( $elementor->widgets_manager ) ) {

                        //LOAD WIDGETS

                        if ( method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {

                            require_once(get_theme_file_path('elementor/tournaments.php'));
                            Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SW_Tournaments() );

	                        require_once(get_theme_file_path('elementor/tournaments_single.php'));
	                        Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SW_Tournaments_Single() );

                            require_once(get_theme_file_path('elementor/matches.php'));
                            Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SW_Matches() );

                            require_once(get_theme_file_path('elementor/teams.php'));
                            Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SW_Teams() );

                            require_once(get_theme_file_path('elementor/games.php'));
                            Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SW_Games() );

                            require_once(get_theme_file_path('elementor/news_small.php'));
                            Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SW_News_Small() );

                            require_once(get_theme_file_path('elementor/news_main.php'));
                            Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SW_News_Main() );

                            require_once(get_theme_file_path('elementor/twitch.php'));
                            Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SW_Twitch() );

                            require_once(get_theme_file_path('elementor/swbutton.php'));
                            Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SW_Button() );

                            require_once(get_theme_file_path('elementor/shop.php'));
                            Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_SW_Shop() );


                        }
                    }
                }
            }
        }
    }
}


ElementorCustomElement::get_instance()->init();


function arcane_add_elementor_widget_categories( $elements_manager ) {

    $elements_manager->add_category(
        'skywarrior',
        [
            'title' => esc_html__( 'Skywarrior', 'arcane' ),
            'icon' => 'fa fa-plug',
        ]
    );

}
add_action( 'elementor/elements/categories_registered', 'arcane_add_elementor_widget_categories' );


final class Elementor_Extension {

    private static $instance = null;

    public static function get_instance() {
        if ( ! self::$instance )
            self::$instance = new self;
        return self::$instance;
    }
    public function init() {

        // Include plugin files
        $this->includes();

        // Register controls
        add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

    }

    public function includes() {

        require_once( __DIR__ . '/controls/multiselect.php' );

    }

    public function register_controls() {

        $controls_manager = \Elementor\Plugin::$instance->controls_manager;
        $controls_manager->register_control( 'multiselect', new Control_Multiselect() );

    }

}

Elementor_Extension::get_instance()->init();