# BootScanner.ps1 - BOSS MAN J's BOOT-TIME SURVEILLANCE NUKE ⚔️

$timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"
$logPath = "C:\BootScan_Logs"
$logFile = "$logPath\BootScan_$timestamp.log"
New-Item -ItemType Directory -Path $logPath -Force | Out-Null

function Log($text) {
    Add-Content -Path $logFile -Value $text
    Write-Host $text
}

Log "`n==== 🔥 BOOT-TIME SYSTEM SCAN INITIATED @ $timestamp ===="

# 🔍 Startup folders
Log "`n>> Startup Folders"
Get-ChildItem "C:\ProgramData\Microsoft\Windows\Start Menu\Programs\Startup", "$env:APPDATA\Microsoft\Windows\Start Menu\Programs\Startup" -Recurse -ErrorAction SilentlyContinue | ForEach-Object { Log $_.FullName }

# 🔎 Registry autoruns
Log "`n>> Registry Run Keys"
Get-ItemProperty "HKLM:\Software\Microsoft\Windows\CurrentVersion\Run" -ErrorAction SilentlyContinue | ForEach-Object { $_.PSObject.Properties | ForEach-Object { Log "$($_.Name): $($_.Value)" } }
Get-ItemProperty "HKCU:\Software\Microsoft\Windows\CurrentVersion\Run" -ErrorAction SilentlyContinue | ForEach-Object { $_.PSObject.Properties | ForEach-Object { Log "$($_.Name): $($_.Value)" } }

# 🕷 Scheduled tasks
Log "`n>> Scheduled Tasks"
Get-ScheduledTask | ForEach-Object { Log $_.TaskName }

# 🔐 Firewall rules
Log "`n>> Firewall Rules"
Get-NetFirewallRule | Where-Object { $_.Enabled -eq "True" } | ForEach-Object { Log "$($_.DisplayName) [$($_.Direction)]" }

# 🕵️ Suspicious profile injections
Log "`n>> Suspicious Profile Scripts"
$profiles = @("$PROFILE", "$PROFILE.AllUsersAllHosts", "$PROFILE.AllUsersCurrentHost", "$PROFILE.CurrentUserAllHosts")
foreach ($profile in $profiles) {
    if (Test-Path $profile) {
        $hits = Get-Content $profile | Select-String -Pattern "iex|Invoke|FromBase64String|proxy|curl|token|AI|script|chat" -AllMatches
        if ($hits) {
            Log "⚠️ $profile - Suspicious content:"
            $hits | ForEach-Object { Log $_.Line }
        } else {
            Log "✅ $profile - Clean"
        }
    }
}

# 🧠 Listening ports
Log "`n>> Listening Ports"
netstat -ano | Select-String "LISTENING" | ForEach-Object { Log $_ }

Log "`n==== ✅ SCAN COMPLETE. LOG SAVED TO $logFile ===="
