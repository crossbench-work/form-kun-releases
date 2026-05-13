# ADR-004: 管理フォーム編集の `c-formEditor` 周辺 DOM

## ステータス

採用

## コンテキスト

フォーム項目エディタ（`c-formEditor`）と、その周辺のパネル（`c-formBlock01` / `c-inputSet01`）で、`area` と `block` の使い分けが混在するとセレクタと意図が読み取りづらくなる。並べ替え対象のリストや静的ブロックのラッパーも、共通の `block.is-contents` 規則に揃えたい。

## 決定

- **`c-formEditor__item` の骨格**は次の階層を正とする。  
  `.c-formEditor__item` > `.wrapper` > `.block.is-header` > `.c-formEditor__itemHeader`、および `.block.is-body` > `.c-formEditor__itemBody` > `.wrapper` > `.block.is-contents`（複数可）> 見出し・フィールド行。
- **`c-formBlock01` のパネル本文**（`> .wrapper > .block.is-contents` 直下）で、見出し行と入力ブロックのラッパーは **`block is-title` / `block is-field`** とする（従来の `area is-title` / `area is-field` から移行）。説明文エディタ（`c-descriptionEditor01`）の外側ラッパーも同じく `block is-field` でよい。
- **並べ替えリスト**（`data-target="m-formBuilder01__fields"`）および **規約同意などの静的 `c-formEditor`** 内の単一ラッパーは **`block is-contents`** とし、`.c-formEditor > .wrapper > .block.is-contents` 前提の SCSS と一致させる。
- **`area`** はタブパネル（`c-formEditTabs01` の `area is-contents`）、説明文ブロック内の `area is-header` / `area is-controls` など、**別コンポーネント規約で既に固定されている箇所**に限り継続利用する。

## 結果

- `_admin-app.scss` では `[data-module="c-formBlock01"]` / `[data-module="c-inputSet01"]` の子として `.block.is-title` / `.block.is-field` を `.area` と並列に指定する。
- JS は主に `data-target` / `data-module` で参照するため、クラス変更の影響は限定的。

## 関連

- [`AGENTS.md`](../../../AGENTS.md)
- [`docs/ai/changelog/2026-05-13.md`](../changelog/2026-05-13.md)
