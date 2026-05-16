# m-deleteBtn01（管理画面のコンパクト削除ボタン）

- **SCSS**: [`_m-deleteBtn01.scss`](_m-deleteBtn01.scss) … `[data-module="m-deleteBtn01"]` の見た目と、`m-formBuilder01` 内の選択肢行向けの `flex-shrink` 上書き。管理画面エントリは [`public_html/assets/scss/_admin-app.scss`](../../../assets/scss/_admin-app.scss) から `@use` で読み込む。
- **マークアップ**: `<button type="button" data-module="m-deleteBtn01" data-target="…" aria-label="削除" title="削除">` ＋ SVG 等。ビューは `public_html/app/views/admin/forms/`、`admin.js` のテンプレート文字列を参照。
