<section class="p-formThanks01">
    <?php $thanksBlocks = decode_form_description_blocks($form['thanks_message'] ?? '', true); ?>
    <h1 class="title">送信が完了しました</h1>
    <?= render_form_description_blocks_at($thanksBlocks, 0) ?>

<?php if (first_form_description_block_html($form['thanks_message'] ?? '', true) === ''): ?>
    <p class="text">お問い合わせいただきありがとうございました。<br>
    内容を確認のうえ、追ってご連絡いたします。</p>
<?php endif; ?>

<p><a class="link" href="<?= e(base_path('/')) ?>">トップへ戻る</a></p>
<?= render_form_description_blocks_at($thanksBlocks, 1) ?>
</section>
