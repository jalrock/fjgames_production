<?php
/*
Plugin Name: User Map
Plugin URI: http://jackwilliams.us
Description: Generate a map based on user location and activity.
Version: 0.1
Author: Jack Williams
Author Email: jack@jackwilliams.us
License:

  Copyright 2011 Jack Williams (jack@jackwilliams.us)

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

class UserMap {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'User Map';
	const slug = 'user_map';
	
	/**
	 * Constructor
	 */
	function __construct() {
		//register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_user_map' ) );

		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_user_map' ) );
	}
  
	/**
	 * Runs when the plugin is activated
	 */  
	function install_user_map() {
		// do not generate any output here
	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function init_user_map() {
		wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
		// Setup localization
		load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();

		// Register the shortcode [user_map_code]
		add_shortcode( 'user_map_code', array( &$this, 'render_shortcode' ) );
	
		if ( is_admin() ) {
			//this will run when in the WordPress admin
		} else {
			//this will run when on the frontend
			
		}

		/*
		 * TODO: Define custom functionality for your plugin here
		 *
		 * For more information: 
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'your_action_here', array( &$this, 'action_callback_method_name' ) );
		add_filter( 'your_filter_here', array( &$this, 'filter_callback_method_name' ) );    
	}

	function action_callback_method_name() {
		// TODO define your action method here
	}

	function filter_callback_method_name() {
		// TODO define your filter method here
	}

	function render_shortcode($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'height' => 'foo', //foo is a default value
			'width' => 'bar'
			), $atts));
		// Get current user
		$current_user = wp_get_current_user();
		$user_meta = get_user_meta( $current_user->ID );
		$atts['lat_lon'] = $user_meta['user_lat_lon'][0];
		print_r($atts);
		echo $this->draw_map($atts);
		// you can now access the attribute values using $attr1 and $attr2
	}
  
	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	private function register_scripts_and_styles() {
		if ( is_admin() ) {
			$this->load_file( self::slug . '-admin-script', '/js/admin.js', true );
			$this->load_file( self::slug . '-admin-style', '/css/admin.css' );
		} else {
			$this->load_file( self::slug . '-script', '/js/widget.js', true );
			$this->load_file( self::slug . '-style', '/css/widget.css' );
		} // end if/else
	} // end register_scripts_and_styles
	
	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name	The 	ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_file( $name, $file_path, $is_script = false ) {

		$url = plugins_url($file_path, __FILE__);
		$file = plugin_dir_path(__FILE__) . $file_path;

		if( file_exists( $file ) ) {
			if( $is_script ) {
				wp_register_script( $name, $url, array('jquery') ); //depends on jquery
				wp_enqueue_script( $name );
			} else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			} // end if
		} // end if

	} // end load_file

	function draw_map($data) {
		//$html = '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>';
		$html .= '<style>#user-map-canvas { height:'.$data['height'].'; width:'.$data['width'].'}</style>';
		$html .= '<div id="user-map-canvas"></div>';
		$html .= '<script>';
		
		$html .= '	var map;
					function initialize() {
					  var mapOptions = {
					    zoom: 11,
					    center: new google.maps.LatLng('.$data['lat_lon'].')
					  };
					  map = new google.maps.Map(document.getElementById(\'user-map-canvas\'),
					      mapOptions);';
		$html .= $this->map_circles($data);
		$html .= '}'.PHP_EOL;

		$html .= 'google.maps.event.addDomListener(window, \'load\', initialize);'.PHP_EOL;
		$html .= '</script>';
		return $html;
	}

	function map_circles($data) {
		
		$script = '	var thecenter = new google.maps.LatLng('.$data['lat_lon'].');
					var populationOptions = {
					    strokeColor: \'#FF0000\',
					    strokeOpacity: 0.8,
					    strokeWeight: 2,
					    fillColor: \'#FF0000\',
					    fillOpacity: 0.35,
					    map: map,
					    center: thecenter,
					    radius: 5000
	    			};';

	    $script .= 'console.log(populationOptions)
	    // Add the circle for this city to the map.
	    cityCircle = new google.maps.Circle(populationOptions);';
	    return $script;
	}
  
} // end class
new UserMap();

?>