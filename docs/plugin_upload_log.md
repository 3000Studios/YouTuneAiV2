# Plugin Upload Log

## Extraction Results

The following plugins were identified in the `plugins/` directory after attempting to extract `wp_plugin_sync_bundle.zip`:

- advanced-custom-fields
- woocommerce
- wp-webhooks
- custom-post-type-ui
- wp-rest-api-controller
- wp-security-audit-log
- code-snippets

Each plugin contains an `index.php` file, confirming their presence.

## SFTP Configuration

The `.vscode/sftp.json` file contains the following configuration:

- **Host:** access-5017098454.webspace-host.com
- **Username:** a132096
- **Protocol:** sftp
- **Port:** 22
- **Remote Path:** /wp-content/plugins/
- **uploadOnSave:** false

## Upload Instructions

Direct SFTP upload via this assistant is not possible. To upload the plugins manually:

1. Open your SFTP client (such as FileZilla or WinSCP).
2. Connect using the credentials above.
3. Navigate to the `/wp-content/plugins/` directory on the remote server.
4. Upload each plugin folder from your local `plugins/` directory.
5. Verify the upload is complete and the plugins appear in your WordPress admin panel.

---

*This log documents the extraction and upload preparation process for the plugin bundle.*
