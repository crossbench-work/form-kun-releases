# m-inputSet01（ラベル＋入力セット）

- **PHP**: [`m-inputSet01.php`](m-inputSet01.php) … `admin_m_input_set01_open` / `admin_m_input_set01_close`。`layouts/admin_components.php` で `require_once` し、フォーム編集など管理ビューから呼び出す。
- **SCSS**: [`_m-inputSet01.scss`](_m-inputSet01.scss) … `[data-module="m-inputSet01"]` のタイトル行レイアウトのみ。入力欄の見た目は **m-input01** の [`_m-input01.scss`](../m-input01/_m-input01.scss) を `_admin-app.scss` から `@use` して読み込む。
