<?php

class Admin_Interface_Controller {

	const SETTINGS_PAGE_SLUG  = 'google_backups_settings_page';
	const SETTINGS_GROUP      = 'google_backups_group';

	private $plugin_settings = array(
		'sole_google_drive_owner',
		'sole_google_db_backup_timestamp',
		'sole_google_db_frequency',
		'sole_google_uploads_backup_timestamp',
		'sole_google_uploads_frequency',
	);

	private $backup_options = array(
		'daily', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
	);

	public function __construct() {
		// Add the admin views
		add_action( 'admin_menu', array( $this, 'add_admin_menu') );
		add_action( 'admin_init', array( $this, 'register_plugin_settings') );
	}

	public function add_admin_menu() {
		add_menu_page( 'Google Backup', 'Google Backup', 'administrator', self::SETTINGS_PAGE_SLUG, '', 'dashicons-analytics' );

		// Register submenu for plugin settings - default page for the plugin
		add_submenu_page( self::SETTINGS_PAGE_SLUG, 'Google Backup Settings', 'Settings', 'administrator', self::SETTINGS_PAGE_SLUG, array( $this, 'display_settings_page' ) );

		// Register submenu for log page
		add_submenu_page( self::SETTINGS_PAGE_SLUG, 'Google Backup Logs', 'Logs', 'administrator', self::SETTINGS_PAGE_SLUG . '-logs', array( $this, 'display_logs' ) );

		// Register submenu for README page
		add_submenu_page( self::SETTINGS_PAGE_SLUG, 'README', 'Help', 'administrator', self::SETTINGS_PAGE_SLUG . '-readme', array( $this, 'display_readme' ) );
	}

	public function register_plugin_settings() {
		foreach ( $this->plugin_settings as $setting ) {
			register_setting( self::SETTINGS_GROUP, $setting );
		}
	}

	public function display_settings_page() {
		$sole_google_controller = Google_Controller::get_instance();
		include plugin_dir_path( __DIR__ ) . 'templates/settings-form.php';
	}

	public function display_logs() {
		include plugin_dir_path( __DIR__ ) . 'templates/log-file.php';
	}

	public function display_readme() {
		include plugin_dir_path( __DIR__ ) . 'templates/readme.php';
	}
}
