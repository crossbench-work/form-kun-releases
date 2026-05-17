<?php /** c-formBlock01 直下 .area.is-field の内側: 自動返信 — 有効化（name=reply_enabled） */ ?>
    <label class="label is-choice">
        <input type="checkbox" name="reply_enabled" value="1" <?= !empty($form['reply_enabled']) ? 'checked' : '' ?>>
        自動返信を有効にする
    </label>
