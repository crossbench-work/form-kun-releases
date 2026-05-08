<h1>入力内容のご確認</h1>
<p>下記の内容で送信します。よろしければ「送信する」ボタンを押してください。</p>

<dl class="confirm-list">
<?php foreach ($fields as $field):
    $key = $field['field_key'];
    $val = $values[$key] ?? '';
    if (is_array($val)) $val = implode(', ', $val);
?>
    <dt><?= e($field['label']) ?></dt>
    <dd>
    <?php if ($field['type'] === 'file'): ?>
        <?php if (!empty($fileMetas[$key]['original_name'])): ?>
            <?= e($fileMetas[$key]['original_name']) ?>
            （<?= e(\Core\Validator::formatBytes((int) ($fileMetas[$key]['size'] ?? 0))) ?>）
        <?php else: ?>
            <span class="muted">（ファイルなし）</span>
        <?php endif; ?>
    <?php else: ?>
        <?= $val !== '' ? nl2br(e((string) $val)) : '<span class="muted">（未入力）</span>' ?>
    <?php endif; ?>
    </dd>
<?php endforeach; ?>
</dl>

<form action="<?= e(base_path('/f/' . $form['slug'] . '/submit')) ?>" method="post">
    <?= \Core\Csrf::field() ?>
    <div class="actions">
        <button type="submit" name="_back" value="1" class="btn">入力に戻る</button>
        <button type="submit" class="btn btn-primary">送信する</button>
    </div>
</form>
