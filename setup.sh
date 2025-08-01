#!/bin/bash
# YouTuneAI Quick Setup Script
# Run this to quickly set up the entire system

echo "🚀 YouTuneAI Quick Setup Starting..."

# Check if Python is installed
if ! command -v python &> /dev/null; then
    echo "❌ Python is not installed. Please install Python 3.8+ first."
    exit 1
fi

echo "✅ Python found"

# Check if pip is available
if ! command -v pip &> /dev/null; then
    echo "❌ pip is not installed. Please install pip first."
    exit 1
fi

echo "✅ pip found"

# Install Python dependencies
echo "📦 Installing Python dependencies..."
pip install -r requirements.txt

if [ $? -eq 0 ]; then
    echo "✅ Dependencies installed successfully"
else
    echo "❌ Failed to install dependencies"
    exit 1
fi

# Check if .env file exists
if [ ! -f ".env" ]; then
    echo "⚠️  .env file not found. Please create one from .env.example"
    echo "🔧 Required environment variables:"
    echo "   - OPENAI_API_KEY"
    echo "   - SFTP_HOST, SFTP_USERNAME, SFTP_PASSWORD"
    echo "   - WP_SITE_URL"
fi

# Create necessary directories
echo "📁 Creating asset directories..."
mkdir -p wp-theme-youtuneai/assets/images
mkdir -p wp-theme-youtuneai/assets/video
mkdir -p wp-theme-youtuneai/assets/audio
mkdir -p wp-theme-youtuneai/assets/css

echo "✅ Asset directories created"

# Set permissions (if on Unix-like system)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    chmod +x ai_controller.py
    echo "✅ Permissions set"
fi

echo ""
echo "🎉 YouTuneAI Setup Complete!"
echo ""
echo "📋 Next Steps:"
echo "1. Configure your .env file with API keys"
echo "2. Upload wp-theme-youtuneai/ to your WordPress /wp-content/themes/ directory"
echo "3. Activate the theme in WordPress admin"
echo "4. Run: python ai_controller.py"
echo ""
echo "🎤 Voice Commands Examples:"
echo '   - "Change background video to space theme"'
echo '   - "Add new product Gaming Headset for $99"'
echo '   - "Update homepage title to Welcome to AI"'
echo ""
echo "🚀 Ready to rock! Just say the word and watch your site transform."
