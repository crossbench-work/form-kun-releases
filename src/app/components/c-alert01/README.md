# c-alert01（アラート）

- **PHP**: [`c-alert01.php`](c-alert01.php) … `admin_c_alert01_open` / `admin_c_alert01_close`。`data-module="c-alert01"` の外枠を出力する。`layouts/admin_components.php` 経由で `require_once`。
- **SCSS**: [`_c-alert01.scss`](_c-alert01.scss) … エラー表示の見た目（背景・文字色・リスト余白）。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use`。
