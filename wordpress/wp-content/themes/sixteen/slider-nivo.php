<?php
		if ( get_theme_mod('sixteen_main_slider_enable' ) && is_home() ) : 

		$count_x = $count = get_theme_mod('sixteen_main_slider_count'); ?>
		
		<div class="slider-parent">	
		<div class="slider-wrapper theme-default container"> 
	    	<div class="ribbon"></div>    
	    		<div id="slider" class="nivoSlider">
	    			<?php
			  		for ( $i = 1; $i <= $count; $i++ ) :
				  		
				  			$url = esc_url ( get_theme_mod('sixteen_slide_url'.$i) );
				  			$img = esc_url ( get_theme_mod('sixteen_slide_img'.$i) );
				  			$title = get_theme_mod('sixteen_slide_title'.$i);
				  			$desc = get_theme_mod('sixteen_slide_desc'.$i);
				  			
						
							echo "<div class='slide'><a href='".$url."'><img src='".$img."' title='#caption_".$i."'></a></div>"; 
					endfor;
					?>  
	    		</div><!--#slider-->
	    		<?php for ($i=1; $i < $count_x; $i++) {
		    		
		    			$url = esc_url ( get_theme_mod('sixteen_slide_url'.$i) );
			  			$img = esc_url ( get_theme_mod('sixteen_slide_img'.$i) );
			  			$title = get_theme_mod('sixteen_slide_title'.$i);
			  			$desc = get_theme_mod('sixteen_slide_desc'.$i);
	    				
	    				if ($title != "")
	    				{
		    				echo "<div id='caption_".$i."' class='nivo-html-caption'>";
		    				echo "<a href='".$url."'><div class='slide-title'><span>".$title."</span></div></a>";
		    				if ( $desc != "" ) {
		    					echo "<div class='slide-description'>".$desc."</div>";
		    				}
		    				echo "</div>";
	    				}
	    			}	
	    	    
				?>
	    </div><!--.container-->	
		</div><!--.slider-parent-->
		<?php 
			endif;
			?>	