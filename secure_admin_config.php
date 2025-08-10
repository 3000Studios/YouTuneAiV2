<?php
/**
 * Secure Admin Configuration for YouTuneAI
 * Boss Man Copilot Security Hardening
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class YouTuneAI_Secure_Admin {
    
    private static $instance = null;
    private $admin_credentials = null;
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new YouTuneAI_Secure_Admin();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Load secure credentials from environment or secure file
        $this->load_secure_credentials();
    }
    
    private function load_secure_credentials() {
        // Try to load from environment variables first (most secure)
        $admin_email = getenv('YOUTUNEAI_ADMIN_EMAIL');
        $admin_password = getenv('YOUTUNEAI_ADMIN_PASSWORD');
        
        if ($admin_email && $admin_password) {
            $this->admin_credentials = [
                'username' => $admin_email,
                'password' => $admin_password
            ];
            return;
        }
        
        // Fallback to secure file (better than hardcoded)
        $secure_file = dirname(__FILE__) . '/.secure_admin';
        if (file_exists($secure_file)) {
            $credentials = json_decode(file_get_contents($secure_file), true);
            if ($credentials && isset($credentials['username'], $credentials['password'])) {
                $this->admin_credentials = $credentials;
                return;
            }
        }
        
        // Last resort: generate temporary credentials and log warning
        $temp_password = $this->generate_secure_password();
        $this->admin_credentials = [
            'username' => 'admin@youtuneai.com',
            'password' => $temp_password
        ];
        
        // Log warning about temporary credentials
        error_log("YouTuneAI Security Warning: Using temporary admin credentials. Please set YOUTUNEAI_ADMIN_EMAIL and YOUTUNEAI_ADMIN_PASSWORD environment variables.");
        error_log("Temporary password: " . $temp_password);
    }
    
    private function generate_secure_password($length = 16) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }
    
    public function authenticate($username, $password) {
        if (!$this->admin_credentials) {
            return false;
        }
        
        // Use hash comparison to prevent timing attacks
        $username_valid = hash_equals($this->admin_credentials['username'], $username);
        $password_valid = hash_equals($this->admin_credentials['password'], $password);
        
        return $username_valid && $password_valid;
    }
    
    public function get_admin_username() {
        return $this->admin_credentials ? $this->admin_credentials['username'] : null;
    }
    
    public function create_secure_session($username) {
        if (!session_id()) {
            session_start();
        }
        
        $_SESSION['youtuneai_admin_authenticated'] = true;
        $_SESSION['youtuneai_admin_username'] = $username;
        $_SESSION['youtuneai_admin_login_time'] = time();
        $_SESSION['youtuneai_admin_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        
        // Session timeout (1 hour)
        $_SESSION['youtuneai_admin_expires'] = time() + 3600;
    }
    
    public function is_authenticated() {
        if (!session_id()) {
            session_start();
        }
        
        if (!isset($_SESSION['youtuneai_admin_authenticated']) || 
            !$_SESSION['youtuneai_admin_authenticated']) {
            return false;
        }
        
        // Check session timeout
        if (time() > $_SESSION['youtuneai_admin_expires']) {
            $this->logout();
            return false;
        }
        
        // Check IP address (basic security)
        $current_ip = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($_SESSION['youtuneai_admin_ip'] !== $current_ip) {
            $this->logout();
            return false;
        }
        
        // Extend session if still active
        $_SESSION['youtuneai_admin_expires'] = time() + 3600;
        
        return true;
    }
    
    public function logout() {
        if (!session_id()) {
            session_start();
        }
        
        // Clear all admin session data
        unset($_SESSION['youtuneai_admin_authenticated']);
        unset($_SESSION['youtuneai_admin_username']);
        unset($_SESSION['youtuneai_admin_login_time']);
        unset($_SESSION['youtuneai_admin_ip']);
        unset($_SESSION['youtuneai_admin_expires']);
        
        return true;
    }
    
    public function get_csrf_token() {
        if (!session_id()) {
            session_start();
        }
        
        if (!isset($_SESSION['youtuneai_csrf_token'])) {
            $_SESSION['youtuneai_csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['youtuneai_csrf_token'];
    }
    
    public function verify_csrf_token($token) {
        if (!session_id()) {
            session_start();
        }
        
        return isset($_SESSION['youtuneai_csrf_token']) && 
               hash_equals($_SESSION['youtuneai_csrf_token'], $token);
    }
    
    public function log_security_event($event, $details = '') {
        $log_entry = [
            'timestamp' => date('c'),
            'event' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'details' => $details
        ];
        
        error_log("YouTuneAI Security: " . json_encode($log_entry));
    }
}

// AJAX handler for secure admin authentication
function youtuneai_handle_admin_auth() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'youtuneai_admin_auth')) {
        wp_send_json_error(['message' => 'Security verification failed']);
        return;
    }
    
    $admin = YouTuneAI_Secure_Admin::getInstance();
    
    $username = sanitize_email($_POST['username'] ?? '');
    $password = sanitize_text_field($_POST['password'] ?? '');
    
    if ($admin->authenticate($username, $password)) {
        $admin->create_secure_session($username);
        $admin->log_security_event('admin_login_success', $username);
        
        wp_send_json_success([
            'message' => 'Authentication successful',
            'csrf_token' => $admin->get_csrf_token()
        ]);
    } else {
        $admin->log_security_event('admin_login_failed', $username);
        wp_send_json_error(['message' => 'Invalid credentials']);
    }
}

function youtuneai_handle_admin_logout() {
    $admin = YouTuneAI_Secure_Admin::getInstance();
    
    if ($admin->is_authenticated()) {
        $username = $_SESSION['youtuneai_admin_username'] ?? 'unknown';
        $admin->logout();
        $admin->log_security_event('admin_logout', $username);
        wp_send_json_success(['message' => 'Logged out successfully']);
    } else {
        wp_send_json_error(['message' => 'Not authenticated']);
    }
}

// Register AJAX handlers
add_action('wp_ajax_youtuneai_admin_auth', 'youtuneai_handle_admin_auth');
add_action('wp_ajax_nopriv_youtuneai_admin_auth', 'youtuneai_handle_admin_auth');
add_action('wp_ajax_youtuneai_admin_logout', 'youtuneai_handle_admin_logout');

?>