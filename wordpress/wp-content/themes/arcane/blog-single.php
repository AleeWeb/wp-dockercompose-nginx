<?php
$options = arcane_get_theme_options();
if(!isset($options['rating_type']))$options['rating_type'] = '';
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?>><!-- blog-post -->

	<div class="blog-info"><!-- blog-info -->
		<div class="post-pinfo">

			<a data-original-title="<?php esc_html_e("View all posts by", 'arcane'); ?> <?php echo esc_attr(get_the_author()); ?>" data-toggle="tooltip" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ))); ?>"><?php echo get_avatar( get_the_author_meta('ID'), 60, '', 'author image', array('class' => 'authorimg') ); ?> <?php esc_html_e('by','arcane'); ?> <?php echo esc_attr(get_the_author()); ?></a>
			<i>|</i>
			<i class="fas fa-tags" aria-hidden="true"></i>

			<?php the_tags( '', ', ', '<i>|</i>' ); ?>

			<?php if ( class_exists( 'Disqus' )){ ?>
	        <a  href="<?php echo the_permalink(); ?>#comments" >
	        <?php comments_number( esc_html__('0 Comments', 'arcane'), esc_html__('1 Comment', 'arcane'), esc_html__('% Comments', 'arcane') ) ?> </a>
	       <?php }else{ ?>
	        <a data-original-title="<?php comments_number( esc_html__('No comments in this post', 'arcane'), esc_html__('One comment in this post', 'arcane'), esc_html__('% comments in this post', 'arcane')); ?>" href="<?php echo the_permalink(); ?>#comments" data-toggle="tooltip">
	         <?php comments_number( esc_html__('0 Comments', 'arcane'), esc_html__('1 Comment', 'arcane'), esc_html__('% Comments', 'arcane') ) ?></a>

	       <?php } ?>

			<i>|</i>
	       <span class="date"> <?php the_date(); ?></span>
			<div class="clear"></div>
		</div>
	<div class="clear"></div>
	</div><!-- blog-info -->


	<!-- post ratings -->
    <?php
    $overall_rating = get_post_meta($post->ID, 'overall_rating', true);
    $rating_one = get_post_meta($post->ID, 'creteria_1', true);
    $rating_two = get_post_meta($post->ID, 'creteria_2', true);
    $rating_three = get_post_meta($post->ID, 'creteria_3', true);
    $rating_four = get_post_meta($post->ID, 'creteria_4', true);
    $rating_five = get_post_meta($post->ID, 'creteria_5', true);

    if($overall_rating== NULL or $rating_one== NULL && $rating_two== NULL && $rating_three== NULL && $rating_four== NULL && $rating_five== NULL ){

    }else{

		 if($options['rating_type'] == 'stars'){
		        if(class_exists('Arcane_Types'))
	                require WP_PLUGIN_DIR . '/arcane_custom_post_types/rating/post-rating.php';
		      }else{
			    if(class_exists('Arcane_Types'))
			        require WP_PLUGIN_DIR . '/arcane_custom_post_types/rating/post-rating-num.php';
		      }


	} ?><!-- /post ratings -->


	<div class="blog-content wcontainer"><!-- /.blog-content -->
		<?php the_content();?>
		<div class="clear"></div>
	</div><!-- /.blog-content -->

	<div class="clear"></div>
</div><!-- /.blog-post -->
<?php  posts_nav_link(); previous_posts_link();  ?>