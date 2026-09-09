# oscss-elementor-bizjapan -- BizJapan Elementor WordPress Theme

WordPress サイト構築において Elementor ページビルダーのポテンシャルを最大限に引き出すための、軽量・高速・モジュール化されたカスタム WordPress テーマ基盤です。

---

## アーキテクチャ

本テーマは、Hello Elementor の最小主義・高速性を継承しつつ、運用保守性と拡張性を高めるモジュール設計を採用しています。レイアウトやコンポーネントのデザインは Elementor に委ね、テーマ本体はクリーンな HTML 骨格と最小限のスタイル・フック基盤を提供します。

### システム構成

```
[Browser / Mobile Clients]
           │
           ▼
[Web Server (Nginx / Apache / LiteSpeed)]
           │
           ▼
[WordPress Core (PHP 8.x)]
    ├── Plugins: Elementor / Elementor Pro / SEO / Security
    └── Theme: oscss-elementor-bizjapan
           ├── functions.php (Core bootstrap & modules loader)
           ├── includes/ (Custom settings, asset registration)
           ├── template-parts/ (Header, footer, fallback templates)
           └── assets/ (Optimized styling & scripts)
```

### インフラ / 実行環境

| 項目 | 推奨構成 |
| :--- | :--- |
| **実行環境** | PHP 7.4+ (推奨 8.1 / 8.2), WordPress 6.0+ |
| **ページビルダー** | Elementor / Elementor Pro |
| **Webサーバー** | Nginx / Apache / LiteSpeed |
| **データベース** | MySQL 5.7+ / MariaDB 10.3+ |

---

## 技術スタック

| レイヤー | 技術 |
| :--- | :--- |
| **CMS** | WordPress 6.x |
| **テーマ基盤** | Hello Elementor 3.x Custom Architecture |
| **ページビルダー** | Elementor |
| **言語** | PHP 8.x, Vanilla JavaScript, CSS3 |
| **バージョン管理** | Git / GitHub |

---

## ディレクトリ構成

```
oscss-elementor-bizjapan/
├── .gemini/                    # Gemini AIエージェント設定・PJ憲章
│   └── GEMINI.md               # プロジェクト個別ルール & 憲章4原則
├── docs/                       # プロジェクト公式ドキュメント階層
│   ├── README.md               # 目次・PJ憲章
│   ├── architecture.md         # アーキテクチャ設計書
│   ├── deployment.md           # デプロイ・環境構築手順書
│   ├── adr/                    # Architecture Decision Records
│   │   ├── _TEMPLATE.md        # ADRテンプレート
│   │   └── 0001-init-theme-architecture.md
│   ├── domains/                # ドメイン・機能仕様書
│   │   └── theme.md
│   └── ops/                    # 運用・保守手順書
│       └── maintenance.md
├── assets/                     # 静的アセット（画像、追加スタイル等）
├── includes/                   # 機能モジュール群
├── template-parts/             # 部分テンプレート（ヘッダー、フッター等）
├── .env.example                # 環境変数サンプル
├── .gitignore                  # Git除外設定
├── functions.php               # テーマエントリーポイント
├── style.css                   # テーマスタイルシート & 定義
└── README.md                   # 本ドキュメント
```

---

## セットアップ & デプロイ手順

### 1. サーバー環境 (LOLIPOP)
- **公開ディレクトリ (テーマ保存先)**: `/bizjapan.oscarchair.jp/wp-content/themes/hello-elementor/`
- **SSH 接続先**: `ssh.lolipop.jp:2222` (ユーザー: `lomo.jp-oscarchair`)
- **データベース**: MySQL 8.0 (`mysql301.phy.lolipop.lan`)

### 2. ワンクリック・自動デプロイ (`deploy.ps1`)
ローカルで修正した内容をリモートサーバーに反映する際は、以下のスクリプトを実行します。

```powershell
./deploy.ps1
```
※本スクリプトは SSH 経由でサーバー上のテーマディレクトリへアクセスし、`git fetch & reset --hard` による同期と LiteSpeed / OPcache の全消去を自動実行します。

### 3. Elementor の初期設定
1. WordPress 管理画面（`https://bizjapan.oscarchair.jp/wp-admin/`）にログイン。
2. **「外観」 > 「テーマ」** にて「Hello Elementor」が有効化されていることを確認。
3. **「Elementor」 > 「ツール」 > 「一般」 > 「CSSとデータの再生成」** を実行。

---

## AI駆動開発 (AIDD: AI-Driven Development) 体制

本リポジトリは、仕様書（Spec）を軸に品質ゲートを通しながら人間とAIが協調開発する **Spec-Driven Development / AIDD 運用体制** を完全導入しています。

### 開発サイクル
1. **Specify**: `docs/specs/` に機能要件と受入条件（Given-When-Then）を言語化。
2. **Plan & ADR**: `docs/adr/` にアーキテクチャ意思決定を記録。
3. **Implement**: 差分最小化の原則に基づきコードを実装。
4. **Quality Gate**: デプロイ前に機密漏洩・ドキュメント整合性を自動検証。
5. **Multi-Device QA**: PC（1280px+）および SP（375px〜414px）で横崩れ・タップ領域を検証。
6. **Deploy**: `deploy.ps1` によるワンクリックリモート同期 & キャッシュ全消去。

### 品質ゲートの実行
```powershell
./scripts/verify_aidd_gate.ps1
```

---

## ドキュメント一覧

- 📜 [AIDD プロジェクト憲章 (.gemini/GEMINI.md)](file:///c:/Users/user/git/oscss-elementor-bizjapan/.gemini/GEMINI.md)
- 📘 [AIDD 実践ガイド (docs/aidd-guide.md)](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/aidd-guide.md)
- 📋 [Spec 仕様書テンプレート (docs/specs/_TEMPLATE.md)](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/specs/_TEMPLATE.md)
- 📚 [ドキュメント目次 (docs/README.md)](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/README.md)
- 🏗️ [アーキテクチャ設計書 (docs/architecture.md)](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/architecture.md)
- 🚀 [デプロイ手順書 (docs/deployment.md)](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/deployment.md)
- 📝 [意思決定ログ (docs/adr/)](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/adr/)
- 📖 [機能仕様書 (docs/domains/theme.md)](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/domains/theme.md)
- 🛠️ [運用・保守手順書 (docs/ops/maintenance.md)](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/ops/maintenance.md)

---

## ライセンス

本テーマは GNU General Public License v3 or later に準拠しています。
