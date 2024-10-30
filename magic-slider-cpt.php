<?php

if ( ! defined( 'ABSPATH' ) ) { // prevent full path disclosure
	exit;
}

function magic_slider_create_post_type() {
	$labels = array(
		'name' => __( 'Magic Slider', 'magic-slider' ),
		'singular_name' => __( 'Slide', 'magic-slider' ),
		'add_new' => __( 'Add Slide', 'magic-slider' ),
		'all_items' => __( 'All Slides', 'magic-slider' ),
		'add_new_item' => __( 'Add Slide', 'magic-slider' ),
		'edit_item' => __( 'Edit Slide', 'magic-slider' ),
		'new_item' => __( 'New Slide', 'magic-slider' ),
		'view_item' => __( 'View Slide', 'magic-slider' ),
		'search_items' => __( 'Search Slides', 'magic-slider' ),
		'not_found' => __( 'No Slides found', 'magic-slider' ),
		'not_found_in_trash' => __( 'No Slides found in trash', 'magic-slider' )
		//'menu_name' => default to 'name'
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'has_archive' => true,
		'publicly_queryable' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_icon' => 'dashicons-images-alt2',
		'supports' => array(
			'title',
			'editor',
			//'excerpt',
			//'thumbnail',
			//'author',
			'revisions'
		),
		'menu_position' => 30,
		'register_meta_box_cb' => 'magic_slider_meta_box_callback',
		'exclude_from_search' => true
	);
	register_post_type( 'magic_slide', $args );

	register_taxonomy( 'magic_slide_category', // register custom taxonomy - category
		'magic_slide',
		array(
			'hierarchical' => true,
			'label' => __( 'Slide categories', 'magic-slider' )
		)
	);
	
}
add_action( 'init', 'magic_slider_create_post_type' );


function magic_slider_meta_box_callback() { // add the meta box
	add_meta_box( 'magic_slider_add_meta_box', __( 'Meta', 'magic-slider' ), 'magic_slider_add_meta_box', 'magic_slide', 'normal' );
}


function magic_slider_add_meta_box() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="magic_slider_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	// Get the data if its already been entered
	$magic_slider_url = get_post_meta($post->ID, '_magic_slider_url', true);
	$magic_slider_image = get_post_meta($post->ID, '_magic_slider_image', true);

	// Echo out the field
	?>


	<p style="display: flex;">
		<span style="flex: 1; padding: 5px 20px 0 0; text-align: right;"><?php _e( 'URL', 'magic-slider' ); ?></span>
		<input style="flex: 4;" type="text" name="magic_slider_url" class="large-text" value="<?php echo $magic_slider_url; ?>" />
	</p>



	<script>
	jQuery(function($){ // document.ready and noConflict mode
		var custom_media_uploader;
		$( '.js-media-choose' ).click( function( event ) {
			event.preventDefault();
			custom_media_uploader = wp.media.frames.file_frame = wp.media( {
				title: '<?php _e( 'Choose image', 'magic-slider' ); ?>',
				button: {
					text: '<?php _e( 'Choose image', 'magic-slider' ); ?>' 
				},
				multiple: false
			});
			custom_media_uploader.on( 'select', function() {
				var attachment = custom_media_uploader.state().get( 'selection' ).first().toJSON();
				$( '.js-media-input' ).val( attachment.url );
			});
			custom_media_uploader.open();
		});
	});
	</script>


	<p style="display: flex;">
		<span style="flex: 1; padding: 5px 20px 0 0; text-align: right;">
			<a href="#" class="button button-small js-media-choose"><?php _e( 'Choose image', 'magic-slider' ); ?></a>
		</span>
		<input style="flex: 4;" type="text" name="magic_slider_image" class="large-text js-media-input" value="<?php echo $magic_slider_image; ?>" />
	</p>

	<?php if ( !empty( $magic_slider_image ) ) : ?>
	<div>
		<img src="<?php echo $magic_slider_image; ?>" style="max-width: 100%;">
	</div>
	<?php endif; ?>

<?php
}


function magic_slider_post_save_meta( $post_id, $post ) { // save the data

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	if ( ! isset( $_POST['magic_slider_noncename'] ) ) { // Check if our nonce is set.
		return;
	}

	if( !wp_verify_nonce( $_POST['magic_slider_noncename'], plugin_basename(__FILE__) ) ) { // Verify that the nonce is valid.
		return $post->ID;
	}

	// is the user allowed to edit the post or page?
	if( ! current_user_can( 'edit_post', $post->ID )){
		return $post->ID;
	}
	// ok, we're authenticated: we need to find and save the data
	// we'll put it into an array to make it easier to loop though

	$quote_post_meta['_magic_slider_url'] = $_POST['magic_slider_url'];
	$quote_post_meta['_magic_slider_image'] = $_POST['magic_slider_image'];

	// add values as custom fields
	foreach( $quote_post_meta as $key => $value ) { // cycle through the $quote_post_meta array
		// if( $post->post_type == 'revision' ) return; // don't store custom data twice
		$value = implode(',', (array)$value); // if $value is an array, make it a CSV (unlikely)
		if( get_post_meta( $post->ID, $key, FALSE ) ) { // if the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // if the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if( !$value ) { // delete if blank
			delete_post_meta( $post->ID, $key );
		}
	}
}
add_action( 'save_post', 'magic_slider_post_save_meta', 1, 2 ); // save the custom fields
	

function magic_slider_output( $settings_input = array() ) {
	$br = "\r\n";

	$settings_defaults_and_user_merged = Magic_Slider_Functions::get_settings();
	
	$magic_slider_settings = array_merge( $settings_defaults_and_user_merged, $settings_input );
	
	$output = '';
	
	$query_args = array(
		'posts_per_page' => -1,
		'orderby' => 'date', // order by published date
		'order' => 'DESC',
		'post_type' => 'magic_slide',
		'post_status' => 'publish'
	);
	
	if( !empty( $magic_slider_settings['category'] ) ) {
		$query_args_cat = array(
			'tax_query' => array(
				array(
					'taxonomy' => 'magic_slide_category',
					'field' => 'term_id',
					'terms' => array( $magic_slider_settings['category'] )
				)
			)
		);
		$query_args = array_merge( $query_args, $query_args_cat );
	}

	$query = new WP_Query( $query_args );
	
	while( $query->have_posts() ) : $query->the_post();
	
		$link_before = '';
		$link_after = '';
		
		$meta_link = get_post_meta( get_the_ID(), '_magic_slider_url', true );
		
		$meta_image = get_post_meta( get_the_ID(), '_magic_slider_image', true );
		
		if( $meta_link ) {
			$link_before = '<a href="'.$meta_link.'">';
			$link_after = '</a>';
		}
		
		$output .= $br.'		<div class="magic-slider-item">
			<div class="magic-slider-image" style="height: '.$magic_slider_settings['height'].'px; background-image: url('.$meta_image.');">
				<div class="magic-slider-text">
					<h3 class="magic-slider-title">'.$link_before.get_the_title().$link_after.'</h3>
					<div class="magic-slider-content">'.get_the_content().'</div><!-- .magic-slider-content -->
				</div><!-- .magic-slider-text -->
			</div><!-- .magic-slider-image -->
		</div><!-- .magic-slider-item -->'.$br;
	
	endwhile;
	
	wp_reset_postdata(); // reset the query
		
	$output = '
	<script>
	jQuery(function($){ // document.ready and noConflict mode at the same time
		$(".magic-slider-js").bxSlider({
			mode: "'.$magic_slider_settings['mode'].'",
			speed: '.$magic_slider_settings['speed'].',
			auto: '.$magic_slider_settings['auto'].',
			pause: '.$magic_slider_settings['pause'].',
			pager: '.$magic_slider_settings['pager'].'
		});
	});
	</script>

	<!-- Powered by Magic-Slider plugin v.' . MAGIC_SLIDER_PLUGIN_VERSION . ' wordpress.org/plugins/magic-slider/ -->
	<div class="magic-slider-js magic-slider-wrap">'.$output.'	</div><!-- .magic-slider-wrap -->'.$br;

	return $output;
}



function magic_slider_shortcode( $atts ) {
	$settings = Magic_Slider_Functions::get_settings();

	$input_atts = shortcode_atts( array(
		'height' => $settings['height'],
		'category' => $settings['category'],
		'mode' => $settings['mode'],
		'speed' => $settings['speed'],
		'auto' => $settings['auto'],
		'pause' => $settings['pause'],
		'pager' => $settings['pager']
	), $atts );

	$magic_slider_args = array(
		'height'  => $input_atts['height'],
		'category' => $input_atts['category'],
		'mode' => $input_atts['mode'],
		'speed' => $input_atts['speed'],
		'auto' => $input_atts['auto'],
		'pause' => $input_atts['pause'],
		'pager' => $input_atts['pager']
	);
	$output = magic_slider_output( $magic_slider_args );

	return $output;
}
add_shortcode( 'magic_slider', 'magic_slider_shortcode' );