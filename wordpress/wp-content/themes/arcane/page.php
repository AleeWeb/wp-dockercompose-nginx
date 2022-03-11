<?php get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-12">
                <?php while ( have_posts() ) : the_post(); ?>
                <?php  the_content(); ?>
                <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
                <div class="clear"></div>
            <?php if(comments_open()){ ?>
                <div id="comments"  class="block-divider"></div>
                <?php comments_template('/short-comments-blog.php'); ?>
            <?php } ?>
            </div>
        </div>
     </div>
</div>
<?php get_footer(); ?>