#!/usr/bin/env python3
"""
Direct CSS Upload Script
Simple script to upload just the style.css file with blue changes
"""

import paramiko
import os
import sys

def upload_css():
    """Upload the updated CSS file directly"""
    
    # SFTP configuration
    sftp_config = {
        'host': 'access-5017098454.webspace-host.com',
        'port': 22,
        'username': 'a917580',
        'password': 'Gabby3000!!!',
    }
    
    local_css = r'C:\YouTuneAiV2\deployment-ready\wp-theme-youtuneai\style.css'
    remote_css = 'clickandbuilds/YouTuneAi/wp-content/themes/wp-theme-youtuneai/style.css'
    
    print("🎨 Uploading blue-themed CSS file...")
    print(f"Local file: {local_css}")
    print(f"Remote file: {remote_css}")
    
    try:
        # Verify local file exists
        if not os.path.exists(local_css):
            print(f"❌ Local CSS file not found: {local_css}")
            return False
            
        # Connect to server
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
        
        # Upload file
        print("📤 Uploading CSS file...")
        sftp = ssh.open_sftp()
        
        # Ensure remote directory exists
        try:
            sftp.stat('clickandbuilds/YouTuneAi/wp-content/themes/wp-theme-youtuneai')
            print("✅ Remote theme directory exists")
        except FileNotFoundError:
            print("❌ Remote theme directory not found")
            sftp.close()
            ssh.close()
            return False
        
        # Upload the file
        sftp.put(local_css, remote_css)
        print("✅ CSS file uploaded successfully!")
        
        # Verify upload
        remote_stat = sftp.stat(remote_css)
        local_size = os.path.getsize(local_css)
        print(f"📊 Local file size: {local_size} bytes")
        print(f"📊 Remote file size: {remote_stat.st_size} bytes")
        
        if local_size == remote_stat.st_size:
            print("✅ File sizes match - upload verified!")
        else:
            print("⚠️ File sizes don't match - upload may be incomplete")
        
        sftp.close()
        ssh.close()
        
        print("🎉 Blue color changes are now live!")
        print("🌐 Visit https://youtuneai.com to see the changes")
        print("💡 You may need to hard refresh (Ctrl+F5) to bypass browser cache")
        
        return True
        
    except Exception as e:
        print(f"❌ Upload failed: {str(e)}")
        return False

if __name__ == "__main__":
    success = upload_css()
    if success:
        print("\n🎨 SUCCESS! Your blue-themed buttons are now live!")
    else:
        print("\n💥 FAILED! Check the errors above.")
        sys.exit(1)
