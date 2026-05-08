#!/usr/bin/env bash
# ローカル開発用: PHP 組み込みサーバー（public_html をドキュメントルート）
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
exec php -S 127.0.0.1:8080 -t public_html public_html/dev-router.php
