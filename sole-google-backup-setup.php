<?php

/**
 	Plugin Name: Dead Simple Wordpress Google Drive Backups
	Plugin URI:
	Description: Simple site backup of your database and uploads directory to Google Drive
	Author: Sole Graphics
	Author URI: http://www.solegraphics.com/
	Version: 0.1
	License:
 */

// Need to allow file uploads.
if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

// Require the Google API files (use the auto loader to load t all)
require( 'vendor/autoload.php' );

// Require core controllers
require( 'core/log-controller.php' );
require( 'core/file-controller.php' );
require( 'core/google-controller.php' );
require( 'core/admin-controller.php' );
require( 'core/schedule-controller.php' );


// Need to make sure that the log table is added.
$sole_logger = Sole_Google_Logger::get_instance();
register_activation_hook( __FILE__, array( $sole_logger, 'build_database' ) );


// Add the backup controller
$sole_google_controller           = new Google_Controller();

// Create the admin interface
$sole_google_admin_controller     = new Admin_Interface_Controller();

// Schedule controller
$sole_google_schedule_controller  = new Sole_Schedule_Controller( $sole_google_controller );


// Once users are set, attempt to upload the google auth file
add_action( 'init', function() {
	if( isset( $_FILES ) && isset( $_FILES['sg-google-auth-file'] ) ) {
		// Grab the Google auth file
		$google_auth_file = $_FILES['sg-google-auth-file'];
		// Make sure it's a json file
		if( 0 < strpos( $google_auth_file['name'], '.json' ) ) {
			$file_target = $google_auth_file['tmp_name'];
			$file_dest   = __DIR__ . '/google_auth.json';
			// Upload the file to this plugin's directory AND name it 'google_auth'
			move_uploaded_file( $file_target, $file_dest );
		}
	}
});


// Check for manual backup trigger
if( isset( $_POST['manual-sole-google-backup-trigger'] ) &&
	'true' === $_POST['manual-sole-google-backup-trigger'] ) {

	$sole_google_controller->backup_sql_to_drive();
	$sole_google_controller->backup_uploads_to_drive();
}

// Check for clear drive trigger
if( isset( $_POST['manual-sole-google-clear-drive-trigger'] ) &&
	'true' === $_POST['manual-sole-google-clear-drive-trigger'] ) {

	$sole_google_controller->delete_old_backups();
}
