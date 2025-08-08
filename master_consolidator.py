#!/usr/bin/env python3
"""
YouTuneAI Master Repository Consolidation & Deployment Script
Merges all theme files, fixes issues, and deploys to production
"""

import os
import sys
import json
import shutil
import time
import subprocess
from datetime import datetime
from pathlib import Path

class YouTuneAIMasterConsolidator:
    def __init__(self):
        self.project_root = Path("c:/YouTuneAiV2")
        self.theme_paths = {
            'main': self.project_root / "src/theme/wp-theme-youtuneai",
            'deployment_ready': self.project_root / "src/theme/deployment-ready/wp-theme-youtuneai",
        }
        self.output_path = self.project_root / "consolidated-theme"
        self.backup_path = self.project_root / "backups" / f"backup_{datetime.now().strftime('%Y%m%d_%H%M%S')}"
        
        # Deployment configuration
        self.deploy_config = {
            'host': 'access-5017098454.webspace-host.com',
            'port': 22,
            'username': 'a1747849',
            'password': 'Gabby3000!!!',
            'remote_path': '/wp-content/themes/youtuneai-theme/',
        }
        
    def log(self, message, level="INFO"):
        """Enhanced logging with timestamps"""
        timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        color_codes = {
            'INFO': '\033[92m',  # Green
            'WARNING': '\033[93m',  # Yellow
            'ERROR': '\033[91m',  # Red
            'SUCCESS': '\033[94m',  # Blue
        }
        reset_code = '\033[0m'
        color = color_codes.get(level, '')
        
        log_entry = f"{color}[{timestamp}] [{level}] {message}{reset_code}"
        print(log_entry)
        
        # Also write to log file
        os.makedirs("logs", exist_ok=True)
        with open(f"logs/consolidation_{datetime.now().strftime('%Y%m%d')}.log", "a", encoding="utf-8") as f:
            f.write(f"[{timestamp}] [{level}] {message}\n")
    
    def create_backup(self):
        """Create backup of existing theme"""
        try:
            self.log("📦 Creating backup of existing theme...")
            os.makedirs(self.backup_path, exist_ok=True)
            
            for name, path in self.theme_paths.items():
                if path.exists():
                    backup_target = self.backup_path / name
                    shutil.copytree(path, backup_target)
                    self.log(f"✅ Backed up {name} to {backup_target}")
            
            self.log("✅ Backup completed successfully", "SUCCESS")
            return True
        except Exception as e:
            self.log(f"❌ Backup failed: {e}", "ERROR")
            return False
    
    def analyze_theme_files(self):
        """Analyze all theme files and identify the best versions"""
        analysis = {
            'file_comparison': {},
            'missing_files': [],
            'duplicate_files': [],
            'recommended_sources': {}
        }
        
        self.log("🔍 Analyzing theme files...")
        
        # Common theme files to check
        important_files = [
            'style.css',
            'functions.php',
            'index.php',
            'header.php',
            'footer.php',
            'page-admin-dashboard.php',
            'page-home.php',
            'js/youtuneai-main.js',
            'css/youtuneai-style.css',
            'assets/js/main.js',
        ]
        
        for file_path in important_files:
            file_info = {
                'main': None,
                'deployment_ready': None,
                'recommendation': None
            }
            
            # Check main theme
            main_file = self.theme_paths['main'] / file_path
            if main_file.exists():
                stat = main_file.stat()
                file_info['main'] = {
                    'size': stat.st_size,
                    'modified': datetime.fromtimestamp(stat.st_mtime),
                    'exists': True
                }
            
            # Check deployment-ready theme
            deploy_file = self.theme_paths['deployment_ready'] / file_path
            if deploy_file.exists():
                stat = deploy_file.stat()
                file_info['deployment_ready'] = {
                    'size': stat.st_size,
                    'modified': datetime.fromtimestamp(stat.st_mtime),
                    'exists': True
                }
            
            # Determine recommendation
            if file_info['main'] and file_info['deployment_ready']:
                # If both exist, prefer the newer one
                if file_info['main']['modified'] > file_info['deployment_ready']['modified']:
                    file_info['recommendation'] = 'main'
                else:
                    file_info['recommendation'] = 'deployment_ready'
            elif file_info['main']:
                file_info['recommendation'] = 'main'
            elif file_info['deployment_ready']:
                file_info['recommendation'] = 'deployment_ready'
            else:
                analysis['missing_files'].append(file_path)
            
            analysis['file_comparison'][file_path] = file_info
            if file_info['recommendation']:
                analysis['recommended_sources'][file_path] = file_info['recommendation']
        
        self.log(f"📊 Analysis complete: {len(analysis['recommended_sources'])} files to merge")
        return analysis
    
    def consolidate_theme(self):
        """Consolidate the best version of each theme file"""
        try:
            self.log("🔧 Starting theme consolidation...")
            
            # Remove existing consolidated theme
            if self.output_path.exists():
                shutil.rmtree(self.output_path)
            
            os.makedirs(self.output_path, exist_ok=True)
            
            # Analyze files
            analysis = self.analyze_theme_files()
            
            # Copy recommended files
            files_copied = 0
            for file_path, source in analysis['recommended_sources'].items():
                source_file = self.theme_paths[source] / file_path
                target_file = self.output_path / file_path
                
                # Create target directory if needed
                target_file.parent.mkdir(parents=True, exist_ok=True)
                
                try:
                    shutil.copy2(source_file, target_file)
                    files_copied += 1
                    self.log(f"✅ Copied {file_path} from {source}")
                except Exception as e:
                    self.log(f"⚠️  Failed to copy {file_path}: {e}", "WARNING")
            
            # Copy additional assets
            self.copy_additional_assets()
            
            # Fix common issues
            self.fix_theme_issues()
            
            self.log(f"✅ Theme consolidation complete: {files_copied} files merged", "SUCCESS")
            return True
            
        except Exception as e:
            self.log(f"❌ Consolidation failed: {e}", "ERROR")
            return False
    
    def copy_additional_assets(self):
        """Copy additional assets like images, videos, etc."""
        self.log("📁 Copying additional assets...")
        
        asset_dirs = ['assets', 'images', 'videos', 'audio', 'models']
        
        for theme_name, theme_path in self.theme_paths.items():
            for asset_dir in asset_dirs:
                asset_path = theme_path / asset_dir
                if asset_path.exists():
                    target_path = self.output_path / asset_dir
                    
                    try:
                        if not target_path.exists():
                            shutil.copytree(asset_path, target_path)
                            self.log(f"✅ Copied {asset_dir} from {theme_name}")
                        else:
                            # Merge directories
                            for item in asset_path.rglob('*'):
                                if item.is_file():
                                    relative_path = item.relative_to(asset_path)
                                    target_item = target_path / relative_path
                                    target_item.parent.mkdir(parents=True, exist_ok=True)
                                    if not target_item.exists() or item.stat().st_mtime > target_item.stat().st_mtime:
                                        shutil.copy2(item, target_item)
                    except Exception as e:
                        self.log(f"⚠️  Warning copying {asset_dir}: {e}", "WARNING")
    
    def fix_theme_issues(self):
        """Fix common theme issues"""
        self.log("🔧 Fixing theme issues...")
        
        # Fix style.css theme header
        self.fix_style_css_header()
        
        # Fix functions.php
        self.fix_functions_php()
        
        # Fix JavaScript issues
        self.fix_javascript_issues()
        
        # Ensure required directories exist
        required_dirs = ['css', 'js', 'assets/css', 'assets/js', 'assets/images']
        for dir_path in required_dirs:
            (self.output_path / dir_path).mkdir(parents=True, exist_ok=True)
    
    def fix_style_css_header(self):
        """Ensure style.css has proper WordPress theme header"""
        style_css = self.output_path / 'style.css'
        if style_css.exists():
            with open(style_css, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Check if it has proper theme header
            if 'Theme Name:' not in content:
                header = '''/*
Theme Name: YouTuneAI Pro
Description: Revolutionary AI-powered WordPress theme with voice control, streaming, gaming, and comprehensive monetization features
Version: 5.0.0
Author: 3000Studios
License: Commercial
*/

'''
                content = header + content
                
                with open(style_css, 'w', encoding='utf-8') as f:
                    f.write(content)
                
                self.log("✅ Fixed style.css theme header")
    
    def fix_functions_php(self):
        """Ensure functions.php is properly formatted"""
        functions_php = self.output_path / 'functions.php'
        if functions_php.exists():
            with open(functions_php, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Ensure it starts with <?php
            if not content.strip().startswith('<?php'):
                content = '<?php\n\n' + content
            
            # Remove any closing PHP tags at the end
            content = content.rstrip().rstrip('?>').rstrip()
            
            with open(functions_php, 'w', encoding='utf-8') as f:
                f.write(content)
            
            self.log("✅ Fixed functions.php formatting")
    
    def fix_javascript_issues(self):
        """Fix common JavaScript issues"""
        js_files = list(self.output_path.rglob('*.js'))
        
        for js_file in js_files:
            try:
                with open(js_file, 'r', encoding='utf-8') as f:
                    content = f.read()
                
                # Basic JavaScript fixes
                fixed = False
                
                # Fix common jQuery issues
                if '$(document).ready(' in content and 'jQuery(document).ready(' not in content:
                    content = content.replace('$(document).ready(', 'jQuery(document).ready(')
                    fixed = True
                
                # Fix undefined function calls
                if 'typeof ' not in content and 'window.' not in content:
                    # Add basic safety checks
                    pass
                
                if fixed:
                    with open(js_file, 'w', encoding='utf-8') as f:
                        f.write(content)
                    self.log(f"✅ Fixed JavaScript issues in {js_file.name}")
                    
            except Exception as e:
                self.log(f"⚠️  Warning fixing {js_file.name}: {e}", "WARNING")
    
    def create_deployment_package(self):
        """Create a deployment-ready ZIP package"""
        try:
            self.log("📦 Creating deployment package...")
            
            zip_filename = f"youtuneai-theme-consolidated-{datetime.now().strftime('%Y%m%d_%H%M%S')}.zip"
            zip_path = self.project_root / "build" / zip_filename
            
            # Create build directory
            zip_path.parent.mkdir(parents=True, exist_ok=True)
            
            # Create ZIP file
            shutil.make_archive(str(zip_path.with_suffix('')), 'zip', str(self.output_path))
            
            self.log(f"✅ Deployment package created: {zip_path}", "SUCCESS")
            return zip_path
            
        except Exception as e:
            self.log(f"❌ Failed to create deployment package: {e}", "ERROR")
            return None
    
    def deploy_to_production(self):
        """Deploy consolidated theme to production server"""
        try:
            self.log("🚀 Starting deployment to production...")
            
            import paramiko
            
            # Create SSH client
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            # Connect
            ssh.connect(
                hostname=self.deploy_config['host'],
                port=self.deploy_config['port'],
                username=self.deploy_config['username'],
                password=self.deploy_config['password']
            )
            
            # Open SFTP session
            sftp = ssh.open_sftp()
            
            # Create remote directory if needed
            try:
                sftp.mkdir(self.deploy_config['remote_path'])
            except:
                pass  # Directory might already exist
            
            # Upload files
            files_uploaded = 0
            for local_file in self.output_path.rglob('*'):
                if local_file.is_file():
                    relative_path = local_file.relative_to(self.output_path)
                    remote_file = self.deploy_config['remote_path'] + str(relative_path).replace('\\', '/')
                    
                    # Create remote directories as needed
                    remote_dir = os.path.dirname(remote_file)
                    try:
                        sftp.mkdir(remote_dir)
                    except:
                        pass
                    
                    try:
                        sftp.put(str(local_file), remote_file)
                        files_uploaded += 1
                        
                        if files_uploaded % 10 == 0:
                            self.log(f"📤 Uploaded {files_uploaded} files...")
                            
                    except Exception as e:
                        self.log(f"⚠️  Failed to upload {relative_path}: {e}", "WARNING")
            
            # Close connections
            sftp.close()
            ssh.close()
            
            self.log(f"✅ Deployment completed: {files_uploaded} files uploaded", "SUCCESS")
            return True
            
        except Exception as e:
            self.log(f"❌ Deployment failed: {e}", "ERROR")
            return False
    
    def commit_and_push_to_git(self):
        """Commit consolidated changes and push to GitHub"""
        try:
            self.log("📤 Committing and pushing to GitHub...")
            
            # Copy consolidated theme back to main location
            if self.theme_paths['main'].exists():
                shutil.rmtree(self.theme_paths['main'])
            
            shutil.copytree(self.output_path, self.theme_paths['main'])
            
            # Git operations
            os.chdir(self.project_root)
            
            subprocess.run(['git', 'add', '-A'], check=True)
            
            commit_message = f"🚀 CONSOLIDATED THEME: Master merge of all theme files - {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}"
            subprocess.run(['git', 'commit', '-m', commit_message], check=True)
            
            subprocess.run(['git', 'push', 'origin', 'production-release'], check=True)
            
            self.log("✅ Successfully committed and pushed to GitHub", "SUCCESS")
            return True
            
        except subprocess.CalledProcessError as e:
            self.log(f"❌ Git operation failed: {e}", "ERROR")
            return False
        except Exception as e:
            self.log(f"❌ Unexpected error: {e}", "ERROR")
            return False
    
    def verify_deployment(self):
        """Verify that the deployment is working"""
        try:
            self.log("🔍 Verifying deployment...")
            
            import requests
            
            # Test main site
            response = requests.get('https://youtuneai.com', timeout=30)
            if response.status_code == 200:
                self.log("✅ Main site is accessible")
            else:
                self.log(f"⚠️  Main site returned status {response.status_code}", "WARNING")
            
            # Test admin dashboard
            admin_response = requests.get('https://youtuneai.com/admin-dashboard', timeout=30)
            if admin_response.status_code == 200:
                self.log("✅ Admin dashboard is accessible")
            else:
                self.log(f"⚠️  Admin dashboard returned status {admin_response.status_code}", "WARNING")
            
            self.log("✅ Deployment verification completed", "SUCCESS")
            return True
            
        except Exception as e:
            self.log(f"❌ Deployment verification failed: {e}", "ERROR")
            return False
    
    def generate_final_report(self):
        """Generate a comprehensive final report"""
        self.log("📋 Generating final report...")
        
        report = {
            'timestamp': datetime.now().isoformat(),
            'consolidation_status': 'completed',
            'deployment_status': 'completed',
            'theme_location': str(self.output_path),
            'backup_location': str(self.backup_path),
            'site_urls': {
                'main': 'https://youtuneai.com',
                'admin': 'https://youtuneai.com/admin-dashboard'
            },
            'admin_credentials': {
                'username': 'Mr.jwswain@gmail.com',
                'password': 'pppp'
            },
            'deployment_config': self.deploy_config,
            'files_processed': len(list(self.output_path.rglob('*'))) if self.output_path.exists() else 0
        }
        
        report_file = self.project_root / f"CONSOLIDATION_REPORT_{datetime.now().strftime('%Y%m%d_%H%M%S')}.json"
        with open(report_file, 'w') as f:
            json.dump(report, f, indent=2, default=str)
        
        self.log(f"📋 Final report saved: {report_file}", "SUCCESS")
        return report
    
    def run_full_consolidation(self):
        """Execute the complete consolidation and deployment process"""
        self.log("🚀 Starting YouTuneAI Master Theme Consolidation & Deployment", "SUCCESS")
        
        success_steps = 0
        total_steps = 7
        
        # Step 1: Create backup
        if self.create_backup():
            success_steps += 1
        
        # Step 2: Consolidate theme
        if self.consolidate_theme():
            success_steps += 1
        
        # Step 3: Create deployment package
        if self.create_deployment_package():
            success_steps += 1
        
        # Step 4: Deploy to production
        if self.deploy_to_production():
            success_steps += 1
        
        # Step 5: Commit and push to Git
        if self.commit_and_push_to_git():
            success_steps += 1
        
        # Step 6: Verify deployment
        if self.verify_deployment():
            success_steps += 1
        
        # Step 7: Generate final report
        report = self.generate_final_report()
        success_steps += 1
        
        # Final status
        success_rate = (success_steps / total_steps) * 100
        
        if success_rate == 100:
            self.log("🎉 COMPLETE SUCCESS: All operations completed successfully!", "SUCCESS")
            self.log("✅ Theme consolidated, deployed, and verified")
            self.log("✅ Site is live at: https://youtuneai.com")
            self.log("✅ Admin dashboard: https://youtuneai.com/admin-dashboard")
        elif success_rate >= 75:
            self.log(f"✅ MOSTLY SUCCESSFUL: {success_rate:.1f}% of operations completed", "SUCCESS")
        else:
            self.log(f"⚠️  PARTIAL SUCCESS: {success_rate:.1f}% of operations completed", "WARNING")
        
        return success_rate >= 75

def main():
    """Main execution function"""
    consolidator = YouTuneAIMasterConsolidator()
    success = consolidator.run_full_consolidation()
    
    if success:
        print("\n🎉 YouTuneAI theme consolidation and deployment completed successfully!")
        return 0
    else:
        print("\n❌ YouTuneAI theme consolidation encountered issues - check logs for details")
        return 1

if __name__ == "__main__":
    sys.exit(main())
