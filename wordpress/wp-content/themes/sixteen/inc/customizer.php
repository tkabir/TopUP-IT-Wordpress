<?php
/**
 * sixteen Theme Customizer
 *
 * @package sixteen
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sixteen_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	
	
	//Logo Settings
	$wp_customize->add_section( 'title_tagline' , array(
	    'title'      => __( 'Title, Tagline & Logo', 'sixteen' ),
	    'priority'   => 30,
	) );
	
	$wp_customize->add_setting( 'sixteen_logo' , array(
	    'default'     => '',
	    'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control(
	    new WP_Customize_Image_Control(
	        $wp_customize,
	        'sixteen_logo',
	        array(
	            'label' => __('Upload Logo','sixteen'),
	            'section' => 'title_tagline',
	            'settings' => 'sixteen_logo',
	            'priority' => 5,
	        )
		)
	);
	
	
	//Replace Header Text Color with, separate colors for Title and Description
	//Override sixteen_site_titlecolor
	$wp_customize->remove_control('display_header_text');
	$wp_customize->remove_setting('header_textcolor');
	$wp_customize->add_setting('sixteen_site_titlecolor', array(
	    'default'     => '#3a85ae',
	    'sanitize_callback' => 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(new WP_Customize_Color_Control( 
		$wp_customize, 
		'sixteen_site_titlecolor', array(
			'label' => __('Site Title Color','sixteen'),
			'section' => 'colors',
			'settings' => 'sixteen_site_titlecolor',
			'type' => 'color'
		) ) 
	);
	
	//Settings For Logo Area
	
	function sixteen_title_visible( $control ) {
		$option = $control->manager->get_setting('sixteen_hide_title_tagline');
	    return $option->value() == false ;
	}
	
	// SLIDER PANEL
	$wp_customize->add_panel( 'sixteen_slider_panel', array(
	    'priority'       => 35,
	    'capability'     => 'edit_theme_options',
	    'theme_supports' => '',
	    'title'          => 'Main Slider',
	) );
	
	$wp_customize->add_section(
	    'sixteen_sec_slider_options',
	    array(
	        'title'     => __('Enable/Disable','sixteen'),
	        'priority'  => 0,
	        'panel'     => 'sixteen_slider_panel'
	    )
	);
	
	
	$wp_customize->add_setting(
		'sixteen_main_slider_enable',
		array( 'sanitize_callback' => 'sixteen_sanitize_checkbox' )
	);
	
	$wp_customize->add_control(
			'sixteen_main_slider_enable', array(
		    'settings' => 'sixteen_main_slider_enable',
		    'label'    => __( 'Enable Slider.', 'sixteen' ),
		    'section'  => 'sixteen_sec_slider_options',
		    'type'     => 'checkbox',
		)
	);
	
	$wp_customize->add_setting(
		'sixteen_main_slider_count',
			array(
				'default' => '0',
				'sanitize_callback' => 'sixteen_sanitize_positive_number'
			)
	);
	
	// Select How Many Slides the User wants, and Reload the Page.
	$wp_customize->add_control(
			'sixteen_main_slider_count', array(
		    'settings' => 'sixteen_main_slider_count',
		    'label'    => __( 'No. of Slides(Min:0, Max: 5)' ,'sixteen'),
		    'section'  => 'sixteen_sec_slider_options',
		    'type'     => 'number',
		    'description' => __('Save the Settings, and Reload this page to Configure the Slides.','sixteen'),
		    
		)
	);
	
		
	
	if ( get_theme_mod('sixteen_main_slider_count') > 0 ) :
		$slides = get_theme_mod('sixteen_main_slider_count');
		
		for ( $i = 1 ; $i <= $slides ; $i++ ) :
			
			//Create the settings Once, and Loop through it.
			
			$wp_customize->add_setting(
				'sixteen_slide_img'.$i,
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
			    new WP_Customize_Image_Control(
			        $wp_customize,
			        'sixteen_slide_img'.$i,
			        array(
			            'label' => '',
			            'section' => 'sixteen_slide_sec'.$i,
			            'settings' => 'sixteen_slide_img'.$i,			       
			        )
				)
			);
			
			
			$wp_customize->add_section(
			    'sixteen_slide_sec'.$i,
			    array(
			        'title'     => 'Slide '.$i,
			        'priority'  => $i,
			        'panel'     => 'sixteen_slider_panel'
			    )
			);
			
			$wp_customize->add_setting(
				'sixteen_slide_title'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'sixteen_slide_title'.$i, array(
				    'settings' => 'sixteen_slide_title'.$i,
				    'label'    => __( 'Slide Title','sixteen' ),
				    'section'  => 'sixteen_slide_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			$wp_customize->add_setting(
				'sixteen_slide_desc'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'sixteen_slide_desc'.$i, array(
				    'settings' => 'sixteen_slide_desc'.$i,
				    'label'    => __( 'Slide Description','sixteen' ),
				    'section'  => 'sixteen_slide_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			
			$wp_customize->add_setting(
				'sixteen_slide_url'.$i,
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
					'sixteen_slide_url'.$i, array(
				    'settings' => 'sixteen_slide_url'.$i,
				    'label'    => __( 'Target URL','sixteen' ),
				    'section'  => 'sixteen_slide_sec'.$i,
				    'type'     => 'url',
				)
			);
			
		endfor;
	
	
	endif;
	
	
	//Sidebar Location
	$wp_customize->add_section(
	    'sixteen_sidebar_options',
	    array(
	        'title'     => __('Sidebar Locations','sixteen'),
	        'priority'  => 41,
	    )
	);
	
	
	$wp_customize->add_setting(
		'sixteen_left_sidebar',
		array( 'sanitize_callback' => 'sixteen_sanitize_checkbox' )
	);
	
	$wp_customize->add_control(
			'sixteen_left_sidebar', array(
		    'settings' => 'sixteen_left_sidebar',
		    'label'    => __( 'Switch Sidebar to Left Side', 'sixteen' ),
		    'section'  => 'sixteen_sidebar_options',
		    'type'     => 'checkbox',
		)
	);
	
	
	
	if (class_exists('WP_Customize_Control')) {
		class sixteen_WP_Customize_Upgrade_Control extends WP_Customize_Control {
	        /**
	         * Render the control's content.
	         */
	        public function render_content() {
	             printf(
	                '<label class="customize-control-upgrade"><span class="customize-control-title">%s</span> %s</label>',
	                $this->label,
	                $this->description
	            );
	        }
	    }
	}
	
	
	//Upgrade
	$wp_customize->add_section(
	    'sixteen_sec_upgrade',
	    array(
	        'title'     => __('Discover SIXTEEN PLUS','sixteen'),
	        'priority'  => 45,
	    )
	);
	
	$wp_customize->add_setting(
			'sixteen_upgrade',
			array( 'sanitize_callback' => 'esc_textarea' )
		);
			
	$wp_customize->add_control(
	    new sixteen_WP_Customize_Upgrade_Control(
	        $wp_customize,
	        'sixteen_upgrade',
	        array(
	            'label' => __('More of Everything','sixteen'),
	            'description' => __('Sixteen Plus has more of Everything. More New Features, More Options, Unlimited Colors, More Fonts, More Layouts, Configurable Slider, Inbuilt Advertising Options, More Widgets, and a lot more options and comes with Dedicated Support. To Know More about the Pro Version, click here: <a href="http://inkhive.com/product/sixteen-plus/">Upgrade to Pro Version</a>.','sixteen'),
	            'section' => 'sixteen_sec_upgrade',
	            'settings' => 'sixteen_upgrade',			       
	        )
		)
	);
		
	
	class sixteen_Custom_CSS_Control extends WP_Customize_Control {
	    public $type = 'textarea';
	 
	    public function render_content() {
	        ?>
	            <label>
	                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	                <textarea rows="8" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	            </label>
	        <?php
	    }
	}
	
	$wp_customize-> add_section(
    'sixteen_custom_codes',
    array(
    	'title'			=> __('Custom CSS','sixteen'),
    	'description'	=> __('Enter your Custom CSS to Modify design.','sixteen'),
    	'priority'		=> 41,
    	)
    );
    
	$wp_customize->add_setting(
	'sixteen_custom_css',
	array(
		'default'		=> '',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'wp_filter_nohtml_kses',
		'sanitize_js_callback' => 'wp_filter_nohtml_kses'
		)
	);
	
	$wp_customize->add_control(
	    new sixteen_Custom_CSS_Control(
	        $wp_customize,
	        'sixteen_custom_css',
	        array(
	            'section' => 'sixteen_custom_codes',
	            

	        )
	    )
	);
	
	function sixteen_sanitize_text( $input ) {
	    return wp_kses_post( force_balance_tags( $input ) );
	}
	
	$wp_customize-> add_section(
    'sixteen_custom_footer',
    array(
    	'title'			=> __('Custom Footer Text','sixteen'),
    	'description'	=> __('Enter your Own Copyright Text.','sixteen'),
    	'priority'		=> 42,
    	)
    );
    
	$wp_customize->add_setting(
	'sixteen_footer_text',
	array(
		'default'		=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
		)
	);
	
	$wp_customize->add_control(	 
	       'sixteen_footer_text',
	        array(
	            'section' => 'sixteen_custom_footer',
	            'settings' => 'sixteen_footer_text',
	            'type' => 'text'
	        )
	);	
	
	
	// Social Icons
	$wp_customize->add_section('sixteen_social_section', array(
			'title' => __('Social Icons','sixteen'),
			'priority' => 44 ,
	));
	
	$social_networks = array( //Redefinied in Sanitization Function.
					'none' => __('-','sixteen'),
					'facebook' => __('Facebook','sixteen'),
					'twitter' => __('Twitter','sixteen'),
					'google-plus' => __('Google Plus','sixteen'),
					'instagram' => __('Instagram','sixteen'),
					'rss' => __('RSS Feeds','sixteen'),
					'flickr' => __('Flickr','sixteen'),
				);
				
	$social_count = count($social_networks);
				
	for ($x = 1 ; $x <= ($social_count - 3) ; $x++) :
			
		$wp_customize->add_setting(
			'sixteen_social_'.$x, array(
				'sanitize_callback' => 'sixteen_sanitize_social',
				'default' => 'none'
			));

		$wp_customize->add_control( 'sixteen_social_'.$x, array(
					'settings' => 'sixteen_social_'.$x,
					'label' => __('Icon ','sixteen').$x,
					'section' => 'sixteen_social_section',
					'type' => 'select',
					'choices' => $social_networks,			
		));
		
		$wp_customize->add_setting(
			'sixteen_social_url'.$x, array(
				'sanitize_callback' => 'esc_url_raw'
			));

		$wp_customize->add_control( 'sixteen_social_url'.$x, array(
					'settings' => 'sixteen_social_url'.$x,
					'description' => __('Icon ','sixteen').$x.__(' Url','sixteen'),
					'section' => 'sixteen_social_section',
					'type' => 'url',
					'choices' => $social_networks,			
		));
		
	endfor;
	
	function sixteen_sanitize_social( $input ) {
		$social_networks = array(
					'none' ,
					'facebook',
					'twitter',
					'google-plus',
					'instagram',
					'rss',
					'flickr',
				);
		if ( in_array($input, $social_networks) )
			return $input;
		else
			return '';	
	}
	
	
	/* Sanitization Functions Common to Multiple Settings go Here, Specific Sanitization Functions are defined along with add_setting() */
	function sixteen_sanitize_checkbox( $input ) {
	    if ( $input == 1 ) {
	        return 1;
	    } else {
	        return '';
	    }
	}
	
	function sixteen_sanitize_positive_number( $input ) {
		if ( ($input >= 0) && is_numeric($input) )
			return $input;
		else
			return '';	
	}
	
	function sixteen_sanitize_category( $input ) {
		if ( term_exists(get_cat_name( $input ), 'category') )
			return $input;
		else 
			return '';	
	}
	
	
}
add_action( 'customize_register', 'sixteen_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sixteen_customize_preview_js() {
	wp_enqueue_script( 'sixteen_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'sixteen_customize_preview_js' );
