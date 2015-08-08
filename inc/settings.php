<?php

	// Exit if accessed directly
	defined( 'ABSPATH' ) || exit;

	function TCBD_Google_Map_settings() {
		add_plugins_page( 'TCBD Google Map Settings', 'TCBD Google Map', 'update_core', 'TCBD_Google_Map_settings', 'tcbd_google_map_settings_page');
	}
	add_action( 'admin_menu', 'TCBD_Google_Map_settings' );
	
	function TCBD_Google_Map_register_settings() {
		register_setting( 'TCBD_Google_Map_register_setting', 'tcbd_google_map_latitude' );
		register_setting( 'TCBD_Google_Map_register_setting', 'tcbd_google_map_longitude' );
		register_setting( 'TCBD_Google_Map_register_setting', 'tcbd_google_map_zoom' );
		register_setting( 'TCBD_Google_Map_register_setting', 'tcbd_google_map_marker' );
		register_setting( 'TCBD_Google_Map_register_setting', 'tcbd_google_map_style' );
	}
	add_action( 'admin_init', 'TCBD_Google_Map_register_settings' );
		
	function tcbd_google_map_settings_page(){ // settings page function
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
		
		?>
			<div class="wrap">
				<h2>TCBD Google Map Settings</h2>
                
				<?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ){ ?>
					<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
						<p><strong>Settings saved.</strong></p>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>
				<?php } ?>
                
            	<form method="post" action="options.php">
                	<?php settings_fields( 'TCBD_Google_Map_register_setting' ); ?>
                    
                	<table class="form-table">
                		<tbody>
                        
                    		<tr>
                        		<th scope="row"><label for="tcbd_google_map_latitude">Latitude Number</label></th>
                            	<td>
                                    <input class="regular-text" name="tcbd_google_map_latitude" type="text" id="tcbd_google_map_latitude" value="<?php echo esc_attr( $map_latitude ); ?>">
                                    <p class="description">You may find Latitude Number and Longitude Number from <a href="http://www.latlong.net/">Here.</a></p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="tcbd_google_map_longitude">Longitude Number</label></th>
                            	<td>
                                    <input class="regular-text" name="tcbd_google_map_longitude" type="text" id="tcbd_google_map_longitude" value="<?php echo esc_attr( $map_longitude ); ?>">
                                    <p class="description">You may find Latitude Number and Longitude Number from <a href="http://www.latlong.net/">Here.</a></p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="tcbd_google_map_zoom">Map Zoom</label></th>
                            	<td>
                                    <input class="regular-text" name="tcbd_google_map_zoom" type="text" id="tcbd_google_map_zoom" value="<?php echo esc_attr( $map_zoom ); ?>">
								</td>
                        	</tr>
							                            
                    		<tr>
                        		<th scope="row"><label for="tcbd_google_map_marker">Show Marker</label></th>
                            	<td>
									<?php
										if( get_option( 'tcbd_google_map_marker' ) ){
											$show_marker = get_option( 'tcbd_google_map_marker' );
										}else{
											$show_marker = '0';
										}
									?>
									<label for="tcbd_google_map_marker">
										<input type="checkbox" id="tcbd_google_map_marker" name="tcbd_google_map_marker" value="1" <?php checked( 1, $show_marker ); ?> >
										You may turn on/off the Google Map Marker icon.
									</label>
								</td>
                        	</tr>
							                            
                    		<tr>
                        		<th scope="row"><label for="tcbd_google_map_marker">Map Style</label></th>
                            	<td>
									<?php
										if( get_option( 'tcbd_google_map_style' ) ){
											$map_style = get_option( 'tcbd_google_map_style' );
										}else{
											$map_style = 'off';
										}
									?>
									<label title="Trun On Google Map Style">
										<input type="radio" name="tcbd_google_map_style" value="on" <?php checked( $map_style, 'on' ); ?> > Enable
									</label><br>
									<label title="Trun Off Google Map Style">
										<input type="radio" name="tcbd_google_map_style" value="off" <?php checked( $map_style, 'off' ); ?> > Disable
									</label>
								</td>
                        	</tr>
                            
                    	</tbody>
                    </table>
                    
                    <p class="submit"><input id="submit" class="button button-primary" type="submit" name="submit" value="Save Changes"></p>
                </form>
                
            </div>
        <?php
	} // settings page function

?>