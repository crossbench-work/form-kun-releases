# ADR-001: アプリケーションモジュール構成（public_html/app）

## ステータス

採用

## コンテキスト

共有レンタルサーバー上で、ドキュメントルートを `public_html/` に限定しつつ、アプリロジックを Web から直接参照されない場所に置きたい。

## 決定

- **ルーティング入口**: `public_html/index.php`（公開）、`public_html/admin/index.php`（管理）。
- **アプリ本体**: `public_html/app/`（`bootstrap.php` → `core/` → `controllers/` / `models/` / `views/`）。
- **設定**: `app/config.php` とオプションの `config.local.php`。

## 結果

- `public_html/.htaccess` 等で `app/` への直アクセスを拒否しやすい。
- Docker / 本番の差分は主に `config.local.php` と環境変数で吸収する。

## 関連

- [`AGENTS.md`](../../../AGENTS.md)
