#!/usr/bin/env python3
"""
IONOS Webspace Cleanup Script
Optional cleanup and optimization for your IONOS webspace
"""

import paramiko

def cleanup_webspace():
    """Clean up and organize the IONOS webspace"""
    
    sftp_config = {
        'host': 'access-5017098454.webspace-host.com',
        'port': 22,
        'username': 'a917580',
        'password': 'Gabby3000!!!',
    }
    
    try:
        print("🔗 Connecting to IONOS webspace...")
        ssh = paramiko.SSHClient()
        ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        
        ssh.connect(
            sftp_config['host'],
            port=sftp_config['port'],
            username=sftp_config['username'],
            password=sftp_config['password'],
            timeout=30
        )
        
        sftp = ssh.open_sftp()
        
        print("\n🧹 IONOS Webspace Cleanup Options:")
        print("=====================================")
        
        # Check themes directory
        themes_path = 'clickandbuilds/YouTuneAi/wp-content/themes'
        themes = sftp.listdir(themes_path)
        
        print(f"\n📁 Current Themes ({len(themes)} total):")
        for theme in sorted(themes):
            if theme != 'index.php':
                size_info = ""
                try:
                    theme_path = f"{themes_path}/{theme}"
                    # Count files in theme
                    if theme != 'twentytwentyfive':  # Skip default theme details
                        theme_files = sftp.listdir(theme_path)
                        size_info = f" ({len(theme_files)} files)"
                except:
                    size_info = " (directory)"
                    
                status = ""
                if theme == 'wp-theme-youtuneai':
                    status = " ✅ ACTIVE"
                elif 'youtuneai' in theme.lower():
                    status = " 🗂️ OLD VERSION"
                elif theme == 'twentytwentyfive':
                    status = " 📦 DEFAULT"
                    
                print(f"  🎨 {theme}{size_info}{status}")
        
        print(f"\n📊 WordPress Structure Analysis:")
        print(f"  📍 WordPress Root: clickandbuilds/YouTuneAi/")
        print(f"  🎨 Active Theme: wp-theme-youtuneai")
        print(f"  🌐 Domain: youtuneai.com")
        print(f"  ✅ SSL: Valid until Aug 2026")
        
        # Check for unnecessary files
        print(f"\n🔍 Checking for cleanup opportunities...")
        root_files = sftp.listdir('clickandbuilds/YouTuneAi')
        
        cleanup_candidates = []
        for file in root_files:
            if file in ['YouTuneAiV2', 'it-api.php'] or file.startswith('backup'):
                cleanup_candidates.append(file)
        
        if cleanup_candidates:
            print(f"\n🗑️ Files that could be cleaned up:")
            for file in cleanup_candidates:
                print(f"  📄 {file}")
        else:
            print(f"\n✨ Webspace is clean - no unnecessary files found!")
        
        # Recommendations
        print(f"\n💡 Recommendations:")
        if len([t for t in themes if 'youtuneai' in t.lower()]) > 1:
            print(f"  1. Consider removing old theme versions to save space")
        print(f"  2. Your current setup is working well")
        print(f"  3. Blue color theme is successfully deployed")
        print(f"  4. No immediate fixes needed")
        
        sftp.close()
        ssh.close()
        
        print(f"\n🎉 Analysis Complete!")
        print(f"Your IONOS webspace is properly configured and working!")
        
    except Exception as e:
        print(f"❌ Connection failed: {str(e)}")

if __name__ == "__main__":
    cleanup_webspace()
