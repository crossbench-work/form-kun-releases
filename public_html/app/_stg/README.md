# ステージング／本番向け設定の作業用フォルダ

`bootstrap.php` が読むのは **`app/config.php`** と **`app/config.local.php`** だけです。この `_stg/` 内のファイルは **自動では読み込まれません**。

## どれを編集するか

| ファイル | 役割 |
|----------|------|
| **`config.php`** | 共通の既定値（メール文面・セッション名など）。本番でもリポジトリと揃えてよいことが多い。 |
| **`config.local.php`**（または下記の例から作成） | **DB 接続・`base_url`・`debug`** など環境ごと。ここにパスワードを書く運用を推奨。 |

本番では **`config.local.php` だけ**をサーバー用に用意し、`config.php` はリポジトリと同じ内容のまま、という分け方が安全です。

## FileZilla でのアップロード先

編集したあと、サーバー上では次の **2 ファイルとして `app/` の直下**に置き換えます（`_stg` フォルダごと公開ルートに置かないでください）。

- `app/config.php` ← ここで編集した ` _stg/config.php` を上書き
- `app/config.local.php` ← `config.local.example.php` をコピーして作ったファイルをリネームして上書き

`config.local.php` が無い場合でも `config.php` だけで動きます（そのときは `config.php` 内の `db` 等を本番値に直す必要があります）。

## `config.local.php` の作り方

1. `config.local.example.php` をコピーして `config.local.php` にリネームする。  
2. `db.user` / `db.password` / `site.base_url` などをサーバー環境に合わせて埋める。  
3. 本番では **`debug` は `false`** のままにする。

`config.local.php` は `.gitignore` に含め、**パスワードをコミットしない**でください。
