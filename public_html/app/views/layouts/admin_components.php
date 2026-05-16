<?php
/**
 * 管理画面レイアウト用 UI ヘルパーの読み込み。
 *
 * Core\View はテンプレートをレイアウトより先に render するため、
 * ビュー内で admin_m_btn01_* 等を呼ぶには、layouts/admin.php より前に
 * 本ファイルを require する必要がある（View::renderToString が担当）。
 */
require_once APP_COMPONENTS_DIR . '/m-btn01/m-btn01.php';
require_once APP_COMPONENTS_DIR . '/m-boldTitle01/m-boldTitle01.php';
