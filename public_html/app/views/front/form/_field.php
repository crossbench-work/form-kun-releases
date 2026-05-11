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
$opts  = \Core\Validator::decodeOptions($field['options_json'] ?? null);
$ph    = $field['placeholder'] ?? '';

$labelFor = $type === 'address' ? 'f_' . $key . '_postal3' : 'f_' . $key;

$addrPostal = '';
$addrPref   = '';
$addrCity   = '';
$addrNumber = '';
$addrBuilding = '';
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
<div class="box is-field <?= ($type !== 'address' && !empty($err)) ? 'is-error' : '' ?>">
    <label for="<?= e($labelFor) ?>" class="label">
        <?= e($label) ?>
        <?php if ($req && $type !== 'address'): ?><span class="is-required">必須</span><?php endif; ?>
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
                        <?php if ($req): ?><span class="is-required">必須</span><?php endif; ?>
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
                        <?php if ($req): ?><span class="is-required">必須</span><?php endif; ?>
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
                        <?php if ($req): ?><span class="is-required">必須</span><?php endif; ?>
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

    <?php if ($type !== 'address'): ?>
        <?php foreach ($err as $msg): ?>
            <p class="text is-error"><?= e($msg) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
