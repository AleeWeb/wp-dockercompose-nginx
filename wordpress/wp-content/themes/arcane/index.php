<?php get_header(); ?>
    <!-- Page content
		================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->
    <div class="blog normal-page container-wrap blog-index-page">
    <div class="container">
        <div class="row">

			<?php if ( is_active_sidebar( 'blog' ) ) : ?>
            <div class="col-8">
				<?php else: ?>
                <div class="col-12">
					<?php endif; ?>
					<?php
					$showposts = get_option( 'posts_per_page' );
					$author    = get_query_var( 'author' );
					$tag       = get_query_var( 'tag' );
					$category  = get_query_var( 'cat' );
					$s         = get_search_query();
					$paged     = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
					$new_query = new WP_Query();
					if ( is_search() ) {
						$new_query->query( 'cat=' . $category . '&tag=' . $tag . '&showposts=' . $showposts . '&author=' . $author . '&paged=' . $paged . '&s=' . $s );
					} else {
						$new_query->query( 'cat=' . $category . '&tag=' . $tag . '&showposts=' . $showposts . '&author=' . $author . '&paged=' . $paged );
					}
					?>
					<?php if ( $new_query->have_posts() ) : while ( $new_query->have_posts() ) : $new_query->the_post(); ?>
						<?php require( get_theme_file_path( 'blog-roll.php' ) ); ?>

					<?php endwhile; ?>
					<?php else : ?>
						<?php if ( is_search() ) { ?>
                            <div class="psearch-content">
                                <h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'arcane' ); ?></h4>
                                <p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'arcane' ); ?></p>
                            </div><!-- .entry-content -->
						<?php } elseif ( is_category() ) { ?>
                            <h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'arcane' ); ?></h4>
                            <p><?php esc_html_e( 'Sorry, but there are no posts for this category yet.', 'arcane' ); ?></p>
						<?php } elseif ( is_tag() ) { ?>
                            <h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'arcane' ); ?></h4>
                            <p><?php esc_html_e( 'Sorry, but there are no posts  for this tag yet.', 'arcane' ); ?></p>
						<?php } elseif ( is_author() ) { ?>
                            <h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'arcane' ); ?></h4>
                            <p><?php esc_html_e( 'Sorry, but there are no posts for this author yet.', 'arcane' ); ?></p>
						<?php } ?>
					<?php endif; ?>
                    <div class="pagination">
                        <ul>
							<?php
							if ( is_search() ) {
								$additional_loop = new WP_Query( 'cat=' . $category . '&tag=' . $tag . '&showposts=' . $showposts . '&author=' . $author . '&paged=' . $paged . '&s=' . $s );
							} else {
								$additional_loop = new WP_Query( 'cat=' . $category . '&tag=' . $tag . '&showposts=' . $showposts . '&author=' . $author . '&paged=' . $paged );
							}
							$page = $additional_loop->max_num_pages;
							echo arcane_kriesi_pagination( $additional_loop->max_num_pages ); ?>
							<?php wp_reset_postdata(); ?>
                        </ul>
                    </div>
                    <div class="clear"></div>

                </div><!-- /.span8 -->

				<?php if ( is_active_sidebar( 'blog' )) : ?>
                    <div class="col-4 sidebar">
						<?php dynamic_sidebar( 'blog' ); ?>
                    </div>
				<?php endif; ?>

                <!-- /.span4 -->
            </div>
            <!-- /.row -->
        </div>
    </div>
    <!-- /.container -->
<?php get_footer(); ?>