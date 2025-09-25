#!/bin/bash
# YouTuneAI Commands Log - Exact commands used for setup and build

# Build Report Generation - 2025-09-25 14:49:24 UTC
echo "=== YouTuneAI Setup and Build Commands ==="

# System requirements check
python3 --version
node --version
npm --version
pip --version

# Repository setup
cd /home/runner/work/YouTuneAiV2/YouTuneAiV2

# Fix requirements.txt sqlite3 issue
# sed -i 's/sqlite3/# sqlite3 is built-in to Python, no need to install/' requirements.txt

# Complete automated setup
make setup

# Build all components
make build

# Generate documentation
make docs

# Create build report
make report

# Alternative manual setup commands (if make is not available):
# python3 setup.py
# pip install --user requests python-dotenv coloredlogs click watchdog
# cd youtuneai-theme && npm install
# cd youtuneai-theme && npm run build

# Test commands
make test
python3 -c "import sys; print('Python version:', sys.version)"
test -f youtuneai-theme/assets/css/dist/main.css && echo "Theme CSS built successfully"
test -f .env && echo "Environment config exists"

echo "=== Setup Complete ==="
echo "Next steps:"
echo "1. Edit .env with your API keys"
echo "2. Run: make dev"  
echo "3. Test: python3 working_deployment_controller.py"