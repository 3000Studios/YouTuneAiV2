# YouTuneAI Deployment Scripts

This directory contains all deployment-related scripts and utilities for the YouTuneAI project.

## Core Deployment Scripts

### `working_deployment_controller.py`
Main deployment controller that handles:
- Theme package creation
- SFTP upload to IONOS server
- SSL/DNS verification
- Deployment logging

**Usage:**
```bash
python src/deployment/working_deployment_controller.py
```

### `ai_controller.py`
Voice-controlled AI system that provides:
- Voice-to-text command processing
- AI-powered website modifications
- SFTP deployment automation
- WordPress integration

**Usage:**
```bash
python src/deployment/ai_controller.py
```

### `enhanced_ai_controller.py`
Advanced AI features including:
- WordPress REST API integration
- WooCommerce product management
- Webhook handling
- Plugin management

**Usage:**
```python
from src.deployment.enhanced_ai_controller import EnhancedYouTuneAIController
controller = EnhancedYouTuneAIController(wp_config)
```

## Utility Scripts

### `config_utils.py` ⭐ NEW
Shared configuration utilities to reduce code duplication:
- `get_sftp_config()` - Get SFTP configuration
- `get_wp_config()` - Get WordPress configuration
- `get_project_paths()` - Get standard project paths
- `create_log_file()` - Create timestamped log files
- `log_message()` - Unified logging function
- `ensure_directories()` - Ensure required directories exist

**Usage:**
```python
from src.deployment.config_utils import get_sftp_config, get_project_paths

# Get SFTP configuration
sftp = get_sftp_config("deployment")

# Get project paths
paths = get_project_paths()
theme_path = paths['theme_source']
```

### `direct_css_upload.py`
Quick CSS-only deployment for rapid styling updates.

**Usage:**
```bash
python src/deployment/direct_css_upload.py
```

### `server_explorer.py`
Server diagnostics and exploration tool.

**Usage:**
```bash
python src/deployment/server_explorer.py
```

### `ionos_webspace_analyzer.py`
Analyzes and cleans up IONOS webspace.

**Usage:**
```bash
python src/deployment/ionos_webspace_analyzer.py
```

### `find_wordpress.py`
Locates WordPress installations on the server.

**Usage:**
```bash
python src/deployment/find_wordpress.py
```

## Mobile Chat Monitors

### `mobile_chat_monitor.py`
Full-featured mobile chat monitoring system.

### `simple_mobile_chat_monitor.py`
Simplified version of the mobile chat monitor.

## Additional Tools

### `instant_deploy.py`
Quick deployment script for rapid iterations.

### `root_override.py`
Direct root deployment system.

## Configuration

All scripts support configuration via:
1. Environment variables (recommended)
2. `.env` or `secrets.env` file
3. Hardcoded defaults (fallback)

### Environment Variables

```bash
# SFTP Configuration
SFTP_HOST=access-5017098454.webspace-host.com
SFTP_PORT=22
SFTP_USERNAME=a917580
SFTP_PASSWORD=your_password
SFTP_REMOTE_PATH=clickandbuilds/YouTuneAi/wp-content/themes/wp-theme-youtuneai

# WordPress Configuration
WP_SITE_URL=https://youtuneai.com
WP_API_URL=https://youtuneai.com/wp-json/wp/v2/
WP_ADMIN_USER=Admin
WP_ADMIN_PASS=your_password
NOTIFICATION_EMAIL=mr.jwswain@gmail.com
WEBHOOK_SECRET=youtuneai_webhook_2025

# OpenAI Configuration
OPENAI_API_KEY=your_openai_key
```

## Best Practices

### Using config_utils

For new scripts or when refactoring existing scripts, use `config_utils.py` to:
1. Reduce configuration duplication
2. Standardize paths across scripts
3. Ensure consistent logging
4. Simplify environment variable handling

**Example:**
```python
from src.deployment.config_utils import (
    get_sftp_config, 
    get_project_paths, 
    create_log_file, 
    log_message
)

# Setup
sftp_config = get_sftp_config("deployment")
paths = get_project_paths()
log_file = create_log_file("my_script")

# Use in your script
log_message(log_file, "Starting deployment...")
```

## Logging

All deployment scripts create timestamped logs in the `logs/` directory:
- Format: `<script_name>_YYYYMMDD_HHMMSS.log`
- Location: `<workspace>/logs/`
- Level: INFO, WARNING, ERROR

## Dependencies

Required Python packages (see `requirements.txt`):
- `paramiko` - SFTP/SSH connections
- `requests` - HTTP/API requests
- `openai` - AI integration
- `python-dotenv` - Environment variables
- And others...

## Support

Created by Mr. Swain (3000Studios)  
Copyright (c) 2025
