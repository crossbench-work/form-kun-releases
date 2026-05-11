<section class="p-formThanks01">
    <h1 class="title">送信が完了しました</h1>

<?php if (!empty($form['thanks_message'])): ?>
    <div class="block is-thanks"><?= nl2br(e($form['thanks_message'])) ?></div>
<?php else: ?>
    <p class="text">お問い合わせいただきありがとうございました。<br>
    内容を確認のうえ、追ってご連絡いたします。</p>
<?php endif; ?>

<p><a class="link" href="<?= e(base_path('/')) ?>">トップへ戻る</a></p>
</section>
