<?php
/*
 * Template name: Blog - Full width
*/
?>
<?php get_header();?>
<!-- Page content
================================================== -->
<!-- Wrap the rest of the page in another container to center all the content. -->
<div class="blog blog-ind">

	<div class="container">

		<div class="row">

			<?php require_once(get_theme_file_path('blog-roll-full-tmp.php')); ?>

		</div><!-- /row -->

	</div><!-- /container -->

</div><!-- /containerblog -->

<?php get_footer(); ?>