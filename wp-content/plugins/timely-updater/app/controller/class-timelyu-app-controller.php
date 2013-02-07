<?php
//
//  class-timelyu-app-controller.php
//  timely-updater
//
//  Created by Timely Network Inc on 2012-07-26.
//

/**
 * Timelyu_App_Controller class
 *
 * @package Controllers
 * @author time.ly
 **/
class Timelyu_App_Controller {
	/**
	 * _instance class variable
	 *
	 * Class instance
	 *
	 * @var null | object
	 **/
	private static $_instance = NULL;

	/**
	 * get_instance function
	 *
	 * Return singleton instance
	 *
	 * @return object
	 **/
	static function get_instance() {
		if( self::$_instance === NULL ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * Default constructor - application initialization
	 **/
	private function __construct() {
		// register_activation_hook
		register_activation_hook( TIMELYU_PLUGIN_NAME . '/' . TIMELYU_PLUGIN_NAME . '.php', array( &$this, 'activation_hook' ) );

		// admin_menu hook
		add_action( 'admin_menu', array( &$this, 'admin_menu' ), 9 );
	}

	/**
	 * activation_hook function
	 *
	 * This function is called when activating the plugin
	 *
	 * @return void
	 **/
	function activation_hook() {}

	/**
	 * admin_menu function
	 * Display the admin menu items using the add_menu_page WP function.
	 *
	 * @return void
	 */
	function admin_menu() {
		// ========================
		// = Timely Updater Page =
		// ========================
		add_submenu_page(
				'options-general.php',
				__( 'Timely Updater', TIMELYU_PLUGIN_NAME ),
				__( 'Timely Updater', TIMELYU_PLUGIN_NAME ),
				'update_plugins',
				TIMELYU_PLUGIN_NAME . '-upgrade',
				array( &$this, 'upgrade' )
		);
		// add timely updater submenu to Events menu if
		// plugin is active
		if( is_plugin_active( 'all-in-one-event-calendar/all-in-one-event-calendar.php') ) {
			// ========================
			// = Timely Updater Page =
			// ========================
			add_submenu_page(
					'edit.php?post_type=ai1ec_event',
					__( 'Timely Updater', TIMELYU_PLUGIN_NAME ),
					__( 'Timely Updater', TIMELYU_PLUGIN_NAME ),
					'update_plugins',
					TIMELYU_PLUGIN_NAME . '-upgrade',
					array( &$this, 'upgrade' )
			);
		}
	}

	/**
	 * upgrade function
	 *
	 * @return void
	 **/
	function upgrade() {
		// continue only if user can update plugins
		if ( ! current_user_can( 'update_plugins' ) ) {
			wp_die( __( 'You do not have sufficient permissions to update plugins for this site.' ) );
		}

		$response = wp_remote_get( TIMELYU_UPDATES_URL );
		if( 
			! is_wp_error( $response )             &&
			isset( $response['response'] )         &&
			isset( $response['response']['code'] ) &&
			$response['response']['code'] == 200   &&
			isset( $response['body'] )             &&
			! empty( $response['body'] ) 
		) {
			// continue only if there is a result
			$updater = json_decode( $response['body'] );

			// get plugin data in array
			$old_plugin = get_plugin_data( WP_PLUGIN_DIR . '/' . $updater->plugin_name, false, true );

			if( ! isset( $old_plugin['Name'] ) || empty( $old_plugin['Name'] ) ) {
				wp_die( __( 'We could not locate All-in-one Event Calendar plugin. Did you install it?' ) );
			}

			$is_premium = false;
			$is_active = false;

			if( 'PREMIU' == strtoupper( substr( $old_plugin["Version"], -7, -1 ) ) ) {
				$is_premium = true;
			}
			if( is_plugin_active( $updater->plugin_name ) ) {
				$is_active = true;
			}

			$old = str_replace( '-PREMIUM', '', strtoupper( $old_plugin['Version'] ) );
			$old = str_replace( ' PREMIUM', '', strtoupper( $old_plugin['Version'] ) );
			$old = trim(str_replace( 'PREMIUM', '', strtoupper( $old_plugin['Version'] ) ));
			$new = isset( $updater->version ) ? $updater->version : $old_plugin['Version'];
			if( strpos( strtoupper( $old_plugin['Version'] ), 'PREMIUM' ) !== FALSE ) {
				$new = str_replace( '-PREMIUM', '', strtoupper( $new ) );
				$new = str_replace( ' PREMIUM', '', strtoupper( $new ) );
				$new = trim(str_replace( 'PREMIUM', '', strtoupper( $new ) ));
				if( ( version_compare( $old, $new ) != -1 ) ) {
					wp_die( __( 'You are running the latest version of All-in-one Event Calendar plugin.' ) );
				}
			}

			// use our custom class
			$upgrader = new Timelyu_Updater();
			// update the plugin
			$upgrader->upgrade( $updater->plugin_name, $updater->package, $is_premium, $is_active );

		} else {
			wp_die( __( 'The following error occurred: ' . $response->get_error_message() ) );
		}
	}
}