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
$addrErrParts = ($type === 'address') ? address_errors_normalize($err) : [];
$pairErrParts = ($type === 'text_pair') ? text_pair_errors_normalize($err) : [];
$multiErrParts = ($type === 'text_multi') ? text_multi_errors_normalize($err) : ['_root' => [], 'items' => []];
$opts  = \Core\Validator::decodeOptions($field['options_json'] ?? null);
$pairLabels = ($type === 'text_pair')
    ? \Models\FormField::decodeTextPairLabels($field['options_json'] ?? null)
    : ['left_label' => '', 'right_label' => ''];
$pairPlaceholders = ($type === 'text_pair')
    ? \Models\FormField::decodeTextPairPlaceholders($field['placeholder'] ?? null)
    : ['left_placeholder' => '', 'right_placeholder' => ''];
$multiItems = ($type === 'text_multi')
    ? \Models\FormField::decodeTextMultiItems($field['options_json'] ?? null, true)
    : [];
$ph    = $field['placeholder'] ?? '';

$labelFor = $type === 'address'
    ? 'f_' . $key . '_postal3'
    : ($type === 'text_pair'
        ? 'f_' . $key . '_left'
        : ($type === 'text_multi' ? 'f_' . $key . '_0' : 'f_' . $key));

$addrPostal = '';
$addrPref   = '';
$addrCity   = '';
$addrNumber = '';
$addrBuilding = '';
$pairLeft = '';
$pairRight = '';
$multiValues = [];
if ($type === 'address') {
    if (is_array($value)) {
        if (isset($value['prefecture']) || isset($value['city']) || isset($value['street'])
            || isset($value['number']) || isset($value['building'])) {
            $addrPostal = (string) ($value['postal'] ?? '');
            $addrPref   = (string) ($value['prefecture'] ?? '');
            $addrCity   = (string) ($value['city'] ?? '');
            $addrNumber = (string) ($value['number'] ?? '');
            $addrBuilding = (string) ($value['building'] ?? '');
            if ($addrNumber === '' && $addrBuilding === '' && isset($value['street'])) {
                $addrNumber = (string) ($value['street']);
            }
        } else {
            $addrPostal = (string) ($value['postal'] ?? '');
            $line1      = (string) ($value['line1'] ?? '');
            [$addrPref, $addrCity] = address_split_legacy_line1($line1);
            $addrNumber = (string) ($value['line2'] ?? '');
        }
    } elseif (is_string($value) && $value !== '') {
        $dec = json_decode($value, true);
        if (is_array($dec) && (isset($dec['postal']) || isset($dec['line1']) || isset($dec['line2'])
            || isset($dec['prefecture']) || isset($dec['city']) || isset($dec['street'])
            || isset($dec['number']) || isset($dec['building']))) {
            if (isset($dec['prefecture']) || isset($dec['city']) || isset($dec['street'])
                || isset($dec['number']) || isset($dec['building'])) {
                $addrPostal = (string) ($dec['postal'] ?? '');
                $addrPref   = (string) ($dec['prefecture'] ?? '');
                $addrCity   = (string) ($dec['city'] ?? '');
                $addrNumber = (string) ($dec['number'] ?? '');
                $addrBuilding = (string) ($dec['building'] ?? '');
                if ($addrNumber === '' && $addrBuilding === '' && isset($dec['street'])) {
                    $addrNumber = (string) ($dec['street']);
                }
            } else {
                $addrPostal = (string) ($dec['postal'] ?? '');
                $line1      = (string) ($dec['line1'] ?? '');
                [$addrPref, $addrCity] = address_split_legacy_line1($line1);
                $addrNumber = (string) ($dec['line2'] ?? '');
            }
        } else {
            $addrCity = $value;
        }
    }
}
if ($type === 'text_pair') {
    if (is_array($value)) {
        $pairLeft = (string) ($value['left'] ?? '');
        $pairRight = (string) ($value['right'] ?? '');
    } elseif (is_string($value) && $value !== '') {
        $dec = json_decode($value, true);
        if (is_array($dec) && (isset($dec['left']) || isset($dec['right']))) {
            $pairLeft = (string) ($dec['left'] ?? '');
            $pairRight = (string) ($dec['right'] ?? '');
        } else {
            $parts = preg_split('/\r\n|\r|\n/', $value) ?: [];
            $pairLeft = (string) ($parts[0] ?? '');
            $pairRight = (string) ($parts[1] ?? '');
        }
    }
}
if ($type === 'text_multi') {
    if (is_array($value)) {
        if (isset($value['items']) && is_array($value['items'])) {
            $multiValues = array_values(array_map(static fn($item): string => trim((string) $item), $value['items']));
        } else {
            $multiValues = array_values(array_map(static fn($item): string => trim((string) $item), $value));
        }
    } elseif (is_string($value) && $value !== '') {
        $dec = json_decode($value, true);
        if (is_array($dec)) {
            if (isset($dec['items']) && is_array($dec['items'])) {
                $multiValues = array_values(array_map(static fn($item): string => trim((string) $item), $dec['items']));
            } elseif (isset($dec['left']) || isset($dec['right'])) {
                $multiValues = [
                    trim((string) ($dec['left'] ?? '')),
                    trim((string) ($dec['right'] ?? '')),
                ];
            } else {
                $multiValues = array_values(array_map(static fn($item): string => trim((string) $item), $dec));
            }
        } else {
            $multiValues = preg_split('/\r\n|\r|\n/', $value) ?: [];
            $multiValues = array_values(array_map(static fn($item): string => trim((string) $item), $multiValues));
        }
    }
}

$postal3 = '';
$postal4 = '';
if ($type === 'address' && $addrPostal !== '') {
    $pd = preg_replace('/\D/', '', $addrPostal);
    if (strlen($pd) >= 7) {
        $postal3 = substr($pd, 0, 3);
        $postal4 = substr($pd, 3, 4);
    } elseif (strlen($pd) > 3) {
        $postal3 = substr($pd, 0, 3);
        $postal4 = substr($pd, 3);
    } elseif ($pd !== '') {
        $postal3 = $pd;
    }
}

$prefOptions = japanese_prefectures();
?>
<div class="box is-field <?= ($type !== 'address' && $type !== 'text_pair' && $type !== 'text_multi' && !empty($err)) ? 'is-error' : '' ?>">
    <label for="<?= e($labelFor) ?>" class="label">
        <?= e($label) ?>
        <?php if ($req && $type !== 'address'): ?><span data-module="m-required01" data-type="required">必須</span><?php endif; ?>
    </label>

    <?php if (!empty($field['help_text'])): ?>
        <p class="text is-help"><?= nl2br(e($field['help_text'])) ?></p>
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

        <?php case 'textarea': ?>
            <textarea
                id="f_<?= e($key) ?>"
                name="<?= e($key) ?>"
                rows="6"
                <?= $field['max_length'] ? 'maxlength="' . (int) $field['max_length'] . '"' : '' ?>
                placeholder="<?= e($ph) ?>"
                <?= $req ? 'required' : '' ?>><?= e(is_array($value) ? '' : $value) ?></textarea>
            <?php break; ?>

        <?php case 'address': ?>
            <div class="box is-address" id="f_<?= e($key) ?>" data-form-address>
                <?php foreach (($addrErrParts['_root'] ?? []) as $m): ?>
                    <p class="text is-error"><?= e($m) ?></p>
                <?php endforeach; ?>
                <div class="box is-addressRow <?= !empty($addrErrParts['postal']) ? 'is-error' : '' ?>">
                    <label class="label is-sublabel" for="f_<?= e($key) ?>_postal3">郵便番号</label>
                    <div class="area is-inline is-postal">
                        <input
                            type="text"
                            class="input-postal-part1"
                            id="f_<?= e($key) ?>_postal3"
                            name="<?= e($key) ?>[postal3]"
                            value="<?= e($postal3) ?>"
                            inputmode="numeric"
                            autocomplete="postal-code"
                            maxlength="3"
                            placeholder="123"
                            aria-label="郵便番号（上3桁）"
                            >
                        <span class="postal-hyphen" aria-hidden="true">-</span>
                        <input
                            type="text"
                            class="input-postal-part2"
                            id="f_<?= e($key) ?>_postal4"
                            name="<?= e($key) ?>[postal4]"
                            value="<?= e($postal4) ?>"
                            inputmode="numeric"
                            autocomplete="postal-code"
                            maxlength="4"
                            placeholder="4567"
                            aria-label="郵便番号（下4桁）"
                            >
                        <button type="button" class="btn is-secondary is-sm js-zip-lookup">住所検索</button>
                    </div>
                    <?php foreach (($addrErrParts['postal'] ?? []) as $m): ?>
                        <p class="text is-error"><?= e($m) ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="box is-addressRow <?= !empty($addrErrParts['prefecture']) ? 'is-error' : '' ?>">
                    <label class="label is-sublabel <?= $req ? 'is-requiredRow' : '' ?>" for="f_<?= e($key) ?>_prefecture">
                        都道府県
                        <?php if ($req): ?><span data-module="m-required01" data-type="required">必須</span><?php endif; ?>
                    </label>
                    <select
                        class="input-prefecture"
                        id="f_<?= e($key) ?>_prefecture"
                        name="<?= e($key) ?>[prefecture]"
                        autocomplete="address-level1"
                        <?= $req ? 'required' : '' ?>>
                        <option value="">選択してください</option>
                        <?php foreach ($prefOptions as $pname): ?>
                            <option value="<?= e($pname) ?>" <?= ($addrPref === $pname) ? 'selected' : '' ?>><?= e($pname) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php foreach (($addrErrParts['prefecture'] ?? []) as $m): ?>
                        <p class="text is-error"><?= e($m) ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="box is-addressRow <?= !empty($addrErrParts['city']) ? 'is-error' : '' ?>">
                    <label class="label is-sublabel <?= $req ? 'is-requiredRow' : '' ?>" for="f_<?= e($key) ?>_city">
                        市区町村
                        <?php if ($req): ?><span data-module="m-required01" data-type="required">必須</span><?php endif; ?>
                    </label>
                    <input
                        type="text"
                        class="input-city"
                        id="f_<?= e($key) ?>_city"
                        name="<?= e($key) ?>[city]"
                        value="<?= e($addrCity) ?>"
                        autocomplete="address-level2"
                        placeholder="例: 千代田区千代田"
                        <?= $req ? 'required' : '' ?>>
                    <?php foreach (($addrErrParts['city'] ?? []) as $m): ?>
                        <p class="text is-error"><?= e($m) ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="box is-addressRow <?= !empty($addrErrParts['number']) || !empty($addrErrParts['combined']) ? 'is-error' : '' ?>">
                    <label class="label is-sublabel <?= $req ? 'is-requiredRow' : '' ?>" for="f_<?= e($key) ?>_number">
                        番地
                        <?php if ($req): ?><span data-module="m-required01" data-type="required">必須</span><?php endif; ?>
                    </label>
                    <input
                        type="text"
                        class="input-number"
                        id="f_<?= e($key) ?>_number"
                        name="<?= e($key) ?>[number]"
                        value="<?= e($addrNumber) ?>"
                        autocomplete="street-address"
                        placeholder="例: 1-2-3"
                        <?= $req ? 'required' : '' ?>>
                    <?php foreach (($addrErrParts['number'] ?? []) as $m): ?>
                        <p class="text is-error"><?= e($m) ?></p>
                    <?php endforeach; ?>
                    <?php foreach (($addrErrParts['combined'] ?? []) as $m): ?>
                        <p class="text is-error"><?= e($m) ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="box is-addressRow">
                    <label class="label is-sublabel" for="f_<?= e($key) ?>_building">建物名・部屋番号</label>
                    <input
                        type="text"
                        class="input-building"
                        id="f_<?= e($key) ?>_building"
                        name="<?= e($key) ?>[building]"
                        value="<?= e($addrBuilding) ?>"
                        autocomplete="address-line2"
                        placeholder="例: ○○マンション101">
                </div>
            </div>
            <?php break; ?>

        <?php case 'text_pair': ?>
            <div class="box is-textPair" id="f_<?= e($key) ?>">
                <?php foreach (($pairErrParts['_root'] ?? []) as $m): ?>
                    <p class="text is-error"><?= e($m) ?></p>
                <?php endforeach; ?>
                <div class="box is-textPairCol <?= !empty($pairErrParts['left']) ? 'is-error' : '' ?>">
                    <label class="label is-sublabel" for="f_<?= e($key) ?>_left">
                        <?= e($pairLabels['left_label'] !== '' ? $pairLabels['left_label'] : '入力欄1') ?>
                    </label>
                    <input
                        type="text"
                        id="f_<?= e($key) ?>_left"
                        name="<?= e($key) ?>[left]"
                        value="<?= e($pairLeft) ?>"
                        <?= $field['max_length'] ? 'maxlength="' . (int) $field['max_length'] . '"' : '' ?>
                        placeholder="<?= e($pairPlaceholders['left_placeholder']) ?>"
                        aria-label="<?= e($pairLabels['left_label'] !== '' ? $pairLabels['left_label'] : '入力欄1') ?>"
                        <?= $req ? 'required' : '' ?>
                        >
                    <?php foreach (($pairErrParts['left'] ?? []) as $m): ?>
                        <p class="text is-error"><?= e($m) ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="box is-textPairCol <?= !empty($pairErrParts['right']) ? 'is-error' : '' ?>">
                    <label class="label is-sublabel" for="f_<?= e($key) ?>_right">
                        <?= e($pairLabels['right_label'] !== '' ? $pairLabels['right_label'] : '入力欄2') ?>
                    </label>
                    <input
                        type="text"
                        id="f_<?= e($key) ?>_right"
                        name="<?= e($key) ?>[right]"
                        value="<?= e($pairRight) ?>"
                        <?= $field['max_length'] ? 'maxlength="' . (int) $field['max_length'] . '"' : '' ?>
                        placeholder="<?= e($pairPlaceholders['right_placeholder']) ?>"
                        aria-label="<?= e($pairLabels['right_label'] !== '' ? $pairLabels['right_label'] : '入力欄2') ?>"
                        <?= $req ? 'required' : '' ?>
                        >
                    <?php foreach (($pairErrParts['right'] ?? []) as $m): ?>
                        <p class="text is-error"><?= e($m) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php break; ?>

        <?php case 'text_multi': ?>
            <div class="box is-textMulti" id="f_<?= e($key) ?>">
                <?php foreach (($multiErrParts['_root'] ?? []) as $m): ?>
                    <p class="text is-error"><?= e($m) ?></p>
                <?php endforeach; ?>
                <?php foreach ($multiItems as $index => $multiItem): ?>
                    <?php
                        $multiItemLabel = trim((string) ($multiItem['label'] ?? ''));
                        $hasSingleUnnamedMultiItem = count($multiItems) === 1 && $multiItemLabel === '';
                        if (!$hasSingleUnnamedMultiItem && $multiItemLabel === '') {
                            $multiItemLabel = '入力欄' . ($index + 1);
                        }
                        $multiItemPlaceholder = (string) ($multiItem['placeholder'] ?? '');
                        $multiItemValue = (string) ($multiValues[$index] ?? '');
                        $multiItemErrors = $multiErrParts['items'][$index] ?? [];
                    ?>
                    <div class="box is-textMultiCol <?= !empty($multiItemErrors) ? 'is-error' : '' ?>">
                        <?php if (!$hasSingleUnnamedMultiItem): ?>
                            <label class="label is-sublabel" for="f_<?= e($key) ?>_<?= $index ?>">
                                <?= e($multiItemLabel) ?>
                            </label>
                        <?php endif; ?>
                        <input
                            type="text"
                            id="f_<?= e($key) ?>_<?= $index ?>"
                            name="<?= e($key) ?>[items][]"
                            value="<?= e($multiItemValue) ?>"
                            <?= $field['max_length'] ? 'maxlength="' . (int) $field['max_length'] . '"' : '' ?>
                            placeholder="<?= e($multiItemPlaceholder) ?>"
                            aria-label="<?= e($hasSingleUnnamedMultiItem ? $label : $multiItemLabel) ?>"
                            <?= $req ? 'required' : '' ?>
                            >
                        <?php foreach ($multiItemErrors as $m): ?>
                            <p class="text is-error"><?= e($m) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
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
            <div class="list is-choices">
            <?php foreach ($opts as $i => $opt): ?>
                <label class="label is-choice">
                    <input type="radio" name="<?= e($key) ?>" value="<?= e($opt) ?>"
                        <?= ((string) $value === $opt) ? 'checked' : '' ?>
                        <?= $req ? 'required' : '' ?>>
                    <span><?= e($opt) ?></span>
                </label>
            <?php endforeach; ?>
            </div>
            <?php break; ?>

        <?php case 'checkbox': ?>
            <div class="list is-choices">
            <?php
                $selected = is_array($value) ? $value : (is_string($value) ? explode("\n", $value) : []);
                foreach ($opts as $opt): ?>
                <label class="label is-choice">
                    <input type="checkbox" name="<?= e($key) ?>[]" value="<?= e($opt) ?>"
                        <?= in_array($opt, $selected, true) ? 'checked' : '' ?>>
                    <span><?= e($opt) ?></span>
                </label>
            <?php endforeach; ?>
            </div>
            <?php break; ?>

        <?php case 'file': ?>
            <input type="file" id="f_<?= e($key) ?>" name="<?= e($key) ?>" <?= $req ? 'required' : '' ?>>
            <p class="text is-help">最大 <?= e(\Core\Validator::formatBytes((int) config('upload.max_size'))) ?></p>
            <?php break; ?>
    <?php endswitch; ?>

    <?php if ($type !== 'address' && $type !== 'text_pair' && $type !== 'text_multi'): ?>
        <?php foreach ($err as $msg): ?>
            <p class="text is-error"><?= e($msg) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
