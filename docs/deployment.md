# デプロイ・環境構築手順書 (Deployment & Environment)

## 1. 開発環境の前提条件

- **PHP**: 7.4 以上（推奨: PHP 8.1 / 8.2）
- **WordPress**: 6.0 以上
- **プラグイン**: Elementor（推奨: 3.15 以上）
- **Webサーバー**: Nginx / Apache / LiteSpeed

---

## 2. テーマの配置とデプロイ先情報 (LOLIPOP)

- **本番ドメイン**: `https://bizjapan.oscarchair.jp`
- **サーバー**: LOLIPOP（ロリポップ共有サーバー）
- **SSH 接続ホスト**: `ssh.lolipop.jp` (ポート: `2222`)
- **サーバー上の保存ディレクトリ**:
  ```text
  /bizjapan.oscarchair.jp/wp-content/themes/hello-elementor/
  （ホームからの相対: ~/web/bizjapan.oscarchair.jp/wp-content/themes/hello-elementor/）
  ```
- **データベース環境**:
  - ホスト: `mysql301.phy.lolipop.lan`
  - バージョン: MySQL 8.0
  - 認証情報: ローカルの `.env` で管理（Git除外）

---

## 3. デプロイ手順

### 方法A: ワンクリック自動デプロイ (`deploy.ps1`)
他PJと同様に、Paramiko を用いた SSH 自動同期スクリプトを提供しています。

```powershell
./deploy.ps1
```
※実行時に `.env.deploy` の認証情報を用いて SSH 接続し、リモートで `git fetch & reset --hard origin/main` および LiteSpeed/OPcache のパージを実行します。

### 方法B: Remote SSH による直接同期
```bash
ssh -p 2222 lomo.jp-oscarchair@ssh.lolipop.jp
cd ~/web/bizjapan.oscarchair.jp/wp-content/themes/hello-elementor/
git fetch origin
git reset --hard origin/main
```

---

## 3. デプロイ後チェックリスト

1. **テーマ有効化状態**: 管理画面「外観」>「テーマ」でテーマが正常に有効化されているか。
2. **Elementor 互換性**: Elementor の編集画面が正常に起動し、Theme Builder または標準テンプレートが描画されるか。
3. **アセット読み込み**: コンソール（F12）で CSS / JS の 404 エラーや JavaScript エラーが発生していないか。
4. **マルチデバイス表示検証（必須）**:
   - PC（1280px以上）および SP（375px〜414px）で横スクロールやレイアウト崩れが発生していないか。
5. **キャッシュ全消去**: OPcache、LiteSpeed Cache、WP Rocket、CDN（Cloudflare等）のキャッシュをパージ。
