#!/usr/bin/env python3
"""
YouTuneAI Connection Tester with Updated Credentials
Tests SFTP, WordPress, and Plugin Functionality
"""

import os
import sys
import paramiko
import requests
import json
from datetime import datetime
from dotenv import load_dotenv
import urllib3

# Disable SSL warnings for testing
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

# Load secure credentials
load_dotenv('secrets.env')

class YouTuneAIConnectionTester:
    def __init__(self):
        """Initialize with credentials from secrets.env"""
        
        self.sftp_config = {
            'host': os.getenv('SFTP_HOST'),
            'username': os.getenv('SFTP_USERNAME'),
            'password': os.getenv('SFTP_PASSWORD'),
            'port': int(os.getenv('SFTP_PORT', '22')),
            'remote_path': os.getenv('SFTP_REMOTE_PATH', '/wp-content/themes/youtuneai/')
        }
        
        self.wp_config = {
            'site_url': os.getenv('WP_SITE_URL', 'https://youtuneai.com'),
            'api_url': os.getenv('WP_API_URL', 'https://youtuneai.com/wp-json/wp/v2/'),
            'admin_user': os.getenv('WP_ADMIN_USER'),
            'admin_pass': os.getenv('WP_ADMIN_PASS')
        }
        
        self.admin_credentials = {
            'username': os.getenv('ADMIN_USERNAME'),
            'password': os.getenv('ADMIN_PASSWORD')
        }
        
        print("🚀 YouTuneAI Connection Tester Initialized")
        print("🔐 Credentials loaded from secrets.env")
        
    def test_sftp_connection(self):
        """Test SFTP connection with updated credentials"""
        try:
            print("\\n🔍 Testing SFTP Connection...")
            print(f"   Host: {self.sftp_config['host']}")
            print(f"   Username: {self.sftp_config['username']}")
            print(f"   Port: {self.sftp_config['port']}")
            
            # Create SSH client
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            # Connect
            ssh.connect(
                hostname=self.sftp_config['host'],
                username=self.sftp_config['username'],
                password=self.sftp_config['password'],
                port=self.sftp_config['port'],
                timeout=30
            )
            
            # Test SFTP functionality
            sftp = ssh.open_sftp()
            
            # List remote directory
            try:
                remote_files = sftp.listdir(self.sftp_config['remote_path'])
                print(f"✅ SFTP Connection: SUCCESS")
                print(f"   Remote path accessible: {self.sftp_config['remote_path']}")
                print(f"   Files found: {len(remote_files)}")
                if remote_files:
                    print(f"   Sample files: {remote_files[:5]}")
            except Exception as dir_error:
                print(f"⚠️  SFTP Connected but directory issue: {str(dir_error)}")
                # Try to list root directory
                try:
                    root_files = sftp.listdir('/')
                    print(f"   Root directory files: {root_files[:10]}")
                except:
                    pass
            
            sftp.close()
            ssh.close()
            
            return {
                'success': True,
                'message': 'SFTP connection successful'
            }
            
        except paramiko.AuthenticationException:
            print("❌ SFTP Connection: AUTHENTICATION FAILED")
            return {
                'success': False,
                'error': 'Authentication failed - check username/password'
            }
        except paramiko.SSHException as e:
            print(f"❌ SFTP Connection: SSH ERROR - {str(e)}")
            return {
                'success': False,
                'error': f'SSH error: {str(e)}'
            }
        except Exception as e:
            print(f"❌ SFTP Connection: FAILED - {str(e)}")
            return {
                'success': False,
                'error': str(e)
            }
    
    def test_wordpress_access(self):
        """Test WordPress site and API access"""
        try:
            print("\\n🔍 Testing WordPress Access...")
            print(f"   Site URL: {self.wp_config['site_url']}")
            
            # Test main site
            response = requests.get(self.wp_config['site_url'], verify=False, timeout=15)
            print(f"   Main site status: {response.status_code}")
            
            # Test WordPress REST API
            api_response = requests.get(self.wp_config['api_url'], verify=False, timeout=15)
            print(f"   REST API status: {api_response.status_code}")
            
            if api_response.status_code == 200:
                api_data = api_response.json()
                print(f"✅ WordPress Access: SUCCESS")
                print(f"   Site name: {api_data.get('name', 'Unknown')}")
                print(f"   Description: {api_data.get('description', 'None')}")
                
                return {
                    'success': True,
                    'message': 'WordPress site accessible',
                    'data': api_data
                }
            else:
                print(f"❌ WordPress Access: API returned {api_response.status_code}")
                return {
                    'success': False,
                    'error': f'API returned status {api_response.status_code}'
                }
                
        except requests.exceptions.SSLError as e:
            print(f"⚠️  WordPress Access: SSL ERROR - {str(e)}")
            return {
                'success': False,
                'error': f'SSL error: {str(e)}'
            }
        except Exception as e:
            print(f"❌ WordPress Access: FAILED - {str(e)}")
            return {
                'success': False,
                'error': str(e)
            }
    
    def test_plugin_endpoints(self):
        """Test WordPress plugin endpoints"""
        try:
            print("\\n🔍 Testing WordPress Plugin Endpoints...")
            
            plugin_endpoints = {
                'WooCommerce': '/wp-json/wc/v3/system_status',
                'Advanced Custom Fields': '/wp-json/acf/v3/options',
                'WP Webhooks': '/wp-json/wp-webhooks/v1/',
                'Custom Post Types': '/wp-json/wp/v2/types',
                'REST API Controller': '/wp-json/wp/v2/posts',
                'Code Snippets': '/wp-json/wp/v2/code-snippets'
            }
            
            results = {}
            
            for plugin_name, endpoint in plugin_endpoints.items():
                try:
                    url = self.wp_config['site_url'] + endpoint
                    response = requests.get(url, verify=False, timeout=10)
                    
                    if response.status_code == 200:
                        print(f"✅ {plugin_name}: Available ({response.status_code})")
                        results[plugin_name] = True
                    elif response.status_code == 401:
                        print(f"🔐 {plugin_name}: Requires authentication ({response.status_code})")
                        results[plugin_name] = 'auth_required'
                    else:
                        print(f"❌ {plugin_name}: Not available ({response.status_code})")
                        results[plugin_name] = False
                        
                except Exception as e:
                    print(f"❌ {plugin_name}: Error - {str(e)[:50]}...")
                    results[plugin_name] = False
            
            return {
                'success': True,
                'results': results
            }
            
        except Exception as e:
            print(f"❌ Plugin endpoint testing failed: {str(e)}")
            return {
                'success': False,
                'error': str(e)
            }
    
    def test_admin_dashboard_access(self):
        """Test admin dashboard functionality"""
        try:
            print("\\n🔍 Testing Admin Dashboard Access...")
            
            # Test admin dashboard page
            dashboard_url = f"{self.wp_config['site_url']}/admin-dashboard/"
            response = requests.get(dashboard_url, verify=False, timeout=10)
            
            print(f"   Dashboard URL: {dashboard_url}")
            print(f"   Status: {response.status_code}")
            
            if response.status_code == 200:
                print("✅ Admin Dashboard: Accessible")
                
                # Check if our discrete admin button is present
                if 'discrete-admin-btn' in response.text:
                    print("✅ Discrete Admin Button: Present")
                else:
                    print("⚠️  Discrete Admin Button: Not found in HTML")
                
                # Check for voice control elements
                if 'voiceControlBtn' in response.text:
                    print("✅ Voice Control: Interface present")
                else:
                    print("⚠️  Voice Control: Interface not found")
                    
                return {
                    'success': True,
                    'message': 'Admin dashboard accessible'
                }
            else:
                print(f"❌ Admin Dashboard: Not accessible ({response.status_code})")
                return {
                    'success': False,
                    'error': f'Dashboard returned status {response.status_code}'
                }
                
        except Exception as e:
            print(f"❌ Admin dashboard test failed: {str(e)}")
            return {
                'success': False,
                'error': str(e)
            }
    
    def test_streaming_page(self):
        """Test streaming page functionality"""
        try:
            print("\\n🔍 Testing Streaming Page...")
            
            streaming_url = f"{self.wp_config['site_url']}/streaming/"
            response = requests.get(streaming_url, verify=False, timeout=10)
            
            print(f"   Streaming URL: {streaming_url}")
            print(f"   Status: {response.status_code}")
            
            if response.status_code == 200:
                print("✅ Streaming Page: Accessible")
                
                # Check for streaming elements
                if 'getUserMedia' in response.text or 'webkitGetUserMedia' in response.text:
                    print("✅ WebRTC Streaming: Code present")
                else:
                    print("⚠️  WebRTC Streaming: Code not found")
                
                return {
                    'success': True,
                    'message': 'Streaming page accessible'
                }
            else:
                print(f"❌ Streaming Page: Not accessible ({response.status_code})")
                return {
                    'success': False,
                    'error': f'Streaming page returned status {response.status_code}'
                }
                
        except Exception as e:
            print(f"❌ Streaming page test failed: {str(e)}")
            return {
                'success': False,
                'error': str(e)
            }
    
    def run_comprehensive_test(self):
        """Run all tests and generate comprehensive report"""
        print("🧪 YOUTUNEAI COMPREHENSIVE CONNECTION TEST")
        print("=" * 60)
        print(f"🕐 Test started: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
        print("=" * 60)
        
        results = {}
        
        # Test SFTP
        results['sftp'] = self.test_sftp_connection()
        
        # Test WordPress
        results['wordpress'] = self.test_wordpress_access()
        
        # Test Plugins
        results['plugins'] = self.test_plugin_endpoints()
        
        # Test Admin Dashboard
        results['admin_dashboard'] = self.test_admin_dashboard_access()
        
        # Test Streaming Page
        results['streaming_page'] = self.test_streaming_page()
        
        # Generate summary
        print("\\n" + "=" * 60)
        print("📊 TEST SUMMARY")
        print("=" * 60)
        
        total_tests = 0
        passed_tests = 0
        
        for test_name, result in results.items():
            total_tests += 1
            if result.get('success'):
                passed_tests += 1
                print(f"✅ {test_name.replace('_', ' ').title()}: PASSED")
            else:
                print(f"❌ {test_name.replace('_', ' ').title()}: FAILED - {result.get('error', 'Unknown error')}")
        
        success_rate = (passed_tests / total_tests) * 100
        print(f"\\n📈 Overall Success Rate: {success_rate:.1f}% ({passed_tests}/{total_tests})")
        
        if success_rate >= 80:
            print("🎉 SYSTEM STATUS: EXCELLENT - Ready for deployment!")
        elif success_rate >= 60:
            print("⚠️  SYSTEM STATUS: GOOD - Minor issues to resolve")
        else:
            print("🚨 SYSTEM STATUS: NEEDS ATTENTION - Major issues found")
        
        # Save detailed report
        report_data = {
            'timestamp': datetime.now().isoformat(),
            'success_rate': success_rate,
            'results': results,
            'credentials_used': {
                'sftp_host': self.sftp_config['host'],
                'sftp_username': self.sftp_config['username'],
                'wp_site_url': self.wp_config['site_url'],
                'wp_admin_user': self.wp_config['admin_user']
            }
        }
        
        with open('connection_test_report.json', 'w') as f:
            json.dump(report_data, f, indent=2)
        
        print(f"\\n📄 Detailed report saved to: connection_test_report.json")
        print("=" * 60)
        
        return results

def main():
    """Run the comprehensive connection test"""
    tester = YouTuneAIConnectionTester()
    results = tester.run_comprehensive_test()
    
    return results

if __name__ == "__main__":
    main()
