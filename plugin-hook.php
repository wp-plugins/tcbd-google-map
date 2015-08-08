<?php
/*
Plugin Name: TCBD Google Map
Plugin URI: http://demos.tcoderbd.com/wordpress_plugins/tcbd-google-map-awesome-wordpress-plugin/
Description: This plugin will enable google map in your Wordpress theme.
Author: Md Touhidul Sadeek
Version: 1.0
Author URI: http://tcoderbd.com
*/

/*  Copyright 2015 tCoderBD (email: info@tcoderbd.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Define Plugin Directory
define('TCBD_GOOGLE_MAP_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );


// Hooks TCBD functions into the correct filters
function tcbd_map_add_mce_button() {
	// check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'tcbd_map_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'tcmd_map_register_mce_button' );
	}
}
add_action('admin_head', 'tcbd_map_add_mce_button');

// Declare script for new button
function tcbd_map_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['tcbd_map_mce_button'] = TCBD_GOOGLE_MAP_PLUGIN_URL.'js/tinymce.js';
	return $plugin_array;
}

// Register new button in the editor
function tcmd_map_register_mce_button( $buttons ) {
	array_push( $buttons, 'tcbd_map_mce_button' );
	return $buttons;
}


// Add settings page link in before activate/deactivate links.
function tcbd_google_map_plugin_action_links( $actions, $plugin_file ){
	
	static $plugin;

	if ( !isset($plugin) ){
		$plugin = plugin_basename(__FILE__);
	}
		
	if ($plugin == $plugin_file) {
		
		if ( is_ssl() ) {
			$settings_link = '<a href="'.admin_url( 'plugins.php?page=TCBD_Google_Map_settings', 'https' ).'">Settings</a>';
		}else{
			$settings_link = '<a href="'.admin_url( 'plugins.php?page=TCBD_Google_Map_settings', 'http' ).'">Settings</a>';
		}
		
		$settings = array($settings_link);
		
		$actions = array_merge($settings, $actions);
			
	}
	
	return $actions;
	
}
add_filter( 'plugin_action_links', 'tcbd_google_map_plugin_action_links', 10, 5 );


// Include Settings page
include( plugin_dir_path(__FILE__).'inc/settings.php' );



function tcbd_fb_scripts(){
    
	// Google Map API
    wp_enqueue_script('tcbd-google-map',  '//maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7');
    
	// Latest jQuery WordPress
	wp_enqueue_script('jquery');

	// TCBD FB JS
	wp_enqueue_script('tcbd-maplace', TCBD_GOOGLE_MAP_PLUGIN_URL.'js/tcbd-maplace-0.1.3.min.js', array('jquery'), '0.1.3', true);

}
add_action('wp_enqueue_scripts', 'tcbd_fb_scripts');


// TCBD Alert Shortcode
function tcbd_google_map( $atts, $content = null  ) {
	extract( shortcode_atts( array(
		'height' => '300px',
		'width' => '100%'
	), $atts ) );
	return '<div style="height: '.$height.'; width: '.$width.';" id="gmap"></div><div id="controls"></div>';
}	
add_shortcode('tcbd-map', 'tcbd_google_map');


function tcbd_google_map_active(){
	
	if( get_option('tcbd_google_map_latitude') ){
		$map_latitude = get_option('tcbd_google_map_latitude');
	}else{
		$map_latitude = '23.810332';
	}
		
	if( get_option('tcbd_google_map_longitude') ){
		$map_longitude = get_option('tcbd_google_map_longitude');
	}else{
		$map_longitude = '90.412518';
	}	
		
	if( get_option('tcbd_google_map_zoom') ){
		$map_zoom = get_option('tcbd_google_map_zoom');
	}else{
		$map_zoom = '8';
	}	
	
	$show_marker = get_option( 'tcbd_google_map_marker' );
	
	$map_style = get_option( 'tcbd_google_map_style' );
	
	?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		new Maplace({
			show_markers: <?php if($show_marker==1): ?> true <?php else: ?> false <?php endif;?>,
			locations: [{
				lat: <?php echo $map_latitude; ?>, 
				lon: <?php echo $map_longitude; ?>,
				zoom: <?php echo $map_zoom; ?>
			}],
			<?php if($map_style=='on'): ?>
			styles: {
				'Night': [{
					featureType: 'all',
					stylers: [
						{ invert_lightness: 'true' }
					]
				}],
				'Greyscale': [{              
					featureType: 'all',
					stylers: [
						{ saturation: -100 },
						{ gamma: 0.50 }
					]
				}]
			}
			<?php endif; ?>
		}).Load(); 
	});
	</script>
	<?php
}
add_action('wp_footer', 'tcbd_google_map_active');
