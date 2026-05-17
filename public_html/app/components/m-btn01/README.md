# m-btn01（管理画面ボタンラッパー）

- **PHP**: [`m-btn01.php`](m-btn01.php) … `public_html/app/views/layouts/admin_components.php` 経由で `require_once`（`Core\View` が管理レイアウト時にビューより先に読み込む。個別ビューで単独利用する場合はそのビュー先頭で `require_once` してもよい）。
- **SCSS**: [`_m-btn01.scss`](_m-btn01.scss) … `[data-module="m-btn01"]`。`public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。

マークアップや見た目を変えるときは **このディレクトリ内の PHP と SCSS をセットで**直すこと。

## API

| 関数 | 説明 |
|------|------|
| `admin_m_btn01_open(?string $dataPosition = null)` | ラッパー開始。`'center'` で `data-position="center"` |
| `admin_m_btn01_close()` | ラッパー終了 |
| `admin_m_btn01_button(string $label, array $attrs = [])` | `<button>`（`class` に `btn` をマージ、`type` 未指定時は `button`） |
| `admin_m_btn01_link(string $label, string $href, array $attrs = [])` | `<a href="...">`（`class` に `btn` をマージ） |
| `admin_m_btn01_preview_submit(string $formactionPath, string $label = 'プレビュー')` | プレビュー用 submit（`formaction` 等）。`$formactionPath` は `base_path()` 済み URL |

`admin_m_btn01_attrs` / `admin_m_btn01_normalize_attrs` は内部用だが、属性の組み立て規則は `m-input01` の `admin_m_input01_attrs` に合わせている（`true` は論理属性）。

## 使用例

```php
<?php admin_m_btn01_open(); ?>
<?php admin_m_btn01_button('保存する', ['type' => 'submit', 'class' => 'is-primary']); ?>
<?php admin_m_btn01_close(); ?>

<?php admin_m_btn01_open(); ?>
<?php admin_m_btn01_link('キャンセル', base_path('/admin/forms')); ?>
<?php admin_m_btn01_close(); ?>

<?php admin_m_btn01_open(); ?>
<?php admin_m_btn01_preview_submit(base_path('/admin/forms/preview-input')); ?>
<?php admin_m_btn01_close(); ?>
```

ラッパー内に **button と textarea など複数子要素**がある場合（選択肢追加・複数入力エリア追加）は、`open` → `admin_m_btn01_button(...)` → 他要素 → `close` の形で閉じる。
