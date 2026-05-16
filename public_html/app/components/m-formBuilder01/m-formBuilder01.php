<?php
/**
 * フォームビルダー m-formBuilder01 のマークアップヘルパー。
 * 説明文ブロック追加用 `<template data-target="m-formBuilder01__descriptionTemplate">` は
 * `m-formBuilder01_description_template.php` に置く（admin.js が `content.firstElementChild` を clone）。
 * スタイルの一部は `_m-formBuilder01__descriptionTemplate.scss`（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_form_builder01_description_template')) {
    function admin_m_form_builder01_description_template(): void
    {
        require __DIR__ . '/m-formBuilder01_description_template.php';
    }
}
