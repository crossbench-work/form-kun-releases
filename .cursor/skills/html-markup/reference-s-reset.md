# `_s_reset.scss` 参照テンプレート

**Bootstrap のリセットは使わない。** プロジェクトの **`_s_reset.scss`（または `@forward` 先の同等ファイル）** には、次のブロックをコピーして全ページの土台とする。必要に応じて `box-sizing` や `img { max-width: 100%; }` などを追記してよい。

方針・エントリでの読み込み順は同ディレクトリの [SKILL.md](SKILL.md) の「リセット CSS（基準）」「SCSS エントリの読み込み順」を参照する。

```scss
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed,
figure, figcaption, footer, header, hgroup,
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
  margin: 0;
  padding: 0;
  border: 0;
  font: inherit;
  font-size: 100%;
  vertical-align: baseline;
}

html {
  line-height: 1;
}

ol, ul {
  list-style: none;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
}

caption, th, td {
  text-align: left;
  font-weight: normal;
  vertical-align: middle;
}

q, blockquote {
  quotes: none;
}
q:before, q:after, blockquote:before, blockquote:after {
  content: "";
  content: none;
}

a img {
  border: none;
}

article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
  display: block;
}

img {
  vertical-align: bottom;
  border: none;
}

button,
input,
select,
textarea {
  vertical-align: middle;
}

input {
  outline: 0;
}

button,
input {
  overflow: visible;
  line-height: normal;
}

button::-moz-focus-inner,
input::-moz-focus-inner {
  padding: 0;
  border: 0;
}

button,
html input[type="button"],
input[type="reset"],
input[type="submit"] {
  cursor: pointer;
  -webkit-appearance: button;
}

label,
select,
button,
input[type="button"],
input[type="reset"],
input[type="submit"],
input[type="radio"],
input[type="checkbox"] {
  cursor: pointer;
}

textarea {
  overflow: auto;
  vertical-align: top;
  font-family: sans-serif;
}

button {
  background: none;
  border: none;
  padding: 0;
  font: inherit;
  color: inherit;
  cursor: pointer;
}

a {
  text-decoration: none;
  color: inherit;
}
```
