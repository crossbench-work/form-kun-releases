<h1>送信が完了しました</h1>

<?php if (!empty($form['thanks_message'])): ?>
    <div class="thanks-message"><?= nl2br(e($form['thanks_message'])) ?></div>
<?php else: ?>
    <p>お問い合わせいただきありがとうございました。<br>
    内容を確認のうえ、追ってご連絡いたします。</p>
<?php endif; ?>

<p><a href="<?= e(base_path('/')) ?>">トップへ戻る</a></p>
