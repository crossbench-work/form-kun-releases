<?php
/**
 * フォーム編集のフィールド型選択モーダル c-fieldTypeModal01 のマークアップヘルパー。
 * スタイルは同ディレクトリの _c-fieldTypeModal01.scss（_admin-app.scss から @use）。
 * `data-target`（m-formBuilder01__typeModal 等）は admin.js 互換のため変更しない。
 */
if (!function_exists('admin_c_field_type_modal01')) {
    function admin_c_field_type_modal01(): void
    {
        require __DIR__ . '/c-fieldTypeModal01_markup.php';
    }
}
