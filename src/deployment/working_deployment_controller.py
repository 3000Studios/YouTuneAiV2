#!/usr/bin/env python3
"""
YouTubeAI Working Deployment Controller
Actually functional deployment system that works with your infrastructure

Copyright (c) 2025 Mr. Swain (3000Studios)
"""

import os
import sys
import shutil
import zipfile
import paramiko
import json
import time
import hashlib
from datetime import datetime
from pathlib import Path
import requests
import socket
import ssl

class YouTuneAIDeploymentController:
    def __init__(self):
        """Initialize the deployment controller"""
        self.workspace_path = Path(__file__).parent.parent.parent  # Updated for new structure
        self.theme_source = self.workspace_path / "src" / "theme" / "deployment-ready" / "wp-theme-youtuneai"
        self.output_dir = self.workspace_path / "build"
        self.logs_dir = self.workspace_path / "logs"
        
        # Create necessary directories
        self.output_dir.mkdir(exist_ok=True)
        self.logs_dir.mkdir(exist_ok=True)
        
        # Server configuration from your existing files
        self.sftp_config = {
            'host': 'access-5017098454.webspace-host.com',
            'port': 22,
            'username': 'a917580',  # Updated with correct credentials
            'password': 'Gabby3000!!!',
            'remote_path': 'clickandbuilds/YouTuneAi/wp-content/themes/wp-theme-youtuneai'
        }
        
        self.domain = 'youtuneai.com'
        self.log_file = self.logs_dir / f"deployment_{datetime.now().strftime('%Y%m%d_%H%M%S')}.log"
        
        print("YouTubeAI Deployment Controller Initialized")
        self.log("Controller initialized successfully")

    def log(self, message: str, level: str = "INFO"):
        """Log messages to file and console"""
        timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        log_entry = f"[{timestamp}] [{level}] {message}"
        
        print(log_entry)
        
        with open(self.log_file, 'a', encoding='utf-8') as f:
            f.write(log_entry + '\n')

    def create_theme_package(self) -> tuple[bool, str]:
        """Create a complete theme package with all files"""
        try:
            self.log("Starting theme package creation...")
            
            # Check if source theme exists
            if not self.theme_source.exists():
                self.log(f"Theme source not found: {self.theme_source}", "ERROR")
                return False, f"Theme source directory not found: {self.theme_source}"
            
            # Create timestamp for unique package name
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            package_name = f"youtuneai-theme-v5.0-{timestamp}.zip"
            package_path = self.output_dir / package_name
            
            self.log(f"Creating package: {package_name}")
            
            # Create zip file
            with zipfile.ZipFile(package_path, 'w', zipfile.ZIP_DEFLATED) as zipf:
                # Add all theme files
                for root, dirs, files in os.walk(self.theme_source):
                    for file in files:
                        file_path = Path(root) / file
                        arcname = file_path.relative_to(self.theme_source)
                        zipf.write(file_path, arcname)
                        self.log(f"Added to package: {arcname}")
            
            # Verify package was created
            if package_path.exists():
                size_mb = package_path.stat().st_size / (1024 * 1024)
                self.log(f"Package created successfully: {package_path} ({size_mb:.2f}MB)")
                return True, str(package_path)
            else:
                self.log("Package creation failed - file not found after creation", "ERROR")
                return False, "Package file not created"
                
        except Exception as e:
            error_msg = f"Package creation failed: {str(e)}"
            self.log(error_msg, "ERROR")
            return False, error_msg

    def deploy_via_sftp(self, local_files: list = None) -> tuple[bool, str]:
        """Deploy files to server via SFTP"""
        try:
            self.log("Starting SFTP deployment...")
            
            # Connect to SFTP server
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            self.log(f"Connecting to {self.sftp_config['host']}...")
            ssh.connect(
                self.sftp_config['host'],
                port=self.sftp_config['port'],
                username=self.sftp_config['username'],
                password=self.sftp_config['password'],
                timeout=30
            )
            
            sftp = ssh.open_sftp()
            
            # If no specific files provided, deploy theme source
            if not local_files:
                if not self.theme_source.exists():
                    return False, "Theme source directory not found"
                
                # Deploy all theme files
                self._deploy_directory_recursive(sftp, self.theme_source, self.sftp_config['remote_path'])
            else:
                # Deploy specific files
                for local_file in local_files:
                    if os.path.exists(local_file):
                        remote_file = f"{self.sftp_config['remote_path']}/{os.path.basename(local_file)}"
                        sftp.put(local_file, remote_file)
                        self.log(f"Deployed: {local_file} -> {remote_file}")
            
            sftp.close()
            ssh.close()
            
            self.log("SFTP deployment completed successfully")
            return True, "Deployment completed successfully"
            
        except Exception as e:
            error_msg = f"SFTP deployment failed: {str(e)}"
            self.log(error_msg, "ERROR")
            return False, error_msg

    def _deploy_directory_recursive(self, sftp, local_dir, remote_dir):
        """Recursively deploy directory contents"""
        try:
            # Ensure remote directory exists
            try:
                sftp.stat(remote_dir)
            except FileNotFoundError:
                sftp.mkdir(remote_dir)
                self.log(f"Created remote directory: {remote_dir}")
            
            # Deploy all files in directory
            for item in os.listdir(local_dir):
                local_path = os.path.join(local_dir, item)
                remote_path = f"{remote_dir}/{item}"
                
                if os.path.isfile(local_path):
                    sftp.put(local_path, remote_path)
                    self.log(f"Deployed file: {item}")
                elif os.path.isdir(local_path):
                    self._deploy_directory_recursive(sftp, local_path, remote_path)
                    
        except Exception as e:
            self.log(f"Directory deployment error: {str(e)}", "ERROR")

    def verify_dns_ssl(self) -> tuple[bool, dict]:
        """Verify DNS and SSL for domain"""
        results = {
            'dns_check': False,
            'ssl_check': False,
            'ip_address': None,
            'ssl_expiry': None,
            'errors': []
        }
        
        try:
            self.log(f"Verifying DNS for {self.domain}...")
            
            # DNS Check
            try:
                ip_address = socket.gethostbyname(self.domain)
                results['ip_address'] = ip_address
                results['dns_check'] = True
                self.log(f"DNS resolved: {self.domain} -> {ip_address}")
            except socket.gaierror as e:
                error_msg = f"DNS resolution failed: {str(e)}"
                results['errors'].append(error_msg)
                self.log(error_msg, "ERROR")
            
            # SSL Check
            try:
                self.log(f"Checking SSL certificate for {self.domain}...")
                context = ssl.create_default_context()
                with socket.create_connection((self.domain, 443), timeout=10) as sock:
                    with context.wrap_socket(sock, server_hostname=self.domain) as ssock:
                        cert = ssock.getpeercert()
                        if cert:
                            results['ssl_check'] = True
                            results['ssl_expiry'] = cert.get('notAfter')
                            self.log(f"SSL certificate valid until: {results['ssl_expiry']}")
                        else:
                            results['errors'].append("No SSL certificate found")
            except Exception as e:
                error_msg = f"SSL check failed: {str(e)}"
                results['errors'].append(error_msg)
                self.log(error_msg, "ERROR")
            
            # HTTP/HTTPS Check
            try:
                self.log(f"Testing HTTP/HTTPS connectivity...")
                response = requests.get(f"https://{self.domain}", timeout=10, verify=True)
                if response.status_code == 200:
                    self.log("HTTPS connectivity verified")
                else:
                    self.log(f"HTTPS returned status: {response.status_code}")
            except Exception as e:
                self.log(f"HTTPS check failed: {str(e)}", "WARNING")
            
            overall_success = results['dns_check'] and results['ssl_check']
            return overall_success, results
            
        except Exception as e:
            error_msg = f"DNS/SSL verification failed: {str(e)}"
            self.log(error_msg, "ERROR")
            results['errors'].append(error_msg)
            return False, results

    def full_deployment_pipeline(self) -> dict:
        """Execute complete deployment pipeline"""
        start_time = time.time()
        pipeline_results = {
            'start_time': datetime.now().isoformat(),
            'package_created': False,
            'package_path': None,
            'deployment_success': False,
            'dns_ssl_verified': False,
            'dns_ssl_results': {},
            'errors': [],
            'duration_seconds': 0,
            'status': 'FAILED'
        }
        
        try:
            self.log("Starting full deployment pipeline...")
            
            # Step 1: Create theme package
            package_success, package_result = self.create_theme_package()
            pipeline_results['package_created'] = package_success
            
            if package_success:
                pipeline_results['package_path'] = package_result
            else:
                pipeline_results['errors'].append(f"Package creation failed: {package_result}")
                return pipeline_results
            
            # Step 2: Deploy to server
            deploy_success, deploy_result = self.deploy_via_sftp()
            pipeline_results['deployment_success'] = deploy_success
            
            if not deploy_success:
                pipeline_results['errors'].append(f"Deployment failed: {deploy_result}")
            
            # Step 3: Verify DNS and SSL
            dns_ssl_success, dns_ssl_results = self.verify_dns_ssl()
            pipeline_results['dns_ssl_verified'] = dns_ssl_success
            pipeline_results['dns_ssl_results'] = dns_ssl_results
            
            if not dns_ssl_success:
                pipeline_results['errors'].extend(dns_ssl_results.get('errors', []))
            
            # Determine overall status
            if package_success and deploy_success and dns_ssl_success:
                pipeline_results['status'] = 'SUCCESS'
                self.log("Full deployment pipeline completed successfully!")
            elif package_success and deploy_success:
                pipeline_results['status'] = 'PARTIAL_SUCCESS'
                self.log("Deployment completed but DNS/SSL issues detected")
            else:
                pipeline_results['status'] = 'FAILED'
                self.log("Deployment pipeline failed")
            
        except Exception as e:
            error_msg = f"Pipeline execution failed: {str(e)}"
            pipeline_results['errors'].append(error_msg)
            self.log(error_msg, "ERROR")
        
        finally:
            pipeline_results['duration_seconds'] = time.time() - start_time
            pipeline_results['end_time'] = datetime.now().isoformat()
            
            # Save results to file
            results_file = self.logs_dir / f"pipeline_results_{datetime.now().strftime('%Y%m%d_%H%M%S')}.json"
            with open(results_file, 'w') as f:
                json.dump(pipeline_results, f, indent=2)
            
            self.log(f"Pipeline results saved to: {results_file}")
            
        return pipeline_results

    def get_deployment_status(self) -> dict:
        """Get current deployment status"""
        status = {
            'theme_source_exists': self.theme_source.exists(),
            'theme_file_count': 0,
            'last_deployment': None,
            'server_accessible': False,
            'domain_accessible': False
        }
        
        # Count theme files
        if status['theme_source_exists']:
            status['theme_file_count'] = sum(1 for _ in self.theme_source.rglob('*') if _.is_file())
        
        # Check server accessibility
        try:
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            ssh.connect(
                self.sftp_config['host'],
                port=self.sftp_config['port'],
                username=self.sftp_config['username'],
                password=self.sftp_config['password'],
                timeout=10
            )
            ssh.close()
            status['server_accessible'] = True
        except:
            status['server_accessible'] = False
        
        # Check domain accessibility
        try:
            response = requests.get(f"https://{self.domain}", timeout=10)
            status['domain_accessible'] = response.status_code == 200
        except:
            status['domain_accessible'] = False
        
        return status

def main():
    """Main deployment function"""
    print("=" * 60)
    print("YouTuneAI Working Deployment Controller")
    print("=" * 60)
    
    controller = YouTuneAIDeploymentController()
    
    # Show current status
    print("\nCurrent Status:")
    status = controller.get_deployment_status()
    for key, value in status.items():
        print(f"   {key}: {value}")
    
    # Run full deployment pipeline
    print("\nStarting deployment pipeline...")
    results = controller.full_deployment_pipeline()
    
    # Display results
    print("\n" + "=" * 60)
    print("DEPLOYMENT RESULTS")
    print("=" * 60)
    print(f"Status: {results['status']}")
    print(f"Duration: {results['duration_seconds']:.2f} seconds")
    print(f"Package Created: {results['package_created']}")
    print(f"Deployment Success: {results['deployment_success']}")
    print(f"DNS/SSL Verified: {results['dns_ssl_verified']}")
    
    if results['package_path']:
        print(f"Package Location: {results['package_path']}")
    
    if results['errors']:
        print("\nErrors:")
        for error in results['errors']:
            print(f"   • {error}")
    
    if results['status'] == 'SUCCESS':
        print(f"\nSUCCESS! Your theme is now live at https://{controller.domain}")
    elif results['status'] == 'PARTIAL_SUCCESS':
        print(f"\nPARTIAL SUCCESS! Theme deployed but check DNS/SSL issues")
    else:
        print("\nDEPLOYMENT FAILED! Check errors above")
    
    return results

if __name__ == "__main__":
    main()
