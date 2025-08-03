#!/usr/bin/env python3
"""
WordPress Plugin Installer for YouTuneAI
Automated installation of required WordPress plugins for AI integration

Copyright (c) 2025 Mr. Swain (3000Studios)
All rights reserved. Part of YouTuneAI voice-controlled website technology.
"""

import os
import sys
import requests
import zipfile
import tempfile
import paramiko
from pathlib import Path
from typing import Dict, List, Any

class WordPressPluginInstaller:
    def __init__(self, sftp_config: Dict[str, str]):
        """Initialize with SFTP configuration"""
        self.sftp_config = sftp_config
        
        # Required plugins for AI automation
        self.plugins = {
            'wp-rest-api-controller': {
                'name': 'WP REST API Controller',
                'url': 'https://downloads.wordpress.org/plugin/wp-rest-api-controller.latest-stable.zip',
                'purpose': 'Expose custom post types to REST API',
                'required': True
            },
            'wp-webhooks': {
                'name': 'WP Webhooks',
                'url': 'https://downloads.wordpress.org/plugin/wp-webhooks.latest-stable.zip',
                'purpose': 'Trigger deployments and WordPress actions',
                'required': True
            },
            'advanced-custom-fields': {
                'name': 'Advanced Custom Fields',
                'url': 'https://downloads.wordpress.org/plugin/advanced-custom-fields.latest-stable.zip',
                'purpose': 'Dynamic content creation and meta fields',
                'required': True
            },
            'woocommerce': {
                'name': 'WooCommerce',
                'url': 'https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip',
                'purpose': 'E-commerce functionality for add_product commands',
                'required': True
            },
            'custom-post-type-ui': {
                'name': 'Custom Post Type UI',
                'url': 'https://downloads.wordpress.org/plugin/custom-post-type-ui.latest-stable.zip',
                'purpose': 'Custom content types for bot commands',
                'required': True
            },
            'code-snippets': {
                'name': 'Code Snippets',
                'url': 'https://downloads.wordpress.org/plugin/code-snippets.latest-stable.zip',
                'purpose': 'Dynamic hooks and PHP logic for AI',
                'required': True
            },
            'wp-security-audit-log': {
                'name': 'WP Activity Log',
                'url': 'https://downloads.wordpress.org/plugin/wp-security-audit-log.latest-stable.zip',
                'purpose': 'Monitor AI changes and security',
                'required': False
            }
        }

    def download_plugin(self, plugin_slug: str, plugin_info: Dict[str, Any]) -> str:
        """Download plugin ZIP file"""
        try:
            print(f"📥 Downloading {plugin_info['name']}...")
            
            response = requests.get(plugin_info['url'], timeout=60)
            response.raise_for_status()
            
            # Save to temporary file
            temp_dir = tempfile.mkdtemp()
            zip_path = os.path.join(temp_dir, f"{plugin_slug}.zip")
            
            with open(zip_path, 'wb') as f:
                f.write(response.content)
            
            print(f"✅ Downloaded {plugin_info['name']} ({len(response.content)} bytes)")
            return zip_path
            
        except Exception as e:
            print(f"❌ Failed to download {plugin_slug}: {str(e)}")
            raise

    def upload_plugin(self, local_zip_path: str, plugin_slug: str) -> bool:
        """Upload plugin to WordPress via SFTP"""
        try:
            print(f"📤 Uploading {plugin_slug} to WordPress...")
            
            # Connect via SFTP
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            ssh.connect(
                hostname=self.sftp_config['host'],
                port=self.sftp_config['port'],
                username=self.sftp_config['username'],
                password=self.sftp_config['password'],
                timeout=30
            )
            
            sftp = ssh.open_sftp()
            
            # Create plugins directory if it doesn't exist
            try:
                sftp.mkdir('/wp-content/plugins')
            except:
                pass  # Directory might already exist
            
            # Upload ZIP file
            remote_zip_path = f"/wp-content/plugins/{plugin_slug}.zip"
            sftp.put(local_zip_path, remote_zip_path)
            
            # Extract ZIP file
            stdin, stdout, stderr = ssh.exec_command(
                f"cd /wp-content/plugins && unzip -o {plugin_slug}.zip && rm {plugin_slug}.zip"
            )
            
            # Check for errors
            exit_status = stdout.channel.recv_exit_status()
            if exit_status == 0:
                print(f"✅ Successfully uploaded and extracted {plugin_slug}")
                result = True
            else:
                error_output = stderr.read().decode()
                print(f"❌ Error extracting {plugin_slug}: {error_output}")
                result = False
            
            sftp.close()
            ssh.close()
            
            return result
            
        except Exception as e:
            print(f"❌ Failed to upload {plugin_slug}: {str(e)}")
            return False

    def install_all_plugins(self) -> Dict[str, bool]:
        """Install all required plugins"""
        results = {}
        
        print("🚀 Starting WordPress Plugin Installation")
        print("=" * 50)
        
        for plugin_slug, plugin_info in self.plugins.items():
            try:
                print(f"\n📦 Installing {plugin_info['name']}")
                print(f"   Purpose: {plugin_info['purpose']}")
                
                if not plugin_info['required']:
                    print("   (Optional plugin)")
                
                # Download plugin
                zip_path = self.download_plugin(plugin_slug, plugin_info)
                
                # Upload to WordPress
                success = self.upload_plugin(zip_path, plugin_slug)
                results[plugin_slug] = success
                
                # Clean up temp file
                try:
                    os.remove(zip_path)
                except:
                    pass
                
                if success:
                    print(f"✅ {plugin_info['name']} installed successfully")
                else:
                    print(f"❌ {plugin_info['name']} installation failed")
                    
            except Exception as e:
                print(f"❌ Failed to install {plugin_slug}: {str(e)}")
                results[plugin_slug] = False
        
        return results

    def create_activation_script(self) -> str:
        """Create PHP script to activate all plugins"""
        
        plugin_slugs = list(self.plugins.keys())
        
        activation_script = f'''<?php
/**
 * YouTuneAI Plugin Activation Script
 * Automatically activate all required plugins
 */

// Required plugins for AI automation
$required_plugins = array(
    {', '.join([f"'{slug}/{slug}.php'" for slug in plugin_slugs])}
);

// Activate plugins
foreach ($required_plugins as $plugin) {{
    if (!is_plugin_active($plugin)) {{
        $result = activate_plugin($plugin);
        if (is_wp_error($result)) {{
            echo "Failed to activate $plugin: " . $result->get_error_message() . "\\n";
        }} else {{
            echo "Activated $plugin successfully\\n";
        }}
    }} else {{
        echo "$plugin is already active\\n";
    }}
}}

// Set up basic configurations
update_option('blogdescription', 'Automated by AI — YouTuneAI Technology');

// Flush rewrite rules
flush_rewrite_rules();

// Create AI automation hooks
include_once 'ai-automation-hooks.php';

echo "\\nYouTuneAI plugin setup completed!\\n";
?>'''
        
        return activation_script

    def create_ai_hooks_file(self) -> str:
        """Create PHP file with AI automation hooks"""
        
        hooks_content = '''<?php
/**
 * YouTuneAI AI Automation Hooks
 * Custom WordPress hooks for AI voice control integration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register custom post type for AI commands
add_action('init', 'youtuneai_register_ai_command_post_type');
function youtuneai_register_ai_command_post_type() {
    register_post_type('ai_command', array(
        'public' => false,
        'show_in_rest' => true,
        'rest_base' => 'ai-commands',
        'supports' => array('title', 'editor', 'custom-fields'),
        'capability_type' => 'post',
        'labels' => array(
            'name' => 'AI Commands',
            'singular_name' => 'AI Command'
        )
    ));
}

// AJAX handler for AI voice commands
add_action('wp_ajax_nopriv_ai_voice_command', 'handle_ai_voice_command');
add_action('wp_ajax_ai_voice_command', 'handle_ai_voice_command');

function handle_ai_voice_command() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'youtuneai_nonce')) {
        wp_die('Security check failed', 403);
    }
    
    $command = sanitize_text_field($_POST['command']);
    $action = sanitize_text_field($_POST['action']);
    $params = $_POST['params'] ?? array();
    
    // Log AI command
    error_log("AI Voice Command: $command | Action: $action");
    
    // Execute based on action
    switch ($action) {
        case 'add_product':
            $result = youtuneai_add_woocommerce_product($params);
            break;
        case 'update_content':
            $result = youtuneai_update_content($params);
            break;
        case 'change_theme':
            $result = youtuneai_change_theme($params);
            break;
        default:
            $result = array('success' => false, 'message' => 'Unknown action');
    }
    
    wp_send_json($result);
}

// Add WooCommerce product via AI
function youtuneai_add_woocommerce_product($params) {
    if (!class_exists('WooCommerce')) {
        return array('success' => false, 'message' => 'WooCommerce not active');
    }
    
    $product = new WC_Product_Simple();
    $product->set_name($params['name'] ?? 'AI Generated Product');
    $product->set_regular_price($params['price'] ?? '9.99');
    $product->set_description($params['description'] ?? 'Created by AI voice command');
    $product->set_status('publish');
    
    // Add AI metadata
    $product->add_meta_data('_created_by_ai', true);
    $product->add_meta_data('_ai_creation_date', current_time('mysql'));
    
    $product_id = $product->save();
    
    if ($product_id) {
        return array(
            'success' => true, 
            'message' => "Product created with ID: $product_id",
            'product_id' => $product_id
        );
    } else {
        return array('success' => false, 'message' => 'Failed to create product');
    }
}

// Update content via AI
function youtuneai_update_content($params) {
    $post_id = $params['post_id'] ?? get_option('page_on_front');
    $content = $params['content'] ?? '';
    $title = $params['title'] ?? '';
    
    $post_data = array(
        'ID' => $post_id,
        'post_content' => $content,
        'post_title' => $title
    );
    
    $result = wp_update_post($post_data);
    
    if ($result) {
        return array('success' => true, 'message' => 'Content updated successfully');
    } else {
        return array('success' => false, 'message' => 'Failed to update content');
    }
}

// Change theme colors via AI
function youtuneai_change_theme($params) {
    $primary_color = $params['primary_color'] ?? '#667eea';
    $secondary_color = $params['secondary_color'] ?? '#764ba2';
    
    // Update theme customizer settings
    set_theme_mod('primary_color', $primary_color);
    set_theme_mod('secondary_color', $secondary_color);
    
    return array('success' => true, 'message' => 'Theme colors updated');
}

// Webhook handler for external AI triggers
add_action('wp_ajax_nopriv_ai_webhook', 'handle_ai_webhook');
add_action('wp_ajax_ai_webhook', 'handle_ai_webhook');

function handle_ai_webhook() {
    $secret = $_POST['secret'] ?? '';
    if ($secret !== 'youtuneai_webhook_2025') {
        wp_die('Unauthorized', 401);
    }
    
    $action = $_POST['action'] ?? '';
    $data = $_POST['data'] ?? array();
    
    // Process webhook action
    $result = apply_filters('youtuneai_webhook_action', array(), $action, $data);
    
    wp_send_json_success($result);
}

// Log all AI actions for security
add_action('youtuneai_action_executed', 'log_ai_action', 10, 2);
function log_ai_action($action, $data) {
    $log_entry = array(
        'timestamp' => current_time('mysql'),
        'action' => $action,
        'data' => $data,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    );
    
    // Save to options table
    $existing_logs = get_option('youtuneai_action_logs', array());
    $existing_logs[] = $log_entry;
    
    // Keep only last 100 logs
    $existing_logs = array_slice($existing_logs, -100);
    update_option('youtuneai_action_logs', $existing_logs);
    
    // Also log to file
    error_log('YouTuneAI Action: ' . json_encode($log_entry));
}

// Add REST API endpoints for AI controller
add_action('rest_api_init', 'youtuneai_register_rest_routes');
function youtuneai_register_rest_routes() {
    register_rest_route('youtuneai/v1', '/voice-command', array(
        'methods' => 'POST',
        'callback' => 'youtuneai_rest_voice_command',
        'permission_callback' => '__return_true'
    ));
}

function youtuneai_rest_voice_command($request) {
    $params = $request->get_json_params();
    
    // Process voice command
    $command = $params['command'] ?? '';
    $action = $params['action'] ?? '';
    
    // Execute action
    switch ($action) {
        case 'add_product':
            return youtuneai_add_woocommerce_product($params);
        case 'update_content':
            return youtuneai_update_content($params);
        default:
            return new WP_Error('invalid_action', 'Invalid action specified');
    }
}
?>'''
        
        return hooks_content

    def deploy_setup_files(self) -> bool:
        """Deploy activation script and hooks file"""
        try:
            print("📄 Creating WordPress setup files...")
            
            # Create activation script
            activation_script = self.create_activation_script()
            ai_hooks = self.create_ai_hooks_file()
            
            # Connect via SFTP
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            ssh.connect(
                hostname=self.sftp_config['host'],
                port=self.sftp_config['port'],
                username=self.sftp_config['username'],
                password=self.sftp_config['password'],
                timeout=30
            )
            
            sftp = ssh.open_sftp()
            
            # Upload activation script
            with sftp.open('/youtuneai-activate-plugins.php', 'w') as f:
                f.write(activation_script)
            
            # Upload AI hooks
            with sftp.open('/ai-automation-hooks.php', 'w') as f:
                f.write(ai_hooks)
            
            sftp.close()
            ssh.close()
            
            print("✅ Setup files deployed successfully")
            print("\n📋 Manual Steps Required:")
            print("1. Go to your WordPress admin panel")
            print("2. Navigate to yoursite.com/youtuneai-activate-plugins.php")
            print("3. This will activate all plugins automatically")
            print("4. Go to Plugins page to verify activation")
            
            return True
            
        except Exception as e:
            print(f"❌ Failed to deploy setup files: {str(e)}")
            return False

def main():
    """Main installation function"""
    
    # SFTP Configuration for YouTuneAI
    sftp_config = {
        'host': 'access-5017098454.webspace-host.com',
        'port': 22,
        'username': 'a104257',
        'password': 'Gabby3000!!!'
    }
    
    print("🚀 YouTuneAI WordPress Plugin Installer")
    print("Copyright (c) 2025 Mr. Swain (3000Studios)")
    print("=" * 60)
    
    installer = WordPressPluginInstaller(sftp_config)
    
    try:
        # Install all plugins
        results = installer.install_all_plugins()
        
        # Deploy setup files
        installer.deploy_setup_files()
        
        # Show results
        print("\n📊 Installation Results:")
        print("=" * 30)
        
        success_count = 0
        for plugin, success in results.items():
            status = "✅ Success" if success else "❌ Failed"
            print(f"{plugin}: {status}")
            if success:
                success_count += 1
        
        print(f"\n🎯 Summary: {success_count}/{len(results)} plugins installed successfully")
        
        if success_count == len([p for p in installer.plugins.values() if p['required']]):
            print("\n🎉 ALL REQUIRED PLUGINS INSTALLED!")
            print("Your AI automation stack is ready!")
        else:
            print("\n⚠️ Some required plugins failed to install.")
            print("Please check the errors above and try again.")
    
    except KeyboardInterrupt:
        print("\n⏹️ Installation cancelled by user.")
    except Exception as e:
        print(f"\n💥 Installation failed: {str(e)}")

if __name__ == "__main__":
    main()
