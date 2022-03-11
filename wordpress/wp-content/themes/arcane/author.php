<?php get_header();?>
<?php
$options = arcane_get_theme_options();
if(!isset($options['archive_template']))$options['archive_template'] = '';
?>
<!-- Page content
    ================================================== -->
<!-- Wrap the rest of the page in another container to center all the content. -->
<div class=" blog">
	<div class="container">
		<div class="row">

		<?php if($options['archive_template'] == 'full'){ ?>

				<?php require_once(get_theme_file_path('blog-roll-full-tmp.php')); ?>

		<?php }elseif($options['archive_template'] == 'left'){ ?>

				<?php require_once(get_theme_file_path('blog-roll-left-tmp.php')); ?>

		<?php }else{ ?>

				<?php require_once(get_theme_file_path('blog-roll-right-tmp.php')); ?>

		<?php } ?>

		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /.blog -->
<?php get_footer(); ?>