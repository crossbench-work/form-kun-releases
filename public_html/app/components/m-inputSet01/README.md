# m-inputSet01（ラベル＋入力セット）

- **PHP**: [`m-inputSet01.php`](m-inputSet01.php) … `admin_m_input_set01_open` / `admin_m_input_set01_close`。`layouts/admin_components.php` で `require_once` し、フォーム編集など管理ビューから呼び出す。
- **SCSS**: [`_m-inputSet01.scss`](_m-inputSet01.scss) … `[data-module="m-inputSet01"]` のタイトル行レイアウトと、`[data-module="m-input01"]` 内のフォームコントロール見た目（`.m-toolbar01` 内の上書き含む）。`public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。
