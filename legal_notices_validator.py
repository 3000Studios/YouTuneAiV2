#!/usr/bin/env python3
"""
YouTuneAI Legal Notices Validator
Ensures patent and legal notices are properly deployed on youtuneai.com

This script validates that all required legal notices, patent attributions,
and copyright information are present on the live site.
"""

import requests
import json
import sys
from datetime import datetime
from pathlib import Path
from typing import Dict, List, Tuple, Optional


class LegalNoticesValidator:
    """Validates legal notices and patent information on deployed site"""
    
    def __init__(self, site_url: str = "https://youtuneai.com"):
        self.site_url = site_url
        self.validation_results = []
        self.required_notices = self._get_required_notices()
        
    def _get_required_notices(self) -> Dict[str, List[str]]:
        """Define required legal notices and patent information"""
        return {
            "copyright": [
                "3000Studios",
                "YouTuneAI",
                "©",
                "Copyright"
            ],
            "patent_notices": [
                "Patent Pending",
                "US Patent",
                "Patent Application",
                "Intellectual Property"
            ],
            "trademark": [
                "™",
                "®",
                "YouTuneAI™",
                "3000Studios®"
            ],
            "legal_disclaimers": [
                "Terms of Service",
                "Privacy Policy",
                "Legal Notice",
                "Disclaimer"
            ],
            "attribution": [
                "3000Studios",
                "Licensed under",
                "All rights reserved"
            ]
        }
    
    def fetch_page_content(self, page_path: str = "") -> Optional[str]:
        """Fetch content from a specific page"""
        url = f"{self.site_url}{page_path}"
        
        try:
            response = requests.get(url, timeout=30, verify=True)
            response.raise_for_status()
            
            print(f"✅ Successfully fetched: {url}")
            return response.text
            
        except requests.RequestException as e:
            print(f"❌ Failed to fetch {url}: {str(e)}")
            return None
    
    def validate_notice_presence(self, content: str, notice_type: str, patterns: List[str]) -> Dict:
        """Validate presence of specific notice patterns"""
        found_patterns = []
        missing_patterns = []
        
        content_lower = content.lower()
        
        for pattern in patterns:
            if pattern.lower() in content_lower:
                found_patterns.append(pattern)
            else:
                missing_patterns.append(pattern)
        
        validation = {
            "notice_type": notice_type,
            "found": found_patterns,
            "missing": missing_patterns,
            "compliance": len(found_patterns) > 0,  # At least one pattern must be found
            "coverage": len(found_patterns) / len(patterns) if patterns else 0
        }
        
        return validation
    
    def validate_homepage_notices(self) -> Dict:
        """Validate legal notices on the homepage"""
        print("🏠 Validating homepage legal notices...")
        
        homepage_content = self.fetch_page_content()
        if not homepage_content:
            return {"status": "failed", "reason": "Could not fetch homepage"}
        
        results = {
            "page": "homepage",
            "url": self.site_url,
            "timestamp": datetime.now().isoformat(),
            "notices": {},
            "overall_compliance": True
        }
        
        # Validate each type of notice
        for notice_type, patterns in self.required_notices.items():
            validation = self.validate_notice_presence(homepage_content, notice_type, patterns)
            results["notices"][notice_type] = validation
            
            if not validation["compliance"]:
                results["overall_compliance"] = False
                print(f"⚠️  Missing {notice_type} notices on homepage")
            else:
                print(f"✅ {notice_type} notices found on homepage")
        
        return results
    
    def validate_legal_pages(self) -> List[Dict]:
        """Validate dedicated legal pages"""
        legal_pages = [
            "/terms-of-service",
            "/privacy-policy",
            "/legal-notice",
            "/patents",
            "/about"
        ]
        
        results = []
        
        for page_path in legal_pages:
            print(f"📄 Validating legal page: {page_path}")
            
            content = self.fetch_page_content(page_path)
            if not content:
                results.append({
                    "page": page_path,
                    "status": "not_found",
                    "compliance": False
                })
                continue
            
            page_result = {
                "page": page_path,
                "url": f"{self.site_url}{page_path}",
                "status": "found",
                "notices": {},
                "compliance": True
            }
            
            # Focus on specific notices for legal pages
            relevant_notices = ["copyright", "patent_notices", "attribution"]
            
            for notice_type in relevant_notices:
                patterns = self.required_notices[notice_type]
                validation = self.validate_notice_presence(content, notice_type, patterns)
                page_result["notices"][notice_type] = validation
                
                if not validation["compliance"]:
                    page_result["compliance"] = False
            
            results.append(page_result)
        
        return results
    
    def validate_footer_notices(self) -> Dict:
        """Validate legal notices in site footer"""
        print("🦶 Validating footer legal notices...")
        
        homepage_content = self.fetch_page_content()
        if not homepage_content:
            return {"status": "failed", "reason": "Could not fetch homepage for footer validation"}
        
        # Extract footer content (simple heuristic)
        footer_markers = ["<footer", "</footer>", "class=\"footer\"", "id=\"footer\""]
        footer_content = ""
        
        for marker in footer_markers:
            if marker in homepage_content.lower():
                # Extract footer section
                start_idx = homepage_content.lower().find(marker)
                if start_idx != -1:
                    # Get a reasonable chunk that likely contains footer
                    footer_content = homepage_content[start_idx:start_idx + 2000]
                    break
        
        if not footer_content:
            # Fallback: check last 1000 characters for footer-like content
            footer_content = homepage_content[-1000:]
        
        results = {
            "section": "footer",
            "content_length": len(footer_content),
            "notices": {},
            "compliance": True
        }
        
        # Check essential footer notices
        footer_notices = ["copyright", "attribution"]
        
        for notice_type in footer_notices:
            patterns = self.required_notices[notice_type]
            validation = self.validate_notice_presence(footer_content, notice_type, patterns)
            results["notices"][notice_type] = validation
            
            if not validation["compliance"]:
                results["compliance"] = False
                print(f"⚠️  Missing {notice_type} in footer")
            else:
                print(f"✅ {notice_type} found in footer")
        
        return results
    
    def validate_meta_information(self) -> Dict:
        """Validate legal information in HTML meta tags"""
        print("🏷️  Validating meta tag legal information...")
        
        homepage_content = self.fetch_page_content()
        if not homepage_content:
            return {"status": "failed", "reason": "Could not fetch homepage for meta validation"}
        
        meta_patterns = {
            "author": ["3000Studios", "YouTuneAI"],
            "copyright": ["3000Studios", "©"],
            "description": ["YouTuneAI", "AI-powered"],
            "keywords": ["AI", "voice", "YouTube", "3000Studios"]
        }
        
        results = {
            "section": "meta_tags",
            "validations": {},
            "compliance": True
        }
        
        for meta_type, patterns in meta_patterns.items():
            found_patterns = []
            
            # Look for meta tags with these patterns
            for pattern in patterns:
                if f'name="{meta_type}"' in homepage_content and pattern in homepage_content:
                    found_patterns.append(pattern)
                elif f"property=\"og:{meta_type}\"" in homepage_content and pattern in homepage_content:
                    found_patterns.append(pattern)
            
            results["validations"][meta_type] = {
                "found": found_patterns,
                "compliance": len(found_patterns) > 0
            }
            
            if not results["validations"][meta_type]["compliance"]:
                results["compliance"] = False
                print(f"⚠️  Missing {meta_type} meta information")
            else:
                print(f"✅ {meta_type} meta information found")
        
        return results
    
    def check_ssl_certificate(self) -> Dict:
        """Validate SSL certificate and security headers"""
        print("🔒 Validating SSL certificate and security...")
        
        try:
            response = requests.get(self.site_url, timeout=30, verify=True)
            
            results = {
                "ssl_valid": True,
                "https_redirect": response.url.startswith("https://"),
                "security_headers": {},
                "compliance": True
            }
            
            # Check important security headers
            security_headers = [
                "Strict-Transport-Security",
                "X-Content-Type-Options",
                "X-Frame-Options",
                "X-XSS-Protection"
            ]
            
            for header in security_headers:
                if header in response.headers:
                    results["security_headers"][header] = response.headers[header]
                    print(f"✅ Security header found: {header}")
                else:
                    results["security_headers"][header] = None
                    print(f"⚠️  Missing security header: {header}")
            
            return results
            
        except requests.exceptions.SSLError:
            return {
                "ssl_valid": False,
                "compliance": False,
                "error": "SSL certificate validation failed"
            }
        except Exception as e:
            return {
                "ssl_valid": False,
                "compliance": False,
                "error": str(e)
            }
    
    def generate_compliance_report(self) -> Dict:
        """Generate comprehensive compliance report"""
        print("📊 Generating legal compliance report...")
        
        report = {
            "site_url": self.site_url,
            "validation_timestamp": datetime.now().isoformat(),
            "validations": {},
            "overall_compliance": True,
            "recommendations": []
        }
        
        # Validate homepage notices
        homepage_validation = self.validate_homepage_notices()
        report["validations"]["homepage"] = homepage_validation
        
        if not homepage_validation.get("overall_compliance", False):
            report["overall_compliance"] = False
            report["recommendations"].append("Add missing legal notices to homepage")
        
        # Validate legal pages
        legal_pages_validation = self.validate_legal_pages()
        report["validations"]["legal_pages"] = legal_pages_validation
        
        for page in legal_pages_validation:
            if not page.get("compliance", False):
                report["overall_compliance"] = False
                report["recommendations"].append(f"Improve legal notices on {page['page']}")
        
        # Validate footer notices
        footer_validation = self.validate_footer_notices()
        report["validations"]["footer"] = footer_validation
        
        if not footer_validation.get("compliance", False):
            report["overall_compliance"] = False
            report["recommendations"].append("Add required legal notices to footer")
        
        # Validate meta information
        meta_validation = self.validate_meta_information()
        report["validations"]["meta_tags"] = meta_validation
        
        if not meta_validation.get("compliance", False):
            report["overall_compliance"] = False
            report["recommendations"].append("Add legal information to meta tags")
        
        # Validate SSL and security
        ssl_validation = self.check_ssl_certificate()
        report["validations"]["security"] = ssl_validation
        
        if not ssl_validation.get("compliance", False):
            report["overall_compliance"] = False
            report["recommendations"].append("Fix SSL certificate and security headers")
        
        # Generate summary
        report["summary"] = {
            "total_validations": len(report["validations"]),
            "passed_validations": sum(1 for v in report["validations"].values() 
                                    if v.get("compliance") or v.get("overall_compliance")),
            "compliance_percentage": 0
        }
        
        if report["summary"]["total_validations"] > 0:
            report["summary"]["compliance_percentage"] = (
                report["summary"]["passed_validations"] / 
                report["summary"]["total_validations"] * 100
            )
        
        return report
    
    def save_report(self, report: Dict, filename: str = None) -> str:
        """Save compliance report to file"""
        if filename is None:
            filename = f"legal_compliance_report_{datetime.now().strftime('%Y%m%d_%H%M%S')}.json"
        
        report_path = Path(filename)
        
        with open(report_path, 'w') as f:
            json.dump(report, f, indent=2)
        
        print(f"📄 Compliance report saved: {report_path}")
        return str(report_path)
    
    def print_compliance_summary(self, report: Dict):
        """Print human-readable compliance summary"""
        print("\n" + "="*50)
        print("📋 LEGAL COMPLIANCE SUMMARY")
        print("="*50)
        
        print(f"🌐 Site: {report['site_url']}")
        print(f"📅 Validated: {report['validation_timestamp']}")
        print(f"✅ Overall Compliance: {'PASS' if report['overall_compliance'] else 'FAIL'}")
        print(f"📊 Compliance Rate: {report['summary']['compliance_percentage']:.1f}%")
        
        print(f"\n📈 Validation Results:")
        for section, validation in report['validations'].items():
            compliance = validation.get('compliance') or validation.get('overall_compliance')
            status = "✅ PASS" if compliance else "❌ FAIL"
            print(f"  {section}: {status}")
        
        if report['recommendations']:
            print(f"\n💡 Recommendations:")
            for i, rec in enumerate(report['recommendations'], 1):
                print(f"  {i}. {rec}")
        
        print("\n" + "="*50)


def main():
    """Main entry point for legal validation"""
    import argparse
    
    parser = argparse.ArgumentParser(description="Validate legal notices on YouTuneAI deployment")
    parser.add_argument("--url", default="https://youtuneai.com", help="Site URL to validate")
    parser.add_argument("--output", help="Output file for report")
    parser.add_argument("--verbose", action="store_true", help="Verbose output")
    
    args = parser.parse_args()
    
    try:
        validator = LegalNoticesValidator(args.url)
        
        print(f"🔍 Starting legal compliance validation for {args.url}")
        
        # Generate compliance report
        report = validator.generate_compliance_report()
        
        # Save report
        report_file = validator.save_report(report, args.output)
        
        # Print summary
        validator.print_compliance_summary(report)
        
        # Exit with appropriate code
        if report['overall_compliance']:
            print("🎉 Legal compliance validation PASSED!")
            sys.exit(0)
        else:
            print("❌ Legal compliance validation FAILED!")
            sys.exit(1)
            
    except Exception as e:
        print(f"❌ Legal validation error: {str(e)}")
        sys.exit(1)


if __name__ == "__main__":
    main()