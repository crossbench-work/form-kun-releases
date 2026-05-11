<?php
/** @var array $field */
/** @var array $values */
/** @var array $errors */
$key   = $field['field_key'];
$label = $field['label'];
$type  = $field['type'];
$req   = (int) $field['required'] === 1;
$value = $values[$key] ?? '';
$err   = $errors[$key] ?? [];
$opts  = \Core\Validator::decodeOptions($field['options_json'] ?? null);
$ph    = $field['placeholder'] ?? '';
?>
<div class="field <?= $err ? 'has-error' : '' ?>">
    <label for="f_<?= e($key) ?>" class="field-label">
        <?= e($label) ?>
        <?php if ($req): ?><span class="required">必須</span><?php endif; ?>
    </label>

    <?php if (!empty($field['help_text'])): ?>
        <p class="field-help"><?= nl2br(e($field['help_text'])) ?></p>
    <?php endif; ?>

    <?php switch ($type):
        case 'text':
        case 'email':
        case 'tel':
        case 'number':
        case 'date':
        case 'time': ?>
            <input
                type="<?= e($type) ?>"
                id="f_<?= e($key) ?>"
                name="<?= e($key) ?>"
                value="<?= e(is_array($value) ? '' : $value) ?>"
                <?= $field['max_length'] ? 'maxlength="' . (int) $field['max_length'] . '"' : '' ?>
                placeholder="<?= e($ph) ?>"
                <?= $req ? 'required' : '' ?>>
            <?php break; ?>

        <?php case 'textarea':
        case 'address': ?>
            <textarea
                id="f_<?= e($key) ?>"
                name="<?= e($key) ?>"
                rows="<?= $type === 'address' ? 4 : 6 ?>"
                <?= $type === 'address' ? 'autocomplete="street-address"' : '' ?>
                <?= $field['max_length'] ? 'maxlength="' . (int) $field['max_length'] . '"' : '' ?>
                placeholder="<?= e($ph) ?>"
                <?= $req ? 'required' : '' ?>><?= e(is_array($value) ? '' : $value) ?></textarea>
            <?php break; ?>

        <?php case 'select': ?>
            <select id="f_<?= e($key) ?>" name="<?= e($key) ?>" <?= $req ? 'required' : '' ?>>
                <option value="">選択してください</option>
                <?php foreach ($opts as $opt): ?>
                    <option value="<?= e($opt) ?>" <?= ((string) $value === $opt) ? 'selected' : '' ?>>
                        <?= e($opt) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php break; ?>

        <?php case 'radio': ?>
            <div class="choices">
            <?php foreach ($opts as $i => $opt): ?>
                <label class="choice">
                    <input type="radio" name="<?= e($key) ?>" value="<?= e($opt) ?>"
                        <?= ((string) $value === $opt) ? 'checked' : '' ?>
                        <?= $req ? 'required' : '' ?>>
                    <span><?= e($opt) ?></span>
                </label>
            <?php endforeach; ?>
            </div>
            <?php break; ?>

        <?php case 'checkbox': ?>
            <div class="choices">
            <?php
                $selected = is_array($value) ? $value : (is_string($value) ? explode("\n", $value) : []);
                foreach ($opts as $opt): ?>
                <label class="choice">
                    <input type="checkbox" name="<?= e($key) ?>[]" value="<?= e($opt) ?>"
                        <?= in_array($opt, $selected, true) ? 'checked' : '' ?>>
                    <span><?= e($opt) ?></span>
                </label>
            <?php endforeach; ?>
            </div>
            <?php break; ?>

        <?php case 'file': ?>
            <input type="file" id="f_<?= e($key) ?>" name="<?= e($key) ?>" <?= $req ? 'required' : '' ?>>
            <p class="field-help">最大 <?= e(\Core\Validator::formatBytes((int) config('upload.max_size'))) ?></p>
            <?php break; ?>
    <?php endswitch; ?>

    <?php foreach ($err as $msg): ?>
        <p class="field-error"><?= e($msg) ?></p>
    <?php endforeach; ?>
</div>
