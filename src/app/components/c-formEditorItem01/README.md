# c-formEditorItem01（`.c-formEditor__item` シェル）

- **PHP**: [`c-formEditorItem01.php`](c-formEditorItem01.php) … `admin_c_form_editor_item01_open` / `admin_c_form_editor_item01_close`。`.c-formEditor__item` の開閉と、`is-collapsed` / `is-editor-item-hidden` の制御を共通化する。`layouts/admin_components.php` 経由で `require_once`。
- **SCSS**: [`_c-formEditorItem01.scss`](_c-formEditorItem01.scss) … `[data-module="c-formEditorList01"]` 配下のカード外枠・ソートプレースホルダ・折りたたみ表示。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use`。
