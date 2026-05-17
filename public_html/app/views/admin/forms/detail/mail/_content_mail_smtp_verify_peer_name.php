<?php /** c-formBlock01 直下 .area.is-field の内側: 通知設定 — 証明書ホスト名検証（name=mail_smtp_verify_peer_name） */ ?>
    <label class="label is-choice">
        <input type="checkbox" name="mail_smtp_verify_peer_name" value="1"
            <?= !empty($form['mail_smtp_verify_peer_name']) ? 'checked' : '' ?>>
        証明書のホスト名を検証する
    </label>
