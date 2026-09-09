import paramiko

envData = {}
with open('.env.deploy', encoding='utf-8', errors='ignore') as f:
    for line in f:
        line = line.strip()
        if '=' in line and not line.startswith('#'):
            k, v = line.split('=', 1)
            envData[k.strip()] = v.strip()

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
try:
    ssh.connect(hostname=envData['SSH_HOST'], port=int(envData['SSH_PORT']), username=envData['SSH_USER'], password=envData['SSH_PASS'], look_for_keys=False, allow_agent=False, timeout=15)
except Exception:
    t = paramiko.Transport((envData['SSH_HOST'], int(envData['SSH_PORT'])))
    t.connect()
    t.auth_interactive(envData['SSH_USER'], lambda title, instructions, prompt_list: [envData['SSH_PASS'] for _ in prompt_list])
    ssh._transport = t

remote_php = """<?php
define('WP_USE_THEMES', false);
require('/home/users/0/lomo.jp-oscarchair/web/bizjapan.oscarchair.jp/wp-load.php');

$front_id = get_option('page_on_front');
$el = get_post_meta($front_id, '_elementor_data', true);
$data = json_decode($el, true);

function find_testimonials($elements, &$found) {
    foreach ($elements as $elem) {
        if (isset($elem['widgetType']) && $elem['widgetType'] === 'testimonial') {
            $found[] = $elem;
        }
        if (!empty($elem['elements'])) {
            find_testimonials($elem['elements'], $found);
        }
    }
}
$found = [];
find_testimonials($data, $found);
echo "Testimonial widgets count: " . count($found) . "\\n";
foreach ($found as $w) {
    echo "CONTENT: " . ($w['settings']['testimonial_content'] ?? '') . "\\n";
    echo "NAME: " . ($w['settings']['testimonial_name'] ?? '') . "\\n";
    echo "JOB: " . ($w['settings']['testimonial_job'] ?? '') . "\\n";
    echo "-----------------------------------------\\n";
}
"""

sftp = ssh.open_sftp()
with sftp.open('/home/users/0/lomo.jp-oscarchair/web/bizjapan.oscarchair.jp/extract_other_info.php', 'w') as f:
    f.write(remote_php)
sftp.close()

stdin, stdout, stderr = ssh.exec_command('/usr/local/php/8.2/bin/php /home/users/0/lomo.jp-oscarchair/web/bizjapan.oscarchair.jp/extract_other_info.php')
out = stdout.read().decode('utf-8', errors='ignore')
with open('scripts/existing_other_info.txt', 'w', encoding='utf-8') as out_f:
    out_f.write(out)
print("Saved to scripts/existing_other_info.txt")

ssh.exec_command('rm /home/users/0/lomo.jp-oscarchair/web/bizjapan.oscarchair.jp/extract_other_info.php')
ssh.close()
