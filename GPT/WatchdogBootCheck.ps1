# ⛓️ PowerBoot Stealth Watchdog – Boss Mode 🔒
Start-Transcript -Path "$env:USERPROFILE\Desktop\BootScan_$(Get-Date -Format yyyy-MM-dd_HH-mm-ss).log" -Append

Write-Host "`n🚨 BOSS MODE BOOT SCAN ENGAGED" -ForegroundColor Red

# 🔍 Check for hidden startup scripts
$profiles = @(
    "$PROFILE", 
    "$env:ProgramData\Microsoft\Windows\Start Menu\Programs\Startup",
    "$env:APPDATA\Microsoft\Windows\Start Menu\Programs\Startup",
    "HKLM:\Software\Microsoft\Windows\CurrentVersion\Run", 
    "HKCU:\Software\Microsoft\Windows\CurrentVersion\Run"
)

foreach ($item in $profiles) {
    Write-Host "`n🔎 Scanning: $item" -ForegroundColor Yellow
    try {
        Get-Item $item -ErrorAction SilentlyContinue | Format-List -Force
    } catch { Write-Warning "⚠️ Could not access $item" }
}

# 🛡️ Detect known malicious patterns in profile or startup
$bootScripts = @(
    "$env:USERPROFILE\Documents\PowerShell\Microsoft.PowerShell_profile.ps1",
    "$env:ProgramData\Microsoft\Windows\Start Menu\Programs\Startup\*",
    "$env:APPDATA\Microsoft\Windows\Start Menu\Programs\Startup\*"
)
foreach ($script in $bootScripts) {
    if (Test-Path $script) {
        Write-Host "`n🔬 Checking $script for injections..." -ForegroundColor Cyan
        Get-Content $script | Select-String "Invoke|IEX|FromBase64|token|curl|http|proxy|lambda|script|flask|fastapi" -AllMatches
    }
}

# 📡 Check open ports and hidden proxies
Write-Host "`n🌐 Checking for open ports and proxies..." -ForegroundColor Cyan
netstat -ano | Select-String "0.0.0.0:|LISTENING"

# 🔧 Firewall rules check
Write-Host "`n🧱 Checking inbound firewall rules..." -ForegroundColor Cyan
Get-NetFirewallRule -Direction Inbound -Enabled True | Select Name, Action, Program

# 🕵️ Suspicious Scheduled Tasks
Write-Host "`n🗓️ Scanning scheduled tasks for stealth..." -ForegroundColor Cyan
Get-ScheduledTask | Where-Object {$_.TaskPath -notlike '\Microsoft\*'} | Format-Table TaskName, TaskPath, State

#
