<?php
require_once APP_COMPONENTS_DIR . '/c-formBlock01/c-formBlock01.php';
require_once APP_COMPONENTS_DIR . '/c-breadcrumb01/c-breadcrumb01.php';
require_once APP_COMPONENTS_DIR . '/m-largeTitle01/m-largeTitle01.php';
$FB_REGEX_CUSTOM = '__custom__';
$FB_REGEX_CHECKBOX_LIMIT = '__checkbox_limit__';
$regexPresetChoices = [
    '' => 'なし',
    '/\A[^\s@]+@[^\s@]+\.[^\s@]+\z/u' => 'メールアドレス',
    '/\A(?:\+?\d[\d\-() ]*)\z/u' => '電話番号',
    '/\A[0-9]+\z/u' => '半角数字のみ',
    '/\A[a-zA-Z0-9]+\z/u' => '半角英数字のみ',
    '/\A[ぁ-んー]+\z/u' => 'ひらがなのみ',
    '/\A[ァ-ヶー　]+\z/u' => 'カタカナのみ（全角スペース可）',
    '/\A[a-zA-Z]+\z/u' => '半角英字のみ',
    $FB_REGEX_CUSTOM => 'カスタム（正規表現を入力）',
];
$breadcrumbTitle = trim((string) ($form['title'] ?? '')) !== '' ? (string) $form['title'] : '（無題）';
$slugPreviewValue = trim((string) ($form['slug'] ?? '')) !== '' ? (string) $form['slug'] : 'your-slug';
$descriptionBlocks = $form['description_blocks'] ?? [['id' => 'description-0', 'label' => '', 'html' => '', 'position' => 0, 'visible' => 1, 'is_html' => 0]];
$confirmBlocks = $form['confirm_blocks'] ?? [['id' => 'confirm-0', 'label' => '', 'html' => '', 'position' => 0, 'visible' => 1, 'is_html' => 0]];
$thanksBlocks = $form['thanks_blocks'] ?? [['id' => 'thanks-0', 'label' => '', 'html' => '', 'position' => 0, 'visible' => 1, 'is_html' => 0]];
?>

<?php admin_c_breadcrumb01([
    ['label' => 'フォーム', 'href' => base_path('/admin/forms')],
    ['label' => $breadcrumbTitle, 'current' => true],
]); ?>

<form method="post"
    action="<?= e($isNew ? base_path('/admin/forms') : base_path('/admin/forms/' . (int) $form['id'])) ?>"
    class="p-formEdit01" id="form-edit-main">
    <?= \Core\Csrf::field() ?>
    <?php admin_p_form_edit01_floating_actions($isNew); ?>
    <div class="wrapper">
        <div class="block is-contents">
            <?php if (!empty($errors)): ?>
                <?php admin_c_alert01_open(); ?>
                    <ul>
                        <?php foreach ($errors as $msg): ?>
                            <li><?= e($msg) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php admin_c_alert01_close(); ?>
            <?php endif; ?>

            <?php admin_c_form_edit_tabs01_open(); ?>
            <?php require __DIR__ . '/_form_edit_tab_contents_basic.php'; ?>
            <?php require __DIR__ . '/_form_edit_tab_contents_fields.php'; ?>
            <?php require __DIR__ . '/_form_edit_tab_contents_confirm.php'; ?>
            <?php require __DIR__ . '/_form_edit_tab_contents_thanks.php'; ?>
            <?php require __DIR__ . '/_form_edit_tab_contents_mail.php'; ?>
            <?php admin_c_form_edit_tabs01_close(); ?>

            <div class="block bottom">
                <?php admin_c_btn_set01_open(); ?>
                    <div class="area is-contents">
                        <?php admin_m_btn01_open(); ?>
                            <button type="submit" class="btn is-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
                        <?php admin_m_btn01_close(); ?>
                    </div>
                    <div class="area is-contents">
                        <?php admin_m_btn01_open(); ?>
                            <a class="btn" href="<?= e(base_path('/admin/forms')) ?>">キャンセル</a>
                        <?php admin_m_btn01_close(); ?>
                    </div>
                <?php admin_c_btn_set01_close(); ?>
            </div>
        </div>
    </div>
</form>

<?php admin_c_field_type_modal01(); ?>

<?php admin_m_form_builder01_description_template(); ?>

<?php admin_m_form_builder01_field_template(); ?>
