#!/usr/bin/env python3
"""
WordPress Configuration Manager for YouTuneAI
Secure credential management and connection testing

Copyright (c) 2025 Mr. Swain (3000Studios)
Patent Pending - All Rights Reserved
"""

import os
import json
from typing import Dict, Any
import getpass

class WordPressConfigManager:
    def __init__(self):
        """Initialize configuration manager"""
        self.config_file = '.wp_config.json'
        self.config = self.load_config()
    
    def load_config(self) -> Dict[str, Any]:
        """Load configuration from file"""
        if os.path.exists(self.config_file):
            try:
                with open(self.config_file, 'r') as f:
                    return json.load(f)
            except Exception as e:
                print(f"⚠️ Error loading config: {e}")
                return {}
        return {}
    
    def save_config(self, config: Dict[str, Any]) -> None:
        """Save configuration to file"""
        try:
            with open(self.config_file, 'w') as f:
                json.dump(config, f, indent=2)
            print(f"✅ Configuration saved to {self.config_file}")
        except Exception as e:
            print(f"❌ Error saving config: {e}")
    
    def setup_sftp_credentials(self) -> Dict[str, str]:
        """Interactive SFTP credential setup"""
        print("🔐 SFTP Credential Setup")
        print("=" * 40)
        
        # Get current credentials or defaults
        current_sftp = self.config.get('sftp', {})
        
        host = input(f"SFTP Host [{current_sftp.get('host', 'access-5017098454.webspace-host.com')}]: ").strip()
        if not host:
            host = current_sftp.get('host', 'access-5017098454.webspace-host.com')
        
        username = input(f"SFTP Username [{current_sftp.get('username', 'u98387750')}]: ").strip()
        if not username:
            username = current_sftp.get('username', 'u98387750')
        
        # Get password securely
        password = getpass.getpass("SFTP Password: ").strip()
        if not password and current_sftp.get('password'):
            use_current = input("Use saved password? (y/n): ").lower().startswith('y')
            if use_current:
                password = current_sftp.get('password')
        
        port = input(f"SFTP Port [{current_sftp.get('port', '22')}]: ").strip()
        if not port:
            port = current_sftp.get('port', '22')
        
        try:
            port = int(port)
        except ValueError:
            port = 22
        
        sftp_config = {
            'host': host,
            'username': username,
            'password': password,
            'port': port
        }
        
        return sftp_config
    
    def setup_wordpress_credentials(self) -> Dict[str, str]:
        """Interactive WordPress credential setup"""
        print("\\n🔐 WordPress Credential Setup")
        print("=" * 40)
        
        current_wp = self.config.get('wordpress', {})
        
        site_url = input(f"WordPress Site URL [{current_wp.get('site_url', 'https://youtuneai.com')}]: ").strip()
        if not site_url:
            site_url = current_wp.get('site_url', 'https://youtuneai.com')
        
        admin_user = input(f"WordPress Admin Username [{current_wp.get('admin_user', 'admin')}]: ").strip()
        if not admin_user:
            admin_user = current_wp.get('admin_user', 'admin')
        
        app_password = getpass.getpass("WordPress Application Password (leave empty to skip): ").strip()
        if not app_password and current_wp.get('app_password'):
            use_current = input("Use saved application password? (y/n): ").lower().startswith('y')
            if use_current:
                app_password = current_wp.get('app_password')
        
        wp_config = {
            'site_url': site_url,
            'admin_user': admin_user,
            'app_password': app_password
        }
        
        return wp_config
    
    def test_sftp_connection(self, sftp_config: Dict[str, str]) -> bool:
        """Test SFTP connection"""
        try:
            import paramiko
            
            print("🔍 Testing SFTP connection...")
            
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            ssh.connect(
                hostname=sftp_config['host'],
                username=sftp_config['username'],
                password=sftp_config['password'],
                port=sftp_config['port'],
                timeout=10
            )
            
            sftp = ssh.open_sftp()
            
            # Test basic operations
            try:
                sftp.listdir('/')
                print("✅ SFTP connection successful!")
                
                # Test WordPress directory
                try:
                    wp_files = sftp.listdir('/')
                    wp_indicators = ['wp-config.php', 'wp-admin', 'wp-content', 'wp-includes']
                    found_wp = any(indicator in wp_files for indicator in wp_indicators)
                    
                    if found_wp:
                        print("✅ WordPress installation detected!")
                    else:
                        print("⚠️ WordPress installation not found in root directory")
                        # Try common subdirectories
                        common_paths = ['/public_html', '/htdocs', '/www', '/web']
                        for path in common_paths:
                            try:
                                subdir_files = sftp.listdir(path)
                                found_wp_sub = any(indicator in subdir_files for indicator in wp_indicators)
                                if found_wp_sub:
                                    print(f"✅ WordPress found in {path}/")
                                    break
                            except Exception:
                                continue
                
                except Exception as e:
                    print(f"⚠️ Could not verify WordPress installation: {e}")
                
                sftp.close()
                ssh.close()
                return True
                
            except Exception as e:
                print(f"❌ SFTP operation failed: {e}")
                sftp.close()
                ssh.close()
                return False
                
        except Exception as e:
            print(f"❌ SFTP connection failed: {e}")
            return False
    
    def test_wordpress_connection(self, wp_config: Dict[str, str]) -> bool:
        """Test WordPress REST API connection"""
        try:
            import requests
            import base64
            
            print("🔍 Testing WordPress REST API connection...")
            
            session = requests.Session()
            
            if wp_config.get('app_password'):
                auth_string = f"{wp_config['admin_user']}:{wp_config['app_password']}"
                encoded_auth = base64.b64encode(auth_string.encode()).decode()
                session.headers.update({
                    'Authorization': f'Basic {encoded_auth}',
                    'Content-Type': 'application/json'
                })
            
            # Test basic API endpoint
            response = session.get(f"{wp_config['site_url']}/wp-json/wp/v2/", timeout=10)
            
            if response.status_code == 200:
                print("✅ WordPress REST API accessible!")
                
                # Test authenticated endpoint if password provided
                if wp_config.get('app_password'):
                    auth_response = session.get(f"{wp_config['site_url']}/wp-json/wp/v2/users/me", timeout=10)
                    if auth_response.status_code == 200:
                        user_data = auth_response.json()
                        print(f"✅ Authentication successful! Logged in as: {user_data.get('name', 'Unknown')}")
                        return True
                    else:
                        print(f"❌ Authentication failed: {auth_response.status_code}")
                        return False
                else:
                    print("⚠️ No application password provided - authentication test skipped")
                    return True
            else:
                print(f"❌ WordPress REST API not accessible: {response.status_code}")
                return False
                
        except Exception as e:
            print(f"❌ WordPress connection test failed: {e}")
            return False
    
    def setup_complete_configuration(self) -> Dict[str, Any]:
        """Complete interactive configuration setup"""
        print("🚀 YouTuneAI Complete Configuration Setup")
        print("🔒 Patent Pending Technology - Copyright (c) 2025 Mr. Swain (3000Studios)")
        print("=" * 70)
        
        # Setup SFTP credentials
        sftp_config = self.setup_sftp_credentials()
        
        # Test SFTP connection
        if not self.test_sftp_connection(sftp_config):
            print("❌ SFTP connection failed. Please check your credentials.")
            retry = input("Retry SFTP setup? (y/n): ").lower().startswith('y')
            if retry:
                sftp_config = self.setup_sftp_credentials()
                if not self.test_sftp_connection(sftp_config):
                    print("❌ SFTP connection still failing. Continuing anyway...")
        
        # Setup WordPress credentials
        wp_config = self.setup_wordpress_credentials()
        
        # Test WordPress connection
        if wp_config.get('app_password'):
            if not self.test_wordpress_connection(wp_config):
                print("❌ WordPress connection failed. Please check your credentials.")
                retry = input("Retry WordPress setup? (y/n): ").lower().startswith('y')
                if retry:
                    wp_config = self.setup_wordpress_credentials()
        
        # Save complete configuration
        complete_config = {
            'sftp': sftp_config,
            'wordpress': wp_config,
            'setup_date': str(datetime.now()),
            'version': '1.0'
        }
        
        self.config = complete_config
        self.save_config(complete_config)
        
        print("\\n🎉 Configuration setup complete!")
        print("✅ SFTP credentials configured")
        print("✅ WordPress credentials configured")
        print("🔄 Ready for plugin installation!")
        
        return complete_config
    
    def get_sftp_config(self) -> Dict[str, str]:
        """Get SFTP configuration"""
        return self.config.get('sftp', {})
    
    def get_wordpress_config(self) -> Dict[str, str]:
        """Get WordPress configuration"""
        return self.config.get('wordpress', {})

def main():
    """Main configuration setup"""
    from datetime import datetime
    
    config_manager = WordPressConfigManager()
    
    if not config_manager.config:
        print("🔧 No configuration found. Setting up new configuration...")
        config_manager.setup_complete_configuration()
    else:
        print("📋 Existing configuration found.")
        print("Current SFTP Host:", config_manager.config.get('sftp', {}).get('host', 'Not set'))
        print("Current WordPress URL:", config_manager.config.get('wordpress', {}).get('site_url', 'Not set'))
        
        reconfigure = input("\\nReconfigure credentials? (y/n): ").lower().startswith('y')
        if reconfigure:
            config_manager.setup_complete_configuration()
        else:
            # Test existing configuration
            print("\\n🔍 Testing existing configuration...")
            sftp_config = config_manager.get_sftp_config()
            wp_config = config_manager.get_wordpress_config()
            
            if sftp_config:
                config_manager.test_sftp_connection(sftp_config)
            
            if wp_config and wp_config.get('app_password'):
                config_manager.test_wordpress_connection(wp_config)
    
    print("\\n✅ Configuration manager ready!")
    return config_manager

if __name__ == "__main__":
    main()
