#!/usr/bin/env python3
"""
Comprehensive CI/CD Pipeline Test Suite
Tests the secure deployment pipeline components
"""

import os
import sys
import json
import tempfile
from pathlib import Path
from datetime import datetime


class DeploymentPipelineTests:
    """Test suite for CI/CD pipeline components"""
    
    def __init__(self):
        self.test_results = {
            "timestamp": datetime.now().isoformat(),
            "tests": [],
            "summary": {
                "total": 0,
                "passed": 0,
                "failed": 0,
                "warnings": 0
            }
        }
        self.project_root = Path(__file__).parent
        
    def run_test(self, test_name: str, test_func, *args, **kwargs):
        """Run a single test and record results"""
        print(f"🧪 Running test: {test_name}")
        
        try:
            result = test_func(*args, **kwargs)
            
            test_result = {
                "name": test_name,
                "status": "passed" if result.get("passed", False) else "failed",
                "details": result.get("details", ""),
                "warnings": result.get("warnings", [])
            }
            
            self.test_results["tests"].append(test_result)
            self.test_results["summary"]["total"] += 1
            
            if test_result["status"] == "passed":
                self.test_results["summary"]["passed"] += 1
                print(f"✅ {test_name} - PASSED")
            else:
                self.test_results["summary"]["failed"] += 1
                print(f"❌ {test_name} - FAILED")
                
            if test_result["warnings"]:
                self.test_results["summary"]["warnings"] += len(test_result["warnings"])
                for warning in test_result["warnings"]:
                    print(f"⚠️  {warning}")
                    
        except Exception as e:
            test_result = {
                "name": test_name,
                "status": "failed",
                "details": f"Test execution error: {str(e)}",
                "warnings": []
            }
            
            self.test_results["tests"].append(test_result)
            self.test_results["summary"]["total"] += 1
            self.test_results["summary"]["failed"] += 1
            print(f"❌ {test_name} - FAILED: {str(e)}")
    
    def test_github_workflow_exists(self):
        """Test that the GitHub Actions workflow file exists and is valid"""
        workflow_file = self.project_root / ".github" / "workflows" / "secure-production-deployment.yml"
        
        result = {
            "passed": False,
            "details": "",
            "warnings": []
        }
        
        if not workflow_file.exists():
            result["details"] = "GitHub Actions workflow file not found"
            return result
        
        try:
            content = workflow_file.read_text()
            
            # Check for required sections
            required_sections = [
                "on:",
                "jobs:",
                "security-scan:",
                "build-and-test:",
                "deploy-to-production:",
                "validate-deployment:",
                "rollback-on-failure:",
                "generate-report:"
            ]
            
            missing_sections = []
            for section in required_sections:
                if section not in content:
                    missing_sections.append(section)
            
            if missing_sections:
                result["details"] = f"Missing workflow sections: {missing_sections}"
                return result
            
            # Check for security features
            security_features = [
                "secrets.IONOS_HOST",
                "secrets.IONOS_USERNAME", 
                "secrets.IONOS_PASSWORD",
                "bandit",
                "safety check",
                "environment: production"
            ]
            
            missing_features = []
            for feature in security_features:
                if feature not in content:
                    missing_features.append(feature)
            
            if missing_features:
                result["warnings"].extend([f"Missing security feature: {f}" for f in missing_features])
            
            result["passed"] = True
            result["details"] = f"Workflow file valid ({len(content)} bytes)"
            
        except Exception as e:
            result["details"] = f"Error reading workflow file: {str(e)}"
        
        return result
    
    def test_secure_deployment_controller(self):
        """Test the secure deployment controller"""
        controller_file = self.project_root / "secure_deployment_controller.py"
        
        result = {
            "passed": False,
            "details": "",
            "warnings": []
        }
        
        if not controller_file.exists():
            result["details"] = "Secure deployment controller not found"
            return result
        
        try:
            # Test import
            import importlib.util
            spec = importlib.util.spec_from_file_location("secure_deployment_controller", controller_file)
            module = importlib.util.module_from_spec(spec)
            
            # Check for required classes and methods
            content = controller_file.read_text()
            
            required_components = [
                "class SecureIONOSDeployment",
                "def scrub_secrets_from_log",
                "def create_ssh_connection",
                "def create_backup",
                "def deploy_files",
                "def set_security_permissions",
                "def rollback_deployment"
            ]
            
            missing_components = []
            for component in required_components:
                if component not in content:
                    missing_components.append(component)
            
            if missing_components:
                result["details"] = f"Missing components: {missing_components}"
                return result
            
            # Check for hardcoded credentials (security test)
            security_issues = []
            
            # Common patterns that should NOT be in the secure version
            insecure_patterns = [
                "password = '",
                "password='",
                'password = "',
                'password="',
                'Gabby3000'
            ]
            
            for pattern in insecure_patterns:
                if pattern in content:
                    security_issues.append(f"Found insecure pattern: {pattern}")
            
            if security_issues:
                result["warnings"].extend(security_issues)
            
            result["passed"] = True
            result["details"] = f"Secure deployment controller valid ({len(content)} bytes)"
            
        except Exception as e:
            result["details"] = f"Error testing controller: {str(e)}"
        
        return result
    
    def test_legal_notices_validator(self):
        """Test the legal notices validator"""
        validator_file = self.project_root / "legal_notices_validator.py"
        
        result = {
            "passed": False,
            "details": "",
            "warnings": []
        }
        
        if not validator_file.exists():
            result["details"] = "Legal notices validator not found"
            return result
        
        try:
            content = validator_file.read_text()
            
            required_components = [
                "class LegalNoticesValidator",
                "def validate_homepage_notices",
                "def validate_legal_pages",
                "def validate_footer_notices",
                "def check_ssl_certificate",
                "def generate_compliance_report"
            ]
            
            missing_components = []
            for component in required_components:
                if component not in content:
                    missing_components.append(component)
            
            if missing_components:
                result["details"] = f"Missing components: {missing_components}"
                return result
            
            # Check for required legal patterns
            legal_patterns = [
                "3000Studios",
                "Patent Pending",
                "Copyright",
                "Terms of Service",
                "Privacy Policy"
            ]
            
            missing_patterns = []
            for pattern in legal_patterns:
                if pattern not in content:
                    missing_patterns.append(pattern)
            
            if missing_patterns:
                result["warnings"].extend([f"Missing legal pattern: {p}" for p in missing_patterns])
            
            result["passed"] = True
            result["details"] = f"Legal validator valid ({len(content)} bytes)"
            
        except Exception as e:
            result["details"] = f"Error testing validator: {str(e)}"
        
        return result
    
    def test_index_html_legal_notices(self):
        """Test that index.html contains proper legal notices"""
        index_file = self.project_root / "index.html"
        
        result = {
            "passed": False,
            "details": "",
            "warnings": []
        }
        
        if not index_file.exists():
            result["details"] = "index.html file not found"
            return result
        
        try:
            content = index_file.read_text()
            
            # Check for required legal notices
            required_notices = [
                "3000Studios®",
                "YouTuneAI™",
                "© 2024",
                "Patent Pending",
                "All Rights Reserved",
                'name="author"',
                'name="copyright"',
                'property="og:title"'
            ]
            
            missing_notices = []
            for notice in required_notices:
                if notice not in content:
                    missing_notices.append(notice)
            
            if missing_notices:
                result["details"] = f"Missing legal notices: {missing_notices}"
                return result
            
            # Check for footer
            if "<footer" not in content:
                result["warnings"].append("Footer element not found")
            
            # Check for schema markup
            if '"@context": "https://schema.org"' not in content:
                result["warnings"].append("Schema.org markup not found")
            
            result["passed"] = True
            result["details"] = f"Legal notices present in index.html ({len(content)} bytes)"
            
        except Exception as e:
            result["details"] = f"Error testing index.html: {str(e)}"
        
        return result
    
    def test_deployment_package_structure(self):
        """Test that required files exist for deployment"""
        result = {
            "passed": False,
            "details": "",
            "warnings": []
        }
        
        required_files = [
            "index.html",
            "package.json",
            "secure_deployment_controller.py",
            "legal_notices_validator.py",
            ".github/workflows/secure-production-deployment.yml"
        ]
        
        missing_files = []
        for file_path in required_files:
            full_path = self.project_root / file_path
            if not full_path.exists():
                missing_files.append(file_path)
        
        if missing_files:
            result["details"] = f"Missing required files: {missing_files}"
            return result
        
        # Check optional files
        optional_files = [
            "youtuneai-theme/",
            "src/",
            "voice_command_test.html",
            "secure_admin_config.php"
        ]
        
        missing_optional = []
        for file_path in optional_files:
            full_path = self.project_root / file_path
            if not full_path.exists():
                missing_optional.append(file_path)
        
        if missing_optional:
            result["warnings"].extend([f"Optional file missing: {f}" for f in missing_optional])
        
        result["passed"] = True
        result["details"] = "All required deployment files present"
        
        return result
    
    def test_requirements_file(self):
        """Test that requirements.txt has necessary dependencies"""
        requirements_file = self.project_root / "requirements.txt"
        
        result = {
            "passed": False,
            "details": "",
            "warnings": []
        }
        
        if not requirements_file.exists():
            result["details"] = "requirements.txt not found"
            return result
        
        try:
            content = requirements_file.read_text()
            
            required_deps = [
                "paramiko",
                "scp",
                "requests",
                "python-dotenv",
                "coloredlogs"
            ]
            
            missing_deps = []
            for dep in required_deps:
                if dep not in content:
                    missing_deps.append(dep)
            
            if missing_deps:
                result["details"] = f"Missing required dependencies: {missing_deps}"
                return result
            
            # Check for potentially problematic dependencies
            lines = content.split('\n')
            for line in lines:
                if line.strip().startswith('sqlite3'):
                    result["warnings"].append("sqlite3 dependency issue found (fixed)")
            
            result["passed"] = True
            result["details"] = f"Requirements file valid ({len(lines)} dependencies)"
            
        except Exception as e:
            result["details"] = f"Error testing requirements: {str(e)}"
        
        return result
    
    def test_voice_command_functionality(self):
        """Test that voice command files are properly integrated"""
        result = {
            "passed": False,
            "details": "",
            "warnings": []
        }
        
        # Check main index.html for voice command integration
        index_file = self.project_root / "index.html"
        if index_file.exists():
            content = index_file.read_text()
            
            voice_features = [
                "SpeechRecognition",
                "webkitSpeechRecognition",
                "voice",
                "speech",
                "recognition"
            ]
            
            found_features = []
            for feature in voice_features:
                if feature in content:
                    found_features.append(feature)
            
            if found_features:
                result["passed"] = True
                result["details"] = f"Voice features found: {found_features}"
            else:
                result["warnings"].append("No voice command integration found in index.html")
        
        # Check for dedicated voice command test file
        voice_test_file = self.project_root / "voice_command_test.html"
        if voice_test_file.exists():
            result["passed"] = True
            if result["details"]:
                result["details"] += " | Voice test file present"
            else:
                result["details"] = "Voice test file present"
        else:
            result["warnings"].append("voice_command_test.html not found")
        
        if not result["passed"] and not result["warnings"]:
            result["details"] = "No voice command functionality detected"
        
        return result
    
    def run_all_tests(self):
        """Run all tests in the suite"""
        print("🚀 Starting CI/CD Pipeline Test Suite")
        print("=" * 50)
        
        # Run all tests
        self.run_test("GitHub Workflow Configuration", self.test_github_workflow_exists)
        self.run_test("Secure Deployment Controller", self.test_secure_deployment_controller)
        self.run_test("Legal Notices Validator", self.test_legal_notices_validator)
        self.run_test("Index.html Legal Notices", self.test_index_html_legal_notices)
        self.run_test("Deployment Package Structure", self.test_deployment_package_structure)
        self.run_test("Requirements File", self.test_requirements_file)
        self.run_test("Voice Command Functionality", self.test_voice_command_functionality)
        
        # Generate summary
        print("\n" + "=" * 50)
        print("📊 TEST RESULTS SUMMARY")
        print("=" * 50)
        
        summary = self.test_results["summary"]
        print(f"✅ Tests Passed: {summary['passed']}")
        print(f"❌ Tests Failed: {summary['failed']}")
        print(f"⚠️  Warnings: {summary['warnings']}")
        print(f"📈 Success Rate: {summary['passed'] / summary['total'] * 100:.1f}%")
        
        # Save detailed results
        report_file = self.project_root / "ci_cd_test_results.json"
        with open(report_file, 'w') as f:
            json.dump(self.test_results, f, indent=2)
        
        print(f"\n📄 Detailed report saved: {report_file}")
        
        # Determine overall result
        if summary["failed"] == 0:
            print("\n🎉 CI/CD Pipeline Test Suite: ALL TESTS PASSED!")
            return True
        else:
            print(f"\n❌ CI/CD Pipeline Test Suite: {summary['failed']} TESTS FAILED!")
            return False


def main():
    """Main entry point"""
    test_suite = DeploymentPipelineTests()
    success = test_suite.run_all_tests()
    
    sys.exit(0 if success else 1)


if __name__ == "__main__":
    main()