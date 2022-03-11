<?php
if ( class_exists( 'WooCommerce' )){
	if (is_shop()){ echo get_the_title(arcane_get_id_by_slug ('shop'));}
	else{ if(is_tag()){esc_html_e("Tag: ",'arcane');echo get_query_var('tag' ); }elseif(is_category()){esc_html_e("Category: ",'arcane');echo get_the_category_by_ID(get_query_var('cat'));}elseif(is_author()){esc_html_e("Author: ",'arcane');echo get_the_author_meta('user_login', get_query_var('author' ));}elseif(is_archive()){ ?>
		<?php if ( is_day() ) : ?>
			<?php printf( esc_html__( 'Daily Archives: %s', 'arcane' ), get_the_date() ); ?>
		<?php elseif ( is_month() ) : ?>
			<?php printf( esc_html__( 'Monthly Archives: %s', 'arcane' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'arcane' ) ) ); ?>
		<?php elseif ( is_year() ) : ?>
			<?php printf( esc_html__( 'Yearly Archives: %s', 'arcane' ), get_the_date( _x( 'Y', 'yearly archives date format', 'arcane' ) ) ); ?>
		<?php elseif ( is_product_category()) : ?>
			<?php  echo single_term_title();  ?>
		<?php else : ?>
			<?php esc_html_e( 'Blog Archives', 'arcane' ); ?>
		<?php endif; }else{the_title();} }
}else{
	if(is_tag()){esc_html_e("Tag: ",'arcane');echo get_query_var('tag' );}elseif(is_category()){esc_html_e("Category: ",'arcane');echo get_the_category_by_ID(get_query_var('cat'));}elseif(is_author()){esc_html_e("Author: ",'arcane');echo get_the_author_meta('user_login', get_query_var('author' ));}elseif(is_archive()){ ?>
	<?php if ( is_day() ) : ?>
		<?php printf( esc_html__( 'Daily Archives: %s', 'arcane' ), get_the_date() ); ?>
	<?php elseif ( is_month() ) : ?>
		<?php printf( esc_html__( 'Monthly Archives: %s', 'arcane' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'arcane' ) ) ); ?>
	<?php elseif ( is_year() ) : ?>
		<?php printf( esc_html__( 'Yearly Archives: %s', 'arcane' ), get_the_date( _x( 'Y', 'yearly archives date format', 'arcane' ) ) ); ?>
	<?php else : ?>
		<?php esc_html_e( 'Blog Archives', 'arcane' ); ?>
	<?php endif; }else{the_title();}
}