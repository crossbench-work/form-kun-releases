# m-btn01（管理画面ボタンラッパー）

- **PHP**: [`m-btn01.php`](m-btn01.php) … `admin_m_btn01_open`（第 1 引数に `'center'` で `data-position="center"`）/ `admin_m_btn01_close`。`public_html/app/views/layouts/admin.php` で `require_once` し、各管理ビューから呼び出す。
- **SCSS**: [`_m-btn01.scss`](_m-btn01.scss) … `[data-module="m-btn01"]`。`public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。

マークアップや見た目を変えるときは **このディレクトリ内の PHP と SCSS をセットで**直すこと。
