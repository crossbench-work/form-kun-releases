# m-formBuilder01（フォームビルダー）

- **PHP**: [`m-formBuilder01.php`](m-formBuilder01.php) … `admin_m_form_builder01_description_template()` が [`m-formBuilder01_description_template.php`](m-formBuilder01_description_template.php) を読み込み、`<template data-target="m-formBuilder01__descriptionTemplate">` を出力する。`layouts/admin_components.php` 経由で `require_once`（`admin.js` は `data-target` で参照するため値は変えない）。
- **SCSS**: [`_m-formBuilder01__descriptionTemplate.scss`](_m-formBuilder01__descriptionTemplate.scss) … `[data-module="c-descriptionEditor01"]` 周り（ツールバー行・サマリー・表示切替）。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use`。
- **フィールド型テンプレ**（`m-formBuilder01__fieldTemplate`）は別作業で切り出し可。
