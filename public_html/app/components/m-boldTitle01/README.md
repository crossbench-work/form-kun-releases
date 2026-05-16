# m-boldTitle01

- **SCSS**: [`_m-boldTitle01.scss`](_m-boldTitle01.scss) … `_admin-app.scss` から `@use`。
- **PHP**: [`m-boldTitle01.php`](m-boldTitle01.php) … `admin_m_bold_title01` / `admin_m_bold_title01_aria_labelledby_attr`。`public_html/app/views/layouts/admin.php` で `require_once` し、管理ビューから呼び出す。
- 旧 `<label class="label" data-module="m-boldTitle01" for="…">` は `<p data-module="m-boldTitle01">` に置き換え。入力と結ぶ場合は第 2 引数に入力の `id` と同じ文字列を渡し、入力に `<?= admin_m_bold_title01_aria_labelledby_attr('そのid') ?>` を付与する。
