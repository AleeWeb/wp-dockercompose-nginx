<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$zocker_woo_relproduct_display = zocker_opt('zocker_woo_relproduct_display');

if ( $related_products && $zocker_woo_relproduct_display) : ?>

	<section class="related-products vs-product-layout2 link-inherit mt-50">

        <?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', esc_html__( 'Related products', 'zocker' ) );

		if ( $heading ) :
			?>
            <h3 class="mb-lg-35">
			    <?php echo esc_html( $heading ); ?>
            </h3>
		<?php endif;?>

		<div class="row vs-carousel" data-slidetoshow="3" data-mdslidetoshow="2" data-smslidetoshow="1" data-xsslidetoshow="1" >
        <?php
            if( class_exists('ReduxFramework') ) {
                $zocker_woo_related_product_col = zocker_opt('zocker_woo_related_product_col');
            } else{
                $zocker_woo_related_product_col = '4';
            }
        ?>

			<?php foreach ( $related_products as $related_product ) : ?>
                <div class="col-md-6 col-lg-4">
                    <?php
                        $post_object = get_post( $related_product->get_id() );

                        setup_postdata( $GLOBALS['post'] =& $post_object );

                        wc_get_template_part( 'content', 'product' );
                    ?>
                </div>

			<?php endforeach; ?>

		</div>

	</section>

<?php endif;

wp_reset_postdata();