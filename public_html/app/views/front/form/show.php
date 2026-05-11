<section class="p-formShow01">
    <h1 class="title"><?= e($form['title']) ?></h1>
<?php if (!empty($form['description'])): ?>
    <div class="block is-lead"><?= nl2br(e($form['description'])) ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="m-alert01 is-danger">
        入力内容にエラーがあります。下記をご確認ください。
        <?php if (!empty($errors['_captcha'])): ?>
            <ul><?php foreach ($errors['_captcha'] as $m): ?><li><?= e($m) ?></li><?php endforeach; ?></ul>
        <?php endif; ?>
    </div>
<?php endif; ?>

<form class="p-formInput01" action="<?= e(base_path('/f/' . $form['slug'] . '/confirm')) ?>" method="post" enctype="multipart/form-data" novalidate data-module="c-publicForm01">
    <?= \Core\Csrf::field() ?>

    <?php // ハニーポット ?>
    <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
        <label>Website
            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
        </label>
    </div>

    <?php foreach ($fields as $field): ?>
        <?php include __DIR__ . '/_field.php'; ?>
    <?php endforeach; ?>

    <?php if (!empty($form['captcha_enabled']) && !empty($captchaQuestion)): ?>
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

    <div class="area is-actions">
        <button type="submit" class="btn is-primary">入力内容を確認する</button>
    </div>
</form>
</section>
