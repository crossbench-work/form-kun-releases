# フォーム管理システム

PHP（vanilla）+ MySQL 製のフォームビルダー型 Web アプリケーションです。
共有レンタルサーバー（FTPアップロードのみで運用するタイプ）でも動作するように設計しています。

## 主な機能

- 管理画面で複数フォームを GUI 作成（基本入力・選択・日時・ファイル・バリデーション）
- 公開フォームに「入力 → 確認画面 → 送信」のフロー
- 送信履歴の一覧（フリーワード検索 / 日付範囲絞り込み / ページング）と詳細表示
- 添付ファイルの認証付きダウンロード（直リンク不可）
- 管理者へのメール通知 / 送信者への自動返信メール（テンプレート変数対応）
- スパム対策（簡易計算CAPTCHA + ハニーポット）
- 管理画面の複数ユーザー（admin / editor）管理
- ブルートフォース対策（連続失敗ロック）

## 動作要件

- PHP 8.0 以上
- MySQL 5.7 以上 もしくは MariaDB 10.2 以上
- Apache + `mod_rewrite`（推奨。なくても動作可。後述）
- 必要な PHP 拡張: `pdo_mysql`, `mbstring`, `fileinfo`, `json`, `session`
- メール送信: `mb_send_mail()` または `mail()` が利用可能であること

## ディレクトリ構成

```
public_html/
├── .htaccess               フロントコントローラ用
├── index.php               フロント側エントリ
├── admin/index.php         管理画面エントリ
├── uploads/                アップロード保存先（直アクセス禁止）
├── assets/{css,js}/        CSS / JS
├── router.php              ルータ入口（PHP組み込みサーバー / nginx のフォールバック）
├── dev-router.php          ルーティング処理本体
├── nginx.local.example.conf  Local / nginx 向け設定の例（.htaccess 代替）
├── app/                    アプリ本体（直アクセス禁止）
│   ├── config.php          DB / メール / アップロード設定
│   ├── config.local.php    任意：ローカル上書き（Git 対象外）
│   ├── config.local.example.php  上記のテンプレート
│   ├── bootstrap.php
│   ├── core/               コアクラス群
│   ├── controllers/
│   ├── models/
│   └── views/
└── sql/schema.sql          DB スキーマ
```

## 初期セットアップ手順

1. レンタルサーバーの管理画面（phpMyAdmin など）で MySQL データベースを作成します。
2. 作成した DB に [`public_html/sql/schema.sql`](public_html/sql/schema.sql) をインポートします。
3. [`public_html/app/config.php`](public_html/app/config.php) を編集します。最低限以下を設定してください。
   - `db.host` / `db.name` / `db.user` / `db.password`
   - `site.base_url`（公開URL。例: `https://example.com`）
   - `site.base_path`（サブディレクトリ運用時のみ。例: `/formkun`）
   - `mail.from_email` / `mail.from_name`
4. `public_html/` 以下を FTP でサーバーの公開ディレクトリにアップロードします。
   - 例: `public_html/` の中身をそのまま `~/public_html/` にアップロード
5. `public_html/uploads/` のディレクトリパーミッションをサーバーの規定に合わせて変更します（多くのレンタルサーバーで `705` または `707`）。
6. ブラウザで `https://（あなたのドメイン）/admin/` にアクセスします。
   - 初期管理者がいない場合は自動でセットアップ画面に遷移します。
   - ユーザー名・パスワードを入力して最初の admin アカウントを作成してください。
7. ログイン後、左上「フォーム」から最初のフォームを作成し、状態を「公開中」にしてください。
8. 公開URL `https://（あなたのドメイン）/f/（スラッグ）` を案内します。

## ローカル開発とデバッグ（Cursor / VS Code）

### ローカル設定ファイル

共有の [`public_html/app/config.php`](public_html/app/config.php) はそのままにし、**マシン固有の値だけ** [`public_html/app/config.local.example.php`](public_html/app/config.local.example.php) をコピーして使います。

```bash
cp public_html/app/config.local.example.php public_html/app/config.local.php
```

`config.local.php` は `.gitignore` 済みです。少なくとも以下を環境に合わせてください。

- `'debug' => true` … 画面に PHP エラー・未捕捉例外のスタックトレースを表示（本番では必ずオフ）
- `db.*` … ローカル MySQL のホスト・ユーザー・パスワード・DB名
- `site.base_url` … 開発サーバーの URL（例: `http://127.0.0.1:8080`）

### PHP 組み込み Web サーバー（Apache なし）

Apache の `mod_rewrite` は使われません。**ルーター** [`public_html/router.php`](public_html/router.php) がフロント／管理画面へ振り分けます（実装は [`public_html/dev-router.php`](public_html/dev-router.php)）。

リポジトリのルートで:

```bash
./scripts/dev-server.sh
```

または同等のコマンド:

```bash
php -S 127.0.0.1:8080 -t public_html public_html/router.php
```

- 公開トップ: `http://127.0.0.1:8080/`
- 管理画面: `http://127.0.0.1:8080/admin/`

Cursor の「タスクの実行」から **「form-kun: PHP dev server (8080)」** でも起動できます（`.vscode/tasks.json`）。

### Local.app（Local by Flywheel）・nginx でエラーになるとき

**Local.app のサイトルートは、`public_html` の中身がドキュメントルートになるようにしてください。**  
リポジトリ全体をフォルダごとドロップすると、`public_html` が一段挟まり URL がずれたり、ルーティングが効かず **404 / 真っ白 / nginx のエラー** になりやすいです。

| 環境 | ルーティング |
| --- | --- |
| **Apache（mod_rewrite + AllowOverride）** | [`public_html/.htaccess`](public_html/.htaccess) が `/` と `/admin/` を `index.php` / `admin/index.php` に渡す。**対応済み** |
| **nginx（Local の既定など）** | `.htaccess` は読まれません。**追加設定が必要**です |

nginx / Local では、サイト設定でドキュメントルートを `.../form-kun/public_html` にしたうえで、`try_files` のフォールバックを **`/router.php`** に向けます。コピー用の例を [`public_html/nginx.local.example.conf`](public_html/nginx.local.example.conf) に置きました。

```nginx
location / {
    try_files $uri $uri/ /router.php?$is_args$args;
}
```

あわせて [`public_html/app/config.local.example.php`](public_html/app/config.local.example.php) を `config.local.php` にコピーし、Local が表示している URL に合わせて **`site.base_url`** を設定してください（例: `http://form-kun.local` や `https://form-kun.local`）。

データベースは Local の「Database」タブにホスト名・ユーザー・パスワード・DB名が出ます。それを `config.local.php` の `db` に転記し、[`public_html/sql/schema.sql`](public_html/sql/schema.sql) をその DB にインポートしてください。

PHP は **8.0 以上** を選んでください（Local のサイト設定でバージョン変更できる場合があります）。

### Xdebug でブレークポイント（ステップ実行）

1. PHP に **Xdebug 3** をインストールし、`php.ini` に例えば次を追加します（パスは `php --ini` で確認）。

   ```ini
   zend_extension=xdebug.so
   xdebug.mode=debug
   xdebug.start_with_request=yes
   xdebug.client_port=9003
   ```

2. 拡張機能 **PHP Debug**（`xdebug.php-debug`）を入れるとよいです（`.vscode/extensions.json` で推奨指定済み）。

3. **開発サーバーを起動した状態で**、サイドバー「実行とデバッグ」から **「Xdebug: listen (form-kun)」** を開始します。

4. ソースにブレークポイントを置き、ブラウザで該当ページを開くと停止します。

ポートが競合する場合は `9003` と `launch.json` の `port` を揃えて変更してください。

## サブディレクトリ運用について

ドメイン直下ではなく `https://example.com/forms/` で運用する場合：

- `public_html/` の中身を `forms/` ディレクトリにアップロード
- `app/config.php` の `site.base_path` に `'/forms'` を設定

## mod_rewrite が無効な環境向けのフォールバック

`.htaccess` が無効、もしくは `mod_rewrite` が利用できない場合でも以下の URL で動作します。

- 公開フォーム: `index.php?path=f/（スラッグ）`
- 管理画面: `admin/index.php?path=login`

## メール送信について

- 既定では PHP 標準の `mb_send_mail()` を使用します。
- レンタルサーバーで `mail()` が無効化されている / SMTP 必須の場合は、[`public_html/app/core/Mailer.php`](public_html/app/core/Mailer.php) の `send()` 内を SMTP ライブラリ（PHPMailer 等）に差し替えてください。

## メールテンプレートで使える変数

| 変数 | 説明 |
| --- | --- |
| `{{field_key}}` | 各フォーム項目の値（フィールドキーで参照） |
| `{{__all__}}`   | 全項目を「ラベル: 値」改行区切りで展開 |
| `{{__form_title__}}` | フォームタイトル |
| `{{__submitted_at__}}` | 受信日時 |
| `{{__ip__}}` | 送信元 IP アドレス |

例:
```
{{name}} 様

お問い合わせいただきありがとうございました。
以下の内容で承りました。

{{__all__}}
```

## セキュリティ

実装済みの主な対策:

- パスワード: `password_hash(PASSWORD_DEFAULT)` / `password_verify`
- セッション: HttpOnly Cookie / SameSite=Lax / 厳格モード / ログイン時のID再生成
- DB: PDO のプリペアドステートメントを徹底
- 出力: テンプレートで `e()` ヘルパによる `htmlspecialchars` 必須
- CSRF: 全 POST リクエストでトークン検証
- スパム対策: 計算式 CAPTCHA + ハニーポット
- アップロード: 拡張子ホワイトリスト + MIME 検査 + サイズ上限 + `uploads/` 直リンク禁止 + PHP 実行禁止
- ログイン保護: 同一IPからの連続失敗で一時ロック
- 機密ディレクトリ: `app/` `sql/` `uploads/` を `.htaccess` で保護
- 共通ヘッダ: `X-Content-Type-Options: nosniff` / `X-Frame-Options: SAMEORIGIN` / `Referrer-Policy`

追加の推奨事項:

- 管理画面の URL は IP 制限することを推奨します（[`public_html/admin/.htaccess`](public_html/admin/.htaccess) にサンプルあり）。
- HTTPS を必須にしてください（Let's Encrypt 等）。

## トラブルシュート

### 「データベースに接続できませんでした」と表示される

- `app/config.php` の DB 接続情報を再確認してください。
- レンタルサーバーで指定された DB ホスト名（`mysql123.example.jp` など `localhost` 以外のことが多い）になっているか確認してください。

### 管理画面に入ろうとすると 404

- `mod_rewrite` が無効な可能性があります。`admin/index.php?path=login` で直接アクセスして動作するか確認してください。

### ファイルアップロードに失敗する

- `app/config.php` の `upload.max_size` と PHP の `upload_max_filesize` / `post_max_size` の上限を確認してください。
- `public_html/uploads/` の書き込み権限を確認してください。

### メールが届かない

- まずレンサバ提供の `noreply@（自ドメイン）` 形式を `mail.from_email` に設定してください。多くの共有サーバーは差出人ドメインを自社ドメインに限定しています。
- 迷惑メール扱いされていないか確認してください。

### 「セッションが切れています」が出る

- 長時間放置するとセッションが切れます。再読み込みしてやり直してください。
- 同一ブラウザの別タブで管理画面と公開フォームを行き来した直後にも稀に発生する場合があります。

## 既知の制限（次フェーズ向け）

- CSV エクスポートは未実装
- 送信ステータス管理（未対応 / 対応中 / 完了）は未実装
- フォーム設定の変更履歴は未実装
- 多言語切替は未対応（日本語固定）
- SMTP 送信は標準では未対応（差し替え可）

## ライセンス

社内/個別運用向けのため、ライセンスは指定していません。利用条件は配布元にお問い合わせください。
