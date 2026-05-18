<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — SSL証明書検証（name=mail_smtp_verify_peer） */ ?>
    <label class="label is-choice">
        <input type="checkbox" name="mail_smtp_verify_peer" value="1" <?= !empty($form['mail_smtp_verify_peer']) ? 'checked' : '' ?>>
        SSL 証明書を検証する
    </label>
