# c-fieldTypeModal01（フィールド型選択モーダル）

- **PHP**: [`c-fieldTypeModal01.php`](c-fieldTypeModal01.php) … `admin_c_field_type_modal01()` が [`c-fieldTypeModal01_markup.php`](c-fieldTypeModal01_markup.php) を読み込む。`layouts/admin_components.php` 経由で `require_once`。
- **SCSS**: [`_c-fieldTypeModal01.scss`](_c-fieldTypeModal01.scss) … `[data-module="c-fieldTypeModal01"]` と `body.is-field-type-modal-open`。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use`。
- **`data-target`**（`m-formBuilder01__typeModal` 等）は `public_html/assets/js/admin.js` が参照するため変更しない。
