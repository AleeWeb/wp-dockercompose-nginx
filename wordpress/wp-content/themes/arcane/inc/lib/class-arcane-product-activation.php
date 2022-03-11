<?php
/**
 * Registration handler.
 *
 * @since 1.0.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * A class to handle everything related to product registration
 *
 * @since 1.0.0
 */
class Arcane_Product_Registration {

	/**
	 * The option name.
	 *
	 * @access private
	 * @since 1.0.0
	 * @var string
	 */
	private $option_name = 'arcane_registration';

	/**
	 * The product-name converted to ID.
	 *
	 * @access private
	 * @since 1.0.0
	 * @var string
	 */
	private $product_id = 'Arcane';

	/**
	 * Updater
	 *
	 * @access private
	 * @since 1.0.0
	 * @var null|object Arcane_Updater.
	 */
	private $updater = null;

	/**
	 * An instance of the Arcane_Envato_API class.
	 *
	 * @access private
	 * @since 1.0.0
	 * @var null|object Arcane_Envato_API.
	 */
	private $envato_api = null;

	/**
	 * Envato API response as WP_Error object.
	 *
	 * @access private
	 * @since 1.7
	 * @var null|object WP_Error.
	 */
	private $envato_api_error = null;

	/**
	 * The class constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp_ajax_process_activation', array( $this, 'check_registration' ) );
		add_action( 'wp_ajax_nopriv_process_activation', array( $this, 'check_registration' ) );

		// Instantiate the updater.
		if ( null === $this->updater ) {
			$this->updater = new Arcane_Updater( $this );
		}
	}

	/**
	 * Gets the arguments.
	 *
	 * @access public
	 * @return array
	 * @since 1.0.0
	 */
	public function get_args() {

		return [ 'type' => 'theme', 'name' => 'Arcane' ];
	}


	/**
	 * Returns the current token.
	 *
	 * @access public
	 *
	 * @param string $product_id The product-ID.
	 *
	 * @return string The current token.
	 * @since 1.0.0
	 */
	public function get_token( $product_id = '' ) {
		if ( '' === $product_id ) {
			$product_id = $this->product_id;
		}

		$option = get_option( $this->option_name );
		if ( ! empty( $option ) && is_array( $option ) && isset( $option[ $product_id ] ) && isset( $option[ $product_id ]['token'] ) ) {
			return $option[ $product_id ]['token'];
		}

		return '';
	}

	/**
	 * Envato API class.
	 *
	 * @access public
	 * @return Arcane_Envato_API
	 * @since 1.0.0
	 */
	public function envato_api() {

		if ( null === $this->envato_api ) {
			$this->envato_api = new Arcane_Envato_API( $this );
		}

		return $this->envato_api;
	}

	/**
	 * Checks if the product is part of the themes or plugins
	 * purchased by the user belonging to the token.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function check_registration() {


		// Sanity check. No need to do anything if we're not saving the form.
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'process_activation' && isset( $_POST['_wpnonce'] ) ) {

			// Security check.
			check_admin_referer( $this->option_name . '_' . $this->product_id );

            // Get the saved products.
            $saved_products = get_option( $this->option_name, array() );

            // Get the arcane_registered option.
            $registered = get_option( 'arcane_registered' );

            // The new token.
            $token = sanitize_text_field( wp_unslash( $_POST['token'] ) );
            $token = trim( $token );

            // If token field is empty, copy is not registered.
            $registered[ $this->product_id ] = false;
            if ( ! empty( $token ) && 32 === strlen( $token ) ) {

                // Check if new token is valid.
                $registered[ $this->product_id ] = $this->product_exists( $token );
            }

            // Update saved product option.
            $saved_products[ $this->product_id ] = array(
                'token' => $token,
            );

            update_option( $this->option_name, $saved_products );

            // Check the token scopes and update the option accordingly.
            $registered['scopes'][ $this->product_id ] = $this->envato_api()->get_token_scopes( $saved_products[ $this->product_id ]['token'] );

            // Update the 'arcane_registered' option.
            update_option( 'arcane_registered', $registered );

		}

		wp_die();
	}

	/**
	 * Checks if the product is part of the themes or plugins
	 * purchased by the user belonging to the token.
	 *
	 * @access private
	 *
	 * @param string $token A token to check.
	 * @param string $page The page number if one is necessary.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	private function product_exists( $token = '', $page = '' ) {

		// Set the new token for the API call.
		if ( '' !== $token ) {
			$this->envato_api()->set_token( $token );
		}

		$products = $this->envato_api()->themes( array(), $page );

		// If a WP Error object is returned we need to check if API is down.
		if ( is_wp_error( $products ) ) {

			// 401 ( unauthorized ) and 403 ( forbidden ) mean the token is invalid, apart from that Envato API is down.
			if ( 401 !== $products->get_error_code() && 403 !== $products->get_error_code() && '' !== $products->get_error_message() ) {
				set_site_transient( 'arcane_envato_api_down', true, 600 );
			}

			$this->envato_api_error = $products;

			return false;
		}

		// Check iv product is part of the purchased themes/plugins.
		foreach ( $products as $product ) {
			if ( isset( $product['name'] ) ) {
				if ( 'Arcane' === $product['name'] ) {
					return true;
				}
			}
		}

		if ( 100 === count( $products ) ) {
			$page = ( ! $page ) ? 2 : $page + 1;

			return $this->product_exists( '', $page );
		}

		return false;
	}

	/**
	 * Has user associated with current token purchased this product?
	 *
	 * @access public
	 * @return bool
	 * @since 1.0.0
	 */
	public function is_registered() {

		// Get the option.
		$registered = get_option( 'arcane_registered' );

		// Is the product registered?
		if ( isset( $registered[ $this->product_id ] ) && true === $registered[ $this->product_id ] ) {
			return true;
		}

		// Is the Envato API down?
		if ( get_site_transient( 'arcane_envato_api_down' ) ) {
			return true;
		}

		// Fallback to false.
		return false;
	}


	/**
	 * Prints the registration form.
	 *
	 * @access public
	 * @return void
	 * @since 1.0.0
	 */
	public function the_form() {

		// Print styles.
		$this->form_styles();

		// Print scripts.
		$this->form_scripts();


		// Get the current token.
		$token = $this->get_token( $this->product_id );

		// Is the product registered?
		$is_registered = $this->is_registered();

		// Get the arcane_registered option.
		$registered = get_option( 'arcane_registered' );
		if ( ! isset( $registered['scopes'] ) ) {
			$registered['scopes'] = array();
		}

		?>
        <div class="  registration-form-container">
			<?php if ( $is_registered ) : ?>
                <p class="about-description"><?php esc_attr_e( 'Congratulations! Thank you for registering your product.', 'arcane' ); ?></p>
			<?php else : ?>
                <p class="about-description"><?php esc_attr_e( 'Please enter your Envato token or Skywarrior purchase code to complete registration.', 'arcane' ); ?></p>
			<?php endif; ?>
            <div class="arcane-library-registration-form">
                <div id="arcane-library_product_registration" method="post">
					<?php $show_form = true; ?>
					<?php $invalid_token = false; ?>
					<?php if ( $token ) : ?>
						<?php if ( $is_registered ) : ?>
                            <span class="dashicons dashicons-yes arcane-library-icon-key<?php echo ( ! $show_form ) ? ' toggle-hidden hidden' : ''; ?>"></span>
						<?php else : ?>
							<?php $invalid_token = true; ?>
                            <span class="dashicons dashicons-no arcane-library-icon-key<?php echo ( ! $show_form ) ? ' toggle-hidden hidden' : ''; ?>"></span>
						<?php endif; ?>
					<?php else : ?>
                        <span class="dashicons dashicons-admin-network arcane-library-icon-key<?php echo ( ! $show_form ) ? ' toggle-hidden hidden' : ''; ?>"></span>
					<?php endif; ?>
                    <input <?php echo ( ! $show_form ) ? 'class="toggle-hidden hidden" ' : ''; ?>type="text"
                           name="<?php echo esc_attr( "{$this->option_name}[{$this->product_id}][token]" ); ?>"
                           class="activation-token"
                           value="<?php echo esc_attr( $token ); ?>"/>

					<?php wp_nonce_field( $this->option_name . '_' . $this->product_id ); ?>
					<?php
					$button_classes = array(
						'primary',
						'large',
						'arcane-library-large-button',
						'arcane-library-register'
					);
					if ( ! $show_form ) {
						$button_classes[] = 'toggle-hidden';
						$button_classes[] = 'hidden';
					}
					?>
                   <button class="activation-submit button media_upload_button"><?php esc_html_e('Submit', 'arcane'); ?></button>
                </div>

				<?php if ( $invalid_token ) : ?>
                    <p class="error-invalid-token">
						<?php if ( 36 === strlen( $token ) && 4 === substr_count( $token, '-' ) ) : ?>
							<?php esc_html_e( 'Registration could not be completed because the value entered above is a purchase code. A token key is needed to register. Please read the directions below to find out how to create a token key to complete registration.', 'arcane' ); ?>
						<?php elseif ( $this->envato_api_error ) : ?>
							<?php $error_code = $this->envato_api_error->get_error_code(); ?>
							<?php $error_message = str_replace( array(
								'Unauthorized',
								'Forbidden'
							), '', $this->envato_api_error->get_error_message() ); ?>
							<?php /* translators: The server error code and the error message. */ ?>
							<?php printf( esc_html__( 'Invalid token, the server responded with code %1$s.%2$s', 'arcane' ), esc_html( $error_code ), esc_html( $error_message ) ); ?>
						<?php else : ?>
							<?php /* translators: The product name for the license. */ ?>
							<?php printf( esc_html__( 'Invalid token, or corresponding Envato account does not have %s purchased.', 'arcane' ), esc_html__('Arcane', 'arcane' ) ); ?>
						<?php endif; ?>
                    </p>
				<?php elseif ( $token ) : ?>
					<?php
					// If the token scopes don't exist, make sure we create them and save them.
					if ( ! isset( $registered['scopes'][ $this->product_id ] ) || ! is_array( $registered['scopes'] ) ) {
						$registered['scopes'][ $this->product_id ] = $this->envato_api()->get_token_scopes();
						update_option( 'arcane_registered', $registered );
					}
					$scopes_ok = $this->envato_api()->check_token_scopes( $registered['scopes'][ $this->product_id ] );
					?>
					<?php if ( ! $scopes_ok ) : ?>
                        <p class="error-invalid-token">
							<?php _e( 'Token does not have the necessary permissions. Please create a new token and make sure the following permissions are enabled for it: <strong>View the user\'s Envato Account username</strong>, <strong>Download the items the user has purchased</strong>, <strong>Verify purchases of the user\'s items</strong>, <strong>List purchases the user has made</strong>.', 'arcane' ); // WPCS: XSS ok. ?>
                        </p>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( ! $is_registered ) : ?>

                    <div
						<?php echo ( ! $show_form ) ? 'class="toggle-hidden hidden" ' : ''; ?>style="font-size:17px;line-height:27px;margin-top:1em;padding-top:1em">
                        <hr>

                        <h3><?php esc_attr_e( 'Instructions For Generating A Token', 'arcane' ); ?></h3>
                        <ol>
                            <li>
								<?php
								printf( // WPCS: XSS ok.
								/* translators: "Generate A Personal Token" link. */
									__( 'Click on this %1$s link. <strong>IMPORTANT:</strong> You must be logged into the same Themeforest account that purchased %2$s. If you are logged in already, look in the top menu bar to ensure it is the right account. If you are not logged in, you will be directed to login then directed back to the Create A Token Page.', 'arcane' ), // WPCS: XSS ok.
									'<a href="https://build.envato.com/create-token/?user:username=t&purchase:download=t&purchase:verify=t&purchase:list=t" target="_blank">' . esc_html__( 'Generate A Personal Token', 'arcane' ) . '</a>',
									esc_html( 'Arcane' )
								);
								?>
                            </li>
                            <li>
								<?php
								_e( 'Enter a name for your token, then check the boxes for <strong>View Your Envato Account Username, Download Your Purchased Items, List Purchases You\'ve Made</strong> and <strong>Verify Purchases You\'ve Made</strong> from the permissions needed section. Check the box to agree to the terms and conditions, then click the <strong>Create Token button</strong>', 'arcane' ); // WPCS: XSS ok.
								?>
                            </li>
                            <li>
								<?php
								_e( 'A new page will load with a token number in a box. Copy the token number then come back to this registration page and paste it into the field below and click the <strong>Submit</strong> button.', 'arcane' ); // WPCS: XSS ok.
								?>
                            </li>
                        </ol>

                    </div>

				<?php endif; ?>
            </div>
        </div>
		<?php

	}

	/**
	 * Print styles for the form.
	 *
	 * @access private
	 * @return void
	 * @since 1.0.0
	 */
	private function form_styles() {
		?>
        <style>
            .registration-form-container {
                float: left;
                width: 95%;
                margin-bottom: 0;
            }

            #arcane-library_product_registration {
                display: -webkit-flex;
                display: -ms-flexbox;
                display: flex;
                flex-wrap: wrap;

                -webkit-align-items: center;
                -ms-align-items: center;
                align-items: center;
            }

            .arcane-library-registration-form input[type="text"]{
                margin: 0 1em;
                padding: 10px 15px;
                width: calc(100% - 2em - 180px);
                height: 40px;
            }


            #arcane-library_product_registration .dashicons {
                margin: 0;
                color: #333333;
                width: 30px;
            }

            #arcane-library_product_registration .dashicons-yes {
                color: #43A047;
            }

            #arcane-library_product_registration .dashicons-no {
                color: #c00;
            }

        </style>
		<?php
	}


	/**
	 * Print scripts for the form.
	 *
	 * @access private
	 * @return void
	 * @since 1.0.0
	 */
	private function form_scripts() {
		?>
        <script>
            let reduxForm = jQuery('.redux-form-wrapper');

            reduxForm.submit(function(e) {
                e.preventDefault();
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>",
                    data: {"action": "process_activation", "website": jQuery('.website-key').is(':checked'), "token": jQuery('.activation-token').val(), "_wpnonce" : jQuery('#arcane-library_product_registration #_wpnonce').val() },
                    success: function(data) {
                      location.reload();
                    }
                });
            });
        </script>
		<?php
	}
}


$Arcane_Product_Registration = new Arcane_Product_Registration();
$is_registered = $Arcane_Product_Registration->is_registered();
if(!$is_registered && function_exists('deactivate_plugins'))
deactivate_plugins(  WP_PLUGIN_DIR . '/arcane_custom_post_types/arcane_custom_post_types.php');
/* Omit closing PHP tag to avoid "Headers already sent" issues. */
