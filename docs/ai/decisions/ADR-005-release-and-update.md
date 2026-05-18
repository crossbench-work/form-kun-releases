# ADR-005: リリース配布と管理画面からの自己更新

## 状況

- 共有レンタルサーバー（FTP、サーバー上 git 不可）
- 開発は `main`、配布は **`update` ブランチ**にまとめて手動 push
- 環境設定は `env/` で管理し ZIP に含めない

## 決定

1. `src/` → `dist/` ビルド、リリース ZIP は `releases/`
2. `scripts/protected-paths.json` でビルド・更新時の保護パスを共有
3. 本番は `config.local.php` の `update.manifest_url` から manifest を取得し ZIP 展開（`Core\AppUpdater`）

## 結果

- FTP の代わりに管理画面から更新可能（有効化時）
- `main` の開発中コードが誤って本番に出ない（update ブランチのみ参照）
