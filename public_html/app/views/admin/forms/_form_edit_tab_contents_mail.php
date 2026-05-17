<?php /** フォーム編集:「通知」タブパネル */ ?>
<div data-target="c-formEditTabs01__contents" data-tab-panel="mail" class="area is-contents" role="tabpanel"
    aria-label="通知" hidden>
    <?php admin_c_form_block01_open(); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('通知設定'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('送信方式'), 'mail_transport'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_select_open(admin_m_input01_attrs_with_bound_label('mail_transport', [
                'id' => 'mail_transport',
                'name' => 'mail_transport',
            ])); ?>
            <?php admin_m_input01_option('mail', 'PHP mail() / mb_send_mail()', ($form['mail_transport'] ?? 'mail') === 'mail'); ?>
            <?php admin_m_input01_option('smtp', 'SMTP', ($form['mail_transport'] ?? '') === 'smtp'); ?>
            <?php admin_m_input01_select_close(); ?>
            <?php admin_m_input01_close(); ?>
        </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('差出人メールアドレス'), 'mail_from_email'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_from_email', [
            'type' => 'email',
            'id' => 'mail_from_email',
            'name' => 'mail_from_email',
            'value' => (string) ($form['mail_from_email'] ?? ''),
            'placeholder' => 'noreply@example.com',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('差出人名'), 'mail_from_name'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_from_name', [
            'type' => 'text',
            'id' => 'mail_from_name',
            'name' => 'mail_from_name',
            'value' => (string) ($form['mail_from_name'] ?? ''),
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('SMTPホスト'), 'mail_smtp_host'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_host', [
            'type' => 'text',
            'id' => 'mail_smtp_host',
            'name' => 'mail_smtp_host',
            'value' => (string) ($form['mail_smtp_host'] ?? ''),
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('SMTPポート'), 'mail_smtp_port'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_port', [
            'type' => 'number',
            'id' => 'mail_smtp_port',
            'name' => 'mail_smtp_port',
            'value' => (string) ($form['mail_smtp_port'] ?? '25'),
            'min' => 1,
            'max' => 65535,
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('暗号化方式'), 'mail_smtp_secure'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_select_open(admin_m_input01_attrs_with_bound_label('mail_smtp_secure', [
            'id' => 'mail_smtp_secure',
            'name' => 'mail_smtp_secure',
        ])); ?>
        <?php admin_m_input01_option('', 'なし', ($form['mail_smtp_secure'] ?? '') === ''); ?>
        <?php admin_m_input01_option('ssl', 'SSL', ($form['mail_smtp_secure'] ?? '') === 'ssl'); ?>
        <?php admin_m_input01_option('tls', 'STARTTLS', ($form['mail_smtp_secure'] ?? '') === 'tls'); ?>
        <?php admin_m_input01_select_close(); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('SMTPユーザー名'), 'mail_smtp_username'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_username', [
            'type' => 'text',
            'id' => 'mail_smtp_username',
            'name' => 'mail_smtp_username',
            'value' => (string) ($form['mail_smtp_username'] ?? ''),
            'autocomplete' => 'off',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('SMTPパスワード'), 'mail_smtp_password'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_password', [
            'type' => 'password',
            'id' => 'mail_smtp_password',
            'name' => 'mail_smtp_password',
            'value' => '',
            'autocomplete' => 'new-password',
        ])); ?>
        <?php admin_m_input01_close(); ?>
        <p class="text is-help">
            空欄のまま保存すると現在の値を保持します。<?= !empty($form['mail_smtp_password_configured']) ? '現在は設定済みです。' : '現在は未設定です。' ?></p>
        <?php if (!empty($form['mail_smtp_password_configured'])): ?>
            <label class="label is-choice">
                <input type="checkbox" name="mail_smtp_clear_password" value="1"
                    <?= !empty($form['mail_smtp_clear_password']) ? 'checked' : '' ?>>
                保存済みのSMTPパスワードを削除する
            </label>
        <?php endif; ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('EHLO ホスト名'), 'mail_smtp_ehlo_host'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_ehlo_host', [
            'type' => 'text',
            'id' => 'mail_smtp_ehlo_host',
            'name' => 'mail_smtp_ehlo_host',
            'value' => (string) ($form['mail_smtp_ehlo_host'] ?? ''),
            'placeholder' => 'localhost',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('タイムアウト（秒）'), 'mail_smtp_timeout'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_smtp_timeout', [
            'type' => 'number',
            'id' => 'mail_smtp_timeout',
            'name' => 'mail_smtp_timeout',
            'value' => (string) ($form['mail_smtp_timeout'] ?? '10'),
            'min' => 1,
            'max' => 300,
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <label class="label is-choice">
        <input type="checkbox" name="mail_smtp_verify_peer" value="1" <?= !empty($form['mail_smtp_verify_peer']) ? 'checked' : '' ?>>
        SSL 証明書を検証する
    </label>
</div>
<div class="area is-field">
    <label class="label is-choice">
        <input type="checkbox" name="mail_smtp_verify_peer_name" value="1"
            <?= !empty($form['mail_smtp_verify_peer_name']) ? 'checked' : '' ?>>
        証明書のホスト名を検証する
    </label>
</div>
<div class="area is-field">
    <label class="label is-choice">
        <input type="checkbox" name="mail_smtp_allow_self_signed" value="1"
            <?= !empty($form['mail_smtp_allow_self_signed']) ? 'checked' : '' ?>>
        自己署名証明書を許可する
    </label>
    <p class="text is-help">ローカル開発や検証用 SMTP のみで使ってください。</p>
</div>
<?php admin_c_form_block01_close(); ?>

<?php admin_c_form_block01_open(); ?>
<div class="area is-title">
    <?php admin_m_large_title01('管理者通知メール'); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('送信先メールアドレス（カンマ区切りで複数可）'), 'admin_emails'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('admin_emails', [
            'type' => 'text',
            'id' => 'admin_emails',
            'name' => 'admin_emails',
            'value' => (string) $form['admin_emails'],
            'placeholder' => 'admin@example.com, sub@example.com',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('件名'), 'mail_subject_admin'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('mail_subject_admin', [
            'type' => 'text',
            'id' => 'mail_subject_admin',
            'name' => 'mail_subject_admin',
            'value' => (string) $form['mail_subject_admin'],
            'placeholder' => '[{{__form_title__}}] 新規送信',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('表示設定'), 'mail_template_admin'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label('mail_template_admin', [
            'id' => 'mail_template_admin',
            'name' => 'mail_template_admin',
            'rows' => 6,
        ]), (string) $form['mail_template_admin']); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('使用可能な変数')); ?>
    </div>
    <div class="area is-field">
        <div class="c-copyTagList01">
            <div class="c-copyTagList01__items">
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{field_key}}"><code>{{field_key}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__all__}}"><code>{{__all__}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__form_title__}}"><code>{{__form_title__}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__submitted_at__}}"><code>{{__submitted_at__}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__ip__}}"><code>{{__ip__}}</code></button>
            </div>
        </div>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<?php admin_c_form_block01_close(); ?>

<?php admin_c_form_block01_open(); ?>
<div class="area is-title">
    <?php admin_m_large_title01('送信者への自動返信メール'); ?>
</div>
<div class="area is-field">
    <label class="label is-choice">
        <input type="checkbox" name="reply_enabled" value="1" <?= !empty($form['reply_enabled']) ? 'checked' : '' ?>>
        自動返信を有効にする
    </label>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('送信先のフィールドキー（メール型項目）'), 'reply_from_field_key'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('reply_from_field_key', [
            'type' => 'text',
            'id' => 'reply_from_field_key',
            'name' => 'reply_from_field_key',
            'value' => (string) $form['reply_from_field_key'],
            'placeholder' => 'メールアドレス項目のフィールドキー（UUID または任意のキー）',
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('件名'), 'reply_subject'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('reply_subject', [
            'type' => 'text',
            'id' => 'reply_subject',
            'name' => 'reply_subject',
            'value' => (string) $form['reply_subject'],
        ])); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('表示設定'), 'reply_template'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input01_open(); ?>
        <?php admin_m_input01_textarea(admin_m_input01_attrs_with_bound_label('reply_template', [
            'id' => 'reply_template',
            'name' => 'reply_template',
            'rows' => 6,
        ]), (string) $form['reply_template']); ?>
        <?php admin_m_input01_close(); ?>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<div class="area is-field">
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('使用可能な変数')); ?>
    </div>
    <div class="area is-field">
        <div class="c-copyTagList01">
            <div class="c-copyTagList01__items">
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{field_key}}"><code>{{field_key}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__all__}}"><code>{{__all__}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__form_title__}}"><code>{{__form_title__}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__submitted_at__}}"><code>{{__submitted_at__}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__ip__}}"><code>{{__ip__}}</code></button>
            </div>
        </div>
    </div>
    <?php admin_m_input_set01_close(); ?>
</div>
<?php admin_c_form_block01_close(); ?>
</div>