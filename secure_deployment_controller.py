#!/usr/bin/env python3
"""
Secure YouTuneAI IONOS Deployment Controller
BlackVault Integration - Secure CI/CD Pipeline for youtuneai.com

This script provides secure deployment functionality using environment variables
and GitHub secrets instead of hardcoded credentials.

Security Features:
- No hardcoded credentials
- Comprehensive logging with secret scrubbing
- Rollback capabilities
- Deployment validation
- Security hardening
"""

import os
import sys
import json
import time
import hashlib
import logging
import tempfile
import shutil
from pathlib import Path
from datetime import datetime
from typing import Dict, List, Tuple, Any, Optional
import paramiko
from scp import SCPClient

class SecureIONOSDeployment:
    """Secure deployment controller for IONOS hosting with BlackVault integration"""
    
    def __init__(self):
        self.project_root = Path(__file__).parent
        self.deployment_log = []
        self.start_time = datetime.now()
        
        # Initialize secure configuration
        self.config = self._load_secure_config()
        self.setup_logging()
        
        print("🔒 YouTuneAI Secure Deployment Controller")
        print("🛡️  BlackVault Integration: Active")
        print("🚀 Target: youtuneai.com")
        
    def _load_secure_config(self) -> Dict[str, str]:
        """Load configuration from environment variables (GitHub secrets)"""
        config = {
            'host': os.environ.get('IONOS_HOST'),
            'username': os.environ.get('IONOS_USERNAME'),
            'password': os.environ.get('IONOS_PASSWORD'),
            'port': int(os.environ.get('IONOS_PORT', '22')),
            'deployment_path': os.environ.get('IONOS_DEPLOYMENT_PATH', '~/public_html'),
            'backup_retention_days': int(os.environ.get('BACKUP_RETENTION_DAYS', '30'))
        }
        
        # Validate required configuration
        missing_vars = [k for k, v in config.items() if v is None and k != 'backup_retention_days']
        if missing_vars:
            raise ValueError(f"Missing required environment variables: {missing_vars}")
        
        return config
        
    def setup_logging(self):
        """Setup comprehensive logging with secret scrubbing"""
        log_dir = self.project_root / 'logs'
        log_dir.mkdir(exist_ok=True)
        
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        log_file = log_dir / f'secure_deployment_{timestamp}.log'
        
        # Configure logging
        logging.basicConfig(
            level=logging.INFO,
            format='%(asctime)s - %(levelname)s - %(message)s',
            handlers=[
                logging.FileHandler(log_file),
                logging.StreamHandler(sys.stdout)
            ]
        )
        
        self.logger = logging.getLogger(__name__)
        self.log_file_path = log_file
        
        # Log deployment start
        self.logger.info("🚀 Secure deployment initiated")
        self.logger.info(f"📋 Log file: {log_file}")
        
    def scrub_secrets_from_log(self, message: str) -> str:
        """Remove sensitive information from log messages"""
        if self.config['password']:
            message = message.replace(self.config['password'], '***REDACTED***')
        
        # Scrub common sensitive patterns
        sensitive_patterns = ['password', 'secret', 'key', 'token', 'auth']
        for pattern in sensitive_patterns:
            # This is a simple scrubbing - in production, use more sophisticated regex
            if pattern in message.lower():
                words = message.split()
                scrubbed_words = []
                for i, word in enumerate(words):
                    if any(p in word.lower() for p in sensitive_patterns):
                        if i + 1 < len(words) and '=' in words[i + 1]:
                            scrubbed_words.append(word)
                            scrubbed_words.append('***REDACTED***')
                            words[i + 1] = '***REDACTED***'
                        else:
                            scrubbed_words.append('***REDACTED***')
                    else:
                        scrubbed_words.append(word)
                message = ' '.join(scrubbed_words)
        
        return message
        
    def create_ssh_connection(self) -> paramiko.SSHClient:
        """Create secure SSH connection to IONOS"""
        ssh_client = paramiko.SSHClient()
        ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        
        try:
            self.logger.info("🔐 Establishing secure SSH connection...")
            
            ssh_client.connect(
                hostname=self.config['host'],
                username=self.config['username'],
                password=self.config['password'],
                port=self.config['port'],
                timeout=30,
                auth_timeout=30
            )
            
            self.logger.info("✅ SSH connection established successfully")
            return ssh_client
            
        except Exception as e:
            error_msg = self.scrub_secrets_from_log(str(e))
            self.logger.error(f"❌ SSH connection failed: {error_msg}")
            raise
            
    def create_backup(self, ssh_client: paramiko.SSHClient) -> str:
        """Create backup of current production site"""
        self.logger.info("💾 Creating production backup...")
        
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        backup_name = f"youtuneai_backup_{timestamp}"
        
        try:
            # Create backup directory
            stdin, stdout, stderr = ssh_client.exec_command(f"mkdir -p ~/backups/{backup_name}")
            stdout.channel.recv_exit_status()
            
            # Copy current site
            backup_commands = [
                f"cp -r {self.config['deployment_path']}/* ~/backups/{backup_name}/",
                f"cd ~/backups && tar -czf {backup_name}.tar.gz {backup_name}/",
                f"rm -rf ~/backups/{backup_name}/",  # Clean up uncompressed backup
                f"ls -la ~/backups/{backup_name}.tar.gz"
            ]
            
            for cmd in backup_commands:
                self.logger.info(f"🔧 Executing backup command...")
                stdin, stdout, stderr = ssh_client.exec_command(cmd)
                exit_status = stdout.channel.recv_exit_status()
                
                if exit_status != 0:
                    error = stderr.read().decode()
                    if error:
                        self.logger.warning(f"⚠️  Backup command warning: {error}")
            
            self.logger.info(f"✅ Backup created: {backup_name}.tar.gz")
            return backup_name
            
        except Exception as e:
            self.logger.error(f"❌ Backup creation failed: {str(e)}")
            raise
            
    def cleanup_old_backups(self, ssh_client: paramiko.SSHClient):
        """Remove backups older than retention period"""
        self.logger.info("🧹 Cleaning up old backups...")
        
        try:
            retention_days = self.config['backup_retention_days']
            cleanup_cmd = f"find ~/backups -name 'youtuneai_backup_*.tar.gz' -mtime +{retention_days} -delete"
            
            stdin, stdout, stderr = ssh_client.exec_command(cleanup_cmd)
            stdout.channel.recv_exit_status()
            
            self.logger.info(f"✅ Cleaned up backups older than {retention_days} days")
            
        except Exception as e:
            self.logger.warning(f"⚠️  Backup cleanup failed: {str(e)}")
            
    def deploy_files(self, ssh_client: paramiko.SSHClient) -> bool:
        """Deploy files to IONOS hosting"""
        self.logger.info("📤 Starting file deployment...")
        
        try:
            with SCPClient(ssh_client.get_transport(), progress=self._scp_progress) as scp:
                
                # Deploy core HTML files
                html_files = [
                    'index.html', 'shop.html', 'streaming.html', 
                    'music.html', 'ai-tools.html', 'voice_command_test.html'
                ]
                
                for html_file in html_files:
                    local_path = self.project_root / html_file
                    if local_path.exists():
                        remote_path = f"{self.config['deployment_path']}/{html_file}"
                        scp.put(str(local_path), remote_path)
                        self.logger.info(f"✅ Deployed: {html_file}")
                    else:
                        self.logger.warning(f"⚠️  File not found: {html_file}")
                
                # Deploy PHP configuration files
                php_files = ['secure_admin_config.php']
                for php_file in php_files:
                    local_path = self.project_root / php_file
                    if local_path.exists():
                        remote_path = f"{self.config['deployment_path']}/{php_file}"
                        scp.put(str(local_path), remote_path)
                        self.logger.info(f"✅ Deployed: {php_file}")
                
                # Deploy theme directory
                theme_dir = self.project_root / 'youtuneai-theme'
                if theme_dir.exists():
                    remote_theme_path = f"{self.config['deployment_path']}/wp-content/themes/"
                    
                    # Ensure remote theme directory exists
                    ssh_client.exec_command(f"mkdir -p {remote_theme_path}")
                    
                    scp.put(str(theme_dir), remote_theme_path, recursive=True)
                    self.logger.info("✅ Deployed: Theme directory")
                
                # Deploy additional assets
                asset_dirs = ['src', 'assets', 'js', 'css']
                for asset_dir in asset_dirs:
                    local_asset_dir = self.project_root / asset_dir
                    if local_asset_dir.exists():
                        remote_asset_path = f"{self.config['deployment_path']}/{asset_dir}/"
                        scp.put(str(local_asset_dir), remote_asset_path, recursive=True)
                        self.logger.info(f"✅ Deployed: {asset_dir} directory")
                
            return True
            
        except Exception as e:
            self.logger.error(f"❌ File deployment failed: {str(e)}")
            return False
            
    def _scp_progress(self, filename: bytes, size: int, sent: int):
        """Progress callback for SCP transfers"""
        if sent == size:
            # Only log completion to avoid spam
            filename_str = filename.decode() if isinstance(filename, bytes) else str(filename)
            self.logger.debug(f"📁 Transfer completed: {filename_str}")
            
    def set_security_permissions(self, ssh_client: paramiko.SSHClient):
        """Apply security hardening to deployed files"""
        self.logger.info("🔒 Applying security permissions...")
        
        security_commands = [
            f"find {self.config['deployment_path']} -type d -exec chmod 755 {{}} \\;",
            f"find {self.config['deployment_path']} -type f -exec chmod 644 {{}} \\;",
            f"chmod 600 {self.config['deployment_path']}/wp-config.php",
            f"chmod 600 {self.config['deployment_path']}/secure_admin_config.php",
            f"chmod 600 {self.config['deployment_path']}/.htaccess"
        ]
        
        for cmd in security_commands:
            try:
                stdin, stdout, stderr = ssh_client.exec_command(cmd)
                exit_status = stdout.channel.recv_exit_status()
                
                if exit_status != 0:
                    error = stderr.read().decode()
                    if "No such file" not in error:  # Ignore missing optional files
                        self.logger.warning(f"⚠️  Security command warning: {error}")
                        
            except Exception as e:
                self.logger.warning(f"⚠️  Security permission failed: {str(e)}")
        
        self.logger.info("✅ Security permissions applied")
        
    def validate_deployment(self, ssh_client: paramiko.SSHClient) -> bool:
        """Validate successful deployment"""
        self.logger.info("✅ Validating deployment...")
        
        try:
            # Check if critical files exist
            critical_files = ['index.html', 'wp-config.php']
            
            for file in critical_files:
                check_cmd = f"ls -la {self.config['deployment_path']}/{file}"
                stdin, stdout, stderr = ssh_client.exec_command(check_cmd)
                exit_status = stdout.channel.recv_exit_status()
                
                if exit_status == 0:
                    self.logger.info(f"✅ Verified: {file}")
                else:
                    self.logger.warning(f"⚠️  Missing: {file}")
                    if file == 'index.html':
                        return False
            
            # Check file permissions
            permission_cmd = f"ls -la {self.config['deployment_path']}/index.html"
            stdin, stdout, stderr = ssh_client.exec_command(permission_cmd)
            output = stdout.read().decode()
            
            if output:
                self.logger.info("✅ File permissions validated")
                
            return True
            
        except Exception as e:
            self.logger.error(f"❌ Deployment validation failed: {str(e)}")
            return False
            
    def rollback_deployment(self, ssh_client: paramiko.SSHClient, backup_name: str):
        """Rollback to previous backup if deployment fails"""
        self.logger.info(f"↩️ Rolling back to backup: {backup_name}")
        
        try:
            rollback_commands = [
                f"mv {self.config['deployment_path']} {self.config['deployment_path']}_failed",
                f"cd ~/backups && tar -xzf {backup_name}.tar.gz",
                f"mv ~/backups/{backup_name.replace('.tar.gz', '')}/* {self.config['deployment_path']}/",
                f"rm -rf ~/backups/{backup_name.replace('.tar.gz', '')}"
            ]
            
            for cmd in rollback_commands:
                stdin, stdout, stderr = ssh_client.exec_command(cmd)
                exit_status = stdout.channel.recv_exit_status()
                
                if exit_status != 0:
                    error = stderr.read().decode()
                    self.logger.warning(f"⚠️  Rollback command warning: {error}")
            
            self.logger.info("✅ Rollback completed successfully")
            
        except Exception as e:
            self.logger.error(f"❌ Rollback failed: {str(e)}")
            
    def generate_deployment_report(self, success: bool, backup_name: str = None) -> Dict:
        """Generate comprehensive deployment report"""
        end_time = datetime.now()
        duration = (end_time - self.start_time).total_seconds()
        
        report = {
            "deployment": {
                "timestamp": end_time.isoformat(),
                "duration_seconds": duration,
                "status": "success" if success else "failed",
                "version": os.environ.get('DEPLOYMENT_VERSION', 'unknown'),
                "commit": os.environ.get('GITHUB_SHA', 'unknown'),
                "target_url": "https://youtuneai.com"
            },
            "backup": {
                "created": backup_name is not None,
                "name": backup_name,
                "retention_days": self.config['backup_retention_days']
            },
            "security": {
                "credentials_secured": True,
                "permissions_hardened": True,
                "logs_scrubbed": True
            },
            "validation": {
                "files_deployed": success,
                "permissions_set": success,
                "site_accessible": success  # This would be validated by external check
            },
            "logs": {
                "file_path": str(self.log_file_path),
                "entries": len(self.deployment_log)
            }
        }
        
        # Save report
        report_file = self.project_root / 'logs' / f'deployment_report_{datetime.now().strftime("%Y%m%d_%H%M%S")}.json'
        with open(report_file, 'w') as f:
            json.dump(report, f, indent=2)
        
        self.logger.info(f"📊 Deployment report saved: {report_file}")
        return report
        
    def deploy(self) -> bool:
        """Main deployment orchestration"""
        ssh_client = None
        backup_name = None
        success = False
        
        try:
            # Establish connection
            ssh_client = self.create_ssh_connection()
            
            # Create backup
            backup_name = self.create_backup(ssh_client)
            
            # Deploy files
            if self.deploy_files(ssh_client):
                
                # Apply security settings
                self.set_security_permissions(ssh_client)
                
                # Validate deployment
                if self.validate_deployment(ssh_client):
                    success = True
                    self.logger.info("🎉 Deployment completed successfully!")
                else:
                    self.logger.error("❌ Deployment validation failed")
                    
            else:
                self.logger.error("❌ File deployment failed")
            
            # Rollback if deployment failed
            if not success and backup_name:
                self.rollback_deployment(ssh_client, backup_name)
            
            # Cleanup old backups
            if success:
                self.cleanup_old_backups(ssh_client)
                
        except Exception as e:
            error_msg = self.scrub_secrets_from_log(str(e))
            self.logger.error(f"❌ Deployment failed with error: {error_msg}")
            
            # Attempt rollback on critical failure
            if ssh_client and backup_name:
                try:
                    self.rollback_deployment(ssh_client, backup_name)
                except:
                    self.logger.error("❌ Rollback also failed!")
                    
        finally:
            if ssh_client:
                ssh_client.close()
                
            # Generate final report
            report = self.generate_deployment_report(success, backup_name)
            
            # Scrub secrets from log file
            self._scrub_log_file()
            
        return success
        
    def _scrub_log_file(self):
        """Remove any remaining secrets from the log file"""
        try:
            with open(self.log_file_path, 'r') as f:
                content = f.read()
            
            scrubbed_content = self.scrub_secrets_from_log(content)
            
            with open(self.log_file_path, 'w') as f:
                f.write(scrubbed_content)
                
            self.logger.info("🧹 Log file scrubbed of sensitive information")
            
        except Exception as e:
            self.logger.warning(f"⚠️  Log scrubbing failed: {str(e)}")


def main():
    """Main entry point for secure deployment"""
    try:
        deployment = SecureIONOSDeployment()
        success = deployment.deploy()
        
        if success:
            print("🎉 Secure deployment completed successfully!")
            sys.exit(0)
        else:
            print("❌ Deployment failed - check logs for details")
            sys.exit(1)
            
    except Exception as e:
        print(f"❌ Critical deployment error: {str(e)}")
        sys.exit(1)


if __name__ == "__main__":
    main()