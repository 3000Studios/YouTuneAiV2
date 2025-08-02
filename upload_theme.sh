#!/bin/bash

# SFTP Upload Script for YouTuneAI Theme
# Server: access-5017098454.webspace-host.com
# User: a104257
# Password: Gabby3000!!!

echo "Starting SFTP upload to YouTuneAI server..."

# Create SFTP commands file
cat > sftp_commands.txt << EOF
cd /wp-content/themes/
put -r wp-theme-youtuneai
chmod -R 755 wp-theme-youtuneai
ls -la
quit
EOF

# Execute SFTP with commands
sshpass -p "Gabby3000!!!" sftp -P 22 -b sftp_commands.txt a104257@access-5017098454.webspace-host.com

echo "Upload completed!"

# Clean up
rm sftp_commands.txt
