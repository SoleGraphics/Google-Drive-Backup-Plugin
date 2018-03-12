<?php

/**
 	Plugin Name: Dead Simple Wordpress Google Drive Backups
	Plugin URI:
	Description: Simple site backup of your database and uploads directory to Google Drive
	Author: Sole Graphics
	Author URI: http://www.solegraphics.com/
	Version: 1
	License: MIT
	Contributors: Ben Greene,
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

class Sole_Google_Backups {

	public function __construct() {

		$this->sole_logger = Sole_Google_Logger::get_instance();

		// Controls interactions with the Google API
		$this->sole_google_controller = Google_Controller::get_instance();

		// Controller for displaying admin interfaces
		$this->sole_google_admin_controller = new Admin_Interface_Controller();

		// The schedule controller for setting up & executing CRON jobs
		$this->sole_google_schedule_controller = new Sole_Schedule_Controller();

		// Controller for dealing with creating the files to upload
		$this->sole_google_file_controller = new Sole_WP_Files_Controller();

		// Add plugin hooks
		register_activation_hook( __FILE__, array( $this->sole_logger, 'build_database' ) );

		add_action( 'init', array( $this, 'upload_files_to_drive' ) );
		add_action( 'init', array( $this, 'check_manual_actions' ) );
	}


	// Once users are set, attempt to upload the google auth file
	public function upload_files_to_drive() {
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
	}

	// Check for the various manual admin triggers
	public function check_manual_actions() {
		if( ! is_admin() ) {
			return;
		}

		// Check for manual backup trigger
		if( isset( $_POST['manual-sole-google-backup-trigger'] ) &&
			'true' === $_POST['manual-sole-google-backup-trigger'] ) {
			$this->sole_google_controller->backup_sql_to_drive();
			$this->sole_google_controller->backup_uploads_to_drive();
		}

		// Check for clear drive trigger
		if( isset( $_POST['manual-sole-google-clear-drive-trigger'] ) &&
			'true' === $_POST['manual-sole-google-clear-drive-trigger'] ) {
			$this->sole_google_controller->delete_old_backups();
		}

		// Check for db dump backup download
		if( isset( $_POST['manual-sole-google-download-db-dump'] ) &&
			'true' === $_POST['manual-sole-google-download-db-dump'] ) {
			$this->sole_google_file_controller->download_db_dump();
		}

		// Check for wp uploads backup download
		if( isset( $_POST['manual-sole-google-download-uploads-zip'] ) &&
			'true' === $_POST['manual-sole-google-download-uploads-zip'] ) {
			$this->sole_google_file_controller->download_uploads_dump();
		}
	}
}

new Sole_Google_Backups();
