<section class="p-formShow01">
<?php $descriptionBlocks = decode_form_description_blocks($form['description'] ?? '', !empty($form['show_description'])); ?>
<?php $descriptionEndPosition = count($fields) + 1; ?>
<?php $previewMode = !empty($previewMode); ?>
<?php $confirmButtonText = trim((string) ($form['confirm_button_text'] ?? '')) !== '' ? (string) $form['confirm_button_text'] : '入力内容を確認する'; ?>
<?php $inputPageTitle = trim((string) ($form['input_page_title'] ?? '')); ?>
<?php if (!empty($form['show_input_page_title']) && $inputPageTitle !== ''): ?>
    <h1 class="title"><?= e($inputPageTitle) ?></h1>
<?php endif; ?>
<?= render_form_description_blocks_at($descriptionBlocks, 0) ?>

<?php if (!empty($errors)): ?>
    <div class="m-alert01 is-danger">
        入力内容にエラーがあります。下記をご確認ください。
    </div>
<?php endif; ?>

<form class="p-formInput01" action="<?= e($previewMode ? '#' : base_path('/f/' . $form['slug'] . '/confirm')) ?>" method="post" enctype="multipart/form-data" novalidate data-module="c-publicForm01">
    <?php if (!$previewMode): ?>
        <?= \Core\Csrf::field() ?>
    <?php endif; ?>

    <?php foreach ($fields as $i => $field): ?>
        <?php include __DIR__ . '/_field.php'; ?>
        <?= render_form_description_blocks_at($descriptionBlocks, $i + 1) ?>
    <?php endforeach; ?>

    <div class="area is-actions is-centered">
        <?php if ($previewMode): ?>
            <button type="button" class="btn is-primary" disabled>プレビューでは送信できません</button>
        <?php else: ?>
            <button type="submit" class="btn is-primary"><?= e($confirmButtonText) ?></button>
        <?php endif; ?>
    </div>
</form>
<?= render_form_description_blocks_at($descriptionBlocks, $descriptionEndPosition) ?>
</section>
