<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — 自己署名許可（name=mail_smtp_allow_self_signed） */ ?>
    <label class="label is-choice">
        <input type="checkbox" name="mail_smtp_allow_self_signed" value="1"
            <?= !empty($form['mail_smtp_allow_self_signed']) ? 'checked' : '' ?>>
        自己署名証明書を許可する
    </label>
    <p class="text is-help">ローカル開発や検証用 SMTP のみで使ってください。</p>
