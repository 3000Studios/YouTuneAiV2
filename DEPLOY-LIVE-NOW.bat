@echo off
echo ====================================================
echo YouTuneAI Pro Theme v3.0 - LIVE DEPLOYMENT
echo ====================================================
echo.
echo Server: access-5017098454.webspace-host.com
echo Username: a2486428
echo Password: Gabby3000!!!
echo.

REM Create WinSCP script file
echo open sftp://a2486428:Gabby3000!!!@access-5017098454.webspace-host.com:22 > winscp-script.txt
echo cd /wp-content/themes/ >> winscp-script.txt
echo put -transfer=binary deployment-ready\wp-theme-youtuneai wp-theme-youtuneai >> winscp-script.txt
echo chmod 755 wp-theme-youtuneai >> winscp-script.txt
echo cd wp-theme-youtuneai >> winscp-script.txt
echo chmod 644 *.php >> winscp-script.txt
echo chmod 644 *.css >> winscp-script.txt
echo chmod 644 *.js >> winscp-script.txt
echo chmod 644 *.txt >> winscp-script.txt
echo chmod 644 *.md >> winscp-script.txt
echo ls -la >> winscp-script.txt
echo exit >> winscp-script.txt

echo WinSCP script created: winscp-script.txt
echo.

REM Try to find WinSCP installation
set WINSCP_PATH=
if exist "C:\Program Files (x86)\WinSCP\WinSCP.com" set WINSCP_PATH=C:\Program Files (x86)\WinSCP\WinSCP.com
if exist "C:\Program Files\WinSCP\WinSCP.com" set WINSCP_PATH=C:\Program Files\WinSCP\WinSCP.com

if "%WINSCP_PATH%"=="" (
    echo WinSCP not found. Downloading portable version...
    powershell -Command "Invoke-WebRequest -Uri 'https://winscp.net/download/WinSCP-5.21.8-Portable.zip' -OutFile 'WinSCP-Portable.zip'"
    powershell -Command "Expand-Archive -Path 'WinSCP-Portable.zip' -DestinationPath 'WinSCP-Portable' -Force"
    set WINSCP_PATH=WinSCP-Portable\WinSCP.com
)

echo Using WinSCP: %WINSCP_PATH%
echo.
echo Uploading theme to live server...
echo.

"%WINSCP_PATH%" /script=winscp-script.txt /log=upload.log

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ====================================================
    echo 🎉 DEPLOYMENT SUCCESSFUL! 🎉
    echo ====================================================
    echo ✓ Theme uploaded to server successfully
    echo ✓ File permissions set correctly
    echo ✓ YouTuneAI Pro theme is now LIVE!
    echo.
    echo NEXT STEPS:
    echo 1. Login to your WordPress admin panel
    echo 2. Go to Appearance ^> Themes
    echo 3. Activate 'YouTuneAI Pro' theme
    echo 4. Test voice control ^(requires HTTPS^)
    echo 5. Verify video backgrounds are working
    echo.
    echo 🚀 Your site is ready!
) else (
    echo.
    echo ❌ Upload failed. Check upload.log for details.
    type upload.log
)

echo.
pause
