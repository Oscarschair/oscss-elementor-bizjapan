# プロジェクトドキュメント (Docs)

本ディレクトリは、`oscss-elementor-bizjapan`（BizJapan Elementor WordPress Theme）のアーキテクチャ設計書、機能仕様書（Specs）、決定ログ（ADR）、ドメイン設計書、および運用手順書を体系的に管理する公式ドキュメント階層です。

---

## 📜 プロジェクト開発・ドキュメント運用憲章 (Project Charter)

1. **第 1 条: 正本はコード (Code is Single Source of Truth)**
   ソースコード（PHPテンプレート、CSS、JS）が常に一次情報（正本）である。コードとドキュメントに不整合が生じた場合は、実行コードを絶対正とする。
2. **第 2 条: ドキュメントは「なぜ」と「不変条件」に特化する (Document the "Why" and Invariants)**
   ドキュメントにはコード単体では表現しきれない「ビジネスの背景」「設計思想」「セキュリティ制約」「用語の定義」「不変条件」に特化して記述する。
3. **第 3 条: PR 同梱の同時更新 ＆ 日本語記述 (Definition of Done & Japanese PR)**
   機能追加・修正時には、対応する `docs/specs/`、`docs/domains/`、および `docs/adr/` の更新を PR マージの必須条件 (Definition of Done) とし、コードとドキュメントの剥離（ドリフト）を 100% 遮断する。また、PR（タイトル・概要・対応内容）は基本的に日本語で作成する。
4. **第 4 条: アーキテクチャ変更の ADR 記録義務 (Mandatory ADR Trail)**
   技術スタックの選定・変更、データモデルの改編、セキュリティ方針の変更などの重要な意思決定は、必ず `docs/adr/NNNN-<title>.md` として標準フォーマットで記録を残す。

---

## 📚 ドキュメント構成一覧

### 1. 全体ガイド & アーキテクチャ
| ファイル | 内容・役割 |
| :--- | :--- |
| [aidd-guide.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/aidd-guide.md) | AI駆動開発（AIDD）の実践フロー・品質ゲート運用ガイド |
| [architecture.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/architecture.md) | 全体構成・モジュール構成・テーマ構造・セキュリティ設計書 |
| [deployment.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/deployment.md) | LOLIPOP環境の配置情報・自動デプロイ手順書 |

### 2. 仕様書 (Spec-Driven Development: `docs/specs/`)
| 仕様書 | 内容・要件 |
| :--- | :--- |
| [_TEMPLATE.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/specs/_TEMPLATE.md) | 仕様書（Given-When-Then / AC）起票用標準テンプレート |
| [0001-bizjapan-elementor-theme.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/specs/0001-bizjapan-elementor-theme.md) | テーマ基盤・Elementor連携・自動デプロイの基礎仕様書 |
| [0002-seo-zhhk-localization-spec.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/specs/0002-seo-zhhk-localization-spec.md) | SEO最適化・香港標準書面語 (zh-HK) ・モバイルCTA仕様書 |
| [0003-lolipop-deployment-spec.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/specs/0003-lolipop-deployment-spec.md) | ロリポップ自動デプロイ・SSH Paramiko・キャッシュパージ仕様書 |
| [0004-contact-form-email-spec.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/specs/0004-contact-form-email-spec.md) | 自社製お問い合わせフォーム及びメール通知 (contact@oscarchair.jp) 仕様書 |
| [0005-responsive-design-spec.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/specs/0005-responsive-design-spec.md) | マルチデバイス完全レスポンシブデザイン仕様書 |

### 3. アーキテクチャ意思決定ログ (ADR: `docs/adr/`)
| ADR | 決定内容 |
| :--- | :--- |
| [_TEMPLATE.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/adr/_TEMPLATE.md) | ADR起票用標準テンプレート |
| [0001-init-theme-architecture.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/adr/0001-init-theme-architecture.md) | Hello Elementor ベースのテーマ構造採用と初期構成の決定 |
| [0002-zh-hk-seo-localization.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/adr/0002-zh-hk-seo-localization.md) | 香港向け標準書面語 (zh-HK) 最適化及び SEO・モバイルCTA基盤の導入 |
| [0003-lolipop-paramiko-cache-purge-deploy.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/adr/0003-lolipop-paramiko-cache-purge-deploy.md) | ロリポップにおける SSH+Paramiko 自動デプロイ及びキャッシュパージ方式の採用 |
| [0004-mobile-floating-cta-strategy.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/adr/0004-mobile-floating-cta-strategy.md) | 香港市場に特化したモバイル固定 WhatsApp / 無料相談バー (Sticky CTA) 導入の決定 |
| [0005-custom-contact-form-system.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/adr/0005-custom-contact-form-system.md) | Googleフォームからテーマ内蔵自社製Webフォーム（青基調）への移行及び自動メール通知の決定 |
| [0006-responsive-optimization.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/adr/0006-responsive-optimization.md) | 専用レスポンシブスタイルシートと Fluid Typography による完全レスポンシブ化の決定 |

### 4. ドメイン・機能詳細仕様書 (`docs/domains/`)
| ドメイン設計書 | 内容 |
| :--- | :--- |
| [theme.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/domains/theme.md) | テーマテンプレート階層・フック仕様 |
| [content-zhhk-optimization.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/domains/content-zhhk-optimization.md) | 香港繁体字（zh-HK）Webサイト・コンテンツ完全置換仕様書 |
| [seo-schema.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/domains/seo-schema.md) | SEOメタタグ及び構造化データ（Service / FAQPage）詳細設計書 |
| [mobile-cta.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/domains/mobile-cta.md) | モバイル固定コンバージョンバー（Sticky Bar）詳細設計書 |
| [funding-bud-emf.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/domains/funding-bud-emf.md) | 香港政府中小企業海外展開支援基金（BUD / EMF）連携設計書 |

### 5. 運用手順書 (`docs/ops/`)
| 運用書 | 内容 |
| :--- | :--- |
| [maintenance.md](file:///c:/Users/user/git/oscss-elementor-bizjapan/docs/ops/maintenance.md) | WordPress / Elementor 定常保守・トラブルシューティング手順書 |
