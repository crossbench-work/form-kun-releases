# m-table01（管理画面テーブル）

- **PHP**: [`m-table01.php`](m-table01.php) … `admin_m_table01_open` / `admin_m_table01_thead` / `admin_m_table01_close` など。ビューでは `require_once APP_COMPONENTS_DIR . '/m-table01/m-table01.php';` のあとに呼び出す。
- **SCSS**: [`_m-table01.scss`](_m-table01.scss) … `[data-module="m-table01"]`。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use` で読み込む。

マークアップや見た目を変えるときは **このディレクトリ内の PHP と SCSS をセットで**直すこと。
