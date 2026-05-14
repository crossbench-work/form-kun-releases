<?php $thanksBlocks = decode_form_description_blocks($form['thanks_message'] ?? '', true); ?>
<?php $hasThanksContent = false; ?>
<?php foreach ($thanksBlocks as $thanksBlock): ?>
    <?php if (!empty($thanksBlock['visible']) && !empty($thanksBlock['html'])): ?>
        <?php $hasThanksContent = true; ?>
        <?php break; ?>
    <?php endif; ?>
<?php endforeach; ?>
<?php $thanksPageTitle = trim((string) ($form['thanks_page_title'] ?? '')) !== '' ? (string) $form['thanks_page_title'] : '送信が完了しました'; ?>
<?php $thanksButtonText = trim((string) ($form['thanks_button_text'] ?? '')) !== '' ? (string) $form['thanks_button_text'] : 'トップへ戻る'; ?>
<?php $thanksButtonUrl = trim((string) ($form['thanks_button_url'] ?? '')) !== '' ? (string) $form['thanks_button_url'] : '/'; ?>
<section data-module="c-thanksSummary01">
    <h1 class="title"><?= e($thanksPageTitle) ?></h1>
    <?= render_form_description_blocks_at($thanksBlocks, 0) ?>

<?php if (!$hasThanksContent): ?>
    <p class="text">お問い合わせいただきありがとうございました。<br>
    内容を確認のうえ、追ってご連絡いたします。</p>
<?php endif; ?>

<div class="area is-actions is-centered">
    <a class="btn is-primary" href="<?= e(resolve_form_button_url($thanksButtonUrl)) ?>"><?= e($thanksButtonText) ?></a>
</div>
<?= render_form_description_blocks_at($thanksBlocks, 1) ?>
</section>
