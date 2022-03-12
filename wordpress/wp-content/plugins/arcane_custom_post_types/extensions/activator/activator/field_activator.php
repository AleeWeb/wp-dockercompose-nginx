<?php
/**
 * Extension-Boilerplate
 * @link https://github.com/ReduxFramework/extension-boilerplate
 *
 * Radium Importer - Modified For ReduxFramework
 * @link https://github.com/FrankM1/radium-one-click-demo-install
 *
 * @package     WBC_Importer - Extension for Importing demo content
 * @author      Webcreations907
 * @version     1.0.1
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_activator' ) ) {

	/**
	 * Main ReduxFramework_wbc_importer class
	 *
	 * @since       1.0.0
	 */
	class ReduxFramework_activator {

		/**
		 * Field Constructor.
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		function __construct() {
		}

		/**
		 * Field Render Function.
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {

			echo '</fieldset></td></tr><tr><td colspan="2"><fieldset class="redux-field wbc_importer">';
			$activator = new Arcane_Product_Registration();

			$activator->the_form();

			echo '</fieldset></td></tr>';

		}


	}
}


