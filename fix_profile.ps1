# Fix PowerShell Profile Errors
# Comment out problematic lines in the profile.ps1 file

# Locate the profile file
$profilePath = "$HOME\Documents\WindowsPowerShell\profile.ps1"

# Check if the profile file exists
if (Test-Path $profilePath) {
    # Read the profile file
    $profileContent = Get-Content $profilePath

    # Comment out problematic lines
    $fixedContent = $profileContent -replace 'Import-Module oh-my-posh', '# Import-Module oh-my-posh'
    $fixedContent = $fixedContent -replace 'Set-PSReadLineOption -PredictionSource History', '# Set-PSReadLineOption -PredictionSource History'
    $fixedContent = $fixedContent -replace 'Set-PoshPrompt -Theme Paradox', '# Set-PoshPrompt -Theme Paradox'

    # Write the fixed content back to the profile file
    $fixedContent | Set-Content $profilePath

    Write-Host "Profile file fixed successfully." -ForegroundColor Green
}
else {
    Write-Host "Profile file not found." -ForegroundColor Red
}
