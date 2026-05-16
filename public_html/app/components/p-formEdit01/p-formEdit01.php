<?php
/**
 * フォーム編集ページ p-formEdit01 のマークアップヘルパー。
 * 未保存時のフローティング操作バー（`.p-formEdit01__floatingActions`）は `p-formEdit01_floating_actions.php`。
 * スタイルは `_p-formEdit01__floatingActions.scss`（_admin-app.scss から @use）。
 * `data-target`（p-formEdit01__dirtyActions 等）は admin.js 互換のため変更しない。
 */
if (!function_exists('admin_p_form_edit01_floating_actions')) {
    /**
     * @param bool $isNew 新規作成時は保存ボタン文言が「作成する」になる
     */
    function admin_p_form_edit01_floating_actions(bool $isNew): void
    {
        require __DIR__ . '/p-formEdit01_floating_actions.php';
    }
}
