<?php
/**
 * PHP 組み込みサーバー / nginx（Local 等）共通のルーター入口。
 * cli-server のときは dev-router 内の return false がここまで伝播する必要がある。
 */
declare(strict_types=1);
return require __DIR__ . '/dev-router.php';
