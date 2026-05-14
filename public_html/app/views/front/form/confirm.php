<section class="p-formConfirm01" data-module="c-confirmSummary01">
    <div class="wrapper">
        <?php $confirmBlocks = decode_form_description_blocks($form['confirm_message'] ?? '', true); ?>
        <?php $previewMode = !empty($previewMode); ?>
        <?php $confirmPageTitle = trim((string) ($form['confirm_page_title'] ?? '')) !== '' ? (string) $form['confirm_page_title'] : '入力内容のご確認'; ?>
        <?php $submitButtonText = trim((string) ($form['submit_button_text'] ?? '')) !== '' ? (string) $form['submit_button_text'] : '送信する'; ?>
        <?php $backButtonText = trim((string) ($form['back_button_text'] ?? '')) !== '' ? (string) $form['back_button_text'] : '入力に戻る'; ?>
        <h1 class="title"><?= e($confirmPageTitle) ?></h1>
        <?= render_form_description_blocks_at($confirmBlocks, 0) ?>

<dl class="list is-confirm">
<?php foreach ($fields as $field):
    $key = $field['field_key'];
    $val = $values[$key] ?? '';
    if (!in_array($field['type'], ['address', 'text_pair', 'text_multi'], true) && is_array($val)) {
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
    <?php elseif ($field['type'] === 'text_multi'): ?>
        <?php $multiText = format_text_multi_value_display($val, \Models\FormField::decodeTextMultiItems($field['options_json'] ?? null, true)); ?>
        <?= $multiText !== '' ? nl2br(e($multiText)) : '<span class="text is-muted">（未入力）</span>' ?>
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
    <div class="area is-actions is-centered">
        <?php if ($previewMode): ?>
            <button type="button" class="btn" disabled><?= e($backButtonText) ?></button>
            <button type="button" class="btn is-primary" disabled>プレビューでは送信できません</button>
        <?php else: ?>
            <button type="submit" name="_back" value="1" class="btn"><?= e($backButtonText) ?></button>
            <button type="submit" class="btn is-primary"><?= e($submitButtonText) ?></button>
        <?php endif; ?>
    </div>
</form>
        <?= render_form_description_blocks_at($confirmBlocks, 1) ?>
    </div>
</section>
