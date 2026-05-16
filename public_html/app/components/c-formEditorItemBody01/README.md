# c-formEditorItemBody01（`.c-formEditor__itemBody` シェル）

- **PHP**: [`c-formEditorItemBody01.php`](c-formEditorItemBody01.php) … `admin_c_form_editor_item_body01_open` / `admin_c_form_editor_item_body01_close`。`.c-formEditor__itemBody` > `.wrapper` の 2 段を出力する。`layouts/admin_components.php` 経由で `require_once`。
- **SCSS**: [`_c-formEditorItemBody01.scss`](_c-formEditorItemBody01.scss) … `[data-module="c-formEditorList01"]` 内の本文レイアウトと `.is-editor-item-hidden` 時の本文 inert、`[data-module="m-formBuilder01"]` 内の `m-borderTitle01` 余白調整。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use`。
- **中身**: 複数の `.block.is-contents` や `block is-field` は呼び出し側のまま（`_field_editor.php`・`edit.php` 等）。
