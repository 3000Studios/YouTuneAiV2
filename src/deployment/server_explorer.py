#!/usr/bin/env python3
"""
Server Directory Explorer
Check what directories exist on the server
"""

import paramiko

def explore_server():
    """Explore server directory structure"""
    
    sftp_config = {
        'host': 'access-5017098454.webspace-host.com',
        'port': 22,
        'username': 'a917580',
        'password': 'Gabby3000!!!',
    }
    
    try:
        print("🔗 Connecting to server...")
        ssh = paramiko.SSHClient()
        ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        
        ssh.connect(
            sftp_config['host'],
            port=sftp_config['port'],
            username=sftp_config['username'],
            password=sftp_config['password'],
            timeout=30
        )
        
        sftp = ssh.open_sftp()
        
        # Check root directory
        print("\n📁 Root directory contents:")
        root_files = sftp.listdir('.')
        for item in sorted(root_files):
            print(f"  📂 {item}")
        
        # Check if wp-content exists
        paths_to_check = [
            '/wp-content',
            './wp-content', 
            'wp-content',
            '/public_html/wp-content',
            '/htdocs/wp-content',
            '/www/wp-content'
        ]
        
        for path in paths_to_check:
            try:
                print(f"\n🔍 Checking path: {path}")
                contents = sftp.listdir(path)
                print(f"  ✅ Found! Contents:")
                for item in sorted(contents):
                    print(f"    📂 {item}")
                    
                # If wp-content found, check themes
                themes_path = f"{path}/themes"
                try:
                    themes = sftp.listdir(themes_path)
                    print(f"  🎨 Themes directory contents:")
                    for theme in sorted(themes):
                        print(f"    🎨 {theme}")
                except:
                    print(f"  ❌ No themes directory in {path}")
                    
                break
                    
            except Exception as e:
                print(f"  ❌ Path not found: {path}")
        
        sftp.close()
        ssh.close()
        
    except Exception as e:
        print(f"❌ Connection failed: {str(e)}")

if __name__ == "__main__":
    explore_server()
