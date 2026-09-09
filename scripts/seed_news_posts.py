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
deploy_dir = envData['DEPLOY_DIR'].replace('~/', '')

posts_data = [
    {
        "title": "【政府資助】中小企業市場推廣基金 (EMF)：拓展日本展覽與境外宣傳最高資助10萬港元",
        "slug": "emf-fund-japan-market-expansion",
        "category": "政府資助 (BUD / EMF)",
        "tags": ["EMF", "中小企業市場推廣基金", "日本市場", "海外展覽", "日本宣傳"],
        "date": "2026-03-01 10:00:00",
        "excerpt": "旨在向中小企業提供資助，以鼓勵中小企業參與出口推廣活動，藉此協助其擴展香港境外市場。資助上限為港幣10萬元或核准開支總費用的50%，以較低者為準。",
        "content": """<p><strong>中小企業市場推廣基金（EMF）</strong>旨在向中小企業提供資助，以鼓勵中小企業參與出口推廣活動，藉此協助其擴展香港境外市場。資助上限為港幣10萬元或核准開支總費用的50%，以較低者為準。</p>

<h2>一、基金目標與適用對象</h2>
<p>對於有意進軍日本市場的香港企業而言，EMF 是測試日本市場反應、獲取第一手日本買家聯絡的最佳切入點。任何在香港按照《商業登記條例》（第310章）登記，並在香港有實質業務運作的中小企業均符合申請資格。</p>

<h2>二、主要資助範圍（適用於日本業務）</h2>
<ul>
    <li><strong>參加日本實體商業展覽：</strong>包括東京國際禮品展（Tokyo Gift Show）、日本食品展（FOODEX JAPAN）、日本IT週（Japan IT Week）等，資助攤位租金及基本展位裝修費用。</li>
    <li><strong>境外商貿考察團：</strong>參加由政府認可機構組織的赴日經貿考察團。</li>
    <li><strong>日本線上展覽與海外宣傳：</strong>在以日本市場為目標對象的境外貿易網站或刊物刊登廣告、建立推廣網頁等。</li>
</ul>

<h2>三、資助金額與撥款比率</h2>
<ul>
    <li><strong>每宗申請資助上限：</strong>港幣 10 萬元，或核准開支總費用的 50%（以較低者為準）。</li>
    <li><strong>企業累計資助上限：</strong>每家合資格企業在基金下的累計資助總額高達港幣 100 萬元。</li>
    <li><strong>發還模式：</strong>通常採用實報實銷（Reimbursement）模式，於活動圓滿結束後向工業貿易署提交申請與完整單據。</li>
</ul>

<h2>四、OSCAR 團隊如何協助您開拓日本市場？</h2>
<p>商業代辦服務 by OSCAR 擁有深厚的日本在地商務網絡，能為有意申請 EMF 拓展日本的香港企業提供：</p>
<ol>
    <li>日本出展現場合格日語商務翻譯與解說員派遣；</li>
    <li>日語產品型錄、宣傳手冊與展位海報設計印刷；</li>
    <li>日本潛在客戶名單整理與展後日語 Follow-up 商務郵件代發；</li>
    <li>符合香港工貿署要求之正規日本在地收據與發票憑據開立支援。</li>
</ol>"""
    },
    {
        "title": "【政府資助】BUD專項基金 (發展品牌、升級轉型及拓展營銷市場)：最高累計資助700萬港元",
        "slug": "bud-fund-branding-japan-expansion",
        "category": "政府資助 (BUD / EMF)",
        "tags": ["BUD專項基金", "日本進軍", "品牌升級", "海外拓展", "700萬資助"],
        "date": "2026-03-03 11:00:00",
        "excerpt": "香港特別行政區政府於2012年6月推出「BUD 專項基金」。政府最多資助個別項目總核准開支的50%，而企業須以現金形式承擔不少於該項目總核准開支的50%，累計上限達700萬港元。",
        "content": """<p>香港特別行政區政府於2012年6月推出<strong>「BUD 專項基金」</strong>（發展品牌、升級轉型及拓展營銷市場專項基金）。政府最多資助個別項目總核准開支的50%，而企業須以現金形式承擔不少於該項目總核准開支的50%，每家企業之累計資助上限高達<strong>港幣 700 萬元</strong>。</p>

<h2>一、BUD 專項基金三大核心範疇</h2>
<p>香港企業開拓日本市場時，可透過以下三大範疇靈活申請資助：</p>
<ol>
    <li><strong>發展品牌（Branding）：</strong>在日本註冊商標、重塑品牌形象、設計適合日本消費者偏好之產品包裝及日語官方品牌站。</li>
    <li><strong>升級轉型（Upgrading）：</strong>因應日本市場嚴格的法規標準（如 PSE 電器安全認證、JAS 日本農業規格、食品衛生法規格等）進行生產與檢測流程升級。</li>
    <li><strong>拓展營銷（Domestic Sales in Japan）：</strong>在日本設立銷售據點、聘用日本在地行銷人員、投放日本線上與線下廣告、參加大型貿易洽談會。</li>
</ol>

<h2>二、涵蓋地域擴展至日本（自由貿易協定及投資協定）</h2>
<p>自政府擴大 BUD 專項基金資助地域範圍後，與香港簽署自由貿易協定（FTA）或促進和保護投資協定（IPPA）的經濟體均屬合資格市場，<strong>日本已全面納入合資格資助地域名單</strong>。</p>

<h2>三、資助架構與撥款亮點</h2>
<ul>
    <li><strong>資助比率：</strong>1:1 等額對衡資助模式（政府資助最高50%）。</li>
    <li><strong>項目數量：</strong>每家企業最多可獲批 70 個核准項目。</li>
    <li><strong>首期撥款：</strong>企業獲批後可申請最高達核准資助額 75% 的首期撥款，有效減輕前期現金流壓力。</li>
</ul>

<h2>四、OSCAR 日本落地執行一站式整合</h2>
<p>BUD 申請的關鍵在於「日本執行方案的可行性」與「符合審計要求的正規單據」。OSCAR 團隊為您提供日本法人設立、在地辦公室租賃、.jp 官網建置及合規日本商業發票全套配套，助您順利通過審批與項目終期驗收。</p>"""
    },
    {
        "title": "【資金支援】中小企融資擔保計劃 (SFGS)：協助香港企業穩健進軍日本開拓新業務",
        "slug": "sfgs-financing-guarantee-japan-launch",
        "category": "企業融資與資金",
        "tags": ["中小企融資擔保", "SFGS", "資金流", "企業擴展", "跨境融資"],
        "date": "2026-03-05 14:00:00",
        "excerpt": "協助本地中小企及非上市企業從參與計劃的貸款機構取得融資，應付業務需要，並在急速轉變的營商環境中，提升生產力和競爭力。根據企業資格提供最高8成至9成信貸擔保。",
        "content": """<p><strong>中小企融資擔保計劃（SME Financing Guarantee Scheme, SFGS）</strong>由香港按證保險有限公司營運，旨在協助本地中小企及非上市企業從參與計劃的貸款機構取得融資，應付業務需要，並在急速轉變的營商環境中，提升生產力和競爭力。政府根據企業資格提供高達八成至九成的信貸擔保。</p>

<h2>一、計劃背景與資金優勢</h2>
<p>企業進軍日本市場時，前期往往需要投入公司註冊資本金（通常建議 500 萬日圓以上）、首期辦公室租金、商品保稅備貨及日本員工薪酬。透過 SFGS 獲得銀行優惠利率融資，能為母公司保留充裕的營運流動資金，降低跨國擴張的財務風險。</p>

<h2>二、主要產品類別概覽</h2>
<ul>
    <li><strong>八成信貸擔保產品（80% Guarantee）：</strong>最高貸款額可達港幣 1,800 萬元，最長還款期達 7 年，適用於一般營運資金及購置設備。</li>
    <li><strong>九成信貸擔保產品（90% Guarantee）：</strong>最高貸款額可達港幣 800 萬元，最長還款期達 5 年，特別適合規模較小、營運年期較短或缺乏充足抵押品的初創及中小企業。</li>
</ul>

<h2>三、申請要點與企業資格</h2>
<ol>
    <li>在香港註冊並有實質營運之非上市企業；</li>
    <li>擁有良好信貸紀錄，具備清晰財務報表或核數報告；</li>
    <li>貸款款項可用於支付正常業務營運開支，包括採購原料、推廣擴展及跨境前期投入。</li>
</ol>

<h2>四、結語：妥善規劃海外資本配置</h2>
<p>進軍日本不單是市場開拓，更是資本與合規體系的整體佈局。結合香港政府的擔保貸款與專項資助（如 BUD / EMF），能讓您的日本事業以最低資本壓力迅速起步。</p>"""
    },
    {
        "title": "【2026最新政策】「BUD專項基金」增設「電商易 (E-commerce Easy)」：最高資助100萬港元拓展日本網購市場",
        "slug": "bud-ecommerce-easy-japan-online-sales",
        "category": "政府資助 (BUD / EMF)",
        "tags": ["BUD電商易", "日本電商", "Amazon Japan", "Rakuten", "網購出海", "100萬資助"],
        "date": "2026-03-08 09:30:00",
        "excerpt": "香港政府於BUD專項基金下推出「電商易」，每家企業可獲最高100萬港元資助，加快推行日本電商業務（包括Amazon Japan、Rakuten、日文官網網店及社群推廣），審批大幅簡化！",
        "content": """<p>為支援香港企業把握海外龐大的網購商機，香港特區政府於「BUD 專項基金」下正式推出<strong>「電商易（E-commerce Easy）」</strong>。合資格中小企業可於基金現有累計 700 萬港元的資助上限中，靈活調動高達<strong>港幣 100 萬元</strong>專項額度，推行各類日本跨境電子商務項目！</p>

<h2>一、「電商易」的三大核心突破</h2>
<ul>
    <li><strong>審批速度大幅提升：</strong>設立專屬快速審批機制，簡化申請文件要求，助企業搶佔電商先機。</li>
    <li><strong>放寬預付款要求：</strong>減輕企業前期墊資壓力，支持分階段撥款。</li>
    <li><strong>直擊跨境電商核心支出：</strong>全方位涵蓋日本主流電商平台進駐與數位行銷。</li>
</ul>

<h2>二、哪些日本電商項目符合資助？</h2>
<p>香港企業拓展日本網購市場時，以下開支均可納入「電商易」資助範圍：</p>
<ol>
    <li><strong>第三方電商平台上架及營運：</strong>在 <strong>Amazon Japan（日本亞馬遜）</strong>、<strong>Rakuten（日本樂天市場）</strong>、<strong>Yahoo! Shopping（日本雅虎購物商城）</strong> 等平台開設店鋪、保證金、上架服務費。</li>
    <li><strong>日語官方購物獨立站建置：</strong>開發具備日圓結算（信用卡、PayPay、便利店代付）、日本當地物流對接（黑貓宅急便 Yamato、佐川急便 Sagawa）的日文官方購物網站（如 Shopify、WooCommerce）。</li>
    <li><strong>日本本地數位廣告與宣傳：</strong>投放 Google Japan 搜尋引擎關鍵字廣告、Yahoo! Japan 廣告、Instagram / Facebook 日本受眾行銷，以及 LINE Official Account 官方帳號推廣。</li>
    <li><strong>日本 SEO 與在地化文案撰寫：</strong>針對日本消費者搜尋習慣進行關鍵字優化與母語級文案潤飾。</li>
</ol>

<h2>三、申請條件與注意事項</h2>
<p>申請企業須於香港持有有效商業登記（BR），且於提交申請時在香港具備實質業務運作。項目執行期最長可達 24 個月。</p>

<h2>四、OSCAR 如何助您一鍵接軌日本電商？</h2>
<p>商業代辦服務 by OSCAR 提供全方位日本落地電商架構：</p>
<ul>
    <li>日本法人設立與「.co.jp / .jp」國家頂級網域名稱申請；</li>
    <li>符合日本《特定商業交易法》（特定商取引法）標準的合規資訊揭露頁面建置；</li>
    <li>日本在地收單金流帳戶及海外匯回方案諮詢；</li>
    <li>完整符合 BUD / 電商易審計要求的合規報價單與項目合約。</li>
</ul>"""
    },
    {
        "title": "【日本法規革新】全體代表非日本居住者設立日本法人要點及「經營・管理」簽證最新趨勢",
        "slug": "incorporate-japan-non-resident-director-visa",
        "category": "日本法規與商業政策",
        "tags": ["日本公司設立", "非居住者代表", "經營管理簽證", "資本金驗資", "日本商業登記"],
        "date": "2026-03-09 15:00:00",
        "excerpt": "外國人無需日本居住者同任代表即可完成日本法人設立。結合資本金500萬日圓、實體事務所租賃及2026年最新經營管理簽證實務解析，助您零障礙進軍日本。",
        "content": """<p>近年日本政府為吸引海外優質企業與創業家進軍日本市場，持續鬆綁外國人投資與設立公司的法規門檻。現時<strong>即使全體董事及代表取締役（公司法定代表）均非日本居住者（即全體代表均為香港或海外人士），亦可直接向法務局合法申請設立日本株式會社或合同會社</strong>！</p>

<h2>一、非居住者設立日本法人的關鍵突破</h2>
<p>過去外國人在日本設立公司，通常被要求必須有一名擁有日本住民票的共同代表。自日本法務省發布民事局通達後，該限制已全面取消。香港企業家無需再尋求名義上的日本在地掛名代表，享有 100% 的股權與公司控制權。</p>

<h2>二、設立日本公司的三大核心實務門檻</h2>
<ol>
    <li><strong>資本金驗資帳戶：</strong>由於非日本居住者尚未取得中長期簽證前無法在日本銀行開設個人帳戶，實務上需透過日本法務省認可的「受任人（通常為日本司法書士或在地合作代理機構）」帳戶代收資本金並出具出資證明。</li>
    <li><strong>註冊地址與實體事務所：</strong>若僅單純設立公司營運，可使用共享辦公室或商業地址；但<strong>若日後計劃申請「經營・管理」簽證，出入國在留管理局明確要求必須具備獨立、可上鎖、備有基本辦公設備的「實體專用辦公室」</strong>，不可使用純虛擬地址。</li>
    <li><strong>資本金規模：</strong>日本公司法規定資本金可低至 1 日圓，但為了公司信譽以及後續申請經營管理簽證之基本要件，通常<strong>資本金必須達 500 萬日圓以上</strong>，並需提出清晰合法的資金來源證明。</li>
</ol>

<h2>三、「經營・管理」簽證審查趨勢剖析</h2>
<p>申請日本「經營・管理」簽證時，入管局審查的重心已從「形式要件」轉向「事業可行性與持續性」：</p>
<ul>
    <li><strong>事業計畫書（Business Plan）：</strong>需詳實列出預估營收、目標客戶群、合作供應商意向書，證明該公司在日本具有長期生存與盈利能力。</li>
    <li><strong>業務真實性：</strong>是否已具備日語官網、商業合作往來郵件或進銷存憑證。</li>
</ul>

<h2>四、OSCAR 日本商業代辦優勢</h2>
<p>我們與日本頂尖的<strong>司法書士（負責登記設立）</strong>、<strong>行政書士（負責簽證與許可申辦）</strong>及<strong>稅理士（負責日語會計報稅）</strong>團隊保持緊密合作，為香港投資者提供「公司成立 ➔ 事務所租賃 ➔ 日語網站建置 ➔ 簽證申請 ➔ 税務維護」的全流程一站式落地服務。</p>"""
    }
]

# Write remote seed PHP script
posts_json = json.dumps(posts_data, ensure_ascii=False)

remote_php = f"""<?php
define('WP_USE_THEMES', false);
require('/home/users/0/lomo.jp-oscarchair/web/bizjapan.oscarchair.jp/wp-load.php');

$posts = json_decode({json.dumps(posts_json)}, true);

echo "Starting news seed execution...\\n";

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

echo "Seed execution finished successfully!\\n";
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

remote_file = '/home/users/0/lomo.jp-oscarchair/web/bizjapan.oscarchair.jp/seed_bizjapan_news.php'
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
