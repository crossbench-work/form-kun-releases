<?php
/**
 * フォームビルダー m-formBuilder01 のマークアップヘルパー。
 * 説明文ブロック追加用 `<template data-target="m-formBuilder01__descriptionTemplate">` は
 * `m-formBuilder01_description_template.php` に置く（admin.js が `content.firstElementChild` を clone）。
 * 項目追加用 `<template data-target="m-formBuilder01__fieldTemplate">` は
 * `m-formBuilder01_field_template.php` に置く。
 * スタイルは `c-descriptionEditor01/_c-descriptionEditor01.scss`（説明文エディタ側）と
 * `_m-formBuilder01__fieldTemplate.scss`（_admin-app.scss から @use）。
 */
if (!function_exists('admin_m_form_builder01_description_template')) {
    function admin_m_form_builder01_description_template(): void
    {
        require __DIR__ . '/m-formBuilder01_description_template.php';
    }
}

if (!function_exists('admin_m_form_builder01_field_template')) {
    function admin_m_form_builder01_field_template(): void
    {
        require __DIR__ . '/m-formBuilder01_field_template.php';
    }
}
