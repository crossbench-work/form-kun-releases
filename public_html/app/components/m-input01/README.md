# m-input01（管理画面入力ラッパー）

- **SCSS**: コントロール見た目は [`../m-inputSet01/_m-inputSet01.scss`](../m-inputSet01/_m-inputSet01.scss) に集約（`_admin-app.scss` は `m-inputSet01` を `@use`）。
- **PHP**: [`m-input01.php`](m-input01.php) … `admin_m_input01_open` / `admin_m_input01_close`。`layouts/admin_components.php` 経由で `require_once`。
- **クライアントで組み立てる HTML**（例: `admin.js` のテンプレート文字列）は PHP を通さないため、従来どおり `<div data-module="m-input01">` をそのまま書く。

空・入力状態の `is-empty` / `is-filled` は `admin.js` が付与する。
