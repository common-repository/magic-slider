<?php
/*
Plugin Name: Magic Slider
Plugin URI: http://wordpress.org/plugins/magic-slider/
Description: Responsive and flexible slider.
Version: 1.3
Author: webvitaly
Text Domain: magic-slider
Author URI: http://web-profile.net/wordpress/plugins/
License: GPLv3
*/


define('MAGIC_SLIDER_PLUGIN_VERSION', '1.3');

if ( ! defined( 'ABSPATH' ) ) { // prevent full path disclosure
	exit;
}


include('magic-slider-functions.php');
include('magic-slider-cpt.php');
include('magic-slider-settings.php');


class Magic_Slider {

	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_script' ) );
		
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu' ) );
		add_action( 'admin_init', array( 'Magic_Slider_Settings', 'settings_init' ) );

		add_action( 'plugins_loaded', array( __CLASS__, 'i18n' ) );

		add_action( 'plugin_action_links_' . plugin_basename(__FILE__), array( __CLASS__, 'plugin_actions' ) );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
	}


	public static function enqueue_script() {
		wp_enqueue_script( 'magic-slider-bxslider-script', plugins_url('/bxslider/jquery.bxslider.min.js', __FILE__), array('jquery'), MAGIC_SLIDER_PLUGIN_VERSION, true );
		//wp_enqueue_script( 'magic-slider-script', plugins_url('/js/magic-slider.js', __FILE__), array('jquery'), MAGIC_SLIDER_PLUGIN_VERSION, true );
		wp_enqueue_style( 'magic-slider-style', plugins_url( '/css/magic-slider.css', __FILE__ ), false, MAGIC_SLIDER_PLUGIN_VERSION, 'all' );
	}


	public static function add_submenu() {
		add_submenu_page(
			'edit.php?post_type=magic_slide', // add submenu item into existing CPT page
			__( 'Settings', 'magic-slider' ), // page title
			__( 'Settings', 'magic-slider' ), // menu title
			'manage_options', // roles and capabiliyt needed
			'settings', // url slug
			'magic_slider_output_settings_page' //array('Magic_Slider_Settings', 'output_settings_page') // callback function
		);
	}


	public static function i18n() { // internationalization
		load_plugin_textdomain( 'magic-slider', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ). 'languages' );
	}


	public static function plugin_actions( $links ) {
		$plugin_actions = array(
			'settings' => '<a href="'.admin_url( 'edit.php?post_type=magic_slide&page=settings' ).'"><span class="dashicons dashicons-admin-settings"></span> ' . __( 'Settings', 'magic-slider' ) . '</a>'
		);
		$links = array_merge( $links, $plugin_actions );
		return (array) $links;
	}


	public static function plugin_row_meta( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$row_meta = array(
				'support' => '<a href="http://web-profile.net/wordpress/plugins/magic-slider/" target="_blank"> ' . __( 'Magic Slider', 'magic-slider' ) . '</a>',
				'donate' => '<a href="http://web-profile.net/donate/" target="_blank"> ' . __( 'Donate', 'magic-slider' ) . '</a>'
			);
			$links = array_merge( $links, $row_meta );
		}
		return (array) $links;
	}

}


Magic_Slider::init();


/*function magic_slider_setup() {
	add_theme_support( 'post-thumbnails', array('post', 'page', 'magic_slide') ); // enable support for featured images
}
add_action( 'after_setup_theme', 'magic_slider_setup' );*/

