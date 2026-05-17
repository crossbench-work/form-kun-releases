<?php /** フォーム編集:「確認画面」タブパネル */ ?>
<div data-target="c-formEditTabs01__contents" data-tab-panel="confirm" class="area is-contents" role="tabpanel"
    aria-label="確認画面" hidden>
    <?php admin_c_form_block01_open(); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('表示設定'); ?>
        <?php admin_m_btn01_open(); ?>
        <button type="submit" class="btn" formaction="<?= e(base_path('/admin/forms/preview-confirm')) ?>"
            formmethod="post" formtarget="_blank" formnovalidate>プレビュー</button>
        <?php admin_m_btn01_close(); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('タイトル'), 'confirm_page_title'); ?>
            <label class="label is-choice">
                <input type="checkbox" name="show_confirm_page_title" value="1" <?= !empty($form['show_confirm_page_title']) ? 'checked' : '' ?>>
                表示する
            </label>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('confirm_page_title', [
                'type' => 'text',
                'id' => 'confirm_page_title',
                'name' => 'confirm_page_title',
                'value' => (string) ($form['confirm_page_title'] ?? ''),
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
    </div>
    <?php admin_c_description_editor01('confirm_blocks', $confirmBlocks, 'simple'); ?>
    <?php admin_c_form_block01_close(); ?>

    <?php admin_c_form_block01_open(); ?>
    <div class="area is-title">
        <?php admin_m_large_title01('ボタン設定'); ?>
    </div>
    <div class="area is-field">
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('送信ボタンテキスト'), 'submit_button_text'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('submit_button_text', [
                'type' => 'text',
                'id' => 'submit_button_text',
                'name' => 'submit_button_text',
                'value' => (string) ($form['submit_button_text'] ?? '送信する'),
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
        <?php admin_m_input_set01_open(); ?>
        <div class="area is-title">
            <?php admin_m_bold_title01(e('戻るボタンテキスト'), 'back_button_text'); ?>
        </div>
        <div class="area is-field">
            <?php admin_m_input01_open(); ?>
            <?php admin_m_input01_input(admin_m_input01_attrs_with_bound_label('back_button_text', [
                'type' => 'text',
                'id' => 'back_button_text',
                'name' => 'back_button_text',
                'value' => (string) ($form['back_button_text'] ?? '入力に戻る'),
            ])); ?>
            <?php admin_m_input01_close(); ?>
        </div>
        <?php admin_m_input_set01_close(); ?>
    </div>
    <?php admin_c_form_block01_close(); ?>
</div>
