<?php
/*
 * Template name: Blog - Left Sidebar
*/
?>
<?php get_header();?>
<!-- Page content
================================================== -->
<!-- Wrap the rest of the page in another container to center all the content. -->
<div class=" blog blog-ind">

	<div class="container">

		<div class="row">

			<?php require_once(get_theme_file_path('blog-roll-left-tmp.php')); ?>

		</div><!-- /row -->

	</div><!-- /container -->

</div><!-- /containerblog -->

<?php get_footer(); ?>