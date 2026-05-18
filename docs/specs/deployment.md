# デプロイ・更新手順

## 初回（本番）

1. 開発マシン: `npm run build` → `dist/`
2. FTP: `dist/` の内容をドキュメントルートへ
3. FTP: `env/production/config.local.php` を `app/config.local.php` として配置（ZIP に含めない）
4. `uploads/` はサーバー上で書き込み可能に

## リリース（update ブランチ）

```bash
npm run release
npm run publish:update
git push origin update
```

## 本番で管理画面更新

`env/production/config.local.php` で `update.enabled` と `update.manifest_url` を設定。
