#!/usr/bin/env python3
"""
Direct Root Deployment - Override WordPress
Deploy directly to website root to bypass WordPress theme system
"""

import os
import paramiko
import time
from pathlib import Path

class DirectRootDeployment:
    def __init__(self):
        self.workspace_path = Path(__file__).parent.parent.parent
        self.theme_source = self.workspace_path / "src" / "theme" / "deployment-ready" / "wp-theme-youtuneai"
        
        # Root deployment configuration
        self.sftp_config = {
            'host': 'access-5017098454.webspace-host.com',
            'port': 22,
            'username': 'a917580',
            'password': 'Gabby3000!!!',
            'remote_path': 'clickandbuilds/YouTuneAi'  # Root directory
        }
    
    def deploy_to_root(self):
        """Deploy our index.html directly to website root to override WordPress"""
        try:
            print("🚀 Starting direct root deployment...")
            
            # Create SFTP connection
            transport = paramiko.Transport((self.sftp_config['host'], self.sftp_config['port']))
            transport.connect(username=self.sftp_config['username'], password=self.sftp_config['password'])
            sftp = paramiko.SFTPClient.from_transport(transport)
            
            # Upload our working index.html to root as index.html
            local_file = self.theme_source / "index.html"
            remote_file = f"{self.sftp_config['remote_path']}/index.html"
            
            print(f"📤 Uploading {local_file} to {remote_file}")
            sftp.put(str(local_file), remote_file)
            
            # Also upload as home.html for backup
            backup_file = f"{self.sftp_config['remote_path']}/home.html"
            sftp.put(str(local_file), backup_file)
            
            sftp.close()
            transport.close()
            
            print("✅ Root deployment completed!")
            print("🌐 Your site should now show at: https://youtuneai.com")
            
            return True
            
        except Exception as e:
            print(f"❌ Root deployment failed: {str(e)}")
            return False

if __name__ == "__main__":
    deployer = DirectRootDeployment()
    deployer.deploy_to_root()
