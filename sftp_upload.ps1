# SFTP Upload Script

# Check if Posh-SSH module is installed, if not, install it
if (-not (Get-Module -ListAvailable -Name Posh-SSH)) {
    Write-Host "Posh-SSH module not found. Installing..."
    Install-Module Posh-SSH -Scope CurrentUser -Force -SkipPublisherCheck
}

Import-Module Posh-SSH -Force


# SFTP Server Details
$sftpHost = "access-5017098454.webspace-host.com"
$sftpPort = 22
$sftpUser = "a917580"
$sftpPass = "Gabby3000!!!"

# File to Upload
$localFile = "c:\\YouTuneAiV2\\youtuneai_installation_package.zip"
$remotePath = "/youtuneai.com/youtuneai_installation_package.zip"

# Create a new SFTP session
$session = New-SSHSession -ComputerName $sftpHost -Port $sftpPort -Credential (New-Object System.Management.Automation.PSCredential($sftpUser, (ConvertTo-SecureString $sftpPass -AsPlainText -Force))) -AcceptKey

# Upload the file
Set-SFTPFile -SSHSession $session -LocalFile $localFile -RemoteFile $remotePath

# Close the SFTP session
Remove-SSHSession -SSHSession $session
