<?php
//
//  constants.php
//  timely-updater
//
//  Created by Timely Network Inc on 2012-07-26.
//

/**
 * timelyu_initial_constants function
 *
 * Initializes plugin constants
 *
 * @return void
 **/
function timelyu_initial_constants() {
	// ===============
	// = Plugin Name =
	// ===============
	define( 'TIMELYU_PLUGIN_NAME', 'timely-updater' );

	// ===================
	// = Plugin Basename =
	// ===================
	define( 'TIMELYU_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

	// ==================
	// = Plugin Version =
	// ==================
	define( 'TIMELYU_VERSION', '1.0' );

	// =======================
	// = Update cron version =
	// =======================
	define( 'TIMELYU_CRON_VERSION', 104 );

	// ================================
	// = Update check, cron frequency =
	// ================================
	define( 'TIMELYU_CRON_FREQ', 'hourly' );

	// ====================
	// = Update JSON file =
	// ====================
	define( 'TIMELYU_UPDATES_URL', 'http://time.ly/latest-version.json' );

	// ============
	// = Lib Path =
	// ============
	define( 'TIMELYU_LIB_PATH', TIMELYU_PATH . '/lib' );

	// =================
	// = Language Path =
	// =================
	define( 'TIMELYU_LANGUAGE_PATH', TIMELYU_PLUGIN_NAME . '/language' );

	// ============
	// = App Path =
	// ============
	define( 'TIMELYU_APP_PATH', TIMELYU_PATH . '/app' );

	// ===================
	// = Controller Path =
	// ===================
	define( 'TIMELYU_CONTROLLER_PATH', TIMELYU_APP_PATH . '/controller' );

	// ===============
	// = Helper Path =
	// ===============
	define( 'TIMELYU_HELPER_PATH', TIMELYU_APP_PATH . '/helper' );

	// ==================
	// = Exception Path =
	// ==================
	define( 'TIMELYU_EXCEPTION_PATH', TIMELYU_APP_PATH . '/exception' );

	// =============
	// = View Path =
	// =============
	define( 'TIMELYU_VIEW_PATH', TIMELYU_APP_PATH . '/view' );
}
