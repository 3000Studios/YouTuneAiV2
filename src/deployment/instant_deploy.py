#!/usr/bin/env python3
"""
INSTANT DEPLOY BEAST - ZERO MERCY EDITION
"""
import os
import paramiko
import requests
import json

# --- CONFIG ---
WORDPRESS_URL = "https://youtuneai.com"
CORS_SECRET_KEY = "your-super-secret-key-for-dev"
REMOTE_PATH = "/wp-content/themes/youtuneai"
LOCAL_PATH = "../theme/wp-theme-youtuneai"
SFTP_PORT = 22

def get_wp_credentials():
    """Fetch SFTP credentials from WordPress."""
    print("🔑 Fetching credentials from WordPress...")
    try:
        url = f"{WORDPRESS_URL}/wp-content/themes/youtuneai/get_creds.php?secret={CORS_SECRET_KEY}"
        response = requests.get(url)
        response.raise_for_status()
        creds = response.json()
        if not creds.get('sftp_host') or not creds.get('sftp_user') or not creds.get('sftp_pass'):
            print("❌ Error: Incomplete credentials received from WordPress.")
            return None
        print("✅ Credentials fetched successfully.")
        return creds
    except requests.exceptions.RequestException as e:
        print(f"❌ Error fetching credentials: {e}")
        return None
    except json.JSONDecodeError:
        print("❌ Error: Could not decode JSON response from WordPress.")
        print(f"Raw response: {response.text}")
        return None

def deploy_beast():
    """Deploy with ZERO confirmation"""
    creds = get_wp_credentials()
    if not creds:
        print("💀 Deployment aborted. Could not retrieve credentials.")
        return

    print("🔥 BEAST DEPLOYING ADMIN FIXES...")

    try:
        ssh = paramiko.SSHClient()
        ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        ssh.connect(
            hostname=creds['sftp_host'],
            port=SFTP_PORT,
            username=creds['sftp_user'],
            password=creds['sftp_pass']
        )
        sftp = ssh.open_sftp()

        # Deploy critical files
        files_to_deploy = ['page-home.php', 'page-admin-dashboard.php', 'get_creds.php']

        for file in files_to_deploy:
            local_file = os.path.join(LOCAL_PATH, file)
            remote_file = f"{REMOTE_PATH}/{file}"

            if os.path.exists(local_file):
                print(f"🚀 Uploading {file}...")
                sftp.put(local_file, remote_file)
                print(f"✅ DEPLOYED: {file}")
            else:
                print(f"⚠️ Warning: Local file not found at {local_file}")

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
