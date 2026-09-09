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

hk_japan_news = [
    {
        "title": "【港日經貿最新動向】香港與日本雙邊貿易突破3,300億港元：HKTDC與JETRO深化AI科技與初創商貿對接",
        "slug": "hong-kong-japan-bilateral-trade-hktdc-jetro-ai-startup",
        "category": "港日經貿最新動向",
        "tags": ["港日貿易", "HKTDC", "JETRO", "香港特別行政區", "日本進軍", "AI科技", "初創對接"],
        "date": "2026-03-10 12:00:00",
        "excerpt": "日本穩居香港重要貿易夥伴，雙邊商品貿易額突破3,360億港元。隨着香港貿易發展局（HKTDC）與日本貿易振興機構（JETRO）深化合作，兩地在AI電子科技、綠色轉型及初創生態圈的商業對接迎來歷史新高。",
        "content": """<p><strong>香港特別行政區與日本</strong>長期以來保持緊密且深厚的雙邊經貿夥伴關係。根據最新統計數據，日本穩居香港第六大貿易夥伴，而香港亦為日本第九大貿易夥伴，雙方商品貿易總額突破<strong>港幣 3,360 億元</strong>，按年穩健增長 9.2%。與此同時，兩地經香港的轉口貿易額亦突破港幣 2,650 億元，充分展現香港作為連接日本與大中華區乃至東南亞市場的「超級聯繫人」樞紐地位。</p>

<h2>一、HKTDC 與 JETRO 攜手推進重點合作產業</h2>
<p>香港貿易發展局（HKTDC）與日本貿易振興機構（JETRO）在 2025–2026 年度進一步深化戰略合作，重點聚焦於以下高成長領域：</p>
<ol>
    <li><strong>AI 人工智能與高端電子產業鏈：</strong>全球生成式 AI 爆發帶動高端半導體、電子零件與智慧硬體需求大幅成長。香港與日本企業在軟硬體協同開發、算法應用落地等方面的雙向採購與合作達到新高峰。</li>
    <li><strong>初創生態圈跨國對接：</strong>兩地積極引領本土初創企業互訪拓展。香港創新科技企業組團赴日參加東京大型科技創新展會（如 SusHi Tech Tokyo、Japan IT Week），日本科技巨頭與創投機構亦頻繁現身香港「亞洲金融論壇（AFF）」與「香港國際創科展（InnoEX）」。</li>
    <li><strong>綠色金融與 ESG 永續發展：</strong>雙方於綠色低碳技術、ESG 融資標準認證及跨境綠色供應鏈展開實務經驗交流。</li>
</ol>

<h2>二、香港中小企拓展日本市場的新機遇</h2>
<p>在當前日圓匯率與日本政府積極吸引海外投資的背景下，日本市場對海外優質商品與數位服務展現出前所未有的開放態度：</p>
<ul>
    <li><strong>日本企業尋求跨國業務敏捷度：</strong>許多日本傳統企業正加速數位轉型（DX），亟需具備國際視野、多語言能力及高效率開發能力的香港科技團隊提供支援。</li>
    <li><strong>香港品牌的精緻在地化機會：</strong>香港原創設計、生活風格品牌、保健食品及特色餐飲，在日本消費市場獲得極高接受度。</li>
</ul>

<h2>三、OSCAR 如何協助香港企業搭上經貿快車？</h2>
<p>商業代辦服務 by OSCAR 扎根日本在地，為香港特別行政區的中小企業及創業家提供零時差的商業落地支援：</p>
<ul>
    <li>日本法人登記與合法税務架構規劃；</li>
    <li>日本國家頂級域名（.jp / .co.jp）與符合日本商務禮儀之官方網站製作；</li>
    <li>對接 JETRO 與香港特區政府各項資助基金（包括 BUD 專項基金與 EMF 出口推廣基金），協助獲取最大政策紅利。</li>
</ul>"""
    },
    {
        "title": "【跨境商業實務】逾1,500家日資企業駐港與港商赴日設立法人熱潮：全解析兩地雙向投資與合規落地路徑",
        "slug": "japan-enterprises-in-hong-kong-and-cross-border-incorporation",
        "category": "港日經貿最新動向",
        "tags": ["駐港日企", "香港特別行政區", "雙向投資", "日本設立公司", "跨境合規", "CDTA"],
        "date": "2026-03-10 10:00:00",
        "excerpt": "最新數據顯示逾1,500家日本企業以香港為地區總部或辦事處，同時香港企業赴日設立法人亦創下新熱潮。結合香港特區低稅制與日本市場高端消費力，雙向跨境商業架構已成為中小企出海的核心策略。",
        "content": """<p>香港特別行政區作為亞洲國際金融中心，憑藉簡單低稅制、資金自由進出及健全的普通法法治環境，長期是日本企業進軍海外與大灣區的旗艦基地。最新統計顯示，目前<strong>駐港的日本企業已超過 1,500 家</strong>，涵蓋地區總部、地區辦事處及各類專業機構。</p>
<p>與此同時，近年愈來愈多香港本地企業家及投資者，亦將目光投向日本本土龐大的內需消費與高端科技市場，<strong>港商赴日設立法人（株式會社/合同會社）掀起全新浪潮</strong>，形成了極具活力的「港日雙向商業生態」。</p>

<h2>一、港日《全面性避免雙重課稅協定》（CDTA）的稅務優勢</h2>
<p>香港特別行政區與日本早在多年前便簽署了《全面性避免雙重課稅協定》（CDTA），為兩地跨國商業運作提供了堅實的法制保障：</p>
<ul>
    <li><strong>股息預扣稅減免：</strong>當日本子公司向符合條件的香港母公司派發股息時，預扣稅率可大幅降低（通常低至 5% 或免稅），有效避免利潤遭到重複課稅。</li>
    <li><strong>利息與特許權使用費保障：</strong>跨國商標授權（如香港母品牌授權日本法人營運）與技術轉移的預扣稅率同樣獲得大幅優惠。</li>
    <li><strong>資本利得明確規範：</strong>保障跨境投資者在處置資產時的合法權益。</li>
</ul>

<h2>二、「香港母公司 ⇋ 日本子公司」雙核營運模式實務</h2>
<p>在實踐中，成熟的香港企業多採取「雙核架構」進軍日本：</p>
<ol>
    <li><strong>香港總部：</strong>負責統籌跨國資金池、申請香港特區政府專項資助（如 BUD 專項基金最高 700 萬港元、電商易 100 萬港元）、協調國際金流與智慧財產權持有。</li>
    <li><strong>日本法人：</strong>直接以日本實體身份在日本各大商業銀行開設法人帳戶、申請日本「.co.jp」專屬網域、進駐日本 Amazon/樂天等主流電商平台，並與日本供應商簽署正規商業合約。</li>
</ol>

<h2>三、香港企業赴日落地面臨的四大痛點與對策</h2>
<ul>
    <li><strong>全體非居住者登記手續：</strong>非日本居民如何在缺乏個人日本住民票的情況下完成資本金驗資及法定登記。</li>
    <li><strong>實體商務地址與租賃：</strong>如何租賃符合日本法規（尤其是未來申請經營管理簽證所需）的獨立實體辦公室。</li>
    <li><strong>日語文化與網站在地化：</strong>日語商務溝通對精確度與禮節要求極高，單純機器翻譯往往難以建立客戶信賴。</li>
    <li><strong>日本在地稅理士與合規報稅：</strong>日本法人稅制嚴謹，需按時進行中間申告與決算申告。</li>
</ul>

<h2>四、結語：OSCAR 助您打造穩健的港日跨國商業版圖</h2>
<p>商業代辦服務 by OSCAR 由深諳兩地法律、稅務與商業慣例的專業團隊組成。我們專注為香港特別行政區企業提供從「前期可行性諮詢 ➔ 日本法人設立 ➔ 銀行帳戶與金流對接 ➔ 日語官網開發 ➔ 簽證申辦」的全方位交鑰匙（Turnkey）落地代辦服務，助您無後顧之憂開拓日本千億商機。</p>"""
    }
]

# Write remote seed PHP script
posts_json = json.dumps(hk_japan_news, ensure_ascii=False)

remote_php = f"""<?php
define('WP_USE_THEMES', false);
require('/home/users/0/lomo.jp-oscarchair/web/bizjapan.oscarchair.jp/wp-load.php');

$posts = json_decode({json.dumps(posts_json)}, true);

echo "Starting HK-Japan news update execution...\\n";

foreach ($posts as $item) {{
    // Check if post with slug exists
    $existing = get_page_by_path($item['slug'], OBJECT, 'post');
    $post_data = [
        'post_title'    => $item['title'],
        'post_name'     => $item['slug'],
        'post_content'   => $item['content'],
        'post_excerpt'   => $item['excerpt'],
        'post_status'   => 'publish',
        'post_type'     => 'post',
        'post_date'     => $item['date'],
        'post_author'   => 1,
    ];

    if ($existing) {{
        $post_data['ID'] = $existing->ID;
        $post_id = wp_update_post($post_data);
        echo "Updated post: ID {{$post_id}} - {{$item['title']}}\\n";
    }} else {{
        $post_id = wp_insert_post($post_data);
        echo "Created post: ID {{$post_id}} - {{$item['title']}}\\n";
    }}

    if (!is_wp_error($post_id)) {{
        // Set category
        wp_set_object_terms($post_id, $item['category'], 'category');
        // Set tags
        wp_set_object_terms($post_id, $item['tags'], 'post_tag');
    }}
}}

echo "HK-Japan news update finished successfully!\\n";
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

remote_file = '/home/users/0/lomo.jp-oscarchair/web/bizjapan.oscarchair.jp/seed_hk_japan_news.php'
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

# Remove seed file
ssh.exec_command(f'rm -f {remote_file}')
ssh.close()
