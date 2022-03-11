<div class="after-nav ">
	<div class="container">
		<?php if(!empty($options['tickertitle'])){ ?> <div class="ticker-title"><i class="fas fa-bullhorn"></i> &nbsp;<?php  echo esc_attr($options['tickertitle']);  ?></div><?php } ?>

		<?php  if(!empty($options['tickeritems'])){ $news = explode('||', $options['tickeritems']); ?>
			<ul id="webTicker" >

				<?php $i = 0; foreach ($news as $new) { $i++; ?>
					<li>
						<?php echo wp_kses_post($new); ?>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>

		<div class="search-top">
            <i id="btn-search" class="fas fa-search"></i>
		</div>

	</div>
</div>