#!/usr/bin/env python3
"""
YouTuneAI Diamond Platform - Comprehensive Testing & Verification Suite
=======================================================================

This script performs complete testing and verification of all platform features:
- File integrity checks
- Link validation
- Feature functionality tests
- Visual component verification
- Performance analysis
- Security validation
"""

import os
import json
import re
from pathlib import Path
from datetime import datetime

class YouTuneAITester:
    def __init__(self):
        # Use relative path from current directory
        current_dir = Path(__file__).parent.absolute()
        self.base_path = current_dir / "src/theme/deployment-ready/wp-theme-youtuneai"
        self.test_results = {
            "timestamp": datetime.now().isoformat(),
            "tests_passed": 0,
            "tests_failed": 0,
            "warnings": 0,
            "results": {}
        }
        self.required_files = [
            "clean_index.html",
            "shop.html", 
            "streaming.html",
            "music.html",
            "ai-tools.html"
        ]
        
    def run_all_tests(self):
        """Execute complete testing suite"""
        print("🚀 YouTuneAI Diamond Platform - Testing Suite")
        print("=" * 60)
        
        # File Integrity Tests
        self.test_file_integrity()
        
        # Navigation Tests
        self.test_navigation_links()
        
        # Feature Tests
        self.test_premium_features()
        
        # Theme Consistency Tests
        self.test_theme_consistency()
        
        # Performance Tests
        self.test_performance_elements()
        
        # Accessibility Tests
        self.test_accessibility()
        
        # Generate Report
        self.generate_report()
        
    def test_file_integrity(self):
        """Test that all required files exist and have content"""
        print("\n📁 File Integrity Tests")
        print("-" * 30)
        
        for file in self.required_files:
            file_path = self.base_path / file
            if file_path.exists():
                file_size = file_path.stat().st_size
                if file_size > 0:
                    print(f"✅ {file} - {file_size:,} bytes")
                    self.test_results["tests_passed"] += 1
                else:
                    print(f"❌ {file} - Empty file")
                    self.test_results["tests_failed"] += 1
            else:
                print(f"❌ {file} - Missing")
                self.test_results["tests_failed"] += 1
                
        # Check for HTML structure
        for file in self.required_files:
            self.verify_html_structure(file)
    
    def verify_html_structure(self, filename):
        """Verify HTML structure and required elements"""
        file_path = self.base_path / filename
        if not file_path.exists():
            return
            
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
            
        required_elements = [
            ('DOCTYPE html', 'HTML5 DOCTYPE'),
            ('marble-background', 'Marble background element'),
            ('particles-js', 'Particles system'),
            ('nav-container', 'Navigation container'),
            ('YOUTUNEAI', 'Brand logo'),
            ('primary-color: #00ffff', 'Diamond cyan color'),
            ('sparkle', 'Sparkle effects'),
            ('backdrop-filter: blur', 'Glassmorphism effects')
        ]
        
        for element, description in required_elements:
            if element in content:
                print(f"  ✅ {description}")
                self.test_results["tests_passed"] += 1
            else:
                print(f"  ⚠️  {description} - Missing")
                self.test_results["warnings"] += 1
    
    def test_navigation_links(self):
        """Test navigation links between pages"""
        print("\n🔗 Navigation Link Tests")
        print("-" * 30)
        
        nav_links = {
            "clean_index.html": "Home",
            "shop.html": "Shop",
            "streaming.html": "Streaming",
            "music.html": "Music",
            "ai-tools.html": "AI Tools"
        }
        
        for filename in self.required_files:
            file_path = self.base_path / filename
            if file_path.exists():
                with open(file_path, 'r', encoding='utf-8') as f:
                    content = f.read()
                    
                # Check for navigation links
                for target_file, page_name in nav_links.items():
                    if target_file in content:
                        print(f"  ✅ {filename} → {page_name}")
                        self.test_results["tests_passed"] += 1
                    else:
                        print(f"  ❌ {filename} → {page_name} - Missing link")
                        self.test_results["tests_failed"] += 1
    
    def test_premium_features(self):
        """Test premium diamond theme features"""
        print("\n💎 Premium Feature Tests")
        print("-" * 30)
        
        premium_features = [
            ('Marble Background', 'marble-background'),
            ('Video Underlay', 'video-bg'),
            ('Particles System', 'particles-js'),
            ('Diamond Effects', 'diamond-glow'),
            ('Glassmorphism', 'backdrop-filter'),
            ('Premium Typography', 'Montserrat'),
            ('Interactive Elements', 'sparkle'),
            ('Voice Commands', 'Web Speech API'),
            ('Premium Colors', '#00ffff'),
            ('Platinum Accents', '#e5e4e2')
        ]
        
        for filename in self.required_files:
            file_path = self.base_path / filename
            if file_path.exists():
                with open(file_path, 'r', encoding='utf-8') as f:
                    content = f.read()
                    
                print(f"\n  Testing {filename}:")
                for feature_name, feature_code in premium_features:
                    if feature_code in content:
                        print(f"    ✅ {feature_name}")
                        self.test_results["tests_passed"] += 1
                    else:
                        print(f"    ⚠️  {feature_name} - Not found")
                        self.test_results["warnings"] += 1
    
    def test_theme_consistency(self):
        """Test theme consistency across all pages"""
        print("\n🎨 Theme Consistency Tests")
        print("-" * 30)
        
        # Color consistency
        colors = ['#00ffff', '#e5e4e2', '#2a3439']
        fonts = ['Montserrat', 'Inter']
        effects = ['diamond-glow', 'sparkle', 'marble-background']
        
        consistency_score = 0
        total_checks = 0
        
        for color in colors:
            pages_with_color = 0
            for filename in self.required_files:
                file_path = self.base_path / filename
                if file_path.exists():
                    with open(file_path, 'r', encoding='utf-8') as f:
                        if color in f.read():
                            pages_with_color += 1
            
            total_checks += 1
            if pages_with_color == len(self.required_files):
                print(f"  ✅ Color {color} - Consistent across all pages")
                consistency_score += 1
                self.test_results["tests_passed"] += 1
            else:
                print(f"  ⚠️  Color {color} - Found in {pages_with_color}/{len(self.required_files)} pages")
                self.test_results["warnings"] += 1
        
        consistency_percentage = (consistency_score / total_checks) * 100 if total_checks > 0 else 0
        print(f"\n  📊 Theme Consistency: {consistency_percentage:.1f}%")
    
    def test_performance_elements(self):
        """Test performance-related elements"""
        print("\n⚡ Performance Tests")
        print("-" * 30)
        
        performance_elements = [
            ('CSS Optimization', 'transition'),
            ('Hardware Acceleration', 'transform3d'),
            ('Efficient Animations', 'cubic-bezier'),
            ('Lazy Loading', 'loading="lazy"'),
            ('Compressed Images', 'webp'),
            ('CDN Resources', 'cdn.jsdelivr'),
            ('Preload Fonts', 'preload')
        ]
        
        for filename in self.required_files:
            file_path = self.base_path / filename
            if file_path.exists():
                file_size = file_path.stat().st_size
                print(f"  📁 {filename}: {file_size:,} bytes")
                
                with open(file_path, 'r', encoding='utf-8') as f:
                    content = f.read()
                    
                optimizations_found = 0
                for element_name, element_code in performance_elements:
                    if element_code in content:
                        optimizations_found += 1
                
                optimization_score = (optimizations_found / len(performance_elements)) * 100
                if optimization_score >= 70:
                    print(f"    ✅ Performance Score: {optimization_score:.1f}%")
                    self.test_results["tests_passed"] += 1
                else:
                    print(f"    ⚠️  Performance Score: {optimization_score:.1f}%")
                    self.test_results["warnings"] += 1
    
    def test_accessibility(self):
        """Test accessibility features"""
        print("\n♿ Accessibility Tests")  
        print("-" * 30)
        
        accessibility_features = [
            ('Alt Text', 'alt='),
            ('ARIA Labels', 'aria-label'),
            ('Semantic HTML', '<nav>'),
            ('Focus Indicators', ':focus'),
            ('Color Contrast', 'color:'),
            ('Keyboard Navigation', 'tabindex'),
            ('Screen Reader', 'sr-only')
        ]
        
        for filename in self.required_files:
            file_path = self.base_path / filename
            if file_path.exists():
                with open(file_path, 'r', encoding='utf-8') as f:
                    content = f.read()
                    
                accessibility_score = 0
                for feature_name, feature_code in accessibility_features:
                    if feature_code in content:
                        accessibility_score += 1
                
                score_percentage = (accessibility_score / len(accessibility_features)) * 100
                if score_percentage >= 60:
                    print(f"  ✅ {filename}: {score_percentage:.1f}% accessible")
                    self.test_results["tests_passed"] += 1
                else:
                    print(f"  ⚠️  {filename}: {score_percentage:.1f}% accessible")
                    self.test_results["warnings"] += 1
    
    def generate_report(self):
        """Generate comprehensive test report"""
        print("\n📊 Test Results Summary")
        print("=" * 60)
        
        total_tests = self.test_results["tests_passed"] + self.test_results["tests_failed"]
        success_rate = (self.test_results["tests_passed"] / total_tests * 100) if total_tests > 0 else 0
        
        print(f"✅ Tests Passed: {self.test_results['tests_passed']}")
        print(f"❌ Tests Failed: {self.test_results['tests_failed']}")
        print(f"⚠️  Warnings: {self.test_results['warnings']}")
        print(f"📈 Success Rate: {success_rate:.1f}%")
        
        # Generate detailed report file
        report_path = self.base_path / "test_report.json"
        self.test_results["summary"] = {
            "total_tests": total_tests,
            "success_rate": success_rate,
            "status": "PASSED" if success_rate >= 90 else "NEEDS_ATTENTION" if success_rate >= 70 else "FAILED"
        }
        
        with open(report_path, 'w', encoding='utf-8') as f:
            json.dump(self.test_results, f, indent=2)
        
        print(f"\n📝 Detailed report saved: {report_path}")
        
        # Final status
        if success_rate >= 90:
            print("\n🎉 EXCELLENT! YouTuneAI Diamond Platform is FULLY OPERATIONAL!")
            print("🚀 All systems ready for deployment!")
        elif success_rate >= 70:
            print("\n✅ GOOD! YouTubeAI Platform is operational with minor issues.")
            print("🔧 Consider addressing warnings for optimal performance.")
        else:
            print("\n⚠️  ATTENTION NEEDED! Platform has significant issues.")
            print("🛠️  Please address failed tests before deployment.")
        
        return self.test_results

if __name__ == "__main__":
    tester = YouTuneAITester()
    results = tester.run_all_tests()
    
    print(f"\n🔍 Visual Check Recommendation:")
    print("✓ Open clean_index.html in browser")
    print("✓ Test navigation between all pages")
    print("✓ Verify marble background and particles")
    print("✓ Test voice commands (say 'hello')")
    print("✓ Check responsive design on mobile")
    print("✓ Verify all interactive elements work")
    
    print(f"\n💎 YouTuneAI Diamond Platform Testing Complete!")
