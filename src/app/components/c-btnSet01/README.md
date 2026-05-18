# c-btnSet01（ボタンセット）

- **PHP**: [`c-btnSet01.php`](c-btnSet01.php) … `admin_c_btn_set01_open` / `admin_c_btn_set01_close`。`data-module="c-btnSet01"` の外枠を出力する。`layouts/admin_components.php` 経由で `require_once`。
- **SCSS**: [`_c-btnSet01.scss`](_c-btnSet01.scss) … `[data-module="c-btnSet01"] > .wrapper > .block.is-contents` の横並びレイアウト。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use`。
