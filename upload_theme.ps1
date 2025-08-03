# PowerShell SFTP Upload Script for YouTuneAI Theme
# This script uploads the theme to the web server

Write-Host "YouTuneAI Theme Upload Script" -ForegroundColor Green
Write-Host "=============================" -ForegroundColor Green

try {
    # Check if WinSCP is available
    $winscpPath = "C:\Program Files (x86)\WinSCP\WinSCP.com"
    if (Test-Path $winscpPath) {
        Write-Host "Using WinSCP for upload..." -ForegroundColor Yellow
        
        # Create WinSCP script
        $scriptContent = @"
open sftp://a104257:Gabby3000!!!@access-5017098454.webspace-host.com:22
cd /wp-content/themes/
put -filemask=*.*|*.tmp wp-theme-youtuneai/ youtuneai/
chmod 755 youtuneai
ls
exit
"@
        
        $scriptPath = ".\winscp_upload.txt"
        $scriptContent | Out-File -FilePath $scriptPath -Encoding ASCII
        
        # Execute WinSCP script
        & $winscpPath /script=$scriptPath
        
        Remove-Item $scriptPath
        Write-Host "Theme uploaded successfully via WinSCP!" -ForegroundColor Green
    }
    else {
        Write-Host "WinSCP not found. Attempting manual SFTP..." -ForegroundColor Yellow
        
        # Alternative method using built-in tools
        Write-Host "Please manually upload the wp-theme-youtuneai folder to:" -ForegroundColor Cyan
        Write-Host "Server: access-5017098454.webspace-host.com" -ForegroundColor White
        Write-Host "Port: 22" -ForegroundColor White
        Write-Host "Username: a104257" -ForegroundColor White
        Write-Host "Password: Gabby3000!!!" -ForegroundColor White
        Write-Host "Upload to: /wp-content/themes/" -ForegroundColor White
    }
}
catch {
    Write-Host "Error during upload: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Manual upload instructions:" -ForegroundColor Yellow
    Write-Host "1. Use FileZilla, WinSCP, or similar SFTP client" -ForegroundColor White
    Write-Host "2. Connect to: access-5017098454.webspace-host.com:22" -ForegroundColor White
    Write-Host "3. Username: a104257" -ForegroundColor White
    Write-Host "4. Password: Gabby3000!!!" -ForegroundColor White
    Write-Host "5. Upload wp-theme-youtuneai folder to /wp-content/themes/" -ForegroundColor White
}

Write-Host "`nTheme files are ready for upload!" -ForegroundColor Green
Write-Host "Location: $(Get-Location)\wp-theme-youtuneai" -ForegroundColor Cyan
