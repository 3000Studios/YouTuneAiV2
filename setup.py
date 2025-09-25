#!/usr/bin/env python3
"""
YouTuneAI Complete Setup Automation
Handles all dependencies, build processes, and environment setup
"""

import os
import sys
import subprocess
import json
from pathlib import Path
from datetime import datetime

class YouTuneAISetup:
    def __init__(self):
        self.root_path = Path(__file__).parent
        self.theme_path = self.root_path / 'youtuneai-theme'
        self.logs_path = self.root_path / 'logs'
        self.config_path = self.root_path / 'config'
        
        # Ensure directories exist
        self.logs_path.mkdir(exist_ok=True)
        self.config_path.mkdir(exist_ok=True)
        
        self.setup_log = self.logs_path / 'setup.log'
        self.success_count = 0
        self.total_steps = 8

    def log(self, message, level="INFO"):
        """Log message to both console and file"""
        timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        log_entry = f"[{timestamp}] {level}: {message}"
        print(log_entry)
        
        with open(self.setup_log, 'a', encoding='utf-8') as f:
            f.write(log_entry + '\n')

    def run_command(self, command, cwd=None, timeout=300):
        """Run command with proper error handling and logging"""
        try:
            self.log(f"Running command: {command}")
            if cwd:
                self.log(f"Working directory: {cwd}")
            
            result = subprocess.run(
                command,
                shell=True,
                cwd=cwd,
                capture_output=True,
                text=True,
                timeout=timeout
            )
            
            if result.returncode == 0:
                self.log("Command completed successfully")
                if result.stdout:
                    self.log(f"Output: {result.stdout[:500]}{'...' if len(result.stdout) > 500 else ''}")
                return True, result.stdout
            else:
                self.log(f"Command failed with code {result.returncode}", "ERROR")
                if result.stderr:
                    self.log(f"Error: {result.stderr[:500]}{'...' if len(result.stderr) > 500 else ''}", "ERROR")
                return False, result.stderr
                
        except subprocess.TimeoutExpired:
            self.log(f"Command timed out after {timeout} seconds", "ERROR")
            return False, "Timeout"
        except Exception as e:
            self.log(f"Command execution failed: {str(e)}", "ERROR")
            return False, str(e)

    def check_system_requirements(self):
        """Check if required system tools are available"""
        self.log("=== Checking System Requirements ===")
        
        requirements = {
            'python3': 'python3 --version',
            'pip': 'pip --version',
            'node': 'node --version',
            'npm': 'npm --version'
        }
        
        missing = []
        for tool, command in requirements.items():
            success, output = self.run_command(command)
            if success:
                self.log(f"✅ {tool} found: {output.strip()}")
            else:
                self.log(f"❌ {tool} not found", "ERROR")
                missing.append(tool)
        
        if missing:
            self.log(f"Missing required tools: {', '.join(missing)}", "ERROR")
            return False
            
        self.success_count += 1
        return True

    def install_python_dependencies(self):
        """Install Python dependencies with error handling"""
        self.log("=== Installing Python Dependencies ===")
        
        # Create a minimal requirements file for essential packages only
        essential_requirements = [
            "requests>=2.25.0",
            "python-dotenv>=0.19.0",
            "coloredlogs>=15.0.0",
            "click>=8.0.0",
            "watchdog>=2.1.0"
        ]
        
        essential_req_file = self.root_path / 'requirements_essential.txt'
        with open(essential_req_file, 'w') as f:
            f.write('\n'.join(essential_requirements))
        
        self.log("Installing essential Python packages...")
        success, output = self.run_command(f"pip install -r {essential_req_file}", timeout=180)
        
        if success:
            self.log("✅ Essential Python dependencies installed")
            self.success_count += 1
            
            # Try to install additional packages individually
            additional_packages = [
                "openai==0.28.1",
                "paramiko>=3.0.0", 
                "Pillow>=9.0.0"
            ]
            
            for package in additional_packages:
                self.log(f"Attempting to install {package}...")
                success, _ = self.run_command(f"pip install {package}", timeout=120)
                if success:
                    self.log(f"✅ {package} installed")
                else:
                    self.log(f"⚠️ {package} failed to install (optional)", "WARNING")
            
            return True
        else:
            self.log("❌ Failed to install essential Python dependencies", "ERROR")
            return False

    def setup_node_environment(self):
        """Setup Node.js environment for theme building"""
        self.log("=== Setting up Node.js Environment ===")
        
        if not self.theme_path.exists():
            self.log("❌ Theme directory not found", "ERROR")
            return False
        
        # Install theme dependencies
        self.log("Installing theme dependencies...")
        success, output = self.run_command("npm install", cwd=self.theme_path, timeout=180)
        
        if success:
            self.log("✅ Theme dependencies installed")
            self.success_count += 1
            return True
        else:
            self.log("❌ Failed to install theme dependencies", "ERROR")
            return False

    def build_theme_assets(self):
        """Build theme CSS and JS assets"""
        self.log("=== Building Theme Assets ===")
        
        # Create source CSS directory if it doesn't exist
        css_src_path = self.theme_path / 'assets' / 'css' / 'src'
        css_src_path.mkdir(parents=True, exist_ok=True)
        
        # Create main.css if it doesn't exist
        main_css_path = css_src_path / 'main.css'
        if not main_css_path.exists():
            main_css_content = """@tailwind base;
@tailwind components;
@tailwind utilities;

/* YouTuneAI Custom Styles */
.youtuneai-glow {
    box-shadow: 0 0 20px rgba(157, 0, 255, 0.6);
}

.youtuneai-text-glow {
    text-shadow: 0 0 10px currentColor;
}
"""
            with open(main_css_path, 'w') as f:
                f.write(main_css_content)
            self.log("Created main.css file")
        
        # Build CSS
        self.log("Building TailwindCSS...")
        success, output = self.run_command("npm run build:tailwind", cwd=self.theme_path, timeout=120)
        
        if success:
            self.log("✅ Theme assets built successfully")
            self.success_count += 1
            return True
        else:
            self.log("❌ Failed to build theme assets", "ERROR")
            return False

    def create_environment_config(self):
        """Create environment configuration files"""
        self.log("=== Creating Environment Configuration ===")
        
        # Create .env.example
        env_example_path = self.root_path / '.env.example'
        env_content = """# YouTuneAI Environment Configuration
# Copy this file to .env and fill in your actual values

# OpenAI API Configuration
OPENAI_API_KEY=your_openai_api_key_here

# WordPress Configuration
WP_SITE_URL=https://youtuneai.com
WP_ADMIN_USER=admin@youtuneai.com
WP_ADMIN_PASSWORD=your_secure_password

# SFTP/Deployment Configuration
SFTP_HOST=your_server_host
SFTP_USERNAME=your_username
SFTP_PASSWORD=your_password
SFTP_PORT=22

# Development Settings
DEBUG=true
LOG_LEVEL=INFO

# Security Settings
SECRET_KEY=generate_a_secure_secret_key_here
"""
        
        with open(env_example_path, 'w') as f:
            f.write(env_content)
        
        # Create basic .env if it doesn't exist
        env_path = self.root_path / '.env'
        if not env_path.exists():
            with open(env_path, 'w') as f:
                f.write("# YouTuneAI Environment Variables\n")
                f.write("# Fill in your actual values\n\n")
                f.write("DEBUG=true\n")
                f.write("LOG_LEVEL=INFO\n")
        
        self.log("✅ Environment configuration created")
        self.success_count += 1
        return True

    def validate_setup(self):
        """Validate that setup was successful"""
        self.log("=== Validating Setup ===")
        
        validation_checks = [
            ("Python import test", "python3 -c 'import requests; print(\"OK\")'"),
            ("Node.js availability", "node --version"),
            ("Theme CSS build", f"test -f {self.theme_path / 'assets' / 'css' / 'dist' / 'main.css'} && echo 'OK'"),
            ("Environment config", f"test -f {self.root_path / '.env.example'} && echo 'OK'")
        ]
        
        all_valid = True
        for check_name, command in validation_checks:
            success, output = self.run_command(command)
            if success and 'OK' in output:
                self.log(f"✅ {check_name}")
            else:
                self.log(f"❌ {check_name}", "ERROR")
                all_valid = False
        
        if all_valid:
            self.log("✅ Setup validation completed successfully")
            self.success_count += 1
            return True
        else:
            self.log("❌ Setup validation failed", "ERROR")
            return False

    def create_setup_report(self):
        """Create detailed setup report"""
        self.log("=== Creating Setup Report ===")
        
        report = {
            "setup_timestamp": datetime.now().isoformat(),
            "success_rate": f"{self.success_count}/{self.total_steps}",
            "status": "SUCCESS" if self.success_count == self.total_steps else "PARTIAL",
            "components": {
                "python_environment": self.success_count >= 2,
                "node_environment": self.success_count >= 3,
                "theme_build": self.success_count >= 4,
                "configuration": self.success_count >= 5,
                "validation": self.success_count >= 6
            },
            "next_steps": [
                "Fill in .env with your actual API keys and credentials",
                "Test voice commands with: python3 working_deployment_controller.py",
                "Build theme in watch mode: cd youtuneai-theme && npm run dev",
                "Deploy to production when ready"
            ]
        }
        
        report_path = self.logs_path / 'setup_report.json'
        with open(report_path, 'w') as f:
            json.dump(report, f, indent=2)
        
        self.log("✅ Setup report created")
        self.success_count += 1
        return True

    def run_complete_setup(self):
        """Run the complete setup process"""
        self.log("🚀 Starting YouTuneAI Complete Setup")
        self.log("=" * 50)
        
        setup_steps = [
            ("System Requirements Check", self.check_system_requirements),
            ("Python Dependencies", self.install_python_dependencies),
            ("Node.js Environment", self.setup_node_environment),
            ("Theme Assets Build", self.build_theme_assets),
            ("Environment Config", self.create_environment_config),
            ("Setup Validation", self.validate_setup),
            ("Setup Report", self.create_setup_report)
        ]
        
        total_start_time = datetime.now()
        
        for step_name, step_function in setup_steps:
            self.log(f"\n🔄 Step: {step_name}")
            step_start = datetime.now()
            
            try:
                success = step_function()
                step_duration = (datetime.now() - step_start).total_seconds()
                
                if success:
                    self.log(f"✅ {step_name} completed in {step_duration:.1f}s")
                else:
                    self.log(f"❌ {step_name} failed after {step_duration:.1f}s", "ERROR")
                    
            except Exception as e:
                self.log(f"❌ {step_name} crashed: {str(e)}", "ERROR")
        
        total_duration = (datetime.now() - total_start_time).total_seconds()
        
        # Final summary
        self.log("\n" + "=" * 50)
        self.log("🎯 SETUP SUMMARY")
        self.log("=" * 50)
        self.log(f"Total time: {total_duration:.1f} seconds")
        self.log(f"Success rate: {self.success_count}/{self.total_steps}")
        
        if self.success_count == self.total_steps:
            self.log("🎉 SETUP COMPLETED SUCCESSFULLY!")
            self.log("\n📋 Next Steps:")
            self.log("1. Edit .env with your API keys and credentials")
            self.log("2. Test: python3 working_deployment_controller.py")
            self.log("3. Develop: cd youtuneai-theme && npm run dev")
            self.log("4. Deploy when ready!")
            return True
        else:
            self.log("⚠️ SETUP COMPLETED WITH ISSUES")
            self.log("Check the logs above for details on failed steps")
            return False

def main():
    """Main setup execution"""
    if len(sys.argv) > 1 and sys.argv[1] == '--help':
        print("YouTuneAI Setup Script")
        print("Usage: python3 setup.py")
        print("This script will:")
        print("- Check system requirements")
        print("- Install Python dependencies")
        print("- Setup Node.js environment")
        print("- Build theme assets")
        print("- Create configuration files")
        print("- Validate the setup")
        return 0
    
    setup = YouTuneAISetup()
    success = setup.run_complete_setup()
    return 0 if success else 1

if __name__ == "__main__":
    try:
        sys.exit(main())
    except KeyboardInterrupt:
        print("\n❌ Setup interrupted by user")
        sys.exit(1)
    except Exception as e:
        print(f"❌ Setup failed with error: {str(e)}")
        sys.exit(1)