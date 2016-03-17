<div id="social-icons">
			<div class="container">
			    <?php for ($i = 1; $i < 8; $i++) : 
							$social = get_theme_mod('sixteen_social_'.$i);
							if ( ($social != 'none') && ($social != '') ) : ?>
							<a href="<?php echo esc_url(get_theme_mod('sixteen_social_url'.$i)); ?>"><img src="<?php echo get_template_directory_uri().'/images/'.$social.'.png'; ?>"></i></a>
							<?php endif;
						
						endfor; ?>
			</div>
            </div>
