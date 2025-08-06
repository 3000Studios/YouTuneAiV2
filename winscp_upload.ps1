# WinSCP Upload Script

# SFTP Server Details
$sftpHost = "access-5017098454.webspace-host.com"
$sftpPort = 22
$sftpUser = "a917580"
$sftpPass = "Gabby3000!!!"

# File to Upload
$localFile = "c:\YouTuneAiV2\youtuneai_installation_package.zip"
$remotePath = "/youtuneai.com/youtuneai_installation_package.zip"

# Path to WinSCP executable
$winscpPath = "C:\Program Files (x86)\WinSCP\WinSCP.com"

# Check if WinSCP.com exists
if (-not (Test-Path $winscpPath)) {
    Write-Error "WinSCP.com not found at $winscpPath. Please ensure WinSCP is installed in the default location or update the path in this script."
    exit
}

# WinSCP command
$command = "& `"$winscpPath`" /command `"open sftp://$sftpUser`:`$sftpPass@$sftpHost`:$sftpPort`" `"put `"`"$localFile`"`" `"`"$remotePath`"`"`" `"exit`""

# Execute the command
Write-Host "Executing WinSCP command..."
Invoke-Expression $command
Write-Host "Upload script finished."
