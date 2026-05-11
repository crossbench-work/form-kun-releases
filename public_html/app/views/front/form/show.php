<section class="p-formShow01">
<?php $descriptionBlocks = decode_form_description_blocks($form['description'] ?? '', !empty($form['show_description'])); ?>
<?php $descriptionEndPosition = count($fields) + 1; ?>
<?php $previewMode = !empty($previewMode); ?>
<?php $confirmButtonText = trim((string) ($form['confirm_button_text'] ?? '')) !== '' ? (string) $form['confirm_button_text'] : '入力内容を確認する'; ?>
<?php if (!empty($form['show_title'])): ?>
    <h1 class="title"><?= e($form['title']) ?></h1>
<?php endif; ?>
<?= render_form_description_blocks_at($descriptionBlocks, 0) ?>

<?php if (!empty($errors)): ?>
    <div class="m-alert01 is-danger">
        入力内容にエラーがあります。下記をご確認ください。
        <?php if (!empty($errors['_captcha'])): ?>
            <ul><?php foreach ($errors['_captcha'] as $m): ?><li><?= e($m) ?></li><?php endforeach; ?></ul>
        <?php endif; ?>
    </div>
<?php endif; ?>

<form class="p-formInput01" action="<?= e($previewMode ? '#' : base_path('/f/' . $form['slug'] . '/confirm')) ?>" method="post" enctype="multipart/form-data" novalidate data-module="c-publicForm01">
    <?php if (!$previewMode): ?>
        <?= \Core\Csrf::field() ?>
    <?php endif; ?>

    <?php // ハニーポット ?>
    <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
        <label>Website
            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
        </label>
    </div>

    <?php foreach ($fields as $i => $field): ?>
        <?php include __DIR__ . '/_field.php'; ?>
        <?= render_form_description_blocks_at($descriptionBlocks, $i + 1) ?>
    <?php endforeach; ?>

    <?php if (!$previewMode && !empty($form['captcha_enabled']) && !empty($captchaQuestion)): ?>
        <div class="box is-field <?= !empty($errors['_captcha']) ? 'is-error' : '' ?>">
            <label for="captcha" class="label">
                スパム対策：<?= e($captchaQuestion) ?> = ?
                <span class="is-required">必須</span>
            </label>
            <input type="text" id="captcha" name="captcha" inputmode="numeric" autocomplete="off" required>
            <?php foreach (($errors['_captcha'] ?? []) as $m): ?>
                <p class="text is-error"><?= e($m) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

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
