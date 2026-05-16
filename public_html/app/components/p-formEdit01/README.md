# p-formEdit01（フォーム編集ページ）

- **PHP**: [`p-formEdit01.php`](p-formEdit01.php) … `admin_p_form_edit01_floating_actions(bool $isNew)` が [`p-formEdit01_floating_actions.php`](p-formEdit01_floating_actions.php) を読み込む。`layouts/admin_components.php` で `m-btn01.php` の **後** に `require_once`（`admin_m_btn01_*` が必要）。
- **SCSS**: [`_p-formEdit01__floatingActions.scss`](_p-formEdit01__floatingActions.scss) … `.p-formEdit01` 内の `.p-formEdit01__floatingActions` とモバイル用 `@media`。`.p-formEdit01 > .wrapper > .block.bottom` 等は従来どおり `_admin-app.scss` に残す。
- **`data-target`**（`p-formEdit01__dirtyActions` 等）は `admin.js` が参照するため変更しない。
