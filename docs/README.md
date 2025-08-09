# YouTuneAI Pro v5.0

Revolutionary AI-powered WordPress theme with voice control, streaming, gaming, and comprehensive monetization features.

## Quick Start

1. **Setup Environment**
   ```bash
   pip install -r requirements.txt
   cp config/.env.example config/.env
   # Edit config/.env with your API keys
   ```

2. **Deploy Theme**
   - Use VS Code task: "Deploy YouTuneAI Theme"
   - Or run: `python src/deployment/working_deployment_controller.py`

3. **Visit Your Site**
   - Live site: https://youtuneai.com

## Project Structure

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

## Development Workflow

### VS Code Tasks (Ctrl+Shift+P → "Tasks: Run Task")
- **Deploy YouTubeAI Theme** - Full deployment pipeline
- **Test Deployment Connection** - Test server connectivity
- **Upload CSS Only** - Quick CSS updates
- **Clean Project** - Remove junk files and organize

### Deployment Process
1. Theme files are packaged from `src/theme/deployment-ready/`
2. Package is created in `build/` directory
3. Files are uploaded via SFTP to IONOS server
4. DNS/SSL verification is performed
5. Results are logged in `logs/` directory

## Server Information

- **Live Site**: https://youtuneai.com
- **Server**: IONOS Webspace (access-5017098454.webspace-host.com)
- **WordPress Path**: clickandbuilds/YouTuneAi/
- **Active Theme**: wp-theme-youtuneai
- **SSL**: Valid until August 2026

## Theme Features

- **Voice Control Integration** - AI-powered voice commands
- **Blue Color Scheme** - Professional blue branding
- **Responsive Design** - Mobile-first approach
- **SEO Optimized** - Built-in SEO features
- **Monetization Ready** - E-commerce integration

## Troubleshooting

### Common Issues
1. **Deployment Fails**: Check server connectivity with test task
2. **CSS Not Updating**: Use "Upload CSS Only" task for quick updates
3. **Theme Not Active**: Verify WordPress admin panel

### Log Files
- All operations are logged in `logs/` directory
- Check latest log file for detailed error information

## Support

Created by Mr. Swain (3000Studios)
Copyright (c) 2025

## Version History

- **v5.0** - Blue theme, voice control, optimized deployment
- **v4.x** - Legacy versions (archived)
