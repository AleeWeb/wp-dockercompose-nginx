<?php
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit();
}
/**
 * @Packge     : Zocker
 * @Version    : 1.0
 * @Author     : Vecurosoft
 * @Author URI : https://www.vecurosoft.com/
 *
 */

// zocker gallery image size hook functions
add_filter('woocommerce_gallery_image_size','zocker_woocommerce_gallery_image_size');
function zocker_woocommerce_gallery_image_size( $imagesize ) {
    $imagesize = 'zocker-shop-single';
    return $imagesize;
}

// zocker shop main content hook functions
if( !function_exists('zocker_shop_main_content_cb') ) {
    function zocker_shop_main_content_cb( ) {

        if( is_shop() || is_product_category() || is_product_tag() ) {
            echo '<section class="vs-product-wrapper vs-product-layout2 space-top newsletter-pb">';
            if( class_exists('ReduxFramework') ) {
                $zocker_woo_product_col = zocker_opt('zocker_woo_product_col');
                if( $zocker_woo_product_col == '2' ) {
                    echo '<div class="container">';
                } elseif( $zocker_woo_product_col == '3' ) {
                    echo '<div class="container">';
                } elseif( $zocker_woo_product_col == '4' ) {
                    echo '<div class="container">';
                } elseif( $zocker_woo_product_col == '5' ) {
                    echo '<div class="zocker-container">';
                } elseif( $zocker_woo_product_col == '6' ) {
                    echo '<div class="zocker-container">';
                }
            } else {
                echo '<div class="container">';
            }
        } else {
            echo '<section class="vs-product-wrapper vs-product-layout2 shop-details space-top newsletter-pb">';
                echo '<div class="container">';
        }
            echo '<div class="row gutters-40">';
    }
}

// zocker shop main content hook function
if( !function_exists('zocker_shop_main_content_end_cb') ) {
    function zocker_shop_main_content_end_cb( ) {
            echo '</div>';
        echo '</div>';
    echo '</section>';
    }
}

// shop column start hook function
if( !function_exists('zocker_shop_col_start_cb') ) {
    function zocker_shop_col_start_cb( ) {
        if( class_exists('ReduxFramework') ) {
            if( class_exists('woocommerce') && is_shop() ) {
                $zocker_woo_shoppage_sidebar = zocker_opt('zocker_woo_shoppage_sidebar');
                if( $zocker_woo_shoppage_sidebar == '2' && is_active_sidebar('zocker-woo-sidebar') ) {
                    echo '<div class="col-lg-8 col-xl-9 order-last">';
                } elseif( $zocker_woo_shoppage_sidebar == '3' && is_active_sidebar('zocker-woo-sidebar') ) {
                    echo '<div class="col-lg-8 col-xl-9">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            } else {
                echo '<div class="col-lg-12">';
            }
        } else {
            if( class_exists('woocommerce') && is_shop() ) {
                if( is_active_sidebar('zocker-woo-sidebar') ) {
                    echo '<div class="col-lg-8 col-xl-9">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            } else {
                echo '<div class="col-lg-12">';
            }
        }

    }
}

// shop column end hook function
if( !function_exists('zocker_shop_col_end_cb') ) {
    function zocker_shop_col_end_cb( ) {
        echo '</div>';
    }
}

// zocker woocommerce pagination hook function
if( ! function_exists('zocker_woocommerce_pagination') ) {
    function zocker_woocommerce_pagination( ) {
        if( ! empty( zocker_pagination() ) ) {
            echo '<div class="row">';
                echo '<div class="col-12">';
                    echo '<div class="pagination-wrapper pagination-layout1 list-style-none pt-lg-30 mb-30">';
                        echo '<ul>';
                            $prev 	= '<i class="fas fa-chevron-left"></i>';
                            $next 	= '<i class="fas fa-chevron-right"></i>';
                            // previous
                            if( get_previous_posts_link() ){
                                echo '<li>';
                                previous_posts_link( $prev );
                                echo '</li>';
                            }
                            echo zocker_pagination();
                            // next
                            if( get_next_posts_link() ){
                                echo '<li>';
                                next_posts_link( $next );
                                echo '</li>';
                            }
                        echo '</ul>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
    }
}
// woocommerce filter wrapper hook function
if( ! function_exists('zocker_woocommerce_filter_wrapper') ) {
    function zocker_woocommerce_filter_wrapper( ) {
        echo '<div class="row">';
            echo '<div class="col-xl-5">';
                echo '<p class="mb-0">'.woocommerce_result_count().'</p>';
            echo '</div>';
            echo '<div class="col-xl-7 mt-3 mt-xl-0">';
                echo '<div class="sort-btn d-flex flex-wrap justify-content-between justify-content-xl-end align-items-start">';
                    echo woocommerce_catalog_ordering();
                    echo '<div class="d-flex mt-3 mt-sm-0">';
                        echo '<div class="nav" role="tablist">';
                            echo '<a href="#" class="icon-btn3 active" id="tab-shop-grid" data-bs-toggle="tab" data-bs-target="#tab-grid" role="tab" aria-controls="tab-grid" aria-selected="true"><i class="far fa-border-all"></i></a>';
                            echo '<a href="#" class="icon-btn3" id="tab-shop-list" data-bs-toggle="tab" data-bs-target="#tab-list" role="tab" aria-controls="tab-grid" aria-selected="false"><i class="fas fa-list"></i></a>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
}

// woocommerce tab content wrapper start hook function
if( ! function_exists('zocker_woocommerce_tab_content_wrapper_start') ) {
    function zocker_woocommerce_tab_content_wrapper_start( ) {
        echo '<!-- Tab Content -->';
        echo '<div class="tab-content" id="nav-tabContent">';
    }
}

// woocommerce tab content wrapper start hook function
if( ! function_exists('zocker_woocommerce_tab_content_wrapper_end') ) {
    function zocker_woocommerce_tab_content_wrapper_end( ) {
        echo '</div>';
        echo '<!-- End Tab Content -->';
    }
}
// zocker grid tab content hook function
if( !function_exists('zocker_grid_tab_content_cb') ) {
    function zocker_grid_tab_content_cb( ) {
        echo '<!-- Grid -->';
            echo '<div class="tab-pane fade show active" id="tab-grid" role="tabpanel" aria-labelledby="tab-shop-grid">';
                woocommerce_product_loop_start();
                if( class_exists('ReduxFramework') ) {
                    $zocker_woo_product_col = zocker_opt('zocker_woo_product_col');
                    if( $zocker_woo_product_col == '2' ) {
                        $zocker_woo_product_col_val = 'col-lg-6 col-sm-6';
                    } elseif( $zocker_woo_product_col == '3' ) {
                        $zocker_woo_product_col_val = 'col-lg-4 col-sm-6';
                    } elseif( $zocker_woo_product_col == '4' ) {
                        $zocker_woo_product_col_val = 'col-lg-3 col-sm-6';
                    }elseif( $zocker_woo_product_col == '5' ) {
                        $zocker_woo_product_col_val = 'col-xl col-lg-4 col-sm-6';
                    } elseif( $zocker_woo_product_col == '6' ) {
                        $zocker_woo_product_col_val = 'col-lg-2 col-sm-6';
                    }
                } else {
                    $zocker_woo_product_col_val = 'col-lg-4 col-sm-6';
                }

                if ( wc_get_loop_prop( 'total' ) ) {
                    while ( have_posts() ) {
                        the_post();

                        echo '<div class="'.esc_attr( $zocker_woo_product_col_val ).'">';
                            /**
                             * Hook: woocommerce_shop_loop.
                             */
                            do_action( 'woocommerce_shop_loop' );

                            wc_get_template_part( 'content', 'product' );

                        echo '</div>';
                    }
                    wp_reset_postdata();
                }

                woocommerce_product_loop_end();
            echo '</div>';
        echo '<!-- End Grid -->';
    }
}

// zocker list tab content hook function
if( !function_exists('zocker_list_tab_content_cb') ) {
    function zocker_list_tab_content_cb( ) {
        echo '<!-- List -->';
        echo '<div class="tab-pane fade" id="tab-list" role="tabpanel" aria-labelledby="tab-shop-list">';
            woocommerce_product_loop_start();

            if ( wc_get_loop_prop( 'total' ) ) {
                while ( have_posts() ) {
                    the_post();
                    echo '<div class="col-sm-6 col-lg-6 col-xl-6">';
                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action( 'woocommerce_shop_loop' );

                        wc_get_template_part( 'content-horizontal', 'product' );
                    echo '</div>';
                }
                wp_reset_postdata();
            }

            woocommerce_product_loop_end();
        echo '</div>';
        echo '<!-- End List -->';
    }
}

// zocker woocommerce get sidebar hook function
if( ! function_exists('zocker_woocommerce_get_sidebar') ) {
    function zocker_woocommerce_get_sidebar( ) {
        if( class_exists('ReduxFramework') ) {
            $zocker_woo_shoppage_sidebar = zocker_opt('zocker_woo_shoppage_sidebar');
        } else {
            if( is_active_sidebar('zocker-woo-sidebar') ) {
                $zocker_woo_shoppage_sidebar = '2';
            } else {
                $zocker_woo_shoppage_sidebar = '1';
            }
        }

        if( is_shop() ) {
            if( $zocker_woo_shoppage_sidebar != '1' ) {
                get_sidebar('shop');
            }
        }
    }
}

// zocker loop product thumbnail hook function
if( !function_exists('zocker_loop_product_thumbnail') ) {
    function zocker_loop_product_thumbnail( ) {
        global $product;

        if( $product->is_on_sale() && $product->get_type() == 'simple' ) {
            echo '<div class="onsale label">'.esc_html__( 'Sale', 'zocker' ).'</div>';
        }
        if( $product->is_featured() ) {
            echo '<div class="featured woocommerce-badge label">'.esc_html__( 'Hot', 'zocker' ).'</div>';
        }
        if( ! $product->is_in_stock() ) {
            echo '<div class="outofstock woocommerce-badge label">'.esc_html__( 'Stock Out', 'zocker' ).'</div>';
        }

        echo '<div class="product-img">';
            if( has_post_thumbnail() ){
                echo '<a href="'.esc_url( get_permalink() ).'">';
                    echo '<img class="w-100" src="'.esc_url( get_the_post_thumbnail_url() ).'" alt="'.esc_attr( zocker_img_default_alt(  get_the_post_thumbnail_url() )).'" >';
                echo '</a>';
            }
            echo '<div class="cart-btn-group">';
                // Cart Button
                woocommerce_template_loop_add_to_cart();
                // Quick View Button
                if( class_exists( 'WPCleverWoosq' ) ){
                    echo do_shortcode('[woosq]');
                }
                // Wishlist Button
                if( class_exists('YITH_WCWL') ) {
                    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
                }
                // Compare Button
                if( class_exists( 'WPCleverWoosc' ) ){
                    echo do_shortcode('[woosc]');
                }
            echo '</div>';
        echo '</div>';
    }
}

// shop loop product summary
if( ! function_exists('zocker_loop_product_summary') ) {
    function zocker_loop_product_summary( ) {
        global $product;
        echo '<div class="product-content">';
            // Product Rating
            woocommerce_template_loop_rating();

            // Product Title
            echo '<h3 class="product-name text-normal font-theme fs-20 lh-base mb-2"><a class="text-inherit" href="'.esc_url( get_permalink() ).'">'.esc_html( get_the_title() ).'</a></h3>';
            // Product Price
            echo woocommerce_template_loop_price();

        echo '</div>';
    }
}

// shop loop horizontal product summary
if( ! function_exists( 'zocker_horizontal_loop_product_summary' ) ) {
    function zocker_horizontal_loop_product_summary() {
        global $product;
        echo '<div class="product-content d-xl-flex align-items-center">';
            echo '<div>';
                // Product Title
                echo '<h3 class="product-name text-normal font-theme fs-20 lh-base mb-2"><a class="text-inherit" href="'.esc_url( get_permalink() ).'">'.esc_html( get_the_title() ).'</a></h3>';
                // Product Price
                echo woocommerce_template_loop_price();
                // Product Rating
                woocommerce_template_loop_rating();

            echo '</div>';
        echo '</div>';
    }
}

// before single product summary hook
if( ! function_exists('zocker_woocommerce_before_single_product_summary') ) {
    function zocker_woocommerce_before_single_product_summary( ) {

        global $post,$product;

        $attachments = $product->get_gallery_image_ids();

        if( $attachments ){
            $slider_class = "product-big-img vs-carousel slick-dots-white arrow-white";
        }else{
            $slider_class = "product-big-img";
        }
        // woocommerce_show_product_images();
        echo '<div class="'.esc_attr( $slider_class ).'" data-slidetoshow="1" data-dots="true" data-arrows="true">';

            if( $attachments ){
                $x = 0;
                foreach( $attachments as $single_image ){
                    $image_url = wp_get_attachment_image_url( $single_image, 'zocker-shop-single' );
                    echo '<div>';
                        echo zocker_img_tag( array(
                            'url'   => esc_url( wp_get_attachment_image_url( $attachments[$x], 'zocker-shop-single' ) ),
                            'class' => 'w-100',
                        ) );
                    echo '</div>';
                    $x++;
                }
            }elseif( has_post_thumbnail() ){
                the_post_thumbnail( 'zocker-shop-single', [ 'class' => 'w-100', ] );
            }
        echo '</div>';
    }
}

// single product price rating hook function
if( !function_exists('zocker_woocommerce_single_product_price_rating') ) {
    function zocker_woocommerce_single_product_price_rating() {
        global $product;
        echo '<!-- Product Price -->';
        woocommerce_template_single_price();
        echo '<!-- End Product Price -->';
        // Product Rating
        woocommerce_template_loop_rating();
    }
}

// single product title hook function
if( !function_exists('zocker_woocommerce_single_product_title') ) {
    function zocker_woocommerce_single_product_title( ) {
        if( class_exists( 'ReduxFramework' ) ) {
            $producttitle_position = zocker_opt('zocker_product_details_title_position');
        } else {
            $producttitle_position = 'header';
        }

        if( $producttitle_position != 'header' ) {
            echo '<!-- Product Title -->';
            echo '<h3 class="product-title mb-1">'.esc_html( get_the_title() ).'</h3>';
            echo '<!-- End Product Title -->';
        }

    }
}

// single product title hook function
if( !function_exists('zocker_woocommerce_quickview_single_product_title') ) {
    function zocker_woocommerce_quickview_single_product_title( ) {
        echo '<!-- Product Title -->';
        echo '<h3 class="product-title mb-1">'.esc_html( get_the_title() ).'</h3>';
        echo '<!-- End Product Title -->';
    }
}

// single product excerpt hook function
if( !function_exists('zocker_woocommerce_single_product_excerpt') ) {
    function zocker_woocommerce_single_product_excerpt( ) {
        echo '<!-- Product Description -->';
        woocommerce_template_single_excerpt();
        echo '<!-- End Product Description -->';
    }
}

// single product availability hook function
if( !function_exists('zocker_woocommerce_single_product_availability') ) {
    function zocker_woocommerce_single_product_availability( ) {
        global $product;
        $availability = $product->get_availability();

        if( $availability['class'] != 'out-of-stock' ) {
            echo '<!-- Product Availability -->';
                echo '<div class="mt-2 link-inherit fs-xs">';
                    echo '<p>';
                        echo '<strong class="text-title me-3 font-theme">'.esc_html__( 'Availability:', 'zocker' ).'</strong>';
                        if( $product->get_stock_quantity() ){
                            echo '<span class="stock in-stock"><i class="far fa-check-square me-2"></i>'.esc_html( $product->get_stock_quantity() ).'</span>';
                        }else{
                            echo '<span class="stock in-stock"><i class="far fa-check-square me-2"></i>'.esc_html__( 'In Stock', 'zocker' ).'</span>';
                        }
                    echo '</p>';
                echo '</div>';
            echo '<!--End Product Availability -->';
        } else {
            echo '<!-- Product Availability -->';
            echo '<div class="mt-2 link-inherit fs-xs">';
                echo '<p>';
                    echo '<strong class="text-title me-3 font-theme">'.esc_html__( 'Availability:', 'zocker' ).'</strong>';
                    echo '<span class="stock out-of-stock"><i class="far fa-check-square me-2"></i>'.esc_html__( 'Out Of Stock', 'zocker' ).'</span>';
                echo '</p>';
            echo '</div>';
            echo '<!--End Product Availability -->';
        }
    }
}

// single product add to cart fuunction
if( !function_exists('zocker_woocommerce_single_add_to_cart_button') ) {
    function zocker_woocommerce_single_add_to_cart_button( ) {
        woocommerce_template_single_add_to_cart();
    }
}

// single product ,eta hook function
if( !function_exists('zocker_woocommerce_single_meta') ) {
    function zocker_woocommerce_single_meta( ) {
        global $product;
        echo '<div class="product_meta">';
            if( ! empty( $product->get_sku() ) ){
                echo '<span class="sku_wrapper">'.esc_html__( 'SKU:', 'zocker' ).'<span class="sku">'.$product->get_sku().'</span></span>';
            }
            echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'zocker' ) . ' ', '</span>' );
        echo '</div>';
    }
}

// single produt sidebar hook function
if( !function_exists('zocker_woocommerce_single_product_sidebar_cb') ) {
    function zocker_woocommerce_single_product_sidebar_cb(){
        if( class_exists('ReduxFramework') ) {
            $zocker_woo_singlepage_sidebar = zocker_opt('zocker_woo_singlepage_sidebar');
            if( ( $zocker_woo_singlepage_sidebar == '2' || $zocker_woo_singlepage_sidebar == '3' ) && is_active_sidebar('zocker-woo-sidebar') ) {
                get_sidebar('shop');
            }
        } else {
            if( is_active_sidebar('zocker-woo-sidebar') ) {
                get_sidebar('shop');
            }
        }
    }
}

// reviewer meta hook function
if( !function_exists('zocker_woocommerce_reviewer_meta') ) {
    function zocker_woocommerce_reviewer_meta( $comment ){
        $verified = wc_review_is_from_verified_owner( $comment->comment_ID );
        if ( '0' === $comment->comment_approved ) { ?>
            <em class="woocommerce-review__awaiting-approval">
                <?php esc_html_e( 'Your review is awaiting approval', 'zocker' ); ?>
            </em>

        <?php } else { ?>
            <div class="comment-author">
                <h4 class="name h5"><?php echo ucwords( get_comment_author() ); ?> </h4>
                <span class="commented-on"><time class="woocommerce-review__published-date" datetime="<?php echo esc_attr( get_comment_date( 'c' ) ); ?>"> <?php printf( esc_html__('%1$s at %2$s', 'zocker'), get_comment_date(wc_date_format()),  get_comment_time() ); ?> </time></span>
            </div>
                <?php
                if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) {
                    echo '<em class="woocommerce-review__verified verified">(' . esc_attr__( 'verified owner', 'zocker' ) . ')</em> ';
                }

                ?>
        <?php
        }

        woocommerce_review_display_rating();
    }
}

// woocommerce proceed to checkout hook function
if( !function_exists('zocker_woocommerce_button_proceed_to_checkout') ) {
    function zocker_woocommerce_button_proceed_to_checkout() {
        echo '<a href="'.esc_url( wc_get_checkout_url() ).'" class="checkout-button button alt wc-forward vs-btn gradient-btn no-skew">';
            esc_html_e( 'Proceed to checkout', 'zocker' );
        echo '</a>';
    }
}

// zocker woocommerce cross sell display hook function
if( !function_exists('zocker_woocommerce_cross_sell_display') ) {
    function zocker_woocommerce_cross_sell_display( ){
        woocommerce_cross_sell_display();
    }
}

// zocker minicart view cart button hook function
if( !function_exists('zocker_minicart_view_cart_button') ) {
    function zocker_minicart_view_cart_button() {
        echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button checkout wc-forward vs-btn style1">' . esc_html__( 'View cart', 'zocker' ) . '</a>';
    }
}

// zocker minicart checkout button hook function
if( !function_exists('zocker_minicart_checkout_button') ) {
    function zocker_minicart_checkout_button() {
        echo '<a href="' .esc_url( wc_get_checkout_url() ) . '" class="button wc-forward vs-btn style1">' . esc_html__( 'Checkout', 'zocker' ) . '</a>';
    }
}

// zocker woocommerce before checkout form
if( !function_exists('zocker_woocommerce_before_checkout_form') ) {
    function zocker_woocommerce_before_checkout_form() {
        echo '<div class="row">';
            if ( ! is_user_logged_in() && 'yes' === get_option('woocommerce_enable_checkout_login_reminder') ) {
                echo '<div class="col-lg-12">';
                    woocommerce_checkout_login_form();
                echo '</div>';
            }

            echo '<div class="col-lg-12">';
                woocommerce_checkout_coupon_form();
            echo '</div>';
        echo '</div>';
    }
}

// add to cart button
function woocommerce_template_loop_add_to_cart( $args = array() ) {
    global $product;

		if ( $product ) {
			$defaults = array(
				'quantity'   => 1,
				'class'      => implode(
					' ',
					array_filter(
						array(
							'cart-button icon-btn3',
							'product_type_' . $product->get_type(),
							$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
							$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
						)
					)
				),
				'attributes' => array(
					'data-product_id'  => $product->get_id(),
					'data-product_sku' => $product->get_sku(),
					'aria-label'       => $product->add_to_cart_description(),
					'rel'              => 'nofollow',
				),
			);

			$args = wp_parse_args( $args, $defaults );

			if ( isset( $args['attributes']['aria-label'] ) ) {
				$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
            }
        }

        echo sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
            esc_attr( isset( $args['class'] ) ? $args['class'] : 'cart-button icon-btn3' ),
            isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
            '<i class="fal fa-cart-plus"></i>'
        );
}

// product searchform
add_filter( 'get_product_search_form' , 'zocker_custom_product_searchform' );
function zocker_custom_product_searchform( $form ) {

    $form = '<form class="search-form" role="search" method="get" action="' . esc_url( home_url( '/'  ) ) . '">
        <label class="screen-reader-text" for="s">' . __( 'Search for:', 'zocker' ) . '</label>
        <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search', 'zocker' ) . '" />
        <button class="submit-btn" type="submit"><i class="far fa-search"></i></button>
        <input type="hidden" name="post_type" value="product" />
    </form>';

    return $form;
}

// cart empty message
add_action('woocommerce_cart_is_empty','zocker_wc_empty_cart_message',10);
function zocker_wc_empty_cart_message( ) {
    echo '<h3 class="cart-empty d-none">'.esc_html__('Your cart is currently empty.','zocker').'</h3>';
}