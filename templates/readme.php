<div class="wrap">
	<h1>Dead Simple Google Drive Backups</h1>
	<hr/>
	<h2>Purpose of the Plugin</h2>
	<p>This plugin is meant to make automated backing up of your uploads directory and WordPress database to Google Drive as simple as possible. Backups are automatically done by the plugin, and you are notified after backups are made.</p><p> Essentially, after initial setup, you can forget about the plugin!</p>
	<h2>Setup</h2>
	<p>There are several steps to setting up the plugin (due to the security around Google Drive). The first step is to setup your <a href="https://console.developers.google.com/">Google Developer Console</a>. <b>NOTE</b>: you do NOT need to pay the fee for becoming a Google Play developer! This plugin doesn't interact with Google Play.</p>
	<p>In the Right hand side navigation, click on the "Credentials" tab. Under "Create credentials" click on the "Service Account Key" link. You'll likely want to create a new service account, and you'll also want to set the key type to JSON (it's the default). When you create it, you'll be given a JSON file that has the service account credentials. KEEP IT! You'll need this for later.</p>
	<p>Next go to the "Library" tab in the Google Console. Then search for the "Google Drive API", and enable it. Now your service account is ready to go!</p>
	<h2>Settings</h2>
	<p>You'll need to add some settings to get the backups working, so after plugin activation, head over to the plugin's settings page.</p>
	<h4>Required Settings</h4>
	<p>First, upload that JSON file for the service account you saved (mentioned above). Then, add the email address of an account you'll want to share the file with.</p>
	<h4>Optional Settings</h4>
	<p>The rest of the settings are to determine how often you want the plugin to backup the database and uploads directory. If there is no time entered, then the plugin will NOT automatically create backups. It is strongly recommended you add a time to enable automated backups.</p>
	<h2>Contributing</h2>
	<p>There are two different ways to contribute:</p>
	<ol>
		<li>You can report any issues found on the <a href="">plugin's repository page</a>. Please give as detailed a report about what you were doing and what happened as you can.</li>
		<li>Developers, feel free to fork the repo - make your changes - create a pull request. When you do PLEASE add a detailed description of both what your changes are AND the reason for them.</li>
	</ol>
</div>
