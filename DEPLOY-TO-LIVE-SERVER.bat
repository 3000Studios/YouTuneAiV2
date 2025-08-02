@echo off
echo ====================================================
echo YouTuneAI Pro Theme v3.0 - Live Deployment Script
echo ====================================================
echo.
echo Server: access-5017098454.webspace-host.com
echo Username: a2486428
echo Theme Package: YouTuneAI-Pro-Theme-v3.0-COMPLETE.zip
echo.
echo DEPLOYMENT STEPS:
echo 1. Extract theme zip file
echo 2. Connect to server via SFTP/FTP client
echo 3. Upload wp-theme-youtuneai folder to /wp-content/themes/
echo 4. Set permissions: 755 for folders, 644 for files
echo 5. Activate theme in WordPress admin
echo.
echo Opening FileZilla configuration...
echo.

REM Create FileZilla session file
echo ^<?xml version="1.0" encoding="UTF-8"?^> > filezilla-session.xml
echo ^<FileZilla3^> >> filezilla-session.xml
echo   ^<Servers^> >> filezilla-session.xml
echo     ^<Server^> >> filezilla-session.xml
echo       ^<Host^>access-5017098454.webspace-host.com^</Host^> >> filezilla-session.xml
echo       ^<Port^>22^</Port^> >> filezilla-session.xml
echo       ^<Protocol^>1^</Protocol^> >> filezilla-session.xml
echo       ^<Type^>0^</Type^> >> filezilla-session.xml
echo       ^<User^>a2486428^</User^> >> filezilla-session.xml
echo       ^<Pass encoding="base64"^>R2FiYnkzMDAwISEh^</Pass^> >> filezilla-session.xml
echo       ^<Name^>YouTuneAI Server^</Name^> >> filezilla-session.xml
echo     ^</Server^> >> filezilla-session.xml
echo   ^</Servers^> >> filezilla-session.xml
echo ^</FileZilla3^> >> filezilla-session.xml

echo FileZilla configuration created: filezilla-session.xml
echo.
echo MANUAL UPLOAD INSTRUCTIONS:
echo 1. Open FileZilla
echo 2. Import the configuration file: filezilla-session.xml
echo 3. Connect to "YouTuneAI Server"
echo 4. Navigate to /wp-content/themes/ on the server
echo 5. Upload the wp-theme-youtuneai folder
echo 6. Set folder permissions to 755
echo 7. Activate theme in WordPress admin
echo.
echo Press any key to extract theme files for upload...
pause

REM Extract the theme package
powershell -Command "Expand-Archive -Path 'YouTuneAI-Pro-Theme-v3.0-COMPLETE.zip' -DestinationPath 'deployment-ready' -Force"

echo.
echo Theme extracted to: deployment-ready\wp-theme-youtuneai
echo Ready for upload to server!
echo.
pause
