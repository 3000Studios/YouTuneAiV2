# YouTuneAI Pro Theme - PuTTY PSFTP Deployment Script
# ====================================================

Write-Host "YouTuneAI Pro Theme v3.0 - PuTTY PSFTP Deployment" -ForegroundColor Cyan
Write-Host "===================================================" -ForegroundColor Cyan
Write-Host ""

# Check for PuTTY installation
$puttyPaths = @(
    "C:\Program Files\PuTTY\psftp.exe",
    "C:\Program Files (x86)\PuTTY\psftp.exe",
    "$env:USERPROFILE\AppData\Local\Programs\PuTTY\psftp.exe",
    "psftp.exe"
)

$psftpExe = $null
foreach ($path in $puttyPaths) {
    try {
        if (Get-Command $path -ErrorAction SilentlyContinue) {
            $psftpExe = $path
            break
        }
    }
    catch {
        # Continue to next path
    }
}

if (-not $psftpExe) {
    Write-Host "PuTTY not found. Please install PuTTY from https://putty.org" -ForegroundColor Red
    Write-Host "Or use the WinSCP deployment script instead: Deploy-WinSCP.ps1" -ForegroundColor Yellow
    exit 1
}

Write-Host "Using PSFTP: $psftpExe" -ForegroundColor Green
Write-Host ""

# Create PSFTP command script
$psftpScript = @"
cd /wp-content/themes/
put -r deployment-ready\wp-theme-youtuneai
mv wp-theme-youtuneai wp-theme-youtuneai-backup-$(Get-Date -Format 'yyyyMMdd')
mv deployment-ready\wp-theme-youtuneai wp-theme-youtuneai
chmod 755 wp-theme-youtuneai
cd wp-theme-youtuneai
chmod 644 *.php
chmod 644 *.css
chmod 644 *.js
chmod 644 *.txt
chmod 644 *.md
ls -la
quit
"@

$psftpScript | Out-File -FilePath "psftp-commands.txt" -Encoding ASCII

Write-Host "Connecting to server with PuTTY PSFTP..." -ForegroundColor Yellow
Write-Host "Server: access-5017098454.webspace-host.com:22" -ForegroundColor White
Write-Host "Username: a2486428" -ForegroundColor White
Write-Host ""

try {
    $process = Start-Process -FilePath $psftpExe -ArgumentList @(
        "-P", "22",
        "-pw", "Gabby3000!!!",
        "a2486428@access-5017098454.webspace-host.com",
        "-b", "psftp-commands.txt"
    ) -Wait -PassThru -NoNewWindow

    if ($process.ExitCode -eq 0) {
        Write-Host "🎉 DEPLOYMENT SUCCESSFUL!" -ForegroundColor Green
        Write-Host "=========================" -ForegroundColor Green
        Write-Host "✓ Theme uploaded successfully" -ForegroundColor Green
        Write-Host "✓ Previous theme backed up" -ForegroundColor Green
        Write-Host "✓ File permissions set" -ForegroundColor Green
        Write-Host ""
        Write-Host "🚀 YouTuneAI Pro theme is now LIVE!" -ForegroundColor Cyan
    }
    else {
        Write-Host "❌ Upload failed with exit code: $($process.ExitCode)" -ForegroundColor Red
    }
}
catch {
    Write-Host "Error during upload: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "NEXT STEPS:" -ForegroundColor Magenta
Write-Host "1. Login to WordPress admin" -ForegroundColor White
Write-Host "2. Activate YouTuneAI Pro theme" -ForegroundColor White
Write-Host "3. Test all features" -ForegroundColor White
