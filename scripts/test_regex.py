import urllib.request
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')
req = urllib.request.Request('https://bizjapan.oscarchair.jp/?nocache=999', headers={'User-Agent': 'Mozilla/5.0'})
html = urllib.request.urlopen(req).read().decode('utf-8')
pattern = r'<div[^>]*class="[^"]*elementor-element-3e87c6c2[^"]*"[^>]*>.*?<div[^>]*class="[^"]*elementor-element-7ff56a9e[^"]*"[^>]*>.*?</div>\s*</div>\s*</div>'
m = re.search(pattern, html, re.DOTALL)
if m:
    print('SUCCESS: Full span matched! Length:', len(m.group(0)))
    print('Starts with:', m.group(0)[:80])
    print('Ends with:', m.group(0)[-80:])
else:
    print('No match found.')
