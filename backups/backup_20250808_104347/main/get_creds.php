<?php
// Define a secret key for basic security.
// This should match the key in the Python script.
define('CORS_SECRET_KEY', 'your-super-secret-key-for-dev');

if (!isset($_GET['secret']) || $_GET['secret'] !== CORS_SECRET_KEY) {
    header('HTTP/1.0 403 Forbidden');
    die('Access denied');
}

// Load WordPress environment
// This path assumes the script is in the theme root. Adjust if necessary.
require_once(dirname(__FILE__) . '/../../../wp-load.php');

// Set header to return JSON
header('Content-Type: application/json');

// Fetch credentials from WordPress options

$credentials = array(
    'sftp_host' => 'your_host',
    'sftp_user' => 'your_username',
    'sftp_pass' => 'your_password'
);

// Echo credentials as JSON
echo json_encode($credentials);
