<h1 class="title">通知設定</h1>

<?php if (!empty($errors)): ?>
    <div class="m-alert01 is-danger">
        <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= e(base_path('/admin/settings/mail')) ?>">
    <?= \Core\Csrf::field() ?>

    <div data-module="c-formBlock01">
        <div class="wrapper">
            <div class="block is-contents">
                <div class="area is-title">
                    <h2 data-module="m-largeTitle01">メール送信設定</h2>
                </div>

                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01">送信方式</label>
                    <div data-module="m-input01">
                        <select name="transport">
                            <option value="mail" <?= ($settings['transport'] ?? 'mail') === 'mail' ? 'selected' : '' ?>>PHP mail() / mb_send_mail()</option>
                            <option value="smtp" <?= ($settings['transport'] ?? '') === 'smtp' ? 'selected' : '' ?>>SMTP</option>
                        </select>
                    </div>
                </div>

                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01">差出人メールアドレス</label>
                    <div data-module="m-input01">
                        <input type="email" name="from_email" value="<?= e($settings['from_email'] ?? '') ?>" required>
                    </div>
                    <p class="text is-help">管理者通知・自動返信・パスワード再設定メールの差出人に使います。</p>
                </div>

                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01">差出人名</label>
                    <div data-module="m-input01">
                        <input type="text" name="from_name" value="<?= e($settings['from_name'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div data-module="c-formBlock01">
        <div class="wrapper">
            <div class="block is-contents">
                <div class="area is-title">
                    <h2 data-module="m-largeTitle01">SMTP 設定</h2>
                </div>

                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01">SMTPホスト</label>
                    <div data-module="m-input01">
                        <input type="text" name="smtp_host" value="<?= e($settings['smtp_host'] ?? '') ?>">
                    </div>
                </div>

                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01">SMTPポート</label>
                    <div data-module="m-input01">
                        <input type="number" name="smtp_port" value="<?= e($settings['smtp_port'] ?? '25') ?>" min="1" max="65535">
                    </div>
                </div>

                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01">暗号化方式</label>
                    <div data-module="m-input01">
                        <select name="smtp_secure">
                            <option value="" <?= ($settings['smtp_secure'] ?? '') === '' ? 'selected' : '' ?>>なし</option>
                            <option value="ssl" <?= ($settings['smtp_secure'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                            <option value="tls" <?= ($settings['smtp_secure'] ?? '') === 'tls' ? 'selected' : '' ?>>STARTTLS</option>
                        </select>
                    </div>
                </div>

                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01">SMTPユーザー名</label>
                    <div data-module="m-input01">
                        <input type="text" name="smtp_username" value="<?= e($settings['smtp_username'] ?? '') ?>" autocomplete="off">
                    </div>
                </div>

                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01">SMTPパスワード</label>
                    <div data-module="m-input01">
                        <input type="password" name="smtp_password" value="" autocomplete="new-password">
                    </div>
                    <p class="text is-help">空欄のまま保存すると現在の値を保持します。<?= !empty($settings['smtp_password_configured']) ? '現在は設定済みです。' : '現在は未設定です。' ?></p>
                    <?php if (!empty($settings['smtp_password_configured'])): ?>
                        <label class="label is-choice">
                            <input type="checkbox" name="smtp_clear_password" value="1" <?= !empty($settings['smtp_clear_password']) ? 'checked' : '' ?>>
                            保存済みのSMTPパスワードを削除する
                        </label>
                    <?php endif; ?>
                </div>

                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01">EHLO ホスト名</label>
                    <div data-module="m-input01">
                        <input type="text" name="smtp_ehlo_host" value="<?= e($settings['smtp_ehlo_host'] ?? '') ?>" placeholder="localhost">
                    </div>
                </div>

                <div class="area is-field">
                    <label class="label" data-module="m-boldTitle01">タイムアウト（秒）</label>
                    <div data-module="m-input01">
                        <input type="number" name="smtp_timeout" value="<?= e($settings['smtp_timeout'] ?? '10') ?>" min="1" max="300">
                    </div>
                </div>

                <div class="area is-field">
                    <label class="label is-choice">
                        <input type="checkbox" name="smtp_verify_peer" value="1" <?= !empty($settings['smtp_verify_peer']) ? 'checked' : '' ?>>
                        SSL 証明書を検証する
                    </label>
                </div>

                <div class="area is-field">
                    <label class="label is-choice">
                        <input type="checkbox" name="smtp_verify_peer_name" value="1" <?= !empty($settings['smtp_verify_peer_name']) ? 'checked' : '' ?>>
                        証明書のホスト名を検証する
                    </label>
                </div>

                <div class="area is-field">
                    <label class="label is-choice">
                        <input type="checkbox" name="smtp_allow_self_signed" value="1" <?= !empty($settings['smtp_allow_self_signed']) ? 'checked' : '' ?>>
                        自己署名証明書を許可する
                    </label>
                    <p class="text is-help">ローカル開発や検証用 SMTP のみで使ってください。</p>
                </div>
            </div>
        </div>
    </div>

    <div class="area is-actions">
        <button type="submit" class="btn is-primary">保存する</button>
    </div>
</form>
