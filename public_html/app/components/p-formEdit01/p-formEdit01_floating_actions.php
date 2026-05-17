<div class="p-formEdit01__floatingActions" data-target="p-formEdit01__dirtyActions" hidden>
    <div class="wrapper">
        <div class="block is-status">
            <p class="text">未保存の変更があります</p>
        </div>
        <div class="block is-actions">
            <?php admin_m_btn01_open(); ?>
            <button type="submit" class="btn is-primary"><?= $isNew ? '作成する' : '保存する' ?></button>
            <?php admin_m_btn01_close(); ?>
            <?php admin_m_btn01_open(); ?>
            <button type="button" class="btn" data-target="p-formEdit01__dirtyCancel">キャンセル</button>
            <?php admin_m_btn01_close(); ?>
        </div>
    </div>
</div>
