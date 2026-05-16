<?php
/**
 * 管理画面レイアウト用 UI ヘルパーの読み込み。
 *
 * Core\View はテンプレートをレイアウトより先に render するため、
 * ビュー内で admin_m_btn01_* 等を呼ぶには、layouts/admin.php より前に
 * 本ファイルを require する必要がある（View::renderToString が layout が admin または admin_minimal のときに担当）。
 */
require_once APP_COMPONENTS_DIR . '/c-formEditorItemHeader01/c-formEditorItemHeader01.php';
require_once APP_COMPONENTS_DIR . '/c-formEditorItemBody01/c-formEditorItemBody01.php';
require_once APP_COMPONENTS_DIR . '/m-formBuilder01/m-formBuilder01.php';
require_once APP_COMPONENTS_DIR . '/m-btn01/m-btn01.php';
require_once APP_COMPONENTS_DIR . '/m-deleteBtn01/m-deleteBtn01.php';
require_once APP_COMPONENTS_DIR . '/m-dragHandle01/m-dragHandle01.php';
require_once APP_COMPONENTS_DIR . '/m-boldTitle01/m-boldTitle01.php';
require_once APP_COMPONENTS_DIR . '/m-input01/m-input01.php';
require_once APP_COMPONENTS_DIR . '/m-inputSet01/m-inputSet01.php';
