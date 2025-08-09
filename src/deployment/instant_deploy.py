#!/usr/bin/env python3
"""
INSTANT DEPLOY BEAST - ZERO MERCY EDITION
"""
import os
import paramiko

# BEAST CONFIG
SFTP_HOST = "access-5017098454.webspace-host.com"
SFTP_USER = "a132096"  
SFTP_PASS = "Gabby3000!!!"
SFTP_PORT = 22
REMOTE_PATH = "/wp-content/themes/youtuneai"
LOCAL_PATH = "wp-theme-youtuneai"

def deploy_beast():
    """Deploy with ZERO confirmation"""
    print("🔥 BEAST DEPLOYING ADMIN FIXES...")
    
    try:
        ssh = paramiko.SSHClient()
        ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        ssh.connect(SFTP_HOST, port=SFTP_PORT, username=SFTP_USER, password=SFTP_PASS)
        sftp = ssh.open_sftp()
        
        # Deploy critical files
        files = ['page-home.php', 'page-admin-dashboard.php']
        
        for file in files:
            local_file = os.path.join(LOCAL_PATH, file)
            remote_file = f"{REMOTE_PATH}/{file}"
            
            if os.path.exists(local_file):
                sftp.put(local_file, remote_file)
                print(f"✅ DEPLOYED: {file}")
        
        sftp.close()
        ssh.close()
        
        print("""
💀 BEAST DEPLOYMENT COMPLETE! 💀

✅ Admin Login: Mr.jwswain@gmail.com
✅ Password: Gabby3000!!!
✅ Voice Command button → Secure admin login
✅ LIVE on youtuneai.com

🎯 ZERO CONFIRMATION ACHIEVED!
        """)
        
    except Exception as e:
        print(f"💀 BEAST RAGE: {e}")

if __name__ == "__main__":
    deploy_beast()
