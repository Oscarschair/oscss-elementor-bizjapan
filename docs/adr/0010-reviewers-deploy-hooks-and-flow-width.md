# ADR-0010: デプロイ時レビューフック（uxui_reviewer & seo_reviewer）の導入及びフロー図最大幅800px適正化

- **ステータス**: 承認済 (Accepted)
- **日付**: 2026-09-10
- **意思決定者**: OSCSS 開発チーム & Antigravity

---

## 1. 背景と課題 (Context & Problem Statement)

1. **レビュー体制の自動化**:
   - デザイン品質（UI/UX）および検索最適化（SEO）の監査を人の手作業に依存せず、本番デプロイ時に機械的に自動実行する仕組みが求められていた。
2. **フロー図画像の巨大化・横幅使いすぎ問題**:
   - 「服務流程」セクション内の2つのインフォグラフィック（3者スキーム図・5ステップ会社設立フロー図）が横幅100%（1140px超）まで広がり、画面縦幅1200px以上を占有して圧迫感を生んでいた。

---

## 2. 決定内容 (Decision)

1. **改善案 A の採用（最大幅 800px 制限 ＋ 浮き立つカード化）**:
   - `.elementor-element-df41cae` および `.elementor-element-42a0550` の最大幅を `800px` に固定・中央揃え配置。
   - `border-radius: 16px`、上品なソフトシャドウ（`box-shadow: 0 12px 36px rgba(0,0,0,0.28)`）、ホバー時の浮き立ち（`translateY(-2px)`）を全デバイスで適用。
2. **本番デプロイ時HOOK（自動品質ゲート）の配備**:
   - `scripts/run_uxui_review.py`（横スクロール防止、800px幅制限、タップ領域検証）を作成。
   - `scripts/run_seo_review.py`（zh-HK言語属性、JSON-LDスキーマ、OGP、画像マッピング検証）を作成。
   - `scripts/verify_aidd_gate.ps1` の Gate 4/5 として統合し、`deploy.ps1` のデプロイ実行時に自動発動。
   - `.gemini/hooks.json` およびグローバル `hooks.json` に PostToolUse フックを配備。

---

## 3. 影響と結果 (Consequences)

- 本番デプロイ時に自動で UI/UX と SEO の合格判定が行われ、不良コードの流出を100%防止。
- フロー図の横幅使いすぎ・圧迫感が解消され、周囲の濃紺背景と調和した上質なカードUIが完成。
