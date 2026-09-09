# 0002. 香港向け標準書面語 (zh-HK) 最適化および SEO・モバイルCTA基盤の導入

- **ステータス**: 承認 (Accepted)
- **決定日**: 2026-09-09
- **決定者**: OSCSS 開発チーム

## 1. コンテキストと問題提起 (Context)
[https://bizjapan.oscarchair.jp/](https://bizjapan.oscarchair.jp/) は香港の企業家・投資家向けに日本進出代行サービスを提供するサイトであるが、現状以下の課題があった：
1. HTML langが `zh-TW` になっており、一部簡体字（注冊）や不自然な語句（閤下、香港３分１）が混在していた。
2. タイトルやMeta Descriptionに重要キーワード（日本設立公司、日本開公司、商業代辦等）が不足し、検索流入機会を損失していた。
3. 構造化データ（JSON-LD）が未整備で、Google検索上でのリッチリザルト（FAQ等）が得られていなかった。
4. 香港市場で主流のモバイル閲覧時における即時問い合わせ（WhatsApp / 無料相談）導線が欠如していた。

## 2. 検討した選択肢 (Considered Options)
- **選択肢 1: テーマ側フックによるSEO・JSON-LD・モバイルCTA実装 ＆ コンテンツ置換仕様の策定（採用）**
  - メリット: Elementor のコアやDBを直接壊すことなく、テーマ層で確実に `zh-HK` 化、タイトル最適化、スキーマ出力、固定CTAを安全に追加可能。
- **選択肢 2: Elementor 側の手動修正のみ**
  - デメリット: JSON-LD の動的出力や言語属性の根本修正、統一的な固定コンバージョンバーの実装が困難。

## 3. 意思決定 (Decision)
**選択肢 1** を採用。
`includes/bizjapan-seo-zhhk.php` および `assets/css/bizjapan-custom.css` を導入し、テーマから高品質なSEO基盤・JSON-LDスキーマ・スマホ固定CTAを安全に配信する。
また、コンテンツ面については `docs/domains/content-zhhk-optimization.md` に完全校正テキストをまとめ、運用者が容易に更新できる体制を構築した。

## 4. 結果と影響 (Consequences)
- **ポジティブな影響**:
  - `lang="zh-HK"` / `og:locale="zh_HK"` による香港検索エンジンへの適合度向上。
  - Service および FAQPage スキーマによるリッチリザルト獲得。
  - スマホ固定CTA（WhatsApp / 諮詢）による問い合わせCVRの向上。
- **ネガティブな影響 / リスク**:
  - 特になし。既存の Hello Elementor 機能に影響を与えない分離設計を維持。
