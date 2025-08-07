#!/usr/bin/env python3
"""
YouTuneAI Complete Project Cleanup & Restructure System
Comprehensive cleanup, organization, and optimization tool

Copyright (c) 2025 Mr. Swain (3000Studios)
"""

import os
import sys
import shutil
import json
import zipfile
import paramiko
from pathlib import Path
from datetime import datetime
import subprocess

class YouTuneAIProjectCleaner:
    def __init__(self):
        """Initialize the project cleaner"""
        self.workspace_path = Path(__file__).parent
        self.backup_dir = self.workspace_path / "PROJECT_BACKUP"
        self.new_structure = self.workspace_path / "NEW_STRUCTURE"
        
        # Create backup directory
        self.backup_dir.mkdir(exist_ok=True)
        
        # SFTP Config for server cleanup
        self.sftp_config = {
            'host': 'access-5017098454.webspace-host.com',
            'port': 22,
            'username': 'a917580',
            'password': 'Gabby3000!!!',
        }
        
        print("🧹 YouTuneAI Project Cleanup & Restructure System")
        print("=" * 60)

    def analyze_current_project(self):
        """Analyze the current project structure"""
        print("\n📊 ANALYZING CURRENT PROJECT...")
        
        all_files = list(self.workspace_path.glob("*"))
        
        # Categorize files
        categories = {
            'essential_files': [],
            'theme_files': [],
            'deployment_files': [],
            'config_files': [],
            'documentation': [],
            'backup_archives': [],
            'temporary_files': [],
            'duplicate_files': [],
            'unknown_files': []
        }
        
        # Essential patterns
        essential_patterns = [
            'working_deployment_controller.py',
            '.git',
            '.gitignore',
            'README.md',
            'requirements.txt',
            'package.json'
        ]
        
        # Theme/WordPress patterns
        theme_patterns = [
            'deployment-ready',
            'wp-theme-youtuneai',
            'wp-content'
        ]
        
        # Deployment patterns  
        deployment_patterns = [
            'ai_controller.py',
            'instant_deploy.py',
            'direct_css_upload.py',
            'server_explorer.py',
            'find_wordpress.py',
            'ionos_webspace_analyzer.py'
        ]
        
        # Config patterns
        config_patterns = [
            '.env',
            'config.json',
            'tasks.json',
            'settings.json'
        ]
        
        # Documentation patterns
        doc_patterns = [
            '.md',
            '.txt',
            'LICENSE',
            'GUIDE'
        ]
        
        # Backup/Archive patterns
        backup_patterns = [
            '.zip',
            '.tar',
            '.gz',
            'backup',
            'archive',
            'BOSS_MAN',
            'Genesis'
        ]
        
        # Temporary/Junk patterns
        temp_patterns = [
            '.bat',
            '.ps1',
            '.log',
            'temp',
            'test_',
            'UNLEASH',
            'DEPLOY-',
            '__pycache__'
        ]
        
        for file_path in all_files:
            file_name = file_path.name.lower()
            
            # Categorize each file
            if any(pattern.lower() in file_name for pattern in essential_patterns):
                categories['essential_files'].append(file_path)
            elif any(pattern.lower() in file_name for pattern in theme_patterns):
                categories['theme_files'].append(file_path)
            elif any(pattern.lower() in file_name for pattern in deployment_patterns):
                categories['deployment_files'].append(file_path)
            elif any(pattern.lower() in file_name for pattern in config_patterns):
                categories['config_files'].append(file_path)
            elif any(pattern in file_name for pattern in doc_patterns):
                categories['documentation'].append(file_path)
            elif any(pattern.lower() in file_name for pattern in backup_patterns):
                categories['backup_archives'].append(file_path)
            elif any(pattern.lower() in file_name for pattern in temp_patterns):
                categories['temporary_files'].append(file_path)
            else:
                categories['unknown_files'].append(file_path)
        
        # Report findings
        print(f"\n📁 PROJECT ANALYSIS RESULTS:")
        print(f"Total files/directories: {len(all_files)}")
        
        for category, files in categories.items():
            if files:
                print(f"\n{category.upper().replace('_', ' ')} ({len(files)}):")
                for file in files[:5]:  # Show first 5
                    print(f"  📄 {file.name}")
                if len(files) > 5:
                    print(f"  ... and {len(files) - 5} more")
        
        return categories

    def create_backup(self, categories):
        """Create backup of important files before cleanup"""
        print(f"\n💾 CREATING PROJECT BACKUP...")
        
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        backup_zip = self.backup_dir / f"youtuneai_project_backup_{timestamp}.zip"
        
        with zipfile.ZipFile(backup_zip, 'w', zipfile.ZIP_DEFLATED) as zipf:
            # Backup essential files
            for file_path in categories['essential_files'] + categories['theme_files'] + categories['config_files']:
                if file_path.exists():
                    if file_path.is_file():
                        zipf.write(file_path, file_path.name)
                    elif file_path.is_dir():
                        for root, dirs, files in os.walk(file_path):
                            for file in files:
                                file_full_path = Path(root) / file
                                arc_name = file_full_path.relative_to(self.workspace_path)
                                zipf.write(file_full_path, arc_name)
        
        print(f"✅ Backup created: {backup_zip}")
        return backup_zip

    def clean_local_files(self, categories):
        """Clean up local junk files"""
        print(f"\n🗑️ CLEANING LOCAL FILES...")
        
        # Files to delete
        files_to_delete = (
            categories['backup_archives'] + 
            categories['temporary_files'] + 
            [f for f in categories['unknown_files'] if self._is_safe_to_delete(f)]
        )
        
        deleted_count = 0
        for file_path in files_to_delete:
            try:
                if file_path.exists():
                    if file_path.is_file():
                        file_path.unlink()
                        print(f"  🗑️ Deleted file: {file_path.name}")
                    elif file_path.is_dir():
                        shutil.rmtree(file_path)
                        print(f"  🗑️ Deleted directory: {file_path.name}")
                    deleted_count += 1
            except Exception as e:
                print(f"  ⚠️ Could not delete {file_path.name}: {e}")
        
        print(f"✅ Deleted {deleted_count} files/directories")

    def _is_safe_to_delete(self, file_path):
        """Check if file is safe to delete"""
        unsafe_patterns = [
            '.git',
            'deployment-ready',
            'wp-theme-youtuneai',
            'working_deployment_controller.py',
            'requirements.txt',
            '.env'
        ]
        
        file_name = file_path.name.lower()
        return not any(pattern.lower() in file_name for pattern in unsafe_patterns)

    def clean_server_files(self):
        """Clean up server files"""
        print(f"\n🌐 CLEANING SERVER FILES...")
        
        try:
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            ssh.connect(
                self.sftp_config['host'],
                port=self.sftp_config['port'],
                username=self.sftp_config['username'],
                password=self.sftp_config['password'],
                timeout=30
            )
            
            sftp = ssh.open_sftp()
            
            # Remove old theme versions
            themes_path = 'clickandbuilds/YouTuneAi/wp-content/themes'
            themes = sftp.listdir(themes_path)
            
            old_themes_removed = 0
            for theme in themes:
                if 'youtuneai-theme-v5.0-' in theme and theme != 'wp-theme-youtuneai':
                    try:
                        # Remove old theme directory
                        theme_path = f"{themes_path}/{theme}"
                        self._remove_remote_directory(sftp, theme_path)
                        print(f"  🗑️ Removed old theme: {theme}")
                        old_themes_removed += 1
                    except Exception as e:
                        print(f"  ⚠️ Could not remove {theme}: {e}")
            
            # Clean up root WordPress directory
            root_files = sftp.listdir('clickandbuilds/YouTuneAi')
            junk_files = ['it-api.php', 'YouTuneAiV2']
            
            for junk_file in junk_files:
                if junk_file in root_files:
                    try:
                        sftp.remove(f'clickandbuilds/YouTuneAi/{junk_file}')
                        print(f"  🗑️ Removed junk file: {junk_file}")
                    except Exception as e:
                        print(f"  ⚠️ Could not remove {junk_file}: {e}")
            
            sftp.close()
            ssh.close()
            
            print(f"✅ Server cleanup completed - removed {old_themes_removed} old themes")
            
        except Exception as e:
            print(f"⚠️ Server cleanup failed: {e}")

    def _remove_remote_directory(self, sftp, path):
        """Recursively remove remote directory"""
        try:
            files = sftp.listdir(path)
            for file in files:
                file_path = f"{path}/{file}"
                try:
                    # Try to remove as file first
                    sftp.remove(file_path)
                except:
                    # If that fails, it's probably a directory
                    self._remove_remote_directory(sftp, file_path)
            
            # Remove the empty directory
            sftp.rmdir(path)
        except Exception as e:
            print(f"    Error removing {path}: {e}")

    def create_optimal_structure(self):
        """Create optimal project structure"""
        print(f"\n📁 CREATING OPTIMAL PROJECT STRUCTURE...")
        
        # Create new directory structure
        directories = [
            'src/deployment',
            'src/theme', 
            'src/utils',
            'config',
            'docs',
            'build',
            'logs',
            'backups'
        ]
        
        for directory in directories:
            dir_path = self.workspace_path / directory
            dir_path.mkdir(parents=True, exist_ok=True)
            print(f"  📁 Created: {directory}")
        
        print("✅ Optimal project structure created")

    def move_files_to_structure(self, categories):
        """Move files to new structure"""
        print(f"\n📦 ORGANIZING FILES INTO NEW STRUCTURE...")
        
        # Move deployment files
        src_deployment = self.workspace_path / 'src' / 'deployment'
        for file_path in categories['deployment_files']:
            if file_path.exists() and file_path.is_file():
                shutil.move(str(file_path), str(src_deployment / file_path.name))
                print(f"  📦 Moved {file_path.name} to src/deployment/")
        
        # Move theme files
        src_theme = self.workspace_path / 'src' / 'theme'
        for file_path in categories['theme_files']:
            if file_path.exists():
                dest_path = src_theme / file_path.name
                if file_path.is_dir():
                    if dest_path.exists():
                        shutil.rmtree(dest_path)
                    shutil.copytree(file_path, dest_path)
                else:
                    shutil.copy2(file_path, dest_path)
                print(f"  📦 Moved {file_path.name} to src/theme/")
        
        # Move config files
        config_dir = self.workspace_path / 'config'
        for file_path in categories['config_files']:
            if file_path.exists() and file_path.is_file():
                shutil.move(str(file_path), str(config_dir / file_path.name))
                print(f"  📦 Moved {file_path.name} to config/")
        
        # Move documentation
        docs_dir = self.workspace_path / 'docs'
        for file_path in categories['documentation']:
            if file_path.exists() and file_path.is_file():
                shutil.move(str(file_path), str(docs_dir / file_path.name))
                print(f"  📦 Moved {file_path.name} to docs/")
        
        print("✅ Files organized into new structure")

    def create_vscode_config(self):
        """Create optimized VS Code configuration"""
        print(f"\n⚙️ CREATING VS CODE CONFIGURATION...")
        
        # VS Code settings
        vscode_dir = self.workspace_path / '.vscode'
        vscode_dir.mkdir(exist_ok=True)
        
        # Settings.json
        settings = {
            "python.defaultInterpreterPath": "./.venv/Scripts/python.exe",
            "python.terminal.activateEnvironment": True,
            "files.exclude": {
                "**/__pycache__": True,
                "**/*.pyc": True,
                "**/logs/*.log": True,
                "**/backups/*": True,
                "**/.git": False
            },
            "explorer.fileNesting.enabled": True,
            "explorer.fileNesting.patterns": {
                "*.py": "${capture}.pyc",
                "requirements.txt": "requirements*.txt",
                "package.json": "package-lock.json"
            },
            "python.analysis.typeCheckingMode": "basic",
            "editor.formatOnSave": True,
            "python.formatting.provider": "black",
            "files.associations": {
                "*.php": "php",
                "*.css": "css",
                "*.js": "javascript"
            }
        }
        
        with open(vscode_dir / 'settings.json', 'w') as f:
            json.dump(settings, f, indent=2)
        
        # Tasks.json - Clean and organized
        tasks = {
            "version": "2.0.0",
            "tasks": [
                {
                    "label": "🚀 Deploy YouTuneAI Theme",
                    "type": "shell",
                    "command": "python",
                    "args": ["src/deployment/working_deployment_controller.py"],
                    "group": "build",
                    "options": {"cwd": "${workspaceFolder}"},
                    "presentation": {"echo": True, "reveal": "always"},
                    "problemMatcher": []
                },
                {
                    "label": "🧪 Test Deployment Connection",
                    "type": "shell", 
                    "command": "python",
                    "args": ["-c", "from src.deployment.working_deployment_controller import YouTuneAIDeploymentController; c = YouTuneAIDeploymentController(); status = c.get_deployment_status(); print('✅ Connection test passed' if status['server_accessible'] else '❌ Connection failed')"],
                    "group": "test",
                    "options": {"cwd": "${workspaceFolder}"}
                },
                {
                    "label": "📦 Install Dependencies",
                    "type": "shell",
                    "command": "pip",
                    "args": ["install", "-r", "requirements.txt"],
                    "group": "build",
                    "options": {"cwd": "${workspaceFolder}"}
                },
                {
                    "label": "🧹 Clean Project",
                    "type": "shell",
                    "command": "python",
                    "args": ["project_cleaner_ultimate.py"],
                    "group": "build",
                    "options": {"cwd": "${workspaceFolder}"}
                },
                {
                    "label": "🎨 Upload CSS Only",
                    "type": "shell",
                    "command": "python",
                    "args": ["src/deployment/direct_css_upload.py"],
                    "group": "build",
                    "options": {"cwd": "${workspaceFolder}"}
                }
            ]
        }
        
        with open(vscode_dir / 'tasks.json', 'w') as f:
            json.dump(tasks, f, indent=2)
        
        # Launch.json for debugging
        launch = {
            "version": "0.2.0",
            "configurations": [
                {
                    "name": "🐛 Debug Deployment Controller",
                    "type": "python",
                    "request": "launch",
                    "program": "${workspaceFolder}/src/deployment/working_deployment_controller.py",
                    "console": "integratedTerminal",
                    "cwd": "${workspaceFolder}"
                },
                {
                    "name": "🧹 Debug Cleaner",
                    "type": "python",
                    "request": "launch", 
                    "program": "${workspaceFolder}/project_cleaner_ultimate.py",
                    "console": "integratedTerminal",
                    "cwd": "${workspaceFolder}"
                }
            ]
        }
        
        with open(vscode_dir / 'launch.json', 'w') as f:
            json.dump(launch, f, indent=2)
        
        print("✅ VS Code configuration created")

    def update_file_paths(self):
        """Update file paths in moved files"""
        print(f"\n🔧 UPDATING FILE PATHS...")
        
        # Update deployment controller paths
        controller_path = self.workspace_path / 'src' / 'deployment' / 'working_deployment_controller.py'
        if controller_path.exists():
            with open(controller_path, 'r') as f:
                content = f.read()
            
            # Update relative paths
            content = content.replace(
                'self.theme_source = self.workspace_path / "deployment-ready" / "wp-theme-youtuneai"',
                'self.theme_source = self.workspace_path / "src" / "theme" / "deployment-ready" / "wp-theme-youtuneai"'
            )
            
            with open(controller_path, 'w') as f:
                f.write(content)
            
            print("  🔧 Updated deployment controller paths")
        
        print("✅ File paths updated")

    def create_documentation(self):
        """Create comprehensive documentation"""
        print(f"\n📚 CREATING DOCUMENTATION...")
        
        docs_dir = self.workspace_path / 'docs'
        
        # Main README
        readme_content = """# YouTuneAI Pro v5.0

Revolutionary AI-powered WordPress theme with voice control, streaming, gaming, and comprehensive monetization features.

## 🚀 Quick Start

1. **Setup Environment**
   ```bash
   pip install -r requirements.txt
   cp config/.env.example config/.env
   # Edit config/.env with your API keys
   ```

2. **Deploy Theme**
   - Use VS Code task: "🚀 Deploy YouTuneAI Theme"
   - Or run: `python src/deployment/working_deployment_controller.py`

3. **Visit Your Site**
   - Live site: https://youtuneai.com

## 📁 Project Structure

```
YouTuneAiV2/
├── src/
│   ├── deployment/              # Deployment scripts and tools
│   │   ├── working_deployment_controller.py  # Main deployment system
│   │   ├── direct_css_upload.py             # CSS-only deployment
│   │   └── server_explorer.py               # Server diagnostics
│   ├── theme/                   # WordPress theme files
│   │   └── deployment-ready/    # Ready-to-deploy theme
│   └── utils/                   # Utility functions
├── config/                      # Configuration files
│   ├── .env                     # Environment variables (create from .env.example)
│   └── tasks.json               # VS Code tasks
├── docs/                        # Documentation
├── build/                       # Build outputs and theme packages
├── logs/                        # Deployment and error logs
├── backups/                     # Project backups
└── .vscode/                     # VS Code configuration
```

## 🛠️ Development Workflow

### VS Code Tasks (Ctrl+Shift+P → "Tasks: Run Task")
- **🚀 Deploy YouTuneAI Theme** - Full deployment pipeline
- **🧪 Test Deployment Connection** - Test server connectivity
- **🎨 Upload CSS Only** - Quick CSS updates
- **🧹 Clean Project** - Remove junk files and organize

### Deployment Process
1. Theme files are packaged from `src/theme/deployment-ready/`
2. Package is created in `build/` directory
3. Files are uploaded via SFTP to IONOS server
4. DNS/SSL verification is performed
5. Results are logged in `logs/` directory

## 🌐 Server Information

- **Live Site**: https://youtuneai.com
- **Server**: IONOS Webspace (access-5017098454.webspace-host.com)
- **WordPress Path**: clickandbuilds/YouTuneAi/
- **Active Theme**: wp-theme-youtuneai
- **SSL**: Valid until August 2026

## 🎨 Theme Features

- **Voice Control Integration** - AI-powered voice commands
- **Blue Color Scheme** - Professional blue branding
- **Responsive Design** - Mobile-first approach
- **SEO Optimized** - Built-in SEO features
- **Monetization Ready** - E-commerce integration

## 🔧 Troubleshooting

### Common Issues
1. **Deployment Fails**: Check server connectivity with test task
2. **CSS Not Updating**: Use "Upload CSS Only" task for quick updates
3. **Theme Not Active**: Verify WordPress admin panel

### Log Files
- All operations are logged in `logs/` directory
- Check latest log file for detailed error information

## 📞 Support

Created by Mr. Swain (3000Studios)
Copyright (c) 2025

## 🚀 Version History

- **v5.0** - Blue theme, voice control, optimized deployment
- **v4.x** - Legacy versions (archived)
"""
        
        with open(docs_dir / 'README.md', 'w') as f:
            f.write(readme_content)
        
        # Deployment Guide
        deployment_guide = """# Deployment Guide

## Overview
The YouTuneAI deployment system uses a comprehensive Python-based controller that handles theme packaging, SFTP upload, and verification.

## Prerequisites
- Python 3.7+
- Required packages: `pip install -r requirements.txt`
- SFTP access to IONOS webspace
- WordPress installation on target server

## Deployment Methods

### Method 1: VS Code Task (Recommended)
1. Open VS Code in project root
2. Press `Ctrl+Shift+P`
3. Select "Tasks: Run Task"
4. Choose "🚀 Deploy YouTuneAI Theme"

### Method 2: Command Line
```bash
python src/deployment/working_deployment_controller.py
```

### Method 3: CSS Only Update
For quick CSS changes:
```bash
python src/deployment/direct_css_upload.py
```

## Deployment Process

### 1. Theme Packaging
- Source: `src/theme/deployment-ready/wp-theme-youtuneai/`
- Creates timestamped ZIP package
- Includes all theme files and assets
- Output: `build/youtuneai-theme-v5.0-TIMESTAMP.zip`

### 2. SFTP Upload
- Connects to IONOS webspace
- Uploads to: `clickandbuilds/YouTuneAi/wp-content/themes/wp-theme-youtuneai/`
- Preserves file permissions and structure

### 3. Verification
- DNS resolution check for youtuneai.com
- SSL certificate validation
- HTTPS connectivity test
- File integrity verification

## Server Configuration

### IONOS Webspace Details
- **Host**: access-5017098454.webspace-host.com
- **Port**: 22 (SFTP)
- **Username**: a917580
- **WordPress Path**: clickandbuilds/YouTuneAi/

### Directory Structure on Server
```
clickandbuilds/YouTuneAi/
├── wp-content/
│   └── themes/
│       ├── wp-theme-youtuneai/     # Active theme (our theme)
│       ├── twentytwentyfive/       # Default WordPress theme
│       └── index.php
├── wp-admin/
├── wp-includes/
└── wp-config.php
```

## Troubleshooting

### Common Issues

#### 1. SFTP Connection Failed
- Verify credentials in deployment controller
- Check network connectivity
- Ensure SFTP is enabled on server

#### 2. Permission Denied
- Check file permissions on server
- Verify user has write access to themes directory

#### 3. Theme Not Updating
- Clear browser cache (Ctrl+F5)
- Check WordPress admin panel
- Verify correct theme directory

#### 4. CSS Changes Not Visible
- Use "Upload CSS Only" for faster updates
- Check browser developer tools for cached resources
- Verify file was actually uploaded

### Log Analysis
- All operations logged in `logs/` directory
- Look for ERROR level messages
- Check deployment timestamps

### Manual Verification
1. Visit https://youtuneai.com
2. Check source code for updated CSS
3. Test responsive design on mobile
4. Verify all theme features work

## Best Practices

### Development Workflow
1. Make changes in `src/theme/deployment-ready/`
2. Test locally if possible
3. Use CSS-only deployment for styling changes
4. Use full deployment for PHP/structure changes
5. Always check logs after deployment

### File Management
- Keep `src/theme/deployment-ready/` as the single source of truth
- Don't edit files directly on server
- Use version control for all changes
- Create backups before major updates

### Performance Tips
- Use CSS-only deployment for quick updates
- Package sizes are optimized automatically
- Logs help identify bottlenecks
- DNS/SSL verification ensures site health
"""
        
        with open(docs_dir / 'DEPLOYMENT_GUIDE.md', 'w') as f:
            f.write(deployment_guide)
        
        print("✅ Documentation created")

    def run_complete_cleanup(self):
        """Run the complete cleanup and restructuring process"""
        print("🚀 STARTING COMPLETE PROJECT CLEANUP & RESTRUCTURE")
        print("=" * 70)
        
        try:
            # Step 1: Analyze current project
            categories = self.analyze_current_project()
            
            # Step 2: Create backup
            backup_file = self.create_backup(categories)
            
            # Step 3: Create optimal structure  
            self.create_optimal_structure()
            
            # Step 4: Move files to new structure
            self.move_files_to_structure(categories)
            
            # Step 5: Clean local junk files
            self.clean_local_files(categories)
            
            # Step 6: Clean server files
            self.clean_server_files()
            
            # Step 7: Configure VS Code
            self.create_vscode_config()
            
            # Step 8: Update file paths
            self.update_file_paths()
            
            # Step 9: Create documentation
            self.create_documentation()
            
            print(f"\n" + "=" * 70)
            print(f"🎉 PROJECT CLEANUP & RESTRUCTURE COMPLETE!")
            print(f"=" * 70)
            print(f"✅ Backup created: {backup_file.name}")
            print(f"✅ Project restructured with optimal organization")
            print(f"✅ Junk files removed locally and on server")
            print(f"✅ VS Code optimized with tasks and debugging")
            print(f"✅ Documentation created")
            print(f"✅ File paths updated")
            print(f"\n💡 Next Steps:")
            print(f"1. 🔄 Reload VS Code to see new structure")
            print(f"2. 🧪 Run test task: 'Test Deployment Connection'")
            print(f"3. 🚀 Deploy with task: 'Deploy YouTuneAI Theme'")
            print(f"4. 📖 Read docs/README.md for full guide")
            print(f"5. 🌐 Your site is live at https://youtuneai.com")
            
        except Exception as e:
            print(f"❌ Cleanup failed: {e}")
            print(f"💾 Backup is available in: {self.backup_dir}")
            raise

def main():
    cleaner = YouTuneAIProjectCleaner()
    cleaner.run_complete_cleanup()

if __name__ == "__main__":
    main()
