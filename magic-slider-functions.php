<?php

if ( ! defined( 'ABSPATH' ) ) { // prevent full path disclosure
	exit;
}

class Magic_Slider_Functions {

	public static function default_settings() { // http://bxslider.com/options
		$settings = array(
			'height' => 400,
			'category' => 0,
			'mode' => 'fade',
			'speed' => 500,
			'auto' => 1,
			'pause' => 4000,
			'pager' => 1,
			'controls' => 0,
		);
		return $settings;
	}

	public static function get_settings() {
		$magic_slider_settings = (array) get_option('magic_slider_settings');
		$default_settings = self::default_settings();
		$magic_slider_settings_merged = array_merge($default_settings, $magic_slider_settings); // set empty options with default values
		return $magic_slider_settings_merged;
	}

}