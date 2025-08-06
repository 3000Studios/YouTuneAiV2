# YouTuneAI Pro Theme - WinSCP PowerShell Deployment Script
# =========================================================

Write-Host "YouTuneAI Pro Theme v3.0 - WinSCP Deployment" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Set strict error handling
$ErrorActionPreference = "Stop"

# === CONFIG ===
$WinSCP_URL = "https://winscp.net/download/WinSCP-6.3.3-Portable.zip"
$WinSCP_ZIP = "$PSScriptRoot\WinSCP.zip"
$WinSCP_DIR = "$PSScriptRoot\WinSCP-Portable"
$WinSCP_EXE = "$WinSCP_DIR\WinSCP.com"
$SCRIPT_FILE = "$PSScriptRoot\winscp-script.txt"
$UPLOAD_LOG = "$PSScriptRoot\upload.log"

$SERVER_HOST = "access-5017098454.webspace-host.com"
$PORT = 22
$USER = "a132096"
$PASS = "Gabby3000!!!"
$LOCAL_DIR = "$PSScriptRoot\deployment-ready\wp-theme-youtuneai\*"
$REMOTE_DIR = "/wp-content/themes/youtuneai/"

# === DOWNLOAD WINSCP ===
if (!(Test-Path $WinSCP_EXE)) {
    Write-Host "`n🚀 Downloading WinSCP Portable..." -ForegroundColor Cyan
    Invoke-WebRequest -Uri $WinSCP_URL -OutFile $WinSCP_ZIP
    Expand-Archive -Path $WinSCP_ZIP -DestinationPath $WinSCP_DIR -Force
    Remove-Item $WinSCP_ZIP -Force
    Write-Host "✅ WinSCP ready." -ForegroundColor Green
}

# === BUILD WINSCP SCRIPT ===
@"
open sftp://$($USER):`"$($PASS)`"@$($SERVER_HOST):$($PORT) -hostkey=* 
lcd "$($LOCAL_DIR.TrimEnd('\*'))"
cd "$REMOTE_DIR"
put * -delete
exit
"@ | Set-Content -Encoding ASCII $SCRIPT_FILE

# === EXECUTE UPLOAD ===
Write-Host "`n📤 Uploading theme to server..." -ForegroundColor Yellow
& "$WinSCP_EXE" /script="$SCRIPT_FILE" /log="$UPLOAD_LOG"

if ($LASTEXITCODE -eq 0) {
    Write-Host "`nUpload successful!" -ForegroundColor Green
}
else {
    Write-Host "`nUpload failed. Check log: $UPLOAD_LOG" -ForegroundColor Red
}
