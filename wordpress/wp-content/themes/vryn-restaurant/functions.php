<?php
	/* Enque Google Fonts */
	
	
	function vryn_restaurant_fonts() {
		wp_enqueue_style('vryn-restaurant-damion', '//fonts.googleapis.com/css?family=Damion');
		wp_enqueue_style('vryn-restaurant-karla', '//fonts.googleapis.com/css?family=Karla:400,400italic,700,700italic');
	}
	add_action( 'wp_enqueue_scripts', 'vryn_restaurant_fonts' );


	function vryn_restaurant_styles() {
		wp_dequeue_style( 'sixteen-basic-style' );
		wp_dequeue_style( 'sixteen-layout' ); 
		wp_dequeue_style( 'sixteen-main-style' );
		wp_enqueue_style( 'sixteen-basic-style-2', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'sixteen-layout-2', get_template_directory_uri() . '/css/layouts/content-sidebar.css' );
		wp_enqueue_style( 'sixteen-main-style-2', get_template_directory_uri() . '/css/main.css' );
		wp_enqueue_style( 'vryn-restaurant-main', get_stylesheet_uri() );
	}
	add_action( 'wp_enqueue_scripts', 'vryn_restaurant_styles', 20 );
	
	remove_action( 'after_setup_theme', 'sixteen_custom_header_setup' );

	function vryn_custom_header_setup() {
	add_theme_support( 'custom-header', array(
		'default-image'          => get_stylesheet_directory_uri().'/images/tracks.jpg',
		'default-text-color'     => 'fff',
		'width'                  => 1600,
		'height'                 => 400,
		'wp-head-callback'       => 'sixteen_header_style',
		'admin-head-callback'    => 'sixteen_admin_header_style',
		'admin-preview-callback' => 'sixteen_admin_header_image',
	) );
}
add_action( 'after_setup_theme', 'vryn_custom_header_setup');
?>