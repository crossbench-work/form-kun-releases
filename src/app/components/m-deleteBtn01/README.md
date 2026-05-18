# m-deleteBtn01（管理画面のコンパクト削除ボタン）

- **PHP**: [`m-deleteBtn01.php`](m-deleteBtn01.php) … `admin_m_delete_btn01($dataTarget, $ariaLabel?, $title?)`。`layouts/admin_components.php` 経由で `require_once`（管理レイアウト利用時は `Core\View` がビューより先に読み込む）。
- **SCSS**: [`_m-deleteBtn01.scss`](_m-deleteBtn01.scss) … `[data-module="m-deleteBtn01"]` の見た目と、`m-formBuilder01` 内の選択肢行向けの `flex-shrink` 上書き。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use` で読み込む。
- **クライアント生成 HTML**（`admin.js` のテンプレート文字列）は PHP を通さないため、**`admin_m_delete_btn01` と同じ属性・SVG** をそのまま書くこと（コメントで対応関係を示す）。
