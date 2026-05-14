---
name: html-markup
description: >-
  HTML/CSS: l-/p-/m-/c- naming, SCSS entry order (@forward _s_reset, @use _s_config as config),
  Meyer-style reset baseline, no Bootstrap, assets layout, data-module patterns. Use for views/styles.
---

# HTML マークアップ規約（l- / p- / m- / c-）

## CSS / アセット方針

- **Bootstrap は使わない**。レイアウト・コンポーネントは **自前の CSS** で組む。
- **スタイルのソースは SCSS**（`scss/` で編集）。ビルド結果の **CSS は `css/` に出力**し、HTML からは **コンパイル済み `.css` のみ**参照する（開発時は watch で同期）。
- 静的ファイルのルートは **`assets/`**（公開ディレクトリからの相対はプロジェクトに合わせる。例: `public_html/assets/`）。

### `assets/` 以下のディレクトリ構成（この形を正とする）

```text
assets/
  css/           # コンパイル済み CSS（本番・配信で読み込む）
  images/
    common/      # 共通画像
    top/         # トップ等ページ別（必要に応じて sister フォルダを増やす）
  js/
  movie/
  scss/          # SCSS ソース（パーシャル・エントリを置き css へビルド）
```

- 画像パスは **`/assets/images/common/...`** のように、上記 `images` 配下を基準に書く。
- 動画は **`movie/`** に置き、参照もそこを基準にする。

### SCSS エントリの読み込み順（例: `scss/style.scss`）

**必ず次の順**にする。先頭は次の 2 行を **このまま**置く（パスはプロジェクトの `scss/` 直下に `_s_reset.scss` / `_s_config.scss` がある想定。サブフォルダに置く場合はパスだけ変える）。

```scss
@forward "_s_reset.scss";
@use "_s_config" as config;

// 以降: Component など（layout / object / utility などのパーシャルを @use / @import 方針に合わせて並べる）
```

- コンポーネント等の SCSS では **`config.vwpc(...)`**、**`@include config.SP { ... }`** のように **`config` 名前空間**で共通変数・mixin を参照する。
- ファイル名の **`_s_` プレフィックス**（例: `_s_reset.scss`, `_s_config.scss`）はプロジェクトで揃える。

### リセット CSS（基準）

**Bootstrap のリセットは使わない。** Meyer 系のベースラインは **`_s_reset.scss`（または `@forward` 先の同等ファイル）** に置き、エントリ先頭で **`@forward "_s_reset.scss";`** する。必要に応じて `box-sizing` や `img { max-width: 100%; }` などを追記してよい。

**全文テンプレート**は **[reference-s-reset.md](reference-s-reset.md)** を正とする。そこからコピーして `_s_reset.scss` に置く。

### `_s_config.scss`（共通設定・基準）

**`_s_config.scss`** に **変数・関数・mixin・`body` のベースタイポ**を集約する。プロジェクトの要件に合わせて **適宜追加・編集**する。エントリでは **`@use "_s_config" as config;`** とし、他ファイルから **`config.$変数`**、**`config.vwpc(...)`**、**`@include config.SP { ... }`** のように参照する。

**全文テンプレート**（`strip-unit`、ブレークポイント・幅・色・フォント、`SP` / `PC`、`vwsp` / `vwpc`、`fontSp` / `fontPc`、`hidden` / `visible`、`contentsWidth`、`body`）は **[reference-s-config.md](reference-s-config.md)** を正とする。そこからコピーして `_s_config.scss` に置き、必要に応じて追記する。

- **メディア**: `@include config.SP` / `@include config.PC`（コメント上は「768px 以上が PC」）。
- **可変サイズ**: `config.vwsp` / `config.vwpc`、フォントは `config.fontSp` / `config.fontPc`。
- **コンテンツ幅**: `@include config.contentsWidth(...)`（テンプレ内に `// width: 100%;` のコメント行あり）。
- **`body` を config に含める場合**: 複数経路で `@use` されると出力が重複しうる。分離したい場合は別パーシャルに切り出す（[reference-s-config.md](reference-s-config.md) の注意も参照）。

## ルートクラスの命名

接頭辞のあとに **（任意で）要素を表す語** と **役割を表す語** を **lowerCamelCase で連結**し、末尾に **連番（通常 2 桁 `01`）** を付ける。

- 形の目安: **`{接頭辞}{element?}{role}{NN}`** — **`element` は省略してよい**（特にボタン・画像など **共通で小さい塊**は短くする）。
- 例（element あり）: **`.c-topComponent01`** … `c-` + `top` + `Component` + `01`。
- 例（element 省略）: **`data-module="c-btn01"`** / **`data-module="c-image01"`** … 共通パーツとしてシンプルに付ける。

`l-` / `p-` / `m-` のクラス名も同じ考え方（例: `.l-siteHeader01`, `.p-formConfirm01`, `.m-module01`）。

## 接頭辞

| 接頭辞 | 用途 |
|--------|------|
| **l-** | レイアウト。例: `.l-layout01` |
| **p-** | ページ固有。例: `.p-topMainvisual01` |
| **m-** | 小モジュール（最小単位）。例: `.m-module01` |
| **c-** | 再利用コンポーネント。例: `.c-topComponent01` |

状態・ユーティリティは接頭辞なし可。子のバリエーションは **`is-*`**（例: `is-contents`）。

## `.block` / `.area` / `.box` と `is-*` の並べ方

- **`block` / `area` / `box` はクラス列の先頭付近**に置く。
- **`is-contents` / `is-title` など役割を表す `is-*` は、なるべくクラス列の末尾**に付ける（例: `class="block inner is-contents"` のように、装飾用の補助クラスより後ろ側を優先）。

## 子要素のクラス名（語彙・連番なし）

`c-*` 配下などで **意味のある塊**に付ける名前は、次を **優先して使う**（このレベルでは **連番 `01` 等は付けない**）。必要なら **複数形**（`.lists` など）や文脈に合わせて変形してよい。

| 優先語 | 用途の目安 |
|--------|------------|
| `.title` | 見出し |
| `.text` | 本文テキスト |
| `.icon` | アイコン |
| `.btn` | ボタン |
| `.contents` | コンテンツまとまり |
| `.image` | **画像の塊**（`img` を含むラッパー） |
| `.img` | **`img` 要素**に直接 |
| `.list` | リスト |
| `.thumbnail` | サムネイル |
| `.link` | リンク |
| `.label` | ラベル |
| `.name` | 名称表示 |
| `.card` | カード |
| `.table` | 表 |
| `.form` | フォーム全体 |
| `.input` | 入力欄 |
| `.select` | セレクト |
| `.textarea` | テキストエリア |
| `.modal` | モーダル |
| `.dialog` | ダイアログ |
| `.tab` | タブ |
| `.slide` | スライド |
| `.page` | ページ単位の塊 |

上記に当てはまらないときだけ、別名を採用する。

## `.wrapper` / `.inner`（l- / m- / c- 共通）

- 任意で **`.wrapper`**。さらに必要なら **`.wrapper` > `.inner`**。
- **管理画面パネルは、`<div data-module="c-formBlock01"><div class="wrapper"><div class="block is-contents"><div class="block is-title">...</div><div class="block is-field">...</div></div></div></div>` の形を正とし、設定 UI などの実コンテンツは `c-formBlock01` の `block is-title` / `block is-field` に分けて置く**。
- **説明文エディタのようにコンポーネント専用ラッパー（例: `.c-descriptionEditor01__editor`）を置く場合も、その直下は `<div class="wrapper" data-...><div class="block is-contents">...</div></div>` の形にし、必要なメタ属性は `wrapper` に寄せつつ、ブロック内は `area` を優先して階層を増やしすぎない**。
- **ラベル行と入力欄を 1 セットで扱うときは、`<div data-module="c-inputSet01"><div class="wrapper"><div class="block is-contents"><div class="block is-title">...</div><div class="block is-field">...</div></div></div></div>` のように component 化して、単発の行レイアウト用クラスを増やさない**。
- **複数ボタンをまとめるときは、`<div data-module="c-btnSet01"><div class="wrapper"><div class="block is-contents"><div class="area is-contents"><div data-module="m-btn01">...</div></div></div></div></div>` のように button set と各ボタンを分けて組む**。
- **フォーム編集画面の項目エディタのように繰り返しカードを並べるときは、`.c-formEditor > .wrapper > .block.is-contents` に各 `.c-formEditor__item` を並べ、各アイテムは `.c-formEditor__item > .wrapper > .block.is-header / .block.is-body` を基本にし、header 内は `c-formEditor__itemHeader`、body 内は `c-formEditor__itemBody > .wrapper > .block.is-contents` を並べる。header 内は `area is-move` / `area is-summary` / `area is-delete` に分け、サマリー表示は `c-formEditorSummary01 > .wrapper > .block.is-contents` にまとめる。各入力行は `c-formEditor__field > .wrapper > .block.is-contents > .block.is-title / .block.is-field` でラベルと入力を分ける**。

### SCSS: 直下の `.wrapper` は子セレクタで縛る

ルート（`.c-pageContact` や `[data-module="c-section01"]` など）から **`.wrapper` へは必ず直下の子結合子**で書く。子孫にまで `.wrapper` のスタイルが漏れないようにする。

```scss
.c-pageContact {
  justify-content: flex-start;
  min-height: auto;

  & > .wrapper {
    max-width: 700px; // 例: config や mixin はプロジェクトの共通を利用
    .title { /* .wrapper の直下〜子孫はここからネスト */ }
  }
}
```

- **避ける**: `.c-pageContact .wrapper { }` だけを深い階層に置き、別ツリーの `.wrapper` まで意図せず当たること。
- **推奨**: `& > .wrapper { ... }` のブロック内に、**そのラッパー専用**の子スタイルをネストする。

## `l-` / `m-`

- **l-**: **`.l-*` > `.wrapper`**。`.block` / `.area` / `.box` は **使わない**。
- **m-**: **`.m-*` > `.wrapper`**。通常はモジュール内に **`.block` / `.area` / `.box` は使わない**。
- **管理画面のパネル系コンポーネントは `data-module="c-formBlock01"` をルートに使い、内側は `.wrapper > .block.is-contents` 配下へ直接 `block is-title` / `block is-field` を置く。`componentWrapper` のような別名ラッパーや `c-panel01` の外枠は増やさない**。
- **管理画面の `input` / `select` / `textarea` は `<div data-module="m-input01">...</div>` の内側に置き、見た目は入力要素ごとの直指定ではなくこのモジュールに集約する**。
- **太字の小見出しや通常の入力ラベルは、`<p data-module="m-boldTitle01">...</p>` や `<label class="label" data-module="m-boldTitle01">...</label>` のように `m-boldTitle01` を使って作る。フォーム項目エディタ内の小さめラベルは `<label class="label" data-module="m-labelTitle01">...</label>` を使い、右側に区切り線を引く見出しは `m-borderTitle01` を使う**。
- **「必須」バッジは `<span data-module="m-required01" data-type="required">必須</span>` を正とし、`.is-required` のような見た目用 class は増やさない**。

## `c-`（コンポーネント）

- 内側だけ **`.block` > `.area` > `.box`**（必要なときだけ）。構造名のあと **末尾に `is-*`**。子の意味付けは **上記「語彙」**（`.title`, `.list` など）を優先。

```html
<div class="c-topComponent01">
  <div class="wrapper">
    <div class="block is-contents">
      <div class="area is-list">
        <div class="box is-title">
          <h2 class="title">...</h2>
          <p class="text">...</p>
        </div>
      </div>
    </div>
  </div>
</div>
```

## `p-`

- **`p-*` > `.wrapper`** を基本。細分化は **`c-`** や **`data-module`** に委ねる。

### コンポーネントが深くなりそうなときの分割

1 つの **`p-*` セクション**の中が肥大化しそうなら、**無接頭辞の `.block`** で大枠を区切り、その内側に **ページ内サブブロック** **`p-{名前}__{element}`**（BEM の **Element** 相当、`__` でつなぐ）を置き、**さらに内側 `.wrapper`** で幅・整列を切り直す。

```html
<section class="p-topComponent01">
  <div class="wrapper">
    <div class="block">
      <div class="p-component01__block">
        <div class="wrapper">
          <!-- 内側だけ別レイアウト・別 max-width -->
          <div class="area is-contents">
            <h2 class="title">...</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
```

- **`.p-component01__block`** は **そのページブロック専用の入れ子**（命名は `p-{ページブロック名}__{部位}` で統一すると追いやすい）。
- 内側の **`.wrapper`** へのスタイルも **`& > .wrapper`** で縛る（例: `.p-component01__block > .wrapper` を SCSS では `.p-component01__block { & > .wrapper { … } }`）。

## CSS（セレクタの書き方）

- `.block` / `.area` / `.box` および **`.title` / `.list` など語彙クラス**は **`.c-topComponent01 .title`** のように **必ず `c-*`（または該当ルート）配下でスコープ**する。
- **SCSS** ではルート（例: `.c-topComponent01 { & > .wrapper { … } }`）を **ネスト**して書いてよい。`.wrapper` へのスタイルは **上記どおり `& > .wrapper`** を原則とする。出力は **`assets/css/`** にまとめる。

## `data-module`（共通の見た目・レイアウトを薄く保つ）

- **複数ページで共通**になる骨格や **小さな共通パーツ**（ボタン、画像枠など）は、**クラス列を増やしすぎない**よう **`data-module` で束ねる**ことを優先してよい。
- 値の形: **`{接頭辞}{element?}{role?}{NN}`**（**element / role は省略可**。例: `c-section01`, `c-btn01`, `c-image01`, `m-btn01`, `m-image01`）。
- **最小単位の見た目**は **`m-*`**、ややまとまった **コンポーネント**は **`c-*`** と使い分けてよい（同一プロジェクト内でルールを統一する）。

### `p-` クラスとの併用（ページで「展開」するとき）

- **ページ固有のラッパーは必ず `.p-*`** をルートの **`class`** に使う。**共通の中身のスタイルは `data-module`** に寄せる。
- **悪い例**: ルートに **`class="c-pageContact"`** と **`data-module="c-pageContact01"`** を両方付けて **c- を二重に掛ける**（ページと共通の責務が混ざる）。
- **よい例**: ルートは **`class="p-pageOtherContent01" data-module="c-pageContact01"`** — ページ上の位置・余白は `.p-pageOtherContent01`、問い合わせブロック共通は `[data-module="c-pageContact01"]`。
- 小パーツの展開例: **`class="p-topBtn01" data-module="c-btn01"`** … トップだけの余白・配置は `.p-topBtn01`、ボタン共通は `[data-module="c-btn01"]`（`data-module` の値は **共通定義と SCSS を一致**させる）。

### `p-` クラスとの併用（セクション）

- **共通 shell は `data-module`、ページだけの拡張は `.p-*`** とし、**両方を同じ要素に付与**する（例: `class="p-topAbout01" data-module="c-section01"`）。SCSS では `[data-module="c-section01"]` に共通、`.p-topAbout01` に上書きのみ。

### 状態クラス

- 表示制御やアニメーション用は **`is-*`**（例: `is-inview`）を **クラス**で付け、`[data-module="..."].is-inview` のように併記する。

### マークアップ例（HTML）

**1. 共通セクションのみ `data-module`（中身は語彙クラス）**

```html
<section data-module="c-section01">
  <div class="wrapper">
    <div class="block is-contents">
      <h2 class="title">見出し</h2>
      <p class="text">本文</p>
    </div>
  </div>
</section>
```

**2. 共通 shell + ページ差分（`data-module` と `.p-*` を同一要素に）**

```html
<section class="p-topAbout01" data-module="c-section01">
  <div class="wrapper">
    <div class="block is-contents">
      <h2 class="title">このページだけの構成</h2>
    </div>
  </div>
</section>
```

- SCSS: `[data-module="c-section01"] { & > .wrapper { … } }` にレイアウト共通、`.p-topAbout01 { … }` に当ページだけの上書き。

**3. 状態クラス（Intersection 等で `is-inview` を付与）**

```html
<div class="p-topSectionTitle01 is-inview" data-module="p-topSectionTitle01">
  <span>
    <img class="img" src="..." alt="">
  </span>
</div>
```

- 初期は `is-inview` なし。ビューポート内に入ったら JS で `is-inview` を追加。CSS は `[data-module="p-topSectionTitle01"].is-inview { … }`。

**4. ページルート `.p-*` + 共通 `data-module`（問い合わせなど）**

```html
<div class="p-pageOtherContent01" data-module="c-pageContact01">
  <div class="wrapper">
    <form class="form">
      <div class="contact-form__field">
        <!-- フィールド -->
      </div>
    </form>
  </div>
</div>
```

- SCSS: **`[data-module="c-pageContact01"] { & > .wrapper { … } }`** に共通レイアウト。**`.p-pageOtherContent01 { … }`** にそのページだけの上書き。ルートの **`class` は `.p-*` を正**とし、**共通ブロック ID は `data-module` だけ**に寄せる。

**5. 小パーツの展開（`.p-*` + 短い `data-module`）**

```html
<div class="p-topBtn01" data-module="c-btn01">
  <a href="#"><span>大見出し</span><span>小見出し</span></a>
</div>
```

- SCSS は **`[data-module="c-btn01"]`** にボタン共通スタイル（下記参照）。`.p-topBtn01` は位置・余白のみ。

### SCSS 例（`data-module` セレクタ・要約）

`config` / `@include config.SP` 等はプロジェクトの共通を利用。 **`& > .wrapper`** が無いブロックでは直下に子要素を書いてよい。

**ボタン共通（HTML が `data-module="c-btn01"` ならセレクタも `c-btn01` に一致。最小パーツで `m-btn01` を使う場合も同じ構造）**

```scss
[data-module="c-btn01"] {
  width: fit-content;

  a, button {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
}
```

**画像枠共通**

```scss
[data-module="m-image01"] {
  overflow: hidden;
  background-color: #0d0639;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}
```

**HTML の `data-module` 値と SCSS の属性セレクタは必ず一致**させる（`c-btn01` ↔ `[data-module="c-btn01"]`、`m-btn01` ↔ `[data-module="m-btn01"]`）。

## JS（任意）

- **`data-module`** は CSS との共通キーとしても使える。JS で要素を拾うときも **同じ `data-module` 値**を参照してよい（必要なら `data-*` 専用の値と CSS 用を分けるルールをプロジェクトで決める）。

## 参照例

- `l-container01`, `p-topMainvisual01`, `m-img` など。
