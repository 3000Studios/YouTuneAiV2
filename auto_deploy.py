#!/usr/bin/env python3
"""
Auto-Deploy System for YouTuneAI
Automatically deploys changes without user intervention
"""
import os
import paramiko
import shutil
from pathlib import Path

class AutoDeploy:
    def __init__(self):
        self.local_theme_path = "wp-theme-youtuneai"
        self.sftp_config = {
            'host': 'access-5017098454.webspace-host.com',
            'username': 'a132096',
            'password': 'Gabby3000!!!',
            'port': 22,
            'remote_path': '/wp-content/themes/youtuneai/'
        }
    
    def deploy_all_files(self):
        """Deploy all theme files automatically"""
        try:
            print("🚀 Starting automatic deployment...")
            
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            ssh.connect(
                hostname=self.sftp_config['host'],
                username=self.sftp_config['username'],
                password=self.sftp_config['password'],
                port=self.sftp_config['port']
            )
            
            sftp = ssh.open_sftp()
            
            # Deploy key files
            files_to_deploy = [
                'page-home.php',
                'page-admin-dashboard.php',
                'functions.php',
                'style.css',
                'header.php',
                'footer.php'
            ]
            
            deployed_count = 0
            for file in files_to_deploy:
                local_file = os.path.join(self.local_theme_path, file)
                remote_file = self.sftp_config['remote_path'] + file
                
                if os.path.exists(local_file):
                    sftp.put(local_file, remote_file)
                    print(f"✅ Deployed {file}")
                    deployed_count += 1
                else:
                    print(f"⚠️ File not found: {file}")
            
            sftp.close()
            ssh.close()
            
            print(f"🎉 Deployment complete! {deployed_count} files deployed to youtuneai.com")
            return True
            
        except Exception as e:
            print(f"❌ Deployment failed: {str(e)}")
            return False

if __name__ == "__main__":
    deployer = AutoDeploy()
    deployer.deploy_all_files()
