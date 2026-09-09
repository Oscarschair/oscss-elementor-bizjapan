# アーキテクチャ設計書 (Architecture Specification)

## 1. 概要と基本方針

`oscss-elementor-bizjapan` は、WordPress 上で Elementor ページビルダーを最大限活用しつつ、香港市場（zh-HK）に完全特化した軽量・高パフォーマンスなカスタム WordPress テーマ基盤です。
Hello Elementor をベースにしつつ、香港向けローカライズ、SEOメタタグ最適化、構造化データ（JSON-LD）、およびモバイル固定コンバージョンバー（Sticky CTA）をテーマ層で統合管理しています。

### 設計思想
- **Minimalist & High Performance**: 不要なスクリプトやスタイルを極限まで排除し、Core Web Vitals を最適化。
- **Elementor First**: サイト構造やデザイン表現は Elementor に委ね、テーマ側は最小限のフォールバック・ラッパー・フックに徹する。
- **Modularity**: PHP ロジックは単一の `functions.php` に肥大化させず、`includes/` ディレクトリ配下に機能単位で分離・保守する。
- **Spec-Driven & Safety**: ドキュメントとコードの乖離を防ぎ、事前の品質ゲートを通過した安全なコードのみをロリポップサーバーへ自動同期する。

---

## 2. システム構成

```
[クライアント (PC Browser / Mobile)]
           │
     (Mobile 767px以下) ──▶ [Mobile Sticky Bar (WhatsApp / 諮詢)]
           │
           ▼
[Web Server (LOLIPOP: LiteSpeed Web Server)]
           │  (SSH Port 2222: deploy.ps1 による Git pull & Cache Purge)
           ▼
[WordPress Core (PHP 8.2 / 8.1)]
    ├── Plugins: Elementor / Header Footer Elementor / AIOSEO
    └── Theme: oscss-elementor-bizjapan
           ├── functions.php (Core bootstrap & modules loader)
           ├── includes/
           │     ├── bizjapan-seo-zhhk.php (SEO / zh-HK / JSON-LD / CTA)
           │     ├── elementor-functions.php
           │     └── settings-functions.php
           ├── assets/
           │     └── css/bizjapan-custom.css (Mobile CTA & HK Fonts)
           └── template-parts/ (Header, footer, fallback templates)
```

---

## 3. モジュール一覧

| モジュール / ファイル | 役割・技術仕様 |
| :--- | :--- |
| `functions.php` | テーマのエントリーポイント。定数定義、依存モジュールの読み込み。 |
| `includes/bizjapan-seo-zhhk.php` | **[NEW]** 香港向け言語属性 (`zh-HK`)、SEOタイトル・ディスクリプションフィルター、JSON-LD (Service/FAQPage) 出力、モバイル固定CTAバー。 |
| `assets/css/bizjapan-custom.css` | **[NEW]** 香港フォント（PingFang HK等）の最適化、モバイル固定バーのグラスモーフィズムデザイン・レスポンシブ制御。 |
| `deploy.ps1` | **[NEW]** AIDD品質ゲート検証後、Paramiko SSH経由でリモートGitリセットとLiteSpeed/OPcacheパージを一括実行。 |
| `scripts/verify_aidd_gate.ps1` | **[NEW]** 機密ファイル漏洩検知・PHP構文スキャン・ドキュメント完全性の自動検証ゲート。 |

---

## 4. セキュリティ & コーディング基準

- **直接アクセスの遮断**: すべての PHP ファイル先頭で `defined( 'ABSPATH' ) || exit;` による直接アクセス遮断を徹底。
- **エスケープとサニタイズ**: 出力時は `esc_html()`, `esc_attr()`, `esc_url()` 等を必ず使用。
- **機密情報の隔離**: 本番サーバー接続情報、DBパスワード等は `.env`, `.env.deploy` で管理し、`.gitignore` および品質ゲートで二重にコミットを防止。
