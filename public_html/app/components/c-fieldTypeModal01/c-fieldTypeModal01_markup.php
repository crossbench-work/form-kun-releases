<div data-module="c-fieldTypeModal01" data-target="m-formBuilder01__typeModal" hidden>
    <div class="c-fieldTypeModal01__backdrop" data-target="m-formBuilder01__typeModalClose"></div>
    <div class="c-fieldTypeModal01__dialog" role="dialog" aria-modal="true" aria-labelledby="field-type-modal-title">
        <div class="c-fieldTypeModal01__header">
            <h2 id="field-type-modal-title">追加する型を選択</h2>
            <button type="button" class="btn is-sm" data-target="m-formBuilder01__typeModalClose">閉じる</button>
        </div>
        <div class="c-fieldTypeModal01__body">
            <div class="c-fieldTypeModal01__grid">
                <?php foreach (\Models\FormField::TYPE_LABELS() as $typeValue => $typeLabel): ?>
                    <button type="button" class="c-fieldTypeModal01__option" data-target="m-formBuilder01__typeOption"
                        data-field-type="<?= e($typeValue) ?>" data-field-type-label="<?= e($typeLabel) ?>">
                        <?= e($typeLabel) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
