<div class="p-formEdit01__floatingActions" data-target="p-formEdit01__dirtyActions" hidden>
    <div class="wrapper">
        <div class="block is-status">
            <p class="text">未保存の変更があります</p>
        </div>
        <div class="block is-actions">
            <?php admin_m_btn01_open(); ?>
            <?php admin_m_btn01_button($isNew ? '作成する' : '保存する', ['type' => 'submit', 'class' => 'is-primary']); ?>
            <?php admin_m_btn01_close(); ?>
            <?php admin_m_btn01_open(); ?>
            <?php admin_m_btn01_button('キャンセル', ['data-target' => 'p-formEdit01__dirtyCancel']); ?>
            <?php admin_m_btn01_close(); ?>
        </div>
    </div>
</div>
