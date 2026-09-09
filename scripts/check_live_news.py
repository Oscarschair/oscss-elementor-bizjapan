import urllib.request
import re
import time
import sys

sys.stdout.reconfigure(encoding='utf-8')

url = f'https://bizjapan.oscarchair.jp/?nocache={time.time()}'
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
html = urllib.request.urlopen(req).read().decode('utf-8')

if 'bizjapan-news-container' in html:
    print('SUCCESS: bizjapan-news-container found!')
    titles = re.findall(r'<h3 class="news-title">\s*<a[^>]*>(.*?)</a>', html)
    for i, t in enumerate(titles, 1):
        print(f'{i}. {t}')
else:
    print('Container not found.')
    idx = html.find('3e87c6c2')
    print('Snippet around 3e87c6c2:')
    print(html[idx:idx+400])
