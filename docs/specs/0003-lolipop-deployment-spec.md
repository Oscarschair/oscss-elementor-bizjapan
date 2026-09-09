# SPEC-0003: ロリポップ共有サーバー自動デプロイメント (deploy.ps1) 仕様書

- **ステータス**: 承認済 (Accepted)
- **対象**: リモートサーバー LOLIPOP、ローカルデプロイスクリプト
- **担当**: OSCSS 開発チーム

---

## 1. 概要 & 背景 (Overview & Background)

ロリポップ（LOLIPOP）共有サーバーは、一般的な PaaS（Vercel, Cloud Run 等）とは異なり、Git プッシュによる自動ビルド・デプロイ機構が標準提供されていない。
従来の FTP による手動ファイルアップロードでは、以下の重大な課題があった：
1. **アップロード漏れ・バージョンの不整合**: 特定のファイルのみ更新され、依存関係が崩れる。
2. **キャッシュの残存**: LiteSpeed Web Server および PHP OPcache のキャッシュにより、CSSやPHPの変更が即時反映されない。
3. **作業工数の肥大化**: 修正のたびにFTPクライアントを立ち上げる必要があり、迅速な改修が阻害される。

本仕様では、ローカルの PowerShell スクリプト（`deploy.ps1`）から SSH 経由でリモートの Git を同期し、同時に PHP キャッシュパージを一括実行する自動デプロイ仕様を定義する。

---

## 2. サーバー環境 & 接続仕様

| 項目 | 設定値 / 仕様 |
| :--- | :--- |
| **接続プロトコル** | SSH2 (Paramiko Python クライアント経由) |
| **接続ホスト (SSH_HOST)** | `ssh.lolipop.jp` |
| **接続ポート (SSH_PORT)** | `2222` |
| **接続ユーザー (SSH_USER)** | `lomo.jp-oscarchair` |
| **認証方式** | パスワード認証（`.env.deploy` 管理 / Git除外） |
| **リモートテーマ配置パス** | `~/web/bizjapan.oscarchair.jp/wp-content/themes/hello-elementor/` |
| **Webサーバー環境** | LiteSpeed Web Server + PHP 8.2 / 8.1 |

---

## 3. デプロイ実行フローと受入条件

### 3.1 実行フロー

```
[開発者 / AIエージェント]
        │
        ▼ 1. powershell ./deploy.ps1 実行
[AIDD Quality Gate (verify_aidd_gate.ps1)]
        │ ├── 機密ファイル漏洩チェック (.env, .env.deploy)
        │ ├── PHP構文スキャン (php -l)
        │ └── AIDD必須ドキュメント整合性確認
        ▼ (合格時のみ進行、不合格時は即時中断)
[Paramiko SSH 接続確立 (ssh.lolipop.jp:2222)]
        │
        ▼ 2. リモートGit同期
        │    cd ~/web/bizjapan.oscarchair.jp/wp-content/themes/hello-elementor/
        │    git fetch --all && git reset --hard origin/main
        │
        ▼ 3. キャッシュ一括パージ (PHP CLI 実行)
        │    /usr/local/php/8.2/bin/php -r "
        │      require('../../../wp-load.php');
        │      opcache_reset();
        │      do_action('litespeed_purge_all');
        │      LiteSpeed\Purge::purge_all();
        │    "
        │
        ▼ 4. 完了ログ出力 & 接続切断
[デプロイ完了 (即時本番反映)]
```

### 3.2 受入条件 (Acceptance Criteria)

- **AC-1 (品質ゲート強制)**:
  - **Given**: Gitステージングに `.env` が含まれている状態、またはPHP構文エラーがある時
  - **When**: `./deploy.ps1` を実行した時
  - **Then**: SSH接続を行う前にスクリプトが即座にエラー終了（終了コード1）し、リモートサーバーへの影響を遮断すること。

- **AC-2 (Git完全同期)**:
  - **Given**: `origin/main` に最新のコミットがプッシュされている時
  - **When**: `./deploy.ps1` を実行した時
  - **Then**: リモートサーバー上のディレクトリが `git reset --hard origin/main` され、最新コミットハッシュがログ出力されること。

- **AC-3 (キャッシュ自動クリア)**:
  - **Given**: デプロイが正常終了した時
  - **When**: ブラウザで `https://bizjapan.oscarchair.jp/` をリロードした時
  - **Then**: LiteSpeed および OPcache のキャッシュがクリアされ、最新のテーマコード・CSSが即座に反映されること。
