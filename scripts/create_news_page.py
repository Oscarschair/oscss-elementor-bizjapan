import paramiko
import json
import sys

# Load .env.deploy
envData = {}
with open('.env.deploy', encoding='utf-8', errors='ignore') as f:
    for line in f:
        line = line.strip()
        if '=' in line and not line.startswith('#'):
            k, v = line.split('=', 1)
            envData[k.strip()] = v.strip()

host = envData['SSH_HOST']
port = int(envData['SSH_PORT'])
user = envData['SSH_USER']
pwd = envData['SSH_PASS']

remote_php = """<?php
define('WP_USE_THEMES', false);
require('/home/users/0/lomo.jp-oscarchair/web/bizjapan.oscarchair.jp/wp-load.php');

$slug = 'news';
$page = get_page_by_path($slug, OBJECT, 'page');

if ($page) {
    update_post_meta($page->ID, '_wp_page_template', 'page-news.php');
    echo "Existing page found (ID: {$page->ID}). Updated template to page-news.php.\\n";
} else {
    $page_data = [
        'post_title'    => '最新資訊與專題文章｜香港特別行政區與日本商業落地支援',
        'post_name'     => 'news',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => 1,
        'post_content'  => '',
    ];
    $page_id = wp_insert_post($page_data);
    if (!is_wp_error($page_id)) {
        update_post_meta($page_id, '_wp_page_template', 'page-news.php');
        echo "Successfully created /news/ page (ID: {$page_id}) with template page-news.php!\\n";
    } else {
        echo "Error creating page: " . $page_id->get_error_message() . "\\n";
    }
}
"""

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(hostname=host, port=port, username=user, password=pwd, look_for_keys=False, allow_agent=False, timeout=15)
except Exception:
    t = paramiko.Transport((host, port))
    t.connect()
    t.auth_interactive(user, lambda title, instructions, prompt_list: [pwd for _ in prompt_list])
    ssh._transport = t

remote_file = '/home/users/0/lomo.jp-oscarchair/web/bizjapan.oscarchair.jp/create_news_page.php'
sftp = ssh.open_sftp()
with sftp.open(remote_file, 'w') as f:
    f.write(remote_php)
sftp.close()

stdin, stdout, stderr = ssh.exec_command(f'/usr/local/php/8.2/bin/php {remote_file}')
out = stdout.read().decode('utf-8', errors='ignore')
err = stderr.read().decode('utf-8', errors='ignore')
print(out)
if err:
    print('ERR:', err)

# Cleanup
ssh.exec_command(f'rm -f {remote_file}')
ssh.close()
