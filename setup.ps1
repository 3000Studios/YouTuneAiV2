# YouTuneAI PowerShell Setup Script
# Run this to quickly set up the entire system on Windows

Write-Host "🚀 YouTuneAI Quick Setup Starting..." -ForegroundColor Cyan

# Check if Python is installed
try {
    $pythonVersion = python --version 2>&1
    Write-Host "✅ Python found: $pythonVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ Python is not installed. Please install Python 3.8+ first." -ForegroundColor Red
    Write-Host "   Download from: https://www.python.org/downloads/" -ForegroundColor Yellow
    exit 1
}

# Check if pip is available
try {
    $pipVersion = pip --version 2>&1
    Write-Host "✅ pip found: $pipVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ pip is not installed. Please install pip first." -ForegroundColor Red
    exit 1
}

# Install Python dependencies
Write-Host "📦 Installing Python dependencies..." -ForegroundColor Yellow
try {
    pip install -r requirements.txt
    Write-Host "✅ Dependencies installed successfully" -ForegroundColor Green
} catch {
    Write-Host "❌ Failed to install dependencies" -ForegroundColor Red
    Write-Host "   Error: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Check if .env file exists
if (!(Test-Path ".env")) {
    Write-Host "⚠️  .env file not found. Creating from template..." -ForegroundColor Yellow
    Write-Host "🔧 Please configure these environment variables:" -ForegroundColor Cyan
    Write-Host "   - OPENAI_API_KEY (from OpenAI)" -ForegroundColor White
    Write-Host "   - SFTP_HOST, SFTP_USERNAME, SFTP_PASSWORD (your hosting)" -ForegroundColor White
    Write-Host "   - WP_SITE_URL (your WordPress site)" -ForegroundColor White
} else {
    Write-Host "✅ .env file found" -ForegroundColor Green
}

# Create necessary directories
Write-Host "📁 Creating asset directories..." -ForegroundColor Yellow
$directories = @(
    "wp-theme-youtuneai\assets\images",
    "wp-theme-youtuneai\assets\video", 
    "wp-theme-youtuneai\assets\audio",
    "wp-theme-youtuneai\assets\css"
)

foreach ($dir in $directories) {
    if (!(Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
    }
}
Write-Host "✅ Asset directories created" -ForegroundColor Green

# Test AI controller import
Write-Host "🧪 Testing AI controller..." -ForegroundColor Yellow
try {
    python -c "from ai_controller import YouTuneAIController; print('AI Controller import successful')" 2>&1 | Out-Null
    Write-Host "✅ AI Controller ready" -ForegroundColor Green
} catch {
    Write-Host "⚠️  AI Controller test failed - check your .env configuration" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "🎉 YouTuneAI Setup Complete!" -ForegroundColor Green -BackgroundColor Black
Write-Host ""
Write-Host "📋 Next Steps:" -ForegroundColor Cyan
Write-Host "1. 🔑 Configure your .env file with API keys" -ForegroundColor White
Write-Host "2. 📁 Upload wp-theme-youtuneai/ to your WordPress /wp-content/themes/ directory" -ForegroundColor White
Write-Host "3. ⚡ Activate the theme in WordPress admin" -ForegroundColor White
Write-Host "4. 🎤 Run: python ai_controller.py" -ForegroundColor White
Write-Host ""
Write-Host "🎯 Quick Start Commands:" -ForegroundColor Cyan
Write-Host "   • Test Setup: python -c 'from ai_controller import YouTuneAIController; print(\"Ready!\")'" -ForegroundColor Gray
Write-Host "   • Start Voice Control: python ai_controller.py" -ForegroundColor Gray
Write-Host "   • Deploy Theme: python ai_controller.py --deploy-all" -ForegroundColor Gray
Write-Host ""
Write-Host "🎤 Voice Commands Examples:" -ForegroundColor Magenta
Write-Host '   • "Change background video to space theme"' -ForegroundColor White
Write-Host '   • "Add new product Gaming Headset for $99"' -ForegroundColor White
Write-Host '   • "Update homepage title to Welcome to AI"' -ForegroundColor White
Write-Host '   • "Change primary color to purple"' -ForegroundColor White
Write-Host ""
Write-Host "🚀 Ready to rock! Just say the word and watch your site transform." -ForegroundColor Green

# Optional: Open VS Code if available
if (Get-Command code -ErrorAction SilentlyContinue) {
    Write-Host ""
    $openVSCode = Read-Host "📝 Open VS Code workspace? (y/n)"
    if ($openVSCode -eq 'y' -or $openVSCode -eq 'Y') {
        code YouTuneAiV2.code-workspace
        Write-Host "✅ VS Code opened with YouTuneAI workspace" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "💬 Need help? Check README.md or run 'python ai_controller.py --help'" -ForegroundColor Cyan
