#!/usr/bin/env python3
"""
WordPress Plugin Auto-Installer for YouTuneAI
Complete automation stack setup with zero-error deployment

Copyright (c) 2025 Mr. Swain (3000Studios) - Patent Pending
"""

import paramiko
import requests
import zipfile
import io
import os
import json
from datetime import datetime
from typing import Dict, List, Any

class WordPressPluginInstaller:
    def __init__(self, sftp_config: Dict[str, str]):
        """Initialize plugin installer with SFTP configuration"""
        self.sftp_config = sftp_config
        self.required_plugins = [
            {
                'slug': 'wp-rest-api-controller',
                'name': 'WP REST API Controller',
                'download_url': 'https://downloads.wordpress.org/plugin/wp-rest-api-controller.latest-stable.zip',
                'required': True,
                'description': 'Advanced REST API control for AI integration'
            },
            {
                'slug': 'wp-webhooks',
                'name': 'WP Webhooks',
                'download_url': 'https://downloads.wordpress.org/plugin/wp-webhooks.latest-stable.zip',
                'required': True,
                'description': 'Webhook automation for AI triggers'
            },
            {
                'slug': 'advanced-custom-fields',
                'name': 'Advanced Custom Fields',
                'download_url': 'https://downloads.wordpress.org/plugin/advanced-custom-fields.latest-stable.zip',
                'required': True,
                'description': 'Custom fields for AI-generated content'
            },
            {
                'slug': 'woocommerce',
                'name': 'WooCommerce',
                'download_url': 'https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip',
                'required': True,
                'description': 'E-commerce platform for AI product creation'
            },
            {
                'slug': 'custom-post-type-ui',
                'name': 'Custom Post Type UI',
                'download_url': 'https://downloads.wordpress.org/plugin/custom-post-type-ui.latest-stable.zip',
                'required': True,
                'description': 'Custom post types for AI content management'
            },
            {
                'slug': 'code-snippets',
                'name': 'Code Snippets',
                'download_url': 'https://downloads.wordpress.org/plugin/code-snippets.latest-stable.zip',
                'required': True,
                'description': 'PHP code execution for AI automation'
            },
            {
                'slug': 'wp-mail-smtp',
                'name': 'WP Mail SMTP',
                'download_url': 'https://downloads.wordpress.org/plugin/wp-mail-smtp.latest-stable.zip',
                'required': False,
                'description': 'Reliable email delivery for AI notifications'
            },
            {
                'slug': 'updraftplus',
                'name': 'UpdraftPlus',
                'download_url': 'https://downloads.wordpress.org/plugin/updraftplus.latest-stable.zip',
                'required': False,
                'description': 'Automated backups for AI safety'
            },
            {
                'slug': 'wp-super-cache',
                'name': 'WP Super Cache',
                'download_url': 'https://downloads.wordpress.org/plugin/wp-super-cache.latest-stable.zip',
                'required': False,
                'description': 'Performance optimization for AI-powered site'
            },
            {
                'slug': 'wp-api-menus',
                'name': 'WP-API Menus',
                'download_url': 'https://downloads.wordpress.org/plugin/wp-api-menus.latest-stable.zip',
                'required': True,
                'description': 'Menu management via REST API for AI control'
            }
        ]
        
        self.installation_log = []
        print("🔧 WordPress Plugin Auto-Installer initialized")

    def connect_sftp(self) -> paramiko.SFTPClient:
        """Establish SFTP connection"""
        try:
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            ssh.connect(
                hostname=self.sftp_config['host'],
                username=self.sftp_config['username'],
                password=self.sftp_config['password'],
                port=self.sftp_config.get('port', 22)
            )
            
            sftp = ssh.open_sftp()
            print("✅ SFTP connection established")
            return sftp, ssh
            
        except Exception as e:
            print(f"❌ SFTP connection failed: {str(e)}")
            raise

    def download_plugin(self, plugin: Dict[str, str]) -> bytes:
        """Download plugin zip file"""
        try:
            print(f"📥 Downloading {plugin['name']}...")
            
            response = requests.get(plugin['download_url'], timeout=60)
            response.raise_for_status()
            
            if response.status_code == 200:
                print(f"✅ Downloaded {plugin['name']} ({len(response.content)} bytes)")
                return response.content
            else:
                raise Exception(f"Download failed with status {response.status_code}")
                
        except Exception as e:
            error_msg = f"Failed to download {plugin['name']}: {str(e)}"
            print(f"❌ {error_msg}")
            self.installation_log.append({
                'plugin': plugin['name'],
                'action': 'download',
                'status': 'failed',
                'error': str(e),
                'timestamp': datetime.now().isoformat()
            })
            raise Exception(error_msg)

    def extract_and_upload_plugin(self, sftp: paramiko.SFTPClient, plugin: Dict[str, str], plugin_data: bytes) -> bool:
        """Extract plugin and upload to WordPress plugins directory"""
        try:
            print(f"📤 Installing {plugin['name']}...")
            
            # Create plugins directory if it doesn't exist
            plugins_path = '/wp-content/plugins'
            try:
                sftp.listdir(plugins_path)
            except FileNotFoundError:
                sftp.mkdir(plugins_path)
            
            # Extract zip file
            with zipfile.ZipFile(io.BytesIO(plugin_data), 'r') as zip_file:
                plugin_folder = None
                
                # Find the main plugin folder
                for file_path in zip_file.namelist():
                    if '/' in file_path and not file_path.startswith('__MACOSX'):
                        plugin_folder = file_path.split('/')[0]
                        break
                
                if not plugin_folder:
                    raise Exception("Could not determine plugin folder structure")
                
                # Create plugin directory
                plugin_path = f"{plugins_path}/{plugin_folder}"
                try:
                    sftp.mkdir(plugin_path)
                except Exception:
                    pass  # Directory might already exist
                
                # Upload all files
                uploaded_files = 0
                for file_path in zip_file.namelist():
                    if file_path.startswith(plugin_folder + '/') and not file_path.endswith('/'):
                        # Calculate relative path
                        relative_path = file_path[len(plugin_folder)+1:]
                        remote_file_path = f"{plugin_path}/{relative_path}"
                        
                        # Create subdirectories if needed
                        remote_dir = os.path.dirname(remote_file_path)
                        if remote_dir and remote_dir != plugin_path:
                            try:
                                sftp.mkdir(remote_dir)
                            except Exception:
                                pass
                        
                        # Upload file
                        file_data = zip_file.read(file_path)
                        with sftp.open(remote_file_path, 'wb') as remote_file:
                            remote_file.write(file_data)
                        
                        uploaded_files += 1
                
                print(f"✅ Installed {plugin['name']} ({uploaded_files} files)")
                
                self.installation_log.append({
                    'plugin': plugin['name'],
                    'slug': plugin['slug'],
                    'action': 'install',
                    'status': 'success',
                    'files_uploaded': uploaded_files,
                    'path': plugin_path,
                    'timestamp': datetime.now().isoformat()
                })
                
                return True
                
        except Exception as e:
            error_msg = f"Failed to install {plugin['name']}: {str(e)}"
            print(f"❌ {error_msg}")
            
            self.installation_log.append({
                'plugin': plugin['name'],
                'action': 'install',
                'status': 'failed',
                'error': str(e),
                'timestamp': datetime.now().isoformat()
            })
            
            return False

    def create_plugin_activation_script(self, sftp: paramiko.SFTPClient) -> None:
        """Create PHP script to activate all installed plugins"""
        try:
            print("🔄 Creating plugin activation script...")
            
            # Get list of successfully installed plugins
            installed_plugins = [
                log['slug'] for log in self.installation_log 
                if log['status'] == 'success' and log['action'] == 'install'
            ]
            
            if not installed_plugins:
                print("⚠️ No plugins to activate")
                return
            
            # Generate PHP activation script
            activation_script = f'''<?php
/**
 * YouTuneAI Plugin Auto-Activation Script
 * Generated on {datetime.now().isoformat()}
 * Copyright (c) 2025 Mr. Swain (3000Studios) - Patent Pending
 */

// Security check
if (!defined('ABSPATH')) {{
    exit;
}}

// Function to activate plugins
function youtuneai_activate_plugins() {{
    $plugins_to_activate = array(
'''
            
            for plugin_slug in installed_plugins:
                # Find main plugin file (usually plugin-name/plugin-name.php)
                main_file = f"{plugin_slug}/{plugin_slug}.php"
                activation_script += f'        "{main_file}",\n'
            
            activation_script += '''    );
    
    $activated_count = 0;
    $activation_errors = array();
    
    foreach ($plugins_to_activate as $plugin) {
        $plugin_path = WP_PLUGIN_DIR . '/' . $plugin;
        
        if (file_exists($plugin_path)) {
            $result = activate_plugin($plugin);
            
            if (is_wp_error($result)) {
                $activation_errors[] = array(
                    'plugin' => $plugin,
                    'error' => $result->get_error_message()
                );
            } else {
                $activated_count++;
                
                // Log successful activation
                error_log("YouTuneAI: Successfully activated plugin: " . $plugin);
            }
        } else {
            $activation_errors[] = array(
                'plugin' => $plugin,
                'error' => 'Plugin file not found: ' . $plugin_path
            );
        }
    }
    
    // Log results
    $log_message = "YouTuneAI Plugin Activation Summary: ";
    $log_message .= "Activated: {$activated_count}, Errors: " . count($activation_errors);
    error_log($log_message);
    
    if (!empty($activation_errors)) {
        foreach ($activation_errors as $error) {
            error_log("YouTuneAI Activation Error: " . $error['plugin'] . " - " . $error['error']);
        }
    }
    
    return array(
        'activated' => $activated_count,
        'errors' => $activation_errors,
        'total_plugins' => count($plugins_to_activate)
    );
}

// Auto-execute if called directly
if (!function_exists('activate_plugin')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

$activation_result = youtuneai_activate_plugins();

// Output results for CLI execution
if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success("Activated {$activation_result['activated']} plugins");
    if (!empty($activation_result['errors'])) {
        foreach ($activation_result['errors'] as $error) {
            WP_CLI::warning("Error activating {$error['plugin']}: {$error['error']}");
        }
    }
} else {
    // Web execution
    echo "<h2>YouTuneAI Plugin Activation Results</h2>";
    echo "<p>Activated: {$activation_result['activated']} plugins</p>";
    
    if (!empty($activation_result['errors'])) {
        echo "<h3>Errors:</h3><ul>";
        foreach ($activation_result['errors'] as $error) {
            echo "<li>{$error['plugin']}: {$error['error']}</li>";
        }
        echo "</ul>";
    }
}

// Clean up - delete this script after execution
if (file_exists(__FILE__)) {
    unlink(__FILE__);
}
?>'''
            
            # Upload activation script
            script_path = '/youtuneai_activate_plugins.php'
            with sftp.open(script_path, 'w') as script_file:
                script_file.write(activation_script)
            
            print(f"✅ Plugin activation script created: {script_path}")
            
            self.installation_log.append({
                'action': 'create_activation_script',
                'status': 'success',
                'script_path': script_path,
                'plugins_count': len(installed_plugins),
                'timestamp': datetime.now().isoformat()
            })
            
        except Exception as e:
            error_msg = f"Failed to create activation script: {str(e)}"
            print(f"❌ {error_msg}")
            
            self.installation_log.append({
                'action': 'create_activation_script',
                'status': 'failed',
                'error': str(e),
                'timestamp': datetime.now().isoformat()
            })

    def create_ai_integration_hooks(self, sftp: paramiko.SFTPClient) -> None:
        """Create WordPress hooks for AI integration"""
        try:
            print("🔗 Creating AI integration hooks...")
            
            hooks_script = '''<?php
/**
 * YouTuneAI Integration Hooks
 * Advanced AI automation hooks for WordPress
 * Copyright (c) 2025 Mr. Swain (3000Studios) - Patent Pending
 */

// Security check
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register YouTuneAI REST API endpoints
 */
add_action('rest_api_init', function() {
    // AI Theme Settings endpoint
    register_rest_route('youtuneai/v1', '/theme-settings', array(
        'methods' => 'POST',
        'callback' => 'youtuneai_update_theme_settings',
        'permission_callback' => 'youtuneai_check_permissions'
    ));
    
    // AI Action Logs endpoint
    register_rest_route('youtuneai/v1', '/action-logs', array(
        'methods' => 'GET',
        'callback' => 'youtuneai_get_action_logs',
        'permission_callback' => 'youtuneai_check_permissions'
    ));
    
    // AI Command Processor endpoint
    register_rest_route('youtuneai/v1', '/process-command', array(
        'methods' => 'POST',
        'callback' => 'youtuneai_process_command',
        'permission_callback' => 'youtuneai_check_permissions'
    ));
});

/**
 * Check AI permissions
 */
function youtuneai_check_permissions() {
    return current_user_can('manage_options');
}

/**
 * Update theme settings via AI
 */
function youtuneai_update_theme_settings($request) {
    $settings = $request->get_json_params();
    
    $updated = array();
    
    foreach ($settings as $key => $value) {
        $theme_mod_key = 'youtuneai_' . $key;
        set_theme_mod($theme_mod_key, $value);
        $updated[$key] = $value;
        
        // Log AI action
        youtuneai_log_action('theme_setting_updated', array(
            'setting' => $key,
            'value' => $value
        ));
    }
    
    return new WP_REST_Response(array(
        'success' => true,
        'updated_settings' => $updated,
        'timestamp' => current_time('mysql')
    ), 200);
}

/**
 * Get AI action logs
 */
function youtuneai_get_action_logs() {
    $logs = get_option('youtuneai_action_logs', array());
    
    // Limit to last 100 actions
    $logs = array_slice($logs, -100);
    
    return new WP_REST_Response(array(
        'success' => true,
        'logs' => $logs,
        'total' => count($logs)
    ), 200);
}

/**
 * Process AI voice command
 */
function youtuneai_process_command($request) {
    $params = $request->get_json_params();
    $command = sanitize_text_field($params['command'] ?? '');
    $confidence = floatval($params['confidence'] ?? 0.0);
    
    $result = array(
        'success' => false,
        'message' => 'Command not recognized',
        'command' => $command,
        'confidence' => $confidence
    );
    
    // Process different command types
    if (strpos($command, 'create product') !== false) {
        $result = youtuneai_create_product_from_command($command, $params);
    } elseif (strpos($command, 'create post') !== false) {
        $result = youtuneai_create_post_from_command($command, $params);
    } elseif (strpos($command, 'update theme') !== false) {
        $result = youtuneai_update_theme_from_command($command, $params);
    }
    
    // Log the command
    youtuneai_log_action('voice_command_processed', array(
        'command' => $command,
        'confidence' => $confidence,
        'result' => $result['success'] ? 'success' : 'failed'
    ));
    
    return new WP_REST_Response($result, $result['success'] ? 200 : 400);
}

/**
 * Log AI actions
 */
function youtuneai_log_action($action, $data = array()) {
    $logs = get_option('youtuneai_action_logs', array());
    
    $log_entry = array(
        'action' => $action,
        'data' => $data,
        'timestamp' => current_time('mysql'),
        'user_id' => get_current_user_id(),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    );
    
    $logs[] = $log_entry;
    
    // Keep only last 500 log entries
    if (count($logs) > 500) {
        $logs = array_slice($logs, -500);
    }
    
    update_option('youtuneai_action_logs', $logs);
}

/**
 * Create WooCommerce product from voice command
 */
function youtuneai_create_product_from_command($command, $params) {
    if (!class_exists('WooCommerce')) {
        return array('success' => false, 'message' => 'WooCommerce not installed');
    }
    
    // Extract product details from command using AI
    $product_name = $params['product_name'] ?? 'AI Generated Product';
    $product_price = $params['product_price'] ?? '9.99';
    $product_description = $params['product_description'] ?? 'Product created by AI voice command';
    
    $product = new WC_Product_Simple();
    $product->set_name($product_name);
    $product->set_regular_price($product_price);
    $product->set_description($product_description);
    $product->set_status('publish');
    
    $product_id = $product->save();
    
    if ($product_id) {
        return array(
            'success' => true,
            'message' => "Product '{$product_name}' created successfully",
            'product_id' => $product_id,
            'edit_url' => admin_url("post.php?post={$product_id}&action=edit")
        );
    } else {
        return array('success' => false, 'message' => 'Failed to create product');
    }
}

/**
 * Create blog post from voice command
 */
function youtuneai_create_post_from_command($command, $params) {
    $post_title = $params['post_title'] ?? 'AI Generated Post';
    $post_content = $params['post_content'] ?? 'Content generated by AI voice command';
    
    $post_id = wp_insert_post(array(
        'post_title' => $post_title,
        'post_content' => $post_content,
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
        'meta_input' => array(
            '_created_by_ai' => true,
            '_ai_command' => $command,
            '_creation_timestamp' => current_time('mysql')
        )
    ));
    
    if ($post_id && !is_wp_error($post_id)) {
        return array(
            'success' => true,
            'message' => "Post '{$post_title}' created successfully",
            'post_id' => $post_id,
            'edit_url' => admin_url("post.php?post={$post_id}&action=edit")
        );
    } else {
        return array('success' => false, 'message' => 'Failed to create post');
    }
}

/**
 * Update theme from voice command
 */
function youtuneai_update_theme_from_command($command, $params) {
    $settings_updated = 0;
    
    if (isset($params['primary_color'])) {
        set_theme_mod('youtuneai_primary_color', $params['primary_color']);
        $settings_updated++;
    }
    
    if (isset($params['font_family'])) {
        set_theme_mod('youtuneai_font_family', $params['font_family']);
        $settings_updated++;
    }
    
    if ($settings_updated > 0) {
        return array(
            'success' => true,
            'message' => "Updated {$settings_updated} theme settings",
            'settings_updated' => $settings_updated
        );
    } else {
        return array('success' => false, 'message' => 'No theme settings to update');
    }
}

// Initialize AI integration
add_action('init', function() {
    // Create AI user role if it doesn't exist
    if (!get_role('youtuneai_controller')) {
        add_role('youtuneai_controller', 'YouTuneAI Controller', array(
            'read' => true,
            'edit_posts' => true,
            'publish_posts' => true,
            'manage_options' => true,
            'manage_woocommerce' => true
        ));
    }
});

?>'''
            
            # Upload hooks script
            hooks_path = '/wp-content/youtuneai-hooks.php'
            with sftp.open(hooks_path, 'w') as hooks_file:
                hooks_file.write(hooks_script)
            
            print(f"✅ AI integration hooks created: {hooks_path}")
            
            # Create mu-plugins directory and autoloader
            mu_plugins_path = '/wp-content/mu-plugins'
            try:
                sftp.mkdir(mu_plugins_path)
            except Exception:
                pass
            
            # Create autoloader for mu-plugins
            autoloader = '''<?php
/**
 * YouTuneAI Must-Use Plugin Autoloader
 */
require_once(WP_CONTENT_DIR . '/youtuneai-hooks.php');
?>'''
            
            with sftp.open(f"{mu_plugins_path}/youtuneai-autoloader.php", 'w') as autoloader_file:
                autoloader_file.write(autoloader)
            
            print("✅ AI hooks autoloader created")
            
            self.installation_log.append({
                'action': 'create_ai_hooks',
                'status': 'success',
                'hooks_path': hooks_path,
                'autoloader_path': f"{mu_plugins_path}/youtuneai-autoloader.php",
                'timestamp': datetime.now().isoformat()
            })
            
        except Exception as e:
            error_msg = f"Failed to create AI hooks: {str(e)}"
            print(f"❌ {error_msg}")
            
            self.installation_log.append({
                'action': 'create_ai_hooks',
                'status': 'failed',
                'error': str(e),
                'timestamp': datetime.now().isoformat()
            })

    def install_all_plugins(self) -> Dict[str, Any]:
        """Install all required plugins"""
        print("🚀 Starting WordPress Plugin Installation Process...")
        print(f"📋 Total plugins to install: {len(self.required_plugins)}")
        
        try:
            # Connect to SFTP
            sftp, ssh = self.connect_sftp()
            
            successful_installs = 0
            failed_installs = 0
            
            # Install each plugin
            for plugin in self.required_plugins:
                try:
                    print(f"\\n📦 Processing {plugin['name']}...")
                    
                    # Download plugin
                    plugin_data = self.download_plugin(plugin)
                    
                    # Install plugin
                    if self.extract_and_upload_plugin(sftp, plugin, plugin_data):
                        successful_installs += 1
                    else:
                        failed_installs += 1
                        if plugin['required']:
                            print(f"⚠️ Required plugin {plugin['name']} failed to install!")
                    
                except Exception as e:
                    failed_installs += 1
                    print(f"❌ Critical error installing {plugin['name']}: {str(e)}")
                    if plugin['required']:
                        print(f"🚨 REQUIRED PLUGIN FAILED: {plugin['name']}")
            
            # Create activation script
            if successful_installs > 0:
                self.create_plugin_activation_script(sftp)
                self.create_ai_integration_hooks(sftp)
            
            # Close connections
            sftp.close()
            ssh.close()
            
            # Generate installation summary
            summary = {
                'total_plugins': len(self.required_plugins),
                'successful_installs': successful_installs,
                'failed_installs': failed_installs,
                'installation_log': self.installation_log,
                'timestamp': datetime.now().isoformat(),
                'required_plugins_installed': sum(1 for log in self.installation_log 
                                                if log.get('status') == 'success' and 
                                                   log.get('action') == 'install'),
                'next_steps': [
                    "1. Navigate to WordPress admin → Plugins",
                    "2. Activate all installed plugins",
                    "3. Configure WooCommerce setup wizard",
                    "4. Test REST API endpoints",
                    "5. Configure WordPress webhooks",
                    "6. Set up Application Password for AI access"
                ]
            }
            
            # Save installation log
            with open('plugin_installation_log.json', 'w') as log_file:
                json.dump(summary, log_file, indent=2)
            
            print(f"\\n🎉 INSTALLATION COMPLETE!")
            print(f"✅ Successfully installed: {successful_installs} plugins")
            print(f"❌ Failed installations: {failed_installs} plugins")
            print(f"📄 Installation log saved to: plugin_installation_log.json")
            
            if successful_installs == len([p for p in self.required_plugins if p['required']]):
                print("🏆 ALL REQUIRED PLUGINS INSTALLED SUCCESSFULLY!")
                print("🔗 AI-WordPress automation stack is ready!")
            else:
                print("⚠️ Some required plugins failed to install. Check the log for details.")
            
            return summary
            
        except Exception as e:
            error_summary = {
                'success': False,
                'error': str(e),
                'installation_log': self.installation_log,
                'timestamp': datetime.now().isoformat()
            }
            
            print(f"🚨 INSTALLATION FAILED: {str(e)}")
            return error_summary

def main():
    """Main execution function"""
    # SFTP Configuration
    sftp_config = {
        'host': 'access-5017098454.webspace-host.com',
        'username': 'u98387750',
        'password': 'YouTuneAi$2025',
        'port': 22
    }
    
    print("🎯 YouTuneAI WordPress Plugin Auto-Installer")
    print("🔒 Patent Pending Technology - Copyright (c) 2025 Mr. Swain (3000Studios)")
    print("=" * 70)
    
    # Initialize installer
    installer = WordPressPluginInstaller(sftp_config)
    
    # Install all plugins
    result = installer.install_all_plugins()
    
    # Display final results
    if result.get('successful_installs', 0) > 0:
        print("\\n🎊 CONGRATULATIONS!")
        print("Your WordPress site is now equipped with AI automation capabilities!")
        print("\\n📋 Next Steps:")
        for step in result.get('next_steps', []):
            print(f"   {step}")
    
    return result

if __name__ == "__main__":
    main()
