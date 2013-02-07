<?php
/**
 * Plugin Name: Timely Updater by Timely
 * Plugin URI: http://time.ly/
 * Description: An updater for all versions of all-in-one event calendar plugin
 * Author: Timely
 * Author URI: http://time.ly/
 * Version: 1.0
 */

// set execution time to unlimited, this is needed for updating the plugin
@set_time_limit( 0 );

/**
 * increase memory to 256M, WordPress also requires 256M in a 
 * couple places of wp-admin. We need to increase the memory to be 
 * able to process unzipping of plugin upgrade
 */
@ini_set( 'memory_limit', '256M' );

// same as set_time_limit
@ini_set( 'max_input_time', '-1' );

// ===============
// = Plugin Path =
// ===============
define( 'TIMELYU_PATH', dirname( __FILE__ ) );

// include constants file and continue only if the file exists
if ( file_exists( TIMELYU_PATH . DIRECTORY_SEPARATOR . 'constants.php' ) ) {
	// include constants file
	include_once( TIMELYU_PATH . DIRECTORY_SEPARATOR . 'constants.php' );

	// initialize constants
	timelyu_initial_constants();

	// ===============================
	// = The autoload function =
	// ===============================
	function timelyu_autoload( $class_name ) {
		// Convert class name to filename format.
		$class_name = strtr( strtolower( $class_name ), '_', '-' );
		$paths = array(
			TIMELYU_CONTROLLER_PATH,
			TIMELYU_HELPER_PATH,
			TIMELYU_EXCEPTION_PATH,
			TIMELYU_LIB_PATH,
			TIMELYU_VIEW_PATH
		);

		// remove duplicates from the paths array
		$paths = array_unique( $paths );

		// Search each path for the class.
		foreach( $paths as $path ) {
			if ( file_exists( "$path/class-$class_name.php" ) ) {
				require_once( "$path/class-$class_name.php" );
			}
		}
	}
	spl_autoload_register( 'timelyu_autoload' );
}

Timelyu_App_Controller::get_instance();