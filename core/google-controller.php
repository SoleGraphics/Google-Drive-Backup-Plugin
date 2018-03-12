<?php

/**
 * Handles connecting to the Google Libraries / using the Google API
 */
class Google_Controller {

	private $google_client;
	private $drive;
	private $logger;

	private static $instance;
	public static function get_instance() {
		if( null === self::$instance ) {
			self::$instance = new Google_Controller();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->file_controller = new Sole_WP_Files_Controller();
		$this->logger          = Sole_Google_Logger::get_instance();

		// Add Google auth file
		putenv('GOOGLE_APPLICATION_CREDENTIALS=' . plugin_dir_path( __DIR__ ) . 'google_auth.json');
	}

	// Setup Google Service Object
	public function setup_google_connection() {
		$this->google_client = new Google_Client();
		$this->google_client->useApplicationDefaultCredentials();
		$this->google_client->setScopes( 'https://www.googleapis.com/auth/drive' );
		$this->drive = new Google_Service_Drive( $this->google_client );
	}

	// Create zip of the uploads folder / upload to Google Drive
	public function backup_uploads_to_drive() {
		$upload_zip = $this->file_controller->get_wp_uploads_zip();

		if( ! $upload_zip ) {
			return false;
		}

		$this->backup_file_to_drive( $upload_zip['name'], $upload_zip['path'], 'application/zip' );

		// Delete file
		unlink( $upload_zip['path'] . $upload_zip['name'] );

		$this->logger->add_log_event( 'Backed up the uploads directory', 'WP Uploads Backup' );
	}

	// Create sql dump of the database / upload to Google Drive
	public function backup_sql_to_drive() {
		$dump_file = $this->file_controller->create_db_dump();

		// Check if there was an error creating the dump file
		if( ! $dump_file ) {
			return;
		}

		$this->backup_file_to_drive( $dump_file['name'], $dump_file['path'], 'application/sql' );

		// Delete the file
		unlink( $dump_file['path'] . $dump_file['name'] );

		$this->logger->add_log_event( 'Backed up the SQL', 'SQL Backup' );
	}

	// Backs up a single file the drive.
	public function backup_file_to_drive( $file_name, $file_path, $file_meta ) {
		$this->setup_google_connection();

		$file_metadata = new Google_Service_Drive_DriveFile(array(
			'name' => $file_name,
		) );

		$content = file_get_contents( $file_path . $file_name );

		$file = $this->drive->files->create($file_metadata, array(
	  		'data'       => $content,
		  	'mimeType'   => $file_meta,
	  		'uploadType' => 'multipart',
	  		'fields'     => 'id'));

		$this->give_user_file_permission( $file );
	}

	// Need to give the admin permissions to access the file uploaded.
	public function give_user_file_permission( $file ) {
		// Couuld be a Guzzle Object. No good.
		if( ! isset( $file->id ) ) {
			return;
		}

		// check if a user is entered.
		$admin_email = get_option( 'sole_google_drive_owner' );
		if( ! $admin_email ) {
			return;
		}

		$this->drive->getClient()->setUseBatch(true);
		try {
			$batch = $this->drive->createBatch();
			$user_permission = new Google_Service_Drive_Permission( array(
				'type'         => 'user',
				'role'         => 'writer',
				'emailAddress' => $admin_email,
			) );

	  		$request = $this->drive->permissions->create(
	    		$file->id, $user_permission, array('fields' => 'id')
	    	);

	    	$batch->add( $request, 'user' );
	  		$results = $batch->execute();
	  	} catch( Exception $e ) {
	  		error_log( $e->getMessage() );
	  	}
	}

	// Get free / used space in the drive
	public function get_drive_quota() {
		$this->setup_google_connection();

		// For use in displaying useage (dont want to display in bytes)
		$b_in_mb = 1048576;

		try{
			$about = $this->drive->about->get( array(
				'fields' => 'storageQuota',
			) );
			$storage_quota = $about->getStorageQuota();
			$used  = intval( $storage_quota->usage / $b_in_mb );
			$limit = intval( $storage_quota->limit / $b_in_mb );

			$to_return = "Used " . $used . ' of ' . $limit . ' MB of your drive space (' . ( round( $used / $limit, 3 ) * 100 ) . '%)';

			if( 0.8 <= ( $used / $limit ) ) {
				$to_return .= '<br/><b>You have used over 80% of your drive space! Please clear up space on the drive.</b>';
			}

			return $to_return;
		}
		catch( Exception $e ) {
			error_log( $e->getMessage() );
			return 'Error retrieving drive quota information';
		}
	}

	// Clear the drive of old backups
	public function delete_old_backups() {
		$this->setup_google_connection();

		// Get all files
		$options = array(
  			'pageSize' => 10000,
  			'fields'   => 'files(id)',
  		);

		$results = $this->drive->files->listFiles( $options );
		$all_files = $results->getFiles();

		// If there are no files, abort
		if( 0 == count( $all_files ) ) {
			return;
		}

		foreach ( $all_files as $file ) {
			$this->drive->files->delete( $file->getId() );
		}

		$this->logger->add_log_event( 'Cleared the Google Drive of old backups', 'Clear Drive' );
	}
}
