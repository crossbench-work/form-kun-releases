# c-inputSet01（ラベル＋入力セット）

- **PHP**: [`c-inputSet01.php`](c-inputSet01.php) … `admin_c_input_set01_open` / `admin_c_input_set01_close`。ビューでは `require_once APP_COMPONENTS_DIR . '/c-inputSet01/c-inputSet01.php';` のあとに呼び出す（フォーム編集は `edit.php` で一度読み込めば、インクルード先の partial からも利用可）。
- **SCSS**: [`_c-inputSet01.scss`](_c-inputSet01.scss) … `[data-module="c-inputSet01"]`。管理画面エントリは `public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。

マークアップや見た目を変えるときは **このディレクトリ内の PHP と SCSS をセットで**直すこと。
