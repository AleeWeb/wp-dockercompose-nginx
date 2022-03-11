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
        echo '<div class="media pt-40 align-items-stretch d-block d-lg-flex">';
            echo '<div class="media-thumb">';
                if( $product->get_type() == 'simple' ) {
                    $rprice = $product->get_price_html();
                    echo '<strong class="food-price bg-theme text-white text-md">'.$rprice.'</strong>';
                }
                if( has_post_thumbnail() ){
    				echo '<a href="'.esc_url( get_the_permalink() ).'">';
    					the_post_thumbnail( 'product-image-120' );
    				echo '</a>';
    			}
            echo '</div>';
            echo '<div class="media-body px-lg-30 d-flex align-items-center">';
                echo '<div class="food-content w-100">';
                    if( get_the_title() ){
        				echo '<h3 class="food-title text-lg mb-0"><a href="'.esc_url( get_the_permalink() ).'">'.esc_html( get_the_title() ).'</a></h3>';
        			}
                    if( ! empty( zocker_meta( 'product_extra_info' ) ) ){
        				echo '<p class="food-text mb-0 text-xs">'.esc_html( zocker_meta( 'product_extra_info' ) ).'</p>';
        			}
                    echo '<span class="food-rating-icon text-theme text-md"><i class="fas fa-star"></i></span>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';