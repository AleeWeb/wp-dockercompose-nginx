<?php
/**
 * Extension-Boilerplate
 *
 * @link https://github.com/ReduxFramework/extension-boilerplate
 *
 * Radium Importer - Modified For ReduxFramework
 * @link https://github.com/FrankM1/radium-one-click-demo-install
 *
 * @package     WBC_Importer - Extension for Importing demo content
 * @author      Webcreations907
 * @version     1.0.3
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_Extension_activator' ) ) {

	class ReduxFramework_Extension_activator {

		public static $instance;

		static $version = "1.0.3";

		protected $parent;


		/**
		 * Class Constructor
		 *
		 * @param $parent
		 *
		 * @since       1.0
		 * @access      public
		 */
		public function __construct( $parent ) {

			$this->parent = $parent;

			if ( !is_admin() ) return;

			$this->field_name = 'activator';


			self::$instance = $this;

			add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array( &$this,
				'overload_field_path'
			) );

			//Adds Importer section to panel
			$this->add_activator_section();

		}

		public static function get_instance() {
			return self::$instance;
		}

		// Forces the use of the embeded field path vs what the core typically would use
		public function overload_field_path( $field ) {
			return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
		}

		function add_activator_section() {
			// Checks to see if section was set in config of redux.
			for ( $n = 0; $n <= count( $this->parent->sections ); $n++ ) {
				if ( isset( $this->parent->sections[$n]['id'] ) && $this->parent->sections[$n]['id'] == 'activation_section' ) {
					return;
				}
			}

			$activator_label = trim( esc_html( apply_filters( 'activator_label', __( 'Theme Activation', 'framework' ) ) ) );

			$activator_label = ( !empty( $activator_label ) ) ? $activator_label : __( 'Theme Activation', 'framework' );

			$this->parent->sections[] = array(
				'id'     => 'activation_section',
				'title'  => $activator_label,
				'desc'   => '',
				'icon'   => 'el-icon-key',
				'fields' => array(
					array(
						'id'   => 'activation',
						'type' => 'activator'
					)
				)
			);
		}

	} // class
} // if
