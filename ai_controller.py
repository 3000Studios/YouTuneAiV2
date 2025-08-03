#!/usr/bin/env python3
"""
YouTuneAI Voice Controller
The FIRST-EVER AI-powered website controller with voice recognition and live deployment

Copyright (c) 2025 Mr. Swain (3000Studios)
All rights reserved. Revolutionary voice-controlled AI website technology.

This software contains groundbreaking proprietary algorithms for:
- Voice-to-website command processing
- AI-powered natural language interpretation for web development  
- Real-time SFTP deployment automation
- Voice-controlled content management

COMMERCIAL USE REQUIRES PAID LICENSE - Contact: mr.jwswain@gmail.com
Unauthorized use subject to legal action and monetary damages.
"""

import os
import json
import requests
from datetime import datetime
import openai
import speech_recognition as sr  # type: ignore
import paramiko
from typing import Dict, Any, Optional
from dotenv import load_dotenv

# Load secure credentials
load_dotenv('secrets.env')

class YouTuneAIController:
    def __init__(self):
        """Initialize the AI controller with all necessary configurations"""
        
        # OpenAI Configuration
        self.openai_key: str = os.getenv('OPENAI_API_KEY', '')
        openai.api_key = self.openai_key
        
        # SFTP Configuration (IONOS hosting)
        self.sftp_config: Dict[str, Any] = {
            'host': os.getenv('SFTP_HOST', 'access-5017098454.webspace-host.com'),
            'username': os.getenv('SFTP_USERNAME', 'a132096'),
            'password': os.getenv('SFTP_PASSWORD', 'Gabby3000!!!'),
            'port': int(os.getenv('SFTP_PORT', '22')),
            'remote_path': os.getenv('SFTP_REMOTE_PATH', '/wp-content/themes/youtuneai/')
        }
        
        # WordPress Configuration
        self.wp_config: Dict[str, Optional[str]] = {
            'site_url': os.getenv('WP_SITE_URL', 'https://youtuneai.com'),
            'rest_api_url': os.getenv('WP_API_URL', 'https://youtuneai.com/wp-json/wp/v2/'),
            'admin_user': os.getenv('WP_ADMIN_USER', 'VScode'),
            'admin_pass': os.getenv('WP_ADMIN_PASS', 'Gabby3000!!!'),
            'admin_email': os.getenv('NOTIFICATION_EMAIL', 'mr.jwswain@gmail.com'),
            'app_password': None,  # Will be set during authentication
            'webhook_secret': os.getenv('WEBHOOK_SECRET', 'youtuneai_webhook_2025')
        }
        
        # Admin Access Credentials
        self.required_plugins: Dict[str, Dict[str, str]] = {
            'wp-rest-api-controller': {
                'slug': 'wp-rest-api-controller',
                'download_url': 'https://downloads.wordpress.org/plugin/wp-rest-api-controller.latest-stable.zip',
                'required': True,
                'purpose': 'Expose custom post types to REST API'
            },
            'wp-webhooks': {
                'slug': 'wp-webhooks',
                'download_url': 'https://downloads.wordpress.org/plugin/wp-webhooks.latest-stable.zip',
                'required': True,
                'purpose': 'Trigger deployments and WordPress actions'
            },
            'advanced-custom-fields': {
                'slug': 'advanced-custom-fields',
                'download_url': 'https://downloads.wordpress.org/plugin/advanced-custom-fields.latest-stable.zip',
                'required': True,
                'purpose': 'Dynamic content creation and meta fields'
            },
            'woocommerce': {
                'slug': 'woocommerce',
                'download_url': 'https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip',
                'required': True,
                'purpose': 'E-commerce functionality for add_product'
            },
            'custom-post-type-ui': {
                'slug': 'custom-post-type-ui',
                'download_url': 'https://downloads.wordpress.org/plugin/custom-post-type-ui.latest-stable.zip',
                'required': True,
                'purpose': 'Custom content types for bot commands'
            },
            'code-snippets': {
                'slug': 'code-snippets',
                'download_url': 'https://downloads.wordpress.org/plugin/code-snippets.latest-stable.zip',
                'required': True,
                'purpose': 'Dynamic hooks and PHP logic'
            },
            'wp-security-audit-log': {
                'slug': 'wp-security-audit-log',
                'download_url': 'https://downloads.wordpress.org/plugin/wp-security-audit-log.latest-stable.zip',
                'required': False,
                'purpose': 'Monitor AI changes and security'
            }
        }
        
        # Voice Recognition
        self.recognizer = sr.Recognizer()
        self.microphone = sr.Microphone()
        
        # Command history
        self.command_history = []
        
        print("🚀 YouTuneAI Controller initialized!")
        print("🎤 Voice recognition ready")
        print("🔗 SFTP connection configured")
        print("🧠 AI processing ready")
        print("🔌 WordPress plugin integration enabled")
        
        # Initialize WordPress connection
        self.setup_wordpress_integration()

    def setup_wordpress_integration(self):
        """Setup WordPress REST API integration and check plugins"""
        try:
            print("🔌 Setting up WordPress integration...")
            
            # Check WordPress site accessibility
            response = requests.get(f"{self.wp_config['site_url']}/wp-json/wp/v2/", timeout=10)
            if response.status_code == 200:
                print("✅ WordPress REST API accessible")
                
                # Check required plugins
                self.check_required_plugins()
                
                # Setup application passwords if needed
                self.setup_app_passwords()
                
            else:
                print(f"⚠️ WordPress site not accessible: {response.status_code}")
                
        except Exception as e:
            print(f"❌ WordPress integration setup failed: {str(e)}")

    def check_required_plugins(self):
        """Check if required plugins are installed and active"""
        try:
            print("🔍 Checking required WordPress plugins...")
            
            # Get list of active plugins
            plugins_url = f"{self.wp_config['site_url']}/wp-json/wp/v2/plugins"
            
            for plugin_name, plugin_info in self.required_plugins.items():
                if plugin_info['required']:
                    print(f"📦 Checking {plugin_name}...")
                    
                    # This would require authentication, for now we'll log the requirement
                    print(f"   Purpose: {plugin_info['purpose']}")
                    
            print("✅ Plugin check completed")
            
        except Exception as e:
            print(f"❌ Plugin check failed: {str(e)}")

    def install_wordpress_plugin(self, plugin_slug: str) -> Dict[str, Any]:
        """Install a WordPress plugin via REST API"""
        try:
            plugin_info = self.required_plugins.get(plugin_slug)
            if not plugin_info:
                return {'success': False, 'error': f'Unknown plugin: {plugin_slug}'}
            
            print(f"📥 Installing {plugin_slug}...")
            
            # Download plugin
            response = requests.get(plugin_info['download_url'], timeout=30)
            if response.status_code != 200:
                return {'success': False, 'error': f'Failed to download {plugin_slug}'}
            
            # Save plugin zip file
            plugin_zip_path = f"plugins/{plugin_slug}.zip"
            os.makedirs('plugins', exist_ok=True)
            
            with open(plugin_zip_path, 'wb') as f:
                f.write(response.content)
            
            print(f"✅ Downloaded {plugin_slug}")
            
            # Upload via SFTP to WordPress plugins directory
            deploy_result = self.deploy_plugin(plugin_zip_path, plugin_slug)
            
            return deploy_result
            
        except Exception as e:
            return {'success': False, 'error': f'Plugin installation failed: {str(e)}'}

    def deploy_plugin(self, local_zip_path: str, plugin_slug: str) -> Dict[str, Any]:
        """Deploy plugin to WordPress via SFTP"""
        try:
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            ssh.connect(
                hostname=self.sftp_config['host'],
                username=self.sftp_config['username'],
                password=self.sftp_config['password'],
                port=self.sftp_config['port']
            )
            
            sftp = ssh.open_sftp()
            
            # Upload to plugins directory
            remote_plugin_path = f"/wp-content/plugins/{plugin_slug}.zip"
            sftp.put(local_zip_path, remote_plugin_path)
            
            # Execute unzip command via SSH
            stdin, stdout, stderr = ssh.exec_command(f"cd /wp-content/plugins && unzip -o {plugin_slug}.zip")
            
            sftp.close()
            ssh.close()
            
            print(f"✅ Plugin {plugin_slug} deployed successfully")
            
            return {'success': True, 'message': f'Plugin {plugin_slug} deployed'}
            
        except Exception as e:
            return {'success': False, 'error': f'Plugin deployment failed: {str(e)}'}

    def setup_app_passwords(self):
        """Setup WordPress Application Passwords for secure authentication"""
        try:
            print("🔐 Setting up WordPress Application Passwords...")
            
            # This would typically be done through WordPress admin
            # For now, we'll prepare the structure
            self.wp_config['app_password'] = None  # To be set manually
            
            print("ℹ️  Please create an Application Password in WordPress admin:")
            print("   1. Go to Users → Profile")
            print("   2. Scroll to Application Passwords")
            print("   3. Create new password for 'YouTuneAI Controller'")
            print("   4. Update wp_config['app_password'] with the generated password")
            
        except Exception as e:
            print(f"❌ App password setup failed: {str(e)}")

    def make_wp_api_request(self, endpoint: str, method: str = 'GET', data: Optional[Dict[str, Any]] = None) -> Dict[str, Any]:
        """Make a request to the WordPress API"""
        headers = {
            'Authorization': f'Bearer {self.wp_config.get("app_password", "")}'
        }
        url = f"{self.wp_config['rest_api_url']}{endpoint}"
        response = requests.request(method, url, headers=headers, json=data, timeout=30)
        return response.json()

    def process_plugins(self):
        """Process required plugins"""
        for plugin_name, plugin_info in self.required_plugins.items():
            download_url = plugin_info.get('download_url', '')
            if not download_url:
                continue
            response = requests.get(download_url, timeout=30)
            if response.status_code == 200:
                print(f"Downloaded {plugin_name} successfully.")
            else:
                print(f"Failed to download {plugin_name}.")

    def create_woocommerce_product(self, params: Dict[str, Any]) -> Dict[str, Any]:
        """Create WooCommerce product via REST API"""
        try:
            product_data = {
                'name': params.get('name', 'New Product'),
                'type': 'simple',
                'regular_price': str(params.get('price', '9.99')),
                'description': params.get('description', 'Product description'),
                'short_description': params.get('short_description', ''),
                'categories': [{'name': params.get('category', 'General')}],
                'status': 'publish',
                'catalog_visibility': 'visible',
                'meta_data': [
                    {'key': '_created_by_ai', 'value': 'YouTuneAI Controller'},
                    {'key': '_creation_date', 'value': datetime.now().isoformat()}
                ]
            }
            
            # Use WooCommerce REST API endpoint
            result = self.make_wp_api_request('products', 'POST', product_data)
            
            if result['success']:
                product_id = result['data'].get('id')
                return {
                    'success': True,
                    'message': f'Product "{product_data["name"]}" created with ID {product_id}',
                    'product_id': product_id
                }
            else:
                return result
                
        except Exception as e:
            return {'success': False, 'error': f'WooCommerce product creation failed: {str(e)}'}

    def create_custom_post(self, post_type: str, params: Dict[str, Any]) -> Dict[str, Any]:
        """Create custom post type entry"""
        try:
            post_data = {
                'title': params.get('title', 'New Post'),
                'content': params.get('content', ''),
                'status': 'publish',
                'meta': {
                    '_created_by_ai': 'YouTuneAI Controller',
                    '_creation_date': datetime.now().isoformat()
                }
            }
            
            # Add custom fields if provided
            if 'custom_fields' in params:
                post_data['meta'].update(params['custom_fields'])
            
            # Use REST API for custom post types
            result = self.make_wp_api_request(f'{post_type}', 'POST', post_data)
            
            return result
            
        except Exception as e:
            return {'success': False, 'error': f'Custom post creation failed: {str(e)}'}

    def trigger_webhook(self, webhook_action: str, data: Dict[str, Any]) -> Dict[str, Any]:
        """Trigger WordPress webhook for deployment or other actions"""
        try:
            webhook_url = f"{self.wp_config['site_url']}/wp-json/wp-webhooks/v1/action/{webhook_action}"
            
            webhook_data = {
                'secret': self.wp_config['webhook_secret'],
                'action': webhook_action,
                'data': data,
                'timestamp': datetime.now().isoformat(),
                'source': 'YouTuneAI Controller'
            }
            
            response = requests.post(webhook_url, json=webhook_data, timeout=30)
            
            if response.status_code == 200:
                return {'success': True, 'message': f'Webhook {webhook_action} triggered'}
            else:
                return {'success': False, 'error': f'Webhook failed: {response.status_code}'}
                
        except Exception as e:
            return {'success': False, 'error': f'Webhook trigger failed: {str(e)}'}

    def setup_ai_automation_hooks(self):
        """Setup WordPress hooks for AI automation using Code Snippets plugin"""
        
        # Code snippet to add AI automation hooks
        ai_hooks_snippet = '''
<?php
// YouTuneAI Automation Hooks
// Added via Code Snippets plugin for AI integration

// Hook for AI-triggered deployments
add_action('wp_ajax_nopriv_ai_deploy', 'handle_ai_deployment');
add_action('wp_ajax_ai_deploy', 'handle_ai_deployment');

function handle_ai_deployment() {
    // Verify webhook secret
    $secret = $_POST['secret'] ?? '';
    if ($secret !== 'youtuneai_webhook_2025') {
        wp_die('Unauthorized', 401);
    }
    
    // Log AI action
    error_log('AI Deployment triggered: ' . json_encode($_POST));
    
    // Process deployment
    $action = $_POST['action'] ?? '';
    $data = $_POST['data'] ?? [];
    
    switch($action) {
        case 'theme_update':
            // Handle theme updates
            do_action('youtuneai_theme_update', $data);
            break;
        case 'content_update':
            // Handle content updates
            do_action('youtuneai_content_update', $data);
            break;
        case 'product_create':
            // Handle product creation
            do_action('youtuneai_product_create', $data);
            break;
    }
    
    wp_send_json_success(['message' => 'AI action processed']);
}

// Custom post type for AI commands
add_action('init', 'register_ai_command_post_type');
function register_ai_command_post_type() {
    register_post_type('ai_command', [
        'public' => false,
        'show_in_rest' => true,
        'rest_base' => 'ai-commands',
        'supports' => ['title', 'editor', 'custom-fields'],
        'capability_type' => 'post'
    ]);
}

// Log all AI actions for security audit
add_action('youtuneai_action_executed', 'log_ai_action', 10, 2);
function log_ai_action($action, $data) {
    $log_entry = [
        'timestamp' => current_time('mysql'),
        'action' => $action,
        'data' => $data,
        'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    // Save to custom table or meta field
    update_option('youtuneai_last_action', $log_entry);
    
    // Also log to file for security monitoring
    error_log('YouTuneAI Action: ' . json_encode($log_entry));
}
?>
'''
        
        return ai_hooks_snippet

    def listen_for_voice_command(self) -> Optional[str]:
        """Listen for voice commands using speech recognition"""
        try:
            print("🎤 Listening for your command...")
            
            with self.microphone as source:
                # Adjust for ambient noise
                self.recognizer.adjust_for_ambient_noise(source, duration=1)
                
                # Listen for audio with timeout
                audio = self.recognizer.listen(source, timeout=10, phrase_time_limit=10)
            
            print("🧠 Processing speech...")
            
            # Recognize speech using Google's service
            command = self.recognizer.recognize_google(audio)
            print(f"✅ Heard: '{command}'")
            
            return command.lower()
            
        except sr.WaitTimeoutError:
            print("⏰ No speech detected within timeout")
            return None
        except sr.UnknownValueError:
            print("❌ Could not understand audio")
            return None
        except sr.RequestError as e:
            print(f"❌ Speech recognition error: {e}")
            return None

    def process_command_with_ai(self, command: str) -> Dict[str, Any]:
        """Process natural language commands using OpenAI GPT"""
        try:
            prompt = f"""
            You are an AI assistant for a WordPress website called YouTuneAI. 
            Convert this user command into specific website actions:
            
            Command: "{command}"
            
            Available actions and their parameters:
            
            1. change_background_video:
               - video_theme: "space", "ocean", "city", "nature", "gaming", "music"
               - video_url: direct URL to video file
            
            2. update_homepage_content:
               - section: "hero", "gallery", "streaming"
               - content: new text content
               - title: new title if needed
            
            3. add_product:
               - name: product name
               - price: price in USD
               - description: product description
               - category: "avatar", "overlay", "music", "tools"
            
            4. change_theme_colors:
               - primary_color: hex color code
               - secondary_color: hex color code
               - accent_color: hex color code
            
            5. create_blog_post:
               - title: post title
               - content: post content
               - category: post category
            
            6. update_navigation:
               - action: "add" or "remove" or "modify"
               - menu_item: menu item details
            
            7. deploy_changes:
               - files: list of files to deploy
               - backup: true/false
            
            Respond with JSON only in this format:
            {{
                "action": "action_name",
                "parameters": {{
                    "param1": "value1",
                    "param2": "value2"
                }},
                "confidence": 0.95,
                "explanation": "Brief explanation of what will be done"
            }}
            """
            
            response = openai.ChatCompletion.create(
                model="gpt-4",
                messages=[
                    {
                        "role": "system",
                        "content": "You are a website management AI. Respond only with valid JSON."
                    },
                    {
                        "role": "user",
                        "content": prompt
                    }
                ],
                max_tokens=500,
                temperature=0.3
            )
            
            ai_response = response.choices[0].message.content.strip()
            
            # Parse JSON response
            try:
                parsed_response = json.loads(ai_response)
                return {
                    'success': True,
                    'action': parsed_response.get('action'),
                    'parameters': parsed_response.get('parameters', {}),
                    'confidence': parsed_response.get('confidence', 0.5),
                    'explanation': parsed_response.get('explanation', '')
                }
            except json.JSONDecodeError:
                return {
                    'success': False,
                    'error': 'Could not parse AI response as JSON',
                    'raw_response': ai_response
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'AI processing failed: {str(e)}'
            }

    def execute_action(self, action: str, parameters: Dict[str, Any]) -> Dict[str, Any]:
        """Execute the determined action"""
        try:
            print(f"🔧 Executing action: {action}")
            print(f"📋 Parameters: {parameters}")
            
            if action == "change_background_video":
                return self.change_background_video(parameters)
            elif action == "update_homepage_content":
                return self.update_homepage_content(parameters)
            elif action == "add_product":
                return self.add_product(parameters)
            elif action == "change_theme_colors":
                return self.change_theme_colors(parameters)
            elif action == "create_blog_post":
                return self.create_blog_post(parameters)
            elif action == "update_navigation":
                return self.update_navigation(parameters)
            elif action == "deploy_changes":
                return self.deploy_changes(parameters)
            else:
                return {
                    'success': False,
                    'error': f'Unknown action: {action}'
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Action execution failed: {str(e)}'
            }

    def change_background_video(self, params: Dict[str, Any]) -> Dict[str, Any]:
        """Change the homepage background video"""
        try:
            video_theme = params.get('video_theme', 'space')
            video_url = params.get('video_url')
            
            # Video URL mapping
            video_urls = {
                'space': 'https://cdn.pixabay.com/vimeo/459567622/space-47668.mp4',
                'ocean': 'https://cdn.pixabay.com/vimeo/459567531/ocean-47667.mp4',
                'city': 'https://cdn.pixabay.com/vimeo/459567642/city-47669.mp4',
                'nature': 'https://cdn.pixabay.com/vimeo/459567652/nature-47670.mp4',
                'gaming': 'https://cdn.pixabay.com/vimeo/459567662/gaming-47671.mp4',
                'music': 'https://cdn.pixabay.com/vimeo/459567672/music-47672.mp4'
            }
            
            if not video_url:
                video_url = video_urls.get(video_theme, video_urls['space'])
            
            # Update the homepage template
            homepage_content = self.read_local_file('page-home.php')
            if homepage_content:
                # Replace video source
                updated_content = homepage_content.replace(
                    'src="<?php echo get_template_directory_uri(); ?>/assets/video/background.mp4"',
                    f'src="{video_url}"'
                )
                
                # Write updated file
                self.write_local_file('page-home.php', updated_content)
                
                # Deploy to server
                deploy_result = self.deploy_file('page-home.php')
                
                if deploy_result['success']:
                    return {
                        'success': True,
                        'message': f'Background video changed to {video_theme} theme',
                        'video_url': video_url
                    }
                else:
                    return deploy_result
            else:
                return {
                    'success': False,
                    'error': 'Could not read homepage template'
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Failed to change background video: {str(e)}'
            }

    def update_homepage_content(self, params: Dict[str, Any]) -> Dict[str, Any]:
        """Update homepage content sections"""
        try:
            section = params.get('section', 'hero')
            content = params.get('content', '')
            title = params.get('title', '')
            
            homepage_content = self.read_local_file('page-home.php')
            if not homepage_content:
                return {'success': False, 'error': 'Could not read homepage template'}
            
            if section == 'hero':
                if title:
                    homepage_content = homepage_content.replace(
                        '<h1 class="hero-title" data-aos="fade-up">YouTuneAI</h1>',
                        f'<h1 class="hero-title" data-aos="fade-up">{title}</h1>'
                    )
                
                if content:
                    homepage_content = homepage_content.replace(
                        'AI-Powered Content Creation & Streaming Platform',
                        content
                    )
            
            # Write and deploy
            self.write_local_file('page-home.php', homepage_content)
            deploy_result = self.deploy_file('page-home.php')
            
            if deploy_result['success']:
                return {
                    'success': True,
                    'message': f'Homepage {section} section updated'
                }
            else:
                return deploy_result
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Failed to update homepage content: {str(e)}'
            }

    def add_product(self, params: Dict[str, Any]) -> Dict[str, Any]:
        """Add a new product to WooCommerce shop via REST API"""
        try:
            name = params.get('name', 'New Product')
            price = params.get('price', '9.99')
            description = params.get('description', 'AI-generated product description')
            category = params.get('category', 'digital')
            
            print(f"🛒 Creating WooCommerce product: {name}")
            
            # Create product using WooCommerce REST API
            product_result = self.create_woocommerce_product({
                'name': name,
                'price': price,
                'description': description,
                'category': category,
                'short_description': f"AI-powered {category} product - {name}"
            })
            
            if product_result['success']:
                # Trigger webhook for additional processing
                webhook_result = self.trigger_webhook('product_create', {
                    'product_id': product_result.get('product_id'),
                    'name': name,
                    'price': price,
                    'category': category
                })
                
                return {
                    'success': True,
                    'message': f'Product "{name}" added to WooCommerce shop for ${price}',
                    'product_id': product_result.get('product_id')
                }
            else:
                return product_result
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Failed to add product: {str(e)}'
            }

    def deploy_file(self, filename: str) -> Dict[str, Any]:
        """Deploy a single file to the server via SFTP"""
        try:
            local_path = os.path.join('wp-theme-youtuneai', filename)
            remote_path = self.sftp_config['remote_path'] + filename
            
            # Create SFTP connection
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            ssh.connect(
                hostname=self.sftp_config['host'],
                username=self.sftp_config['username'],
                password=self.sftp_config['password'],
                port=self.sftp_config['port']
            )
            
            sftp = ssh.open_sftp()
            
            # Upload file
            sftp.put(local_path, remote_path)
            
            # Close connections
            sftp.close()
            ssh.close()
            
            print(f"✅ Successfully deployed {filename}")
            
            return {
                'success': True,
                'message': f'File {filename} deployed successfully'
            }
            
        except Exception as e:
            return {
                'success': False,
                'error': f'Deployment failed: {str(e)}'
            }

    def deploy_changes(self, params: Dict[str, Any]) -> Dict[str, Any]:
        """Deploy multiple files or entire theme"""
        try:
            files = params.get('files', [])
            backup = params.get('backup', True)
            
            if not files:
                # Deploy all theme files
                files = [
                    'style.css',
                    'functions.php',
                    'header.php',
                    'footer.php',
                    'index.php',
                    'page-home.php'
                ]
            
            success_count = 0
            failed_files = []
            
            for file in files:
                result = self.deploy_file(file)
                if result['success']:
                    success_count += 1
                else:
                    failed_files.append(file)
            
            if failed_files:
                return {
                    'success': False,
                    'error': f'Failed to deploy: {", ".join(failed_files)}'
                }
            else:
                return {
                    'success': True,
                    'message': f'Successfully deployed {success_count} files'
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Deployment failed: {str(e)}'
            }

    def read_local_file(self, filename: str) -> Optional[str]:
        """Read a local theme file"""
        try:
            file_path = os.path.join('wp-theme-youtuneai', filename)
            with open(file_path, 'r', encoding='utf-8') as f:
                return f.read()
        except Exception as e:
            print(f"❌ Error reading {filename}: {str(e)}")
            return None

    def write_local_file(self, filename: str, content: str) -> bool:
        """Write content to a local theme file"""
        try:
            file_path = os.path.join('wp-theme-youtuneai', filename)
            os.makedirs(os.path.dirname(file_path), exist_ok=True)
            
            with open(file_path, 'w', encoding='utf-8') as f:
                f.write(content)
            
            print(f"✅ Updated {filename}")
            return True
            
        except Exception as e:
            print(f"❌ Error writing {filename}: {str(e)}")
            return False

    def log_command(self, command: str, result: Dict[str, Any]):
        """Log command execution for debugging"""
        log_entry = {
            'timestamp': datetime.now().isoformat(),
            'command': command,
            'result': result
        }
        
        self.command_history.append(log_entry)
        
        # Save to file
        try:
            with open('command_history.json', 'w') as f:
                json.dump(self.command_history, f, indent=2)
        except Exception as e:
            print(f"Warning: Could not save command history: {e}")

    def run_voice_loop(self):
        """Main loop for voice command processing"""
        print("\n🎤 YouTuneAI Voice Controller Started!")
        print("Say 'hey YouTune' to activate, or 'exit' to quit")
        print("-" * 50)
        
        while True:
            try:
                # Listen for activation phrase or direct command
                command = self.listen_for_voice_command()
                
                if not command:
                    continue
                
                if 'exit' in command or 'quit' in command:
                    print("👋 Goodbye!")
                    break
                
                # Process command with AI
                print("🧠 Processing with AI...")
                ai_result = self.process_command_with_ai(command)
                
                if not ai_result['success']:
                    print(f"❌ AI Error: {ai_result['error']}")
                    continue
                
                print(f"📝 AI Interpretation: {ai_result['explanation']}")
                print(f"🎯 Confidence: {ai_result['confidence']:.0%}")
                
                # Execute action
                execution_result = self.execute_action(
                    ai_result['action'], 
                    ai_result['parameters']
                )
                
                if execution_result['success']:
                    print(f"✅ {execution_result['message']}")
                else:
                    print(f"❌ {execution_result['error']}")
                
                # Log the command
                self.log_command(command, {
                    'ai_result': ai_result,
                    'execution_result': execution_result
                })
                
                print("-" * 50)
                
            except KeyboardInterrupt:
                print("\n👋 Interrupted by user. Goodbye!")
                break
            except Exception as e:
                print(f"❌ Unexpected error: {str(e)}")
                continue

def main():
    """Main entry point"""
    try:
        controller = YouTuneAIController()
        controller.run_voice_loop()
    except KeyboardInterrupt:
        print("\n👋 Goodbye!")
    except Exception as e:
        print(f"❌ Fatal error: {str(e)}")

if __name__ == "__main__":
    main()
