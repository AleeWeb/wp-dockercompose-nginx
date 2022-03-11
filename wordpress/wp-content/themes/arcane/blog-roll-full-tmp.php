        <?php
    $kategorije = array();
    $categories = get_categories(
    array(
            'type'          => 'post',
            'child_of'      => 0,
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    => 1,
            'hierarchical'  => 1,
            'taxonomy'      => 'category',
            'pad_counts'    => false
    ) );

    foreach ($categories as $cat) {
        $expression = get_post_meta($post->ID, '_smartmeta_blog_cats'.$cat->cat_ID, true);
        if($expression == 'true'){
            $kategorije[] = $cat->cat_ID;
        }
    }

    ?>
	<div class="bwrap">
		<?php
	        $showposts = get_option('posts_per_page');
			$author = get_query_var('author');
			$tag = get_query_var('tag' );
			$category = get_query_var('cat' );
            if(empty($category) && !empty($kategorije))
            $category = implode(',',$kategorije);
			$s = get_search_query();
	        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	        $new_query = new WP_Query();
	        if ( is_search() ) {
	        	$new_query->query( 'cat='.$category.'&tag='.$tag.'&showposts='.$showposts.'&author='.$author.'&paged='.$paged.'&s='.$s  );
			}else{
				$new_query->query( 'cat='.$category.'&tag='.$tag.'&showposts='.$showposts.'&author='.$author.'&paged='.$paged);
			}
	    ?>
		<?php if ( $new_query->have_posts() ) : while ( $new_query->have_posts() ) : $new_query->the_post(); ?>

			<?php require(get_theme_file_path('blog-roll.php')); ?>

			<?php endwhile; ?>
			<?php else : ?>
				<?php if(is_search()){ ?>
				<div class="psearch-content">
					<h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'arcane' ); ?></h4>
					<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'arcane' ); ?></p>
				</div><!-- .entry-content -->
				<?php }elseif(is_category()){ ?>
					<h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'arcane' ); ?></h4>
					<p><?php esc_html_e( 'Sorry, but there are no posts for this category yet.', 'arcane' ); ?></p>
				<?php }elseif(is_tag()){ ?>
					<h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'arcane' ); ?></h4>
					<p><?php esc_html_e( 'Sorry, but there are no posts  for this tag yet.', 'arcane' ); ?></p>
				<?php }elseif(is_author()){ ?>
					<h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'arcane' ); ?></h4>
					<p><?php esc_html_e( 'Sorry, but there are no posts for this author yet.', 'arcane' ); ?></p>
				<?php } ?>
			<?php endif; ?>
	</div><!-- /.bwrap -->
	<div class="pagination">
		<ul>
				<?php
				if ( is_search() ) {
						$additional_loop = new WP_Query('cat='.$category.'&tag='.$tag.'&showposts='.$showposts.'&author='.$author.'&paged='.$paged.'&s='.$s  );
					}else {
						$additional_loop = new WP_Query('cat='.$category.'&tag='.$tag.'&showposts='.$showposts.'&author='.$author.'&paged='.$paged);
					}
				$page=$additional_loop->max_num_pages;
				echo arcane_kriesi_pagination($additional_loop->max_num_pages);	?>
				<?php wp_reset_postdata(); ?>
		</ul>
	</div>
	<div class="clear"></div>
