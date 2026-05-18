# c-formEditorItemHeader01（`.c-formEditor__itemHeader` シェル）

- **PHP**: [`c-formEditorItemHeader01.php`](c-formEditorItemHeader01.php) … `admin_c_form_editor_item_header01_open` / `admin_c_form_editor_item_header01_close`。`.c-formEditor__itemHeader` > `.wrapper` > `.block.is-contents` の 3 段を出力する。`layouts/admin_components.php` 経由で `require_once`。
- **SCSS**: [`_c-formEditorItemHeader01.scss`](_c-formEditorItemHeader01.scss) … `[data-module="c-formEditorList01"]` 内の `.c-formEditor__itemHeader` と直下レイアウト（`area.is-move` / `is-summary` / `is-delete`）。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use`。
- **中身**: ドラッグハンドル・サマリー・表示切替／削除ボタン等は呼び出し側のまま（`_field_editor.php`・`edit.php` 等）。
