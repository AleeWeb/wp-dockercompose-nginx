<?php
/**
 * Widget Name: Info widget
 * Description: Widget with logo, text and social icons
 * Version: 1.0
 */

class Arcane_Info_Widget extends WP_Widget {

	function __construct() {
		parent::__construct( false, $name = esc_html__( 'SW Info Widget', 'arcane' ) );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$text  = $instance['text'];
		$image = $instance['image_uri'];
		$facebook = $instance['facebook'];
		$twitter = $instance['twitter'];
		$vimeo = $instance['vimeo'];
		$dribbble = $instance['dribbble'];
		$pinterest = $instance['pinterest'];
		$youtube = $instance['youtube'];
		$rss = $instance['rss'];
		$steam = $instance['steam'];
		$instagram = $instance['instagram'];
		$twitch = $instance['twitch'];
		$discord = $instance['discord'];


		$arcane_allowed = wp_kses_allowed_html( 'post' );
		echo wp_kses( $before_widget, $arcane_allowed );
		?>
        <div class="widget_info">
			<?php if ( ! empty( $image ) ) { ?>
                <img alt="widget-img" src="<?php echo esc_url( $image ); ?>">
			<?php } ?>
            <p>
				<?php echo wp_kses_post( $text ); ?>
            </p>
            <div class="social-top">
                <?php if(!empty($facebook)){ ?>
                <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($facebook); ?>">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <?php } ?>

	            <?php if(!empty($twitter)){ ?>
                    <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($twitter); ?>">
                        <i class="fab fa-twitter"></i>
                    </a>
	            <?php } ?>

	            <?php if(!empty($vimeo)){ ?>
                    <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($vimeo); ?>">
                        <i class="fab fa-vimeo-v"></i>
                    </a>
	            <?php } ?>

	            <?php if(!empty($dribbble)){ ?>
                    <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($dribbble); ?>">
                        <i class="fab fa-dribbble"></i>
                    </a>
	            <?php } ?>

	            <?php if(!empty($pinterest)){ ?>
                    <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($pinterest); ?>">
                        <i class="fab fa-pinterest"></i>
                    </a>
	            <?php } ?>

	            <?php if(!empty($youtube)){ ?>
                    <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($youtube); ?>">
                        <i class="fab fa-youtube"></i>
                    </a>
	            <?php } ?>

	            <?php if(!empty($rss)){ ?>
                    <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($rss); ?>">
                        <i class="fas fa-rss"></i>
                    </a>
	            <?php } ?>

	            <?php if(!empty($steam)){ ?>
                    <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($steam); ?>">
                        <i class="fab fa-steam-symbol"></i>
                    </a>
	            <?php } ?>

	            <?php if(!empty($instagram)){ ?>
                    <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($instagram); ?>">
                        <i class="fab fa-instagram"></i>
                    </a>
	            <?php } ?>

	            <?php if(!empty($twitch)){ ?>
                    <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($twitch); ?>">
                        <i class="fab fa-twitch"></i>
                    </a>
	            <?php } ?>

	            <?php if(!empty($discord)){ ?>
                    <a rel="nofollow noopener noreferrer" target="_blank" href="<?php echo esc_url($discord); ?>">
                        <i class="fab fa-discord"></i>
                    </a>
	            <?php } ?>
            </div>
        </div>
		<?php
		echo wp_kses( $after_widget, $arcane_allowed );

	}

	/** @param $new_instance
	 * @param $old_instance
	 *
	 * @return array
	 * @see WP_Widget::update
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['text']      = strip_tags( $new_instance['text'] );
		$instance['image_uri'] = strip_tags( $new_instance['image_uri'] );
		$instance['facebook']  = strip_tags( $new_instance['facebook'] );
		$instance['twitter']   = strip_tags( $new_instance['twitter'] );
		$instance['vimeo']     = strip_tags( $new_instance['vimeo'] );
		$instance['dribbble']  = strip_tags( $new_instance['dribbble'] );
		$instance['pinterest'] = strip_tags( $new_instance['pinterest'] );
		$instance['youtube']   = strip_tags( $new_instance['youtube'] );
		$instance['rss']       = strip_tags( $new_instance['rss'] );
		$instance['steam']     = strip_tags( $new_instance['steam'] );
		$instance['instagram'] = strip_tags( $new_instance['instagram'] );
		$instance['twitch']    = strip_tags( $new_instance['twitch'] );
		$instance['discord']   = strip_tags( $new_instance['discord'] );


		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		$defaults = [
			'text'      => '',
			'image_uri' => '',
			'facebook'  => '',
			'twitter'   => '',
			'vimeo'     => '',
			'dribbble'  => '',
			'pinterest' => '',
			'youtube'   => '',
			'rss'       => '',
			'steam'     => '',
			'instagram' => '',
			'twitch'    => '',
			'discord'   => '',

		];
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text:', 'arcane' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"
                      name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo wp_kses_post( $instance['text'] ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>"><?php esc_html_e( 'Image:', 'arcane' ); ?></label>
            <img class="<?php echo esc_attr( $this->id ); ?>_img"
                 src="<?php echo esc_url( $instance['image_uri'] ); ?>"/>
            <input type="text" class="widefat <?php echo esc_attr( $this->id ); ?>_url"
                   name="<?php echo esc_attr( $this->get_field_name( 'image_uri' ) ); ?>"
                   value="<?php echo esc_url( $instance['image_uri'] ); ?>"/>
            <input type="button"
                   id="<?php echo esc_attr( $this->id ); ?>"
                   class="button button-primary js_custom_upload_media"
                   value="Upload Image"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>"
                   value="<?php echo esc_url( $instance['facebook'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>"
                   value="<?php echo esc_url( $instance['twitter'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'vimeo' ) ); ?>"><?php esc_html_e( 'Vimeo:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'vimeo' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'vimeo' ) ); ?>"
                   value="<?php echo esc_url( $instance['vimeo'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'dribbble' ) ); ?>"><?php esc_html_e( 'Dribbble:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dribbble' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'dribbble' ) ); ?>"
                   value="<?php echo esc_url( $instance['dribbble'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>"><?php esc_html_e( 'Pinterest:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'pinterest' ) ); ?>"
                   value="<?php echo esc_url( $instance['pinterest'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'Youtube:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>"
                   value="<?php echo esc_url( $instance['youtube'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'rss' ) ); ?>"><?php esc_html_e( 'RSS:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'rss' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'rss' ) ); ?>"
                   value="<?php echo esc_url( $instance['rss'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'steam' ) ); ?>"><?php esc_html_e( 'Steam:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'steam' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'steam' ) ); ?>"
                   value="<?php echo esc_url( $instance['steam'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>"
                   value="<?php echo esc_url( $instance['instagram'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitch' ) ); ?>"><?php esc_html_e( 'Twitch:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitch' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'twitch' ) ); ?>"
                   value="<?php echo esc_url( $instance['twitch'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'discord' ) ); ?>"><?php esc_html_e( 'Discord:', 'arcane' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'discord' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'discord' ) ); ?>"
                   value="<?php echo esc_url( $instance['discord'] ); ?>"/>
        </p>

        <script>
            jQuery(document).ready(function ($) {
                function media_upload(button_selector) {
                    let _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                    $('body').on('click', button_selector, function () {
                        let button_id = $(this).attr('id');
                        wp.media.editor.send.attachment = function (props, attachment) {
                            if (_custom_media) {
                                $('.' + button_id + '_img').attr('src', attachment.url);
                                $('.' + button_id + '_url').val(attachment.url).trigger('change');
                            } else {
                                return _orig_send_attachment.apply($('#' + button_id), [props, attachment]);
                            }
                        }
                        wp.media.editor.open($('#' + button_id));
                        return false;
                    });
                }

                media_upload('.js_custom_upload_media');
            });
        </script>
		<?php
	}

}


function arcane_return_info_widget() {
	return register_widget( "Arcane_Info_Widget" );
}

// register widget
add_action( 'widgets_init', 'arcane_return_info_widget' );