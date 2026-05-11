<section class="p-formConfirm01" data-module="c-confirmSummary01">
    <div class="wrapper">
        <?php $confirmBlocks = decode_form_description_blocks($form['confirm_message'] ?? '', true); ?>
        <?php $previewMode = !empty($previewMode); ?>
        <h1 class="title">入力内容のご確認</h1>
        <?= render_form_description_blocks_at($confirmBlocks, 0) ?>

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

<form action="<?= e($previewMode ? '#' : base_path('/f/' . $form['slug'] . '/submit')) ?>" method="post">
    <?php if (!$previewMode): ?>
        <?= \Core\Csrf::field() ?>
    <?php endif; ?>
    <div class="area is-actions">
        <?php if ($previewMode): ?>
            <button type="button" class="btn" disabled>入力に戻る</button>
            <button type="button" class="btn is-primary" disabled>プレビューでは送信できません</button>
        <?php else: ?>
            <button type="submit" name="_back" value="1" class="btn">入力に戻る</button>
            <button type="submit" class="btn is-primary">送信する</button>
        <?php endif; ?>
    </div>
</form>
        <?= render_form_description_blocks_at($confirmBlocks, 1) ?>
    </div>
</section>
