# モバイル固定コンバージョンバー設計書 (Mobile Sticky CTA Specification)

## 1. 概要とUI設計

香港のビジネスユーザーは、Eメールよりもチャットツール（WhatsApp）による即時コミュニケーションを好む傾向が顕著です。
本機能は、スマートフォン（幅 767px 以下）での閲覧時に画面最下部へコンバージョンボタンを常時フロート表示し、離脱を防いで問い合わせを促進します。

---

## 2. コンポーネント仕様

### 2.1 ボタン構成
| ボタン | 配色 / アイコン | アクション | 遷移先 |
| :--- | :--- | :--- | :--- |
| **WhatsApp 查詢** | グリーン (`#25D366`) / 💬 | WhatsApp アプリ起動または Web 画面遷移 | `https://api.whatsapp.com/send?text=你好，我想查詢日本設立公司及商業代辦服務` |
| **免費諮詢** | コーポレートブルー (`#0b57d0`) / ✉️ | サイト内お問い合わせフォームへ遷移 | `/contact-us/` |

### 2.2 レイアウト & レスポンシブ挙動
- **表示ブレークポイント**: `@media screen and (max-width: 767px)` のみ `display: flex`。PC・タブレット（768px以上）では `display: none`。
- **配置**: `position: fixed; bottom: 0; left: 0; width: 100%; z-index: 99999;`
- **背景エフェクト**: `backdrop-filter: blur(8px)` によるすりガラス調＋微弱なシャドウ（`box-shadow: 0 -3px 12px rgba(0,0,0,0.12)`）。
- **被り防止**: `body { padding-bottom: 64px !important; }` により、ページ下部のフッターリンクやコピーライトが固定バーで隠れないよう余白を確保。
