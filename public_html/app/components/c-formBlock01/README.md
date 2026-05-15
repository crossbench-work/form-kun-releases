# c-formBlock01（管理画面パネル）

- **PHP**: [`c-formBlock01.php`](c-formBlock01.php) … `admin_c_form_block01_open`（任意で `.block.is-contents` に属性配列） / `admin_c_form_block01_close`。ビューでは `require_once APP_COMPONENTS_DIR . '/c-formBlock01/c-formBlock01.php';` のあとに呼び出す。
- **SCSS**: [`_c-formBlock01.scss`](_c-formBlock01.scss) … `[data-module="c-formBlock01"]`。管理画面エントリは `public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。

マークアップや見た目を変えるときは **このディレクトリ内の PHP と SCSS をセットで**直すこと。
