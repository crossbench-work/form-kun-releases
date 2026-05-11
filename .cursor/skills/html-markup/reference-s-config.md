# `_s_config.scss` 参照テンプレート

プロジェクトの **`_s_config.scss` 実体**は次のブロックをコピーし、**適宜追加・編集**する（方針は同ディレクトリの `SKILL.md` の「_s_config.scss（共通設定・基準）」節）。エントリでは `@use "_s_config" as config;` とし、他ファイルから `config.$変数` / `config.vwpc(...)` / `@include config.SP { ... }` で参照する。

```scss
@use 'sass:math';
@use 'sass:meta';
// 単位を除去する関数
@function strip-unit($value) {
  @if meta.type-of($value) == 'number' and not math.is-unitless($value) {
    @return math.div($value, $value * 0 + 1);
  }
  @return $value;
}
// Breakpoints
$RES_WID_SMALL01: 768px;

$CONTENTS_WIDTH01: 1200px;
$CONTENTS_WIDTH02: 1000px;

$DEFAULT_COLOR01: #FFF;

// Fonts
$DF_FONT: "Noto Sans JP", sans-serif;

@mixin SP {
  @media (max-width: 767px) {
    @content;
  }
}

// ✅ PC：768px 以上
@mixin PC {
  @media (min-width: 768px) {
    @content;
  }
}

// vwsp: スマホ用 vw変換
@function vwsp($size) {
  $rate: math.div(100, 400);
  @return $rate * $size * 1vw;
}

// vwpc: PC用 vw変換
@function vwpc($size) {
  $rate: math.div(100, 1400);
  @return $rate * $size * 1vw;
}

// fontSp: スマホ用フォント設定
@mixin fontSp($font-size) {
  font-size: $font-size * 1px;
  font-size: vwsp($font-size);
}

// fontPc: PC用フォント設定
@mixin fontPc($font-size) {
  font-size: $font-size * 1px;
  font-size: vwpc($font-size);
}

@mixin hidden($important: false) {
  @if $important == true {
    pointer-events: none !important;
    opacity: 0 !important;
  } @else {
    pointer-events: none;
    opacity: 0;
  }
}
@mixin visible($important: false) {
  @if $important == true {
    pointer-events: auto !important;
    opacity: 1 !important;
  } @else {
    pointer-events: auto;
    opacity: 1;
  }
}

@mixin contentsWidth($width:1200, $padding: 20, $spPadding: 15) {
  padding-right: vwpc($padding);
  padding-left: vwpc($padding);
  width: $width * 1px + $padding * 2;
	width: vwpc($width + $padding * 2);
	// width: 100%;
	margin: 0 auto;
	box-sizing: border-box;

	@include SP {
		padding: 0 vwsp($spPadding);
		width: 100% !important;
	}
}

body {
	font-family: $DF_FONT;
	font-feature-settings: "palt";
  line-height: 2.6;
	letter-spacing: 0.2em;
  -webkit-text-size-adjust: 100%;

  @include SP {
    line-height: 2.6;
    letter-spacing: 0.15em;
  }
}
```

## 注意

- **`body` をこのファイルに含める場合**: エントリから複数回間接的に読み込まれるとスタイルが重複出力されることがある。プロジェクト方針に応じて `body` だけ別パーシャルに分ける選択も可。
- **767px / 768px** は `SP` / `PC` と `$RES_WID_SMALL01` の関係を揃えて保守する。
