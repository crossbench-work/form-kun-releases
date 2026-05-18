# m-largeTitle01（管理画面セクション見出し）

- **PHP**: [`m-largeTitle01.php`](m-largeTitle01.php) … `admin_m_large_title01`。ビューでは `require_once APP_COMPONENTS_DIR . '/m-largeTitle01/m-largeTitle01.php';` のあとに呼び出す。
- **SCSS**: [`_m-largeTitle01.scss`](_m-largeTitle01.scss) … `[data-module="m-largeTitle01"]`。管理画面エントリは `public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。

マークアップや見た目を変えるときは **このディレクトリ内の PHP と SCSS をセットで**直すこと。
