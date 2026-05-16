<?php /** フォーム編集:「通知」タブパネル */ ?>
        <div data-target="c-formEditTabs01__contents" data-tab-panel="mail" class="area is-contents" role="tabpanel" aria-label="通知" hidden>
<?php admin_c_form_block01_open(); ?>
            <div class="block is-title">
                <?php admin_m_large_title01('通知設定'); ?>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('送信方式'), 'mail_transport'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <select id="mail_transport" name="mail_transport"<?= admin_m_bold_title01_aria_labelledby_attr('mail_transport') ?>>
                                        <option value="mail" <?= ($form['mail_transport'] ?? 'mail') === 'mail' ? 'selected' : '' ?>>PHP mail() / mb_send_mail()</option>
                                        <option value="smtp" <?= ($form['mail_transport'] ?? '') === 'smtp' ? 'selected' : '' ?>>SMTP</option>
                                    </select>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('差出人メールアドレス'), 'mail_from_email'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="email" id="mail_from_email" name="mail_from_email" value="<?= e($form['mail_from_email'] ?? '') ?>" placeholder="noreply@example.com"<?= admin_m_bold_title01_aria_labelledby_attr('mail_from_email') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('差出人名'), 'mail_from_name'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="mail_from_name" name="mail_from_name" value="<?= e($form['mail_from_name'] ?? '') ?>"<?= admin_m_bold_title01_aria_labelledby_attr('mail_from_name') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('SMTPホスト'), 'mail_smtp_host'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="mail_smtp_host" name="mail_smtp_host" value="<?= e($form['mail_smtp_host'] ?? '') ?>"<?= admin_m_bold_title01_aria_labelledby_attr('mail_smtp_host') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('SMTPポート'), 'mail_smtp_port'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="number" id="mail_smtp_port" name="mail_smtp_port" value="<?= e($form['mail_smtp_port'] ?? '25') ?>" min="1" max="65535"<?= admin_m_bold_title01_aria_labelledby_attr('mail_smtp_port') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('暗号化方式'), 'mail_smtp_secure'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <select id="mail_smtp_secure" name="mail_smtp_secure"<?= admin_m_bold_title01_aria_labelledby_attr('mail_smtp_secure') ?>>
                                        <option value="" <?= ($form['mail_smtp_secure'] ?? '') === '' ? 'selected' : '' ?>>なし</option>
                                        <option value="ssl" <?= ($form['mail_smtp_secure'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                        <option value="tls" <?= ($form['mail_smtp_secure'] ?? '') === 'tls' ? 'selected' : '' ?>>STARTTLS</option>
                                    </select>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('SMTPユーザー名'), 'mail_smtp_username'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="mail_smtp_username" name="mail_smtp_username" value="<?= e($form['mail_smtp_username'] ?? '') ?>" autocomplete="off"<?= admin_m_bold_title01_aria_labelledby_attr('mail_smtp_username') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('SMTPパスワード'), 'mail_smtp_password'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="password" id="mail_smtp_password" name="mail_smtp_password" value="" autocomplete="new-password"<?= admin_m_bold_title01_aria_labelledby_attr('mail_smtp_password') ?>>
                                <?php admin_m_input01_close(); ?>
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
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('EHLO ホスト名'), 'mail_smtp_ehlo_host'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="mail_smtp_ehlo_host" name="mail_smtp_ehlo_host" value="<?= e($form['mail_smtp_ehlo_host'] ?? '') ?>" placeholder="localhost"<?= admin_m_bold_title01_aria_labelledby_attr('mail_smtp_ehlo_host') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('タイムアウト（秒）'), 'mail_smtp_timeout'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="number" id="mail_smtp_timeout" name="mail_smtp_timeout" value="<?= e($form['mail_smtp_timeout'] ?? '10') ?>" min="1" max="300"<?= admin_m_bold_title01_aria_labelledby_attr('mail_smtp_timeout') ?>>
                                <?php admin_m_input01_close(); ?>
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
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('送信先メールアドレス（カンマ区切りで複数可）'), 'admin_emails'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="admin_emails" name="admin_emails" value="<?= e($form['admin_emails']) ?>" placeholder="admin@example.com, sub@example.com"<?= admin_m_bold_title01_aria_labelledby_attr('admin_emails') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('件名'), 'mail_subject_admin'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="mail_subject_admin" name="mail_subject_admin" value="<?= e($form['mail_subject_admin']) ?>" placeholder="[{{__form_title__}}] 新規送信"<?= admin_m_bold_title01_aria_labelledby_attr('mail_subject_admin') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('表示設定'), 'mail_template_admin'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <textarea id="mail_template_admin" name="mail_template_admin" rows="6"<?= admin_m_bold_title01_aria_labelledby_attr('mail_template_admin') ?>><?= e($form['mail_template_admin']) ?></textarea>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('使用可能な変数')); ?>
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
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('送信先のフィールドキー（メール型項目）'), 'reply_from_field_key'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="reply_from_field_key" name="reply_from_field_key" value="<?= e($form['reply_from_field_key']) ?>" placeholder="メールアドレス項目のフィールドキー（UUID または任意のキー）"<?= admin_m_bold_title01_aria_labelledby_attr('reply_from_field_key') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('件名'), 'reply_subject'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <input type="text" id="reply_subject" name="reply_subject" value="<?= e($form['reply_subject']) ?>"<?= admin_m_bold_title01_aria_labelledby_attr('reply_subject') ?>>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('表示設定'), 'reply_template'); ?>
                            </div>
                            <div class="block is-field">
                                <?php admin_m_input01_open(); ?>
                                    <textarea id="reply_template" name="reply_template" rows="6"<?= admin_m_bold_title01_aria_labelledby_attr('reply_template') ?>><?= e($form['reply_template']) ?></textarea>
                                <?php admin_m_input01_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block is-field">
                <?php admin_c_input_set01_open(); ?>
                            <div class="block is-title">
                                <?php admin_m_bold_title01(e('使用可能な変数')); ?>
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
