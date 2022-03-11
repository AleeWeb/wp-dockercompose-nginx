<?php
/**
 * Envato API class.
 *
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Creates the Envato API connection.
 *
 * @class Arcane_Updater
 * @version 5.0.0
 * @since 5.0.0
 */
final class Arcane_Updater {

	/**
	 * The arguments that are used in the Arcane_Product_Registration class.
	 *
	 * @access private
	 * @since 1.0.0
	 * @var array
	 */
	private $args = array();

	/**
	 * An instance of the Arcane_Product_Registration class.
	 *
	 * @access private
	 * @since 1.0.0
	 * @var object Arcane_Product_Registration.
	 */
	private $registration;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param object $registration An instance of the Arcane_Product_Registration class.
	 */
	public function __construct( $registration ) {

		$this->registration = $registration;
		$this->args         = $registration->get_args();

		// Check for theme & plugin updates.
		add_filter( 'http_request_args', array( $this, 'update_check' ), 5, 2 );

		// Inject theme updates into the response array.
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'update_themes' ) );
		add_filter( 'pre_set_transient_update_themes', array( $this, 'update_themes' ) );

		// Deferred Download.
		add_action( 'upgrader_package_options', array( $this, 'maybe_deferred_download' ), 99 );

	}

	/**
	 * Deferred item download URL.
	 *
	 * @since 5.0.0
	 *
	 * @param int $id The item ID.
	 * @return string.
	 */
	public function deferred_download( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		$args = array(
			'deferred_download' => true,
			'item_id' => $id,
		);
		return add_query_arg( $args, esc_url( admin_url( 'admin.php?page=Arcane' ) ) );
	}

	/**
	 * Get the item download.
	 *
	 * @since 5.0.0
	 *
	 * @param  int   $id The item ID.
	 * @param  array $args The arguments passed to `wp_remote_get`.
	 * @return bool|array The HTTP response.
	 */
	public function download( $id, $args = array() ) {
		if ( empty( $id ) ) {
			return false;
		}

		$url = 'https://api.envato.com/v2/market/buyer/download?item_id=' . $id . '&shorten_url=true';
		$response = $this->registration->envato_api()->request( $url, $args );

		// @todo Find out which errors could be returned & handle them in the UI.
		if ( is_wp_error( $response ) || empty( $response ) || ! empty( $response['error'] ) ) {
			return false;
		}

		if ( ! empty( $response['wordpress_theme'] ) ) {
			return $response['wordpress_theme'];
		}

		return false;
	}

	/**
	 * Inject update data for premium themes.
	 *
	 * @since 5.0.0
	 *
	 * @param object $transient The pre-saved value of the `update_themes` site transient.
	 * @return object
	 */
	public function update_themes( $transient ) {
		// Process Arcane updates.
		if ( isset( $transient->checked ) ) {

			// Get the installed version of Arcane.
			$arcane_theme = wp_get_theme();
			$current_arcane_version = $arcane_theme->get( 'Version' );

			// Get the themes from the Envato API.
			$themes = $this->registration->envato_api()->themes();

			// Get latest Arcane version.
			$latest_arcane = array(
				'id'      => '',
				'name'    => '',
				'url'     => '',
				'version' => '',
			);
			foreach ( $themes as $theme ) {
				if ( isset( $theme['name'] ) && 'arcane' === strtolower( $theme['name'] ) ) {
					$latest_arcane = $theme;
					break;
				}
			}

			if ( version_compare( $current_arcane_version, $current_arcane_version, '<' ) ) {
				$transient->response[ $latest_arcane['name'] ] = array(
					'theme'       => $latest_arcane['name'],
					'new_version' => $latest_arcane['version'],
					'url'         => 'https://themeforest.net/item/arcane-the-gaming-community-theme/17400106',
					'package'     => $this->deferred_download( $latest_arcane['id'] ),
				);
			}
		}

		return $transient;
	}

	/**
	 * Disables requests to the wp.org repository for Arcane.
	 *
	 * @since 5.0.0
	 *
	 * @param array  $request An array of HTTP request arguments.
	 * @param string $url The request URL.
	 * @return array
	 */
	public function update_check( $request, $url ) {

		// Theme update request.
		if ( false !== strpos( $url, '//api.wordpress.org/themes/update-check/1.1/' ) ) {

			// Decode JSON so we can manipulate the array.
			$data = json_decode( $request['body']['themes'] );

			// Remove Arcane.
			unset( $data->themes->Arcane );

			// Encode back into JSON and update the response.
			$request['body']['themes'] = wp_json_encode( $data );
		}
		return $request;
	}

	/**
	 * Defers building the API download url until the last responsible moment to limit file requests.
	 *
	 * Filter the package options before running an update.
	 *
	 * @since 5.0.0
	 *
	 * @param array $options {
	 *     Options used by the upgrader.
	 *
	 *     @type string $package                     Package for update.
	 *     @type string $destination                 Update location.
	 *     @type bool   $clear_destination           Clear the destination resource.
	 *     @type bool   $clear_working               Clear the working resource.
	 *     @type bool   $abort_if_destination_exists Abort if the Destination directory exists.
	 *     @type bool   $is_multi                    Whether the upgrader is running multiple times.
	 *     @type array  $hook_extra                  Extra hook arguments.
	 * }
	 */
	public function maybe_deferred_download( $options ) {
		$package = $options['package'];
		if ( false !== strrpos( $package, 'deferred_download' ) && false !== strrpos( $package, 'item_id' ) ) {
			parse_str( wp_parse_url( $package, PHP_URL_QUERY ), $vars );
			if ( $vars['item_id'] ) {
				$args = $this->set_bearer_args();
				$options['package'] = $this->download( $vars['item_id'], $args );
			}
		}
		return $options;
	}

	/**
	 * Returns the bearer arguments for a request with a single use API Token.
	 *
	 * @since 5.0.0
	 * @return array
	 */
	public function set_bearer_args() {
		$args = array();
		$token = $this->registration->get_token();
		if ( ! empty( $token ) ) {
			$args = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $token,
					'User-Agent'    => 'WordPress - Arcane',
				),
				'timeout' => 20,
			);
		}
		return $args;
	}


}
