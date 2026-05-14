<h1 class="title">設定</h1>

<div data-module="c-formBlock01">
    <div class="wrapper">
        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">サイト</h2>
            </div>
            <div class="block is-field">
                <dl class="admin-settings-dl">
                    <dt>管理画面の表示名</dt>
                    <dd><code><?= e((string) config('site.admin_name', '')) ?></code></dd>
                    <dt>公開サイトのベース URL</dt>
                    <dd><?php $baseUrl = (string) config('site.base_url', ''); if ($baseUrl !== ''): ?><code><?= e($baseUrl) ?></code><?php else: ?><span class="text is-muted">未設定（相対パスのみ）</span><?php endif; ?></dd>
                    <dt>サブディレクトリ（base_path）</dt>
                    <dd><code><?= e((string) config('site.base_path', '')) ?></code>（空ならドキュメントルート直下）</dd>
                    <dt>タイムゾーン</dt>
                    <dd><code><?= e((string) config('site.timezone', '')) ?></code></dd>
                </dl>
                <p class="text is-help">上記は <code>app/config.php</code> および <code>config.local.php</code> の値です。変更後はサーバー上のファイルを編集してください。</p>
            </div>
        </div>
    </div>
</div>

<div data-module="c-formBlock01">
    <div class="wrapper">
        <div class="block is-contents">
            <div class="block is-title">
                <h2 data-module="m-largeTitle01">メール（既定）</h2>
            </div>
            <div class="block is-field">
                <dl class="admin-settings-dl">
                    <dt>送信経路</dt>
                    <dd><code><?= e((string) config('mail.transport', 'mail')) ?></code></dd>
                    <dt>差出人メール</dt>
                    <dd><code><?= e((string) config('mail.from_email', '')) ?></code></dd>
                    <dt>差出人名</dt>
                    <dd><code><?= e((string) config('mail.from_name', '')) ?></code></dd>
                    <dt>SMTP 利用</dt>
                    <dd><?= !empty(config('mail.smtp.enabled')) ? '有効' : '無効' ?></dd>
                </dl>
                <p class="text is-help">フォームごとの通知設定は各フォームの「通知」タブで上書きできます。</p>
            </div>
        </div>
    </div>
</div>
