<?php
/**
 * @Packge     : Zocker
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// Block direct access
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

echo '<!-- Single Post -->';
?>
<div <?php post_class(); ?> >
<?php

    // Blog Post Thumbnail
    do_action( 'zocker_blog_post_thumb' );
    // Blog Post Meta
    do_action( 'zocker_blog_post_meta' );
    // Excerpt And Read More Button
    do_action( 'zocker_blog_postexcerpt_read_content' );

echo '</div>';
echo '<!-- End Single Post -->';