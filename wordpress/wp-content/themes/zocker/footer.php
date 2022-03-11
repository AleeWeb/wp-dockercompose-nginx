<?php
/**
 * @Packge     : Zocker
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
    /**
    *
    * Hook for Footer Content
    *
    * Hook zocker_footer_content
    *
    * @Hooked zocker_footer_content_cb 10
    *
    */
    do_action( 'zocker_footer_content' );

    if( !is_404(  ) ) {
        /**
        *
        * Hook for Back to Top Button
        *
        * Hook zocker_back_to_top
        *
        * @Hooked zocker_back_to_top_cb 10
        *
        */
        do_action( 'zocker_back_to_top' );
    }

    wp_footer();
    ?>
</body>
</html>