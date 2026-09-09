# SPEC-0001: BizJapan Elementor テーマ基盤および自動デプロイ仕様

## 1. 概要 & 背景 (Overview & Background)
- **課題・目的**: BizJapan Webサイトにおいて、Elementor を用いた高自由度・高デザイン性のページ作成と、高速表示・自動デプロイ・AI駆動開発（AIDD）の運用体制を両立する。
- **スコープ**: `oscss-elementor-bizjapan` テーマ、ロリポップ共有サーバー環境、およびデプロイスクリプト。

---

## 2. ユーザーストーリー (User Stories)
- **AS A** サイト運用者・コンテンツ制作者
- **I WANT TO** Elementor を使用して直感的にページを構築・更新したい
- **SO THAT** テーマ側の制約や不具合に煩わされることなく、高品質なコンテンツを素早く公開できる

- **AS A** 開発者・AIエージェント
- **I WANT TO** ローカルで検証したテーマコードを、ワンクリックでロリポップサーバー上の保存先へ安全に反映したい
- **SO THAT** 手動FTP作業のミスやキャッシュ残りによる表示不整合を防止できる

---

## 3. 受入条件 (Acceptance Criteria: Given-When-Then)

### AC-1: サーバー上の配置・連携
- **Given**: ロリポップサーバー上の `~/web/bizjapan.oscarchair.jp/wp-content/themes/hello-elementor/` にテーマファイル群が配置された状態
- **When**: WordPress 管理画面（`https://bizjapan.oscarchair.jp/wp-admin/`）でテーマが有効化されている時
- **Then**: サイトトップページおよび各投稿がエラーなく表示され、Elementorの編集画面が正常に起動すること。

### AC-2: 自動デプロイとキャッシュ消去
- **Given**: `deploy.ps1` をローカル環境から実行した時
- **When**: SSH経由でリモートの Git リセットおよび LiteSpeed/OPcache パージ処理が実行された時
- **Then**: 変更内容が即座に公開ページに反映され、古いキャッシュが表示されないこと。

### AC-3: マルチデバイス表示品質
- **Given**: PC (1280px以上) および SP (375px〜414px) 環境
- **When**: ページを読み込んだ時
- **Then**: 画面外への不要な横スクロールが発生せず、ヘッダー・ナビゲーション・フッターが崩れずに表示されること。

---

## 4. 非機能要件 & 制約事項
- **セキュリティ**: `.env`, `.env.deploy` 等のサーバー接続情報が Git コミットに含まれないこと（`scripts/verify_aidd_gate.ps1` で遮断）。
- **軽量性**: テーマ側の追加アセットは最小限に抑え、ElementorのCSS/JS配信を阻害しないこと。
