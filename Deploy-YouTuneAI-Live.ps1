# YouTuneAI Pro Theme v3.0 - PowerShell Deployment Script
# ========================================================

Write-Host "YouTuneAI Pro Theme v3.0 - Live Deployment" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Server Configuration
$ServerHost = "access-5017098454.webspace-host.com"
$Username = "a2486428"
$Password = "Gabby3000!!!"
$Port = 22
$RemotePath = "/wp-content/themes/"

Write-Host "Server Details:" -ForegroundColor Yellow
Write-Host "Host: $ServerHost"
Write-Host "Port: $Port"
Write-Host "Username: $Username"
Write-Host "Remote Path: $RemotePath"
Write-Host ""

# Extract theme package
Write-Host "Extracting theme package..." -ForegroundColor Green
if (Test-Path "deployment-ready") {
    Remove-Item "deployment-ready" -Recurse -Force
}
Expand-Archive -Path "YouTuneAI-Pro-Theme-v3.0-COMPLETE.zip" -DestinationPath "deployment-ready" -Force

Write-Host "Theme extracted successfully!" -ForegroundColor Green
Write-Host ""

# Create SFTP commands file
$SftpCommands = @"
cd $RemotePath
put -r deployment-ready/wp-theme-youtuneai
chmod 755 wp-theme-youtuneai
cd wp-theme-youtuneai
chmod 644 *.php *.css *.js *.txt *.md
cd js
chmod 644 *.js
cd ../assets
chmod 644 *.*
quit
"@

$SftpCommands | Out-File -FilePath "sftp-commands.txt" -Encoding ASCII

Write-Host "SFTP commands created: sftp-commands.txt" -ForegroundColor Green
Write-Host ""

Write-Host "DEPLOYMENT OPTIONS:" -ForegroundColor Magenta
Write-Host "==================" -ForegroundColor Magenta
Write-Host ""
Write-Host "OPTION 1 - Automatic SFTP Upload:" -ForegroundColor Yellow
Write-Host "sftp -b sftp-commands.txt $Username@$ServerHost" -ForegroundColor White
Write-Host ""
Write-Host "OPTION 2 - Manual FileZilla Upload:" -ForegroundColor Yellow
Write-Host "1. Open FileZilla"
Write-Host "2. Host: $ServerHost"
Write-Host "3. Username: $Username"
Write-Host "4. Password: $Password"
Write-Host "5. Port: $Port"
Write-Host "6. Upload folder: deployment-ready\wp-theme-youtuneai"
Write-Host "7. Target location: /wp-content/themes/"
Write-Host ""

Write-Host "POST-DEPLOYMENT CHECKLIST:" -ForegroundColor Magenta
Write-Host "==========================" -ForegroundColor Magenta
Write-Host "✓ Upload theme folder to /wp-content/themes/"
Write-Host "✓ Set folder permissions to 755"
Write-Host "✓ Set file permissions to 644"
Write-Host "✓ Login to WordPress admin"
Write-Host "✓ Go to Appearance > Themes"
Write-Host "✓ Activate 'YouTuneAI Pro'"
Write-Host "✓ Test voice control (requires HTTPS)"
Write-Host "✓ Verify video backgrounds are loading"
Write-Host "✓ Test all CodePen integrations"
Write-Host ""

Write-Host "Theme Features Included:" -ForegroundColor Green
Write-Host "• Voice Control System with Speech Recognition"
Write-Host "• Background Video System (Homepage + Navigation)"
Write-Host "• 10+ CodePen Integrations (Gallery, 3D Carousel, etc.)"
Write-Host "• Admin Dashboard v2.1"
Write-Host "• Avatar Creator Studio"
Write-Host "• Live Streaming Hub"
Write-Host "• Payment Integration (PayPal, Cash App, Crypto)"
Write-Host "• Mobile Responsive Design"
Write-Host "• SEO Optimized"
Write-Host ""

$choice = Read-Host "Press 'A' to attempt automatic upload, or any key to continue with manual instructions"

if ($choice -eq 'A' -or $choice -eq 'a') {
    Write-Host "Attempting automatic SFTP upload..." -ForegroundColor Yellow
    try {
        $process = Start-Process -FilePath "sftp" -ArgumentList "-b", "sftp-commands.txt", "$Username@$ServerHost" -Wait -PassThru
        if ($process.ExitCode -eq 0) {
            Write-Host "Upload completed successfully!" -ForegroundColor Green
        }
        else {
            Write-Host "Upload failed. Please use manual method." -ForegroundColor Red
        }
    }
    catch {
        Write-Host "Error during upload: $($_.Exception.Message)" -ForegroundColor Red
        Write-Host "Please use manual upload method with FileZilla." -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "Deployment package ready in: deployment-ready\wp-theme-youtuneai" -ForegroundColor Cyan
Write-Host "🚀 YouTuneAI Pro Theme v3.0 is ready for live deployment!" -ForegroundColor Green
