# m-dragHandle01（管理画面のドラッグハンドル）

- **PHP**: [`m-dragHandle01.php`](m-dragHandle01.php) … `admin_m_drag_handle01($dataTarget)`。`layouts/admin_components.php` 経由で `require_once`（管理レイアウト利用時は `Core\View` がビューより先に読み込む）。
- **SCSS**: [`_m-dragHandle01.scss`](_m-dragHandle01.scss) … `[data-module="c-formEditorList01"]` 内の `[data-module="m-dragHandle01"]`。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use` で読み込む。
- **ラッパー**: 並び替え用の `div.box.is-handle.ui-sortable-handle`（`title="ドラッグして並べ替え"` 等）は呼び出し側に残す。
- **`admin.js`** のテンプレート文字列は PHP を通さないため、**`admin_m_drag_handle01` と同じ属性・SVG** をそのまま書くこと。
