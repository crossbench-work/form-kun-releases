# m-input01（管理画面入力ラッパー）

- **SCSS**: [`_m-input01.scss`](_m-input01.scss) … `[data-module="m-input01"]` と `.m-toolbar01` 内の上書き。`public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。
- **PHP**: [`m-input01.php`](m-input01.php) … `admin_m_input01_open` / `admin_m_input01_close`。`layouts/admin_components.php` 経由で `require_once`。
- **クライアントで組み立てる HTML**（例: `admin.js` のテンプレート文字列）は PHP を通さないため、従来どおり `<div data-module="m-input01">` をそのまま書く。

空・入力状態の `is-empty` / `is-filled` は `admin.js` が付与する。
