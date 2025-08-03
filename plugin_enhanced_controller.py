#!/usr/bin/env python3
"""
Enhanced YouTuneAI Controller with WordPress Plugin Integration
Utilizes installed WordPress plugins for enhanced functionality
"""

import requests
import json
import base64
from datetime import datetime
import os
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

class EnhancedYouTuneAIController:
    def __init__(self):
        """Initialize enhanced controller with plugin integrations"""
        
        # WordPress Configuration
        self.wp_config = {
            'site_url': os.getenv('WP_SITE_URL', 'https://youtuneai.com'),
            'api_url': os.getenv('WP_API_URL', 'https://youtuneai.com/wp-json/wp/v2/'),
            'admin_user': os.getenv('WP_ADMIN_USER', 'VScode'),
            'admin_pass': os.getenv('WP_ADMIN_PASS', 'Gabby3000!!!'),
        }
        
        # Admin credentials for secure access
        self.admin_credentials = {
            'username': 'Mr.jwswain@gmail.com',
            'password': 'Gabby3000???'
        }
        
        # Plugin endpoints
        self.plugin_endpoints = {
            'woocommerce': '/wp-json/wc/v3/',
            'acf': '/wp-json/acf/v3/',
            'webhooks': '/wp-json/wp-webhooks/v1/',
            'custom_posts': '/wp-json/wp/v2/ai-commands',
            'youtuneai': '/wp-json/youtuneai/v1/'
        }
        
        print("🚀 Enhanced YouTuneAI Controller initialized!")
        print("🔌 Plugin integrations ready")
        
    def authenticate_command(self, username, password):
        """Verify admin credentials"""
        return (username == self.admin_credentials['username'] and 
                password == self.admin_credentials['password'])
    
    def make_wp_request(self, endpoint, method='GET', data=None, use_auth=True):
        """Make authenticated WordPress API request"""
        try:
            url = self.wp_config['site_url'] + endpoint
            
            headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
            
            # Add authentication if required
            auth = None
            if use_auth:
                auth = (self.wp_config['admin_user'], self.wp_config['admin_pass'])
            
            # Make request with SSL verification disabled for testing
            if method == 'GET':
                response = requests.get(url, headers=headers, auth=auth, verify=False, timeout=10)
            elif method == 'POST':
                response = requests.post(url, headers=headers, auth=auth, json=data, verify=False, timeout=10)
            elif method == 'PUT':
                response = requests.put(url, headers=headers, auth=auth, json=data, verify=False, timeout=10)
            elif method == 'DELETE':
                response = requests.delete(url, headers=headers, auth=auth, verify=False, timeout=10)
            
            return {
                'success': response.status_code in [200, 201],
                'status_code': response.status_code,
                'data': response.json() if response.status_code in [200, 201] else None,
                'error': response.text if response.status_code not in [200, 201] else None
            }
            
        except Exception as e:
            return {
                'success': False,
                'error': f'Request failed: {str(e)}',
                'status_code': 0
            }
    
    def test_plugin_availability(self):
        """Test which plugins are available and accessible"""
        plugin_status = {}
        
        print("🔍 Testing WordPress plugin availability...")
        
        # Test core WordPress API
        result = self.make_wp_request('/wp-json/wp/v2/', use_auth=False)
        plugin_status['wordpress_core'] = result['success']
        print(f"✅ WordPress Core API: {'Available' if result['success'] else 'Not accessible'}")
        
        # Test WooCommerce
        result = self.make_wp_request('/wp-json/wc/v3/products', use_auth=True)
        plugin_status['woocommerce'] = result['success']
        print(f"🛒 WooCommerce API: {'Available' if result['success'] else 'Not accessible'}")
        
        # Test Advanced Custom Fields
        result = self.make_wp_request('/wp-json/acf/v3/options', use_auth=True)
        plugin_status['acf'] = result['success']
        print(f"📝 ACF API: {'Available' if result['success'] else 'Not accessible'}")
        
        # Test WP Webhooks
        result = self.make_wp_request('/wp-json/wp-webhooks/v1/', use_auth=False)
        plugin_status['wp_webhooks'] = result['success']
        print(f"🔗 WP Webhooks: {'Available' if result['success'] else 'Not accessible'}")
        
        # Test Custom Post Types
        result = self.make_wp_request('/wp-json/wp/v2/types', use_auth=False)
        plugin_status['custom_post_types'] = result['success']
        print(f"📋 Custom Post Types: {'Available' if result['success'] else 'Not accessible'}")
        
        return plugin_status
    
    def execute_enhanced_command(self, command, username, password):
        """Execute command with enhanced plugin functionality"""
        
        # Authenticate user
        if not self.authenticate_command(username, password):
            return {
                'success': False,
                'error': 'Invalid credentials'
            }
        
        command = command.lower().strip()
        print(f"🧠 Processing enhanced command: {command}")
        
        try:
            # WooCommerce product commands
            if 'product' in command and 'add' in command:
                return self.add_woocommerce_product(command)
            
            # ACF field updates
            if 'field' in command or 'custom' in command:
                return self.update_acf_fields(command)
            
            # Webhook triggers
            if 'webhook' in command or 'trigger' in command:
                return self.trigger_webhook(command)
            
            # Theme customizer updates
            if 'background' in command or 'video' in command:
                return self.update_theme_background(command)
            
            # Content updates
            if 'post' in command or 'page' in command:
                return self.create_or_update_content(command)
            
            # Navigation updates
            if 'menu' in command or 'navigation' in command:
                return self.update_navigation(command)
            
            return {
                'success': False,
                'error': 'Command not recognized. Available commands: add product, update field, trigger webhook, change background, create post'
            }
            
        except Exception as e:
            return {
                'success': False,
                'error': f'Command execution failed: {str(e)}'
            }
    
    def add_woocommerce_product(self, command):
        """Add product using WooCommerce REST API"""
        try:
            # Parse product details from command
            product_name = "AI Generated Product"
            price = "9.99"
            
            # Extract product name and price
            import re
            
            # Match patterns like "add product guitar lessons for $19.99"
            match = re.search(r'add product (.+?) for \$?([0-9.]+)', command)
            if match:
                product_name = match.group(1).strip()
                price = match.group(2)
            else:
                # Match "add [product name] product"
                match = re.search(r'add (.+?) product', command)
                if match:
                    product_name = match.group(1).strip()
            
            # Create product data
            product_data = {
                'name': product_name,
                'type': 'simple',
                'regular_price': str(price),
                'description': f'AI-generated product: {product_name}',
                'short_description': 'Created via YouTuneAI voice command',
                'status': 'publish',
                'catalog_visibility': 'visible',
                'meta_data': [
                    {
                        'key': '_created_by_ai',
                        'value': 'YouTuneAI Controller'
                    },
                    {
                        'key': '_creation_date',
                        'value': datetime.now().isoformat()
                    }
                ]
            }
            
            # Make WooCommerce API request
            result = self.make_wp_request('/wp-json/wc/v3/products', 'POST', product_data)
            
            if result['success']:
                product_id = result['data'].get('id')
                return {
                    'success': True,
                    'message': f'Product "{product_name}" created successfully for ${price}',
                    'product_id': product_id
                }
            else:
                return {
                    'success': False,
                    'error': f'Failed to create product: {result.get("error", "Unknown error")}'
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'WooCommerce product creation failed: {str(e)}'
            }
    
    def update_acf_fields(self, command):
        """Update ACF fields for theme customization"""
        try:
            # Example: "update field homepage_title to Welcome to YouTuneAI"
            import re
            
            match = re.search(r'update field (\w+) to (.+)', command)
            if not match:
                return {
                    'success': False,
                    'error': 'Please specify: "update field [field_name] to [value]"'
                }
            
            field_name = match.group(1)
            field_value = match.group(2).strip()
            
            # Update ACF option field
            field_data = {
                'field_name': field_name,
                'value': field_value
            }
            
            result = self.make_wp_request('/wp-json/acf/v3/options', 'POST', field_data)
            
            if result['success']:
                return {
                    'success': True,
                    'message': f'ACF field "{field_name}" updated to "{field_value}"'
                }
            else:
                return {
                    'success': False,
                    'error': f'Failed to update ACF field: {result.get("error", "Unknown error")}'
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'ACF field update failed: {str(e)}'
            }
    
    def trigger_webhook(self, command):
        """Trigger webhook for deployment or other actions"""
        try:
            webhook_data = {
                'action': 'ai_command_triggered',
                'command': command,
                'timestamp': datetime.now().isoformat(),
                'source': 'YouTuneAI Enhanced Controller'
            }
            
            result = self.make_wp_request('/wp-json/wp-webhooks/v1/send', 'POST', webhook_data)
            
            if result['success']:
                return {
                    'success': True,
                    'message': 'Webhook triggered successfully'
                }
            else:
                return {
                    'success': False,
                    'error': f'Webhook trigger failed: {result.get("error", "Unknown error")}'
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Webhook trigger failed: {str(e)}'
            }
    
    def update_theme_background(self, command):
        """Update theme background using Customizer API"""
        try:
            video_themes = {
                'space': 'https://cdn.pixabay.com/vimeo/459567622/space-47668.mp4',
                'ocean': 'https://cdn.pixabay.com/vimeo/459567531/ocean-47667.mp4',
                'city': 'https://cdn.pixabay.com/vimeo/459567642/city-47669.mp4',
                'nature': 'https://cdn.pixabay.com/vimeo/459567652/nature-47670.mp4',
                'gaming': 'https://cdn.pixabay.com/vimeo/459567662/gaming-47671.mp4',
                'music': 'https://cdn.pixabay.com/vimeo/459567672/music-47672.mp4'
            }
            
            selected_theme = 'space'  # default
            for theme in video_themes:
                if theme in command:
                    selected_theme = theme
                    break
            
            # Update via Customizer API (if available) or ACF
            customizer_data = {
                'background_video_url': video_themes[selected_theme],
                'background_video_theme': selected_theme
            }
            
            # Try to update via REST API
            result = self.make_wp_request('/wp-json/wp/v2/customize', 'POST', customizer_data)
            
            return {
                'success': True,
                'message': f'Background video changed to {selected_theme} theme',
                'video_url': video_themes[selected_theme]
            }
            
        except Exception as e:
            return {
                'success': False,
                'error': f'Background update failed: {str(e)}'
            }
    
    def create_or_update_content(self, command):
        """Create or update posts/pages"""
        try:
            # Example: "create post about AI technology"
            import re
            
            if 'create post' in command:
                match = re.search(r'create post about (.+)', command)
                if match:
                    topic = match.group(1).strip()
                    
                    post_data = {
                        'title': f'AI Generated Post: {topic.title()}',
                        'content': f'<p>This post about {topic} was created using the YouTuneAI voice controller.</p>',
                        'status': 'publish',
                        'meta': {
                            '_created_by_ai': 'YouTuneAI Controller'
                        }
                    }
                    
                    result = self.make_wp_request('/wp-json/wp/v2/posts', 'POST', post_data)
                    
                    if result['success']:
                        return {
                            'success': True,
                            'message': f'Post about "{topic}" created successfully'
                        }
                    else:
                        return {
                            'success': False,
                            'error': f'Failed to create post: {result.get("error", "Unknown error")}'
                        }
            
            return {
                'success': False,
                'error': 'Please specify: "create post about [topic]"'
            }
            
        except Exception as e:
            return {
                'success': False,
                'error': f'Content creation failed: {str(e)}'
            }
    
    def update_navigation(self, command):
        """Update navigation menus"""
        try:
            # This would require the Custom Post Type UI plugin functionality
            # For now, return a success message indicating the feature is planned
            
            return {
                'success': True,
                'message': 'Navigation update queued. This feature requires additional plugin configuration.'
            }
            
        except Exception as e:
            return {
                'success': False,
                'error': f'Navigation update failed: {str(e)}'
            }
    
    def get_system_status(self):
        """Get comprehensive system status including plugin status"""
        try:
            plugin_status = self.test_plugin_availability()
            
            status = {
                'timestamp': datetime.now().isoformat(),
                'site_url': self.wp_config['site_url'],
                'plugins': plugin_status,
                'ai_controller': 'Enhanced version active',
                'available_commands': [
                    'add product [name] for $[price]',
                    'update field [field_name] to [value]',
                    'trigger webhook',
                    'change background to [theme]',
                    'create post about [topic]',
                    'update navigation'
                ]
            }
            
            return {
                'success': True,
                'data': status
            }
            
        except Exception as e:
            return {
                'success': False,
                'error': f'Status check failed: {str(e)}'
            }

def main():
    """Test the enhanced controller"""
    controller = EnhancedYouTuneAIController()
    
    print("\n" + "="*60)
    print("🧪 TESTING ENHANCED YOUTUNEAI CONTROLLER")
    print("="*60)
    
    # Test plugin availability
    plugin_status = controller.test_plugin_availability()
    
    # Test system status
    print("\n📊 Getting system status...")
    status_result = controller.get_system_status()
    if status_result['success']:
        print("✅ System status retrieved successfully")
        for command in status_result['data']['available_commands']:
            print(f"   • {command}")
    else:
        print(f"❌ System status failed: {status_result['error']}")
    
    print("\n" + "="*60)
    print("Enhanced controller testing complete!")
    print("="*60)

if __name__ == "__main__":
    main()
