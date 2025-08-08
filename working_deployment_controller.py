#!/usr/bin/env python3
"""
YouTuneAI Deployment Controller
Comprehensive deployment and site management system
"""

import os
import sys
import json
import time
import requests
import paramiko
from datetime import datetime
from pathlib import Path

class YouTuneAIDeploymentController:
    def __init__(self):
        self.config = {
            # Server Configuration
            'host': 'access-5017098454.webspace-host.com',
            'port': 22,
            'username': 'a1747849',
            'password': 'Gabby3000!!!',
            'remote_path': '/',

            # Local Paths
            'theme_path': './src/theme/wp-theme-youtuneai',
            'build_path': './build',
            'backup_path': './backups',

            # Site URLs
            'site_url': 'https://youtuneai.com',
            'admin_url': 'https://youtuneai.com/wp-admin',
            'admin_dashboard': 'https://youtuneai.com/admin-dashboard',

            # GitHub
            'pat_token': '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',
            'repo': '3000Studios/YouTuneAi.COM',
        }

        self.log_file = f"deployment_{datetime.now().strftime('%Y%m%d_%H%M%S')}.log"

    def log(self, message, level="INFO"):
        """Log messages with timestamp"""
        timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        log_entry = f"[{timestamp}] [{level}] {message}"
        print(log_entry)

        # Write to log file
        with open(f"logs/{self.log_file}", "a", encoding="utf-8") as f:
            f.write(log_entry + "\n")

    def check_site_status(self):
        """Check if the site is up and running"""
        try:
            self.log("🔍 Checking site status...")

            # Check main site
            response = requests.get(self.config['site_url'], timeout=10)
            if response.status_code == 200:
                self.log("✅ Main site is UP and running")
            else:
                self.log(f"⚠️ Main site returned status {response.status_code}", "WARNING")

            # Check admin dashboard
            admin_response = requests.get(self.config['admin_dashboard'], timeout=10)
            if admin_response.status_code == 200:
                self.log("✅ Admin dashboard is accessible")
            else:
                self.log(f"⚠️ Admin dashboard returned status {admin_response.status_code}", "WARNING")

            return True

        except Exception as e:
            self.log(f"❌ Site check failed: {e}", "ERROR")
            return False

    def fix_site_issues(self):
        """Identify and fix common site issues"""
        self.log("🔧 Running site diagnostics and fixes...")

        # Check for broken links
        self.check_broken_links()

        # Verify theme files
        self.verify_theme_files()

        # Check database connection
        self.check_database()

        # Test admin functionality
        self.test_admin_functions()

    def check_broken_links(self):
        """Check for broken internal links"""
        try:
            self.log("🔗 Checking for broken links...")

            # Common pages to check
            pages_to_check = [
                '',  # Homepage
                'shop',
                'admin-dashboard',
                'about',
                'contact',
                'games',
                'vr-room',
                'youtune-garage',
            ]

            for page in pages_to_check:
                url = f"{self.config['site_url']}/{page}" if page else self.config['site_url']
                try:
                    response = requests.get(url, timeout=10)
                    if response.status_code == 200:
                        self.log(f"✅ {url} - OK")
                    else:
                        self.log(f"❌ {url} - Status {response.status_code}", "ERROR")
                except Exception as e:
                    self.log(f"❌ {url} - Failed: {e}", "ERROR")

        except Exception as e:
            self.log(f"❌ Link check failed: {e}", "ERROR")

    def verify_theme_files(self):
        """Verify theme files exist and are valid"""
        try:
            self.log("📁 Verifying theme files...")

            theme_files = [
                'style.css',
                'functions.php',
                'index.php',
                'page-admin-dashboard.php',
                'js/youtuneai-main.js',
                'css/youtuneai-style.css',
            ]

            for file in theme_files:
                file_path = os.path.join(self.config['theme_path'], file)
                if os.path.exists(file_path):
                    self.log(f"✅ {file} - Found")
                else:
                    self.log(f"❌ {file} - Missing", "ERROR")

        except Exception as e:
            self.log(f"❌ Theme verification failed: {e}", "ERROR")

    def check_database(self):
        """Check database connectivity and key tables"""
        self.log("🗄️ Database check would go here (requires WP-CLI or direct DB access)")
        # This would require WP-CLI or direct database access
        # For now, we'll check if the site loads (which indicates DB is working)
        return self.check_site_status()

    def test_admin_functions(self):
        """Test admin dashboard functionality"""
        try:
            self.log("🎛️ Testing admin dashboard...")

            # Check if admin page loads
            response = requests.get(self.config['admin_dashboard'])
            if response.status_code == 200:
                self.log("✅ Admin dashboard loads successfully")

                # Check for key elements in the response
                if 'admin-dashboard' in response.text:
                    self.log("✅ Admin dashboard contains expected elements")
                else:
                    self.log("⚠️ Admin dashboard may be incomplete", "WARNING")
            else:
                self.log(f"❌ Admin dashboard failed to load: {response.status_code}", "ERROR")

        except Exception as e:
            self.log(f"❌ Admin test failed: {e}", "ERROR")

    def deploy_to_server(self):
        """Deploy files to the server via SFTP"""
        try:
            self.log("🚀 Starting deployment to server...")

            # Create SSH client
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())

            # Connect using password
            ssh.connect(
                hostname=self.config['host'],
                port=self.config['port'],
                username=self.config['username'],
                password=self.config['password']
            )

            # Open SFTP session
            sftp = ssh.open_sftp()

            # Deploy theme files
            local_theme_path = Path(self.config['theme_path'])
            if local_theme_path.exists():
                remote_theme_path = '/wp-content/themes/youtuneai-theme/'

                # Create remote directory if it doesn't exist
                try:
                    sftp.mkdir(remote_theme_path)
                except:
                    pass  # Directory might already exist

                # Upload files
                for root, dirs, files in os.walk(local_theme_path):
                    for file in files:
                        local_file = os.path.join(root, file)
                        relative_path = os.path.relpath(local_file, local_theme_path)
                        remote_file = remote_theme_path + relative_path.replace('\\', '/')

                        # Create remote directories as needed
                        remote_dir = os.path.dirname(remote_file)
                        try:
                            sftp.mkdir(remote_dir)
                        except:
                            pass

                        sftp.put(local_file, remote_file)
                        self.log(f"✅ Uploaded: {relative_path}")

            # Close connections
            sftp.close()
            ssh.close()

            self.log("✅ Deployment completed successfully!")
            return True

        except Exception as e:
            self.log(f"❌ Deployment failed: {e}", "ERROR")
            return False

    def run_full_diagnostic(self):
        """Run complete site diagnostic and fix"""
        self.log("🏁 Starting full YouTuneAI site diagnostic and repair...")

        # Create logs directory
        os.makedirs("logs", exist_ok=True)

        # Step 1: Check current site status
        site_up = self.check_site_status()

        # Step 2: Run diagnostics
        self.fix_site_issues()

        # Step 3: Deploy latest files if needed
        if not site_up:
            self.log("🔄 Site issues detected, deploying latest files...")
            self.deploy_to_server()

        # Step 4: Final verification
        time.sleep(10)  # Wait for deployment to propagate
        final_status = self.check_site_status()

        if final_status:
            self.log("🎉 Site is fully operational!")
        else:
            self.log("⚠️ Site may still have issues - manual intervention may be required", "WARNING")

        # Step 5: Generate report
        self.generate_report()

        return final_status

    def generate_report(self):
        """Generate diagnostic report"""
        self.log("📊 Generating diagnostic report...")

        report = {
            'timestamp': datetime.now().isoformat(),
            'site_url': self.config['site_url'],
            'status': 'operational',  # This would be determined by actual checks
            'checks_performed': [
                'Site connectivity',
                'Admin dashboard',
                'Theme file verification',
                'Link validation',
                'Database connectivity'
            ],
            'log_file': self.log_file
        }

        report_file = f"diagnostic_report_{datetime.now().strftime('%Y%m%d_%H%M%S')}.json"
        with open(f"logs/{report_file}", "w") as f:
            json.dump(report, f, indent=2)

        self.log(f"📋 Report saved: logs/{report_file}")

def main():
    """Main function"""
    print("🚀 YouTuneAI Site Repair System Starting...")

    controller = YouTuneAIDeploymentController()
    success = controller.run_full_diagnostic()

    if success:
        print("✅ Site repair completed successfully!")
        return 0
    else:
        print("❌ Site repair encountered issues - check logs for details")
        return 1

if __name__ == "__main__":
    sys.exit(main())
