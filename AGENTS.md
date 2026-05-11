# AI エージェント向け共通指示（form-kun）

このファイルを **AI 作業の正** とする。詳細ルール・履歴・ADR は `docs/ai/` を参照する。

## プロジェクト概要

- **スタック**: PHP 8+（素の PHP）、MySQL / MariaDB、Apache（`mod_rewrite` 想定）。フロントは Bootstrap なし、**SCSS → CSS**（`npm run build:css`）。
- **公開ルート**: `public_html/`（`index.php` / `admin/index.php` / `assets/` / `app/`）。
- **設定**: `public_html/app/config.php`、ローカル上書きは `config.local.php`（Git 対象外）。

## 守ること

1. **変更は依頼範囲に限定**する。無関係なリファクタやドキュメントの乱立を避ける。
2. **ユーザー向け文言は日本語**を既定とする（コードコメント・コミット説明もプロジェクトに合わせる）。
3. **セキュリティ**: CSRF・認可・パスワードハッシュ・ファイルダウンロード経路を壊さない。秘密情報をコミットしない。
4. **マークアップ / スタイル**: Cursor スキル [`.cursor/skills/html-markup/SKILL.md`](.cursor/skills/html-markup/SKILL.md)（`l-` / `p-` / `m-` / `c-`、`data-module`、SCSS エントリ順）に沿う。
5. **CSS 編集**: ソースは `public_html/assets/scss/`。ビルド後の `assets/css/*.css` をコミットする運用なら、変更後に `npm run build:css` を実行する。

## 参照パス

| 用途 | パス |
|------|------|
| 共有ルール（人間・AI 兼用の補足） | [`docs/ai/shared-rules.md`](docs/ai/shared-rules.md) |
| 意思決定ログ（ADR） | [`docs/ai/decisions/`](docs/ai/decisions/) |
| 作業・リリース単位の変更メモ | [`docs/ai/changelog/`](docs/ai/changelog/) |
| プロンプト雛形 | [`docs/ai/prompts/`](docs/ai/prompts/) |
| 仕様メモ置き場 | [`docs/specs/`](docs/specs/) |
| HTML/CSS 規約スキル | [`.cursor/skills/html-markup/`](.cursor/skills/html-markup/) |

## 変更を記録するとき

- **軽い変更**（1 セッション・小さな修正）: `docs/ai/changelog/YYYY-MM-DD.md` に 1 ファイル追記、またはその日のファイルへ箇条書きで追加する。
- **設計に影響する判断**: `docs/ai/decisions/` に ADR を追加し、changelog からリンクする。

## Copilot / その他ツール

- GitHub Copilot は [`.github/copilot-instructions.md`](.github/copilot-instructions.md) から本ファイルを指す。
