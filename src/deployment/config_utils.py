#!/usr/bin/env python3
"""
Shared configuration utilities for YouTuneAI deployment scripts
Centralizes common configuration patterns to reduce code duplication

Copyright (c) 2025 Mr. Swain (3000Studios)
"""

import os
from typing import Dict, Any, Optional
from pathlib import Path
from datetime import datetime

# Optional: Load environment variables if dotenv is available
try:
    from dotenv import load_dotenv
    load_dotenv('secrets.env')
except ImportError:
    pass  # dotenv not installed, will use os.getenv defaults


def get_sftp_config(config_type: str = "default") -> Dict[str, Any]:
    """
    Get SFTP configuration based on type
    
    Args:
        config_type: Type of configuration ("default", "ai_controller", or "deployment")
    
    Returns:
        Dictionary containing SFTP configuration
    """
    configs = {
        "default": {
            'host': os.getenv('SFTP_HOST', 'access-5017098454.webspace-host.com'),
            'port': int(os.getenv('SFTP_PORT', '22')),
            'username': os.getenv('SFTP_USERNAME', 'a917580'),
            'password': os.getenv('SFTP_PASSWORD', 'Gabby3000!!!'),
            'remote_path': os.getenv('SFTP_REMOTE_PATH', 'clickandbuilds/YouTuneAi/wp-content/themes/wp-theme-youtuneai')
        },
        "ai_controller": {
            'host': os.getenv('SFTP_HOST', 'access-5017098454.webspace-host.com'),
            'username': os.getenv('SFTP_USERNAME', 'a132096'),
            'password': os.getenv('SFTP_PASSWORD', 'Gabby3000!!!'),
            'port': int(os.getenv('SFTP_PORT', '22')),
            'remote_path': os.getenv('SFTP_REMOTE_PATH', '/wp-content/themes/youtuneai/')
        },
        "deployment": {
            'host': 'access-5017098454.webspace-host.com',
            'port': 22,
            'username': 'a917580',
            'password': 'Gabby3000!!!',
            'remote_path': 'clickandbuilds/YouTuneAi/wp-content/themes/wp-theme-youtuneai'
        }
    }
    
    return configs.get(config_type, configs["default"])


def get_wp_config() -> Dict[str, Optional[str]]:
    """
    Get WordPress configuration from environment variables
    
    Returns:
        Dictionary containing WordPress configuration
    """
    return {
        'site_url': os.getenv('WP_SITE_URL', 'https://youtuneai.com'),
        'rest_api_url': os.getenv('WP_API_URL', 'https://youtuneai.com/wp-json/wp/v2/'),
        'admin_user': os.getenv('WP_ADMIN_USER', 'Admin'),
        'admin_pass': os.getenv('WP_ADMIN_PASS', 'Gabby3000!!!'),
        'admin_email': os.getenv('NOTIFICATION_EMAIL', 'mr.jwswain@gmail.com'),
        'app_password': None,
        'webhook_secret': os.getenv('WEBHOOK_SECRET', 'youtuneai_webhook_2025')
    }


def get_project_paths() -> Dict[str, Path]:
    """
    Get standard project paths
    
    Returns:
        Dictionary containing common project paths
    """
    workspace_path = Path(__file__).parent.parent.parent
    
    return {
        'workspace': workspace_path,
        'src': workspace_path / "src",
        'deployment': workspace_path / "src" / "deployment",
        'theme_source': workspace_path / "src" / "theme" / "deployment-ready" / "wp-theme-youtuneai",
        'build': workspace_path / "build",
        'logs': workspace_path / "logs",
        'config': workspace_path / "config"
    }


def create_log_file(prefix: str = "deployment") -> Path:
    """
    Create a timestamped log file path
    
    Args:
        prefix: Prefix for the log file name
    
    Returns:
        Path object for the log file
    """
    paths = get_project_paths()
    logs_dir = paths['logs']
    logs_dir.mkdir(exist_ok=True)
    
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    return logs_dir / f"{prefix}_{timestamp}.log"


def log_message(log_file: Path, message: str, level: str = "INFO") -> None:
    """
    Log a message to file and console
    
    Args:
        log_file: Path to the log file
        message: Message to log
        level: Log level (INFO, WARNING, ERROR, etc.)
    """
    timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    log_entry = f"[{timestamp}] [{level}] {message}"
    
    print(log_entry)
    
    with open(log_file, 'a', encoding='utf-8') as f:
        f.write(log_entry + '\n')


def ensure_directories() -> None:
    """Ensure all required project directories exist"""
    paths = get_project_paths()
    
    for path in ['build', 'logs', 'config']:
        paths[path].mkdir(exist_ok=True)
