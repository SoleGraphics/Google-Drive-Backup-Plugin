<div class="wrap">
	<h1>Dead Simple Google Drive Backups</h1>
	<hr/>
	<div class="quota">
		<h2>Info</h2>
		<?php echo $sole_google_controller->get_drive_quota(); ?>
	</div>
	<hr/>
	<form method="POST" action="options.php" enctype="multipart/form-data">
		<?php settings_fields( self::SETTINGS_GROUP ); ?>
		<?php do_settings_sections( 'sole-settings-page' ); ?>
		<h2>Settings</h2>
		<table>
			<tr>
				<td>Google Auth File</td>
				<td><input id="sg-google-auth-file" name="sg-google-auth-file" type="file" /></td>
				<?php if( file_exists( plugin_dir_path(__DIR__) . '/google_auth.json' ) ): ?>
					<td><b>There is already an auth file uploaded!</b></td>
				<?php endif; ?>
			</tr>
			<tr>
				<td>Drive Owner</td>
				<td>
					<?php $current_val = get_option( 'sole_google_drive_owner' ); ?>
					<input type="text" name="sole_google_drive_owner" value="<?php echo $current_val; ?>" />
				</td>
			</tr>
			<tr>
				<td>Database Backup Time</td>
				<td><input type="text" name="sole_google_db_backup_timestamp" value="<?php echo get_option( 'sole_google_db_backup_timestamp' ); ?>" /></td>
				<td>Enter time in a 24 hour "HH:MM" format</td>
			</tr>
			<tr>
				<td>Database Backup Frequency</td>
				<td><select name="sole_google_db_frequency">
					<?php $selected = get_option( 'sole_google_db_frequency' ); ?>
					<?php foreach( $this->backup_options as $option ): ?>
						<option value="<?php echo $option; ?>" <?php if($option == $selected){ echo 'selected="selected"'; } ?>><?php echo $option; ?></option>
					<?php endforeach; ?>
				</select></td>
			</tr>
			<tr>
				<td>Uploads Backup Time</td>
				<td><input type="text" name="sole_google_uploads_backup_timestamp" value="<?php echo get_option( 'sole_google_uploads_backup_timestamp' ); ?>"/></td>
			</tr>
			<tr>
				<td>Uploads Backup Frequency</td>
				<td><select name="sole_google_uploads_frequency">
					<?php $selected = get_option( 'sole_google_uploads_frequency' ); ?>
					<?php foreach( $this->backup_options as $option ): ?>
						<option value="<?php echo $option; ?>" <?php if($option == $selected){ echo 'selected="selected"'; } ?>><?php echo $option; ?></option>
					<?php endforeach; ?>
				</select></td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
	<hr/>
	<h2>Extras</h2>
	<form method="POST" action="">
		<input type="hidden" name="manual-sole-google-backup-trigger" value="true" />
		<?php submit_button( 'Backup Files & Database' ); ?>
	</form>
	<form method="POST" action="">
		<input type="hidden" name="manual-sole-google-download-db-dump" value="true" />
		<?php submit_button( 'Download Database Dump' ); ?>
	</form>
	<form method="POST" action="">
		<input type="hidden" name="manual-sole-google-download-uploads-zip" value="true" />
		<?php submit_button( 'Download Uploads Directory' ); ?>
	</form>
	<hr/>
	<form method="POST" action="">
		<input type="hidden" name="manual-sole-google-clear-drive-trigger" value="true" />
		<?php submit_button( 'Clear Drive.' ); ?>
		<p><b>It is strongly recommended that you use the manual backup function right after clearing the drive.</b></p>
	</form>
</div>
