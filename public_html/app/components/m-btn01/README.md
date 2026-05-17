# m-btn01（管理画面ボタンラッパー）

- **PHP**: [`m-btn01.php`](m-btn01.php) … `public_html/app/views/layouts/admin_components.php` 経由で `require_once`（`Core\View` が管理レイアウト時にビューより先に読み込む。個別ビューで単独利用する場合はそのビュー先頭で `require_once` してもよい）。
- **SCSS**: [`_m-btn01.scss`](_m-btn01.scss) … `[data-module="m-btn01"]`。`public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。

マークアップや見た目を変えるときは **このディレクトリ内の PHP と SCSS をセットで**直すこと。

## API

| 関数 | 説明 |
|------|------|
| `admin_m_btn01_open(?string $dataPosition = null)` | ラッパー開始。`'center'` で `data-position="center"` |
| `admin_m_btn01_close()` | ラッパー終了 |
| `admin_m_btn01_button(string $label, array $attrs = [])` | `<button>` のみ（ラッパー内・複数子のとき） |
| `admin_m_btn01_link(string $label, string $href, array $attrs = [])` | `<a>` のみ（同上） |
| `admin_m_btn01_preview_submit(string $formactionPath, string $label = 'プレビュー')` | プレビュー用 submit のみ（同上） |
| `admin_m_btn01_button_wrap(...)` | ラッパー + `<button>` を一括（単一子） |
| `admin_m_btn01_link_wrap(...)` | ラッパー + `<a>` を一括（単一子） |
| `admin_m_btn01_preview_wrap(string $formactionPath, ...)` | ラッパー + プレビュー submit を一括 |

`admin_m_btn01_attrs` / `admin_m_btn01_normalize_attrs` は内部用。属性規則は `m-input01` の `admin_m_input01_attrs` に合わせる（`true` は論理属性）。

## 使用例

```php
<?php admin_m_btn01_button_wrap('保存する', ['type' => 'submit', 'class' => 'is-primary']); ?>

<?php admin_m_btn01_link_wrap('キャンセル', base_path('/admin/forms')); ?>

<?php admin_m_btn01_preview_wrap(base_path('/admin/forms/preview-input')); ?>
```

ラッパー内に **button と textarea など複数子要素**がある場合は `*_wrap` は使わず、`open` → `admin_m_btn01_button(...)` → 他要素 → `close` とする。
