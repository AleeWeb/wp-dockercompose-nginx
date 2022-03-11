<?php
/*
 * Template name: Blog - Right Sidebar
*/
?>
<?php get_header();?>
<!-- Page content
================================================== -->
<!-- Wrap the rest of the page in another container to center all the content. -->
<div class="blog">

	<div class="container ">

		<div class="row">

			<?php require_once(get_theme_file_path('blog-roll-right-tmp.php')); ?>

		</div><!-- /row -->

    </div><!-- /container -->

</div><!-- /blog -->

<?php get_footer(); ?>