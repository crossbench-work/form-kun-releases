<?php $thanksBlocks = decode_form_description_blocks($form['thanks_message'] ?? '', true); ?>
<?php $hasThanksContent = false; ?>
<?php foreach ($thanksBlocks as $thanksBlock): ?>
    <?php if (!empty($thanksBlock['visible']) && !empty($thanksBlock['html'])): ?>
        <?php $hasThanksContent = true; ?>
        <?php break; ?>
    <?php endif; ?>
<?php endforeach; ?>
<section>
    <h1 class="title">送信が完了しました</h1>
    <?= render_form_description_blocks_at($thanksBlocks, 0) ?>

<?php if (!$hasThanksContent): ?>
    <p class="text">お問い合わせいただきありがとうございました。<br>
    内容を確認のうえ、追ってご連絡いたします。</p>
<?php endif; ?>

<p><a class="link" href="<?= e(base_path('/')) ?>">トップへ戻る</a></p>
<?= render_form_description_blocks_at($thanksBlocks, 1) ?>
</section>
