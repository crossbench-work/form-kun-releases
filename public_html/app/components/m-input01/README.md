# m-input01（管理画面入力ラッパー）

- **SCSS**: [`_m-input01.scss`](_m-input01.scss) … `[data-module="m-input01"]` と `.m-toolbar01` 内の上書き。`public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。
- **PHP**: [`m-input01.php`](m-input01.php) … `admin_m_input01_open` / `admin_m_input01_close` に加え、属性組み立てとコントロール出力用に `admin_m_input01_attrs` / `admin_m_input01_attrs_with_bound_label`（`admin_m_bold_title01` の `id` と対になる `aria-labelledby`）/ `admin_m_input01_input` / `admin_m_input01_textarea` / `admin_m_input01_select_open` / `admin_m_input01_select_close` / `admin_m_input01_option`。`layouts/admin_components.php` 経由で `require_once`。
- **クライアントで組み立てる HTML**（例: `admin.js` のテンプレート文字列）は PHP を通さないため、従来どおり `<div data-module="m-input01">` をそのまま書く。

空・入力状態の `is-empty` / `is-filled` は `admin.js` が付与する。
