import os
import requests
import json
import subprocess

# ===== CONFIG =====
BLACKVAULT_API_KEY = "ac26822c69b34400b72f1fec1f41271c:ueBGcKNTTn52Jxqj5TlWBHaUQQVlp2LWIUSxv1z0Hbi-v-KZvY632TXGsa7mqb6KZ5Tx3_7-4MHoZ_MQ_i8XCA"
IONOS_API_BASE = "https://api.ionos.com/cloudapi/v6"
BLACKVAULT_EXPORT_FILE = "blackvault_secrets.json"
WORDPRESS_SITE = "https://youtuneai.com"
BOSS_PASSWORD = "Gabby3000!!!"

# ===== STEP 1: EXPORT BLACKVAULT =====
print("[*] Exporting BlackVault secrets...")
subprocess.run([
    "blackvault", "export", "--all", "--format=json",
    f">{BLACKVAULT_EXPORT_FILE}"
], shell=True)

# ===== STEP 2: RETRIEVE IONOS HOST/IP =====
print("[*] Getting IONOS server details...")
headers = {"Authorization": f"Bearer {BLACKVAULT_API_KEY}"}
resp = requests.get(f"{IONOS_API_BASE}/servers", headers=headers)
servers = resp.json()
server_ip = servers[0]["entities"]["nics"]["items"][0]["entities"]["ips"]["items"][0]["properties"]["ip"] if servers else None
server_name = servers[0]["properties"]["name"] if servers else None

print(f"[+] Server: {server_name} @ {server_ip}")

# ===== STEP 3: ENSURE SFTP/SSH ACCESS =====
print("[*] Checking for SSH keys...")
ssh_key_path = os.path.expanduser("~/.ssh/id_rsa")
if not os.path.exists(ssh_key_path):
    print("[*] Generating SSH keypair...")
    subprocess.run(["ssh-keygen", "-t", "rsa", "-b", "4096", "-N", "", "-f", ssh_key_path])

# TODO: Push public key to IONOS via API
# requests.post(f"{IONOS_API_BASE}/servers/{server_id}/sshkeys", headers=headers, json={...})

# ===== STEP 4: GET WORDPRESS REST API CREDS =====
print("[*] Fetching/Generating WordPress REST API credentials...")
# This requires WordPress application password API access
# You would run WP CLI:
# subprocess.run(["wp", "user", "application-password", "create", "BossMan", "--porcelain"], capture_output=True)

wp_api_key = "TO_BE_FETCHED"  # Placeholder until WP CLI command runs successfully

# ===== STEP 5: SAVE SFTP CONFIG =====
print("[*] Writing sftp.json for VS Code...")
sftp_config = {
    "name": "WordPress Live Server",
    "host": server_ip,
    "protocol": "sftp",
    "port": 22,
    "username": "YOUR_SFTP_USER",
    "privateKeyPath": ssh_key_path,
    "remotePath": "/public_html/",
    "uploadOnSave": True
}
with open(".vscode/sftp.json", "w") as f:
    json.dump(sftp_config, f, indent=2)

print("[+] SFTP config saved. DIPSHIT can now deploy live.")
