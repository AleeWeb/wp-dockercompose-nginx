<?php

/*init vars*/
$post_format_class = ''; $quote_background_color = '';
$featured_class = '';

/*define colors*/
$postcats = wp_get_post_categories($post->ID);
if(!empty($postcats))
    $cat_data = get_option("category_$postcats[0]");
$options = get_option('arcane_redux');
if(!isset($options['general-settings-color-selector'])) $options['general-settings-color-selector'] = '#ff3e58';
if(empty($cat_data)) $cat_data['catBG'] = $options['general-settings-color-selector'];

/*fire up formats*/
$post_format = get_post_format($post->ID);
if(!isset($options['blog-feed-template']))$options['blog-feed-template'] = 'standard_right';
if(!isset($options['blog-feed-template']))$options['blog-feed-template'] = 'standard_right';
if($options['blog-feed-template'] == 'standard_right' or  $options['blog-feed-template'] == 'standard_left' or $options['blog-feed-template'] == 'standard_full' ){
    $featured_class = 'blogs-featured blogs-style2';
}
if($post_format == 'audio'){ $post_format_class = 'spaudio-post '.$featured_class; }
elseif($post_format == 'video'){ $post_format_class = 'spvideo-post '.$featured_class; }
elseif($post_format == 'quote'){ $post_format_class = 'blogs-style2 blogs-style1 spquote-post '.$featured_class; $quote_background_color = str_replace("#","",$cat_data['catBG']);  }
elseif($post_format == 'gallery'){$post_format_class = 'blogs-style2 blogs-style1 blogs-gallery '.$featured_class ;}
else{ $post_format_class = 'blogs-style1 '.$featured_class; }

if($post_format == 'quote'){
    $title = get_post_meta($post->ID, 'quote-value', true);
    if(empty($title)) $title = esc_html__('Please insert a quote', 'arcane');
}else{
    $title = get_the_title();
}

$post_format = get_post_format()
?>
<?php
$no_image_class = '';
if(has_post_thumbnail($post->ID)){
    $thumb = get_post_thumbnail_id();
    $img_url = wp_get_attachment_url( $thumb); //get img URL
    // $image = arcane_aq_resize( $img_url, 366, 540, true, '', true ); //iskljuceno zbog vertikalne
    $image_link = $img_url;

} else{
    if(get_post_format($post->ID) == 'gallery'){

        $gallery = get_post_gallery_images( $post->ID );
        if(isset($gallery) && is_array($gallery)){
            $image_link = $gallery[0];
        }

    }else{
        $no_image_class = 'no_image';
    }
}
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(esc_attr($no_image_class). ' '. esc_attr($post_format_class). ' blogs-style1 spcard '); ?>>


    <div class="spwrapper" >

        <div class="spbgimg blog-roll-i post<?php echo esc_attr($post->ID); ?>" >
            <a href="<?php the_permalink(); ?>">
                <?php if(isset($image_link) && !empty($image_link)){ ?>
                    <img alt="<?php echo esc_attr($title); ?>" title="<?php echo esc_attr($title); ?>" src="<?php echo esc_url($image_link); ?>" />
                <?php } ?>
            </a>
        </div>



        <div class="spdata">

            <div class="spcontent">

                <div class="spheader">
                    <?php
                    if ($postcats) {
                        foreach($postcats as $c){
                            $cat = get_category( $c ); ?>
                            <a class="spcategory" href="<?php echo esc_url(get_category_link($cat->cat_ID)); ?>"> <?php echo esc_attr($cat->cat_name) . ' '; ?> </a>
                            <?php
                            break;
                        }
                    }
                    ?>
                    <?php
                    $arcane_allowed = array(
                        'a' => array(
                            'class' => array(),
                            'id' => array(),
                            'title' => array(),
                        ),
                        'i' => array(
                            'class' => array(),
                        ),
                        'span' => array()
                    );
                    ?>
                    <ul class="spmenu-content">
                        <li>
                            <a href="<?php the_permalink(); ?>#comments" class="far fa-comments">
                                <span><?php comments_number( '0', '1', '%'); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>


                <h1 class="sptitle">
                    <a rel="bookmark" href="<?php the_permalink(); ?>"><?php echo wp_kses_post($title); ?></a>
                </h1>


                <p class="sptext"> <?php echo esc_html(arcane_excerpt(esc_attr(100))); ?></p>

                <?php if(empty($title)){ ?>
                    <a rel="bookmark" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more','arcane');?></a>
                <?php } ?>

            </div>
            <div class="spextra">
			<span class="spauthor">
				<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )); ?>">
					<?php if (function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), 40 ); }?>
				</a>
				<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )); ?>">

					<span><?php esc_html_e('by','arcane');?> <?php the_author(); ?></span>
				</a>
			</span>

                <div class="spdate">
                    <i class="far fa-calendar-alt"></i> <?php the_time(get_option('date_format')); ?>
                </div>
                <?php $tags_array = get_the_tags($post->ID); ?>
                <?php if(!empty($tags_array)){ ?>
                    <div class="sptags">
                        <i class="fas fa-tags"></i> <?php echo is_array($tags_array) ? count($tags_array) : 0 ?>
                    </div>
                <?php } ?>
            </div>
        </div>


    </div>
</div>