<section class="p-formConfirm01" data-module="c-confirmSummary01">
    <div class="wrapper">
        <h1 class="title">入力内容のご確認</h1>
        <p class="text">下記の内容で送信します。よろしければ「送信する」ボタンを押してください。</p>

<dl class="list is-confirm">
<?php foreach ($fields as $field):
    $key = $field['field_key'];
    $val = $values[$key] ?? '';
    if (!in_array($field['type'], ['address', 'text_pair'], true) && is_array($val)) {
        $val = implode(', ', $val);
    }
?>
    <dt><?= e($field['label']) ?></dt>
    <dd>
    <?php if ($field['type'] === 'file'): ?>
        <?php if (!empty($fileMetas[$key]['original_name'])): ?>
            <?= e($fileMetas[$key]['original_name']) ?>
            （<?= e(\Core\Validator::formatBytes((int) ($fileMetas[$key]['size'] ?? 0))) ?>）
        <?php else: ?>
            <span class="text is-muted">（ファイルなし）</span>
        <?php endif; ?>
    <?php elseif ($field['type'] === 'address'): ?>
        <?php $addrText = format_address_value_display($val); ?>
        <?= $addrText !== '' ? nl2br(e($addrText)) : '<span class="text is-muted">（未入力）</span>' ?>
    <?php elseif ($field['type'] === 'text_pair'): ?>
        <?php $pairText = format_text_pair_value_display($val, \Models\FormField::decodeTextPairLabels($field['options_json'] ?? null)); ?>
        <?= $pairText !== '' ? nl2br(e($pairText)) : '<span class="text is-muted">（未入力）</span>' ?>
    <?php else: ?>
        <?= $val !== '' ? nl2br(e((string) $val)) : '<span class="text is-muted">（未入力）</span>' ?>
    <?php endif; ?>
    </dd>
<?php endforeach; ?>
</dl>

<form action="<?= e(base_path('/f/' . $form['slug'] . '/submit')) ?>" method="post">
    <?= \Core\Csrf::field() ?>
    <div class="area is-actions">
        <button type="submit" name="_back" value="1" class="btn">入力に戻る</button>
        <button type="submit" class="btn is-primary">送信する</button>
    </div>
</form>
    </div>
</section>
