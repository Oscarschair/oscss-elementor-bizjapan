# deploy.ps1
# Automated deploy script for LOLIPOP (Remote SSH Git Pull & LiteSpeed/OPcache Flush)

Write-Host "--- Remote SSH PULL & Cache Flush (LOLIPOP) ---" -ForegroundColor Cyan

# 0. AIDD Quality Gate Check
Write-Host "Executing AIDD Quality Gate..." -ForegroundColor Yellow
powershell -ExecutionPolicy Bypass -File .\scripts\verify_aidd_gate.ps1
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: AIDD Quality Gate failed! Aborting deployment." -ForegroundColor Red
    exit 1
}

# Load .env.deploy
if (-Not (Test-Path ".env.deploy")) {
    Write-Host "Error: .env.deploy not found." -ForegroundColor Red
    exit 1
}

$envData = @{}
Get-Content ".env.deploy" | ForEach-Object {
    $line = $_.Trim()
    if ($line -match "^[^#].+=.*$") {
        $parts = $line.Split("=", 2)
        if ($parts.Length -eq 2) {
            $key = $parts[0].Trim(); $val = $parts[1].Trim()
            $envData.$key = $val
        }
    }
}

$u = $envData.SSH_USER
$h = $envData.SSH_HOST
$p = $envData.SSH_PORT
$pass = $envData.SSH_PASS
$d = $envData.DEPLOY_DIR

if (-not ($u -and $h -and $p -and $d)) {
    Write-Host "Error: .env.deploy missing required configuration." -ForegroundColor Red
    exit 1
}

Write-Host "Connecting to $h via Paramiko SSH, running git pull, and purging LiteSpeed/OPcache..." -ForegroundColor Cyan

$pyScript = @"
import sys
if hasattr(sys.stdout, 'reconfigure'):
    sys.stdout.reconfigure(encoding='utf-8', errors='replace')
if hasattr(sys.stderr, 'reconfigure'):
    sys.stderr.reconfigure(encoding='utf-8', errors='replace')
import paramiko

host = '$h'
port = $p
user = '$u'
password = '$pass'
deploy_dir = '$d'.replace('~/', '')

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    ssh.connect(hostname=host, port=port, username=user, password=password, look_for_keys=False, allow_agent=False, timeout=15)
except Exception as e:
    transport = paramiko.Transport((host, port))
    transport.connect()
    transport.auth_interactive(user, lambda title, instructions, prompt_list: [password for _ in prompt_list])
    ssh._transport = transport

php_inline = "define('WP_USE_THEMES', false); require('../../../wp-load.php'); if (function_exists('opcache_reset')) { opcache_reset(); } do_action('litespeed_purge_all'); if (class_exists('LiteSpeed\\\\Purge')) { LiteSpeed\\\\Purge::purge_all(); } echo 'Cache flushed successfully\\n';"

remote_cmd = 'cd ' + deploy_dir + ' && if [ -d .git ]; then git fetch --all && git reset --hard origin/main && git log -n 1 --oneline; else echo \"Notice: Remote directory is not a git clone yet.\"; fi && if [ -f ../../../wp-load.php ]; then /usr/local/php/8.2/bin/php -r \"' + php_inline + '\" || /usr/local/php/8.1/bin/php -r \"' + php_inline + '\" || echo \"PHP cache flush fallback\"; fi'

stdin, stdout, stderr = ssh.exec_command(remote_cmd)
out = stdout.read().decode('utf-8', errors='ignore')
err = stderr.read().decode('utf-8', errors='ignore')

print('[REMOTE STDOUT]')
print(out)
if err:
    print('[REMOTE STDERR]')
    print(err)

ssh.close()
"@

$pyScript | python -

if ($LASTEXITCODE -eq 0) {
    Write-Host "`n--------------------------------------------------------" -ForegroundColor Cyan
    Write-Host "Success: Remote deployment and cache flush process finished!" -ForegroundColor Green
    Write-Host "--------------------------------------------------------" -ForegroundColor Cyan
} else {
    Write-Host "`nError: SSH deployment command failed." -ForegroundColor Red
}
