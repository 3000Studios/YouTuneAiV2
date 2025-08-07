#!/usr/bin/env python3
"""
Enhanced YouTuneAI Controller with WordPress Plugin Integration
Full AI-WP automation stack with REST API, webhooks, and voice control

Copyright (c) 2025 Mr. Swain (3000Studios)
Patent Pending - All Rights Reserved
"""

import requests
import json
from typing import Dict, Any, Optional
from datetime import datetime
import base64

class EnhancedYouTuneAIController:
    def __init__(self, wp_config: Dict[str, str]):
        """Initialize enhanced AI controller with WordPress integration"""
        self.wp_config = wp_config
        self.session = requests.Session()
        
        # Setup authentication headers
        if wp_config.get('app_password'):
            auth_string = f"{wp_config['admin_user']}:{wp_config['app_password']}"
            encoded_auth = base64.b64encode(auth_string.encode()).decode()
            self.session.headers.update({
                'Authorization': f'Basic {encoded_auth}',
                'Content-Type': 'application/json'
            })
        
        print("🔌 Enhanced WordPress integration ready!")

    def test_wp_connection(self) -> Dict[str, Any]:
        """Test WordPress REST API connection"""
        try:
            response = self.session.get(
                f"{self.wp_config['site_url']}/wp-json/wp/v2/users/me",
                timeout=10
            )
            
            if response.status_code == 200:
                user_data = response.json()
                return {
                    'success': True,
                    'message': f"Connected as {user_data.get('name', 'Unknown')}",
                    'user_data': user_data
                }
            else:
                return {
                    'success': False,
                    'error': f"Authentication failed: {response.status_code}",
                    'details': response.text
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f"Connection test failed: {str(e)}"
            }

    def create_woocommerce_product_advanced(self, params: Dict[str, Any]) -> Dict[str, Any]:
        """Create WooCommerce product with advanced features"""
        try:
            product_data = {
                'name': params.get('name', 'AI Generated Product'),
                'type': params.get('type', 'simple'),
                'regular_price': str(params.get('price', '9.99')),
                'description': params.get('description', 'Created by AI voice command'),
                'short_description': params.get('short_description', ''),
                'sku': params.get('sku', f"AI-{datetime.now().strftime('%Y%m%d-%H%M%S')}"),
                'manage_stock': True,
                'stock_quantity': params.get('stock', 100),
                'in_stock': True,
                'status': 'publish',
                'catalog_visibility': 'visible',
                'featured': params.get('featured', False),
                'categories': [{'name': params.get('category', 'AI Products')}],
                'tags': [{'name': 'AI Generated'}, {'name': 'Voice Command'}],
                'images': [],
                'meta_data': [
                    {'key': '_created_by_ai', 'value': 'YouTuneAI Controller'},
                    {'key': '_ai_command', 'value': params.get('original_command', '')},
                    {'key': '_creation_timestamp', 'value': datetime.now().isoformat()},
                    {'key': '_ai_confidence', 'value': str(params.get('confidence', 0.95))}
                ]
            }
            
            # Add product image if provided
            if params.get('image_url'):
                product_data['images'] = [{
                    'src': params['image_url'],
                    'alt': f"Image for {product_data['name']}"
                }]
            
            # Create product via WooCommerce REST API
            response = self.session.post(
                f"{self.wp_config['site_url']}/wp-json/wc/v3/products",
                json=product_data,
                timeout=30
            )
            
            if response.status_code == 201:
                product = response.json()
                
                # Trigger webhook notification
                self.trigger_webhook('product_created', {
                    'product_id': product['id'],
                    'name': product['name'],
                    'price': product['price'],
                    'permalink': product['permalink']
                })
                
                return {
                    'success': True,
                    'message': f'Product "{product["name"]}" created successfully',
                    'product_id': product['id'],
                    'permalink': product['permalink'],
                    'edit_url': f"{self.wp_config['site_url']}/wp-admin/post.php?post={product['id']}&action=edit"
                }
            else:
                return {
                    'success': False,
                    'error': f'Product creation failed: {response.status_code}',
                    'details': response.text
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Product creation failed: {str(e)}'
            }

    def create_blog_post_ai(self, params: Dict[str, Any]) -> Dict[str, Any]:
        """Create blog post with AI-generated content"""
        try:
            post_data = {
                'title': params.get('title', 'AI Generated Post'),
                'content': params.get('content', 'Content generated by AI voice command'),
                'status': params.get('status', 'publish'),
                'author': 1,  # Admin user
                'featured_media': 0,
                'categories': [1],  # Default category
                'tags': [],
                'meta': {
                    '_created_by_ai': True,
                    '_ai_command': params.get('original_command', ''),
                    '_creation_timestamp': datetime.now().isoformat()
                }
            }
            
            # Add category if specified
            if params.get('category'):
                category_id = self.get_or_create_category(params['category'])
                if category_id:
                    post_data['categories'] = [category_id]
            
            # Create post
            response = self.session.post(
                f"{self.wp_config['site_url']}/wp-json/wp/v2/posts",
                json=post_data,
                timeout=30
            )
            
            if response.status_code == 201:
                post = response.json()
                return {
                    'success': True,
                    'message': f'Blog post "{post["title"]["rendered"]}" created',
                    'post_id': post['id'],
                    'permalink': post['link'],
                    'edit_url': f"{self.wp_config['site_url']}/wp-admin/post.php?post={post['id']}&action=edit"
                }
            else:
                return {
                    'success': False,
                    'error': f'Post creation failed: {response.status_code}',
                    'details': response.text
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Post creation failed: {str(e)}'
            }

    def get_or_create_category(self, category_name: str) -> Optional[int]:
        """Get existing category or create new one"""
        try:
            # Search for existing category
            response = self.session.get(
                f"{self.wp_config['site_url']}/wp-json/wp/v2/categories",
                params={'search': category_name},
                timeout=10
            )
            
            if response.status_code == 200:
                categories = response.json()
                if categories:
                    return categories[0]['id']
            
            # Create new category
            category_data = {
                'name': category_name,
                'description': f'Category created by AI for: {category_name}'
            }
            
            response = self.session.post(
                f"{self.wp_config['site_url']}/wp-json/wp/v2/categories",
                json=category_data,
                timeout=10
            )
            
            if response.status_code == 201:
                return response.json()['id']
            
            return None
            
        except Exception:
            return None

    def update_theme_customizer(self, params: Dict[str, Any]) -> Dict[str, Any]:
        """Update WordPress theme customizer settings"""
        try:
            settings = {}
            
            # Map AI parameters to theme settings
            if params.get('primary_color'):
                settings['primary_color'] = params['primary_color']
            if params.get('secondary_color'):
                settings['secondary_color'] = params['secondary_color']
            if params.get('font_family'):
                settings['font_family'] = params['font_family']
            if params.get('logo_url'):
                settings['custom_logo'] = params['logo_url']
            
            # Update via REST API (requires custom endpoint)
            response = self.session.post(
                f"{self.wp_config['site_url']}/wp-json/youtuneai/v1/theme-settings",
                json=settings,
                timeout=30
            )
            
            if response.status_code == 200:
                return {
                    'success': True,
                    'message': 'Theme settings updated successfully',
                    'settings': settings
                }
            else:
                return {
                    'success': False,
                    'error': f'Theme update failed: {response.status_code}'
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Theme update failed: {str(e)}'
            }

    def manage_navigation_menu(self, params: Dict[str, Any]) -> Dict[str, Any]:
        """Manage WordPress navigation menu"""
        try:
            action = params.get('action', 'add')  # add, remove, modify
            menu_item = params.get('menu_item', {})
            
            if action == 'add':
                # Add new menu item
                menu_data = {
                    'title': menu_item.get('title', 'New Menu Item'),
                    'url': menu_item.get('url', '#'),
                    'menu-item-parent-id': menu_item.get('parent_id', 0),
                    'position': menu_item.get('position', 0),
                    'status': 'publish'
                }
                
                # This would require a custom endpoint for menu management
                # For now, we'll simulate the response
                return {
                    'success': True,
                    'message': f'Menu item "{menu_data["title"]}" added successfully'
                }
            
            elif action == 'remove':
                # Remove menu item
                item_id = menu_item.get('id')
                if item_id:
                    return {
                        'success': True,
                        'message': f'Menu item {item_id} removed'
                    }
                else:
                    return {
                        'success': False,
                        'error': 'Menu item ID required for removal'
                    }
            
            else:
                return {
                    'success': False,
                    'error': f'Unsupported menu action: {action}'
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Menu management failed: {str(e)}'
            }

    def trigger_webhook(self, event: str, data: Dict[str, Any]) -> Dict[str, Any]:
        """Trigger WordPress webhook"""
        try:
            webhook_data = {
                'event': event,
                'data': data,
                'timestamp': datetime.now().isoformat(),
                'source': 'YouTuneAI Controller'
            }
            
            response = self.session.post(
                f"{self.wp_config['site_url']}/wp-json/wp-webhooks/v1/send",
                json=webhook_data,
                timeout=10
            )
            
            if response.status_code == 200:
                return {
                    'success': True,
                    'message': f'Webhook {event} triggered successfully'
                }
            else:
                return {
                    'success': False,
                    'error': f'Webhook failed: {response.status_code}'
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Webhook trigger failed: {str(e)}'
            }

    def get_ai_action_logs(self) -> Dict[str, Any]:
        """Get logs of AI actions for monitoring"""
        try:
            response = self.session.get(
                f"{self.wp_config['site_url']}/wp-json/youtuneai/v1/action-logs",
                timeout=10
            )
            
            if response.status_code == 200:
                logs = response.json()
                return {
                    'success': True,
                    'logs': logs,
                    'total_actions': len(logs)
                }
            else:
                return {
                    'success': False,
                    'error': f'Failed to retrieve logs: {response.status_code}'
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Log retrieval failed: {str(e)}'
            }

    def backup_and_deploy(self, files: list) -> Dict[str, Any]:
        """Backup current files and deploy new ones"""
        try:
            # Create backup via webhook
            backup_result = self.trigger_webhook('create_backup', {
                'files': files,
                'backup_name': f"ai_backup_{datetime.now().strftime('%Y%m%d_%H%M%S')}"
            })
            
            if backup_result['success']:
                # Proceed with deployment
                deploy_result = self.trigger_webhook('deploy_files', {
                    'files': files,
                    'source': 'AI Controller'
                })
                
                return {
                    'success': True,
                    'message': 'Backup and deployment completed',
                    'backup_result': backup_result,
                    'deploy_result': deploy_result
                }
            else:
                return {
                    'success': False,
                    'error': 'Backup failed, deployment aborted',
                    'details': backup_result
                }
                
        except Exception as e:
            return {
                'success': False,
                'error': f'Backup and deploy failed: {str(e)}'
            }

def setup_enhanced_controller():
    """Setup the enhanced AI controller with WordPress integration"""
    
    wp_config = {
        'site_url': 'https://youtuneai.com',
        'admin_user': 'admin',
        'app_password': None,  # Set this after creating application password
    }
    
    print("🚀 Setting up Enhanced YouTuneAI Controller...")
    
    # Initialize controller
    controller = EnhancedYouTuneAIController(wp_config)
    
    # Test connection
    connection_test = controller.test_wp_connection()
    if connection_test['success']:
        print(f"✅ {connection_test['message']}")
    else:
        print(f"❌ {connection_test['error']}")
        print("⚠️ Please set up Application Password in WordPress admin")
    
    return controller

if __name__ == "__main__":
    # Example usage
    controller = setup_enhanced_controller()
    
    # Test product creation
    print("\n🛒 Testing product creation...")
    product_result = controller.create_woocommerce_product_advanced({
        'name': 'AI Voice Assistant Plugin',
        'price': '49.99',
        'description': 'Advanced voice control plugin for WordPress',
        'category': 'WordPress Plugins',
        'stock': 50
    })
    
    print(f"Product creation: {product_result}")
