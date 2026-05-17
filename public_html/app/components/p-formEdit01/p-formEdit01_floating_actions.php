<div class="p-formEdit01__floatingActions" data-target="p-formEdit01__dirtyActions" hidden>
    <div class="wrapper">
        <div class="block is-status">
            <p class="text">未保存の変更があります</p>
        </div>
        <div class="block is-actions">
            <?php admin_m_btn01_button_wrap($isNew ? '作成する' : '保存する', ['type' => 'submit', 'class' => 'is-primary']); ?>
            <?php admin_m_btn01_button_wrap('キャンセル', ['data-target' => 'p-formEdit01__dirtyCancel']); ?>
        </div>
    </div>
</div>
