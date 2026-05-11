<section class="p-formIndex01" data-module="c-formCardList01">
    <div class="wrapper">
        <h1 class="title">公開中のフォーム</h1>

<?php if (empty($forms)): ?>
    <p class="text is-muted">現在公開中のフォームはありません。</p>
<?php else: ?>
    <ul class="list">
    <?php foreach ($forms as $form): ?>
        <li class="card">
            <h2><a class="link" href="<?= e(base_path('/f/' . $form['slug'])) ?>"><?= e($form['title']) ?></a></h2>
            <?php if (!empty($form['description'])): ?>
                <div class="text is-muted"><?= render_rich_text_html($form['description']) ?></div>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
    </div>
</section>
