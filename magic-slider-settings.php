<?php
/*
Magic-Slider Settings
Powered by WordPress Settings API - http://codex.wordpress.org/Settings_API
*/

if ( ! defined( 'ABSPATH' ) ) { // prevent full path disclosure
	exit;
}


class Magic_Slider_Settings {
	
	static function settings_init() {
		
		$magic_slider_settings = Magic_Slider_Functions::get_settings();
		update_option('magic_slider_settings', $magic_slider_settings);
		
		register_setting('magic_slider_settings_group', 'magic_slider_settings', 'magic_slider_settings_validate');
		
		add_settings_section('magic_slider_settings_section', '', 'magic_slider_section_callback', 'magic_slider_settings_page');


		add_settings_field('height', __( 'Height', 'magic-slider' ), 'magic_slider_field_height_callback', 'magic_slider_settings_page', 'magic_slider_settings_section');

		add_settings_field('mode', __( 'Mode', 'magic-slider' ), 'magic_slider_field_mode_callback', 'magic_slider_settings_page', 'magic_slider_settings_section');

		add_settings_field('speed', __( 'Speed', 'magic-slider' ), 'magic_slider_field_speed_callback', 'magic_slider_settings_page', 'magic_slider_settings_section');

		add_settings_field('auto', __( 'Auto transition', 'magic-slider' ), 'magic_slider_field_auto_callback', 'magic_slider_settings_page', 'magic_slider_settings_section');

		add_settings_field('pause', __( 'Pause', 'magic-slider' ), 'magic_slider_field_pause_callback', 'magic_slider_settings_page', 'magic_slider_settings_section');

		add_settings_field('pager', __( 'Pager', 'magic-slider' ), 'magic_slider_field_pager_callback', 'magic_slider_settings_page', 'magic_slider_settings_section');

	}
	
} // class


function magic_slider_settings_validate($input) {
	$default_settings = Magic_Slider_Functions::get_settings();

	// checkboxes
	if( empty($input['auto']) ) {
		$input['auto'] = 0;
	}
	$output['auto'] = $input['auto'];

	if( empty($input['pager']) ) {
		$input['pager'] = 0;
	}
	
	$output['pager'] = $input['pager'];
	$output['height'] = trim($input['height']);
	$output['mode'] = trim($input['mode']);
	$output['speed'] = trim($input['speed']);
	$output['pause'] = trim($input['pause']);

	return $output;
}


function magic_slider_section_callback() { // Magic-Slider settings description
	_e( 'These settings can be overwritten via shortcode params or via magic_slider_output() function params.', 'magic-slider' );
	echo ' <a href="https://wordpress.org/plugins/magic-slider/">'. __( 'Additional info about Magic-Slider options', 'magic-slider' ). '</a>.';
}


function magic_slider_checkbox_default( $value ) {
	$output = 'disabled';
	if ( !empty( $value ) ) {
		$output = 'enabled';
	}
	return $output;
}


function magic_slider_field_height_callback() {
	$settings = Magic_Slider_Functions::get_settings();
	$default_settings = Magic_Slider_Functions::default_settings();
	echo '<input type="number" name="magic_slider_settings[height]" class="regular-text" value="'.$settings['height'].'" required="required" />';
	echo '<p class="description">';
	echo __( 'Slider height in pixels.', 'magic-slider' ) . ' ';
	printf( __( 'Default: %s', 'magic-slider' ), $default_settings['height'] );
	echo '</p>';
}


function magic_slider_field_mode_callback() {
	$settings = Magic_Slider_Functions::get_settings();
	$default_settings = Magic_Slider_Functions::default_settings();
	$mode_options = array( 'fade', 'horizontal', 'vertical' );
	echo '<select name="magic_slider_settings[mode]">';
	foreach ( $mode_options as $mode_option ) {
		$selected = '';
		if( $mode_option == $settings['mode'] ) {
			$selected = ' selected="selected"';
		}
		echo '<option value="'.$mode_option.'"'.$selected.'>'.$mode_option.'</option>';
	}
	echo '</select>';
	echo '<p class="description">';
	echo __( 'Transition mode.', 'magic-slider' ) . ' ';
	printf( __( 'Default: %s', 'magic-slider' ), $default_settings['mode'] );
	echo '</p>';
}


function magic_slider_field_speed_callback() {
	$settings = Magic_Slider_Functions::get_settings();
	$default_settings = Magic_Slider_Functions::default_settings();
	echo '<input type="number" name="magic_slider_settings[speed]" class="regular-text" value="'.$settings['speed'].'" required="required" />';
	echo '<p class="description">';
	echo __( 'Transition speed in milliseconds.', 'magic-slider' ) . ' ';
	printf( __( 'Default: %s', 'magic-slider' ), $default_settings['speed'] );
	echo '</p>';
}


function magic_slider_field_auto_callback() {
	$settings = Magic_Slider_Functions::get_settings();
	$default_settings = Magic_Slider_Functions::default_settings();
	echo '<input type="checkbox" name="magic_slider_settings[auto]" '.checked(1, $settings['auto'], false).' value="1" />';
	echo '<p class="description">';
	printf( __( 'Default: %s', 'magic-slider' ), magic_slider_checkbox_default( $default_settings['auto'] ) );
	echo '</p>';
}


function magic_slider_field_pause_callback() {
	$settings = Magic_Slider_Functions::get_settings();
	$default_settings = Magic_Slider_Functions::default_settings();
	echo '<input type="number" name="magic_slider_settings[pause]" class="regular-text" value="'.$settings['pause'].'" required="required" />';
	echo '<p class="description">';
	echo __( 'Pause between each auto transition in milliseconds.', 'magic-slider' ) . ' ';
	printf( __( 'Default: %s', 'magic-slider' ), $default_settings['pause'] );
	echo '</p>';
}


function magic_slider_field_pager_callback() {
	$settings = Magic_Slider_Functions::get_settings();
	$default_settings = Magic_Slider_Functions::default_settings();
	echo '<input type="checkbox" name="magic_slider_settings[pager]" '.checked(1, $settings['pager'], false).' value="1" />';
	echo '<p class="description">';
	printf( __( 'Default: %s', 'magic-slider' ), magic_slider_checkbox_default( $default_settings['pager'] ) );
	echo '</p>';
}


function magic_slider_output_settings_page () {
	?>
	
	<div class="wrap">
		
		<h2><span class="dashicons dashicons-admin-settings"></span> <?php _e( 'Magic-Slider Settings', 'magic-slider' ); ?></h2>

		<form method="post" action="options.php">
			<?php
			settings_fields('magic_slider_settings_group');
			do_settings_sections('magic_slider_settings_page');
			submit_button();
			?>
		</form>

	</div><!-- .wrap -->
	
	<?php
}
