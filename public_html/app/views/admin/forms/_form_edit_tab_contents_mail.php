<?php /** フォーム編集:「通知」タブパネル */ ?>
        <div data-target="c-formEditTabs01__contents" data-tab-panel="mail" class="area is-contents" role="tabpanel" aria-label="通知" hidden>
<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('通知設定'); ?>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">送信方式</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <select name="mail_transport">
                                        <option value="mail" <?= ($form['mail_transport'] ?? 'mail') === 'mail' ? 'selected' : '' ?>>PHP mail() / mb_send_mail()</option>
                                        <option value="smtp" <?= ($form['mail_transport'] ?? '') === 'smtp' ? 'selected' : '' ?>>SMTP</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">差出人メールアドレス</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="email" name="mail_from_email" value="<?= e($form['mail_from_email'] ?? '') ?>" placeholder="noreply@example.com">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">差出人名</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="mail_from_name" value="<?= e($form['mail_from_name'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">SMTPホスト</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="mail_smtp_host" value="<?= e($form['mail_smtp_host'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">SMTPポート</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="number" name="mail_smtp_port" value="<?= e($form['mail_smtp_port'] ?? '25') ?>" min="1" max="65535">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">暗号化方式</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <select name="mail_smtp_secure">
                                        <option value="" <?= ($form['mail_smtp_secure'] ?? '') === '' ? 'selected' : '' ?>>なし</option>
                                        <option value="ssl" <?= ($form['mail_smtp_secure'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                        <option value="tls" <?= ($form['mail_smtp_secure'] ?? '') === 'tls' ? 'selected' : '' ?>>STARTTLS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">SMTPユーザー名</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="mail_smtp_username" value="<?= e($form['mail_smtp_username'] ?? '') ?>" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">SMTPパスワード</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="password" name="mail_smtp_password" value="" autocomplete="new-password">
                                </div>
                                <p class="text is-help">空欄のまま保存すると現在の値を保持します。<?= !empty($form['mail_smtp_password_configured']) ? '現在は設定済みです。' : '現在は未設定です。' ?></p>
                                <?php if (!empty($form['mail_smtp_password_configured'])): ?>
                                    <label class="label is-choice">
                                        <input type="checkbox" name="mail_smtp_clear_password" value="1" <?= !empty($form['mail_smtp_clear_password']) ? 'checked' : '' ?>>
                                        保存済みのSMTPパスワードを削除する
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">EHLO ホスト名</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="mail_smtp_ehlo_host" value="<?= e($form['mail_smtp_ehlo_host'] ?? '') ?>" placeholder="localhost">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">タイムアウト（秒）</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="number" name="mail_smtp_timeout" value="<?= e($form['mail_smtp_timeout'] ?? '10') ?>" min="1" max="300">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <label class="label is-choice">
                    <input type="checkbox" name="mail_smtp_verify_peer" value="1" <?= !empty($form['mail_smtp_verify_peer']) ? 'checked' : '' ?>>
                    SSL 証明書を検証する
                </label>
            </div>
            <div class="block is-field">
                <label class="label is-choice">
                    <input type="checkbox" name="mail_smtp_verify_peer_name" value="1" <?= !empty($form['mail_smtp_verify_peer_name']) ? 'checked' : '' ?>>
                    証明書のホスト名を検証する
                </label>
            </div>
            <div class="block is-field">
                <label class="label is-choice">
                    <input type="checkbox" name="mail_smtp_allow_self_signed" value="1" <?= !empty($form['mail_smtp_allow_self_signed']) ? 'checked' : '' ?>>
                    自己署名証明書を許可する
                </label>
                <p class="text is-help">ローカル開発や検証用 SMTP のみで使ってください。</p>
            </div>
                        </div>
                    </div>
                </div>

<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('管理者通知メール'); ?>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">送信先メールアドレス（カンマ区切りで複数可）</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="admin_emails" value="<?= e($form['admin_emails']) ?>" placeholder="admin@example.com, sub@example.com">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">件名</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="mail_subject_admin" value="<?= e($form['mail_subject_admin']) ?>" placeholder="[{{__form_title__}}] 新規送信">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">表示設定</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <textarea name="mail_template_admin" rows="6"><?= e($form['mail_template_admin']) ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">使用可能な変数</label>
                            </div>
                            <div class="block is-field">
                                <div class="c-copyTagList01">
                                    <div class="c-copyTagList01__items">
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{field_key}}"><code>{{field_key}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__all__}}"><code>{{__all__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__form_title__}}"><code>{{__form_title__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__submitted_at__}}"><code>{{__submitted_at__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__ip__}}"><code>{{__ip__}}</code></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                        </div>
                    </div>
                </div>

<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('送信者への自動返信メール'); ?>
            </div>
            <div class="block is-field">
                <label class="label is-choice">
                    <input type="checkbox" name="reply_enabled" value="1" <?= !empty($form['reply_enabled']) ? 'checked' : '' ?>>
                    自動返信を有効にする
                </label>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">送信先のフィールドキー（メール型項目）</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="reply_from_field_key" value="<?= e($form['reply_from_field_key']) ?>" placeholder="メールアドレス項目のフィールドキー（UUID または任意のキー）">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">件名</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <input type="text" name="reply_subject" value="<?= e($form['reply_subject']) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">表示設定</label>
                            </div>
                            <div class="block is-field">
                                <div data-module="m-input01">
                                    <textarea name="reply_template" rows="6"><?= e($form['reply_template']) ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <div data-module="c-inputSet01">
                    <div class="wrapper">
                        <div class="block is-contents">
                            <div class="block is-title">
                                <label class="label" data-module="m-boldTitle01">使用可能な変数</label>
                            </div>
                            <div class="block is-field">
                                <div class="c-copyTagList01">
                                    <div class="c-copyTagList01__items">
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{field_key}}"><code>{{field_key}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__all__}}"><code>{{__all__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__form_title__}}"><code>{{__form_title__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__submitted_at__}}"><code>{{__submitted_at__}}</code></button>
                                        <button type="button" class="c-copyTagList01__tag" data-copy-text="{{__ip__}}"><code>{{__ip__}}</code></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
