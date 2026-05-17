<?php
/** フォーム編集:「通知」タブパネル
 * _content_*.php … c-formBlock01 直下 .area.is-field の内側。各ファイル先頭コメントにフィールドの説明あり。
 */
?>
<div data-target="c-formEditTabs01__contents" data-tab-panel="mail" class="area is-contents" role="tabpanel"
    aria-label="通知" hidden>
    <?php admin_c_form_block01_open(); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('通知設定'); ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_transport.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_from_email.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_from_name.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_smtp_host.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_smtp_port.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_smtp_secure.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_smtp_username.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_smtp_password.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_smtp_ehlo_host.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_smtp_timeout.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_smtp_verify_peer.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_smtp_verify_peer_name.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_smtp_allow_self_signed.php'; ?>
    </div>
    <?php admin_c_form_block01_close(); ?>

    <?php admin_c_form_block01_open(); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('管理者通知メール'); ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_admin_emails.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_subject_admin.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_template_admin.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_mail_template_vars.php'; ?>
    </div>
    <?php admin_c_form_block01_close(); ?>

    <?php admin_c_form_block01_open(); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('送信者への自動返信メール'); ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_reply_enabled.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_reply_from_field_key.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_reply_subject.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_reply_template.php'; ?>
    </div>
    <div class="area is-field">
        <?php require __DIR__ . '/_content_reply_template_vars.php'; ?>
    </div>
    <?php admin_c_form_block01_close(); ?>
</div>
