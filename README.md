# Google Drive WP Backups.

## Development Dependencies

This plugin uses composer! You will to run `composer install` in the plugin directory before you can use it! (This is a developer repo, not a production repo).

## Connecting to Google Drive

To use this plugin you will also need to create a Google Service Account (you can do this from [Google Console](https://console.cloud.google.com)).

In the Right hand side navigation, click on the "Credentials" tab. Under "Create credentials" click on the "Service Account Key" link. You'll likely want to create a new service account, and you'll also want to set the key type to JSON (it's the default). When you create it, you'll be given a JSON file that has the service account credentials. KEEP IT! You'll need this for later.

Next go to the "Library" tab in the Google Console. Then search for the "Google Drive API", and enable it. Now your service account is ready to go!

In the WordPress dashboard, go to 'Google Backup' > 'Settings', and upload that JSON file for the service account you saved (mentioned above). Then, add the email address of an account you'll want to share the file with.
