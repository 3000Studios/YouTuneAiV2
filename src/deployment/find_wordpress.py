#!/usr/bin/env python3
"""
Deep Server Explorer
Find the WordPress installation
"""

import paramiko

def find_wordpress():
    """Find WordPress installation on server"""
    
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
        
        # Check clickandbuilds directory
        print("\n🔍 Exploring clickandbuilds directory...")
        try:
            clickandbuilds = sftp.listdir('clickandbuilds')
            print(f"  ✅ Found clickandbuilds contents:")
            for item in sorted(clickandbuilds):
                print(f"    📂 {item}")
                
                # Check each subdirectory for WordPress
                subpath = f"clickandbuilds/{item}"
                try:
                    subcontents = sftp.listdir(subpath)
                    print(f"      Contents of {item}:")
                    for subitem in sorted(subcontents):
                        print(f"        📄 {subitem}")
                        if subitem == 'wp-content':
                            print(f"        🎯 FOUND WORDPRESS! Path: {subpath}/wp-content")
                            
                            # Check themes
                            themes_path = f"{subpath}/wp-content/themes"
                            try:
                                themes = sftp.listdir(themes_path)
                                print(f"        🎨 Themes found:")
                                for theme in sorted(themes):
                                    print(f"          🎨 {theme}")
                                    if 'youtuneai' in theme.lower():
                                        print(f"          🎯 TARGET THEME FOUND: {theme}")
                            except:
                                print(f"        ❌ No themes directory")
                        
                except Exception as e:
                    print(f"      ❌ Cannot access {item}: {str(e)}")
                    
        except Exception as e:
            print(f"  ❌ Cannot access clickandbuilds: {str(e)}")
        
        # Also check homepages directory
        print("\n🔍 Exploring homepages directory...")
        try:
            homepages = sftp.listdir('homepages')
            print(f"  ✅ Found homepages contents:")
            for item in sorted(homepages):
                print(f"    📂 {item}")
                
                # Check for WordPress in homepages
                subpath = f"homepages/{item}"
                try:
                    subcontents = sftp.listdir(subpath)
                    for subitem in sorted(subcontents):
                        if subitem == 'wp-content':
                            print(f"        🎯 FOUND WORDPRESS! Path: {subpath}/wp-content")
                except:
                    pass
                    
        except Exception as e:
            print(f"  ❌ Cannot access homepages: {str(e)}")
        
        sftp.close()
        ssh.close()
        
    except Exception as e:
        print(f"❌ Connection failed: {str(e)}")

if __name__ == "__main__":
    find_wordpress()
