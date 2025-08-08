#!/usr/bin/env python3
"""
YouTuneAI Deployment Validation Script
Boss Man Copilot - Final deployment verification
"""

import os
import sys
import json
from pathlib import Path
from datetime import datetime

def validate_deployment_readiness():
    """Validate that all deployment requirements are met"""
    print("🔍 YouTuneAI Deployment Validation")
    print("=" * 50)
    
    validation_results = {
        'timestamp': datetime.now().isoformat(),
        'overall_status': 'PENDING',
        'checks': {}
    }
    
    # Check 1: Deployment controller exists
    controller_path = Path('ionos_deployment_controller.py')
    if controller_path.exists():
        validation_results['checks']['deployment_controller'] = 'PASS'
        print("✅ IONOS deployment controller found")
    else:
        validation_results['checks']['deployment_controller'] = 'FAIL'
        print("❌ IONOS deployment controller missing")
    
    # Check 2: Secure admin config exists
    secure_config_path = Path('secure_admin_config.php')
    if secure_config_path.exists():
        validation_results['checks']['secure_config'] = 'PASS'
        print("✅ Secure admin configuration found")
    else:
        validation_results['checks']['secure_config'] = 'FAIL'
        print("❌ Secure admin configuration missing")
    
    # Check 3: WordPress theme files exist
    theme_path = Path('src/theme/deployment-ready/wp-theme-youtuneai')
    required_theme_files = [
        'functions.php',
        'page-admin-dashboard.php',
        'secure_admin_config.php',
        'index.php',
        'style.css'
    ]
    
    theme_files_valid = True
    for file in required_theme_files:
        file_path = theme_path / file
        if file_path.exists():
            print(f"✅ Theme file found: {file}")
        else:
            print(f"❌ Theme file missing: {file}")
            theme_files_valid = False
    
    validation_results['checks']['theme_files'] = 'PASS' if theme_files_valid else 'FAIL'
    
    # Check 4: Security hardening applied
    dashboard_file = theme_path / 'page-admin-dashboard.php'
    if dashboard_file.exists():
        with open(dashboard_file, 'r') as f:
            content = f.read()
            
        # Check that hardcoded credentials are removed
        if 'ADMIN_CREDENTIALS' not in content and 'Gabby3000' not in content:
            validation_results['checks']['security_hardening'] = 'PASS'
            print("✅ Hardcoded credentials removed from admin dashboard")
        else:
            validation_results['checks']['security_hardening'] = 'FAIL'
            print("❌ Hardcoded credentials still present")
    else:
        validation_results['checks']['security_hardening'] = 'FAIL'
        print("❌ Admin dashboard file not found")
    
    # Check 5: Dependencies available
    try:
        import paramiko
        import scp
        validation_results['checks']['dependencies'] = 'PASS'
        print("✅ Required dependencies (paramiko, scp) installed")
    except ImportError as e:
        validation_results['checks']['dependencies'] = 'FAIL'
        print(f"❌ Missing dependencies: {e}")
    
    # Check 6: Documentation exists
    docs_exist = all([
        Path('SECURITY_DEPLOYMENT_GUIDE.md').exists(),
        Path('README.md').exists()
    ])
    
    if docs_exist:
        validation_results['checks']['documentation'] = 'PASS'
        print("✅ Security and deployment documentation complete")
    else:
        validation_results['checks']['documentation'] = 'FAIL'
        print("❌ Documentation incomplete")
    
    # Check 7: .gitignore security
    gitignore_path = Path('.gitignore')
    if gitignore_path.exists():
        with open(gitignore_path, 'r') as f:
            gitignore_content = f.read()
            
        security_patterns = ['.secure_admin', '*.key', '*.pem', 'logs/']
        security_protected = all(pattern in gitignore_content for pattern in security_patterns)
        
        if security_protected:
            validation_results['checks']['gitignore_security'] = 'PASS'
            print("✅ .gitignore protects sensitive files")
        else:
            validation_results['checks']['gitignore_security'] = 'FAIL'
            print("❌ .gitignore insufficient for security")
    else:
        validation_results['checks']['gitignore_security'] = 'FAIL'
        print("❌ .gitignore file missing")
    
    # Overall status calculation
    passed_checks = sum(1 for check in validation_results['checks'].values() if check == 'PASS')
    total_checks = len(validation_results['checks'])
    
    if passed_checks == total_checks:
        validation_results['overall_status'] = 'READY'
        print(f"\n🎯 Deployment Status: READY ✅ ({passed_checks}/{total_checks} checks passed)")
    else:
        validation_results['overall_status'] = 'NOT_READY'
        print(f"\n⚠️ Deployment Status: NOT READY ({passed_checks}/{total_checks} checks passed)")
    
    # Save validation report
    report_path = Path('deployment_validation_report.json')
    with open(report_path, 'w') as f:
        json.dump(validation_results, f, indent=2)
    
    print(f"\n📊 Validation report saved to: {report_path}")
    
    return validation_results

def main():
    """Main validation execution"""
    try:
        results = validate_deployment_readiness()
        
        if results['overall_status'] == 'READY':
            print("\n🚀 YouTuneAI is ready for IONOS deployment!")
            print("📋 Next steps:")
            print("1. Set environment variables: YOUTUNEAI_ADMIN_EMAIL and YOUTUNEAI_ADMIN_PASSWORD")
            print("2. Verify IONOS SSH access credentials")
            print("3. Run: python3 ionos_deployment_controller.py")
            print("4. Monitor logs/ directory for deployment status")
            return 0
        else:
            print("\n❌ Deployment validation failed. Please address the issues above.")
            return 1
            
    except Exception as e:
        print(f"❌ Validation error: {str(e)}")
        return 1

if __name__ == "__main__":
    sys.exit(main())