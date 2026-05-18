# 環境設定（env/）

DB 接続・`base_url`・`update.manifest_url` など **環境ごとに変わる値**はここで管理し、`src/` / `dist/` / リリース ZIP には含めません。

## ディレクトリ

| パス | Git | 用途 |
|------|-----|------|
| `examples/*.example` | 追跡 | 雛形（秘密情報なし） |
| `local/config.local.php` | 除外 | ローカル開発 |
| `staging/config.local.php` | 除外 | STG |
| `production/config.local.php` | 除外 | 本番 |

## ローカル開発

```bash
cp env/examples/local.php.example env/local/config.local.php
# 編集後
npm run build
npm run env:local
```

## 本番サーバー（初回のみ）

1. `npm run release` で ZIP をビルド（開発マシン）
2. `dist/` を FTP でドキュメントルートへ
3. `env/production/config.local.php` をサーバーの `app/config.local.php` として **別途**アップロード
4. 以降は管理画面「アプリ更新」（`update` ブランチの manifest）

## Git update ブランチ

配布 ZIP は `main` ではなく **`update` ブランチ**のみ。反映後は手動で `git push origin update`。

```bash
npm run release
npm run publish:update
git push origin update
```
