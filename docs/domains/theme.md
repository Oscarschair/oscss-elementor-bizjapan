# テーマ・機能ドメイン仕様書 (Theme Domain Specification)

## 1. ドメイン概要

本ドキュメントは、`oscss-elementor-bizjapan` テーマのテンプレート階層、フック仕様、および Elementor 連携時の振る舞いを定義する生きた機能仕様書です。

---

## 2. テンプレート階層と振る舞い

### 2.1 ヘッダー (`header.php` / `template-parts/header.php`)
- **Elementor Theme Builder 有効時**: `elementor_theme_do_location( 'header' )` が `true` を返した場合、Elementor 側のカスタムヘッダーを出力し、テーマ標準のヘッダー出力をスキップする。
- **フォールバック**: Elementor ヘッダー未定義時は、WordPress カスタマイザーのロゴ、サイトタイトル、および標準ナビゲーションメニューを表示。

### 2.2 フッター (`footer.php` / `template-parts/footer.php`)
- **Elementor Theme Builder 有効時**: `elementor_theme_do_location( 'footer' )` が `true` を返した場合、Elementor 側のカスタムフッターを出力する。
- **フォールバック**: コピーライトおよび標準ウィジェット/リンクを表示。

### 2.3 メインコンテンツ (`index.php` / `template-parts/archive.php` / `single.php`)
- 単一投稿・固定ページ・アーカイブ表示時、Elementor テンプレートが指定されている場合はそちらを優先。
- 標準ループ時は `the_content()` を介して Elementor のビルド済みブロックを出力。

---

## 3. アセット読み込み方針 (`includes/`)

1. **基本スタイル**:
   - `style.css` (または `style.min.css`)
   - 必要最小限のリセットとタイポグラフィのみ定義。
2. **スクリプト**:
   - 不要な jQuery 依存を排除し、Vanilla JS を基本とする。
   - Elementor 本体のフロントエンドスクリプトと重複する機能（モーダル、スライダー等）はテーマ側に二重実装しない。
