<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<div <?php wc_product_class( array('vs-product'), $product ); ?>>
	<?php
		echo '<div class="product-img">';
			if( has_post_thumbnail() ){
				echo '<a href="'.esc_url( get_the_permalink() ).'">';
					echo zocker_img_tag( array(
						'url'	=> esc_url( get_the_post_thumbnail_url() ),
						'class'	=> 'w-100',
					) );
				echo '</a>';
			}
			if( $product->is_on_sale() && $product->get_type() == 'simple' ) {
	            echo '<div class="onsale label">'.esc_html__( 'Sale', 'zocker' ).'</div>';
	        }
	        if( $product->is_featured() ) {
	            echo '<div class="featured woocommerce-badge label">'.esc_html__( 'Hot', 'zocker' ).'</div>';
	        }
	        if( ! $product->is_in_stock() ) {
	            echo '<div class="outofstock woocommerce-badge label">'.esc_html__( 'Stock Out', 'zocker' ).'</div>';
	        }
		echo '</div>';
		
		echo '<div class="product-content">';
			if( get_the_title() ){
				echo '<a class="category" href="'.esc_url( get_the_permalink() ).'">'.esc_html( get_the_title() ).'</a>';
			}
			// Product Price
			if( $product->get_type() == 'simple' ) {
				$rprice = $product->get_price_html();
				echo '<span class="price h1 text-white mb-0">'.$rprice.'</span>';
			}
		echo '</div>';
	?>
</div>