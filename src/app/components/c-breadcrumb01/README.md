# c-breadcrumb01（管理画面パンくず）

- **PHP**: [`c-breadcrumb01.php`](c-breadcrumb01.php) … `admin_c_breadcrumb01`。ビューでは `require_once APP_COMPONENTS_DIR . '/c-breadcrumb01/c-breadcrumb01.php';` のあとに呼び出す。
- **SCSS**: [`_c-breadcrumb01.scss`](_c-breadcrumb01.scss) … `[data-target="c-breadcrumb01"]`（ルートは **data-module ではなく data-target**）。管理画面エントリは `public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。

マークアップや見た目を変えるときは **このディレクトリ内の PHP と SCSS をセットで**直すこと。
