# m-input01（管理画面入力ラッパー）

- **SCSS**: [`_m-input01.scss`](_m-input01.scss) … `[data-module="m-input01"]` と `.m-toolbar01` 内の上書き。`public_html/assets/scss/_admin-app.scss` から `@use` で読み込む。
- **マークアップ**: ビューでは `<div data-module="m-input01">` で `input` / `select` / `textarea` を包む（空・入力状態は `admin.js` が `is-empty` / `is-filled` を付与）。
